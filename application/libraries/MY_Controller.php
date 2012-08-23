<?php

class Controller extends Controller_Core {

    public $db;   // Database
    public $session; // Session
    public $site;  // Site information
    public $mr = array();   // one data record
    public $mlist;  // list data records
    public $warning = '';

    public function __construct() {
        parent::__construct();
        $this->db = Database::instance();  // init property db use Database
        $this->session = Session::instance(); // init property session use Session   
        // get site information, config, language		
        $this->site = Site_Model::get();
        $config = Model::factory('configuration')->get_mod();
        foreach ($config as $conf) {
            $key = $conf['configuration_key'];
            $this->site['config'][$key] = $conf['configuration_value'];
        }

        $this->site['base_url'] = url::base();

        // init admin or client
        if (strpos($this->uri->segment(1), 'admin') === false || $this->uri->segment(1) == 'admin') { // if in url no contain 'admin' then init client
            $this->site['theme_url'] = url::base() . 'themes/client/' . $this->site['config']['CLIENT_THEME'] . '/';
            $this->init_client();
        } else { // else init admin
            $footer = Model::factory('footer')->get();
            $version = Version_Model::get_version();

            $this->site['theme_url'] = url::base() . 'themes/admin/';
            $this->site['site_footer'] = $footer['footer_content'];
            $this->site['version'] = $version['cur_version'];

            $this->init_admin();
        }
        //echo Kohana::debug($_SERVER);	
    }

    protected function get_MCH_layout() {
        $gpl = Gpl_Model::get();

        $page_id = URI::segment('pid');

        if (valid::digit($page_id) === FALSE)
            $page_id = FALSE;

        $ps = Page_special_Model::get_page_special(URI::segment(1, 'home'));
        if (count($ps) > 0)
            $page_id = $ps['page_id'];

        $porm = ORM::factory('page_orm')->find($page_id);

        if (empty($porm->page_id))
            $this->warning = 'wrong_pid';

        elseif ($porm->page_status === 0)
            $this->warning = 'block_page';

        elseif (isset($this->sess_cus['id'])) {
            if ($this->sess_cus['level'] > $porm->page_read_permission)
                $this->warning = 'restrict_access';
        }
        else {
            if ($porm->page_read_permission < 4)
                $this->warning = 'restrict_access';
        }

        $this->page_id = $page_id;

        // Init properties of Controller
        $this->page_model = new Page_Model();

        // Init properties of Template
        $layout = Page_layout_Model::get($page_id);
        if (count($layout) < 1)
            $layout = $gpl;

        $ph = Model::factory('header')->get_mod($page_id);
        if (empty($ph))
            $ph = Model::factory('header')->get_mod($gpl['page_id']);

        for ($i = 1; $i <= 15; $i++) {
            $arr_tmp = $list_tmp = array();
            $pos = $this->get_position($i);

            if ($i <= 5) {
                $order = ($layout[$pos . '_menu_level'] == 0) ? '' : 'page_order';
                $layout['list_' . $pos . '_menu'] = $this->page_model->get_page_lang($this->get_client_lang(), explode('|', $layout[$pos . '_menu']), '', '', '', $order);
            }

            /*if ($i == 2)
                continue;*/

            $arr_tmp = empty($layout[$pos . '_banner']) ? array() : explode('|', $layout[$pos . '_banner'], -1);

            if ($this->site['config']['BANNER_' . strtoupper($pos)] === 'random')
                shuffle($arr_tmp);

            foreach ($arr_tmp as $key => $value) {
                $ban = Model::factory('banner')->get_mod($value);

                if (!empty($ban)) {
                    $ban['banner_type'] = ORM::factory('file_type_orm', $ban['file_type_id'])->file_type_detail;
                    $list_tmp[$key] = $ban;
                }
            }
            $layout['list_' . $pos . '_banner'] = $list_tmp;
        }

        $layout = array_merge($layout, $ph, Model::factory('footer')->get());
        // CSS
        $layout['css'] = $this->site['theme_url'] . (uri::segment(1) ? uri::segment(1) : 'home') . '/' . (uri::segment(1) ? uri::segment(1) : 'home') . '.css';

        return $layout;
    }

    private function init_client() {
        $this->set_sess_history('client'); // Get history back from session if have
        if ($this->session->get('sess_client_lang')) {
            $lang_id = $this->session->get('sess_client_lang');
        } else {
            $lang_id = $this->site['config']['CLIENT_LANG'];
        }
        $lang_code = ORM::factory('languages')->find($lang_id)->languages_code;

        Kohana::config_set('locale.language', $lang_code); //$this->site['config']['ADMIN_LANG']);
        $this->site['lang_id'] = $lang_id;

        //load customer info if logined
        $this->sess_cus = Login_Model::get('customer');
    }

    private function init_admin() {
        $this->set_sess_history('admin'); // Get history back from session if have

        $this->sess_admin = Login_Model::get('admin');
        $this->sess_role = Login_Model::get('role');

        if ($this->sess_admin === FALSE) { // not yet login
            if ($this->uri->segment(1) != "admin_login")
                url::redirect('admin_login');
        }

        // Get search (keyword, curpage) from session if have  
        if ($this->session->get('sess_search')) {
            $this->search = $this->session->get('sess_search');
            $this->session->set_flash('sess_search', $this->search);
        }
        else
            $this->search = array('keyword' => '', 'sel_type' => '');

        //Load language		
        if ($this->session->get('sess_admin_lang')) {
            $lang_id = $this->session->get('sess_admin_lang');
            $lang_code = ORM::factory('languages')->find($lang_id)->languages_code;
        } else { //Language default
            $lang_id = $this->site['config']['ADMIN_LANG'];
            $lang_code = ORM::factory('languages')->find($lang_id)->languages_code;

            $this->session->set('sess_admin_lang', $lang_id);
        }

        Kohana::config_set('locale.language', $lang_code);
        $this->site['lang_id'] = $lang_id;

        // Save active last time
        // if($this->uri->segment(1) != "admin_login") Login_Model::save_active_last($this->sess_admin['id']);			
    }

    private function set_sess_history($type) {
        if ($this->session->get('sess_his_' . $type)) {
            $this->site['history'] = $this->session->get('sess_his_' . $type);
            if (empty($this->site['history']['current']))
                $this->site['history']['current'] = url::base();
            if ($this->_check_url_valid($this->site['history'])) {
                $this->site['history']['back'] = $this->site['history']['current'];
                $this->site['history']['current'] = url::current(true);
            }
            $this->session->set('sess_his_' . $type, $this->site['history']);
        } else {
            $this->site['history']['back'] = url::base();
            $this->site['history']['current'] = url::current(true);
            if ($this->_check_url_valid($this->site['history']))
                $this->site['history']['current'] = url::base();
            $this->session->set('sess_his_' . $type, $this->site['history']);
        }
        //print_r($this->site['history']);
    }

    private function _check_url_valid($history) {
        $arr_invalid = array(
            'save', 'delete', 'action', 'download', 'upload', 'check_login', 'log_out', 'order',
            'viewaccount', 'update_account', 'calendar',
            'wrong_pid', 'block_page', 'restrict_access',
            'captcha', 'general/pid/1', 'support', 'comment', 'compare_pass'
        );

        if ($history['current'] == url::current(true))
            return FALSE;

        foreach ($arr_invalid as $value) {
            if (strpos(url::current(true), $value) !== FALSE)
                return FALSE;
        }

        return TRUE;
    }

    protected function get_admin_lang() {
        return $this->session->get('sess_admin_lang');
    }

    protected function get_client_lang() {
        if ($this->session->get('sess_client_lang'))
            return $this->session->get('sess_client_lang');
        else
            return $this->site['lang_id'];
    }

    protected function get_position($position) {
        switch ($position) {
            case 1 : return 'top';
            case 2 : return 'center';
            case 3 : return 'left';
            case 4 : return 'right';
            case 5 : return 'bottom';
            case 6 : return 'center_top';
            case 7 : return 'center_bottom';
            case 8 : return 'left_outside';
            case 9 : return 'right_outside';
            case 10 : return 'top_T';
            case 11 : return 'top_B';
            case 12 : return 'left_T';
            case 13 : return 'left_B';
            case 14 : return 'right_T';
            case 15 : return 'right_B';
            case 'top' : return 1;
            case 'center' : return 2;
            case 'left' : return 3;
            case 'right' : return 4;
            case 'bottom' : return 5;
        }
    }

    //Search focus
    protected function format_focus_search($str_search, $str_format) {
        $str = mb_substr($str_format,strpos(strtolower($str_format),  strtolower($str_search)),strlen($str_search)); //echo $str; die();
        return preg_replace('#(?!<.*)(?<!\w)(' . $str . ')(?!\w|[^<>]*(?:</s(?:cript|tyle))?>)#is', "<span style='background-color:rgb(255, 255, 0)'>" .$str. "</span>", $str_format);
    }

    protected function get_thumbnail_size($path_image, $max_width = 300, $max_height = 150) {
        if (is_file($path_image) && file_exists($path_image)) {
            $image = new Image($path_image);

            if ($image->__get('width') > $image->__get('height')) {
                if ($image->__get('width') > $max_width && $max_width > 0)
                    return "width='$max_width'";
            }
            else {
                if ($image->__get('height') > $max_height && $max_height > 0)
                    return "height='$max_height'";
            }
        }
    }

    protected function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    protected function warning_msg($messages) {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/warning/index');

        switch ($messages) {
            case 'wrong_pid' :
                $msg = Kohana::lang('errormsg_lang.war_wrong_pid');
                break;

            case 'block_page' :
                $msg = Kohana::lang('errormsg_lang.war_page_blocked');
                break;

            case 'restrict_access' :
                $msg = Kohana::lang('errormsg_lang.war_restrict_access');
                break;

            case 'empty_search' :
                $msg = Kohana::lang('errormsg_lang.msg_no_result');
                break;

            default :
                url::redirect('home');
                die();
        }

        $this->template->content->msg = $msg;
    }

    protected function _download_bbs_file($type, $id) {
        $bbs_file = ORM::factory('bbs_file_orm')->find($id);

        if (!empty($bbs_file->bbs_file_id)) {
            $path_file = DOCROOT . "uploads/$type/" . $bbs_file->bbs_file_name;

            $bbs_file->bbs_file_download++;
            $bbs_file->save();

            download::force($path_file);
        }

        return FALSE;
    }

    protected function _delele_bbs_file($type, $bbs_fid = '') {
        $bbs_file = ORM::factory('bbs_file_orm')->find($bbs_fid);

        if (!empty($bbs_file->bbs_file_id)) {
            $msg = array('error' => '', 'success' => '');

            $path_file = MCH_Core::format_path(DOCROOT . "uploads/$type/" . $bbs_file->bbs_file_name);
            $path_file_thumb = MCH_Core::format_path(DOCROOT . "uploads/$type/thumb_" . $bbs_file->bbs_file_name);

            if (is_file($path_file) && file_exists($path_file)) {
                if (@unlink($path_file)) {
                    
                    if (is_file($path_file_thumb) && file_exists($path_file_thumb))
                        @unlink($path_file_thumb);
                }
                else
                    $msg['error'] = Kohana::lang('errormsg_lang.error_file_del') . '<br>';
            }
            else
                $msg['error'] = Kohana::lang('errormsg_lang.error_file_not_exist') . '<br>';

            $bbs_file->delete();

            if (empty($bbs_file->bbs_file_id))
                $msg['success'] = Kohana::lang('errormsg_lang.msg_file_del') . '<br>';
            else
                $msg['error'] .= Kohana::lang('errormsg_lang.error_del') . '<br>';

            if ($msg['error'] !== '')
                $this->session->set_flash('error_msg', $msg['error']);

            if ($msg['success'] !== '')
                $this->session->set_flash('success_msg', $msg['success']);

            return TRUE;
        }

        return FALSE;
    }

    protected function _order_bbs_file($bbs_id, $bbs_fid = '', $action = '') {
        $orm = ORM::factory('bbs_file_orm');
        $list_file = $orm->where('bbs_id', $bbs_id)->orderby('bbs_file_order')->find_all()->as_array();

        if (!empty($action) && !empty($list_file)) {
            foreach ($list_file as $i => $file) {
                if ($file->bbs_file_id == $bbs_fid) {
                    $cur_file = $file;
                    if ($action == 'down')
                        $change_file = $list_file[$i + 1];
                    else
                        $change_file = $list_file[$i - 1];
                }
            }

            $tmp = $cur_file->bbs_file_order;
            $cur_file->bbs_file_order = $change_file->bbs_file_order;
            $change_file->bbs_file_order = $tmp;

            $cur_file->save();
            $change_file->save();

            return TRUE;
        }
        return FALSE;
    }

    protected function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        $this->rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }

    //FUNCTION GET PERMISSION OF CONTROLLERS
    //@PARAM $option is permis : 2 is view | 3 is add | 4 is edit | 5 is delete
    //@PARAM $CONTROL IS CONTROLLER OF PAGE
    //@case : view | add | edit | delete
    public function permisController($option = '', $control = '') {
        //PERMISSION OF CONTROLLER
        $ct = $control != '' ? $control : $this->uri->segment(1);
        if (isset($this->sess_role[$ct])) {
            $this->roleControl = explode('|', $this->sess_role[$ct]);
        } else {
            if ($this->sess_admin['level'] == 1 || $this->sess_admin['level'] == 2) {
                $this->roleControl = array(2, 3, 4, 5);
            }
            else
                $this->roleControl = '';
        }

        if ($this->uri->segment(2) == 'create' && $option == 'save')
            $option = 'add';
        elseif ($option == 'save')
            $option = 'edit';

        switch ($option) {
            case 'view' : //IS VIEW
                return $this->returnPermis(2);
                break;
            case 'add' :
                return $this->returnPermis(3);
                break;
            case 'edit' :
                return $this->returnPermis(4);
                break;
            case 'delete' :
                return $this->returnPermis(5);
                break;
        }
    }

    //FUNCTION RETURN PERMISSION OF CONTROLLER
    //SUPPORT TO FUNCTION permisController
    //@PARAM #option is permis : 2 is view | 3 is add | 4 is edit | 5 is delete
    private function returnPermis($option) {

        if ($this->roleControl != '') {//CHECK EXIT PERRMISSION OF CONTROLELR
            for ($i = 0; $i < count($this->roleControl); $i++) {

                if ($this->roleControl[$i] == $option) { //CHECK CAN VIEW | ADD | EDIT | DELETE
                    return TRUE;
                    break;
                }
            }
            return FALSE; //IF NOT VIEW | ADD | EDIT | DELETE
        } else { //IF EMPTY IS NOT PERMISSION OF CONTROLLER
            return FALSE;
        }
    }

    //Format code
    public function print_array($arr) {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }

}
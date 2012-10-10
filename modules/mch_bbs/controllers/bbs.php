<?php

class Bbs_Controller extends Template_Controller {

    public $template;

    public function __construct() {
        parent::__construct();
        $this->template = new View('templates/' . $this->site['config']['TEMPLATE'] . '/client/index');
        $this->template->layout = $this->get_MCH_layout(); // init layout for template controller		

        /* --------------------------------------get session if exist ------------------------------------------ */
        if ($this->session->get('sess_bbs')) {
            $this->search = $this->session->get('sess_bbs');
            $this->session->keep_flash('sess_bbs');
        }
        else
            $this->search = array(
                'sel_type' => '', 'keyword' => ''
            );
        $this->rss_model = new Rss_Model();
        $this->_get_session_msg();
        /* --------------------------------------------------------------------------------------------------- */

        /* ---------------------------------- Init properties of Controller ---------------------------------- */
        $this->bbs_model = new Bbs_Model();
        //require Kohana::find_file('vendor/feed_xml','xml');
        $this->mr = array_merge($this->mr, $this->page_model->get_page_lang($this->get_client_lang(), $this->page_id));
    }

    private function _get_session_msg() {
        if ($this->session->get('error_msg'))
            $this->template->error_msg = $this->session->get('error_msg');
        if ($this->session->get('warning_msg'))
            $this->template->warning_msg = $this->session->get('warning_msg');
        if ($this->session->get('success_msg'))
            $this->template->success_msg = $this->session->get('success_msg');
        if ($this->session->get('info_msg'))
            $this->template->info_msg = $this->session->get('info_msg');
        if ($this->session->get('input_data')) {
            $indata = $this->session->get('input_data');

            if (isset($indata['txt_title']))
                $this->mr['bbs_title'] = $indata['txt_title'];
            if (isset($indata['txt_name']))
                $this->mr['bbs_author'] = $indata['txt_name'];
            if (isset($indata['txt_content']))
                $this->mr['bbs_content'] = $indata['txt_content'];
        }
    }
    
    public function check_exist_array($arr,$value){
        foreach($arr as $item){
            if($item['id'] == $value)
                return true;
        }
        return false;
    }
    
    public function __call($method, $arguments) {
        $mlist = $this->bbsInit();
        $bbs_id = $this->uri->segment('id');
        $akc_status = Configuration_Model::get_value('ENABLE_AKCOMP');
        $bbs = $this->bbs_model->get(TRUE, $this->page_id, '', $this->get_client_lang());
        if($akc_status == 1){
            $bbs = $this->bbs_model->get(TRUE, $this->page_id, $bbs_id, $this->get_client_lang());
            
            if($this->page_id==165){
                $json = $this->curl_download('http://akcomp.com/home/get_news_page'); 
                $mlist = json_decode($json,true); //$this->print_array($mlist);
                if(!empty($mlist)){ foreach($mlist as $id => $value){
                    if($bbs_id == $value['bbs_id']) $bbs = $value;
                }}
            } else if($this->page_id==166){
                $mlist = $this->get_wp();
                if(!empty($mlist)){ foreach($mlist as $id => $value){
                    if($bbs_id == $value['bbs_id']) $bbs = $value;
                }}
            }   
            if (isset($arguments[1]) && $bbs_id && empty($bbs)) {
                url::redirect('bbs/pid/' . $this->page_id);
                die();
            } elseif ($bbs_id)
                $this->mr['bbs_id'] = $bbs_id;
        } else if($akc_status == 2) {
            $newsUrl = Configuration_Model::get_value('RSS_NEWS_URL');
            $blogUrl = Configuration_Model::get_value('RSS_BLOG_URL');
            
            if(!empty($newsUrl)&&$this->page_id==165){
                $mlist = $this->rss_model->getRss($newsUrl,true);
                if(!empty($mlist)){ foreach($mlist as $id => $value){
                    if($bbs_id == $value['id']) $bbs = $value;
                }}
            } else if(!empty($blogUrl)&&$this->page_id==166){
                $mlist = $this->rss_model->getRss($blogUrl,true);
                if(!empty($mlist)){ 
                    foreach($mlist as $id => $value){
                    if($bbs_id == $value['id']) $bbs = $value;
                }}
            }  
            if($bbs_id && !$this->check_exist_array($mlist,'id',$bbs_id)){
                url::redirect('bbs/pid/' . $this->page_id);
                die();
            } elseif ($bbs_id)
                $this->mr['bbs_id'] = $bbs_id;
        } else {
            $this->bbs_model->search($this->search);
            $this->bbs_model->set_query('where', 'bbs_status', 1);
            $total_rows = count($this->bbs_model->get(TRUE, $this->page_id, '', $this->get_client_lang()));
        }
        
        $this->pagination = new Pagination(array(
            'base_url' => 'bbs/pid/' . $this->page_id . '/search',
            'uri_segment' => 'page',
            'total_items' => @$total_rows,
            'items_per_page' => $this->site['config']['CLIENT_NUM_LINE'],
            'style' => 'digg',
        ));
        
        if($akc_status == 0){
            $this->bbs_model->set_limit($this->pagination->items_per_page, $this->pagination->sql_offset);		
            $this->bbs_model->search($this->search);
            $this->bbs_model->set_query('where', 'bbs_status', 1);
            $mlist = $this->bbs_model->get(TRUE, $this->page_id, '', $this->get_client_lang());
        }
        
        
        if(empty($mlist)){
            $mlist = $this->bbsInit();
            $this->mr = array_merge($mlist, $this->mr);
        }
        if (!empty($this->warning)) {
            $this->warning_msg($this->warning);
        } elseif ($method === 'pid') {
            if (!isset($arguments[1])) {
                $this->search['keyword'] = '';
                $this->_show_list();
            } else {
                switch ($arguments[1]) {
                    case 'search' :
                        if (empty($_POST) && uri::segment('page') === FALSE) {
                            $this->warning_msg('wrong_pid');
                        } else {
                            $this->mr = array_merge($bbs, $this->mr);
                            $this->search();
                        }
                        break;

                    case 'view_with_password' :
                        $this->mr = array_merge($bbs, $this->mr);
                        $this->view_with_password();
                        break;

                    case 'create' : {
                            if (!empty($this->sess_cus) && $this->sess_cus['level'] > $this->mr['page_write_permission']) {
                                $this->warning_msg('restrict_access');
                            } elseif (empty($this->sess_cus) && $this->mr['page_write_permission'] < 5) {
                                $this->warning_msg('restrict_access');
                            }
                            else
                                $this->create();
                            break;
                        }

                    case 'edit' :
                    case 'delete' : {
                            $this->mr = array_merge($bbs, $this->mr);

                            if (!$bbs_id)
                                $this->warning_msg('wrong_pid');
                            elseif (!empty($this->sess_cus) && $this->sess_cus['level'] > $this->mr['page_write_permission']
                                    && $this->mr['bbs_author'] != $this->sess_cus['username']) {
                                $this->warning_msg('restrict_access');
                            } elseif (empty($this->sess_cus) && ($this->mr['page_write_permission']==5&&empty($_SESSION['write_everybody'])))
                                $this->warning_msg('restrict_access');
                            else
                                $this->{$arguments[1]}();
                            break;
                        }

                    case 'save' : $this->save();
                        break;

                    case 'delete_file' : {
                            $this->mr = array_merge($bbs, $this->mr);

                            if (!$bbs_id)
                                $this->warning_msg('wrong_pid');
                            elseif (!empty($this->sess_cus) && $this->sess_cus['level'] > $this->mr['page_write_permission']
                                    && $this->mr['bbs_author'] != $this->sess_cus['username']) {
                                $this->warning_msg('restrict_access');
                            } elseif (empty($this->sess_cus)&& $this->mr['page_write_permission']!=5)
                                $this->warning_msg('restrict_access');
                            else
                                $this->delete_file(isset($arguments[2]) ? $arguments[2] : '');
                            break;
                        }

                    case 'download' :
                        $this->download(isset($arguments[2]) ? $arguments[2] : '');
                        break;

                    default : $this->warning_msg('wrong_pid');
                }
            }
        }
        else {
            $this->warning_msg('wrong_pid');
        }
    }

    private function view_with_password() {
        $txt_pass = $this->input->post('txt_pass');
        if (empty($txt_pass))
            $this->session->set_flash('error_msg', Kohana::lang('bbs_validation.txt_pass.required'));
        else {
            $bbs = ORM::factory('bbs_orm')->find($this->mr['bbs_id']);

            if ($this->mr['bbs_password'] == md5($txt_pass))
                $this->session->set_flash('sess_bbs_pass', true);
            else
                $this->session->set_flash('error_msg', Kohana::lang('bbs_validation.error_pass'));
        }

        url::redirect($this->site['history']['back']);
        die();
    }

    public function compare_pass(){
        $input_pass = $_POST['input_pass'];
        $cur_pass = $_POST['cur_pass'];
        $page_id = $_POST['page_id'];
        $action = $_POST['method'];
        $id = $_POST['bbs_id'];
        if($input_pass && $cur_pass && $cur_pass == md5($input_pass) && !empty($page_id) && !empty($action) && !empty($id)){
            $this->session->set('write_everybody', '1');
            echo url::base().'bbs/pid/'.$page_id.'/'.$action.'/id/'.$id;
        }
        die();
    }
    
    private function curl_download($Url=''){ 
        // is cURL installed yet?
        if (!function_exists ('curl_init')){
            die ('Sorry cURL is not installed!');
        }
        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();
        // Now set some options (most are optional)
        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, $Url);
        // Set a referer
        //curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
        // User agent
        curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        // Download the given URL, and return output
        $output = curl_exec($ch);
        // Close the cURL resource, and free system resources
        curl_close($ch);
        return $output;
    }
    
    private function rsstotime($rss_time) {
        $day = substr($rss_time, 5, 2);
        $month = substr($rss_time, 8, 3);
        $month = date('m', strtotime("$month 1 2011"));
        $year = substr($rss_time, 12, 4);
        $hour = substr($rss_time, 17, 2);
        $min = substr($rss_time, 20, 2);
        $second = substr($rss_time, 23, 2);
        $timezone = substr($rss_time, 26);

        $timestamp = mktime($hour, $min, $second, $month, $day, $year);

        date_default_timezone_set('UTC');

        if(is_numeric($timezone)) {
            $hours_mod = $mins_mod = 0;
            $modifier = substr($timezone, 0, 1);
            $hours_mod = (int) substr($timezone, 1, 2);
            $mins_mod = (int) substr($timezone, 3, 2);
            $hour_label = $hours_mod>1 ? 'hours' : 'hour';
            $strtotimearg = $modifier.$hours_mod.' '.$hour_label;
            if($mins_mod) {
                $mins_label = $mins_mod>1 ? 'minutes' : 'minute';
                $strtotimearg .= ' '.$mins_mod.' '.$mins_label;
            }
            $timestamp = strtotime($strtotimearg, $timestamp);
        }

        return $timestamp;
    }
    
    public function get_wp(){
        
        $dom = new MyDOMDocument;			
        @$dom->load('http://aandkcomputers.wordpress.com/feed/');
        @$arr = $dom->toArray();
        if(isset($arr) && empty($arr))
        {
            $path = 'application/vendor/feed_xml/feed_rss.xml';
            $fi = fopen($path,'r+');
            $html_content = file_get_contents($path);
            $dom->load($path);
            $arr = $dom->toArray();
        }
        $mlist = array();
        if(isset($arr) && !empty($arr)){ //$this->print_array($arr);
        for($i=0;$i<10;$i++) {
            $a = array(
                'bbs_id' => $i,
                'bbs_author' => $arr['rss']['channel']['item'][$i]['dc:creator'],
                'bbs_title' => $arr['rss']['channel']['item'][$i]['title'],
                'bbs_content' => $arr['rss']['channel']['item'][$i]['content:encoded'],
                'bbs_date_modified' => $this->rsstotime($arr['rss']['channel']['item'][$i]['pubDate']),
                'bbs_link' => $arr['rss']['channel']['item'][$i]['link'],
                'bbs_level' => 0,
            );
            $mlist[] = $a;
        }
        }
        return $mlist;
    }
    
    public function bbsInit(){
        $arr = array(
            'bbs_id' => '',
            'bbs_password' => '',
            'bbs_count' => '',
            'bbs_download' => '',
            'bbs_author' => '',
            'bbs_date_created' => '',
            'bbs_date_modified' => '',
            'bbs_order' => '',
            'bbs_status' => '',
            'bbs_page_id' => '',
            'bbs_left' => '',
            'bbs_right' => '',
            'bbs_level' => '',
            'bbs_sort_order' => '',
            'bbs_content' => '',
            'bbs_title' => '',
            'bbs_file' => '',
            'bbs_date' => ''
        );
        return $arr;
    }
    
    public function _show_list() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/bbs/list');
        $mlist = $this->bbsInit();
        $akc_status = Configuration_Model::get_value('ENABLE_AKCOMP');
        $newsUrl = Configuration_Model::get_value('RSS_NEWS_URL');
        $blogUrl = Configuration_Model::get_value('RSS_BLOG_URL');
        
        if($akc_status == 1){
            if($this->page_id==165){
                $json = $this->curl_download('http://akcomp.com/home/get_news_page'); 
                $mlist = json_decode($json,true); //$this->print_array($mlist);
                $total_rows = count($mlist);
                $this->site['config']['CLIENT_NUM_LINE'] = $total_rows;
            } else if($this->page_id==166){
                $mlist = $this->get_wp();
                $total_rows = count($mlist);
                $this->site['config']['CLIENT_NUM_LINE'] = $total_rows;
            } else {
                $this->bbs_model->search($this->search);
                $this->bbs_model->set_query('where', 'bbs_status', 1);
                $total_rows = count($this->bbs_model->get(TRUE, $this->page_id, '', $this->get_client_lang()));
            }
            
            
        } else if($akc_status == 2 && (!empty($newsUrl)||!empty($blogUrl))){
            if(!empty($newsUrl) && $this->page_id==165){
                $mlist = $this->rss_model->getRss($newsUrl,true);

                $total_rows = count($mlist);
                $this->site['config']['CLIENT_NUM_LINE'] = $total_rows;
            } else if(!empty($blogUrl) && $this->page_id==166){

                $mlist = $this->rss_model->getRss($blogUrl,true);

                $total_rows = count($mlist);
                $this->site['config']['CLIENT_NUM_LINE'] = $total_rows;
            } 
        } else {
            $this->bbs_model->search($this->search);
            $this->bbs_model->set_query('where', 'bbs_status', 1);
            $total_rows = count($this->bbs_model->get(TRUE, $this->page_id, '', $this->get_client_lang()));
        }
        
        $this->pagination = new Pagination(array(
            'base_url' => 'bbs/pid/' . $this->page_id . '/search',
            'uri_segment' => 'page',
            'total_items' => @$total_rows,
            'items_per_page' => $this->site['config']['CLIENT_NUM_LINE'],
            'style' => 'digg',
        ));
        
        if($akc_status == 0){
            $this->bbs_model->set_limit($this->pagination->items_per_page, $this->pagination->sql_offset);		
            $this->bbs_model->search($this->search);
            $this->bbs_model->set_query('where', 'bbs_status', 1);
            $mlist = $this->bbs_model->get(TRUE, $this->page_id, '', $this->get_client_lang());
        }
        if(count($mlist) > 0)
        for ($i = 0; $i < count($mlist); $i++) {
            if($akc_status < 2){
                if (empty($mlist[$i]['bbs_date_created']))
                    $mlist[$i]['bbs_date'] = @$mlist[$i]['bbs_date_modified'];
                else
                    $mlist[$i]['bbs_date'] = @$mlist[$i]['bbs_date_created'];
                $mlist[$i]['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], $mlist[$i]['bbs_date']);
                $mlist[$i]['bbs_content'] = @$mlist[$i]['bbs_content'];
            } else {
                $mlist[$i]['bbs_date'] = @$mlist[$i]['pubDate'];
                $mlist[$i]['bbs_author'] = @$mlist[$i]['author'];
                $mlist[$i]['bbs_level'] = '0';
                $mlist[$i]['bbs_id'] = @$mlist[$i]['id'];;
                $mlist[$i]['bbs_title'] = @$mlist[$i]['title'];;

                $mlist[$i]['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], strtotime($mlist[$i]['pubDate']));
                $mlist[$i]['bbs_content'] = @$mlist[$i]['content'];
            }
            

            if (!empty($this->search['keyword'])) {
                switch ($this->search['sel_type']) {
                    case '1' :
                        $mlist[$i]['bbs_title'] = $this->format_focus_search($this->search['keyword'], $mlist[$i]['bbs_title']);
                        break;

                    case '2' : $mlist[$i]['bbs_author'] = $this->format_focus_search($this->search['keyword'], $mlist[$i]['bbs_author']);
                        break;

                    case '3' : $mlist[$i]['bbs_content'] = $this->format_focus_search($this->search['keyword'], $mlist[$i]['bbs_content']);
                        break;

                    default :
                        $mlist[$i]['bbs_title'] = $this->format_focus_search($this->search['keyword'], $mlist[$i]['bbs_title']);
                        $mlist[$i]['bbs_author'] = $this->format_focus_search($this->search['keyword'], $mlist[$i]['bbs_author']);
                        $mlist[$i]['bbs_content'] = $this->format_focus_search($this->search['keyword'], $mlist[$i]['bbs_content']);
                }
            }

            $list_file = ORM::factory('bbs_file_orm')->where('bbs_id', @$mlist[$i]['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();

            $mlist[$i]['bbs_file'] = empty($list_file[0]->bbs_file_name) ? array() : $list_file;

            if (isset($this->mr['bbs_id']) && $this->mr['bbs_id'] == $mlist[$i]['bbs_id']) {
                $this->mr = array_merge($this->mr, $mlist[$i]);
            }
        }
        
        $this->session->set_flash('sess_bbs', $this->search); // session important		

        if (!isset($this->mr['bbs_id']) && !empty($mlist)) {
            $this->mr = array_merge($this->mr, $mlist[0]);
        }

        if ($this->session->get('sess_bbs_pass'))
            $this->mr['bbs_password'] = '';
        $this->bbs_model->increase_count(@$this->mr['bbs_id']);
//        $mlist = $this->bbsInit();
//        $this->mr = array_merge($this->mr,$mlist);
//        var_dump($mlist);
//        var_dump($this->mr); die();
        $this->template->content->mlist = $mlist;
        $this->template->content->mr = $this->mr;
    }

    private function search() {
        $txt_keyword = $this->input->post('txt_keyword');
        $sel_type = $this->input->post('sel_type');

        if (!empty($_POST)) {
            $this->search['keyword'] = $txt_keyword;
            $this->search['sel_type'] = $sel_type;
        }
        
        $this->mr['keyword'] = $this->search['keyword'];

        if (!empty($this->search['sel_type'])) {
            $str_tmp = 'type' . $this->search['sel_type'] . '_selected';
            $this->mr[$str_tmp] = 'selected';
        }

        $this->_show_list();
    }

    private function edit() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/bbs/frm');

        $list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();
        
        $this->template->content->mr = $this->mr;
        $this->template->content->list_file = $list_file;
    }

    private function create() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/bbs/frm');

        if (isset($this->mr['bbs_id']))
            unset($this->mr['bbs_id']);

        $this->template->content->mr = $this->mr;
        $this->template->content->list_file = array();
    }

    private function _get_frm_valid($id='') {
        $form = array(
            'hd_id' => '',
            'txt_title' => '',
            'txt_name' => '',
            'txt_pass' => '', 
            'txt_content' => '',                       
        );

        for ($i = 1; $i <= 3; $i++)
            $form['attach_file' . $i] = '';

        $errors = $form;
        $file_type = '';
        foreach (ORM::factory('file_type_orm')->find_all() as $ft) {
            $file_type = $file_type . $ft->file_type_ext . ',';
        }

        $post = new Validation(array_merge($_POST, $_FILES));

        $post->pre_filter('trim', TRUE);
        $post->add_rules('txt_title', 'required');
        if(empty($this->sess_cus)){
            if(!$id){
                $post->add_rules('txt_name', 'required');
                $post->add_rules('txt_pass', 'length[6,20]', 'required');                
            } else {
                $post->add_rules('txt_pass', 'length[6,20]');
            }
        }
        $post->add_rules('txt_content', 'required');

        for ($i = 1; $i <= 3; $i++)
            $post->add_rules('attach_file' . $i, 'upload::type[' . $file_type . ']', 'upload::size[10M]');

        if ($post->validate()) {
            $form = arr::overwrite($form, $post->as_array());
            return $form;
        } else {
            $form = arr::overwrite($form, $post->as_array());  // Retrieve input data
            $this->session->set_flash('input_data', $form);  // Set input data in session			

            $errors = arr::overwrite($errors, $post->errors('bbs_validation'));
            $str_error = '';

            foreach ($errors as $id => $name)
                if ($name)
                    $str_error.='<br>' . $name;
            $this->session->set_flash('error_msg', $str_error);

            url::redirect($this->site['history']['current']);
        }
    }

    private function save() {
        $bbs_id = $this->input->post('hd_id');
        $msg = array('error' => '', 'success' => '');

        $frm_bbs = $this->_get_frm_valid($bbs_id);
        //var_dump($frm_bbs); die();
        $set = array(
            'bbs_page_id' => $this->mr['page_id'],
            'bbs_author' => empty($this->sess_cus['username']) ? $frm_bbs['txt_name'] : $this->sess_cus['username'],
            'bbs_status' => 1,
            'bbs_title' => $frm_bbs['txt_title'],
            'bbs_content' => $frm_bbs['txt_content'],
            'bbs_sort_order' => ''
        );
        if(!empty($frm_bbs['txt_pass']))
            $set['bbs_password'] = md5($frm_bbs['txt_pass']);
        
        if (empty($bbs_id) || isset($this->mr['bbs_id'])) { // create new bbs or reply
            $set['bbs_date_created'] = time();

            if (isset($this->mr['bbs_id']))
                $bbs_id = $this->bbs_model->insert($set, TRUE, $this->mr['bbs_id']);
            else
                $bbs_id = $this->bbs_model->insert($set, TRUE);

            if ($bbs_id === FALSE)
                $msg['error'] = Kohana::lang('errormsg_lang.error_data_add') . '<br>';
            else
                $msg['success'] = Kohana::lang('errormsg_lang.msg_data_add') . '<br>';
        }
        else { // edit bbs
            $set['bbs_id'] = $bbs_id;
            $set['bbs_date_modified'] = time();
            $result = $this->bbs_model->update($this->get_client_lang(), $set);

            if ($result)
                $msg['success'] = Kohana::lang('errormsg_lang.msg_data_save') . '<br>';
            else
                $msg['error'] = Kohana::lang('errormsg_lang.error_data_save') . '<br>';
        }

        for ($i = 1; $i <= 3; $i++) {
            if (isset($frm_bbs['attach_file' . $i]['error']) && $frm_bbs['attach_file' . $i]['error'] == 0) {
                $path_dir = DOCROOT . 'uploads/storage/';

                $path_file = upload::save('attach_file' . $i, NULL, $path_dir);
                $file_name = basename($path_file);

                // upload file in storage directory and save in database				
                $ext_file = substr($file_name, strrpos($file_name, '.') + 1);
                $type_file = ORM::factory('file_type_orm')->like('file_type_ext', $ext_file)->find();

                $bbs_file = ORM::factory('bbs_file_orm');

                $bbs_file->file_type_id = $type_file->file_type_id;
                $bbs_file->bbs_id = $bbs_id;
                $bbs_file->bbs_file_name = $file_name;
                $bbs_file->bbs_file_description = '';
                $bbs_file->bbs_file_date_created = time();
                $bbs_file->bbs_file_download = 0;

                $bbs_file->save();
                $bbs_file->bbs_file_order = $bbs_file->bbs_file_id;
                $bbs_file->save();
            } elseif (isset($frm_bbs['attach_file' . $i]['error']) && $frm_bbs['attach_file' . $i]['error'] != 4)
                $msg['error'] .= Kohana::lang('errormsg_lang.error_file_upload') . ' (' . Kohana::lang('client_lang.lbl_file') . " $i)<br>";
        }

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        if (isset($set['bbs_id'])) // edit bbs or reply
            url::redirect($this->site['history']['current']);
        else
            url::redirect($this->site['history']['back']);

        die();
    }

    private function delete() {
        $bbs = ORM::factory('bbs_mptt')->find($this->mr['bbs_id']);

        if (!empty($bbs->bbs_id) && $bbs->is_leaf()) {
            $bbs_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->find_all();

            $path_news = DOCROOT . 'uploads/storage/';
            for ($i = 0; $i < count($bbs_file); $i++) {
                if (!empty($bbs_file[$i]->bbs_file_id)) {
                    $path_nf = $path_news . $bbs_file[$i]->bbs_file_name;

                    if (is_file($path_nf) && file_exists($path_nf))
                        @unlink($path_nf);
                    $bbs_file[$i]->delete();
                }
            }

            if ($this->bbs_model->delete($this->mr['bbs_id'], TRUE))
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));
            else
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_del'));
        }
        else {
            if (empty($bbs->bbs_id))
                $this->session->set_flash('error_msg', Kohana::lang('bbs_lang.error_null_id'));
            if ($bbs->has_children())
                $this->session->set_flash('error_msg', Kohana::lang('bbs_lang.error_has_children'));
        }

        url::redirect($this->site['history']['current']);
        die();
    }

    private function _get_frm_com() {
        $form = array(
            'txt_name' => '',
            'txt_email' => '',
            'txt_pass' => '',
            'txt_content' => ''
        );

        $errors = $form;

        $post = new Validation($_POST);

        $post->add_rules('txt_name', 'required');
        $post->add_rules('txt_email', 'email');
        $post->add_rules('txt_pass', 'length[6,6]');
        $post->add_rules('txt_content', 'required');

        if ($post->validate()) {
            $form = arr::overwrite($form, $post->as_array());
            return $form;
        } else {
            $form = arr::overwrite($form, $post->as_array());  // Retrieve input data
            $this->session->set_flash('input_data', $form);  // Set input data in session

            $errors = arr::overwrite($errors, $post->errors('comment_validation'));
            $str_error = '';

            foreach ($errors as $id => $name)
                if ($name)
                    $str_error.=$name . '<br>';
            $this->session->set_flash('error_msg', $str_error);

            url::redirect($this->site['history']['back']);
            die();
        }
    }

    private function delete_file($bbs_fid = '') {
        if ($this->_delele_bbs_file('storage', $bbs_fid)) {
            url::redirect($this->site['history']['current']);
            die();
        }

        $this->warning_msg('wrong_pid');
    }

    private function download($id) {
        if (!$this->_download_bbs_file('storage', $id))
            $this->warning_msg('wrong_pid');
    }

}
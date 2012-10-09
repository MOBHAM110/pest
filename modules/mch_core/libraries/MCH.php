<?php

class MCH_Core {

    public function get_MCH_layout() {
        $gpl = Model::factory('gpl')->get();
        $page_id = URI::segment('pid');
        $layout = array(
            'page_id' => '',
            'warning' => ''
        );
        $sess_cus = Model::factory('login')->get('customer');

        // get site information, config, language		
        $this->site = Model::factory('site')->get();
        $config = Model::factory('configuration')->get_mod();
        foreach ($config as $conf) {
            $key = $conf['configuration_key'];
            $this->site['config'][$key] = $conf['configuration_value'];
        }

        if (valid::digit($page_id) === FALSE)
            $page_id = FALSE;

        $ps = Model::factory('page_special')->get_page_special(URI::segment(1, 'home'));
        if (count($ps) > 0)
            $page_id = $ps['page_id'];

        $porm = ORM::factory('page_orm')->find($page_id);

        if (empty($porm->page_id))
            $layout['warning'] = 'wrong_pid';

        elseif ($porm->page_status === 0)
            $layout['warning'] = 'block_page';

        elseif ($sess_cus !== FALSE) {
            if ($sess_cus['level'] > $porm->page_read_permission)
                $layout['warning'] = 'restrict_access';
        }
        else {
            if ($porm->page_read_permission < 4)
                $layout['warning'] = 'restrict_access';
        }

        $layout['page_id'] = $page_id;

        // Init properties of Template
        $pl = Model::factory('page_layout')->get($page_id);
        if (count($pl) < 1)
            $layout = array_merge($layout, $gpl);

        $ph = Model::factory('header')->get_mod($page_id);
        if (empty($ph))
            $ph = Model::factory('header')->get_mod($gpl['page_id']);

        for ($i = 1; $i <= 5; $i++) {
            $arr_tmp = $list_tmp = array();
            $pos = $this->get_position($i);

            $layout['list_' . $pos . '_menu'] = Model::factory('page')->get_page_lang($this->site['config']['CLIENT_LANG'], explode('|', $layout[$pos . '_menu']), '', '', '');

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
        $layout['css'] = (uri::segment(1) ? uri::segment(1) : 'home') . '/' . (uri::segment(1) ? uri::segment(1) : 'home') . '.css';

        return $layout;
    }

    public static function one_level_menu($list_menu, $separator = '&nbsp;|&nbsp;', $tab_html_begin = '', $tab_html_end = '') {
        $str = '';
        foreach ($list_menu as $i => $menu) {
            $str .= $tab_html_begin;

            if ($menu['page_type_name'] != 'menu') {
                $str .= '<a href="' . url::base() . $menu['page_type_name'];
                if ($menu['page_type_special'] == 0)
                    $str .= '/pid/' . $menu['page_id'];
                $str .= '">';
            }
            else {
                $str .= '<a href="' . (empty($menu['page_content']) ? '#">' 
                        : (strpos($menu['page_content'],'http')?$menu['page_content']:url::base().$menu['page_content']) . '" target="' 
                        . (!empty($menu['page_target'])?$menu['page_target']:Model::factory('configuration')->get_value('TARGET_MENU'))
                        . '">');
            }

            $str .= $menu['page_title'];
            $str .= '</a>';
            if ($i != (count($list_menu) - 1) && count($list_menu) > 1) {
                $str .= $separator;
            }
            $str .= $tab_html_end;
        } //end foreach

        return $str;
    }

    public static function multi_menu_mpt($node, $menu) {
        $str = '';
        $arr_order = arr::rotate($menu);
        $key = array_search($node->page_id, $arr_order['page_id']);
        //var_dump($menu); die();
        if ($key !== FALSE) {
            //Selected menu
            $page_id = URI::segment('pid');
            $class_menu = '';
            if (!empty($page_id))
                $class_menu = ($node->page_id == $page_id) ? 'id="selected"' : '';
            elseif (URI::segment(1) == 'home' && $menu[$key]['page_type_name'] == 'home')
                $class_menu = 'id="selected"';
            elseif (URI::segment(1) == 'contact' && $menu[$key]['page_type_name'] == 'contact')
                $class_menu = 'id="selected"';
            elseif (URI::segment(1) == 'register' && $menu[$key]['page_type_name'] == 'register')
                $class_menu = 'id="selected"';
            elseif (URI::segment(1) == 'login' && $menu[$key]['page_type_name'] == 'login')
                $class_menu = 'id="selected"';
            elseif (URI::segment(1) == 'rss' && $menu[$key]['page_type_name'] == 'rss')
                $class_menu = 'id="selected"';
            elseif (URI::segment(1) == '' && $menu[$key]['page_type_name'] == 'home')
                $class_menu = 'id="selected"';
            elseif (URI::segment(1) == '' && $menu[$key]['page_type_name'] == 'form')
                $class_menu = 'id="selected"';
            $str .= '<li>';
            if ($menu[$key]['page_type_name'] != 'menu') {
                $page_type = '';
                if ($menu[$key]['page_type_name'] != 'general')
                    $page_type = $menu[$key]['page_type_name'];
                else
                    $page_type = 'article';
                $str .= '<a href="' . url::base() . $page_type;
                if ($menu[$key]['page_type_special'] == 0)
                    $str .= '-' . $menu[$key]['page_id'] . '/' . MyFormat::title_url($menu[$key]['page_title']);
                $str .= '"' . $class_menu . '>';
            }
            else {
                $str .= '<a href="' . (empty($menu[$key]['page_content']) ? '#">' 
                        : (MyFormat::check_is_url($menu[$key]['page_content'])?$menu[$key]['page_content']:url::base().$menu[$key]['page_content']) . '" target="' 
                        . (!empty($menu[$key]['page_target'])?$menu[$key]['page_target']:Model::factory('configuration')->get_value('TARGET_MENU'))
                        . '">');
            }

            $str .= $menu[$key]['page_title'] . '</a>';

            if ($node->has_children()) {
                $submenu = false;
                $node_children = ORM::factory('page_mptt', $node->page_id)->__get('children');
                foreach ($node_children as $nc) {
                    $key = array_search($nc->page_id, $arr_order['page_id']);
                    if ($key !== FALSE) {
                        $submenu = true;
                        break;
                    }
                } // end foreach  
                if ($submenu) {
                    $str .= '<ul>';
                    foreach ($node_children as $nc) {
                        $str .= MCH_Core::multi_menu_mpt($nc, $menu);
                    } // end foreach 
                    $str .= '</ul>';
                } // end if have not submenu 
            }

            $str .= '</li>';
        } // end if key

        return $str;
    }

    private function get_position($position) {
        switch ($position) {
            case 1 : return 'top';
            case 2 : return 'center';
            case 3 : return 'left';
            case 4 : return 'right';
            case 5 : return 'bottom';
            case 'top' : return 1;
            case 'center' : return 2;
            case 'left' : return 3;
            case 'right' : return 4;
            case 'bottom' : return 5;
        }
    }

    private function go_sess_history($type) {
        if (Session::get('sess_his_' . $type)) {
            $history = Session::get('sess_his_' . $type);
            if ($this->_check_url_valid($history)) {
                $history['back'] = $history['current'];
                $history['current'] = url::current(true);
            }
            Session::set('sess_his_' . $type, $history);
        } else {
            $history = array('back' => url::current(true), 'current' => url::current(true));
            Session::set('sess_his_' . $type, $history);
        }

        return $history;
    }

    public static function format_path($path) {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        return str_replace('\\', DIRECTORY_SEPARATOR, $path);
    }

    private function _check_url_valid($history) {
        $arr_invalid = array(
            'save', 'delete', 'download', 'check_login', 'log_out', 'order',
            'viewaccount', 'update_account', 'calendar',
            'wrong_pid', 'block_page', 'restrict_access',
            'captcha'
        );

        if ($history['current'] == url::current(true))
            return FALSE;

        foreach ($arr_invalid as $value) {
            if (strpos(url::current(true), $value) !== FALSE)
                return FALSE;
        }

        return TRUE;
    }

    public function upload_bbs_file($type, $file, $bbs_id) {
        if (!empty($_FILES)) {
            $file_img_id = ORM::factory('file_type_orm')->where('file_type_detail', 'image')->find()->file_type_id;
            $path_dir = DOCROOT . 'uploads/' . $type . '/';

            $path_file = upload::save($file, NULL, $path_dir);

            // upload file in directory and save in database									
            $bbs_file = ORM::factory('bbs_file_orm');

            $bbs_file->file_type_id = $file_img_id;
            $bbs_file->bbs_id = $bbs_id;
            $bbs_file->bbs_file_name = basename($path_file);
            $bbs_file->bbs_file_description = '';
            $bbs_file->bbs_file_date_created = time();
            $bbs_file->bbs_file_download = 0;

            $bbs_file->save();
            $bbs_file->bbs_file_order = $bbs_file->bbs_file_id;
            $bbs_file->save();

            echo '1';
        }
    }

}

?>
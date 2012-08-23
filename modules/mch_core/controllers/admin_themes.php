<?php

class Admin_themes_Controller extends Template_Controller {

    public $template = 'admin/index';

    public function __construct() {
        parent::__construct();

        $this->themes_model = new Themes_Model();

        $this->_get_session_msg();
    }

    public function __call($method, $arguments) {
        $this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');

        $this->index();
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

            if (isset($indata['txt_name']))
                $this->mr['themes_name'] = $indata['txt_name'];
            if (isset($indata['sel_status']))
                $this->mr['themes_status'] = $indata['sel_status'];
        }
    }

    public function index() {
        $this->template->content = new View('admin_themes/list');

        $this->mlist = $this->themes_model->get_mod();

        //Pagination
        $this->pagination = new Pagination(array(
                    'base_url' => uri::segment(1) . '/search',
                    'uri_segment' => 'page',
                    'total_items' => count($this->mlist),
                    'items_per_page' => $this->site['config']['ADMIN_NUM_LINE'],
                    'style' => 'digg',
                ));
        $this->themes_model->limit_mod($this->pagination->items_per_page, $this->pagination->sql_offset);
        $this->mlist = $this->themes_model->get_mod();

        $this->mr['title'] = Kohana::lang('themes_templates_lang.tt_themes');
        if(!empty($this->mlist)){
            foreach($this->mlist as $id => $value){
                $path = 'themes/client/'.$value['themes_dir'];
                $arr_dir = scandir($path);
                foreach($arr_dir as $dir){
                    if(!is_dir($path.'/'.$dir)) {
                        $ext = substr($dir, strrpos($dir, '.') + 1);
                        if(in_array($ext, array("jpg","jpeg","png","gif")))
                            $this->mlist[$id]['themes_img'] = $path.'/'.$dir;
                    }
                }
            }
        }
        $this->template->content->mr = $this->mr;
        $this->template->content->mlist = $this->mlist;
    }

    public function create() {
        $this->template->content = new View('admin_themes/frm');

        $this->mr['title'] = Kohana::lang('themes_templates_lang.tt_themes') . ' :: ' . Kohana::lang('global_lang.lbl_create');

        $this->template->content->mr = $this->mr;
    }

    public function edit($id) {
        $this->template->content = new View('admin_themes/frm');

        $this->mr = array_merge($this->mr, $this->themes_model->get_mod($id));
        $this->mr['title'] = Kohana::lang('themes_templates_lang.tt_themes') . ' :: ' . Kohana::lang('global_lang.lbl_edit');

        $this->template->content->mr = $this->mr;
    }

    private function _get_frm_valid() {
        $file_type = ORM::factory('file_type_orm')->where('file_type_detail', 'compress')->find();
        $hd_id = $this->input->post('hd_id');

        $form = Array
            (
            'hd_id' => '',
            'txt_name' => '',
            'sel_status' => '',
            'attach_file' => ''
        );

        $errors = $form;

        $post = new Validation(array_merge($_POST, $_FILES));

        $post->pre_filter('trim', TRUE);
        $post->add_rules('txt_name', 'required', 'length[1,200]');
        //if (empty($hd_id))	$post->add_rules('attach_file', 'upload::required');	
        $post->add_rules('attach_file', 'upload::type[' . $file_type->file_type_ext . ']', 'upload::size[10M]');

        $form = arr::overwrite($form, $post->as_array());

        if ($post->validate())  // Data validation	
            return $form;
        else {
            $this->session->set_flash('input_data', $form);

            $errors = arr::overwrite($errors, $post->errors('themes_templates_validation'));
            $str_error = '';

            foreach ($errors as $id => $name)
                if ($name)
                    $str_error.=$name . '<br>';
            $this->session->set_flash('error_msg', $str_error);

            url::redirect($this->site['history']['current']);
            die();
        }
    }

    public function save() {
        $zip_file_id = ORM::factory('file_type_orm')->where('file_type_detail', 'compress')->find()->file_type_id;
        $msg = array('error' => '', 'success' => '');
        $frm = $this->_get_frm_valid();
        $path_themes = DOCROOT . 'themes/client/';
        $zip = new ZipArchive();

        $set = array(
            'themes_name' => $frm['txt_name'],
            'themes_status' => $frm['sel_status']
        );

        if (isset($frm['attach_file']['error']) && $frm['attach_file']['error'] == 0) {
            $path_file = Upload::save('attach_file', NULL, $path_themes);
            $dir_themes = scandir($path_themes);

            if ($zip->open($path_file) === TRUE) {
                $dir_name = substr($zip->getNameIndex(0), 0, -1);

                if (array_search($dir_name, $dir_themes) === FALSE) {
                    if (!$zip->extractTo($path_themes)) {
                        $msg['error'] = Kohana::lang('errormsg_lang.error_file_upload');
                    } else {
                        $set['themes_dir'] = $dir_name;
                        $msg['success'] = Kohana::lang('errormsg_lang.msg_file_up') . '<br>';

                        @unlink($path_file);
                    }
                }
                else
                    $msg['error'] = Kohana::lang('errormsg_lang.error_dir_exist');
            }
            else
                $msg['error'] = Kohana::lang('errormsg_lang.error_file_upload');
        }
        elseif (isset($frm['attach_file']['error']) && $frm['attach_file']['error'] != 4) {
            $msg['error'] = Kohana::lang('errormsg_lang.error_file_upload');
        }

        if ($msg['error'] !== '') {
            $this->session->set_flash('error_msg', $msg['error']);

            url::redirect($this->site['history']['current']);
            die();
        }

        if (empty($frm['hd_id'])) { // create new
            if ($this->themes_model->insert_mod($set))
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_add') . '<br>';
            else {
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_add_save'));
                url::redirect($this->site['history']['current']);
            }
        } else { // edit
            // delete old themes
            $themes = $this->themes_model->get_mod($frm['hd_id']);
            if (isset($set['themes_dir']) && $set['themes_dir'] != $themes['themes_dir'])
                $this->rrmdir($path_themes . $themes['themes_dir']);

            if ($this->themes_model->update_mod($set, array('themes_id' => $frm['hd_id'])))
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_save') . '<br>';
            else
                $msg['error'] .= Kohana::lang('errormsg_lang.error_data_save') . '<br>';
        }

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        if (isset($_POST['btn_save_add']))
            url::redirect(uri::segment(1) . '/create');
        elseif (!empty($frm['hd_id'])) // edit		
            url::redirect($this->site['history']['current']);
        else // create new
            url::redirect(uri::segment(1));

        die();
    }

    public function change_status($id) {
        $themes = ORM::factory('themes_orm', $id);

        if (empty($themes->themes_id))
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));
        else {
            $themes->themes_status = abs(1 - $themes->themes_status);
            $themes->save();

            if ($themes->saved)
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
            else
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_save'));
        }

        url::redirect($this->site['history']['back']);
        die();
    }

    public function delete($id) {
        $themes = ORM::factory('themes_orm', $id);

        if (empty($themes->themes_id)) {
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));
        } elseif ($themes->themes_dir == $this->site['config']['CLIENT_THEME']) {
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_theme_used'));
        } else {
            $this->rrmdir(DOCROOT . 'themes/client/' . $themes->themes_dir);
            $themes->delete();

            if (empty($themes->themes_id))
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));
            else
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_del'));
        }

        url::redirect($this->site['history']['current']);
        die();
    }

    public function change_default($id) {
        $themes = ORM::factory('themes_orm', $id);

        if (empty($themes->themes_id))
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));
        else {
            $result = Model::factory('configuration')->update_value('CLIENT_THEME', $themes->themes_dir);

            if ($result->count() > 0)
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
            else
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_save'));
        }

        url::redirect($this->site['history']['back']);
        die();
    }

}

?>
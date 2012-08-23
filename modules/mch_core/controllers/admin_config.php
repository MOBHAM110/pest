<?php

class Admin_config_Controller extends Template_Controller {

    public $template = 'admin/index';

    public function __construct() {
        parent::__construct();

        $this->header_model = new Header_Model();
        $this->footer_model = new Footer_Model();

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
    }

    public function index() {
        $this->template->content = new View('admin_config/index');

        $this->template->content->mr = $this->mr;
    }

    private function _get_frm_valid() {
        $form = array
            (
            'txt_name' => '',
            'txt_phone' => '',
            'txt_fax' => '',
            'txt_email' => '',
            'txt_address' => '',
            'txt_city' => '',
            'txt_zipcode' => '',
            'txt_contact' => '',
            'txt_state' => '',
            'txt_slogan' => '',
            'txt_title' => '',
            'txt_keyword' => '',
            'txt_description' => '',
            'txt_facebook' => '',
            'txt_twitter' => '',
            'txt_youtube' => '',
            'txt_linkedin' => '',
            'txt_short_date' => '',
            'txt_long_date' => '',
            'txt_admin_num_line' => '',
            'txt_client_num_line' => '',
            'sel_admin_lang' => '',
            'sel_client_lang' => '',
            'attach_logo' => '',
            'txt_width' => '',
            'txt_height' => '',
            'sel_df_sreg' => '',
            //'sel_client_theme' => '',
            'sel_left_column' => '',
            'sel_right_column' => '',
            'sel_login_frm' => '',
            'sel_support_frm' => '',
            'sel_google_calendar' => '',
            'sel_target_menu' => '',
			'sel_akcomp' => ''
        );

        for ($i = 1; $i <= 15; $i++) {
            if ($i == 2)
                continue; // except center position	
            $form['sel_ban_' . $this->get_position($i)] = '';
        }

        $errors = $form;

        if ($_POST) {
            $post = new Validation(array_merge($_POST, $_FILES));

            if (!empty($_FILES['attach_logo']['name'])) {
                $post->add_rules('attach_logo', 'upload::type[gif,jpg,png,jpeg]', 'upload::size[2M]');
            }
            $post->pre_filter('trim', TRUE);
            $post->add_rules('txt_name', 'required');
            $post->add_rules('txt_phone', 'required');
            //$post->add_rules('txt_fax','phone[7,10,11,14]');
            $post->add_rules('txt_email', 'required', 'email');
            $post->pre_filter('trim', TRUE);
            $post->add_rules('txt_admin_num_line', 'digit');
            $post->add_rules('txt_client_num_line', 'digit');
            $post->add_rules('txt_width', 'digit');
            $post->add_rules('txt_height', 'digit');

            if ($post->validate()) {
                $form = arr::overwrite($form, $post->as_array());
                return $form;
            } else {
                $errors = arr::overwrite($errors, $post->errors('site_validation'));
                $str_error = '';
                foreach ($errors as $id => $name)
                    if ($name)
                        $str_error.=$name . '<br>';
                $this->session->set_flash('error_msg', $str_error);
            }
        }

        url::redirect('admin_config');
        die();
    }

    public function save() {
        $frm = $this->_get_frm_valid();
        $msg = array('error' => '', 'success' => '');
        $pos = $set = array(
            'site_name' => $frm['txt_name'],
            'site_phone' => $frm['txt_phone'],
            'site_fax' => $frm['txt_fax'],
            'site_email' => $frm['txt_email'],
            'site_address' => $frm['txt_address'],
            'site_city' => $frm['txt_city'],
            'site_zipcode' => $frm['txt_zipcode'],
            'site_contact_name' => $frm['txt_contact'],
            'site_state' => $frm['txt_state'],
            'site_slogan' => $frm['txt_slogan'],
            'site_title' => $frm['txt_title'],
            'site_keyword' => $frm['txt_keyword'],
            'site_description' => $frm['txt_description'],
            'site_facebook' => $frm['txt_facebook'],
            'site_twitter' => $frm['txt_twitter'],
            'site_youtube' => $frm['txt_youtube'],
            'site_linkedin' => $frm['txt_linkedin'],
            'site_logo_width' => $frm['txt_width'],
            'site_logo_height' => $frm['txt_height']
        );

        $set_conf = array(
            'LOGIN_FRM' => $frm['sel_login_frm'],
            'SUPPORT_FRM' => $frm['sel_support_frm'],
            'GOOGLE_CALENDAR' => $frm['sel_google_calendar'],
            'ADMIN_NUM_LINE' => $frm['txt_admin_num_line'],
            'CLIENT_NUM_LINE' => $frm['txt_client_num_line'],
            'ADMIN_LANG' => $frm['sel_admin_lang'],
            'CLIENT_LANG' => $frm['sel_client_lang'],
            'FORMAT_SHORT_DATE' => $frm['txt_short_date'],
            'FORMAT_LONG_DATE' => $frm['txt_long_date'],
            'DEF_SREG' => $frm['sel_df_sreg'],
            'TARGET_MENU' => $frm['sel_target_menu'],
			'ENABLE_AKCOMP' => $frm['sel_akcomp']	
        );
        for ($i = 1; $i <= 15; $i++) {
            if ($i == 2)
                continue; // except center position
            $pos = $this->get_position($i);
            $key = 'BANNER_' . strtoupper($pos);
            $value = $frm['sel_ban_' . $pos];
            $set_conf[$key] = $value;
        }

        if (!empty($frm['attach_logo']['name'])) {
            if ($frm['attach_logo']['error'] > 0) {
                $msg['error'] = Kohana::lang('errormsg_lang.error_upload');
            } else {
                $path_site = DOCROOT . 'uploads/site/';

                $path_file = Upload::save('attach_logo', NULL, $path_site);

                $msg['success'] = Kohana::lang('errormsg_lang.msg_file_upload');
                $set['site_logo'] = basename($path_file);

                $img = new Image($path_file);
                if (empty($frm['txt_width']))
                    $set['site_logo_width'] = $img->__get('width');
                if (empty($frm['txt_height']))
                    $set['site_logo_height'] = $img->__get('height');
            }
        }

        $update_conf = FALSE;
        foreach ($set_conf as $key => $value) {
            $result = Model::factory('configuration')->update_value($key, $value);

            if ($result->count() > 0)
                $update_conf = TRUE;
        }

        if (Site_Model::update($set) || $update_conf)
            $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_update');
        else
            $this->session->set_flash('info_msg', Kohana::lang('errormsg_lang.war_data_update'));

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        url::redirect(uri::segment(1));
        die();
    }

    public function del_logo() {
        $msg = array('error' => '', 'success' => '');
        $path_file = DOCROOT . 'uploads/site/' . $this->site['site_logo'];

        if (is_file($path_file) && file_exists($path_file)) {
            if (@unlink($path_file))
                $msg['success'] = Kohana::lang('errormsg_lang.msg_file_del');
            else
                $msg['error'] = Kohana::lang('errormsg_lang.error_del_file');
        }
        else
            $msg['error'] = Kohana::lang('errormsg_lang.error_file_not_exist');

        if (Site_Model::update(array('site_logo' => '')))
            $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_update');
        else
            $msg['error'] .= Kohana::lang('errormsg_lang.error_data_update');

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        url::redirect('admin_config');
        die();
    }

}

?>
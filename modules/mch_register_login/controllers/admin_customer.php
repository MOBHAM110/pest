<?php

class Admin_customer_Controller extends Template_Controller {

    public $template = 'admin/index';

    public function __construct() {
        parent::__construct();

        $this->customer_model = new Customer_Model();
        $this->user_model = new User_Model();
        $this->_get_session_msg();
        $this->search = array('display' => '', 'keyword' => '');
    }

    public function __call($method, $arguments) {
        $this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');
    }

    public function _get_session_msg() {
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
        $this->session->delete('sess_search');
        $this->showlist();
    }

    public function search() {
        if ($this->session->get('sess_search')) {
            $this->search = $this->session->get('sess_search');
        }
        //Keyword
        $keyword = $this->input->post('txt_keyword');
        if ($keyword !== NULL)
            $this->search['keyword'] = $keyword;
        //Set
        $this->session->set_flash('sess_search', $this->search);
        $this->showlist();
    }

    public function display() {
        //Get
        if ($this->session->get('sess_search')) {
            $this->search = $this->session->get('sess_search');
        }
        //Display
        $display = $this->input->post('sel_display');
        if (isset($display)) {
            $this->search['display'] = $display;
        }
        //Set
        $this->session->set('sess_search', $this->search);
        $this->showlist();
    }

    private function showlist() {
        $this->template->content = new View('admin_customer/list');
        //Assign
        $this->template->content->set(array(
            'keyword' => $this->search['keyword'],
            'display' => $this->search['display']
        ));
        $this->customer_model->search($this->search);
        $total_items = count($this->customer_model->get());
        if (isset($this->search['display']) && $this->search['display']) {
            if ($this->search['display'] == 'all')
                $per_page = $total_items;
            else
                $per_page = $this->search['display'];
        } else
            $per_page = $this->site['config']['ADMIN_NUM_LINE'];
        //Pagination
        $this->pagination = new Pagination(array(
                    'base_url' => 'admin_customer/search/',
                    'uri_segment' => 'page',
                    'total_items' => $total_items,
                    'items_per_page' => $per_page,
                    'style' => 'digg',
                ));
        $this->customer_model->limit_mod($this->pagination->items_per_page, $this->pagination->sql_offset);

        $this->customer_model->search($this->search);
        $this->template->content->set(array(
            'mlist' => $this->customer_model->get()
        ));
    }

    public function create() {
        $this->template->content = new View('admin_customer/frm');
    }

    public function _check_user($array, $field) {
        if (Model::factory('user')->field_value_exist('user_name', $array[$field])) {
            $array->add_error($field, '_check_user');
        }
    }

    public function _check_email($array, $field) {
        if (Model::factory('user')->field_value_exist('user_email', $array[$field])) {
            $array->add_error($field, '_check_email');
        }
    }

    private function _get_frm_valid() {
        $hd_id = $this->input->post('hd_id');
        $txt_pass = $this->input->post('txt_pass');
        $form = $this->customer_model->get_frm();
        $errors = $form;
        if ($_POST) {
            $post = new Validation($_POST);

            $post->pre_filter('trim', TRUE);
            $post->add_rules('txt_username', 'required', 'length[3,50]');
            $post->add_rules('txt_email', 'required', 'email');

            if (empty($hd_id)) {
                $post->add_rules('txt_pass', 'required', 'length[6,50]');
                $post->add_callbacks('txt_username', array($this, '_check_user'));
                $post->add_callbacks('txt_email', array($this, '_check_email'));
            } elseif (!empty($txt_pass)) {
                $post->add_rules('txt_pass', 'length[6,50]');
            }
            if ($post->validate()) {
                $form = arr::overwrite($form, $post->as_array());
                return $form;
            } else {
                $form = arr::overwrite($form, $post->as_array());
                $errors = arr::overwrite($errors, $post->errors('register_validation'));
                $str_error = '';
                foreach ($errors as $id => $name)
                    if ($name)
                        $str_error.=$name . '<br>';
                $this->session->set_flash('error_msg', $str_error);

                if ($hd_id)
                    url::redirect('admin_customer/edit/' . $hd_id);
                else
                    url::redirect('admin_customer/create');
                die();
            }
        }
    }

    public function save() {
        if (empty($_POST))
            url::redirect('admin_customer');
        $frm = $this->_get_frm_valid();
        if (empty($frm['hd_id'])) {
            $user = ORM::factory('user_orm');
            $user->user_pass = md5($frm['txt_pass']);
            $user->user_reg_date = time();
        } else {
            $user = ORM::factory('user_orm', $frm['hd_id']);
            if (!empty($frm['txt_pass']))
                $user->user_pass = md5($frm['txt_pass']);
        }

        $user->user_name = $frm['txt_username'];
        $user->user_email = $frm['txt_email'];
        $user->user_level = $frm['chk_staff'] ? 3 : 4;
        $user->user_status = $frm['sel_status'];
        $user->save();

        if ($user->saved) {
            $set = array(
                'user_id' => $user->user_id,
                'customers_firstname' => $frm['txt_first_name'],
                'customers_lastname' => $frm['txt_last_name'],
                'customers_address' => $frm['txt_address'],
                'customers_city' => $frm['txt_city'],
                'customers_state' => $frm['txt_state'],
                'customers_zipcode' => $frm['txt_zipcode'],
                'customers_phone' => $frm['txt_phone'],
                'customers_fax' => $frm['txt_fax']
            );

            if (empty($frm['hd_id'])) {
                $result = $this->customer_model->insert_mod($set);
            } else {
                $result = $this->customer_model->update_mod($set, array('user_id' => $frm['hd_id']));
            }

            if ($result || $user->saved)
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
            else
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_save'));
        }
        else
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_add'));

        if (!empty($_POST['hd_save_add']))
            url::redirect('admin_customer/create');
        elseif (empty($frm['hd_id']))
            url::redirect('admin_customer');
        else
            url::redirect('admin_customer/edit/' . $frm['hd_id']);
        die();
    }

    public function edit($id) {
        $this->template->content = new View('admin_customer/frm');
        $this->template->content->mr = $this->customer_model->get($id);
    }

    public function delete($id) {
        if (!$this->permisController('delete')) {
            $this->session->set_flash('error_msg', Kohana::lang('global_lang.lbl_authorized'));
            url::redirect(uri::segment(1));
            die();
        } else {
            $result_cus = $this->customer_model->delete($id);
            $result_user = $this->user_model->delete($id);
            if (is_array($id)) {
                if ($result_cus && $result_user)
                    $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));
                else
                    $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_del'));
                url::redirect(uri::segment(1));
                die();
            } else {
                $json['status'] = $result_cus && $result_user ? 1 : 0;
                $json['mgs'] = $result_cus && $result_user ? '' : Kohana::lang('errormsg_lang.error_data_del');
                $json['customer'] = array('id' => $id);
                echo json_encode($json);
                die();
            }
        }
    }

    public function setstatus($id, $status) {
        if (!$this->permisController('edit')) {
            $this->session->set_flash('error_msg', Kohana::lang('global_lang.authorized'));
            url::redirect(uri::segment(1));
            die();
        } else {
            if ($this->user_model->set_status($id, $status))
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_status_change'));
            else
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_save'));
            url::redirect(uri::segment(1));
            die();
        }
    }

    public function action() {
        if ($this->input->post('chk_id')) {
            $select_action = $this->input->post('sel_action');
            $chk_id = $this->input->post('chk_id');

            if ($select_action == 'delete')
                $this->delete($chk_id);
            else
                $this->setstatus($chk_id, $select_action == 'active' ? 1 : 0);
        }
        url::redirect('admin_customer');
        die();
    }

    public function change_level($id) {
        $user = ORM::factory('user_orm', $id);
        if (!empty($user->user_id)) {
            $user->user_level = abs($user->user_level - 7);
            $user->save();

            if ($user->saved)
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
            else
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_save'));
        }
        else
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));

        url::redirect($this->site['history']['back']);
        die();
    }

}

?>
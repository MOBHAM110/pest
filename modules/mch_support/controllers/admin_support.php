<?php

class Admin_support_Controller extends Template_Controller {

    var $mr;
    var $mlist;
    public $template = 'admin/index';

    public function __construct() {
        parent::__construct();

        $this->search = array('display' => '', 'keyword' => '', 'page' => 0);
        $this->_get_session_msg();
    }

    public function __call($method, $arguments) {
        // Disable auto-rendering
        $this->auto_render = FALSE;
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
        if ($this->session->get('frm'))
            $this->template->content->mr = $this->session->get('frm');
    }

    public function index() {
        $this->showlist();
    }

    private function showlist() {
        $this->template->content = new View('admin_support/list');
        //Assign
        $this->template->content->set(array(
            'keyword' => $this->search['keyword'],
            'display' => $this->search['display'],
        ));
        $model = new Support_Model();
        $this->where_sql();
        $mlist = $model->get();
        $total_items = count($mlist);
        if (isset($this->search['display']) && $this->search['display']) {
            if ($this->search['display'] == 'all')
                $per_page = $total_items;
            else
                $per_page = $this->search['display'];
        } else
            $per_page = $this->site['config']['ADMIN_NUM_LINE'];
        //Pagination
        $this->pagination = new Pagination(array(
                    'base_url' => 'admin_support/search/',
                    'uri_segment' => 'page',
                    'total_items' => $total_items,
                    'items_per_page' => $per_page,
                    'style' => 'digg',
                ));

        $this->db->limit($this->pagination->items_per_page, $this->pagination->sql_offset);

        $this->where_sql();
        $mlist = $model->get();
        
        $this->template->content->set(array(
            'mlist' => $mlist
        ));
    }

    public function where_sql() {
        if ($this->search['keyword'])
            $this->db->like('support_name', $this->search['keyword']);
    }

    public function search() {
        if ($this->session->get('sess_search_support')) {
            $this->search = $this->session->get('sess_search_support');
        }
        //Keyword
        $keyword = $this->input->post('txt_keyword');
        if (isset($keyword)) {
            $this->search['keyword'] = $keyword;
        }
        $this->session->set('sess_search_support', $this->search);
        $this->showlist();
    }

    public function display() {
        //Get
        if ($this->session->get('sess_search_support')) {
            $this->search = $this->session->get('sess_search_support');
        }
        //Sort
        $display = $this->input->post('sel_display');
        if (isset($display)) {
            $this->search['display'] = $display;
        }
        //Set
        $this->session->set('sess_search_support', $this->search);
        $this->showlist();
    }

    public function create() {
        $this->template->content = new View('admin_support/frm');
    }

    private function _set_form($form) {
        $record['support_name'] = $form['txt_name'];
        $record['support_nick'] = $form['txt_nick'];
        $record['support_type'] = $form['sel_type'];
        $record['support_sort_order'] = $form['txt_sort_order'];
        $record['support_status'] = $form['sel_status'];
        return $record;
    }

    private function _get_record() {
        $form = array
            (
            'txt_name' => '',
            'txt_nick' => '',
            'sel_type' => '',
            'txt_sort_order' => '',
            'sel_status' => '',
        );

        $errors = $form;

        if ($_POST) {
            $post = new Validation($_POST);

            $post->pre_filter('trim', TRUE);
            $post->add_rules('txt_name', 'required');

            $form = arr::overwrite($form, $post->as_array());
            $form = $this->_set_form($form);
            if ($post->validate()) {
                return $form;
            } else {
                $this->session->set_flash('frm', $form);
                $errors = arr::overwrite($errors, $post->errors('support_validation'));
                $str_error = '';
                foreach ($errors as $id => $name)
                    if ($name)
                        $str_error .= $name . '<br>';
                $this->session->set_flash('error_msg', $str_error);

                $hd_id = $this->input->post('hd_id');
                if ($hd_id)
                    url::redirect('admin_support/edit/' . $hd_id);
                else
                    url::redirect('admin_support/create');
            }
        }
    }

    public function save() {
        $hd_id = $this->input->post('hd_id');
        $record = $this->_get_record();

        if ($record) {
            if (!$hd_id) {
                $query = $this->db->insert('support', $record);
                $hd_id = $query->insert_id();
            } else {
                $query = $this->db->update('support', $record, array('support_id' => $hd_id));
            }
        }

        if (!$this->input->post('hd_id'))
            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_add'));
        else
            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
        
        if (!empty($_POST['hd_save_add']) && $hd_id)
            url::redirect('admin_support/create');
        elseif ($hd_id)
            url::redirect('admin_support/edit/' . $hd_id);
        else
            url::redirect('admin_support');
        die();
    }

    public function action() {
        $arr_id = $this->input->post('chk_id');

        if (is_array($arr_id)) {
            $sel_action = $this->input->post('sel_action');

            if ($sel_action == 'delete') {
                $id = ORM::factory('Support_orm')->delete_all($arr_id);
                if ($id)
                    $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));
            } elseif ($sel_action == 'block') {
                foreach ($arr_id as $arr => $id) {
                    $result = ORM::factory('Support_orm', $id);
                    $result->support_status = 0;
                    $id = $result->save();
                }
                if ($id)
                    $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
            } elseif ($sel_action == 'active') {
                foreach ($arr_id as $arr => $id) {
                    $result = ORM::factory('Support_orm', $id);
                    $result->support_status = 1;
                    $id = $result->save();
                }
                if ($id)
                    $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
            }
        } else {
            $this->session->set_flash('warning_msg', Kohana::lang('errormsg_lang.msg_data_error'));
        }

        url::redirect('admin_support/search');
        die();
    }

    public function edit($id) {
        $this->template->content = new View('admin_support/frm');

        $this->template->content->mr = ORM::factory('Support_orm')->find($id)->as_array();
    }

    public function delete($id='') {
        if (!$this->permisController('delete')) {
            $this->session->set_flash('error_msg', Kohana::lang('global_lang.lbl_authorized'));
            url::redirect(uri::segment(1));
            die();
        } else {
            $del = $this->db->delete('support', array('support_id' => $id));
            $json['mgs'] = $del ? '' : Kohana::lang('errormsg_lang.error_data_del');
        }
        $json['status'] = $del ? 1 : 0;
        $json['bbs'] = array('id' => $del);
        echo json_encode($json);
        die();
    }

    public function setstatus($id, $status) {
        $status = $this->db->update('support', array('support_status' => $status), array('support_id' => $id));
        if (count($status) > 0) {
            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_status_change'));
        }
        url::redirect('admin_support');
        die();
    }

}
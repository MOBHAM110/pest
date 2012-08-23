<?php

class Admin_bbs_Controller extends Template_Controller {

    public $template = 'admin/index';

    public function __construct() {
        parent::__construct();

        $this->bbs_model = new Bbs_Model();

        $page_id = $this->uri->segment('pid');
        $bbs_id = $this->uri->segment('id');

        if ($bbs_id) {
            $page_id = ORM::factory('bbs_orm', $bbs_id)->bbs_page_id;
            $this->mr['bbs_id'] = $bbs_id;
        }

        $page_model = new Page_Model();
        $this->mr = array_merge($this->mr, $page_model->get_page_lang($this->get_admin_lang(), $page_id, 'page.page_id', 'pt.page_type_name', 'pd.page_title'));
        $this->search = array('display' => '', 'keyword' => '', 'sel_type' => '');
        $this->_get_session_msg();
    }

    public function __call($method, $agruments) {
        url::redirect('admin_page');
        die();
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
            if (isset($indata['txt_content']))
                $this->mr['bbs_content'] = $indata['txt_content'];
        }
    }

    public function search() {
        //Get
        if ($this->session->get('sess_search_bbs')) {
            $this->search = $this->session->get('sess_search_bbs');
        }
        //Keyword
        if (isset($_POST['txt_keyword']))
            $this->search['keyword'] = $this->input->post('txt_keyword');
        if (!empty($this->search['keyword'])) {
            $this->search['sel_type'] = 1; // search for title
            $this->bbs_model->search($this->search);
        }
        //Sort
        $display = $this->input->post('sel_display');
        if (isset($display)) {
            $this->search['display'] = $display;
        }
        $this->session->set('sess_search_bbs', $this->search);
        $this->showlist();
    }

    private function showlist()
    {
        $this->template->content = new View('admin_bbs/list'); 
        //Assign
        $this->template->content->set(array(
            'keyword' => $this->search['keyword'],
            'display' => $this->search['display'],
        ));
        $mlist = $this->bbs_model->get(TRUE, $this->mr['page_id'], '', $this->get_admin_lang());
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
                    'base_url' => 'admin_bbs/search/pid/' . $this->mr['page_id'],
                    'uri_segment' => 'page',
                    'total_items' => $total_items,
                    'items_per_page' => $per_page,
                    'style' => 'digg',
                ));
        $this->bbs_model->set_limit($this->pagination->items_per_page, $this->pagination->sql_offset);
        if (!empty($this->search['keyword'])) {
            $this->search['sel_type'] = 1; // search for title
            $this->bbs_model->search($this->search);
        }
        $mlist = $this->bbs_model->get(TRUE, $this->mr['page_id'], '', $this->get_admin_lang());

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang($this->mr['page_type_name'] . '_lang.tt_page'),
            'mlist' => $mlist,
            'mr' => $this->mr
        ));
    }
    
    public function create() {
        $this->template->content = new View('admin_bbs/frm');

        $reply = '&nbsp;';
        if (isset($this->mr['bbs_id'])) {
            unset($this->mr['bbs_id']);
            $reply .= Kohana::lang('bbs_lang.lbl_reply');
        }

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang('bbs_lang.tt_page') . ' -> ' . Kohana::lang('global_lang.lbl_create') . $reply,
            'mr' => $this->mr,
            'list_file' => array()
        ));
    }

    public function edit() {
        $this->template->content = new View('admin_' . $this->mr['page_type_name'] . '/frm');

        $mr = $this->bbs_model->get(TRUE, '', $this->mr['bbs_id'], $this->get_admin_lang());
        if (empty($mr)) {
            url::redirect('admin_blog/search/pid/' . $this->mr['page_id']);
            die();
        }
        $this->mr = array_merge($mr, $this->mr);

        $list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang('bbs_lang.tt_page') . ' -> ' . Kohana::lang('global_lang.lbl_edit'),
            'mr' => $this->mr,
            'list_file' => $list_file
        ));
    }

    private function _get_frm_valid() {
        $form = array(
            'hd_pid' => '',
            'txt_title' => '',
            'txt_content' => '',
            'txt_sort_order' => '',
            'sel_status' => '',
            'txt_pass' => ''
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
        $post->add_rules('txt_title', 'required', 'length[1,200]');
        $post->add_rules('txt_content', 'required');
        $post->add_rules('txt_pass', 'length[6,20]');

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
                    $str_error .= $name . '<br>';
            $this->session->set_flash('error_msg', $str_error);

            url::redirect($this->site['history']['current']);
        }
    }

    public function save() {
        $bbs_id = $this->input->post('hd_id');
        $msg = array('error' => '', 'success' => '');

        $frm_bbs = $this->_get_frm_valid();

        $set = array(
            'bbs_page_id' => $this->mr['page_id'],
            'bbs_author' => $this->sess_admin['username'],
            'bbs_sort_order' => $frm_bbs['txt_sort_order'],
            'bbs_status' => $frm_bbs['sel_status'],
            'bbs_title' => $frm_bbs['txt_title'],
            'bbs_content' => $frm_bbs['txt_content'],
            'bbs_password' => empty($frm_bbs['txt_pass']) ? '' : md5($frm_bbs['txt_pass'])
        );

        if (empty($bbs_id) || uri::segment('id')) { // create new bbs
            $set['bbs_date_created'] = time();

            if (uri::segment('id'))
                $bbs_id = $this->bbs_model->insert($set, TRUE, uri::segment('id'));
            else
                $bbs_id = $this->bbs_model->insert($set, TRUE);

            $msg['success'] = Kohana::lang('errormsg_lang.msg_data_add') . '<br>';
        }
        else { // edit bbs
            $set['bbs_id'] = $bbs_id;
            $set['bbs_date_modified'] = time();
            $this->bbs_model->update($this->get_admin_lang(), $set);

            $msg['success'] = Kohana::lang('errormsg_lang.msg_data_save') . '<br>';
        }

        for ($i = 1; $i <= 3; $i++) {
            if (isset($frm_bbs['attach_file' . $i]['error']) && $frm_bbs['attach_file' . $i]['error'] == 0) {
                $path_dir = DOCROOT . 'uploads/storage/';

                $path_file = upload::save('attach_file' . $i, NULL, $path_dir);
                $file_name = basename($path_file);

                // upload file in storage directory and save in database				
                $ext_file = substr($file_name, strrpos($file_name, '.') + 1);
                $type_file = ORM::factory('file_type_orm')->like('file_type_ext', $ext_file)->find();

                // upload file in storage directory and save in database								
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

        if(!empty($_POST['hd_save_add']))
            url::redirect('admin_bbs/create/pid/' . $this->mr['page_id']);
        elseif ($this->input->post('hd_id'))
            url::redirect($this->site['history']['current']);
        elseif (!$this->input->post('hd_id') || uri::segment('id'))
            url::redirect($this->site['history']['back']);

        die();
    }

    public function setstatus() {
        if (isset($this->mr['bbs_id']) && $this->mr['bbs_id'] !== '') {
            $bbs_rc = ORM::factory('bbs_mptt', $this->mr['bbs_id']);
            $bbs_rc->bbs_status = abs($bbs_rc->bbs_status - 1);
            $bbs_rc->save();

            $bbs_descen = ORM::factory('bbs_mptt', $this->mr['bbs_id'])->__get('descendants');

            foreach ($bbs_descen as $bbs) {
                $bbs->bbs_status = $bbs_rc->bbs_status;
                $bbs->save();
            }

            if ($bbs_rc->saved)
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
            else
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_save'));
        }
        else
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_access_denied'));

        url::redirect($this->site['history']['back']);
        die();
    }

    public function delete($id='') {
        if (!$this->permisController('delete')) {
            $this->session->set_flash('error_msg', Kohana::lang('global_lang.lbl_authorized'));
            url::redirect('admin_news/search/pid/' . $this->mr['page_id']);
            die();
        } else {
            $del = '';
            if (!empty($id)) {
                $bbs = ORM::factory('bbs_mptt')->find($id);
                if (!empty($bbs->bbs_id) && $bbs->is_leaf()) {
                    $bbs_file = ORM::factory('bbs_file_orm')->where('bbs_id', $id)->find_all();

                    $path_news = DOCROOT . 'uploads/storage/';
                    for ($i = 0; $i < count($bbs_file); $i++) {
                        if (!empty($bbs_file[$i]->bbs_file_id)) {
                            $path_nf = $path_news . $bbs_file[$i]->bbs_file_name;

                            if (is_file($path_nf) && file_exists($path_nf))
                                unlink($path_nf);
                            $bbs_file[$i]->delete();
                        }
                    }

                    $del = $this->bbs_model->delete($id, TRUE);
                    $json['mgs'] = $del ? '' : Kohana::lang('errormsg_lang.error_data_del');
                } else {
                    $del = 0;
                    if (empty($bbs->bbs_id))
                        $json['mgs'] = Kohana::lang('bbs_lang.error_null_id');
                    if ($bbs->has_children())
                        $json['mgs'] = Kohana::lang('bbs_lang.error_has_children');
                }
            } else {
                $del = 0;
                $json['mgs'] = Kohana::lang('errormsg_lang.error_parameter');
            }

            $json['status'] = $del ? 1 : 0;
            $json['bbs'] = array('id' => $del);
            echo json_encode($json);
            die();
        }
    }

    public function action() {
        $action = $this->input->post('sel_action');
        $chk = $this->input->post('chk_id');
        
        if($chk){
        if ($action == 'delete') {
            foreach ($chk as $id) {
                $bbs = ORM::factory('bbs_mptt')->find($id);
                $bbs_file = ORM::factory('bbs_file_orm')->where('bbs_id', $id)->find_all();

                $path_news = DOCROOT . 'uploads/storage/';
                for ($i = 0; $i < count($bbs_file); $i++) {
                    if (!empty($bbs_file[$i]->bbs_file_id)) {
                        $path_nf = $path_news . $bbs_file[$i]->bbs_file_name;

                        if (is_file($path_nf) && file_exists($path_nf))
                            unlink($path_nf);
                        $bbs_file[$i]->delete();
                    }
                }
                $this->bbs_model->delete($id, FALSE);
            }
        } else {
            foreach ($chk as $id) {
                $bbs_rc = ORM::factory('bbs_mptt', $id);
                $bbs_rc->bbs_status = $action;
                $bbs_rc->save();

                $bbs_descen = ORM::factory('bbs_mptt', $id)->__get('descendants');
                foreach ($bbs_descen as $bbs) {
                    $bbs->bbs_status = $action;
                    $bbs->save();
                }
            }
        }
        $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
        }
        
        url::redirect($this->site['history']['current']);
        die();
    }

    public function del_storage_file($bbs_fid = '') {
        if (!$this->_delele_bbs_file('storage', $bbs_fid))
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));

        url::redirect($this->site['history']['back']);
        die();
    }

}
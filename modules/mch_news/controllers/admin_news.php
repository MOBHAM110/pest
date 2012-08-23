<?php

class Admin_news_Controller extends Template_Controller {

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
            $this->mr['news_title'] = $indata['txt_title'];
            $this->mr['news_content'] = $indata['txt_content'];
        }
    }

    public function search() {
        //Get
        if ($this->session->get('sess_search_news')) {
            $this->search = $this->session->get('sess_search_news');
        }
        //Keyword
        if (isset($_POST['txt_keyword']))
            $this->search['keyword'] = $this->input->post('txt_keyword');

        if (!empty($this->search['keyword'])) {
            $this->search['sel_type'] = 1; // search for title
            $this->bbs_model->search($this->search);
            $this->mr['keyword'] = $this->search['keyword'];
        }
        //Sort
        $display = $this->input->post('sel_display');
        if (isset($display)) {
            $this->search['display'] = $display;
        }
        $this->session->set('sess_search_news', $this->search);
        $this->showlist();
    }

    private function showlist() {
        $this->template->content = new View('admin_bbs/list');
        //Assign
        $this->template->content->set(array(
            'keyword' => $this->search['keyword'],
            'display' => $this->search['display'],
        ));
        $mlist = $this->bbs_model->get(FALSE, $this->mr['page_id'], '', $this->get_admin_lang());
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
                    'base_url' => 'admin_news/search/pid/' . $this->mr['page_id'],
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
        $mlist = $this->bbs_model->get(FALSE, $this->mr['page_id'], '', $this->get_admin_lang());

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang($this->mr['page_type_name'] . '_lang.tt_page'),
            'mlist' => $mlist,
            'mr' => $this->mr
        ));
    }

    public function create() {
        $this->template->content = new View('admin_news/frm');

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang('news_lang.tt_page') . ' -> ' . Kohana::lang('global_lang.lbl_create'),
            'mr' => $this->mr,
            'list_file' => ''
        ));
    }

    public function edit() {
        $this->template->content = new View('admin_news/frm');

        $mr = $this->bbs_model->get(FALSE, '', $this->mr['bbs_id'], $this->get_admin_lang());
        if (empty($mr)) {
            url::redirect('admin_news/search/pid/' . $this->mr['page_id']);
            die();
        }
        $this->mr = array_merge($mr, $this->mr);
        $list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang('news_lang.tt_page') . ' -> ' . Kohana::lang('global_lang.lbl_edit'),
            'mr' => $this->mr,
            'list_file' => $list_file
        ));
    }

    public function delete($id='') {
        if (!$this->permisController('delete')) {
            $this->session->set_flash('error_msg', Kohana::lang('global_lang.lbl_authorized'));
            url::redirect('admin_news/search/pid/' . $this->mr['page_id']);
            die();
        } else {
            $del = '';
            if (!empty($id)) {
                $bbs = ORM::factory('bbs_orm')->find($id);
                $bbs_file = ORM::factory('bbs_file_orm')->where('bbs_id', $id)->find_all();

                $path_news = DOCROOT . 'uploads/news/';
                for ($i = 0; $i < count($bbs_file); $i++) {
                    if (!empty($bbs_file[$i]->bbs_file_id)) {
                        $path_nf = $path_news . $bbs_file[$i]->bbs_file_name;

                        if (is_file($path_nf) && file_exists($path_nf))
                            @unlink($path_nf);
                        $bbs_file[$i]->delete();
                    }
                }

                $del = $this->bbs_model->delete($bbs->bbs_id, FALSE);
                $json['mgs'] = $del ? '' : Kohana::lang('errormsg_lang.error_data_del');
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

    private function _get_frm_valid() {
        $file_type = ORM::factory('file_type_orm')->where('file_type_detail', 'image')->find();
        $form = array(
            'txt_title' => '',
            'txt_content' => '',
            'txt_sort_order' => '',
            'sel_status' => ''
        );

        for ($i = 1; $i <= 3; $i++)
            $form['attach_file' . $i] = '';

        $errors = $form;

        $post = new Validation(array_merge($_POST, $_FILES));

        $post->pre_filter('trim', TRUE);
        $post->add_rules('txt_title', 'required', 'length[1,200]');
        $post->add_rules('txt_content', 'required');

        for ($i = 1; $i <= 3; $i++)
            $post->add_rules('attach_file' . $i, 'upload::type[' . $file_type->file_type_ext . ']', 'upload::size[10M]');

        if ($post->validate()) {
            $form = arr::overwrite($form, $post->as_array());
            return $form;
        } else {
            $form = arr::overwrite($form, $post->as_array());  // Retrieve input data
            $this->session->set_flash('input_data', $form);  // Set input data in session			

            $errors = arr::overwrite($errors, $post->errors('news_validation'));
            $str_error = '';

            foreach ($errors as $id => $name)
                if ($name)
                    $str_error .= $name . '<br>';
            $this->session->set_flash('error_msg', $str_error);

            url::redirect($this->site['history']['current']);
        }
    }

    public function save() {
        $msg = array('error' => '', 'success' => '');
        $file_img_id = ORM::factory('file_type_orm')->where('file_type_detail', 'image')->find()->file_type_id;
        $bbs_id = $this->input->post('hd_id');

        $frm_news = $this->_get_frm_valid();

        $set = array(
            'bbs_page_id' => $this->mr['page_id'],
            'bbs_author' => $this->sess_admin['username'],
            'bbs_sort_order' => $frm_news['txt_sort_order'],
            'bbs_status' => $frm_news['sel_status'],
            'bbs_title' => $frm_news['txt_title'],
            'bbs_content' => $frm_news['txt_content']
        );

        if (empty($bbs_id)) { // create news 
            $set['bbs_date_created'] = time();

            $bbs_id = $this->bbs_model->insert($set, FALSE);
            if ($bbs_id !== FALSE)
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_add') . '<br>';
            else {
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_add'));
                url::redirect($this->site['history']['current']);
            }
        } else { // edit news
            $set['bbs_id'] = $bbs_id;
            $set['bbs_date_modified'] = time();

            if ($this->bbs_model->update($this->get_admin_lang(), $set))
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_save') . '<br>';
            else
                $msg['error'] .= Kohana::lang('errormsg_lang.error_data_save') . '<br>';
        }

        for ($i = 1; $i <= 3; $i++) {
            if (isset($frm_news['attach_file' . $i]['error']) && $frm_news['attach_file' . $i]['error'] == 0) {
                $path_news = DOCROOT . 'uploads/news/';

                $path_file = upload::save('attach_file' . $i, NULL, $path_news);
                $file_name = basename($path_file);
                
                // create file image thumbnail
                $image = new Image($path_news.$file_name);
                $image->resize(NULL, 150);
                $image->save($path_news.'thumb_'.$file_name);
                
                // upload file image in album directory and save in database											
                $bbs_file = ORM::factory('bbs_file_orm');

                $bbs_file->file_type_id = $file_img_id;
                $bbs_file->bbs_id = $bbs_id;
                $bbs_file->bbs_file_name = $file_name;
                $bbs_file->bbs_file_description = '';
                $bbs_file->bbs_file_date_created = time();
                $bbs_file->bbs_file_download = 0;

                $bbs_file->save();
                $bbs_file->bbs_file_order = $bbs_file->bbs_file_id;
                $bbs_file->save();
            } elseif (isset($frm_news['attach_file' . $i]['error']) && $frm_news['attach_file' . $i]['error'] != 4)
                $msg['error'] .= Kohana::lang('errormsg_lang.error_file_upload') . ' (' . Kohana::lang('client_lang.lbl_file') . " $i)<br>";
        }

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        if (!empty($_POST['hd_save_add']))
            url::redirect('admin_news/create/pid/' . $this->mr['page_id']);
        elseif ($this->input->post('hd_id'))
            url::redirect($this->site['history']['current']);
        else
            url::redirect($this->site['history']['back']);

        die();
    }

    public function del_image($bbs_fid = '') {
        if (!$this->_delele_bbs_file('news', $bbs_fid))
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));

        url::redirect($this->site['history']['back']);
        die();
    }

}

?>
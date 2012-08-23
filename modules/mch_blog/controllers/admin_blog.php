<?php

class Admin_blog_Controller extends Template_Controller {

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
        if ($this->session->get('sess_search_blog')) {
            $this->search = $this->session->get('sess_search_blog');
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
        $this->session->set('sess_search_blog', $this->search);	
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
                    'base_url' => 'admin_blog/search/pid/' . $this->mr['page_id'],
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
    
    private function _get_frm_valid() {
        $form = array(
            'hd_pid' => '',
            'txt_title' => '',
            'txt_content' => '',
            'txt_sort_order' => '',
            'sel_status' => ''
        );

        $errors = $form;

        $post = new Validation($_POST);

        $post->pre_filter('trim', TRUE);
        $post->add_rules('txt_title', 'required', 'length[1,200]');
        $post->add_rules('txt_content', 'required');

        if ($post->validate()) {
            $form = arr::overwrite($form, $post->as_array());
            return $form;
        } else {
            $form = arr::overwrite($form, $post->as_array());  // Retrieve input data
            $this->session->set_flash('input_data', $form);  // Set input data in session			

            $errors = arr::overwrite($errors, $post->errors('bbs_validation'));
            $str_error = '';

            foreach ($errors as $id => $name) if ($name) $str_error .= $name.'<br>';
            $this->session->set_flash('error_msg', $str_error);

            url::redirect($this->site['history']['current']);
        }
    }

    public function save() {
        $bbs_id = $this->input->post('hd_id');

        $frm_blog = $this->_get_frm_valid();

        $set = array(
            'bbs_page_id' => $frm_blog['hd_pid'],
            'bbs_author' => $this->sess_admin['username'],
            'bbs_sort_order' => $frm_blog['txt_sort_order'],
            'bbs_status' => $frm_blog['sel_status'],
            'bbs_title' => $frm_blog['txt_title'],
            'bbs_content' => $frm_blog['txt_content']
        );

        if (empty($bbs_id)) { // create new blog
            $set['bbs_date_created'] = time();
            $this->bbs_model->insert($set, FALSE); // insert new bbs

            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_add'));
        } else { // edit blog
            $set['bbs_id'] = $bbs_id;
            $set['bbs_date_modified'] = time();

            $this->bbs_model->update($this->get_admin_lang(), $set);
            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
        }

        if(!empty($_POST['hd_save_add']))
            url::redirect('admin_blog/create/pid/' . $this->mr['page_id']);
        elseif (!empty($bbs_id))
            url::redirect($this->site['history']['current']);
        else
            url::redirect($this->site['history']['back']);
        die();
    }

    public function create() {
        $this->template->content = new View('admin_blog/frm');

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang('blog_lang.tt_page') . ' -> ' . Kohana::lang('global_lang.lbl_create'),
            'mr' => $this->mr
        ));
    }

    public function edit() {
        $this->template->content = new View('admin_blog/frm');

        $mr = $this->bbs_model->get(FALSE, '', $this->mr['bbs_id'], $this->get_admin_lang());
        if (empty($mr)) {
            url::redirect('admin_blog/search/pid/' . $this->mr['page_id']);
            die();
        }
        $this->mr = array_merge($mr, $this->mr);
        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang('blog_lang.tt_page') . ' -> ' . Kohana::lang('global_lang.lbl_edit'),
            'mr' => $this->mr
        ));
    }

    public function delete($id='') {
        if (!$this->permisController('delete')) {
            $this->session->set_flash('error_msg', Kohana::lang('global_lang.lbl_authorized'));
            url::redirect($this->site['history']['current']);
            die();
        } else {
            $del = '';
            if (!empty($id)) {
                $del = $this->bbs_model->delete($id, FALSE);                
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

}

?>
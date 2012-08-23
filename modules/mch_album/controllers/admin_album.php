<?php

class Admin_album_Controller extends Template_Controller {

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
        $this->mr = array_merge($this->mr, $page_model->get_page_lang($this->get_admin_lang(), $page_id));
        $this->search = array('display' => '', 'keyword' => '', 'sel_type' => '');
        $this->_get_session_msg();
    }

    public function __call($method, $agruments) {
        $this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');

        $this->search();
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
        if ($this->session->get('sess_search_album')) {
            $this->search = $this->session->get('sess_search_album');
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
        $this->session->set('sess_search_album', $this->search);
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
                    'base_url' => 'admin_album/search/pid/' . $this->mr['page_id'],
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
        $this->db->orderby('bbs.bbs_id', 'desc');
        $mlist = $this->bbs_model->get(FALSE, $this->mr['page_id'], '', $this->get_admin_lang());

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang($this->mr['page_type_name'] . '_lang.tt_page'),
            'mlist' => $mlist,
            'mr' => $this->mr
        ));
    }
    
    public function create() {
        $this->template->content = new View('admin_album/frm');
        $this->mr['quantity'] = $this->uri->segment('quantity', 5);
        if (!valid::digit($this->mr['quantity']))
            $this->mr['quantity'] = 5;

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang('album_lang.tt_page') . ' -> ' . Kohana::lang('global_lang.lbl_create'),
            'mr' => $this->mr,
            'list_file' => array()
        ));
    }

    public function edit() {
        $this->template->content = new View('admin_album/frm');
        // Id of tab need selected
        $this->mr['tab_id'] = $this->input->post('tab_id');

        $bbs = $this->bbs_model->get(FALSE, '', $this->mr['bbs_id'], $this->get_admin_lang());
        if (empty($bbs)) {
            url::redirect('admin_album/search/pid/' . $this->mr['page_id']);
            die();
        }
        $img_ext = Model::factory('file_type')->get_fext('image');
        $arr_tmp = explode(',', $img_ext);
        foreach ($arr_tmp as $key => $value)
            $arr_tmp[$key] = '*.' . $value;
        // Image file extension allow
        $this->mr['file_ext'] = implode(';', $arr_tmp);

        // List files of bbs
        $this->mlist['files'] = Model::factory('bbs_file')->get($this->mr['bbs_id']);
        // Total files of bbs
        $this->mr['file_total'] = count($this->mlist['files']);

        for ($i = 0; $i < $this->mr['file_total']; $i++) {
            // Absolute path of file image
            $this->mlist['files'][$i]['file_path_absolute'] = MCH_Core::format_path(DOCROOT . 'uploads/album/' . $this->mlist['files'][$i]['bbs_file_name']);
            // Html path of file image ("src" property of IMG tag)
            $this->mlist['files'][$i]['file_path_html'] = url::base() . 'uploads/album/' . $this->mlist['files'][$i]['bbs_file_name'];
        }

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' -> ' . Kohana::lang('album_lang.tt_page') . ' -> ' . Kohana::lang('global_lang.lbl_edit'),
            'mr' => array_merge($this->mr, $bbs),
            'mlist' => $this->mlist
        ));
    }

    private function _get_frm_valid() {
        $file_type = ORM::factory('file_type_orm')->where('file_type_detail', 'image')->find();

        $form = Array
            (
            'txt_title' => '',
            'txt_content' => '',
            'txt_sort_order' => '',
            'sel_status' => ''
        );

        for ($i = 1; $i <= 5; $i++) {
            //$form['image'.$i] = '';
            $form['txt_des' . $i] = '';
        }

        $errors = $form;

        $post = new Validation(array_merge($_POST, $_FILES));

        $post->pre_filter('trim', TRUE);
        $post->add_rules('txt_title', 'required', 'length[1,200]');

        for ($i = 1; $i <= 5; $i++) {
            $post->add_rules('txt_des' . $i, 'length[1,100]');
        }

        $form = arr::overwrite($form, $post->as_array());

        if ($post->validate()) // Data validation	
            return $form;
        else {
            $this->session->set_flash('input_data', $form);

            $errors = arr::overwrite($errors, $post->errors('album_validation'));
            $str_error = '';

            foreach ($errors as $id => $name)
                if ($name)
                    $str_error .= $name . '<br>';
            $this->session->set_flash('error_msg', $str_error);

            url::redirect($this->site['history']['current']);
            die();
        }
    }

    public function save() {
        $file_img_id = ORM::factory('file_type_orm')->where('file_type_detail', 'image')->find()->file_type_id;
        $msg = array('error' => '', 'success' => '');
        $frm_album = $this->_get_frm_valid();

        $bbs_id = $this->input->post('hd_id');

        $set = array(
            'bbs_page_id' => $this->mr['page_id'],
            'bbs_title' => $frm_album['txt_title'],
            'bbs_content' => $frm_album['txt_content'],
            'bbs_count' => 0,
            'bbs_author' => $this->sess_admin['username'],
            'bbs_sort_order' => $frm_album['txt_sort_order'],
            'bbs_status' => $frm_album['sel_status']
        );

        if (empty($bbs_id)) { // create new album 
            $set['bbs_date_created'] = time();

            $bbs_id = $this->bbs_model->insert($set, FALSE);
            if ($bbs_id !== FALSE)
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_add') . '<br>';
            else {
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_add'));
                url::redirect($this->site['history']['current']);
            }
        } else { // edit album
            $list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $bbs_id)->find_all()->as_array();
            for ($i = 0; $i < count($list_file); $i++) {
                if (!empty($list_file[$i]->bbs_file_id)) {
                    $des = $this->input->post('des_' . $list_file[$i]->bbs_file_id);
                    $list_file[$i]->bbs_file_description = $des;
                    $list_file[$i]->save();
                }
            }

            $set['bbs_id'] = $bbs_id;
            $set['bbs_date_modified'] = time();

            if ($this->bbs_model->update($this->get_admin_lang(), $set))
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_save') . '<br>';
            else
                $msg['error'] .= Kohana::lang('errormsg_lang.error_data_save') . '<br>';
        }

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        if(!empty($_POST['hd_save_add']))
            url::redirect('admin_album/create/pid/' . $this->mr['page_id']);
        elseif ($this->input->post('hd_id'))	
            url::redirect($this->site['history']['current']);
        else
            url::redirect('admin_album/edit/id/' . $bbs_id);
        die();
    }

    public function delete($id='') {
        if (!$this->permisController('delete')) {
            $this->session->set_flash('error_msg', Kohana::lang('global_lang.lbl_authorized'));
            url::redirect('admin_album/search/pid/' . $this->mr['page_id']);
            die();
        } else {
            $del = '';
            if (!empty($id)) {
                $bbs = ORM::factory('bbs_orm')->find($id);
                $bbs_file = ORM::factory('bbs_file_orm')->where('bbs_id', $id)->find_all();

                $path_album = DOCROOT . 'uploads/album/';
                for ($i = 0; $i < count($bbs_file); $i++) {
                    if (!empty($bbs_file[$i]->bbs_file_id)) {
                        $path_af = $path_album . $bbs_file[$i]->bbs_file_name;
                        $path_thumb_af = $path_album . 'thumb_' . $bbs_file[$i]->bbs_file_name;

                        if (is_file($path_af) && file_exists($path_af))
                            unlink($path_af);
                        if (is_file($path_thumb_af) && file_exists($path_thumb_af))
                            unlink($path_thumb_af);
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

    public function del_image($bbs_fid = '') {
        if (!$this->_delele_bbs_file('album', $bbs_fid))
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));

        url::redirect($this->site['history']['back']);
        die();
    }

    public function order_file($action, $id, $bbs_id) {
        if (!$this->_order_bbs_file($bbs_id, $id, $action))
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));

        url::redirect($this->site['history']['current']);
        die();
    }

    public function upload($id) {
        $this->auto_render = FALSE; // disable auto render

        MCH_Core::upload_bbs_file('album', 'bbsFile', $id);
    }

}

?>
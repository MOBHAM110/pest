<?php

class Album_Controller extends Template_Controller {

    public $template;

    public function __construct() {
        parent::__construct();
        $this->template = new View('templates/' . $this->site['config']['TEMPLATE'] . '/client/index');
        $this->template->layout = $this->get_MCH_layout(); // init layout for template controller

        /* --------------------------------------get session if exist ------------------------------------------ */
        if ($this->session->get('sess_album')) {
            $this->search = $this->session->get('sess_album');
            $this->session->keep_flash('sess_album');
        }
        else
            $this->search = array('sel_type' => '', 'keyword' => '');
        $this->_get_session_msg();

        /* ----------------------------------- init db, variables ---------------------------------------------- */
        $this->bbs_model = new Bbs_Model();

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
            if (isset($indata['txt_content']))
                $this->mr['bbs_content'] = $indata['txt_content'];
            if (isset($indata['txt_name']))
                $this->mr['bbs_author'] = $indata['txt_name'];
            if (isset($indata['txt_name']))
                $this->mr['com_name'] = $indata['txt_name'];
            if (isset($indata['txt_email']))
                $this->mr['com_email'] = $indata['txt_email'];
            if (isset($indata['txt_content_com']))
                $this->mr['com_content'] = $indata['txt_content_com'];
        }
    }

    public function __call($method, $arguments) {
        $bbs_id = $this->uri->segment('id');
        //Check page
        $bbs = $this->bbs_model->get(FALSE, $this->page_id, '', $this->get_client_lang());
        if (!empty($bbs)) {
            //Check bbs
            $bbs = $this->bbs_model->get(FALSE, $this->page_id, $bbs_id, $this->get_client_lang());
            if (isset($arguments[1]) && $bbs_id && empty($bbs)) {
                url::redirect('album/pid/' . $this->page_id);
                die();
            } elseif ($bbs_id)
                $this->mr['bbs_id'] = $bbs_id;
        } else
            $this->warning = 'wrong_pid';

        if (isset($arguments[1]) && $bbs_id && empty($bbs))
            $this->warning = 'wrong_pid';
        elseif ($bbs_id)
            $this->mr['bbs_id'] = $bbs_id;

        if (!empty($this->warning)) {
            $this->warning_msg($this->warning);
        } elseif ($method === 'pid') {
            if (!isset($arguments[1])) {
                $this->search['keyword'] = '';
                $this->_show_list();
            } else {
                switch ($arguments[1]) {
                    case 'search' :
                    case 'upload' :
                    case 'detail' :
                        $this->mr = array_merge($bbs, $this->mr);
                        $this->{$arguments[1]}();
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
                            } elseif (empty($this->sess_cus)&& $this->mr['page_write_permission']!=5)
                                $this->warning_msg('restrict_access');
                            else
                                $this->{$arguments[1]}();
                            break;
                        }

                    case 'delete_image' : {
                            $this->mr = array_merge($bbs, $this->mr);

                            if (!$bbs_id)
                                $this->warning_msg('wrong_pid');
                            elseif (!empty($this->sess_cus) && $this->sess_cus['level'] > $this->mr['page_write_permission']
                                    && $this->mr['bbs_author'] != $this->sess_cus['username']) {
                                $this->warning_msg('restrict_access');
                            } elseif (empty($this->sess_cus)&& $this->mr['page_write_permission']!=5)
                                $this->warning_msg('restrict_access');
                            else
                                $this->delele_image(isset($arguments[2]) ? $arguments[2] : '');
                            break;
                        }

                    case 'order_file' : {
                            $this->mr = array_merge($bbs, $this->mr);

                            $arguments[2] = isset($arguments[2]) ? $arguments[2] : '';
                            $arguments[3] = isset($arguments[3]) ? $arguments[3] : '';

                            if (!$bbs_id)
                                $this->warning_msg('wrong_pid');
                            elseif (!empty($this->sess_cus) && $this->sess_cus['level'] > $this->mr['page_write_permission']
                                    && $this->mr['bbs_author'] != $this->sess_cus['username']) {
                                $this->warning_msg('restrict_access');
                            } elseif (empty($this->sess_cus)&& $this->mr['page_write_permission']!=5)
                                $this->warning_msg('restrict_access');
                            else
                                $this->order_file($arguments[2], $arguments[3]);
                            break;
                        }

                    case 'save' :
                    case 'comment' :
                        if (!isset($_POST))
                            $this->warning_msg('wrong_pid');
                        else
                            $this->{$arguments[1]}();
                        break;

                    case 'download' :
                    case 'save_comment' :
                    case 'modify_comment' :
                        $this->{$arguments[1]}(isset($arguments[2]) ? $arguments[2] : '');
                        break;

                    default : $this->warning_msg('wrong_pid');
                }
            }
        }
        else {
            $this->warning_msg('wrong_pid');
        }
    }

    private function _show_list() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/album/list');

        //paging
        $this->bbs_model->search($this->search);

        $this->bbs_model->set_query('where', 'bbs_status', 1);
        $total_rows = count($this->bbs_model->get(FALSE, $this->page_id, '', $this->get_client_lang()));

        $this->pagination = new Pagination(array(
                    'base_url' => 'album/pid/' . $this->page_id . '/search',
                    'uri_segment' => 'page',
                    'total_items' => $total_rows,
                    'items_per_page' => $this->site['config']['CLIENT_NUM_LINE'],
                    'style' => 'punbb',
                ));
        $this->bbs_model->set_limit($this->pagination->items_per_page, $this->pagination->sql_offset); // LIMIT query
        //run sql		
        $this->bbs_model->search($this->search);

        $this->bbs_model->set_query('where', 'bbs_status', 1);
        $this->db->orderby('bbs.bbs_id', 'desc');
        $mlist = $this->bbs_model->get(FALSE, $this->page_id, '', $this->get_client_lang());

        for ($i = 0; $i < count($mlist); $i++) {
            $mlist[$i]['bbs_title_full'] = $mlist[$i]['bbs_title'];
            $mlist[$i]['bbs_title'] = text::limit_chars($mlist[$i]['bbs_title'], 20);

            // show format date 
            $mlist[$i]['bbs_date_created'] = date($this->site['config']['FORMAT_SHORT_DATE'], $mlist[$i]['bbs_date_created']);
            if ($mlist[$i]['bbs_date_modified'] != 0)
                $mlist[$i]['bbs_date_modified'] = date($this->site['config']['FORMAT_SHORT_DATE'], $mlist[$i]['bbs_date_modified']);

            $list_file = Model::factory('bbs_file')->get($mlist[$i]['bbs_id']);

            $mlist[$i]['bbs_file'] = empty($list_file) ? '' : $list_file[0]['bbs_file_name'];
            $mlist[$i]['bbs_file_path'] = MCH_Core::format_path(DOCROOT . 'uploads/album/thumb_' . $mlist[$i]['bbs_file']);
            $mlist[$i]['bbs_file_path_html'] = url::base() . 'uploads/album/thumb_' . $mlist[$i]['bbs_file'];

            if (!empty($this->search['keyword'])) {
                switch ($this->search['sel_type']) {
                    case '1' :
                        $mlist[$i]['bbs_title'] = $this->format_focus_search($this->search['keyword'], $mlist[$i]['bbs_title']);
                        break;

                    case '3' : $mlist[$i]['bbs_content'] = $this->format_focus_search($this->search['keyword'], $mlist[$i]['bbs_content']);
                        break;

                    default :
                        $mlist[$i]['bbs_title'] = $this->format_focus_search($this->search['keyword'], $mlist[$i]['bbs_title']);
                        $mlist[$i]['bbs_content'] = $this->format_focus_search($this->search['keyword'], $mlist[$i]['bbs_content']);
                }
            }
        }

        $this->session->set_flash('sess_album', $this->search); // session important		
        // assign template->content
        $this->template->content->mlist = $mlist;
        $this->template->content->mr = $this->mr;
    }

    public function detail() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/album/detail');

        $bbs_file_model = new Bbs_file_Model();
        $this->mr['list_file'] = $bbs_file_model->get($this->mr['bbs_id']);

        $img_ext = Model::factory('file_type')->get_fext('image');
        $arr_tmp = explode(',', $img_ext);
        foreach ($arr_tmp as $key => $value)
            $arr_tmp[$key] = '*.' . $value;
        $this->mr['file_ext'] = implode(';', $arr_tmp);

        for ($i = 0; $i < count($this->mr['list_file']); $i++) {
            $this->mr['list_file'][$i]['file_path'] = MCH_Core::format_path(DOCROOT . 'uploads/album/' . $this->mr['list_file'][$i]['bbs_file_name']);
            $this->mr['list_file'][$i]['file_path_html'] = 'uploads/album/' . $this->mr['list_file'][$i]['bbs_file_name'];

            $this->mr['list_file'][$i]['file_size'] = $this->formatBytes(@filesize($this->mr['list_file'][$i]['file_path']));
        }

        $this->mr['bbs_date_created'] = date($this->site['config']['FORMAT_SHORT_DATE'], $this->mr['bbs_date_created']);

        $comment_model = new Comment_Model();
        $list_com = $comment_model->get_com($this->mr['bbs_id']);

        for ($i = 0; $i < count($list_com); $i++) {
            $list_com[$i]['comment_time'] = date($this->site['config']['FORMAT_LONG_DATE'], $list_com[$i]['comment_time']);
            $list_com[$i]['comment_content'] = $list_com[$i]['comment_content'];
        }
        $this->template->content->list_comment = $list_com;

        //set increate view	
        $this->bbs_model->increase_count($this->mr['bbs_id']);

        $this->template->content->mr = $this->mr;
    }

    public function search() {
        $txt_keyword = $this->input->post('txt_keyword');
        $sel_type = $this->input->post('sel_type');

        if (!empty($_POST)) {
            $this->search['keyword'] = $txt_keyword;
            $this->search['sel_type'] = $sel_type;
        }

        //assign keyword
        $this->mr['keyword'] = $this->search['keyword'];

        if (!empty($this->search['sel_type'])) {
            $str_tmp = 'type' . $this->search['sel_type'] . '_selected';
            $this->mr[$str_tmp] = 'selected';
        }

        $this->_show_list();
    }

    public function create() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/album/frm');

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' :: ' . Kohana::lang('global_lang.lbl_create'),
            'mr' => $this->mr
        ));
    }

    public function edit() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/album/frm');

        // Id of tab need selected
        $this->mr['tab_id'] = $this->input->post('tab_id');

        $bbs = $this->bbs_model->get(FALSE, '', $this->mr['bbs_id'], $this->get_client_lang());

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
            $this->mlist['files'][$i]['file_path_absolute'] = MCH_Core::format_path(DOCROOT . 'uploads/album/thumb_' . $this->mlist['files'][$i]['bbs_file_name']);
            // Html path of file image ("src" property of IMG tag)
            $this->mlist['files'][$i]['file_path_html'] = url::base() . 'uploads/album/thumb_' . $this->mlist['files'][$i]['bbs_file_name'];
        }

        $this->template->content->set(array(
            'title' => $this->mr['page_title'] . ' :: ' . Kohana::lang('global_lang.lbl_edit'),
            'mr' => array_merge($this->mr, $bbs),
            'mlist' => $this->mlist
        ));
    }

    private function _get_album_valid($id='') {
        $file_type = ORM::factory('file_type_orm')->where('file_type_detail', 'image')->find();

        $form = Array
            (
            'txt_title' => '',
            'txt_name' => '',
            'txt_content' => '',            
            'txt_pass' => '',						
        );

        for ($i = 1; $i <= 5; $i++) {
            $form['txt_des' . $i] = '';
        }

        $errors = $form;

        $post = new Validation(array_merge($_POST, $_FILES));

        $post->pre_filter('trim', TRUE);
        $post->add_rules('txt_title', 'required', 'length[1,200]');
        $post->add_rules('txt_name', 'required');
        if(!$id)
        $post->add_rules('txt_pass', 'length[6,20]', 'required');
        $post->add_rules('txt_content', 'required');

        for ($i = 1; $i <= 5; $i++) {
            $post->add_rules('txt_des' . $i, 'length[1,100]');
        }

        $form = arr::overwrite($form, $post->as_array());

        if ($post->validate())  // Data validation	
            return $form;
        else {
            $this->session->set_flash('input_data', $form);

            $errors = arr::overwrite($errors, $post->errors('album_validation'));
            $str_error = '';

            foreach ($errors as $id => $name)
                if ($name)
                    $str_error.='<br>' . $name;
            $this->session->set_flash('error_msg', $str_error);

            url::redirect($this->site['history']['current']);
            die();
        }
    }

    public function save() {
        $file_img_id = ORM::factory('file_type_orm')->where('file_type_detail', 'image')->find()->file_type_id;
        $msg = array('error' => '', 'success' => '');
        $bbs_id = $this->input->post('hd_id');
        $frm_album = $this->_get_album_valid($bbs_id);        

        $set = array(
            'bbs_page_id' => $this->mr['page_id'],
            'bbs_title' => $frm_album['txt_title'],
            'bbs_content' => $frm_album['txt_content'],
            'bbs_author' => empty($this->sess_cus['username']) ? $frm_album['txt_name'] : $this->sess_cus['username'],
            'bbs_status' => 1
        );
        if(!empty($frm_album['txt_pass']))
            $set['bbs_password'] = md5($frm_album['txt_pass']);

        if (empty($bbs_id)) { // create new album 
            $set['bbs_date_created'] = time();
            $set['bbs_count'] = 0;

            $bbs_id = $this->bbs_model->insert($set, FALSE);
            if ($bbs_id !== FALSE)
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_add') . '<br>';
            else {
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_add'));
                url::redirect($this->site['history']['back']);
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
            //$set['bbs_file'] = ORM::factory('bbs_orm')->find($bbs_id)->bbs_file.$set['bbs_file'];
            $set['bbs_date_modified'] = time();

            if ($this->bbs_model->update($this->get_client_lang(), $set))
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_save') . '<br>';
            else
                $msg['error'] .= Kohana::lang('errormsg_lang.error_data_save') . '<br>';
        }

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        if ($this->input->post('hd_id'))
            url::redirect($this->site['history']['current']);
        else
            url::redirect('album/pid/'.$this->mr['page_id'].'/edit/id/'. $bbs_id);

        die();
    }

    private function delete() {
        $comment_model = new Comment_Model();
        $bbs_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->find_all();

        $path_album = DOCROOT . 'uploads/album/';
        for ($i = 0; $i < count($bbs_file); $i++) {
            if (!empty($bbs_file[$i]->bbs_file_id)) {
                $path_af = $path_album . $bbs_file[$i]->bbs_file_name;

                if (is_file($path_af) && file_exists($path_af))
                    @unlink($path_af);
                $bbs_file[$i]->delete();
            }
        }

        // delete bbs in database	
        if ($this->bbs_model->delete($this->mr['bbs_id'], FALSE)) {
            $comment_model->delete_mod(array('bbs_id' => $this->mr['bbs_id']));
            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));
        }
        else
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_del'));

        url::redirect($this->site['history']['current']);
        die();
    }

    public function delele_image($bbs_fid = '') {
        if ($this->_delele_bbs_file('album', $bbs_fid)) {
            url::redirect($this->site['history']['current']);
            die();
        }

        $this->warning_msg('wrong_pid');
    }

    public function order_file($action = '', $bbs_fid = '') {
        if ($this->_order_bbs_file($this->mr['bbs_id'], $bbs_fid, $action)) {
            url::redirect($this->site['history']['current']);
            die();
        }

        $this->warning_msg('wrong_pid');
    }

    public function compare_pass()
    {
        $input_pass = $_POST['input_pass'];
        $cur_pass = $_POST['cur_pass'];
        $page_id = $_POST['page_id'];
        $action = $_POST['method'];
        $id = $_POST['bbs_id'];
        if($input_pass && $cur_pass && $cur_pass == md5($input_pass) && !empty($page_id) && !empty($action) && !empty($id)){
            $this->session->set('write_everybody', '1');
            echo url::base().'album/pid/'.$page_id.'/'.$action.'/id/'.$id;
        }
        die();
    }
    
    private function _get_frm_com() {
        $captcha = new Captcha();

        $form = array(
            'txt_name' => '',
            'txt_email' => '',
            'txt_pass' => '',
            'txt_content_com' => '',
            'captcha_response' => ''
        );

        $errors = $form;

        $post = new Validation($_POST);

        $post->add_rules('txt_name', 'required');
        $post->add_rules('txt_email', 'email');
        $post->add_rules('txt_pass', 'length[6,6]', 'required');
        $post->add_rules('txt_content_com', 'required');
        $post->add_rules('captcha_response', 'required', 'Captcha::valid');
        //$post->add_callbacks('txt_captcha', array('Captcha', 'valid'));

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

    public function comment() {
        $bbs_id = $this->input->post('hd_id');
        $frm_com = $this->_get_frm_com();
        $page_type = $this->uri->segment(1);
        $page_id = $this->uri->segment('pid');
        if (!empty($bbs_id)) {
            $set = array(
                'bbs_id' => $bbs_id,
                'comment_password' => md5($frm_com['txt_pass']),
                'comment_author' => $frm_com['txt_name'],
                'comment_email' => $frm_com['txt_email'],
                'comment_content' => $frm_com['txt_content_com'],
                'page_type_name' => $page_type,
                'page_id' => $page_id,
                'comment_time' => time()
            );

            $comment_model = new Comment_Model();
            $cid = $comment_model->insert_mod($set);

            url::redirect($this->site['history']['back'] . '#comment-' . $cid);
        }

        $this->warning_msg('wrong_pid');
    }

    public function modify_comment($id = '') {
        $com_pass = $this->input->post('txt_com_pass', '', TRUE);
        $sel_action = $this->input->post('sel_action');
        $com = ORM::factory('comment_orm')->find($id);

        if (!empty($com->comment_id)) {
            if (md5($com_pass) == $com->comment_password || $this->sess_cus['type'] < 3) { // < 3 : super admin or admin
                if ($sel_action == 'edit') {
                    $this->session->set_flash('edit_com_id', $id);

                    url::redirect($this->site['history']['back'] . '#comment-' . $id);
                } else {
                    $com->delete();
                    //$this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));

                    url::redirect($this->site['history']['back'] . '#album_comment');
                }
            }
            else
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_com_pass'));

            url::redirect($this->site['history']['back']);
            die();
        }

        $this->warning_msg('wrong_pid');
    }

    public function save_comment($id = '') {
        $txt_content = $this->input->post('txt_content_com');
        $com = ORM::factory('comment_orm')->find($id);

        if (!empty($com->comment_id)) {
            if (empty($txt_content))
                $this->session->set_flash('error_msg', Kohana::lang('comment_validation.txt_content.required'));
            else {
                $com->comment_content = $txt_content;

                $com->save();
                //$this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));

                url::redirect($this->site['history']['current'] . '#comment-' . $id);
            }

            url::redirect($this->site['history']['current']);
            die();
        }

        $this->warning_msg('wrong_pid');
    }

    public function download($id) {
        if (!$this->_download_bbs_file('album', $id))
            $this->warning_msg('wrong_pid');
    }

    public function upload() {
        $this->auto_render = FALSE; // disable auto render
        //MCH_Core::upload_bbs_file('album', 'bbsFile', $this->mr['bbs_id']);
        if (!empty($_FILES)) {
            $file_type_model = new File_type_Model();
            $file_img_id = $file_type_model->get_fid('image');
            $file_up_id = $file_type_model->get_fid('', $_FILES['bbsFile']['name']);

            if ($file_img_id == $file_up_id) {
                $path_dir = MCH_Core::format_path(DOCROOT . 'uploads/album/');

                $path_file = upload::save('bbsFile', NULL, $path_dir);
                $file_name = basename($path_file);

                // create file image thumbnail
                $image = new Image($path_dir . $file_name);
                $image->resize(NULL, 150);
                $image->save($path_dir . 'thumb_' . $file_name);

                // upload file in directory and save in database									
                $bbs_file = ORM::factory('bbs_file_orm');

                $bbs_file->file_type_id = $file_img_id;
                $bbs_file->bbs_id = $this->mr['bbs_id'];
                $bbs_file->bbs_file_name = $file_name;
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

}
<?php

class Blog_Controller extends Template_Controller {

    public $template;

    public function __construct() {
        parent::__construct();
        $this->template = new View('templates/' . $this->site['config']['TEMPLATE'] . '/client/index');
        $this->template->layout = $this->get_MCH_layout(); // init layout for template controller        

        /* --------------------------------------get session if exist ------------------------------------------ */
        if ($this->session->get('sess_blog')) {
            $this->search = $this->session->get('sess_blog');
            $this->session->keep_flash('sess_blog');
        }
        else
            $this->search = array('sel_type' => '', 'keyword' => '');
        $this->_get_session_msg();
        /* --------------------------------------------------------------------------------------------------- */

        /* ---------------------------------- Init properties of Controller ---------------------------------- */
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
                url::redirect('blog/pid/' . $this->page_id);
                die();
            } elseif ($bbs_id)
                $this->mr['bbs_id'] = $bbs_id;
        } else
            $this->warning = 'wrong_pid';

        if (!empty($this->warning)) {
            $this->warning_msg($this->warning);
        } elseif ($method === 'pid') {
            if (!isset($arguments[1])) {
                $this->search['keyword'] = '';
                $this->_show_list();
            } else {
                switch ($arguments[1]) {
                    case 'search' :
                    case 'detail' :
                        $this->mr = array_merge($bbs, $this->mr);
                        $this->{$arguments[1]}();
                        break;

                    case 'save' :
                    case 'comment' :
                        if (!isset($_POST))
                            $this->warning_msg('wrong_pid');
                        else
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
                            } elseif (empty($this->sess_cus))
                                $this->warning_msg('restrict_access');
                            else
                                $this->{$arguments[1]}();
                            break;
                        }

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
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/blog/list');

        //paging
        $this->bbs_model->search($this->search);

        $this->bbs_model->set_query('where', 'bbs_status', 1);
        $total_rows = count($this->bbs_model->get(FALSE, $this->page_id, '', $this->get_client_lang()));

        $this->pagination = new Pagination(array(
                    'base_url' => 'blog/pid/' . $this->page_id . '/search',
                    'uri_segment' => 'page',
                    'total_items' => $total_rows,
                    'items_per_page' => $this->site['config']['CLIENT_NUM_LINE'],
                    'style' => 'punbb',
                ));
        $this->bbs_model->set_limit($this->pagination->items_per_page, $this->pagination->sql_offset); // LIMIT query
        //run sql		
        $this->bbs_model->search($this->search);

        $this->bbs_model->set_query('where', 'bbs_status', 1);
        $mlist = $this->bbs_model->get(FALSE, $this->page_id, '', $this->get_client_lang());

        for ($i = 0; $i < count($mlist); $i++) {
            if (empty($mlist[$i]['bbs_date_modified']))
                $mlist[$i]['bbs_date'] = $mlist[$i]['bbs_date_created'];
            else
                $mlist[$i]['bbs_date'] = $mlist[$i]['bbs_date_modified'];

            $mlist[$i]['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], $mlist[$i]['bbs_date']);
            $mlist[$i]['bbs_content'] = text::limit_words(strip_tags($mlist[$i]['bbs_content']), 50);

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

        $this->session->set_flash('sess_blog', $this->search); // session important
        //assign
        $this->template->content->mlist = $mlist;
        $this->template->content->mr = $this->mr;
    }

    private function search() {
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

    private function detail() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/blog/detail');

        if (empty($this->mr['bbs_date_modified']))
            $this->mr['bbs_date'] = $this->mr['bbs_date_created'];
        else
            $this->mr['bbs_date'] = $this->mr['bbs_date_modified'];

        $this->mr['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], $this->mr['bbs_date']);
        //$mr['bbs_content'] = text::limit_words($mr['bbs_content'], 50);	

        $comment_model = new Comment_Model();
        $list_com = $comment_model->get_com($this->mr['bbs_id']);

        for ($i = 0; $i < count($list_com); $i++) {
            $list_com[$i]['comment_time'] = date($this->site['config']['FORMAT_LONG_DATE'], $list_com[$i]['comment_time']);
            $list_com[$i]['comment_content'] = $list_com[$i]['comment_content'];
        }
        $this->template->content->list_comment = $list_com;

        $this->template->content->mr = $this->mr;
    }

    private function create() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/blog/frm');

        $this->template->content->mr = $this->mr;
    }

    private function edit() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/blog/frm');

        $this->template->content->mr = $this->mr;
    }

    private function _get_frm_valid() {
        $form = array(
            'txt_title' => '',
            'txt_content' => ''
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

            foreach ($errors as $id => $name)
                if ($name)
                    $str_error.='<br>' . $name;
            $this->session->set_flash('error_msg', $str_error);

            if (empty($form['hd_id'])) // create new bbs
                url::redirect('blog/create');
            else // edit bbs
                url::redirect('blog/edit/id/' . $form['hd_id']);
        }

        url::redirect('blog/search/pid/' . $this->mr['page_id']);
        die();
    }

    private function save() {
        $bbs_id = $this->input->post('hd_id');

        $frm_bbs = $this->_get_frm_valid();

        $set = array(
            'bbs_page_id' => $this->page_id,
            'bbs_author' => $this->sess_cus['username'],
            'bbs_status' => 1,
            'bbs_title' => $frm_bbs['txt_title'],
            'bbs_content' => $frm_bbs['txt_content']
        );

        if (empty($bbs_id)) { // create new blog
            $set['bbs_date_created'] = time();
            $this->bbs_model->insert($set, FALSE); // insert new bbs

            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_add'));

            url::redirect($this->site['history']['back']);
        } else { // edit bbs
            $set['bbs_id'] = $bbs_id;
            $set['bbs_date_modified'] = time();
            $this->bbs_model->update($this->get_client_lang(), $set);
            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));

            url::redirect($this->site['history']['current']);
        }

        die();
    }

    private function delete() {
        $comment_model = new Comment_Model();

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

    private function comment() {
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

    private function modify_comment($id) {
        $com_pass = $this->input->post('txt_com_pass', '', TRUE);
        $sel_action = $this->input->post('sel_action');
        $com = ORM::factory('comment_orm')->find($id);

        if (md5($com_pass) == $com->comment_password || $this->sess_cus['type'] < 3) { // < 3 : super admin or admin
            if ($sel_action == 'edit') {
                $this->session->set_flash('edit_com_id', $id);

                url::redirect($this->site['history']['back'] . '#comment-' . $id);
            } else {
                $com->delete();
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));

                url::redirect($this->site['history']['back'] . '#blog_comment');
            }
        }
        else
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_com_pass'));

        url::redirect($this->site['history']['back']);
        die();
    }

    private function save_comment($id) {
        $txt_content = $this->input->post('txt_content');
        $com = ORM::factory('comment_orm')->find($id);

        if (empty($txt_content))
            $this->session->set_flash('error_msg', Kohana::lang('comment_validation.txt_content.required'));
        elseif (!empty($com->comment_id)) {
            $com->comment_content = $txt_content;

            $com->save();
            //$this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));

            url::redirect($this->site['history']['current'] . '#comment-' . $id);
        }

        url::redirect($this->site['history']['current']);
        die();
    }

}

?>
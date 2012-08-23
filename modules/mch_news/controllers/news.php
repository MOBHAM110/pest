<?php

class News_Controller extends Template_Controller {

    public $template;

    public function __construct() {
        parent::__construct();
        $this->template = new View('templates/' . $this->site['config']['TEMPLATE'] . '/client/index');
        $this->template->layout = $this->get_MCH_layout(); // init layout for template controller

        /* --------------------------------------get session if exist ------------------------------------------ */
        if ($this->session->get('sess_news')) {
            $this->search = $this->session->get('sess_news');
            $this->session->keep_flash('sess_news');
        }
        else
            $this->search = array('sel_type' => '', 'keyword' => '');
        $this->_get_session_msg();

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
                url::redirect('news/pid/' . $this->page_id);
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
                        if (empty($_POST) && uri::segment('page') === FALSE) {
                            $this->warning_msg('wrong_pid');
                        } else {
                            $this->mr = array_merge($bbs, $this->mr);
                            $this->search();
                        }
                        break;
                    case 'detail' : {
                            $this->mr = array_merge($bbs, $this->mr);
                            $this->{$arguments[1]}();
                            break;
                        }
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

                    case 'save' : $this->save();
                        break;

                    case 'delete_image' : {
                            $this->mr = array_merge($bbs, $this->mr);

                            if (!$bbs_id)
                                $this->warning_msg('wrong_pid');
                            elseif (!empty($this->sess_cus) && $this->sess_cus['level'] > $this->mr['page_write_permission']
                                    && $this->mr['bbs_author'] != $this->sess_cus['username']) {
                                $this->warning_msg('restrict_access');
                            } elseif (empty($this->sess_cus))
                                $this->warning_msg('restrict_access');
                            break;
                        }

                    case 'download' :
                        $this->download(isset($arguments[2]) ? $arguments[2] : '');
                        break;

                    default : $this->warning_msg('wrong_pid');
                }
            }
        }
        else {
            $this->warning_msg('wrong_pid');
        }
    }

    public function detail() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/news/detail');

        $this->bbs_model->search($this->search);
        $this->db->limit($this->site['config']['CLIENT_NUM_LINE']);
        $this->bbs_model->set_query('where', 'bbs_status', 1);
        $mlist = $this->bbs_model->get(FALSE, $this->page_id, '', $this->get_client_lang());

        for ($i = 0; $i < count($mlist); $i++) {
            if (empty($mlist[$i]['bbs_date_created']))
                $mlist[$i]['bbs_date'] = $mlist[$i]['bbs_date_modified'];
            else
                $mlist[$i]['bbs_date'] = $mlist[$i]['bbs_date_created'];

            $list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $mlist[$i]['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();

            $mlist[$i]['bbs_file'] = empty($list_file[0]->bbs_file_name) ? '' : $list_file[0]->bbs_file_name;

            $mlist[$i]['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], $mlist[$i]['bbs_date']);
            $mlist[$i]['bbs_content'] = $mlist[$i]['bbs_content'];
            if (isset($this->mr['bbs_id']) && $this->mr['bbs_id'] == $mlist[$i]['bbs_id']) {
                $this->mr = array_merge($this->mr, $mlist[$i]);
                $this->list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();
            }
        }

        $this->session->set_flash('sess_news', $this->search);

        if (!isset($this->mr['bbs_id']) && !empty($mlist)) {
            $this->mr = array_merge($this->mr, $mlist[0]);
            $this->list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();
        }
        $this->id_menu = $this->page_id;

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

    private function _show_list() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/news/list');

        $this->bbs_model->search($this->search);

        $this->bbs_model->set_query('where', 'bbs_status', 1);
        $total_rows = count($this->bbs_model->get(FALSE, $this->page_id, '', $this->get_client_lang()));

        $this->pagination = new Pagination(array(
                    'base_url' => 'news/pid/' . $this->page_id . '/search',
                    'uri_segment' => 'page',
                    'total_items' => $total_rows,
                    'items_per_page' => $this->site['config']['CLIENT_NUM_LINE'],
                    'style' => 'punbb',
                ));
        $this->bbs_model->set_limit($this->pagination->items_per_page, $this->pagination->sql_offset);

        $this->bbs_model->search($this->search);

        $this->bbs_model->set_query('where', 'bbs_status', 1);
        $mlist = $this->bbs_model->get(FALSE, $this->page_id, '', $this->get_client_lang());

        for ($i = 0; $i < count($mlist); $i++) {
            if (empty($mlist[$i]['bbs_date_created']))
                $mlist[$i]['bbs_date'] = $mlist[$i]['bbs_date_modified'];
            else
                $mlist[$i]['bbs_date'] = $mlist[$i]['bbs_date_created'];

            $list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $mlist[$i]['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();

            $mlist[$i]['bbs_file'] = empty($list_file[0]->bbs_file_name) ? '' : $list_file[0]->bbs_file_name;

            $mlist[$i]['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], $mlist[$i]['bbs_date']);
            $mlist[$i]['bbs_content'] = text::limit_words(strip_tags($mlist[$i]['bbs_content']), 30);

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

            if (isset($this->mr['bbs_id']) && $this->mr['bbs_id'] == $mlist[$i]['bbs_id']) {
                $this->mr = array_merge($this->mr, $mlist[$i]);
                $this->list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();
            }
        }

        $this->session->set_flash('sess_news', $this->search);

        if (!isset($this->mr['bbs_id']) && !empty($mlist)) {
            $this->mr = array_merge($this->mr, $mlist[0]);
            $this->list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();
        }
        $this->id_menu = $this->page_id;

        $this->template->content->mlist = $mlist;
        $this->template->content->mr = $this->mr;
    }

    public function create() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/news/frm');

        $this->template->content->mr = $this->mr;
        $this->template->content->list_file = '';
    }

    public function edit() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/news/frm');

        $list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();

        $this->template->content->mr = $this->mr;
        $this->template->content->list_file = $list_file;
    }

    private function _get_frm_valid() {
        $file_type = ORM::factory('file_type_orm')->where('file_type_detail', 'image')->find();
        $form = array(
            'txt_title' => '',
            'txt_content' => ''
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
                    $str_error.='<br>' . $name;
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
            'bbs_author' => $this->sess_cus['username'],
            'bbs_status' => 1,
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

            if ($this->bbs_model->update($this->get_client_lang(), $set))
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_save') . '<br>';
            else
                $msg['error'] .= Kohana::lang('errormsg_lang.error_data_save') . '<br>';
        }

        for ($i = 1; $i <= 3; $i++) {
            if (isset($frm_news['attach_file' . $i]['error']) && $frm_news['attach_file' . $i]['error'] == 0) {
                $path_news = DOCROOT . 'uploads/news/';

                $path_file = upload::save('attach_file' . $i, NULL, $path_news);
                $file_name = basename($path_file);

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

        if ($this->input->post('hd_id')) // edit news
            url::redirect($this->site['history']['current']);
        else // create news
            url::redirect('news/pid/' . $this->page_id);

        die();
    }

    public function delete_image($bbs_file_id = '') {
        if ($this->_delele_bbs_file('news', $bbs_file_id)) {
            url::redirect($this->site['history']['current']);
            die();
        }

        $this->warning_msg('wrong_pid');
    }

    public function delete() {
        $bbs_file = ORM::factory('bbs_file_orm')->where('bbs_id', $this->mr['bbs_id'])->find_all();

        $path_news = DOCROOT . 'uploads/news/';
        for ($i = 0; $i < count($bbs_file); $i++) {
            if (!empty($bbs_file[$i]->bbs_file_id)) {
                $path_nf = $path_news . $bbs_file[$i]->bbs_file_name;

                if (is_file($path_nf) && file_exists($path_nf))
                    @unlink($path_nf);
                $bbs_file[$i]->delete();
            }
        }

        if ($this->bbs_model->delete($this->mr['bbs_id'], FALSE))
            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));
        else
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_del'));

        url::redirect($this->site['history']['current']);
        die();
    }

}

?>
<?php

class Admin_page_Controller extends Template_Controller {

    public $template = 'admin/index';

    public function __construct() {
        parent::__construct();

        $this->page_model = new Page_Model();
        $this->pl_model = new Page_Layout_Model();
        $this->header_model = new Header_Model();
        $this->banner_model = new Banner_Model();

        $this->_get_submit();
    }

    public function __call($method, $arguments) {
        if ($method !== 'index')
            $this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');

        $this->view();
    }

    private function _get_submit() {
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
            $this->mr['page_title'] = $indata['txt_title'];
            $this->mr['page_content'] = $indata['txt_content'];
            $this->mr['page_type_id'] = $indata['sel_type'];

            if (isset($indata['txt_content_url']))
                $this->mr['page_content'] = $indata['txt_content_url'];
            if (isset($indata['txt_content_form']))
                $this->mr['page_content'] = $indata['txt_content_form'];
        }
    }

    public function view($type = 'mptt') {
        $this->_showlist($type);
    }

    private function _showlist($type = 'mptt') {
        $this->template->content = new View('admin_page/list');

        $this->mr['view_list'] = $type;
        $order = ($type == 'list') ? 'page_order' : '';

        $this->template->content->mr = $this->mr;
        $this->template->content->mlist = $this->page_model->get_page_lang($this->get_admin_lang(), '', '', '', 'pd.page_title', $order);
    }

    protected function _show_sel_page_type_detail($mr) {
        $str = '';
        $normal_page = Model::factory('page_type')->get_all('', 0, 1, 'obj');

        // Normal page
        foreach ($normal_page as $value) {
            if (!empty($mr) && $mr['page_type_id'] == $value->page_type_id) {
                $str .= '<option value="' . $mr['page_type_id'] . '" selected>' . ORM::factory('page_type_orm', $mr['page_type_id'])->page_type_name . '</option>';
            }
            else
                $str .= '<option value="' . $value->page_type_id . '">' . $value->page_type_name . '</option>';
        }

        return $str;
    }

    protected function show_sel_page($page_id = '', $show_root_page = TRUE) {
        $str = '';

        // get descendants of root		
        $sel_page = 'page.page_id,page_left,page_level';

        $gpl = Gpl_Model::get();
        $result = $this->page_model->get_page_lang($this->get_admin_lang(), '', $sel_page, '', '');

        $selected = '';

        if (!empty($page_id))
            $parent = ORM::factory('page_mptt', $page_id)->__get('parent');

        // show root page
        if ($show_root_page)
            $str .= '<option value="' . $gpl['page_id'] . '" ' . $selected . '>' . Kohana::lang('page_lang.lbl_root_page') . '</option>';

        foreach ($result as $value) {
            $expand = '';
            for ($i = 1; $i < $value['page_level']; $i++)
                $expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            $expand .= '&nbsp;&nbsp;&nbsp;|----';

            if (!empty($page_id) && $parent->page_id == $value['page_id']) {
                $str .= '<option value="' . $value['page_id'] . '" selected>' . $expand . $value['page_title'] . '</option>';
            } else {
                $disabled = '';
                if (!empty($page_id)) {
                    $cur_page = ORM::factory('page_mptt', $page_id);
                    $sel_menu = ORM::factory('page_mptt', $value['page_id']);
                    if ($sel_menu->is_descendant($cur_page) || $page_id == $value['page_id'])
                        $disabled = 'disabled="disabled"';
                }

                $str .= '<option value="' . $value['page_id'] . '" ' . $disabled . '>' . $expand . $value['page_title'] . '</option>';
            }
        }

        return $str;
    }

    public function chop() { // CHange Page Order
        $up_down = $this->uri->segment('chop', '');
        $page_id = $this->uri->segment('id', ''); // id of current page
        if (!empty($up_down) && !empty($page_id)) {
            if ($up_down == 'up') { // move current page to previous page
                $prev_page = ORM::factory('page_mptt', $page_id)->prev_sibling();
                ORM::factory('page_mptt', $page_id)->move_to_prev_sibling($prev_page);
            } else { //down	// move current page to next page
                $next_page = ORM::factory('page_mptt', $page_id)->next_sibling();
                ORM::factory('page_mptt', $page_id)->move_to_next_sibling($next_page);
            }

            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
        }

        url::redirect('admin_page');
        die();
    }

    public function show_mptt_order($table_name, $mptt_order, $node_id) { // here is page id
        $str = '';
        $node = ORM::factory($table_name, $node_id);
        $node_siblings = $node->__get('siblings')->count(); // count siblings of current node
        // show 'down' when current node is first child and must have siblings (num of its siblings > 0)
        if ($node->is_first_child() && $node_siblings > 0)
            $str .= '<a href="' . url::base() . uri::segment(1) . '/' . $mptt_order . '/down/id/' . $node_id . '"><img src="' . url::base() . 'themes/admin/pics/icon_down.png"><a>';
        // show 'up' when current node is last child and must have siblings (num of its siblings > 0)	
        elseif ($node->is_last_child() && $node_siblings > 0)
            $str .= '<a href="' . url::base() . uri::segment(1) . '/' . $mptt_order . '/up/id/' . $node_id . '"><img src="' . url::base() . 'themes/admin/pics/icon_up.png"><a>';
        // show 'down up' when current node have siblings (num of siblings > 0)
        elseif ($node_siblings > 0) {
            $str .= '<a href="' . url::base() . uri::segment(1) . '/' . $mptt_order . '/down/id/' . $node_id . '"><img src="' . url::base() . 'themes/admin/pics/icon_down.png"><a>';
            $str .= '<a href="' . url::base() . uri::segment(1) . '/' . $mptt_order . '/up/id/' . $node_id . '"><img src="' . url::base() . 'themes/admin/pics/icon_up.png"><a>';
        }
        // otherwise show nothing
        return $str;
    }

    public function create() {
        $this->template->content = new View('admin_page/frm');
        $this->template->content->title = Kohana::lang('page_lang.tt_page') . ' -> ' . Kohana::lang('global_lang.lbl_create');
        if (!empty($this->mr))
            $this->template->content->mr = $this->mr;
    }

    public function edit($page_id) {
        $this->template->content = new View('admin_page/frm');
        $mr = $this->page_model->get_page_lang($this->get_admin_lang(), $page_id);

        if (!empty($this->mr))
            $mr = array_merge($mr, $this->mr);
        $this->template->content->set(array(
            'title' => $mr['page_title'] . ' -> ' . Kohana::lang('global_lang.lbl_edit'),
            'mr' => $mr
        ));
    }

    public function _check_type($array, $field) {
        if (Page_Model::type_exist($array[$field])) {
            $array->add_error($field, '_check_type');
        }
    }

    public function _check_form_exist($array, $field) {
        if (Kohana::find_file('views', 'templates/' . $this->site['config']['TEMPLATE'] . '/form/' . $array[$field]) === FALSE) {
            $array->add_error($field, '_check_form_exist');
        }
    }

    private function _get_frm_valid() {
        $hd_id = $this->input->post('hd_id');
        $sel_type = $this->input->post('sel_type');

        $form = $this->page_model->get_frm();
        $errors = $form;

        if ($_POST) {
            $post = new Validation($_POST);

            $post->pre_filter('trim', TRUE);
            $post->add_rules('txt_title', 'required');

            if (ORM::factory('page_type_orm', $sel_type)->page_type_name == 'form')
                $post->add_callbacks('txt_content_form', array($this, '_check_form_exist'));

            if ($post->validate()) {
                $form = arr::overwrite($form, $post->as_array());
                return $form;
            } else {
                $form = arr::overwrite($form, $post->as_array());  // Retrieve input data
                $this->session->set_flash('input_data', $form);  // Set input data in session

                $errors = arr::overwrite($errors, $post->errors('page_validation'));
                $str_error = '';

                foreach ($errors as $id => $name)
                    if ($name)
                        $str_error.= $name.'<br>';
                $this->session->set_flash('error_msg', $str_error);

                if ($hd_id)
                    url::redirect('admin_page/edit/' . $hd_id);
                else
                    url::redirect('admin_page/create');
                die();
            }
        }
    }

    public function save() {
        $hd_id = $this->input->post('hd_id'); // page_id		

        $record = $this->_get_frm_valid();

        $set = array
            (
            'page_id' => $hd_id,            
            'page_title' => $record['txt_title'],
            'page_title_seo' => $record['txt_title_seo'],
            'page_keyword' => $record['txt_keyword'],
            'page_description' => $record['txt_description'],
            'page_content' => $record['txt_content'],
            'page_type_id' => $record['sel_type'],
            'page_parent' => $record['sel_parent'],
            'page_status' => $record['sel_status']
        );

        $page_type_name = ORM::factory('page_type_orm', $set['page_type_id'])->page_type_name;

        switch ($page_type_name) {
            case 'menu' : $set['page_content'] = $record['txt_content_url'];
                $set['page_target'] = $record['sel_target'];
                break;
            case 'form' : $set['page_content'] = $record['txt_content_form'];
                break;
            case 'tab' : $set['page_content'] = '';
                break;
        }

        if (empty($set['page_title']))
            unset($set['page_title'], $set['page_content']);

        if (empty($hd_id)) { // if save a new page
            if ($this->page_model->insert($set))
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_add'));
            else
                $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_add'));
        }
        else {  // if edit a old page

            if ($this->page_model->update($this->get_admin_lang(), $set))
                $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
            else
                $this->session->set_flash('warning_msg', Kohana::lang('errormsg_lang.war_data_update'));
        }

        if(!empty($_POST['hd_save_add']))
            url::redirect('admin_page/create');
        elseif (empty($hd_id))
            url::redirect('admin_page');
        else
            url::redirect('admin_page/edit/' . $hd_id);
        die();
    }

    public function delete($id) { // @param $id page_id | array page_id
        if (!$this->permisController('delete')) {
            $this->session->set_flash('error_msg', Kohana::lang('global_lang.lbl_authorized'));
            url::redirect(uri::segment(1));
            die();
        } else {
            $page = $this->page_model->get_page_lang($this->get_admin_lang(), $id, '', '', NULL);
            // page is page special type
            if ($page['page_type_special'] == 1) {
                Session::set_flash('error_msg', Kohana::lang('errormsg_lang.error_del_ps'));
                url::redirect('admin_page');
                die();
            }
            // page has children
            if (ORM::factory('page_mptt', $id)->has_children()) {
                Session::set_flash('error_msg', Kohana::lang('errormsg_lang.error_page_parent'));
                url::redirect('admin_page');
                die();
            }
            // page has components
            if (!empty(ORM::factory('bbs_orm')->where('bbs_page_id', $id)->find()->bbs_id)) {
                Session::set_flash('error_msg', Kohana::lang('errormsg_lang.error_page_component'));
                url::redirect('admin_page');
                die();
            }
            $this->page_model->delete($id);
            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));

            url::redirect('admin_page');
            die();
        }
    }

    public function setstatus($id) {
        $result = ORM::factory('page_orm')->find($id);
        $result->page_status = abs($result->page_status - 1);
        $result->save();

        $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_update'));

        url::redirect('admin_page');
        die();
    }

    public function change_permission($id, $per_type) {
        $result = ORM::factory('page_orm')->find($id);
        $read = $result->page_read_permission;
        $write = $result->page_write_permission;

        if ($per_type == 'read') {
            if (!($read == 1 && $this->sess_admin['level'] > 1)) {
                // super admin
                if ($this->sess_admin['level'] == 1)
                    $read = $read == 5 ? 1 : ($read + 1);
                else
                    $read = $read == 5 ? 2 : ($read + 1);
                $write = ($read < $write) ? $read : $write;
            }
        }
        else {
            if (!($write == 1 && $this->sess_admin['level'] > 1)) {
                // super admin
                if ($this->sess_admin['level'] == 1)
                    $write = $write == 5 ? 1 : ($write + 1);
                else
                    $write = $write == 5 ? 2 : ($write + 1);
                $read = ($read < $write) ? $write : $read;
            }
        }

        $result->page_read_permission = $read;
        $result->page_write_permission = $write;
        $result->save();

        $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_status_change'));

        url::redirect('admin_page');
        die();
    }

    public function move_to() { //($source_pid, $dest_pid)

        $method = $this->input->post('sel_method_move');
        $chk_id = $this->input->post('chk_id');
        $dest_pid = $this->input->post('sel_move');

        if (empty($chk_id) || !is_array($chk_id) || !valid::digit($dest_pid)) {
            $this->session->set_flash('warning_msg', Kohana::lang('errormsg_lang.error_parameter'));

            url::redirect(uri::segment(1));
            die();
        }

        $page_dest = ORM::factory('page_mptt', $dest_pid);

        foreach ($chk_id as $i => $pid) {
            $page_source = ORM::factory('page_mptt', $pid);

            if ($method == 'next_sibling' || $method == 'prev_sibling') {
                if ($page_dest->is_root() // if page destination is page root
                        || $page_dest->page_id == $page_source->page_id // OR page destination is page source
                        || $page_dest->is_child($page_source) // OR page destination is page source's child
                        || $page_dest->is_descendant($page_source) // OR page destination is page source's descendant
                ) {
                    continue; // pass
                }
            } elseif ($method == 'first_child' || $method == 'last_child') {

                if ($page_dest->page_id == $page_source->page_id // if page destination is page source
                        || $page_dest->is_child($page_source) // OR page destination is page source's child
                        || $page_dest->is_descendant($page_source) // OR page destination is page source's descendant
                ) {
                    continue; // pass
                }
            }
            else
                break;

            $page_source->{'move_to_' . $method}($page_dest);
        }

        $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_update'));

        url::redirect(uri::segment(1));
        die();
    }

    public function save_menu_order() {
        $list_page = ORM::factory('page_orm')->where('page_left >', 1)->find_all()->as_array();

        foreach ($list_page as $page) {
            $txt_order_page = $this->input->post('txt_order_' . $page->page_id);

            if (!valid::digit($txt_order_page) || $txt_order_page > (count($list_page) * 3))
                continue;

            $page->page_order = $txt_order_page;
            $page->save();
        }

        $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_update'));

        url::redirect(uri::segment(1) . '/view/list');
        die();
    }

}

?>
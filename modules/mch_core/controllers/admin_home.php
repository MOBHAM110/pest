<?php

class Admin_home_Controller extends Template_Controller {

    public $template = 'admin/index';

    public function __construct() {
        parent::__construct();

        $this->_get_session_msg();
    }

    public function __call($method, $arguments) {
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
    }

    public function index() {
        $this->template->content = new View('admin_home/index');

        $page_model = new Page_Model();

        $home_pid = $page_model->get_page_type(array('bbs', 'blog', 'album', 'news', 'general', 'tab'));

        if (empty($home_pid))
            $list_page = $home_pid;
        else {
            $page_model->set_query('where', 'page_status', 1);
            $list_page = $page_model->get_page_lang($this->get_admin_lang(), $home_pid);
        }

        $this->template->content->list_page = $list_page;
    }

    public function save() {
        $num_row_def = $this->site['config']['HOME_NUM_REC_DEF'];
        $exception = array('general', 'tab');

        for ($i = 1; $i <= $this->site['config']['HOME_NUM_ROW']; $i++) {
            for ($j = 1; $j <= $this->site['config']['HOME_NUM_COL']; $j++) {
                $txt_num = $this->input->post('txt_row_' . $i . '_' . $j);
                $sel_page = $this->input->post('sel_page_' . $i . '_' . $j);
                $page = Model::factory('page')->get_page_lang($this->get_admin_lang(), $sel_page);

                if (isset($page['page_type_name']) && array_search($page['page_type_name'], $exception) === FALSE) {
                    $num_bbs = count(ORM::factory('bbs_orm')->where('bbs_page_id', $sel_page)->find_all()->as_array());

                    if (!valid::digit($txt_num) || $txt_num < 1 || $txt_num > $num_bbs)
                        $txt_num = ($num_row_def > $num_bbs) ? $num_bbs : $num_row_def;
                }

                $home = ORM::factory('home_orm');
                $home->where('home_row', $i);
                $home->where('home_col', $j);

                $result = $home->find();
                $result->page_id = $sel_page;
                $result->num_row = $txt_num;
                $result->save();
            }
        }

        $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_update'));

        url::redirect(uri::segment(1));
        die();
    }

    public function add_row() {
        $num_row = $this->site['config']['HOME_NUM_ROW'];
        $num_col = $this->site['config']['HOME_NUM_COL'];

        for ($i = 0; $i < $num_col; $i++) {
            $home = ORM::factory('home_orm');
            $home->page_id = -1;
            $home->home_row = $num_row + 1;
            $home->home_col = $i + 1;
            $home->save();
        }

        // update num row in Site table
        Model::factory('configuration')->update_value('HOME_NUM_ROW', $num_row + 1);

        $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_add'));

        url::redirect(uri::segment(1));
        die();
    }

    public function add_col() {
        $num_col = $this->site['config']['HOME_NUM_COL'];

        if (count(ORM::factory('home_orm')->find_all()->as_array())) {
            // update num row in Site table
            Model::factory('configuration')->update_value('HOME_NUM_COL', $num_col + 1);

            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_add'));
        }
        else
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));

        url::redirect('admin_home');
        die();
    }

    public function del_row() {
        $num_row = $this->site['config']['HOME_NUM_ROW'];

        $home = ORM::factory('home_orm')->where('home_row', $num_row)->delete_all();

        // update num row in Site table
        Model::factory('configuration')->update_value('HOME_NUM_ROW', $num_row - 1);

        if (empty($home->home_id))
            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));
        else
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_del'));

        url::redirect(uri::segment(1));
        die();
    }

    public function del_col() {
        $num_col = $this->site['config']['HOME_NUM_COL'];

        if (count(ORM::factory('home_orm')->find_all()->as_array())) {
            // update num row in Site table
            Model::factory('configuration')->update_value('HOME_NUM_COL', $num_col - 1);

            $this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));
        }
        else
            $this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));

        url::redirect(uri::segment(1));
        die();
    }

}

?>
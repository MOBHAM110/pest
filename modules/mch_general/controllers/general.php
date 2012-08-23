<?php

class General_Controller extends Template_Controller {

    public $template;

    public function __construct() {
        parent::__construct();
        $this->template = new View('templates/' . $this->site['config']['TEMPLATE'] . '/client/index');
        $this->template->layout = $this->get_MCH_layout(); // init layout for template controller        
        //Init session 
        $this->_get_session_msg();

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
    }

    public function __call($method, $arguments) {
        if (!empty($this->warning)) {
            $this->warning_msg($this->warning);
        } elseif ($method === 'pid') {
            if (!isset($arguments[1]))
                $this->_show();
            else
                $this->warning_msg('wrong_pid');
        }
        else {
            $this->warning_msg('wrong_pid');
        }
    }

    private function _show() {
        $page = $this->page_model->get_page_lang($this->get_client_lang(), $this->page_id, NULL, NULL);

        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/general/detail');

        $this->template->content->mr = $page;
    }
}
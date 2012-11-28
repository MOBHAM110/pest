<?php

class Home_Controller extends Template_Controller {

    public $template;

    public function __construct() {
        parent::__construct();
        $this->template = new View('templates/' . $this->site['config']['TEMPLATE'] . '/client/index');
        $this->template->layout = $this->get_MCH_layout();
        //$this->init_template_layout(); 
        $this->rss_model = new Rss_Model();
        $this->Bbs_model = new Bbs_Model();
        // Init session 
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
        } else {
            switch ($method) {
                case 'index' : $this->index();
                    break;

                default : $this->warning_msg('wrong_pid');
            }
        }
    }

    private function index() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/home/index');

        $bbs_model = new Bbs_Model();
        $page_model = new Page_Model();
        $mlist = array();

        for ($i = 1; $i <= $this->site['config']['HOME_NUM_ROW']; $i++) {
            for ($j = 1; $j <= $this->site['config']['HOME_NUM_COL']; $j++) {
                $home = ORM::factory('home_orm');
                $home->where('home_row', $i);
                $home->where('home_col', $j);

                $result = $home->find();
                if ($result->page_id !== -1) {
                    $list_bbs = array();
                    $mlist[$i][$j] = $page_model->get_page_lang($this->get_client_lang(), $result->page_id);

                    if (empty($mlist[$i][$j])) {
                        unset($mlist[$i][$j]);
                        continue; // page not exist
                    } elseif ((!empty($this->sess_cus) && $this->sess_cus['level'] > $mlist[$i][$j]['page_read_permission'])
                            || (empty($this->sess_cus) && $mlist[$i][$j]['page_read_permission'] < 5)) {
                        unset($mlist[$i][$j]);
                        continue; // page has restrict access
                    }

                    switch ($mlist[$i][$j]['page_type_name']) {
                        case 'bbs' :
                            $bbs_model->set_query('where', 'bbs_status', 1);
                            $bbs_model->limit_mod($result->num_row);
                            $list_bbs = $bbs_model->get(TRUE, $result->page_id, '', $this->get_client_lang());
                            for ($k = 0; $k < count($list_bbs); $k++) {
                                if (empty($list_bbs[$k]['bbs_date_created']))
                                    $list_bbs[$k]['bbs_date'] = $list_bbs[$k]['bbs_date_modified'];
                                else
                                    $list_bbs[$k]['bbs_date'] = $list_bbs[$k]['bbs_date_created'];

                                $list_bbs[$k]['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], $list_bbs[$k]['bbs_date']);
                            }
                            break;

                        case 'blog' :
                            $bbs_model->set_query('where', 'bbs_status', 1);
                            $bbs_model->limit_mod($result->num_row);
                            $list_bbs = $bbs_model->get(FALSE, $result->page_id, '', $this->get_client_lang());
                            for ($k = 0; $k < count($list_bbs); $k++) {
                                if (empty($list_bbs[$k]['bbs_date_created']))
                                    $list_bbs[$k]['bbs_date'] = $list_bbs[$k]['bbs_date_modified'];
                                else
                                    $list_bbs[$k]['bbs_date'] = $list_bbs[$k]['bbs_date_created'];

                                $list_bbs[$k]['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], $list_bbs[$k]['bbs_date']);
                                $list_bbs[$k]['bbs_content'] = text::limit_words(strip_tags($list_bbs[$k]['bbs_content']), 30);
                            }
                            break;

                        case 'news' :
                            $bbs_model->set_query('where', 'bbs_status', 1);
                            $bbs_model->limit_mod($result->num_row);
                            $list_bbs = $bbs_model->get(FALSE, $result->page_id, '', $this->get_client_lang());
                            for ($k = 0; $k < count($list_bbs); $k++) {
                                if (empty($list_bbs[$k]['bbs_date_created']))
                                    $list_bbs[$k]['bbs_date'] = $list_bbs[$k]['bbs_date_modified'];
                                else
                                    $list_bbs[$k]['bbs_date'] = $list_bbs[$k]['bbs_date_created'];

                                $list_bbs[$k]['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], $list_bbs[$k]['bbs_date']);
                                $list_bbs[$k]['bbs_content'] = text::limit_words(strip_tags($list_bbs[$k]['bbs_content']), 30);

                                $list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $list_bbs[$k]['bbs_id'])->find_all()->as_array();
                                if (isset($list_file[0]->bbs_file_name))
                                    $list_bbs[$k]['bbs_file'] = $list_file[0]->bbs_file_name;
                            }
                            break;

                        case 'album' :
                            $bbs_model->set_query('where', 'bbs_status', 1);
                            $bbs_model->limit_mod($result->num_row);
                            $list_bbs = $bbs_model->get(FALSE, $result->page_id, '', $this->get_client_lang());
                            for ($k = 0; $k < count($list_bbs); $k++) {
                                $list_bbs[$k]['bbs_title_full'] = $list_bbs[$k]['bbs_title'];
                                $list_bbs[$k]['bbs_title'] = text::limit_chars($list_bbs[$k]['bbs_title'], 15);

                                if (empty($list_bbs[$k]['bbs_date_created']))
                                    $list_bbs[$k]['bbs_date'] = $list_bbs[$k]['bbs_date_modified'];
                                else
                                    $list_bbs[$k]['bbs_date'] = $list_bbs[$k]['bbs_date_created'];

                                $list_bbs[$k]['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], $list_bbs[$k]['bbs_date']);
                                $list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $list_bbs[$k]['bbs_id'])->find_all()->as_array();
                                if (isset($list_file[0]->bbs_file_name))
                                    $list_bbs[$k]['bbs_file'] = $list_file[0]->bbs_file_name;
                            }
                            break;

                        case 'tab' :
                            $arr_pid = explode('|', $mlist[$i][$j]['page_content']);

                            foreach ($arr_pid as $k => $pid) {
                                $list_bbs[$k] = $page_model->get_page_lang($this->get_client_lang(), $pid);
                                if ($list_bbs[$k]['page_type_name'] != 'general') {
                                    $bbs_model->set_query('where', 'bbs_status', 1);
                                    if ($list_bbs[$k]['page_type_name'] == 'bbs') {
                                        $bbs_model->limit_mod(3);
                                    } else {
                                        $bbs_model->limit_mod($this->site['config']['HOME_NUM_REC_DEF']);
                                    }
                                    $list_bbs[$k]['list_bbs'] = $bbs_model->get(FALSE, $pid, '', $this->get_client_lang());
                                    $list_bbs[$k]['page_is_tab'] = 1;
                                    foreach ($list_bbs[$k]['list_bbs'] as $t => $list) {
                                        if (empty($list['bbs_date_created']))
                                            $list_bbs[$k]['list_bbs'][$t]['bbs_date'] = $list['bbs_date_modified'];
                                        else
                                            $list_bbs[$k]['list_bbs'][$t]['bbs_date'] = $list['bbs_date_created'];

                                        $list_bbs[$k]['list_bbs'][$t]['bbs_date'] = date($this->site['config']['FORMAT_SHORT_DATE'], $list_bbs[$k]['list_bbs'][$t]['bbs_date']);
                                        $list_bbs[$k]['list_bbs'][$t]['bbs_content'] = text::limit_words(strip_tags($list['bbs_content']), 30);

                                        $list_file = Model::factory('bbs_file')->get($list['bbs_id']);
                                        if (isset($list_file[0]['bbs_file_name']))
                                            $list_bbs[$k]['list_bbs'][$t]['bbs_file'] = $list_file[0]['bbs_file_name'];
                                    }
                                }
                            }
                            break;
                    }

                    $mlist[$i][$j]['list_bbs'] = $list_bbs;
                }
                else
                    unset($mlist[$i][$j]);
            }
        }
        $akc_status = Configuration_Model::get_value('ENABLE_AKCOMP');
        if($akc_status==2){
            $rssNews = $this->rss_model->getRss($this->site['config']['RSS_NEWS_URL'], 5); //$rssNews = $this->bbs_model->get(FALSE);            
            $mlist['rssnews'] = $rssNews;
            $rssBlog = $this->rss_model->getRss($this->site['config']['RSS_BLOG_URL'], 5);
            $mlist['rssblog'] = $rssBlog; //var_dump($mlist['rssnews']); die();
        }        
        $this->template->content->mlist = $mlist;//echo Kohana::debug($mlist);die();		
    }
}

?>
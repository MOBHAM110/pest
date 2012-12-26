<?php

class Rss_Model extends Model {

    public function __construct() {
        parent::__construct();
        //require Kohana::find_file('vendor/feed_xml', 'xml');
        $this->site = Model::factory('site')->get();
        $config = Model::factory('configuration')->get_mod();
        foreach ($config as $conf) {
            $key = $conf['configuration_key'];
            $this->site['config'][$key] = $conf['configuration_value'];
        }
    }
    
    public function getRss($url = '', $limit = false, $page = 1) {
        $rss = array();
        if(empty($url))
            return $rss;
        include_once('simplepie/autoloader.php');
        include_once('simplepie/idn/idna_convert.class.php');
        $feed = new SimplePie();
        //if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
        //$url = stripslashes($url);
        $feed->set_feed_url($url);
        //$feed->set_input_encoding($url);
        $feed->force_feed(true);
        $success = $feed->init();
        $feed->handle_content_type();
        $rss = array(); //var_dump($feed);
        if ($feed->error()) echo $feed->error();
        if($success){
        foreach($feed->get_items() as $key => $item){
            if ($limit){
                if($key >= $limit*$page)
                    break;
				if($key < $limit*($page - 1))
					continue;
			}
            $temp = array(
                'id' => $key,
                'author' => 'Rss',
                'title' => $item->get_title(),
                'content' => $item->get_content(),
                'pubDate' => $item->get_date($this->site['config']['FORMAT_SHORT_DATE'])
            );
            $rss[] = $temp;
        }
        }
        return $rss;
    }
}

?>
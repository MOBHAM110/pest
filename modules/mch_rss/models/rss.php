<?php
class Rss_Model extends Model
{
    public function __construct()
	{
		parent::__construct();
        require Kohana::find_file('vendor/feed_xml','xml');
    }
    
    public function getRss($url = '',$limit = false){
        $dom = new MyDOMDocument;			
        @$dom->load($url);
        @$arr = $dom->toArray();
        
//        if(isset($arr) && empty($arr))
//        {
//            $path = APPPATH.'vendor/feed_xml/feed_rss.xml';
//            $fi = fopen($path,'r+');
//            $html_content = file_get_contents($path);
//            $dom->load($path);
//            $arr = $dom->toArray();
//        }
        $rss = array();
        if(isset($arr['rss']['channel']['item']) && is_array($arr['rss']['channel']['item']))
            foreach($arr['rss']['channel']['item'] as $key=>&$item){
                if(!$limit) if($key >= 5) break;
                $item['id'] = $key;
                if(!isset($item['content']))
                    $item['content'] = @$item['description'];
                $rss[] = $item;
            }
            
        return $rss;
        
    }
}
?>
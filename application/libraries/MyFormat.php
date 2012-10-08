<?php
class MyFormat_Core {
	
    public function __construct()
    {  
    }
    
    //Currency
	public static function format_currency($val=0, $site_lang=1)
	{
		$f = '';
		//if(!$val) return false;
		if ($val<0)
		{
			$val = abs($val);
			$f = "- ";
		}
		if($val==0) return $val;		
		if($site_lang == 1){
			//format English
			return $f.'$'.number_format($val,2,".",",");
		} elseif($site_lang == 2) {
			//format Vietnam
			return number_format($val,0,",",".").' VND';
		} elseif($site_lang == 3) {
			//format Korean
			return number_format($val,0,".",",").'&#50896;';
		} elseif($site_lang == 4){
			//format Japan
			return '¥'.number_format($val,0,".",",");		
		} else {
			return $val;
		}	
	}	
	//Number
	public static function format_number($val=0, $site_lang=1)
	{
		if(!$val) return false;
		
		if($site_lang == 1 || $site_lang==3 || $site_lang==4){
			//format English
			return number_format($val,2,".",",");
		} elseif($site_lang == 2){
			//format Vietnam
			return number_format($val,0,",",".");
		} else {
			return $val;
		}	
	}
	//Int date
	public static function format_int_date($int_date,$str_format)
	{
		if(!$int_date || !is_numeric($int_date)) return false;
		return date($str_format,$int_date);
	}
	//String date
	public static function format_str_date($str_date,$str_format = 'Y/m/d',$str_sep='/',$h=0,$mi=0,$s=0)
	{
		if(!$str_date) return false;
		
		$arr = explode($str_sep, $str_date);
			
		switch($str_format)
		{
			case 'Y/m/d':	list($y,$m,$d) = $arr;break;
			
			case 'm/d/Y':	list($m,$d,$y) = $arr;break;
			
			case 'n/j/Y':	list($m,$d,$y) = $arr;break;
			
			case 'd/m/Y':	list($d,$m,$y) = $arr;break;		
		}		
		return mktime($h,$mi,$s,$m,$d,$y);
	}
	//String code
	public static function format_code($str_code,$str_format)
	{
		return str_pad($str_code,strlen($str_format),$str_format,STR_PAD_LEFT);
	}
	//Search focus
    public static function format_focus_search($str_search,$str_format)
    {
        $str_temp = substr($str_format,strpos($str_format,$str_search),strlen($str_search));
		return preg_replace('#(?!<.*)(?<!\w)(' .$str_search. ')(?!\w|[^<>]*(?:</s(?:cript|tyle))?>)#is',"<span style='background-color:rgb(255, 255, 0)'>".$str_temp."</span>", $str_format);
    } 
    
    public function title_url($text)
    {
    	$text = html_entity_decode ($text);
    	$text = preg_replace("/(ä|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text);
    	$text = str_replace("ç","c",$text);
    	$text = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $text);
    	$text = preg_replace("/(ì|í|î|ị|ỉ|ĩ)/", 'i', $text);
    	$text = preg_replace("/(ö|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $text);
    	$text = preg_replace("/(ü|ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $text);
    	$text = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $text);
    	$text = preg_replace("/(đ)/", 'd', $text);
        $text = preg_replace("/(0|1|2|3|4|5|6|7|8|9)/", '', $text);
    	//Escape
    	$text = preg_replace("/(Ä|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $text);
    	$text = str_replace("Ç","C",$text);
    	$text = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $text);
    	$text = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $text);
    	$text = preg_replace("/(Ö|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $text);
    	$text = preg_replace("/(Ü|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $text);
    	$text = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $text);
    	$text = preg_replace("/(Đ)/", 'D', $text);
    	
    	//Special string
    	$text = preg_replace("/(!|#|$|%)/", '', $text);
    	$text = preg_replace("/('|'|`|$|>)/", '', $text);
    	$text = preg_replace("'<[\/\!]*?[^<>]*?>'si", '', $text);
        $text = str_replace("?","",$text);
        $text = str_replace(".","",$text);
        $text = str_replace(",","",$text);
        $text = str_replace(";","",$text);
        $text = str_replace("&","",$text);
        $text = str_replace("/","",$text);
        
        $text = trim($text);
    	$text = str_replace("----","-",$text);
    	$text = str_replace("---","-",$text);
    	$text = str_replace("--","-",$text);
    	$text = str_replace(" ","-",$text);
    	return strtolower($text);
    }
    
    public function url_format($url) {

        if(!(strpos($url, "http://") === 0)
        && !(strpos($url, "https://") === 0)) {
            $url = "http://$url";
        }
        return $url;
    }
    
    public function check_is_url($url) {

        if(!(strpos($url, "http://") === 0)
        && !(strpos($url, "https://") === 0)) {
            return 0;
        }
        return 1;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require('seo.php')?>
<title><?php echo isset($this->site['seo_title'])?$this->site['seo_title']:''?></title>
<meta http-equiv="Content-Type" content="text/html; charset='<?php $lang = new Language_Model(); $list_lang = $lang->get_with_active(); ?><?php for($i=0; $i<count($list_lang); $i++){ ?>
<?php echo ($list_lang[$i]['languages_id']==Session::get('sess_client_lang')||$list_lang[$i]['languages_id']==$this->site['lang_id'])?$list_lang[$i]['languages_charset']:''?><?php } ?>'"/>
<meta name="keywords" content="<?php echo isset($this->site['meta_key'])?$this->site['meta_key']:$this->site['meta_key']?>" charset="utf-8" />
<meta name="description" content="<?php echo isset($this->site['meta_des'])?$this->site['meta_des']:$this->site['meta_des']?>" charset="utf-8" />
<meta name="author" content="MCH, TechKnowledge"/>
<meta name="copyright" content="<?php echo @$this->site['site_name']?> [<?php echo @$this->site['site_email']?>]" />
<meta name="robots" content="index, archive, follow, noodp" /> 
<meta name="googlebot" content="index,archive,follow,noodp" /> 
<meta name="msnbot" content="all,index,follow" /> 
<meta name="generator" content="MCH" />
<link rel="shortcut icon" href="<?php echo $this->site['theme_url']?>index/pics/favicon.ico">
<!-- CSS -->
<link href="<?php echo $this->site['theme_url']?>index/index.css" rel="stylesheet" type="text/css">
<link href="<?php echo isset($this->site)?$this->site['theme_url']:''?>index/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo isset($this->site)?$this->site['base_url']:''?>themes/ui/ui.css" rel="stylesheet" type="text/css">
<link href="<?php echo isset($this->site)?$this->site['base_url']:''?>themes/paging/paging.css" rel="stylesheet" type="text/css">
<link href="<?php echo isset($layout['css'])?$layout['css']:''?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->site['theme_url']?>sf_menu/superfish.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?php echo $this->site['theme_url']?>sf_menu/superfish-vertical.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?php echo $this->site['theme_url']?>sf_menu/superfish-navbar.css" media="screen">
<!-- jQuery -->
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery.validate/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery.qtip/jquery.qtip.min.js"></script>
<link href="<?php echo url::base()?>plugins/jquery.qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo url::base()?>plugins/collection/form.js"></script>
<!-- Menu -->
<script type="text/javascript" src="<?php echo url::base()?>plugins/sf_menu/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/sf_menu/superfish.js"></script>
<!--<script type="text/javascript"> $(document).ready(function(){ $("ul.sf-menu").superfish({pathClass:  'current'});});</script>-->
<!-- Scroll to top -->
<script src="<?php echo url::base()?>plugins/jquery.ui.totop/easing.js" type="text/javascript"></script>
<script src="<?php echo url::base()?>plugins/jquery.ui.totop/jquery.ui.totop.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo url::base()?>plugins/jquery.ui.totop/css/ui.totop.css" />
<script type="text/javascript">	$(document).ready(function() { $().UItoTop({ easingType: 'easeOutQuart' }); });
</script>
<!-- Msg -->
<script type="text/javascript" src="<?php echo $this->site['base_url']?>plugins/jquery.msgbox/jquery.msgbox.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->site['base_url']?>plugins/jquery.msgbox/jquery.msgbox.css" />
</head>
<body topmargin="0" bottommargin="0" class="contain">
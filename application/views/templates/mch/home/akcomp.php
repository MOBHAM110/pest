<img id="imgLoading" src="<?php echo $this->site['theme_url']?>index/pics/loading_icon.gif"/>
<!--DETAIL-->
<?php
$akc_status = Configuration_Model::get_value('ENABLE_AKCOMP');
if($akc_status < 2)
    include 'rss_akcomp.php';
else
    include 'rss_any.php';
?>
<script type="text/javascript" src="<?php echo $this->site['base_url']?>plugins/jquery.tmpl/jquery.tmpl.min.js"></script>
<script language="javascript">
$(document).ready(function() {
    <?php if($akc_status < 2){ ?>
        $.getJSON("http://akcomp.com/home/get_news",
    <?php } else {?>
        $.getJSON("<?php echo $this->site['base_url'] ?>rss/readrss",
    <?php } ?>
	function(data) {
		if(data) { 
			$("div#newsAkcomp").show();
			$("img#imgLoading").hide();
            <?php if($akc_status < 2){ ?>
			$("#newsTmpl").tmpl(data).appendTo("#newsTbody");
            <?php } ?>
		} else {
			$("img#imgLoading").hide();
			$("div#newsAkcomp").hide();		
		}
	})
    
});

function get_news(id){
	$("img#imgLoading").show();
	$("div#newsAkcomp").hide();
	$.getJSON("http://akcomp.com/home/get_news/"+id,
	function(data) {
        console.log(data);
		if(data) { 
			$("img#imgLoading").hide();			
			$("div#detailAkcomp").show();
			$("#detailTmpl").tmpl(data).appendTo("div#detailAkcomp");			
		} else {
			$("img#imgLoading").hide();
			$("div#detailAkcomp").hide();
		}
	})
}

function get_newsWP(i){
	$("img#imgLoading").show();
	$("div#newsAkcomp").hide();
	for(var k=0; k<5; k++){
		if(k==i) {
			$("div#detailAkcompWP"+i).show(); 
			$("img#imgLoading").hide();
			break;
		} else $("div#detailAkcompWP"+i).hide();
	}
}

function get_blog(i){
	$("img#imgLoading").show();
	$("div#newsAkcomp").hide();
	for(var k=0; k<5; k++){
		if(k==i) {
			$("div#detailAkcompWP"+i).show(); 
			$("img#imgLoading").hide();
			break;
		} else $("div#detailAkcompWP"+i).hide();
	}
}

function get_news_rss(i){
	$("img#imgLoading").show();
	$("div#newsAkcomp").hide();
	for(var k=0; k<5; k++){
		if(k==i) {
			$("div#detailNEWS"+i).show(); 
			$("img#imgLoading").hide();
			break;
		} else $("div#detaildetailNEWS"+i).hide();
	}
}
</script>
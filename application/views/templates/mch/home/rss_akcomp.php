<!--DETAIL-->
<div id="detailAkcomp" style="display:none">
<script id="detailTmpl" type="text/x-jquery-tmpl">
<table class="home_bbs" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="home_bbs_T_L"></td>
    <td class="home_bbs_T_Ce">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="home_bbs_TT">${bbs_title}</td>
    <td class="home_bbs_more"></td>
    </tr>
    </table>
	<td class="home_bbs_T_R">
	</td>    
</tr>
<tr>
	<td class="home_bbs_M_L"></td>
    <td class="home_bbs_M_Ce">${bbs_content}</td>
    <td class="home_bbs_M_R"></td> 
</tr>
<tr>
    <td class="home_bbs_B_L"></td>
    <td class="home_bbs_B_Ce"><center><button type="button" onclick="location.href='<?php echo url::base();?>'">Homepage</button></center></td>
    <td class="home_bbs_B_R"></td>
</tr> 
</table>
</script>
</div>
<?php 
require Kohana::find_file('vendor/feed_xml','xml');
$dom = new MyDOMDocument;			
@$dom->load('http://aandkcomputers.wordpress.com/feed/');
@$arr = $dom->toArray();
if(isset($arr) && empty($arr))
{
$path = 'application/vendor/feed_xml/feed_rss.xml';
$fi = fopen($path,'r+');
$html_content = file_get_contents($path);
$dom->load($path);
$arr = $dom->toArray();
}
?>
<?php
if(isset($arr) && !empty($arr)){
for($i=0;$i<5;$i++) {
?>
<div id="detailAkcompWP<?php echo $i;?>" style="display:none">
<table class="home_bbs" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="home_bbs_T_L"></td>
    <td class="home_bbs_T_Ce">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="home_bbs_TT"><?php echo isset($arr['rss']['channel']['item'][$i]['title'])?$arr['rss']['channel']['item'][$i]['title']:'' ?></td>
    <td class="home_bbs_more"></td>
    </tr>
    </table>
	<td class="home_bbs_T_R">
	</td>    
</tr>
<tr>
	<td class="home_bbs_M_L"></td>
    <td class="home_bbs_M_Ce"><?php echo isset($arr['rss']['channel']['item'][$i]['content:encoded'])?$arr['rss']['channel']['item'][$i]['content:encoded']:'' ?></td>
    <td class="home_bbs_M_R"></td> 
</tr>
<tr>
    <td class="home_bbs_B_L"></td>
    <td class="home_bbs_B_Ce"><center><button type="button" onclick="location.href='<?php echo url::base();?>'">Homepage</button></center></td>
    <td class="home_bbs_B_R"></td>
</tr> 
</table>
</div>
<?php }} ?>
<!--HOME-->
<div id="newsAkcomp" style="display:none">
<table><tr><td width="50%" valign="top">
<table class="home_bbs" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="home_bbs_T_L"></td>
    <td class="home_bbs_T_Ce">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="home_bbs_TT">News</td>
    <td class="home_bbs_more"></td>
    </tr>
    </table>
	<td class="home_bbs_T_R">
	</td>    
</tr>
<tr>
	<td class="home_bbs_M_L"></td>
    <td class="home_bbs_M_Ce" >
    <table class="home_bbs_M_Ce" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="home_bbs_M_Ce_T"></td>
      </tr>
      <tr>
        <td class="home_bbs_M_Ce_M">
            <table class="home_bbs_item" cellpadding="0" cellspacing="0">
            <tr><td class="home_bbs_item_T"></td></tr>
            <tbody id="newsTbody"></tbody>
            <script id="newsTmpl" type="text/x-jquery-tmpl">
            <tr>
            <td class="home_bbs_item_M" style="padding-left:5px;">
                <a href="<?php echo url::base()?>bbs-165-1-${bbs_id}/${bbs_title}" >${bbs_title}</a>
            </td></tr>
            </script>
            <tr><td class="home_bbs_item_B"></td></tr>        
            </table>
        </td>
      </tr>
      <tr>
        <td class="home_bbs_M_Ce_B">&nbsp;</td>
      </tr>
    </table>
    </td>
    <td class="home_bbs_M_R"></td> 
</tr>
<tr>
    <td class="home_bbs_B_L"></td>
    <td class="home_bbs_B_Ce"></td>
    <td class="home_bbs_B_R"></td>
</tr> 
</table></td><td width="50%" valign="top">
<table class="home_bbs" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="home_bbs_T_L"></td>
    <td class="home_bbs_T_Ce">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="home_bbs_TT">Blog</td>
    <td class="home_bbs_more"></td>
    </tr>
    </table>
	<td class="home_bbs_T_R">
	</td>    
</tr>
<tr>
	<td class="home_bbs_M_L"></td>
    <td class="home_bbs_M_Ce">
    <table class="home_bbs_M_Ce" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="home_bbs_M_Ce_T"></td>
      </tr>
      <tr>
        <td class="home_bbs_M_Ce_M">
            <table class="home_bbs_item" cellpadding="0" cellspacing="0">
            <tr><td class="home_bbs_item_T"></td></tr>
            <?php
			if(isset($arr) && !empty($arr)){
			for($i=0;$i<5;$i++) {
			?>
            <tr>
            <td class="home_bbs_item_M" style="padding-left:5px;">
            <a href="<?php echo url::base()?>bbs-166-1-<?=$i?>/<?=@$arr['rss']['channel']['item'][$i]['title']?>" >
	  		<?php echo isset($arr['rss']['channel']['item'][$i]['title'])?$arr['rss']['channel']['item'][$i]['title']:'' ?></a>
			</td></tr>
            <?php }} ?>
            <tr><td class="home_bbs_item_B"></td></tr>        
            </table>
        </td>
      </tr>
      <tr>
        <td class="home_bbs_M_Ce_B">&nbsp;</td>
      </tr>
    </table>
    </td>
    <td class="home_bbs_M_R"></td> 
</tr>
<tr>
    <td class="home_bbs_B_L"></td>
    <td class="home_bbs_B_Ce"></td>
    <td class="home_bbs_B_R"></td>
</tr> 
</table>
</td></td></table>
</div>
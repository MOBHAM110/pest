
<?php
if(isset($mlist['rssnews']) && !empty($mlist['rssnews'])){
for($i=0;$i<5;$i++) {
?>
<div id="detailNEWS<?php echo $i;?>" style="display:none">
<table class="home_bbs" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="home_bbs_T_L"></td>
    <td class="home_bbs_T_Ce">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="home_bbs_TT"><?php echo isset($mlist['rssnews'][$i]['title'])?$mlist['rssnews'][$i]['title']:'' ?></td>
    <td class="home_bbs_more"></td>
    </tr>
    </table>
	<td class="home_bbs_T_R">
	</td>    
</tr>
<tr>
	<td class="home_bbs_M_L"></td>
    <td class="home_bbs_M_Ce"><?php echo isset($mlist['rssnews'][$i]['content'])?$mlist['rssnews'][$i]['content']:'' ?></td>
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

<?php
if(isset($mlist['rssblog']) && !empty($mlist['rssblog'])){
for($i=0;$i<5;$i++) {
?>
<div id="detailAkcompWP<?php echo $i;?>" style="display:none">
<table class="home_bbs" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="home_bbs_T_L"></td>
    <td class="home_bbs_T_Ce">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="home_bbs_TT"><?php echo isset($mlist['rssblog'][$i]['title'])?$mlist['rssblog'][$i]['title']:'' ?></td>
    <td class="home_bbs_more"></td>
    </tr>
    </table>
	<td class="home_bbs_T_R">
	</td>    
</tr>
<tr>
	<td class="home_bbs_M_L"></td>
    <td class="home_bbs_M_Ce"><?php echo isset($mlist['rssblog'][$i]['content'])?$mlist['rssblog'][$i]['content']:'' ?></td>
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

<table><tr>
<td width="50%">
<table class="home_bbs" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="home_bbs_T_L"></td>
    <td class="home_bbs_T_Ce">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="home_bbs_TT">NEWS</td>
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
			if(isset($mlist['rssnews']) && !empty($mlist['rssnews'])){
			for($i=0;$i<5;$i++) {
			?>
            <tr>
            <td class="home_bbs_item_M" style="padding-left:5px;">
            <a href="#" onclick="get_news_rss(<?php echo $i;?>)">
	  		<?php echo isset($mlist['rssnews'][$i]['title'])?$mlist['rssnews'][$i]['title']:'' ?></a>
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
</td>

<td width="50%">
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
			if(isset($mlist['rssblog']) && !empty($mlist['rssblog'])){
			for($i=0;$i<5;$i++) {
			?>
            <tr>
            <td class="home_bbs_item_M" style="padding-left:5px;">
            <a href="#" onclick="get_blog(<?php echo $i;?>)">
	  		<?php echo isset($mlist['rssblog'][$i]['title'])?$mlist['rssblog'][$i]['title']:'' ?></a>
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
</td>

    </td></table>
</div>
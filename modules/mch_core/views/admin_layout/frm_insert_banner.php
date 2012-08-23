<?php echo $top?>
<form action="<?php echo url::base()?>admin_layout/save_banner_layout/<?php echo $mr['page_id']?>/<?php echo $mr['position']?>" method="post">
<table id="float_table" align="center" cellspacing="0" cellpadding="0" class="title">
<tr>
    <td style="width:600px" class="title_label"><?php echo $mr['page_title']?></td>
    <td align="left"><button type="button" name="btn_close" class="button close" onclick="javascript:close_refresh();"><span><?php echo Kohana::lang('global_lang.btn_close')?></span></button>     
	<?php if (count($mlist) > 0) { ?>
    	<button type="submit" name="btn_save"  class="button new"><span><?php echo Kohana::lang('global_lang.btn_insert')?></span></button>
    <?php } // end if ?>
	</td>
</tr>
</table>
<table class="list" cellspacing="1" cellpadding="5">
<tr class="list_header">   
    <td width="10"><?php echo Kohana::lang('layout_lang.lbl_select_banner')?></td> 			
    <td><?php echo Kohana::lang('banner_lang.lbl_id')?></td>     
    <td><?php echo Kohana::lang('global_lang.lbl_type')?></td>   
    <td><?php echo Kohana::lang('global_lang.lbl_view')?></td>
</tr>
<?php foreach ($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>"> 
<td><input name="chk_banner[]" type="checkbox" value="<?php echo $list['banner_id'] ?>"></td>    
<td align="center"><?php echo $list['banner_id']?></td>   
<td align="center"><?php echo Kohana::lang('global_lang.lbl_'.$list['banner_type'])?></td>
<td align="center">
    <?php if ($list['banner_type'] == 'image') { 
	$src = "uploads/header/".$list['banner_file'];
	$maxw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST');
	$maxh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST');
	$arr = MyImage::thumbnail($maxw,$maxh,$src);
	?>
        <img src="<?php echo url::base()?>uploads/banner/<?php echo $list['banner_file']?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'auto'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):@$maxh?>"> 
    <?php } else { ?>
        <embed src="<?php echo url::base()?>uploads/banner/<?php echo $list['banner_file']?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
    <?php } // end if ?>        
</td>    
</tr>
<?php } // end foreach ?>
</table>
<input type="hidden" name="page_id" id="page_id" value="<?php echo $mr['page_id']?>" />
<input type="hidden" name="position" id="position" value="<?php echo $mr['position']?>" />
</form>
<script type="text/javascript" src="<?php echo url::base()?>plugins/collection/popup.js"></script>
<script type="text/javascript">
$().ready(function() {
	$('#float_table').floatBanner();
});
window.onblur = function() {
	//self.close();
	window.opener.location.href = window.opener.location.href;
};
</script>
<?php echo $bottom?>
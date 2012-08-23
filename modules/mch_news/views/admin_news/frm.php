<form id="frm" name="frm" action="<?php echo url::base()?>admin_news/save/pid/<?php echo $mr['page_id']?>" method="post" enctype="multipart/form-data">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo $title?></td>
    <td class="title_button"><?php require('button.php')?></td>
  </tr>
</table>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><font color="#FF0000">*</font> <?php echo Kohana::lang('global_lang.lbl_title')?>:</div>
    <div class="yui3-u-4-5"><input type="text" value="<?php if (isset($mr['bbs_title'])) echo $mr['bbs_title']; ?>" id="txt_title" name="txt_title" size="50"/></div>
</div>
<?php for($i=1;$i<=3;$i++) { ?>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_image').' '.$i?>:</div>
    <div class="yui3-u-4-5">
<?php if (isset($list_file[$i-1])) { 
		$src = "uploads/news/".$list_file[$i-1]->bbs_file_name;
		$maxw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST');
		$maxh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST');	
		$arr = MyImage::thumbnail($maxw,$maxh,$src);
?>
	<img src="<?php echo url::base()?>uploads/news/<?php echo $list_file[$i-1]->bbs_file_name?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'auto'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):@$maxh?>" />&nbsp;
    <button type="button" name="btn_delete" id="btn_delete" class="button delete" onclick="javascript:location.href='<?php echo url::base()?>admin_news/del_image/<?php echo $list_file[$i-1]->bbs_file_id?>'"/><span><?php echo Kohana::lang('global_lang.btn_delete') ?></span></button>
<?php } else { ?>
    <input type="file" name="attach_file<?php echo $i?>" id="attach_file<?php echo $i?>">
<?php } // end if ?>
</div>
</div>
<?php } // end for ?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><font color="#FF0000">*</font> <?php echo Kohana::lang('global_lang.lbl_content')?>:</div>
	<div class="yui3-u-4-5">
	<textarea class="ckeditor" id="txt_content" name="txt_content" cols="50" style="width:100%;height:100px"><?php echo isset($mr['bbs_content'])?$mr['bbs_content']:''?></textarea>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_order')?>:</div>
    <div class="yui3-u-4-5"><input type="text" value="<?php echo !empty($mr['bbs_sort_order'])?$mr['bbs_sort_order']:'' ?>" id="txt_sort_order" name="txt_sort_order" size="10" class="text_number"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_status')?>:</div>
    <div class="yui3-u-4-5">
        <select name="sel_status" id="sel_status">            
            <option value="1" <?php echo isset($mr['bbs_status'])?($mr['bbs_status']?'selected':''):'selected'?>><?php echo Kohana::lang('global_lang.lbl_active')?></option>
            <option value="0" <?php echo isset($mr['bbs_status'])?($mr['bbs_status']?'':'selected'):''?>><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>	
        </select>
    </div>
</div>
<div class="yui3-g center"><?php require('button.php')?></div>
</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['bbs_id'])?$mr['bbs_id']:''?>"/>
<input name="hd_pid" type="hidden" id="hd_pid" value="<?php echo isset($mr['page_id'])?$mr['page_id']:''?>" />
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
</form>
<?php require('frm_js.php')?>
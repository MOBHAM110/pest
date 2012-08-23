<form name="frm" action="<?php echo $this->site['base_url']?>admin_blog/save/pid/<?php echo $mr['page_id']?>" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo $title?></td>
    <td class="title_button"><?php require('button.php')?></td>
  </tr>
</table>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><font color="#FF0000">*</font> <?php echo Kohana::lang('global_lang.lbl_title')?>:</div>
    <div class="yui3-u-4-5"><input type="text" id="txt_title" name="txt_title" value="<?php if (isset($mr['bbs_title'])) echo $mr['bbs_title']; ?>" size="50"/></div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><font color="#FF0000">*</font> <?php echo Kohana::lang('global_lang.lbl_content')?>:</div>
	<div class="yui3-u-4-5"><textarea class="ckeditor" id="txt_content" name="txt_content" cols="50" style="width:100%;height:100px"><?php echo isset($mr['bbs_content'])?$mr['bbs_content']:''?></textarea></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_order')?>:</div>
    <div class="yui3-u-4-5"><input type="text" value="<?php echo !empty($mr['bbs_sort_order'])?$mr['bbs_sort_order']:'' ?>" id="txt_sort_order" name="txt_sort_order" size="10" class="text_number"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_status')?>:</div>
    <div class="yui3-u-4-5">
    <select name="sel_status" id="sel_status">
        <option value="0" <?php echo isset($mr['bbs_status'])?($mr['bbs_status']?'':'selected'):''?>><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>	
        <option value="1" <?php echo isset($mr['bbs_status'])?($mr['bbs_status']?'selected':''):'selected'?>><?php echo Kohana::lang('global_lang.lbl_active')?></option>
    </select>
    </div>
</div>
<div class="yui3-g center"><?php require('button.php')?></div>
  </tr>
</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['bbs_id'])?$mr['bbs_id']:''?>"/>
<input name="hd_pid" type="hidden" id="hd_pid" value="<?php echo $mr['page_id']?>" />
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
</form>
<?php require('frm_js.php')?>
<?php $seg_id = uri::segment('id')?>
<?php $action = ($seg_id && !isset($mr['bbs_id']))?'id/'.$seg_id:'pid/'.$mr['page_id']?>
<form name="frm" action="<?php echo $this->site['base_url']?>admin_bbs/save/<?php echo $action?>" method="post" enctype="multipart/form-data">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo $title?></td>
    <td class="title_button"><?php require('button.php')?></td>
  </tr>
</table>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><font color="#FF0000">*</font>&nbsp;<?php echo Kohana::lang('global_lang.lbl_title')?>:</div>
    <div class="yui3-u-4-5"><input type="text" value="<?php if (isset($mr['bbs_title'])) echo $mr['bbs_title']; ?>" id="txt_title" name="txt_title" size="50"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_pass')?>:</div>
    <div class="yui3-u-4-5"><input type="password" id="txt_pass" name="txt_pass" size="30"/></div>
</div>
<div class="yui3-g">
  	<div class="yui3-u-1-6 right"><font color="#FF0000">*</font>&nbsp;<?php echo Kohana::lang('global_lang.lbl_content')?>:</div>
  	<div class="yui3-u-4-5"><textarea class="ckeditor" id="txt_content" name="txt_content" cols="50" style="width:100%;height:100px"><?php echo isset($mr['bbs_content'])?$mr['bbs_content']:''?></textarea></div>
</div>
<?php for($i=1;$i<=3;$i++) { ?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_file').' '.$i?>:</div>
	<div class="yui3-u-4-5">
	<?php if (isset($list_file[$i-1])) { ?>
    <a href="<?php echo url::base()?>uploads/storage/<?php echo $list_file[$i-1]->bbs_file_name?>">
    <?php $file_type = ORM::factory('file_type_orm', $list_file[$i-1]->file_type_id)->file_type_detail?>
    <img src="<?php echo url::base()?>themes/admin/pics/icon_<?php echo $file_type?>.png" border="0"/></a>&nbsp;
        <button type="button" name="btn_delete" id="btn_delete" class="button delete" onclick="javascript:location.href='<?php echo url::base()?>admin_bbs/del_storage_file/<?php echo $list_file[$i-1]->bbs_file_id?>'"/><span><?php echo Kohana::lang('client_lang.btn_delete') ?></span></button>
    <?php } else { ?>
    <input type="file" name="attach_file<?php echo $i?>" id="attach_file<?php echo $i?>">
    <?php } // end if ?>
    </div>
</div>
<?php } // end for ?>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_order')?>:</div>
    <div class="yui3-u-4-5"><input type="text" value="<?php echo !empty($mr['bbs_sort_order'])?$mr['bbs_sort_order']:'' ?>" id="txt_sort_order" name="txt_sort_order" size="10" class="text_number"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_status')?>:</div>
    <div class="yui3-u-4-5">
    <select name="sel_status" id="sel_status">            
    <option value="1" <?php if(isset($mr['bbs_status']) && $mr['bbs_status']==1) echo 'selected'?> ><?php echo Kohana::lang('global_lang.lbl_active')?></option>
    <option value="0"><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>	
    </select>
    </div>
</div>
<div class="yui3-g center"><?php require('button.php')?></div>
</div>
<input name="hd_pid" type="hidden" id="hd_pid" value="<?php echo $mr['page_id']?>"/>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['bbs_id'])?$mr['bbs_id']:''?>"/>
<input name="hd_save_add" type="hidden" id="hd_save_add" value="0"/>
</form>
<?php require('frm_js.php')?>
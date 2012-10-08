<?php $seg_id = uri::segment('id')?>
<?php $action = ($seg_id && !isset($mr['bbs_id']))?'id/'.$seg_id:''?>
<form action="<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/save/<?php echo $action?>" method="post" enctype="multipart/form-data">
<table width="100%" class="bbs_create" cellspacing="0" cellpadding="0">
<tr><td class="bbs_create_T"></td></tr>
<tr>
    <td class="bbs_create_M">
        <table class="bbs_create_M_Co" cellspacing="3" cellpadding="3">        
        <tr><td align="left"><?php echo Kohana::lang('global_lang.lbl_title')?>&nbsp;<font color="#FF0000">*</font></td></tr>
        <tr><td align="left"><input size="50" name="txt_title" type="text" id="txt_title" value="<?php echo isset($mr['bbs_title'])?$mr['bbs_title']:''?>"></td>
        <?php if (!isset($this->sess_cus['id'])) { ?>
        <tr><td align="left"><?php echo Kohana::lang('account_lang.lbl_name')?> <font color="#FF0000">*</font></td></tr>
        <tr><td align="left"><input size="50" name="txt_name" type="text" value="<?php echo isset($mr['bbs_author'])?$mr['bbs_author']:''?>"/></td></tr>
        <tr><td align="left"><?php echo Kohana::lang('account_lang.lbl_pass')?> <font color="#FF0000">*</font></td></tr>
        <tr><td align="left"><input size="20" name="txt_pass" type="password" id="txt_pass"></td>
        <?php } // sess_cus ?>
        <?php for($i=1;$i<=3;$i++) { ?>
        <tr><td width="10%" align="left" valign="top"><?php echo Kohana::lang('global_lang.lbl_file').' '.$i?></td></tr>
        <?php if (isset($list_file[$i-1])) { ?>
        <tr><td align="left"><a href="<?php echo url::base()?>uploads/storage/<?php echo $list_file[$i-1]->bbs_file_name?>">
        <?php $file_type = ORM::factory('file_type_orm', $list_file[$i-1]->file_type_id)->file_type_detail?>
        <img src="<?php echo url::base()?>themes/admin/pics/icon_<?php echo $file_type?>.png" border="0"/>        </a>&nbsp;
            <button type="button" name="btn_delete" id="btn_delete" class="button" onclick="return confirm('Do you want delete?')?(location.href='<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/delete_file/<?php echo $list_file[$i-1]->bbs_file_id?>/id/<?php echo $mr['bbs_id']?>'):''"/><span><?php echo Kohana::lang('client_lang.btn_delete') ?></span></button></td>
		</tr>
        <?php } else { ?>
        <tr><td align="left"><input type="file" name="attach_file<?php echo $i?>" id="attach_file<?php echo $i?>"></td></tr>
        <?php } // end if ?>
        <?php } // end for ?>
        <tr><td valign="top" align="left"><?php echo Kohana::lang('global_lang.lbl_content')?>&nbsp;<font color="#FF0000">*</font></td></tr>
        <tr><td><textarea name="txt_content" id="txt_content" style="width:100%;"><?php echo isset($mr['bbs_content'])?$mr['bbs_content']:(isset($indata['txt_content'])?$indata['txt_content']:'')?></textarea></td>
        </tr>
        <tr>
            <td align="center">
            <button type="submit" name="btn_save"  /><?php echo Kohana::lang('global_lang.btn_save')?></button>&nbsp;&nbsp;&nbsp;       
            <button type="button" name="btn_back"  onClick="javascript:location.href='<?php echo url::base().$this->site['history']['back']?>'"><?php echo Kohana::lang('global_lang.btn_back')?></button>
            </td>
        </tr>
        </table>
    </td>
</tr>
<tr><td class="bbs_create_B">&nbsp;</td></tr>
</table>
<input type="hidden" name="hd_id" id="hd_id" value="<?php echo isset($mr['bbs_id'])?$mr['bbs_id']:''?>" />
</form>
<script type="text/javascript" src="<?php echo url::base()?>plugins/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "searchreplace,insertdatetime,preview",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,sub,sup,|,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,
		forced_root_block : false,
		force_br_newlines : true,
		force_p_newlines : false
	});
});
</script>
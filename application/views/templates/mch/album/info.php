<table class="form" name="album_info" cellspacing="0" cellpadding="5" width="100%">
<tr>
    <td align="right" width="14%"><?php echo Kohana::lang('global_lang.lbl_title')?>&nbsp;<font color="#FF0000">*</font></td>
    <td align="left">
    	<input name="txt_title" type="text" id="txt_title" value="<?php echo isset($mr['bbs_title'])?$mr['bbs_title']:''?>" style="width:420px;"/>
	</td>
</tr>
<tr>
    <td align="right"><?php echo Kohana::lang('account_lang.lbl_name')?> <font color="#FF0000">*</font></td>
    <td align="left">
	<input name="txt_name" type="text" value="<?php echo isset($mr['bbs_author'])?$mr['bbs_author']:''?>" style="width:420px;"/>
	</td>
</tr>
<tr>
    <td align="right"><?php echo Kohana::lang('account_lang.lbl_pass')?> <font color="#FF0000">*</font></td>
    <td align="left">
	<input size="30" name="txt_pass" type="password" id="txt_pass">
	</td>
</tr>
<tr>
    <td valign="top" align="right"><?php echo Kohana::lang('global_lang.lbl_content')?>&nbsp;<font color="#FF0000">*</font></td>
    <td><textarea name="txt_content" cols="50" rows="20" id="txt_content"><?php echo isset($mr['bbs_content'])?$mr['bbs_content']:''?></textarea></td>
</tr>
</table>
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
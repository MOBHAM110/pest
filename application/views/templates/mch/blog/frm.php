<form action="<?php echo url::base()?>blog/pid/<?php echo $this->page_id?>/save" method="post">
<table class="blog_create" align="center" cellspacing="0" cellpadding="0">
<tr><td class="blog_create_T"></td></tr>
<tr>
    <td class="blog_create_M">
        <table class="blog_create_M_Co" class="form" cellspacing="3" cellpadding="3">        
        <tr><td align="left"><?php echo Kohana::lang('global_lang.lbl_title')?><font color="#FF0000">*</font></td></tr>
         <tr><td align="left"><input size="50" name="txt_title" type="text" id="txt_title" value="<?php echo isset($mr['bbs_title'])?$mr['bbs_title']:''?>"></td>        
        <tr><td valign="top" align="left"><?php echo Kohana::lang('global_lang.lbl_content')?></td></tr>
        <tr><td><textarea name="txt_content" cols="70" rows="10" id="txt_content"><?php echo isset($mr['bbs_content'])?$mr['bbs_content']:(isset($indata)?$indata['txt_content']:'')?></textarea></td>
        </tr>
        <tr>
            <td align="center">
            <button type="submit" name="btn_save"  /><?php echo Kohana::lang('global_lang.btn_save')?></button>&nbsp;&nbsp;&nbsp;            <button type="button" name="btn_back"  onClick="javascript:location.href='<?php echo url::base().$this->site['history']['back']?>'"><?php echo Kohana::lang('global_lang.btn_back')?></button>
            </td>
        </tr>
        <tr>
            <td align="center">&nbsp;</td>
        </tr>         
        </table>
    </td>
</tr>
<tr><td class="blog_create_B"></td></tr>
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
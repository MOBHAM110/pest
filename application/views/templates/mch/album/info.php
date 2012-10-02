<table class="" name="album_info" cellspacing="0" cellpadding="5" width="100%">
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
    <td>
        <div class="yui3-u-4-5"><textarea class="ckeditor" name="txt_content" cols="50" rows="20" style="width:100%;height:100px" id="txt_content"><?php echo isset($mr['bbs_content'])?$mr['bbs_content']:''?></textarea></div>
    </td>
</tr>
</table>		
<script language="javascript" src="<?php echo url::base()?>plugins/ckeditor/ckeditor.js"></script>
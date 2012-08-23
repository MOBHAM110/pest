<form id="frm" name="frm" method="post" action="<?php
/* */ echo url::base()?>register/save" >
<table class="register" align="center" border="0" cellspacing="0" cellpadding="8" width="100%">
<tr>
    <td align="right" width="35%"><?php echo Kohana::lang('account_lang.lbl_username')?></td>
    <td align="left"><input class="username" name="txt_username" type="text" id="txt_username" style="width:200px;" value="<?php echo isset($mr['txt_username'])?$mr['txt_username']:''?>">	
    <font color="#FF0000">*</font>&nbsp;</div>		
    </td>
</tr>
<tr>
    <td align="right"><?php echo Kohana::lang('account_lang.lbl_pass')?></td>
    <td align="left"><input name="txt_pass" type="password" id="txt_pass" style="width:200px;" class="password"> 
    <font color="#FF0000">*</font></td>
</tr>
<tr>
    <td align="right"><?php echo Kohana::lang('account_lang.lbl_cfpass')?></td>
    <td align="left"><input name="txt_cfpass" type="password" id="txt_cfpass" style="width:200px;" class="password"> 
    <font color="#FF0000">*</font></td>
</tr>
<tr>
    <td align="right"><?php echo Kohana::lang('account_lang.lbl_email')?></td>
    <td align="left"><input name="txt_email" type="text" id="txt_email" value="<?php echo isset($mr['txt_email'])?$mr['txt_email']:''?>" style="width:200px;" class="email"> 
    <font color="#FF0000">*</font></td>
</tr>
<tr>
	<td align="right"><?php echo Kohana::lang('register_lang.lbl_staff')?></td>
    <td align="left"><input type="checkbox" name="chk_staff" id="chk_staff" value="1" <?php echo isset($mr['chk_staff'])?'checked="checked"':''?>/></td>
</tr>
<tr>
    <td align="right"><?php echo Kohana::lang('register_lang.lbl_security')?></td>
    <td align="left"><?php echo Captcha::factory()->render()?></td>
</tr>
<tr>
    <td align="right"><?php echo Kohana::lang('register_lang.lbl_retype_security')?></td>
    <td align="left"><input type="text" name="captcha_response" size="10" class="password"/> 
    <font color="#FF0000">*</font></td>
</tr>
<tr>
    <td align="right">&nbsp;</td>
    <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <button type="submit" name="Submit"  class="button"><span><?php echo Kohana::lang('register_lang.btn_register')?></span></button>
    <button type="reset" name="Submit2" class="button"><span><?php echo Kohana::lang('global_lang.btn_reset')?></span></button>
    </td>
</tr>
</table>
</form>
<?php require('frm_js.php')?>
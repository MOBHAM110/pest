<table width="100%" class="login" align="center" cellpadding="0" cellspacing="0" border="0">
<tr><td class="login_top"></td></tr>
<?php
/* */ if (!empty($this->sess_cus['username'])) { ?> 
<tr><td class="login_middle"><?php require('account.php'); ?></td></tr>
<?php } else { ?>
<tr>
    <td class="login_middle" width="50%" valign="top">
        <form action="<?php echo $this->site['base_url']?>login/check_login" method="post" name="frm" id="frm">
        <table class="login_form" align="center" cellspacing="0" cellpadding="5" border="0">
        <tr>
            <td colspan="2" align="left"><strong><?php echo Kohana::lang('account_lang.lbl_login')?></strong></td>
        </tr>
        <tr>
            <td colspan="2" align="center" class="error"><?php if(isset($error_msg)) echo $error_msg ?></td>
        </tr>
        <tr>
            <td width="40%" align="right" nowrap="nowrap"><?php echo Kohana::lang('account_lang.lbl_username')?>:&nbsp;</td>
            <td align="left"><input name="txt_username" type="text" id="txt_username" class="username" style="width:9em"/></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_pass')?>:&nbsp;</td>
            <td align="left"><input name="txt_pass" type="password" id="txt_pass" class="password" style="width:9em"/></td>
        </tr>
        <tr>
            <td colspan="2" class="login_button" align="center">
                <button type="submit" class="button"><?php echo Kohana::lang('account_lang.lbl_login')?></button>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <a href="<?php echo url::base()?>forgotpass"><?php echo Kohana::lang('login_lang.lbl_forgotpass')?>?</a>
            </td>
        </tr>
        </table>
        </form>
    </td>
    <td style="border-left:1px solid" width="2%">&nbsp;</td>
    <td class="login_middle" valign="top">
        <table class="login_register" align="center" cellspacing="0" cellpadding="5" border="0">
        <tr>
            <td align="left"><strong><?php echo Kohana::lang('register_lang.lbl_title') ?></strong></td>
        </tr>
        <tr>
            <td align="center"><div style="padding:5px"><?php echo Kohana::lang('login_lang.lbl_register')?></div></td>
        </tr>
        <tr>
            <td align="center">
                <button type="submit" name="Submit"  class="button" onclick="javascript:location.href='<?php echo url::base()?>register'">
                <span><?php echo Kohana::lang('register_lang.btn_register')?></span>
                </button>
            </td>
        </tr>
        <tr><td align="center">&nbsp;</td></tr>
        </table>
	</td>
</tr>
<?php } // end if check login ?>
<tr><td class="login_bottom"></td></tr>
</table>
<script language="JavaScript" type="text/javascript">
document.getElementById('txt_username').focus();
</script>
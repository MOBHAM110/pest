<table cellpadding="0" cellspacing="0" class="login_block">
<tr><td valign="top" class="login_block_top"><?php echo Kohana::lang('login_lang.tt_client_page')?></td></tr>
<tr><td class="login_block_middle" valign="top">
    <form name="login" action="<?php echo url::base()?>login/check_login" method="post">
    <?php if (!empty($this->sess_cus['username'])) { ?> 
    <table class="login_content" cellpadding="0" cellspacing="0">	
    <tr><td><?php require('account.php'); ?></td></tr>
    </table>
    <?php } else { ?> 
    <table  align="center" cellpadding="5" cellspacing="0">        <tr><td align="center"><input type="text" autocomplete="off" class="input_login"  name="txt_username" id="txt_username" onfocus="if(this.value=='<?php echo Kohana::lang('account_lang.lbl_username')?>') { this.value = ''; this.style.color='#666';}" onblur="if(this.value=='') { this.value = '<?php echo Kohana::lang('account_lang.lbl_username')?>'; this.style.color='#CCC';}" value="<?php echo Kohana::lang('account_lang.lbl_username')?>" style="color:#CCC"></td></tr>
    <tr><td align="center"><input type="password" autocomplete="off" class="input_login" name="txt_pass" id="txt_pass" onfocus="if(this.value=='<?php echo Kohana::lang('account_lang.lbl_pass')?>') { this.value = ''; this.style.color='#666';}" onblur="if(this.value=='') { this.value = '<?php echo Kohana::lang('account_lang.lbl_pass')?>'; this.style.color='#CCC';}" value="<?php echo Kohana::lang('account_lang.lbl_pass')?>"></td></tr> 
    <tr><td class="login_content_btn" align="center"><button type="submit" class="button"><?php echo Kohana::lang('login_lang.btn_login')?></button></td>
    </tr>
    <tr>
    <td class="login_content_a" align="center"><a href="<?php echo url::base()?>forgotpass"><?php echo Kohana::lang('account_lang.lbl_forgot_pass')?>?</a><span style="color:#999; padding:0px 5px;">|</span><a href="<?php echo url::base()?>register"><?php echo Kohana::lang('account_lang.lbl_register')?></a></td>
    </tr> 
    </table>      
    <?php } // end if check login ?>      
    </form>
</td></tr>
<tr><td class="login_block_bottom"></td></tr>  
</table>
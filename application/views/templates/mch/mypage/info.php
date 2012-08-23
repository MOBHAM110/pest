<table class="mypage" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="mypage_top"></td></tr>
<tr>
    <td class="mypage_middle">
        <table class="mypage_middle_Co" border="0" cellspacing="0" cellpadding="3" align="center">
        <form method="post" action="<?php echo url::base()?>mypage/update_account" >
        <tr>
            <td width="20%"></td>
            <td><font class="text_error"><?php echo isset($mr['frm_error'])?$mr['frm_error']:''?></font></td>
        </tr>
        <tr style="background-color:#CCC">
        	<td align="center" colspan="2"><strong><?php echo Kohana::lang('account_lang.lbl_detail_info')?></strong></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_fname')?></td>
            <td align="left"><input name="txt_first_name" type="text" id="txt_first_name" value="<?php echo $mr['customers_firstname']?>" size="30" style="width:150px;"></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_lname')?></td>
            <td align="left"><input name="txt_last_name" type="text" id="txt_last_name" value="<?php echo $mr['customers_lastname']?>" size="30" style="width:150px;"></td>
        </tr>
        <tr>
            <td align="right" valign="top"><?php echo Kohana::lang('account_lang.lbl_email')?></td>
            <td align="left"><input name="txt_email" type="text" id="txt_email" value="<?php echo $mr['user_email']?>" size="30" style="width:150px;">
            <font color="#FF0000">*</font><?php if (isset($mr['calendar']) && $this->site['config']['GOOGLE_CALENDAR']) { ?><br />
            <a href="<?php echo url::base()?>mypage/calendar"><?php echo Kohana::lang('account_lang.lbl_google_calendar')?></a><?php } // end if ?>
            </td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_phone')?></td>
            <td align="left"><input name="txt_phone" id="txt_phone" type="text"  value="<?php echo $mr['customers_phone']?>" style="width:150px;" ></td>
        </tr>
        <tr>
            <td colspan="2" align="right">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_address')?></td>
            <td align="left">
            	<input name="txt_address" type="text" id="txt_address" value="<?php echo $mr['customers_address']?>" style="width:350px;" maxlength="50">
            </td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_city')?></td>
            <td align="left"><input name="txt_city" type="text" id="txt_city" value="<?php echo $mr['customers_city']?>" style="width:350px;" maxlength="50"></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_state')?></td>
            <td align="left"><input name="txt_state" type="text" id="txt_state" value="<?php echo $mr['customers_state']?>" style="width:350px;" maxlength="50"></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_zipcode')?></td>
            <td align="left"><input name="txt_zipcode" type="text" id="txt_zipcode" value="<?php echo $mr['customers_zipcode']?>" style="width:350px;" maxlength="50"></td>
        </tr>
        <tr>
            <td colspan="2" align="right">&nbsp;</td>
        </tr>
        <tr style="background-color:#CCC">
            <td colspan="2" align="center"><strong><?php echo Kohana::lang('account_lang.lbl_change_pass')?></strong></td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" name="set_new_pass" id="set_new_pass">
                <tr>
                    <td width="30%" align="right"><?php echo Kohana::lang('account_lang.lbl_old_pass')?></td>
                    <td align="left"><input type="password" name="txt_old_pass" id="old_pass" /></td>
                </tr>
                <tr>
                    <td align="right"><?php echo Kohana::lang('account_lang.lbl_new_pass')?></td>
                    <td align="left"><input type="password" name="txt_new_pass" id="new_pass" /></td>
                </tr>
                <tr>
                    <td align="right"><?php echo Kohana::lang('account_lang.lbl_cf_new_pass')?></td>
                    <td align="left"><input type="password" name="txt_cf_new_pass" id="cf_new_pass" /></td>
                </tr>
                </table>
            </td>
        </tr>       
        <tr>           
            <td align="center" colspan="2"><button type="submit" name="Submit"><?php echo Kohana::lang('global_lang.btn_save')?></button>&nbsp;
            <button type="reset" name="reset"><?php echo Kohana::lang('global_lang.btn_reset')?></button>&nbsp;
            <button type="button" name="btn_back" id="btn_back" onclick="location.href='<?php echo url::base().$this->site['history']['back']?>'" /><?php echo Kohana::lang('global_lang.btn_back')?></button></td>
        </tr>
        </form>
        </table>
	</td>
</tr>
<tr><td class="mypage_bottom"></td></tr>
</table>
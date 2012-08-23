<form id="frm" name="frm" action="<?php echo url::base() ?>admin_customer/save" method="post" enctype="multipart/form-data">
<table id="float_table" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td><?php echo Kohana::lang('account_lang.tt_cus_account') ?></td>
    <td align="right"><?php require('button.php')?></td>
  </tr>
</table>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><font color="#FF0000">*</font>&nbsp;<?php echo Kohana::lang('account_lang.lbl_username') ?>:</div>
    <div class="yui3-u-4-5"><input tabindex="1" type="text" id="txt_username" name="txt_username" value="<?php echo isset($mr['user_name'])?$mr['user_name']:''?>" size="50" onkeypress="return isUsername(this, event)" autofocus /></div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right">
    	<?php if (isset($mr['user_id'])) {?>
        	<?php echo Kohana::lang('account_lang.lbl_new_pass') ?>
        <?php } else { ?>
    		<font color="#FF0000">*</font>&nbsp;<?php echo Kohana::lang('account_lang.lbl_pass') ?>
        <?php } // end if ?>:
	</div>
    <div class="yui3-u-4-5"><input tabindex="2" type="password" id="txt_pass" name="txt_pass" value="" size="50"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_fname') ?>:</div>
    <div class="yui3-u-4-5">
    	<input tabindex="3" type="text" id="txt_first_name" name="txt_first_name" value="<?php echo isset($mr['customers_firstname'])?$mr['customers_firstname']:''?>" size="50"/>
	</div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_lname') ?>:</div>
    <div class="yui3-u-4-5"><input tabindex="4" type="text" id="txt_last_name" name="txt_last_name" value="<?php echo isset($mr['customers_lastname'])?$mr['customers_lastname']:''?>" size="50"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><font color="#FF0000">*</font>&nbsp;<?php echo Kohana::lang('account_lang.lbl_email') ?>:</div>
    <td align="left"><input tabindex="5" type="text" id="txt_email" name="txt_email" value="<?php echo isset($mr['user_email'])?$mr['user_email']:''?>" size="50"/></td>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_address') ?>:</div>
    <div class="yui3-u-4-5"><input tabindex="6" type="text" id="txt_address" name="txt_address" value="<?php echo isset($mr['customers_address'])?$mr['customers_address']:''?>" size="50" maxlength="50"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_city') ?>:</div>
    <div class="yui3-u-4-5"><input tabindex="7" type="text" id="txt_city" name="txt_city" value="<?php echo isset($mr['customers_city'])?$mr['customers_city']:''?>" size="50"/></div>
</div>
<div class="yui3-g">
  	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_state') ?>:</div>
    <div class="yui3-u-4-5"><input tabindex="8" type="text" id="txt_state" name="txt_state" value="<?php echo isset($mr['customers_state'])?$mr['customers_state']:''?>" size="50"/></div>
</div>
<div class="yui3-g">
  	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_zipcode') ?>:</div>
    <div class="yui3-u-4-5"><input tabindex="9" type="text" id="txt_zipcode" name="txt_zipcode" value="<?php echo isset($mr['customers_zipcode'])?$mr['customers_zipcode']:''?>" size="50"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_phone') ?>:</div>
    <div class="yui3-u-4-5"><input tabindex="10" type="text" id="txt_phone" name="txt_phone" value="<?php echo isset($mr['customers_phone'])?$mr['customers_phone']:''?>" size="50"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_fax') ?>:</div>
    <div class="yui3-u-4-5"><input tabindex="11" type="text" id="txt_fax" name="txt_fax" value="<?php echo isset($mr['customers_fax'])?$mr['customers_fax']:''?>" size="50"/></div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_staff')?>:</div>
    <div class="yui3-u-4-5"><input tabindex="12" type="checkbox" id="chk_staff" name="chk_staff" value="1" <?php echo (isset($mr['user_level'])&&$mr['user_level']==3)?'checked="checked"':''?> /></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_status') ?>:</div>
    <div class="yui3-u-4-5">
    <select tabindex="13" id="sel_status" name="sel_status">
        <option value="1"><?php echo Kohana::lang('global_lang.lbl_active') ?></option>
        <option value="0" <?php if(isset($mr['user_status']) && $mr['user_status'] == 0) echo 'selected' ?>><?php echo Kohana::lang('global_lang.lbl_inactive') ?></option>      
    </select>
	</div>
</div>
<div class="yui3-g center">
    <?php require('button.php')?>
</div>
</div>
<input type="hidden" id="hd_id" name="hd_id" value="<?php echo isset($mr['user_id'])?$mr['user_id']:''?>" />
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
</form>
<?php require('frm_js.php')?>
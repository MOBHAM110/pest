<form id="frm" name="frm" action="<?php echo url::base() ?>admin_account/save" method="post">
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label"><?php echo Kohana::lang('account_lang.tt_admin_account') ?></td>
    <td class="title_button"><?php require('button.php')?></td>
</tr>
</table>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_username') ?>: <font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_username" id="txt_username" value="<?php echo isset($mr['user_name'])?$mr['user_name']:''?>" size="30" onkeypress="return isUsername(this, event)" autofocus />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right">
	<?php if (!isset($mr['user_id'])) { ?>
        <?php echo Kohana::lang('account_lang.lbl_pass')?>:&nbsp;<font color="#FF0000">*</font>
    <?php } else { ?>
        <?php echo Kohana::lang('account_lang.lbl_new_pass')?>:
    <?php } ?>
    </div>
    <div class="yui3-u-4-5"><input tabindex="2" name="txt_pass" type="password" id="txt_pass" value="" size="30"></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_email') ?>:&nbsp;<font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5"><input tabindex="3" type="text" name="txt_email" id="txt_email" value="<?php echo isset($mr['user_email'])?$mr['user_email']:''?>" size="30"></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_role') ?>:</div>
    <div class="yui3-u-4-5">
    <select tabindex="4" name="sel_role" id="sel_role">
        <option value="0">&nbsp;</option>
        <?php 
		if(isset($mRole) && $mRole!=false){
		foreach($mRole as $role) {?>
        <option <?php echo (isset($mr['user_role']) && ($mr['user_role'] == $role['role_id'])) ? 'selected="selected"' :'' ?> value="<?php echo $role['role_id']?>"><?php echo $role['role_name']?></option>
        <?php } }//Role?>
    </select>
    </div>
</div>
<div class="yui3-g center"><?php require('button.php')?></div>
</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['user_id'])?$mr['user_id']:''?>"/>
</form>
<?php require('frm_js.php')?>
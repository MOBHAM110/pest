<?php
	$per = '';
	if(uri::segment(2) == 'edit')
		$per = 'edit';
	
	if(uri::segment(2) == 'create')
		$per = 'add';
	
	if(!$this->permisController($per)) {
?>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_permissions/search" method="post" >
<table cellspacing="0" cellpadding="0" class="title">
<tbody><tr>
    <td class="title_label"><?php echo Kohana::lang('permissions_lang.tt_page')?></td>
    <td class="title_button">
    	<?php if($this->permisController('add')) { ?>
        <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base() ?>admin_permissions/create'">
        <span><?php echo Kohana::lang('permissions_lang.btn_new') ?></span>
        </button>
        <?php } ?>
    </td> 
</tr>
</tbody></table>
<table cellspacing="1" cellpadding="5" class="list">
  <tbody>
      <tr class="list_header">
      	<td style="color:#900;font-weight:bold;"><?php echo Kohana::lang('global_lang.authorized') ?></td>
      </tr>
	</tbody>
</table>
</form>
<?php   		
	} else {
?>
<form name="frm" id="frm" action="<?php echo $this->site['base_url']?>admin_permissions/save" method="post" enctype="multipart/form-data" >
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('permissions_lang.tt_page')?></td>
    <td class="title_button">
    <button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_permissions'">
    <span><?php echo Kohana::lang('global_lang.btn_back')?></span>
    </button>
    <?php if($this->permisController('add')) { ?>
    <button type="submit" name="btn_save" class="button save">
    <span><?php echo Kohana::lang('global_lang.btn_save')?></span>
    </button>
    <?php } ?>
    </td>
  </tr>
</table>
<table class="form" align="center" cellpadding="5" cellspacing="0">
    <tr>
        <td width="10%">
		<?php echo Kohana::lang('permissions_lang.lbl_name')?>:<font color="#FF0000">*</font>
        </td>
        <td>
            <div style="float:left;">
            	<input value="<?php if (isset($mr['permissions_name'])) echo $mr['permissions_name']; ?>" name="txt_name" id="txt_name" size="30"/>
            </div>
        </td>
    </tr>
    <tr>
        <td>
		<?php echo Kohana::lang('permissions_lang.lbl_code')?>:<font color="#FF0000">*</font>
        </td>
        <td>
            <div style="float:left;">
            	<input value="<?php if (isset($mr['permissions_code'])) echo $mr['permissions_code']; ?>" name="txt_code" id="txt_nick" size="30"/>
            </div>
        </td>
    </tr>
    <tr>
        <td>
		<?php echo Kohana::lang('global_lang.lbl_action')?>:<font color="#FF0000">*</font>
        </td>
        <td>
            <div style="float:left;">
            	<select name="sel_status" id="sel_status">
                    <option value="1"><?php echo Kohana::lang('global_lang.lbl_active') ?></option>
                    <option value="0" <?php if(isset($mr) && $mr['permissions_status'] == 0) echo 'selected' ?>><?php echo Kohana::lang('global_lang.lbl_inactive') ?></option>      
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['permissions_id'])?$mr['permissions_id']:''?>"/></td>
    </tr>
</table>
</form>
<?php } ?>
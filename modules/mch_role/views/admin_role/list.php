<?php
	if(!$this->permisController('view')) {
?>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_role/search" method="post" >
<table cellspacing="0" cellpadding="0" class="title">
<tbody><tr>
    <td class="title_label"><?php echo Kohana::lang('role_lang.lbl_tt') ?></td>
    <td class="title_button">
    	<?php if($this->permisController('add')) { ?>
        <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base() ?>admin_role/create'">
        <span><?php echo Kohana::lang('role_lang.btn_new') ?></span>
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
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_role/search" method="post" >
<table cellspacing="0" cellpadding="0" class="title">
<tbody><tr>
    <td class="title_label"><?php echo Kohana::lang('role_lang.lbl_tt') ?></td>
    <td class="title_button">
        <?php if($this->permisController('add')) { ?>
        <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base() ?>admin_role/create'">
        <span><?php echo Kohana::lang('role_lang.btn_new') ?></span>
        </button>
        <?php } ?>
    </td> 
</tr>
</tbody></table>
<table cellspacing="0" class="list_top">
    <tbody>
        <tr>
            <td>
                <?php echo Kohana::lang('global_lang.lbl_search') ?>:
                <input name="txt_keyword" type="text" id="txt_keyword" value="">
                &nbsp;
                <button type="submit" name="btn_search" id="btn_search" class="button search">
                <span><?php echo Kohana::lang('global_lang.lbl_search') ?></span>
                </button>
            </td>
        </tr>
    </tbody>
</table>
<table cellspacing="1" cellpadding="5" class="list">
  <tbody>
      <tr class="list_header">
        <td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick="checkedAll(this.checked);"></td>

        <td><?php echo Kohana::lang('role_lang.lbl_name') ?></td>
        <td align="center" width="80"><?php echo Kohana::lang('global_lang.lbl_status') ?></td>
        <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
        <td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_action') ?></td>
        <?php } ?>
      </tr>
      <?php foreach($mlist as $id => $list){ ?>
      <tr class="row<?php if($id%2 == 0) echo 2 ?>">
        <td><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $list['role_id']?>"></td>
        <td>
			<a href="<?php echo url::base() ?>admin_role/edit/<?php echo $list['role_id'] ?>">	
			<?php echo $list['role_name'] ?>
            </a>
        </td>
        <td align="center">
        	<?php if($list['role_status'] == 1){ ?>
                <a href="<?php echo url::base() ?>admin_role/setstatus/<?php echo $list['role_id']?>/0">
                <img src="<?php echo url::base() ?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active') ?>" />
                </a>
            <?php } else { ?>
                <a href="<?php echo url::base() ?>admin_role/setstatus/<?php echo $list['role_id'] ?>/1">
                <img src="<?php echo url::base() ?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive') ?>" />
                </a>
			<?php } ?>
        </td>
        <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
        <td align="center">  
        	<?php if($this->permisController('edit')) { ?> 
            <a href="<?php echo url::base() ?>admin_role/edit/<?php echo $list['role_id'] ?>">	
            <img src="<?php echo url::base() ?>themes/admin/pics/icon_edit.png" title="<?php echo Kohana::lang('global_lang.btn_edit') ?>" />
            </a>  
            <?php } ?>
            <?php if($this->permisController('delete')) { ?>
            <a href="<?php echo url::base() ?>admin_role/delete/<?php echo $list['role_id'] ?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del'); ?>')" >	
            <img src="<?php echo url::base() ?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete') ?>" />
            </a>
            <?php } ?>
        </td>
        <?php } ?>
      </tr>
      <?php }?>
  </tbody>
</table>
<?php if($this->permisController('edit')) { ?> 
<table cellspacing="0" cellpadding="5" class="list_bottom">
    <tbody>
        <tr>
            <td>
                <select name="sel_action" id="sel_action">
                    <option value="inactive"><?php echo Kohana::lang('admin_lang.sel_inactive_selected') ?></option>
                    <option value="active"><?php echo Kohana::lang('admin_lang.sel_active_selected') ?></option>
                    <?php if($this->permisController('delete')) { ?>
                    <option value="delete"><?php echo Kohana::lang('admin_lang.sel_delete_selected') ?></option>
                    <?php } ?>
                  </select>
                  <button type="button" name="btn_update" id="btn_update" class="button save" onclick="check_action();"/>
                  <span><?php echo Kohana::lang('global_lang.btn_update') ?></span>
                  </button>
            </td>
        </tr>
    </tbody>
</table>
<?php } ?>
<div class='pagination'><?php echo $this->pagination?>Page: (<?php echo $this->pagination->current_page?>/<?php echo $this->pagination->total_pages?>), Total Row: <?php echo $this->pagination->total_items?></div>
</form>
<script type="text/javascript">
function check_action()
{
	chk = document.getElementsByName('chk_id[]');
	for (i=0;i<chk.length;i++)
	{
		if (chk[i].checked == true)
		{
			sm_frm(document.getElementById('frmlist'),'<?php echo url::base()?>admin_role/action','<?php echo Kohana::lang('errormsg_lang.msg_confirm_del') ?>');
			return true;
		}
	}
	alert('<?php echo Kohana::lang('errormsg_lang.msg_no_check')?>');
}
</script>
<?php } ?>
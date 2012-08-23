<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_customer/search" method="post" >
<table cellspacing="0" cellpadding="0" class="title">
<tr>
    <td class="title_label"><?php echo Kohana::lang('account_lang.tt_cus_account') ?></td>
    <td class="title_button">
	<?php if($this->permisController('add')) { ?>
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base() ?>admin_customer/create'"/>
    <span><?php echo Kohana::lang('account_lang.btn_new_account') ?></span>
    </button>
    <?php }//add ?>
    </td> 
</tr>
</table>
<table cellspacing="0" class="list_top">
  <tr>
    <td><?php echo Kohana::lang('account_lang.lbl_username') ?>:
      <input tabindex="1" name="txt_keyword" type="text" id="txt_keyword" value="<?php if(isset($keyword)) echo $keyword ?>" autofocus />
      &nbsp;
      <button type="submit" name="btn_search" id="btn_search" class="button search" />
      <span><?php echo Kohana::lang('global_lang.lbl_search') ?></span>
      </button>
      </td>
  </tr>
</table>
<table cellspacing="1" cellpadding="5" class="list">
  <tr class="list_header">
  	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' ></td>
    <?php }//edit,delete ?>
    <td><?php echo Kohana::lang('account_lang.lbl_username') ?></td>
    <td><?php echo Kohana::lang('account_lang.lbl_name') ?></td>
    <td><?php echo Kohana::lang('account_lang.lbl_email') ?></td>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td><?php echo Kohana::lang('account_lang.lbl_level')?>
    <td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_status') ?></td>
    <td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_action') ?></td>
    <?php }//edit,delete ?>
  </tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
  <tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['user_id']?>">
  	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $list['user_id']?>"></td>
    <?php }//edit,delete ?>
    <td><?php echo $list['user_name']?></td>
    <td><?php echo $list['customers_firstname'].' '.$list['customers_lastname']?></td>
    <td><?php echo $list['user_email']?></td>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td align="center">
        <a href="<?php echo url::base().uri::segment(1)?>/change_level/<?php echo $list['user_id']?>" style="text-decoration:none;">
        <?php if ($list['user_level']==3) { ?>
            <strong><font color="#000000"><?php echo Kohana::lang('account_lang.lbl_staff')?></font></strong>
        <?php } else { ?>
            <font color="#0000FF"><?php echo Kohana::lang('account_lang.lbl_registered')?></font>
        <?php } // end if ?>
        </a>
	</td>
    <td align="center">
    <?php if($list['user_status'] == 1){ ?>
	<a href="<?php echo url::base() ?>admin_customer/setstatus/<?php echo $list['user_id']?>/0/<?php echo $this->pagination->current_page?>">
	<img src="<?php echo url::base() ?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active') ?>" />
    </a>
	<?php } else { ?>
	<a href="<?php echo url::base() ?>admin_customer/setstatus/<?php echo $list['user_id'] ?>/1/<?php echo $this->pagination->current_page?>">
	<img src="<?php echo url::base() ?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive') ?>" />
    </a>
	<?php } ?>
    </td>
    <td align="center">  
    <a href="<?php echo url::base() ?>admin_customer/edit/<?php echo $list['user_id'] ?>" class="ico_edit">	<?php echo Kohana::lang('global_lang.btn_edit') ?></a>
    <?php if($this->permisController('delete')) { ?>
    <a id="delete_<?php echo $list['user_id']?>" href="javascript:delete_customer(<?php echo $list['user_id']?>);" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
    <?php }//delete ?>
    </td>
    <?php }//edit,delete ?>
  </tr>
  <?php } }//edit,delete ?>
</table>
<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
<table cellspacing="0" cellpadding="5" class="list_bottom">
  	<tr><td>
    <select name="sel_action" id="sel_action">
    <?php if($this->permisController('edit')) { ?>
    <option value="inactive"><?php echo Kohana::lang('admin_lang.sel_inactive_selected') ?></option>
    <option value="active"><?php echo Kohana::lang('admin_lang.sel_active_selected') ?></option>
    <?php }//edit ?>
    <?php if($this->permisController('delete')) { ?>
    <option value="delete"><?php echo Kohana::lang('admin_lang.sel_delete_selected') ?></option>
    <?php }//delete ?>
    </select>&nbsp;
    <button type="button" name="btn_update" id="btn_update" class="button save" onclick="javascript:check_action();"/>
    <span><?php echo Kohana::lang('global_lang.btn_update') ?></span>
    </button>
	</td></tr>
</table>
<?php }//edit,delete ?>
</form>
<div class='pagination'><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_customer/display">
<?php echo Kohana::lang('global_lang.lbl_display')?> #<select id="sel_display" name="sel_display" onchange="document.frm_display.submit();">
	<option value="">---</option>
    <option value="20" <?php echo !empty($display)&&$display==20?'selected="selected"':''?>>20</option>
    <option value="30" <?php echo !empty($display)&&$display==30?'selected="selected"':''?>>30</option>
    <option value="50" <?php echo !empty($display)&&$display==50?'selected="selected"':''?>>50</option>
    <option value="100" <?php echo !empty($display)&&$display==100?'selected="selected"':''?>>100</option>
    <option value="all" <?php echo !empty($display)&&$display=='all'?'selected="selected"':''?>><?php echo Kohana::lang('global_lang.lbl_all')?></option>
</select>
<?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($this->pagination)?$this->pagination->total_items:''?>
</form>
</div>
<?php require('list_js.php')?>
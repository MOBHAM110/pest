<form name="frmlist" id="frmlist" action="<?php echo $this->site['base_url']?>admin_support/search" method="post" >
<table class="title" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('support_lang.tt_page')?></td>
    <td class="title_button">
    <button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_page'"><span><?php echo Kohana::lang('global_lang.btn_back')?></span></button>
    <?php if($this->permisController('add')) { ?>
    <button type="button" name="btn_new" class="button new" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_support/create'">
    <span><?php echo Kohana::lang('support_lang.btn_new_support') ?></span>
    </button>
    <?php }//add?>
    </td>
  </tr>
</table>
  <table class="list_top" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo Kohana::lang('global_lang.lbl_search')?>:
    <input name="txt_keyword" type="text" id="txt_keyword" value="<?php if(isset($keyword)) echo $keyword ?>"/>
    &nbsp;
    <button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>
    </td>
  </tr>
</table> 
<table class="list" align="left" cellspacing="1" cellpadding="5">
  <tr class="list_header">
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td width="10">
    <input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);'>
    </td>
    <?php }//edit,delete?>
    <td><?php echo Kohana::lang('support_lang.lbl_name')?></td>
    <td align="center"><?php echo Kohana::lang('support_lang.lbl_nick')?></td>
    <td width="100" align="center"><?php echo Kohana::lang('support_lang.lbl_type')?></td>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_status')?></td>
    <td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_action')?></td>
    <?php }//edit,delete?>
  </tr> 
   <?php for($i=0; $i<count($mlist); $i++){ ?>
   <tr class="row<?php echo $i%2==0?2:''?>" id="row_<?php echo $mlist[$i]['support_id']?>">
	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td align="center">
    <input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $mlist[$i]['support_id']?>">
    </td>
    <?php }//edit,delete?>
    <td style="text-align:left;"><?php echo $mlist[$i]['support_name']?></td>
    <td align="center"><?php echo $mlist[$i]['support_nick']?></td>
    <td align="center">
	<?php if($mlist[$i]['support_type'] == 1){?>
    	<?php echo Kohana::lang('support_lang.lbl_yahoo')?>
	<?php }elseif($mlist[$i]['support_type'] == 2){?>
    	<?php echo Kohana::lang('support_lang.lbl_skype')?>
    <?php }elseif($mlist[$i]['support_type'] == 3){?>
    	<?php echo Kohana::lang('support_lang.lbl_hotline')?>
    <?php }//support_type?>
    </td>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
	<td align="center">
      <?php if($mlist[$i]['support_status'] == 1){ ?>
      <a href="<?php echo $this->site['base_url']?>admin_support/setstatus/<?php if(!empty($mlist[$i]['support_id'])) echo $mlist[$i]['support_id']?>/0" >
        <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active')?>" />
      </a>
      <?php } else { ?>    
      <a href="<?php echo $this->site['base_url']?>admin_support/setstatus/<?php  if(!empty($mlist[$i]['support_id'])) echo $mlist[$i]['support_id']?>/1" >
        <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive')?>" />
      </a>
      <?php } ?>
    </td>
    <td align="center">
    <a href="<?php echo $this->site['base_url']?>admin_support/edit/<?php echo $mlist[$i]['support_id']?>" class="ico_edit"><?php echo Kohana::lang('global_lang.btn_edit') ?></a>
    <?php if($this->permisController('delete')) { ?>
    <a id="delete_<?php echo $mlist[$i]['support_id']?>" href="javascript:delete_support(<?php echo $mlist[$i]['support_id']?>);" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
    <?php }//delete?>
    </td>
    <?php }//edit,delete?>
  </tr>
  <? } ?>
</table>
<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
<br clear="all" />
<table class="list_bottom" cellspacing="0" cellpadding="5">
  <tr>
    <td>
	<select name="sel_action" id="sel_action">
		<option value="block"><?php echo Kohana::lang('admin_lang.sel_block_selected')?></option>
		<option value="active"><?php echo Kohana::lang('admin_lang.sel_active_selected')?></option>
		<option value="delete"><?php echo Kohana::lang('admin_lang.sel_delete_selected')?></option>
	</select>
    &nbsp;
	<button type="button" name="btn_update" class="button save" onclick="check_action();"/>
    <span><?php echo Kohana::lang('global_lang.btn_update')?></span>
    </button>
    </td>
  </tr>
</table>
<?php }//edit,delete?>
</form>
<div class='pagination'><?php echo $this->pagination?>
<br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_support/display">
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
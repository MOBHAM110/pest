<form name="frmlist" id="frmlist" action="<?php echo $this->site['base_url'].'admin_'.$mr['page_type_name']?>/search/pid/<?php echo $mr['page_id']?>" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo $title?></td>
    <td class="title_button">
    <button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_page'"><span><?php echo Kohana::lang('global_lang.btn_back')?></span></button>
    <?php if($this->permisController('add')) { ?>
    <button type="button" name="btn_new" class="button new" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_<?php echo $this->mr['page_type_name']?>/create/pid/<?php echo $mr['page_id']?>'"/><span><?php echo Kohana::lang($this->mr['page_type_name'].'_lang.btn_create_new')?></span></button>
    <?php }//add?>
    </td>
  </tr>
</table>
<table class="list_top" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo Kohana::lang('global_lang.lbl_search')?>:
    <input type="text" name="txt_keyword" id="txt_keyword" value="<?php if(isset($keyword)) echo $keyword ?>"/>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>
    </td>
  </tr>
</table>	
<table class="list" cellspacing="1" cellpadding="5">
  <tr class="list_header">
  	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);'></td>
    <?php }//edit,delete?>
    <td><?php echo Kohana::lang('global_lang.lbl_title')?></td>
    <td width="80"><?php echo Kohana::lang('global_lang.lbl_order')?></td>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td width="50"><?php echo Kohana::lang('global_lang.lbl_status')?></td>
    <td width="80"><?php echo Kohana::lang('global_lang.lbl_action')?></td>
    <?php }//edit,delete?>
  </tr>
  <?php for($i=0; $i<count($mlist); $i++){ ?>
  <tr class="row<?php echo $i%2==0?2:''?>" id="row_<?php echo $mlist[$i]['bbs_id']?>">
  	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $mlist[$i]['bbs_id']?>"></td>
    <?php }//edit,delete?>
    <td style="text-align:left;">
    <?php {
				$expand = '';
				for ($j=1;$j<$mlist[$i]['bbs_level'];$j++)
            		$expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';				
			}
	?> 
    <?php echo $expand.$mlist[$i]['bbs_title']?>
    </td>
    <td align="center"><?php echo !empty($mlist[$i]['bbs_sort_order'])?$mlist[$i]['bbs_sort_order']:''?></td>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td align="center">
      <?php if($mlist[$i]['bbs_status'] == 1){ ?>
      <a href="<?php echo $this->site['base_url']?>admin_bbs/setstatus/id/<?php echo $mlist[$i]['bbs_id']?>" >
        <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active')?>" />
        </a>
      <?php } else { ?>    
      <a href="<?php echo $this->site['base_url']?>admin_bbs/setstatus/id/<?php echo $mlist[$i]['bbs_id']?>" >
        <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive')?>" />
        </a>
      <?php } ?>
    </td>
    <td align="center">
      <a href="<?php echo $this->site['base_url']?>admin_<?php echo $this->mr['page_type_name']?>/edit/id/<?php echo $mlist[$i]['bbs_id']?>" class="ico_edit"><?php echo Kohana::lang('global_lang.btn_edit')?></a>
      <a id="delete_<?php echo $mlist[$i]['bbs_id']?>" href="javascript:delete_bbs(<?php echo $mlist[$i]['bbs_id']?>);" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete')?></a>
    </td>
    <?php }//edit,delete?>
  </tr>
  <?php } ?>
</table>
<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
<table class="list_bottom" cellspacing="0" cellpadding="5">
  <tr>
    <td>
	<select name="sel_action" id="sel_action">
		<option value="0"><?php echo Kohana::lang('admin_lang.sel_block_selected')?></option>
		<option value="1"><?php echo Kohana::lang('admin_lang.sel_active_selected')?></option>
		<option value="delete"><?php echo Kohana::lang('admin_lang.sel_delete_selected')?></option>
	</select>
    &nbsp;
	<button type="button" name="btn_action" class="button save" onclick="check_action();"/>
    <span><?php echo Kohana::lang('global_lang.btn_update')?></span>
    </button>
    </td>
  </tr>
</table>
<?php }//edit,delete?>
</form>
<div class='pagination'><?php echo $this->pagination?>
<br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_<?php echo $mr['page_type_name']?>/search/pid/<?php echo $mr['page_id']?>">
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
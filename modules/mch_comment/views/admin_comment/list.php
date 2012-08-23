<form name="frmlist" id="frmlist" action="<?php echo $this->site['base_url']?>admin_comment/search" method="post" >
<table class="title" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('comment_lang.tt_page')?></td>
    <td class="title_button">
    <button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_page'"><span><?php echo Kohana::lang('global_lang.btn_back')?></span></button>
    </td>
  </tr>
</table>
  <table class="list_top" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo Kohana::lang('global_lang.lbl_search')?>:
    <input name="txt_keyword" type="text" id="txt_keyword" value="<?php if(isset($keyword)) echo $keyword ?>"/>
    &nbsp;
    <select name="sel_page"><option>--<?php echo Kohana::lang('comment_lang.lbl_select') ?>--</option><option value="album"><?php echo Kohana::lang('comment_lang.lbl_album') ?></option><option value="blog"><?php echo Kohana::lang('comment_lang.lbl_blog') ?></option></select>
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
    <?php }//edit,delete ?>
    <td width="190"><?php echo Kohana::lang('comment_lang.lbl_name')?></td>
    <td><?php echo Kohana::lang('comment_lang.lbl_content')?></td>
    <td width="20"></td>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_status')?></td>
    <td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_action')?></td>
    <?php }//edit,delete ?>
  </tr> 
   <?php for($i=0; $i<count($mlist); $i++){ ?>
   <tr class="row<?php echo $i%2==0?2:''?>" id="row_<?php echo $mlist[$i]['comment_id']?>">
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td align="center">
    <input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $mlist[$i]['comment_id']?>">
    </td>
    <?php }//edit,delete ?>
    <td style="text-align:left;"><a href="<?php echo $this->site['base_url']?>admin_comment/edit/<?php echo $mlist[$i]['comment_id']?>"><?php echo $mlist[$i]['comment_author']?></a></td>
    <td align="center"><a href="<?php echo $this->site['base_url']?>admin_comment/edit/<?php echo $mlist[$i]['comment_id']?>"><?php echo $mlist[$i]['comment_content']?></a></td>
    <td nowrap="nowrap">
    <?php if (strpos('bbs,news',$mlist[$i]['page_type_name']) !== FALSE) { ?>
	<a href="<?php echo $this->site['base_url']?><?php echo $mlist[$i]['page_type_name'] ?>/pid/<?php echo $mlist[$i]['page_id'] ?>/search/page/1/id/<?php echo $mlist[$i]['bbs_id'] ?>" target="_blank"><?php echo Kohana::lang('comment_lang.lbl_preview') ?></a>
	<?php } else { ?>
    <a href="<?php echo $this->site['base_url']?><?php echo $mlist[$i]['page_type_name'] ?>/pid/<?php echo $mlist[$i]['page_id'] ?>/detail/id/<?php echo $mlist[$i]['bbs_id'] ?>" target="_blank"><?php echo Kohana::lang('comment_lang.lbl_preview') ?></a>
    <?php } ?>
    </td>
	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td align="center">
      <?php if($mlist[$i]['comment_status'] == 1){ ?>
      	<a href="<?php echo $this->site['base_url']?>admin_comment/setstatus/<?php if(!empty($mlist[$i]['comment_id'])) echo $mlist[$i]['comment_id']?>/0" >
        <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active')?>" />
        </a>
      <?php } else { ?>    
      	<a href="<?php echo $this->site['base_url']?>admin_comment/setstatus/<?php  if(!empty($mlist[$i]['comment_id'])) echo $mlist[$i]['comment_id']?>/1" >
        <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive')?>" />
        </a>
      <?php } ?>
    </td>
    <td align="center"><a href="<?php echo $this->site['base_url']?>admin_comment/edit/<?php echo $mlist[$i]['comment_id']?>" class="ico_edit"><?php echo Kohana::lang('global_lang.btn_edit') ?></a>
	<?php if($this->permisController('delete')) { ?>
    <a id="delete_<?php echo $mlist[$i]['comment_id']?>" href="javascript:delete_comment(<?php echo $mlist[$i]['comment_id']?>);" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
    <?php }//delete ?>
    </td>
    <?php }//edit,delete ?>
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
<?php }//edit,delete ?>
</form>
<div class='pagination'><?php echo $this->pagination?>
<br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_comment/display">
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
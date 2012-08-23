<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo $mr['title']?></td>
    <td class="title_button">    
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base().uri::segment(1)?>/create'"/>
    <span><?php echo Kohana::lang('themes_templates_lang.btn_new_template') ?></span>
    </button>
    </td>
  </tr>
</table>
<!--
<form name="frmlist" id="frmlist" action="<?php echo url::base().uri::segment(1)?>/search" method="post">
<table class="list_top" cellspacing="0" cellpadding="0">
<tr>
    <td><?php echo Kohana::lang('global_lang.lbl_search') ?>:
    <input name="txt_keyword" type="text" id="txt_keyword" value="<?php if(isset($keyword)) echo $keyword ?>"/>
    <button name="btn_search" id="btn_search" class="button search" type="submit"/>
    <span><?php echo Kohana::lang('global_lang.btn_search') ?></span>
    </button>
    </td>
</tr>
</table>
</form>
-->
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr class="list_header">
    <td><?php echo Kohana::lang('account_lang.lbl_name') ?></td>
    <td><?php echo Kohana::lang('themes_templates_lang.lbl_dir') ?></td>
    <td><?php echo Kohana::lang('global_lang.lbl_status')?></td>
    <td><?php echo Kohana::lang('global_lang.lbl_action')?></td>
</tr>
<?php foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>">
    <td align="center"><?php echo $list['templates_name']?></td>
    <td align="center"><?php echo $list['templates_dir']?></td>
    <td align="center">
        <a href="javascript:location.href='<?php echo url::base().uri::segment(1)?>/change_default/<?php echo $list['templates_id']?>'">          
            <img src="<?php echo url::base()?>themes/admin/pics/<?php echo $list['templates_dir']==$this->site['config']['TEMPLATE']?'':'un'?>check.png">	 
        </a>
    </td> 
    <td align="center">
        <a href="<?php echo url::base().uri::segment(1)?>/edit/<?php echo $list['templates_id'] ?>">
        	<img src="<?php echo url::base() ?>themes/admin/pics/icon_edit.png" title="<?php echo Kohana::lang('global_lang.btn_edit') ?>" />
        </a>
        <a href="<?php echo url::base().uri::segment(1)?>/delete/<?php echo $list['templates_id']?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del'); ?>')">
        	<img src="<?php echo url::base() ?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete') ?>" />
        </a>
    </td>
</tr>
<?php } // end foreach ?>
</table>
<!-- 
<table class="list_bottom" cellspacing="0" cellpadding="5">
<tr>
    <td>
        <select name="sel_action" id="sel_action">
            <option value="inactive"><?php echo Kohana::lang('global_lang.sel_inactive_selected') ?></option>
            <option value="active"><?php echo Kohana::lang('global_lang.sel_active_selected') ?></option>
            <option value="delete"><?php echo Kohana::lang('global_lang.sel_delete_selected') ?></option>
        </select>
        <button name="btn_update" id="btn_update" class="button save" onclick="sm_frm(frmlist,'<?php echo url::base().uri::segment(1)?>/action','<?php echo Kohana::lang('errormsg_lang.msg_confirm_del') ?>');"/>
        <span><?php echo Kohana::lang('global_lang.btn_update') ?></span>
        </button>
    </td>
</tr>
</table> -->
<div class='pagination'><?php echo $this->pagination?>Page: (<?php echo $this->pagination->current_page?>/<?php echo $this->pagination->total_pages?>), Total Row: <?php echo $this->pagination->total_items?></div>
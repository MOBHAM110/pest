<table class="bbs" align="center" cellpadding="0" cellspacing="0">
<?php if(isset($mr['page_content']) && $mr['page_content']!==''){?>
<tr><td class="bbs_top"><?php echo $mr['page_content']?></td></tr>
<?php } // end if have page content ?>
<?php if (isset($mr['bbs_id'])) { ?>
<tr>
    <td class="bbs_middle">
    <table class="bbs_item" align="center" cellspacing="0" cellpadding="5">
    <tr><td>
        <table align="center" cellpadding="0" cellspacing="0">
        <tr><td align="left" class="bbs_item_TT"><div style="float:left;"><?php echo $mr['bbs_title']?></div>
        <!-- AddThis Button BEGIN -->
        <div style="float:left;padding-left:5px;" class="addthis_toolbox addthis_default_style"><a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c9329f918eb26eb" class="addthis_button_compact">Share</a></div><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c9329f918eb26eb"></script>
        <!-- AddThis Button END -->
        </td>
        <td align="right">
        </td></tr>
        </table>
    </td>
    </tr>
    <tr>
      <td class="bbs_item_Date"><?php echo (empty($mr['bbs_author']))?Kohana::lang('client_lang.lbl_anonymous').' - ':$mr['bbs_author'].' - '?><?php echo $mr['bbs_date'] ?></td>
    </tr>
    <?php if (empty($mr['bbs_password']) || (!empty($this->sess_cus) && $this->sess_cus['level'] < 3)
            || ($this->sess_cus['username'] == $mr['bbs_author'])) { ?>
    <?php if (count($mr['bbs_file']) > 0) { ?>
    <tr>
        <td class="bbs_item_File">
        <?php foreach ($mr['bbs_file'] as $file) { ?>
            <?php $file_type = ORM::factory('file_type_orm', $file->file_type_id)->file_type_detail?>
            <a href="javascript:location.href='<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/download/<?php echo $file->bbs_file_id?>'">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_<?php echo $file_type?>.png" border="0" width="16" height="16"/></a> | <a href="javascript:location.href='<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/download/<?php echo $file->bbs_file_id?>'"><?php echo Kohana::lang('global_lang.lbl_download')?></a> : <?php echo $file->bbs_file_download?><br />       	
        <?php } // end foreach ?>
        </td>
    </tr>
    <?php } // end if ?>
    <tr><td class="bbs_item_Des"><?php echo $mr['bbs_content']?></td></tr>
    <?php } else { ?>
    <form name="bbs_password" action="<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/view_with_password/id/<?php echo $mr['bbs_id']?>" method="post">
    <tr>
        <td class="bbs_item_Pass"><?php echo Kohana::lang('global_lang.lbl_pass')?>&nbsp;<input type="password" name="txt_pass" />&nbsp;<button type="submit"><?php echo Kohana::lang('global_lang.btn_view')?></button></td>
    </tr>
    </form>
    <?php } // end if bbs password ?>
    </table>
    </td>
</tr>
<?php } // end if ?>
<tr>
	<td class="bbs_btn">
    <?php if ((!empty($this->sess_cus) && (($this->sess_cus['username'] == $mr['bbs_author'] && !empty($mr['bbs_author'])) || $this->sess_cus['level'] < 3)) || $mr['page_write_permission'] == 5) {?>
    <button onclick="javascript:update_bbs('edit')"><?php echo Kohana::lang('global_lang.btn_edit')?></button>
	<button onclick="javascript:update_bbs('delete')"><?php echo Kohana::lang('global_lang.btn_delete')?></button>
    <?php }//edit/del ?>
	<?php if (!empty($this->sess_cus)) { ?>
    <?php if ($this->sess_cus['level'] <= $mr['page_write_permission']) { ?>
        <?php if (isset($mr['bbs_id'])) { ?>    	
        <button onclick="javascript:location.href='<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/create/id/<?php echo $mr['bbs_id']?>'"><?php echo Kohana::lang('bbs_lang.btn_reply')?></button><?php } // end if ?>&nbsp;
        <button onclick="javascript:location.href='<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/create'"><?php echo Kohana::lang('bbs_lang.btn_post_bbs')?></button>   	
    <?php } // end if registered read permission ?>
    <?php } elseif ($mr['page_write_permission'] == 5) { ?>
        <?php if (isset($mr['bbs_id'])) { ?>    	
        <button onclick="javascript:location.href='<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/create/id/<?php echo $mr['bbs_id']?>'"><?php echo Kohana::lang('bbs_lang.btn_reply')?></button><?php } // end if ?>&nbsp;
        <button onclick="javascript:location.href='<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/create'"><?php echo Kohana::lang('bbs_lang.btn_post_bbs')?></button>   	
    <?php } // end if ?>
	</td>
</tr>
<tr><td class="bbs_list">
	<table align="center" cellspacing="0" cellpadding="2" width="95%" class="bbs_list">
	<tr>
		<th scope="col" ><?php echo Kohana::lang('global_lang.lbl_title')?></th>
		<th scope="col"><?php echo Kohana::lang('global_lang.lbl_date')?></th>
		<?php if (isset($this->sess_cus['id'])) { ?><th scope="col" nowrap="nowrap" width="100px"><?php echo Kohana::lang('global_lang.lbl_action')?></th><?php } // if logined ?>
	</tr>
	<?php foreach ($mlist as $id => $ml) { ?>
	<?php $expand = ''?>
	<?php for ($j=1;$j<$ml['bbs_level'];$j++) { $expand .= '&nbsp;&nbsp;&nbsp;'; }?>
	<tr>
		<td align="left">
		<?php if (isset($mr['bbs_id']) && $mr['bbs_id'] == $ml['bbs_id']) { ?>
            <?php echo $expand?><img src="<?php echo $this->site['theme_url']?>index/pics/icon_light.gif" border="0" ><b><?php echo $ml['bbs_title']?></b>
        <?php } else { ?>
           <a href="<?php echo url::base()?>bbs-<?php echo $this->page_id?>-<?php echo $this->pagination->current_page?>-<?php echo $ml['bbs_id']?>/<?php echo MyFormat::title_url($ml['bbs_title']);?>">
           <?php echo $expand?><img src="<?php echo $this->site['theme_url']?>index/pics/icon_dark.gif" border="0"><?php echo $ml['bbs_title']?>
            </a>
        <?php } ?>
		</td>
		<td align="center"><?php echo $ml['bbs_date']?></td>
		<?php if (!empty($this->sess_cus)) { ?>
		<td align="center">
		<?php if (($this->sess_cus['username'] == $ml['bbs_author'] && !empty($ml['bbs_author'])) || $this->sess_cus['level'] < 3) { ?>&nbsp;
            <a href="<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/edit/id/<?php echo $ml['bbs_id']?>">
            <img border="0" width="16" height="16" src="<?php echo $this->site['theme_url']?>index/pics/icon_edit.png" alt="Click here to edit" />
            </a>
            <a href="<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/delete/id/<?php echo $ml['bbs_id']?>">
            <img border="0" width="16" height="16" src="<?php echo $this->site['theme_url']?>index/pics/icon_del.png" alt="Click here to delete" />
            </a>
        <?php } // end if ?>
		</td>
		<?php } // if logined ?>
	</tr>
	<?php } // end foreach ?>
	<tr <?php if(Configuration_Model::get_value('ENABLE_AKCOMP')) echo 'style="display:none"'?> ><td class="bbs_list_search" colspan="3">                
        <form name="frmlist" id="frmlist" action="<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/search" method="post">        <select name=sel_type>
            <option value='' > - - - </option>
            <option value='1' <?php echo isset($mr['type1_selected'])?$mr['type1_selected']:''?> ><?php echo Kohana::lang('global_lang.lbl_title')?></option>
            <option value='2' <?php echo isset($mr['type2_selected'])?$mr['type2_selected']:''?> ><?php echo Kohana::lang('global_lang.lbl_author')?></option>
            <option value='3' <?php echo isset($mr['type3_selected'])?$mr['type3_selected']:''?> ><?php echo Kohana::lang('global_lang.lbl_content')?></option>
        </select>
        <input type="text" name="txt_keyword" id="txt_keyword" value="<?php echo isset($mr['keyword'])?$mr['keyword']:''?>" >&nbsp;
        <button type="submit" class="button"/><span><?php echo Kohana::lang('global_lang.btn_search')?></span></button>
        </form>
	</td></tr>
	</table>
</td></tr>
<tr><td class="bbs_pagination">
<div class='pagination'><?php echo $this->pagination?></div>
<?php echo Kohana::lang('global_lang.lbl_page')?>: (<?php echo $this->pagination->current_page?>/<?php echo $this->pagination->total_pages?>), <?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo $this->pagination->total_items?>
</td></tr>
<tr><td class="bbs_bottom"></td></tr>
</table>
<?php require('list_js.php')?>
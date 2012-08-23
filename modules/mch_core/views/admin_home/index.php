<form name="frm" id="frm" action="<?php echo url::base()?>admin_home/save" method="post">
<table id="float_table" cellspacing="0" cellpadding="0" class="title">
<tr>
    <td class="title_label"><?php echo Kohana::lang('home_lang.tt_page')?></td>
    <td align="right"><?php require('button.php');?></td>
</tr>
</table>
<table class="list" cellspacing="1" cellpadding="5">
<tr class="list_header">
	<td width="10%" align="center">
    <?php if (empty($this->site['config']['HOME_NUM_ROW'])) { ?>
    	<a href="<?php echo url::base()?>admin_home/add_col">
        	<img src="<?php echo $this->site['theme_url']?>pics/icon_plus.png" title="<?php echo Kohana::lang('home_lang.btn_add_col')?>"/>
        </a>
    <?php } else { ?>
    	<?php echo Kohana::lang('home_lang.lbl_row').' / '.Kohana::lang('home_lang.lbl_col')?>
    <?php } // end if ?>
    </td>
    <?php for ($i=1;$i<=$this->site['config']['HOME_NUM_COL'];$i++) { ?>
    <td align="center"><?php echo Kohana::lang('home_lang.lbl_col').' '.$i?>
    	<?php if ($i == $this->site['config']['HOME_NUM_COL'] && empty($this->site['config']['HOME_NUM_ROW'])) { ?>
            <a href="<?php echo url::base()?>admin_home/del_col">
            <img src="<?php echo $this->site['theme_url']?>pics/icon_delete.png" title="<?php echo Kohana::lang('home_lang.btn_del_col')?>"/>
            </a>
        <?php } // end if ?>
    </td>
    <?php } // end foreach ?>
    <td width="80"><?php echo Kohana::lang('global_lang.lbl_action')?></td>
</tr>
<?php for ($i=1;$i<=$this->site['config']['HOME_NUM_ROW'];$i++) { ?>
<?php $list_home = ORM::factory('home_orm')->where('home_row',$i)->find_all()->as_array()?>
<tr class="row<?php echo $i%2==0?2:''?>">
	<td align="center"><strong><?php echo Kohana::lang('home_lang.lbl_row').' '.$i?></strong></td>
    <?php for ($j=1;$j<=$this->site['config']['HOME_NUM_COL'];$j++) { ?>
    <td align="center">
    	<select name="sel_page_<?php echo $i?>_<?php echo $j?>" onchange="check_page_type(this);">
        	<option value="-1" <?php echo $list_home[$j-1]->page_id==-1?'selected':''?>><?php echo Kohana::lang('global_lang.sel_none')?></option>
            <?php $txt_disable = false?>
        	<?php foreach ($list_page as $page) { ?>            
        	<option value="<?php echo $page['page_id']?>" <?php echo $list_home[$j-1]->page_id==$page['page_id']?'selected="selected"':''?>><?php echo $page['page_title']?>&nbsp;[<?php echo $page['page_type_name']?>]</option>
            <?php if ($list_home[$j-1]->page_id==$page['page_id'] && $page['page_type_name'] !=='general') { ?>
				<?php $txt_disable = $list_home[$j-1]->num_row?>
			<?php } // end if ?>
            <?php if ($list_home[$j-1]->page_id==$page['page_id'] && $page['page_type_name']=='general') { ?><?php $txt_disable = true?><?php } ?>
            <?php if ($list_home[$j-1]->page_id==$page['page_id'] && $page['page_type_name']=='tab') { ?><?php $txt_disable = true?><?php } ?>
            <?php if ($list_home[$j-1]->page_id==-1) { ?><?php $txt_disable = true?><?php } ?>
            <?php } // end foreach list_page?>
        </select>&nbsp;
        <input type="text" size="2" name="txt_row_<?php echo $i?>_<?php echo $j?>" style="text-align:center;" id="txt_row_<?php echo $i?>_<?php echo $j?>"
        <?php echo $txt_disable===true?'disabled="disabled"':''?> value="<?php echo $txt_disable===true?'0':$txt_disable?>">
    </td>
    <?php } // end foreach ?>
    <td align="center" width="10%">
	<?php if($this->permisController('delete')) { ?>
    <?php if ($i == $this->site['config']['HOME_NUM_ROW']) { ?>
    <a href="<?php echo url::base()?>admin_home/del_row" class="ico_delete"><?php echo Kohana::lang('home_lang.btn_del_row')?></a>
    <?php } // end if ?>
    <?php }//delete?>
	</td>
</tr>
<?php } // end foreach ?>
<tr>
	<td colspan="<?php echo $this->site['config']['HOME_NUM_COL']+1?>">&nbsp;</td>
    <td align="center" colspan="2">
    <?php if($this->permisController('add')) { ?>
    <?php if($this->site['config']['HOME_NUM_COL'] > 0) { ?>
    <a href="<?php echo url::base()?>admin_home/add_row">
    <img src="<?php echo $this->site['theme_url']?>pics/icon_plus.png" title="<?php echo Kohana::lang('home_lang.btn_add_row')?>"/>
    </a>
    <?php } //end if ?>
    <?php }//add?>
	</td>
</tr>
<tr><td colspan="3" align="center"><?php require('button.php');?></td></tr>
</table>
</form>
<?php require('frm_js.php');?>
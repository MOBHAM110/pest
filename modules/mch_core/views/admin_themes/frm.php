<form name="frm" action="<?php echo url::base().uri::segment(1)?>/save" method="post" enctype="multipart/form-data">
<table class="title" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="title_label"><?php echo $mr['title']?></td>
    <td class="title_button">
    <button name="btn_back" type="button" class="button back" onclick="javascript:location.href='<?php echo url::base().uri::segment(1)?>'">
    	<span><?php echo Kohana::lang('global_lang.btn_back')?></span>
    </button>
    <button name="btn_reset" type="reset" class="button reset"/>
    	<span><?php echo Kohana::lang('global_lang.btn_reset')?></span>
    </button>
    <button name="btn_save" class="button save" type="submit">
    	<span><?php echo Kohana::lang('global_lang.btn_save')?></span>
    </button>
    <button type="submit" name="btn_save_add" class="button save">
    	<span><?php echo Kohana::lang('global_lang.btn_save_add')?></span>
	</button>
    </td>
  </tr>
</table>

<table class="form" cellspacing="0" cellpadding="5">
<tr>
    <td width="10%"><?php echo Kohana::lang('account_lang.lbl_name')?><font color="#FF0000">*</font></td>
    <td>
    	<input name="txt_name" type="text" id="txt_name" value="<?php echo (isset($mr['themes_name']))?$mr['themes_name']:''?>" size="50"> 
    </td>
</tr>
<?php if (isset($mr['themes_dir'])) { ?>
<tr>
    <td><?php echo Kohana::lang('themes_templates_lang.lbl_dir')?></td>
    <td><font color="#FF0000"><b><?php echo $mr['themes_dir']?></b></font></td>
</tr>
<?php } // end if ?>
<tr>
    <td><?php echo Kohana::lang('global_lang.lbl_file')?><font color="#FF0000">*</font></td>
    <td>
    	<input name="attach_file" type="file">
    </td>
</tr>
<tr>
    <td><?php echo Kohana::lang('global_lang.lbl_status')?></td>
    <td>
    	<select name="sel_status">
        	<option value="1"><?php echo Kohana::lang('global_lang.lbl_active')?></option>
            <option value="0" <?php echo (isset($mr['themes_status'])&&!$mr['themes_status'])?'selected="selected"':''?>>
				<?php echo Kohana::lang('global_lang.lbl_inactive')?>
			</option>
        </select>
	</td>
</tr>
</table>

<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['themes_id'])?$mr['themes_id']:''?>"/>
</form>

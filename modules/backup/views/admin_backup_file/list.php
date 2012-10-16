<form name="frmlist" id="frmlist" action="<?php echo $this->site['base_url']?>admin_backup_file/restore" method="post" enctype="multipart/form-data">

<table width="100%" height="40" border="0" cellspacing="0" cellpadding="0" class="title">
	<tr>
    	<td class="text_tt"><?php echo Kohana::lang('backup_restore_lang.tt_page_file')?></td>
        <td align="right"> 
       <button type="button" name="btn_backup" class="button save" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_backup_file/backup'"/>
    <span><?php echo Kohana::lang('backup_restore_lang.btn_backup_file')?></span>    </button>
        </td>
    </tr>
</table>
<table cellspacing="1" cellpadding="5" class="list">
 <tr class="list_header">
<!-- 	<td><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' ></td>-->
    <td><?php echo Kohana::lang('backup_restore_lang.lbl_file_name')?></td>
    <td><?php echo Kohana::lang('backup_restore_lang.lbl_size')?></td>
     <td><?php echo Kohana::lang('backup_restore_lang.lbl_date_time')?></td>
    <td width="80" align="center"><?php echo Kohana::lang('backup_restore_lang.lbl_type')?></td>
     <td width="150" align="center"><?php echo Kohana::lang('backup_restore_lang.lbl_action')?></td>
 </tr>
 <?php 
 $dir='backups/';
 $dirhead=false;
 //var_dump($arrayFile); die();
	 foreach($arrayFile as $dirfile)	
	 { 
			
			if ($dirfile != "." && $dirfile != ".." && $dirfile!=basename($_SERVER["SCRIPT_FILENAME"]))
			{
			if (!$dirhead){$dirhead=true;}
			$filenameArr = explode('.',$dirfile);
			$filename = $filenameArr[0];
				
?>
			
			<tr class="row2">
<!--            	<td align="center"><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $filename?>"></td>-->
            	<td align="center"><?php echo $dirfile; ?></td>
                <td align="center"><?php echo  number_format(filesize($dir.$dirfile)/1024); ?>&nbsp;MB</td>
                <td align="center"><?php  echo date("m-d-Y H:i:s",filemtime($dir.$dirfile)) ;?></td>
                <td align="center">
                	<?php
						 if (preg_match("/\.sql$/i",$dirfile)) echo 'SQL';
						 elseif (preg_match("/\.gz$/i",$dirfile))echo "GZip";
						 elseif (preg_match("/\.zip$/i",$dirfile))echo "Zip";
						 elseif (preg_match("/\.csv$/i",$dirfile))echo "CSV";
						 else echo "Misc";
					 ?>
                </td>
               
                	<?php if (((preg_match("/\.zip$/i",$dirfile) || preg_match("/\.gz$/i",$dirfile) ) && function_exists("gzopen")) || preg_match("/\.sql$/i",$dirfile) || preg_match("/\.csv$/i",$dirfile)){?>
                <td align="center">
                		<a href="<?php echo url::base();?>admin_backup_file/restore/<?php echo $filename ?>" onclick="return confirm('<?php echo Kohana::lang('backup_restore_lang.lbl_restore')?>')">
                             <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_restore.png" title="Restore" />
                        </a>&nbsp;
                        <a href="<?php echo url::base(); echo $dir?><?php echo urlencode($dirfile) ?>">
                             <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_down.png" title="Download" />
                        </a>&nbsp;
                       <a href="<?php echo url::base()?>admin_backup_file/delete/<?php echo $filename;?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')">
                        	<img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete')?>" /></a>
                </td>
                <?php } else { ?>
                	<td >&nbsp;</td>
                <?php } ?>
           </tr>
			
 
 <?php 
 
 	}
  
 } ?>	
 
</table>
<!--<table class="list_bottom" cellspacing="0" cellpadding="5">
  <tr>
    <td>
	<select name="sel_action" id="sel_action">
		<option value="delete"><?php echo Kohana::lang('admin_lang.sel_delete_selected')?></option>
	</select>
    &nbsp;
	<button type="button" name="btn_update" class="button save" onclick="sm_frm(frmlist,'<?php echo $this->site['base_url']?>admin_backup_file/deleteall','<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>');"/>
    <span><?php echo Kohana::lang('global_lang.btn_update')?></span>
    </button>
    </td>
  </tr>
</table>-->
</form>

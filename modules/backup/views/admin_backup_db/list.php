<form action="<?php echo url::base()?>admin_backup_db/backupsm" method="post">
<table width="100%" height="40" border="0" cellspacing="0" cellpadding="0" class="title">
	<tr>
    	<td class="text_tt"><?php echo Kohana::lang('backup_restore_lang.tt_page_db')?></td>
        <td align="right">  
<!--            <button type="submit" name="btn_submit" class="button save" />
            <span><?php echo Kohana::lang('backup_restore_lang.btn_backup_db')?></span>
            </button>-->
            <button id="btn_backup" type="button" name="btn_submit" class="button save" />
            <span><?php echo Kohana::lang('backup_restore_lang.btn_backup_db')?></span>
            </button>
    	</td>
    </tr>
</table>
</form>
<form method="POST" action="<?php echo url::base()?>admin_backup_db/showlist" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo isset($mr['upload_max_filesize'])?$mr['upload_max_filesize']:''?>">
<p><?php echo Kohana::lang('backup_restore_lang.lbl_dump_file')?>&nbsp;<input type="file" name="dumpfile" accept="*/*" size="20">
	<button type="submit" name="uploadbutton" class="button upfile" value="Upload"><span><?php echo Kohana::lang('backup_restore_lang.btn_upload')?></span></button></p>
</form>
<table width="100%" border="0" cellspacing="0" class="tbl_list_top">
	<tr>
	  <td nowrap>
      		
      </td>
    </tr>
	<tr>
    	<td nowrap>
      
        </td>
    </tr>
</table>
<table cellspacing="1" cellpadding="5" class="list">
 <tr class="list_header">
    <td><?php echo Kohana::lang('backup_restore_lang.lbl_file_name')?></td>
    <td><?php echo Kohana::lang('backup_restore_lang.lbl_size')?></td>
     <td><?php echo Kohana::lang('backup_restore_lang.lbl_date_time')?></td>
    <td width="80" align="center"><?php echo Kohana::lang('backup_restore_lang.lbl_type')?></td>
     <td width="150" align="center"><?php echo Kohana::lang('backup_restore_lang.lbl_action')?></td>
 </tr>
 <?php 
 $ignore = array("cgi-bin", ".", "..", "Thumbs.db");
 $dirs = array();
 $files = array();
 $target = $mr['upload_dir'];
 if(isset($target)){
    if($dir = opendir($target)){
        $dirhead=false;
            while (($file = readdir($dir)) !== false){
                if(!in_array($file, $ignore)){
                    if(is_dir("$target/$file")){
                        array_push($dirs, "$target/$file");
                    }
                    else{
                        array_push($files, "$target/$file");
                        if (!$dirhead){$dirhead=true;}
                    }

                }
            }

            //Sort
            sort($dirs);
            arsort($files);
            $all = array_unique($files);

            foreach ($all as $value){
                $filename = explode('/',$value);
                $filename = $filename[count($filename)-1];
            ?>
            <tr class="row2">
            	<td align="center"><?php echo $filename; ?></td>
                <td align="center"><?php echo  number_format(filesize($value)).'&nbsp;KB'; ?></td>
                <td align="center"><?php  echo date("m-d-Y H:i:s",filemtime($value)) ;?></td>
                <td align="center">
                	<?php
						 if (preg_match("/\.sql$/i",$filename)) echo 'SQL';
						 elseif (preg_match("/\.gz$/i",$filename))echo "GZip";
						 elseif (preg_match("/\.zip$/i",$filename))echo "Zip";
						 elseif (preg_match("/\.csv$/i",$filename))echo "CSV";
						 else echo "Misc";
					 ?>
                </td>
               
                	<?php if (((preg_match("/\.zip$/i",$filename) || (preg_match("/\.gz$/i",$filename))) && function_exists("gzopen")) || preg_match("/\.sql$/i",$filename) || preg_match("/\.csv$/i",$filename)){?>
                <td align="center">
                    <a id="btn_restore" url="<?php echo url::base()?>admin_backup_db/restore/<?php echo urlencode($filename) ?>" >
                             <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_restore.png" title="start restore" />
                        </a>&nbsp;
                        <a href="<?php echo url::base()?>uploads/dbbackup/<?php echo urlencode($filename) ?>">
                             <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_download.png" title="Download file" />
                        </a>&nbsp;
                       <a href="<?php echo url::base()?>admin_backup_db/delete/<?php echo urlencode($filename);?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')">
                        	<img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete')?>" /></a>
               
                </td>
                <?php } else { ?>
                	<td >&nbsp;</td>
                <?php } ?>
           </tr>
            <?php
            if ($dirhead) echo ('');
   		    else echo ("<tr class='row2'><td align='center' colspan='6'>No uploaded files found in the working directory</td></tr>");
			
            }
        }
        closedir($dir);
    }?>	
</table>
<link href="<?php echo url::base()?>plugins/jquery.tMessage/tTopMessage.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery.tMessage/tTopMessage.js"></script>
<script>
    $(function() {
        $("#btn_restore").live("click",function(){
            if (confirm("Do you want to restore?")) { 
                $(document).tTopMessage({load: true, type : 'loading', text : 'restoring...', effect : 'slide'});
                var url = $(this).attr('url');
				$.ajax({ 
					url: url, 
					//data: { type: frm, id: relativeId }, 
					//dataType: 'json', 
					type: 'post', 
					success: function () { 
					} 
				}).done(function(){
                    $(document).tTopMessage({load: false, type : 'loading', text : 'restoring...', effect : 'slide'});
                    location.reload();
                });
			}
        });
        
        $("#btn_backup").live("click",function(){
            if (confirm("Do you want to backup?")) { 
                $(document).tTopMessage({load: true, type : 'loading', text : 'saving...', effect : 'slide'});
                var url = '<?php echo url::base()?>admin_backup_db/backupsm';
				$.ajax({ 
					url: url, 
					//data: { type: frm, id: relativeId }, 
					//dataType: 'json', 
					type: 'post', 
					success: function () { 
					} 
				}).done(function(){
                    $(document).tTopMessage({load: false, type : 'loading', text : 'saving...', effect : 'slide'});
                    location.reload();
                });
			}
        });
        
        
    });
</script>
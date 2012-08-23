<form name="frm" action="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/save" method="post" enctype="multipart/form-data">
<table width="100%" class="album_create" align="center" cellpadding="0" cellspacing="0">
<tr>
    <td class="album_create_T"><?php echo $title?></td>
</tr>
<tr>
	<td class="album_create_M">
    	<table width="100%" cellpadding="0" cellspacing="0" class="album_create_M_Co">
        <tr>
        	<td>   
                <ul id="tabs_album" class="shadetabs">
                <li><a href="#" rel="info"><?php echo Kohana::lang('global_lang.lbl_info')?></a></li>
                <?php if (isset($mr['bbs_id'])) { ?>
                <li><a href="#" rel="list"><?php echo Kohana::lang('album_lang.lbl_list_file')?></a></li>
                <li><a href="#" rel="upload"><?php echo Kohana::lang('global_lang.lbl_upload')?></a></li>
                <?php } // end if ?>
                </ul>
                <div style="border:1px solid gray; background-color: #EAEAEA; padding: 5px">
                    <div id="info" class="tabcontent">
                    <?php require('info.php')?>
                    </div>
                    <?php if (isset($mr['bbs_id'])) { ?>
                    <div id="list" class="tabcontent">
                    <?php require('list_file.php')?>
                    </div>
                    <div id="upload" class="tabcontent">
                    <?php require('upload.php')?>
                    </div>
                    <?php } // end if ?>
                </div>
			</td>
		</tr>
        <tr style="height:30px;">
        	<td align="center">
            <button type="submit" name="save_album"><?php echo Kohana::lang('global_lang.btn_save')?></button>&nbsp;
            <button type="button" name="back" onclick="javascript:location.href='<?php echo url::base()?>album/pid/<?php echo $this->page_id?>'"><?php echo Kohana::lang('global_lang.btn_back')?></button>
			</td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td class="album_create_B"></td>
</tr>
</table>

<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['bbs_id'])?$mr['bbs_id']:''?>"/>
</form>

<link rel="stylesheet" type="text/css" href="<?php echo url::base()?>plugins/tabcontent/tabcontent.css">
<script type="text/javascript" src="<?php echo url::base()?>plugins/tabcontent/tabcontent.js"></script>
<script language="javascript" >
var tabs_album=new ddtabcontent("tabs_album");
tabs_album.setpersist(true);
tabs_album.setselectedClassTarget("link"); //"link" or "linkparent"
tabs_album.init();
<?php if (!empty($mr['tab_id'])) { ?>
tabs_album.expandit(<?php echo $mr['tab_id']?>);
<?php } // end if have tab selected ?>
</script>
<?php if (isset($mr['bbs_id'])) { ?>
<form id="frm_tab" action="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/edit/id/<?php echo $mr['bbs_id']?>" method="post">
<input type="hidden" name="tab_id" id="tab_id" value="" />
</form>
<?php } // end if edit ?>
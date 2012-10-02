<?php //var_dump($mlist) ?>
<link rel="stylesheet" type="text/css" href="<?php echo url::base()?>plugins/jquery.ui/themes/redmond/jquery.ui.all.css">
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery.ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery.ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery.ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery.ui/jquery.ui.tabs.js"></script>
<script>
    $(function() {
		$( "#tabs" ).tabs({
			cookie: {
				// store cookie for a day, without, it would be a session cookie
				expires: 1
			}
		});
	});
</script>
<style>
/*    .ui-tab .ui-widget-content, .ui-tab .ui-widget-header { border: none; background: none; }*/
    
</style>
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
                <div id="tabs" class="album-ui-tabs">
                    <ul>
                        <li><a href="#tabs-1"><?php echo Kohana::lang('global_lang.lbl_info')?></a></li>
                        <?php if (isset($mr['bbs_id'])) { ?>
                        <li><a href="#tabs-2"><?php echo Kohana::lang('album_lang.lbl_list_file')?></a></li>
                        <li><a href="#tabs-3"><?php echo Kohana::lang('global_lang.lbl_upload')?></a></li>
                        <?php } // end if ?>
                    </ul>
                    <div id="tabs-1">
                        <?php require('info.php')?>
                    </div>
                    <?php if (isset($mr['bbs_id'])) { ?>
                    <div id="tabs-2">
                        <?php require('list_file.php')?>
                    </div>
                    <div id="tabs-3">
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

<?php if (isset($mr['bbs_id'])) { ?>
<form id="frm_tab" action="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/edit/id/<?php echo $mr['bbs_id']?>" method="post">
<input type="hidden" name="tab_id" id="tab_id" value="" />
</form>
<?php } // end if edit ?>
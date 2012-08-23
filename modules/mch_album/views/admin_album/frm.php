<form name="frm" action="<?php echo url::base()?>admin_album/save/pid/<?php echo $mr['page_id']?>" method="post" enctype="multipart/form-data">
<table class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label"><?php echo $title?></td>
    <td class="title_button"><?php require('button.php')?></td>
</tr>
</table>
<div id="tabs">
<ul>
<li><a href="#info"><span><?php echo Kohana::lang('global_lang.lbl_info')?></span></a></li>
<?php if(isset($mr['bbs_id'])) { ?>
<li><a href="#list_file"><span><?php echo Kohana::lang('album_lang.lbl_list_file')?></span></a></li>
<li><a href="#upload"><span><?php echo Kohana::lang('global_lang.lbl_upload')?></span></a></li>
<?php } // end if ?>
</ul>
<div id="info">
<?php require('frm_info.php')?>
</div>
<?php if (isset($mr['bbs_id'])) { ?>
<div id="list_file">
<?php require('list_file.php')?>
</div>
<div id="upload">
<?php require('frm_upload.php')?>
</div>
<?php } // end if ?>
</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['bbs_id'])?$mr['bbs_id']:''?>" />
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
</form>
<?php require('frm_js.php')?>
<form id="frm_tab" action="<?php echo url::base().uri::segment(1)?>/edit/id/<?php echo isset($mr['bbs_id'])?$mr['bbs_id']:''?>" method="post">
<input type="hidden" name="tab_id" id="tab_id" value="" />
</form>
<form id="frm" name="frm" action="<?php echo url::base()?>admin_layout/save_menu_layout" method="post">
<table id="float_table" cellspacing="0" cellpadding="0" class="title">
<tr>
    <td width="200" class="title_label"><?php echo $mr['page_title']?></td>
    <td align="right"><?php require('button.php')?></td>        
</tr>
</table>
<div id="tabs">
<ul>
    <li><a href="#tabs-top"><span><?php echo Kohana::lang('global_lang.lbl_top')?></span></a></li>
	<li><a href="#tabs-center"><span><?php echo Kohana::lang('global_lang.lbl_center')?></span></a></li>
    <li><a href="#tabs-left"><span><?php echo Kohana::lang('global_lang.lbl_left')?></span></a></li>
    <li><a href="#tabs-right"><span><?php echo Kohana::lang('global_lang.lbl_right')?></span></a></li>
    <li><a href="#tabs-bottom"><span><?php echo Kohana::lang('global_lang.lbl_bottom')?></span></a></li>
    <li><a href="#tabs-left_outside"><span><?php echo Kohana::lang('global_lang.lbl_left')?> <?php echo Kohana::lang('global_lang.lbl_outside')?></span></a></li>
    <li><a href="#tabs-right_outside"><span><?php echo Kohana::lang('global_lang.lbl_right')?> <?php echo Kohana::lang('global_lang.lbl_outside')?></a></span></a></li>
</ul>
<div id="tabs-top">
<?php require('top.php')?>
</div>
<div id="tabs-center">
<?php require('center.php')?>
</div>
<div id="tabs-left">
<?php require('left.php')?>
</div>
<div id="tabs-right">
<?php require('right.php')?>
</div>
<div id="tabs-bottom">
<?php require('bottom.php')?>
</div>
<div id="tabs-left_outside">
<?php require('left_outside.php')?>
</div>
<div id="tabs-right_outside">
<?php require('right_outside.php')?>
</div>
</div>
<div align="center"><?php require('button.php')?></div>
<input type="hidden" name="hd_id" value="<?php echo isset($mr['page_id'])?$mr['page_id']:''?>" />
</form>
<?php require('frm_js.php')?>
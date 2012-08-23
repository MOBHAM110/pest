<table class="list" cellspacing="1" cellpadding="5">
<tr class="list_header">
<!--    <td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);'></td>
-->    <td><?php echo Kohana::lang('album_lang.lbl_file_name')?></td>
    <td width="50"><?php echo Kohana::lang('global_lang.lbl_view')?></td>
    <td><?php echo Kohana::lang('global_lang.lbl_description')?></td>
    <td><?php echo Kohana::lang('global_lang.lbl_order')?></td>
    <td width="80"><?php echo Kohana::lang('global_lang.lbl_action')?></td>
</tr>
<?php foreach ($mlist['files'] as $i => $file) { 
	$src = "uploads/album/".$file['bbs_file_name'];
	$maxw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST');
	$maxh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST');	
	$arr = MyImage::thumbnail($maxw,$maxh,$src);
?>
<tr class="row<?php echo $i%2==0?2:''?>">
<td style="text-align:left;"><?php echo $file['bbs_file_name']?></td>
    <td><img src="<?php echo $file['file_path_html']?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'auto'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):@$maxh?>" /></td>
    <td align="center"><textarea name="des_<?php echo $file['bbs_file_id']?>" rows="2" cols="50"><?php echo $file['bbs_file_description']?></textarea>
    <td align="center">
    	<?php if ($i == 0 && $mr['file_total'] > 1) { ?>
        <a href="<?php echo url::base()?>admin_album/order_file/down/<?php echo $file['bbs_file_id']?>/<?php echo $mr['bbs_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
        </a>
        <?php } elseif ($i == ($mr['file_total'] - 1) && $mr['file_total'] > 1) { ?>
        <a href="<?php echo url::base()?>admin_album/order_file/up/<?php echo $file['bbs_file_id']?>/<?php echo $mr['bbs_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
        </a>
        <?php } elseif ($mr['file_total'] > 2) { ?>
        <a href="<?php echo url::base()?>admin_album/order_file/down/<?php echo $file['bbs_file_id']?>/<?php echo $mr['bbs_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
        </a>
        <a href="<?php echo url::base()?>admin_album/order_file/up/<?php echo $file['bbs_file_id']?>/<?php echo $mr['bbs_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
        </a>
        <?php } // end if ?>
    </td>
    <td align="center">
    <a href="<?php echo url::base()?>admin_album/del_image/<?php echo $file['bbs_file_id']?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete')?></a>
    </td>
</tr>
<?php } ?>
</table>
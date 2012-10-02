<style>
    .row td, .row2 td{
        border-top: 2px solid #CCC;
    }
</style>
<table class="list" cellspacing="1" cellpadding="5" style="-moz-box-shadow: none; -webkit-box-shadow: none; background: none; border: none; width: 100%">
<tr class="list_header" >
    <td><?php echo Kohana::lang('album_lang.lbl_file_name')?></td>
    <td width="50"><?php echo Kohana::lang('global_lang.lbl_view')?>/<?php echo Kohana::lang('global_lang.lbl_description')?></td>
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
    <td style="text-align:left;">
        <?php echo $file['bbs_file_name']?>
        <textarea name="des_<?php echo $file['bbs_file_id']?>" rows="2" cols="30"><?php echo $file['bbs_file_description']?></textarea>
    </td>
    <td align="center"><img src="<?php echo $file['file_path_html']?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'auto'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):@$maxh?>" /></td>
    <td align="center">
    	<?php if ($i == 0 && $mr['file_total'] > 1) { ?>
        <a href="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/order_file/down/<?php echo $file['bbs_file_id']?>/id/<?php echo $mr['bbs_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" border="0"/>
        </a>
        <?php } elseif ($i == ($mr['file_total'] - 1) && $mr['file_total'] > 1) { ?>
        <a href="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/order_file/up/<?php echo $file['bbs_file_id']?>/id/<?php echo $mr['bbs_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" border="0"/>
        </a>
        <?php } elseif ($mr['file_total'] > 2) { ?>
        <a href="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/order_file/down/<?php echo $file['bbs_file_id']?>/id/<?php echo $mr['bbs_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" border="0"/>
        </a>
        <a href="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/order_file/up/<?php echo $file['bbs_file_id']?>/id/<?php echo $mr['bbs_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" border="0"/>
        </a>
        <?php } // end if ?>
    </td>
    <td align="center">
        <a href="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/delete_image/<?php echo $file['bbs_file_id']?>/id/<?php echo $mr['bbs_id']?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')">
        <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete')?>" border="0" />
        </a>
    </td>
</tr>
<?php } ?>
</table>
<?php echo $top?>
<form id="frm" name="frm" action="<?php echo url::base().uri::segment(1)?>/save_footer" method="post" enctype="multipart/form-data">
<table class="title" cellspacing="0" cellpadding="0">
<tr>
	<td class="title_label"><?php echo Kohana::lang('layout_lang.lbl_footer')?></td>
	<td class="title_button"><?php require('button_footer.php')?></td>
</tr>
</table>
<div class="yui3-g form">	
<div class="yui3-g">
  <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_type')?>:</div>
  <div class="yui3-u-4-5">
    <select name="sel_ft_type" id="sel_ft_type" onchange="sh_content(this);">
        <option value="0" <?php echo $mr['footer_type']=="0"?'selected="selected"':''?>><?php echo Kohana::lang('global_lang.lbl_text')?></option>
        <option value="1" <?php echo $mr['footer_type']=="1"?'selected="selected"':''?>><?php echo Kohana::lang('config_lang.lbl_image_full')?></option>
        <option value="2" <?php echo $mr['footer_type']=="2"?'selected="selected"':''?>><?php echo Kohana::lang('global_lang.lbl_image')?></option>
        <option value="3" <?php echo $mr['footer_type']=="3"?'selected="selected"':''?>><?php echo Kohana::lang('global_lang.lbl_flash')?></option>           
    </select>
  </div>
</div>
<div class="yui3-g" id="content_text">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_text')?>:</div>
    <div class="yui3-u-4-5"><textarea class="ckeditor" id="txt_text" name="txt_text" cols="60" rows="10"><?php echo $mr['footer_content']?></textarea></div>
</div>
<div class="yui3-g" id="content_image">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_image')?>:</div>
    <div class="yui3-u-4-5">
        <?php if ($mr['footer_image'] != '') {
        $src = "uploads/footer/".$mr['footer_image'];
        $maxw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST')*2;
        $maxh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST')*2;	
        $arr = MyImage::thumbnail($maxw,$maxh,$src);
        ?>
            <img src="<?php echo url::base()?>uploads/footer/<?php echo $mr['footer_image']?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'auto'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):@$maxh?>">
            <p>
            <button type="button" name="btn_del_image" class="button delete" onclick="javascript:location.href='<?php echo url::base().uri::segment(1)?>/del_footer_file/image'"/><span><?php echo Kohana::lang('global_lang.btn_delete') ?></span>
            </button>   	
        <?php } else { ?>
            <img src="<?php echo url::base()?>themes/admin/pics/no_img.jpg" title="<?php echo Kohana::lang('layout_lang.msg_is_image')?>">     
        <p>
        <input id="attach_image" name="attach_image" type="file" size="50"/>        
        <button type="submit" name="btn_up_image" class="button upimage"><span><?php echo Kohana::lang('global_lang.btn_upload') ?></span>
</button>
        (<?php echo Kohana::lang('global_lang.lbl_width')?>: 1000px)
        <?php } // end if ?>
    </div>
</div>
<div class="yui3-g" id="content_flash">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_flash')?>:</div>
    <div class="yui3-u-4-5">
    <?php if ($mr['footer_flash'] != '') { ?>
        <embed src="<?php echo url::base()?>uploads/footer/<?php echo $mr['footer_flash']?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" height="100" width="200"></embed>      
        <p>
        <button type="button" name="btn_del_flash" class="button delete" onclick="javascript:location.href='<?php echo url::base().uri::segment(1)?>/del_footer_file/flash'"/><span><?php echo Kohana::lang('global_lang.btn_delete') ?></span>
        </button> 
    <?php } else { ?>
        <img src="<?php echo url::base()?>themes/admin/pics/flash3.png" title="<?php echo Kohana::lang('layout_lang.msg_is_flash')?>">     
        <p>
        <input id="attach_flash" name="attach_flash" type="file" size="50"/>
        <button type="submit" name="btn_up_flash" class="button upflash">
            <span><?php echo Kohana::lang('global_lang.btn_upload') ?></span>
        </button>
    (<?php echo Kohana::lang('global_lang.lbl_width')?>: 1000px) 
    <?php } // end if ?>    
    </div>
</div>
<div class="yui3-g center">
	<?php require('button_footer.php')?>
	<input id="hd_id" name="hd_id" type="hidden" value="<?php echo $mr['footer_id']?>"/>
</div>
</div>
</form>
<?php require('footer_js.php')?>
<?php echo $bottom?>
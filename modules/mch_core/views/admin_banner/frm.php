<form id="frm" name="frm" action="<?php echo url::base()?>admin_banner/save" method="post" enctype="multipart/form-data">
<table cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td class="title_label"><?php echo $mr['page_title']?></td>
    <td align="right"><?php require('button.php')?></td>
  </tr>
</table>
<div class="yui3-g form">
<?php if (isset($mr['banner_id'])) { ?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('banner_lang.lbl_id')?></div>
    <div class="yui3-u-4-5"><?php echo $mr['banner_id']?></div>
</div>
<?php } // end if ?>
<?php if (empty($mr['banner_file']) || $mr['banner_type'] == 'image') { ?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_image')?>:</div>
    <div class="yui3-u-4-5">
    	<input type="radio" name="rdo_type" value="image" <?php echo isset($mr['banner_type'])?($mr['banner_type']=='image'?'checked':''):'checked'?>/>
    	<?php if (!empty($mr['banner_file'])) { 
		$src = "uploads/banner/".$mr['banner_file'];
		$maxw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST')*2;
		$maxh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST')*2;
		$arr = MyImage::thumbnail($maxw,$maxh,$src);
		?>        	
            <img src="<?php echo url::base()?>uploads/banner/<?php echo $mr['banner_file']?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'auto'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):@$maxh?>"/>&nbsp;         
            <button type="button" name="btn_delete" id="btn_delete" class="button delete" onclick="javascript:location.href='<?php echo url::base()?>admin_banner/delete_file/<?php echo $mr['banner_id']?>'"/><span><?php echo Kohana::lang('global_lang.btn_delete') ?></span></button>   		
        <?php } else { ?>
        	<input tabindex="1" type="file" name="attach_image" id="attach_image" onclick="set_type(0,1);">			
		<?php } // end if ?>
    </div>
</div>
<?php } // end if banner file is image ?>
<?php if (empty($mr['banner_file']) || $mr['banner_type'] == 'flash') { ?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_flash')?>:</div>
    <div class="yui3-u-4-5">
    	<input type="radio" name="rdo_type" value="flash" <?php echo isset($mr['banner_type'])?($mr['banner_type']=='flash'?'checked':''):''?>/>
        <?php if (!empty($mr['banner_file'])) { ?>
        	<embed src="<?php echo url::base()?>/uploads/banner/<?php echo $mr['banner_file']?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
            <button type="button" name="btn_delete" id="btn_delete" class="button delete" onclick="javascript:location.href='<?php echo url::base()?>admin_banner/delete_file/<?php echo $mr['banner_id']?>'"/><span><?php echo Kohana::lang('global_lang.btn_delete') ?></span></button>
		<?php } else { ?>            
    		<input tabindex="2" type="file" name="attach_flash" onclick="set_type(1,0);">
        <?php } // end if ?>
    </div>
</div>
<?php } // end if banner file is flash ?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_width')?>:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input tabindex="3" type="text" name="txt_width" size="10" value="<?php echo isset($mr['banner_width'])?$mr['banner_width']:''?>" onkeypress="return numbersonly(this,event)"/></div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_height')?>:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input tabindex="4" type="text" name="txt_height" size="10" value="<?php echo isset($mr['banner_height'])?$mr['banner_height']:''?>" onkeypress="return numbersonly(this,event)"/></div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('banner_lang.lbl_link')?>:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input tabindex="5" type="text" name="txt_link" id="txt_link" size="50" value="<?php echo isset($mr['banner_link'])?$mr['banner_link']:''?>"/>      
        (Link: http://www.abc.com)
    </div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right">Alt:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input tabindex="5" type="text" name="txt_alt" id="txt_alt" size="50" value="<?php echo isset($mr['banner_alt'])?$mr['banner_alt']:''?>"/>      
	</div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('banner_lang.lbl_target')?>:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <select tabindex="6" name="sel_target" id="sel_target">
    		<option value="_blank" <?php echo isset($mr['banner_target']) && $mr['banner_target']=='_blank'?'selected':''?>>_blank</option>
            <option value="_parent" <?php echo isset($mr['banner_target']) && $mr['banner_target']=='_parent'?'selected':''?>>_parent</option>
            <option value="_self" <?php echo isset($mr['banner_target']) && $mr['banner_target']=='_self'?'selected':''?>>_self</option>
            <option value="_top" <?php echo isset($mr['banner_target']) && $mr['banner_target']=='_top'?'selected':''?>>_top</option>
    </select>      
	</div>
</div>
<div class="yui3-g center"><?php require('button.php')?></div>
</div>
<input type="hidden" name="hd_id" id="hd_id" value="<?php echo isset($mr['banner_id'])?$mr['banner_id']:''?>" />
</form>
<?php require('frm_js.php')?>
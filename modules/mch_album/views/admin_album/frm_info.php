<div class="yui3-g">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><font color="#FF0000">*</font>&nbsp;<?php echo Kohana::lang('global_lang.lbl_title')?>:</div>
    <div class="yui3-u-4-5"><input name="txt_title" type="text" id="txt_title" value="<?php echo isset($mr['bbs_title'])?$mr['bbs_title']:''?>" size="50" /></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_content')?>:</div>
    <div class="yui3-u-4-5"><textarea class="ckeditor" name="txt_content" cols="50" rows="10" style="width:100%;height:100px" id="txt_content"><?php echo isset($mr['bbs_content'])?$mr['bbs_content']:''?></textarea></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_order')?>:</div>
    <div class="yui3-u-4-5"><input type="text" value="<?php echo !empty($mr['bbs_sort_order'])?$mr['bbs_sort_order']:'' ?>" id="txt_sort_order" name="txt_sort_order" size="10" class="text_number"/></div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_status')?>:</div>
    <div class="yui3-u-4-5"><select name="sel_status" id="sel_status">
            <option value="0" <?php echo isset($mr['bbs_status'])?($mr['bbs_status']?'':'selected'):''?>><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>	
            <option value="1" <?php echo isset($mr['bbs_status'])?($mr['bbs_status']?'selected':''):'selected'?> ><?php echo Kohana::lang('global_lang.lbl_active')?></option>
        </select>
	</div>
</div>
<div class="yui3-g center"><?php require('button.php')?></div>
</div>
<script language="javascript" src="<?php echo url::base()?>plugins/ckeditor/ckeditor.js"></script>
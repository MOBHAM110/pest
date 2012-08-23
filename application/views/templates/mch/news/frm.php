<form action="<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/save" method="post" enctype="multipart/form-data">
<table class="news_create" align="center" cellspacing="0" cellpadding="0">
<tr><td class="news_create_T"></td></tr>
<tr>
    <td class="news_create_M">
        <table class="news_create_M_Co" cellspacing="3" cellpadding="3">        
        <tr><td align="left"><?php echo Kohana::lang('global_lang.lbl_title')?><font color="#FF0000">*</font></td></tr>
         <tr><td align="left"><input size="50" name="txt_title" type="text" id="txt_title" value="<?php echo isset($mr['bbs_title'])?$mr['bbs_title']:''?>"></td>       
        <?php for($i=1;$i<=3;$i++) { ?>
        <tr><td width="10%" align="left" valign="top"><?php echo Kohana::lang('global_lang.lbl_image').' '.$i?></td></tr>
        <?php if (isset($list_file[$i-1])) { 
			$src = "uploads/news/".$list_file[$i-1]->bbs_file_name;
			$maxw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST');
			$maxh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST');	
			$arr = MyImage::thumbnail($maxw,$maxh,$src);
		?>
        <tr><td align="left"><img src="<?php echo url::base()?>uploads/news/<?php echo $list_file[$i-1]->bbs_file_name?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'auto'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):@$maxh?>" />&nbsp;
        <a href="<?php echo url::base()?>news/delete_image/<?php echo $list_file[$i-1]->bbs_file_id?>/id/<?php echo $mr['bbs_id']?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')">
        <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete')?>" border="0" />
        </a>
        </td>
		</tr>
        <?php } else { ?>
        <tr><td align="left"><input type="file" name="attach_file<?php echo $i?>" id="attach_file<?php echo $i?>"></td></tr>
        <?php } // end if ?>
        <?php } // end for ?>
        <tr><td valign="top" align="left"><?php echo Kohana::lang('global_lang.lbl_content')?></td></tr>
        <tr><td><textarea name="txt_content" style="width:100%" rows="10" id="txt_content"><?php echo isset($mr['bbs_content'])?$mr['bbs_content']:(isset($indata)?$indata['txt_content']:'')?></textarea></td>
        </tr>
        <tr>
            <td align="center">
            <button type="submit" name="btn_save" /><?php echo Kohana::lang('global_lang.btn_save')?></button>&nbsp;&nbsp;&nbsp;       
            <button type="button" name="btn_back" onClick="javascript:location.href='<?php echo url::base().$this->site['history']['back']?>'"><?php echo Kohana::lang('global_lang.btn_back')?></button>
            </td>
        </tr>         
        </table>
    </td>
</tr>
<tr><td class="news_create_B"></td></tr>
</table>
<input type="hidden" name="hd_id" id="hd_id" value="<?php echo isset($mr['bbs_id'])?$mr['bbs_id']:''?>" />
</form>
<script type="text/javascript" src="<?php echo url::base()?>plugins/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "searchreplace,insertdatetime,preview",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,sub,sup,|,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,
		forced_root_block : false,
		force_br_newlines : true,
		force_p_newlines : false
	});
});
</script>
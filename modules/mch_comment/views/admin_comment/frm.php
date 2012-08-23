<form name="frm" id="frm" action="<?php echo $this->site['base_url']?>admin_comment/save" method="post" enctype="multipart/form-data" >
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('comment_lang.tt_page')?></td>
    <td class="title_button">
    <button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_comment'">
    <span><?php echo Kohana::lang('global_lang.btn_back')?></span>
    </button>
    <?php if($this->permisController('save')) { ?>
    <button type="submit" name="btn_save" class="button save">
    <span><?php echo Kohana::lang('global_lang.btn_save')?></span>
    </button>
    <?php }//save?>
    </td>
  </tr>
</table>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('comment_lang.lbl_name')?>: <font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5"><input value="<?php if (isset($mr['comment_author'])) echo $mr['comment_author']; ?>" name="txt_name" id="txt_name" size="50"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('comment_lang.lbl_content')?>:</div>
    <div class="yui3-u-4-5"><textarea cols="40" rows="5" name="txt_content"><?php if (isset($mr['comment_content'])) echo $mr['comment_content']; ?></textarea></div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_status')?>:</div>
	<div class="yui3-u-4-5">
    <select name="sel_status" id="sel_status">            
        <option value="1" <?php if(isset($mr['comment_status']) && $mr['comment_status']==1) echo 'selected="selected"'?> ><?php echo Kohana::lang('global_lang.lbl_active')?></option>
        <option value="0" <?php if(isset($mr['comment_status']) && $mr['comment_status']!=1) echo 'selected="selected"'?> ><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>	
    </select>
    </div>
</div>
</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['comment_id'])?$mr['comment_id']:''?>"/>
</form>
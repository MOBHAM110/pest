<form name="frm" id="frm" action="<?php echo $this->site['base_url']?>admin_support/save" method="post" enctype="multipart/form-data" >
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('support_lang.tt_page')?></td>
    <td class="title_button"><?php require('button.php')?></td>
  </tr>
</table>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><font color="#FF0000">*</font> <?php echo Kohana::lang('support_lang.lbl_name')?>:</div>
    <div class="yui3-u-4-5">
    <input value="<?php if (isset($mr['support_name'])) echo $mr['support_name']; ?>" name="txt_name" id="txt_name" size="30"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('support_lang.lbl_nick')?>:</div>
    <div class="yui3-u-4-5"><input value="<?php if (isset($mr['support_nick'])) echo $mr['support_nick']; ?>" name="txt_nick" id="txt_nick" size="30"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('support_lang.lbl_type')?>:</div>
    <div class="yui3-u-4-5">
    <select name="sel_type" id="sel_type">            
        <option value="1" <?php if(isset($mr['support_type']) && $mr['support_type']==1) echo 'selected="selected"'?> ><?php echo Kohana::lang('support_lang.lbl_yahoo')?></option>
        <option value="2" <?php if(isset($mr['support_type']) && $mr['support_type']==2) echo 'selected="selected"'?> ><?php echo Kohana::lang('support_lang.lbl_skype')?></option>
        <option value="3" <?php if(isset($mr['support_type']) && $mr['support_type']==3) echo 'selected="selected"'?> ><?php echo Kohana::lang('support_lang.lbl_hotline')?></option>
    </select>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_order')?>:</div>
    <div class="yui3-u-4-5"><input name="txt_sort_order" id="txt_sort_order" class="text_number" value="<?php if (isset($mr['support_sort_order'])){ echo $mr['support_sort_order']; } else {echo '0'; }?>" size="10"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_status')?>:</div>
    <div class="yui3-u-4-5">
    <select name="sel_status" id="sel_status">            
        <option value="1" <?php if(isset($mr['support_status']) && $mr['support_status']==1) echo 'selected="selected"'?> ><?php echo Kohana::lang('global_lang.lbl_active')?></option>
        <option value="0" <?php if(isset($mr['support_status']) && $mr['support_status']!=1) echo 'selected="selected"'?> ><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>	
    </select>
    </div>
</div>
<div class="yui3-g center"><?php require('button.php')?></div>
</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['support_id'])?$mr['support_id']:''?>"/>
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
</form>
<?php require('frm_js.php')?>
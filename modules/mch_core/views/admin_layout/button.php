<?php if (isset($mr['page_status'])) { ?>
<button type="button" name="btn_back"  class="button back" onclick="javascript:location.href='<?php echo url::base()?>admin_page'"><span><?php echo Kohana::lang('global_lang.btn_back')?></span></button><?php } // end if ?>
<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
<button type="button" name="btn_save" class="button update" onclick="javascript:save();"><span><?php echo Kohana::lang('global_lang.btn_update')?></span></button>
<?php }//edit,delete?>
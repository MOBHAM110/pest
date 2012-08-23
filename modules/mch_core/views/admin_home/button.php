<button type="button" name="btn_back"  class="button back" onclick="javascript:location.href='<?php echo url::base()?>admin_page'">
<span><?php echo Kohana::lang('global_lang.btn_back')?></span>
</button>
<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
<?php if (!empty($this->site['config']['HOME_NUM_ROW'])) { ?>
<button type="submit" name="btn_save" class="button save" onclick="javascript:save();"><span><?php echo Kohana::lang('global_lang.btn_save')?></span></button>
<?php } // end if ?>
<?php }//edit,delete?>
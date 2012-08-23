<button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo url::base()?>admin_bbs/search/pid/<?php echo $mr['page_id']?>'">
<span><?php echo Kohana::lang('global_lang.btn_back')?></span>
</button>
<?php if($this->permisController('save')) { ?>
<button type="button" name="btn_save" class="button save" onclick="javascript:save();">
<span><?php echo Kohana::lang('global_lang.btn_save')?></span>
</button>
<button type="button" name="btn_save_add" class="button save" onclick="javascript:save('add');"><span><?php echo Kohana::lang('global_lang.btn_save_add')?></span></button>
<?php if (isset($mr['bbs_id']) && uri::segment('id')) { ?>
<button type="button" name="reply" class="button reply" onclick="javascript:location.href='<?php echo url::base()?>admin_bbs/create/reply/id/<?php echo $seg_id?>'" <?php if($mr['bbs_status']==0) echo 'disabled="disabled"'?>><span><?php echo Kohana::lang('bbs_lang.lbl_reply')?></span></button>
<?php } // end if ?>
<?php }//save?>
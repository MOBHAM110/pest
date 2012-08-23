<form action="<?php echo url::base().uri::segment(1)?>/save" method="post">
<table cellspacing="0" cellpadding="0" class="title">
<tr >
    <td width="200"class="title_label"><?php echo $mr['title']?>
	</td>
    <td align="right">
    <button type="button" name="btn_back"  class="button back" onclick="javascript:location.href='<?php echo url::base()?>admin_page'"><span><?php echo Kohana::lang('global_lang.btn_back')?></span></button>
    <?php if($this->permisController('save')) { ?>
    <button type="submit" name="btn_save"  class="button save"><span><?php echo Kohana::lang('global_lang.btn_save')?></span></button>
    <?php }//save?>
    </td>        
</tr>
</table>
<div class="yui3-g form">
<div class="yui3-g center">
    <select name="sel_pid[]" multiple="mutiple">
    <?php foreach ($mlist as $list) { ?>
        <option value="<?php echo $list['page_id']?>" <?php echo array_search($list['page_id'],$mr['arr_pid'])!==FALSE?'selected="selected"':''?>>
            <?php echo $list['page_title']?>&nbsp;[<?php echo $list['page_type_name']?>]
        </option>
    <?php } // end foreach ?>
    </select>
</div>
</div>
<input type="hidden" name="hd_id" value="<?php echo $mr['page_id']?>">
</form>
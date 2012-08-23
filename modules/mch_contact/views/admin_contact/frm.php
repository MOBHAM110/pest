<table cellspacing="0" cellpadding="0" class="title">
<tr>
<td class="title_label"><?php echo $mr['title']?></td>
    <td align="right">
    <button type="button" name="btn_back"  class="button back" onclick="javascript:location.href='<?php echo url::base()?>admin_page'">
	<span><?php echo Kohana::lang('global_lang.btn_back')?></span>
    </button>
</tr>
</table>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_phone_fax')?></div>
    <div class="yui3-u-4-5">
        <input id="txt_phone" name="txt_phone" type="text" value="<?php echo $this->site['site_phone']?>" size="20" readonly="readonly"/>
        <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
        <a href="<?php echo url::base().uri::segment(1)?>/change_client_view/phone" >
        <img src="<?php echo url::base()?>themes/admin/pics/icon_<?php echo $mr['contact_phone']?'':'in'?>active.png" title="<?php echo Kohana::lang('global_lang.lbl_'.($mr['contact_phone']?'':'in').'active')?>" />
        </a>
        <?php }//edit,delete?>
        <input id="txt_fax" name="txt_fax" type="text" value="<?php echo $this->site['site_fax']?>" size="22" readonly="readonly"/>
        <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
        <a href="<?php echo url::base().uri::segment(1)?>/change_client_view/fax" >
        <img src="<?php echo url::base()?>themes/admin/pics/icon_<?php echo $mr['contact_fax']?'':'in'?>active.png" title="<?php echo Kohana::lang('global_lang.lbl_'.($mr['contact_fax']?'':'in').'active')?>" />
        </a>
        <?php }//edit,delete?>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_email')?></div>
    <div class="yui3-u-4-5"><input id="txt_email" name="txt_email" type="text" size="50" value="<?php echo $this->site['site_email']?>" readonly="readonly"/>
	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <a href="<?php echo url::base().uri::segment(1)?>/change_client_view/email" >
    <img src="<?php echo url::base()?>themes/admin/pics/icon_<?php echo $mr['contact_email']?'':'in'?>active.png" title="<?php echo Kohana::lang('global_lang.lbl_'.($mr['contact_email']?'':'in').'active')?>" />
    </a>
    <?php }//edit,delete?>
    </div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_address')?></div>
    <div class="yui3-u-4-5"><input id="txt_address" name="txt_address" type="text" size="50" value="<?php echo $this->site['site_address']?>" readonly="readonly"/>
	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <a href="<?php echo url::base().uri::segment(1)?>/change_client_view/address" >
    <img src="<?php echo url::base()?>themes/admin/pics/icon_<?php echo $mr['contact_address']?'':'in'?>active.png" title="<?php echo Kohana::lang('global_lang.lbl_'.($mr['contact_address']?'':'in').'active')?>" />
    </a>
    <?php }//edit,delete?>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_city')?></div>
    <div class="yui3-u-4-5"><input id="txt_city" name="txt_city" type="text" size="50" value="<?php echo $this->site['site_city']?>" readonly="readonly"/>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <a href="<?php echo url::base().uri::segment(1)?>/change_client_view/city" >
    <img src="<?php echo url::base()?>themes/admin/pics/icon_<?php echo $mr['contact_city']?'':'in'?>active.png" title="<?php echo Kohana::lang('global_lang.lbl_'.($mr['contact_city']?'':'in').'active')?>" />
    </a>
	<?php }//edit,delete?>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_state')?></div>
    <div class="yui3-u-4-5"><input id="txt_state" name="txt_state" type="text" size="50" value="<?php echo $this->site['site_state']?>" readonly="readonly"/>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <a href="<?php echo url::base().uri::segment(1)?>/change_client_view/state" >
    <img src="<?php echo url::base()?>themes/admin/pics/icon_<?php echo $mr['contact_state']?'':'in'?>active.png" title="<?php echo Kohana::lang('global_lang.lbl_'.($mr['contact_state']?'':'in').'active')?>" />
    </a>
	<?php }//edit,delete?>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_zipcode')?></div>
    <div class="yui3-u-4-5"><input id="txt_zipcode" name="txt_zipcode" type="text" size="50" value="<?php echo $this->site['site_zipcode']?>" readonly="readonly"/>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <a href="<?php echo url::base().uri::segment(1)?>/change_client_view/zipcode" >
    <img src="<?php echo url::base()?>themes/admin/pics/icon_<?php echo $mr['contact_zipcode']?'':'in'?>active.png" title="<?php echo Kohana::lang('global_lang.lbl_'.($mr['contact_zipcode']?'':'in').'active')?>" />
    </a>
	<?php }//edit,delete?>
    </div>
</div>
</div>
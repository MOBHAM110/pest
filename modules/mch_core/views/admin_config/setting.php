<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_line_admin')?>:</div>
    <div class="yui3-u-4-5"><input name="txt_admin_num_line" type="text" id="txt_admin_num_line" value="<?php echo $this->site['config']['ADMIN_NUM_LINE']?>" size="12" /></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_line_client')?>:</div>
    <div class="yui3-u-4-5"><input name="txt_client_num_line" type="text" id="txt_client_num_line" value="<?php echo $this->site['config']['CLIENT_NUM_LINE']?>" size="12" /></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_short_date')?>:</div>
    <div class="yui3-u-4-5"><input name="txt_short_date" type="text" id="txt_short_date" value="<?php echo $this->site['config']['FORMAT_SHORT_DATE']?>" size="12" /></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_long_date')?>:</div>
    <div class="yui3-u-4-5"><input name="txt_long_date" type="text" id="txt_long_date" value="<?php echo $this->site['config']['FORMAT_LONG_DATE']?>" size="12" /></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_admin_lang')?>:</div>
    <div class="yui3-u-4-5">
        <select name="sel_admin_lang">
        <?php foreach (ORM::factory('languages')->where('languages_status',1)->find_all() as $lang) { ?>
        <option value="<?php echo $lang->languages_id?>" <?php echo ($this->site['config']['ADMIN_LANG']==$lang->languages_id)?'selected':''?>>
			<?php echo $lang->languages_name?></option>
        <?php } // end foreach ?>
        </select>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_client_lang')?>:</div>
    <div class="yui3-u-4-5">
        <select name="sel_client_lang">
        <?php foreach (ORM::factory('languages')->where('languages_status',1)->find_all() as $lang) { ?>
        <option value="<?php echo $lang->languages_id?>" <?php echo ($this->site['config']['CLIENT_LANG']==$lang->languages_id)?'selected':''?>>
			<?php echo $lang->languages_name?></option>
        <?php } // end foreach ?>
        </select>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_df_sreg')?>:</div>
    <div class="yui3-u-4-5">
        <select name="sel_df_sreg">
        <option value="1" <?php echo $this->site['config']['DEF_SREG']==1?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_active')?></option>
        <option value="o" <?php echo $this->site['config']['DEF_SREG']==1?'':'selected'?>><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>
        </select>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_banner')?>:</div>
    <div class="yui3-u-4-5"><div>
<?php if (isset($this->site['config']['BANNER_TOP'])) { ?>
<?php for ($i=1;$i<=15;$i++) { ?>
<?php if ($i == 2) { continue; } ?>
<?php $pos = $this->get_position($i)?>
<?php $conf_ban = $this->site['config']['BANNER_'.strtoupper($pos)]?>
	<div style="float:left"><?php echo Kohana::lang('global_lang.lbl_banner')?>&nbsp;<?php echo Kohana::lang('global_lang.lbl_'.$pos)?>
    <select name="sel_ban_<?php echo $pos?>">
    <option value="random" <?php echo $conf_ban=='random'?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_random')?></option>
    <option value="order" <?php echo $conf_ban=='order'?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_order')?></option>
    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </div>
<?php } // end for ?>
<?php } // end if have config banner position ?></div>   
    </div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_login_frm')?>:</div>
    <div class="yui3-u-4-5">
    	<select name="sel_login_frm">
        <option value="1" <?php echo $this->site['config']['LOGIN_FRM']==1?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_left')?></option>
        <option value="2" <?php echo $this->site['config']['LOGIN_FRM']==2?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_right')?></option>
        <option value="0" <?php echo $this->site['config']['LOGIN_FRM']==0?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_hidden')?></option>
        </select>
    </div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_support_frm')?>:</div>
    <div class="yui3-u-4-5">
    	<select name="sel_support_frm">
        <option value="1" <?php echo $this->site['config']['SUPPORT_FRM']==1?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_left')?></option>
        <option value="2" <?php echo $this->site['config']['SUPPORT_FRM']==2?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_right')?></option>
        <option value="0" <?php echo $this->site['config']['SUPPORT_FRM']==0?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_hidden')?></option>
        </select>
    </div>
</div>
<?php if (isset($this->site['config']['GOOGLE_CALENDAR'])) { ?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_google_calendar')?>:</div>
    <div class="yui3-u-4-5">
    	<select name="sel_google_calendar">
        <option value="1" <?php echo $this->site['config']['GOOGLE_CALENDAR']==1?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_active')?></option>
        <option value="0" <?php echo $this->site['config']['GOOGLE_CALENDAR']==0?'selected':''?>><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>
        </select>
    </div>
</div>
<?php } // end if have google calendar ?>
<?php if (isset($this->site['config']['TARGET_MENU'])) { ?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_target_menu')?>:</div>
    <div class="yui3-u-4-5">
    	<select name="sel_target_menu">
        <option value="_blank" <?php echo $this->site['config']['TARGET_MENU']=='_blank'?'selected':''?>>_blank</option>
        <option value="_parent" <?php echo $this->site['config']['TARGET_MENU']=='_parent'?'selected':''?>>_parent</option>
        <option value="_self" <?php echo $this->site['config']['TARGET_MENU']=='_self'?'selected':''?>>_self</option>
        <option value="_top" <?php echo $this->site['config']['TARGET_MENU']=='_top'?'selected':''?>>_top</option>
        </select>
    </div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right">A&K Computer's Blog&News:</div>
    <div class="yui3-u-4-5">
    	<select name="sel_akcomp">
        <option value="1" <?php echo Configuration_Model::get_value('ENABLE_AKCOMP')==1?'selected':''?>>Use</option>
        <option value="0" <?php echo Configuration_Model::get_value('ENABLE_AKCOMP')==0?'selected':''?>>Not Use</option>
        </select>
    </div>
</div>
<?php } // end if have target menu ?>
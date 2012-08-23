<div class="yui3-g" name="left" id="left">
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_banner')?>&nbsp;<?php echo Kohana::lang('global_lang.lbl_top')?>:</div> 
    <div class="yui3-u-4-5">
    <?php if (isset($mr['page_id'])) { ?>
    	<?php if($this->permisController('add')) { ?>
        <button type="button" name="btn_insert_left_ban" class="button new" onclick="javascript:showpopup('<?php echo url::base()?>admin_layout/insert_banner/<?php echo $mr['page_id']?>/12',800,600);"/><span><?php echo Kohana::lang('layout_lang.btn_insert_ban')?></span></button>
        <?php }//add?>
        <?php if($this->permisController('delete')) { ?>
    	<?php if (!empty($mr['left_T_banner'])) { ?>
        <button type="button" name="btn_del_ban_top" id="btn_del_ban_top" class="button remove" onclick="javascript:location.href='<?php echo url::base()?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/12'"><span><?php echo Kohana::lang('layout_lang.btn_remove_ban') ?></span></button>  
    	<?php } //end if ?>
        <?php }//delete?>
    <?php } //end if ?>    
    </div>   
</div>
<div class="yui3-g">
    <?php if (!empty($mr['left_T_banner'])) { ?>
    <table class="list" align="center" cellspacing="1" cellpadding="5">
    <tr class="list_header">			
        <td align="center" colspan="3"><?php echo Kohana::lang('global_lang.lbl_banner')?></td>      		
    </tr>
    <?php foreach ($list_left_T_banner as $i => $banner) { ?>
    <tr class="row">        	
        <td align="center">				
            <?php if ($banner['banner_type'] == 'image') { 
			$src = "uploads/banner/".$banner['banner_file'];
			$max_banw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST');
			$max_banh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST');
			$arr_ban = MyImage::thumbnail($max_banw,$max_banh,$src);
			?>
                <img src="<?php echo url::base()?>uploads/banner/<?php echo $banner['banner_file']?>" width="<?php echo !empty($arr_ban['width'])?($arr_ban['width'].'px'):'auto'?>" height="<?php echo !empty($arr_ban['height'])?($arr_ban['height'].'px'):@$max_banh?>" /> 
            <?php } else { ?>
                <embed src="<?=url::base()?>uploads/banner/<?php echo $banner['banner_file']?>" width="600" height="100" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
            <?php } // end if ?>
        </td>
        <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
        <td align="center" width="10%">
            <?php if ($i == 0 && count($list_left_T_banner) > 1) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/12/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
            </a>
            <?php } elseif ($i == (count($list_left_T_banner) - 1) && count($list_left_T_banner) > 1) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/12/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
            </a>
            <?php } elseif (count($list_left_T_banner) > 2) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/12/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
            </a>
            <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/12/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
            </a>
            <?php } // end if ?>
        </td>
        <?php if($this->permisController('delete')) { ?>
        <td align="center">
        <a href="<?=url::base() ?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/12/<?php echo $banner['banner_id']?>" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
        </td>
        <?php }//delete?>
        <?php }//edit,delete?>
    </tr>
    <?php } // end foreach ?>
    </table>
    <?php } // end if?>
</div>
<div class="yui3-g center">
    <?php echo Kohana::lang('layout_lang.lbl_left_menu')?><br />
    <select name="sel_left_menu_level">
        <option value="0"><?php echo Kohana::lang('layout_lang.lbl_multi_lv')?></option>
        <option value="1" <?php echo ($mr['left_menu_level']==1)?'selected="selected"':''?>><?php echo Kohana::lang('layout_lang.lbl_one_lv')?></option>
    </select><br />
    <?php $list_menu = ($mr['left_menu_level'] == 0) ? $list_multi_menu : $list_one_menu?>
    <select name="sel_left_menu[]" multiple="multiple" size="<?php echo count($list_menu)?>">
        <?php foreach ($list_menu as $page) { ?>
            <?php
                $expand = '';
                if ($mr['left_menu_level'] == 0)
                {
                    for ($i=1;$i<$page['page_level'];$i++)
                        $expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    $expand .= '|----';
                }
            ?> 
            <?php $selected = in_array($page['page_id'],$arr_left_menu) ? 'selected="selected"' : ''?>
            <option value="<?php echo $page['page_id']?>" <?php echo $selected?>><?php echo $expand.$page['page_title']?></option>
        <?php } // end foreach ?>           
    </select>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_banner')?>&nbsp;<?php echo Kohana::lang('global_lang.lbl_bottom')?>:</div> 
    <div class="yui3-u-4-5">
    <?php if (isset($mr['page_id'])) { ?>
    	<?php if($this->permisController('add')) { ?>
        <button type="button" name="btn_insert_left_ban" class="button new" onclick="javascript:showpopup('<?php echo url::base()?>admin_layout/insert_banner/<?php echo $mr['page_id']?>/13',800,600);"/><span><?php echo Kohana::lang('layout_lang.btn_insert_ban')?></span></button>
        <?php }//add?>
        <?php if($this->permisController('delete')) { ?>
    	<?php if (!empty($mr['left_B_banner'])) { ?>
        <button type="button" name="btn_del_ban_top" id="btn_del_ban_top" class="button remove" onclick="javascript:location.href='<?php echo url::base()?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/13'"><span><?php echo Kohana::lang('layout_lang.btn_remove_ban') ?></span></button>  
    	<?php } //end if ?>
        <?php }//delete?>
    <?php } //end if ?>    
    </div>   
</div>
<div class="yui3-g">
	<?php if (!empty($mr['left_B_banner'])) { ?>
	<table class="list" align="center" cellspacing="1" cellpadding="5">
    <tr class="list_header">			
        <td align="center" colspan="3"><?php echo Kohana::lang('global_lang.lbl_banner')?></td>      		
    </tr>
    <?php foreach ($list_left_B_banner as $i => $banner) { ?>
    <tr class="row">        	
        <td align="center">				
            <?php if ($banner['banner_type'] == 'image') { 
				$src = "uploads/banner/".$banner['banner_file'];
				$max_banw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST');
				$max_banh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST');
				$arr_ban = MyImage::thumbnail($max_banw,$max_banh,$src);
			?>
                <img src="<?php echo url::base()?>uploads/banner/<?php echo $banner['banner_file']?>" width="<?php echo !empty($arr_ban['width'])?($arr_ban['width'].'px'):'auto'?>" height="<?php echo !empty($arr_ban['height'])?($arr_ban['height'].'px'):@$max_banh?>" /> 
            <?php } else { ?>
                <embed src="<?=url::base()?>uploads/banner/<?php echo $banner['banner_file']?>" width="600" height="100" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
            <?php } // end if ?>
        </td>
        <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
        <td align="center" width="10%">
            <?php if ($i == 0 && count($list_left_B_banner) > 1) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/13/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
            </a>
            <?php } elseif ($i == (count($list_left_B_banner) - 1) && count($list_left_B_banner) > 1) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/13/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
            </a>
            <?php } elseif (count($list_left_B_banner) > 2) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/13/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
            </a>
            <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/13/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
            </a>
            <?php } // end if ?>
        </td>
        <?php if($this->permisController('delete')) { ?>
        <td align="center">
        <a href="<?=url::base() ?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/13/<?php echo $banner['banner_id']?>" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
        </td>
        <?php }//delete?>
        <?php }//edit,delete?>
    </tr>
    <?php } // end foreach ?>
    </table>
    <?php } // end if ?>
</div>
<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_status')?>:</div> 
    <div class="yui3-u-4-5">
    <select name="sel_status_left" id="sel_status_left">        <option value="1" <?php echo isset($mr['left_col'])?($mr['left_col']?'selected':''):'selected'?>><?php echo Kohana::lang('global_lang.lbl_active')?></option>
        <option value="0" <?php echo isset($mr['left_col'])?($mr['left_col']?'':'selected'):''?>><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>	
    </select>
    </div>   
</div>
<?php }//edit,delete?>
</div>
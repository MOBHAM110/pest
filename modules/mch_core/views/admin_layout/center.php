<div class="yui3-g" name="center" id="center">
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_banner')?>&nbsp;<?php echo Kohana::lang('global_lang.lbl_top')?>:</div> 
    <div class="yui3-u-4-5">
    <?php if (isset($mr['page_id'])) { ?>
    	<?php if($this->permisController('add')) { ?>
        <button type="button" name="btn_insert_cen_ban" class="button new" onclick="javascript:showpopup('<?php echo url::base()?>admin_layout/insert_banner/<?php echo $mr['page_id']?>/6',800,600);"/><span><?php echo Kohana::lang('layout_lang.btn_insert_ban')?></span></button>
        <?php }//add?>
    	<?php if($this->permisController('delete')) { ?>
		<?php if(!empty($mr['center_top_banner'])) { ?>
        <button type="button" name="btn_del_ban_top" id="btn_del_ban_top" class="button remove" onclick="javascript:location.href='<?php echo url::base()?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/6'"/>
        <span><?php echo Kohana::lang('layout_lang.btn_remove_ban') ?></span>
        </button>
    	<?php }//end if ?>
        <?php }//delete?>
    <?php } //end if ?>
    </div>   
</div>
<div class="yui3-g">
    <?php if (!empty($mr['center_top_banner'])) { ?>
    <table class="list" align="center" cellspacing="1" cellpadding="5">
    <tr class="list_header">			
        <td align="center" colspan="3"><?php echo Kohana::lang('global_lang.lbl_banner')?></td>      		
    </tr>
    <?php foreach ($list_center_top_banner as $i => $banner) { ?>
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
            <embed src="<?=url::base()?>uploads/banner/<?php echo $banner['banner_file']?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
        <?php } // end if ?>
        </td>
        <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>  
        <td align="center" width="10%">
		<?php if ($i == 0 && count($list_center_top_banner) > 1) { ?>
        <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/6/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
        </a>
        <?php } elseif ($i == (count($list_center_top_banner) - 1) && count($list_center_top_banner) > 1) { ?>
        <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/6/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
        </a>
        <?php } elseif (count($list_center_top_banner) > 2) { ?>
        <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/6/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
        </a>
        <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/6/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
        </a>
        <?php } // end if ?>
        </td>
        <?php if($this->permisController('delete')) { ?>
        <td align="center">
        <a href="<?=url::base() ?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/6/<?php echo $banner['banner_id']?>" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
        </td>
        <?php }//delete?>
        <?php }//edit,delete?>   
    </tr>
    <?php } // end foreach ?>    
    </table>
    <?php } // end if ?>
</div>
<div class="yui3-g" style="background-color:#39F; color:#FFF; font-weight:bold"><?php echo Kohana::lang('layout_lang.lbl_content_center')?></div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_banner')?>&nbsp;<?php echo Kohana::lang('global_lang.lbl_bottom')?>:</div> 
    <div class="yui3-u-4-5">
    <?php if (isset($mr['page_id'])) { ?>
    	<?php if($this->permisController('add')) { ?>
        <button type="button" name="btn_insert_cen_ban" class="button new" onclick="javascript:showpopup('<?php echo url::base()?>admin_layout/insert_banner/<?php echo $mr['page_id']?>/7',800,600);"/><span><?php echo Kohana::lang('layout_lang.btn_insert_ban')?></span></button>
        <?php }//add?>
    	<?php if($this->permisController('delete')) { ?>
		<?php if (!empty($mr['center_bottom_banner'])) { ?>
        <button type="button" name="btn_del_ban_top" id="btn_del_ban_top" class="button remove" onclick="javascript:location.href='<?php echo url::base()?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/7'"/>
		<span><?php echo Kohana::lang('layout_lang.btn_remove_ban') ?></span>
        </button>
    	<?php }// end if ?>
        <?php }//delete?>
    <?php } //end if ?>
    </div>   
</div>
<div class="yui3-g">
    <?php if (!empty($mr['center_bottom_banner'])) { ?>
    <table class="list" align="center" cellspacing="1" cellpadding="5">
    <tr class="list_header">			
        <td align="center" colspan="3"><?php echo Kohana::lang('global_lang.lbl_banner')?></td>      		
    </tr>
    <?php foreach ($list_center_bottom_banner as $i => $banner) { ?>
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
            <embed src="<?=url::base()?>uploads/banner/<?php echo $banner['banner_file']?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
        <?php } // end if ?>
        </td>
        <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
        <td align="center" width="10%">
		<?php if ($i == 0 && count($list_center_bottom_banner) > 1) { ?>
        <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/7/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
        </a>
        <?php } elseif ($i == (count($list_center_bottom_banner) - 1) && count($list_center_bottom_banner) > 1) { ?>
        <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/7/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
        </a>
        <?php } elseif (count($list_center_bottom_banner) > 2) { ?>
        <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/7/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
        </a>
        <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/7/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
        </a>
        <?php } //end if ?>
        </td> 
        <?php if($this->permisController('delete')) { ?>
        <td align="center">
        <a href="<?=url::base() ?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/7/<?php echo $banner['banner_id']?>" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
        </td>
        <?php }//delete?>
        <?php }//edit,delete?> 
    </tr>
    <?php } // end foreach ?>
    </table>
    <?php } // end if ?>
</div>
</div>
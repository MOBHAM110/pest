<div class="yui3-g" name="right_outside" id="right_outside">
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_banner')?>:</div> 
    <div class="yui3-u-4-5">
    <?php if (isset($mr['page_id'])) { ?>
	<?php if($this->permisController('add')) { ?>	
    	<button type="button" name="btn_insert_right_ban" class="button new" onclick="javascript:showpopup('<?php echo url::base()?>admin_layout/insert_banner/<?php echo $mr['page_id']?>/9',800,600);"/><span><?php echo Kohana::lang('layout_lang.btn_insert_ban')?></span></button>
    <?php }//add?>
    <?php }//end if ?>
    <?php if($this->permisController('delete')) { ?>
    <?php if (!empty($mr['right_outside_banner'])) { ?>
        <button type="button" name="btn_del_ban" id="btn_del_ban" class="button remove" onclick="javascript:location.href='<?php echo url::base()?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/9'"><span><?php echo Kohana::lang('layout_lang.btn_remove_ban') ?></span></button> 
	<?php }//end if ?>
    <?php }//delete?>
    </div>
</div>
<div class="yui3-g">
	<?php if (!empty($mr['right_outside_banner'])) { ?>
    <table class="list" align="center" cellspacing="1" cellpadding="5">        
    <tr class="list_header">
        <td align="center" colspan="3"><?php echo Kohana::lang('global_lang.lbl_banner')?></td>      		
    </tr>        
    <?php foreach ($list_right_outside_banner as $i => $banner) { ?>
    <tr class="row">        	
        <td align="center">				
        <?php if ($banner['banner_type'] == 'image') { 
            $src = "uploads/banner/".$banner['banner_file'];
            $max_banw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST');
            $max_banh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST');
            $arr_ban = MyImage::thumbnail($max_banw,$max_banh,$src);
        ?>
            <img src="<?php echo url::base()?>uploads/banner/<?php echo $banner['banner_file']?>" width="<?php echo !empty($arr_ban['width'])?($arr_ban['width'].'px'):'auto'?>" height="<?php echo !empty($arr_ban['height'])?($arr_ban['height'].'px'):@$max_banh?>" /> 
        <?php } // end if ?>
        </td>
        <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
        <td align="center" width="10%">
        <?php if ($i == 0 && count($list_right_outside_banner) > 1) { ?>
        <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/9/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
        </a>
        <?php } elseif ($i == (count($list_right_outside_banner) - 1) && count($list_right_outside_banner) > 1) { ?>
        <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/9/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
        </a>
        <?php } elseif (count($list_right_outside_banner) > 2) { ?>
        <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/9/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
        </a>
        <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/9/<?php echo $banner['banner_id']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
        </a>
        <?php } // end if ?>
        </td>
        <?php if($this->permisController('delete')) { ?>
        <td align="center">
        <a href="<?php echo url::base() ?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/9/<?php echo $banner['banner_id']?>" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
        </td>
        <?php }//delete?>
        <?php }//edit,delete?>
    </tr>
    <?php } // end foreach ?>
    </table>
    <?php } // end if right_banner_order = 1 ?>
</div>
</div>
<div class="yui3-g" name="top" id="top">
<div class="yui3-g center">
	<?php echo Kohana::lang('layout_lang.lbl_top_menu')?><br />
    <select name="sel_top_menu_level">
        <option value="0"><?php echo Kohana::lang('layout_lang.lbl_multi_lv')?></option>
        <option value="1" <?php echo ($mr['top_menu_level']==1)?'selected="selected"':''?>><?php echo Kohana::lang('layout_lang.lbl_one_lv')?></option>
    </select><br />
    <?php $list_menu = ($mr['top_menu_level'] == 0) ? $list_multi_menu : $list_one_menu?>
    <select name="sel_top_menu[]" multiple="multiple" size="<?php echo count($list_menu)?>">
        <?php foreach ($list_menu as $page) { ?> 
            <?php 
                $expand = '';
                if ($mr['top_menu_level'] == 0) { 
                    for ($i=1;$i<$page['page_level'];$i++)
                        $expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    $expand .= '|----';
                }
            ?>
            <?php $selected = in_array($page['page_id'],$arr_top_menu) ? 'selected="selected"' : ''?>
            <option value="<?php echo $page['page_id']?>" <?php echo $selected?>><?php echo $expand.$page['page_title']?></option>
        <?php } // end foreach ?>           
    </select>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('layout_lang.lbl_header')?>:
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <a href="javascript:showpopup('<?php echo url::base().uri::segment(1)?>/header/<?php echo $mr['page_id']?>',800,600);">
        <img src="<?=url::base() ?>themes/admin/pics/icon_edit.png" title="<?php echo Kohana::lang('global_lang.btn_edit') ?>" />
    </a>
    <?php }//edit,delete?>
    </div>
    <div class="yui3-u-4-5">
    	<?php if ($header['header_type'] == 0) { ?>
        	<?php echo $header['header_content'] ?>
       	<?php } elseif($header['header_type'] == 3 && $header['header_flash'] != '') { ?>
        	<embed src="<?=url::base()?>uploads/header/<?php echo $header['header_flash']?>" width="1024px" height="200" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
        <?php } elseif ($header['header_type'] <3 && $header['header_image'] != '') { 
		$src = "uploads/header/".$header['header_image'];
        $maxw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST');
        $maxh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST');
		$arr = MyImage::thumbnail($maxw,$maxh,$src);
		?>
        	<img src="<?=url::base()?>uploads/header/<?php echo $header['header_image']?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'auto'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):@$maxh?>"/>
        <?php } else { ?>
        	<?php if ($header['header_type'] == 3) { ?>
            	<img src="<?php echo url::base()?>themes/admin/pics/flash3.png" title="<?php echo Kohana::lang('admin_config_lang.msg_no_flash')?>" /> 
            <?php } else { ?>
              	<img src="<?php echo url::base()?>themes/admin/pics/no_img.jpg" title="<?php echo Kohana::lang('admin_config_lang.msg_no_image')?>" />
            <?php } // end if ?>
        <?php } // end if ?>        
    </div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_banner')?>&nbsp;<?php echo Kohana::lang('global_lang.lbl_top')?>:</div> 
    <div class="yui3-u-4-5">
    <?php if (isset($mr['page_id'])) { ?>	
    	<?php if($this->permisController('add')) { ?>
        <button type="button" name="btn_add_com_top" class="button new" onclick="javascript:showpopup('<?php echo url::base()?>admin_layout/insert_banner/<?php echo $mr['page_id']?>/10',800,600);"/><span><?php echo Kohana::lang('layout_lang.btn_insert_ban')?></span></button>
        <?php }//add?>
    	<?php if($this->permisController('delete')) { ?>
		<?php if (!empty($mr['top_T_banner'])) { ?>
        <button type="button" name="btn_del_ban_top" id="btn_del_ban_top" class="button remove" onclick="javascript:location.href='<?php echo url::base()?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/10'"/>
        <span><?php echo Kohana::lang('layout_lang.btn_remove_ban') ?></span></button>
    	<?php } // end if ?>
        <?php }//delete?>
    <?php } // end if ?>
    </div>   
</div>
<div class="yui3-g">
    <?php if (!empty($mr['top_T_banner'])) { ?>
    <table class="list" cellspacing="1" cellpadding="5">    
    <tr class="list_header">			
        <td align="center" width="90" colspan="3"><?php echo Kohana::lang('global_lang.lbl_banner')?></td>      		
    </tr>
    <?php foreach ($list_top_T_banner as $i => $banner) { ?>
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
            <?php if ($i == 0 && count($list_top_T_banner) > 1) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/10/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
            </a>
            <?php } elseif ($i == (count($list_top_T_banner) - 1) && count($list_top_T_banner) > 1) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/10/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
            </a>
            <?php } elseif (count($list_top_T_banner) > 2) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/10/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
            </a>
            <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/10/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
            </a>
            <?php } // end if ?>
        </td>
        <?php if($this->permisController('delete')) { ?>
        <td align="center">
        <a href="<?=url::base() ?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/10/<?php echo $banner['banner_id']?>" class="ico_delete"><?=Kohana::lang('global_lang.btn_delete') ?></a>
        </td>
        <?php }//delete?>
        <?php }//edit,delete?>    
    </tr>
    <?php } // end foreach ?>    		
    </table>
    <?php } // end if?>
</div>
<div class="yui3-g center">	
    <?php echo Kohana::lang('layout_lang.lbl_center_menu')?><br />
    <select name="sel_center_menu_level">
        <option value="0"><?php echo Kohana::lang('layout_lang.lbl_multi_lv')?></option>
        <option value="1" <?php echo ($mr['center_menu_level']==1)?'selected="selected"':''?>><?php echo Kohana::lang('layout_lang.lbl_one_lv')?></option>
    </select><br />
    <?php $list_menu = ($mr['center_menu_level'] == 0) ? $list_multi_menu : $list_one_menu?>
    <select name="sel_center_menu[]" multiple="multiple" size="<?php echo count($list_menu)?>"> 
	<?php foreach ($list_menu as $page) { ?> 
        <?php
            $expand = '';
            if ($mr['center_menu_level'] == 0)
            {
                for ($i=1;$i<$page['page_level'];$i++)
                    $expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                $expand .= '|----';
            }
        ?>
        <?php $selected = in_array($page['page_id'],$arr_center_menu) ? 'selected="selected"' : ''?>
        <option value="<?php echo $page['page_id']?>" <?php echo $selected?>><?php echo $expand.$page['page_title']?></option>
    <?php } // end foreach ?>           
    </select>
</div>
<div class="yui3-g">	
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_banner')?>&nbsp;<?php echo Kohana::lang('global_lang.lbl_bottom')?>:</div> 
    <div class="yui3-u-4-5">
    <?php if (isset($mr['page_id'])) { ?>
    	<?php if($this->permisController('add')) { ?>
        <button type="button" name="btn_add_com_top" class="button new" onclick="javascript:showpopup('<?php echo url::base()?>admin_layout/insert_banner/<?php echo $mr['page_id']?>/11',800,600);"/><span><?php echo Kohana::lang('layout_lang.btn_insert_ban')?></span></button>
        <?php }//add?>
        <?php if($this->permisController('delete')) { ?>
    	<?php if (!empty($mr['top_B_banner'])) { ?>
        <button type="button" name="btn_del_ban_top" id="btn_del_ban_top" class="button remove" onclick="javascript:location.href='<?php echo url::base()?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/11'"/>
        <span><?=Kohana::lang('layout_lang.btn_remove_ban') ?></span></button>
    	<?php } //end if ?>
        <?php }//delete?>
    <?php } //end if ?>
    </div>   
</div>
<div class="yui3-g">
    <?php if (!empty($mr['top_B_banner'])) { ?>
    <table class="list" cellspacing="1" cellpadding="5">	
    <tr class="list_header">			
        <td align="center" width="90" colspan="3"><?php echo Kohana::lang('global_lang.lbl_banner')?></td>      		
    </tr>
    <?php foreach ($list_top_B_banner as $i => $banner) { ?>
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
            <?php if ($i == 0 && count($list_top_B_banner) > 1) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/1/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
            </a>
            <?php } elseif ($i == (count($list_top_B_banner) - 1) && count($list_top_B_banner) > 1) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/11/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
            </a>
            <?php } elseif (count($list_top_B_banner) > 2) { ?>
            <a href="<?php echo url::base()?>admin_layout/order_banner/down/<?php echo $mr['page_id']?>/11/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" />
            </a>
            <a href="<?php echo url::base()?>admin_layout/order_banner/up/<?php echo $mr['page_id']?>/11/<?php echo $banner['banner_id']?>">
                <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" />
            </a>
            <?php } // end if ?>
        </td>
        <?php if($this->permisController('delete')) { ?>
        <td align="center">
        <a href="<?=url::base() ?>admin_layout/remove_banner/<?php echo $mr['page_id']?>/11/<?php echo $banner['banner_id']?>" class="ico_delete"><?=Kohana::lang('global_lang.btn_delete') ?></a>
        </td>
        <?php }//delete?>
        <?php }//edit,delete?>         
    </tr>
    <?php } // end foreach ?>    
    </table>
    <?php } // end if ?>
</div>
</div>
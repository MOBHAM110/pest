<table class="album" align="center" cellspacing="0" cellpadding="0">
<tr>
    <td class="album_top">
	<div class="album_top_TT"><?php echo $mr['page_content']?></div>
    <div class="album_top_btn">
	<?php if ((isset($this->sess_cus['id']) && $this->sess_cus['level'] <= $mr['page_write_permission']) || $mr['page_write_permission'] == 5) { ?>
    <button type="button" onclick="javascript:location.href='<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/create'"><?php echo Kohana::lang('album_lang.btn_post')?></button>    <?php } // end if ?>
    </div>
    </td>            
</tr>  
<tr>
	<td class="album_middle">    
    	<div class="album_middle_Co">
		<?php foreach ($mlist as $i => $ml) { ?>        
            <div class="album_item">			
                <div class="album_item_T"></div>
                <div class="album_item_M">
                	<div class="album_image">
                    <?php if (!empty($ml['bbs_file'])) { 
						$src = "uploads/album/".$ml['bbs_file'];
						$arr = MyImage::thumbnail(125,125,$src);
					?>
                        <a href="<?php echo url::base()?>album-<?php echo $this->page_id?>-<?php echo $ml['bbs_id']?>/<?php echo MyFormat::title_url($ml['bbs_title']);?>">
                        <img class="album_img" src="<?php echo $ml['bbs_file_path_html']?>" border="0" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'125px'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):'auto'?>" alt="<?php echo $ml['bbs_title'] ?>" title="<?php echo $ml['bbs_title'] ?>" />
                        </a>
                    <?php } else { ?>
                        <img class="album_img" src="<?php echo url::base()?>themes/admin/pics/no_img.jpg" border="0" />
                    <?php } // end if ?>
                    </div>
                	<div class="album_item_TT">
                    <a href="<?php echo url::base()?>album-<?php echo $this->page_id?>-<?php echo $ml['bbs_id']?>/<?php echo MyFormat::title_url($ml['bbs_title']);?>"><?php echo $ml['bbs_title']?></a>
                    </div>
                	<div class="album_item_view">
					<?php echo Kohana::lang('global_lang.lbl_view')?> : <?php echo $ml['bbs_count']?>
                    <?php if (!empty($this->sess_cus)) { ?>
                    <?php if (($this->sess_cus['username'] == $ml['bbs_author'] && !empty($ml['bbs_author'])) || $this->sess_cus['level'] < 3) { ?>
                    <a href="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/edit/id/<?php echo $ml['bbs_id']?>">
                        <img src="<?php echo $this->site['theme_url']?>index/pics/icon_edit.png" border="0" alt="Click here to edit">
                    </a>
                    <a href="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/delete/id/<?php echo $ml['bbs_id']?>">
                         <img border="0" src="<?php echo $this->site['theme_url']?>index/pics/icon_del.png" alt="Click here to delete" />
                    </a>
					<?php } // end if ?>
                    <?php } // end if ?>
                	</div>
                </div>
                <div class="album_item_B"></div>
            </div>
        <?php } // end foreach ?>	
        </div>
	</td>
</tr>
<?php if(!empty($this->pagination->total_items) && $this->pagination->total_items>15){?>
<tr>
    <td width="100%" align="left" class="album_bottom">
    <table><tr>
    <td align="left">
    <form name="frmlist" id="frmlist" action="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/search" method="post" >
            <input type="text" name="txt_keyword" id="txt_keyword" value="<?php echo isset($mr['keyword'])?$mr['keyword']:''?>" />
            <select name="sel_type">
                <option value='' > - - - </option>
                <option value='1' <?php echo isset($mr['type1_selected'])?$mr['type1_selected']:''?>><?php echo Kohana::lang('global_lang.lbl_title')?></option>
                <option value='2' <?php echo isset($mr['type2_selected'])?$mr['type2_selected']:''?>><?php echo Kohana::lang('global_lang.lbl_author')?></option>
                <option value='3' <?php echo isset($mr['type3_selected'])?$mr['type3_selected']:''?>><?php echo Kohana::lang('global_lang.lbl_content')?></option>
          </select>&nbsp;&nbsp;<button type="submit"/><?php echo Kohana::lang('global_lang.btn_search')?></button>
        </form>
    </td>
    <td width="10%" nowrap="nowrap">
    <div class='pagination'><?php echo $this->pagination?></div>
    <?php echo Kohana::lang('global_lang.lbl_page')?>: (<?php echo $this->pagination->current_page?>/<?php echo $this->pagination->total_pages?>), <?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo $this->pagination->total_items?>
    </td>
    </tr></table>
    </td>
</tr>
<?php }//total_items?>
</table>
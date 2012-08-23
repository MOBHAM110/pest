<table class="news_detail" align="center" cellpadding="0" cellspacing="0">
<?php if(isset($mr['page_content']) && $mr['page_content']!==''){?>
<tr><td class="news_detail_T"><?php echo $mr['page_content']?></td></tr>
<?php }//if ?>
<?php if (isset($mr['bbs_id'])) { ?>
<tr>
    <td class="news_detail_M">
        <table class="news_detail_item" align="center" cellspacing="0" cellpadding="5">
        <tr><td><table align="center" cellpadding="0" cellspacing="0">
        <tr><td align="left" class="news_detail_item_TT"><div style="float:left;"><?php echo $mr['bbs_title']?></div>
        <!-- AddThis Button BEGIN --><div style="float:left;padding-left:5px;" class="addthis_toolbox addthis_default_style">
            <a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c9329f918eb26eb" class="addthis_button_compact">Share</a></div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c9329f918eb26eb"></script><!-- AddThis Button END -->
        </td>
        <td align="right">
        <?php if (!empty($this->sess_cus)) { ?>
        <?php if (($this->sess_cus['username'] == $mr['bbs_author'] && !empty($mr['bbs_author'])) || $this->sess_cus['level'] < 3) { ?>
        <a href="<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/edit/id/<?php echo $mr['bbs_id']?>">
            <img src="<?php echo $this->site['theme_url']?>index/pics/icon_edit.png" border="0" alt="Click here to edit">
        </a>
        <a href="<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/delete/id/<?php echo $mr['bbs_id']?>">
             <img border="0" src="<?php echo $this->site['theme_url']?>index/pics/icon_del.png" alt="Click here to delete" />
        </a>
        <?php } // end if ?>
        <?php } // end if ?>
        </td></tr>
        </table></td></tr>
        <tr>
          <td class="news_detail_item_Date"><?php echo (empty($mr['bbs_author']))?Kohana::lang('client_lang.lbl_anonymous').' - ':$mr['bbs_author']?><?php echo !empty($mr['bbs_date'])?' - '.$mr['bbs_date']:'' ?></td>
        </tr>
        <tr><td class="news_detail_item_Des">
		<?php if(!empty($mr['bbs_file'])){
            $src = "uploads/news/".$mr['bbs_file'];
			if(file_exists($src)){
            $arr = MyImage::thumbnail(150,150,$src);
            ?>
        <img class="img_detail" src="<?php echo url::base()?>uploads/news/<?php echo $mr['bbs_file']?>" border="0" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'150px'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):'auto'?>"  align="left"/>
        <?php }}//bbs_file?>
        <?php echo $mr['bbs_content']?>
		</td></tr>
        </table>
    </td>
</tr>
<?php } // end if ?>
<?php if (isset($this->sess_cus['id'])) { ?>
<?php if ($this->sess_cus['level'] <= $mr['page_write_permission']) { ?>
<tr>
	<td class="news_btn">
    <button onclick="javascript:location.href='<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/create'"><?php echo Kohana::lang('bbs_lang.btn_post_news')?></button>   	
	</td>
</tr>
<?php } // end if registered read permission ?>
<?php } elseif ($mr['page_write_permission'] == 5) { ?>
<tr>
	<td class="news_btn">    
    <button onclick="javascript:location.href='<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/create'"><?php echo Kohana::lang('news_lang.btn_post_news')?></button>   	
	</td>
</tr>
<?php } // end if ?>
<tr>
    <td>
        <table class="news_list" align="center" cellspacing="0" cellpadding="2">
        <tr>
            <th scope="col"><?php echo Kohana::lang('global_lang.lbl_title')?></th>
            <th scope="col"><?php echo Kohana::lang('global_lang.lbl_date')?></th>
            <?php if (isset($this->sess_cus['id'])) { ?><th scope="col" nowrap="nowrap" width="100px"><?php echo Kohana::lang('global_lang.lbl_action')?></th><?php } // if logined ?>
        </tr>
        <?php foreach ($mlist as $id => $ml) { ?>
        <tr>
            <td align="left">
            <?php if (isset($mr['bbs_id']) && $mr['bbs_id'] == $ml['bbs_id']) { ?>
            <img src="<?php echo $this->site['theme_url']?>index/pics/icon_light.gif" border="0" ><b><?php echo $ml['bbs_title']?></b>
            <?php } else { ?>
            <a href="<?php echo url::base()?>news-<?php echo $this->page_id?>-<?php echo $ml['bbs_id']?>/<?php echo MyFormat::title_url($ml['bbs_title']);?>">
            <?php echo $ml['bbs_title']?>&nbsp;           
            </a>	           	
            <?php } ?>
            <?php if ($ml['bbs_file']) { ?><img src="<?php echo url::base()?>themes/admin/pics/icon_photo.png" border="0" /><?php } // end if ?>
            </td>
            <td align="center">		
            <?php echo $ml['bbs_date']?>          	
            </td>
            <?php if (isset($this->sess_cus['id'])) { ?>
            <td>
            <?php if (($this->sess_cus['username'] == $ml['bbs_author'] && !empty($ml['bbs_author'])) || $this->sess_cus['level'] < 3) { ?>&nbsp;
            <a href="<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/edit/id/<?php echo $ml['bbs_id']?>">
            <img border="0" width="16" height="16" src="<?php echo $this->site['theme_url']?>index/pics/icon_edit.png" alt="Click here to edit" />
            </a>
            <a href="<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/delete/id/<?php echo $ml['bbs_id']?>">
            <img border="0" width="16" height="16" src="<?php echo $this->site['theme_url']?>index/pics/icon_del.png" alt="Click here to delete" />
            </a>
            <?php } // end if ?>
            </td>
            <?php } // if logined ?>
        </tr>
        <?php } // end foreach ?>
        </table>
	</td>
</tr>
<tr><td>
<table align="center" cellpadding="0" cellspacing="0">
<tr><td class="news_detail_M">
<form name="frmlist" id="frmlist" action="<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/search" method="post">
<select name="sel_type">
    <option value=''> - - - </option>
    <option value='1' <?php echo isset($mr['type1_selected'])?$mr['type1_selected']:''?> ><?php echo Kohana::lang('global_lang.lbl_title')?></option>
    <option value='3' <?php echo isset($mr['type3_selected'])?$mr['type3_selected']:''?> ><?php echo Kohana::lang('global_lang.lbl_content')?></option>
</select>
<input type="text" name="txt_keyword" id="txt_keyword" value="<?php echo isset($mr['keyword'])?$mr['keyword']:''?>" >&nbsp;
<button type="submit" class="button"/><span><?php echo Kohana::lang('global_lang.btn_search')?></span></button>
</form>
</td></tr>
</table>
</td></tr>
<tr>
    <td class="news_detail_M" align="center"><button type="button" name="btn_back" onclick="javascript:location.href='<?php echo url::base().$this->site['history']['back']?>'"><?php echo Kohana::lang('global_lang.btn_back')?></button></td>
</tr>
<tr><td class="news_detail_B"></td></tr>
</table>
<?php require('detail_js.php')?>
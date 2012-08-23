<table class="news" align="center" cellspacing="0" cellpadding="0">
<?php if(!empty($mr['page_content'])){?>
<tr>
	<td class="news_top" align="center"><?php echo $mr['page_content']?></td>
</tr>
<?php }//page_content?>
<?php if (isset($this->sess_cus['id'])) { ?>
<tr>
	<td class="news_btn">
	<?php if ($this->sess_cus['level'] <= $mr['page_write_permission']) { ?>
        <button onclick="javascript:location.href='<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/create'" type="submit"><?php echo Kohana::lang('news_lang.btn_post_news')?></button>
    <?php } // end if registered read permission ?>
	<?php } elseif ($mr['page_write_permission'] == 5) { ?>
        <button onclick="javascript:location.href='<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/create'" type="submit"><?php echo Kohana::lang('news_lang.btn_post_news')?></button>
	</td>
</tr>
<?php } // end if ?>
<tr>
    <td class="news_middle" align="center">
        <table class="news_middle_Co">
        <?php foreach ($mlist as $list) { ?>
        <tr><td>
        <table width="100%" border="0" class="news_item" cellpadding="0" cellspacing="0">
        <tr>
            <td class="news_item_TT" align="left" nowrap="nowrap">
            <a href="<?php echo url::base()?>news-<?php echo $this->page_id?>-<?php echo $list['bbs_id']?>/<?php echo MyFormat::title_url($list['bbs_title']);?>"><?php echo $list['bbs_title']?></a>
            <?php if (isset($this->sess_cus['id'])) { ?>
            <?php if (($list['bbs_author'] == $this->sess_cus['username'] && !empty($list['bbs_author'])) || $this->sess_cus['level'] < 3) { ?>
            <a href="javascript:location.href='<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/edit/id/<?php echo $list['bbs_id']?>'">
                <img src="<?php echo $this->site['theme_url']?>index/pics/icon_edit.png" border="0" alt="Click here to edit">
            </a>
            <a href="<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/delete/id/<?php echo $list['bbs_id']?>">
                 <img border="0" src="<?php echo $this->site['theme_url']?>index/pics/icon_del.png" alt="Click here to delete" />
            </a>
            <?php } // end if ?>
            <?php } // end if ?> 
            <span class="news_item_date"><?php echo $list['bbs_date']?></span>
            </td>
        </tr>
        <tr><td class="news_item_Des">
        <?php if(!empty($list['bbs_file'])){
			$news_path = "uploads/news/thumb_".$list['bbs_file'];
			if(!file_exists($news_path)) $news_path = "uploads/news/".$list['bbs_file'];
			$arr = MyImage::thumbnail(120,120,$news_path);
			?>
        <a href="<?php echo url::base()?>news-<?php echo $this->page_id?>-<?php echo $list['bbs_id']?>/<?php echo MyFormat::title_url($list['bbs_title']);?>"><img align="left" src="<?php echo url::base()?><?php echo $news_path;?>" border="0" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'120px'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):'auto'?>" alt="<?php echo $list['bbs_title']?>" title="<?php echo $list['bbs_title']?>"/></a>
        <?php }//bbs_file?>
        <?php echo text::limit_words(strip_tags($list['bbs_content'],30))?>
        &nbsp;<a class="news_item_more" href="<?php echo url::base()?>news-<?php echo $this->page_id?>-<?php echo $list['bbs_id']?>/<?php echo MyFormat::title_url($list['bbs_title']);?>"><?php echo strtolower(Kohana::lang('home_lang.lbl_more'))?></a>
        </td></tr>
        </table>
        </td></tr>
        <?php }//mlist?>
        </table>
    </td>
</tr>
<tr>
	<td class="news_bottom">
    <table><tr>
    <td align="left">
    <form name="frmlist" id="frmlist" action="<?php echo url::base()?>news/pid/<?php echo $this->page_id?>/search" method="post" >
    <input type="text" name="txt_keyword" id="txt_keyword" value="<?php echo isset($mr['keyword'])?$mr['keyword']:''?>" />
    <select name="sel_type">
    <option value='' > - - - </option>
    <option value='1' <?php echo isset($mr['type1_selected'])?$mr['type1_selected']:''?>><?php echo Kohana::lang('global_lang.lbl_title')?></option>                
    <option value='3' <?php echo isset($mr['type3_selected'])?$mr['type3_selected']:''?>><?php echo Kohana::lang('global_lang.lbl_content')?></option>
    </select>              
    <button type="submit"/><?php echo Kohana::lang('global_lang.btn_search')?></button>
    </form>
    </td>
    <td width="10%" nowrap="nowrap">
    <div class='pagination'><?php echo $this->pagination?></div>
    <?php echo Kohana::lang('global_lang.lbl_page')?>: (<?php echo $this->pagination->current_page?>/<?php echo $this->pagination->total_pages?>), <?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo $this->pagination->total_items?>
    </td>
    </tr></table>       
    </td>
</tr>
</table>
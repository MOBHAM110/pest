<table class="home_news" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="home_news_T_L"></td>
    <?php if(isset($mlist['page_is_tab'])){ ?>
    <td></td><?php }else { ?>
    <td class="home_news_T_Ce"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="home_news_TT"><?php echo isset($mlist['page_is_tab']) ? '' : $mlist['page_title']?></td>
    <td class="home_news_more">
    <a href="<?php echo url::base()?>rss/get/<?php echo $mlist['page_id']?>"><img align="middle" src="<?php echo $this->site['theme_url']?>home/pics/icon_rss.png"/></a>&nbsp;
	<?php if (!isset($mlist['page_is_tab'])) { ?>
	<a class="more" href="<?php echo url::base()?>news-<?php echo $mlist['page_id']?>/<?php echo MyFormat::title_url($mlist['page_title']);?>"><?php echo Kohana::lang('home_lang.lbl_more')?></a>
	<?php } // end if page not tab ?></td></tr></table>
	</td> <?php } ?>
    <td class="home_news_T_R">
	</td>    
</tr>
<tr>
	<td class="home_news_M_L"></td>
    <td class="home_news_M_Ce">
        <table class="home_news_M_Ce_Co" cellpadding="0" cellspacing="0">
        <?php foreach ($mlist['list_bbs'] as $k => $news) { ?>    
        <tr><td valign="top">
        <table class="home_news_item" cellspacing="0" cellpadding="0">
        <tr>
            <td class="home_news_item_TT" valign="top">            <a href="<?php echo url::base()?>news-<?php echo $mlist['page_id']?>-<?php echo $news['bbs_id']?>/<?php echo MyFormat::title_url($news['bbs_title']);?>"><?php echo $news['bbs_title']?></a>&nbsp;[<?php echo $news['bbs_date']?>]
            </td>
        </tr>
        <tr>
            <td class="home_news_item_Co">
            <?php if (isset($news['bbs_file'])) { 
				$news_path = "uploads/news/thumb_".$news['bbs_file'];
				if(!file_exists($news_path)) $news_path = "uploads/news/".$news['bbs_file'];
				$arr = MyImage::thumbnail(120,120,$news_path);
			?>
             <a href="<?php echo url::base()?>news-<?php echo $mlist['page_id']?>-<?php echo $news['bbs_id']?>/<?php echo MyFormat::title_url($news['bbs_title']);?>"><img align="left" src="<?php echo url::base()?><?php echo $news_path;?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'120px'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):'auto'?>" alt="<?php echo $news['bbs_title'];?>" title="<?php echo $news['bbs_title'];?>"/></a>
            <?php } // end if empty bbs_file ?>
            <?php echo $news['bbs_content']?>&nbsp;<a class="more" href="<?php echo url::base()?>news-<?php echo $mlist['page_id']?>-<?php echo $news['bbs_id']?>/<?php echo MyFormat::title_url($news['bbs_title']);?>"><?php echo Kohana::lang('home_lang.lbl_more')?></a>
            </td>
        </tr>
        </table>
        </td></tr>  
        <?php } // end for event ?>
        </table>
    </td>
	<td class="home_news_M_R"></td>
</tr>
<tr>
    <td class="home_news_B_L"></td>
    <td class="home_news_B_Ce"></td>
    <td class="home_news_B_R"></td>
</tr>
</table>
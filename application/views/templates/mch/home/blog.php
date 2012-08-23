<table class="home_blog" cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
	<td class="home_blog_T_L"></td>
        <?php if(isset($mlist['page_is_tab'])){ ?>
    <td></td><?php }else { ?>
	<td class="home_blog_T_Ce"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="home_blog_TT"><?php echo isset($mlist['page_is_tab']) ? '' : $mlist['page_title']?></td>
    <td class="home_blog_more">
    <a href="<?php echo url::base()?>rss/get/<?php echo $mlist['page_id']?>"><img align="middle" src="<?php echo $this->site['theme_url']?>home/pics/icon_rss.png"/></a>&nbsp;
    <a class="more" href="<?php url::base()?>blog-<?php echo $mlist['page_id']?>/<?php echo MyFormat::title_url($mlist['page_title']);?>"><?php echo Kohana::lang('home_lang.lbl_more')?></a></td>
    </tr></table></td> <?php } ?>
    <td class="home_blog_T_R">
	</td>    
</tr>
<tr>
	<td class="home_blog_M_L"></td>
	<td class="home_blog_M_Ce">
    	<table class="home_blog_M_Ce_Co" align="center" cellspacing="0" cellpadding="0">
        <?php foreach ($mlist['list_bbs'] as $k => $blog) { ?>  
        <tr valign="top"><td>
        <table class="home_blog_item" cellspacing="0" cellpadding="0">
        <tr>
            <td class="home_blog_item_TT">                    
            <a href="<?php echo url::base()?>blog-<?php echo $mlist['page_id']?>-<?php echo $blog['bbs_id']?>/<?php echo MyFormat::title_url($blog['bbs_title']);?>"><?php echo $blog['bbs_title']?></a>&nbsp;[<?php echo $blog['bbs_date']?>]
            </td>
        </tr>
        <tr>
            <td class="home_blog_item_Co"><?php echo $blog['bbs_content']?>&nbsp;<a class="more" href="<?php echo url::base()?>blog-<?php echo $mlist['page_id']?>-<?php echo $blog['bbs_id']?>/<?php echo MyFormat::title_url($blog['bbs_title']);?>"><?php echo Kohana::lang('home_lang.lbl_more')?></a></td>
        </tr>
        </table>
        </td></tr>  
        <?php } // end for event ?>
        </table>
	</td>
    <td class="home_blog_M_R"></td> 
</tr>
<tr>
    <td class="home_blog_B_L"></td>
    <td class="home_blog_B_Ce"></td>
    <td class="home_blog_B_R"></td>
</tr> 
</table>
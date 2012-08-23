<table class="blog" align="center" cellspacing="0" cellpadding="0">
<tr>
	<td class="blog_top" align="center"><?php echo $mr['page_content']?></td>
</tr>
<?php if (isset($this->sess_cus['id'])) { ?>
<tr>
	<td class="blog_btn">
	<?php if ($this->sess_cus['level'] <= $mr['page_write_permission']) { ?>
    <button onclick="javascript:location.href='<?php echo url::base()?>blog/pid/<?php echo $this->page_id?>/create'" type="submit"><?php echo Kohana::lang('blog_lang.btn_post_blog')?></button>
    <?php } // end if registered read permission ?>
    <?php } elseif ($mr['page_write_permission'] == 5) { ?>
    <button onclick="javascript:location.href='<?php echo url::base()?>blog/pid/<?php echo $this->page_id?>/create'" type="submit"><?php echo Kohana::lang('blog_lang.btn_post_blog')?></button>
	</td>
</tr>
<?php } // end if ?>
<tr>
    <td class="blog_middle" align="center">
        <table class="blog_middle_Co">
        <?php foreach ($mlist as $list) { ?>
        <tr>
            <td>
                <table class="blog_item" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="blog_item_TT" align="left" nowrap="nowrap">
                        <a href="<?php echo url::base()?>blog-<?php echo $this->page_id?>-<?php echo $list['bbs_id']?>/<?php echo MyFormat::title_url($list['bbs_title']);?>"><?php echo $list['bbs_title']?></a>
                        <?php if (isset($this->sess_cus['id'])) { ?>
                        <?php if (($list['bbs_author'] == $this->sess_cus['username'] && !empty($list['bbs_author'])) || $this->sess_cus['level'] < 3) { ?>
                        <a href="javascript:location.href='<?php echo url::base()?>blog/pid/<?php echo $this->page_id?>/edit/id/<?php echo $list['bbs_id']?>'">
                            <img src="<?php echo $this->site['theme_url']?>index/pics/icon_edit.png" border="0" alt="Click here to edit">
                        </a>
                        <a href="<?php echo url::base()?>blog/pid/<?php echo $this->page_id?>/delete/id/<?php echo $list['bbs_id']?>">
                             <img border="0" src="<?php echo $this->site['theme_url']?>index/pics/icon_del.png" alt="Click here to delete" />
                        </a>
                        <?php } // end if ?>
                        <?php } // end if ?>           
                    </td>
                    <td class="blog_item_Date" align="right"><?php echo $list['bbs_date']?>
                    </td>
                </tr>
                <tr><td colspan="2" class="blog_item_Des"><?php echo $list['bbs_content']?>
                &nbsp;<a class="blog_item_more" href="<?php echo url::base()?>blog-<?php echo $this->page_id?>-<?php echo $list['bbs_id']?>/<?php echo MyFormat::title_url($list['bbs_title']);?>"><?php echo strtolower(Kohana::lang('home_lang.lbl_more'))?></a>
                </td></tr>
                </table>
            </td>
        </tr>
        <?php }?>
        </table>
    </td>
</tr>
<tr>
	<td class="blog_bottom">
    <table><tr>
    <td align="left">
    <form name="frmlist" id="frmlist" action="<?php echo url::base()?>blog/pid/<?php echo $this->page_id?>/search" method="post" >
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
<table class="album_detail" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="album_detail_T"><div style="float:left;"><?php echo $mr['bbs_title']?></div>
	<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style" style="float:left; padding-left:5px;"><a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c9329f918eb26eb" class="addthis_button_compact">Share</a>
	</div><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c9329f918eb26eb"></script>
	<!-- AddThis Button END -->
	<?php if (!empty($this->sess_cus)) { ?>
    <?php if (($this->sess_cus['username'] == $mr['bbs_author'] && !empty($mr['bbs_author'])) || $this->sess_cus['level'] < 3) { ?>
    <div style="float:right">
    <a href="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/edit/id/<?php echo $mr['bbs_id']?>">
        <img src="<?php echo $this->site['theme_url']?>index/pics/icon_edit.png" border="0" alt="Click here to edit">
    </a>
    <a href="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/delete/id/<?php echo $mr['bbs_id']?>">
         <img border="0" src="<?php echo $this->site['theme_url']?>index/pics/icon_del.png" alt="Click here to delete" />
    </a>
    </div>    
    <?php } // end if ?>
    <?php } // end if ?>
	</td>
</tr>
<tr>
	<td class="album_detail_M" align="center">
	<div class="album_detail_Co"><?php echo $mr['bbs_content']?></div>
    </td>
</tr>
<?php foreach ($mr['list_file'] as $file) { ?>
<?php 
 	$src = $file['file_path_html'] ;
	$maxw = $this->site['config']['MAX_WIDTH_IMAGE_DETAIL'];
	$maxh = $this->site['config']['MAX_HEIGHT_IMAGE_DETAIL'];
	$arr = MyImage::thumbnail($maxw,$maxh,$src);
?>
<tr>
	<td class="album_detail_M" align="center">
    <div class="img_album">
    <img class="img_album" src="<?php echo url::base()?><?php echo $file['file_path_html']?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):$maxw?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):$maxh?>" title="<?php echo $file['bbs_file_description']?>" alt="<?php echo $mr['bbs_title']?>"/>
    <?php if (!empty($this->sess_cus)) { ?>
    <?php if (($this->sess_cus['username'] == $mr['bbs_author'] && !empty($mr['bbs_author'])) || $this->sess_cus['level'] < 3) { ?>
	<a href="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/delete_image/<?php echo $file['bbs_file_id']?>/id/<?php echo $mr['bbs_id']?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')">
    <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete')?>" border="0" />
    </a>
    <?php } // end if ?>
    <?php } // end if ?>
    <br/><?php echo $file['bbs_file_description']?>
    </div>
	</td>
</tr>
<?php } // end for ?>
<tr>
	<td class="album_detail_M">
        <table class="album_detail_des" cellpadding="0" cellspacing="0">
        <tr>
            <th width="23%" style="text-align:right;" scope="row"><?php echo Kohana::lang('global_lang.lbl_author')?></th>
            <td width="42%"><?php echo $mr['bbs_author']?></td>
            <td width="35%" style="text-align:right;"><?php echo $mr['bbs_date_created']?> | <?php echo Kohana::lang('global_lang.lbl_view')?> : <?php echo $mr['bbs_count']?></td>
        </tr>
        <?php foreach ($mr['list_file'] as $file) { ?>      
        <tr>
            <th scope="row" style="text-align:right;"><?php echo Kohana::lang('album_lang.lbl_attachment')?></th>
            <td> 
                <a href="javascript:location.href='<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/download/<?php echo $file['bbs_file_id']?>'">
                <img src="<?php echo $this->site['theme_url']?>index/pics/icon_file.gif" border="0">&nbsp;<?php echo $file['bbs_file_name']?></a><br />(<?php echo $file['file_size']?>)
            </td>
            <td style="text-align:right;"><?php echo Kohana::lang('global_lang.lbl_download')?> : <?php echo $file['bbs_file_download']?></td>
        </tr>
        <?php } // end for ?>
        </table>
	</td>
</tr>
<tr>
	<td class="album_btn">
    <?php if ((!empty($this->sess_cus) && (($this->sess_cus['username'] == $mr['bbs_author'] && !empty($mr['bbs_author'])) || $this->sess_cus['level'] < 3)) || $mr['page_write_permission'] == 5) {?>
    <button onclick="javascript:update_album('edit')"><?php echo Kohana::lang('global_lang.btn_edit')?></button>
	<button onclick="javascript:update_album('delete')"><?php echo Kohana::lang('global_lang.btn_delete')?></button>
    <?php }//edit/del ?>
<?php if ((isset($this->sess_cus['id']) && $this->sess_cus['level'] <= $mr['page_write_permission']) || $mr['page_write_permission'] == 5) { ?>
    <button onclick="javascript:location.href='<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/create'"><?php echo Kohana::lang('album_lang.btn_post')?></button>  
    <?php } // end if ?>
	</td>
</tr>
<?php if(Configuration_Model::get_value('ENABLE_COMMENT')){?>
<tr>
	<td class="album_detail_M">
    	<form action="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/comment" method="post">
    	<table class="album_detail_comment" width="100%" cellpadding="3" cellspacing="2">
        <tr>
        	<td align="left"><strong><?php echo Kohana::lang('bbs_lang.lbl_leave_comment')?></strong></td>
        </tr>
        <tr>
        	<td align="left">
            <?php if (isset($this->sess_cus['id'])) { ?>
            	<strong><?php echo $this->sess_cus['username']?></strong>
                <input type="hidden" name="txt_name" value="<?php echo $this->sess_cus['username']?>" />
            <?php } else { ?>
            	<input size="30" type="text" name="txt_name" value="<?php echo isset($mr['com_name'])?$mr['com_name']:''?>"/>
                &nbsp;<?php echo Kohana::lang('account_lang.lbl_name')?> (<font color="#FF0000"><?php echo Kohana::lang('bbs_lang.lbl_required')?></font>)
            <?php } // end if ?>
            </td>			
		</tr>
        <tr>
        	<td align="left">
			<?php if (isset($this->sess_cus['id'])) { ?>
            	<strong><?php echo $this->sess_cus['email']?></strong>
                <input type="hidden" name="txt_email" value="<?php echo $this->sess_cus['email']?>" />
            <?php } else { ?>
                <input size="30" type="text" name="txt_email" value="<?php echo isset($mr['com_email'])?$mr['com_email']:''?>"/>
                &nbsp;<?php echo Kohana::lang('account_lang.lbl_email')?>
            <?php } // end if ?>            
            </td>
        </tr>
        <tr>
        	<td align="left"><input size="10" type="password" name="txt_pass" />&nbsp;<?php echo Kohana::lang('account_lang.lbl_pass')?> (<font color="#FF0000"><?php echo Kohana::lang('bbs_lang.lbl_required')?></font>)</td>
        </tr>
        <tr><td align="left"><?php echo Captcha::factory()->render()?></td></tr>
        <tr>
        	<td>
        		<input type="text" name="captcha_response" size="5"/>&nbsp;<font color="#FF0000"><?php echo Kohana::lang('bbs_lang.lbl_required')?></font>
            </td>
        </tr>
        <tr>
        	<td align="left">
            	<textarea name="txt_content_com" rows="10" style="width:100%;"><?php echo isset($mr['com_content'])?$mr['com_content']:''?></textarea>
			</td>
        </tr>
        <tr>
        	<td align="left"><button type="submit" name="save_comment"><?php echo Kohana::lang('bbs_lang.btn_comment')?></button></td>
        </tr>
        </table>
        <input type="hidden" name="hd_id" value="<?php echo isset($mr['bbs_id'])?$mr['bbs_id']:''?>" />
        </form>
    </td>
</tr>
<?php if (count($list_comment)>0) { ?>
<tr>
	<td class="album_detail_M">
        <table class="album_detail_show_comment" width="100%" cellpadding="3" cellspacing="0" id="album_comment">
        <?php foreach ($list_comment as $com) { ?>
        <tr style="background-color:#CCC;" id="comment-<?php echo $com['comment_id']?>">
            <td align="left"><strong><?php echo $com['comment_author']?></strong></td>
            <td align="right"><a href="#comment-<?php echo $com['comment_id']?>"><?php echo $com['comment_time']?></a></td>
        </tr>
        <tr style="text-align:left; padding-left:10px;">
            <form name="modify_comment" action="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/modify_comment/<?php echo $com['comment_id']?>" method="post">
            <td colspan="2">
                <?php echo Kohana::lang('account_lang.lbl_pass')?>&nbsp;<input type="password" name="txt_com_pass" size="10" />&nbsp;
                <select name="sel_action">
                    <option value="edit"><?php echo Kohana::lang('global_lang.lbl_edit')?></option>
                    <option value="delete"><?php echo Kohana::lang('global_lang.lbl_delete')?></option>
                </select>&nbsp;
                <button type="submit"><?php echo Kohana::lang('bbs_lang.btn_action')?></button>
            </td>
            </form>
        </tr>
        <tr>    	
            <td colspan="2" style="text-align:left">
            <?php $edit_com_id = $this->session->get('edit_com_id')?>
            <?php if (!empty($edit_com_id) && $edit_com_id == $com['comment_id']) { ?>
            <form name="save_comment" action="<?php echo url::base()?>album/pid/<?php echo $this->page_id?>/save_comment/<?php echo $com['comment_id']?>" method="post">
                <textarea name="txt_content_com" rows="10" style="width:100%;"><?php echo $com['comment_content']?></textarea><p>
                <button type="submit"><?php echo Kohana::lang('global_lang.btn_save')?></button>
            </form>     
            <?php } else { ?>
                <?php echo text::auto_p($com['comment_content'])?>
            <?php } // end if edit com ?>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <?php } // end foreach ?>
        </table>
    </td>
</tr>
<?php } // end if ?>
<?php }//Enable Comment?>
<tr>
	<td class="album_detail_M" align="center"><button type="button" name="btn_back" onclick="javascript:location.href='<?php echo url::base().$this->site['history']['back']?>'"><?php echo Kohana::lang('global_lang.btn_back')?></button></td>
</tr>
<tr><td class="album_detail_B"></td></tr>
</table>
<?php require('detail_js.php')?>
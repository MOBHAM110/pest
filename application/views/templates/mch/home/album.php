<table class="home_album" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="home_album_T_L"></td>
    <td class="home_album_T_Ce"></td>
	<td class="home_album_T_R"></td>    
</tr>
<tr>
	<td class="home_album_M_L"></td>
	<td class="home_album_M_Ce">
    <div class="rollBox"><div style="float:left; width:20px; padding-top:23px;"><img onmousedown="ISL_GoDown()" onmouseup="ISL_StopDown()" onmouseout="ISL_StopDown()"  class="img1" src="<?php echo $this->site['theme_url']?>sildeshow_img/pics/btn_left.png" width="15" height="61" /></div>
     <div class="Cont" id="ISL_Cont"  style="float:left; width:425px;">
      <div class="ScrCont">
       <div id="List1">
<?php foreach ($mlist['list_bbs'] as $k => $album) { ?>        
        <div class="pic">
          <a href="<?php echo url::base()?>album-<?php echo $mlist['page_id']?>-<?php echo $album['bbs_id']?>/<?php echo MyFormat::title_url($album['bbs_title']);?>"><?php echo $album['bbs_title']?></a>
           <a href="<?php echo url::base()?>album-<?php echo $mlist['page_id']?>-<?php echo $album['bbs_id']?>/<?php echo MyFormat::title_url($album['bbs_title']);?>">
           <?php if (isset($album['bbs_file'])) { 
				$src = "uploads/album/".$album['bbs_file'];
				$arr = MyImage::thumbnail(85,85,$src);
			?>
			 <img class="home_album_item_img" src="<?php echo url::base()?>uploads/album/<?php echo $album['bbs_file']?>" border="0" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'85px'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):'auto'?>" title="<?php echo $album['bbs_title']?>" alt="<?php echo $album['bbs_title']?>"/>           	
			<?php } else { ?>
                <img class="home_album_item_img" src="<?php echo url::base()?>themes/admin/pics/no_img.jpg" border="0"/>
            <?php } // end if empty bbs_file ?></a>
         </div>
 <?php } // end foreach album ?>
       </div>
      </div>
     </div><div style="float:right; width:20px; padding-top:23px"><img  onmousedown="ISL_GoUp()" onmouseup="ISL_StopUp()" onmouseout="ISL_StopUp()" class="img2" src="<?php echo $this->site['theme_url']?>sildeshow_img/pics/btn_right.png" width="15" height="61" /></div>
    </div>
         </td>
     <td class="home_album_M_R"></td> 
</tr>
<tr>
    <td class="home_album_B_L"></td>
    <td class="home_album_B_Ce"></td>
    <td class="home_album_B_R"></td>
</tr> 
</table>
<script type="text/javascript" src="<?php echo url::base()?>plugins/sildeshow/sildeshow.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->site['theme_url']?>sildeshow_img/sildeshow_img.css" media="screen">
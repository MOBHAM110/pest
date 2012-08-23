<table class="home_bbs" cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
	<td class="home_bbs_T_L"></td>
    <?php if(isset($mlist['page_is_tab'])){ ?>
    <td></td><?php }else { ?>
    <td class="home_bbs_T_Ce">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td class="home_bbs_TT"><?php echo isset($mlist['page_is_tab']) ? '' : $mlist['page_title']?></td>
    <td class="home_bbs_more">
    <a href="<?php echo url::base()?>rss/get/<?php echo $mlist['page_id']?>"><img align="middle" src="<?php echo $this->site['theme_url']?>home/pics/icon_rss.png"/></a> </td>
    </tr>
    </table>
    </td><?php } ?>
	<td class="home_bbs_T_R">
	</td>    
</tr>
<tr>
	<td class="home_bbs_M_L"></td>
    <td class="home_bbs_M_Ce" ><table class="home_bbs_M_Ce" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="home_bbs_M_Ce_T"></td>
          </tr>
          <tr>
            <td class="home_bbs_M_Ce_M">
            	<table class="home_bbs_item" cellpadding="0" cellspacing="0">
                <tr><td class="home_bbs_item_T"></td></tr>
                <?php foreach ($mlist['list_bbs'] as $bbs) { ?><tr>
                <td class="home_bbs_item_M" style="padding-left:5px;"> 
                <a href="<?php echo url::base()?>bbs-<?php echo $mlist['page_id']?>-1-<?php echo $bbs['bbs_id']?>/<?php echo MyFormat::title_url($bbs['bbs_title']);?>"><?php echo $bbs['bbs_title']?>
                </a>               
                </td>   
                <td style="text-align:right; padding-right:5px;">[<?php echo $bbs['bbs_date']?>]</td>
                </tr>
                <?php } // end foreach bbs ?> 
                <tr><td class="home_bbs_item_B"></td></tr>        
                </table>
            </td>
          </tr>
          <tr>
            <td class="home_bbs_M_Ce_B">&nbsp;</td>
          </tr>
        </table>
    </td>
    <td class="home_bbs_M_R"></td> 
</tr>
<tr>
    <td class="home_bbs_B_L"></td>
    <td class="home_bbs_B_Ce"></td>
    <td class="home_bbs_B_R"></td>
</tr> 
</table>
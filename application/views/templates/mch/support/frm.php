<table class="support" align="center" cellspacing="0" cellpadding="0">
<tr><td class="support_T"><?php echo Kohana::lang('support_lang.tt_page') ?></td></tr>
<tr><td class="support_M"><?php 
	$support_model = new Support_Model();
	$list_support = $support_model->get_with_active()?>
    <?php if(isset($list_support) && !empty($list_support)>0){?>
    <?php for($i=0; $i<count($list_support); $i++){?>
    <?php if(!empty($list_support[$i]['support_type'])&&$list_support[$i]['support_type']==1){?>
       <table align="center" cellspacing="5" cellpadding="0">
          <tr>
            <td align="center"><a href="ymsgr:sendim?<?php echo $list_support[$i]['support_nick']?>">
            <img border="0" src="http://opi.yahoo.com/online?u=<?php echo $list_support[$i]['support_nick']?>&m=g&t=0" alt="" align="absmiddle">&nbsp;<?php echo $list_support[$i]['support_name']?></a></td>
          </tr>
        </table>
    <?php } elseif(!empty($list_support[$i]['support_type'])&&$list_support[$i]['support_type']==2){?>
        <table align="center" cellspacing="5" cellpadding="0">
          <tr>
            <td align="center"><script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script><a href="skype:<?php echo $list_support[$i]['support_nick']?>?chat"><img src="http://mystatus.skype.com/smallicon/<?php echo $list_support[$i]['support_nick']?>" style="border: none;" alt="" align="absmiddle"/>&nbsp;<?php echo $list_support[$i]['support_name']?></a></td>
          </tr>
        </table>     
    <?php } elseif(!empty($list_support[$i]['support_type'])&&$list_support[$i]['support_type']==3){?>
        <table align="center" cellspacing="5" cellpadding="0">          
          <tr>
            <td align="center"><a href="<?php echo $list_support[$i]['support_nick']?>" target="_blank">
        <img border="0" src="<?php echo $this->site['theme_url']?>index/pics/facebook.png" alt="<?php echo $list_support[$i]['support_name']?>" align="absmiddle">&nbsp;<?php echo $list_support[$i]['support_name']?></a></td>
          </tr>
        </table>        	       	
    <? }//if?>
    <? }//fot?>
    <? }//if?>
    </td></tr>
<tr><td class="support_B"></td></tr>
</table>
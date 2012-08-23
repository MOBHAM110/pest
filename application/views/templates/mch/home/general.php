<table class="home_general" cellpadding="0" cellspacing="0">
<!--<tr>
    <td class="home_general_T_L"></td>
    <td class="home_general_T_Ce"></td>
    <td class="home_general_T_R"></td>
</tr>
--><tr>
    <td class="home_general_M_L"></td>
    <td class="home_general_M_Ce"><?php echo text::limit_words($mlist['page_content'],168)?>&nbsp;&nbsp;<a class="more" href="<?php echo url::base()?>article-<?php echo $mlist['page_id']?>/<?php echo MyFormat::title_url($mlist['page_title']);?>"><?php echo strtolower(Kohana::lang('home_lang.lbl_more'))?></a></td>
    <td class="home_general_M_R"></td>
</tr>   
<tr>
    <td class="home_general_B_L"></td>
    <td class="home_general_B_Ce"></td>
    <td class="home_general_B_R"></td>
</tr>    
</table>
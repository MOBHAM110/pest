<table class="home_bbs" cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
	<td class="home_bbs_M_L"></td>
    <td class="home_bbs_M_Ce">
        <table class="home_bbs_item" cellpadding="0" cellspacing="0">
        <tr><td class="home_bbs_item_T"></td></tr>
        <tr>
            <td class="home_bbs_item_M">
          <div class="tabs_item" align="left"><?php foreach ($mlist['list_bbs'] as $tab => $list) { ?> 
            <div id="tabs_item<?php echo $list['page_id'] ?>" align="left">
                     <?php  }?>
          <ul>
		 <?php foreach ($mlist['list_bbs'] as $tab => $list){?><li><a href="#tabs_item<?php echo $list['page_id'] ?>-<?php echo $tab ?>"><?php echo $list['page_title']?></a> </li><?php } // end foreach bbs ?></ul>
		 
		 <?php foreach ($mlist['list_bbs'] as $tab => $list) { ?><div id="tabs_item<?php echo $list['page_id'] ?>-<?php echo $tab ?>">
                	<?php $view = new View('templates/'.$this->site['config']['TEMPLATE'].'/home/'.$list['page_type_name'])?><?php $view->mlist = $list?><?php $view->render(TRUE)?></div> <?php } // end foreach bbs ?></div></div>
            </td>   
		</tr>
		<tr><td class="home_bbs_item_B"></td></tr>        
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
<script type="text/javascript">
<?php foreach ($mlist['list_bbs'] as $tab => $list) { ?> 
$(document).ready(function(){
	$('#tabs_item<?php echo $list['page_id'] ?> div').hide();
	$('#tabs_item<?php echo $list['page_id'] ?> div:first').show();
	$('#tabs_item<?php echo $list['page_id'] ?> > ul > li:first').addClass('active');
	$('#tabs_item<?php echo $list['page_id'] ?> > ul > li a').click(function(){
		$('#tabs_item<?php echo $list['page_id'] ?> ul li').removeClass('active');
		$(this).parent().addClass('active');
		var currentTab = $(this).attr('href');
		$('#tabs_item<?php echo $list['page_id'] ?> div').hide();
		$(currentTab).show();
		return false;
	});
}); <?php } ?>
</script>
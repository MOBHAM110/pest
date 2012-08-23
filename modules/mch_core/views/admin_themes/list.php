<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo $mr['title']?></td>
  </tr>
</table>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr class="list_header">
    <td><?php echo Kohana::lang('account_lang.lbl_name') ?></td>
    <td><?php echo Kohana::lang('themes_templates_lang.lbl_dir') ?></td>
    <td><?php echo Kohana::lang('global_lang.lbl_default')?></td>
  </tr>
<?php foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>">
    <td align="center"><?php echo $list['themes_name']?>
    <p style="padding-top:5px"><img src="<?php echo @$list['themes_img']?>" height="100px" /></p>
    </td>
    <td align="center"><?php echo $list['themes_dir']?></td>
    <td align="center">
        <a href="javascript:location.href='<?php echo url::base().uri::segment(1)?>/change_default/<?php echo $list['themes_id']?>'">          
            <img src="<?php echo url::base()?>themes/admin/pics/<?php echo $list['themes_dir']==$this->site['config']['CLIENT_THEME']?'':'un'?>check.png">	 
        </a>
    </td> 
  </tr>
<?php } // end foreach ?>
</table>
<div class='pagination'><?php echo $this->pagination?>Page: (<?php echo $this->pagination->current_page?>/<?php echo $this->pagination->total_pages?>), Total Row: <?php echo $this->pagination->total_items?></div>
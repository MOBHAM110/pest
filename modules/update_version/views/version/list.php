<form action="<?php echo $this->site['base_url']?>admin_check_update/update" method="post">
<table cellspacing="0" cellpadding="0" class="title">
  <tr >
    <td width="200"class="title_label"><?php echo Kohana::lang('update_version_lang.tt_page')?></td>
    <td align="right">
    <!-- <button type="button" name="btn_back" class="button back" onclick="location.href='<?php //echo $this->site['history']['back']?>'">
    <span><?php //echo Kohana::lang('admin_lang.btn_back')?></span>
    </button>-->
    <?php if (count($mlist) != 0) { ?>   
    <button type="button" name="btn_save" class="button save" id="btn_save">
    <span><?php echo Kohana::lang('admin_lang.btn_update')?></span>
    </button>
    <?php } // end if ?>   
    </td>
  </tr>
</table>
<table name="list_version" cellpadding="0" cellspacing="5" class="form">
<?php foreach ($mlist as $list) { ?>
<tr>
	<td><?php echo Kohana::lang('update_version_lang.lbl_version')?> : <?php echo $list['version_name']?>
</tr>
<tr>
	<td><?php echo Kohana::lang('update_version_lang.lbl_release_date')?> : <?php echo $list['version_release_date']?>
</tr>
<tr>	
    <td><?php echo Kohana::lang('update_version_lang.lbl_update_news')?> :
		<?php $new_features = is_array($list['version_new_features'])?$list['version_new_features']:array($list['version_new_features'])?>
		<ul>
		<?php foreach ($new_features as $info) { ?>
			<li><?php echo $info?></li>
        <?php } // end foreach info update ?> 
        </ul>   
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<?php } // end foreach ?>
</table>
</form>

<script type="text/javascript"> 
 
    // unblock when ajax activity stops 
    $(document).ajaxStop(function(){		
		$.unblockUI;
		location.reload();
	});  
 
    $(document).ready(function() { 
        $('#btn_save').click(function() {
			<?php if (isset($update)) { ?>
				$.blockUI();
				$.ajax({ url: '<?php echo url::base()?>admin_check_update/update', cache: false });
			<?php } else { ?>
            	location.reload();
            <?php } // end if ?> 
        }); 
        /*$('#pageDemo2').click(function() { 
            $.blockUI({ message: '<h1><img src="busy.gif" /> Just a moment...</h1>' }); 
            test(); 
        }); 
        $('#pageDemo3').click(function() { 
            $.blockUI({ css: { backgroundColor: '#f00', color: '#fff' } }); 
            test(); 
        }); 
 
        $('#pageDemo4').click(function() { 
            $.blockUI({ message: $('#domMessage') }); 
            test(); 
        }); */
    }); 
 
</script> 
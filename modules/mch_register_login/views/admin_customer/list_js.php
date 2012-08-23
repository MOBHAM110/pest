<script type="text/javascript">
document.getElementById('txt_keyword').focus();
function check_action()
{
	$(document).tTopMessage({load: true, type : 'loading', text : 'loading...', effect : 'slide'});
	chk = document.getElementsByName('chk_id[]');
	for(i=0;i<chk.length;i++)
	{
		if (chk[i].checked == true)
		{
			sm_frm(document.getElementById('frmlist'),'<?php echo url::base()?>admin_customer/action','<?php echo Kohana::lang('errormsg_lang.msg_confirm_del') ?>');
			return true;
		}
	}
	alert('<?php echo Kohana::lang('errormsg_lang.msg_no_check')?>');
	$(document).tTopMessage({load: false, type : 'loading', text : 'loading...', effect : 'slide'});
}
function delete_customer(id) {
	$.msgbox('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del'); ?>', {
		type : 'confirm',
		buttons : [
			{type: 'submit', value:'Yes'},
			{type: 'submit', value:'Cancel'}
			]
		}, 
		function(re) {
			if(re == 'Yes') {
				$('a#delete_'+id).html('<img src="<?php echo url::base(); ?>themes/admin/pics/icon_loading.gif"/>');
				$.getJSON("<?php echo $this->site['base_url']?>admin_customer/delete/"+ id,
					function(obj) {
						if(obj.status) {
							$('tr#row_'+id).fadeOut('normal', function() {
								$('tr#row_'+id).remove();
							})
						} else {
							if(obj.mgs)
							$.msgbox(obj.mgs, {type: "error" , buttons : [{type: 'submit', value:'Yes'}]});
							$('a#delete_'+id).html('<img src="<?php echo url::base() ?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete') ?>" />');
						}
					}
				);
			}
		}
	);
};
</script>
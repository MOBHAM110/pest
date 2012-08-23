<script type="text/javascript">
$(document).ready(function() {
	$('#frm').validate({
		rules: {
			txt_username: {
				required: true,
			},
			txt_email: {
		  		required: true,
		  		email: true
			},
			txt_pass: {
				required: true
			},
			txt_cfpass: {
				required: true,
				equalTo: "#txt_pass"
			},
			captcha_response: {
                required: true,
            }
	    },
	    messages: {
	    	txt_username: {
	        	required: "<?php echo Kohana::lang('account_lang.validate_name') ?>",
			},
			txt_email: { 
				required: "<?php echo Kohana::lang('account_lang.validate_email') ?>",
				email: "<?php echo Kohana::lang('account_lang.validate_email_valid') ?>"
	    	},
			txt_pass: {
	        	required: "<?php echo Kohana::lang('account_lang.validate_password') ?>"
			},
			txt_cfpass: {
	        	required: "<?php echo Kohana::lang('account_lang.validate_password_cf') ?>",
				equalTo: "<?php echo Kohana::lang('account_lang.validate_password_valid') ?>"
			},
			captcha_response: {
                required: "<?php echo Kohana::lang('account_lang.validate_captcha') ?>.",
            }
		},
		errorPlacement: function(error, element)
		{
			var elem = $(element),
				corners = ['right center', 'left center'],
				flipIt = elem.parents('span.right').length > 0;

			if(!error.is(':empty')) {
				elem.filter(':not(.valid)').qtip({
					overwrite: false,
					content: error,
					position: {
						my: corners[ flipIt ? 0 : 1 ],
						at: corners[ flipIt ? 1 : 0 ],
						viewport: $(window)
					},
					show: {
						event: false,
						ready: true
					},
					hide: false,
					style: {
						classes: 'ui-tooltip-plain'
					}
				})
				.qtip('option', 'content.text', error);
			}
			else { elem.qtip('destroy'); }
		},
		success: $.noop
	});
});
</script>
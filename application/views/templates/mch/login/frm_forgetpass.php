<form name="frm" id="frm" action="<?php echo url::base()?>forgotpass/save" method="post">
<table name="forgotpass" width="100%" align="center" cellspacing="0" cellpadding="0" border="0" class="forgotpass">
<tr>
	<td class="forgotpass_middle">
    	<table name="forgotpass_middle_Co" cellpadding="5" cellspacing="0">
        <tr>
        	<td align="right" width="15%"><?php echo Kohana::lang('account_lang.lbl_email') ?>&nbsp;<font color="#FF0000">*</font></td>
        	<td align="left"><input name="txt_email" type="text" id="txt_email" class="text" size="30" value="<?php echo isset($mr['txt_email'])?$mr['txt_email']:''?>"/></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
            <td align="left">
            	<button type="submit" name="btn_submit" class="button"/><?php echo Kohana::lang('login_lang.btn_get_new_pass')?></button>
            	<button type="button" name="btn_back" class="button" onclick="javascript:location.href='<?php echo $this->site['history']['back']?>'"/>
                	<?php echo Kohana::lang('global_lang.btn_back')?>
                </button>
            </td>
        </tr>
        </table>
	</td>
</tr>
<tr><td class="forgotpass_bottom">&nbsp;</td></tr>
</table>
</form>
<script type="text/javascript">
$(document).ready(function() {
	$('#frm').validate({
		rules: {
			txt_email: {
		  		required: true,
		  		email: true
			},
	    },
	    messages: {
			txt_email: { 
				required: "<?php echo Kohana::lang('account_lang.validate_email') ?>",
				email: "<?php echo Kohana::lang('account_lang.validate_email_valid') ?>"
	    	},
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

			// If the error is empty, remove the qTip
			else { elem.qtip('destroy'); }
		},
		success: $.noop
	});
});


</script>
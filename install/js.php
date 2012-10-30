<script type="text/javascript">
$(document).ready(function() {
	$.validator.addMethod('admin', function (value) { 
		if(value=='admin' || value=='superadmin')
			return false;
		else return true;
    }, 'invalid username');
	$("#frm_install").validate({
		rules: {
			txt_host: {
				required: true,
				minlength: 2
			},
			txt_username: {
				required: true,
				minlength: 2
			},
			txt_db_name: {
				required: true,
				minlength: 2
			},
			txt_site_domain: {
				required: true,
				minlength: 2
			},
			txt_site_name: {
				required: true,
				minlength: 2
			},
			txt_account_email: {
				required: true,
				email: true
			},
			txt_account_username: {
				required: true,
				minlength: 3,
				admin: true
			},
			txt_account_password: {
				required: true,
				minlength: 6
			}
		},
		submitHandler: function(form) {
		process_install('next');
		}
	});
});
</script>
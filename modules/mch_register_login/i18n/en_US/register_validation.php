<?php 
$lang = array
(
	'txt_username' => Array
	(
		'required' => 'User name is required.',
		'length' => 'User name must be between 3 and 50 letters.',
		'_check_user' => 'User Name has been used.'
	),
	'txt_pass' => Array
	(	
		'required' => 'Password is required.',
		'length' => 'Password must be between 6 and 50 letters.'
	),
	'txt_cfpass' => Array
	(	
		'required' => 'Confirm Password is required.',
		'matches' => 'Confirm Password invalid.'
	),
	'txt_email' => Array
	(
		'required' => 'Email is required.',
		'email' => 'Email must contain a valid email address.',
		'_check_email' => 'Email has been used.'
	),
	'captcha_response' => Array
	(
		'required' => 'Captcha Code is required.',
		'valid' => 'Captcha Code invalid.'
	)
);
?>
<?php 
$lang = array
(
	'txt_username' => Array
	(
		'required' => 'Tên đăng nhập không được để trống.',
		'length' => 'Tên đăng nhập phải có từ 3 đến 50 kí tự.',
		'_check_user' => 'Tên đăng nhập đã được sử dụng.'
	),
	'txt_pass' => Array
	(	
		'required' => 'Mật khẩu không được để trống.',
		'length' => 'Mật khẩu phải có từ 6 đến 50 kí tự.'
	),
	'txt_cfpass' => Array
	(	
		'required' => 'Xác nhận Mật khẩu không được để trống.',
		'matches' => 'Mật khẩu để xác nhận không phù hợp.'
	),
	'txt_email' => Array
	(
		'required' => 'Đại chỉ Email không được để trống.',
		'email' => 'Địa chỉ Email không hợp lệ.',
		'_check_email' => 'Địa chỉ Email đã được sử dụng.'
	),
	'captcha_response' => Array
	(
		'required' => 'Mã Captcha không được để trống.',
		'valid' => 'Mã Captcha không hợp lệ.'
	)
);
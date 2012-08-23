<?php 
$lang = array
(
	'txt_username' => Array
	(
		'required' => '사용자 이름이 필요합니다.',
		'length' => '사용자 이름은 3시 사이에 50 글자를해야합니다.',
		'_check_user' => '사용자 이름이 사용되었습니다.'
	),
	'txt_pass' => Array
	(	
		'required' => '비밀 번호가 필요합니다.',
		'length' => '비밀 번호는 6 세 이상 50 문자 여야합니다.'
	),
	'txt_cfpass' => Array
	(	
		'required' => '비밀 번호 확인이 필요합니다.',
		'matches' => '비밀 번호 확인 잘못되었습니다.'
	),
	'txt_email' => Array
	(
		'required' => '이메일이 필요합니다.',
		'email' => '이메일 유효한 이메일 주소를 포함해야합니다.',
		'_check_email' => '이메일이 사용되었습니다.'
	),	
	'captcha_response' => Array
	(
		'required' => '보안 문자 코드가 필요합니다.',
		'valid' => '보안 문자 코드가 잘못되었습니다.'
	)
);
?>
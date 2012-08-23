<?php 
$lang = array
(
'txt_title' => Array
(
 	'required' => 'The Title field is required',	
	'length' => 'The Title just can be maximum contain 200 characters'
),
'txt_short_title' => Array
(
 	'required' => 'The Short Title field is required',	
	'length' => 'The Short Title just can be maximum contain 20 characters'
),
'txt_name' => Array
(
 	'required' => 'The Name field is required',	
	'length' => 'The Name just can be maximum contain 200 characters'
),
'txt_content' => Array
(
 	'required' => 'The Content field is required',
	'length' => 'The Content just can be maximum contain 1000 characters'
),
'txt_pass' => Array
(
	'required' => 'The Password field is required',
	'length' => 'Password must be contain 6 characters'
),
'error_pass' => 'Password wrong',
);

for ($i=1;$i<=3;$i++)
{
	$lang['attach_file'.$i] = Array
	(	
		'type' => 'Type of File '.$i.' not validation.',
		'size' => 'Maximum of File '.$i.' size is 10 Mb.'
	);
}
?>
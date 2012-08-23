<?php
$lang = array
(
'txt_title' => Array
(
	'required' => 'The Title field is required',
	'length' => 'The Title field just can be maximum contain 200 characters'
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
	'length' => 'The Content field just can be maximum contain 1000 characters'
),
'txt_pass' => Array
(
	'required' => 'The Password field is required',
	'length' => 'Password must be contain 6 characters'
),
'error_pass' => 'Password wrong',
);

for ($i=0;$i<10;$i++)
{
	$lang['txt_des'.$i] = Array
	(
	 	'length' => 'Description of image '.$i.' just allow maximum 100 characters'
	 );
	$lang['image'.$i] = Array
	(	
		'type' => 'Image '.$i.' only type PNG, GIF, JPEG, JPG allow.',
		'size' => 'Maximum of image '.$i.' size is 10 Mb.'
	);
}
?>
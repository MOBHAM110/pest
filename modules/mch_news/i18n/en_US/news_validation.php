<?php
$lang = array
(
'txt_title' => Array
(
	'required' => 'News Title is required',
	'length' => 'News Title just can be maximum contain 200 characters'
),
'txt_content' => Array
(
	'required' => 'News Content is required',
	'length' => 'News Content just can be maximum contain 1000 characters'
)
);

for ($i=1;$i<=3;$i++)
{
	$lang['attach_file'.$i] = Array
	(	
		'type' => 'Image '.$i.' just allow type PNG, GIF, JPEG, JPG.',
		'size' => 'Maximum of image '.$i.' size is 10 Mb.'
	);
}
?>
<?php
$lang = array
(
'txt_title' => Array
(
	'required' => 'Tiêu đề tin tức được yêu cầu',
	'length' => 'Tiêu đề tin tức chứa tối đa 200 ký tự'
),
'txt_content' => Array
(
	'required' => 'Nội dung tiêu đề được yêu cầu',
	'length' => 'Nội dung tiêu đề chứa tối đa 1000 ký tự'
)
);

for ($i=1;$i<=3;$i++)
{
	$lang['attach_file'.$i] = Array
	(	
		'type' => 'Hình '.$i.' just allow type PNG, GIF, JPEG, JPG.',
		'size' => 'Kích thước hình '.$i.' vượt quá 10Mb.'
	);
}
?>
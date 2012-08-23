<?php 
$lang = array
(
'txt_title' => Array
(
 	'required' => 'Tiêu đề không được để trống.',	
	'length' => 'Tiêu đề chỉ có thể chứa tối đa 200 kí tự.'
),
'txt_name' => Array
(
 	'required' => 'Tên không được để trống.',	
	'length' => 'Tên chỉ có thể chứa tối đa 200 kí tự.'
),
'txt_content' => Array
(
 	'required' => 'Nội dung không được để trống.',
	'length' => 'Nội dung chỉ có thể chứa tối đa 1000 kí tự.'
),
'txt_pass' => Array
(
	'required' => 'Mật khẩu rỗng',
	'length' => 'Mật khẩu ít nhất 6 ký tự.'
),
'error_pass' => 'Mật khẩu sai'
);

for ($i=1;$i<=3;$i++)
{
	$lang['attach_file'.$i] = Array
	(	
		'type' => 'File type of file '.$i.' not validation.',
		'size' => 'Maximum of file '.$i.' size is 10 Mb.'
	);
}
?>
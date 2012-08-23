<script language="javascript" src="<?php echo url::base()?>plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
document.getElementById('txt_title').focus();
function save(value)
{
	if(document.getElementById('txt_title').value=="")
	{
		alert('<?php echo Kohana::lang('bbs_validation.txt_title.required')?>');
		document.getElementById('txt_title').focus();
		return false; 
	}
	$(document).tTopMessage({load: true, type : 'loading', text : 'loading...', effect : 'slide'});
	if(value=='add') $('input#hd_save_add').val(1);
	document.frm.submit();
	$(document).tTopMessage({load: false, type : 'loading', text : 'loading...', effect : 'slide'});
}
</script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/collection/popup.js"></script>
<script type="text/javascript">
var arr_type = new Array('text','image','image','flash');
window.onblur = function() {
	//self.close();
	window.opener.location.href = window.opener.location.href;
};
sh_content(document.getElementById('sel_ft_type'));
function sh_content(sel_type)
{		
	var type = sel_type.value;
	
	$('#content_text').hide();
	$('#content_image').hide();
	$('#content_flash').hide();
			
	$('#content_' + arr_type[type]).show();
}
function save(value)
{
	$(document).tTopMessage({load: true, type : 'loading', text : 'loading...', effect : 'slide'});
	document.frm.submit();
	$(document).tTopMessage({load: false, type : 'loading', text : 'loading...', effect : 'slide'});
}
</script>
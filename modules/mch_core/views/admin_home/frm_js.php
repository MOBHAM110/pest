<script type="text/javascript">
$().ready(function() {
	$('#float_table').floatBanner();
});
function save(value)
{
	$(document).tTopMessage({load: true, type : 'loading', text : 'loading...', effect : 'slide'});
	document.frm.submit();
	$(document).tTopMessage({load: false, type : 'loading', text : 'loading...', effect : 'slide'});
}
function check_page_type(sel_page)
{
	var page = sel_page.options[sel_page.selectedIndex].text;	
	var arr_name = sel_page.name.split('_');	
	var txt_num = document.getElementById('txt_row_' + arr_name[2] + '_' + arr_name[3]);
	
	if (page.indexOf('[general]') !== -1 || page.indexOf('[tab]') !== -1 || sel_page.options[sel_page.selectedIndex].value == -1) // general page type
	{
		txt_num.disabled = true;
		txt_num.value = 0;
	}
	else
	{	
		txt_num.disabled = false;
		txt_num.value = 3;
	}
}
</script>
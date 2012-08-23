<script type="text/javascript">
$().ready(function() {
	$('table.bbs_item').imgAlt({
		_alt : '<?php echo @$mr['bbs_title']?>',
		_title : ''
	});
});
function update_bbs(action)
{
	<?php if (!empty($this->sess_cus) && (($this->sess_cus['username'] == $mr['bbs_author'] && !empty($mr['bbs_author'])) || $this->sess_cus['level'] < 3)) { ?>
		if(action == 'edit') document.location.href='<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/edit/id/<?php echo @$mr['bbs_id']?>';
		if(action == 'delete') document.location.href='<?php echo url::base()?>bbs/pid/<?php echo $this->page_id?>/delete/id/<?php echo @$mr['bbs_id']?>';
	<?php } else if($mr['page_write_permission'] == 5){?>
	$.msgbox("", {
	type    : "prompt",
	inputs  : [
	  {type: "password", label: "<?php echo Kohana::lang('bbs_lang.msg_pass_required')?>", required: true}
	],
	buttons : [
	  {type: "submit", value: "OK"},
	  {type: "cancel", value: "Cancel"}
	]
	}, function(password) {
	if(password){
		$.ajax({
			type: "POST",
			url: "<?php echo $this->site['base_url']?>bbs/compare_pass/",
			data: {page_id: <?php echo $this->page_id?>, method: action, bbs_id: <?php echo !empty($mr['bbs_id'])?$mr['bbs_id']:0?>, input_pass: password, cur_pass: '<?php echo !empty($mr['bbs_password'])?$mr['bbs_password']:''?>' }
		}).done(function (data) {
			if(data){
				document.location.href=data;
			} else
			$.msgbox('<?php echo Kohana::lang('bbs_validation.error_pass')?>', {type: "error"});
		});	
	}
	});
	<?php }//edit ?>
}
</script>
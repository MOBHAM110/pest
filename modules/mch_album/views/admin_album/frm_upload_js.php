<link rel="stylesheet" href="<?php echo url::base()?>plugins/jquery/uploadify/uploadify.css" type="text/css" />
<link rel="stylesheet" href="<?php echo url::base()?>plugins/jquery/uploadify/uploadify.jGrowl.css" type="text/css" />
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery/uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery/uploadify/jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery/uploadify/jquery.jgrowl_minimized.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#albumUpload").uploadify({
		'uploader': '<?php echo url::base()?>plugins/jquery/uploadify/uploadify.swf',
		'cancelImg': '<?php echo url::base()?>plugins/jquery/uploadify/cancel.png',
		'script': '<?php echo url::base()?>album/pid/<?php echo $this->mr['page_id']?>/upload/id/<?php echo $mr['bbs_id']?>',
		'fileDataName' : 'bbsFile',
		'buttonText' : 'Add more Photos',
		'fileDesc' : 'Image files(<?php echo $mr['file_ext']?>)',
		'fileExt' : '<?php echo $mr['file_ext']?>',
		'auto': false,
		'multi': true,
		onError: function (event, queueID ,fileObj, errorObj) {
			alert('sdsd');
			var msg;
			if (errorObj.status == 404) {
   				alert("Error: "+errorObj.type+"      Info: "+errorObj.info);
			} else if (errorObj.type === "HTTP")
				msg = errorObj.type+": "+errorObj.status+" -Info: "+errorObj.info;
			else if (errorObj.type ==="File Size")
				msg = fileObj.name+'<br>'+errorObj.type+' Limit: '+Math.round(errorObj.sizeLimit/1024)+'KB';
			else
				msg = errorObj.type+": "+errorObj.text;
			$.jGrowl('<p></p>'+msg, {
				theme: 	'error',
				header: 'ERROR',
				sticky: true
			});			
			$("#fileUploadgrowl" + queueID).fadeOut(250, function() { $("#fileUploadgrowl" + queueID).remove()});
			return false;
		},
		
		onAllComplete : function (event, data) {
			
			alert("Successfully uploaded!");
			$('#tab_id').val(1);
			$('#frm_tab').submit();
		}
	});
});
</script>
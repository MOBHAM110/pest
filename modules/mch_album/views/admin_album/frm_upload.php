<?php require('frm_upload_js.php')?>
<table name="album_upload" cellpadding="5" cellspacing="0">
</tr> 
<tr>
    <td align="center">
    	<div id="fileQueue"></div>
        <input type="file" name="uploadify" id="albumUpload" />            	
        <p><a href="javascript:$('#albumUpload').uploadifyUpload();">Start Upload</a>&nbsp;|&nbsp;<a href="javascript:$('#albumUpload').uploadifyClearQueue()">Clear Queue</a></p>
	</td>
</tr>
</table>
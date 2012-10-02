<!--var url = '<?php echo url::base()?>album/pid/<?php echo $this->mr['page_id']?>/upload/id/<?php echo $mr['bbs_id']?>';-->
<link rel="stylesheet" href="<?php echo url::base()?>plugins/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css" type="text/css" />
<!-- Third party script for BrowserPlus runtime (Google Gears included in Gears runtime now) -->
<script type="text/javascript" src="<?php echo url::base()?>plugins/plupload/browserplus-min.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/plupload/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>plugins/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>

<div id="bbsFile">
    <p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
</div>

<script type="text/javascript">
$(function() {
    
    var url = '<?php echo url::base()?>album/pid/<?php echo $this->mr['page_id']?>/upload/id/<?php echo $mr['bbs_id']?>';
    $("#bbsFile").pluploadQueue({
        runtimes : 'html5,html4',
        url : url,
        max_file_size : '10mb',
        unique_names : true,
        multiple_queues : true,
        //multipart : true,
        multi_selection : true,
        //sortable: true,
        rename: true,
        //file_data_name : file,
        filters : [
            {title : "Image files", extensions : "jpg,gif,png,jpeg"}
        ],
        multipart_params: {
            csrf_test_name : 'test',
            node_id : 'text'
        },
        init : {
//            FilesAdded: function(u) {
//                u.start();
            },
        preinit : {
            UploadComplete: function(up, files) {
                    //When uploads are done, refresh the page!
                    $( "#tabs" ).tabs('select',1);
                    location.reload();
                }
            }
//        FileUploaded: function(up, file, info) {
//            // Called when a file has finished uploading
//            up.refresh();
//            location.reload();
//            $( "#tabs" ).tabs('select',1);
//            //log('[FileUploaded] File:', file, "Info:", info);
//        }
    });

});
</script>

<script language="javascript">
var currentImage;
var currentIndex = -1;
var interval;
function showImage(index){
	if(index < $('#banner_top_M img').length){
		var indexImage = $('#banner_top_M img')[index]
		if(currentImage){   
			if(currentImage != indexImage ){
				$(currentImage).css('z-index',2);
				clearTimeout(myTimer);
				$(currentImage).fadeOut(1000, function() {
					myTimer = setTimeout("showNext()", 3000);
					$(this).css({'display':'none','z-index':1})
				});
			}
		}
		$(indexImage).css({'display':'block', 'opacity':1});
		currentImage = indexImage;
		currentIndex = index;
	}
}

function showNext(){
	var len = $('#banner_top_M img').length;
	var next = currentIndex < (len-1) ? currentIndex + 1 : 0;
	showImage(next);
}

var myTimer;

$(document).ready(function() {
	myTimer = setTimeout("showNext()", 3000);
	showNext(); //loads first image
});
</script>
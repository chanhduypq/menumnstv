<HTML>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<style>
	body {
		margin: 0;
	}
</style>

<BODY>
<iframe id="myIFrame" name="myIFrame" src="<?=$adv_url?>" style="border: 0; position:absolute; top:0; left:0; right:0; bottom:0; width:100%; height:100%">Your browser doesn't support iFrames.</iframe>
<script>
	var urlIndex = -1;
	var urlArr = [
	<?php
		$str = "";
		foreach($templateList as $template) {
			$str = $str .'"' . $upload_template_url . "/" . $template->path . '/index.html",' . "\n";
			$str = $str .'"' . $adv_url . '",' . "\n";
		}
		echo $str;
	?>
	
					];
	var oldMin = -1;
	var min = 0;		
	var index = -1;
	var curIndex = -1;
	$(document).ready(function(){
		$("#myIFrame").attr("src", urlArr[0]);
		setInterval(changeIFrame, 4000);
	});

	function changeIFrame() {
		var d = new Date();
		var n = d.getMinutes();
		
		
		
		if((min <= 10)) {
			if(index != 0) {
				$("#myIFrame").attr("src", urlArr[0]);
				index = 0;
			}
			
			
		} else if((min > 10) && (min <= 20)) {
			if(index != 1) {
				$("#myIFrame").attr("src", urlArr[1]);
				index = 1;
			}
			
			
		} else if(min <= 30) {
			if(index != 2) {
				$("#myIFrame").attr("src", urlArr[2]);
				
				index = 2;
			}
		} else {
			min = 0;
		}
		min++;	
		
		
	}
</script>
</BODY>

</HTML>
jQuery(document).ready(function(){
	var topBlock = document.querySelectorAll('.topBlock')[0];
	var topInformation = document.querySelectorAll('.topBlock .topInformation')[0];
	var reservationsWindWrapp = document.querySelectorAll('.topBlock .reservationsWindWrapp')[0];
	if (topBlock && topInformation && reservationsWindWrapp){
		var relocated;
		function transferOnNewPosition(){
			topBlock.insertBefore(topInformation,reservationsWindWrapp.nextSibling);			
		}
		function transerBackwards(){
			topBlock.insertBefore(topInformation,reservationsWindWrapp);
		}
		jQuery(window).resize(function(){
			var clientWidth = document.documentElement.clientWidth;
			if (clientWidth <= 767 && !relocated){
				transferOnNewPosition();
				relocated = true;
			}else if (clientWidth > 767 && relocated){
				transerBackwards();
				relocated = false;	
			}				
		});
		jQuery(window).resize();
	}	
});
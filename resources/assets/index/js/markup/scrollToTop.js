jQuery(document).ready(function(){
	var upButton = document.getElementsByClassName('upButton')[0];
	var techBlockForScrollMthod;
	if (upButton){
		var $upButton = jQuery(upButton);		
		$upButton.on('click',function(){
			console.log('поднимаемся вверх!');
			jQuery('html, body').animate({scrollTop: 0},1000); 
		});
		// создаём технический блок для вычисления координаты верхней границы экрана 
		//относительно начала документа;
		function createTechnicalBlock(){
			techBlockForScrollMthod = document.createElement('DIV');
			document.body.appendChild(techBlockForScrollMthod);
			techBlockForScrollMthod.style.position = 'fixed';
			techBlockForScrollMthod.style.left = '0';
			techBlockForScrollMthod.style.top = '0';
			techBlockForScrollMthod.style.right = '0';
			techBlockForScrollMthod.style.visibility = 'hidden';
			techBlockForScrollMthod.style.height = '1px';
			techBlockForScrollMthod.style.zIndex = '-100';						
		}
		// получаем горизонтальную координату верхней границы экрана относительно начала документа;
		function getVerticalCoordinateOfTopScreenLine(){
			if (!techBlockForScrollMthod){
				createTechnicalBlock();
			}					
			return jQuery(techBlockForScrollMthod).offset().top;	
		}		
		function shoHideScrollButton(){
			var coordinate = getVerticalCoordinateOfTopScreenLine();
			if (coordinate <= 100){
				if (jQuery(upButton).css('display') == 'block' && !upButton.style.opacity)				
					jQuery(upButton).animate({opacity: 0},700,function(){
						upButton.style.display = 'none';
						upButton.style.opacity = '';
						shoHideScrollButton();
					});					
			}else{
				if (jQuery(upButton).css('display') == 'none' && !upButton.style.opacity){
					upButton.style.opacity = 0;								
					upButton.style.display = 'block';								
					jQuery(upButton).animate({opacity: 1},700,function(){
						upButton.style.opacity = '';
						shoHideScrollButton();
					});					
				}
			}			
		}
		window.onscroll = shoHideScrollButton;
	}	
});
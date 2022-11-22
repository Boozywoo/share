jQuery(document).ready(function(){
	console.log('Сравниваем блоки!');	
	var ticket = document.querySelectorAll('.ticketMainBlock .ticket')[0];
	if (ticket){
		function copmareTwoBlocks(){
			if (arguments.length > 0){
				var heightArr = getHeightsArr(arguments);
				var maxHeight = getMaxHeight(heightArr);
				setHeightToBlocks(arguments,maxHeight);
			//	return maxHeight;		
			}		
		}
		function getHeightsArr(elements){
			var heightArr = [];
			for (var i = 0; i < elements.length; i++){
				heightArr.push(getTotalHeight(elements[i]));
			}				
			return heightArr;
		}	
		function getMaxHeight(heightArr){
			var maxHeight = heightArr[0];
			for (var i = 0; i < heightArr.length; i++){		
				if (heightArr[i] > maxHeight)
					maxHeight = heightArr[i];
			}			
			return maxHeight;			
		}
		function setHeightToBlocks(blocks,height){
			for (var i = 0; i < blocks.length; i++){
				blocks[i].style.height = height + 'px';
			}
		}
		function getNumberFromString(val){
			return parseInt(val,10);
		}
		function getTotalHeight(element){
			var contentHeight = getNumberFromString(jQuery(element).css('height'));
			var paddingTop = getNumberFromString(jQuery(element).css('padding-top'));
			var paddingBottom = getNumberFromString(jQuery(element).css('padding-bottom'));
			var borderTop = getNumberFromString(jQuery(element).css('border-top-width'));
			var borderBottom = getNumberFromString(jQuery(element).css('border-bottom-width'));
			var boxSizing;
			switch (jQuery(element).css('box-sizing')){
				case 'padding-box' :
					var height = contentHeight + borderTop + borderBottom;	
					break;
				case 'border-box' :
				var height = contentHeight;	
					break;		
				default :	
				var height = contentHeight + paddingTop + paddingBottom + borderTop + borderBottom;			
			} 
			return height;
		}	
		var leftBlock = ticket.querySelectorAll('.left.ticketPart')[0];
		var rightBlock = ticket.querySelectorAll('.right.ticketPart')[0];
		console.log(getTotalHeight(leftBlock));	
		if (leftBlock && rightBlock){
			if (document.body.clientWidth >= 570){
				console.log(copmareTwoBlocks(leftBlock,rightBlock));
			}			
			jQuery(window).resize(function(){	
				console.log('!!!!!!');
				if (document.body.clientWidth >= 570){
					console.log(copmareTwoBlocks(leftBlock,rightBlock));
				}else{
					leftBlock.style.height = '';
					rightBlock.style.height = '';
				}					
				console.log('меняем размер');	
			});
		}
	}
	
});
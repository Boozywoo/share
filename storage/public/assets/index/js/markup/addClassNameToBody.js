jQuery(document).ready(function(){	
	var itemPage = document.querySelectorAll('div.item-page')[0];
	if (itemPage){
		//класс раздела должен быть во 2й позиции;		
		if (itemPage.classList.length > 1 && itemPage.classList[0] == 'item-page' && itemPage.classList[1] != 'mainWidth'){
			var classToBody = itemPage.classList[1];	
			if (classToBody == 'personalCabinet' && itemPage.classList.length > 2){
				//класс подразела должен быть в 3й позиции;
				var secondClass = itemPage.classList[2];
				classToBody += ' ' + secondClass;
			}
		}	
		if (classToBody){
			if (document.body.classList > 0)
				document.body.className += ' ' + classToBody;	
			else
				document.body.className = classToBody;
		}			
	}
});
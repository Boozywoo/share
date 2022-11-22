jQuery(document).ready(function(){
	function setHREFsToMainMenuItems(){
		function setScrollHref(menu,elSelector,linkValue){
			var span = menu.querySelectorAll(elSelector)[0];
			if (span){
				span.setAttribute('href',linkValue);
				jQuery(span).click(function(){
					var scroll_el = jQuery(this).attr('href');									
					if (jQuery(scroll_el).length != 0) { // проверим существование элемента чтобы избежать ошибки
						jQuery('html, body').animate({ scrollTop: jQuery(scroll_el).offset().top }, 800); // анимируем скроолинг к элементу scroll_el
					}
					return false; // выключаем стандартное действие									
				});
			}
		}
		function setScrollHrefs(menu){
			setScrollHref(menu,'span.whyWeLink','div.moreInnerBanner');
			setScrollHref(menu,'span.systemOfDiscountsLink','div.systemOfDiscounts');
		//	setScrollHref(menu,'span.popularFlightsLink','div.popularFlights');
			setScrollHref(menu,'span.clientsAboutUsLink','div.clientsAboutUs');
		}
		/* только для меню на главной странице! Определяется по классу .mainPage */
		var menu = document.querySelectorAll('.topBlock ul.topMenu.mainPage')[0];		
		if (menu){
			setScrollHrefs(menu);
		}		
	}  
	setHREFsToMainMenuItems();
	/*-- scroll from another page --*/
		var scrollBlock = document.getElementById('scrollFromAnitherPage'); 
		if (scrollBlock){
			function scrollToElement(elemSelector){
				jQuery('html, body').animate({ scrollTop: jQuery(elemSelector).offset().top }, 800);
			}
			var scrollVariable = scrollBlock.firstChild.nodeValue;
			switch (scrollVariable){
				case ' whyWeLink': 
					scrollToElement('div.moreInnerBanner');
					break;
				case ' systemOfDiscountsLink': 
					scrollToElement('div.systemOfDiscounts');
					break;	
			/*	case ' popularFlightsLink': 
					scrollToElement('div.popularFlights');
					break;	*/		
				case ' clientsAboutUsLink': 
					scrollToElement('div.clientsAboutUs');
					break;							
			}		
		}	
	/*-- scroll from another page end --*/
	
});
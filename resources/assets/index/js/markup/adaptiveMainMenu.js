jQuery(document).on('ready',function(){	
	var mainMenu = document.querySelectorAll('.menuAndLogoWrapp ul.topMenu')[0];
	var showHideMainMenuButt = document.querySelectorAll('.menuAndLogoWrapp .showHideMainMenuButt')[0];
	if (mainMenu && showHideMainMenuButt){
		var $mainMenu = jQuery(mainMenu);
		var registrationContactsBlock = document.querySelectorAll('.topBlock .registrationContacts')[0];
		var topLogo = document.querySelectorAll('.topLogo.left')[0];
		var topLogoIMGWrapp = document.querySelectorAll('.topLogo.left > div')[0];
		var topLogoIMG = document.querySelectorAll('.topLogo.left > div > img')[0];
		var menuMainWrapper = document.querySelectorAll('.menuAndLogoWrapp .showHideMainMenuButtWrapp')[0];
		var menuWrapp = document.querySelectorAll('.menuAndLogoWrapp .menuAndLogo > div.right')[0];		
		var menuAndLogoWrapp = document.querySelectorAll('.menuAndLogoWrapp')[0];		
		var mainMenuItems = getMainMenuItems(mainMenu);		
		var registrationListWrapp = document.querySelectorAll('.topBlock .registration')[0];
		var registrationWindow = document.querySelectorAll('.topBlock .registrationFormWrapperPU')[0];
		var enterWindow = document.querySelectorAll('.topBlock .entrfFormWrapperPU')[0];		
		//массив классов внутренних элементов пунктов главного меню, родительские пункты которых нужно спрятать для телефона;
		var classesOfInnerElementsInPointsForHideOnMobile = ['whyWeLink','systemOfDiscountsLink','popularFlightsLink','clientsAboutUsLink'];
		var classesOfInnerElementsInPointsForHideOnDesctop = ['personalArea'];
		classesOfInnerElementsInPointsForShow = [];	
		setClassesOfChildElsToItems(mainMenuItems);
		function getChildElement(element){
			var childEl = element.firstChild;
			while(childEl && childEl.tagName != 'SPAN' && childEl.tagName != 'A'){
				childEl = childEl.nextSibling;
			}
			return childEl;
		};
		//находит и задаёт в качестве свойства пункту меню его дочерний элемент (a или span);
		function setItemChildElemToItemAsProp(item){
			var childElement = getChildElement(item);
			if (childElement)
				item.childElement = childElement;
		}
		// получаем массив пунктов главного меню
		function getMainMenuItems(menu){
			var items = [];
			var item = menu.firstChild;
			while(item){
				if (item.tagName == 'LI'){
					items.push(item);	
					setItemChildElemToItemAsProp(item);
				}					
				item = item.nextSibling;
			}
			return items;
		}
		//изменяем стили пунктов главного меню;
		function changeMainMenuStyle(width){
			if (width == 'narrow'){
				for (var i = 0; i < mainMenuItems.length; i++){
					mainMenuItems[i].style.display = 'block';
					if (mainMenuItems[i].childElement)
						mainMenuItems[i].childElement.style.fontSize = '14px';
				}
			}else if (width == 'wide'){
				for (var i = 0; i < mainMenuItems.length; i++){
					mainMenuItems[i].style.display = '';
					if (mainMenuItems[i].childElement)
						mainMenuItems[i].childElement.style.fontSize = '';
				}
			}
		}
		// применяем стили для маленькой ширины;
		function applyAdaptiveStyles(){
			topLogo.style.float = 'none';	
			topLogo.style.width = 'auto';
			topLogo.style.display = 'inline-block';
			topLogo.style.verticalAlign = 'top';
			menuWrapp.style.paddingLeft = '0';
			menuMainWrapper.style.display = 'inline-block';
			menuMainWrapper.style.marginRight = '20px';
			menuMainWrapper.style.verticalAlign = 'top';
			menuAndLogoWrapp.style.textAlign = 'center';
			mainMenu.style.float = 'none';
			mainMenu.style.display = 'none';			
			changeMainMenuStyle('narrow');			
		//	registrationContactsBlock.style.backgroundColor = '#141b21';
			registrationContactsBlock.style.paddingTop = '60px';
			if (registrationListWrapp)
				registrationListWrapp.style.right = '0' ;
			if (registrationWindow)
				registrationWindow.style.top = '46px';
			if (enterWindow)
				enterWindow.style.top = '46px';
			topLogoIMGWrapp.style.paddingTop = '5px';
			topLogoIMGWrapp.style.paddingBottom = '5px';
            if (topLogoIMG.style.height)
				topLogoIMG.style.height = '50px';
			console.log(registrationWindow);
		}		
		// отменяем применённые стили, если ширина большая;
		function cancelAdaptiveStyles(){
			topLogo.style.float = '';	
			topLogo.style.width = '';	
			topLogo.style.display = '';
			topLogo.style.verticalAlign = '';
			menuWrapp.style.paddingLeft = '';	
			menuMainWrapper.style.display = '';
			menuMainWrapper.style.marginLeft = '';
			menuMainWrapper.style.verticalAlign = '';
			menuAndLogoWrapp.style.textAlign = '';
			mainMenu.style.float = '';
			mainMenu.style.display = '';	
			changeMainMenuStyle('wide');			
		//	registrationContactsBlock.style.backgroundColor = '';
			registrationContactsBlock.style.paddingTop = '';
			if (registrationListWrapp)
				registrationListWrapp.style.right = '' ;
			if (registrationWindow)
				registrationWindow.style.top = '';
			if (enterWindow)
				enterWindow.style.top = '';	
			topLogoIMGWrapp.style.paddingTop = '';
			topLogoIMGWrapp.style.paddingBottom = '';
			if (topLogoIMG.style.height)
				topLogoIMG.style.height = '';
		}				
		//получаем дочерний элемент span или a;
		function getChildElement(item){
			var childEl = item.firstChild;
			while (childEl && childEl.tagName != 'A' && childEl.tagName != 'SPAN'){
				childEl = childEl.nextSibling;
			} 
			return childEl;
		}		
		function setClassesOfChildElsToItems(mainMenuItems){
			for (var i = 0; i < mainMenuItems.length; i++){
				var childEl = getChildElement(mainMenuItems[i]);
				if (childEl.classList.contains('hiddenForDesctop')){
					childEl.parentNode.classList.add('hiddenForDesctop');
				}else if (childEl.classList.contains('hiddenForMobile')){
					childEl.parentNode.classList.add('hiddenForMobile');
				}
			}
		}		
		// функция, которая выполняется по изменению размера окна
		window.onresize = function(){
			//console.log('меняем размер');
			if (document.body.clientWidth <= 767){
				applyAdaptiveStyles();
			}else{
				cancelAdaptiveStyles();				 
			}				
		}	
		function showHideMainMenu(){			
			if ($mainMenu.css('display') == 'none'){
				$mainMenu.slideDown(700);
			}else if ($mainMenu.css('display') == 'block' && !mainMenu.style.height){
				$mainMenu.slideUp(700,function(){
				//	mainMenu.style.display = '';
				});
			}
		}	
		showHideMainMenuButt.onclick = showHideMainMenu;
		window.onresize(); // выполняем метод, меняющий свойства меню при загрузке страницы;		
	}

});

$(function() {
 
	$("body").css({padding:0,margin:0});
	  var f = function() {
		$("<div>").css({position:"relative"});
		var h1 = $("body").height();
		var h2 = $(window).height();
		var d = h2 - h1;
		var h = $("<div>").height() + d;    
		var ruler = $("<footer>").appendTo("<div>");       
		h = Math.max(ruler.position().top,h);
		ruler.remove();    
		$("<div>").height(h);
	  };
	  setInterval(f,1000);
	  $(window).resize(f);
	  f();
	 
	});
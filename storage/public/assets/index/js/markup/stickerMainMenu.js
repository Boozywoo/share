jQuery(document).ready(function(){
	var mainMenu = document.querySelectorAll('.menuAndLogoWrapp ul.topMenu')[0];
	var menuAndLogoWrapp = document.querySelectorAll('.menuAndLogoWrapp')[0];
	var topBlock = document.querySelectorAll('.topBlock')[0];
//	var topLogoWrapp = document.querySelectorAll('.menuAndLogoWrapp .menuAndLogo > div')[0];	
	var logoPicture = document.querySelectorAll('.menuAndLogoWrapp .menuAndLogo > div')[0];	
	var menuAndLogoSumstitute;
	if (mainMenu && menuAndLogoWrapp && topBlock){
		var measurementBlockForStickMemu;
		//создаём измерительный блок, который будет всегда в верхней части экрана;
		//Измеряет расстояние от верхней части экрана до верха страницы;
		function createMeasurementBlock(){
		//	if (!window.measurementBlockForStickMemu){
			measurementBlockForStickMemu = document.createElement('DIV');
			measurementBlockForStickMemu.style.position = 'fixed';
			measurementBlockForStickMemu.style.left = 0;			
			measurementBlockForStickMemu.style.top = 0;
			document.body.appendChild(measurementBlockForStickMemu);
		//	}			
		}	
		// получаем расстояние от верхней части экрана до верхней части страницы;
		function getTopScreenPosition(){
			if (!measurementBlockForStickMemu)
				createMeasurementBlock();
			return jQuery(measurementBlockForStickMemu).offset().top;
		}		
		//добавляем блок, замещающий меню для баланса элементов после меню;
		function addSubstitute(){	
			if (!menuAndLogoSumstitute){
				menuAndLogoSumstitute = document.createElement('DIV');
				menuAndLogoSumstitute.classList.add('menuAndLogoSumstitute');
				var height = jQuery(menuAndLogoWrapp).css('height');
				topBlock.insertBefore(menuAndLogoSumstitute,menuAndLogoWrapp);
				menuAndLogoSumstitute.style.width = '100%';
				menuAndLogoSumstitute.style.height = height;				
			}
		}
		//удаляем блок, замещающий меню
		function removeSubstitute(){
			if (menuAndLogoSumstitute){
				menuAndLogoSumstitute.parentNode.removeChild(menuAndLogoSumstitute);
				menuAndLogoSumstitute = null;
			}				
		}
		//приклеиваем меню к верхней части экрана;
		function stickingMenu(){
			if (menuAndLogoWrapp.style.position != 'fixed'){
				//if (document.body.clientWidth > 767)
				//	addSubstitute();
				menuAndLogoWrapp.style.position = 'fixed';
				menuAndLogoWrapp.style.left = 0;
				menuAndLogoWrapp.style.right = 0;
				menuAndLogoWrapp.style.top = 0;
				menuAndLogoWrapp.style.zIndex = 10000;			
			}		
		}
		// отклеиваем меню от верхней части экрана;
		function toUnstickMenu(){
			if (menuAndLogoWrapp.style.position == 'fixed'){
			//	if (menuAndLogoSumstitute)
				//	removeSubstitute();
				menuAndLogoWrapp.style.position = '';
				menuAndLogoWrapp.style.left = '';
				menuAndLogoWrapp.style.right = '';
				menuAndLogoWrapp.style.top = '';
			//	logoPicture.style.display = '';					
			}
		}
		function stickingMenuTotal(){
			var topPosition = getTopScreenPosition();
			if (document.body.clientWidth <= 767){
				stickingMenu();
				removeSubstitute();	
			}else{
				if (topPosition >= 100){
					stickingMenu();
					addSubstitute();
				}else if (topPosition < 100){
					toUnstickMenu();
					removeSubstitute();
				}
			}		
			
		}		
		jQuery(window).scroll(function(){
			stickingMenuTotal();
		});		
		// применяем общую функцию приклеивания/отклеивания меню в случае изменения размера экрана;
		//делается потому, что если менять размер экрана до определённой величины, меню адаптируется и так же
		// будет стикерным, а это не требуется;
		jQuery(window).resize(function(){
			stickingMenuTotal();
		//	console.log('меняем размер стикерное меню!');
		});	
		jQuery(document).ready(function(){
			stickingMenuTotal();			
		});
	}	
});







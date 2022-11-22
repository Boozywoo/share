jQuery(document).ready(function(){		
	var ticketsBlock = document.querySelectorAll('.personalCabinet.myTickets')[0];
	if (ticketsBlock){
		var reviewPopup;
		var reviewButtons = ticketsBlock.querySelectorAll('a.giveReview');
		var revewPopupParentBlock = document.querySelectorAll('.personalCabinet.myTickets div.mainWidth .right')[0];
		console.log(revewPopupParentBlock);
		/*-- дополнительный код по требованию разработчика --*/		
		setDataAttributs(reviewButtons,'id');			
		/*-- дополнительный код по требованию разработчика завершение--*/			
		function createReviewPopup(){
			reviewPopup = document.createElement('DIV');
			reviewPopup.className = 'reviewPopup';
			revewPopupParentBlock.appendChild(reviewPopup);
			reviewPopup.innerHTML = 
						'<p class = \'title\'>Оставить отзыв</p>' +
						'<form>' +
							'<textarea>' +										
							'</textarea>' +
						'</form>' +
                        '<p class = \'evaluationTitle\'>Оценить: </p>' +
                        '<ul class = \'gradeStars\'>' +
							'<li></li>' +
							'<li></li>' +
							'<li></li>' +
							'<li></li>' +
							'<li></li>' +
						'</ul>' +                      
                        '<a class = \'sendReviewButt\'>Отправить</a>' +
                        '<a class = \'closeButt\'>x</a>';	
			var closeButton = reviewPopup.querySelectorAll('.closeButt')[0];
			if (closeButton){
			//	buttons[i].closeButton = closeButton;
				closeButton.onclick = closePopup;
			}	
			var sendButton = reviewPopup.querySelectorAll('.sendReviewButt')[0];
			//отправка отзыва по нажатию на кнопку;
			//sendButton.onclick = sendReview;	
			removeCreateMethodsFromButtons(reviewButtons);	
			var stars = reviewPopup.querySelectorAll('ul.gradeStars > li');
			console.log(stars);
			setMethodsToSrars(stars);	
			addHiddenInputOnPopup(reviewPopup,this);	
		}
		function closePopup(){
			reviewPopup.parentNode.removeChild(reviewPopup);
			reviewPopup = null;
			setCreatePopupMethodsToButtons(reviewButtons);
		}
		function setCreatePopupMethodsToButtons(buttons){
			for (var i = 0; i < buttons.length; i++){
				buttons[i].onclick = createReviewPopup;
			}			
		}
		function removeCreateMethodsFromButtons(reviewButtons){
			for (var i = 0; i < reviewButtons.length; i++){
				reviewButtons[i].onclick = null;
			}
		}
		function sendReview(){
			//код отправки отзыва
			closePopup();	
		}
		setCreatePopupMethodsToButtons(reviewButtons);
		/*---------------- set Evaluation -------------------*/
//		var evaluation;
		function getGoldenStars(star){
			var goldenStars = [star];
			var star = star.previousSibling;
			while (star){
				if (star.tagName == 'LI')
					goldenStars.push(star);
				star = star.previousSibling;
			}
			return goldenStars;	
		}
		function changeStarsClassName(goldenStars){		
			for (var i = 0; i < goldenStars.length; i++){
				jQuery(goldenStars[i]).addClass('golden');
			}															
		}		
		function changeStarsViewToGolden(){
		//	console.log('звезда!');
			var goldenStars = getGoldenStars(this);
			changeStarsClassName(goldenStars)
		}
		function returnSilverColorOfStars(){
			var stars = this.parentNode.querySelectorAll('LI');
			for (var i = 0; i < stars.length; i++){
				jQuery(stars[i]).removeClass('golden');
			}				
		}
		function setEvaluation(){
			evaluation = true;
		}
		function setMethodsToSrars(stars){
			for (var i = 0; i < stars.length; i++){
				stars[i].onmouseover = changeStarsViewToGolden;
				stars[i].onmouseout = returnSilverColorOfStars;
				stars[i].onclick = setEvaluation;
			}
		}
		/*-- дополнительный код по требованию разработчика --*/
		//назначаем атрибут data  с дополнительным именем, передаваемым в параметр dataAttrAdditName;
		//commonNameForAllEls - если true, значения для всех элементов одинаковые,
		// если false - значение равно порядковому номеру итерации + 1;
		function setDataAttributs(elements,dataAttrAdditName,commonNameForAllEls){
			var attrFullName = 'data-' + dataAttrAdditName;
			for (var i = 0; i < elements.length; i++){
				if (commonNameForAllEls)
					attrValue = commonNameForAllEls;
				else
					attrValue = i + 1;
				if (!elements[i].getAttribute(attrFullName))
					elements[i].setAttribute(attrFullName,attrValue);						
			}		
		}		
		//добавляем невидимый input type = 'text'  в popup;
		function addHiddenInputOnPopup(popup,clickButt){
			var input = document.createElement('INPUT');
			input.type = 'text';
			input.style.display = 'none';
			input.classList.add('techHiddenInput');
			popup.appendChild(input);
			input.setAttribute('value',clickButt.getAttribute('data-id'));	
		}
		//не используется. Popup не скрывается, а удаляется и надобность в удалении технического input Отпадает;
		function removeHiddenInputFromPopup(popup){
			var hiddenInput = popup.getElementsByClassName('techHiddenInput')[0];
			if (hiddenInput)
				hiddenInput.parentNode.removeChild(hiddenInput);
		}
		/*-- дополнительный код по требованию разработчика завершение--*/		
	}
});

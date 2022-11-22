jQuery(document).ready(function(){	

/*	var reviews = [
		{
			userName: 'Вася',
			reviewDate: '25.11.2016',
			review: '<p>Экран, как мне показалось, оптимален - яркий, сочный, на солнце не слепнет (IPS). Неплохая камера, при нормальном освещении получаются достойные фотки, видео снимает тоже достаточно хорошо. 4-ядерный процессор гарантирует отсутствие тормозов. АКБ держится 1-2 суток при умеренном использовании. Хочу особенно отметить качество связи - передача голоса просто идеальная, собеседника слышно, как рядом. Динамики громкие, запаса хватает с лихвой. Приём сети отличный. GPS, W-Fi, BT работают, как положено. Единственное, чего не хватает - ОЗУ. 512 Мб для 8.1 достаточно, а вот 10-тке мало (ставил technical prewiew), начинаются тормоза, телефон греется до не комфортных температур. Странно, что на время работы, это никак не влияло...</p>'
		},
		{
			userName: 'Володя',
			reviewDate: '25.11.2016',
			review: '<p>В целом телефон неплохой, если предполагается использовать его с большего как обычную звонилку. Хороший экран, на ярком солнце блекнет не сильно. Громкость звука и его качество вполне устраивают. Камера слабенькая, фронталки нет, вспышки нет. Система работает достаточно шустро.</p>'
		},		
		{
			userName: 'Миша',
			reviewDate: '25.11.2016',
			review: '<p>Отличный телефон! А за такие деньги, тем более. Хотя по началу и возникли проблемы: периодически пропадала сеть (не мог выйти в интернет и позвонить и до меня не могли дозвониться). Помогало только выключение/включение телефона.Отнес по гарантии. Перепрошили, но это не помогло. На второй раз поменяли плату и проблема исчезла. Но несмотря на эту неприятность телефон отличный. Пользуюсь уже больше полугода. При нормальном освещении фото получаются отличными. Мощности процессора и оперативной памяти хватает. Покупкой очень доволен, а за такую цену тем более. Советую всем, кто не гонится за новоротами.</p>'
		},	
		{
			userName: 'Света',
			reviewDate: '25.11.2016',
			review: '<p>Телефон просто отличный. Учитывая мою нелюбовь к Нокиа вообще, покупал с некоторой опаской. </p>'
		},		
		{
			userName: 'Марина',
			reviewDate: '25.11.2016',
			review: '<p>Родная камера не может похвастаться обилием настроек, но приложение люмиа камера исправляет дело. Фотки получаются очень даже хорошие, главное не полениться настроить их. Из недостатков хочется отметить скользкий корпус, слишком простой проигрыватель, если аппарат лежит в кармане, часто перекрывается динамик и звук вызова практически не слышен. Таже история когда телефон лежит на диване на задней крышке. А в целом аппаратом очень доволен. </p>'
		},	
		{
			userName: 'Алексей',
			reviewDate: '25.11.2016',
			review: '<p>Пользуюсь телефоном третий месяц и хочу поделится своими ощущениями по поводу этого девайса. Телефон в плане интернета, звонков и музыки просто супер!!!! Нет слов, но в плане игрового софта он отстает от "Android" намного, но на него есть тяжелые игрушки, одна из них "Heroes of order of chaos" ну и всеми знаменитая игра "GTA San Andreas" она идет без глюков и на ультрах.</p>'
		},		
		{
			userName: 'Борис',
			reviewDate: '25.11.2016',
			review: '<p>Никогда ранее не пользовался Windows phone, до приобретения этого смартфона Lumia 630! Был Андройд, который меня в принципе устраивал всегда, кроме батареи ( стоит включить 3g и батареи хватит на 2-3 часа )... А теперь про телефон, реально, лучше телефона за такие деньги не видел !</p>'
		},	
		{
			userName: 'Сергей',
			reviewDate: '25.11.2016',
			review: '<p>Брал не так давно на замену своей 520, ибо захотелось чего то нового. Телефон полностью меня устроил в плане работы</p>'
		}				
	]*/
	
	//получаем массив объектов отзывов из блока на странице;
	function getReviews(){
		var reviewsInfo = document.querySelectorAll('.clientsAboutUs .reviewsInfo')[0]; 
		if (reviewsInfo){
			//получаем дочерние пункты списка;
			function getChildItemsInList(list){
				var items = [];
				var item = list.firstChild;
				while (item){
					if (item.tagName == 'LI')
						items.push(item);
					item = item.nextSibling;
				}
				return items;
			}		
			//получаем содержание элемента с определённым классом;
			function getValueOfDescendantElementWithClassName(element,descendantClassName){
				var descendantElement = element.getElementsByClassName(descendantClassName)[0];
				if (descendantElement)
					return descendantElement.innerHTML;
			}
			//создаём объект информации об отзыве;
			function createReviewObject(element){				
				var userName = getValueOfDescendantElementWithClassName(element,'author');				
				var reviewDate = getValueOfDescendantElementWithClassName(element,'date');				
				var review = getValueOfDescendantElementWithClassName(element,'review');				
				var reviewObject = {
					userName: userName,
					reviewDate: reviewDate,
					review: review
				};		
				if (reviewObject.userName && reviewObject.reviewDate && reviewObject.review)
					return reviewObject;
			}
			function getReviewsArray(reviewItems){
				var reviewsArray = [];
				for (var i = 0; i < reviewItems.length; i++){
					var review = createReviewObject(reviewItems[i]);
					if (review)
						reviewsArray.push(review);
				}
				return reviewsArray;
			}
			var reviewItems = getChildItemsInList(reviewsInfo);
		//	var reviews = [];			
			var reviews = getReviewsArray(reviewItems);
		}
		return reviews;
	}
	var reviews = getReviews();
	var reviewsSlider = document.querySelectorAll('.clientsAboutUs .reviewsSlider')[0];	
	if (reviewsSlider && reviews){
		if (reviews.length > 3){	
			var sliderList = document.querySelectorAll('.silesList')[0];	
			var leftButton = reviewsSlider.querySelectorAll('.buttons.leftButton')[0];	
			var rightButton = reviewsSlider.querySelectorAll('.buttons.rightButton')[0];
			var buttons = [leftButton,rightButton];	
			sliderList.style.overflow = 'hidden';	
			var slidesNum = 3, 
			reduceSlides = true; //сжатие слайдов в зависимости от заданного параметра maxSlideHeight;
			extensibleSlider = true; // растяжение слайдера в зависимости от размера нового слайда;
			var activeFirstSlide = 0, movement = false,// maxSlideHeight = 480;	
			maxSlideHeight = 355,
			commonIncrease = 0; // увеличение высоты слайдера относительно изначальной величины;
			//вычисляем шаг перемещения;
			function getStep(){
				var slides = getSlidesInSlider(sliderList);
				if (slides[1]){
					$slide = jQuery(slides[1]);
					var width = $slide.css('width');
					var margin = $slide.css('margin-left');
				}
				return parseInt(width) + parseInt(margin);
			}
			// функция задания метода массиву элементов; пердаётся массив, название метода, метод;
			function setMethodToElements(elements,methName,meth){
				for (var i = 0; i < elements.length; i++){
					elements[i][methName] = meth;
				}
			}
			//функция добавления слайда в слайдер. Можно добавлять как в начало, так и в конец списка слайдов;
			function addSlide(sliderList,position,slideInfo,additional){
				var slide = document.createElement('LI');
				switch (position){
					case 'beginning' :
						sliderList.insertBefore(slide,sliderList.firstChild);
						break;
					case 'end' : 
						sliderList.appendChild(slide);					
				}		
				if (additional == true)
					slide.style.position = 'absolute';				
				var itemTopPartWrapp = document.createElement('DIV');
				slide.appendChild(itemTopPartWrapp);
				itemTopPartWrapp.className = 'itemTopPartWrapp';
				itemTopPartWrapp.innerHTML = '<div class=\'itemTopPart\'></div>';
				var review = document.createElement('DIV');
				slide.appendChild(review);
				slide.review = review;
				review.classList.add('review');
				var textBlock = document.createElement('DIV');
				review.appendChild(textBlock);
				textBlock.classList.add('textBlock');
				textBlock.innerHTML = slideInfo.review;
				slide.textBlock = textBlock;
				var angleWrapper = document.createElement('DIV');
				angleWrapper.classList.add('angleWrapper');
				review.appendChild(angleWrapper);
				angleWrapper.innerHTML = '<div class = \'angle\'></div>';
				slide.angleWrapper = angleWrapper;
				var revAuthor = document.createElement('P');
				slide.appendChild(revAuthor);
				var revAuthTextNode = document.createTextNode(slideInfo.userName);
				revAuthor.appendChild(revAuthTextNode);
				revAuthor.classList.add('revAuthor');
				var revDate = document.createElement('P');
				slide.appendChild(revDate);
				var revDateTextNode = document.createTextNode(slideInfo.reviewDate);
				revDate.appendChild(revDateTextNode);
				revDate.classList.add('revDate');
				if (reduceSlides)
					checkAndChangeSlideHeight(slide);
				return slide;				
			}		
			//добавляем первые слайды;
			function addFirstSlides(slidesNum,sliderList){
				for (var i = 0; i < slidesNum; i++){
					addSlide(sliderList,'end',reviews[i],false);
				}
			}
			// получаем позицию кнопки (левая или правая)
			function getButtonPosition(button){
				var position;
				if (button.classList.contains('leftButton'))
					position = 'left';
				else if (button.classList.contains('rightButton'))
					position = 'right';
				return position;								
			}
			function setPositionsToButtons(buttons){
				for (var i = 0; i < buttons.length; i++){
					buttons[i].position = getButtonPosition(buttons[i]);
				}
			}
			// меняем индекс элемента массива слайдера на индекс элемента, который находится в позиции 1го слайда;
			//используется для вычисления местоположения ленты;
			function changeFirstActiveSlide(){
				switch (this.position){
					case 'left' :
						activeFirstSlide--;
						break;
					case 'right' :
						activeFirstSlide++
				}
				if (activeFirstSlide < 0)
					activeFirstSlide = (reviews.length - 1)
				else if (activeFirstSlide > (reviews.length - 1))
					activeFirstSlide = 0;
				console.log(activeFirstSlide);				
			}
			//получаем слайды в слайдере, включая временный дополнительный;
			function getSlidesInSlider(sliderList){
				var slides = [];
				var slide = sliderList.firstChild;
				while (slide){
					if (slide.tagName == 'LI'){
						slides.push(slide);
					}
					slide = slide.nextSibling;
				}
				return slides;
			}
			//удаление лишнего слайда, который выходит за область видимости;
			function removeExcessSlide(sliderList,position){
				var slides = getSlidesInSlider(sliderList);
				if (position == 'left')
					slides[slides.length - 1].parentNode.removeChild(slides[slides.length - 1]);
				else if (position == 'right')
					slides[0].parentNode.removeChild(slides[0]);
			}
			//определяет слайды, прикреплённые к слайдеру на данный момент без дополнительного;
			// без дополнительного потому, что его необходимо перемещать, применяя абсолютное позиционирование,
			//а остальные - относительное;
			function getActualSlidesWithoutAdditional(direction,slidesInSlide){
				var actualSlidesWithoutAdditional = [], indexOfFirstSLideInAcrtualSlidesArr;
				if (direction == 'left')
					indexOfFirstSLideInAcrtualSlidesArr = 1;
				else if (direction == 'right')
					indexOfFirstSLideInAcrtualSlidesArr = 0;
				for (var i = indexOfFirstSLideInAcrtualSlidesArr; i < (slidesNum + indexOfFirstSLideInAcrtualSlidesArr); i++){
					actualSlidesWithoutAdditional.push(slidesInSlide[i]);
				}
				return actualSlidesWithoutAdditional;
			}
			//получаем высоту слайдера;
			function getSlidesListHeight(){
				return parseInt(jQuery(sliderList).css('height'));
			}	
			//получаем высоту слайда;		
			function getSlideHeight(slide){
				return parseInt(jQuery(slide).css('height'));
			}
			//находим высоту слайдера без лишгего слайда;
			function getMaxSlideHeightWithoutexcessSlide(excessSlide,slidesInSlider){
				var maxHeight = 0;
				for (var i = 0; i < slidesInSlider.length; i++){
					if (slidesInSlider[i] != excessSlide){
						var slideHeight = parseInt(jQuery(slidesInSlider[i]).css('height'));
						if (slideHeight > maxHeight)
							maxHeight = slideHeight;
					}
				}
				return maxHeight;
			}
			//функция растяжения слайдера. Растягивается, если размер добовляемого слайда больше размера слайдера;
			function stretchSlider(additionalSlide,excessSlide,slidesInSlider){
				//additionalSlide - дополнительный слайд; excessSlide - удаляемый слайд
				var sliderHeight = getSlidesListHeight();
				var slideHeight = getSlideHeight(additionalSlide);
				if (slideHeight > sliderHeight){
					var differenceInPx = '+=' + (slideHeight - sliderHeight) + 'px';					
						
					jQuery(sliderList).animate({height: differenceInPx},1000,'linear');
				}else if(slideHeight < sliderHeight){
					var excessSlideSlideHeight = getSlideHeight(excessSlide);
					var sliderHeightWithoutexcessSlide = getMaxSlideHeightWithoutexcessSlide(excessSlide,slidesInSlider)
					if (excessSlideSlideHeight > sliderHeightWithoutexcessSlide){
						var differenceInPx = '-=' + (excessSlideSlideHeight - sliderHeightWithoutexcessSlide);
						jQuery(sliderList).animate({height: differenceInPx},1000,'linear');
					}
				}				
			}
			//функция движения в одном направлении
			function movementInOneDirection(direction){
				if (direction == 'left'){
					var slidesInSlider = getSlidesInSlider(sliderList);
					var actualSlidesWithoutAdditional = getActualSlidesWithoutAdditional('left',slidesInSlider)//для tight будет activeFirstSlide
					var additionalSlide = slidesInSlider[0];
					var $additionalSlide = jQuery(additionalSlide);
					var excessSlide = actualSlidesWithoutAdditional[slidesNum -1];
					if (extensibleSlider)
						stretchSlider(additionalSlide,excessSlide,slidesInSlider);	
					for (var i = 0; i < slidesNum; i++){
						jQuery(actualSlidesWithoutAdditional[i]).animate({left: step},1000,'linear',function(){
						//	console.log('Перемещение завершили');
							this.style.left = '';						
							this.style.top = '';
							movement = false;							
						});
					}
					additionalSlide.style.top = 0;
					additionalSlide.style.left = -step + 'px';				
					$additionalSlide.animate({left: 0},1000,'linear',function(){					
						excessSlide.parentNode.removeChild(excessSlide);
						additionalSlide.style.position = '';
						this.style.left = '';						
						this.style.top = '';		
						changeCommonIncrease();	
					});			
				}else if (direction == 'right'){
					var slidesInSlider = getSlidesInSlider(sliderList);
					var actualSlidesWithoutAdditional = getActualSlidesWithoutAdditional('right',slidesInSlider)//для tight будет activeFirstSlide
					var additionalSlide = slidesInSlider[3];		
					var $additionalSlide = jQuery(additionalSlide);	
					var excessSlide = actualSlidesWithoutAdditional[0];
					if (extensibleSlider)
						stretchSlider(additionalSlide,excessSlide,slidesInSlider);				
					for (var i = 0; i < slidesNum; i++){
						jQuery(actualSlidesWithoutAdditional[i]).animate({right: step},1000,'linear',function(){
						//	console.log('Перемещение завершили');
							this.style.right = '';						
							this.style.top = '';
							movement = false;	
						});
					}			
					additionalSlide.style.top = 0;
					additionalSlide.style.right = -step + 'px';						
					$additionalSlide.animate({right: 0},1000,'linear',function(){
						excessSlide.parentNode.removeChild(excessSlide);
						additionalSlide.style.position = '';
						this.style.right = '';						
						this.style.top = '';
						changeCommonIncrease();	
					});	
				}			
			}
			//удаление кнопки раширения отзыва;	
			function removeMoreButtonWrapp(){			
				var moreButtonWrapp = this.slide.moreButtonWrapp;
				moreButtonWrapp.parentNode.removeChild(moreButtonWrapp);
				this.slide.moreButtonWrapp = null;
			}		
			function changeCommonIncrease(){
			//	commonIncrease
				var sliderHeght = getSlidesListHeight();
				var different = sliderHeght - maxSlideHeight;
				if (different => 0)
					commonIncrease = different;
			}			
			//увеличение высоты текстового блока на разницу между изначальной высотой слайда и максимальной;
			//высота слайда увеличится соответственно;			
			//commonIncrease - увеличение высоты слайдера относительно максимальной высоты слайда;
			//commonIncreaseAdditionalHeight - величина, на которую превышает высота разворачиваемого слайда
			// нынешнюю высоту слайдера
			function increaseSlide(){
				if (!movement && this.slide.moreButtonWrapp){
					this.slide.angleWrapper.style.display = '';
					movement = true;
					var heightDifference = this.slide.heightDifference;
					var commonIncreaseAdditionalHeight = heightDifference - commonIncrease;					
					var textBlock = this.slide.textBlock;
					var moreButton = this;
					if (commonIncreaseAdditionalHeight > 0){
						commonIncrease += commonIncreaseAdditionalHeight;
					}					
					console.log(commonIncrease);				
					jQuery(this.slide.textBlock).animate({height: '+=' + heightDifference + 'px'},400,'linear');
					var sliderIncrease = 0;
					if (commonIncreaseAdditionalHeight > 0){
						sliderIncrease = commonIncreaseAdditionalHeight;
					}
					jQuery(sliderList).animate({height: '+='  + sliderIncrease + 'px'},400,'linear',function(){
						moreButton.removeMoreButtonWrapp();
						movement = false;						
					});					
				}
			}
			//добавляем кнопку расширения отзыва и прячем уголок;
			function addMoreButton(slide){
				slide.angleWrapper.style.display = 'none';
				var moreButtonWrapp = document.createElement('DIV');
				slide.review.appendChild(moreButtonWrapp);
				moreButtonWrapp.classList.add('moreButtonWrapp');					
				var moreButton = document.createElement('A');
			//	moreButton.classList.add('reviewButt increase');
				moreButton.className = 'reviewButt increase';
				moreButton.style.display = 'inline-block';
				moreButton.style.cursor = 'pointer';
				moreButton.style.color = '#ff4200';
				moreButton.style.paddingBottom = '3px';
				moreButton.style.textTransform = 'lovercae';
				moreButton.style.fontStyle = 'normal';
			//	moreButton.style.fontWeight = '500';
				moreButtonWrapp.appendChild(moreButton);
				slide.moreButtonWrapp = moreButtonWrapp;
				var moreText = document.createTextNode('весь отзыв...');
				moreButton.appendChild(moreText);
				moreButton.slide = slide;
				moreButton.removeMoreButtonWrapp = removeMoreButtonWrapp;
				moreButton.onclick = increaseSlide;				
			}
		/*	//слайд состоит из отзыва и дополнительной информации. функция вычисляет высоту блоков этой информации;
			function getHeightOfAdditionalReviewInfo(slide){
				
			}*/			
			
			//проверяем высоту слайда и если она больше установленного значения, ужимаем с добавлением кнопки расширения;
			function checkAndChangeSlideHeight(slide){
			//	var $slide = jQuery(slide);
				var startHeight = getSlideHeight(slide);
				slide.startHeight = startHeight;
				if (startHeight > maxSlideHeight){
					// разница между начальной высотой слайда и максимально допустимоый его высотой;
					slide.heightDifference = startHeight - maxSlideHeight;
					var textBlockHeight = parseInt(jQuery(slide.textBlock).css('height'));
					slide.textBlock.style.height = (textBlockHeight - slide.heightDifference) + 'px';				
					addMoreButton(slide);
				}else if(startHeight < maxSlideHeight){
					slide.heightDifference = maxSlideHeight - startHeight;
					var textBlockHeight = parseInt(jQuery(slide.textBlock).css('height'));
					slide.textBlock.style.height = (textBlockHeight + slide.heightDifference) + 'px';
				}	
			}
			//основной метод перемещения ленты
			function moveRibbon(){
				if (!movement){
					movement = true;
					switch (this.position){
						case 'left' : 
							activeFirstSlide--;
							if (activeFirstSlide < 0)
								activeFirstSlide = (reviews.length - 1);					
							var slide = addSlide(sliderList,'beginning',reviews[activeFirstSlide],true);
							movementInOneDirection('left');
							break;
						case 'right' : 				
							var indexOfAddSlide = activeFirstSlide + slidesNum;
							if (indexOfAddSlide > (reviews.length - 1))
								indexOfAddSlide = (indexOfAddSlide) - (reviews.length - 1)	- 1;						
							//	(reviews.length - 1) - индекс последнего элемента в массиве слайдов;
							// - 1. Если разница между индексом прибавляемого слайда и индексом последнего слайда, например
							// будет равна 1, то нужно добавить слайд 0. Т.е. отнимается 1.
							var slide = addSlide(sliderList,'end',reviews[indexOfAddSlide],true);				
							activeFirstSlide++;
							if (activeFirstSlide > (reviews.length - 1))
								activeFirstSlide = 0;
							movementInOneDirection('right');						
					}
				}
			}		
			// executions;
			setPositionsToButtons(buttons);
			addFirstSlides(3,sliderList);
			var step = getStep();
			setMethodToElements(buttons,'onclick',moveRibbon);	
			setMethodToElements(buttons,'increaseSlide',increaseSlide);	
		}	
	}
});

















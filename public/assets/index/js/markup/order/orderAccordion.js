//TODO добавить имя и телефон в вёрстку
jQuery(document).ready(function(){
	//Список способов оплаты
	var paymentMethodList = document.querySelectorAll('.mainOrderBlock ul.paymentMethod')[0];	
	if (paymentMethodList){
		console.log('Работает аккордион!');	
		// ищет дочерние элементы в родительском по названию элемента
		function getChildTags(parentTag,childTagName){
			var childElement = parentTag.firstChild;
			var tags = [];
			while (childElement){				
				if (childElement.tagName == childTagName)
					tags.push(childElement);
				childElement = childElement.nextSibling;
			}	
			return tags;
		}
		//функция назначает каждому пункту в качестве свойства его раскрывающийся блок
		function setLatentBlockToItem(items,latentBlockInnerSlector){
			for (var i = 0; i < items.length; i++){
				var latentBlock = items[i].querySelectorAll(latentBlockInnerSlector)[0];
				if (latentBlock)
					items[i].latentBlock = latentBlock;
			}
		}
		//назначает каждому пункту метод, который передаётся вместе с именем в качестве аргументов
		function setMethodToItems(items,method,methodName){
			for (var i = 0; i < items.length; i++){
				items[i][methodName] = method;
				items[i].onclick = method;
				items[i].itemsArr = items;
			}
		}
		//Проверяет все элементы списка на наличие в них активного класса и удаляет его в найденном;
		//срабатывает, если есть активный клсс и если элемент при этом не является актуальным. актуальный 
		//передаётся в аргументе
		function removeActiveClassFromNotActualElement(items,actualActiveEl){
			for (var i = 0; i < items.length; i++){
				if (jQuery(items[i]).hasClass('active') && items[i] !== actualActiveEl){
					jQuery(items[i]).removeClass('active');
					items[i].hiddenInput.checked = false;
				}					
			}
		}		
		//метод назначает активный класс элементу по клику, если у этого элемента ещё не было активного класса
		function changeActiveClass(){
			var isActive = jQuery(this).hasClass('active');
			if (!isActive){
				jQuery(this).addClass('active');
				this.hiddenInput.checked = true;
				removeActiveClassFromNotActualElement(this.itemsArr,this);
			}			
		}
		//функция добавляет невидимый input type='radio' внутрь пункта;
		function createHiddenInput(item,name){
			var input = document.createElement('INPUT');
			input.type = 'radio';
			input.style.display = 'none';
			if (item){
				item.appendChild(input);
				input.name = name;
				item.hiddenInput = input;
			}	
		}
		//выполняет функцию для массива элементов;
		//в качестве аргументов передаётся функция, массив элементов, для которых она должны быть выполнена
		//и параметры, которые должны будут в неё передаваться (массив);
		function executeFuncForArr(func,arr,params){
			for (var i = 0; i < arr.length; i++){
				var paramsArr = [arr[i]];
				for (var j = 0; j < params.length; j++){
					paramsArr.push(params[j]);
				}
				func.apply(this,paramsArr);
			}
		}
		var mainItems = getChildTags(paymentMethodList,'LI');
		executeFuncForArr(createHiddenInput,mainItems,['paymentMethod']);
		setLatentBlockToItem(mainItems,'.latentBlock');
		setMethodToItems(mainItems,changeActiveClass,'changeActiveClass');
		//внутренний список 'Оплатить онлайн сейчас'
		//Используются те же методы, что и для внешнего
		var paymentOnlineList = document.querySelectorAll('.mainOrderBlock ul.paymentMethod > li.paymentOnline > div > ul.innerList')[0];
		if (paymentOnlineList){
			paymentOnlineListItems = getChildTags(paymentOnlineList,'LI');
			setMethodToItems(paymentOnlineListItems,changeActiveClass,'changeActiveClass');
			executeFuncForArr(createHiddenInput,paymentOnlineListItems,['paymentOnline']);
		}
	}	
});

					
	

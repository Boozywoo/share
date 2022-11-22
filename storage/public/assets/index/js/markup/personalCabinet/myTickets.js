jQuery(document).ready(function(){
	var myTicketsBlock = document.querySelectorAll('.personalCabinet.myTickets')[0];
	if (myTicketsBlock){
		console.log('страница расписания');
		//обънкт с данными о маршрутах
		//в перспективе должен быть настроен механизм получения данных не из этой переменной,
		// а из БД		
		var tickets = {
			upcoming: [
				[
					'Витебск-Минск',
					'07.08.2016',
					'7.00',
					'10'
				],
				
			],
			done: [
				
			],
			canceled: [
				
			]	
		}
		//объект с парами: 1) селектор кнопки 2) селектор табрицы, на которую влияет кнопка
		var buttAndTableSelectors = [
			{
				butSel: 'a.upcoming',
				tebleSel: 'table.upcoming'
			},
			{
				butSel: 'a.done',
				tebleSel: 'table.done'
			},
			{
				butSel: 'a.canceled',
				tebleSel: 'table.canceled'
			}					
		]
		//переменная активной кнопки перехода на одну из таблиц. Входит в область видимости основных функций 
		var activeButt;
		//показывает таблицу по клику на кнопку	
		function showTicketTable(){
			console.log(this.ticketTable);			
			if (activeButt != this){
				hideTicketTable();
				jQuery(activeButt).removeClass('active');
				jQuery(this).addClass('active');
				this.ticketTable.style.display = 'table';			
				activeButt = this;				
			}			
		}	
		//скрывает таблицу, которая более не является свойством активной кнопки
		function hideTicketTable(){
			if (activeButt)
				activeButt.ticketTable.style.display = '';
		}
		//назначаем методы и свойства кнопкам. передаём массив кнопок, имя свойства(метода), свойство(метод)
		function setMethOrPropsToButts(buttons,name,methOrProp){
			for (var i = 0; i < buttons.length; i++){
				buttons[i][name] = methOrProp;
			}
		}
		//удаляет кнопки из ряда после перенесения его в таблицу отменённых поездок
		function removeIrrelevantButtons(row){
			var removeRow = row.querySelectorAll('a.removeRow')[0];	
			var purse = row.querySelectorAll('a.purse')[0];
			if (removeRow)
				removeRow.parentNode.removeChild(removeRow);	
			if (purse)
				purse.parentNode.removeChild(purse);				
		}
		//переносит ряд в таблицу отменённых поездок
		function transferRow(){
			if (canceledTable){				
				var tbody = getChildElement(canceledTable,'TBODY');
				tbody.appendChild(rowForTransfer);
				removeIrrelevantButtons(rowForTransfer);
				refreshRowNumbers();
				hidePopup();	
			}				
		}
		//переменная перемещаемого ряда таблицы. Входит в область видимости основных функций
		var rowForTransfer;
		//заносим в переменную перемещаемого ряда ряд кнопки, от которой выполнено событие,
		//показываем popup
		function isTransferRow(){				
			rowForTransfer = this.parentNode.parentNode;
			var popup = showPopup();			
			addHiddenInputOnPopup(popup,this);	
		}
		//назначаем таблицы кнопкам как свойства, используя в качества аргумента объект, созданный ранее
		function setTablesToButtonsAsProp(buttAndTableSelectors){
			for (var i = 0; i < buttAndTableSelectors.length; i++){
				var button = myTicketsBlock.querySelectorAll(buttAndTableSelectors[i].butSel)[0];				
				var table = myTicketsBlock.querySelectorAll(buttAndTableSelectors[i].tebleSel)[0];
				if (button && table)
					button.ticketTable = table;	
			}		
		}
		// получаем таблицы расписаний
		function getTables(buttAndTableSelectors){
			var tables = [];
			for (var i = 0; i < buttAndTableSelectors.length; i++){
				var table = myTicketsBlock.querySelectorAll(buttAndTableSelectors[i].tebleSel)[0];				
				if (table)
					tables.push(table);
			}
			return tables;
		}
		//возвращает первый из дочерних элементов передаваемого блока, имеющий передаваемое имя тега   
		function getChildElement(parent,childTagName){
			var element = parent.firstChild;
			while (element && element.tagName != childTagName){				
				element = element.nextSibling;
			}
			return element;
		}
		// возвращает массив дочерних элементов передаваемого блока, имеющих передаваемое имя тега 
		function getChildElements(parent,childElsTagName){
			var elements = [];
			var  element = parent.firstChild;
			while (element){
				if (element.tagName == childElsTagName)
					elements.push(element)				
				element = element.nextSibling;					
			}
			return elements;
		}
		//обновляет номера рядов таблицы
		function refreshRowNumbers(){
			var tables = getTables(buttAndTableSelectors);
			for (var i = 0; i < tables.length; i++){
				var tbody = getChildElement(tables[i],'TBODY');
				var trs = getChildElements(tbody,'TR');	
				for (var j = 0; j < trs.length; j++){
					var firstTD = getChildElement(trs[j],'TD');
					var oldRowNumTextNode = firstTD.firstChild;
					if (oldRowNumTextNode){
						oldRowNumTextNode.parentNode.removeChild(oldRowNumTextNode);
						var textNode = document.createTextNode(j + 1);
						console.log(firstTD);
						firstTD.appendChild(textNode);					
					}else{
						var textNode = document.createTextNode(j + 1);
						firstTD.appendChild(textNode);								
					}
				}
			}
		}
		//возвращает цифру, передаётся строка. Для удаления 'px'
		function getNumberFromString(val){
			return parseInt(val,10);
		}	
		//переменная высоты обёркт popup. Высота блока, котоырй располагается в другом блоке с display: none не возвращается
		//методом jQuery height, если она формируется за счёт контента блока.
		var popupHeight;	
		//вычисление высоты popup с внесением значения в переменную
		function getPopupHeight(popup,popupWrapp){
			popupWrapp.style.visibility = 'hidden';
			popupWrapp.style.display = 'block';
			popupHeight = jQuery(popup).css('height');	
			popupWrapp.style.visibility = '';
			popupWrapp.style.display = '';			
		}
		//задаёт положение popup по горизонтали, применяя верхнее значение поля к обёртке popup
		function setPopupVertPosition(popup,popupWrapp){
			console.log('popup!');
			var popupWrappHeight = getNumberFromString(jQuery(popupWrapp).css('height'));
		//	var popupHeight = jQuery(popup).css('height');			
			popupWrapp.style.paddingTop = getNumberFromString(popupWrappHeight)/2 - getNumberFromString(popupHeight)/2 + 'px';
			console.log(popupWrappHeight);
		//	console.log(popupHeight);
		}
		function showPopup(){
			popupWrapp.style.display = 'block';
			return popupWrapp; 
		}
		function hidePopup(){
			popupWrapp.style.display = '';	
			removeHiddenInputFromPopup(popupWrapp);	
			return popupWrapp;
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
		function removeHiddenInputFromPopup(popup){
			var hiddenInput = popup.getElementsByClassName('techHiddenInput')[0];
			if (hiddenInput)
				hiddenInput.parentNode.removeChild(hiddenInput);
		}
		/*-- дополнительный код по требованию разработчика завершение--*/
		var upcomingButt = 	myTicketsBlock.querySelectorAll('a.upcoming')[0];
		var doneButt = 	myTicketsBlock.querySelectorAll('a.done')[0];
		var canceledButt = 	myTicketsBlock.querySelectorAll('a.canceled')[0];
		var buttons = [upcomingButt,doneButt,canceledButt];
		var transferButtons = myTicketsBlock.querySelectorAll('table.userRoutsShedule.upcoming .removeRow');
		var canceledTable = myTicketsBlock.querySelectorAll('table.userRoutsShedule.canceled')[0];
		setMethOrPropsToButts(buttons,'onclick',showTicketTable);
		setMethOrPropsToButts(transferButtons,'onclick',isTransferRow);		
		setTablesToButtonsAsProp(buttAndTableSelectors);	
		upcomingButt.onclick();
		refreshRowNumbers();
		//popup
		var popupWrapp = myTicketsBlock.getElementsByClassName('popupWrapper')[0];
		var popup = myTicketsBlock.getElementsByClassName('popup')[0];		
		if (popupWrapp && popup){
			getPopupHeight(popup,popupWrapp);			
			setPopupVertPosition(popup,popupWrapp);
			var yesButton = popup.querySelectorAll('.butt.yes')[0];			
			var noButton = popup.querySelectorAll('.butt.no')[0];
			noButton.popup = popup;			
			noButton.onclick = hidePopup;
			yesButton.popup = popup;			
			//перенос ряда по клику на кнопку "да" в попапе;
		//	yesButton.onclick = transferRow;
		}
		/*-- дополнительный код по требованию разработчика --*/
		var removeButts = document.querySelectorAll('.userRoutsShedule.upcoming a.removeRow');
		setDataAttributs(removeButts,'id');			
		/*-- дополнительный код по требованию разработчика завершение--*/		
	}	
});
jQuery(document).ready(function(){


	function initTemplateBus() {
		if ($('.filterElements').length) {
			var rowQuantity = 4; // количество рядов
			var cellQuantity = 7; // количество столбцов
			var minRowQuantity = 3;	// минимальное количество рядов
			var maxRowQuantity = 5;	// максимальное количество столбцов
			var minCellQuantity = 3; // максимальное количество рядов
			var maxCellQuantity = 20;// максимальное количество столбцов
			var rowQuantityInscriptionEl = document.querySelectorAll('.addRowColumnWrapp .rowQuantity')[0];
			var cellQuantityInscriptionEl = document.querySelectorAll('.addRowColumnWrapp .cellQuantity')[0];
			var seatsBlock = document.querySelectorAll('.busLayoutBlock .mainBusBlock .seats')[0];
			var addRowButt = document.querySelectorAll('.addRowColumnWrapp.rowOp .addRow')[0];
			var removeRowButt = document.querySelectorAll('.addRowColumnWrapp.rowOp .removeRow')[0];
			var addColumnButt = document.querySelectorAll('.addRowColumnWrapp.columnOp .addColumn')[0];
			var removeColumnButt = document.querySelectorAll('.addRowColumnWrapp.columnOp .removeColumn')[0];
			var busBodeyClasses = ['threeRowsOfSeats','fiveRowsOfSeats'];
			//-- add methods
			//функция очистки класса, отвечающего за габариты шаблона
			function cleanBodeyBusCountRowClasses(busBodeyClasses){
				var busBodey  = document.querySelectorAll('.busLayoutBlock .mainBusBlock .busBodey')[0];
				for (var i = 0; i < busBodeyClasses.length; i++){
					busBodey.classList.remove(busBodeyClasses[i]);
				}
			}
			addRowButt.onclick = function(){
				if (rowQuantity < maxRowQuantity){
					addRow(cellQuantity,rowQuantity);
					rowQuantity++;
					refreshNumInscriprion(rowQuantityInscriptionEl,rowQuantity);
					refreshInputOfNumIndicatorValue(rowQuantityInscriptionEl,rowQuantity);
					changeBusBodeyClassname(rowQuantity);
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
				}
			}
			removeRowButt.onclick = function(){
				if (rowQuantity > minRowQuantity){
					removeLastRow();
					rowQuantity--;
					refreshNumInscriprion(rowQuantityInscriptionEl,rowQuantity);
					refreshInputOfNumIndicatorValue(rowQuantityInscriptionEl,rowQuantity);
					changeBusBodeyClassname(rowQuantity);
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
				}
			}
			addColumnButt.onclick = function(){
				if (cellQuantity < maxCellQuantity){
					addColumn();
					cellQuantity++;
					refreshNumInscriprion(cellQuantityInscriptionEl,cellQuantity);
					refreshInputOfNumIndicatorValue(cellQuantityInscriptionEl,cellQuantity);
					changeBusBodeyClassname(rowQuantity);
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
				}
			}
			removeColumnButt.onclick = function(){
				if (cellQuantity > minCellQuantity){
					removeColumn();
					cellQuantity--;
					refreshNumInscriprion(cellQuantityInscriptionEl,cellQuantity);
					refreshInputOfNumIndicatorValue(cellQuantityInscriptionEl,cellQuantity);
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
				}
			}
			//обновляет индикаторы столбцов и строк;
			function refreshNumInscriprion(numElement,num){
				numElement.firstChild.nodeValue = num;
			}
			//обновляет значение инпута индикаторов столбцов и строк;
			function refreshInputOfNumIndicatorValue(numElement,num){
				function getComplementaryInput(){
					var complementaryInput = numElement.parentNode.querySelectorAll('input.complementaryInput')[0];
					var inputForReturn;
					if (!complementaryInput){
						var compInput = document.createElement('INPUT');
						compInput.classList.add('complementaryInput');
						compInput.name = 'ranks';
						compInput.type = 'hidden';
						var buttonsList = numElement.parentNode.querySelectorAll('ul.buttons')[0];
						numElement.parentNode.insertBefore(compInput,buttonsList);
						inputForReturn = compInput;
					}else{
						inputForReturn = complementaryInput;
					}
					return inputForReturn;
				}
				var compInput = getComplementaryInput();
				compInput.value = num;
				console.log('инпут: ' + compInput);
				console.log('значение: ' + compInput.value);
			}
			//получаем список всех клеток
			function getAllCells(seatsBlock){
				var cells = [];
				var cell = seatsBlock.firstChild;
				while (cell){
					if (cell.tagName == 'DIV')
						cells.push(cell);
					cell = cell.nextSibling;
				}
				return cells;
			}
			//поиск последних ячеек рядов
			function getLastCellsInRows(){
				var cells = getAllCells(seatsBlock);
				var lastCells = [];
				for (var i = 0; i < cells.length; i++){
					if ((i + 1)%cellQuantity == 0)
						lastCells.push(cells[i]);
				}
				return lastCells;
			}
			//добавляем клетки последовательно одна за одной
			function addCellLinear(cellClassName,numOfRow){
				var cell = document.createElement('DIV');
				seatsBlock.appendChild(cell);
				cell.className = cellClassName;
				cell.numOfRow = numOfRow;
				setMethodToCell(addRemoveCellPopup,'onclick',cell);
				setMethodToCell(removeCellPopup,'removeCellPopup',cell);
				setMethodToCell(addCellPopup,'addCellPopup',cell);
				setMethodToCell(addChangeButtonsToPopup,'addChangeButtonsToPopup',cell);
				setMethodToCell(addChangeToFreeCellButt,'addChangeToFreeCellButt',cell);
				setMethodToCell(addEnterSeatNumButt,'addEnterSeatNumButt',cell);
				setMethodToCell(addChangeToSeatCellButt,'addChangeToSeatCellButt',cell);
				setMethodToCell(addCancelButton,'addCancelButton',cell);
				setMethodToCell(addChangeToDriverButton,'addChangeToDriverButton',cell);
				//	setMethodToCell(removeButtonsFromPopup,'removeButtonsFromPopup',cell);
				return cell;
			}
			// добавляем ячейку после последней ячейки ряда
			function addCellForAddColumnFunc(lastRowCell,numOfRow){
				var cell = document.createElement('DIV');
				//	lastRowCell.parentNode.insertBefore(cell,lastRowCell);
				lastRowCell.parentNode.insertBefore(cell,lastRowCell.nextSibling);
				cell.className = 'cell seat';
				cell.numOfRow = numOfRow;
				setMethodToCell(addRemoveCellPopup,'onclick',cell);
				setMethodToCell(removeCellPopup,'removeCellPopup',cell);
				setMethodToCell(addCellPopup,'addCellPopup',cell);
				setMethodToCell(addChangeButtonsToPopup,'addChangeButtonsToPopup',cell);
				setMethodToCell(addChangeToFreeCellButt,'addChangeToFreeCellButt',cell);
				setMethodToCell(addEnterSeatNumButt,'addEnterSeatNumButt',cell);
				setMethodToCell(addChangeToSeatCellButt,'addChangeToSeatCellButt',cell);
				setMethodToCell(addCancelButton,'addCancelButton',cell);
				setMethodToCell(addChangeToDriverButton,'addChangeToDriverButton',cell);
				//	setMethodToCell(removeButtonsFromPopup,'removeButtonsFromPopup',cell);
			}
			//добавляем ряд
			function addRow(cellQuantity,numOfRow){
				for (var i = 0; i < cellQuantity; i++){
					var cell = addCellLinear('cell seat',numOfRow);
					if (i == 0)
						cell.style.clear = 'both';
				}
			}
			//получаем ячейки последнего ряда
			function getLastRow(seatsBlock){
				var cellsOfLastRow = [];
				var cell = seatsBlock.lastChild;
				while(cell && cell.style.clear != 'both'){
					if (cell.tagName == 'DIV')
						cellsOfLastRow.push(cell);
					cell = cell.previousSibling;
					if (cell.style.clear == 'both')
						cellsOfLastRow.push(cell);
				}
				return cellsOfLastRow;
			}
			//удаляем ячейки последнего ряда
			function removeLastRow(){
				var lastRow = getLastRow(seatsBlock);
				for (var i = 0; i < lastRow.length; i++){
					lastRow[i].parentNode.removeChild(lastRow[i]);
				}
			}
			//добавляем колонку ячеек
			function addColumn(){
				var lastCells = getLastCellsInRows();
				for (var i = 0; i <  lastCells.length; i++){
					addCellForAddColumnFunc(lastCells[i],(i + 1));
				}
			};
			// удаляем ряд ячеек
			function removeColumn(){
				var lastCells = getLastCellsInRows();
				for (var i = 0; i < lastCells.length; i++){
					lastCells[i].parentNode.removeChild(lastCells[i]);
				}
			}
			//изменяет класс тела автобуса. В зависимости от этого класса меняются параметры,
			//такие, как ширина авобуса, габариты частей с изображениями, размер и расположение
			//места водителя (если оно есть)
			function changeBusBodeyClassname(rowQuantity){
				var busBodey  = document.querySelectorAll('.busLayoutBlock .mainBusBlock .busBodey')[0];
				if (busBodey){
					cleanBodeyBusCountRowClasses(busBodeyClasses);
					switch (rowQuantity){
						case 3 :
							busBodey.classList.add('threeRowsOfSeats');
							break;
						case 5 :
							busBodey.classList.add('fiveRowsOfSeats');
							break;
					}
				}
			}
			//изначально на странице присутствует автобус без ячеек
			//функция добавляет ячейки при загрузке страницы в соответствии с
			//изночально заданными значениями их количества
			function firstAddOfSeats(rowQuantity,cellQuantity){
				changeBusBodeyClassname(rowQuantity);
				for (var i = 0; i < rowQuantity; i++){
					addRow(cellQuantity,(i + 1));
				}
			}
//	firstAddOfSeats(rowQuantity,cellQuantity);

			/*-------------- chenge of cell type -----------*/
			var activeCell; //-- клетка с добавленным popup
			//добавление popup клетке или удаление
			function addRemoveCellPopup(){
				if (activeCell != this){
					if (activeCell && activeCell.popup){
						activeCell.removeCellPopup();
					}
					this.addCellPopup();
					this.addChangeButtonsToPopup();
					activeCell = this;
				}else{
					this.removeCellPopup();
					activeCell = null;
				}
			}
			//добавляем popup
			function addCellPopup(){
				var cellPopupPositionBlock = document.createElement('DIV');
				this.appendChild(cellPopupPositionBlock);
				cellPopupPositionBlock.classList.add('cellPopupPositionBlock');
				var cellPopup = document.createElement('DIV');
				cellPopupPositionBlock.appendChild(cellPopup);
				cellPopup.classList.add('cellPopup');
				this.popup = cellPopupPositionBlock;
				this.popupInnerBlock = this.popup.querySelectorAll('.cellPopup')[0];
			}
			//удаляем popup
			function removeCellPopup(){
				this.removeChild(this.popup);
				this.popup = null;
				activeCell = null;
			}
			//задаём метод клетке
			function setMethodToCell(meth,methName,cell){
				cell[methName] = meth;
			}

			//-- executions

			/*-------------- chenge of cell type end -----------*/
			//добавляем кнопку изменения клетки на пустое место
			function addChangeToFreeCellButt(){
				var cell = this;
				//	var numElement = cell.querySelectorAll('.numElement')[0];
				//функция удаления номера с клетки
				function removeCellNum(){
					cell.numElement.parentNode.removeChild(cell.numElement);
					cell.numElement = null;
				}
				// метод изменения клетки на визуально пустую
				function changeToFreeCell(evt){
					evt.stopPropagation(); //отменяем click клетки
					cell.className = 'cell';
					cell.removeCellPopup();
					if (cell.numElement)
						removeCellNum();
					activeCell = null;
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
					addComplementaryInput(cell,'hidden','placeTypes[]','delete');
				}
				var button = document.createElement('DIV');
				this.popupInnerBlock.appendChild(button);
				button.className = 'changeButton changeToFreeCell';
				jQuery(button).click(changeToFreeCell);
			}
			// добавляем кнопку изменения клетки на клетку сидения
			function addChangeToSeatCellButt(){
				var cell = this;
				// метод изменения клетки на клетку сидения
				function changeToSeatCell(evt){
					evt.stopPropagation(); //отменяем click клетки
					cell.className = 'cell seat';
					cell.removeCellPopup();
					activeCell = null;
					if (autoNumbering){
						autoAdditionOfNumElementsTotal();
						addComplementaryInput(cell,'hidden','placeTypes[]',cell.numElement.firstChild.nodeValue);
					}else{
						removeOldCompInput(cell);
					}
				}
				var button = document.createElement('DIV');
				this.popupInnerBlock.appendChild(button);
				button.className = 'changeButton changeToSeat';
				jQuery(button).click(changeToSeatCell);
			}

			//добавляем инпут и кнопку добавления/изменения номера места
			function addEnterSeatNumButt(){
				var cell = this;
				var input = cell.querySelectorAll('input[type = \'text\']')[0];
				function createOrReplaceCellNum(){
					//	numElement = cell.querySelectorAll('.numElement')[0];
					if (cell.numElement){
						var textNodeValue = input.value;
						cell.numElement.firstChild.nodeValue = textNodeValue;
					}else{
						cell.numElement = document.createElement('DIV');
						cell.appendChild(cell.numElement);
						cell.numElement.className = 'numElement';
						var textNodeValue = input.value;
						cell.numElement.appendChild(document.createTextNode(textNodeValue));
					}
					addComplementaryInput(cell,'hidden','placeTypes[]',cell.numElement.firstChild.nodeValue);
				}
				// метод при нажатии на кнопку ok
				function enterSeatNumber(evt){
					evt.stopPropagation();
					createOrReplaceCellNum();
					cell.removeCellPopup();
					console.log('вводим номер');
				}
				// метод, который выполняется при нажатии в поле input
				function inputFunc(evt){
					evt.stopPropagation();
					enterButt.classList.add('visible');
				}
				var enterSeatNumWrapp = document.createElement('DIV');
				this.popupInnerBlock.appendChild(enterSeatNumWrapp);
				enterSeatNumWrapp.className = 'changeButton enterSeatNum';
				var input = document.createElement('INPUT');
				enterSeatNumWrapp.appendChild(input);
				input.type = 'text';
				input.placeholder = '№';
				var enterButt = document.createElement('DIV');
				enterSeatNumWrapp.appendChild(enterButt);
				enterButt.className = 'enterBut';
				enterButt.appendChild(document.createTextNode('Ок'));
				jQuery(enterButt).click(enterSeatNumber);
				jQuery(input).click(inputFunc);
			}

			//добавляем кнопку отмены действий в popap-е
			function addCancelButton(){
				var cell = this;
				//закрываем popup не производя никаких изменений
				function cencelPopup(evt){
					evt.stopPropagation();
					cell.removeCellPopup();
				}
				console.log('добавляем кнопку отмены');
				var cancelButton = document.createElement('DIV');
				this.popupInnerBlock.appendChild(cancelButton);
				cancelButton.className = 'changeButton cancelButton';
				jQuery(cancelButton).click(cencelPopup);
			}
			//добавляем кнопку преобразования клетки в клетку водителя
			function addChangeToDriverButton(){
				var cell = this;
				//функция удаления номера с клетки
				function removeCellNum(){
					cell.numElement.parentNode.removeChild(cell.numElement);
					cell.numElement = null;
				}
				function changeToDriver(evt){
					evt.stopPropagation(); //отменяем click клетки
					cell.className = 'cell driverCell';
					cell.removeCellPopup();
					if (cell.numElement)
						removeCellNum();
					activeCell = null;
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
					addComplementaryInput(cell,'hidden','placeTypes[]','driver');
				}
				var button = document.createElement('DIV');
				this.popupInnerBlock.appendChild(button);
				button.className = 'changeButton changeToDriver';
				button.appendChild(document.createTextNode('В'));
				jQuery(button).click(changeToDriver);
			}
			//метод добавления кнопок управления клетками в popup
			function addChangeButtonsToPopup(){
				var $cell = jQuery(this);
				if ($cell.hasClass('cell') && $cell.hasClass('seat')){
					this.addChangeToFreeCellButt();
					this.addEnterSeatNumButt();
					this.addChangeToDriverButton();
					this.addCancelButton();
				}else if (this.className == 'cell'){
					this.addChangeToSeatCellButt();
					this.addChangeToDriverButton();
					this.addCancelButton();
				}else if ($cell.hasClass('cell') && $cell.hasClass('driverCell')){
					this.addChangeToFreeCellButt();
					this.addChangeToSeatCellButt();
					this.addCancelButton();
				}
			}
			/*-- additional code --*/
			var applyDataFromDB = true;// отвечает за применение шаблона, данные по которому будут получаться из БД
			// если false, открывается стандартный шаблон для заполнения;
			if (applyDataFromDB){
				var templateData = [
					[
						['cell seat', 1],
						['cell'],
						['cell'],
						['cell seat', 6],
						['cell seat', 9],
						['cell seat', 12],
						['cell seat', 15]
					],
					[
						['cell'],
						['cell'],
						['cell'],
						['cell'],
						['cell'],
						['cell'],
						['cell seat', 16],
					],
					[
						['cell'],
						['cell seat', 2],
						['cell seat', 4],
						['cell seat', 7],
						['cell seat', 10],
						['cell seat', 13],
						['cell seat', 17],
					],
					[
						['cell driverCell'],
						['cell seat', 3],
						['cell seat', 5],
						['cell seat', 8],
						['cell seat', 11],
						['cell seat', 14],
						['cell seat', 18],
					],
				];

				rowQuantity = templateData.length;
				cellQuantity = templateData[0].length;
				//получаем номер клетки в общем массиве клеток по её координатам в двумерном массиве;
				function getCellIndexInCellsArray(i,j) {
					return (i * colNumInTempl) + j;
				}
				//изменение значения дополнительного инпута (требование стороннего разработчика);
				/*	function changeComplInputValue(cell,value){
				 function getComplInput(cell){
				 var complInput = cell.getElementsByClassName('complementaryCellInput')[0];
				 return complInput;
				 }
				 var complInput = getComplInput(cell);
				 if (complInput)
				 complInput.value = value;
				 }*/
				//удаляем дополнительный инпут;
				function removeOldCompInput(parent){
					var oldCompInput = parent.getElementsByClassName('complementaryCellInput')[0];
					if (oldCompInput)
						oldCompInput.parentNode.removeChild(oldCompInput);
				}
				//добавляем дополнительный инпут в ячейку (требование стороннего разработчика);
				function addComplementaryInput(parent,type,name,value){
					removeOldCompInput(parent);
					var input = document.createElement('INPUT');
					parent.insertBefore(input,parent.firstChild);

					input.classList.add('complementaryCellInput');

					//	input.style.display = 'none';

					input.type = type;
					input.name = name;
					input.value = value;
				}
				//изменение на свободную клетку;
				function transformToFreeCell(cellIndex,cellClassName){
					var cell = cells[cellIndex];
					cell.className = cellClassName;
					addComplementaryInput(cell,'hidden','placeTypes[]','delete');
				}
				//изменение на клетку сидения с номером;
				function transformToSeatCell(cellIndex,cellClassName,seatNum) {
					var cell = cells[cellIndex];

					cell.className = cellClassName;
					cell.numElement = document.createElement('DIV');
					cell.numElement.className = 'numElement';
					cell.appendChild(cell.numElement);
					const numTextNode = document.createTextNode(seatNum);
					cell.numElement.appendChild(numTextNode);
					addComplementaryInput(cell,'hidden','placeTypes[]', seatNum);
				}
//		function addComplementaryInput(parent,type,name,value,cellType){
				//изменение на клетку водителя;
				function transformToDriver(cellIndex,cellClassName){
					var cell = cells[cellIndex];
					cell.className = cellClassName;
					addComplementaryInput(cell,'hidden','placeTypes[]','driver');
				}
				//изменение класса автобуса;
				function changeBusClassName(rowsCount){
					switch (rowsCount){
						case 3:
							busBodey.className = 'busBodey threeRowsOfSeats';
							break;
						case 4:
							busBodey.className = 'busBodey';
							break;
						case 5:
							busBodey.className = 'busBodey fiveRowsOfSeats';
					}
				}
				// общая функция применения данных для готового шаблона;
				function applyTemplate(templateData){
					changeBusClassName(templateData.length);
					for (var i = 0; i < templateData.length; i++){
						for (var j = 0; j < templateData[i].length; j++){
							var cellIndex = getCellIndexInCellsArray(i,j);
							var cellClassName = templateData[i][j][0];
							switch (cellClassName){
								case 'cell':
									transformToFreeCell(cellIndex,cellClassName);
									break;
								case 'cell seat':
									var seatNum = templateData[i][j][1];
									transformToSeatCell(cellIndex, cellClassName, seatNum);
									break;
								case 'cell driverCell' :
									transformToDriver(cellIndex,cellClassName);
							}
						}
					}
				}
				firstAddOfSeats(rowQuantity,cellQuantity);
				var busBodey  = document.querySelectorAll('.busLayoutBlock .mainBusBlock .busBodey')[0];
				var cells = busBodey.getElementsByClassName('cell');
				var colNumInTempl = templateData[0].length;
				var rowNumInTempl = templateData.length;
				applyTemplate(templateData);
			}else{
				firstAddOfSeats(rowQuantity,cellQuantity);
			}
			/*-- secondary additional code --*/
			var autoNumbering, // включает/отключает автоматическую нумерацию сидений;
				regidNumbering, // включает/отключает жёсткую нумерацию сидений;
				reverseNumbering, // включает/отключает нумерацию рядов снизу;
				manualEnterPlaceNum; // включает/отключает возможность ручного добавления номера места сидения;
			//удаляет числовые блоки со всех клеток сидений;
			function removeNumElementsFromSeatCells(){
				var numElements = document.querySelectorAll('div.numElement');
				for (var i = 0; i < numElements.length; i++){
					numElements[i].parentNode.removeChild(numElements[i]);
				}
			}
			//проверяем целое ли число;
			function isNumberInteger(num){
				if (num%1 == 0)
					return true
				else
					return false;
			}
			//получаем номер ряда, к которому относится ячейка;
			function getNumOfRow(i,cellsCount){
				var serialNum = i + 1;
				var devisionResult = serialNum/cellQuantity;
				var rowNum;
				if (isNumberInteger(devisionResult))
					rowNum = devisionResult
				else
					rowNum = Math.floor(devisionResult + 1);
				return rowNum;
			}
			//получаем номер колонки, к которой относится ячейка;
			function getNumOfCell(i,cellsCount,numOfRow){
				var quantityOfExcessCellsForCalculation = (numOfRow - 1)*cellQuantity;
				var cellNum = i + 1 - quantityOfExcessCellsForCalculation;
				return cellNum;
			}
			//задание координат всем клеткам, информация заносится в свойство объекта клетки;
			function setCoordinatesToCells(){
				var cells = document.querySelectorAll('div.cell');
				var cellsCount = cells.length;
				for (var i = 0; i < cellsCount; i++){
					cells[i].numOfRow = getNumOfRow(i,cellsCount);
					cells[i].numOfCell = getNumOfCell(i,cellsCount,cells[i].numOfRow);
					//	console.log(cells[i].numOfRow + ', '+ cells[i].numOfCell);
				}
			}
			//атоматическое добавление номеров всем местам;
			function autoAdditionOfNumElements(){
				let seats = document.querySelectorAll('.cell');
				let seatCells = document.querySelectorAll('.cell.seat');

				if (regidNumbering) {
					setCoordinatesToCells();
				}

				const reversArr = getReversNumericArray();

				if (!regidNumbering) {
					const result = {};

					seats.forEach((value, index) => {
						const ceil = Math.ceil((index + 1) / cellQuantity);

						if (!result[ceil]) {
							result[ceil] = [];
						}

						result[ceil].push(value);

						return result;
					});

					seats = [];
					const start = reverseNumbering ? rowQuantity : 1;
					const end = reverseNumbering ? 1 : rowQuantity;
					for (let i = 0; i < cellQuantity; i++) {
						for (let j = start; start > end ? (j >= end) : (j <= end); start > end ? j-- : j++) {
							seats.push(result[j][i]);
						}
					}

					seatCells = seats.filter((item) => item.className.indexOf('seat') !== -1 );
				}

				for (let i = 0; i < seatCells.length; i++){
					var numElement = document.createElement('DIV');
					numElement.classList.add('numElement');
					seatCells[i].appendChild(numElement);
					seatCells[i].numElement = numElement;

					let numNode = document.createTextNode(i + 1);

					if (regidNumbering) {
						if (reverseNumbering) {
							numNode = document.createTextNode(reversArr[seatCells[i].numOfRow - 1] + String(seatCells[i].numOfCell - 1));
						} else {
							numNode = document.createTextNode(lettersArray[seatCells[i].numOfRow - 1] + String(seatCells[i].numOfCell - 1));
						}
					}

					numElement.appendChild(numNode);
					//	changeComplInputValue(seatCells[i],numNode.nodeValue);
					//	addComplementaryInput(parent,type,name,value);
					//	addComplementaryInput(cell,'hidden','placeTypes',seatNum);
					addComplementaryInput(seatCells[i],'hidden','placeTypes[]',numNode.nodeValue);
				}
			}
			//атоматическое добавление номеров всем местам - общая функция;
			function autoAdditionOfNumElementsTotal(){
				removeNumElementsFromSeatCells();
				autoAdditionOfNumElements();
				//	console.log(rowQuantity + ', ' + cellQuantity);
			}
			//определение изначального положения переключателей панели настроек. общая функция для всех переключателей;
//	getStartSwitchValue(manEntPlNumYesButt,manEntPlNumNoButt,'manualEnterPlaceNum');
			function getStartSwitchValue(yesButton,noButton,paramForChange){
				if (yesButton.classList.contains('active')){
					if (paramForChange == 'autoNumbering')
						autoNumbering = true
					else if (paramForChange == 'regidNumbering')
						regidNumbering = true
					else if (paramForChange == 'reverseNumbering')
						reverseNumbering = true
					else if (paramForChange == 'manualEnterPlaceNum')
						manualEnterPlaceNum = true;
				}
				else if(noButton.classList.contains('active')){
					if (paramForChange == 'autoNumbering')
						autoNumbering = false
					else if (paramForChange == 'regidNumbering')
						regidNumbering = false
					else if (paramForChange == 'reverseNumbering')
						reverseNumbering = true
					else if (paramForChange == 'manualEnterPlaceNum')
						manualEnterPlaceNum = false;
				}
			}
			//изменение положения переключателя автоматической нумерации при клике;
			function changeAutoNumberingValue(){
				if (!this.classList.contains('active')){
					if (this.classList.contains('yes')){
						autoNumbering = true;
						this.classList.add('active');
						autoNumNoButton.classList.remove('active');
						autoAdditionOfNumElementsTotal();
						// showOptionBlock(regidNumberingPanel);
						showOptionBlock(reverseLetterNubmeringPanel);
					}else if(this.classList.contains('no')){
						this.classList.add('active');
						autoNumbering = false;
						autoNumYesButton.classList.remove('active');
						// changeRegidNumberingValue.call(regidNumNoButton);
						// hideOptionBlock(regidNumberingPanel);
						hideOptionBlock(reverseLetterNubmeringPanel);
					}
				}
			}
			//изменение положения переключателя жёсткой нумерации при клике;
			function changeRegidNumberingValue(){
				if (!this.classList.contains('active')){
					if (this.classList.contains('yes')){
						regidNumbering = true;
						this.classList.add('active');
						regidNumNoButton.classList.remove('active');
						autoAdditionOfNumElementsTotal();
					}else if(this.classList.contains('no')){
						this.classList.add('active');
						regidNumbering = false;
						regidNumYesButton.classList.remove('active');
						autoAdditionOfNumElementsTotal();
					}
				}
			}
			//изменение буквенной нумерации по клику (сверху или снизу);
			function changeReverseNumbering(){
				if (!this.classList.contains('active')){
					if (this.classList.contains('yes')){
						reverseNumbering = true;
						this.classList.add('active');
						reverseLettNumNoButt.classList.remove('active');
						autoAdditionOfNumElementsTotal();
					}else if(this.classList.contains('no')){
						this.classList.add('active');
						reverseNumbering = false;
						reverseLettNumYesButt.classList.remove('active');
						autoAdditionOfNumElementsTotal();
					}
				}
			}
			//Добавление или удаление возможности редактировать номера мест;
			function changeManualEnterPlaceNum(callMethod){
				if (!this.classList.contains('active') || callMethod){
					if (this.classList.contains('yes')){
						manualEnterPlaceNum = true;
						this.classList.add('active');
						manEntPlNumNoButt.classList.remove('active');
						showEnterNumberBlocks();
					}else if(this.classList.contains('no')){
						this.classList.add('active');
						manualEnterPlaceNum = false;
						manEntPlNumYesButt.classList.remove('active');
						hideEnterNumberBlocks();
					}
				}
			}
			//Добавление или удаление возможности редактировать номера мест при загрузке страницы;
			function changeManualEnterPlaceNumOnLoad(){
				if (manEntPlNumYesButt.classList.contains('active'))
					changeManualEnterPlaceNum.call(manEntPlNumYesButt,'callMethod')
				else if (manEntPlNumNoButt.classList.contains('active'))
					changeManualEnterPlaceNum.call(manEntPlNumNoButt,'callMethod')
			}
			//прячем блоки, содержащие инпуты добавления номеров мест;
			function hideEnterNumberBlocks(){
				seatsBlock.classList.add('hiddenNumericBlocks');
			}
			//показываем блоки, содержащие инпуты добавления номеров мест;
			function showEnterNumberBlocks(){
				seatsBlock.classList.remove('hiddenNumericBlocks');
			}
			//прячем переданный в параметр;
			function hideOptionBlock(optionBlock){
				optionBlock.style.display = 'none';
			}
			// показываем переданный в параметр;
			function showOptionBlock(optionBlock){
				optionBlock.style.display = '';
			}
			//получаем реверсивный массив букв;
			function getReversNumericArray(){
				var intermadiateArr = [],
					reversNumericArr = [];
				for (var i = 0; i < rowQuantity; i++){
					intermadiateArr.push(lettersArray[i]);
				}
				for (var j = (intermadiateArr.length - 1); j >= 0; j--){
					reversNumericArr.push(intermadiateArr[j]);
				}
				return reversNumericArr;
			}
			var lettersArray = ['A','B','C','D','E','F','G','H','I','J','K']; // массив букв рядов;
			// var regidNumberingPanel = document.querySelectorAll('.templateOptions .regidNumbering')[0];
			var reverseLetterNubmeringPanel = document.querySelectorAll('.templateOptions .reverseLetterNubmering')[0];
			var autoNumYesButton = document.querySelectorAll('.autoNumbering .yes')[0];
			var autoNumNoButton = document.querySelectorAll('.autoNumbering .no')[0];
			// var regidNumYesButton = document.querySelectorAll('.regidNumbering .yes')[0];
			// var regidNumNoButton = document.querySelectorAll('.regidNumbering .no')[0];
			var reverseLettNumYesButt = document.querySelectorAll('.reverseLetterNubmering .yes')[0];
			var reverseLettNumNoButt = document.querySelectorAll('.reverseLetterNubmering .no')[0];
			var manEntPlNumYesButt = document.querySelectorAll('.manualEnterPlaceNum .yes')[0];
			var manEntPlNumNoButt = document.querySelectorAll('.manualEnterPlaceNum .no')[0];
			autoNumYesButton.onclick = changeAutoNumberingValue;
			autoNumNoButton.onclick = changeAutoNumberingValue;
			// regidNumYesButton.onclick = changeRegidNumberingValue;
			// regidNumNoButton.onclick = changeRegidNumberingValue;
			reverseLettNumYesButt.onclick = changeReverseNumbering;
			reverseLettNumNoButt.onclick = changeReverseNumbering;
			manEntPlNumYesButt.onclick = changeManualEnterPlaceNum;
			manEntPlNumNoButt.onclick = changeManualEnterPlaceNum;
			console.log(autoNumYesButton);
			console.log(autoNumNoButton);
			/*	getStartAutoNumberingValue(autoNumYesButton,autoNumNoButton);
			 getStartRegidNumberingValue(regidNumYesButton,regidNumNoButton);*/
			getStartSwitchValue(autoNumYesButton,autoNumNoButton,'autoNumbering');
			// getStartSwitchValue(regidNumYesButton,regidNumNoButton,'regidNumbering');
			// getStartSwitchValue(reverseLettNumYesButt,reverseLettNumNoButt,'reverseNumbering');
			getStartSwitchValue(manEntPlNumYesButt,manEntPlNumNoButt,'manualEnterPlaceNum');
			changeManualEnterPlaceNumOnLoad();
			/*
			 1 1 1 1
			 2 2 2 2
			 3 3 3 3
			 4 4 4 4
			 */
			var manEntPlNumYesButt = document.querySelectorAll('.manualEnterPlaceNum .yes')[0];
			var manEntPlNumNoButt = document.querySelectorAll('.manualEnterPlaceNum .no')[0];
		}
	}

	initTemplateBus();
	window.initTemplateBus = initTemplateBus;
});/*
 // changeReverseNumbering
 regidNumbering = true, // включает/отключает жёсткую нумерацию сидений;
 reverseNumbering = true;*/













	jQuery(document).ready(function(){
		var pageIndicator = document.getElementsByClassName('shedulePage');
		if (pageIndicator.length > 0){
			rotateBusesTotal();
		}		
	});
	//Общая функция обеспечения визуального поворота. Видна во всех скриптах;
	function rotateBusesTotal(){
		var busBodeyWrapp = document.querySelectorAll('.busBodeyWrapp')[6];
		//определяем количество рядов автобуса по классу;
		function getBusRowsCount(busWrapp){
			var busBodey = busWrapp.querySelectorAll('.busBodey')[0];
			var rowsCount;
			if (busBodey.classList.contains('threeRowsOfSeats')){
				rowsCount = 3;
			}
			else if(busBodey.classList.contains('fiveRowsOfSeats')){
				rowsCount = 5;
			}else{
				rowsCount = 4;
			}
			return rowsCount;
		}
		//получаем количество рядов автобуса;
		function getRowsQuantity(){
			
		}
		//получаем информацию о клетках автобуса
		function getCellsInfo(cells){
			var cellsInfo = [];
			for (var i = 0; i < cells.length; i++){
				var cellInfo = {};
				cellInfo.classList = cells[i].classList;
				var numElement = cells[i].getElementsByClassName('numElement')[0]
				if (numElement && numElement.firstChild)
					cellInfo.num = numElement.firstChild.nodeValue;
				cellsInfo.push(cellInfo);
			}
			return cellsInfo;
		}	
		//получаем информацию об автобусе;
		function getBusInfo(busWrapp){
			var busInfo = {};
			var busInfoT = {
				rowsCount: 3,
				cellsInfo: [
					{classList: 'classList', num: 'num'}
				]
			}			
			var cells = busWrapp.getElementsByClassName('cell');
			busInfo.rowsCount = getBusRowsCount(busWrapp);			
			busInfo.cellsInfo = getCellsInfo(cells);			
			return busInfo;
		}	
		console.log(getBusInfo(busBodeyWrapp));	
	}
	
	
	
	
	
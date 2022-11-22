jQuery(document).ready(function(){	
	var shedulePageBlock = document.querySelectorAll('.item-page.shedulePage')[0];	
	if (shedulePageBlock){
		/* -- showHideBusRows -- */
		// function getReservButton(sheduleRow){
		// 	var reservButton = sheduleRow.querySelectorAll('.reservationButton')[0];
		// 	return reservButton;
		// }
		// function getBusRow(resButton,busRowClass){
		// 	var sheduleRow = resButton.parentNode.parentNode;
		// 	var busRow = sheduleRow.nextSibling;
		// 	while(busRow && !jQuery(busRow).hasClass(busRowClass)){
		// 		busRow = busRow.nextSibling;
		// 		if (jQuery(busRow).hasClass('sheduleRow')){
		// 			busRow = null;
		// 			break;
		// 		}
		// 	}
		// 	return busRow;
		// }
		// function getResButtons(){
		// 	var resButtons = [];
		// 	var sheduleRows = shedulePageBlock.getElementsByClassName('sheduleRow');
		// 	for (var i = 0; i < sheduleRows.length; i++){
		// 		var resButton = getReservButton(sheduleRows[i]);
		// 		if (resButton)
		// 			resButtons.push(resButton);
		// 	}
		// 	return resButtons;
		// }
		// function addPropsAndMethodsToResButtons(resButtons){
		// 	for (var i = 0; i < resButtons.length; i++){
		// 		resButtons[i].onclick = showHideBusRow;
		// 		resButtons[i].busRow = getBusRow(resButtons[i],'busRow');
		// 	}
		// }
		// function showHideBusRow(){
		// 	var $busRow = jQuery(this.busRow);
		// 	if (this.busRow.style.display == 'block' && !this.busRow.style.height){
		// 		$busRow.slideUp(700);
		// 	}else if(this.busRow.style.display == 'none' || this.busRow.style.display == ''){
		// 		$busRow.slideDown(700);
		// 	}
		// }
		// var resButtons = getResButtons();
		// addPropsAndMethodsToResButtons(resButtons);
		/* -- showHideBusRows end -- */
		/* -- change seats Class -- */		//закомментировано по требованию разработчика
	/*	function getFreeSeats(seats){
			var freeSeats = [];
			for (var i = 0; i < seats.length; i++){
				if (!jQuery(seats[i]).hasClass('reserved'))
					freeSeats.push(seats[i]);
			}
			return freeSeats;
		}
		var seats = document.querySelectorAll('div.cell.seat');
		var freeSeats = getFreeSeats(seats);
		if (freeSeats.length > 0){
			function changeSeatClass(){
				var seat = jQuery(this);
				if (seat.hasClass('active'))
					seat.removeClass('active')
				else
					seat.addClass('active');
			}
			for (var i = 0; i < freeSeats.length; i++){
				freeSeats[i].onclick = changeSeatClass;
			}
			console.log(freeSeats);
		}*/
		/* -- change seats Class end -- */
	}
});



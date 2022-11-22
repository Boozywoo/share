	// jQuery(document).ready(function(){
	// 	var buttons = document.querySelectorAll('form#reservations a.reservatButon');
	// 	if (buttons.length > 0){
	// 		function changeDateInscription(inscr){
	// 			var dateInput = document.querySelectorAll('#reservations input.dateInp')[0];
	// 			if (dateInput){
	// 				dateInput.value = inscr;
	// 			}
	// 		}
	// 		function changeActiveClass(){
	// 			if (!this.classList.contains('active')){
	// 				this.classList.add('active');
	// 				clearClassesInElsArr('active',buttons,this);
	// 				if (this.classList.contains('today'))
	// 					changeDateInscription(getDateString());
	// 				else if (this.classList.contains('tomorrow'))
	// 					changeDateInscription(getDateString(true));
	// 			}
	// 		}
	// 		function clearClassesInElsArr(classN,arr,exceptionEl){
	// 			for (var i = 0; i < arr.length; i++){
	// 				if (arr[i] != exceptionEl)
	// 					arr[i].classList.remove(classN);
	// 			}
	// 		}
	// 		for (var i = 0; i < buttons.length; i++){
	// 			buttons[i].onclick = changeActiveClass;
	// 		}
	// 		function getDateString(tomorrow){
	// 			var today = new Date();
	// 			if (tomorrow)
	// 				today.setDate(today.getDate() + 1);
	// 			var day = today.getDate();
	// 			var month = today.getMonth() + 1;
	// 			if (String(month).length < 2)
	// 				month = '0' + month;
	// 			var year = today.getFullYear();
	// 			dateStringArr = [day,month,year];
	// 			return dateStringArr.join('.');
	// 		}
	// 		buttons[0].onclick();
	// 		var dateInp = document.querySelectorAll('.dateInp')[0];
	// 		dateInp.onclick = function(){
	// 			console.log('нажали на' + dateInp);
	// 			('нажали на' + dateInp);
	// 		}
	// 		var select = document.querySelectorAll('.visModWrapp.forRoute')[0];
	// 		select.onclick = function(){
	// 			console.log('нажали на' + select);
	// 		}
	// 	}
	// });
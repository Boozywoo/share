jQuery(document).ready(function(){
	function setShowHideOnClick(button,popup){
		if (button && popup){
			jQuery(button).click(function(){
				if (popup.style.display == 'block'){
					popup.style.display = '';
				}else if (!popup.style.display){
					popup.style.display = 'block';
				}			
			});
		}		
	}
	/*registration in top block*/
	var regButtonInTopBlock = document.querySelectorAll('.registrationContacts .registration .showHideRButton')[0];
	var regPopupInTopBlock = document.querySelectorAll('.registrationContacts .registration .registrationFormWrapperPU')[0]; 	
	if (regButtonInTopBlock && regPopupInTopBlock){
		setShowHideOnClick(regButtonInTopBlock,regPopupInTopBlock);				
	}
	/*registration on order page*/
	var regButtonOnOrderPage = document.querySelectorAll('.mainOrderBlock .registBut')[0];
	var regPopupOnOrderPage = document.querySelectorAll('.mainOrderBlock .registrationFormWrapperPU')[0]; 	
	if (regButtonOnOrderPage && regPopupOnOrderPage){
		setShowHideOnClick(regButtonOnOrderPage,regPopupOnOrderPage);				
	}
	/*enter on order page*/
	var enterButtonOnOrderPage = document.querySelectorAll('.mainOrderBlock .enterBut')[0];
	var enterPopupOnOrderPage = document.querySelectorAll('.mainOrderBlock .entrfFormWrapperPU')[0]; 	
	if (enterButtonOnOrderPage && enterPopupOnOrderPage){
		setShowHideOnClick(enterButtonOnOrderPage,enterPopupOnOrderPage);				
	}
	/*enter in top block*/
	var enterButtonInTopBlock = document.querySelectorAll('.registrationContacts .registration .showHideEntButton')[0];
	var enterPopupInTopBlock = document.querySelectorAll('.registrationContacts .registration .entrfFormWrapperPU')[0]; 	
	if (enterButtonInTopBlock && enterPopupInTopBlock){
		setShowHideOnClick(enterButtonInTopBlock,enterPopupInTopBlock);				
	}	
});


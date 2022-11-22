jQuery(document).ready(function(){			
//закомментиовано по требованию разработчика				
/*	var customerData = document.querySelectorAll('.presonalCabinetMainBlock.personalCabinet .customerData')[0];
	var parentForm, saveChangeButton;
	if (customerData){
		parentForm = customerData.querySelectorAll('form.userData')[0];
		saveChangeButton = customerData.querySelectorAll('.saveChangeButton')[0];		
	}
	if (parentForm && saveChangeButton){
		function getInputs(parentBlock){
			var inputs = [];
			if (parentBlock){
				var input = parentBlock.firstChild;
				while (input){
					if (
						(input.tagName == 'INPUT' && input.disabled && (input.type == 'text' || input.type == 'checkbox'))
						||
						(input.tagName = 'SELECT' && input.disabled)
					)
						inputs.push(input);
					input = input.nextSibling;		
				}
			}		
			return inputs;	
		}		
		function enableInputs(){
			for (var i = 0; i < this.inputs.length; i++){
				if (this.inputs[i] != exception)
					this.inputs[i].removeAttribute('disabled');
			}
		}
		function disableInputs(){
			for (var i = 0; i < this.inputs.length; i++){
				if (this.inputs[i] != exception)
					this.inputs[i].setAttribute('disabled','disabled');							
			}
		}
		function getInputLabel(input,sequence){			
			var sibling = (sequence == 'lebel input') ? 'previousSibling' : 'nextSibling';
			var label = input[sibling];	
			while (label && label.tagName != 'LABEL'){	
				label = label[sibling];
			}		
			return label;
		}
		function getPasswordFromInput(passwordInput){
			return passwordInput.value;
		}
		function showPassword(passwordInput){
			passwordInput.value = saveChangeButton.passwordValue;
		}
		function createSecretString(length){
			var string = '';
			for (var i = 0; i < length; i++){
				string += '*';
			}
			return string;
		}
		function hidePassword(passwordInput){
			var length = getPasswordFromInput(passwordInput).length;
			saveChangeButton.passwordValue = passwordInput.value;
			passwordInput.value = createSecretString(length);
		}
		// определяем свойство display предыдущего элемента input для того, чтобы выставить такое же свойство display
		// у данного элемента. 
		function getPreviousInputDisplayStyle(element){
			var prevInput = element.previousSibling;
			while(prevInput && prevInput.tagName != 'INPUT'){
				prevInput = prevInput.previousSibling;
			}	
			if (prevInput)
				return jQuery(prevInput).css('display');
		}	
		// задаём свойтво display для input в соответствии с аналогичным свойством предыдущего элемента Input;
		//Обычные правила CSS на него действовать не будут, т.к. мы меняли его скриптом через inline;	
		function setDisplayProperty(element){
			var displayProp = getPreviousInputDisplayStyle(element);
			element.style.display = displayProp;	
		}
		function disableEnableInputs(){
			switch (this.position){
				case 'enabled' :
					this.disableInputs();
					saveChangeButton.firstChild.nodeValue = 'Изменить данные';
					jQuery(saveChangeButton).removeClass('save');
					jQuery(saveChangeButton).addClass('change');					
					confirmPassword.style.display = '';
					confirmPasswordLabel.style.display = '';
					hidePassword(password)	
					this.position = 'disabled';
					break;
				default :
					this.enableInputs();
					saveChangeButton.firstChild.nodeValue = 'Сохранить';
					jQuery(saveChangeButton).removeClass('change');
					jQuery(saveChangeButton).addClass('save');
			//		confirmPassword.style.display = 'block';
					confirmPasswordLabel.style.display = 'block';
					setDisplayProperty(confirmPassword);
					showPassword(password)		
					this.position = 'enabled';											
			}				
		} 	
		var exception = document.getElementById('regDate');
		var password = document.getElementById('password');
		var confirmPassword = document.getElementById('confirmPassword');			
		var confirmPasswordLabel = getInputLabel(confirmPassword,'lebel input');				
		var inputs = getInputs(parentForm);
		saveChangeButton.onclick = disableEnableInputs;
		saveChangeButton.inputs = inputs;			
		saveChangeButton.enableInputs = enableInputs;		
		saveChangeButton.disableInputs = disableInputs;	
		hidePassword(password);
	}*/
});




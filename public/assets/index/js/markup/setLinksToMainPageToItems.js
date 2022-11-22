jQuery(document).ready(function(){
	var itemPage = document.querySelectorAll('body > div.item-page')[0];	
	if (itemPage){
		if (!jQuery(itemPage).hasClass('mainPage')){			
			function changeTagOnA(link){
				var linkClassName = link.className;
				var linkInscription = link.firstChild.nodeValue;	
				var parent = link.parentNode;
				parent.removeChild(link);
				var newLinkA = document.createElement('A');
				newLinkA.className = linkClassName;
				parent.appendChild(newLinkA);
				newLinkA.appendChild(document.createTextNode(linkInscription));
				return newLinkA;
			}
			function setAddress(link,address){
				link.setAttribute('href',address);
			}
			function addLinkToLinksArrBySelector(arr,menu,selector,address){
				var link = document.querySelectorAll(selector)[0];
				if (link){
					newLinkA = changeTagOnA(link);
					setAddress(newLinkA,address);
					arr.push(newLinkA);					
				}
			}
			function getItems(){
				var mainMenu = document.querySelectorAll('.topBlock .mainMenu')[0];				
				var items = [];				
				addLinkToLinksArrBySelector(items,mainMenu,'.topMenu span.whyWeLink','/?scrollp=whyWeLink');
				addLinkToLinksArrBySelector(items,mainMenu,'.topMenu span.systemOfDiscountsLink','/?scrollp=systemOfDiscountsLink');
			//	addLinkToLinksArrBySelector(items,mainMenu,'.topMenu span.popularFlightsLink','/index.php/glavnaya/?scrollp=popularFlightsLink');
				addLinkToLinksArrBySelector(items,mainMenu,'.topMenu span.clientsAboutUsLink','/?scrollp=clientsAboutUsLink');			
				return items;										
			}	
			getItems();
		}
	}
});
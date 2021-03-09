

window.addEventListener('DOMContentLoaded', function(){
	let supportButtons=document.getElementsByClassName('moJoom-OauthClient-supportButton-SideButton');
	let supportForms  = document.getElementsByClassName('moJoom-OauthClient-supportForm');
	for(let i=0;i<supportButtons.length;i++){
	supportButtons[i].addEventListener("click",function (e) {
    if (supportForms[i].style.right != "0px") {
        supportForms[i].style.right= "0px";
        
    }
    else {
        supportForms[i].style.right= "-391px";
        
    }
 });
}
//  

let appSearchInput = document.getElementById('moAuthAppsearchInput');
let moAuthAppsTable = document.getElementById('moAuthAppsTable');
let allHtml='';
if(moAuthAppsTable!=null)
	allHtml         = moAuthAppsTable.innerHTML;
let allTds = document.querySelectorAll("#moAuthAppsTable tr td");
noAppFoundStr = '<tr><td>No applications found in this category, matching your search query. Please select a custom application from below OR <b><a href="#" style="cursor:pointer;text-decoration:none;" >Contact Us</a></b> </td></tr>';

if(appSearchInput!=null)
appSearchInput.onkeyup=function(e){
	let j=1;
	let htmlStr='';
	for(let i=0;i<allTds.length;i++)
	{
		if(allTds[i].attributes.moauthappselector.value.search(new RegExp(appSearchInput.value, "i"))!=-1){

			if(j%6==1 || i==allTds.length){
				htmlStr=htmlStr+'<tr>';
			}
			htmlStr = htmlStr+'<td>'+allTds[i].innerHTML+'</td>';
			if(j%6==0){
			 htmlStr=htmlStr+'</tr>'	
			}
			j++;
		}
	}
	if(appSearchInput.value=='')
		moAuthAppsTable.innerHTML=allHtml;
	else if(j==1)
		moAuthAppsTable.innerHTML=noAppFoundStr;
	else
		moAuthAppsTable.innerHTML=htmlStr;
	console.log(htmlStr);
	

	};


}

);


 
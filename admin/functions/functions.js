document.getElementById('toggle_container').style.visibility = "hidden";



function toggleContainer(){ // for post insert or update page. use on radio button
	var toggle = document.getElementById('toggle_container').style.visibility;
	var btn_status = document.getElementById('is_child').checked;
	if(btn_status==true){
		document.getElementById('toggle_container').style.visibility = "visible";
	}
	if(btn_status==false){
		document.getElementById('toggle_container').style.visibility = "hidden";
	}
}
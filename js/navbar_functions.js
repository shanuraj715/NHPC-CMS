document.getElementById('toggle').style.display = 'none';
function show_navbar(){
	var status = document.getElementById('toggle').style.display;
	// console.log("status");
	if(status=='none'){
		document.getElementById('toggle').style.display = 'inline-grid';
	}
	else{
		document.getElementById('toggle').style.display = 'none';
	}
}
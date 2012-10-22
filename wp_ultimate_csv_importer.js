
// Function for add customfield

function addcustomfield(){
	var a = document.getElementById('h1').value;
	var aa = document.getElementById('h2').value;
	for(i=0;i<aa;i++){ 
		var b = document.getElementById('mapping'+i).value;
		if(b=='add_custom'+i){
			document.getElementById('textbox'+i).style.display="";
		}
		else{
			document.getElementById('textbox'+i).style.display="none";
		}
	}
}

// Function for check file exist

function file_exist(){

	if(document.getElementById('csv_import').value==''){
		return false;
	}
	else{
		return true;
	}
}

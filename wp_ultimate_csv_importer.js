function importAllPostStatus(selectedId,headerCount){
	var select;
	var options;
	if(selectedId != 0){
//		document.getElementById('poststatusalert').style.display = 'none';
		for(var u=0;u< headerCount;u++){
			select = document.getElementById('mapping'+u);
			options = select.options;
			for(var o=0;o<options.length;o++){
				if(options[o].value == 'post_status'){
					select.remove(o);
				}
			}
		}
	}
	else{
//		document.getElementById('poststatusalert').style.display = 'block';

		for(var v=0;v<headerCount;v++){
			select = document.getElementById('mapping'+v);
			options = select.options;
			poststatus = 0;
			for(var o=0;o<options.length;o++){
				if(options[o].value == 'post_status')
					poststatus = 1;
			}
			if(poststatus == 0){
				var option=document.createElement("option");
				option.text="post_status";
				select.add(option);
			}

		}
	}//exits post_status show hide
	if(selectedId == 3){
		document.getElementById('postsPassword').style.display = "";
		document.getElementById('passwordlabel').style.display = "";
		document.getElementById('passwordhint').style.display = "";
	}
	else{
		document.getElementById('postsPassword').style.display = "none";
		document.getElementById('passwordlabel').style.display = "none";
		document.getElementById('passwordhint').style.display = "none";
	}
}

// Function for add customfield

function addcustomfield(myval,selected_id){
	var a = document.getElementById('h1').value;
	var aa = document.getElementById('h2').value;
	var selected_value;
	for(var i=0;i<aa;i++){ 
		var b = document.getElementById('mapping'+i).value;
		if(b=='add_custom'+i){
			document.getElementById('textbox'+i).style.display="";
			document.getElementById('customspan'+i).style.display = "";
		}
		else{
			document.getElementById('textbox'+i).style.display="none";	
			document.getElementById('customspan'+i).style.display = "none";

		}
	}
	var header_count = document.getElementById('h2').value;
	for(var j=0;j<header_count;j++){
		var selected_value = document.getElementById('mapping'+j);
		var value1 = selected_value.options[selected_value.selectedIndex].value;
		if(j != selected_id){
			if(myval == value1 && myval != '-- Select --'){
				var selected_dropdown = document.getElementById('mapping'+selected_id);
				selected_dropdown.selectedIndex = '-- Select --';
				if(myval == 'post_date'){
					document.getElementById('date'+selected_id).style.display="none";
				}
				alert(myval+' is already selected!');
			}
		}
	}
}

// Function for check file exist
function file_exist(){

	if(document.getElementById('csv_import').value==''){
		alert('Please attach your csv');
		return false;
	}
	else{
		return true;
	}
}

function import_csv(){
	var header_count = document.getElementById('h2').value;
	var chk_status_in_csv;
	var post_status_msg;
	var error_msg = '';
	post_status_msg = 'Off';
	chk_status_in_csv = document.getElementById('importallwithps').value;
	if(chk_status_in_csv != 0)
		post_status_msg = 'On';
	var array = new Array();
	var val1,val2;
	val1 = val2 = 'Off';
	for(var i=0;i<header_count;i++){
		var e = document.getElementById("mapping"+i);
		var value = e.options[e.selectedIndex].value;
		array[i] = value;
	}
	for(var j=0;j<array.length;j++){
		if(array[j] == 'post_title'){
			val1 = 'On';	
		}
		if(array[j] == 'post_content'){
			val2 = 'On';
		}
		if(post_status_msg == 'Off'){
			if(array[j] == 'post_status')
				post_status_msg = 'On';
		}

	}
	if(val1 == 'On' && val2 == 'On' && post_status_msg == 'On') {
		return true;
	}
	else {
		if(val1 == 'Off')
			error_msg += " post_title,";
		if(val2 == 'Off')
			error_msg += " post_content,";
		if(post_status_msg == 'Off')
			error_msg += " post_status";
	}

	alert('Error: '+error_msg+' are mandatory fields. Please map the fields to proceed.');
	return false;

}

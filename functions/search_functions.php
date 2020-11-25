<?php
function searchData(){
	$search = "";
	if(isset($_GET['s'])){
		if($_GET['s']!=""){
			$search = $_GET['s'];
			$search = validateSearchData($search);
		}
	}
	return $search;
}

function validateSearchData($search){
	global $connection;
	if(strlen($search)<=100){
		$search1 = mysqli_real_escape_string($connection,$search);
		return $search1;
	}
}
?>
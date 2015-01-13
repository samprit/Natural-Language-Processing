<?php
function array_push_assoc($array, $key, $value){
	$array[$key] = $value;
	return $array;

}
array_map('unlink', glob("*.pdf"));
$file_handle = fopen("paper_ids.txt", "rb");
$paper = array();
$paper_ids = array();
$paper_nms = array();
$paper_yr = array();
$paper_lwr = array();
while (!feof($file_handle) ) {

$line_of_text = fgets($file_handle);
$count=0;
try{
	$parts = explode('	', $line_of_text);
	//echo $parts[1] . $parts[2]. "<br>";
	//$paper2 = array($parts[0]=>$parts[1]);
	if(count($parts)==2){
		echo $parts[0]."||".$parts[1].'<br>';
	}
	else{
		$paper_yr[$parts[0]] = $parts[2];
	}
	$paper[$parts[0]] = $parts[1];
	//$paper_yr[$parts[0]] = $parts[2];
	array_push($paper_ids, $parts[0]);
	array_push($paper_nms, $parts[1]);
	array_push($paper_lwr, strtolower($parts[1]));

	//echo $paper_ids[$count]."-----".$paper[$parts[0]]."<br>";
	$count++;
}
catch(Exception $e){
	break;
}
}
fclose($file_handle);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>LitmusDX Tester</title>
<script type="text/javascript" src="js/jquery.js"></script>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="js/bootstrap.js"></script>
<style type="text/css">
body{
	font-family : Courier New, monospace;
	background-image:url("pattern.png");
	background-attachment:fixed;
}
.remove_ele{
	color:black;
	border:solid 10px black; 
}
#page_scrolla{
	margin:0px auto ; 
	width:800px;
	font-size:20px;
	line-height:60px;
	padding-top:50px;
	box-shadow:0px 0px 100px grey;	
	padding:30px;
	min-height :800px;
	background-image:url("box.png");
}
#results{
	padding: 20px;
	line-height: 30px;
	font-size: 15px;
}
h1{
	font-size: 46px;
	font-weight: 900;
	color:grey;
	box-shadow: 0px 0px 100px grey;	
	padding: 20px;
}
</style>
</head>
<body>
	<div id="page_scrolla">
	<h1>Citation Context Analysis</h1>
	<br>
		<form method="post" action="">
		<div class="input-group">
	      <div class="input-group-btn">
	        <button type="submit" name = "srch_type" value = "name" class="btn btn-default "><b>Search by title/keyword</b> <span class="glyphicon glyphicon-search"></span></button>
	       	
	      </div><!-- /btn-group -->
	      <input type="text" name="search" class="form-control">
	      <div class="input-group-btn">
	        <button type="submit" name = "srch_type" value = "year" class="btn btn-default "><b>Search by year</b> <span class="glyphicon glyphicon-search"></span></button>
	       	
	      </div>
	    </div>
	    </form>
	    <div id="results">
		<?php
	    if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			//echo $_POST['srch_type'];
			if($_POST['srch_type']=="name"){
			//echo count($paper)."-------".count($paper_ids)."<br>";
				$matches = preg_grep("/".strtolower($_POST['search'])."/",$paper_lwr);
				echo count($matches)." match(es) found for <i><b>".$_POST['search']."</b></i> ..."."<br><br>";
				for($i=0;$i<count($matches);$i++){
					echo '<form method="post" action="index2.php">';
					echo "<input type='hidden' name = 'paper_id' value = '".$paper_ids[array_keys($matches)[$i]]."'>";
					echo "<input type='hidden' name = 'paper_name' value = '".$paper[$paper_ids[array_keys($matches)[$i]]]."'>";
					echo "<input type='hidden' name = 'paper_year' value = '".$paper_yr[$paper_ids[array_keys($matches)[$i]]]."'>";
					echo "<li>[".$i."]"." ".$paper[$paper_ids[array_keys($matches)[$i]]]." <button type = \"submit\" class=\"btn btn-default\">Generate Plot</button></li><br>";
					echo "</form>";
				}
			}
			else{
				$matches = preg_grep("/".$_POST['search']."/",array_values($paper_yr));
				echo count($matches)." paper(s) found for the year <i><b>".$_POST['search']."</b></i>"."<br><br>";
				for($i=0;$i<count($matches);$i++){
					echo '<form method="post" action="index2.php">';
					echo "<input type='hidden' name = 'paper_id' value = '".$paper_ids[array_keys($matches)[$i]]."'>";
					echo "<input type='hidden' name = 'paper_name' value = '".$paper[$paper_ids[array_keys($matches)[$i]]]."'>";
					echo "<input type='hidden' name = 'paper_year' value = '".array_values($matches)[$i]."'>";
					echo "<li>[".$i."]"." ".$paper[$paper_ids[array_keys($matches)[$i]]]." <button type = \"submit\" class=\"btn btn-default\">Generate Plot</button></li><br>";
					echo "</form>";
				}
			}
		}
		?>
		</div>
	</div>

</body>
</html>

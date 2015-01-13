<?php

$file_handle = fopen("incitations.txt", "rb");
//$paper = array();
$paper_incites = array();
$paper_ids = array();
while (!feof($file_handle) ) {

$line_of_text = fgets($file_handle);
$count=0;
try{
	
	$parts = explode(' ', $line_of_text);
	//echo $parts[1] . $parts[2]. "<br>";
	//$paper2 = array($parts[0]=>$parts[1]);
	if(count($parts)==1){
		$parts[1]="0";
	}
	
	$paper_incites[$parts[0]] = $parts[1];
	array_push($paper_ids, $parts[0]);
	//array_push($paper_nms, $parts[1]);
	//echo $parts[0]."---<br>";
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
<title></title>
<script type="text/javascript" src="js/jquery.js"></script>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="js/bootstrap.js"></script>
<style type="text/css">
body{
	font-family : Courier New, monospace;
	background-image:url("pattern-adtc.png");
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
	font-size: 40px;
	font-weight: 900;
	color:grey;
}
</style>
</head>
<body>
	<div id="page_scrolla">
	<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if(array_key_exists($_POST['paper_id'],$paper_incites)){

			}
			else{
				$paper_incites[$_POST['paper_id']] = "0";
			}
	?>
	<h1><?php echo $_POST['paper_name'] ?></h1>
	<br>
	<h2><?php echo "Number of Citations : ".$paper_incites[$_POST['paper_id']]?></h2>
	<h2><?php echo "Year of Publication : ".$_POST['paper_year']?></h2>

		<?php 
		if($paper_incites[$_POST['paper_id']]=="0"){

		}
		else{
		$file_handle = fopen("Query.txt", "w");
		fwrite($file_handle,$_POST['paper_id']);
		fclose($file_handle);
		exec("C:\Python34\python generate_plot.py",$output,$return);
		//echo $output[0]."<br>";
		exec("C:\Python34\python generate_plot_tags.py",$output,$return);
		//echo $output[0]."<br>";
		//echo $return."<br>";
		?>
		<br>
		<div class="embed-responsive embed-responsive-4by3">
		  <iframe class="embed-responsive-item" src=<?php echo "\"".$_POST['paper_id']."_tags.pdf\""?>></iframe>
		</div>
		<br>
		<div class="embed-responsive embed-responsive-4by3">
		  <iframe class="embed-responsive-item" src=<?php echo "\"".$_POST['paper_id']."_temporal.pdf\""?>></iframe>
		</div>
		<br>	
		<a class = "btn btn-primary btn-lg btn-block" onclick="window.history.back()">Back</a>
		<?php
		}
		}
		else{
			echo 'Error : Please visit index.php';
		}
		?>

	</div>

</body>
</html>

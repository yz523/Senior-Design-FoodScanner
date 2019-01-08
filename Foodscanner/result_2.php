<!DOCTYPE html>
<html>
	<head>
		<title>Result page</title>

	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/stylish-portfolio.css" rel="stylesheet">

	</head>


  <body id="page-top">
  	<?php include("header.php");?>

  	<div class="masthead d-flex">

<?php
	$cos=mysqli_connect("localhost","saikuk","TECIl4^ya$[R");
	mysqli_select_db($cos,"foodscanner_search");

	if(isset($_GET['search'])){

	$get_value = $_GET['user_query'];

	if($get_value==''){

	echo "<center><b>Please go back, and write something in the search box!</b></center>";
	exit();
	}

	$result_query = "select * from sites where site_keywords like '%$get_value%'";

	$run_result = mysqli_query($cos,$result_query);

	if(mysqli_num_rows($run_result)<1){

	echo "<center><b>Oops! sorry, nothing was found in the database!</b></center>";
	exit();

	}

	while($row_result=mysqli_fetch_array($run_result)){

		$site_title=$row_result['site_title'];
		$site_link=$row_result['site_link'];
		$site_desc=$row_result['site_desc'];
		$site_image=$row_result['site_image'];

	echo "<div class='results'>

		<h2>$site_title</h2>
		<a href='$site_link' target='_blank'>$site_link</a>
		<p align='justify'>$site_desc</p>
		<img src='img/$site_image' width='100' height='100' />

		</div>";

		}
}


?>
</div>

</body>
</html>

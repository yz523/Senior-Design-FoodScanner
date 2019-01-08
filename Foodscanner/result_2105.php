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
	mysqli_select_db($cos,"foodscanner_food");

	if(isset($_GET['search'])){

	$get_value = $_GET['user_query'];

	if($get_value==''){

	echo "<center><b>Please go back, and write something in the search box!</b></center>";
	exit();
	}

	$result_query = "select * from foods where food_keywords like '%$get_value%'";

	$run_result = mysqli_query($cos,$result_query);

	if(mysqli_num_rows($run_result)<1){

	echo "<center><b>Oops! sorry, nothing was found in the database!</b></center>";
	exit();

	}
	
	echo "<div class='results_table'>";

	while($row_result=mysqli_fetch_array($run_result)){

		$food_title=$row_result['food_title'];
		$food_link=$row_result['food_link'];
		$food_desc=$row_result['food_desc'];
		$food_image=$row_result['food_image'];
		$serving_size=$row_result['serving_size'];
		$calories=$row_result['calories'];
		$total_fat=$row_result['total_fat'];
		$sodium=$row_result['sodium'];
		$total_carbohydrate=$row_result['total_carbohydrate'];
		$sugars=$row_result['sugars'];
		$protein=$row_result['protein'];
		$calcium=$row_result['calcium'];
		$potassium=$row_result['potassium'];
		

	echo "<div class='results'>
	
		<h2>$food_title</h2>
		<table class='results_table_loop'>
		  <tr>
		  <th>$food_title</th>
		  <th>Serving Size</th>
		  <th>Calories</th>
		  <th>Total fat</th>
		  <th>Sodium</th>
		  <th>Total Carbohydrate</th>
		  <th>Sugars</th>
		  <th>Protein</th>
		  <th>Calcium</th>
		  <th>Potassium</th>
		  </tr>
		  <tr>
		  <td><img src='img/$food_image' width='100' height='100' /></td>
		  <td>$serving_size</td>
		  <td>$calories</td>
		  <td>$total_fat</td>
		  <td>$sodium</td>
		  <td>$total_carbohydrate</td>
		  <td>$sugars</td>
		  <td>$protein</td>
		  <td>$calcium</td>
		  <td>$potassium</td>
		  </tr>
		</table>

		</div>";

		}
	echo "</div>";
}


?>
</div>

</body>
</html>

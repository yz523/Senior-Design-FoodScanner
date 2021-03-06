<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Food Scanner</title>

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


  <div class="container gallery-container">

    <h2>Leg</h2>

<div class="container">
		<p>
			Below is the image of a raw leg.

		</p>
		<p>
			<br> One leg is around 114g (bone removed)<br>

			<div class="dropdown">
              <button onclick="myfunction()" class="dropbtn">Calories 264</button>
                <div id="Drop" class="dropdown-content">
              </div>
            </div>

			<div class="dropdown">
              <button onclick="myfunction()" class="dropbtn">Total Fat 15.3g</button>
                <div id="myDropdown" class="dropdown-content">
                  <a href="#Saturated">Saturated Fat 4.3g</a>
                  <a href="#Polyunsaturated">Polyunsaturated Fat 3.4g</a>
                  <a href="#Monounsaturated">Monounsaturated Fat 6g</a>
              </div>
            </div>
			<div class="dropdown">
              <button onclick="myfunction()" class="dropbtn">Protein 29.6g</button>
                <div id="Dropdown" class="dropdown-content">
              </div>
            </div>

            <div class="dropdown">
              <button onclick="myfunction()" class="dropbtn">Cholestrol 105mg</button>
                <div id="Droping" class="dropdown-content">
              </div>
            </div>
		</p>

		<img src="img/rawleg.jpg" alt="raw leg" width="460" height="345">
	</div>

</div>
    <!-- Navigation -->
    <a class="menu-toggle rounded" href="#">
      <i class="fa fa-bars"></i>
    </a>
    <nav id="sidebar-wrapper">
      <ul class="sidebar-nav">
        <li class="sidebar-brand">
          <a class="js-scroll-trigger" href="#page-top">Food Menu</a>
        </li>
        <li class="sidebar-nav-item">
          <a class="js-scroll-trigger" href="meat.php">Meat</a>
        </li>
        <li class="sidebar-nav-item">
          <a class="js-scroll-trigger" href="vegetables.php">Vegetables</a>
        </li>
        <li class="sidebar-nav-item">
          <a class="js-scroll-trigger" href="fruits.php">Fruits</a>
        </li>
        <li class="sidebar-nav-item">
          <a class="js-scroll-trigger" href="seafood.php">Seafood</a>
        </li>
        </li>
      </ul>
    </nav>
  </div>




  </nav>

<?php include("footer.php");?>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded js-scroll-trigger" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/stylish-portfolio.min.js"></script>

  </body>

</html>

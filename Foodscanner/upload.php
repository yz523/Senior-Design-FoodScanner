<?php
 $connect = mysqli_connect("localhost", "root", "", "testing");
 if(isset($_POST["insert"]))
 {
      $file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
      $query = "INSERT INTO tbl_images(name) VALUES ('$file')";
      if(mysqli_query($connect, $query))
      {
           echo '<script>alert("Image Inserted into Database")</script>';
      }
 }
 ?>
 
<?php /*
 $connect = mysqli_connect("localhost", "root", "", "testing");
 if(isset($_POST["insert"]))
 {
      $file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
      $query = "INSERT INTO tbl_images(name) VALUES ('$file')";
      if(mysqli_query($connect, $query))
      {
           echo '<script>alert("Image Inserted into Database")</script>';
      }
 }
 */?> 

<?php
	if(isset($_POST["insert"]))
	{	
		//convert picture to json
		$file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
		$encoded_image = $file.base64_encode($file);
		$payload = array("image" => $encoded_image);
		$json = json_encode($payload);
		
		//post request
		$url = "http://71.230.118.240:5000/predict";
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		
		//get response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);
		
		$pred = json_decode($res, true);
		//$cat = array_keys($pred, max($pred))[0];

		echo "wtf $pred" ;
	}	
?>
	
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

  </head>


<?php include("header.php");?>
 <div class="masthead d-flex">

<body id="page-top">

           <br /><br />
           <div class="container">
                <h2 align="center">Upload your image</h2>
                <br />
                <form align="center" method="post" enctype="multipart/form-data">
                     <input type="file" name="image" id="image" style="color: white;"/>
                     <br />
                     <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-insert" /><a class="btn" href="food.php">Insert</a>
                </form>



                <br />
                <br />
                <table class="table-upload">
                     <tr>
                         <h2 align="center">Images just uploaded to database</h2>
                     </tr>
                <?php
                $query = "SELECT * FROM tbl_images ORDER BY id DESC";
                $result = mysqli_query($connect, $query);
                while($row = mysqli_fetch_array($result))
                {
                     echo '
                          <tr>
                               <td>
                                    <img src="data:image/jpeg;base64,'.base64_encode($row['name'] ).'" height="200" width="200" class="img-thumnail" />
                               </td>
                          </tr>
                     ';
                }
                ?>
                </table>
           </div>
 </div>

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
 <script>
 $(document).ready(function(){
      $('#insert').click(function(){
           var image_name = $('#image').val();
           if(image_name == '')
           {
                alert("Please Select Image");
                return false;
           }
           else
           {
                var extension = $('#image').val().split('.').pop().toLowerCase();
                if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
                {
                     alert('Invalid Image File');
                     $('#image').val('');
                     return false;
                }
           }
      });
 });
 </script>

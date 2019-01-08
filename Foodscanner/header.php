<?php
session_start();

if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: index.php");
  }
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Please enter username.';
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            //session_start();
                            $_SESSION['username'] = $username;
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- Custom CSS -->
<link href="css/stylish-portfolio.css" rel="stylesheet">
<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet"

    <!-- header -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <div class="logo"><img src="img/logo.png" alt="logo" height="39" width="291"></div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
							<?php  if (isset($_SESSION['username'])) : ?>
								 <li class="nav-item">
             					 <a class="nav-link" href="#">Welcome! <?php echo $_SESSION['username']; ?></a>
            				 </li>

								  <li class="nav-item">
             					 <a class="nav-link" href="index.php?logout='1'">logout</a>
            				 </li>
							<?php else: ?>
                      <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>

      <ul id="login-dp" class="dropdown-menu">
				<li>
					 <div class="row">
							<div class="col-md-12">
								Login via
								<div class="social-buttons">
									<a href="#" class="btn btn-fb"><i class="fa fa-facebook"></i> Facebook</a>
									<a href="#" class="btn btn-tw"><i class="fa fa-twitter"></i> Twitter</a>
								</div>
                                or
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                                        <label>Username</label>
                                        <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                                        <span class="help-block"><?php echo $username_err; ?></span>
                                    </div>
                                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control">
                                        <span class="help-block"><?php echo $password_err; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Login">
										<div class="checkbox">
											 <label>
											 <input type="checkbox"> keep me logged-in
											 </label>
										</div>
								 </form>
								<div class="bottom text-center">
								New here ? <a href="register.php"><b>Join Us</b></a>
							</div>
							</div>

					 </div>
				</li>
			</ul>
        </li>
			  <?php endif ?>
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Home
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="food.php">Food</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="upload.php">Upload</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">FAQ</a>
            </li>
            <li>
                <form action="result.php" method="get" id="search">
                    <input type="text" name="user_query" size="80" placeholder="Enter your food..."/>
                    <input type="submit" name="search" value="Search">
                </form>
            </li>
          </ul>
        </div>
      </div>
    </nav>

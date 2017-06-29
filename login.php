<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>
	<link rel="icon" type="image/jpg" href="img/logo.jpg" />

	<!-- Bootstrap Core CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  
  <!-- Custom CSS -->
  <link href="css/indexshopee-page.css" rel="stylesheet">
  
  <!-- Bootstrap Core Script -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  

</head>

<body id="page-top">

    <nav class="navbar navbar-default">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <a class="navbar-brand" href="index.html"><span class="glyphicon glyphicon-arrow-left"></span></a>
		</div>
		<ul class="nav navbar-nav">
		  <li class="active"><a href="#">Login View</a></li>
		</ul>
	  </div>
	</nav>

<?php
//connect ke database mysql di xampp ('localhost','username','password','database_name')
$db = mysqli_connect('localhost','root','','shopee')
or die('Error connecting to MySQL server.');
		
// define variables and set to empty values
$berhasil = $username = $usernameErr = $passwordErr = $password = "";

$errors ='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	
	//Validation Form
	  if (empty($_POST["username"])) {
		$usernameErr = "username is required";
	  } else {
		$username = test_input($_POST["username"]);
		// check if name only contains letters and numeric
		if (!preg_match("/^[A-Za-z0-9]*$/",$username)) {
		  $usernameErr = "Username field only letters and numeric are allowed"; 
		} //check if length must be between 6-15 character
		else if(strlen($username)<6||strlen($username)>15){
		  $usernameErr = "Username field length must be between 6-15 character"; 
		}
	  }
	  
	  if (empty($_POST["password"])) {
		$passwordErr = "password is required";
	  } else {
		$password = test_input($_POST["password"]);
		// check if length must be between 8-16 character
		if(strlen($password)<8||strlen($password)>16){
		  $passwordErr = "Password field length must be between 8-16 character"; 
		}
	  }


	//Berhasil login	  
    if(($usernameErr == "") && ($passwordErr == "")){
		$query = "SELECT * FROM user where username='$username' and password='$password'";
		mysqli_query($db, $query) or die('Error querying database.');
		
		$result = mysqli_query($db, $query);
		if ($result->num_rows > 0){
			$berhasil = "Berhasil!!! Login";
			
			//Check if user has sign up
			$query = "SELECT * FROM collection where username='$username'";
			mysqli_query($db, $query) or die('Error querying database.');
			$result = mysqli_query($db, $query);
			if ($result->num_rows > 0){ //user has sign up b4
				header( "Location: signupComplete.php" );
			}
			else{ //user not found in collection
				session_start();
				/*session is started if you don't write this line can't use $_Session  global variable*/
				$_SESSION["username"]=$username;
				header( "Location: signup.php" );
			}
		}
		else{
			$berhasil = "<h5 style='color:red'>GAGAL, username belum terdaftar atau salah password.</h5>
						 <a href='userreg.php'> untuk mendaftar silahkan klik link ini &lt--</a> <br>";
		}
		
		mysqli_close($db);
	}
    else{ //gagal login
		$berhasil = "<h5 style='color:red'>GAGAL, ada kesalahan masukan.</h5>";
	  }
 
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


?>

    <!-- Page Content -->
<div class="container">
	<div class="row text-center" >
		<?php echo $berhasil ?>
		<div class="col-lg-12">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data"> 
			<div class="row">
				<div class="col-md-12">
					<!--username-->
					<div class="form-group">
							<input class="form-control" type="text" name="username"  placeholder="Username" required data-validation-required-message="Please enter your username." value="<?php echo $username;?>">
							<p class="help-block text-danger"><?php echo $usernameErr;?></p>
					<!--password-->
							<input class="form-control" type="password" name="password"  placeholder="Password" required data-validation-required-message="Please enter your password." value="<?php echo $password;?>">
							<p class="help-block text-danger"><?php echo $passwordErr;?></p>
					</div>
					
					<button class="btn btn-warning" type="submit" name="submit">Login</button>
				</div>
			</div>
			</form>
			<br><br>
		</div>
	</div>
		
	
</div>

</body>

</html>

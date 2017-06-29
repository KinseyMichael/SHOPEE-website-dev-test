<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registration Page</title>
	<link rel="icon" type="image/png" href="img/logo.jpg" />

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
		  <a class="navbar-brand" href="index.html"><span class="glyphicon glyphicon-arrow-left"></span> | Pendaftaran</a>
		</div>
	  </div>
	</nav>

<?php


//connect ke database mysql di xampp ('localhost','username','password','database_name')
$db = mysqli_connect('localhost','root','','shopee')
or die('Error connecting to MySQL server.');
		
// define variables and set to empty values
$berhasil = $username = $usernameErr = $email = $emailErr = $password = $passwordErr = $password2 = "";

$errors ='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	//Validation Form
	if (empty($_POST["email"])) {
			$emailErr = "Email is required";
		  } else {
			$emailErr = ""; 
			$email = test_input($_POST["email"]);
			// check if e-mail address is well-formed
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			  $emailErr = "Invalid email format"; 
			}
		  }
		  
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
	  
	  if (empty($_POST["password"]) || empty($_POST["password2"])) {
		$passwordErr = "password and confirmation password is required";
	  } else {
		$password = test_input($_POST["password"]);
		$password2 = test_input($_POST["password2"]);
		// check if length must be between 8-16 character
		if(strlen($password)<8||strlen($password)>16){
		  $passwordErr = "Password field length must be between 8-16 character"; 
		}
		else if($password != $password2){
		  $passwordErr = "Password and the confirmation password different"; 
		}
	  }
	  
	  

	//Berhasil login	  
    if(($passwordErr == "") && ($usernameErr == "") && ($emailErr == "")){
		$berhasil = "Berhasil!!! Register";
		
		
		$query = "SELECT * FROM user where username='$username'";
		mysqli_query($db, $query) or die('Error querying database.');
		
		
		$result = mysqli_query($db, $query);
		if ($result->num_rows > 0){ //username sudah terdaftar
			$berhasil = "<h5 style='color:red'>GAGAL, ada username telah terpakai.</h5>";
		}
		else{ //username belum terdaftar
			$query = "INSERT INTO `user` (`username`, `password`, `email`) VALUES ('$username', '$password', '$email');";
			mysqli_query($db, $query) or die('Error querying database.');
			
			$result = mysqli_query($db, $query);
			$berhasil = "Berhasil melakukan Register. <a href='login.php'> Klik ini jika anda tidak dialihkan ke halaman login &lt--</a>";
			
			mysqli_close($db);
			
			// sleep for 5 seconds
			header('Refresh: 2; login.php');
		}
		
		
		

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
					
					<div class="form-group">
						<!--email-->
							<input class="form-control" type="email" name="email"  placeholder="Email" required data-validation-required-message="Please enter your email." value="<?php echo $email;?>">
							<p class="help-block text-danger"><?php echo $emailErr;?></p>
						<!--Username-->
							<input class="form-control" type="text" name="username"  placeholder="Username" required data-validation-required-message="Please enter your username." value="<?php echo $username;?>">
							<p class="help-block text-danger"><?php echo $usernameErr;?></p>
						<!--Password-->
							<input class="form-control" type="password" name="password"  placeholder="Password" required data-validation-required-message="Please enter your password." value="<?php echo $password;?>">
							<p class="help-block text-danger"></p>
						<!--Konfirmasi Password-->
							<input class="form-control" type="password" name="password2"  placeholder="Konfirmasi Password" required data-validation-required-message="Please enter your password again." value="<?php echo $password2;?>">
							<p class="help-block text-danger"><?php echo $passwordErr;?></p>
					</div>
				</div>
				
				<button class="btn btn-warning" type="submit" name="submit">Register</button>
			</div>
			</form>
			<br><br>
		</div>
	</div>
		
	
</div>

</body>

</html>

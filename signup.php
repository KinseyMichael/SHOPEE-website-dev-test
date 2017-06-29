<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Signup Page</title>
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
		  <a class="navbar-brand" href="index.html"><span class="glyphicon glyphicon-arrow-left"></span> | Pendaftaran</a>
		</div>
	  </div>
	</nav>

<?php
//check if user has login
session_start();
//echo $_SESSION['username'];
if(empty($_SESSION['username'])){
		die( ' <h2>Laman ini hanya dapat diakses setelah login</h2>
					<br>
					<p><a href="login.php"> &lt-- Kembali ke halaman login</a></p>'); //menulis "<-- kembali ke hlman login"
}
else{
	$username = $_SESSION['username'];
}

//connect ke database mysql di xampp ('localhost','username','password','database_name')
$db = mysqli_connect('localhost','root','','shopee')
or die('Error connecting to MySQL server.');
		
// define variables and set to empty values
$berhasil = $noktp = $noktpErr = $uploaded_KTP_file = $uploaded_KTP_fileErr = $uploaded_foto_file = $uploaded_foto_fileErr = "";

$errors ='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	
	//Validation Form
	  if (empty($_POST["noktp"])) {
		$noktpErr = "noktp is required";
	  } else {
		$noktp = test_input($_POST["noktp"]);
		// check if name only contains numeric
		if (!preg_match("/^[0-9]*$/",$noktp)) {
		  $noktpErr = "noktp field only numeric are allowed"; 
		} //check if length must be between 6-15 character
		else if(strlen($noktp)!=16){
		  $noktpErr = "noktp field length must be 16 digit long"; 
		}
	  }
	  
	  //setting validasi foto
	  $max_allowed_file_size = 100; // size in KB
	  $allowed_extensions = array("jpg", "jpeg", "JPG", "PNG", "png");
	  
	  //validasi foto
	  if (empty($_FILES['uploaded_foto_file']['name'])){
			$uploaded_foto_fileErr = "photo file is required";
		}
		else{
			//Get the uploaded file information
			$name_of_uploaded_foto_file =  basename($_FILES['uploaded_foto_file']['name']);
			
			//get the file extension of the file
			$type_of_uploaded_file = substr($name_of_uploaded_foto_file, 
									strrpos($name_of_uploaded_foto_file, '.') + 1);
			
			$size_of_uploaded_file = $_FILES["uploaded_foto_file"]["size"]/1024;
			
			if($size_of_uploaded_file > $max_allowed_file_size ) 
			{
				$uploaded_foto_fileErr .= "\n Size of file should be less than $max_allowed_file_size";
			}
			
			//------ Validate the file extension -----
			$allowed_ext = false;
			for($i=0; $i<sizeof($allowed_extensions); $i++) 
			{ 
				if(strcasecmp($allowed_extensions[$i],$type_of_uploaded_file) == 0)
				{
					$allowed_ext = true;		
				}
			}
			
			if(!$allowed_ext)
			{
				$uploaded_foto_fileErr .= "\n The uploaded file is not supported file type. ".
				" Only the following file types are supported: ".implode(',',$allowed_extensions);
			}
		}

		//validasi foto ktp
		if (empty($_FILES['uploaded_KTP_file']['name'])){
			$uploaded_KTP_fileErr = "photo file is required";
		}
		else{
			//Get the uploaded file information
			$name_of_ktp_foto_file =  basename($_FILES['uploaded_KTP_file']['name']);
			
			//get the file extension of the file
			$type_of_uploaded_file = substr($name_of_ktp_foto_file, 
									strrpos($name_of_ktp_foto_file, '.') + 1);
			
			$size_of_uploaded_file = $_FILES["uploaded_KTP_file"]["size"]/1024;
			
			if($size_of_uploaded_file > $max_allowed_file_size ) 
			{
				$uploaded_KTP_fileErr .= "\n Size of file should be less than $max_allowed_file_size";
			}
			
			//------ Validate the file extension -----
			$allowed_ext = false;
			for($i=0; $i<sizeof($allowed_extensions); $i++) 
			{ 
				if(strcasecmp($allowed_extensions[$i],$type_of_uploaded_file) == 0)
				{
					$allowed_ext = true;		
				}
			}
			
			if(!$allowed_ext)
			{
				$uploaded_KTP_fileErr .= "\n The uploaded file is not supported file type. ".
				" Only the following file types are supported: ".implode(',',$allowed_extensions);
			}
		}

	//Berhasil login	  
    if(($noktpErr == "") && ($uploaded_KTP_fileErr == "") && ($uploaded_foto_fileErr == "")){
		$berhasil = "Berhasil!!! mengirimkan data";
		$query = "INSERT INTO `collection` (`username`, `NoKTP`, `filefoto`, `filektp`) VALUES ('$username','$noktp', '$name_of_uploaded_foto_file', '$name_of_ktp_foto_file')";
		mysqli_query($db, $query) or die('Error querying database.');
		
		
		$result = mysqli_query($db, $query);
			$berhasil = "Berhasil!!! Mengirimkan data.";

		unset($_SESSION["username"]);
		session_destroy();
		
		mysqli_close($db);
		
		header( "Location: signupComplete.php" );
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
				<hr>
				<h2>Langkah 1:</h2>
				<hr>
				<div class="col-md-12">
					<!--no ktp-->
					<div class="form-group">
						<label>Masukkan No KTP</label>
							<input class="form-control" type="text" name="noktp"  placeholder="No. KTP" required data-validation-required-message="Please enter your ktp number." value="<?php echo $noktp;?>">
							<p class="help-block text-danger"><?php echo $noktpErr;?></p>
					</div>
					<hr>
				</div>
				<hr>
				<h2>Langkah 2:</h2>
				<hr>
				<p>Foto diri beserta KTP Anda. Nomor KTP dan Wajah Anda harus terlihat jelas dalam foto.</p>
				
					<!--foto diri-->
					<div class="form-group">
						<label>Tambahkan foto Anda</label>
						<input class="form-control" type="file" name="uploaded_foto_file" required data-validation-required-message="Please enter your KTP photo." value="<?php echo $uploaded_foto_file;?>">
							<p class="help-block text-danger"><?php echo $uploaded_foto_fileErr;?></p>
					</div>

					<!--foto ktp-->
					<div class="form-group">
						<label>Tambahkan foto KTP Anda</label>
						<input class="form-control" type="file" name="uploaded_KTP_file" required data-validation-required-message="Please enter your KTP photo." value="<?php echo $uploaded_KTP_file;?>">
						<p class="help-block text-danger"><?php echo $uploaded_KTP_fileErr;?></p>
					</div>
					<hr>
				
				<div class="checkbox">
					<label><input type="checkbox" required>Saya setuju dengan Syarat & Ketentuan program Penjual Terpilih Shopee.</label>
				  </div>
				<button class="btn btn-warning" type="submit" name="submit">Kirimkan</button>
			</div>
			</form>
			<br><br>
		</div>
	</div>
		
	
</div>

</body>

</html>

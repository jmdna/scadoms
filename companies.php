<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="/bootstrap-4.3.1/vendor/bootstrap/css/bootstrap.min.css" >
  <title>SCADOMS - Companies</title>
</head>
<body>
<?php
$page='maintenance';
include('navbar.php');
include('connection.php');
$company_table = "companies";
$company_field_code = "company_code";
$company_field_name = "company_name";
$company_code = $company_name ="";
$company_codeErr = $company_nameErr ="";
$result="";
if (isset($_POST["btnSave"])){
	
	if(empty($_POST["c_code"])){
		$company_codeErr = "Company code required";
	}else{
		$company_code=$_POST["c_code"];
	}

	if(empty($_POST["c_name"])){
		$company_nameErr = "Company name is required";
	}else{
		$company_name=$_POST["c_name"];
    
	}


if (($company_name != '') && ($company_code != '')){
      $link = mysqli_connect($hostname, $username, $password, $dbname);
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
$sql = "INSERT INTO $company_table ($company_field_code, $company_field_name) values ('$company_code','$company_name')";
if(mysqli_query($link, $sql)){
    $result =  "Records inserted successfully.";
    header("Refresh:1; url=home.php");
} else{
    $result = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

mysqli_close($link);
    }
    else{
      echo "insufficient Data!";
    }


}
?>
<style>
	.error{color:Red;}
</style>
<section id="cover" class="min-vh-100">
    <div id="cover-caption">
        <div class="container">
            <div class="row ">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-center form p-4">
                    <h1 class="display-4 py-2 text-truncate">Create Company<BR>Profile</h1>
                    <div class="px-2">
                        <form method="POST" class="justify-content-center">
                            <div class="form-group has-error has-feedback">
                                <input type="text" name="c_code" id="c_code" class="form-control"  placeholder="Company Code" value="<?php echo $company_code; ?>"><span class="error"><?php echo $company_codeErr; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="c_name" id="c_name" class="form-control"  placeholder="Company Name" value = "<?php echo $company_name; ?>"><span class="error"><?php echo $company_nameErr; ?></span>
                            </div>
                            <h1> <?php 
                            if ($result){
                              echo $result;
                              exit;
                            }else{
                              echo $result;

                            }?></h1>
                            <button type="submit" name= "btnSave" class="btn btn-info btn-block">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</form>

	 <script src="bootstrap-4.3.1/js/bootstrap.bundle.js" ></script>
  	<script src="bootstrap-4.3.1/js/bootstrap.js" ></script>
  	<script src="bootstrap-4.3.1/js/bootstrap.min.js" ></script>
  	<script src="bootstrap-4.3.1/js/bootstrap.bundle.min.js" ></script>
  	</body>
</html>
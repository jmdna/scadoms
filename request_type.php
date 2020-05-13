 <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<link rel="stylesheet" href="bootstrap-4.3.1/css/bootstrap.min.css" >
  	<title>SCADOMS - New Request Type</title>
</head>
<body>
<?php
$page='tickets';
include('navbar.php');
$request_type  ="";
$request_typeErr  ="";
$result = "";
$task = "";


include('connection.php');
$request_table = "request_type";
$request_field = "request_desc";

if (isset($_POST["btnSave"])){
	
	if(empty($_POST["request_type"])){
		$request_typeErr = "Request type required";
	}else{
		$request_type=$_POST["request_type"];
    
    $link = mysqli_connect($hostname, $username, $password, $dbname);
    if($link === false){
      die("ERROR: Could not connect. " . mysqli_connect_error());
      }
    $sql = "INSERT INTO $request_table ($request_field) values ('$request_type')";
    if(mysqli_query($link, $sql)){
        $result =  "Records inserted successfully.";
        header("Refresh:1; url=home.php");
    } else{
        $result = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

    mysqli_close($link);
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
                    <h1 class="display-4 py-2 text-truncate">Request Type</h1>
                    <div class="px-2">
                        <form method="POST" class="justify-content-center" >
                            <div class="form-group ">
                                <input type="text" name="request_type" id="request_type" class="form-control"  placeholder="New Type of Request" value="<?php echo $request_type; ?>"><span class="error"><?php echo $request_typeErr; ?></span>
                            </div>
                            <h1> <?php 
                            if ($result){
                              echo $result;
                              exit;
                            }else{
                              echo $result;

                            }?></h1>
                            <button type="submit" name= "btnSave" class="btn btn-info btn-block">Save</button>
                            <input class = "btn btn-secondary btn-block" type="button" name="cancel" value="cancel" onClick="window.location='home.php';" />
</form>
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
  	
</html>
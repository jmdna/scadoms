<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<link rel="stylesheet" href="bootstrap-4.3.1/css/bootstrap.min.css" >
  	<title>Projects</title>
</head>
<body>
<?php
$page='maintenance';
include('navbar.php');
$hostname = "127.0.0.1";
$username = "root";
$dbname = "scadoms";
$password = "admin";
$companies_table = "companies";

$company_name = "company_name";



$con = mysqli_connect($hostname, $username, $password);
mysqli_connect($hostname, $username, $password) OR die("Unable to connect.");
mysqli_select_db($con,$dbname);
$query = "select * from $companies_table";
$result = mysqli_query($con,$query);

$project_code = $project_name = $project_description = $company_code="";
$project_codeErr = $project_nameErr = $project_descriptionErr = $company_codeErr="";


if (isset($_POST["btnSave"])){
	
	if(empty($_POST["project_code"])){
		$project_codeErr = "Project code required";
	}else{
		$project_code=$_POST["project_code"];
	}

	if(empty($_POST["project_name"])){
		$project_nameErr = "Project name is required";
	}else{
		$project_name=$_POST["project_name"];
	}

  if(empty($_POST["project_description"])){
    $project_descriptionErr = "Project description is required";
  }else{
    $project_description=$_POST["project_description"];
  }

  if(empty($_POST["company_code"])){
    $company_codeErr = "Company code is required";
  }else{
    
    $company_code = explode("|",$_POST["company_code"])[0]; 
  }
  

  $link = mysqli_connect($hostname, $username, $password, $dbname);
  if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
  $sql = "INSERT INTO PROJECTS (PROJECT_CODE, PROJECT_NAME, PROJECT_DESC, COMPANY) values ('$project_code','$project_name','$project_description','$company_code')";
  if(mysqli_query($link, $sql)){
    $result =  "Records inserted successfully.";
    header("Refresh:1; url=home.php");
} else{
    $result = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

mysqli_close($link);
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
                    <h1 class="display-4 py-2 text-truncate">Create Project<BR>Profile</h1>
                    <div class="px-2">
                        <form method="POST" class="justify-content-center">
                            <div class="form-group has-error has-feedback">
                                <input type="text" name="project_code" id="project_code" class="form-control"  placeholder="Project Code" value="<?php echo $project_code; ?>"><span class="error"><?php echo $project_codeErr; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="project_name" id="project_name" class="form-control"  placeholder="Project Name" value = "<?php echo $project_name; ?>"><span class="error"><?php echo $project_nameErr; ?></span>
                            </div>
                            <div class="form-group">
                                <textarea  name="project_description" id="project_description" class="form-control" rows="2"  placeholder="Project Description" ><?php echo $project_description; ?></textarea><span class="error"><?php echo $project_descriptionErr; ?></span>
                            </div>
                            <div class="form-group">
                              <select name="company_code" id="company_code" class="form-control" >
                                <option name="company_code" value="" >Select Company Name</option>
                                <?php
                                    if($result){
                                      while($row=mysqli_fetch_array($result)){
                                        $c_name = $row ["$company_name"];
                                        $c_code = $row ['company_code'];
                                        echo "<option> $c_code | $c_name<br></option>";

                                      }
                                    }
                                ?>
                                
                              </select>
                            </div>
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
  	
</html>
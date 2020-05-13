<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<link rel="stylesheet" href="bootstrap-4.3.1/css/bootstrap.min.css" >
  	<title>SCADOMS - New Ticket</title>
</head>
<body>
<?php


require_once('class/PHPMailerAutoload.php');
$page='tickets';
include('navbar.php');
include('connection.php');
if (!preg_match("/Employee/",$_SESSION['user_level'])){ header("Refresh:0,home.php");}
$con = mysqli_connect($hostname, $username, $password);
mysqli_connect($hostname, $username, $password) OR die("Unable to connect.");
mysqli_select_db($con,$dbname);

$tickets_table = "tickets";
$query_tickets = "select * from $tickets_table";
$result_tickets = mysqli_query($con, $query_tickets);
$companies_table = "companies";
$company_field = "company_name";
$company_code_field = "company_code";
$projects_table = "projects";
$projects_name = "project_name";
$request_table = "request_type";
$request_field = "request_desc";
$approver_table = "users";
$approver_field_fn = "first_name";
$approver_field_ln = "last_name";
$con_approver = "user_level";
$query_company = "select * from $companies_table";
$result_company = mysqli_query($con, $query_company);
$query_project = "select * from $projects_table";
$result_project = mysqli_query($con, $query_project);
$query_request = "select * from $request_table";
$result_request = mysqli_query($con, $query_request);
$query_approver = "select * from $approver_table where $con_approver='Approver'";
$result_approver = mysqli_query($con, $query_approver);

$save_status = $ticket_id = $company_name = $buyer_name = $project_name = $unit_code = $request_type = $remarks = $approved_by = "";
$company_nameErr = $buyer_nameErr = $project_nameErr = $unit_codeErr = $request_typeErr = $remarksErr = $approved_byErr = "";

  if (isset($_POST["btnSave"])){
  
  if(empty($_POST["c_name"])){$company_nameErr = "Company Name Required";}else{$company_name=$_POST["c_name"];}
  if(empty($_POST["buyer_name"])){$buyer_nameErr = "Buyer Name Required";}else{$buyer_name=$_POST["buyer_name"];}
  if(empty($_POST["project_name"])){$project_nameErr = "Project Name Required";}else{$project_name=$_POST["project_name"];}
  if(empty($_POST["unit_code"])){$unit_codeErr = "Unit Code Required";}else{$unit_code=$_POST["unit_code"];}
  if(empty($_POST["request_type"])){$request_typeErr = "Request Type Required";}else{$request_type=$_POST["request_type"];}
  if(empty($_POST["remarks"])){$remarksErr = "Remarks Required";}else{$remarks=$_POST["remarks"];}
  if(empty($_POST["approved_by"])){$approved_byErr = "Appprover Required";}else{$approved_by=$_POST["approved_by"];}
  if (($company_name !='') && ($buyer_name !='') && ($project_name !='') && ($unit_code != '') && ($request_type !='') &&  ($remarks !='') && ($approved_by != '')){
   $link = mysqli_connect($hostname, $username, $password, $dbname);
   if($link === false){die("ERROR: Could not connect. " . mysqli_connect_error());}
      if ($result_tickets){
        $row_tickets=mysqli_num_rows($result_tickets);
        $c_code= explode("|", $company_name,2)[0];
        $ticket_id =str_replace(" ","",$c_code."-".date("Y")."-".str_pad($row_tickets+1,4,"0",STR_PAD_LEFT)) ;
        $q_request = "select * from $request_table where request_desc = '$request_type' ";
        $r = mysqli_query($con, $q_request);
        $r_row= mysqli_fetch_array($r);
        $r_type = $r_row["request_code"];
       
        $apprvr_fn = substr("$approved_by",0, strrpos($approved_by,' '));
        $apprvr_ln = substr("$approved_by", (strrpos($approved_by,' ') + 1));
        $a_request = "select * from users where first_name = '$apprvr_fn' and last_name = '$apprvr_ln'";
        $a = mysqli_query($con, $a_request);
        $a_row= mysqli_fetch_array($a);
        $a_type = $a_row["id"];
        $a_email = $a_row["email_address"];
        


      

    $sql = "INSERT INTO $tickets_table (ticket_id, company, buyer, project, unit_code, request_type, requestor, remarks, approver, status, create_date) values ('$ticket_id', '$c_code', '$buyer_name','$project_name',' $unit_code', $r_type, ". $_SESSION['id']. ", '$remarks', $a_type, 'CREATED', '". date('Y-m-d H:i:s')."')";

    }
    if(mysqli_query($link, $sql)){

      //email requestor
      $mail_requestor = new PHPMailer();
      $mail_requestor->isSMTP();
      $mail_requestor->SMTPAuth = true;
      $mail_requestor->SMTPSecure = 'ssl';
      $mail_requestor->Host = 'smtp.gmail.com';
      $mail_requestor->Port = '465';
      $mail_requestor->isHTML();
      $mail_requestor->Username = 'scadoms.system@gmail.com';
      $mail_requestor->Password = '@SCADOMS2020';
      $mail_requestor->setFrom('scadoms.system@gmail.com');
      $mail_requestor->Subject = $ticket_id." Created";
      $mail_requestor->Body= "<h3>Your ticket with ID <u>". $ticket_id."</u> has been <b>created</b>.<br><br>Please click this link to review the filed ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
      $mail_requestor->AddAddress($_SESSION['email_address']);
      if($mail_requestor->Send()){header("Refresh:0,create_ticket.php");} else {echo "Email sending failed...";}

      //email approver       
      $mail_approver = new PHPMailer();
      $mail_approver->isSMTP();
      $mail_approver->SMTPAuth = true;
      $mail_approver->SMTPSecure = 'ssl';
      $mail_approver->Host = 'smtp.gmail.com';
      $mail_approver->Port = '465';
      $mail_approver->isHTML();
      $mail_approver->Username = 'scadoms.system@gmail.com';
      $mail_approver->Password = '@SCADOMS2020';
      $mail_approver->setFrom('scadoms.system@gmail.com');
      $mail_approver->Subject = $ticket_id." For Approval.";
      $mail_approver->Body= "<h3>A ticket with ID <u>". $ticket_id."</u> has been created for your <b>approval</b>.<br><br>Please click this link to review the filed ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
      $mail_approver->AddAddress($a_email);
      if($mail_approver->Send()){header("Refresh:0,create_ticket.php");} else {echo "Email sending failed...";}
    }else{
    $save_status = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    mysqli_close($link);
    
  }
}
}

 ?>
<style>.error{color:Red;}</style>
<section id="cover" class="min-vh-100">
    <div id="cover-caption">
        <div class="container">
            <div class="row ">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-center form p-2">
                    <h2 class="display-5 py-2 text-center">Create New Ticket</h2>
                    <div class="px-2">
                        <form method="POST" class="justify-content-center">
                        <div class="form-group">
                              <select name="c_name" id="c_name" class="form-control" >
                                <option name="c_name" value="" >Select Company Name</option>
                                <?php
                                    if($result_company){
                                      while($row=mysqli_fetch_array($result_company)){
                                        $c_code = $row["$company_code_field"];
                                        $c_name = $row ["$company_field"];
                                        echo "<option>$c_code | $c_name<br></option>";
                                      }
                                     
                                    }
                                ?>
                              </select>
                            </div>
                            <div class="form-group has-error has-feedback">
                                <input type="text" name="buyer_name" id="buyer_name" class="form-control"  placeholder="Buyer's Name" value="<?php echo $buyer_name; ?>"><span class="error"><?php echo $buyer_nameErr; ?></span>
                            </div>
                            <div class="form-group">
                              <select name="project_name" id="project_name" class="form-control" >
                                <option name="project_name" value="" >Project</option>
                                <?php
                                    if($result_project){
                                      while($row_project=mysqli_fetch_array($result_project)){
                                        $p_name = $row_project ["$projects_name"];
                                        echo "<option> $p_name<br></option>";
                                      }
                                    }
                                ?>
                              </select>
                            </div>
                             <div class="form-group">
                                <input type="text" name="unit_code" id="unit_code" class="form-control"  placeholder="Unit Code" value = "<?php echo $unit_code; ?>"><span class="error"><?php echo $unit_codeErr; ?></span>
                            </div>
                            <div class="form-group">
                              <select name="request_type" id="request_type" class="form-control" >
                                <option name="request_type " value="" >Type of Request</option>
                                <?php
                                    if($result_request){
                                      while($row_request=mysqli_fetch_array($result_request)){
                                        $r_type = $row_request ["$request_field"];
                                        echo "<option> $r_type<br></option>";
                                      }
                                    }
                                ?>
                              </select>
                            </div>
                            <div class="form-group">
                                <textarea  name="remarks" id="remarks" class="form-control" rows="3"  placeholder="Remarks" ><?php echo $remarks; ?></textarea><span class="error"><?php echo $remarksErr; ?></span>
                            </div>
                            <div class="form-group">
                              <select name="approved_by" id="approved_by" class="form-control" >
                                <option name="approved_by " value="" >Approved By</option>
                                <?php
                                    if($result_approver){
                                      while($row_approver=mysqli_fetch_array($result_approver)){
                                        $approver_fn = $row_approver["$approver_field_fn"];
                                        $approver_ln = $row_approver["$approver_field_ln"];
                                        echo "<option> $approver_fn $approver_ln</option>";
                                      }
                                    }
                                ?>
                              </select>
                            </div>
                            
                            <button type="submit"  name= "btnSave"  id= "btnSave" class="btn btn-info btn-block" >Save</button>
                            <button type="submit"  name= "btnCancel" class="btn btn-secondary btn-block"
                            >Cancel</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <script src="bootstrap-4.3.1/js/bootstrap.bundle.js" ></script>
  	<script src="bootstrap-4.3.1/js/bootstrap.js" ></script>
  	<script src="bootstrap-4.3.1/js/bootstrap.min.js" ></script>
  	<script src="bootstrap-4.3.1/js/bootstrap.bundle.min.js" ></script>
  	</body>
</html>
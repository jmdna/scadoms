<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<link rel="stylesheet" href="bootstrap-4.3.1/css/bootstrap.min.css" >
  	<title>SCADOMS - <?php echo $_GET['ID']; ?></title>
</head>
<body>

<?php
require_once('class/PHPMailerAutoload.php');
$page='tickets';
include('navbar.php');
include('connection.php');
$ticket_id = $_GET['ID'];
$status = '';
$buttons = '';
$approver_code=$approver_email_add='';

$app_remarks = $app_remarksErr ='';
$con = mysqli_connect($hostname, $username, $password);
mysqli_connect($hostname, $username, $password) OR die("Unable to connect.");
mysqli_select_db($con,$dbname);
$query_approver = "select * from users where user_level='Approver'";
$result_approver = mysqli_query($con, $query_approver);
// $query = "select t.ticket_id, c.company_name, t.buyer, t.project, t.unit_code, r.request_desc, t.remarks, CONCAT(u.first_name,' ', u.last_name) AS 'Approver', t.status, uu.email_address as 'req_email', app.email_address as 'app_email', imp.email_address as 'imp_email' from tickets t inner join companies c on t.company = c.company_code inner join request_type r on t.request_type=r.request_code inner join users u on t.approver = u.id inner join users uu on t.requestor = uu.id inner join users app on t.approver = app.id inner join users imp on t.implementer = imp.id where t.ticket_id='$ticket_id'";
$query = "select t.ticket_id, t.buyer, t.project, t.unit_code, t.remarks, t.status ,r.request_desc, CONCAT(u.first_name,' ', u.last_name) AS 'Approver', uu.email_address as 'req_email' from tickets t inner join request_type r on t.request_type = r.request_code inner join users u on t.approver=u.id inner join users uu on t.requestor = uu.id where t.ticket_id='$ticket_id'";
    $result = mysqli_query($con,$query);
    
//REQUESTOR    
if (isset($_POST["btnRecall"])){
  $link = mysqli_connect($hostname, $username, $password, $dbname);
  $sql = "update tickets set status = 'RECALLED' where ticket_id = '$ticket_id'";
  if(mysqli_query($link, $sql)){
    echo "Record successfully updated!";      
    header("Refresh:0");
  }
}
//APPROVER
if (isset($_POST["btnApprove"])){
  if(empty($_POST["remarks"])){
    $app_remarks = "No Remarks";
  }else{
    $app_remarks=$_POST["remarks"];
  }
    $link = mysqli_connect($hostname, $username, $password, $dbname);
    
    $sql = "update tickets set status = 'APPROVED', approve_remarks=' $app_remarks ', approve_date= '". date('Y-m-d H:i:s')."' where ticket_id = '$ticket_id'";
    if(mysqli_query($link, $sql)){
    echo "Record successfully updated!";
    //email requestor
    $row_email = mysqli_fetch_array($result);
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
      $mail_requestor->Subject = "SCAdOMS Ticket ID: ".$ticket_id." For Approval";
      $mail_requestor->Body= "<h3>Ticket with ID <u>". $ticket_id."</u> has been <b>APPROVED</b>. <br><br>Approver's Remarks:". $app_remarks ." <br>Please click this link to review the filed ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
      $mail_requestor->AddAddress($row_email['req_email']);
      $mail_requestor->AddCC($_SESSION['email_address']);
      if($mail_requestor->Send()){header("Refresh:0,create_ticket.php");} else {echo "Email sending failed...";}
      
      $get_implementer = "Select email_address from users where user_level = 'Administrator - DC Assistant'";
      $imp_i = mysqli_query($con,$get_implementer);
        while ($imp_email = mysqli_fetch_array($imp_i)){
          $mail_implementer = new PHPMailer();
          $mail_implementer->isSMTP();
          $mail_implementer->SMTPAuth = true;
          $mail_implementer->SMTPSecure = 'ssl';
          $mail_implementer->Host = 'smtp.gmail.com';
          $mail_implementer->Port = '465';
          $mail_implementer->isHTML();
          $mail_implementer->Username = 'scadoms.system@gmail.com';
          $mail_implementer->Password = '@SCADOMS2020';
          $mail_implementer->setFrom('scadoms.system@gmail.com');
          $mail_implementer->Subject = "SCAdOMS Ticket ID: ".$ticket_id." For Implementation";
          $mail_implementer->Body= "<h3>Ticket with ID <u>". $ticket_id."</u> has been created for your <b>implementation</b>.  <br><br>Please click this link to review and implement the filed ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
          $mail_implementer->AddAddress($imp_email['email_address']);
          
        if($mail_implementer->Send()){header("Refresh:0,create_ticket.php");} else {echo "Email sending failed...";}
          

        }
    }
}
if (isset($_POST["btnReject"])){
  if(empty($_POST["remarks"])){
    $app_remarks = "No Remarks";
  }else{
    $app_remarks=$_POST["remarks"];
  }
    $link = mysqli_connect($hostname, $username, $password, $dbname);
    
    $sql = "update tickets set status = 'REJECTED', approve_remarks=' $app_remarks ', approve_date= '". date('Y-m-d H:i:s')."' where ticket_id = '$ticket_id'";
    if(mysqli_query($link, $sql)){
    echo "Record successfully updated!";
    //email requestor
      $row_email = mysqli_fetch_array($result);
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
      $mail_requestor->Subject = "SCAdOMS Ticket ID: ".$ticket_id." For Approval";
      $mail_requestor->Body= "<h3>Your ticket with ID <u>". $ticket_id."</u> has been <b>REJECTED</b>. <br><br>Approver's Remarks:". $app_remarks ." <br><br>Please click this link to review the filed ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
      $mail_requestor->AddAddress($row_email['email_address']);
      $mail_requestor->AddCC($_SESSION['email_address']);
      if($mail_requestor->Send()){header("Refresh:0,create_ticket.php");} else {echo "Email sending failed...";}

  }
}

//IMPLEMENTER
if (isset($_POST["btnImplement"])){

  if(empty($_POST["remarks"])){$app_remarks = "No Remarks";}else{$app_remarks=$_POST["remarks"];
  }
    $link = mysqli_connect($hostname, $username, $password, $dbname);
    $sql = "update tickets set status = 'IMPLEMENTED', implement_remarks=' $app_remarks ', implement_date= '". date('Y-m-d H:i:s')."' where ticket_id = '$ticket_id'";
     $result = mysqli_query($con,$query);
     $raw_emails =  mysqli_fetch_array($result);
     $requestor_email = $raw_emails['req_email'];
     $approver_email = $raw_emails['app_email'];
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

      $mail_requestor->Subject = "SCAdOMS Ticket ID: ".$ticket_id." For Implementation";
      $mail_requestor->Body= "<h3>Ticket with ID <u>". $ticket_id."</u> has been <b>IMPLEMENTED</b>. <br><br>Implementer's Remarks:". $app_remarks ." <br><br>Please click this link to review the filed ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
      $mail_requestor->AddAddress($requestor_email);
      $mail_requestor->AddCC($_SESSION['email_address'],'Implementer');
      $mail_requestor->AddCC($approver_email,'Approver');
      if($mail_requestor->Send()){header("Refresh:0,create_ticket.php");} else {echo "Email sending failed...";}

        
        $get_head = "Select email_address from users where user_level = 'Administrator - DC Head'";
        $head_h = mysqli_query($con,$get_head);

        while ($head_email = mysqli_fetch_array($head_h)){
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

        $mail_requestor->Subject = "SCAdOMS Ticket ID: ".$ticket_id." For Confirmation";
        $mail_requestor->Body= "<h3>A Ticket with ID <u>". $ticket_id."</u> has been created for your <b>confirmation</b>.  <br><br>Please click this link to review and confirm implementation of this ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
        $mail_requestor->AddAddress($head_email['email_address']);
        $mail_requestor->AddCC( $requestor_email);
        
        if($mail_requestor->Send()){header("Refresh:0,create_ticket.php");} else {echo "Email sending failed...";} 
      }
    }else
  {echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);}
}
if (isset($_POST["btnDelegate"])){
    if(empty($_POST["approved_by"])){
    $approved_by = "";
  }else{
    $approved_by=$_POST["approved_by"];
  }
    $link = mysqli_connect($hostname, $username, $password, $dbname);
    $apprvr_fn = substr("$approved_by",0, strrpos($approved_by,' '));
        $apprvr_ln = substr("$approved_by", (strrpos($approved_by,' ') + 1));
        $a_request = "select * from users where first_name = '$apprvr_fn' and last_name = '$apprvr_ln'";
        $a = mysqli_query($con, $a_request);
        $a_row= mysqli_fetch_array($a);
        $a_type = $a_row["id"];
        $a_email = $a_row["email_address"];
    $sql = "update tickets set approver = $a_type where ticket_id = '$ticket_id'";
    echo $sql;
    if(mysqli_query($link, $sql)){
      $result = mysqli_query($con,$query);
     $raw_emails =  mysqli_fetch_array($result);
     $requestor_email = $raw_emails['req_email'];
     $approver_email = $raw_emails['app_email'];

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

      $mail_requestor->Subject = "SCAdOMS Ticket ID: ".$ticket_id." For Approval";
      $mail_requestor->Body= "<h3>Your ticket with ID <u>". $ticket_id."</u> has been created for your<b>APPROVAL</b>.  <br><br>Please click this link to review the filed ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
      $mail_requestor->AddAddress($a_email,'Approver');
      if($mail_requestor->Send()){header("Refresh:0");} else {echo "Email sending failed...";}
  }
}
if (isset($_POST["btnRejectI"])){
  if(empty($_POST["remarks"])){
    $app_remarks = "No Remarks";
  }else{
    $app_remarks=$_POST["remarks"];
  }
    $link = mysqli_connect($hostname, $username, $password, $dbname);
    
    $sql = "update tickets set status = 'REJECTED', implement_remarks=' $app_remarks ', implement_date= '". date('Y-m-d H:i:s')."' where ticket_id = '$ticket_id'";
    if(mysqli_query($link, $sql)){
      $result = mysqli_query($con,$query);
     $raw_emails =  mysqli_fetch_array($result);
     $requestor_email = $raw_emails['req_email'];
     $approver_email = $raw_emails['app_email'];

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

      $mail_requestor->Subject = "SCAdOMS Ticket ID: ".$ticket_id." For Implementation";
      $mail_requestor->Body= "<h3>Your ticket with ID <u>". $ticket_id."</u> has been <b>REJECTED</b>. <br><br>Implementer's Remarks:". $app_remarks ." <br><br>Please click this link to review the filed ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
      $mail_requestor->AddAddress($requestor_email);
      $mail_requestor->AddCC($_SESSION['email_address'],'Implementer');
      $mail_requestor->AddCC($approver_email,'Approver');
      if($mail_requestor->Send()){header("Refresh:0,create_ticket.php");} else {echo "Email sending failed...";}
  }
}
//CONFIRMER
if (isset($_POST["btnConfirm"])){

  if(empty($_POST["remarks"])){$app_remarks = "No Remarks";}else{$app_remarks=$_POST["remarks"];
  }
    $link = mysqli_connect($hostname, $username, $password, $dbname);
    $sql = "update tickets set status = 'CONFIRMED', confirm_remarks=' $app_remarks ', confirm_date= '". date('Y-m-d H:i:s')."' where ticket_id = '$ticket_id'";
     $result = mysqli_query($con,$query);
     $raw_emails =  mysqli_fetch_array($result);
     $requestor_email = $raw_emails['req_email'];
     $approver_email = $raw_emails['app_email'];
     $implementer_email = $raw_emails['imp_email'];
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

      $mail_requestor->Subject =  "SCAdOMS Ticket ID: ".$ticket_id." For Confirmation";
      $mail_requestor->Body= "<h3>Ticket with ID <u>". $ticket_id."</u> has been <b>CONFIRMED<b>.<br><br>Confirmer's Remarks:". $app_remarks ." <br><br>Please click this link to review the filed ticket. <br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
      $mail_requestor->AddAddress($requestor_email);
      $mail_requestor->AddCC( $_SESSION['email_address'],'Confirmer');
      $mail_requestor->AddCC($approver_email,'Approver');
      $mail_requestor->AddCC($implementer_email,'Implementer');
      if($mail_requestor->Send()){header("Refresh:0,create_ticket.php");} else {echo "Email sending failed...";}


    }else
  {echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);}
}
if (isset($_POST["btnDelegate"])){
    if(empty($_POST["approved_by"])){
    $approved_by = "";
  }else{
    $approved_by=$_POST["approved_by"];
  }
    $link = mysqli_connect($hostname, $username, $password, $dbname);
    $apprvr_fn = substr("$approved_by",0, strrpos($approved_by,' '));
        $apprvr_ln = substr("$approved_by", (strrpos($approved_by,' ') + 1));
        $a_request = "select * from users where first_name = '$apprvr_fn' and last_name = '$apprvr_ln'";
        $a = mysqli_query($con, $a_request);
        $a_row= mysqli_fetch_array($a);
        $a_type = $a_row["id"];
        $a_email = $a_row["email_address"];
    $sql = "update tickets set approver = $a_type where ticket_id = '$ticket_id'";
    echo $sql;
    if(mysqli_query($link, $sql)){
      $result = mysqli_query($con,$query);
     $raw_emails =  mysqli_fetch_array($result);
     $requestor_email = $raw_emails['req_email'];
     $approver_email = $raw_emails['app_email'];

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

      $mail_requestor->Subject = "SCAdOMS Ticket ID: ".$ticket_id." For Approval";
      $mail_requestor->Body= "<h3>Your ticket with ID <u>". $ticket_id."</u> has been created for your<b>APPROVAL</b>.  <br><br>Please click this link to review the filed ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
      $mail_requestor->AddAddress($a_email,'Approver');
      if($mail_requestor->Send()){header("Refresh:0");} else {echo "Email sending failed...";}
  }
}
if (isset($_POST["btnRejectC"])){
  if(empty($_POST["remarks"])){
    $app_remarks = "No Remarks";
  }else{
    $app_remarks=$_POST["remarks"];
  }
    $link = mysqli_connect($hostname, $username, $password, $dbname);
    
    $sql = "update tickets set status = 'REJECTED', confirm_remarks=' $app_remarks ', confirm_date= '". date('Y-m-d H:i:s')."' where ticket_id = '$ticket_id'";
    $result = mysqli_query($con,$query);
     $raw_emails =  mysqli_fetch_array($result);
     $requestor_email = $raw_emails['req_email'];
     $approver_email = $raw_emails['app_email'];
     $implementer_email = $raw_emails['imp_email'];
    if(mysqli_query($link, $sql)){

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

      $mail_requestor->Subject =  "SCAdOMS Ticket ID: ".$ticket_id." For Confirmation";
      $mail_requestor->Body= "<h3>Ticket with ID ". $ticket_id." has been <b>REJECTED</b>. <br><br>Confirmer's Remarks:". $app_remarks ." <br><br>Please click this link to review the filed ticket.<br>". "http://localhost/SCADOMS/ticket_link.php?ID=$ticket_id</h3>";
      $mail_requestor->AddAddress($requestor_email);
      $mail_requestor->AddCC( $_SESSION['email_address'],'Confirmer');
      $mail_requestor->AddCC($approver_email,'Approver');
      $mail_requestor->AddCC($implementer_email,'Implementer');
      if($mail_requestor->Send()){header("Refresh:0,create_ticket.php");} else {echo "Email sending failed...";}

  }
}
?>





<style>
	.error{color:Red;}
  
</style>

<section id="cover" class="min-vh-500 ">
    <div id="cover-caption">
        <div class="container"> 
          <br/>
                
                <div class="table-responsive col-5 mx-auto ">  
                     <table id="employee_data" class="table  table-bordered" style="width:100%">
                          <?php  
                          while($row = mysqli_fetch_array($result))  
                          {  
                               echo '  
                               <tr> 
                                    <td class="text-white text-right font-weight-bold" style=background-color:#00146F >Ticket ID:</td>   
                                    <td><center>'.$row["ticket_id"].'</td>  
                                </tr>
                                <tr>
                                    <td class=" text-white text-right font-weight-bold" style=background-color:#00146F>Buyer:</td>   
                                    <td><center>'.$row["buyer"].'</td>
                                 </tr>
                                 <tr>
                                    <td class=" text-white text-right font-weight-bold" style=background-color:#00146F>Project:</td>   
                                    <td><center>'.$row["project"].'</td>
                                 </tr>
                                 <tr>
                                    <td class="text-white text-right font-weight-bold "  style=background-color:#00146F>Unit Code:</td>   
                                    <td><center>'.$row["unit_code"].'</td>
                                 </tr>
                                 <tr>
                                    <td class="text-white text-right font-weight-bold" style=background-color:#00146F>Type of Request:</td>   
                                    <td><center>'.$row["request_desc"].'</td>
                                 </tr>
                                 <tr>
                                    <td class="text-white text-right font-weight-bold" style=background-color:#00146F>Remarks:</td>   
                                    <td><center>'.$row["remarks"].'</td>
                                 </tr>
                                 <tr>
                                    <td class="text-white text-right font-weight-bold" style=background-color:#00146F>Approver:</td>   
                                    <td><center>'.$row["Approver"].'</td>
                                 </tr>
                                 <tr>
                                    <td class="text-white text-right font-weight-bold" style=background-color:#00146F>Status:</td>   
                                    <td><center>'.$row["status"].'</td>
                                    
                                 </tr>
                               '; 
                               $status = $row["status"];

                          }

                           
                          ?>  

                     </table> 
                     
                     <div class=" span-6">
    <?php 
    if (preg_match("/Approver/",$_SESSION['user_level'])){
      if ($status== "CREATED"){
        $buttons = '<form method="POST" class="container-fluid-xs"  >
        <div class="form-group">
                                <textarea  name="remarks" id="remarks" class="form-control" rows="3"  placeholder="Remarks" ></textarea><span class="error"><?php echo $remarksErr; ?></span>
                            </div>
                  <div class="row text-center py-2 px-auto">
                      <div class=" col-sm-6  ">
                        <button type="submit" name= "btnApprove" class="btn btn-success col-sm-12" >APPROVE</button>
                      </div>
                      <div class=" col-sm-6">
                        <button type="submit" name= "btnReject" class="btn btn-danger col-sm-12 ">REJECT</button>
                      </div>
                    </div>
                    <div class="row-sm-12">
                    <input class = "btn btn-secondary col-sm-12" type="button" name="cancel" value="CANCEL" onClick="window.location=\'home.php\';" />
                    </div>

                  </form>';
      }else{
         $buttons = '<form method="POST" class="container-fluid-xs"  >
                  <div class="row text-center py-2 px-auto">
                      <div class=" col-sm-6  ">
                        <button type="submit" name= "btnApprove" class="btn btn-success col-sm-12" disabled >APPROVE</button>
                      </div>
                      <div class=" col-sm-6">
                        <button type="submit" name= "btnReject" class="btn btn-danger col-sm-12 " disabled>REJECT</button>
                      </div>
                    </div>
                    <div class="row-sm-12">
                    <input class = "btn btn-secondary col-sm-12" type="button" name="cancel" value="CANCEL" onClick="window.location=\'home.php\';" />
                    </div>
                  </form>';
      }
      
    }elseif (preg_match("/Employee/",$_SESSION['user_level'])){
      if (($status == 'RECALLED') or ($status=='APPROVED') or ($status=='IMPLEMENTED') or ($status=='CONFIRMED')){
      $buttons = '<form method="POST" class=" col-">
                    <button type="submit" name= "btnRecall" class="btn  btn-warning btn-block "disabled>RECALL</button>
                    <input class = "btn btn-secondary btn-block" type="button" name="cancel"  value="CANCEL" onClick="window.location=\'home.php\';" />
                  </form>';
                    
      }else{
      $buttons = '<form method="POST"  class="min-vh-500 " >
                    <button type="submit" name= "btnRecall" class="btn  btn-warning btn-block" >RECALL</button>
                    <input class = "btn btn-secondary btn-block" type="button" name="cancel" value="CANCEL" onClick="window.location=\'home.php\';" />
                  </form>';   
      }
    }elseif (preg_match("/DC Assistant/",$_SESSION['user_level'])){
      if ($status== "APPROVED"){
        $buttons = '<form method="POST" class="container-fluid-xs"  >
        <div class="form-group">
          <textarea  name="remarks" id="remarks" class="form-control" rows="3"  placeholder="Remarks" ></textarea><span class="error"><?php echo $remarksErr; ?></span>
          </div>
          <div class="row text-center py-2 px-auto">
          <div class=" col-sm-6  ">
          <button type="submit" name= "btnImplement" class="btn btn-success col-sm-12" >IMPLEMENT</button>
          </div>
          <div class=" col-sm-6">
          <button type="submit" name= "btnRejectI" class="btn btn-danger col-sm-12 ">REJECT</button>
          </div>
          </div>
          <div class="row-sm-12">
          <input class = "btn btn-secondary col-sm-12" type="button" name="cancel" value="CANCEL" onClick="window.location=\'home.php\';" />
        </div>
        </form>';
      }elseif ($status== "CREATED"){

                        

                            $addedField = '<div class="form-group">
                              <select name="approved_by" id="approved_by" class="form-control" >
                                <option name="approved_by " value="" >Approved By</option> 
                                ';
                                
                          while($row_approver=mysqli_fetch_array($result_approver)){
                            $approver_fn = $row_approver["first_name"];
                            $approver_ln = $row_approver["last_name"];
                            
                                   
                                   $addedField.=' <option>'.$approver_fn.' '. $approver_ln;
                                   $addedField.='</option>';}
                                     $addedField.='</select>
                            </div>';
                                      
                                
                              
         $buttons = '<form method="POST" class="container-fluid-xs"  >
         <div class="row-sm-12 py-2">
                    '.$addedField.'
                    <button type="submit" name= "btnDelegate" class="btn btn-success col-sm-12"  >DELEGATE</button>
                    </div>
                  
                    <div class="row-sm-12">
                    <input class = "btn btn-secondary col-sm-12" type="button" name="cancel" value="CANCEL" onClick="window.location=\'home.php\';" />
                    </div>
                  </form>';
                }else{
         $buttons = '<form method="POST" class="container-fluid-xs"  >
                  <div class="row text-center py-2 px-auto">
                      <div class=" col-sm-6  ">
                        <button type="submit" name= "btnImplement" class="btn btn-success col-sm-12" disabled >IMPLEMENT</button>
                      </div>
                      <div class=" col-sm-6">
                        <button type="submit" name= "btnRejectI" class="btn btn-danger col-sm-12 " disabled>REJECT</button>
                      </div>
                    </div>
                    <div class="row-sm-12">
                    <input class = "btn btn-secondary col-sm-12" type="button" name="cancel" value="CANCEL" onClick="window.location=\'home.php\';" />
                    </div>
                  </form>';
                }
      }elseif (preg_match("/DC Head/",$_SESSION['user_level'])){
      if ($status== "IMPLEMENTED"){
        $buttons = '<form method="POST" class="container-fluid-xs"  >
        <div class="form-group">
                                <textarea  name="remarks" id="remarks" class="form-control" rows="3"  placeholder="Remarks" ></textarea><span class="error"><?php echo $remarksErr; ?></span>
                            </div>
                  <div class="row text-center py-2 px-auto">
                      <div class=" col-sm-6  ">
                        <button type="submit" name= "btnConfirm" class="btn btn-success col-sm-12" >CONFIRM</button>
                      </div>
                      <div class=" col-sm-6">
                        <button type="submit" name= "btnRejectC" class="btn btn-danger col-sm-12 ">REJECT</button>
                      </div>
                    </div>
                    <div class="row-sm-12">
                    <input class = "btn btn-secondary col-sm-12" type="button" name="cancel" value="CANCEL" onClick="window.location=\'home.php\';" />
                    </div>

                  </form>';
      }elseif ($status== "CREATED"){

                        

                            $addedField = '<div class="form-group">
                              <select name="approved_by" id="approved_by" class="form-control" >
                                <option name="approved_by " value="" >Approved By</option> 
                                ';
                                
                          while($row_approver=mysqli_fetch_array($result_approver)){
                            $approver_fn = $row_approver["first_name"];
                            $approver_ln = $row_approver["last_name"];
                            
                                   
                                   $addedField.=' <option>'.$approver_fn.' '. $approver_ln;
                                   $addedField.='</option>';}
                                     $addedField.='</select>
                            </div>';
                                      
                                
                              
         $buttons = '<form method="POST" class="container-fluid-xs"  >
         <div class="row-sm-12 py-2">
                    '.$addedField.'
                    <button type="submit" name= "btnDelegate" class="btn btn-success col-sm-12"  >DELEGATE</button>
                    </div>
                  
                    <div class="row-sm-12">
                    <input class = "btn btn-secondary col-sm-12" type="button" name="cancel" value="CANCEL" onClick="window.location=\'home.php\';" />
                    </div>
                  </form>';
                }else{
         $buttons = '<form method="POST" class="container-fluid-xs"  >
                  <div class="row text-center py-2 px-auto">
                      <div class=" col-sm-6  ">
                        <button type="submit" name= "btnConfirm" class="btn btn-success col-sm-12" disabled >CONFIRM</button>
                      </div>
                      <div class=" col-sm-6">
                        <button type="submit" name= "btnRejectC" class="btn btn-danger col-sm-12 " disabled>REJECT</button>
                      </div>
                    </div>
                    <div class="row-sm-12">
                    <input class = "btn btn-secondary col-sm-12" type="button" name="cancel" value="CANCEL" onClick="window.location=\'home.php\';" />
                    </div>
                  </form>';
      }
    
  }
echo $buttons;?>

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
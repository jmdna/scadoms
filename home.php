<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SCADOMS - Home</title>
  <link href="bootstrap-4.3.1/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php
$page='home'; 
include("navbar.php"); 

$user_id = $_SESSION["id"];
$connect = mysqli_connect("127.0.0.1", "root", "admin", "scadoms");  

if ($_SESSION['user_level']=='Employee'){
  $query ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.requestor = $user_id and t.status in ('APPROVED', 'IMPLEMENTED', 'CONFIRMED') ";  
  $result = mysqli_query($connect, $query); 
  if (mysqli_num_rows ($result)>0){
    echo '
      <script>
        $(document).ready(function(){
        $("#empModal").modal(\'show\');
        });
      </script>';
  }
    }
elseif ($_SESSION['user_level']=='Approver'){
  $query_App ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.approver = $user_id and t.status = 'CREATED' ";  
  $result_App = mysqli_query($connect, $query_App);
  if (mysqli_num_rows ($result_App)>0){
    echo '
      <script>
        $(document).ready(function(){
        $("#appModal").modal(\'show\');
        });
      </script>';
  }
    }
elseif (preg_match("/DC Assistant/",$_SESSION['user_level'])){
 $query_Imp ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.status ='APPROVED' ";  
  $result_Imp = mysqli_query($connect, $query_Imp);
  if (mysqli_num_rows ($result_Imp) >0 ){
    echo '
      <script>
        $(document).ready(function(){
        $("#assModal").modal(\'show\');
        });
      </script>';
  }
  }
elseif (preg_match("/DC Head/",$_SESSION['user_level'])){
 $query_Conf ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.status ='IMPLEMENTED' ";  
  $result_Conf = mysqli_query($connect, $query_Conf);
  if (mysqli_num_rows ($result_Conf) >0 ){
    echo '
      <script>
        $(document).ready(function(){
        $("#headModal").modal(\'show\');
        });
      </script>';
  }
  }
?>
</head>

<body>
<?php 
  $connect = mysqli_connect("127.0.0.1", "root", "admin", "scadoms");  
  $query ="select t.ticket_id, c.company_name, t.buyer, t.project, t.unit_code, r.request_desc, t.remarks, CONCAT(u.first_name,' ', u.last_name) AS 'Approver', t.status from tickets t inner join companies c on t.company = c.company_code inner join request_type r on t.request_type=r.request_code inner join users u on t.approver = u.id where t.approver = $user_id";  
  $result = mysqli_query($connect, $query);  

//landing page   
if($_SESSION['user_level']=='Approver'){
    $query ="select t.ticket_id, c.company_name, t.buyer, t.project, t.unit_code, r.request_desc, t.remarks, CONCAT(u.first_name,' ', u.last_name) AS 'Approver', t.status from tickets t inner join companies c on t.company = c.company_code inner join request_type r on t.request_type=r.request_code inner join users u on t.approver = u.id where t.approver = $user_id and status in ('IMPLEMENTED','CONFIRMED')";  
    $result = mysqli_query($connect, $query); 
    $query_App ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.approver = $user_id and t.status ='APPROVED' ";  
    $result_App = mysqli_query($connect, $query_App);
    if (mysqli_num_rows($result)>0){
           echo 
           '<div class="container">  
              <div class = "row " >
                <div class="col "><br><h3>List of Implemented/Confirmed Tickets as of '. date("M d, Y").'</h3><br>  
                  <div class="table-responsive">  
                      <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                        <thead >  
                          <tr>  
                            <td><center>Ticket ID</td>  
                            <td><center>Company Name</td>  
                            <td><center>Buyer</td>  
                            <td><center>Project</td>  
                            <td><center>Status</td>
                          </tr>  
                        </thead>';                 
                        while($row = mysqli_fetch_array($result))  
                        {  
                          echo '
                            <tr>  
                              <td>'.$row['ticket_id'].'</td>  
                              <td>'.$row["company_name"].'</td>  
                              <td>'.$row["buyer"].'</td>  
                              <td>'.$row["project"].'</td>  
                              <td>'.$row["status"].'</td>
                            </tr>';}  
                          echo '
                      </table>  
                  </div>
                </div>
              </div>
            </div>';}else{
          echo '
          <div class="container">
            <div class = "row " >
              <div class ="col-md-auto"><br><h3>No Implemented/Confirmed tickets yet.</h3>
              </div>
            </div>
          </div>';
        }
            if (mysqli_num_rows($result_App)>0){
             echo ' 
            <div class="container">
              <div class = "row">
                <div class ="col"><br><h3>Pending tickets</h3>
                  <div class="table-responsive">  
                      <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                        <thead >  
                          <tr>  
                            <td><center>Ticket ID</td>
                            <td><center>Company Name</td>  
                            <td><center>Buyer</td>  
                            <td><center>Project</td>  
                            <td><center>Status</td>  
                          </tr>  
                        </thead>';                 
                        while($row_p = mysqli_fetch_array($result_App))  
                        {  
                          echo '
                            <tr>  
                              <td>'.$row_p['ticket_id'].'</td>  
                              <td>'.$row_p["company_name"].'</td>  
                              <td>'.$row_p["buyer"].'</td>  
                              <td>'.$row_p["project"].'</td>  
                              <td>'.$row_p["status"].'</td>
                            </tr>';}  
                          echo '
                      </table>  
                  </div>
                </div>
              </div>
            </div>';}else{
          echo '
          <div class="container">
            <div class = "row " >
              <div class ="col-md-auto"><br><h3>No Pending tickets for Approval yet.</h3>
              </div>
            </div>
          </div>';
        }
          }
if($_SESSION['user_level']=='Employee'){
  $query ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.requestor = $user_id and t.status ='APPROVED'";  
  $result = mysqli_query($connect, $query);  
  $query_pending ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.requestor = $user_id and t.status ='CREATED'";  
  $result_pending = mysqli_query($connect, $query_pending);
  if (mysqli_num_rows($result)>0){
   echo 
   '<div class="container">  
      <div class = "row " >
        <div class="col "><br><h3>List of Tickets as of '. date("M d, Y").'</h3><br>  
          <div class="table-responsive">  
              <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                <thead >  
                  <tr>  
                    <td><center>Ticket ID</td>  
                    <td><center>Company Name</td>  
                    <td><center>Buyer</td>  
                    <td><center>Project</td>  
                    <td><center>Status</td>
                  </tr>  
                </thead>';                 
                while($row = mysqli_fetch_array($result))  
                {  
                  echo '
                    <tr>  
                      <td>'.$row['ticket_id'].'</td>  
                      <td>'.$row["company_name"].'</td>  
                      <td>'.$row["buyer"].'</td>  
                      <td>'.$row["project"].'</td>  
                      <td>'.$row["status"].'</td>
                    </tr>';}  
                  echo '
              </table>  
          </div>
        </div>
      </div>
    </div>';}else{
  echo '
  <div class="container">
    <div class = "row " >
      <div class ="col-md-auto"><br><h3>No Approved tickets yet.</h3>
      </div>
    </div>
  </div>';
  }
  if (mysqli_num_rows($result_pending)>0){

      echo '
      <div class="container">
        <div class = "row">
          <div class ="col"><br><h3>Pending tickets</h3>
            <div class="table-responsive">  
                <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                  <thead >  
                    <tr>  
                      <td><center>Ticket ID</td>
                      <td><center>Company Name</td>  
                      <td><center>Buyer</td>  
                      <td><center>Project</td>  
                      <td><center>Status</td>  
                    </tr>  
                  </thead>';                 
                  while($row_p = mysqli_fetch_array($result_pending))  
                  {  
                    echo '
                      <tr>  
                        <td>'.$row_p['ticket_id'].'</td>  
                        <td>'.$row_p["company_name"].'</td>  
                        <td>'.$row_p["buyer"].'</td>  
                        <td>'.$row_p["project"].'</td>  
                        <td>'.$row_p["status"].'</td>
                      </tr>';}  
                    echo '
                </table>  
            </div>
          </div>
        </div>
      </div>';}else{
    echo '
    <div class="container">
      <div class = "row " >
        <div class ="col-md-auto"><br><h3>No Pending tickets for Approval yet.</h3>
        </div>
      </div>
    </div>';}
              }
if(preg_match("/DC Assistant/",$_SESSION['user_level'])){
  $query ="select t.ticket_id, c.company_name, t.buyer, t.project, t.unit_code, r.request_desc, t.remarks, CONCAT(u.first_name,' ', u.last_name) AS 'Approver', t.status from tickets t inner join companies c on t.company = c.company_code inner join request_type r on t.request_type=r.request_code inner join users u on t.approver = u.id where  t.status = 'CONFIRMED'";  
  $result = mysqli_query($connect, $query); 
  $query_Imp ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.status ='APPROVED' ";  
  $result_Imp = mysqli_query($connect, $query_Imp);
      if (mysqli_num_rows($result)>0){
         echo 
         '<div class="container">  
            <div class = "row " >
              <div class="col "><br><h3>List of Tickets as of '. date("M d, Y").'</h3><br>  
                <div class="table-responsive">  
                    <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                      <thead >  
                        <tr>  
                          <td><center>Ticket ID</td>  
                          <td><center>Company Name</td>  
                          <td><center>Buyer</td>  
                          <td><center>Project</td>  
                          <td><center>Status</td>
                        </tr>  
                      </thead>';                 
                      while($row = mysqli_fetch_array($result))  
                      {  
                        echo '
                          <tr>  
                            <td>'.$row['ticket_id'].'</td>  
                            <td>'.$row["company_name"].'</td>  
                            <td>'.$row["buyer"].'</td>  
                            <td>'.$row["project"].'</td>  
                            <td>'.$row["status"].'</td>
                          </tr>';}  
                        echo '
                    </table>  
                </div>
              </div>
            </div>
          </div>';}else{
          echo '
          <div class="container">
            <div class = "row " >
              <div class ="col-md-auto"><br><h3>No Confirmed tickets yet.</h3>
              </div>
            </div>
          </div>';
        }
          if (mysqli_num_rows ($result_Imp)>0){

            echo '

          <div class="container">
            <div class = "row">
              <div class ="col"><br><h3>Pending tickets</h3>
                <div class="table-responsive">  
                    <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                      <thead >  
                        <tr>  
                          <td><center>Ticket ID</td>
                          <td><center>Company Name</td>  
                          <td><center>Buyer</td>  
                          <td><center>Project</td>  
                          <td><center>Status</td>  
                        </tr>  
                      </thead>';                 
                      while($row_p = mysqli_fetch_array($result_Imp))  
                      {  
                        echo '
                          <tr>  
                            <td>'.$row_p['ticket_id'].'</td>  
                            <td>'.$row_p["company_name"].'</td>  
                            <td>'.$row_p["buyer"].'</td>  
                            <td>'.$row_p["project"].'</td>  
                            <td>'.$row_p["status"].'</td>
                          </tr>';}  
                        echo '
                    </table>  
                </div>
              </div>
            </div>
          </div>';}else{
          echo '
          <div class="container">
            <div class = "row " >
              <div class ="col-md-auto"><br><h3>No Pending tickets for Implementation yet.</h3>
              </div>
            </div>
          </div>';
        }
        }
if(preg_match("/DC Head/",$_SESSION['user_level'])){

  $query_Conf ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.status ='IMPLEMENTED' ";  
  $result_Conf = mysqli_query($connect, $query_Conf);
    
          if (mysqli_num_rows ($result_Conf)>0){

            echo '

          <div class="container">
            <div class = "row">
              <div class ="col"><br><h3>Pending tickets</h3>
                <div class="table-responsive">  
                    <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                      <thead >  
                        <tr>  
                          <td><center>Ticket ID</td>
                          <td><center>Company Name</td>  
                          <td><center>Buyer</td>  
                          <td><center>Project</td>  
                          <td><center>Status</td>  
                        </tr>  
                      </thead>';                 
                      while($row_p = mysqli_fetch_array($result_Conf))  
                      {  
                        echo '
                          <tr>  
                            <td>'.$row_p['ticket_id'].'</td>  
                            <td>'.$row_p["company_name"].'</td>  
                            <td>'.$row_p["buyer"].'</td>  
                            <td>'.$row_p["project"].'</td>  
                            <td>'.$row_p["status"].'</td>
                          </tr>';}  
                        echo '
                    </table>  
                </div>
              </div>
            </div>
          </div>';}else{
          echo '
          <div class="container">
            <div class = "row " >
              <div class ="col-md-auto"><br><h3>No Pending tickets for Confirmation yet.</h3>
              </div>
            </div>
          </div>';
        }
        }
//Employee Modal
  $query ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.requestor = $user_id and t.status in ('APPROVED', 'IMPLEMENTED', 'CONFIRMED') ";  
  $result = mysqli_query($connect, $query); 
  echo '<div class="modal fade bd-example-modal-lg" tabindex="-1" id ="empModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" center>Great News! Here are your ticket updates</h4>
              <button type="button" class="close" data-dismiss="modal">X</button>
            </div>
          <div class="modal-body">
            <div class="container">
              <div class = "row">
                <div class ="col">
                  <div class="table-responsive">  
                    <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                      <thead >  
                        <tr>  
                          <td><center><b>Ticket ID</td>
                          <td><center><b>Company Name</td>  
                          <td><center><b>Buyer</td>  
                          <td><center><b>Project</td>  
                          <td><center><b>Status</td>  
                        </tr>  
                      </thead>';                 
                      while($row = mysqli_fetch_array($result)){  
                      echo '
                        <tr>  
                          <td>'.$row['ticket_id'].'</td>  
                          <td>'.$row["company_name"].'</td>  
                          <td>'.$row["buyer"].'</td>  
                          <td>'.$row["project"].'</td>  
                          <td>'.$row["status"].'</td>
                        </tr>';}  
                      echo '
                    </table>  
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button  type="button" class="btn btn-secondary" onclick="window.location=\'view_ticket.php\'" data-dismiss="modal">View Tickets</button>
        </div>
      </div>
      </div>
    </div>';
//Approver Modal
  $query_App ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where  t.status ='CREATED' ";  
  $result_App = mysqli_query($connect, $query_App);
  echo '<div class="modal fade bd-example-modal-lg" tabindex="-1" id ="appModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" center>Here are the tickets asking for approval.</h4>
              <button type="button" class="close" data-dismiss="modal">X</button>
            </div>
          <div class="modal-body">
            <div class="container">
              <div class = "row">
                <div class ="col">
                  <div class="table-responsive">  
                    <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                      <thead >  
                        <tr>  
                          <td><center><b>Ticket ID</td>
                          <td><center><b>Company Name</td>  
                          <td><center><b>Buyer</td>  
                          <td><center><b>Project</td>  
                          <td><center><b>Status</td>  
                        </tr>  
                      </thead>';                 
                      while($row = mysqli_fetch_array($result_App)){  
                      echo '
                        <tr>  
                          <td>'.$row['ticket_id'].'</td>  
                          <td>'.$row["company_name"].'</td>  
                          <td>'.$row["buyer"].'</td>  
                          <td>'.$row["project"].'</td>  
                          <td>'.$row["status"].'</td>
                        </tr>';}  
                      echo '
                    </table>  
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button  type="button" class="btn btn-secondary" onclick="window.location=\'view_ticket.php\'" data-dismiss="modal">View Tickets</button>
        </div>
      </div>
      </div>
    </div>'; 
//Assistant Modal
  $query_Imp ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.status ='APPROVED' ";  
  $result_Imp = mysqli_query($connect, $query_Imp);
  echo '<div class="modal fade bd-example-modal-lg" tabindex="-1" id ="assModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" center>Here are the tickets waiting for implementation.</h4>
              <button type="button" class="close" data-dismiss="modal">X</button>
            </div>
          <div class="modal-body">
            <div class="container">
              <div class = "row">
                <div class ="col">
                  <div class="table-responsive">  
                    <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                      <thead >  
                        <tr>  
                          <td><center><b>Ticket ID</td>
                          <td><center><b>Company Name</td>  
                          <td><center><b>Buyer</td>  
                          <td><center><b>Project</td>  
                          <td><center><b>Status</td>  
                        </tr>  
                      </thead>';                 
                      while($row = mysqli_fetch_array($result_Imp)){  
                      echo '
                        <tr>  
                          <td>'.$row['ticket_id'].'</td>  
                          <td>'.$row["company_name"].'</td>  
                          <td>'.$row["buyer"].'</td>  
                          <td>'.$row["project"].'</td>  
                          <td>'.$row["status"].'</td>
                        </tr>';}  
                      echo '
                    </table>  
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button  type="button" class="btn btn-secondary" onclick="window.location=\'view_ticket.php\'" data-dismiss="modal">View Tickets</button>
        </div>
      </div>
      </div>
    </div>';
//Head Modal
  $query_Conf ="select t.ticket_id, c.company_name, t.buyer, t.project, t.status from tickets t inner join companies c on t.company = c.company_code inner join users u on t.approver = u.id where t.status ='IMPLEMENTED' ";  
  $result_Conf = mysqli_query($connect, $query_Conf);
  echo '<div class="modal fade bd-example-modal-lg" tabindex="-1" id ="headModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" center>Here are the tickets waiting for confirmation.</h4>
              <button type="button" class="close" data-dismiss="modal">X</button>
            </div>
          <div class="modal-body">
            <div class="container">
              <div class = "row">
                <div class ="col">
                  <div class="table-responsive">  
                    <table id="employee_data" class="table table-striped table-bordered" style="width:100%">
                      <thead >  
                        <tr>  
                          <td><center><b>Ticket ID</td>
                          <td><center><b>Company Name</td>  
                          <td><center><b>Buyer</td>  
                          <td><center><b>Project</td>  
                          <td><center><b>Status</td>  
                        </tr>  
                      </thead>';                 
                      while($row = mysqli_fetch_array($result_Conf)){  
                      echo '
                        <tr>  
                          <td>'.$row['ticket_id'].'</td>  
                          <td>'.$row["company_name"].'</td>  
                          <td>'.$row["buyer"].'</td>  
                          <td>'.$row["project"].'</td>  
                          <td>'.$row["status"].'</td>
                        </tr>';}  
                      echo '
                    </table>  
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button  type="button" class="btn btn-secondary" onclick="window.location=\'view_ticket.php\'" data-dismiss="modal">View Tickets</button>
        </div>
      </div>
      </div>
    </div>'; 
        ?>
            
  <script src="bootstrap-4.3.1/js/bootstrap.min.js" ></script>
  <script src="bootstrap-4.3.1/vendor/jquery/jquery.slim.min.js"></script>
  <script src="bootstrap-4.3.1/js/popper.min.js"></script>
  <script src="bootstrap-4.3.1/js/bootstrap.bundle.min.js" ></script>

</body>
</html>

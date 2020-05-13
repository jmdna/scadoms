<!DOCTYPE html>
<html>
<head>




<?php
$page='reports';
include('navbar.php');
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "scadoms";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$qry="select t.ticket_id, t.create_date, t.confirm_date, c.company_name, t.buyer, t.project, t.unit_code, r.request_desc, CONCAT(u.first_name,' ',u.last_name) as 'requestor', rr.email_address as 'req_email', t.remarks, CONCAT(a.first_name,' ', a.last_name) AS 'approver', aa.email_address as 'app_email', t.status from tickets t inner join request_type r on t.request_type = r.request_code inner join users u on t.requestor = u.id inner join users a on t.approver = a.id inner join companies c on t.company = c.company_code inner join users rr on t.requestor = rr.id inner join users aa on t.approver = aa.id";
 $result=mysqli_query($conn, $qry);
 $records = array();
 while($row = mysqli_fetch_assoc($result)){ 
  $records[] = $row;
  }
?>
<div class="container-fluid">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script type="text/javascript" src="tableExport.js"></script>
  <script type="text/javascript" src="jquery.base64.js"></script>
  <script type="text/javascript" src="html2canvas.js"></script>
  

  <div class="row">
    <div class="col ">
  </div>
  <div class="col-5">
  </div>
  <div class="col-md-auto">
    <br><br>
    <button class="btn btn-success " type="button" id="dropdownMenu1"  onclick="$('#tickets').tableExport({type:'excel',escape:'false'});"> Export to Excel</button><br><br>
  
<!-- <button class="btn btn-success " type="button" id="dropdownMenu1"  onclick="$('#tickets').tableExport({type:'csv',escape:'false'});"> Export to CSV</button> -->

   <div class="row"></div>
  </div>  </div>
  <div class="row" style="height:450px !important;overflow:scroll;">
    <table id="tickets" class="table table-striped table-bordered ">
                        <thead >  
                          <tr>  
                            <td><center>Ticket ID</td>  
                            <td><center>Company Name</td>  
                            <td><center>Buyer</td>  
                            <td><center>Project</td>
                            <td><center>Unit Code</td>
                            <td><center>Type of Request</td>
                            <td><center>Requestor</td>
                            <td><center>Req. Email</td>
                            <td><center>Date Created</td>
                            <td><center>Remarks</td>
                            <td><center>Approver</td>
                            <td><center>App. Email</td> 
                            <td><center>Status</td>
                            <td><center>Date Confirmed</td>

                          </tr>  
                        </thead>                
                        <?php foreach($records as $rec):?>
                        
                            <tr>  

                              <td><?php echo $rec['ticket_id'] ?></td>  
                              <td><?php echo $rec["company_name"] ?></td>  
                              <td><?php echo $rec["buyer"] ?></td>  
                              <td><?php echo $rec["project"] ?></td>
                              <td><?php echo $rec["unit_code"] ?></td>
                              <td><?php echo $rec["request_desc"] ?></td>
                              <td><?php echo $rec["requestor"] ?></td>
                              <td><?php echo $rec["req_email"] ?></td>
                              <td><?php echo date("m/d/Y", strtotime($rec["create_date"])) ?></td>
                              <td><?php echo $rec["remarks"] ?></td>
                              <td><?php echo $rec["approver"] ?></td>
                              <td><?php echo $rec["app_email"] ?></td>  
                              <td><?php echo $rec["status"] ?></td>
                            <td><?php if (strtotime($rec["confirm_date"]) == NULL) { echo ' ';}else{ echo date("m/d/Y", strtotime($rec["confirm_date"]));} ?></td>

                            </tr>
                          <?php endforeach; ?>
                      </table>
          
        
          
</div>
</div>

</body>
</html>
<script type="text/javascript">

$(function(){
  $('#example').DataTable();
      }); 
</script>
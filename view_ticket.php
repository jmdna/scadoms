<?php 
include('navbar.php');
$query=$result='';
if ($_SESSION['user_level']=='Employee'){
  $connect = mysqli_connect("127.0.0.1", "root", "admin", "scadoms");
  $query ="select t.ticket_id, c.company_name, t.buyer, t.project, t.unit_code, r.request_desc, t.remarks, CONCAT(u.first_name,' ', u.last_name) AS 'Approver', t.status from tickets t inner join companies c on t.company = c.company_code inner join request_type r on t.request_type=r.request_code inner join users u on t.approver = u.id where t.requestor=".$_SESSION['id'];  
 $result = mysqli_query($connect, $query);  
}
elseif ($_SESSION['user_level']=='Approver'){
  $connect = mysqli_connect("127.0.0.1", "root", "admin", "scadoms");
  $query ="select t.ticket_id, c.company_name, t.buyer, t.project, t.unit_code, r.request_desc, t.remarks, CONCAT(u.first_name,' ', u.last_name) AS 'Approver', t.status from tickets t inner join companies c on t.company = c.company_code inner join request_type r on t.request_type=r.request_code inner join users u on t.approver = u.id where t.approver=".$_SESSION['id'];  
 $result = mysqli_query($connect, $query);
}
elseif (preg_match("/DC Assistant/",$_SESSION['user_level'])){
  
  $connect = mysqli_connect("127.0.0.1", "root", "admin", "scadoms");
  $query ="select t.ticket_id, c.company_name, t.buyer, t.project, t.unit_code, r.request_desc, t.remarks, CONCAT(u.first_name,' ', u.last_name) AS 'Approver', t.status from tickets t inner join companies c on t.company = c.company_code inner join request_type r on t.request_type=r.request_code inner join users u on t.approver = u.id ";  
 $result = mysqli_query($connect, $query);
}
elseif (preg_match("/DC Head/",$_SESSION['user_level'])){
  $connect = mysqli_connect("127.0.0.1", "root", "admin", "scadoms");
    $query ="select t.ticket_id, c.company_name, t.buyer, t.project, t.unit_code, r.request_desc, t.remarks, CONCAT(u.first_name,' ', u.last_name) AS 'Approver', t.status from tickets t inner join companies c on t.company = c.company_code inner join request_type r on t.request_type=r.request_code inner join users u on t.approver = u.id ";  
 $result = mysqli_query($connect, $query);
}
 
$page='tickets';
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>SCADOMS - View Tickets</title>  
            <script src="bootstrap-4.3.1/js/popper.min.js"></script>
            <script src="bootstrap-4.3.1/js/bootstrap.min.js" ></script>
            <script src="bootstrap-4.3.1/vendor/jquery/jquery.slim.min.js"></script>  
            <script src="bootstrap-4.3.1/js/bootstrap.bundle.min.js" ></script>
          
 <script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
    </script>   
      </head>  
      
      <body>  
         
                    <br /><br />  
           <div class="container">  
                <h3 align="center">List of Tickets as of <?php echo  date("M d, Y"); ?></h3>  
                <br /> 
                
                <div class="table-responsive">  
                       
                     <table id="tickets" class="table table-striped table-bordered" style="width:100%">
                      <link rel="stylesheet" href="bootstrap-4.3.1/css/bootstrap.css" />  
                       <link rel="stylesheet" href="bootstrap-4.3.1/css/dataTables.bootstrap4.min.css"/>
                       
                       <script src="bootstrap-4.3.1/js/jquery-1.10.2.js"></script>
                       <script src="bootstrap-4.3.1/js/jquery.dataTables.min.js"></script>  
                       <script src="bootstrap-4.3.1/js/dataTables.bootstrap.min.js"></script>   
                       <script src="bootstrap-4.3.1/js/bootstrap.js"></script>           
                       <script src="bootstrap-4.3.1/js/tickets.js"></script>
                          <thead >  
                               <tr>  
                                    <td><center>Ticket ID</td>  
                                    <td><center>Company Name</td>  
                                    <td><center>Buyer</td>  
                                    <td><center>Project</td>  
                                    <td><center>Unit Code</td>
                                    <td><center>Type of Request</td>
                                    <td><center>Remarks </td>
                                    <td><center>Status</td>
                                      <td><center>Action</td>
                               </tr>  
                          </thead>  
                         
                     </table>  
                </div>  
           </div>  

      </body>  

 </html>  
 <script>  
 $(document).ready(function(){  
      $('#tickets').DataTable();  
 });  
 </script>  

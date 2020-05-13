<?php
	
	include ("connection.php");
	$con = mysqli_connect($hostname, $username, $password);
	mysqli_connect($hostname, $username, $password) OR die("Unable to connect.");
	mysqli_select_db($con,$dbname);
	$sql ="select t.ticket_id, c.company_name, t.buyer, t.project, t.unit_code, r.request_desc, t.remarks, CONCAT(u.first_name,' ', u.last_name) AS 'Approver', t.status from tickets t inner join companies c on t.company = c.company_code inner join request_type r on t.request_type=r.request_code inner join users u on t.approver = u.id";
	$records = mysqli_query($con,$sql);
	
	$table = "";
	
	while($row = mysqli_fetch_array($records)){		

		
		$vi= '<a href=\"ticket_link.php?ID='.$row['ticket_id'].'\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View Details\" class=\"btn btn-info col-lg\">View</a>';
		$table.='{
				  "Ticket ID":"'.$row['ticket_id'].'",
				  "Company":"'.$row['company_name'].'",
				  "Buyer":"'.$row['buyer'].'",
				  "Project":"'.$row['project'].'",
				  "Unit Code":"'.$row['unit_code'].'",
				  "Type of Request":"'.$row['request_desc'].'",
				  "Remarks":"'.$row['remarks'].'",
				  "Status":"'.$row['status'].'",
				  "Action":"'.$vi.'"
				  
				},';		
	}	

	$table = substr($table,0, strlen($table) - 1);

	echo '{"data":['.$table.']}';	

?>
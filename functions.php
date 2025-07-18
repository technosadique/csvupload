<?php

function get_all_records(){ 
include "db.php";  

    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);  


    if (mysqli_num_rows($result) > 0) {
     echo "<div class='table-responsive'>
	       <table id='myTable' class='table table-striped table-bordered'>
             <thead><tr>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>                          
                        </tr>
			</thead>
			<tbody>";


     while($row = mysqli_fetch_assoc($result)) {

         echo "<tr>
                   <td>" . $row['firstname']."</td>
                   <td>" . $row['lastname']."</td>
                   <td>" . $row['email']."</td>
                   </tr>";        
     }
    
     echo "</tbody></table></div>";
     
} else {
     echo "you have no records";
}
}

 if(isset($_POST["Export"])){
		 
      header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=data.csv');  
      $output = fopen("php://output", "w");  
      fputcsv($output, array('First Name', 'Last Name', 'Email'));  
      $query = "SELECT firstname,lastname,email from users ORDER BY id DESC";  
      $result = mysqli_query($conn, $query);  
      while($row = mysqli_fetch_assoc($result))  
      {  
           fputcsv($output, $row);  
      }  
      fclose($output);  
 }  

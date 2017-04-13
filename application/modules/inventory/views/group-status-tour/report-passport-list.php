<?php

//print "<pre>";
//print_r($query); 
//print "</pre>";
//die; 

//print $before_table;
?>
<thead>
    <tr>
       <!-- <th>No</th> -->
        <th>Name</th>
        <th>Passport<br>No</th>
        <th>Place<br>Of<br>Issued</th>
        <th>Date<br>Of<br>Issued<br>Expired</th>
        <th>Place<br>Of<br>Birth</th>
        <th>Date<br>Of<br>Birth</th>
        <th>Address</th>
        <th>Phone</th>
    </tr>
</thead>
<tbody>
  <?php
  $no = 0;
    foreach ($data as $key => $value) {
     $no = $no + 1;
      if($value->tanggal_lahir == "0000-00-00"){
        $tanggal_lahir = "";
      }else{
       $tanggal_lahir = date("d M Y", strtotime($value->tanggal_lahir));
      }
      
      if($value->date_of_issued == "0000-00-00"){
        $date_of_issued = "";
      }else{
        $date_of_issued = date("d M", strtotime($value->date_of_issued))." - ".date("d M Y", strtotime($value->date_of_expired));
      }
     
      print '
      <tr>
       
        <td>'.$value->first_name.' '.$value->last_name.'</td>
        <td>'.$value->passport.'</td>
        <td>'.$value->place_of_issued.'</td>
        <td>'.$date_of_issued.'</td>
        <td>'.$value->tempat_tanggal_lahir.'</td>
        <td>'.$tanggal_lahir.'</td>
         <td>'.$value->address.'</td>
          <td>'.$value->telphone.'</td>
         </tr>';
    }

  ?>
</tbody>

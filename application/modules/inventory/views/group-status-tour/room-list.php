<?php

//print "<pre>";
//print_r($data); 
//print "</pre>";
//die; 

//print $before_table;
?>
<thead>
    <tr>
      <!--  <th>No</th> -->
      <th style="width: 40%">Name Of Pax</th>
        <th style="width: 7%">Room</th>
        <th>Room<br>Type</th>
        <th>Room<br>Number</th>
        <th>Passport<br>No</th>
        <th>Passport<br>Expired<br>Date</th>
        <th>Date<br>Of<br>Birth</th>
        <th>Remarks</th>
    </tr>
</thead>
<tbody>
  <?php
  $no = 0;
    foreach ($data as $key => $value) {
     $no = $no + 1;
      
      
      
     $name = explode(",", $value->name);
      $date_of_issued = explode(",", $value->date_of_issued);
     $date_of_expired = explode(",", $value->date_of_expired);
     $tanggal_lahir = explode(",", $value->tanggal_lahir);
     $no_passport = explode(",", $value->no_passport);
     $no_room = explode(",", $value->room);
    
    // echo count($name)."SIP";
//     $no_name =0;
     
             foreach ($name as $ky_name => $val_name) {
              // if($r == $no_room[$ky_name]){
                 
                $hs_name[$key] .=  $val_name."<br>";
                $hs_no_passport[$key] .=  $val_no_passport[$ky_name]."<br>";

                if($date_of_issued[$ky_name] == "0000-00-00"){
                  $date_of_issued2 = "";
                }else{
                  $date_of_issued2 = date("d M Y", strtotime($date_of_issued[$ky_name]));
                }

                if($date_of_expired[$ky_name] == "0000-00-00"){
                  $date_of_expired2 = "";
                }else{
                  $date_of_expired2 = date("d M Y", strtotime($date_of_expired[$ky_name]));
                }
                $hs_date_of_passport[$key] .=  $date_of_issued2."-".$date_of_expired2."<br>";

                if($tanggal_lahir[$ky_name] == "0000-00-00"){
                  $tanggal_lahir2 = "";
                }else{
                  $tanggal_lahir2 = date("d M Y", strtotime($tanggal_lahir[$ky_name]));
                }
                $hs_tanggal_lahir2[$key] .= $tanggal_lahir2."<br>";
                $hs_room[$key] .= "Room ".$no_room[$ky_name]."<br>";
             //  }

              }
         
  
     
//     print_r($name);
     
      $data = '
      <tr>
      <!-- <td>'.$no.'</td> -->
        <td >'.$hs_name[$key].'</td>
          <td >'.$hs_room[$key].'</td>
        <td></td>
        <td></td>
        <td>'.$hs_no_passport[$key].'</td>
        <td>'.$hs_date_of_passport[$key].'</td>
        <td>'.$hs_tanggal_lahir2[$key].'</td>
         <td></td>
         </tr>';
      print $data;
    }

  ?>
</tbody>
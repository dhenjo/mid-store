<?php
/*
print "<pre>";
print $data[0]['category']['id'];
print_r($data);
print "</pre>"; */
?>
<thead>
    <tr>
        <th>name</th>
        <th>Code</th>
        <th>Status</th>
        <th>Option</th>
    </tr>
</thead> 
<tbody>
  <?php

    foreach ($data as $value) {
       
      if($value->status == 1){
        $status = "Active";
      }else{
        $status = "Disable";
      }
        
      $data_show = '
      <tr>
        <td>'.$value->name.'</td>
        <td>'.$value->code.'</td>
          <td>'.$status.'</td>
            <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("inventory/master-currency/add/{$value->id_master_currency}").'">Edit</a></li>
              <li><a href="'.site_url("inventory/master-currency/delete/{$value->id_master_currency}").'">Delete</a></li>
            </ul>
          </div>
        </td>
      </tr>';
     
       print $data_show;
      }
      
  ?>
 
</tbody>

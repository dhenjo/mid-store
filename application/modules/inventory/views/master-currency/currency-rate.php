<?php

//print "<pre>";
//print_r($data);
//print "</pre>"; 
?>
<thead>
    <tr>
        <th>Code</th>
        <th>Rate</th>
        <th>Option</th>
    </tr>
</thead> 
<tbody>
  <?php

    foreach ($data as $value) {
       
      $data_show = '
      <tr>
        <td>'.$dropdown[$value->id_master_currency].'</td>
        <td>'.number_format($value->rate).'</td>
            <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("inventory/master-currency/add-rate/{$value->id_master_currency_rate}").'">Edit</a></li>
              <li><a href="'.site_url("inventory/master-currency/delete/{$value->id_master_currency_rate}").'">Delete</a></li>
            </ul>
          </div>
        </td>
      </tr>';
     
       print $data_show;
      }
  ?>
</tbody>

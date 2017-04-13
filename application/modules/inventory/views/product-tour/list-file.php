<?php

//print_r($data); die;
?>
<thead>
    <tr>
        <th>File</th>
        <th>Kategori</th>
        <th>Option</th>
    </tr>
</thead>
<tbody>
  <?php
  $status_kategori = array(1 => "Product Tour",
                            2 => "Product Tour Information",
                            3 => "List Tour");
  if(is_array($data)){
     foreach ($data as $key => $value) {
      
  $data_file .= '<tr>
        <td>'.$value->file.'</td>
      <td>'.$status_kategori[$value->kategori].'</td><td>';
  if($value->status == 1){
      $data_file .= '<div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("inventory/product_tour/proses-file/".$value->id_csv_file).'">Proses</a></li>
             <!-- <li><a href="'.site_url("inventory/product_tour/delete-product-tour/".$value->id_product_tour).'">Delete</a></li> -->
            </ul>
          </div>'; }
       $data_file .=  '</td>
      </tr>';
    }
    print $data_file;
  }
  ?>
</tbody>
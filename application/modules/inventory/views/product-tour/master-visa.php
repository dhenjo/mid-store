<thead>
    <tr>
        <th>Name</th>
        <th>Nominal</th>
        <th>Status</th>
        <th>Option</th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
     foreach ($data as $key => $value) {
   
      if($value->status == 1){
        $status = "Active";
      }else{
        $status = "Non Active";
      }
      print '
      <tr>
        <td>'.$value->name.'</td>
          <td>'.number_format($value->nominal,2,".",",").'</td>
        <td>'.$status.'</td>
       <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("inventory/product_tour/add-master-visa/".$value->id_product_tour_master_visa).'">Edit</a></li>
             <!-- <li><a href="'.site_url("inventory/product_tour/delete-product-tour/".$value->id_product_tour).'">Delete</a></li> -->
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
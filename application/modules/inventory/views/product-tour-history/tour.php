<?php 
//print "<pre>";
//print_r($data); 
//print "</pre>";
?>
<thead>
    <tr>
        <th>Ver</th>
        <th>Log Status</th>
        <th>Tanggal</th>
        <th>Users</th>
        <th>Title</th>
        <th>Days</th>
        <th>Airlines</th>
        <th>Option</th>
    </tr>
</thead> 
<tbody>
  <?php
  $status_log = array(
    1 => "<span class='label label-default'>Draft</span>",
    2 => "<span class='label label-success'>In Use</span>"
  );
  foreach($data AS $value){
    $use_it = "";
    if($value->status_log != 2){
      $use_it = "<a href='".site_url("inventory/product-tour-history/use-it/{$value->id_product_tour_log}")."' class='btn btn-info'>Use it</a>";
    }
    $tampil .= "<tr>"
        . "<td>{$value->version}</td>"
        . "<td>{$status_log[$value->status_log]}</td>"
        . "<td>{$value->create_date}</td>"
        . "<td>{$value->users}</td>"
        . "<td>{$value->title}</td>"
        . "<td>{$value->days}</td>"
        . "<td>{$value->airlines}</td>"
        . "<td>"
          . "<div class='btn-group'>"
            . "<a href='".site_url("inventory/product-tour-history/detail/{$value->id_product_tour_log}")."' class='btn btn-success'>Detail</a>"
            . "{$use_it}"
          . "</div>"
        . "</td>"
      . "</tr>";
  }
  print $tampil;
  ?>
 
</tbody>

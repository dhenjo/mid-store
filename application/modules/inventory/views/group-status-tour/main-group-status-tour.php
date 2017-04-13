<?php 
//print "<pre>";
//print_r($data); 
//print "</pre>";
print $before_table;
?>
<thead>
    <tr>
      <th style="width: 10%;word-wrap: break-word">Name Tour</th>
        <th>Tanggal Keberangkatan</th>
        <th>Days</th>
        <th>Seat</th>
        <th>Book</th>
        <th>Deposit</th>
        <th>Lunas</th>
        <th>Available<br>Seat</th>
        <th>Region</th>
        <th></th>
    </tr>
</thead> 
 <tbody id="data_list">
    
  </tbody>
  <tfoot>
    <tr>
      <th colspan="10" style="height: <?php print 20 * $menu_action?>px"></th>
    </tr>
  </tfoot>
</table>
<div class="box-footer clearfix" id="halaman_set">
    <ul class="pagination pagination-sm no-margin pull-right">
        <li><a href="#">«</a></li>
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">»</a></li>
    </ul>
</div>
<!--<tbody>
  <?php 

//    foreach ($data as $key => $value) {
//       
//      }    
  ?>
</tbody> -->

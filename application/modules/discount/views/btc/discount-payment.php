<thead>
    <tr>
        <th>Start</th>
        <th>End</th>
        <th>Title</th>
        <th>Channel</th>
        <th>Type</th>
        <th>Diskon</th>
        <th>Status</th>
        <th>Option</th>
    </tr>
</thead>
<tbody>
  <?php
  $channel = array(
    1 => "BCA", 
    2 => "Mega CC", 
    3 => "Visa/Master", 
    4 => "Mega Priority",
    5 => "Mandiri ClickPay",
    );
  $type = array(
    1 => "Persentase", 
    2 => "Nominal");
  if(is_array($data)){
    foreach ($data as $key => $value) {
      $status = array(
          2 => "<span class='label label-default'>Draft</span>",
          1 => "<span class='label label-success'>Active</span>",
      );
      
      print '
      <tr>
        <td>'.$value->mulai.'</td>
        <td>'.$value->akhir.'</td>
        <td>'.$value->title.'</td>
        <td>'.$channel[$value->channel].'</td>
        <td>'.$type[$value->type].'</td>
        <td style="text-align: right">'.number_format($value->nilai, 0).'</td>
        <td>'.$status[$value->status].'</td>
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("discount/btc/add-new/".$value->id_tiket_discount).'">Edit</a></li>
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
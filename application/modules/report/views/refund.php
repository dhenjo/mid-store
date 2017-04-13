<thead>
    <tr>
        <th>Tanggal</th>
        <th>Users</th>
        <th>Tiket Nomor</th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
    foreach ($data as $key => $value) {
      
      print '
      <tr>
        <td>'.$value->tanggal.'</td>
        <td>'.$value->name.'</td>
        <td>'.$value->issued_no.'</td>
      </tr>';
    }
  }
  ?>
</tbody>
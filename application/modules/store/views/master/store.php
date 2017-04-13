<thead>
    <tr>
        <th>Sort</th>
        <th>Store</th>
        <th>Telp</th>
        <th>Fax</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
    $status = array(
      NULL => "<span class='label label-info'>Store</>",
      2 => "<span class='label label-success'>Master Tour</>"
    );
    foreach ($data as $key => $value) {
      
      print '
      <tr>
        <td>'.$value->sort.'</td>
        <td>'.$value->title.'</td>
        <td>'.$value->telp.'</td>
        <td>'.$value->fax.'</td>
        <td>'.$status[$value->master].'</td>
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("store/master-store/add-new/".$value->id_store).'">Edit</a></li>
              <li><a href="'.site_url("store/master-store/list-counter/".$value->id_store).'">TC</a></li>
              <li><a href="'.site_url("store/master-store/list-commited/".$value->id_store).'">Commited</a></li>
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
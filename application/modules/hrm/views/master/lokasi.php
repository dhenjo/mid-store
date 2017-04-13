<thead>
    <tr>
        <th>Title</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
    foreach ($data as $key => $value) {
      print '
      <tr>
        <td>'.$value->title.'</td>
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("monitoring/add-new-lokasi/".$value->id_internal_lokasi_client).'">Edit</a></li>
              <li><a href="'.site_url("monitoring/delete-lokasi/".$value->id_internal_lokasi_client).'">Delete</a></li>
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
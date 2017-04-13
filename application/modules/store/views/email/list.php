<thead>
    <tr>
        <th>Title</th>
        <th>Store</th>
        <th>Users</th>
        <th>Region</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
   
    foreach ($data as $key => $value) {
      $user = $value->name." [".$value->email."]";
      print '
      <tr>
        <td>'.$value->title.'</td>
        <td>'.$dropdown[$value->id_store].'</td>
          <td>'.$user.'</td>
            <td>'.$region[$value->region].'</td>
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("store/tour-settings-region/add-new/".$value->id_tour_settings_region).'">Edit</a></li>
             
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
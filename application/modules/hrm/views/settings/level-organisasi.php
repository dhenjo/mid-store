<thead>
    <tr>
        <th>Title</th>
        <th>Parent</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
    foreach ($data as $key => $value) {
      if(!$value->parent)
        $value->parent = 0;
      print '
      <tr>
        <td><a href="'.site_url("hrm/settings-hrm/child-level-organisasi/".$value->id_hrm_settings_level_organisasi).'">'.$value->title.'</a></td>
        <td>'.$value->kepala.'</td>
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("hrm/settings-hrm/add-level-organisasi/{$value->parent}/".$value->id_hrm_settings_level_organisasi).'">Edit</a></li>
              <li><a href="'.site_url("hrm/settings-hrm/child-level-organisasi/".$value->id_hrm_settings_level_organisasi).'">Child</a></li>
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
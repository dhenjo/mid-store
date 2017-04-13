<thead>
    <tr>
        <th>Title</th>
        <th>Code</th>
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
        <td>'.$value->code.'</td>
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("hrm/master-hrm/add-department/".$value->id_hrm_department).'">Edit</a></li>
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
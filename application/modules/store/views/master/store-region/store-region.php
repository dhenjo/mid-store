<thead>
    <tr>
        <th>Store Region</th>
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
      if($this->session->userdata("id") == 1){
        $delete = '<li><a href="'.site_url("store/master-store-region/delete/".$value->id_store_region).'">Delete</a></li>';
      }else{
        $delete = "";
      }
      
      print '
      <tr>
        <td>'.$value->title.'</td>
        <td>'.$value->telp.'</td>
        <td>'.$value->fax.'</td>
        <td>'.$status[$value->master].'</td>
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("store/master-store-region/add-new/".$value->id_store_region).'">Edit</a></li>
              <li><a href="'.site_url("store/master-store-region/list-operation/".$value->id_store_region).'">Tour Operation</a></li>
              '.$delete.'
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
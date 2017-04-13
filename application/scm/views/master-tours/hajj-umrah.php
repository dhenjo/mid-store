<thead>
    <tr>
        <th>Gambar</th>
        <th>Title</th>
        <th>Status</th>
        <th>Option</th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
    foreach ($data as $key => $value) {
      $status = array(
          2 => "<span class='label label-default'>Draft</span>",
          1 => "<span class='label label-success'>Active</span>",
      );
      
      if($value->file_temp)
        $gambar = base_url()."files/antavaya/master/hajj/{$value->file_temp}";
      else
        $gambar = base_url()."files/no-pic.png";
      
      print '
      <tr>
        <td><img src="'.$gambar.'" width="150"></td>
        <td>'.$value->title.' <br /> '.$value->sub_title.'</td>
        <td>'.$status[$value->status].'</td>
        <td>
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">Action<span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="'.site_url("scm/master-tours/add-new-hajj-umrah/".$value->id_master_hajj_umrah).'">Edit</a></li>
              <li><a href="'.site_url("scm/master-tours/delete-hajj-umrah/".$value->id_master_hajj_umrah).'">Delete</a></li>
            </ul>
          </div>
        </td>
      </tr>';
    }
  }
  ?>
</tbody>
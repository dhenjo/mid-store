<div id="loading-page" style="display: none"><img src="<?php print $url?>img/ajax-loader.gif" width="20" /></div>
<table class="table table-bordered">
  <thead>
    <tr>
        <th>Title</th>
        <th>Users</th>
        <th>Department</th>
        <th>Tanggal</th>
        <th>Tanggal Disetujui</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
  </thead>
  <tbody id="data_list">
    
  </tbody>
  <tfoot>
    <tr>
      <th colspan="7" style="height: <?php print 20 * $menu_action?>px"></th>
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
<?php print $this->form_eksternal->form_open("", 'role="form"')?>
<table class="table table-bordered">
  <tr>
    <td>Name/Email</td>
    <td><?php print $this->form_eksternal->form_input('name', $this->session->userdata("users_name"), 'class="form-control" placeholder="Name/Email"')?></td>
    <td>Privilege</td>
    <td><?php print $this->form_eksternal->form_dropdown('privilege', $this->global_models->get_dropdown("m_privilege", "id_privilege", "name", TRUE, array("parent >" => 0)), array($this->session->userdata("users_id_privilege")), 'class="form-control"')?></td>
     </tr>
  <tr>
    <td>Store Region</td>
    <td><?php print $this->form_eksternal->form_dropdown('store_region', $this->global_models->get_dropdown("store_region", "id_store_region", "title"), array($this->session->userdata("users_id_store_region")), 'class="form-control"')?></td>
    <td>Status</td>
    <td><?php print $this->form_eksternal->form_dropdown('status', array("" => "- Pilih -", 1 => "Active", 2 => "Draft"), array($this->session->userdata("users_status")), 'class="form-control"')?></td>
  </tr>
  <tr>
    <td>Store</td>
    <td><?php print $this->form_eksternal->form_dropdown('store', $this->global_models->get_dropdown("store", "id_store", "title"), array($this->session->userdata("users_id_store")), 'class="form-control"')?></td>
  </tr>
  <tr>
    <td colspan="4"><button class="btn btn-primary" type="submit">Search</button></td>
  </tr>
</table>
</form>
<hr />
<table class="table table-bordered">
  <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Privilege</th>
        <th>Store Region</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
  </thead>
  <tbody id="data_list">
    
  </tbody>
  <tfoot>
    <tr>
      <th colspan="6" style="height: <?php print 20 * $menu_action?>px"></th>
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
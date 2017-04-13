<?php 
//print "<pre>";
//print_r($data); 
//print "</pre>";
?>
<?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => ""))?>
<div class="box-body col-sm-6">

      <div class="control-group">
        <label>Tour Name</label>
       <?php print $this->form_eksternal->form_input('name', $this->session->userdata('tour_name'), 
        ' class="form-control input-sm" placeholder="Tour Name"');?>
      </div>

      <div class="control-group">
        <label>Season</label>
        <?php
         $category = array(
       0 => "All",     
      1 => "Low Season",
      2 => "Hight Season Chrismast",
      3 => "Hight Season Lebaran",
      4 => "School Holiday Period",
    );
        ?>
        <?php print $this->form_eksternal->form_dropdown('season', $category, array($this->session->userdata('tour_season')), 'class="form-control" placeholder="Season"');?>
   
      </div>
  <div class="control-group">
          <label>Status</label>
        <?php 
        
       $status = array(
          0 => "All",
          1 => "Publish",
          2 => "Draft",
          
        );
       
       print $this->form_eksternal->form_dropdown('status', $status, array($this->session->userdata('tour_status')), 'class="form-control" placeholder="Status"');?></div>
  
  <div class="control-group">
          <label>Product News</label>
        <?php 
       print $this->form_eksternal->form_input('pn_news', $this->session->userdata('pn_news'), 'class="form-control" placeholder="Product News"');?></div>
      
    </div>

<div class="box-body col-sm-6">

      <div class="control-group">
        <label>Kota</label>
        <?php print $this->form_eksternal->form_input('kota', $this->session->userdata('tour_kota'), 
        ' class="form-control input-sm" placeholder="Kota"');?>      
      </div>

      <div class="control-group">
        <label>Region</label>
         <?php
      
    $subcategory = array(
      0 => "All",
       1 => "Eropa",
      2 => "Africa",
      3 => "America",
      4 => "Australia",
      5 => "Asia",
	  6 => "China",
	  7 => "New Zealand"
    );
         print $this->form_eksternal->form_dropdown('region', $subcategory, array($this->session->userdata('tour_region')), 'class="form-control" placeholder="Region"');?>
      </div>
  <div class="control-group">
          <label>Store</label>
        <?php 
        
       print $this->form_eksternal->form_dropdown('store_region', $this->global_models->get_dropdown("store_region", "id_store_region", "title"), array($this->session->userdata('tour_store')), 'class="form-control"');?></div>
      
    </div>


      
    </div>

<div class="box-body col-sm-12">
  <div class="box-footer" >
      <div class="control-group">
        <button class="btn btn-primary" type="submit">Search</button> 
      </div>
  </div>
</div>
    

</form>
<br />

<table class="table table-bordered">
<thead>
    <tr>
        <th>Store</th>
        <th>Product News</th>
        <th>Tour Name</th>
        <th>Days</th>
        <th>Kota</th>
        <th>Season</th>
        <th>Region</th>
        <th>Status</th>
        <th>Option</th>
    </tr>
</thead> 
 <tbody id="data_list">
    
  </tbody>
  <tfoot>
    <tr>
      <th colspan="8" style="height: <?php print 20 * $menu_action?>px"></th>
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


<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_product_tour_info" => $id_product_tour_info))?>
              <div class="box-body">
        <?php 
       
            if($detail){
              $no = 0;
              $no1 = 0;
        foreach ($detail AS $inf){
         
            ?>
    <div class='box-body col-sm-9'>
      <div class="control-group">
      <label>Flight Detail</label>
      <?php print $this->form_eksternal->form_input('name[]', $inf->name, ' class="form-control input-sm" placeholder="Flight Detail"');?>
      </div>
      </div>    
       <div class='box-body col-sm-3'>
      <div class="control-group">
      <label></label><br>
      <a href="<?php print site_url("inventory/group-status-tour/delete-row-flight-detail/".$id_product_tour_info."/".$inf->id_product_tour_flight_detail); ?>" class="btn btn-info"><?php print lang("Delete")?></a>
      </div>
      </div>         
    <br><br><br><br>
              <?php
                  print $this->form_eksternal->form_input('id_product_tour_flight_detail[]', $inf->id_product_tour_flight_detail, 
                      'style="display: none"');
                 print "</tr>";
                  }
                }
            ?>  
        <div class='box-body col-sm-9'>
      <div class="control-group">
      <label>Flight Detail</label>
      <?php print $this->form_eksternal->form_input('name[]', "", ' class="form-control input-sm" placeholder="Flight Detail"');?>
      </div>
      </div>  
     <br><br><br><br>
              <span id="tambah-additional"></span>
     <a href="javascript:void(0)" onclick="tambah_items_flight_detail()" class="btn btn-info"><?php print lang("Add row")?></a>
          
              <div class="box-footer" >
                  <button class="btn btn-primary" type="submit">Save changes</button>
                 <a href="<?php print site_url("inventory/group-status-tour/report-passport-list/".$id_product_tour_info)?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
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
                    array("id_detail" => $id_data))?>
              <div class="box-body">
        <?php 
        $dropdown = array(1 => "Persen %",
                      2 => "Nominal");
            if($detail){
              
              $no = 0;
              $no1 = 0;
        foreach ($detail AS $inf){
          $inf->id_product_tour_setting_discount;
            ?>
    <div class='box-body col-sm-3'>
      <div class="control-group">
      <label>Batas Discount <?php print $no1 = $no1 + 1; ?></label>
      <?php print $this->form_eksternal->form_input('batas_discount[]', $inf->batas_discount, ' class="form-control input-sm" placeholder="Batas Discount"');?>
      </div>
      </div>            
    <div class='box-body col-sm-3'>
    <div class="control-group">
      <label><div class='number_additional'>Discount <?php print $no = $no + 1; ?> </div></label>
      <?php print $this->form_eksternal->form_dropdown('stnb_discount[]', $dropdown, array($inf->stnb_discount), 'class="form-control" placeholder="Additional"')?>
      </div>
      </div>
     <div class='box-body col-sm-3'>
      <div class="control-group">
      <label></label>
      <?php print $this->form_eksternal->form_input('discount[]', $inf->discount, ' class="form-control input-sm" placeholder="Nominal"');?>
      </div>
      </div>   
       <div class='box-body col-sm-3'>
      <div class="control-group">
      <label></label><br>
      <a href="<?php print site_url("inventory/product-tour/delete-setting-discount/".$inf->id_product_tour_setting_discount); ?>" class="btn btn-info"><?php print lang("Delete")?></a>
      </div>
      </div>         
    <br><br><br><br>
              <?php
                  print $this->form_eksternal->form_input('id_product_tour_setting_discount[]', $inf->id_product_tour_setting_discount, 
                      'style="display: none"');
                 print "</tr>";
                  }
                }
                
            ?>      
    <div class='box-body col-sm-4'>
      <div class="control-group">
      <label>Batas Discount </label>
      <?php print $this->form_eksternal->form_input('batas_discount[]', $inf->nominal, ' class="form-control input-sm" placeholder="Batas Discount"');?>
      </div>
      </div>            
    <div class='box-body col-sm-4'>
    <div class="control-group">
      <label><div class='number_additional'>Discount </div></label>
      <?php print $this->form_eksternal->form_dropdown('stnb_discount[]', $dropdown, array($inf->id_product_tour_master_additional), 'class="form-control" placeholder="Additional"')?>
      </div>
      </div>
     <div class='box-body col-sm-4'>
      <div class="control-group">
      <label> </label><br>
      <?php print $this->form_eksternal->form_input('discount[]', $inf->nominal, ' class="form-control input-sm" placeholder=""');?>
      </div>
      </div>           
    <br><br><br>
     
         
     <br><br>
              <span id="tambah-additional"></span>
     <a href="javascript:void(0)" onclick="tambah_items_setting_discount()" class="btn btn-info"><?php print lang("Add")?></a>
          
              <div class="box-footer" >
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("inventory/product-tour/master-discount")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
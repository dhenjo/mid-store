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
                    array("id_detail" => $detail[0]->id_product_tour_master_visa))?>
              <div class="box-body">

                <div class="control-group">
                  <label>name</label>
                  <?php print $this->form_eksternal->form_input('name', $detail[0]->name, 'class="form-control input-sm" placeholder="Name"');?>
                </div>
              <div class="form-group">
                  <label>Currency</label>
                  <div class="input-group">
              <?php print $this->form_eksternal->form_dropdown('id_currency', $dropdown, array($detail[0]->id_currency), 'class="form-control" placeholder="Currency"');?>        
                  </div>
              </div>
                <div class="control-group">
                  <label>Nominal</label>
                  <?php print $this->form_eksternal->form_input('nominal', $detail[0]->nominal, 'class="form-control input-sm" placeholder="Nominal"');?>
                </div>
                
                <div class="control-group">
                  <label>Status</label>
											<div class="input-group">
                        <div class="checkbox">
                            <label>
                                <?php
                                if($detail[0]->status == 1)
                                  print $this->form_eksternal->form_checkbox('status', 1, TRUE);
                                else
                                  print $this->form_eksternal->form_checkbox('status', 1, FALSE);
                                ?>
                                Active
                            </label>
                        </div>
											</div>
										</div>
              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("inventory/product-tour/master-visa")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
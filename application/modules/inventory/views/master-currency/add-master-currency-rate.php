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
                    array("id_detail" => $detail[0]->id_master_currency_rate))?>
              <div class="box-body">

                <div class="control-group">
                  <label>Code</label>
                  <?php print $this->form_eksternal->form_dropdown('id_currency', $dropdown, array($detail[0]->id_master_currency), 'class="form-control" placeholder="Currency"'); ?>
                </div>
                <div class="control-group">
                  <label>Rate</label>
                  <?php print $this->form_eksternal->form_input('rate', $detail[0]->rate, 'class="form-control input-sm" placeholder="Rate"');?>
                </div>
                
              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("inventory/master-currency/rate")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
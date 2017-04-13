
<section class="content">
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
                      array("id_detail" => ""))?>
                  <div class="box-body">
                    <div class="control-group">
                      <label>PNR Code</label>
                      <div class="input-group">
                         
                          <?php print $this->form_eksternal->form_input('pnr_code', $detail['pnr_code'], 'class="form-control" placeholder="PNR Code"')?>
                      </div>
										</div>
                    
                    <div class="control-group">
                      <?php
                     $data_debug;
                      ?>
										</div>
                    
										<div class="control-group">
                      <label>Payment Type</label>
                      <div class="input-group">
                        <?php
                  $channel = array(
                            0 => "-Pilih-",
                            1 => "BCA",
                            2 => "Mega CC",
                            3 => "Visa/Master",
                            4 => "Mega Priority"
                          );
                  ?>
                          <?php print $this->form_eksternal->form_dropdown('payment_type', $channel, array($detail['payment_type']), 'class="form-control" placeholder="Payment Type"')?>
                      </div>
										</div>
                    
                  </div>
                  <div class="box-footer">
                      <button class="btn btn-primary" type="submit">Submit</button>
                    
                  </div>
                </form>
            </div><!-- /.box -->
        </div><!--/.col (left) -->
    </div>   <!-- /.row -->
</section>


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
                    array("id_tour_info" => $id_tour_info,"id_detail" => $detail[0]->id_product_tour_leader))?>
              <div class="box-body">

                <div class="col-md-6">
                      <div class="control-group">
                      <label>First Name</label>
                      <?php print $this->form_eksternal->form_input('first_name', $detail[0]->first_name, 'id="tfirst'.$total_no++.'"  class="form-control adulttw input-sm" placeholder="First Name"');?>
                      </div>
                      </div>
               <div class="col-md-6">
                      <div class="control-group">
                      <label>Last Name</label>
                      <?php print $this->form_eksternal->form_input('last_name', $detail[0]->last_name, 'id="tlast'.$total_no1++.'" class="form-control input-sm" placeholder="Last Name"');?>
                      </div>
                      </div>
                    <div class="col-md-4">
                     <div class="control-group">
                      <label>No Telp</label>
                      <?php print $this->form_eksternal->form_input('telp', $detail[0]->telphone, 'class="form-control input-sm" id="ano_telp_pemesan" placeholder="No Telp"');?>
                      </div>
                      </div>
                    <div class="col-md-4">
                      <div class="control-group">
                      <label>Place Of Birth</label>
                       <?php print $this->form_eksternal->form_input('place_birth', $detail[0]->tempat_tanggal_lahir, 'id="tmpt_lahir'.$total_no1++.'" class="form-control input-sm" placeholder="Place Of Birth"');?>
                      </div>
                       </div>
                     <div class="col-md-4">
                      <div class="control-group">
                      <label>Date Of Birth</label>
                      <?php print $this->form_eksternal->form_input('date', $detail[0]->tanggal_lahir, ' class="form-control input-sm adult_date" placeholder="Date Of Birth"');?>
                       </div>
                     </div>
                     <div class="col-md-6">
                      <div class="control-group">
                      <label>No Passport</label>
                      <?php print $this->form_eksternal->form_input('passport', $detail[0]->passport, 'class="form-control input-sm" placeholder="No Passport"');?>
                     </div>
                    </div>
                    
                    <div class="col-md-6">
                    <div class="control-group">
                      <label>Place Of Issued</label>
                      <?php print $this->form_eksternal->form_input('place_issued', $detail[0]->place_of_issued, 'class="form-control input-sm" placeholder="Place Of Issue"');?>
                     </div>
                      </div>
                       <div class="col-md-6">
                    <div class="control-group">
                      <label>Date Of Issued</label>
                      <?php print $this->form_eksternal->form_input('date_issued', $detail[0]->date_of_issued, 'id="tlahir'.$total_no2++.'" class="form-control input-sm passport" placeholder="Date Of Issued"');?>
                      </div>
                      </div>
                <div class="col-md-6">
                    <div class="control-group">
                      <label>Date Of Expired</label>
                      <?php print $this->form_eksternal->form_input('date_expired', $detail[0]->date_of_expired, 'id="tlahir'.$total_no2++.'" class="form-control input-sm passport" placeholder="Date Of Expired"');?>
                   
                    </div><br>
                        </div>
                <div class="control-group">
                      <label>Address</label>
                      <?php print $this->form_eksternal->form_textarea('address', $detail[0]->address, 'class="form-control input-sm" id="address" placeholder="Address"');?>
                      </div>
              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("inventory/group-status-tour/report-passport-list/".$id_tour_info)?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
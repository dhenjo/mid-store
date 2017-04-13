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
                      array("id_detail" => $detail[0]->id_users))?>
                  <div class="box-body">
                    <div class="control-group">
                      <h4>Name</h4>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-user"></i></span>
                          <?php print $this->form_eksternal->form_input('name', $detail[0]->name, 'class="form-control" placeholder="Name"')?>
                      </div>
										</div>
                    
										<div class="control-group">
                      <h4>Email</h4>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                          <?php print $this->form_eksternal->form_input('email', $detail[0]->email, 'class="form-control" placeholder="Email"')?>
                      </div>
										</div>
                    
										<div class="control-group">
                      <h4>Privilege</h4>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                          <?php print $this->form_eksternal->form_dropdown('privilege', $dropdown[0], array($detail[0]->id_privilege), 'class="form-control" placeholder="Privilege"')?>
                      </div>
										</div>
                    
<!--										<div class="control-group">
                      <h4>Struktur Organisasi</h4>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                          <?php 
                          $struktur = $this->global_models->get_dropdown("hrm_settings_level_organisasi", "id_hrm_settings_level_organisasi", "title");
                          print $this->form_eksternal->form_dropdown('id_hrm_settings_level_organisasi', $struktur, 
                            array($detail[0]->id_hrm_settings_level_organisasi), 'class="form-control"')?>
                      </div>
										</div>-->
                    
										<div class="control-group">
                      <h4>Store Region</h4>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                          <?php 
                          $store_region = $this->global_models->get_dropdown("store_region", "id_store_region", "title");
                          print $this->form_eksternal->form_dropdown('id_store_region', $store_region, 
                            array($detail[0]->id_store_region), 'class="form-control"')?>
                      </div>
										</div>
                    <div class="control-group">
                      <h4>Store</h4>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                          <?php 
                          $store = $this->global_models->get_dropdown("store", "id_store", "title");
                          print $this->form_eksternal->form_dropdown('id_store', $store, 
                            array($detail[0]->id_store), 'class="form-control"')?>
                      </div>
										</div>
                    
										<div class="control-group">
                      <h4>Password</h4>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-key"></i></span>
                          <?php print $this->form_eksternal->form_password('pass', '', 'class="form-control" placeholder="Password"')?>
                      </div>
										</div>
                    
										<div class="control-group">
                      <h4>Re-Password</h4>
                      <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-key"></i></span>
                          <?php print $this->form_eksternal->form_password('repass', '', 'class="form-control" placeholder="Re-Password"')?>
                      </div>
										</div>
										<div class="control-group">
											<h4>Status</h4>
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
                      <a href="<?php print site_url("users")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
                  </div>
                </form>
            </div><!-- /.box -->
        </div><!--/.col (left) -->
    </div>   <!-- /.row -->
</section>
<?php
//print_r($detail); die;
?>
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
                    array("id_detail" => $detail[0]->id_tour_settings_region))?>
              <div class="box-body">

                <div class="control-group">
                  <label>Title</label>
                  <?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'class="form-control input-sm" placeholder="Title"');?>
                </div>
                <div class="control-group">
                  <label>Store</label>
                  <?php print $this->form_eksternal->form_dropdown('id_store', $dropdown,array($detail[0]->id_store), 'class="form-control input-sm"');?>
                </div>
                <div class="box-body">

                <div class="control-group">
                  <label>Users</label>
                  <?php print $hasil;?>
                  <div class="input-group margin" id="users-box">
                    <?php 
                    if($detail[0]->id_users){
                      $user = "{$detail[0]->name} <{$detail[0]->email}>";
                    }else{
                      $user = "";
                    }
                    ?>
                      <input type="text" class="form-control" style="width: 170%" id="users" name="users" value="<?php print $user; ?>">
                      <input type="text" class="form-control" id="id_users" name="id_users" value="<?php print $detail[0]->id_users; ?>" style="display: none">
<!--                      <span class="input-group-btn">
                          <a href="javascript:void(0)" class="btn btn-danger btn-flat delete" isi="users-box" >
                            <i class="fa fa-fw fa-times"></i>
                          </a>
                      </span>-->
                  </div>
<!--                  <div id="wadah"></div>-->
                </div>
<!--                <div class="control-group">
                  <br />
                  <a href="javascript:void(0)" id="add-row" class="btn btn-success btn-sm"><i class="fa fa-fw fa-plus"></i></a>
                </div>-->

              </div>
                <div class="box-body">

                <div class="control-group">
                  <label>Region</label>
                  <?php print $hasil2;?>
                  <div class="input-group margin" id="users-box2">
                      <?php print $this->form_eksternal->form_dropdown('region', array("0" =>"Pilih", "1" =>"eropa","2" =>"Africa","3" =>"america","4" => "australia", "5" => "asia", "6" =>"china","7" =>"new zeland" ),$detail[0]->region, ' style="width: 170%" class="  form-control input-sm"');?>
<!--                      <span class="input-group-btn">
                          <a href="javascript:void(0)" class="btn btn-danger btn-flat delete2" isi2="users-box2" >
                            <i class="fa fa-fw fa-times"></i>
                          </a>
                      </span>-->
                  </div>
<!--                  <div id="wadah2"></div>-->
                </div>
<!--                <div class="control-group">
                  <br />
                  <a href="javascript:void(0)" id="add-row2" class="btn btn-success btn-sm"><i class="fa fa-fw fa-plus"></i></a>
                </div>-->

              </div>
              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("store/tour-settings-region")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
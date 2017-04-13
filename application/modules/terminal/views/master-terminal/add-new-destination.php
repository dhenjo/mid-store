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
                    array("id_detail" => $detail[0]->id_master_hotel_city))?>
              <div class="box-body">

                <div class="control-group">
                  <label>Title</label>
                  <?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'class="form-control input-sm" placeholder="Title"');?>
                </div>

                <div class="control-group">
                  <label>Code</label>
                  <?php print $this->form_eksternal->form_input('kode', $detail[0]->kode, 'class="form-control input-sm" placeholder="Code"');?>
                </div>
                  
                <div class="control-group">
                  <label>Nation</label>
                  <?php 
                  $this->global_models->get_connect("terminal");
                  print $this->form_eksternal->form_input("master_hotel_nation", $this->global_models->get_field("master_hotel_nation", "title", 
                    array("id_master_hotel_nation" => $detail[0]->id_master_hotel_nation)), 
                    'id="master_hotel_nation" class="form-control input-sm" placeholder="Nation"', true);
                  print $this->form_eksternal->form_input("id_master_hotel_nation", $detail[0]->id_master_hotel_nation, 
                    'id="id_master_hotel_nation" style="display: none"');
                  $this->global_models->get_connect("default");
                  ?>
                </div>
                  
                <div class="control-group">
                  <label>Detail</label>
                  <?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, 'class="form-control input-sm" id="editor2"')?>
                </div>

              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("terminal/master-terminal/city")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
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
                    array("id_detail" => $detail[0]->id_store))?>
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
                  <label>Sort</label>
                  <?php print $this->form_eksternal->form_input('sort', $detail[0]->sort, 'class="form-control input-sm" placeholder="Sort"');?>
                </div>

                <div class="control-group">
                  <label>Telp</label>
                  <?php print $this->form_eksternal->form_input('telp', $detail[0]->telp, 'class="form-control input-sm" placeholder="Telp"');?>
                </div>

                <div class="control-group">
                  <label>Fax</label>
                  <?php print $this->form_eksternal->form_input('fax', $detail[0]->fax, 'class="form-control input-sm" placeholder="Fax"');?>
                </div>
                
                <div class="control-group">
                  <label>Master Tour</label>
                  <?php print $this->form_eksternal->form_dropdown('master', array(NULL => "Store Biasa", 2 => "Master Tour"),array($detail[0]->master), 'class="form-control input-sm"');?>
                </div>
                
                <div class="control-group">
                  <label>Alamat</label>
                  <?php print $this->form_eksternal->form_textarea('alamat', $detail[0]->alamat, 'class="form-control input-sm" placeholder="Alamat"');?>
                </div>

              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("store/master-store")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
<style>
    .working{
        background:url('<?php print $url?>img/ajax-loader.gif') no-repeat right center;
        background-size: 20px;
    }
</style>
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php 
            $akses = "normal";
            $kunci = TRUE;
            if($detail[0]->status > 1){
              $akses = "view";
              $kunci = FALSE;
            }
            
            if($akses != "view"){
              print $this->form_eksternal->form_open("", 'role="form"', array("id_detail" => $detail[0]->id_mrp_pengajuan_pengadaan));
            }
            ?>
              <div class="box-body">

                <div class="control-group">
                  <label>Title</label>
                  <?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'class="form-control input-sm" placeholder="Title"', $kunci);?>
                </div>
                 
                <?php
                if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "mrp_pengajuan_asset_approver", "edit") !== FALSE){
                ?>
                <div class="control-group">
                  <label>Status</label>
                  <?php 
                  print $this->form_eksternal->form_dropdown("status", array(1 => "Diajukan", 2 => "Diijinkan", 3 => "Ditolak"), 
                    array($detail[0]->status), 'class="form-control input-sm"', $kunci);
                  ?>
                </div>
                <?php
                }
                ?>

                <div class="control-group">
                  <label>Berdasarkan Pengajuan</label>
                  <?php 
                  print $this->form_eksternal->form_input("mrp_pengajuan_asset", $this->global_models->get_field("mrp_pengajuan_asset", "title", 
                    array("id_mrp_pengajuan_asset" => $detail[0]->id_mrp_pengajuan_asset)), 
                    'id="mrp_pengajuan_asset" class="form-control input-sm" placeholder="Pengajuan Penggunaan"', $kunci);
                  print $this->form_eksternal->form_input("id_mrp_pengajuan_asset", $detail[0]->id_mrp_pengajuan_asset, 
                    'id="id_mrp_pengajuan_asset" style="display: none"');
                  ?>
                </div>
                  <div class="control-group">
                    <label style="width: 100%">Items</label>
                    <div id="tambah-items">
                <?php
                if($items){
                  foreach ($items AS $itm){
                    print $this->form_eksternal->form_input('items[]', $itm->items, 
                      'class="form-control input-sm items" style="width: 40%" placeholder="Item"', $kunci)
                      ." Qty ".$this->form_eksternal->form_input('qty[]', $itm->qty, 
                      'class="form-control input-sm items" style="width: 40%" placeholder="Qty"', $kunci);
                    print $this->form_eksternal->form_input('id_mrp_pengajuan_pengadaan_items[]', $itm->id_mrp_pengajuan_pengadaan_items, 
                      'style="display: none"');
                    print "<br />".$this->form_eksternal->form_textarea('note[]', $itm->note, 
                      'style="height: 70px" class="form-control input-sm" placeholder="Note"', $kunci);
                    print "<br />";
                    print "<br />";
                  }
                }
                if($akses != "view"){
                  print $this->form_eksternal->form_input('items[]', "", 'class="form-control input-sm items" style="width: 40%" placeholder="Item"')." Qty ".$this->form_eksternal->form_input('qty[]', "", 'class="form-control input-sm" style="width: 40%" placeholder="Qty"');
                  print "<br />".$this->form_eksternal->form_textarea('note[]', "", 'style="height: 70px" class="form-control input-sm" placeholder="Note"');?>
                    <br />
                    <br />
                    </div>
                    <a href="javascript:void(0)" onclick="tambah_items()" class="btn btn-info"><?php print lang("Add")?></a>
                <?php
                }
                ?>
                </div>

              </div>
              <div class="box-footer">
                  <?php
                  if($akses != "view"){
                  ?>
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <?php }?>
                  <a href="<?php print site_url("mrp/pengajuan-pengadaan")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->

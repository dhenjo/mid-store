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
                    array("id_detail" => $detail[0]->id_tiket_discount_maskapai))?>
              <div class="box-body">

                <div class="control-group">
                  <label>Title</label>
                  <?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'class="form-control input-sm" placeholder="Title"');?>
                </div>
                  
                <div class="control-group">
                  <label>Type</label>
                    <?php print $this->form_eksternal->form_dropdown('type', array('1' => "Persentase", '2' => "Nominal"), array($detail[0]->type), 'class="form-control input-sm"')?>
                </div>

                <div class="control-group">
                  <label>Diskon Nominal</label>
                  <?php print $this->form_eksternal->form_input('nilai', $detail[0]->nilai, 'class="form-control input-sm" placeholder="Diskon Nominal"');?>
                </div>
                  
                <div class="control-group">
                  <label>Maskapai</label>
                    <?php 
                    $maskapai = array(
                        'SJ' => "Sriwijaya Air",
                        'GA' => "Garuda Indonesia",
                        'JT' => "Lion Air",
                        'QZ' => "Air Asia 1",
                        'AK' => "Air Asia 2",
                        'QG' => "Citilink",
                        'ID' => "Batik Air",
                        'IW' => "Wings Air",
                      );
                    print $this->form_eksternal->form_dropdown('maskapai', $maskapai, array($detail[0]->maskapai), 'class="form-control input-sm"');?>
                </div>

                <div class="control-group">
                  <label>Start</label>
                  <?php print $this->form_eksternal->form_input('periodestart', $detail[0]->mulai, 
                    'id="start_date" class="form-control input-sm" placeholder="Periode Start"');?>
                </div>

                <div class="control-group">
                  <label>End</label>
                  <?php print $this->form_eksternal->form_input('periodeend', $detail[0]->akhir, 
                    'id="end_date" class="form-control input-sm" placeholder="Periode End"');?>
                </div>

                <div class="control-group">
                  <label>Status</label>
                    <?php print $this->form_eksternal->form_dropdown('status', array('1' => "Aktif", '2' => "Draft"), array($detail[0]->status), 'class="form-control input-sm"')?>
                </div>
                  
                <div class="control-group">
                  <label>Detail</label>
                  <?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, 'class="form-control input-sm" id="editor2"')?>
                </div>

              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("diskon/diskon-mega")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
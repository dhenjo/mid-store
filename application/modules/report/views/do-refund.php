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
                      array("id_tiket_issued" => $detail[0]->id_tiket_issued))?>
                  <div class="box-body">
                    <div class="control-group">
                      Anda Yakin Akan Melakukan Refund Terhadap Tiket : <?php print $detail[0]->issued_no?> <br />
                      Pemesan : <?php print $detail[0]->first_name." ".$detail[0]->last_name?> <br />
                      Email : <?php print $detail[0]->email?> <br />
                      Telp : <?php print $detail[0]->telphone?> <br />
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


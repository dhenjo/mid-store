<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
              <div class="box-body">
                <?php
                if($data[0]->file){
                  $url = base_url()."files/antavaya/product_tour/".$data[0]->file;
                    print "<center><a href='javascript:void(0)'><img src='{$url}' alt=''></a></center>";
                }
                if($data[0]->days > 0){
                  $day = $data[0]->days." Hari / ";
                }else{
                  $day ="";
                }
                if($data[0]->night > 0){
                  $night = $data[0]->night." Malam - ";
                }else{
                  $night ="";
                }
                if($data[0]->airlines){
                  $irln = $data[0]->airlines;
                }else{
                  $irln ="";
                }
                ?>
                <h1><center> <?php print $data[0]->title; ?><br></center></h1>
                <h4><center><?php print $day.$night.$irln; ?><br><?php print $data[0]->destination; ?><br><?php print $data[0]->landmark; ?></center></h4> 
                <br>
                
                <div class="control-group" style="word-wrap: break-word">
                 <?php print $data[0]->note; ?>
                </div>
                 <br>
                 <div class="control-group">
                      <label>Product Tour Information</label>
                      <div class="box-body table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                          <th>Tanggal</th>
                          <th>Dewasa <br /> Triple / <br /> Twin</th>
                          <th>Child <br /> Twin Bed</th>
                          <th>Child <br /> Extra Bed</th>
                          <th>Child <br /> No Bed</th>
                          <th>SGL <br /> SUPP</th>
                          <th>Airport <br /> Tax & <br /> Flight</th>
                          <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($information as $inf) {
                          $start_date = date("d M Y", strtotime($inf['start_date']));
                          print "<tr>"
                            . "<td>{$start_date}</td>"
                            . "<td style='text-align:right'>".number_format($inf['price']['adult_triple_twin'],2,".",",")."</td>"
                            . "<td style='text-align:right'>".number_format($inf['price']['child_twin_bed'],2,".",",")."</td>"
                            . "<td style='text-align:right'>".number_format($inf['price']['child_extra_bed'],2,".",",")."</td>"
                            . "<td style='text-align:right'>".number_format($inf['price']['child_no_bed'],2,".",",")."</td>"
                            . "<td style='text-align:right'>".number_format($inf['price']['sgl_supp'],2,".",",")."</td>"
                            . "<td style='text-align:right'>".number_format($inf['price']['airport_tax'],2,".",",")."</td>"
                            . "<td>"
                              . "<div class='btn-group'>"
                                . "<button type='button' class='btn btn-info tour-edit' id='tour-edit'  isi='{$inf['id_product_tour_information']}' data-toggle='modal' data-target='#compose-modal'><i class='fa fa-edit'></i></button>"
//                                . "<button type='button' class='btn btn-danger'><i class='fa fa-times'></i></button>"
                              . "</div>"
                            . "</td>"
                          . "</tr>";
                        }
                        ?>
                    </tbody>
                        <tfoot>
                         
                        </tfoot>
                      </table>
                </div>
              </div>
                 
                 
<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-calendar"></i> Schedule Tour</h4>
            </div>
            
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode PS</label>
                        <?php print $this->form_eksternal->form_input('kode_ps', "",'id="kode_ps" class="form-control input-sm"  placeholder="Kode Tour Information"'); ?>
                        <?php print $this->form_eksternal->form_input('id_product_tour_information', "",'id="id_product_tour_information" style="display: none"'); ?>
                        <?php print $this->form_eksternal->form_input('id_product_tour', $detail[0]->id_product_tour,'id="id_product_tour" style="display: none"'); ?>
                        <?php print $this->form_eksternal->form_input('data_id_product_tour', $detail[0]->id_product_tour,' style="display: none"'); ?>
                    </div>
                    <div class="form-group">
                        <label>Tanggal <small style="font-weight: normal">keberangkatan, waktu keberangkatan, tiba, waktu tiba</small></label>
                        <div class="row">
                            <div class="col-xs-4">
                                <?php print $this->form_eksternal->form_input('start_date', "",'onchange="changedate()" id="start_date_0" class="start_date form-control input-sm"  placeholder="Start Date"'); ?>
                            </div>
                            <div class="col-xs-2">
                                <?php print $this->form_eksternal->form_input('etd', "",' id="start_time_1" class="start_time form-control input-sm"  placeholder="ETD"'); ?>
                            </div>
                            <div class="col-xs-4">
                                <?php print $this->form_eksternal->form_input('end_date', "",'id="end_date_0" class="end_date form-control input-sm" placeholder="End Date"'); ?>
                            </div>
                            <div class="col-xs-2">
                                <?php print $this->form_eksternal->form_input('eta', "",' id="end_time_1" class="start_time form-control input-sm"  placeholder="ETA"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Seat</label>
                        <?php print $this->form_eksternal->form_input('available_seat', "", 'id="available_seat" class="form-control input-sm"  placeholder="Total Seat"'); ?>
                    </div>
                    <div class="form-group">
                        <label>Flight <small style="font-weight: normal">no flight, in, out</small></label>
                        <div class="row">
                            <div class="col-xs-4">
                                <?php print $this->form_eksternal->form_input('flt', "", 'id="flt" class="form-control input-sm"  placeholder="FLT"');?>
                            </div>
                            <div class="col-xs-4">
                                <?php print $this->form_eksternal->form_input('in', "", 'id="in" class="form-control input-sm"  placeholder="IN"');?>
                            </div>
                            <div class="col-xs-4">
                                <?php print $this->form_eksternal->form_input('out', "", 'id="out" class="form-control input-sm"  placeholder="OUT"');?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Adult Price <small style="font-weight: normal">mata uang, triple twin, sgl supp</small></label>
                        <div class="row">
                            <div class="col-xs-4">
                                <?php 
                                print $this->form_eksternal->form_dropdown('id_currency', $dropdown, array(2), 'id="id_currency" class="form-control" placeholder="Currency"');?>
                            </div>
                            <div class="col-xs-4">
                                <?php print $this->form_eksternal->form_input('adult_triple_twin', "", 'id="adult_triple_twin" class="uang form-control input-sm"  placeholder="Adult Triple/Twin"');?>
                            </div>
                            <div class="col-xs-4">
                                <?php print $this->form_eksternal->form_input('sgl_supp', "", 'id="sgl_supp" class="uang form-control input-sm"  placeholder="SGL SUPP"');?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Child Price <small style="font-weight: normal">twin bed, extra bed, no bed</small></label>
                        <div class="row">
                            <div class="col-xs-4">
                                <?php print $this->form_eksternal->form_input('child_twin_bed', "", 'id="child_twin_bed" class="uang form-control input-sm"  placeholder="Child Twin Bed"');?>
                            </div>
                            <div class="col-xs-4">
                                <?php print $this->form_eksternal->form_input('child_extra_bed', "", 'id="child_extra_bed" class="uang form-control input-sm"  placeholder="Child Extra Bed"');?>
                            </div>
                            <div class="col-xs-4">
                                <?php print $this->form_eksternal->form_input('child_no_bed', "", 'id="child_no_bed" class="uang form-control input-sm"  placeholder="Child No Bed"');?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Other Price <small style="font-weight: normal">visa, airport tax</small></label>
                        <div class="row">
                            <div class="col-xs-6">
                                <?php print $this->form_eksternal->form_input('visa', "", 'id="visa" class="uang form-control input-sm"  placeholder="Harga Visa"');?>
                            </div>
                            <div class="col-xs-6">
                                <?php print $this->form_eksternal->form_input('airport_tax', "", 'id="airport_tax" class="uang form-control input-sm"  placeholder="Airport Tax & Flight Insurance"');?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="display:none">
                        <label>Discount</label>
                        <div class="row" >
                            <div class="col-xs-6">
                                <?php print $this->form_eksternal->form_dropdown('stnb_discount_tetap', $dropdown_disc, "",  'onchange="tambah_discount()" id="stnb_discount_tetap" class="form-control input-sm"');?>
                            </div>
                            
                        </div>
                    </div>
                  <span id="tambah-items-discount">
                    </span>
                </div>
                <div class="modal-footer clearfix">
<!--
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    -->
                      <?php
//                    if(!$detail[0]->id_product_tour){
//                      print '<a href="javascript:void(0)" id="save-schedule" class="btn btn-primary pull-left"> Save</a>';
//                    }
//                    else{
                 //     print '<button type="submit" class="btn btn-primary pull-left"> Save</button>';
//                    }
                    ?>
                </div>
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
                  </div>
              </div>
              
        </div>
    </div>
</div>  
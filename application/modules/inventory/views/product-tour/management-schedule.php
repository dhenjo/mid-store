
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            
              <div class="control-group">
                      <table width="100%" class="table">
                        <tr>
                          <th></th>
                          <th>Kode PS</th>
                          <th>Tanggal</th>
                          <th>Seat</th>
                          <th>Flight</th>
                          <th>Price</th>
                          <th>Action</th>
                        </tr>
                        <?php
                        foreach ($info as $jk => $inf) {
                          if($jk%2 == 0){
                            $bg = "background-color: lightgray;";
                          }
                          else{
                            $bg = "";
                          }
                          if($inf->status < 2){
                            $bg = "background-color: #f69e9e;";
//                            $delete = "<a href='".site_url("inventory/product-tour/undo-delete/{$inf->id_product_tour_information}")."' class='btn btn-danger tour-delete' isi='{$inf->id_product_tour_information}' id='delete-schedule'><i class='fa fa-undo'></i></a>";
                          }
                          else{
                            $delete = "";
                          }
                          print "<tr style='{$bg}'>"
                            . "<td><input type='checkbox' class='flat-red'/></td>"
                            . "<td>{$inf->kode_ps}</td>"
                            . "<td>{$inf->start_date}</td>"
                            . "<td>{$inf->available_seat}</td>"
                            . "<td>{$inf->flt}</td>"
                            . "<td>".number_format($inf->adult_triple_twin,2,".",",")."</td>"
                            . "<td>"
                              . "<div class='btn-group'>"
                                . "<button type='button' class='btn btn-info tour-edit' isi='{$inf->id_product_tour_information}' data-toggle='modal' data-target='#compose-modal' id='edit-schedule'><i class='fa fa-edit'></i></button>"
                                . "<button type='button' class='btn btn-info tour-copy' isi='{$inf->id_product_tour_information}' data-toggle='modal' data-target='#compose-modal' id='copy-schedule'><i class='fa fa-copy'></i></button>"
                                . "{$delete}"
//                                . "<button type='button' class='btn btn-danger'><i class='fa fa-times'></i></button>"
                              . "</div>"
                            . "</td>"
                          . "</tr>";
                        }
                        ?>
                        <tfoot>
                          <tr>
                            <td colspan="6">
                              <button type='button' class='btn btn-success' data-toggle='modal' id="add-schedule" data-target='#compose-modal'><i class='fa fa-plus'></i></button>
                              <a href='<?php print site_url("inventory/product-tour/delete/{$inf->id_product_tour_information}")?>' class='btn btn-danger tour-delete' isi='<?php print $inf->id_product_tour_information ?>' id='delete-schedule'><i class='fa fa-times'></i></a>
                              
                              <a href='<?php print site_url("inventory/product-tour/undo-delete/{$inf->id_product_tour_information}")?>' class='btn btn-warning tour-delete' isi='<?php print $inf->id_product_tour_information ?>' id='delete-schedule'><i class='fa fa-undo'></i></a>
                            </td>
                          </tr>
                        </tfoot>
                      </table>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->

<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-calendar"></i> Schedule Tour</h4>
            </div>
            <?php print $this->form_eksternal->form_open("inventory/product-tour/tour-schedule", 'role="form"')?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode PS</label>
                        <?php print $this->form_eksternal->form_input('kode_ps', "",'id="kode_ps" class="form-control input-sm"  placeholder="Kode Tour Information"'); ?>
                        <?php print $this->form_eksternal->form_input('id_product_tour_information', "",'id="id_product_tour_information" style="display: none"'); ?>
                        <?php print $this->form_eksternal->form_input('id_product_tour', $detail[0]->id_product_tour,'id="id_product_tour" style="display: none"'); ?>
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
                    <div class="form-group">
                        <label>Discount</label>
                        <div class="row">
                            <div class="col-xs-6">
                                <?php print $this->form_eksternal->form_dropdown('stnb_discount_tetap', $status_nb, "", 'id="stnb_discount_tetap" class="form-control input-sm"');?>
                            </div>
                            <div class="col-xs-6">
                                <?php print $this->form_eksternal->form_input('discount_tetap', "", 'id="discount_tetap" class="uang form-control input-sm" placeholder="Discount Tetap"');?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer clearfix">

                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <?php
//                    if(!$detail[0]->id_product_tour){
//                      print '<a href="javascript:void(0)" id="save-schedule" class="btn btn-primary pull-left"> Save</a>';
//                    }
//                    else{
                      print '<button type="submit" class="btn btn-primary pull-left"> Save</button>';
//                    }
                    ?>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php print $this->form_eksternal->form_open_multipart("", 'role="form"', 
                    array("id_detail" => $detail[0]->id_product_tour))?>
              <div class="box-body">
                <?php
                if($detail[0]->file){
                    print "<center><a href='javascript:void(0)'><img src='".base_url()."files/antavaya/product_tour/{$detail[0]->file}' alt=''></a></center>";
                }
                ?>
                <h1><center> <?php print $detail[0]->title; ?></center></h1>
                <h4><center> <?php print $detail[0]->sub_title; ?><br><?php print $detail[0]->summary; ?></center></h4>
                <h5><center> <?php print $detail[0]->sub_title; ?><br><?php print $detail[0]->summary; ?></center></h5>
              
                <br>
                
                <div class="control-group">
                 <?php print $detail[0]->note;?>
                </div>
                 <br>
                 <div class="control-group">
                      <label>Product Tour Information</label>
                      <table id="tablePrice" border="0" cellpadding="0" cellspacing="0">
                         <tbody>
                         <?php 
                            foreach ($info as $in) { ?>

                             <tr>
                                <td style="width: 50%;">Adult Twin Share</td>
                                <td style="text-align: right; width: 15%;"><?php print number_format($in->price_adult_twin, 0, ".", ",");?></td>
                             </tr>
                            <tr>                                                                                       
                                <td style="width: 50%;">Child Twin Share</td>
                                <td style="text-align: right; width: 15%;"><?php print number_format($in->price_child_twin, 0, ".", ",");?></td>
                            </tr>
                            <tr>                                                                                             
                                <td style="width: 50%;">Child With Extra Bed</td>
                                <td style="text-align: right; width: 15%;"><?php print number_format($in->price_child_with_extra_bed, 0, ".", ",");?></td>
                            </tr>                                 
                            <tr>                                                                                          
                                <td style="width: 50%;">Child Without Bed</td>
                                <td style="text-align: right; width: 15%;"><?php print number_format($in->price_child_without_bed, 0, ".", ",");?></td>
                            </tr>                              
                            
                            <tr>                                                                                         
                                <td style="width: 50%;"><b>Date</b></td>
                                <td style="text-align: right; width: 15%;"><?php print $in->start_date; ?></td>
                                <td style="text-align: center; width: 10%;"><?php print $in->end_date; ?></td></b>
                            </tr>
                            <?php } ?>          
                                </tbody>
                                </table>
                  </div>
                 <br>
                  <div class="control-group">
                      <label>Tour Date</label>
                      <select name="date" class="form-control input-sm">
                          <?php foreach ($info as $value) { ?>
                          <option value="<?php print $value->id_product_tour_information?>"><?php print $value->start_date." || ".$value->end_date." || Available Seat: ".$value->available_seat; ?></option>       
                          
                                        <?php  }?>
                        </select>
                  </div>
                 
                  </div>
              </div>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("inventory/product-tour")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <div class="pull-right box-tools">
                  <?php if($data[0]->status_log != 2){ ?>
                    <button class="btn btn-info btn-sm" data-toggle="tooltip" title="" data-original-title="Use It">Use It</button>
                  <?php }?>
                </div><!-- /. tools -->
            </div>
          
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
                      <h4>Product Tour Information</h4>
                      <hr />
                    <?php
                   
                    foreach($information AS $inf){
                      
                    ?>
                        <div class="row">
                        <div class="col-md-4">
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <div class="input-group">
                                            <?php print date("d M Y", strtotime($inf->start_date)); ?>
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>ETD</label>
                                        <?php
                                        $etd = date("H:i", strtotime($inf->start_time));
                                        ?>
                                        <div class="input-group">
                                            <?php print $etd; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <div class="input-group">
                                            <?php print date("d M Y", strtotime($inf->end_date)); ?>
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>ETA</label>
                                        <?php
                                        $eta = date("H:i", strtotime($inf->end_time));
                                        ?>
                                        <div class="input-group">
                                            <?php print $eta; ?>
                                        </div>
                                    </div>
                                  
                                  <div class="form-group">
                                        <label>Available Seat</label>
                                        <div class="input-group">
                                            <?php print $inf->available_seat; ?>
                                        </div>
                                    </div>
                                  
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box box-primary">
                                <div class="box-body">
                                  <div class="form-group">
                                        <label>FLT</label>
                                        <div class="input-group">
                                   <?php print $inf->flt; ?>         
                                        </div>
                                    </div>
                                 
                                  <div class="form-group">
                                        <label>IN/OUT</label>
                                        <div class="input-group">
                                    <?php print $inf->in."/".$inf->out; ?>          
                                        </div>
                                    </div>
                                   <div class="form-group">
                                        <label>Currency</label>
                                        <div class="input-group">
                                    <?php print $dropdown[$inf->id_currency];?>        
                                        </div><!-- /.input group -->
                                    </div>
                                  <div class="form-group">
                                        <label>Adult Triple/Twin</label>
                                        <div class="input-group">
                                    <?php print number_format($inf->adult_triple_twin);?>        
                                        </div><!-- /.input group -->
                                    </div>
                                    <div class="form-group">
                                        <label>Child Twin Bed</label>
                                        <div class="input-group">
                                    <?php print number_format($inf->child_twin_bed);?>        
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Child Extra Bed</label>
                                        <div class="input-group">
                                    <?php print number_format($inf->child_extra_bed); ?>        
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    <div class="col-md-4">
                      <?php
                      $status_nb = array(1 => "Persen (%)",
                                      2 => "Nominal");
                      ?>
                            <div class="box box-primary">
                                <div class="box-body">
                                  <div class="form-group">
                                        <label>Child No Bed</label>
                                        <div class="input-group">
                                    <?php print number_format($inf->child_no_bed); ?>        
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>SGL SUPP</label>
                                        <div class="input-group">
                                    <?php print number_format($inf->sgl_supp); ?>        
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Airport Tax & Flight Insurance</label>
                                        <div class="input-group">
                                    <?php print number_format($inf->airport_tax); ?>        
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Discount Tetap</label>
                                        <div class="input-group">
                                    <?php print $status_nb[$inf->stnb_discount_tetap].": ".$inf->discount_tetap;?>        
                                        </div><!-- /.input group -->
                                    </div><!-- /.form group -->
                                    
                                 <!--   <div class="form-group">
                                        <label>Discount Tambahan</label>
                                        <div class="input-group">
                                    <?php print $status_nb[$inf->stnb_discount_tambahan].": ".$inf->discount_tambahan;?>        
                                        </div>
                                    </div> -->
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                 ?>
                 </div> 
             
                  </div>
              </div>
              
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->

<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php print $this->form_eksternal->form_open_multipart("", 'role="form"')?>
              <div class="box-body">
                <div class="control-group">
                  <label>Division</label>
                  <?php print $this->form_eksternal->form_dropdown('division', array(1 => "Leisure", 2 => "Umroh", 3=> "FIT"), array($detail[0]->division), 'class="form-control input-sm" id="division-utama"');?>
                </div>
                <div class="control-group">
                  <label>No. Product News</label>
                  <?php print $this->form_eksternal->form_input('no_pn', $detail[0]->no_pn, 'id="no-pn-utama" class="form-control input-sm" placeholder="No. Product News"');?>
                  <?php print $this->form_eksternal->form_input('id_detail', $detail[0]->id_product_tour, 'style="display: none" id="id-product-tour-utama"');?>
                </div>
                
                <div class="control-group">
                  <label>Name Tour</label>
                  <?php print $this->form_eksternal->form_input('title', $detail[0]->title, 'id="title-utama" class="form-control input-sm" placeholder="Name Tour"');?>
                </div>
                <div class="control-group">
                  <label>Product Cabang</label>
                  <?php print $this->form_eksternal->form_input('product_cabang', $detail[0]->product_cabang, 'id="product-cabang" class="form-control input-sm" placeholder="Product Cabang"');?>
                </div>
				<div class="control-group">
                  <label>Selling Poin</label>
                  <?php print $this->form_eksternal->form_input('selling_poin', $detail[0]->selling_poin, 'id="selling-poin-utama" class="form-control input-sm" placeholder="Selling Poin"');?>
                </div>
                <div class="control-group">
                  <label>Kota-Kota Tujuan</label>
                  <?php print $this->form_eksternal->form_input('destination', $detail[0]->destination, 'id="destination-utama" class="form-control input-sm" placeholder="Kota-Kota Tujuan"');?>
                </div>
                
               <!-- <div class="control-group">
                  <label>Tour Termasuk</label>
                  <?php print $this->form_eksternal->form_input('summary', $detail[0]->summary, 'id="summary-utama" class="form-control input-sm" placeholder="Tour Termasuk"');?>
                </div> -->
                <div class="control-group">
                  <label>Tour Termasuk</label>
                  <?php print $this->form_eksternal->form_input('landmark', $detail[0]->landmark, 'id="landmark-utama" class="form-control input-sm" placeholder="Tour Termasuk"');?>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                  <label>Days</label>
                  <div class="input-group">
                      <?php print $this->form_eksternal->form_input('days', $detail[0]->days, ' id="tot_days" class="form-control input-sm"  placeholder="DAYS"'); ?>
                  </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                  <label>Night</label>
                  <div class="input-group">
                      <?php print $this->form_eksternal->form_input('night', $detail[0]->night, 'id="night-utama" class="form-control input-sm"  placeholder="Night"'); ?>
                  </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                  <label>Airline</label>
                  <div class="input-group">
                      <?php print $this->form_eksternal->form_input('airlines', $detail[0]->airlines, 'id="airlines-utama" style="width:200%" class="form-control input-sm"  placeholder="Airline"'); ?>
                  </div>
                  </div>
                </div>
                <div class="col-md-4">
                <div class="control-group">
                  <label>Season</label>
                  <?php print $this->form_eksternal->form_dropdown('category', array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period"), array($detail[0]->category), 'id="category-utama" class="form-control input-sm"');?>
                </div>
                  </div>
				  <div class="col-md-4">
                <div class="control-group">
                  <label>Category Product</label>
                  <?php print $this->form_eksternal->form_dropdown('category_product', array(1 => "Viesta", 2 => "Premium"), array($detail[0]->category_product), 'id="category-product" class="form-control input-sm"');?>
                </div>
                  </div>
                  <div class="col-md-4">
                <div class="control-group">
                  <label>Region</label>
                  <?php print $this->form_eksternal->form_dropdown('sub_category', array(1 => "Eropa", 2 => "Africa", 3 => "America", 4 => "Australia", 5 => "Asia", 6 => "China", 7 => "New Zealand" ), array($detail[0]->sub_category), 'id="sub-category-utama" class="form-control input-sm"');?>
                </div>
               </div>
                <div class="control-group">
                  <label>Gambar</label>
                  <?php print $this->form_eksternal->form_upload('file', $detail[0]->file, "class='form-control input-sm'");
                  if($detail[0]->file)
                    print "<br /><img src='".base_url()."files/antavaya/product_tour/{$detail[0]->file}' width='100' />";
                  else
                    print "<br /><img src='".base_url()."files/no-pic.png' width='50' />";
                  ?>
                </div>
                  
                <div class="control-group">
                  <label>Gambar Thumb</label>
                  <?php print $this->form_eksternal->form_upload('file_thumb', $detail[0]->file_thumb, "class='form-control input-sm'");
                  if($detail[0]->file_thumb)
                    print "<br /><img src='".base_url()."files/antavaya/product_tour/{$detail[0]->file_thumb}' width='100' />";
                  else
                    print "<br /><img src='".base_url()."files/no-pic.png' width='50' />";
                  ?>
                </div>
               
                <div class="control-group">
                  <label>File Itin</label>
                  <?php print $this->form_eksternal->form_upload('file_itin', $detail[0]->file_itin, "class='form-control input-sm'");
                  if($detail[0]->file_itin)
                    print "<br /><a href='".base_url()."files/antavaya/product_tour/{$detail[0]->file_itin}'>{$detail[0]->file_itin}</a>";
                  ?>
                </div>
               
                  <br>
                  <div class="control-group">
                  <label>Publish In Website</label>
											<div class="input-group">
                        <div class="checkbox">
                            <label>
                                <?php
                                if($detail[0]->push_selling == 1)
                                  print $this->form_eksternal->form_checkbox('push_selling', 1, TRUE);
                                else
                                  print $this->form_eksternal->form_checkbox('push_selling', 1, FALSE);
                                ?>
                                Active
                            </label>
                        </div>
											</div>
										</div>
                  <!--<br> <br>-->
                  
           <?php 
//           if($info){
//             $no = 0;
//           foreach ($info as $inf) {
//             $no = $no + 1;
           ?>           
<!--                   <div class="row">
                        <div class="col-md-4">
                            <div class="box box-primary">
                                <div class="box-body">
                                  <div class="form-group">
                                        <label>Kode PS</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('kode_ps[]', $inf->kode_ps,' class="form-control input-sm "  placeholder="Kode PS"'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Start Date<span class="type_bedq"></span></label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('start_date[]', $inf->start_date,'id="start_date'.$no.'" onchange="changedate2()" class="start_date" class="form-control input-sm"  placeholder="Start Date"'); ?>
                                        </div>
                                    </div>
                                  <div class="form-group">
                                    <?php $etd = date("H:i", strtotime($inf->start_time)); ?>
                                        <label>ETD</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('etd[]', $etd,' class="start_time" id="start_time_1" class="form-control input-sm"  placeholder="ETD"'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('end_date[]', $inf->end_date,'id="end_date'.$no.'" class="end_date" class="form-control input-sm" placeholder="End Date"'); ?>
                                        </div>
                                    </div>
                                  <div class="form-group">
                                    <?php $eta = date("H:i", strtotime($inf->end_time)); ?>
                                        <label>ETA</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('eta[]', $eta,' class="start_time" id="end_time_1" class="form-control input-sm"  placeholder="ETD"'); ?>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label>Total Seat</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('available_seat[]', $inf->available_seat, 'class="form-control input-sm"  placeholder="Total Seat"'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_dropdown('id_currency[]', $dropdown, array($inf->id_currency), 'class="form-control" placeholder="Currency"');?>        
                                        </div> /.input group 
                                    </div> /.form group 
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box box-primary">
                                <div class="box-body">
                                  <div class="form-group">
                                     <div class="form-group">
                                        <label>FLT</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('flt[]', $inf->flt, 'class="form-control input-sm"  placeholder="FLT"');?>        
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>IN</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('in[]', $inf->in, 'class="form-control input-sm"  placeholder="IN"');?>        
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>OUT</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('out[]', $inf->out, 'class="form-control input-sm"  placeholder="OUT"');?>        
                                        </div>
                                    </div>
                                        <label>Adult Triple/Twin</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('adult_triple_twin[]', number_format($inf->adult_triple_twin,2,".",","), ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Adult Triple/Twin"');?>        
                                        </div> /.input group 
                                    </div>
                                    <div class="form-group">
                                        <label>Child Twin Bed</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('child_twin_bed[]', number_format($inf->child_twin_bed,2,".",","), ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child Twin Bed"');?>        
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Child Extra Bed</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('child_extra_bed[]', number_format($inf->child_extra_bed,2,".",","), ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child Extra Bed"');?>        
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
                                    <?php print $this->form_eksternal->form_input('child_no_bed[]', number_format($inf->child_no_bed,2,".",","), ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child No Bed"');?>        
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>SGL SUPP</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('sgl_supp[]', number_format($inf->sgl_supp,2,".",","), ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="SGL SUPP"');?>        
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Airport Tax & Flight Insurance</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('airport_tax[]', number_format($inf->airport_tax,2,".",","), ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Airport Tax & Flight Insurance"');?>        
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>Harga Visa</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('visa[]', number_format($inf->visa,2,".",","), ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Harga Visa"');?>        
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label>DP</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_dropdown('stnb_dp[]', $status_nb, $inf->stnb_dp, 'class="form-control" style="width:60%"');?><?php print $this->form_eksternal->form_input('dp[]', $inf->dp, 'style="width: 40%" class="form-control input-sm" placeholder="DP"'); ?>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label>Discount Tetap</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_dropdown('stnb_discount_tetap[]', $status_nb, $inf->stnb_discount_tetap, 'class="form-control" style="width:60%"');?><?php print $this->form_eksternal->form_input('discount_tetap[]', $inf->discount_tetap, 'style="width: 40%" class="form-control input-sm" placeholder="Discount Tetap"');?>        
                                        </div> /.input group 
                                    </div> /.form group 
                                    
                                    <div class="form-group">
                                        <label>Discount Tambahan</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_dropdown('stnb_discount_tambahan[]', $status_nb, $inf->stnb_discount_tambahan, 'class="form-control" style="width:60%"');?><?php print $this->form_eksternal->form_input('discount_tambahan[]', $inf->discount_tambahan, 'style="width: 40%" class="form-control input-sm" placeholder="Discount Tambahan"');?>        
                                        </div>
                                    </div> 
                                    
                                </div>
                              <a href="javascript:void(0)" onclick="copy_tambah_items(<?php print $inf->id_product_tour_information; ?>)" class="btn btn-info"><?php print lang("Copy")?></a>
                    <br><br> 
                            </div>
                        </div>
                    </div>-->
              <?php 
//               print $this->form_eksternal->form_input('id_product_tour_information[]', $inf->id_product_tour_information, 
//                      'style="display: none"');
//                   print $this->form_eksternal->form_input('kode[]', $inf->kode, 
//                      'style="display: none"');
//              } }else{ ?>  
             
<!--             <div class="row">
                        <div class="col-md-4">
                            <div class="box box-primary">
                                <div class="box-body">
                                  <div class="form-group">
                                        <label>Kode PS</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('kode_ps[]', "",' class="form-control input-sm"  placeholder="Kode Tour Information"'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('start_date[]', "",'onchange="changedate()" id="start_date_0" class="start_date" id="start_date_1" class="form-control input-sm"  placeholder="Start Date"'); ?>
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>ETD</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('etd[]', "",' class="start_time" id="start_time_1" class="form-control input-sm"  placeholder="ETD"'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('end_date[]', "",'class="end_date" id="end_date_0" class="form-control input-sm" placeholder="End Date"'); ?>
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>ETA</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('eta[]', "",' class="start_time" id="end_time_1" class="form-control input-sm"  placeholder="ETA"'); ?>
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>Days</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('days[]', "", 'class="form-control input-sm"  placeholder="DAYS"'); ?>
                                        </div>
                                    </div> 
                                     <div class="form-group">
                                        <label>Total Seat</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_input('available_seat[]', "", 'class="form-control input-sm"  placeholder="Total Seat"'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_dropdown('id_currency[]', $dropdown, "", 'class="form-control" placeholder="Currency"');?>        
                                        </div> /.input group 
                                    </div> /.form group 
                               
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box box-primary">
                                <div class="box-body">
                                  <div class="form-group">
                                        <label>FLT</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('flt[]', "", 'class="form-control input-sm"  placeholder="FLT"');?>        
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>IN</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('in[]', "", 'class="form-control input-sm"  placeholder="IN"');?>        
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>OUT</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('out[]', "", 'class="form-control input-sm"  placeholder="OUT"');?>        
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>Adult Triple/Twin</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('adult_triple_twin[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Adult Triple/Twin"');?>        
                                        </div> /.input group 
                                    </div>
                                    <div class="form-group">
                                        <label>Child Twin Bed</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('child_twin_bed[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child Twin Bed"');?>        
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Child Extra Bed</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('child_extra_bed[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child Extra Bed"');?>        
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
                                    <?php print $this->form_eksternal->form_input('child_no_bed[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child No Bed"');?>        
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>SGL SUPP</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('sgl_supp[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="SGL SUPP"');?>        
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Airport Tax & Flight Insurance</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('airport_tax[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Airport Tax & Flight Insurance"');?>        
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label>Harga Visa</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_input('visa[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Harga Visa"');?>        
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label>DP</label>
                                        <div class="input-group">
                                            <?php print $this->form_eksternal->form_dropdown('stnb_dp[]', $status_nb, "", 'class="form-control" style="width:60%"');?><?php print $this->form_eksternal->form_input('dp[]', "", 'style="width: 40%" class="form-control input-sm" placeholder="DP"'); ?>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label>Discount Tetap</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_dropdown('stnb_discount_tetap[]', $status_nb, "", 'class="form-control" style="width:60%"');?><?php print $this->form_eksternal->form_input('discount_tetap[]', "", 'style="width: 40%" class="form-control input-sm" placeholder="Discount Tetap"');?>        
                                        </div> /.input group 
                                    </div> /.form group 
                                    
                                    <div class="form-group">
                                        <label>Discount Tambahan</label>
                                        <div class="input-group">
                                    <?php print $this->form_eksternal->form_dropdown('stnb_discount_tambahan[]', $status_nb, "", 'class="form-control" style="width:60%"');?><?php print $this->form_eksternal->form_input('discount_tambahan[]', "", 'style="width: 40%" class="form-control input-sm" placeholder="Discount Tambahan"');?>        
                                        </div>
                                    </div> 
                                    
                                </div>
                            </div>
                        </div>
                    </div>-->
                      <?php // } ?>
<!--                      <span id="copy-tambah-items">
                    </span>
                   <span id="tambah-items">
                    </span>
             <a href="javascript:void(0)" onclick="tambah_items()" class="btn btn-info"><?php print lang("Add")?></a>-->
                    <!--<br><br>-->   
                <div class="control-group">
                  <label>Detail</label>
                  <?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, 'class="form-control input-sm" id="editor2"')?>
                </div><br>
                <div class="control-group">
                  <label>ToC</label>
                  <?php print $this->form_eksternal->form_textarea('toc', $detail[0]->toc, 'class="form-control input-sm" id="editor3"')?>
                </div><br>
                  <div class="col-md-3">  
                <div class="control-group">
                  <label>Status</label>
                  <?php print $this->form_eksternal->form_dropdown('status', array(2 => "Draft", 1 => "Publish"), array($detail[0]->status), 'class="form-control input-sm"');?>
                </div>
                    </div>
                  <div class="col-md-3">  
                <div class="control-group">
                  <label>Hot Deal</label><br />
                  <?php print $this->form_eksternal->form_checkbox('hot_deal', 2, $check_hot, 'class="form-control input-sm"');?> checked
                </div>
                    </div>
              </div>
                  <br><br>
                  <br><br>
              <div class="control-group">
                      <label>Product Tour Schedule</label>
                <div class="box-body table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                          <th>Tanggal<br>And<br>Condition</th>
						  <th>Kode<br>Tour<br>Schedule<br>And<br>Status</th>
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
						$st_tampil = array("1" => "<span style='color:black' class='label label-success'>Publish</span>",
						"2" => "<span style='color:black' class='label label-warning'>Draft</span>", "3" => "Delete");
            
            $st_condition = array("1" => "<span style='color:black' class='label label-success'>Available</span>",
						"5" => "<span style='color:black' class='label label-warning'>Push Selling</span>");
                        foreach ($info as $inf) {
	                        
	                        if($inf->at_airport_date == "0000-00-00" OR $inf->at_airport_date == ""){
                                $st_date1 = $inf->start_date;
                            }else{
                                $st_date1 = $inf->at_airport_date;
                            }
                            
							$start_date= date("d M Y", strtotime($st_date1))."<br>".$st_condition[$inf->status];
							$dt_status = $inf->kode."<br>".$st_tampil[$inf->tampil]."";
                          print "<tr>"
                             . "<td>{$start_date}</td>"
                            . "<td>{$dt_status}</td>"
                            . "<td style='text-align: right'>".number_format($inf->adult_triple_twin,2,".",",")."</td>"
                            . "<td style='text-align: right'>".number_format($inf->child_twin_bed,2,".",",")."</td>"
                            . "<td style='text-align: right'>".number_format($inf->child_extra_bed,2,".",",")."</td>"
                            . "<td style='text-align: right'>".number_format($inf->child_no_bed,2,".",",")."</td>"
                            . "<td style='text-align: right'>".number_format($inf->sgl_supp,2,".",",")."</td>"
                            . "<td style='text-align: right'>".number_format($inf->airport_tax,2,".",",")."</td>"
                            . "<td>"
                              . "<div class='btn-group'>"
                                . "<button type='button' class='btn btn-info tour-edit' id='tour-edit' isi='{$inf->id_product_tour_information}' data-toggle='modal' data-target='#compose-modal'><i class='fa fa-edit'></i></button>"
                                . "<button type='button' class='btn btn-info tour-copy' isi='{$inf->id_product_tour_information}' data-toggle='modal' data-target='#compose-modal'><i class='fa fa-copy'></i></button>"
                                . "<button type='button' class='btn btn-danger tour-delete' isi='{$inf->id_product_tour_information}' ><i class='fa fa-times'></i></button>"
                              . "</div>"
                            . "</td>"
                          . "</tr>";
                        }
                        ?>
                      </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="9"><button type='button' class='btn btn-success' data-toggle='modal' id="add-schedule" data-target='#compose-modal'><i class='fa fa-plus'></i></button></td>
                          </tr>
                        </tfoot>
                      </table>
                </div>
              </div>
                  <br>
              <div class="box-footer">
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("inventory/product-tour")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->
</form>

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
                        <label>Tour Code</label>
                        <span id="tour-code"></span>
                    </div>
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
                        <div class="row">
                            <div class="col-xs-4">
                                <label>At Airport Date</label>
                                <?php print $this->form_eksternal->form_input('at_airport_date', "",'onchange="changedate_airport()" id="at_airport_date" class="start_date form-control input-sm"  placeholder="At Airport Date"'); ?>
                            </div>
                            <div class="col-xs-4">
                                <label>At Airport Time</label>
                                <?php print $this->form_eksternal->form_input('at_airport', "",' id="at_airport" class="start_time form-control input-sm"  placeholder="At Airport Time"'); ?>
                            </div>
                            <div class="col-xs-4">
                                <label>STS</label>
                                <?php print $this->form_eksternal->form_input('sts', "", 'id="sts" class="form-control input-sm"  placeholder="STS"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Seat</label>
                                <?php print $this->form_eksternal->form_input('available_seat', "", 'id="available_seat" class="form-control input-sm"  placeholder="Total Seat"'); ?>
                            </div>
                            <div class="col-xs-6">
                                <label>Keberangkatan</label>
                                <?php print $this->form_eksternal->form_input('keberangkatan', "", 'id="keberangkatan" class="form-control input-sm"  placeholder="Keberangkatan"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-4">
                                <label>Flight</label>
                                <?php print $this->form_eksternal->form_input('flt', "", 'id="flt" class="form-control input-sm"  placeholder="FLT"');?>
                            </div>
                            <div class="col-xs-4">
                                <label>IN</label>
                                <?php print $this->form_eksternal->form_input('in', "", 'id="in" class="form-control input-sm"  placeholder="IN"');?>
                            </div>
                            <div class="col-xs-4">
                                <label>OUT</label>
                                <?php print $this->form_eksternal->form_input('out', "", 'id="out" class="form-control input-sm"  placeholder="OUT"');?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-4">
                            <label>Kurs Rate</label>
                            <?php print $this->form_eksternal->form_input('kurs_rate', "", 'id="kurs_rate" class="uang form-control input-sm"  placeholder="Kurs Rate"');?>
                          </div>
                          <div class="col-xs-4">
                            <label>Adult Triple/Twin (USD)</label>
                            <?php print $this->form_eksternal->form_input('adult_triple_twin_usd', "", 'id="adult_triple_twin_usd" class="uang form-control input-sm"  placeholder="Adult Triple/Twin (USD)"');?>
                          </div>
                          <div class="col-xs-4">
                            <label>Adult Triple/Twin (IDR)</label>
                            <?php print $this->form_eksternal->form_input('adult_triple_twin', "", 'id="adult_triple_twin" class="uang form-control input-sm"  placeholder="Adult Triple/Twin (IDR)"');?>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-6">
                            <label>SGL SUPP (USD)</label>
                            <?php print $this->form_eksternal->form_input('sgl_supp_usd', "", 'id="sgl_supp_usd" class="uang form-control input-sm"  placeholder="SGL SUPP (USD)"');?>
                          </div>
                          <div class="col-xs-6">
                            <label>SGL SUPP (IDR)</label>
                            <?php print $this->form_eksternal->form_input('sgl_supp', "", 'id="sgl_supp" class="uang form-control input-sm"  placeholder="SGL SUPP (IDR)"');?>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-6">
                            <label>Child Twin Bed (USD)</label>
                            <?php print $this->form_eksternal->form_input('child_twin_bed_usd', "", 'id="child_twin_bed_usd" class="uang form-control input-sm"  placeholder="Child Twin Bed (USD)"');?>
                          </div>
                          <div class="col-xs-6">
                            <label>Child Twin Bed (IDR)</label>
                            <?php print $this->form_eksternal->form_input('child_twin_bed', "", 'id="child_twin_bed" class="uang form-control input-sm"  placeholder="Child Twin Bed (IDR)"');?>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-6">
                            <label>Child Extra Bed (USD)</label>
                            <?php print $this->form_eksternal->form_input('child_extra_bed_usd', "", 'id="child_extra_bed_usd" class="uang form-control input-sm"  placeholder="Child Extra Bed (USD)"');?>
                          </div>
                          <div class="col-xs-6">
                            <label>Child Extra Bed (IDR)</label>
                            <?php print $this->form_eksternal->form_input('child_extra_bed', "", 'id="child_extra_bed" class="uang form-control input-sm"  placeholder="Child Extra Bed (IDR)"');?>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-6">
                            <label>Child No Bed (USD)</label>
                            <?php print $this->form_eksternal->form_input('child_no_bed_usd', "", 'id="child_no_bed_usd" class="uang form-control input-sm"  placeholder="Child No Bed (USD)"');?>
                          </div>
                          <div class="col-xs-6">
                            <label>Child No Bed (IDR)</label>
                            <?php print $this->form_eksternal->form_input('child_no_bed', "", 'id="child_no_bed" class="uang form-control input-sm"  placeholder="Child No Bed (IDR)"');?>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-6">
                            <label>Harga Visa (USD)</label>
                            <?php print $this->form_eksternal->form_input('visa_usd', "", 'id="visa_usd" class="uang form-control input-sm"  placeholder="Harga Visa (USD)"');?>
                          </div>
                          <div class="col-xs-6">
                            <label>Harga Visa (IDR)</label>
                            <?php print $this->form_eksternal->form_input('visa', "", 'id="visa" class="uang form-control input-sm"  placeholder="Harga Visa (IDR)"');?>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-6">
                            <label>Airport Tax (USD)</label>
                            <?php print $this->form_eksternal->form_input('airport_tax_usd', "", 'id="airport_tax_usd" class="uang form-control input-sm"  placeholder="Airport Tax (USD)"');?>
                          </div>
                          <div class="col-xs-6">
                            <label>Airport Tax (IDR)</label>
                            <?php print $this->form_eksternal->form_input('airport_tax', "", 'id="airport_tax" class="uang form-control input-sm"  placeholder="Airport Tax (IDR)"');?>
                          </div>
                      </div>
                    </div>
                  
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-6">
                            <label>Less Ticket Adult (USD)</label>
                            <?php print $this->form_eksternal->form_input('less_ticket_adl_usd', "", 'id="less_ticket_adl_usd" class="uang form-control input-sm"  placeholder="Less Ticket Adult (USD)"');?>
                          </div>
                          <div class="col-xs-6">
                            <label>Less Ticket Adult (IDR)</label>
                            <?php print $this->form_eksternal->form_input('less_ticket_adl', "", 'id="less_ticket_adl" class="uang form-control input-sm"  placeholder="Less Ticket Adult (IDR)"');?>
                          </div>
                      </div>
                    </div>
                  
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-6">
                            <label>Less Ticket Child (USD)</label>
                            <?php print $this->form_eksternal->form_input('less_ticket_chl_usd', "", 'id="less_ticket_chl_usd" class="uang form-control input-sm"  placeholder="Less Ticket Child (USD)"');?>
                          </div>
                          <div class="col-xs-6">
                            <label>Less Ticket Child (IDR)</label>
                            <?php print $this->form_eksternal->form_input('less_ticket_chl', "", 'id="less_ticket_chl" class="uang form-control input-sm"  placeholder="Less Ticket Child (IDR)"');?>
                          </div>
                      </div>
                    </div>
                  
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-6">
                            <label>Status</label>
                            <?php print $this->form_eksternal->form_dropdown('tampil', array(2 => "Draft", 1 => "Publish"), array($detail[0]->status), ' id="tampil" class="form-control input-sm"');?>
                          </div>
                          <div class="col-xs-6">
                            <label>Condition</label>
                            <?php print $this->form_eksternal->form_dropdown('condition', array(1 => "Available", 5 => "Push Selling"), array($detail[0]->condition), ' id="condition" class="form-control input-sm"');?>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-12">
                            <label>Public Sales (Sub Agent & Website)</label>
                            <?php print $this->form_eksternal->form_dropdown('umum', array(1 => "Internal", 2 => "Umum"), array(), ' id="umum" class="form-control input-sm"');?>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                          <div class="col-xs-12">
                            <label>Remarks</label>
                            <?php print $this->form_eksternal->form_textarea('remarks', '', ' id="remarks" class="form-control input-sm"');?>
                          </div>
                      </div>
                    </div>
                    
                  
<!--                    <div class="form-group">
                        <label>Discount</label>
                        <div class="row">
                            <div class="col-xs-6">
                                <?php print $this->form_eksternal->form_dropdown('stnb_discount_tetap', $dropdown_disc, "",  'onchange="add_tambah_discount()" id="stnb_discount_tetap" class="form-control input-sm"');?>
                            </div>
                            <div class="col-xs-6">
                                <?php print $this->form_eksternal->form_input('discount_tetap', "", 'id="discount_tetap" class="uang form-control input-sm" placeholder="Discount Tetap"');?>
                            </div> 
                        </div>
                    </div>-->
                  <span id="tambah-items-discount">
                    </span>
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
<?php
//print "<pre>";
//print_r($book);
//print "</pre>";
?>
<?php
  $total_person =($book['jumlah_person_adult_triple_twin'] + $book['jumlah_person_child_twin'] + $book['jumlah_person_child_extra'] + $book['jumlah_person_child_no_bed'] + $book['jumlah_person_sgl_supp']);
  ?>


<div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Passenger Detail</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="nav-tabs-custom">
 <ul class="nav nav-tabs">
   <li class='active' ><a href="#tour_detail" data-toggle="tab">Tour Detail</a></li>
   <li><a href="#info" data-toggle="tab">Informasi Pemesan</a></li>
  
 <li><a href="#book_detail" data-toggle="tab">Booking Detail</a></li>
 
 <?php if($book['additional']){  ?>
 <!--<li><a href="#additional" data-toggle="tab">Additional Request</a></li>-->
 <?php } ?>
 <?php if($book['discount_tambahan'][0]->discount_request){ ?>
 
 <?php } ?>
 <!--<li><a href="#price" data-toggle="tab">Price Detail</a></li>-->
 
 </ul>
   <div class="tab-content">
     <div class="tab-pane active" id="tour_detail">
       <table class="table table-condensed">
         
                <tr>
                  <th>Name Tour</th>
                  <td><?php print $tour['title']; ?></td>
                </tr>
                <tr>
                  <th>Tour Schedule Code</th>
                  <td><?php print $tour['information']['code']; ?></td>
                </tr>
                <tr>
                  <th>Category</th>
                  <td><?php print $tour['category']['name']." ".$tour['sub_category']['name']; ?></td>
                </tr>
                <tr>
                  <th>Start Date</th>
                  <td><?php print date("d F Y", strtotime($tour['information']['start_date']))?></td>
                </tr>
                <tr>
                  <th>End Date</th>
                  <td><?php print date("d F Y", strtotime($tour['information']['end_date']))?></td>
                </tr>
               <!-- <tr>
                  <th>Committed Book</th>
                  <td><?php print $tour['information']['committed_book']; ?> %</td>
                </tr> -->
                <tr>
                  <th>Status</th>
                  <td><?php 
                  $status = array(
                    1 => "Book",
                    2 => "Deposit",
                    3 => "Lunas",
                    4 => "Cancel",
                  );
                  $nobook     = 0;
                  $nocommit   = 0;
                  $nolunas    = 0;
                  $nocancel   = 0;
                  $wt_app     = 0;
                  foreach ($book['passenger'] as $valps) {
                   // echo $valps['status'];
                    if($valps['status'] == "Book"){
                      $nobook2 +=  $nobook + 1;
                    }elseif($valps['status'] == "Deposit"){
                      $nocommit2 +=  $nocommit + 1;
                    }elseif($valps['status'] == "Lunas"){
                      $nolunas2 +=  $nolunas + 1;
                    }elseif($valps['status'] == "Cancel"){
                      $nocancel2 +=  $nocancel + 1;
                    }elseif($valps['status'] == "[Cancel] Waiting Approval"){
                      $wt_app2 += $wt_app + 1;
                     
                    }
                  }
                  if($nobook2 > 0){
                    $st_book = "Book For ".$nobook2." Person<br>";
                  }
                  if($nocommit2 > 0){
                    $st_commit = "Deposit For ".$nocommit2." Person<br>";
                  }
                  if($nolunas2 > 0){
                    $st_lunas = "Lunas For ".$nolunas2." Person<br>";
                  }
                  if($nocancel2 > 0){
                    $st_cancel = "Cancel For ".$nocancel2." Person<br>";
                  }
                  if($wt_app2 > 0){
                    $st_wtapp = "[Cancel] Waiting Approval For ".$wt_app2." Person<br>";
                  }
                 // $total_person =($book['jumlah_person_adult_triple_twin'] + $book['jumlah_person_child_twin'] + $book['jumlah_person_child_extra'] + $book['jumlah_person_child_no_bed'] + $book['jumlah_person_sgl_supp']);
                 // print "<b>".$status[$book['status']]." For ".$total_person." Person </b>";
                  print "<b>".$st_book.$st_commit.$st_lunas.$st_cancel.$st_wtapp."</b>";
                  ?></td>
                
                </tr>
                <tr>
                  <th></th>
                  <?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("code_book" => $book['code']))?>
                  <?php  if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "commit_book", "edit") !== FALSE OR $this->session->userdata("id") == 1){ 
//                    if($book['status'] == 1 ){?>
                  <!--<td><input class="btn btn-primary" type="submit" name="committed_book" value="Deposit"></input></td>-->
                  <?php 
//                    } 
                    
                    } ?>
                  </form>
                </tr>
            </table>
     </div>
     <div class="tab-pane" id="info">
       <table class="table table-condensed">
                <tr>
                  <th>Name</th>
                  <td><?php print $book['first_name']." ".$book['last_name']; ?></td>
                </tr>
                <tr>
                  <th>Email</th>
                  <td><?php print $book['email']; ?></td>
                </tr>
                <tr>
                  <th>No Telp</th>
                  <td><?php print $book['telphone']; ?></td>
                </tr>
                <tr>
                  <th>Address</th>
                  <td><?php print $book['address']; ?></td>
                </tr>
            </table>
        <!--  <table class="table table-condensed">
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>No Telp</th>
                </tr>
                <tr>
                  <td><?php print $book['first_name']." ".$book['last_name']; ?></td>
                  <td><?php print $book['telphone']; ?></td>
                  <td><?php print $book['email']; ?></td>
                </tr>
               
            </table>   -->   
       </div>
      
       <div class="tab-pane" id="book_detail">
         <?php    if($book['room']){
            for($k = 1 ; $k <= $book['room'] ; $k++){
              $type_bed = "type_bed".$k;
              $qty = "qty".$k;
              
              if($k == 1){
                $class_active = "active";
              }else{
                $class_active = "";
              }
              
     ?>
         <div class="box">
        <div class="box-header">
            <h3 class="box-title">Room <?php print $k; ?></h3>
        </div>
        <div class="box-body">
          <table class="table table-condensed">
                <tr>
                  <th>Name</th>
                  <th>Birthdate</th>
                  <th>Type</th>
                  <th>Passport</th>
                  <th>Status</th>
                <th></th> 
                </tr>
                <?php
               
                foreach($book['passenger'] AS $dbp){
                  if($dbp['room'] == $k){
                    
                  print "<tr>"
                    . "<td>{$dbp['first_name']} {$dbp['last_name']}</td>"
                    . "<td>".date("d F Y", strtotime($dbp['tanggal_lahir']))."</td>"
                    . "<td>{$dbp['type']['desc']}</td>"
                    . "<td>{$dbp['no_passport']}</td>"
                    . "<td>{$dbp['status']}</td>"
                    . "<td><button type='button' class='btn btn-info tour-edit' onclick='test({$dbp['id_customer']})'  data-toggle='modal' data-target='#compose-modal'>Detail</button></td>"
                  . "</tr>";
                    $no++; ?>
              <!--  <div id="dialog-form<?php print $k; ?>">
                aa
              </div> -->
               
               <?php     
                }
                ?>
                <?php
                }
                ?>
            </table> 
        </div>
    </div> 
     <!--  <table class="table table-condensed">
         
           -->
 <!-- <table class="table table-condensed">
                <tr>
                  <th>Name</th>
                  <th>Birthdate</th>
                  <th>Type</th>
                  <th>Passport</th>
                  <th>Status</th>
                </tr>
                <?php
               
                foreach($book['passenger'] AS $dbp){
                  if($dbp['room'] == $k){
                    
                  print "<tr>"
                    . "<td>{$dbp['first_name']} {$dbp['last_name']}</td>"
                    . "<td>".date("d F Y", strtotime($dbp['tanggal_lahir']))."</td>"
                    . "<td>{$dbp['type']['desc']}</td>"
                    . "<td>{$dbp['no_passport']}</td>"
                    . "<td>{$dbp['status']}</td>"
                  . "</tr>";
                    $no++;
                }}
                ?>
            </table>    -->
          <?php } } ?>     
       </div>
     <?php if($book['additional']){ ?>  
<!--   <div class="tab-pane" id="additional">
  
          <table class="table table-condensed">
                <tr>
                  <th>Additional</th>
                  <th>Nominal</th>
                  <th>User Pengaju</th>
                  <th>User Approval</th>
                  <th>Status</th>
                </tr>
               <?php
                foreach($book['additional'] AS $add){
                  print "<tr>"
                    . "<td>{$add['name_additional']}</td>"
                    . "<td>".$dropdown[$add['id_currency']]." ".number_format($add['nominal_additional'],0,",",".")."</td>"
                    . "<td>{$add['user_pengaju']}</td>"
                    . "<td>{$add['user_approval']}</td>"
                    . "<td></td>"
                      . "</tr>";
                }
                ?>
               
            </table>      
       </div>-->
       <?php } ?>
       
<!--       <div class="tab-pane" id="price">
          <table class="table table table-bordered">
                <tr>
                  <th>Name</th>
                  <th>Person</th>
                  <th>Price [<b><?php print $tour['information']['price']['currency'];?></b>]</th>
                  <th></th>
                  <th></th>
                </tr>
               <tr>
                  <td>Adult Triple Twin</td>
                  <td><?php print $book['jumlah_person_adult_triple_twin']; ?></td>
                  <td><?php print number_format($tour['information']['price']['adult_triple_twin'],0,",","."); ?></td>
                  <td  style="text-align:right"><?php print number_format(($book['jumlah_person_adult_triple_twin'] * $tour['information']['price']['adult_triple_twin']),0,",","."); ?></td>
                  <td></td>
               </tr>
                
                <tr>
                  <td>Child Twin Bed</td>
                  <td><?php print $book['jumlah_person_child_twin']; ?></td>
                  <td><?php print number_format($tour['information']['price']['child_twin_bed'],0,",","."); ?></td>
                 <td  style="text-align:right"><?php print number_format(($book['jumlah_person_child_twin'] * $tour['information']['price']['child_twin_bed']),0,",","."); ?></td>              
                 <td></td>
                </tr>
                
                <tr>
                  <td>Child Extra Bed</td>
                  <td><?php print $book['jumlah_person_child_extra']; ?></td>
                  <td><?php print number_format($tour['information']['price']['child_extra_bed'],0,",","."); ?></td>
                  <td  style="text-align:right"><?php print number_format(($book['jumlah_person_child_extra'] * $tour['information']['price']['child_extra_bed']),0,",","."); ?></td>               
                  <td></td>
                </tr>
                
                 <tr>
                  <td>Child No Bed</td>
                  <td><?php print $book['jumlah_person_child_no_bed']; ?></td>
                  <td><?php print number_format($tour['information']['price']['child_no_bed'],0,",","."); ?></td>
                  <td  style="text-align:right"><?php print number_format(($book['jumlah_person_child_no_bed'] * $tour['information']['price']['child_no_bed']),0,",","."); ?></td>
                  <td></td>
                </tr>
                <tr>
                  <?php $sgl_adl = $tour['information']['price']['sgl_supp'] + $tour['information']['price']['adult_triple_twin'] ?>
                  <td>Single Adult</td>
                  <td><?php print $book['jumlah_person_sgl_supp']; ?></td>
                  <td><?php print number_format($sgl_adl,0,",","."); ?></td>
                  <td  style="text-align:right"><?php print number_format(($book['jumlah_person_sgl_supp'] * $sgl_adl),0,",","."); ?></td>
                  <td></td>
                </tr>
                
                <tr>
                  <?php
                  $total_adult_ttwin = ($book['jumlah_person_adult_triple_twin'] * $tour['information']['price']['adult_triple_twin']);
                  $total_child_twin = ($book['jumlah_person_child_twin'] * $tour['information']['price']['child_twin_bed']);
                  $total_child_extra = ($book['jumlah_person_child_extra'] * $tour['information']['price']['child_extra_bed']);
                  $total_child_no_bed = ($book['jumlah_person_child_no_bed'] * $tour['information']['price']['child_no_bed']);
                  $total_sgl_supp = ($book['jumlah_person_sgl_supp'] * $sgl_adl);
                  $total_tax = ($total_person * $tour['information']['price']['tax_and_insurance']);
                   $total_all_person = $total_adult_ttwin + $total_child_twin + $total_child_extra + $total_child_no_bed + $total_sgl_supp;
                  ?>
                  
                  <td><b>Total Price</b></td>
                  <td></td>
                  <td></td>
                  <td style="text-align:right" ><b><?php print $total = number_format($total_all_person,0,",","."); ?></b></td>
                  <td></td>
                </tr>
                <tr>
                  <td><b>Airport Tax & Flight Insurance</b></td>
                  
                  <td><?php print $total_person; ?></td>
                  <td><?php print number_format($tour['information']['price']['tax_and_insurance'],0,",","."); ?></td>
                  <td  style="text-align:right"><b><?php print number_format(($total_person * $tour[information]['price']['tax_and_insurance']),0,",","."); ?></b></td>
                  <td></td>
                </tr>
                <?php if($book['total_visa'] > 0){
                  $total_visa = $book['total_visa'] * $tour['information']['price']['visa'];
                  ?>
                 <tr>
                  <td><b>Visa</b></td>
                  
                  <td><?php print $book['total_visa']; ?></td>
                  <td><?php print number_format($tour['information']['price']['visa'],0,",","."); ?></td>
                  <td  style="text-align:right"><b><?php print number_format(($total_visa),0,",","."); ?></b></td>
                  <td></td>
                </tr>
                <?php } ?>
                <?php 
                if($book['status_discount']){
                $stnb = "[".$book['status_discount']."]"; 
                }else{
                  $stnb = "";
                }
                // print $book['status_discount'];
                $status_price="";
                if($book['status_discount'] == "Persen"){
                  $status_price = $book['discount'];
                  $tot_disc_price =  (($total_all_person * $book['discount'])/100);
                }elseif($book['status_discount'] == "Nominal") {
                 $tot_disc_price = number_format($book['status_discount'],0,",",".");
                }
                if($book['discount']){
                  $tnd_minus = "-";
                }else{
                  $tnd_minus = "";
                }
               
                ?>
                  <tr>
                  <td><b>Discount <?php print $status_price." ".$stnb; ?></b></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td  style="text-align:right" ><b><?php print $tnd_minus; ?> <?php print $total = number_format($tot_disc_price,0,",","."); ?></b></td>
                  </tr>
                <tr>
                  <?php
                  $total = ($total_all_person + $total_tax + $total_visa);
                  ?>
                  <td><b>Total</b></td>
                  <td></td>
                  <td></td>
                  <td style="text-align:right" ><b><?php print number_format($total,0,",","."); ?></b></td>
                  <td style="text-align:right"><b>- <?php print number_format($tot_disc_price,0,",","."); ?></b></td>
                </tr>
                <tr>
                  <?php
                  $ppn = 1 *(($total_all_person + $total_tax + $total_visa)- $tot_disc_price)/100;
                  ?>
                  <td><b>PPN 1%</b></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td style="text-align:right" ><b><?php print number_format($ppn,2,",","."); ?></b></td>
                </tr>
                <tr>
                  <td><b>Total All</b></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td style="text-align:right" ><b><?php print number_format(($total + $ppn)-$tot_disc_price,2,",","."); ?></b></td>
                </tr>
            </table>      
       </div>-->
   </div>
       </div>
          
        </div>
    </div>
  </div>
<?php
if($tour['id_store_region'] == $this->session->userdata("store_region")){
?>
<div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Additional Request</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="nav-tabs-custom">
 <ul class="nav nav-tabs">
 <li class='active'><a href="#additional1" data-toggle="tab">Additional Request</a></li>
 <?php
 // if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval_discount", "edit") !== FALSE OR $this->session->userdata("id") == 1){
 //if($book['discount_tambahan'][0]->discount_request){ ?>
 <!--<li><a href="#disc_tambahan1" data-toggle="tab">Discount Request</a></li>-->
 <?php// } //} ?>

 </ul>
   <div class="tab-content">       
     
   <div class="tab-pane active" id="additional1">
      <?php
 // if($book['note_additional']){
    $st_add_req = array(1 => "Waiting Approval", 2 => "Approve", 3 => "Reject");
    $pos = array(1 => "Penambahan Biaya",
                2 => "Pengurangan Biaya");
  ?> <h3 class="box-title">Description</h3>
     <table class="table table-condensed">
       <tr>
         <th>Type</th>
         <th>Note</th>
         <th>Nominal</th>
         <th>Status</th>
       </tr>
       <?php
       foreach($additional AS $add){
         print "<tr>"
          . "<td>{$pos[$add->pos]}</td>"
          . "<td>{$add->name}</td>"
          . "<td style='text-align: right'>".  number_format($add->nominal)."</td>"
          . "<td>{$st_add_req[$add->status]}</td>"
         . "</tr>";
       }
       ?>
    </table> 
     <br><br>
  <?php //} ?> 
     <?php if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "commit_additional", "add") !== FALSE OR $this->session->userdata("id") == 1){?>
        
     
     <div class="box">
        <div class="box-header">
            <h3 class="box-title">Additional Request</h3>
        </div>
        
          <?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => ""))?>
        <div class="box-body">
    <div class='box-body col-sm-12'>
      <div class="control-group">
        <div class="row">
            <div class="col-xs-12">
              <label>Note</label>
              <?php print $this->form_eksternal->form_input('name_additional[]', "", 'class="form-control input-sm" placeholder="Name Additional"');?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
              <label>Jenis Biaya</label>
              <?php print $this->form_eksternal->form_dropdown('pos[]', $pos, "", 'class="form-control input-sm"');?>
            </div>
            <div class="col-xs-2">
              <label>Pajak 1%</label>
              <?php print $this->form_eksternal->form_dropdown('pajak[]', array(NULL => "Kena Pajak 1%", 2 => "Tidak kena pajak"), "", 'class="form-control input-sm"');?>
            </div>
            <div class="col-xs-2">
              <label>Mata Uang</label>
              <?php print $this->form_eksternal->form_dropdown('currency[]', $dropdown, "", 'class="form-control input-sm"');?>
            </div>
            <div class="col-xs-5">
              <label>Nominal</label>
              <?php print $this->form_eksternal->form_input('nominal[]', "", 'class="form-control input-sm" placeholder="Nominal"');?>
            </div>
        </div>
      </div>
      </div> 
           <div class="box-footer" >
                 <input class="btn btn-primary" type="submit" name="input_additional" value="Submit"></input>
              </div>
        </div>
          </form>
    </div>  
     <?php } ?>
     <div class="box">
        <div class="box box-success">
                               <?php
                               
                             
                    $no = 1;           
             foreach($book['log_request_additional'] AS $sld){
                if($no % 2 == 0){
                 $dta_cls = "class='online'";
                }else{
                   $dta_cls = "class='offline'";
                }
                  ?>
                   <div class="box-body chat" id="chat-box">
                                    <!-- chat item -->
                                    <div class="item">
                                        <img src="<?php print base_url("themes/lte/img/no-pic.png");?>" alt="user image" <?php print $dta_cls; ?> />
                                        <p class="message">
                                            <a href="#" class="name">
                                                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php print date("d M Y H:m:i", strtotime($sld['tanggal'])); ?></small>
                                                <?php print $sld['name']; ?>
                                            </a>
                                           <?php print nl2br($sld['note']); ?>
                                        </p>
                                    </div>
                                   
                                </div>
                   <?php
                   $no++;
                }
                ?>
                                 <?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => $book['code']))?>
                                <div class="box-footer">
                                    <div class="input-group">
                                        <input class="form-control" name="note_additional_tour" placeholder="Type message..."/>
                                      
                                        <div class="input-group-btn">
                                            <input class="btn btn-success" type="submit" name="request_additional_tour" value="Submit"></input>

                                        </div>
                                    </div>
                                </div>
                   </form>
                            </div>
    </div>
       </div>
       
       <?php
 
	 //  if($book['discount_tambahan'][0]->discount_request){ ?>    
<!-- <div class="tab-pane" id="disc_tambahan1">
  
          <table class="table table-condensed">
                <tr>
                  <th>Discount Request</th>
                  <th>User Request</th>
                  <th>User Approval</th>
                  <th>Status</th>
                  <th></th>
                </tr>
                
               <?php
               $status_request = array(1=> "approve", 2=> "Waiting Approval", 3=> "Reject");
               $disc_req = number_format($book['discount_tambahan'][0]->discount_request);
                  $data_disc = "<tr>"
                    . "<td>{$disc_req}</td>"
                    . "<td>{$book['user_request_discount_tambahan']}</td>"
                    . "<td>{$book['user_approval_discount_tambahan']}</td>"
                 //   . "<td>{$book['discount_tambahan'][0]->batas_discount_tambahan}</td>"
                    . "<td>{$status_request[$book['discount_tambahan'][0]->status]}</td>";
                  
                 if($book['discount_tambahan'][0]->status == 2){if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "approval_discount", "edit") !== FALSE OR $this->session->userdata("id") == 1){
                   print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => $book['discount_tambahan'][0]->id_product_tour_discount_tambahan));
                   $data_disc .= "<td><input class='btn btn-primary' type='submit' name='approve' value='Approve'></input> <input class='btn btn-primary' type='submit' name='reject' value='Reject'></input></td>
                  </form>";
                  } }
                  $data_disc .= "</tr>";
               print $data_disc;
                ?>
            </table> 
   
       </div> -->
        
   </div><!-- /.tab-content -->
       </div>
          
        </div>
        
    </div>
  </div>
<?php  
if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "cancel_customer", "edit") !== FALSE OR $this->session->userdata("id") == 1){ ?>  
<div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Customer Cancel</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="nav-tabs-custom">
 <ul class="nav nav-tabs">
 <li class='active'><a href="#customer_cancel2" data-toggle="tab">Customer Cancel</a></li>

 </ul>
   <div class="tab-content">
       <div class="tab-pane active" id="customer_cancel2">
          <table class="table table-condensed">
                <tr>
                  <th>Name</th>
                  <th>Type</th>
                  <th>Price</th>
                  <th>Status</th>
                  <th></th>
                </tr>
                <?php
                if($cust_cancel){
                  foreach ($cust_cancel as $valc) {
                    if($valc->type == 1){
                      $typ_cust = "Adult Triple Twin";
                      $price_cust = $valc->adult_triple_twin;
                    }elseif($valc->type == 2){
                      $typ_cust = "Child Twin Bed";
                      $price_cust = $valc->child_twin_bed;
                    }elseif($valc->type == 3){
                      $typ_cust = "Child Extra Bed";
                      $price_cust = $valc->child_extra_bed;
                    }elseif($valc->type == 4){
                      $typ_cust = "Child No Bed";
                      $price_cust = $valc->child_no_bed;
                    }elseif($valc->type == 5){
                      $typ_cust = "Sgl Supp";
                       $price_cust = $valc->sgl_supp;
                    }
                    
                    
                    $btn_url2 = "";
                    if($valc->status_customer == 1){
                      $st_cust = "Book";
                    }elseif($valc->status_customer == 2){
                      $st_cust = "Committed Book";
                    }elseif($valc->status_customer == 3){
                      $st_cust = "Lunas";
                    }elseif($valc->status_customer == 4){
                      $st_cust = "Cancel";
                    }elseif($valc->status_customer == 5){
                      $st_cust = "[Cancel] Waiting Approval";
                      $btn_url2 = site_url("inventory/tour-book/book-information/".$book['code']."/".$valc->customer_code);
                      $btn_url = "<a href='$btn_url2' class='btn btn-primary'>Approval</a>";
                      
                    }
                ?>
                <tr>
                  <td><?php print $valc->first_name." ".$valc->last_name; ?></td>
                  <td><?php print $typ_cust; ?></td>
                  <td><?php print $dropdown[$valc->id_currency]." ".number_format($price_cust,0,",","."); ?></td>
                  <td><?php print $st_cust; ?></td>
                  <td><?php print $btn_url; ?></td>
                </tr>
                <?php
                  }
                }
                ?>
            </table>  
         <div class="box-body">
           <br><br><br>
          <table class="table table-condensed">
            <?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => ""))?>
                
                <tr>
                  <th>Potongan Biaya Cancel</th>
                  <td><?php print $this->form_eksternal->form_dropdown('id_currency', $dropdown, "", 'style="width:25%" class="form-control" placeholder="Currency"'); ?>
                    <?php print $this->form_eksternal->form_input('nom_add', "", 'style="width:30%" class="form-control input-sm" placeholder="Biaya Cancel"');?></td>
                </tr>
                <tr>
                 <td><input class="btn btn-primary" type="submit" name="beban_biaya" value="Submit"></input></td>
                </form>
                </tr>
                
            </table>
        </div>
       </div>
   </div><!-- /.tab-content -->
       </div>
          
        </div>
    </div>
  </div>
 <?php } 
}
?>
<!--
<div class="row">
  <div class="col-md-6">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Payment Detail [USD]</h3>
        </div>
        <div class="box-body">
          <table class="table table table-bordered">
                <tr>
                  <th>Tanggal</th>
                  <th>Status Payment</th>
                  <th>Payment</th>
                  <th>Debit</th>
                  <th>Kredit</th>
                </tr>
                <?php
                $total_debit = $total_kredit = $total_debit2 = $total_kredit2 = 0;
                 $status_payment = array(
                  1 => "Draft",
                  2 => "Confirmed",
                  3 => "Not Paid"
                );
                $channel = array(
                  1 => "Cash",
                  2 => "BCA",
                  3 => "Mega",
                  4 => "Mandiri",
                  5 => "CC"
                );
                
                
                foreach($payment AS $dp){
                  if($dp['currency'] == 1){
                      $tot_debit0 = $dp['nominal'];
                    }elseif($dp['currency'] == 2){
                      $tot_debit0 = $dp['nominal']/$dropdown_rate[1];
                    }
                  if($dp['pos'] == 1){
                    $debit = number_format($tot_debit0, 0, ",", ".");
                    $kredit = "";
                    $total_debit += $tot_debit0;
                  }
                  else{
                    $kredit = number_format($tot_debit0, 0, ",", ".");
                    $debit = "";
                    $total_kredit += $tot_debit0;
                  }
                  print "<tr>"
                  . "<td>{$dp['tanggal']}</td>"
                  . "<td>{$status_payment[$dp['status']]}</td>"
                  . "<td>{$channel[$dp['status_payment']]}</td>"
                  . "<td style='text-align: right'>{$debit}</td>"
                  . "<td style='text-align: right'>{$kredit}</td>"
                  . "</tr>";
                }
                ?>
                <tfoot>
                  <tr>
                    <?php
                    $data_tax = $total_person * $tour['information']['price']['tax_and_insurance'];
                    ?>
                    <th colspan="3">Airport Tax & Flight Insurance</th>
                    <th style='text-align: right'><?php print number_format($data_tax,0,",","."); ?></th>
                    <th style='text-align: right'>0</th>
                  </tr>
                   <?php
                foreach($book['additional'] AS $add){
                  
                  if($add['nominal_additional']){
                    $nom_ad1 = "";
                    $nom_ad2 = "";
                    if($add['id_currency'] == 1){
                        $nom_add0 = $add['nominal_additional'];
                      }elseif($add['id_currency'] == 2){
                        $nom_add0 = $add['nominal_additional']/$dropdown_rate[1];
                      }
                    if($add['pos'] == 1){
                      $nom_ad1 = $nom_add0;
                      $total_kredit2 += $nom_add0;
                    }else{
                      $nom_ad2 = $nom_add0;
                      $total_debit2 += $nom_add0;
                    }
                  print "<tr>"
                    . "<th colspan='3'>{$add['name_additional']}</th>"
                    . "<th style='text-align: right'>".number_format($nom_ad2,0,",",".")."</th>"
                      . "<th style='text-align: right'>".number_format($nom_ad1,0,",",".")."</th>"
                  . "</tr>";
                }}
                ?>
                 <?php
                  if($book['discount']){
                    
                     if($book['status_discount']){
                $stnb = "[".$book['status_discount']."]"; 
                }else{
                  $stnb = "";
                }
                
                $status_price1="";
                if($book['status_discount'] == "Persen"){
                  $status_price = $book['discount'];
                  $tot_disc_price1 =  (($total_debit * $book['discount'])/100);
                }elseif($book['status_discount'] == "Nominal") {
                 $tot_disc_price1 = $book['discount'];
                }
                 ?>
                  <tr>
                    <th colspan="3">Discount <?php print $status_price." ".$stnb; ?></th>
                    <th style='text-align: right'>0</th>
                    <th style='text-align: right'><?php print number_format($tot_disc_price1, 0, ",", ".")?></th>
                  </tr>
                  <?php } ?>
                  <?php 
                
                  if($book['discount_tambahan'][0]->status == 1){
                       if($book['discount_tambahan'][0]->status_discount == 1){
                    $status_disc_tambh = "[Persen]";
                    $tot_disc_tambahan =  (($total_debit * $book['discount_tambahan'][0]->discount_request)/100);
                  }elseif($book['discount_tambahan'][0]->status_discount == 2) {
                      $status_disc_tambh = "Nominal";
                   $tot_disc_tambahan = $book['discount_tambahan'][0]->discount_request;
                  }
                   ?>
                  <tr>
                    <th colspan="3">Discount Tambahan <?php print $book['discount_tambahan'][0]->discount_request." ".$status_disc_tambh ?></th>
                    <th style='text-align: right'>0</th>
                    <th style='text-align: right'><?php print number_format($tot_disc_tambahan, 0, ",", ".")?></th>
                  </tr>
                  <?php } ?>
                  <tr>
                    <?php
                    $ppn = 1 * (($total_debit + $total_debit2 + $data_tax) - $tot_disc_price1)/100;
                    ?>
                    <th colspan="3">PPN 1%</th>
                    <th style='text-align: right'><?php print number_format($ppn, 2, ",", ".")?></th>
                    <th style='text-align: right'>0</th>
                  </tr>
                  <tr>
                    <th colspan="3">TOTAL</th>
                    <th style='text-align: right'><?php print number_format(($total_debit + $total_debit2 + $data_tax + $ppn), 2, ",", ".")?></th>
                    <th style='text-align: right'><?php print number_format(($total_kredit + $total_kredit2 + $tot_disc_price1 + $tot_disc_tambahan), 0, ",", ".")?></th>
                  </tr>
                  <tr>
                    <th colspan="3">BALANCE</th>
                    <th></th>
                    <th style='text-align: right'><?php print number_format((((($total_debit + $total_debit2 + $ppn) - $tot_disc_price1) + $data_tax)- ($total_kredit2 + $total_kredit + $tot_disc_tambahan) ), 2, ",", ".")?></th>
                  </tr>
                </tfoot>
            </table>
        </div>
    </div>
  </div> -->
  
  <div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Payment Detail [IDR]<br><!-- [1USD = <?php print number_format($dropdown_rate[1]);?> IDR] --></h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="table table table-bordered">
                <tr>
                  <th>Tanggal</th>
                  <th>Status Payment</th>
                  <th>Payment</th>
                  <th>Note</th>
                  <th>Debit</th>
                  <th>Kredit</th>
                </tr>
                <?php
                $total_debit = $total_kredit = $total_debit2 = $total_kredit2 = 0;
                 $status_payment = array(
                  1 => "Draft",
                  2 => "Confirm",
                  3 => "Not Paid"
                );
                $channel = array(
                  1 => "Cash",
                  2 => "BCA",
                  3 => "Mega",
                  4 => "Mandiri",
                  5 => "CC"
                );
                
                foreach($payment AS $dp){
                  if($dp['currency'] == 1){
                        $nom1 = $dp['nominal'] * $dropdown_rate[1];
                      }elseif($dp['currency'] == 2){
                        $nom1 = $dp['nominal'];
                      }
                  if($dp['pos'] == 1){
                    $debit = number_format($nom1, 0, ",", ".");
                    $kredit = "";
                    $total_debit += $nom1;
                  }
                  else{
                    $kredit = number_format($nom1, 0, ",", ".");
                    $debit = "";
                    $total_kredit += $nom1;
                  }
                  print "<tr>"
                  . "<td>{$dp['tanggal']}</td>"
                  . "<td>{$status_payment[$dp['status']]}</td>"
                  . "<td>{$channel[$dp['status_payment']]}</td>"
                   . "<td>{$dp['note']}</td>"
                  . "<td style='text-align: right'>{$debit}</td>"
                  . "<td style='text-align: right'>{$kredit}</td>"
                  . "</tr>";
                }
                ?>
                <tfoot>
<!--                  <tr>
                    <?php 
                  
//                  if($tour['information']['price']['currency'] == "IDR"){
//                    $data_tax = ($total_person * $tour['information']['price']['tax_and_insurance']);
//                  }elseif($tour['information']['price']['currency'] == "USD"){
//                    $data_tax = ($total_person * $tour['information']['price']['tax_and_insurance']) * $dropdown_rate[1];
//                  }
                    
                    ?>
                    <th colspan="3">Airport Tax & Flight Insurance</th>
                    <th style='text-align: right'><?php print number_format($data_tax,0,",","."); ?></th>
                    <th style='text-align: right'>0</th>
                  </tr>-->
                
                   <?php
//                foreach($book['additional'] AS $add){
//                 
//                  if($add['nominal_additional']){
//                    if($add['id_currency'] == 1){
//                        $nom_add1 = $add['nominal_additional'] * $dropdown_rate[1];
//                      }elseif($add['id_currency'] == 2){
//                        $nom_add1 = $add['nominal_additional'];
//                      }
//                    $nom_ad1 = "";
//                    $nom_ad2 = "";
//                    if($add['pos'] == 1){
//                      $nom_ad1 = $nom_add1;
//                      $total_kredit2 += $nom_add1;
//                    }else{
//                      $nom_ad2 = $nom_add1;
//                      $total_debit2 += $nom_add1;
//                    }
//                  print "<tr>"
//                    . "<th colspan='3'>{$add['name_additional']}</th>"
//                    . "<th style='text-align: right'>".number_format($nom_ad2,0,",",".")."</th>"
//                      . "<th style='text-align: right'>".number_format($nom_ad1,0,",",".")."</th>"
//                  . "</tr>";
//                }}
                ?>
                 <?php
//                  if($book['discount']){
//                    
//                     if($book['status_discount']){
//                $stnb = "[".$book['status_discount']."]"; 
//                }else{
//                  $stnb = "";
//                }
//                
//                $status_price1="";
//                if($book['status_discount'] == "Persen"){
//                  $status_price = $book['discount'];
//                  $tot_disc_price1 =  (($total_debit * $book['discount'])/100);
//                }elseif($book['status_discount'] == "Nominal") {
//                 $tot_disc_price1 = number_format($book['status_discount'],0,",",".");
//                }
                 ?>
<!--                  <tr>
                    <th colspan="3">Discount <?php print $status_price." ".$stnb; ?></th>
                    <th style='text-align: right'>0</th>
                    <th style='text-align: right'><?php print number_format($tot_disc_price1, 0, ",", ".")?></th>
                  </tr>-->
                  <?php// } ?>
                  <?php 
//                  if($book['discount_tambahan'][0]->status == 1){ 
//                        if($book['discount_tambahan'][0]->status_discount == 1){
//                    $status_disc_tambh = "[Persen]";
//                    $tot_disc_tambahan =  (($total_debit * $book['discount_tambahan'][0]->discount_request)/100);
//                  }elseif($book['discount_tambahan'][0]->status_discount == 2) {
//                      $status_disc_tambh = "Nominal";
//                   $tot_disc_tambahan = $book['discount_tambahan'][0]->discount_request;
//                  }
                      ?>
<!--                  <tr>
                    <th colspan="3">Discount Tambahan <?php print $tot_disc_tambahan." ".$status_disc_tambh ?></th>
                    <th style='text-align: right'>0</th>
                    <th style='text-align: right'><?php print number_format($tot_disc_tambahan, 0, ",", ".")?></th>
                  </tr>-->
                  <?php //} ?>
                  <tr>
                    <?php
//                    $ppn = 1 * (($total_debit + $total_debit2 + $data_tax + $total_visa) - $tot_disc_price1)/100;
                    ?>
<!--                    <th colspan="3">PPN 1%</th>
                    <th style='text-align: right'><?php print number_format($ppn, 2, ",", ".")?></th>
                    <th style='text-align: right'>0</th>
                  </tr>-->
                  <tr>
                    <th colspan="4">TOTAL</th>
                    <th style='text-align: right'><?php print number_format(($total_debit), 0, ",", ".")?></th>
                    <th style='text-align: right'><?php print number_format(($total_kredit ), 0, ",", ".")?></th>
                  </tr>
                  <tr>
                    <th colspan="4">BALANCE</th>
                    <th></th>
                    <th style='text-align: right'><?php print number_format((($total_debit)- ($total_kredit2 + $total_kredit) ), 0, ",", ".")?></th>
                  </tr>
                </tfoot>
            </table>
        </div>
    </div>
  </div>
  
 <!-- 
  <div class="col-md-7">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">History Request Discount</h3>
        </div>
        <div class="box-body">
          
          <table class="table table-condensed">
               <tr>
                  <th>Log</th>
                  <th>Note</th>
                  <th>Discount</th>
                  <th>Users</th>
                  <th>Status</th>
                </tr>
                <?php
                
                $log_status = array(1 => "Default",
                                    2 => "Pengajuan User",
                                    3 => "Revisi Admin",
                                    4 => "Approval Admin",
                                    5 => "Approval User");
               
               foreach($log AS $lg){
                  print "<tr>"
                    . "<td>{$lg->kode}</td>"
                    . "<td>{$lg->note}</td>"
                    . "<td>{$lg->discount}</td>"
                    . "<td>{$lg->name}</td>"
                    . "<td>{$log_status[$lg->status]}</td>"
                  . "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Request Discount</h3>
        </div>
        <div class="box-body">
          <table class="table table-condensed">
            <?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => ""))?>
                <tr>
                  <th>Note</th>
                  <td><?php print $this->form_eksternal->form_textarea('note', $detail[0]->note, 'class="form-control input-sm" id="Note"')?></td>
                </tr>
                <tr>
                  <th>Discount</th>
                  <td><?php print $this->form_eksternal->form_input('discount', $detail[0]->title, 'class="form-control input-sm" placeholder="Nominal Discount"');?></td>
                </tr>
                <tr>
                   
                  <th><input class="btn btn-primary" type="submit" name="approval" value="Approval"></input></th>
                  <td><input class="btn btn-primary" type="submit" name="request_dicount" value="Submit"></input></td>
                </form>
                </tr>
                
            </table>
        </div>
    </div>
  </div> -->
</div>
<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"> Customer Detail</h4>
            </div>
           
                <div class="modal-body">
                   <div class="box-body">
                    <div class="col-md-12">
                      <div class="control-group">
                      <label>Name</label>
                      <?php print $this->form_eksternal->form_input('name', "", 'id="name" disabled class="form-control input-sm" placeholder="Name"');?>
                      </div>
                      </div>
                    
                    <div class="col-md-4">
                     <div class="control-group">
                      <label>No Telp</label>
                      <?php print $this->form_eksternal->form_input('telp',"", 'id="telp" class="form-control input-sm" disabled id="ano_telp_pemesan" placeholder="No Telp"');?>
                      </div>
                      </div>
                     <div class="col-md-4">
                      <div class="control-group">
                      <label>Place Of Birth</label>
                       <?php print $this->form_eksternal->form_input('place_birth', "", 'id="tmpt_tgl_lahir" disabled class="form-control input-sm" placeholder="Place Of Birth"');?>
                      </div>
                       </div>
                   
                    <div class="col-md-4">
                      <div class="control-group">
                      <label>Date Of Birth</label>
                      <?php print $this->form_eksternal->form_input('date', "", 'id="tgl_lahir" disabled class="form-control input-sm adult_date" placeholder="Date Of Birth"');?>
                       </div>
                     </div>
                    
                    <div class="col-md-6">
                      <div class="control-group">
                      <label>No Passport</label>
                      <?php print $this->form_eksternal->form_input('passport', "", 'id="passport" disabled class="form-control input-sm" placeholder="No Passport"');?>
                     </div>
                    </div>
                    <div class="col-md-6">
                    <div class="control-group">
                      <label>Place Of Issued</label>
                      <?php print $this->form_eksternal->form_input('place_issued', "", 'id="place_issued" disabled class="form-control input-sm" placeholder="Place Of Issue"');?>
                     </div>
                      </div>
                   
                    <div class="col-md-6">
                    <div class="control-group">
                      <label>Date Of Issued</label>
                      <?php print $this->form_eksternal->form_input('date_issued', "", 'id="date_issued" disabled class="form-control input-sm " placeholder="Date Of Issued"');?>
                      </div>
                      </div>
                   
                      <div class="col-md-6">
                    <div class="control-group">
                      <label>Date Of Expired</label>
                      <?php print $this->form_eksternal->form_input('date_expired', "", 'id="date_expired" disabled class="form-control input-sm " placeholder="Date Of Expired"');?>
                    
                    </div><br>
                        </div>
                   <div class="col-md-12">
                      <div class="control-group">
                      <label>Type</label>
                      <?php print $this->form_eksternal->form_input('type', "", 'id="type" disabled  class="form-control  input-sm" placeholder="Type"');?>
                      </div><br>
                      </div>
                   
                   
                  </div>
                </div>
                <div class=" clearfix">

                   
                </div>
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

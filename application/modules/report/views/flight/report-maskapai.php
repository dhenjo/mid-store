<?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => ""))?>
<?php print $data_query; ?>
<div class="box-body col-sm-6">

      <div class="control-group">
        <label>Start Date</label>
        <?php print $this->form_eksternal->form_input('booking_from', $this->session->userdata('flight_report_maskapai_booking_from'), 
        'id="start_date" class="form-control input-sm" placeholder="Start Date"');?>      
      </div>

      <div class="control-group">
        <label>Book Code</label>
        <?php print $this->form_eksternal->form_input('book_code', $this->session->userdata('flight_report_maskapai_book_code'), 'class="form-control" placeholder="Book Code"')?>
      </div>
  
   <div class="control-group">
        <label>Maskapai</label>
         <?php
        $data_maskapai = array("0"	=> "All",
				  "GA" => "Garuda",
				  "ID"	=> "Batik Air",
				  "IW"  => "Wings Air",
				  "JT"	=> "Lion Air",
				  "QG"	=> "Citylink",
				  "QZ"	=> "Air Asia",
				  "SJ"	=> "Sriwijaya Air");
         print $this->form_eksternal->form_dropdown('maskapai', $data_maskapai, array($this->session->userdata('flight_report_maskapai_maskapai')), 'class="form-control" placeholder="Maskapai"');?>
      </div>
      
    </div>

<div class="box-body col-sm-6" >

      <div class="control-group">
          <label>End Date</label>
        <?php print $this->form_eksternal->form_input('booking_to', $this->session->userdata('flight_report_maskapai_booking_to'), 
        'id="end_date" class="form-control input-sm" placeholder="End Date"');?>
      </div>
  
  

      <div class="control-group">
        <label>Payment Type</label>
       <?php 
       $channel = array(
          0 => "All",
          1 => "BCA",
          2 => "Mega CC",
          3 => "Visa/Master",
          4 => "Mega Priority"
        );
       $type = array(
          1 => "Persentase",
          2 => "Nominal",
        );
       print $this->form_eksternal->form_dropdown('payment', $channel, array($this->session->userdata('flight_report_maskapai_payment')), 'class="form-control" placeholder="Flight"');?></div>
  
  <div class="control-group">
          <label>Tiket No</label>
        <?php print $this->form_eksternal->form_input('tiket_no', $this->session->userdata('flight_report_maskapai_tiket_no'), 
        'id="tiket_no" class="form-control input-sm" placeholder="Tiket No"');?>
      </div>    
  <br><br>
    </div>



<div class="box-body col-sm-6" style="margin-right: 20%;">
  <div class="box-footer">
      <div class="control-group">
        <button class="btn btn-primary" type="submit">Search</button> 
      </div>
  </div>
 
</div>
  <div class="box-body col-sm-6" style="margin-left: 50%;margin-top: -8%;">
  <div class="box-footer">
      <div class="control-group">
        <input type="submit" name="export" value="Export Excel" class="btn btn-primary" type="submit"></input> 
      </div>
  </div>
</div>
</form>
<br />

<?php
if($this->uri->segment(4) == 1){
    $sort = 2;
}elseif($this->uri->segment(4) == 2){
    $sort = 1;
}else{
    $sort = 1;
}
?>

<thead>
    <tr>
        <th><a href="<?php print site_url("report/flight/btc-maskapai/$sort/1")?>">Tanggal Issued</a></th>
        <th>Book Code</th>
        <th>Tiket No</th>
        <th>Maskapai</th>
        <th><a href="<?php print site_url("report/flight/btc-maskapai/$sort/6")?>">HPP</a></th>
    </tr>
</thead>
<tbody>
  <?php
  if(is_array($data)){
    $r = date("Y-m-d");
    $detail_harga = "";
    $total = array();
    foreach ($data as $key => $value) {
      $flight = $this->global_models->get_query("SELECT SUM(price) AS jml"
        . " FROM tiket_flight"
        . " WHERE "
        . " id_tiket_flight IN (SELECT id_tiket_flight FROM tiket_flight WHERE id_tiket_book = '{$value->id_tiket_book}' GROUP BY issued_no)");
        
//      cek discount
        $dt_flag = $this->global_models->get("tiket_flight", array("id_tiket_book" => $value->id_tiket_book));
        
      print '
      <tr>
        <td>'.date("Y-m-d H:i:s", strtotime($value->tanggal)).'</td>
        <td>
          '.$value->book_code.'
        </td>
        <td>'.$value->issued_no.'</td>
        <td><center>'.$data_maskapai[$value->maskapai].'</center></td>  
        <td style="text-align: right">'.number_format($value->price,0,".",",").'</td>
      </tr>';
      $total['hpp'] += $value->price;
      $r++;
    }
  }
  ?>
</tbody>
<tfoot>
    <tr>
        <td colspan="4" style="text-align: center"><b>TOTAL</b></td>
        <td style="text-align: right"><b><?php print number_format($total['hpp'],0,".",",")?></b></td>
    </tr>
</tfoot>
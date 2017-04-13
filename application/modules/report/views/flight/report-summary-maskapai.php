<?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => ""))?>
<?php print $data_query; ?>
<div class="box-body col-sm-6">

      <div class="control-group">
        <label>Start Date</label>
        <?php print $this->form_eksternal->form_input('booking_from', $this->session->userdata('flight_report_maskapai_booking_from'), 
        'id="start_date" class="form-control input-sm" placeholder="Start Date"');?>      
      </div>
      
    </div>

<div class="box-body col-sm-6" >

      <div class="control-group">
          <label>End Date</label>
        <?php print $this->form_eksternal->form_input('booking_to', $this->session->userdata('flight_report_maskapai_booking_to'), 
        'id="end_date" class="form-control input-sm" placeholder="End Date"');?>
      </div>
  
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
        <th>Maskapai</th>
        <th>NTA</th>
    </tr>
</thead>
<tbody>
  <?php
  $data_maskapai = array("0"	=> "All",
				  "GA" => "Garuda",
				  "ID"	=> "Batik Air",
				  "IW"  => "Wings Air",
				  "JT"	=> "Lion Air",
				  "QG"	=> "Citylink",
				  "QZ"	=> "Air Asia",
				  "SJ"	=> "Sriwijaya Air");
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
        <td><center>'.$data_maskapai[$key].'</center></td>  
        <td style="text-align: right">'.number_format($value,0,".",",").'</td>
      </tr>';
      $r++;
    }
  }
  ?>
</tbody>
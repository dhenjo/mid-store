<?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => ""))?>
<div class="box-body col-sm-6">

      <div class="control-group">
        <label>Start Date</label>
        <?php print $this->form_eksternal->form_input('booking_from', $this->session->userdata('flight_report_transaksi_sales_booking_from'), 
        'id="start_date" class="form-control input-sm" placeholder="Start Date"');?>      
      </div>

      <div class="control-group">
        <label>Book Code</label>
        <?php print $this->form_eksternal->form_input('book_code', $this->session->userdata('flight_report_transaksi_sales_book_code'), 'class="form-control" placeholder="Book Code"')?>
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
         print $this->form_eksternal->form_dropdown('maskapai', $data_maskapai, array($this->session->userdata('flight_report_transaksi_sales_maskapai')), 'class="form-control" placeholder="Maskapai"');?>
      </div>
   
      
    </div>

<div class="box-body col-sm-6" >

      <div class="control-group">
          <label>End Date</label>
        <?php print $this->form_eksternal->form_input('booking_to', $this->session->userdata('flight_report_transaksi_sales_booking_to'), 
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
          4 => "Mega Priority",
          5 => "Mandiri ClickPay",
        );
       $type = array(
          1 => "Persentase",
          2 => "Nominal",
        );
       print $this->form_eksternal->form_dropdown('payment', $channel, array($this->session->userdata('flight_report_transaksi_sales_payment')), 'class="form-control" placeholder="Flight"');?></div>
      
      <div class="control-group">
          <label>Tiket No</label>
        <?php print $this->form_eksternal->form_input('tiket_no', $this->session->userdata('flight_report_transaksi_sales_tiket_no'), 
        'id="tiket_no" class="form-control input-sm" placeholder="Tiket No"');?>
      </div>
      <br><br>
    </div>



<div class="box-body col-sm-6" style="  margin-right: 20%;">
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
        <th><a href="<?php print site_url("report/flight/btc-sales/$sort/1")?>">Tanggal Book</a></th>
        <th>Book Code</th>
        <th>Tiket No</th>
        <th>Maskapai</th>
        <th>Payment</th>
        <th><a href="<?php print site_url("report/flight/btc-sales/$sort/2")?>">Harga Tiket</a></th>
        <th><a href="<?php print site_url("report/flight/btc-sales/$sort/3")?>">Diskon</a></th>
        <th><a href="<?php print site_url("report/flight/btc-sales/$sort/5")?>">Uang Terima</a></th>
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
        $maskapai1 = "";
        $bk_code = "";
        foreach($dt_flag AS $flg){
          if($flg->flight == 1){
            $maskapai1 .= $data_maskapai[$flg->maskapai];
            $bk_code .= $flg->book_code;
          }else{
            $maskapai1 .= "<br>".$data_maskapai[$flg->maskapai];
            if($flg->book_code){
              $bk_code .= "<br>".$flg->book_code;
            }
          }
        }
      $discount = $this->mdiscount->btc_payment($value->tglbook, $value->harga_normal, $value->channel);
      $detail_harga = "";
      if($discount['nilai'] > 0){
        $detail_harga = "<table width='100%'>"
        . "<tr>"
          . "<td>Title</td>"
          . "<td style='text-align: left'>".$discount['discount']->title."</td>"
        . "</tr>"
        . "<tr>"
          . "<td>Channel</td>"
          . "<td style='text-align: left'>".$channel[$discount['discount']->channel]."</td>"
        . "</tr>"
        . "<tr>"
          . "<td>Periode Mulai</td>"
          . "<td style='text-align: left'>".date("d M y H:i", strtotime($discount['discount']->mulai))."</td>"
        . "</tr>"
        . "<tr>"
          . "<td>Periode Akhir</td>"
          . "<td style='text-align: left'>".date("d M y H:i", strtotime($discount['discount']->akhir))."</td>"
        . "</tr>"
        . "<tr>"
          . "<td>Payment Type</td>"
          . "<td style='text-align: left'>".$type[$discount['discount']->type]."</td>"
        . "</tr>"
        . "<tr>"
          . "<td>Discount</td>"
          . "<td style='text-align: left'>".number_format($discount['discount']->nilai,0,".",",")."</td>"
        . "</tr>"
        . "</table>";
        $cetak_diskon = ''
          . '<td style="text-align: right">'
            . '<a id="info_discount'.$value->id_tiket_book.'" href="javascript:void(0)">'
              . number_format(($value->diskon),0,".",",")
            . '</a>'
            . '<div style="display: none" id="isidiscount'.$value->id_tiket_book.'">'.$detail_harga.'</div> '
            . '<script> '
              . '$(function() { '
                . '$("#info_discount'.$value->id_tiket_book.'").tooltipster({ '
                  . 'content: $("#isidiscount'.$value->id_tiket_book.'").html(), '
                  . 'minWidth: 300, '
                  . 'maxWidth: 300, '
                  . 'contentAsHTML: '
                  . 'true, '
                  . 'interactive: true '
                . '}); '
              . '}); '
            . '</script>'
          . '</td>';
      }
      else{
        $cetak_diskon = '<td style="text-align: right">'.number_format(($value->diskon),0,".",",").'</td>';
      }
        
      print '
      <tr>
        <td>'.date("Y-m-d H:i:s", strtotime($value->tanggal)).'</td>
        <td>
          '.$bk_code.'
        </td>
        <td>'.$value->issued_no.'</td>
        <td><center>'.$maskapai1.'</center></td>  
        <td>'.$channel[$value->channel].'</td>
        <td style="text-align: right">'.number_format(($value->harga_bayar + $value->diskon),0,".",",").'</td>
        '.$cetak_diskon.'
        <td style="text-align: right">'.number_format(($value->harga_bayar),0,".",",").'</td>
      </tr>';
      $total['harga_tiket'] += $value->harga_bayar + $value->diskon;
      $total['diskon'] += $value->diskon;
      $total['uang_terima'] += ($value->harga_bayar);
      $r++;
    }
  }
  ?>
</tbody>
<tfoot>
    <tr>
        <td colspan="5" style="text-align: center"><b>TOTAL</b></td>
        <td style="text-align: right"><b><?php print number_format($total['harga_tiket'],0,".",",")?></b></td>
        <td style="text-align: right"><b><?php print number_format($total['diskon'],0,".",",")?></b></td>
        <td style="text-align: right"><b><?php print number_format($total['uang_terima'],0,".",",")?></b></td>
    </tr>
</tfoot>
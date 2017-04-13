<?php
print "<pre>";
print_r($data); 
print "</pre>";
//die;
?>
<thead>
    <tr>
        <th>Tanggal</th>
        <th>Destination</th>
        <th>Seat</th>
        <th>Available Seat</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>
  <?php
  
    $status = array(
      1 => "<span class='label label-default'>Book</span>",
      2 => "<span class='label label-info'>Committed Payment</span>",
      3 => "<span class='label label-success'>Clear</span>",
      4 => "<span class='label label-warning'>Cancel</span>",
    );
    foreach ($data as $value) {
    
  
      foreach ($value['passenger'] as $valps) {
        // echo $valps['status'];
        if($valps->status == 1){
          $nobook2 +=  $nobook + 1;
        }elseif($valps->status == 2){
          $nocommit2 +=  $nocommit + 1;
        }elseif($valps->status == 3){
          $nolunas2 +=  $nolunas + 1;
        }elseif($valps->status == 4){
          $nocancel2 +=  $nocancel + 1;
        }elseif($valps->status == 5){
          $wt_app2 += $wt_app + 1;
        }
      }
          if($nobook2 > 0){
            $st_book = "<b>".$nobook2." Book </b> <br>";
          }
          if($nocommit2 > 0){
            $st_commit = "<b>".$nocommit2." Committed Book </b> <br>";
          }
          if($nolunas2 > 0){
            $st_lunas = "<b>".$nolunas2." Lunas </b> <br>";
          }
          if($nocancel2 > 0){
            $st_cancel = "<b>".$nocancel2." Cancel </b> <br>";
          }
          if($wt_app2 > 0){
            $st_wtapp = "<b>".$wt_app2." [Cancel] Waiting Approval </b> <br>";
          }
         $statusPassager = $st_book.$st_commit.$st_lunas.$st_cancel.$st_wtapp;
      /*if($value['status'] < 3){
        $payment = "<li><a href='".site_url("grouptour/product-tour/payment-book/".$value['code'])."'>Payment</a></li>";
      }else{
        $payment = "";
      } */
      $detail_beban = ""
      . "<div style='display: none' id='isi{$value['code']}'>"
        . "<table width='100%'>"
          . "<tr>"
            . "<td>Beban Awal</td>"
            . "<td style='text-align: left'>".number_format($value['beban_awal'])."</td>"
          . "</tr>"
          . "<tr>"
            . "<td>Airport Tax & Flight Insurance </td>"
            . "<td style='text-align: left'>".number_format($value['tax_and_insurance'])."</td>"
          . "</tr>";
      
      //$array_additional = json_decode($data_add);
    
        $total_additional = 0;
      //  print_r($value['additional']); die;
        if(is_array($value['additional'])){
        //  print_r($value->additional);
          foreach ($value['additional'] as $val) {
            
           // $total_additional += $val->nominal;
            if($val->id_currency == 1){
                  $nom_add0 = $val->nominal;
                }elseif($val->id_currency == 2){
                  $nom_add0 = $val->nominal/$value['currency_rate'];
                }
                if($val->pos == 1){
                  $mins = "- ";
                $total_kredit2 += $nom_add0;
              }else{
                $mins = "";
                $total_debit2 += $nom_add0;
              }
            $detail_beban .= "<tr>"
            . "<td>{$val->name}</td>"
            . "<td style='text-align: left'>"."{$mins}".number_format($nom_add0)."</td>"
          . "</tr>";
          }
        }
          $tot_disc_price=0;
        if($value['status_discount'] == "Persen"){
          $tot_disc_price =  (($value['beban_awal'] * $value['discount'])/100);
        }elseif($value['status_discount'] == "Nominal") {
          $tot_disc_price = $value['discount'];
        }
       
        $detail_beban .= "<tr>"
            . "<td>Discount</td>"
            . "<td style='text-align: left'>- ".number_format($tot_disc_price)."</td>"
          . "</tr>"
          . "<tr>"
            . "<td>Pembayaran</td>"
            . "<td style='text-align: left'>- ".number_format($value['pembayaran'])."</td>"
          . "</tr>"
         // . "<tr>"
         //   . "<td>Committed Book</td>"
         //   . "<td style='text-align: left'>".number_format($value->committed_book)."</td>"
         // . "</tr>"
        . "</table>"
      . "</div>"
      . "<div style='display: none' id='isiinfo{$value['code']}'>"
        . "<table width='100%'>"
          . "<tr>"
            . "<td>Tour</td>"
            . "<td style='text-align: left'><a href='".site_url("inventory/product-tour/tour-detail/{$value['id_product_tour']}")."'>{$value['tour']}</a></td>"
          . "</tr>"
          . "<tr>"
            . "<td>Start Date</td>"
            . "<td style='text-align: left'>".date("d F Y", strtotime($value['start_date']))."</td>"
          . "</tr>"
          . "<tr>"
            . "<td>End Date</td>"
            . "<td style='text-align: left'>".date("d F Y", strtotime($value['end_date']))."</td>"
          . "</tr>"
        . "</table>"
      . "</div>"
      . "<script>"
        . "$(function() {"
          . "$('#{$value['code']}').tooltipster({"
            . "content: $('#isi{$value['code']}').html(),"
            . "minWidth: 300,"
            . "maxWidth: 300,"
            . "contentAsHTML: true,"
            . "interactive: true"
          . "});"
          . "$('#info{$value['code']}').tooltipster({"
            . "content: $('#isiinfo{$value['code']}').html(),"
            . "minWidth: 300,"
            . "maxWidth: 300,"
            . "contentAsHTML: true,"
            . "interactive: true"
          . "});"
        . "});"
      . "</script>";
      print "<tr>"
        . "<td>{$value['tanggal']}</td>"
        . "<td><a href='javascript:void(0)' id='info{$value['code']}'>{$value['code']}</a></td>"
        . "<td>{$value['first_name']} {$value['last_name']}</td>"
        . "<td>{$value['email']}</td>"
        . "<td>{$value['telp']}</td>"
        . "<td>{$value['passenger']}</td>"
        . "<td style='text-align: right; font-weight: bold;'>"
          . "<a href='javascript:void(0)' id='{$value['code']}'>".number_format((($value['beban_awal'] + $total_debit2 + $value['tax_and_insurance']) - ($value['pembayaran'] + $total_kredit2 + $tot_disc_price)),0,",",".")."</a>"
          . $detail_beban
        . "</td>"
        . "<td>"
          . "<div class='btn-group'>"
          . "<button data-toggle='dropdown' class='btn btn-small dropdown-toggle'>Action<span class='caret'></span></button>"
          . "<ul class='dropdown-menu'>"
            . "<li><a href='".site_url("inventory/tour-book/book-information/".$value['code'])."'>Detail</a></li>"
           // . $payment
           //  . "<li><a href='".site_url("inventory/tour-book/change-tour/".$value['code'])."'>Change Book Tour</a></li>"
        . "</td>"
      . "</tr>";
  
  }
  ?>
 
</tbody>

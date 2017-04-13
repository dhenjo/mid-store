<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flight extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
    $this->load->model('report/mflight');
  }
  
  /* public function btc_book(){
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>";
    $list = $this->global_models->get_query("SELECT A.*"
      . " FROM tiket_book AS A"
      . " WHERE A.book_code IS NOT NULL"
      . " GROUP BY A.book_code");
    $this->template->build('flight/book', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "report/flight/btc-book",
            'data'        => $list,
            'title'       => lang("antavaya_flight_book"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc',
            'css'         => $css,
            'foot'        => $foot,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('flight/book');
  } */
  
  public function btc_book(){
    
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />"
      ."<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    
    $pst = $this->input->post(NULL);
    if($pst){
    
        $newdata = array(
            'flight_btc_start_date'   => $pst['start_date'],
            'flight_btc_book_code'    => $pst['book_code'],
            'flight_btc_end_date'     => $pst['end_date'],
            'flight_btc_maskapai'     => $pst['maskapai'],
            'flight_btc_pemesan'      => $pst['pemesan'],
            'flight_btc_status'       => $pst['status']
          );
          $this->session->set_userdata($newdata);
    }
    
    if($pst['export']){
      $this->mflight->export_btc_book_xls("Data-Report-Flight-Book");
    }
    
    if($this->session->userdata('flight_btc_start_date') != "" OR $this->session->userdata('flight_btc_end_date') != ""){
      $date = " AND A.tanggal BETWEEN '{$this->session->userdata('flight_btc_start_date')}' AND '{$this->session->userdata('flight_btc_end_date')}' ";
    }
    
    if($this->session->userdata('flight_btc_book_code')){
      $book_code = " AND A.book_code ='{$this->session->userdata('flight_btc_book_code')}'";
    }
    
    if($this->session->userdata('flight_btc_maskapai')){
      $maskapai = " AND B.maskapai ='{$this->session->userdata('flight_btc_maskapai')}'";
    }
    
    if($this->session->userdata('flight_btc_status')){
      $status = " AND A.status ='{$this->session->userdata('flight_btc_status')}'";
    }
    
    if($this->session->userdata('flight_btc_pemesan')){
      $pemesan = " AND CONCAT(A.first_name, ' ', A.last_name) LIKE '%{$this->session->userdata('flight_btc_pemesan')}%' OR A.telphone LIKE '%{$this->session->userdata('flight_btc_pemesan')}%' OR A.email LIKE '%{$this->session->userdata('flight_btc_pemesan')}%'";
    }
          
    $jumlah_list1 = $this->global_models->get_query("SELECT count(A.id_tiket_book) as total"
      . " FROM tiket_book AS A"
      . " INNER JOIN tiket_flight AS B ON B.id_tiket_book = A.id_tiket_book"
      . " WHERE A.book_code IS NOT NULL {$date} {$book_code} {$maskapai} {$status} {$pemesan} "
      . " GROUP BY A.book_code");
    
   $jumlah_list = count($jumlah_list1);
   
    
    $url_list = site_url("report/flight/ajax-btc-book/".$jumlah_list);
    $url_list_halaman = site_url("report/flight/ajax-halaman-btc-book/".$jumlah_list);
    
    $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>"
      ."<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
       ."<script type='text/javascript'>"

      . "$(function() { "
        . "$( '#start_date' ).datetimepicker({ "
          . "dateFormat: 'yy-mm-dd', "
        . "}); "
        . "$( '#end_date' ).datetimepicker({ "
          . "dateFormat: 'yy-mm-dd', "
        . "}); "
      . "}); "
      
      ."function get_list(start){"
        ."if(typeof start === 'undefined'){"
         ."start = 0;"
          ."}"
           ."$.post('{$url_list}/'+start, function(data){"
            ."$('#data_list').html(data);"
             ."$.post('{$url_list_halaman}/'+start, function(data){"
              ."$('#halaman_set').html(data);"
               ." });"
                ."});"
            ."}"
            ."get_list(0);"
      . "</script> ";
    
    
    $this->template->build('flight/book', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "report/flight/btc-book",
            'data'        => $list,
            'title'       => lang("antavaya_flight_book"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc',
            'css'         => $css,
            'foot'        => $foot,
            'menu_action' => 3
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('flight/book');
  }
  
  function ajax_btc_book($total = 0, $start = 0){
    
   if($this->session->userdata('flight_btc_start_date') != "" OR $this->session->userdata('flight_btc_end_date') != ""){
      $date = " AND A.tanggal BETWEEN '{$this->session->userdata('flight_btc_start_date')}' AND '{$this->session->userdata('flight_btc_end_date')}' ";
    }
    
    if($this->session->userdata('flight_btc_book_code')){
      $book_code = " AND A.book_code ='{$this->session->userdata('flight_btc_book_code')}'";
    }
    
    if($this->session->userdata('flight_btc_maskapai')){
      $maskapai = " AND B.maskapai ='{$this->session->userdata('flight_btc_maskapai')}'";
    }
    
    if($this->session->userdata('flight_btc_status')){
      $status = " AND A.status ='{$this->session->userdata('flight_btc_status')}'";
    }
    
    if($this->session->userdata('flight_btc_pemesan')){
      $pemesan = " AND CONCAT(A.first_name, ' ',A.last_name) LIKE '%{$this->session->userdata('flight_btc_pemesan')}%' OR A.telphone LIKE '%{$this->session->userdata('flight_btc_pemesan')}%' OR A.email LIKE '%{$this->session->userdata('flight_btc_pemesan')}%'";
    }
    
      $data = $this->global_models->get_query("SELECT A.*"
      . " FROM tiket_book AS A"
      . " INNER JOIN tiket_flight AS B ON B.id_tiket_book = A.id_tiket_book"
      . " WHERE A.book_code IS NOT NULL {$date} {$book_code} {$maskapai} {$status} {$pemesan}"
      . " GROUP BY A.book_code ORDER BY A.tanggal DESC"
      . " LIMIT {$start}, 10");
      
      $data_maskapai = array("0"	=> "All",
				  "GA" => "Garuda",
				  "ID"	=> "Batik Air",
				  "IW"  => "Wings Air",
				  "JT"	=> "Lion Air",
				  "QG"	=> "Citylink",
				  "QZ"	=> "Air Asia",
				  "SJ"	=> "Sriwijaya Air");
      
    if(is_array($data)){
    $status = array(
      1 => "<span class='label label-default'>Proses</span>",
      3 => "<span class='label label-success'>Issued</span>",
      5 => "<span class='label label-success'>IBA</span>",
      4 => "<span class='label label-info'>Cancel</span>",
      6 => "<span class='label label-danger'>No Tiket Gagal</span>",
      7 => "<span class='label label-danger'>File Tiket Gagal</span>",
      8 => "<span class='label label-warning'>Refund</span>",
    );
    
    $r = date("Y-m-d");
    $detail_harga = "";
    foreach ($data as $key => $value) {
      
      $flight = $this->global_models->get("tiket_flight", array("id_tiket_book" => $value->id_tiket_book));
      $tanggal_issued = $this->global_models->get("tiket_issued", array("id_tiket_book" => $value->id_tiket_book));
     $issued_tanggal = "";
      if($tanggal_issued[0]->tanggal){
        
        if($value->status == 3){
          $issued_tanggal = "<br>".$tanggal_issued[0]->tanggal;
          }elseif($value->status == 6){
          $issued_tanggal = "<br>".$tanggal_issued[0]->tanggal;
        }elseif ($value->status == 7) {
            $issued_tanggal = "<br>".$tanggal_issued[0]->tanggal;
       
          }
        
      }
      $maskapai1 = "";
      foreach($flight AS $flg){
        $items = $this->global_models->get("tiket_flight_items", array("id_tiket_flight" => $flg->id_tiket_flight));
        if($flg->flight == 1){
          $maskapai1 .= $data_maskapai[$flg->maskapai];
          $items1st = $penerbangan_kembali = $penumpang = "";
          foreach($items AS $itm){
            $items1st .= $itm->flight_no." {$itm->dari} - {$itm->ke} ".date("Y/M/d H:s", strtotime($itm->departure))."-".date("H:s", strtotime($itm->arrive))."<br />";
          }
          $tipe = "One Way";
          $penerbangan_dari = "<tr><td><h4>Penerbangan {$this->global_models->array_kota($flg->dari)} - {$this->global_models->array_kota($flg->ke)}</h4><td></tr><tr><td>{$items1st}<td></tr>";
        }
        else{
          $maskapai1 .= "<br>".$data_maskapai[$flg->maskapai];
          $items2nd = "";
          foreach($items AS $itm){
            $items2nd .= $itm->flight_no." {$itm->dari} - {$itm->ke} ".date("Y/M/d H:s", strtotime($itm->departure))."-".date("H:s", strtotime($itm->arrive))."<br />";
          }
          $penerbangan_kembali = "<tr><td><h4>Penerbangan {$this->global_models->array_kota($flg->dari)} - {$this->global_models->array_kota($flg->ke)}</h4><td></tr><tr><td>{$items2nd}<td></tr>";
          $tipe = "Round Trip";
        }
      }
      
      $type = array(
        1 => "Adult", 2 => "Child", 3 => "Infant"
      );
      
      $passenger = $this->global_models->get("tiket_passenger", array("id_tiket_book" => $value->id_tiket_book));
      $price1st = $value->diskon;
      $price2nd = 0;
      foreach($passenger AS $psgr){
        $price1st += $psgr->price;
        $price2nd += $psgr->price2nd;
        $penumpang .= "<tr><td>{$psgr->title} {$psgr->first_name} {$psgr->last_name} ".date("Y M d", strtotime($psgr->tanggal_lahir))." {$type[$psgr->type]} "
        . number_format(($psgr->price + $psgr->price2nd),0,".",",")." </td></tr>";
      }
      
      $detail_harga = "<table width='100%'>"
        . "<tr>"
          . "<td>Penerbangan Pergi</td>"
          . "<td style='text-align: right'>".number_format($price1st,0,".",",")."</td>"
        . "</tr>"
        . "<tr>"
          . "<td>Penerbangan Kembali</td>"
          . "<td style='text-align: right'>".number_format($price2nd,0,".",",")."</td>"
        . "</tr>"
        . "<tr>"
          . "<td>Hemat</td>"
          . "<td style='text-align: right'>".number_format($value->diskon,0,".",",")."</td>"
        . "</tr>"
        . "<tr>"
          . "<td>TOTAL</td>"
          . "<td style='text-align: right'>".number_format($value->harga_bayar,0,".",",")."</td>"
        . "</tr>"
        . "</table>";
      
      $hasil .= '
      <tr>
        <td>'.date("Y-m-d H:i:s", strtotime($value->tanggal)).'</td>
        <td>
          <a class="btn btn-block btn-primary" data-toggle="modal" data-target="#book'.$value->id_tiket_book.'">
            '.$value->book_code.' '.$value->book2nd.'
          </a>
          <div class="modal fade" id="book'.$value->id_tiket_book.'" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"> '.$value->book_code.' '.$value->book2nd.'</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <table>
                            '.$penerbangan_dari.'
                            '.$penerbangan_kembali.'
                            <tr>
                              <td><h4>Penumpang</h4><td>
                            </tr>
                            '.$penumpang.'
                          </table>
                        </div>
                    </div>
                    <div class="modal-footer clearfix">
                      <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        </td>
        <td>'.$tipe.'</td>
          <td><center>'.$maskapai1.'</center></td>
        <td>
          '.$value->first_name.' '.$value->last_name.' <br />
          '.$value->email.' <br />
          '.$value->telphone.'
        </td>
        <td>'.date("Y-m-d H:i", strtotime($value->timelimit)).'</td>
        <td>'.$value->cara_bayar.'</td>
        <td><center>'.$status[$value->status].$issued_tanggal.'</center></td>
        <td style="text-align:right"><span style="display: none">'.$r.'</span>
          <a id="harga'.$value->id_tiket_book.'" href="javascript:void(0)">
            '.number_format($value->harga_bayar,0,".",",").'</a>
          <div style="display: none" id="isiharga'.$value->id_tiket_book.'">'.$detail_harga.'</div>
          <script>
          $(function() {
            $("#harga'.$value->id_tiket_book.'").tooltipster({
              content: $("#isiharga'.$value->id_tiket_book.'").html(),
                minWidth: 300,
                maxWidth: 300,
                contentAsHTML: true,
                interactive: true
            });
          });
          </script>
        </td>
      </tr>';
      $r++;
    }
  }
    
    print $hasil;
    die;
  }
  
  function ajax_halaman_btc_book($total = 0, $start = 0){
    
    $this->load->library('pagination');

    $config['base_url'] = '';
    $config['total_rows'] = $total;
    $config['per_page'] = 10; 
    $config['uri_segment'] = 5; 
    $config['cur_tag_open'] = "<li class='active'><a href='javascript:void(0)'>"; 
    $config['cur_tag_close'] = "</a></li>"; 
    $config['first_tag_open'] = "<li>"; 
    $config['first_tag_close'] = "</li>"; 
    $config['last_tag_open'] = "<li>"; 
    $config['last_tag_close'] = "</li>"; 
    $config['next_tag_open'] = "<li>"; 
    $config['next_tag_close'] = "</li>"; 
    $config['prev_tag_open'] = "<li>"; 
    $config['prev_tag_close'] = "</li>"; 
    $config['num_tag_open'] = "<li>"; 
    $config['num_tag_close'] = "</li>";
    $config['function_js'] = "get_list";
    $this->pagination->initialize($config); 
    
      print "<ul id='halaman_delete' class='pagination pagination-sm no-margin pull-right'>"
    . "{$this->pagination->create_links_ajax()}"
    . "</ul>";
    die;
  }
  
  function btc_issued($sort = 1,$field = ""){
   // print_r($_REQUEST); die;
//    $this->debug($this->global_models->get_query("SELECT SUM(price) AS jml"
//        . " FROM tiket_flight"
//        . " WHERE id_tiket_book = '20' AND "
//        . " id_tiket_flight IN (SELECT id_tiket_flight FROM tiket_flight WHERE id_tiket_book = '20' GROUP BY issued_no)"), true);
    $this->load->model('discount/mdiscount');
    
    if($sort == 1){
          $sort = "ASC";
      }elseif ($sort == 2) {
            $sort = "DESC";
        }
        if($field == 1){
            $field = "A.tanggal";
        }elseif($field == 2){
            $field = "B.harga_bayar";
        }elseif($field == 3){
            $field = "A.diskon";
        }elseif($field == 4){
            $field = "B.id_website_hemat_mega";
        }elseif($field == 5){
            $field = "B.harga_bayar";
        }elseif($field == 6){
            $field = "C.price";
        }else{
            $field = "";
        }
        
      if($field){
          $orderby = " ORDER BY ".$field." ".$sort;
      }else{
          $orderby = " ORDER BY A.tanggal DESC";
      }
      
    $pst = $this->input->post(NULL, TRUE);
   // $order = "ORDER BY tanggal DESC";
     
    
    if($pst){
    
        $newdata = array(
            'flight_report_transaksi_booking_from'    => $pst['booking_from'],
            'flight_report_transaksi_book_code'       => $pst['book_code'],
            'flight_report_transaksi_booking_to'      => $pst['booking_to'],
            'flight_report_transaksi_payment'         => $pst['payment'],
            'flight_report_transaksi_maskapai'        => $pst['maskapai'],
            'flight_report_transaksi_tiket_no'        => $pst['tiket_no']
          );
          $this->session->set_userdata($newdata);
    }
    
    if($pst['export']){
      $this->mflight->export_btc_issued_xls("Data-Report-transaksi",$sort,$field);
    }
    
     if($this->session->userdata('flight_report_transaksi_booking_from') != "" OR $this->session->userdata('flight_report_transaksi_booking_to') != ""){
      $date = " AND A.tanggal BETWEEN '{$this->session->userdata('flight_report_transaksi_booking_from')}' AND '{$this->session->userdata('flight_report_transaksi_booking_to')}'";
    }else{
      if(!$this->session->userdata('flight_report_transaksi_book_code')){
        $date = " AND A.tanggal BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-t")."'";
      }
    }
    
    if($this->session->userdata('flight_report_transaksi_book_code')){
      $book_code = " AND (C.book_code LIKE '%{$this->session->userdata('flight_report_transaksi_book_code')}%' OR B.book_code LIKE '%{$this->session->userdata('flight_report_transaksi_book_code')}%')";
    }
    
    if($this->session->userdata('flight_report_transaksi_payment')){
      $channel2 = " AND A.channel ='{$this->session->userdata('flight_report_transaksi_payment')}'";
    }
    
    if($this->session->userdata('flight_report_transaksi_maskapai')){
      $maskapai = " AND C.maskapai LIKE '%{$this->session->userdata('flight_report_transaksi_maskapai')}%'";
    }
    
    if($this->session->userdata('flight_report_transaksi_tiket_no')){
      $issued_no = " AND A.issued_no LIKE '{$this->session->userdata('flight_report_transaksi_tiket_no')}%'";
    }
    
    
    $css = ""
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    
    $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
            ."<script type='text/javascript'>"
      . "$(function() { "
        . "$( '#start_date' ).datetimepicker({ "
          . "dateFormat: 'yy-mm-dd', "
        . "}); "
        . "$( '#end_date' ).datetimepicker({ "
          . "dateFormat: 'yy-mm-dd', "
        . "}); "
      . "}); "
      . "</script> ";
    
//    $report = $this->global_models->get_query("SELECT C.*, IFNULL(C.book_code, B.book_code) AS code"
//      . " ,A.channel"
//      . " FROM tiket_flight AS C"
//      . " LEFT JOIN tiket_issued AS A ON A.id_tiket_book = C.id_tiket_book"
//      . " LEFT JOIN tiket_book AS B ON C.id_tiket_book = B.id_tiket_book"
//      . " WHERE 1=1 {$date} {$book_code} {$channel2} {$maskapai}"
//      . " {$orderby}");
//    $this->debug($report, true);
    $report = $this->global_models->get_query("SELECT A.*, B.book_code, B.tanggal AS tglbook, B.harga_normal, C.maskapai"
      . " ,D.id_discount, D.type AS type_discount, D.nilai AS diskon_spesial, D.status AS status_discount"
      . " FROM tiket_issued AS A"
      . " LEFT JOIN tiket_book AS B ON A.id_tiket_book = B.id_tiket_book"
      . " LEFT JOIN tiket_flight AS C ON A.id_tiket_book = C.id_tiket_book"
      . " LEFT JOIN tiket_book_discount AS D ON A.id_tiket_issued = D.id_tiket_issued"
      . " WHERE 1=1 {$date} {$book_code} {$channel2} {$maskapai} {$issued_no}"
      . " GROUP BY B.book_code"
      . " {$orderby}");
//    $this->debug($report, true);  
    $this->template->build('flight/report-transaksi', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "report/flight/btc-issued",
            'data'        => $report,
            'title'       => lang("antavaya_report_transaksi"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc',
            'css'         => $css,
            'foot'        => $foot,
            'type_payment' => $cra_byar,
//            'sort'        => $sort,
//            'serach_data'   => $search,
            'before_table' => $before_table,
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('flight/report-transaksi');
  }
  
  function btc_maskapai($sort = 1,$field = ""){
    
    if($sort == 1){
          $sort = "ASC";
      }elseif ($sort == 2) {
            $sort = "DESC";
        }
        if($field == 1){
            $field = "A.tanggal";
        }elseif($field == 2){
            $field = "B.harga_bayar";
        }elseif($field == 3){
            $field = "A.diskon";
        }elseif($field == 4){
            $field = "B.id_website_hemat_mega";
        }elseif($field == 5){
            $field = "B.harga_bayar";
        }elseif($field == 6){
            $field = "C.price";
        }else{
            $field = "";
        }
        
      if($field){
          $orderby = " ORDER BY ".$field." ".$sort;
      }else{
          $orderby = " ORDER BY A.tanggal DESC";
      }
      
    $pst = $this->input->post(NULL, TRUE);
   // $order = "ORDER BY tanggal DESC";
    
    if($pst){
    
        $newdata = array(
            'flight_report_maskapai_booking_from'    => $pst['booking_from'],
            'flight_report_maskapai_book_code'       => $pst['book_code'],
            'flight_report_maskapai_booking_to'      => $pst['booking_to'],
            'flight_report_maskapai_payment'         => $pst['payment'],
            'flight_report_maskapai_maskapai'        => $pst['maskapai'],
            'flight_report_maskapai_tiket_no'         => $pst['tiket_no']
          );
          $this->session->set_userdata($newdata);
    }
    
    if($pst['export']){
      $this->mflight->export_btc_maskapai_xls("Data-Report-Maskapai",$sort,$field);
    }
    
     if($this->session->userdata('flight_report_maskapai_booking_from') != "" OR $this->session->userdata('flight_report_maskapai_booking_to') != ""){
      $date = " AND A.tanggal BETWEEN '{$this->session->userdata('flight_report_maskapai_booking_from')}' AND '{$this->session->userdata('flight_report_maskapai_booking_to')}'";
    }else{
      if(!$this->session->userdata('flight_report_maskapai_book_code')){
        $date = " AND A.tanggal BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-t")."'";
      }
    }
    
    if($this->session->userdata('flight_report_maskapai_book_code')){
      $book_code = " AND (C.book_code LIKE '%{$this->session->userdata('flight_report_maskapai_book_code')}%' OR B.book_code LIKE '%{$this->session->userdata('flight_report_maskapai_book_code')}%')";
    }
    
    if($this->session->userdata('flight_report_maskapai_payment')){
      $channel2 = " AND A.channel ='{$this->session->userdata('flight_report_maskapai_payment')}'";
    }
    
    if($this->session->userdata('flight_report_maskapai_maskapai')){
      $maskapai = " AND C.maskapai LIKE '%{$this->session->userdata('flight_report_maskapai_maskapai')}%'";
    }
    
    if($this->session->userdata('flight_report_maskapai_tiket_no')){
      $issued_no = " AND C.issued_no LIKE '{$this->session->userdata('flight_report_maskapai_tiket_no')}%'";
    }
    
    
    $css = ""
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    
    $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
            ."<script type='text/javascript'>"
      . "$(function() { "
        . "$( '#start_date' ).datetimepicker({ "
          . "dateFormat: 'yy-mm-dd', "
        . "}); "
        . "$( '#end_date' ).datetimepicker({ "
          . "dateFormat: 'yy-mm-dd', "
        . "}); "
      . "}); "
      . "</script> ";
    
    $report = $this->global_models->get_query("SELECT C.*, IFNULL(C.book_code, B.book_code) AS code"
      . " ,A.channel"
      . " FROM tiket_flight AS C"
      . " LEFT JOIN tiket_issued AS A ON A.id_tiket_book = C.id_tiket_book"
      . " LEFT JOIN tiket_book AS B ON C.id_tiket_book = B.id_tiket_book"
      . " WHERE (B.status = 3 OR B.status = 5)"
      . " AND C.price > 0"
      . " {$date} {$book_code} {$channel2} {$maskapai} {$issued_no}"
      . " GROUP BY C.book_code"
      . " {$orderby}");
//    $this->debug($report, true);
//    $report = $this->global_models->get_query("SELECT A.*, B.book_code, B.tanggal AS tglbook, B.harga_normal"
//      . " FROM tiket_issued AS A"
//      . " LEFT JOIN tiket_book AS B ON A.id_tiket_book = B.id_tiket_book"
//      . " LEFT JOIN tiket_flight AS C ON A.id_tiket_book = C.id_tiket_book"
//      . " WHERE 1=1 {$date} {$book_code} {$channel2} {$maskapai}"
//      . " GROUP BY B.book_code"
//      . " {$orderby}");
      
    $this->template->build('flight/report-maskapai', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "report/flight/btc-maskapai",
            'data'        => $report,
            'title'       => lang("antavaya_report_maskapai"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc',
            'css'         => $css,
            'foot'        => $foot,
            'type_payment' => $cra_byar,
//            'sort'        => $sort,
//            'serach_data'   => $search,
            'before_table' => $before_table,
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('flight/report-maskapai');
  }
  
  function btc_summary_maskapai($sort = 1,$field = ""){
    
    if($sort == 1){
          $sort = "ASC";
      }elseif ($sort == 2) {
            $sort = "DESC";
        }
        if($field == 1){
            $field = "A.tanggal";
        }elseif($field == 2){
            $field = "B.harga_bayar";
        }elseif($field == 3){
            $field = "A.diskon";
        }elseif($field == 4){
            $field = "B.id_website_hemat_mega";
        }elseif($field == 5){
            $field = "B.harga_bayar";
        }elseif($field == 6){
            $field = "C.price";
        }else{
            $field = "";
        }
        
      if($field){
          $orderby = " ORDER BY ".$field." ".$sort;
      }else{
          $orderby = " ORDER BY A.tanggal DESC";
      }
      
    $pst = $this->input->post(NULL, TRUE);
   // $order = "ORDER BY tanggal DESC";
    
    if($pst){
    
        $newdata = array(
            'flight_report_maskapai_booking_from'    => $pst['booking_from'],
            'flight_report_maskapai_booking_to'      => $pst['booking_to'],
          );
          $this->session->set_userdata($newdata);
    }
    
    
     if($this->session->userdata('flight_report_maskapai_booking_from') != "" OR $this->session->userdata('flight_report_maskapai_booking_to') != ""){
      $date = " AND A.tanggal BETWEEN '{$this->session->userdata('flight_report_maskapai_booking_from')}' AND '{$this->session->userdata('flight_report_maskapai_booking_to')}'";
    }else{
      $newdata = array(
          'flight_report_maskapai_booking_from'    => date("Y-m")."-01",
          'flight_report_maskapai_booking_to'       => date("Y-m-t"),
        );
        $this->session->set_userdata($newdata);
      $date = " AND A.tanggal BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-t")."'";
    }
     
   
    $css = ""
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    
    $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
            ."<script type='text/javascript'>"
      . "$(function() { "
        . "$( '#start_date' ).datetimepicker({ "
          . "dateFormat: 'yy-mm-dd', "
        . "}); "
        . "$( '#end_date' ).datetimepicker({ "
          . "dateFormat: 'yy-mm-dd', "
        . "}); "
      . "}); "
      . "</script> ";
    
    $report = $this->global_models->get_query("SELECT C.*, SUM(C.price) AS hpp"
      . " FROM tiket_flight AS C"
      . " LEFT JOIN tiket_issued AS A ON A.id_tiket_book = C.id_tiket_book"
      . " LEFT JOIN tiket_book AS B ON C.id_tiket_book = B.id_tiket_book"
      . " WHERE (B.status = 3 OR B.status = 5)"
      . " AND C.price > 0"
      . " {$date} {$book_code} {$channel2} {$maskapai} {$issued_no}"
      . " GROUP BY C.book_code"
      . " {$orderby}");
//    $this->debug($report, true);  
    $hasil = array();
    foreach($report AS $rp){
      $hasil[$rp->maskapai] += $rp->price;
    }
    if($pst['export']){
      $this->mflight->export_btc_summary_maskapai_xls("Data-Report-Summary-Maskapai",$hasil);
    }
//    $this->debug($report);
//    $this->debug($hasil, true);
//    $report = $this->global_models->get_query("SELECT A.*, B.book_code, B.tanggal AS tglbook, B.harga_normal"
//      . " FROM tiket_issued AS A"
//      . " LEFT JOIN tiket_book AS B ON A.id_tiket_book = B.id_tiket_book"
//      . " LEFT JOIN tiket_flight AS C ON A.id_tiket_book = C.id_tiket_book"
//      . " WHERE 1=1 {$date} {$book_code} {$channel2} {$maskapai}"
//      . " GROUP BY B.book_code"
//      . " {$orderby}");
      
    $this->template->build('flight/report-summary-maskapai', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "report/flight/btc-summary-maskapai",
            'data'        => $hasil,
            'title'       => lang("antavaya_report_summary_maskapai"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc',
            'css'         => $css,
            'foot'        => $foot,
            'type_payment' => $cra_byar,
//            'sort'        => $sort,
//            'serach_data'   => $search,
            'before_table' => $before_table,
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('flight/report-summary-maskapai');
  }
  
  function btc_sales($sort = 1,$field = ""){
//    $this->debug($this->global_models->get_query("SELECT SUM(price) AS jml"
//        . " FROM tiket_flight"
//        . " WHERE id_tiket_book = '20' AND "
//        . " id_tiket_flight IN (SELECT id_tiket_flight FROM tiket_flight WHERE id_tiket_book = '20' GROUP BY issued_no)"), true);
    $this->load->model('discount/mdiscount');
    
    if($sort == 1){
          $sort = "ASC";
      }elseif ($sort == 2) {
            $sort = "DESC";
        }
        if($field == 1){
            $field = "A.tanggal";
        }elseif($field == 2){
            $field = "B.harga_bayar";
        }elseif($field == 3){
            $field = "A.diskon";
        }elseif($field == 4){
            $field = "B.id_website_hemat_mega";
        }elseif($field == 5){
            $field = "B.harga_bayar";
        }elseif($field == 6){
            $field = "C.price";
        }else{
            $field = "";
        }
        
      if($field){
          $orderby = " ORDER BY ".$field." ".$sort;
      }else{
          $orderby = " ORDER BY A.tanggal DESC";
      }
      
    $pst = $this->input->post(NULL, TRUE);
   // $order = "ORDER BY tanggal DESC";
    
    if($pst){
    
        $newdata = array(
            'flight_report_transaksi_sales_booking_from'    => $pst['booking_from'],
            'flight_report_transaksi_sales_book_code'       => $pst['book_code'],
            'flight_report_transaksi_sales_booking_to'      => $pst['booking_to'],
            'flight_report_transaksi_sales_payment'         => $pst['payment'],
            'flight_report_transaksi_sales_maskapai'        => $pst['maskapai'],
            'flight_report_transaksi_sales_tiket_no'        => $pst['tiket_no']
          );
          $this->session->set_userdata($newdata);
    }
    
    if($pst['export']){
      $this->mflight->export_btc_sales_xls("Data-Report-Transaksi-Sales",$sort,$field);
    }
    
    
     if($this->session->userdata('flight_report_transaksi_sales_booking_from') != "" OR $this->session->userdata('flight_report_transaksi_sales_booking_to') != ""){
      $date = " AND A.tanggal BETWEEN '{$this->session->userdata('flight_report_transaksi_sales_booking_from')}' AND '{$this->session->userdata('flight_report_transaksi_sales_booking_to')}'";
    }else{
      if(!$this->session->userdata('flight_report_transaksi_book_code')){
        $date = " AND A.tanggal BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-t")."'";
      }
    }
    
    if($this->session->userdata('flight_report_transaksi_sales_book_code')){
      $book_code = " AND B.book_code LIKE '%{$this->session->userdata('flight_report_transaksi_sales_book_code')}%'";
    }
    
    if($this->session->userdata('flight_report_transaksi_sales_payment')){
      $channel2 = " AND A.channel ='{$this->session->userdata('flight_report_transaksi_sales_payment')}'";
    }
    if($this->session->userdata('flight_report_transaksi_sales_maskapai')){
      $maskapai = " AND C.maskapai LIKE '%{$this->session->userdata('flight_report_transaksi_sales_maskapai')}%'";
    }
    
    if($this->session->userdata('flight_report_transaksi_sales_tiket_no')){
      $issued_no = " AND A.issued_no LIKE '{$this->session->userdata('flight_report_transaksi_sales_tiket_no')}%'";
    }
    
    
    $css = ""
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    
    $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
            ."<script type='text/javascript'>"
      . "$(function() { "
        . "$( '#start_date' ).datetimepicker({ "
          . "dateFormat: 'yy-mm-dd', "
        . "}); "
        . "$( '#end_date' ).datetimepicker({ "
          . "dateFormat: 'yy-mm-dd', "
        . "}); "
      . "}); "
      . "</script> ";
    
    $report = $this->global_models->get_query("SELECT A.*, B.book_code, B.tanggal AS tglbook, B.harga_normal"
      . " FROM tiket_issued AS A"
      . " LEFT JOIN tiket_book AS B ON A.id_tiket_book = B.id_tiket_book"
      . " LEFT JOIN tiket_flight AS C ON B.id_tiket_book = C.id_tiket_book"
      . " WHERE 1=1 {$date} {$book_code} {$channel2} {$maskapai} {$issued_no}"
      . " GROUP BY B.book_code"
      . " {$orderby}");
      
    $this->template->build('flight/report-transaksi-sales', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "report/flight/btc-sales",
            'data'        => $report,
            'title'       => lang("antavaya_report_transaksi_sales"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc',
            'css'         => $css,
            'foot'        => $foot,
            'type_payment' => $cra_byar,
//            'sort'        => $sort,
//            'serach_data'   => $search,
            'before_table' => $before_table,
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('flight/report-transaksi-sales');
  }
  
  function sync_diskon(){
    $this->load->model('discount/mdiscount');
    $book = $this->global_models->get_query("SELECT A.*, B.book_code, B.tanggal AS tglbook, B.harga_normal, C.maskapai"
      . " ,D.id_discount, D.type AS type_discount, D.nilai, D.status AS status_discount"
      . " FROM tiket_issued AS A"
      . " LEFT JOIN tiket_book AS B ON A.id_tiket_book = B.id_tiket_book"
      . " LEFT JOIN tiket_flight AS C ON A.id_tiket_book = C.id_tiket_book"
      . " LEFT JOIN tiket_book_discount AS D ON A.id_tiket_issued = D.id_tiket_issued"
      . " WHERE 1=1"
      . " GROUP BY B.book_code");
    foreach ($book AS $bk){
      if($bk->book_code){
        $discount = $this->mdiscount->btc_payment($bk->tanggal, $bk->harga_normal, $bk->channel);
        if($discount['nilai'] > 0){
          $kirim_diskon[] = array(
            "id_tiket_book"         => $bk->id_tiket_book,
            "id_tiket_issued"       => $bk->id_tiket_issued,
            "id_discount"           => $discount['id_discount'],
            "type"                  => 1,
            "nilai"                 => $discount['nilai'],
            "status"                => 2,
          );
          $diskon_maskapai = $bk->diskon - $discount['nilai'];
          if($diskon_maskapai < 0)
            $diskon_maskapai = 0;
          $this->global_models->update("tiket_issued", array("id_tiket_issued" => $bk->id_tiket_issued), array("diskon" => $diskon_maskapai));
        }
      }
    }
    if($kirim_diskon){
      $this->global_models->insert_batch("tiket_book_discount", $kirim_diskon);
    }
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
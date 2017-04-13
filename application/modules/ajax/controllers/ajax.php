<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  function department(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM hrm_department
      WHERE 
      LOWER(title) LIKE '%{$q}%' OR LOWER(code) LIKE '%{$q}%'
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_hrm_department,
            "label" => $tms->title." - ".$tms->code,
            "value" => $tms->title." - ".$tms->code,
        );
      }
    }
    else{
      $result[] = array(
          "id"    => 0,
          "label" => "No Found",
          "value" => "No Found",
      );
    }
    echo json_encode($result);
    die;
  }
  
  function hotel_nation(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $this->global_models->get_connect("terminal");
    $items = $this->global_models->get_query("
      SELECT *
      FROM master_hotel_nation
      WHERE 
      LOWER(title) LIKE '%{$q}%' OR LOWER(kode) LIKE '%{$q}%'
      LIMIT 0,10
      ");
    $this->global_models->get_connect("default");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_master_hotel_nation,
            "label" => $tms->title." - ".$tms->kode,
            "value" => $tms->title." - ".$tms->kode,
        );
      }
    }
    else{
      $result[] = array(
          "id"    => 0,
          "label" => "No Found",
          "value" => "No Found",
      );
    }
    echo json_encode($result);
    die;
  }
  
  function mrp_pengajuan_asset(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM mrp_pengajuan_asset
      WHERE 
      LOWER(title) LIKE '%{$q}%'
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_mrp_pengajuan_asset,
            "label" => $tms->title,
            "value" => $tms->title,
        );
      }
    }
    else{
      $result[] = array(
          "id"    => 0,
          "label" => "No Found",
          "value" => "No Found",
      );
    }
    echo json_encode($result);
    die;
  }
  
  function master_asset_pengajuan(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM mrp_master_asset_pengajuan
      WHERE 
      LOWER(title) LIKE '%{$q}%'
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_mrp_master_asset_pengajuan,
            "label" => $tms->title,
            "value" => $tms->title,
        );
      }
    }
    else{
      $result[] = array(
          "id"    => 0,
          "label" => "No Found",
          "value" => "No Found",
      );
    }
    echo json_encode($result);
    die;
  }
  
  function users(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM m_users
      WHERE 
      LOWER(name) LIKE '%{$q}%'
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_users,
            "label" => $tms->name,
            "value" => $tms->name,
        );
      }
    }
    else{
      $result[] = array(
          "id"    => 0,
          "label" => "No Found",
          "value" => "No Found",
      );
    }
    echo json_encode($result);
    die;
  }
  
  function ajax_halaman_default($total = 0, $start = 0){
    
    $this->load->library('pagination');

    $config['base_url'] = '';
    $config['total_rows'] = $total;
    $config['per_page'] = 10; 
    $config['uri_segment'] = 4; 
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
  
  function add_row_pengajuan_asset(){
    $html = $this->form_eksternal->form_input('items[]', "", 'class="form-control input-sm items" placeholder="Item" style="width: 40%"')
      . "Qty ".$this->form_eksternal->form_input('qty[]', "", 'class="form-control input-sm" style="width: 40%" placeholder="Qty"')
      . $this->form_eksternal->form_textarea('note[]', "", 'style="height: 70px" class="form-control input-sm" placeholder="Note"')
      . "<br /><br />"
      . "<script>"
      . "$(function() {"
      . "$( '.items' ).autocomplete({"
        . "source: '".site_url("ajax/master-asset-pengajuan")."',"
        . "minLength: 1,"
        . "search  : function(){ $(this).addClass('working');},"
        . "open    : function(){ $(this).removeClass('working');},"
      . "});"
      . "});"
      . "</script>";
    print $html;
    die;
  }
  
  /**
   * tour
   * hendri 2015-05-10
   */
  function add_row_product_tour(){
     $angka = rand(11111,99999);
     $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));     
   
     $status_nb = array(1 => "Persen (%)",
                        2 => "Nominal");
    
    $html = " 
     <link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />
      <script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>
     <script type='text/javascript'>
      function addDays(myDate,days) {
      var month = new Array();
        month[0] = '01';
        month[1] = '02';
        month[2] = '03';
        month[3] = '04';
        month[4] = '05';
        month[5] = '06';
        month[6] = '07';
        month[7] = '08';
        month[8] = '09';
        month[9] = '10';
        month[10] = '11';
        month[11] = '12';
      
       var hari = new Array();
        hari[1] = '01';
        hari[2] = '02';
        hari[3] = '03';
        hari[4] = '04';
        hari[5] = '05';
        hari[6] = '06';
        hari[7] = '07';
        hari[8] = '08';
        hari[9] = '09';
        hari[10] = '10';
        hari[11] = '11';
        hari[12] = '12';
        var day2 = (days - 1);
        var myDate = new Date(myDate.getTime() + day2*24*60*60*1000);
        var hari2 = 0;
        if(myDate.getDate() > 10){
        hari2 =  myDate.getDate();
        }else{
        hari2 = hari[myDate.getDate()];
        }
        return myDate.getFullYear() + '-' + month[myDate.getMonth()] + '-' +  hari2;
        }
        function changedate(){
        var today_date = $('#start_date2_$angka').val();
        var myDate = new Date(today_date);
        var totday = $('#tot_days').val()* 1;
        var newDate = addDays(myDate,totday);
        $('#end_date2_$angka').val(newDate);
        }
      
      $(function() {
              $( '#start_date2_$angka' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
              $( '#start_time2_$angka' ).timepicker();
            $( '#end_time2_$angka' ).timepicker();
              $( '#end_date2_$angka' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
            });
        </script>" 
     ."<div class='row'>
                        <div class='col-md-4'>
                            <div class='box box-primary'>
                                <div class='box-body'>
                                    <div class='form-group'>
                                        <label>Kode PS</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('kode_ps[]', "",  "  class='form-control input-sm' placeholder='Kode Tour Information'")."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Start Date</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('start_date[]', "",  "onchange='changedate()' id='start_date2_$angka' class='start_date'  class='form-control input-sm' placeholder='Start Date'")."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>ETD</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('etd[]', "",  " id='start_time2_$angka' class='start_time'  class='form-control input-sm' placeholder='ETD'")."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>End Date</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input("end_date[]", ""," id='end_date2_$angka' class='end_date'  class='form-control input-sm' placeholder='End Date'")."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>ETA</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('eta[]', "",  " id='end_time2_$angka' class='start_time'  class='form-control input-sm' placeholder='ETA'")."</div>
                                    </div>
                                    
                                     <div class='form-group'>
                                        <label>Available Seat</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input("available_seat[]","", 'class="form-control input-sm"  placeholder="Available Seat"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Currency</label>
                                        <div class='input-group'>".$this->form_eksternal->form_dropdown('id_currency[]', $dropdown, "", 'class="form-control" placeholder="Currency"')."</div><!-- /.input group -->
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='box box-primary'>
                                <div class='box-body'>
                                 <div class='form-group'>
                                        <label>FLT</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('flt[]', "", 'class="form-control input-sm"  placeholder="FLT"')."</div><!-- /.input group -->
                                    </div>
                                    <div class='form-group'>
                                        <label>IN</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('in[]', "", 'class="form-control input-sm"  placeholder="IN"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>OUT</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('out[]', "", 'class="form-control input-sm"  placeholder="OUT"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Adult Triple/Twin</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('adult_triple_twin[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Adult Triple/Twin"')."</div><!-- /.input group -->
                                    </div>
                                    <div class='form-group'>
                                        <label>Child Twin Bed</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('child_twin_bed[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child Twin Bed"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Child Extra Bed</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('child_extra_bed[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child Extra Bed"')."</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class='col-md-4'>
                    <div class='box box-primary'>
                                <div class='box-body'>
                                <div class='form-group'>
                                        <label>Child No Bed</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('child_no_bed[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child No Bed"')."</div>
                                    </div>
                                <div class='form-group'>
                                        <label>SGL SUPP</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('sgl_supp[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="SGL SUPP"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Airport Tax & Flight Insurance</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('airport_tax[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Airport Tax & Flight Insurance"')."</div>
                                    </div>
                                     <div class='form-group'>
                                        <label>Harga Visa</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('visa[]', "", ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Harga Visa"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Discount Tetap</label>
                                        <div class='input-group'>".$this->form_eksternal->form_dropdown('stnb_discount_tetap[]', $status_nb, "", 'class="form-control" style="width:60%"').$this->form_eksternal->form_input('discount_tetap[]', "", 'style="width: 40%" class="form-control input-sm" placeholder="Discount Tetap"')."</div><!-- /.input group -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
     
    print $html;
    die;
  }
  function copy_add_row_product_tour(){
     $angka = rand(11111,99999);
     $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));     
    $id_product_tour_information = $_POST['id_tour_information'];
  
     $status_nb = array(1 => "Persen (%)",
                        2 => "Nominal");
     //$inf = $this->global_models->get("product_tour_information", array("id_product_tour_information" => $id_product_tour_information));
   
     $inf = $this->global_models->get_query("SELECT A.id_product_tour_information,A.id_product_tour, A.start_date, A.end_date,A.start_time,A.end_time,A.available_seat,A.adult_triple_twin,A.child_twin_bed,A.child_extra_bed,A.child_no_bed,A.sgl_supp,A.airport_tax,A.kode,A.dp,A.discount_tetap,A.discount_tambahan,"
        . "id_currency,stnb_dp,stnb_discount_tetap,stnb_discount_tambahan,A.flt,A.in,A.out,A.kode_ps,A.visa"
        . " FROM product_tour_information AS A"
        . " WHERE A.id_product_tour_information = '{$id_product_tour_information}'");
     
     
     $etd = date("H:i", strtotime($inf[0]->start_time)); 
    $eta = date("H:i", strtotime($inf[0]->end_time));
    $html = " 
     <link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />
      <script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>
     <script type='text/javascript'>
      function addDays(myDate,days) {
      var month = new Array();
        month[0] = '01';
        month[1] = '02';
        month[2] = '03';
        month[3] = '04';
        month[4] = '05';
        month[5] = '06';
        month[6] = '07';
        month[7] = '08';
        month[8] = '09';
        month[9] = '10';
        month[10] = '11';
        month[11] = '12';
      
       var hari = new Array();
        hari[1] = '01';
        hari[2] = '02';
        hari[3] = '03';
        hari[4] = '04';
        hari[5] = '05';
        hari[6] = '06';
        hari[7] = '07';
        hari[8] = '08';
        hari[9] = '09';
        hari[10] = '10';
        hari[11] = '11';
        hari[12] = '12';
        var day2 = (days - 1);
        var myDate = new Date(myDate.getTime() + day2*24*60*60*1000);
        var hari2 = 0;
        if(myDate.getDate() > 10){
        hari2 =  myDate.getDate();
        }else{
        hari2 = hari[myDate.getDate()];
        }
        return myDate.getFullYear() + '-' + month[myDate.getMonth()] + '-' +  hari2;
        }
        function changedate(){
        var today_date = $('#start_date3_$angka').val();
        var myDate = new Date(today_date);
        var totday = $('#tot_days').val()* 1;
        var newDate = addDays(myDate,totday);
        $('#end_date3_$angka').val(newDate);
        }     
      $(function() {
               
              $( '#start_date3_$angka' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
              $( '#start_time2_$angka' ).timepicker();
            $( '#end_time2_$angka' ).timepicker();
              $( '#end_date3_$angka' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
            });
        </script>" 
     ."<div class='row'>
                        <div class='col-md-4'>
                            <div class='box box-primary'>
                                <div class='box-body'>
                                     <div class='form-group'>
                                        <label>Kode PS</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('kode_ps[]', $inf[0]->kode_ps,  "  class='form-control input-sm' placeholder='Kode Tour Information'")."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Start Date</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('start_date[]', $inf[0]->start_date,  " onchange='changedate()' id='start_date3_$angka' class='start_date'  class='form-control input-sm' placeholder='Start Date'")."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>ETD</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('etd[]', $etd,  " id='start_time2_$angka' class='start_time'  class='form-control input-sm' placeholder='ETD'")."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>End Date</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input("end_date[]", $inf[0]->end_date," id='end_date3_$angka' class='end_date'  class='form-control input-sm' placeholder='End Date'")."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>ETA</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('eta[]', $eta,  " id='end_time2_$angka' class='start_time'  class='form-control input-sm' placeholder='ETA'")."</div>
                                    </div>
                                    
                                     <div class='form-group'>
                                        <label>Available Seat</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input("available_seat[]",$inf[0]->available_seat, 'class="form-control input-sm"  placeholder="Available Seat"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Currency</label>
                                        <div class='input-group'>".$this->form_eksternal->form_dropdown('id_currency[]', $dropdown, array($inf[0]->id_currency), 'class="form-control" placeholder="Currency"')."</div><!-- /.input group -->
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='box box-primary'>
                                <div class='box-body'>
                                 <div class='form-group'>
                                        <label>FLT</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('flt[]', $inf[0]->flt, 'class="form-control input-sm"  placeholder="FLT"')."</div><!-- /.input group -->
                                    </div>
                                    <div class='form-group'>
                                        <label>IN</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('in[]', $inf[0]->in, 'class="form-control input-sm"  placeholder="IN"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>OUT</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('out[]', $inf[0]->out, 'class="form-control input-sm"  placeholder="OUT"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Adult Triple/Twin</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('adult_triple_twin[]', $inf[0]->adult_triple_twin, ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Adult Triple/Twin"')."</div><!-- /.input group -->
                                    </div>
                                    <div class='form-group'>
                                        <label>Child Twin Bed</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('child_twin_bed[]', $inf[0]->child_twin_bed, ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child Twin Bed"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Child Extra Bed</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('child_extra_bed[]', $inf[0]->child_extra_bed, ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child Extra Bed"')."</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class='col-md-4'>
                    <div class='box box-primary'>
                                <div class='box-body'>
                                <div class='form-group'>
                                        <label>Child No Bed</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('child_no_bed[]', $inf[0]->child_no_bed, ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Child No Bed"')."</div>
                                    </div>
                                <div class='form-group'>
                                        <label>SGL SUPP</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('sgl_supp[]', $inf[0]->sgl_supp, ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="SGL SUPP"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Airport Tax & Flight Insurance</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('airport_tax[]', $inf[0]->airport_tax, ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Airport Tax & Flight Insurance"')."</div>
                                    </div>
                                     <div class='form-group'>
                                        <label>Harga Visa</label>
                                        <div class='input-group'>".$this->form_eksternal->form_input('visa[]', $inf[0]->visa, ' onkeyup="FormatCurrency(this)" class="form-control input-sm"  placeholder="Harga Visa"')."</div>
                                    </div>
                                    <div class='form-group'>
                                        <label>Discount Tetap</label>
                                        <div class='input-group'>".$this->form_eksternal->form_dropdown('stnb_discount_tetap[]', $status_nb, $inf[0]->stnb_discount_tetap, 'class="form-control" style="width:60%"').$this->form_eksternal->form_input('discount_tetap[]', $inf[0]->discount_tetap, 'style="width: 40%" class="form-control input-sm" placeholder="Discount Tetap"')."</div><!-- /.input group -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
     
    print $html;
    die;
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
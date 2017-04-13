<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_status_tour extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
    $this->load->model('inventory/mgroup_status_tour');
  } 

  function index(){
   
    $pst = $this->input->post(NULL);
    if($pst){
    
        $newdata = array(
            'group_tour_status_title'       => $pst['title'],
            'group_tour_status_start_date'     => $pst['start_date'],
            'group_tour_status_end_date'    => $pst['end_date'],
            'group_tour_status_region'      => $pst['region']
          );
          $this->session->set_userdata($newdata);
    }
    
	 if($pst['dt_search'] == "Search"){
      
      $newdata = array(
             'group_tour_status_page'       => 0,
          );
          $this->session->set_userdata($newdata);
    }
    
   $category = array(0 =>"Pilih", 1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
      $sub_category = array(0 =>"Pilih", 1 => "Eropa", 2 => "Africa", 3 => "America", 4 => "Australia", 5 => "Asia", 6 => "China", 7 => "New Zealand");
     
    $where = "";
    $where_information = "";
//    if($pst){
     if($this->session->userdata('group_tour_status_title')){
      $where .= " AND LOWER(A.title) LIKE '%".strtolower($this->session->userdata('group_tour_status_title'))."%'"; 
    }
    
    if($this->session->userdata('group_tour_status_region') > 0){
      $where .= " AND A.sub_category ='{$this->session->userdata('group_tour_status_region')}'";
    }
    

      $st_date = date("Y-m-01");
      $en_date = date("Y-m-t");
      if($this->session->userdata('group_tour_status_start_date')){
        if($this->session->userdata('group_tour_status_end_date') ==""){
          $end_date = date("Y-m-d");
        }
        else{
          $end_date = $this->session->userdata('group_tour_status_end_date');
        }
         $where .= " AND (B.start_date BETWEEN '{$this->session->userdata('group_tour_status_start_date')}' AND '{$end_date}')";
       // $where .= " AND B.start_date >= '".date("Y-m-d")."' AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$end_date}')";
        $where_information .= " AND (B.start_date BETWEEN '{$this->session->userdata('group_tour_status_start_date')}' AND '{$end_date}')";
      }
     
//    }
    if($pst['export']){
//      print ($where);
//      echo "<br>aa"; die;
      $this->mgroup_status_tour->export_xls("Group-Status-AntaVaya",$where,$where_information);
    }

    
      $list = $this->global_models->get_query("SELECT count(A.id_product_tour) AS total"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.status=1 AND B.tampil=1 AND id_store_region = '{$this->session->userdata("store_region")}' {$where}"
          );
//    $qr = $this->db->last_query();
//    print $qr; die;
     $jumlah_list = $list[0]->total;
     $url_list = site_url("inventory/group-status-tour/ajax-group-status-tour/".$jumlah_list);
    $url_list_halaman = site_url("inventory/group-status-tour/ajax-halaman-group-status-tour/".$jumlah_list);
    
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    $foot = "
        <link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>
       
        <script type='text/javascript'>
             $(document).ready(function () { 
           
            
           $('#bb').click( function()
           {
             $('#loading-tour').show();
           }
        );                      
            
             $( '#start_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
              
              $( '#end_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });          

            })
        </script>";
    
     $foot .= "<script type='text/javascript'>"

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
            ."get_list({$this->session->userdata('group_tour_status_page')});"
      . "</script> ";
    
    
     $before_table = "<div>"
      . "{$this->form_eksternal->form_open("", 'role="form"')}"
        . "<div class='box-body col-sm-12' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Name Tour</label><br>"
            . "{$this->form_eksternal->form_input('title', $this->session->userdata('group_tour_status_title'), ' class="form-control input-sm" placeholder="Title"')}"
          . "</div>"
        . "</div>"
        . "</div>"
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Tanggal Keberangkatan</label>"
            . "{$this->form_eksternal->form_input('start_date', $this->session->userdata('group_tour_status_start_date'), 'id="start_date" class="form-control input-sm" placeholder="Date"')}"
          . "</div>"
          . "<div class='control-group'>"
            . "<label>Region</label>"
            . "{$this->form_eksternal->form_dropdown('region', $sub_category, $this->session->userdata('group_tour_status_region'), 'class="form-control" placeholder="Kategori 2"')}"
          . "</div>"
        . "</div>"              
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Tanggal Tiba</label>"
            . "{$this->form_eksternal->form_input('end_date', $this->session->userdata('group_tour_status_end_date'), 'id="end_date" class="form-control input-sm" placeholder="Date"')}"
          . "</div>"
          . "<div class='control-group'>"
//            . "<label>Region</label>"
//            . "{$this->form_eksternal->form_dropdown('kategori2', $sub_category, $pst['kategori2'], 'class="form-control" placeholder="Kategori 2"')}"
          . "</div>"
        . "</div>"  
        . "<div class='box-body col-sm-6' style='padding-left:1%;padding-top: 10%;'>"
          . "<div class='control-group'>"
              . "<input type='submit' name='export' value='Export Excel' class='btn btn-primary' type='submit'></input> "
           
          . "</div>"
        . "</div>"
              . "<div class='box-body col-sm-6' style='padding-left:5%;margin-top: -5%;'>"
          . "<div class='control-group'>"
            // . "<button id='bb' class='btn btn-primary' type='submit'>Search</button>"
			. "<input type='submit' id='bb' name='dt_search' value='Search' class='btn btn-primary' type='submit'></input> "
           
          . "</div>"
        . "</div>"
      . "</form>"
    . "</div>";
    
    $this->template->build('group-status-tour/main-group-status-tour', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/group-status-tour",
            'data'        => $tour,
            'foot'        => $foot,
            'css'         => $css,
            'title'       => lang("antavaya_group_tour"),
            'before_table'    => $before_table,
            'dropdown'     => $dropdown,
          //  'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('group-status-tour/main-group-status-tour');
  }
  function ajax_group_status_tour($total = 0, $start = 0){
    

    
    $where = "";
    $where_information = "";
//    if($pst){
     if($this->session->userdata('group_tour_status_title')){
      $where .= " AND LOWER(A.title) LIKE '%".strtolower($this->session->userdata('group_tour_status_title'))."%'"; 
    }
    
    if($this->session->userdata('group_tour_status_region') > 0){
      $where .= " AND A.sub_category ='{$this->session->userdata('group_tour_status_region')}'";
    }
    

      $st_date = date("Y-m-01");
      $en_date = date("Y-m-t");
      if($this->session->userdata('group_tour_status_start_date')){
        if($this->session->userdata('group_tour_status_end_date') ==""){
          $end_date = date("Y-m-d");
        }
        else{
          $end_date = $this->session->userdata('group_tour_status_end_date');
        }
         $where .= " AND (B.start_date BETWEEN '{$this->session->userdata('group_tour_status_start_date')}' AND '{$end_date}')";
       // $where .= " AND B.start_date >= '".date("Y-m-d")."' AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$end_date}')";
        $where_information .= " AND (B.start_date BETWEEN '{$this->session->userdata('group_tour_status_start_date')}' AND '{$end_date}')";
      }
      
      
      $category = array(0 =>"Pilih", 1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
      $sub_category = array(0 =>"Pilih", 1 => "Eropa", 2 => "Africa", 3 => "America", 4 => "Australia", 5 => "Asia");
    
    $items = $this->global_models->get_query("SELECT A.kode,A.title,A.days,A.destination,A.landmark,"
        . " A.category,A.sub_category,A.id_product_tour,B.id_product_tour_information,"
        . " B.kode AS kode_information,B.start_date,B.end_date,B.id_currency,"
        . " B.available_seat,B.adult_triple_twin,B.child_twin_bed,B.child_extra_bed,B.child_no_bed,B.sgl_supp,B.airport_tax,B.status"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.status=1 AND B.tampil=1 AND id_store_region = '{$this->session->userdata("store_region")}' {$where}"
        . " ORDER BY B.start_date ASC"
          . " LIMIT {$start}, 10");
      if($items){
        $status = array(
          1 => "<span class='label label-info'>Active</span>",
          2 => "<span class='label label-warning'>Draft</span>",
          3 => "<span class='label label-danger'>Cancel</span>",
          4 => "<span class='label label-success'>Close</span>",
        );
      
        foreach($items AS $it){
          $action = "<li><a href='".site_url("inventory/group-status-tour/close-tour/{$it->id_product_tour_information}")."'>Close Tour</a></li>"
            . "<li><a href='".site_url("inventory/group-status-tour/cancel-tour/{$it->id_product_tour_information}")."'>Cancel Tour</a></li>"
            . "<li><a href='".site_url("inventory/tour-book/schedule/{$it->id_product_tour_information}")."'>Book List</a></li>";
            
          if($it->status == 3){
            $action = "<li><a href='".site_url("inventory/tour-book/schedule/{$it->id_product_tour_information}")."'>Book List</a></li>";
          }
          if($it->status == 4){
            $action = "<li><a href='".site_url("inventory/group-status-tour/room-list/{$it->id_product_tour_information}")."'>Room List</a></li>"
              . "<li><a href='".site_url("inventory/group-status-tour/room-list/{$it->id_product_tour_information}")."'>Passport List</a></li>";
          }
//          $info = $this->global_models->get_query("SELECT B.*"
//            . " FROM product_tour_information AS B"
//            . " WHERE B.id_product_tour = '{$it->id_product_tour}' $where_information ");
//          $information = array();
          $available_seat = array();
          $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, "");     
   
//          foreach($info AS $fo){
           /* $book = $this->global_models->get_query("SELECT SUM(adult_triple_twin) AS a, SUM(child_twin_bed) AS c, SUM(child_extra_bed) AS d,SUM(child_no_bed) AS e,SUM(sgl_supp) AS f"
              . " FROM product_tour_book"
              . " WHERE id_product_tour_information = '{$fo->id_product_tour_information}'"
              . " AND (status = 2 OR status = 3)"); */
              $book = $this->global_models->get_query("SELECT count(A.kode) AS aid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 2 OR A.status = 3)");
              
              $totl_book = $this->global_models->get_query("SELECT count(A.kode) AS id"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 1)");
              
              $totl_commit  = $this->global_models->get_query("SELECT count(A.kode) AS cid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 2)");
              
              $totl_lunas  = $this->global_models->get_query("SELECT count(A.kode) AS lid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 3)");
        $avail_seat = ($it->available_seat - ($book[0]->aid));
        $start_date = "";
        $etd = date("H:i", strtotime($it->start_time));
       $eta = date("H:i", strtotime($it->end_time));
        $start_date .= "<a href='javascript:void(0)' id='{$it->kode_information}'>"
          . date("d M y", strtotime($it->start_date))." - ".date("d M y", strtotime($it->end_date))."</a>"
          . "<div style='display: none' id='isi{$it->kode_information}'>"
            . "<table width='100%'>"
             . "<tr>"
                . "<td>Departure</td>"
                . "<td style='text-align: left'>".date("d M y", strtotime($it->start_date))."</td>"
              . "</tr>"
            . "<tr>"
                . "<td>Estimate Time Departure</td>"
                . "<td style='text-align: left'>".$etd."</td>"
              . "</tr>"
              . "<tr>"
                . "<td>Arrival</td>"
                . "<td style='text-align: left'>".date("d M y", strtotime($it->end_date))."</td>"
              . "</tr>"
            . "<tr>"
                . "<td>Estimate Time Arrival</td>"
                . "<td style='text-align: left'>".$eta."</td>"
              . "</tr>"
              . "<tr>"
                . "<td>Adult Triple/Twin</td>"
                . "<td style='text-align: left'>".$dropdown[$it->id_currency]." ".number_format($it->adult_triple_twin)."</td>"
              . "</tr>"
              . "<tr>"
                . "<td>Child Twin Bed</td>"
                . "<td style='text-align: left'>".$dropdown[$it->id_currency]." ".number_format($it->child_twin_bed)."</td>"
              . "</tr>"
              . "<tr>"
                . "<td>Child Extra Bed</td>"
                . "<td style='text-align: left'>".$dropdown[$it->id_currency]." ".number_format($it->child_extra_bed)."</td>"
              . "</tr>"
              . "<tr>"
                . "<td>Child No Bed</td>"
                . "<td style='text-align: left'>".$dropdown[$it->id_currency]." ".number_format($it->child_no_bed)."</td>"
              . "</tr>"
              . "<tr>"
                . "<td>SGL SUPP</td>"
                . "<td style='text-align: left'>".$dropdown[$it->id_currency]." ".number_format($it->sgl_supp)."</td>"
              . "</tr>"
              . "<tr>"
                . "<td>Airport Tax & Flight Insurance</td>"
                . "<td style='text-align: left'>".$dropdown[$it->id_currency]." ".number_format($it->airport_tax)."</td>"
              . "</tr>"
//              . "<tr>"
//                . "<td>Available Seat</td>"
//                . "<td style='text-align: left'>".number_format($info['available_seat'])."</td>"
//              . "</tr>"
            . "<tr>"
             //   . "<td colspan='2'><center><a href='".site_url("inventory/product-tour/add-additional/".$info['id_product_tour_information'])."' class='btn btn-primary'>Additional</a></center></td>"
                . "<td></td>"
              . "</tr>"
              .""
            . "</table>"
          . "</div>"
          . "<script>"
            . "$(function() {"
              . "$('#{$it->kode_information}').tooltipster({"
                . "content: $('#isi{$it->kode_information}').html(),"
                . "minWidth: 300,"
                . "maxWidth: 300,"
                . "contentAsHTML: true,"
                . "interactive: true"
              . "});"
            . "});"
          . "</script>"
          . "<br />";
                
          $tampil .= "<tr>"
        . "<td>{$it->title}</td>"
        . "<td>{$start_date}</td>"
        . "<td>{$it->days}</td>"
        . "<td>{$it->available_seat}</td>"
        . "<td>{$totl_book[0]->id}</td>"
        . "<td>{$totl_commit[0]->cid}</td>"
        . "<td>{$totl_lunas[0]->lid}</td>"
        . "<td>{$avail_seat}</td>"
        . "<td>{$sub_category[$it->sub_category]} <br />"
        . "{$status[$it->status]}"
        . "</td>"
        . "<td>"
          . "<div class='btn-group'>"
            . "<button data-toggle='dropdown' class='btn btn-small dropdown-toggle'>Action<span class='caret'></span></button>"
            . "<ul class='dropdown-menu'>"
              . "{$action}"
            . "</ul>"
          . "</div>"
        . "</td>"
      . "</tr>";
        }
        
      }
    
   
  print $tampil;
    die;
  }
  
  function ajax_halaman_group_status_tour($total = 0, $start = 0){
    
	$newdata = array(
            'group_tour_status_page'       => $start,
          );
          $this->session->set_userdata($newdata);
		  
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
  
  function report_payment(){
   
      $serach_data = $this->input->post(NULL);

      $stt = array("Draft" => 1, "Confirm" => 2, "Not Paid" => 3);   
      $where = "";
       if($serach_data["code"]){
          $where .= " AND LOWER(A.kode) LIKE '%".strtolower($serach_data['code'])."%' OR LOWER(A.kode) LIKE '%".strtolower($serach_data['code'])."%'"; 
      }
      $st_date = date("Y-m-01");
      $en_date = date("Y-m-t");
      if($serach_data['start_date'] || $serach_data['$end_date']){
        $where .= " AND (A.tanggal BETWEEN '{$serach_data['start_date']} 00:00:00' AND '{$serach_data['end_date']} 23:59:59')";
      }else{
        $serach_data['start_date'] = $st_date;
        $serach_data['end_date'] = $en_date;
        $where .= " AND (A.tanggal BETWEEN '{$st_date} 00:00:00' AND '{$en_date} 23:59:59')";
      }
     
      if($serach_data['status']){
        $where .= " AND LOWER(B.status) LIKE '%".strtolower($serach_data['status'])."%' OR LOWER(B.status) LIKE '%".strtolower($serach_data['status'])."%'"; 
      }
      
      if($serach_data['payment_type']){
        $where .= " AND LOWER(B.payment) LIKE '%".strtolower($serach_data['payment_type'])."%' OR LOWER(B.payment) LIKE '%".strtolower($serach_data['payment_type'])."%'"; 
      }
      
      $book = $this->global_models->get_query("SELECT A.kode,A.first_name,A.last_name,B.id_product_tour_book, B.id_currency, B.nominal,B.status, B.payment,B.tanggal,C.name AS name_tc,D.name AS name_konfirm"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_book_payment AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " LEFT JOIN users_channel AS C ON B.id_users = C.id_users"
        . " LEFT JOIN users_channel AS D ON B.update_by_users = D.id_users"
        . " WHERE 1=1 AND pos=2 $where"
        . " ORDER BY B.tanggal DESC");
      
      if($book){
        $additional_tour = "";
        foreach($book AS $ky => $bk){
         $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
         $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));     
          
        $name = $bk->first_name." ".$bk->last_name;
        $currency = $dropdown[$bk->id_currency];
        $channel2 = array(
                  1 => "Cash",
                  2 => "BCA",
                  3 => "Mega",
                  4 => "Mandiri",
                  5 => "CC");
        $status2 = array(1 => "Draft", 2 => "Confirm", 3 => "Not Paid");
        
          $book_detail[] = array(
            "name"            => $name,
            "name_tc"            => $bk->name_tc,
            "name_konfirm"            => $bk->name_konfirm,
            "book_code"       => $bk->kode,
            "currency"        => $currency,
            "tanggal"         => $bk->tanggal,
            "status"            => $bk->status,
            "nominal"            => $bk->nominal,
            "payment_type"        => $bk->payment,
           // 'currency_rate' => $dropdown_rate[1],
          //  "passenger" => $passenger,
          );
        }
	  }
    if($serach_data['export']){
      $this->load->model('inventory/mgroup_status_tour');
      $this->mgroup_status_tour->export_report_payment_xls("Report-Payment",$book_detail);
    }

    $category = array(0 => "Pilih",1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
    $sub_category = array(0 => "Pilih",1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia & New Zealand", 5 => "Asia", 6 => "China");
    
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    $foot = "
        <link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>
       
        <script type='text/javascript'>
             $(document).ready(function () { 
           
            
           $('#bb').click( function()
           {
             $('#loading-tour').show();
           }
        );
            
             $( '#start_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
              
              $( '#end_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });          

            })
        </script>";
    
   // print_r($serach_data); die;
   
        $status = array("0"	=> "All",
                        "1" => "Draft", 
                        "2" => "Confirm");
         $channel = array("0" => "All",
                  1 => "Cash",
                  2 => "BCA",
                  3 => "Mega",
                  4 => "Mandiri",
                  5 => "CC");
    
    $before_table = "<div>"
      . "{$this->form_eksternal->form_open("", 'role="form"')}"
        . "<div class='box-body col-sm-12' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Code</label><br>"
            . "{$this->form_eksternal->form_input('code', $serach_data['code'], ' class="form-control input-sm" placeholder="Code"')}"
          . "</div>"
        . "</div>"
            
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Start Date</label>"
            . "{$this->form_eksternal->form_input('start_date', $serach_data['start_date'], 'id="start_date" class="form-control input-sm" placeholder="Start Date"')}"
          . "</div>"
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>End Date</label>"
            . "{$this->form_eksternal->form_input('end_date', $serach_data['end_date'], 'id="end_date" class="form-control input-sm" placeholder="End Date"')}"
          . "</div>"
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Status</label>"
            . "{$this->form_eksternal->form_dropdown('status', $status, array($serach_data['status']), 'class="form-control" placeholder="Status"')}"
          . "</div>"
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Payment Type</label>"
            . "{$this->form_eksternal->form_dropdown('payment_type', $channel, array($serach_data['payment_type']), 'class="form-control" placeholder="Payment Type"')}"
          . "</div>"
        . "</div>"      
              
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<button id='bb' class='btn btn-primary' type='submit'>Search</button>"
          . "</div>"
        . "</div>"
               ."<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<input name='export' class='btn btn-primary' value='Export XLS' type='submit'></input>"
          . "</div>"
        . "</div>"
      . "</form>"
    . "</div>";
    
    $this->template->build('group-status-tour/report-payment', 
      array(
          'url'           => base_url()."themes/".DEFAULTTHEMES."/",
          'url_image'     => base_url()."themes/antavaya/",
          'menu'          => "grouptour/product-tour/report-payment",
          'data'          => $book_detail,
          'title'         => lang("report_payment"),
          'category'      => $category,
          'sub_category'  => $sub_category,
          'foot'          => $foot,
          'css'           => $css,
         'tableboxy'      => 'tableboxydesc',
          'serach_data'   => $serach_data,
          'serach'        => $serach,
          'before_table'  => $before_table,
          'channel'       => $channel2,
          'status'        => $status2
        ));
    $this->template
      ->set_layout("tableajax")
      ->build('group-status-tour/report-payment');
  }
  function report_passport_list($id_product_tour_information){
     
    
     $detail = $this->global_models->get("product_tour_leader", array("id_product_tour_information" => $id_product_tour_information));
     $flight_detail = $this->global_models->get("product_tour_flight_detail", array("id_product_tour_information" => $id_product_tour_information));
     
          $info = $this->global_models->get_query("SELECT A.id_product_tour_information,A.id_product_tour,B.title, A.start_date, A.end_date"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.id_product_tour_information = '{$id_product_tour_information}' ORDER BY B.id_product_tour DESC");
     
      
     if($detail[0]->id_product_tour_leader > 0){
      $tour_leader = $detail[0]->first_name." ".$detail[0]->last_name;
    }
     $before_table = "<div>"
      . "{$this->form_eksternal->form_open("", 'role="form"')}"
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Name Tour</label><br>"
            . "{$info[0]->title}<br>".date("d M", strtotime($info[0]->start_date))." - ".date("d M Y", strtotime($info[0]->end_date))
          . "</div>"
        . "</div>"
        . "</div>";
           
        $before_table .= "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Flight Detail</label><br>";
        $no = 1;
          foreach ($flight_detail as $val) {
            $no = $no + 2;
             $before_table .= $val->name."<br>";
          }
          $ank =  $no;
          
          $before_table .= "</div>"
        . "</div>";              
        $before_table .=  "<div class='box-body col-sm-6' style='padding-left:2%;'>"
          . "<div class='control-group'>"
            . "<label>Tour Leader</label><br>"
            . "{$tour_leader}"
          . "</div>"
        . "</div>";
        $before_table .= "<div class='box-body col-sm-12' style='padding-left:1%;'>"
          . "<div class='control-group'>"
              . "<br><br><input type='submit' name='export' value='Export Excel' class='btn btn-primary' type='submit'></input> "
           
          . "</div><br><br>"
        . "</div>"
      . "</form>"
    . "</div>";
            
         
     if($detail[0]->id_product_tour_leader){
       $add = '<li><a href="'.site_url("inventory/group-status-tour/add-tour-leader/".$id_product_tour_information."/".$detail[0]->id_product_tour_leader).'"><i class="icon-plus"></i>Edit Tour Leader</a></li>';
     }else{
       $add = '<li><a href="'.site_url("inventory/group-status-tour/add-tour-leader/".$id_product_tour_information).'"><i class="icon-plus"></i>Add Tour Leader</a></li>';
     }
     $flight = '<li><a href="'.site_url("inventory/group-status-tour/flight-detail/".$id_product_tour_information).'"><i class="icon-plus"></i>Flight Detail</a></li>';
     $room_list = '<li><a href="'.site_url("inventory/group-status-tour/room-list/".$id_product_tour_information).'"><i class="icon-plus"></i>Room List</a></li>';
      $menutable = $add.$flight.$room_list;
            
    $cust = $this->global_models->get_query("SELECT A.id_product_tour_book,B.id_product_tour_customer,A.address,B.first_name,B.last_name,B.passport,"
        . " B.place_of_issued,B.date_of_issued,B.date_of_expired,B.tanggal_lahir,B.tempat_tanggal_lahir,B.telphone"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_customer AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " WHERE A.id_product_tour_information = '$id_product_tour_information'");
//    $ck = $this->db->last_query();
    
      $serach_data = $this->input->post(NULL);
     if($serach_data['export']){
      $this->load->model('inventory/mgroup_status_tour');
      $this->mgroup_status_tour->export_report_passport_xls("Report-Passport-List-".$info[0]->title."-".date("d M", strtotime($info[0]->start_date))." - ".date("d M Y", strtotime($info[0]->end_date)),$id_product_tour_information);
    }
    
    $this->template->build('group-status-tour/report-passport-list', 
      array(
          'url'           => base_url()."themes/".DEFAULTTHEMES."/",
          'url_image'     => base_url()."themes/antavaya/",
          'menu'          => "inventory/group-status-tour",
          'data'          => $cust,
          'before_table'    => $before_table,
          'menutable'   => $menutable,
          'title'         => lang("passport_list"),
          'foot'          => $foot,
          'css'           => $css,
         'tableboxy'      => 'tableboxydesc',
          'status'        => $status2
        ));
    $this->template
      ->set_layout("datatables")
      ->build('group-status-tour/report-passport-list');
  }
  
  function room_list($id_product_tour_information){
     
    
     $detail = $this->global_models->get("product_tour_leader", array("id_product_tour_information" => $id_product_tour_information));
     $flight_detail = $this->global_models->get("product_tour_flight_detail", array("id_product_tour_information" => $id_product_tour_information));
     
          $info = $this->global_models->get_query("SELECT A.id_product_tour_information,A.id_product_tour,B.title, A.start_date, A.end_date"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.id_product_tour_information = '{$id_product_tour_information}' ORDER BY B.id_product_tour DESC");
     
      
     if($detail[0]->id_product_tour_leader > 0){
      $tour_leader = $detail[0]->first_name." ".$detail[0]->last_name;
    }
     $before_table = "<div>"
      . "{$this->form_eksternal->form_open("", 'role="form"')}"
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Name Tour</label><br>"
            . "{$info[0]->title}<br>".date("d M", strtotime($info[0]->start_date))." - ".date("d M Y", strtotime($info[0]->end_date))
          . "</div>"
        . "</div>"
        . "</div>";
           
        $before_table .= "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Flight Detail</label><br>";
        $no = 2;
          foreach ($flight_detail as $val) {
            $no = $no + 2;
             $before_table .= $val->name."<br>";
          }
          $ank =  $no;
         
          $before_table .= "</div>"
        . "</div>";              
        $before_table .=  "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Tour Leader</label><br>"
            . "{$tour_leader}"
          . "</div>"
        . "</div>";
        $before_table .= "<div class='box-body col-sm-12' style='padding-left:1%;'>"
          . "<div class='control-group'>"
              . "<br><br><input type='submit' name='export' value='Export Excel' class='btn btn-primary' type='submit'></input> "
           
          . "</div><br><br>"
        . "</div>"
      . "</form>"
    . "</div>";
            
         
     if($detail[0]->id_product_tour_leader){
       $add = '<li><a href="'.site_url("inventory/group-status-tour/add-tour-leader/".$id_product_tour_information."/".$detail[0]->id_product_tour_leader).'"><i class="icon-plus"></i>Edit Tour Leader</a></li>';
     }else{
       $add = '<li><a href="'.site_url("inventory/group-status-tour/add-tour-leader/".$id_product_tour_information).'"><i class="icon-plus"></i>Add Tour Leader</a></li>';
     }
     $flight = '<li><a href="'.site_url("inventory/group-status-tour/flight-detail/".$id_product_tour_information).'"><i class="icon-plus"></i>Flight Detail</a></li>';
     $room_list = '<li><a href="'.site_url("inventory/group-status-tour/report-passport-list/".$id_product_tour_information).'"><i class="icon-plus"></i>Report Passport List</a></li>';
      $menutable = $add.$flight.$room_list;
            
    $cust = $this->global_models->get_query("SELECT A.id_product_tour_book,A.room AS jml_room,B.id_product_tour_customer,"
        . " GROUP_CONCAT(CONCAT(B.first_name,' ',B.last_name)) AS name,"
        . " GROUP_CONCAT(B.passport) AS no_passport,GROUP_CONCAT(B.date_of_issued) AS date_of_issued,GROUP_CONCAT(B.date_of_expired) AS date_of_expired,"
        . " GROUP_CONCAT(B.tanggal_lahir) AS tanggal_lahir,GROUP_CONCAT(B.room) AS room"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_customer AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " WHERE A.id_product_tour_information = '$id_product_tour_information'"
        . " GROUP BY A.id_product_tour_book ORDER BY B.id_product_tour_customer ASC");
//    $ck = $this->db->last_query();
    
      $serach_data = $this->input->post(NULL);
     if($serach_data['export']){
      $this->load->model('inventory/mgroup_status_tour');
      $this->mgroup_status_tour->export_room_list_xls1("Report-Room-List-".$info[0]->title."-".date("d M", strtotime($info[0]->start_date))." - ".date("d M Y", strtotime($info[0]->end_date)),$id_product_tour_information);
    }
    
    $this->template->build('group-status-tour/room-list', 
      array(
          'url'           => base_url()."themes/".DEFAULTTHEMES."/",
          'url_image'     => base_url()."themes/antavaya/",
          'menu'          => "inventory/group-status-tour",
          'data'          => $cust,
          'before_table'    => $before_table,
          'menutable'   => $menutable,
          'title'         => lang("Room List"),
         'tableboxy'      => 'tableboxydesc',
          'status'        => $status2,
//          'query'         => $ck
        ));
    $this->template
      ->set_layout("datatables")
      ->build('group-status-tour/room-list');
  }
  
  public function add_tour_leader($id_product_tour_info = 0,$id_product_tour_leader = 0){
      
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("product_tour_leader", array("id_product_tour_information" => $id_product_tour_info));
       $foot = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />";
    
    $foot .= "<script type='text/javascript'>"
      
      . "$( '.adult_date' ).datepicker({"
            . "changeMonth: true,"
            . "changeYear: true,"
            . "yearRange : '-75:-13',"
            . "dateFormat: 'yy-mm-dd',"
          . "});"
     
          . "$( '.child_date' ).datepicker({"
            . "changeMonth: true,"
            . "changeYear: true,"
            . "yearRange : '-12:+0',"
            . "dateFormat: 'yy-mm-dd',"
          . "});"
       
        . "$( '.passport' ).datepicker({"
            . "changeMonth: true,"
            . "changeYear: true,"
            . "yearRange : '-5:+7',"
            . "dateFormat: 'yy-mm-dd',"
          . "});"
          . "$( '.infant_date' ).datepicker({"
            . "changeMonth: true,"
            . "changeYear: true,"
            . "yearRange : '-2:+0',"
            . "dateFormat: 'yy-mm-dd',"
          . "});"
      . "</script>";
    
      $this->template->build("group-status-tour/add-tour-leader", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/product-tour',
              'title'       => lang("Tour Leader"),
              'detail'      => $detail,
              'id_tour_info'  => $id_product_tour_info,
              'breadcrumb'  => array(
                    "Passport-List"  => "inventory/group-status-tour/report-passport-list/".$id_product_tour_info,
                ),
             // 'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("group-status-tour/add-tour-leader");
    }
    else{
      $pst = $this->input->post(NULL);
//      print_r($pst); die;
      
      if($pst['id_detail']){
        $kirim = array(
            "first_name"                 => $pst['first_name'],
            "last_name"                   => $pst['last_name'],
            "telphone"                    => $pst['telp'],
            "tempat_tanggal_lahir"          => $pst['place_birth'],
            "tanggal_lahir"                 => $pst['date'],
            "passport"                       => $pst['passport'],
            "place_of_issued"                => $pst['place_issued'],
            "date_of_issued"                => $pst['date_issued'],
            "date_of_expired"                 => $pst['date_expired'],
            "address"                      => $pst['address'],
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_product_tour_master_disc = $this->global_models->update("product_tour_leader", array("id_product_tour_leader" => $pst['id_detail']),$kirim);
      
      }
      else{
        $kirim = array(
             "first_name"                 => $pst['first_name'],
            "last_name"                   => $pst['last_name'],
            "telphone"                    => $pst['telp'],
            "tempat_tanggal_lahir"          => $pst['place_birth'],
            "tanggal_lahir"                 => $pst['date'],
            "passport"                       => $pst['passport'],
            "place_of_issued"                => $pst['place_issued'],
            "date_of_issued"                => $pst['date_issued'],
              "date_of_expired"                 => $pst['date_expired'],
              "address"                      => $pst['address'],
              "id_product_tour_information"     => $pst['id_tour_info'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        $id_product_tour_leader = $this->global_models->insert("product_tour_leader", $kirim);
        
        
      }
      if($id_product_tour_leader){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/group-status-tour/report-passport-list/".$id_product_tour_info);
    }
  }
  public function flight_detail($id_product_tour_infomation = 0){
      
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("product_tour_flight_detail", array("id_product_tour_information" => $id_product_tour_infomation));
   
     
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    $foot = "
        <link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>
       ";
    $foot .= "<script>"
     ."function tambah_items_flight_detail(){"
      ."var num = $('.number_additional').length;"
      ."var dataString2 = 'name='+ num;"
      ."$.ajax({"
      ."type : 'POST',"
      ."url : '".site_url("inventory/group-status-tour/ajax-add-row-flight-detail")."',"
      ."data: dataString2,"
      ."dataType : 'html',"
      ."success: function(data) {"
            ."$('#tambah-additional').append(data);"
      ."},"
    ."});"
        ."}"
      ."</script>";
    $this->template->build("group-status-tour/flight-detail", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/product-tour',
              'title'       => lang("Add Flight Detail"),
              'detail'      => $detail,
              'id_product_tour_info' => $id_product_tour_infomation,
              'breadcrumb'  => array(
                 "passport_list "  => "inventory/group-status-tour/report-passport-list/".$id_product_tour_infomation,
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("group-status-tour/flight-detail");
    }
    else{
      $pst = $this->input->post(NULL);
     
//      print "<pre>";
//      print_r($pst);
//      print "</pre>"; die;
     if($pst['id_product_tour_flight_detail']){
      foreach($pst['name'] AS $key_na => $val_na){
      if($val_na){
        $kirim_info = array(
            "id_product_tour_flight_detail"    => $pst['id_product_tour_flight_detail'][$key_na],
            "id_product_tour_information"     => $pst['id_product_tour_info'],
            "name"                            => $pst['name'][$key_na],
            "create_by_users"                   => $this->session->userdata("id"),
              "create_date"                       => date("Y-m-d H:i:s")
            );
            
            $else_kirim_info = array(
             "id_product_tour_flight_detail"        => $pst['id_product_tour_flight_detail'][$key_na],
            "id_product_tour_information"           => $pst['id_product_tour_info'],
            "name"                                  => $pst['name'][$key_na],
            "update_by_users"                       => $this->session->userdata("id"),
            );
         $this->global_models->update_duplicate("product_tour_flight_detail", $kirim_info, $else_kirim_info);
      
         }
    }
     $this->session->set_flashdata('success', 'Data tersimpan');
      redirect("inventory/group-status-tour/report-passport-list/".$id_product_tour_infomation);
      
     }else{
       foreach($pst['name'] AS $key_na => $val_na){
      if($val_na){
        $kirim[] = array(
          "id_product_tour_information"           => $pst['id_product_tour_info'],
            "name"                                  => $pst['name'][$key_na],
          "create_by_users"                     => $this->session->userdata("id"),
          "create_date"                         => date("Y-m-d H:i:s")
        );
      }
    }
    $id_product = $this->global_models->insert_batch("product_tour_flight_detail", $kirim);
    if($id_product){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/group-status-tour/report-passport-list/".$id_product_tour_infomation);
     }
   
      
    }
  }
  function ajax_add_row_flight_detail(){
     $pos = $_POST['name'];
     $pos++;
     
    $html = "<div class='box-body col-sm-9'>
      <div class='control-group'>
        <label>Flight Detail</label>";
    $html .= $this->form_eksternal->form_input('name[]', "", ' class="form-control input-sm" placeholder="Flight Detail"');
    $html .= "</div></div>"
    ."<br><br><br><br>";
    
    print $html;
    die;
  }
  
  function delete_row_flight_detail($id_info,$id){
   $detail = $this->global_models->get("product_tour_flight_detail", array("id_product_tour_flight_detail" => $id));
     if($detail[0]->id_product_tour_flight_detail > 0){
       $this->global_models->delete("product_tour_flight_detail", array("id_product_tour_flight_detail" => $id));
    $this->session->set_flashdata('success', 'Data terhapus');
    redirect("inventory/group-status-tour/flight-detail/{$id_info}");
     }
    
  }
  
  function close_tour($id_product_tour_information){
    if($this->global_models->update("product_tour_information", array("id_product_tour_information" => $id_product_tour_information), array("status" => 4))){
      $this->session->set_flashdata('success', 'Tour Close');
    }
    else {
      $this->session->set_flashdata('notice', 'Fail, Try Again');
    }
    redirect("inventory/group-status-tour");
  }
  
  function cancel_tour($id_product_tour_information){
    if($this->global_models->update("product_tour_information", array("id_product_tour_information" => $id_product_tour_information), array("status" => 3))){
      $this->session->set_flashdata('success', 'Tour Canceled');
    }
    else {
      $this->session->set_flashdata('notice', 'Fail, Try Again');
    }
    redirect("inventory/group-status-tour");
  }
 
}


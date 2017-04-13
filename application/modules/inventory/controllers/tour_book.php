<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tour_book extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function delete_product_tour($id_product_tour){
    $this->global_models->delete("product_tour", array("id_product_tour" => $id_product_tour));
    $this->global_models->delete("product_tour_information", array("id_product_tour" => $id_product_tour));
    $this->session->set_flashdata('success', 'Data terhapus');
    redirect("inventory/product-tour");
  }
 
  function index($code_tour_information){
      
  
   $pst = $this->input->post(NULL);
    if($pst){
    
        $newdata = array(
            'tour_book_title'                   => $pst['title'],
            'tour_book_code'                    => $pst['code'],
            'tour_book_start_date'              => $pst['start_date'],
            'tour_book_end_date'                => $pst['end_date'],
            'tour_book_name'                    => $pst['name'],
            'tour_book_tc'                      => $pst['tc']
          );
          $this->session->set_userdata($newdata);
    }
    
      $where = "";
     if($this->session->userdata('tour_book_title')){
          $where .= " AND LOWER(C.title) LIKE '%".strtolower($this->session->userdata('tour_book_title'))."%'"; 
      }
      
      if($this->session->userdata('tour_book_tc')){
          $where .= " AND LOWER(D.name) LIKE '%".strtolower($this->session->userdata('tour_book_tc'))."%'"; 
      }
      
      if($this->session->userdata('tour_book_name')){
          $where .= " AND LOWER(CONCAT(A.first_name, ' ', A.last_name)) LIKE '%".strtolower($this->session->userdata('tour_book_name'))."%'"; 
      }
      
      if($this->session->userdata('tour_book_title')){
          $where .= " AND LOWER(C.title) LIKE '%".strtolower($this->session->userdata('tour_book_title'))."%'"; 
      }
      
       if($this->session->userdata('tour_book_code')){
          $where .= " AND LOWER(B.kode) LIKE '%".strtolower($this->session->userdata('tour_book_code'))."%' OR LOWER(A.kode) LIKE '%".strtolower($this->session->userdata('tour_book_code'))."%'"; 
      }
      
     
      $st_date = date("Y-m-01");
      $en_date = date("Y-m-t");
      if($this->session->userdata('tour_book_start_date') || $this->session->userdata('tour_book_end_date')){
        $where .= " AND (A.tanggal BETWEEN '{$this->session->userdata('tour_book_start_date')} 00:00:00' AND '{$this->session->userdata('tour_book_end_date')} 23:59:59')";
      }
      
       
       $book = $this->global_models->get_query("SELECT COUNT(A.id_product_tour_book) AS total"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " WHERE 1=1  {$where}"
        . " ORDER BY tanggal DESC");
       
        $jumlah_list = $book[0]->total;
       
        $url_list = site_url("inventory/tour-book/ajax-tour-book/".$jumlah_list);
        $url_list_halaman = site_url("inventory/tour-book/ajax-halaman-tour-book/".$jumlah_list);
       
//      $book = $this->global_models->get_query("SELECT A.*,B.id_currency, B.start_date,B.visa, B.end_date, B.kode AS code_info,B.airport_tax AS price_tax_insurance, C.title, C.kode AS code_tour,D.name AS name_tc"
//        . " FROM product_tour_book AS A"
//        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
//        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
//        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
//        . " WHERE 1=1 $where "
//        . " ORDER BY tanggal DESC");
      
     
       
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    $foot = "
        <link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>
       
        <script type='text/javascript'>
             $(document).ready(function () { 
            
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
            ."get_list(0);"
      . "</script> ";
    
   // print_r($serach_data); die;
    
    $before_table = "<div>"
      . "{$this->form_eksternal->form_open("", 'role="form"')}"
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Tour Name</label><br>"
            . "{$this->form_eksternal->form_input('title', $this->session->userdata('tour_book_title'), ' class="form-control input-sm" placeholder="Tour Name"')}"
          . "</div>"
        . "</div>"
              
              . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Code</label><br>"
            . "{$this->form_eksternal->form_input('code', $this->session->userdata('tour_book_code'), ' class="form-control input-sm" placeholder="Code"')}"
          . "</div>"
        . "</div>"
            
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Start Date</label>"
            . "{$this->form_eksternal->form_input('start_date', $this->session->userdata('tour_book_start_date'), 'id="start_date" class="form-control input-sm" placeholder="Date"')}"
          . "</div>"
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%;'>"
          . "<div class='control-group'>"
            . "<label>End Date</label>"
            . "{$this->form_eksternal->form_input('end_date', $this->session->userdata('tour_book_end_date'), 'id="end_date" class="form-control input-sm" placeholder="Date"')}"
          . "</div>"
        . "</div>"
              
              . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Name</label>"
            . "{$this->form_eksternal->form_input('name', $this->session->userdata('tour_book_name'), ' class="form-control input-sm" placeholder="Name"')}"
          . "</div>"
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%;'>"
          . "<div class='control-group'>"
            . "<label>TC</label>"
            . "{$this->form_eksternal->form_input('tc', $this->session->userdata('tour_book_tc'), ' class="form-control input-sm" placeholder="TC"')}"
          . "</div>"
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%;'>"
          . "<div class='control-group'>"
            . "<button id='bb' class='btn btn-primary' type='submit'>Search</button>"
          . "</div>"
        . "</div>"
              . "<div class='box-body col-sm-6' style='padding-left:2%;padding-bottom:8%'>"
          . "<div class='control-group'>"
           
          . "</div>"
        . "</div>"
      . "</form>"
    . "</div>";
    
	 if($this->session->userdata("id") == '1'){
              $menutable = '
      <li><a href="'.site_url("inventory/tour-book/generate-price-book").'"><i class="icon-plus"></i> Generate Price Book</a></li>
     ';  
            }else{
              $menutable ="";
            }
			
    $this->template->build('tour-book/list-tour-book', 
      array(
          'url'           => base_url()."themes/".DEFAULTTHEMES."/",
          'url_image'     => base_url()."themes/antavaya/",
          'menu'          => "inventory/tour-book",
          'data'          => $book_detail,
          'title'         => lang("tour_book_list"),
          'category'      => $category,
          'sub_category'  => $sub_category,
          'foot'          => $foot,
          'css'           => $css,
          'serach_data'   => $pst,
          'serach'        => $serach,
          'before_table'  => $before_table,
		  'menutable'   => $menutable,
        ));
    $this->template
      ->set_layout("tableajax")
      ->build('tour-book/list-tour-book');
  
  }
  
  function generate_price_book(){
  
   if(!$this->input->post(NULL)){
     $this->template->build('tour-book/generate-price-book', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
            'data'        => $list,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('tour-book/generate-price-book');
   }else{
    $pst = $this->input->post(NULL);
     $sql = "SELECT A.id_product_tour_book,A.id_users,A.kode AS kode_booking"
        . " ,B.adult_triple_twin,B.child_twin_bed,B.child_extra_bed,B.child_no_bed,B.sgl_supp,B.airport_tax"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " WHERE A.id_product_tour_information ='{$pst['id_product_tour_information']}'";
      $data = $this->global_models->get_query($sql);
     
      foreach ($data as $val) {
        
        $single_adult = ($val->sgl_supp + $val->adult_triple_twin);
        
         $kirim = array(
            "harga_adult_triple_twin"           => $val->adult_triple_twin,
            "harga_child_twin_bed"              => $val->child_twin_bed,
            "harga_child_extra_bed"             => $val->child_extra_bed,
           "harga_child_no_bed"                 => $val->child_no_bed,
            "harga_single_adult"                => $single_adult,
           "harga_airport_tax"                  => $val->airport_tax
        );
        $this->global_models->update("product_tour_book", array("id_product_tour_book" => $val->id_product_tour_book),$kirim);
        $this->load->model("json/mjson_tour");
        $this->mjson_tour->revert_all_payment($val->id_product_tour_book, $this->session->userdata("id"));
        $this->mjson_tour->recount_payment($val->id_product_tour_book, $this->session->userdata("id"));
        
        $user_email  = $this->global_models->get_field("users_channel", "email", array("id_users" => $val->id_users));
        
        $this->load->library('email');
        $this->email->initialize($this->global_models->email_conf());
        $this->email->from('no-reply@antavaya.co.id', 'Administrator AV TMS');
        $this->email->to($user_email);
        $this->email->bcc('hendri.prasetyo@antavaya.com');
        $link = "http://".$_SERVER['HTTP_HOST']."/store/grouptour/product-tour/book-information/".$val->kode_booking;
        $link_url = "<a href='{$link}'>{$val->kode_booking}</a>";
        $this->email->subject('Notifikasi Perubahan Harga Customer Group Tour Status');
        $html = "<html>
            <body>
              Dear Bookers Group tour Status<br><br>
              
                Adanya perubahan harga untuk kode Booking [{$link_url}] <br>
                Link Book Customer <b>{$link}</b><br><br>
                Bookers dapat menginfokan perubahan harga ini ke customer yang bersangkutan.
                <br> 
                </body>
          </html>";
        $this->email->message($html);
        $this->email->send();
      }
       if($user_email){
        $this->session->set_flashdata('success', 'Success');
      }
      else{
        $this->session->set_flashdata('notice', 'Gagal');
      }
      redirect("inventory/tour-book");
   }
   
    
  }
  
  function schedule($id_product_tour_information){
      
  
   $pst = $this->input->post(NULL);
    if($pst){
    
        $newdata = array(
            'tour_book_title'                   => $pst['title'],
            'tour_book_code'                    => $pst['code'],
            'tour_book_start_date'              => $pst['start_date'],
            'tour_book_end_date'                => $pst['end_date'],
            'tour_book_name'                    => $pst['name'],
            'tour_book_tc'                      => $pst['tc']
          );
          $this->session->set_userdata($newdata);
    }
    
      $where = "";
     if($this->session->userdata('tour_book_title')){
          $where .= " AND LOWER(C.title) LIKE '%".strtolower($this->session->userdata('tour_book_title'))."%'"; 
      }
      
      if($this->session->userdata('tour_book_tc')){
          $where .= " AND LOWER(D.name) LIKE '%".strtolower($this->session->userdata('tour_book_tc'))."%'"; 
      }
      
      if($this->session->userdata('tour_book_name')){
          $where .= " AND LOWER(CONCAT(A.first_name, ' ', A.last_name)) LIKE '%".strtolower($this->session->userdata('tour_book_name'))."%'"; 
      }
      
      if($this->session->userdata('tour_book_title')){
          $where .= " AND LOWER(C.title) LIKE '%".strtolower($this->session->userdata('tour_book_title'))."%'"; 
      }
      
       if($this->session->userdata('tour_book_code')){
          $where .= " AND LOWER(B.kode) LIKE '%".strtolower($this->session->userdata('tour_book_code'))."%' OR LOWER(A.kode) LIKE '%".strtolower($this->session->userdata('tour_book_code'))."%'"; 
      }
      
     
      $st_date = date("Y-m-01");
      $en_date = date("Y-m-t");
      if($this->session->userdata('tour_book_start_date') || $this->session->userdata('tour_book_end_date')){
        $where .= " AND (A.tanggal BETWEEN '{$this->session->userdata('tour_book_start_date')} 00:00:00' AND '{$this->session->userdata('tour_book_end_date')} 23:59:59')";
      }
      
       
       $book = $this->global_models->get_query("SELECT COUNT(A.id_product_tour_book) AS total"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " WHERE B.id_product_tour_information = '{$id_product_tour_information}'"
        . " {$where}"
        . " ORDER BY tanggal DESC");
       
        $jumlah_list = $book[0]->total;
       
        $url_list = site_url("inventory/tour-book/ajax-tour-book-schedule/".$jumlah_list);
        $url_list_halaman = site_url("inventory/tour-book/ajax-halaman-tour-book/".$jumlah_list);
       
//      $book = $this->global_models->get_query("SELECT A.*,B.id_currency, B.start_date,B.visa, B.end_date, B.kode AS code_info,B.airport_tax AS price_tax_insurance, C.title, C.kode AS code_tour,D.name AS name_tc"
//        . " FROM product_tour_book AS A"
//        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
//        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
//        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
//        . " WHERE 1=1 $where "
//        . " ORDER BY tanggal DESC");
      
     
       
        
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    $foot = "
        <link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>
       
        <script type='text/javascript'>
             $(document).ready(function () { 
            
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
           ."$.post('{$url_list}/'+start, {id_product_tour_information: {$id_product_tour_information}}, function(data){"
            ."$('#data_list').html(data);"
             ."$.post('{$url_list_halaman}/'+start, function(data){"
              ."$('#halaman_set').html(data);"
               ." });"
                ."});"
            ."}"
            ."get_list(0);"
      . "</script> ";
    
   // print_r($serach_data); die;
    
    $before_table = "<div>"
      . "{$this->form_eksternal->form_open("", 'role="form"')}"
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Tour Name</label><br>"
            . "{$this->form_eksternal->form_input('title', $this->session->userdata('tour_book_title'), ' class="form-control input-sm" placeholder="Tour Name"')}"
          . "</div>"
        . "</div>"
              
              . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Code</label><br>"
            . "{$this->form_eksternal->form_input('code', $this->session->userdata('tour_book_code'), ' class="form-control input-sm" placeholder="Code"')}"
          . "</div>"
        . "</div>"
            
        . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Start Date</label>"
            . "{$this->form_eksternal->form_input('start_date', $this->session->userdata('tour_book_start_date'), 'id="start_date" class="form-control input-sm" placeholder="Date"')}"
          . "</div>"
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%;'>"
          . "<div class='control-group'>"
            . "<label>End Date</label>"
            . "{$this->form_eksternal->form_input('end_date', $this->session->userdata('tour_book_end_date'), 'id="end_date" class="form-control input-sm" placeholder="Date"')}"
          . "</div>"
        . "</div>"
              
              . "<div class='box-body col-sm-6' style='padding-left:2%'>"
          . "<div class='control-group'>"
            . "<label>Name</label>"
            . "{$this->form_eksternal->form_input('name', $this->session->userdata('tour_book_name'), ' class="form-control input-sm" placeholder="Name"')}"
          . "</div>"
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%;'>"
          . "<div class='control-group'>"
            . "<label>TC</label>"
            . "{$this->form_eksternal->form_input('tc', $this->session->userdata('tour_book_tc'), ' class="form-control input-sm" placeholder="TC"')}"
          . "</div>"
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%;'>"
          . "<div class='control-group'>"
            . "<button id='bb' class='btn btn-primary' type='submit'>Search</button>"
          . "</div>"
        . "</div>"
              . "<div class='box-body col-sm-6' style='padding-left:2%;padding-bottom:8%'>"
          . "<div class='control-group'>"
           
          . "</div>"
        . "</div>"
      . "</form>"
    . "</div>";
    
    $this->template->build('tour-book/list', 
      array(
          'url'           => base_url()."themes/".DEFAULTTHEMES."/",
          'url_image'     => base_url()."themes/antavaya/",
          'menu'          => "inventory/tour-book",
          'data'          => $book_detail,
          'title'         => lang("tour_book_list"),
          'category'      => $category,
          'sub_category'  => $sub_category,
          'foot'          => $foot,
          'css'           => $css,
          'serach_data'   => $pst,
          'serach'        => $serach,
          'before_table'  => $before_table,
        ));
    $this->template
      ->set_layout("tableajax")
      ->build('tour-book/list');
  
  }
  
  function ajax_tour_book($total = 0, $start = 0){
    
    $where = "";
     if($this->session->userdata('tour_book_title')){
          $where .= " AND LOWER(C.title) LIKE '%".strtolower($this->session->userdata('tour_book_title'))."%'"; 
      }
      
      if($this->session->userdata('tour_book_tc')){
          $where .= " AND LOWER(D.name) LIKE '%".strtolower($this->session->userdata('tour_book_tc'))."%'"; 
      }
      
      if($this->session->userdata('tour_book_name')){
          $where .= " AND LOWER(CONCAT(A.first_name, ' ', A.last_name)) LIKE '%".strtolower($this->session->userdata('tour_book_name'))."%'"; 
      }
      
       if($this->session->userdata('tour_book_code')){
          $where .= " AND LOWER(B.kode) LIKE '%".strtolower($this->session->userdata('tour_book_code'))."%' OR LOWER(A.kode) LIKE '%".strtolower($this->session->userdata('tour_book_code'))."%'"; 
      }
      
     
      $st_date = date("Y-m-01");
      $en_date = date("Y-m-t");
      if($this->session->userdata('start_date') || $this->session->userdata('$end_date')){
        $where .= " AND (A.tanggal BETWEEN '{$this->session->userdata('start_date')} 00:00:00' AND '{$this->session->userdata('$end_date')} 23:59:59')";
      }
      
        $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
      
      $book = $this->global_models->get_query("SELECT A.*,B.id_currency, B.start_date,B.visa, B.end_date, B.kode AS code_info,B.airport_tax AS price_tax_insurance, C.title, C.kode AS code_tour,D.name AS name_tc"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " WHERE 1=1 {$where}"
        . " ORDER BY tanggal DESC"
        . " LIMIT {$start}, 10");
//     print $this->db->last_query(); die;
     if($book){
        $additional_tour = "";
        foreach($book AS $key => $bk){
        $price_tax_inusrance =  ($bk->adult_triple_twin + $bk->child_twin_bed + $bk->child_extra_bed + $bk->child_no_bed + $bk->sgl_supp) * $bk->price_tax_insurance;
//          $balance = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE 0 END) AS debit"
//            . " ,SUM(CASE WHEN pos = 2 THEN nominal ELSE 0 END) AS kredit"
//            . " FROM product_tour_book_payment"
//            . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
         $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status < '3' ");
         
        
        if($bk->id_currency == 1){
              $total_visa = ($bk->visa * $total_visa[0]->totl_visa) * $dropdown_rate[1];
              $price_tax_inusrance = $price_tax_inusrance * $dropdown_rate[1];
          }elseif($bk->id_currency == 2){
              $total_visa = ($bk->visa * $total_visa[0]->totl_visa);
              $price_tax_inusrance = $price_tax_inusrance;
          }
          
        
        //  $dp = $this->global_models->get_field("product_tour_information", "dp", array("id_product_tour_information" => $bk->id_product_tour_information));
          $nominal_pertama = $this->global_models->get_field("product_tour_book_payment", "nominal", array("pos" => 1, "status" => 1, "id_product_tour_book" => $bk->id_product_tour_book));
        
//        $additional = $this->global_models->get_query("SELECT name,nominal,id_currency,pos "
//        . " FROM product_tour_additional"
//        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
         $passenger_book = $this->global_models->get_query("SELECT count(kode) as book"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='1' ");
        
        $passenger_commit = $this->global_models->get_query("SELECT count(kode) as commit_book"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='2' ");
      
        $passenger_lunas = $this->global_models->get_query("SELECT count(kode) as lunas"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='3' ");
        
        $passenger_cancel = $this->global_models->get_query("SELECT count(kode) as cancel"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='4' ");
        
        $passenger_cancel_waiting = $this->global_models->get_query("SELECT count(kode) as waiting"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='5' ");
           
        
//        if($passenger_book[0]->book > 0){
//          $st_book = "<b>".$passenger_book[0]->book." Book </b> <br>";
//        }if($passenger_commit[0]->commit_book > 0){
//          $st_commit = "<b>".$passenger_commit[0]->commit_book." Committed Book </b> <br>";
//        }if($passenger_lunas[0]->lunas > 0){
//          $st_lunas = "<b>".$passenger_lunas[0]->lunas." Lunas </b> <br>";
//        }if($passenger_cancel[0]->cancel > 0){
//          $st_cancel = "<b>".$passenger_cancel[0]->cancel." Cancel </b> <br>";
//        }if($passenger_cancel_waiting->waiting > 0){
//          $st_wtapp = "<b>".$passenger_cancel_waiting->waiting." [Cancel] Waiting Approval </b> <br>";
//        }
//  
//      
//      $statusPassager = $st_book.$st_commit.$st_lunas.$st_cancel.$st_wtapp;
        // $passenger = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
       
          $balance[$key] = $this->global_models->get_query("SELECT nominal,status, id_currency,pos"
              . " FROM product_tour_book_payment"
              . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'"); 
              
           
        foreach ($balance[$key] as $val_balance) {
           if($val_balance->id_currency == 1){
//               $nom1_usd = $val_balance->nominal;
//               $nom1_idr = $val_balance->nominal * $dropdown_rate[1];
             }elseif($val_balance->id_currency == 2){
//                   $nom1_usd = $val_balance->nominal/$dropdown_rate[1];
                   $nom1_idr = $val_balance->nominal;
             }
                if($val_balance->pos == 1){
//                  $balance_debit_usd[$ky] += $nom1_usd;
                  $balance_debit_idr[$key] += $nom1_idr;
                }
                elseif($val_balance->pos == 2 AND $val_balance->status != 2){
//                  $balance_kredit_usd[$ky] += $nom1_usd;
                  $balance_kredit_potongan_tambahan_idr[$key] += $nom1_idr;
                }elseif($val_balance->pos == 2 AND $val_balance->status == 2){
//                    $balance_kredit_usd[$ky] += $nom1_usd;
                  $balance_kredit_pembayaran_idr[$key] += $nom1_idr;
                }
        }
//         if($discount_tambahan[0]->status == 1){
//            $status_disc_tambahan = "Apprave";
//          }  elseif($discount_tambahan[0]->status == 2) {
//            $status_disc_tambahan = "Waiting Appraval";
//          }
//          if($bk->stnb_discount == 1){
//            $stnb_discount = "Persen";
//          }elseif($bk->stnb_discount == 2){
//            $stnb_discount = "Nominal";
//          }
//           $discount_tambahan = $this->global_models->get_query("SELECT id_product_tour_discount_tambahan,discount_request,status_discount,status"
//          . " FROM product_tour_discount_tambahan"
//          . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status=1"); 
          
//          $book_detail[] = array(
//            "id_product_tour"   => $bk->id_product_tour,
//            "tour"              => $bk->title,
//            "tour_code"         => $bk->code_tour,
//            "start_date"        => $bk->start_date,
//            "end_date"          => $bk->end_date,
//            "info_code"       => $bk->code_info,
//            "code"            => $bk->kode,
//            "first_name"      => $bk->first_name,
//            "name_tc"       => $bk->name_tc,
//            "last_name"       => $bk->last_name,
//            "telp"            => $bk->telphone,
//            "email"           => $bk->email,
//            "tanggal"         => $bk->tanggal,
//            "status"          => $bk->status,
//            "discount"        => $bk->discount,
//            "total_visa"      => $total_visa,
//            "dicount_tambahan"  => $discount_tambahan,
//            "status_discount"         => $stnb_discount,
//            "tax_and_insurance" => $price_tax_inusrance,
//            "beban_awal"      => $balance_debit_idr[$ky],
//            "pembayaran"      => $balance_kredit_idr[$ky],
//            "currency_rate"   => $dropdown_rate[1],  
//            "additional"      => $additional,
//            "passenger"       => array("passenger_book" =>$passenger_book[0]->book,"passenger_commit" => $passenger_commit[0]->commit_book,"passenger_lunas" =>$passenger_lunas[0]->lunas,"passenger_cancel" => $passenger_cancel[0]->cancel,"passenger_cancel_waiting" => $passenger_cancel_waiting[0]->waiting),
//            "committed_book"  => $ps_comit
//          );
          
  
  if($passenger_book[0]->book > 0){
      $st_book[$key] = "<b>".$passenger_book[0]->book." Book </b> <br>";
  }
  if($passenger_commit[0]->commit_book > 0){
       $st_commit[$key] = "<b>".$passenger_commit[0]->commit_book." Deposit</b> <br>";
  }
  if($passenger_lunas[0]->lunas > 0){
       $st_lunas[$key] = "<b>".$passenger_lunas[0]->lunas." Lunas </b> <br>";
  }
  if($passenger_cancel[0]->cancel > 0){
       $st_cancel[$key] = "<b>".$passenger_cancel[0]->cancel." Cancel </b> <br>";
  }
  if($passenger_cancel_waiting[0]->waiting > 0){
       $st_wtapp[$key] = "<b>".$passenger_cancel_waiting[0]->waiting." [Cancel] Waiting Approval </b> <br>";
  }
 $statusPassager[$key] = $st_book[$key].$st_commit[$key].$st_lunas[$key].$st_cancel[$key].$st_wtapp[$key];

      $detail_beban = ""
      . "<div style='display: none' id='isi{$bk->kode}'>"
        . "<table width='100%'>"
          . "<tr>"
            . "<td>Beban Awal</td>"
            . "<td style='text-align: left'>".number_format($balance_debit_idr[$key])."</td>"
          . "</tr>";
         
     // if($total_visa > 0){
       //    $detail_beban .= "<tr>"
        //    . "<td>Visa </td>"
         //   . "<td style='text-align: left'>".number_format($total_visa)."</td>"
         //   . "</tr>";
     // }
            

        $total_additional = 0;
   
//        if(is_array($additional)){
//     
//          foreach ($additional as $ky => $val) {
//            
//        
//            if($val->id_currency == 1){
//                  $nom_add0[$ky] = $val->nominal * $dropdown_rate[1];
//                }elseif($val->id_currency == 2){
//                  $nom_add0[$ky] = $val->nominal;
//                }
//                if($val->pos == 1){
//                  $mins = "- ";
//                $total_kredit2[$key] .= $total_kredit2[$key] + $nom_add0[$ky];
//              }else{
//                $mins = "";
//                $total_debit2[$key] .= $total_debit2[$key] + $nom_add0[$ky];
//              }
//            $detail_beban .= "<tr>"
//            . "<td>{$val->name}</td>"
//            . "<td style='text-align: left'>"."{$mins}".number_format($nom_add0[$ky])."</td>"
//          . "</tr>";
//          }
//        }
//          $tot_disc_price=0;
//        if($stnb_discount == "Persen"){
//          $tot_disc_price =  (($balance_debit_idr[$key] * $bk->discount)/100);
//        }elseif($stnb_discount == "Nominal") {
//          $tot_disc_price = $bk->discount;
//        }
//        $ppn = 1 * (($balance_debit_idr[$key] + (int)$total_visa + $total_debit2[$key] + $price_tax_inusrance) - $tot_disc_price)/100;
//       
//        $detail_beban .= "<tr>"
//            . "<td>Discount</td>"
//            . "<td style='text-align: left'>- ".number_format($tot_disc_price)."</td>"
//          . "</tr>";
//        
//        $detail_beban .= "<tr>"
//            . "<td>PPN 1%</td>"
//            . "<td style='text-align: left'> ".number_format($ppn,2,".",",")."</td>"
//          . "</tr>";
        
//        if($discount_tambahan[0]->discount_request){
//            if($discount_tambahan[0]->status_discount == 1){
//                    $status_disc_tambh = "[Persen]";
//                    $tot_disc_tambahan[$key] =  (($balance_debit_idr[$key] * $discount_tambahan[0]->discount_request)/100);
//            }elseif($discount_tambahan[0]->status_discount == 2) {
//                   $status_disc_tambh = "Nominal";
//                   $tot_disc_tambahan[$key] = $discount_tambahan[0]->discount_request;
//             }
//                  $detail_beban .= "<tr>"
//            . "<td>Discount Tambahan</td>"
//            . "<td style='text-align: left'>- ".number_format($tot_disc_tambahan[$key])."</td>"
//          . "</tr>";
//        }       
        if($balance_kredit_potongan_tambahan_idr[$key]){
             $detail_beban .= "<tr>"
            . "<td>Potongan Biaya</td>"
            . "<td style='text-align: left'>- ".number_format($balance_kredit_potongan_tambahan_idr[$key])."</td>"
          . "</tr>";
        }
        
           $detail_beban .= "<tr>"
            . "<td>Pembayaran</td>"
            . "<td style='text-align: left'>- ".number_format($balance_kredit_pembayaran_idr[$key])."</td>"
          . "</tr>"
       
        . "</table>"
      . "</div>"
      . "<div style='display: none' id='isiinfo{$bk->kode}'>"
        . "<table width='100%'>"
          . "<tr>"
            . "<td>Tour</td>"
            . "<td style='text-align: left'><a href='".site_url("inventory/product-tour/tour-detail/{$bk->id_product_tour}")."'>{$bk->title}</a></td>"
          . "</tr>"
          . "<tr>"
            . "<td>Start Date</td>"
            . "<td style='text-align: left'>".date("d F Y", strtotime($bk->start_date))."</td>"
          . "</tr>"
          . "<tr>"
            . "<td>End Date</td>"
            . "<td style='text-align: left'>".date("d F Y", strtotime($bk->end_date))."</td>"
          . "</tr>"
        . "</table>"
      . "</div>"
              . "<div style='display: none' id='isiinfo1{$bk->kode}'>"
        . "<table width='100%'>"
          . "<tr>"
            . "<td>Email</td>"
            . "<td style='text-align: left'>{$bk->email}</td>"
          . "</tr>"
          . "<tr>"
            . "<td>No Telp</td>"
            . "<td style='text-align: left'>{$bk->telphone}</td>"
          . "</tr>"
        . "</table>"
      . "</div>"
      . "<script>"
        . "$(function() {"
          . "$('#{$bk->kode}').tooltipster({"
            . "content: $('#isi{$bk->kode}').html(),"
            . "minWidth: 300,"
            . "maxWidth: 300,"
            . "contentAsHTML: true,"
            . "interactive: true"
          . "});"
          . "$('#info{$bk->kode}').tooltipster({"
            . "content: $('#isiinfo{$bk->kode}').html(),"
            . "minWidth: 300,"
            . "maxWidth: 300,"
            . "contentAsHTML: true,"
            . "interactive: true"
          . "});"
              . "$('#info1{$bk->kode}').tooltipster({"
            . "content: $('#isiinfo1{$bk->kode}').html(),"
            . "minWidth: 300,"
            . "maxWidth: 300,"
            . "contentAsHTML: true,"
            . "interactive: true"
          . "});"
        . "});"
      . "</script>";
      $tampil = "<tr>"
        . "<td>{$bk->tanggal}</td>"
        . "<td>{$bk->title}</td>"
        . "<td><a href='javascript:void(0)' id='info{$bk->kode}'>{$bk->kode}</a></td>"
        . "<td><a href='javascript:void(0)' id='info1{$bk->kode}'>{$bk->first_name} {$bk->last_name}</a></td>"
        . "<td>{$statusPassager[$key]}</td>"
        . "<td style='text-align: right; font-weight: bold;'>"
          . "<a href='javascript:void(0)' id='{$bk->kode}'>".number_format((($balance_debit_idr[$key] ) - ($balance_kredit_pembayaran_idr[$key] + $balance_kredit_potongan_tambahan_idr[$key])),2,".",",")."</a>"
          . $detail_beban
        . "</td>"
         . "<td>{$bk->name_tc}</td>"
        . "<td>"
          . "<div class='btn-group'>"
          . "<button data-toggle='dropdown' class='btn btn-small dropdown-toggle'>Action<span class='caret'></span></button>"
          . "<ul class='dropdown-menu'>"
            . "<li><a href='".site_url("inventory/tour-book/book-information/".$bk->kode)."'>Detail</a></li>"
          
        . "</td>"
      . "</tr>";
         print $tampil;
  }
        }
   
   
  
    die;
  }
  
  function ajax_tour_book_schedule($total = 0, $start = 0){
    
    $where = "";
     if($this->session->userdata('tour_book_title')){
          $where .= " AND LOWER(C.title) LIKE '%".strtolower($this->session->userdata('tour_book_title'))."%'"; 
      }
      
      if($this->session->userdata('tour_book_tc')){
          $where .= " AND LOWER(D.name) LIKE '%".strtolower($this->session->userdata('tour_book_tc'))."%'"; 
      }
      
      if($this->session->userdata('tour_book_name')){
          $where .= " AND LOWER(CONCAT(A.first_name, ' ', A.last_name)) LIKE '%".strtolower($this->session->userdata('tour_book_name'))."%'"; 
      }
      
       if($this->session->userdata('tour_book_code')){
          $where .= " AND LOWER(B.kode) LIKE '%".strtolower($this->session->userdata('tour_book_code'))."%' OR LOWER(A.kode) LIKE '%".strtolower($this->session->userdata('tour_book_code'))."%'"; 
      }
      
     
      $st_date = date("Y-m-01");
      $en_date = date("Y-m-t");
      if($this->session->userdata('start_date') || $this->session->userdata('$end_date')){
        $where .= " AND (A.tanggal BETWEEN '{$this->session->userdata('start_date')} 00:00:00' AND '{$this->session->userdata('$end_date')} 23:59:59')";
      }
      
        $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
      
      $book = $this->global_models->get_query("SELECT A.*,B.id_currency, B.start_date,B.visa, B.end_date, B.kode AS code_info,B.airport_tax AS price_tax_insurance, C.title, C.kode AS code_tour,D.name AS name_tc"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " WHERE B.id_product_tour_information = '{$this->input->post("id_product_tour_information")}'"
        . " {$where}"
        . " ORDER BY tanggal DESC"
        . " LIMIT {$start}, 10");
//     print $this->db->last_query(); die;
     if($book){
        $additional_tour = "";
        foreach($book AS $key => $bk){
        $price_tax_inusrance =  ($bk->adult_triple_twin + $bk->child_twin_bed + $bk->child_extra_bed + $bk->child_no_bed + $bk->sgl_supp) * $bk->price_tax_insurance;
//          $balance = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE 0 END) AS debit"
//            . " ,SUM(CASE WHEN pos = 2 THEN nominal ELSE 0 END) AS kredit"
//            . " FROM product_tour_book_payment"
//            . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
         $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status < '3' ");
         
        
        if($bk->id_currency == 1){
              $total_visa = ($bk->visa * $total_visa[0]->totl_visa) * $dropdown_rate[1];
              $price_tax_inusrance = $price_tax_inusrance * $dropdown_rate[1];
          }elseif($bk->id_currency == 2){
              $total_visa = ($bk->visa * $total_visa[0]->totl_visa);
              $price_tax_inusrance = $price_tax_inusrance;
          }
          
        
        //  $dp = $this->global_models->get_field("product_tour_information", "dp", array("id_product_tour_information" => $bk->id_product_tour_information));
          $nominal_pertama = $this->global_models->get_field("product_tour_book_payment", "nominal", array("pos" => 1, "status" => 1, "id_product_tour_book" => $bk->id_product_tour_book));
        
        $additional = $this->global_models->get_query("SELECT name,nominal,id_currency,pos "
        . " FROM product_tour_additional"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
         $passenger_book = $this->global_models->get_query("SELECT count(kode) as book"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='1' ");
        
        $passenger_commit = $this->global_models->get_query("SELECT count(kode) as commit_book"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='2' ");
      
        $passenger_lunas = $this->global_models->get_query("SELECT count(kode) as lunas"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='3' ");
        
        $passenger_cancel = $this->global_models->get_query("SELECT count(kode) as cancel"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='4' ");
        
        $passenger_cancel_waiting = $this->global_models->get_query("SELECT count(kode) as waiting"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='5' ");
           
        
//        if($passenger_book[0]->book > 0){
//          $st_book = "<b>".$passenger_book[0]->book." Book </b> <br>";
//        }if($passenger_commit[0]->commit_book > 0){
//          $st_commit = "<b>".$passenger_commit[0]->commit_book." Committed Book </b> <br>";
//        }if($passenger_lunas[0]->lunas > 0){
//          $st_lunas = "<b>".$passenger_lunas[0]->lunas." Lunas </b> <br>";
//        }if($passenger_cancel[0]->cancel > 0){
//          $st_cancel = "<b>".$passenger_cancel[0]->cancel." Cancel </b> <br>";
//        }if($passenger_cancel_waiting->waiting > 0){
//          $st_wtapp = "<b>".$passenger_cancel_waiting->waiting." [Cancel] Waiting Approval </b> <br>";
//        }
//  
//      
//      $statusPassager = $st_book.$st_commit.$st_lunas.$st_cancel.$st_wtapp;
        // $passenger = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
       
          $balance[$key] = $this->global_models->get_query("SELECT nominal, id_currency,pos"
              . " FROM product_tour_book_payment"
              . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'"); 
              
           
        foreach ($balance[$key] as $val_balance) {
           if($val_balance->id_currency == 1){
                $nom1_usd = $val_balance->nominal;
                $nom1_idr = $val_balance->nominal * $dropdown_rate[1];
             }elseif($val_balance->id_currency == 2){
                   $nom1_usd = $val_balance->nominal/$dropdown_rate[1];
                   $nom1_idr = $val_balance->nominal;
             }
                if($val_balance->pos == 1){
                  $balance_debit_usd[$key] += $nom1_usd;
                  $balance_debit_idr[$key] += $nom1_idr;
                }
                elseif($val_balance->pos == 2){
                  $balance_kredit_usd[$key] += $nom1_usd;
                  $balance_kredit_idr[$key] += $nom1_idr;
                }
        }
         if($discount_tambahan[0]->status == 1){
            $status_disc_tambahan = "Apprave";
          }  elseif($discount_tambahan[0]->status == 2) {
            $status_disc_tambahan = "Waiting Appraval";
          }
          if($bk->stnb_discount == 1){
            $stnb_discount = "Persen";
          }elseif($bk->stnb_discount == 2){
            $stnb_discount = "Nominal";
          }
           $discount_tambahan = $this->global_models->get_query("SELECT id_product_tour_discount_tambahan,discount_request,status_discount,status"
          . " FROM product_tour_discount_tambahan"
          . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status=1"); 
          
//          $book_detail[] = array(
//            "id_product_tour"   => $bk->id_product_tour,
//            "tour"              => $bk->title,
//            "tour_code"         => $bk->code_tour,
//            "start_date"        => $bk->start_date,
//            "end_date"          => $bk->end_date,
//            "info_code"       => $bk->code_info,
//            "code"            => $bk->kode,
//            "first_name"      => $bk->first_name,
//            "name_tc"       => $bk->name_tc,
//            "last_name"       => $bk->last_name,
//            "telp"            => $bk->telphone,
//            "email"           => $bk->email,
//            "tanggal"         => $bk->tanggal,
//            "status"          => $bk->status,
//            "discount"        => $bk->discount,
//            "total_visa"      => $total_visa,
//            "dicount_tambahan"  => $discount_tambahan,
//            "status_discount"         => $stnb_discount,
//            "tax_and_insurance" => $price_tax_inusrance,
//            "beban_awal"      => $balance_debit_idr[$ky],
//            "pembayaran"      => $balance_kredit_idr[$ky],
//            "currency_rate"   => $dropdown_rate[1],  
//            "additional"      => $additional,
//            "passenger"       => array("passenger_book" =>$passenger_book[0]->book,"passenger_commit" => $passenger_commit[0]->commit_book,"passenger_lunas" =>$passenger_lunas[0]->lunas,"passenger_cancel" => $passenger_cancel[0]->cancel,"passenger_cancel_waiting" => $passenger_cancel_waiting[0]->waiting),
//            "committed_book"  => $ps_comit
//          );
          
  
  if($passenger_book[0]->book > 0){
      $st_book[$key] = "<b>".$passenger_book[0]->book." Book </b> <br>";
  }
  if($passenger_commit[0]->commit_book > 0){
       $st_commit[$key] = "<b>".$passenger_commit[0]->commit_book." Deposit</b> <br>";
  }
  if($passenger_lunas[0]->lunas > 0){
       $st_lunas[$key] = "<b>".$passenger_lunas[0]->lunas." Lunas </b> <br>";
  }
  if($passenger_cancel[0]->cancel > 0){
       $st_cancel[$key] = "<b>".$passenger_cancel[0]->cancel." Cancel </b> <br>";
  }
  if($passenger_cancel_waiting[0]->waiting > 0){
       $st_wtapp[$key] = "<b>".$passenger_cancel_waiting[0]->waiting." [Cancel] Waiting Approval </b> <br>";
  }
 $statusPassager[$key] = $st_book[$key].$st_commit[$key].$st_lunas[$key].$st_cancel[$key].$st_wtapp[$key];

      $detail_beban = ""
      . "<div style='display: none' id='isi{$bk->kode}'>"
        . "<table width='100%'>"
          . "<tr>"
            . "<td>Beban Awal</td>"
            . "<td style='text-align: left'>".number_format($balance_debit_idr[$key])."</td>"
          . "</tr>"
          . "<tr>"
            . "<td>Airport Tax & Flight Insurance </td>"
            . "<td style='text-align: left'>".number_format($price_tax_inusrance)."</td>"
          . "</tr>";
     // if($total_visa > 0){
       //    $detail_beban .= "<tr>"
       //     . "<td>Visa </td>"
        //    . "<td style='text-align: left'>".number_format($total_visa)."</td>"
        //    . "</tr>";
     // }
            

        $total_additional = 0;
   
        if(is_array($additional)){
     
          foreach ($additional as $ky => $val) {
            
        
            if($val->id_currency == 1){
                  $nom_add0[$ky] = $val->nominal * $dropdown_rate[1];
                }elseif($val->id_currency == 2){
                  $nom_add0[$ky] = $val->nominal;
                }
                if($val->pos == 1){
                  $mins = "- ";
                $total_kredit2[$key] .= $total_kredit2[$key] + $nom_add0[$ky];
              }else{
                $mins = "";
                $total_debit2[$key] .= $total_debit2[$key] + $nom_add0[$ky];
              }
            $detail_beban .= "<tr>"
            . "<td>{$val->name}</td>"
            . "<td style='text-align: left'>"."{$mins}".number_format($nom_add0[$ky])."</td>"
          . "</tr>";
          }
        }
          $tot_disc_price=0;
        if($stnb_discount == "Persen"){
          $tot_disc_price =  (($balance_debit_idr[$key] * $bk->discount)/100);
        }elseif($stnb_discount == "Nominal") {
          $tot_disc_price = $bk->discount;
        }
        $ppn = 1 * (($balance_debit_idr[$key] + (int)$total_visa + $total_debit2[$key] + $price_tax_inusrance) - $tot_disc_price)/100;
       
        $detail_beban .= "<tr>"
            . "<td>Discount</td>"
            . "<td style='text-align: left'>- ".number_format($tot_disc_price)."</td>"
          . "</tr>";
        
        $detail_beban .= "<tr>"
            . "<td>PPN 1%</td>"
            . "<td style='text-align: left'> ".number_format($ppn,2,".",",")."</td>"
          . "</tr>";
        
        if($discount_tambahan[0]->discount_request){
            if($discount_tambahan[0]->status_discount == 1){
                    $status_disc_tambh = "[Persen]";
                    $tot_disc_tambahan[$key] =  (($balance_debit_idr[$key] * $discount_tambahan[0]->discount_request)/100);
            }elseif($discount_tambahan[0]->status_discount == 2) {
                   $status_disc_tambh = "Nominal";
                   $tot_disc_tambahan[$key] = $discount_tambahan[0]->discount_request;
             }
                  $detail_beban .= "<tr>"
            . "<td>Discount Tambahan</td>"
            . "<td style='text-align: left'>- ".number_format($tot_disc_tambahan[$key])."</td>"
          . "</tr>";
        }       
           $detail_beban .= "<tr>"
            . "<td>Pembayaran</td>"
            . "<td style='text-align: left'>- ".number_format($balance_kredit_idr[$key])."</td>"
          . "</tr>"
       
        . "</table>"
      . "</div>"
      . "<div style='display: none' id='isiinfo{$bk->kode}'>"
        . "<table width='100%'>"
          . "<tr>"
            . "<td>Tour</td>"
            . "<td style='text-align: left'><a href='".site_url("inventory/product-tour/tour-detail/{$bk->id_product_tour}")."'>{$bk->title}</a></td>"
          . "</tr>"
          . "<tr>"
            . "<td>Start Date</td>"
            . "<td style='text-align: left'>".date("d F Y", strtotime($bk->start_date))."</td>"
          . "</tr>"
          . "<tr>"
            . "<td>End Date</td>"
            . "<td style='text-align: left'>".date("d F Y", strtotime($bk->end_date))."</td>"
          . "</tr>"
        . "</table>"
      . "</div>"
              . "<div style='display: none' id='isiinfo1{$bk->kode}'>"
        . "<table width='100%'>"
          . "<tr>"
            . "<td>Email</td>"
            . "<td style='text-align: left'>{$bk->email}</td>"
          . "</tr>"
          . "<tr>"
            . "<td>No Telp</td>"
            . "<td style='text-align: left'>{$bk->telphone}</td>"
          . "</tr>"
        . "</table>"
      . "</div>"
      . "<script>"
        . "$(function() {"
          . "$('#{$bk->kode}').tooltipster({"
            . "content: $('#isi{$bk->kode}').html(),"
            . "minWidth: 300,"
            . "maxWidth: 300,"
            . "contentAsHTML: true,"
            . "interactive: true"
          . "});"
          . "$('#info{$bk->kode}').tooltipster({"
            . "content: $('#isiinfo{$bk->kode}').html(),"
            . "minWidth: 300,"
            . "maxWidth: 300,"
            . "contentAsHTML: true,"
            . "interactive: true"
          . "});"
              . "$('#info1{$bk->kode}').tooltipster({"
            . "content: $('#isiinfo1{$bk->kode}').html(),"
            . "minWidth: 300,"
            . "maxWidth: 300,"
            . "contentAsHTML: true,"
            . "interactive: true"
          . "});"
        . "});"
      . "</script>";
      $tampil = "<tr>"
        . "<td>{$bk->tanggal}</td>"
        . "<td>{$bk->title}</td>"
        . "<td><a href='javascript:void(0)' id='info{$bk->kode}'>{$bk->kode}</a></td>"
        . "<td><a href='javascript:void(0)' id='info1{$bk->kode}'>{$bk->first_name} {$bk->last_name}</a></td>"
        . "<td>{$statusPassager[$key]}</td>"
//        . "<td style='text-align: right; font-weight: bold;'>"
//          . "<a href='javascript:void(0)' id='{$bk->kode}'>".number_format((($balance_debit_idr[$key] + $total_debit2[$key] + $price_tax_inusrance + $ppn) - ($balance_kredit_idr[$key] + $total_kredit2[$key] + $tot_disc_price + $tot_disc_tambahan[$key])),2,".",",")."</a>"
//          . $detail_beban
//        . "</td>"
         . "<td>{$bk->name_tc}</td>"
        . "<td>"
          . "<div class='btn-group'>"
          . "<button data-toggle='dropdown' class='btn btn-small dropdown-toggle'>Action<span class='caret'></span></button>"
          . "<ul class='dropdown-menu'>"
            . "<li><a href='".site_url("inventory/tour-book/book-information/".$bk->kode)."'>Detail</a></li>"
          
        . "</td>"
      . "</tr>";
         print $tampil;
  }
        }
   
   
  
    die;
  }
  
  function ajax_halaman_tour_book($total = 0, $start = 0){
    
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
  
  function payment($id_product_tour_book_payment){
      
   // $pst = $this->input->post(NULL);
  
    if($id_product_tour_book_payment){
      $data_payment2 = $this->global_models->get("product_tour_book_payment", array("id_product_tour_book_payment" => $id_product_tour_book_payment));
      if($data_payment2[0]->id_product_tour_book_payment > 0){
        
        
        $status_data = $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $id_product_tour_book_payment), array("status" => 2,"update_by_users" =>$this->session->userdata("id")));
       $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
           
 //  $data_tour_book2 = $this->global_models->get("product_tour_book", array("id_product_tour_book" => $data_payment2[0]->id_product_tour_book));
        $data_tour_book2 = $this->global_models->get_query("SELECT A.*,A.stnb_discount,A.adult_triple_twin AS total_person_adult_ttwin,A.child_twin_bed AS total_person_child_twin,A.child_extra_bed AS total_person_child_extra,A.child_no_bed AS total_person_child_no_bed,A.sgl_supp AS total_person_sgl_supp, A.DP,A.status_additional_request"
        . " , B.title, B.category, B.sub_category, B.kode AS tour_code"
        . " , C.kode AS tour_information_code, C.id_product_tour_information, C.start_date, C.end_date, C.available_seat, C.adult_triple_twin, C.child_twin_bed, C.child_extra_bed, C.child_no_bed,C.sgl_supp,C.airport_tax,C.id_currency,C.visa"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_additional AS D ON A.id_product_tour_book = D.id_product_tour_book"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " WHERE A.id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}'");
     //  print_r($data_tour_book2);
        $total_nom = $this->global_models->get_query("SELECT nominal, id_currency"
              . " FROM product_tour_book_payment"
              . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}'"
              . " AND status = 2"); 
              
              foreach($total_nom AS $pr_nom){
               
                  if($pr_nom->id_currency == 1){
                      $tot_nom = $pr_nom->nominal;
                    }elseif($pr_nom->id_currency == 2){
                      $tot_nom = $pr_nom->nominal/$dropdown_rate[1];
                    }
                  $total_price_awal += $tot_nom;
                 
                }
        
              
      //  if($data_tour_book2[0]->DP > 0){
//           if($total_price_awal >= 0){
             if($data_payment2[0]->nominal > 0){
               $kirim_st = array("status" => 2);
              $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $data_payment2[0]->id_product_tour_book,"status" => 1), $kirim_st);
             }   
             
//           }
      //  }
        $additional = $this->global_models->get("product_tour_additional", array("id_product_tour_book" => $data_payment2[0]->id_product_tour_book));
            
            $tour_payment = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}' AND (status = 2 OR status = 0)"
          . " ORDER BY tanggal ASC");
           $total_person =($data_tour_book2[0]->total_person_adult_ttwin + $data_tour_book2[0]->total_person_child_twin + $data_tour_book2[0]->total_person_child_extra + $data_tour_book2[0]->total_person_child_no_bed + $data_tour_book2[0]->total_person_sgl_supp);
          
           $data_tax = $total_person * $data_tour_book2[0]->airport_tax;
           if($data_tour_book2[0]->id_currency == 1){
               $data_tax = $data_tax * $dropdown_rate[1];
           }elseif($data_tour_book2[0]->id_currency == 2){
               $data_tax = $data_tax;
           }
           $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}' AND status < '3' ");
         
        if($total_visa[0]->totl_visa > 0){
         if($data_tour_book2[0]->id_currency == 1){
               $total_visa = ($total_visa[0]->totl_visa * $data_tour_book2[0]->visa) * $dropdown_rate[1];
           }elseif($data_tour_book2[0]->id_currency == 2){
               $total_visa = $total_visa[0]->totl_visa * $data_tour_book2[0]->visa;
           }
        }else{
          $total_visa =0;
        }
        //  print_r($tour_payment);
           foreach($tour_payment AS $dp){
               
                  if($dp->id_currency == 1){
                      $tot_debit0_idr = $dp->nominal * $dropdown_rate[1];
                    }elseif($dp->id_currency == 2){
                      $tot_debit0_idr = $dp->nominal;
                    }
                  if($dp->pos == 1){
                    $debit = number_format($tot_debit0_idr, 0, ",", ".");
                    $kredit = "";
                    $total_debit += $tot_debit0_idr;
                  }
                  else{
                    $kredit = number_format($tot_debit0_idr, 0, ",", ".");
                    $debit = "";
                    $total_kredit += $tot_debit0_idr;
                  }
                 
                }
//             print_r($additional);die;
            foreach($additional AS $add){
                    $nom_ad1 = "";
                    $nom_ad2 = "";
                    if($add->id_currency == 1){
                        $nom_add0 = $add->nominal * $dropdown_rate[1];
                      }elseif($add->id_currency == 2){
                        $nom_add0 = $add->nominal;
                      }
                    if($add->pos == 1){
                      $nom_ad1 = $nom_add0;
                      $total_kredit2 += $nom_add0;
                    }else{
                      $nom_ad2 = $nom_add0;
                      $total_debit2 += $nom_add0;
                    }
        }
        if($data_tour_book2[0]->stnb_discount == 1){
                  $status_price = $book['discount'];
                  $tot_disc_price1 =  (($total_debit * $data_tour_book2[0]->discount)/100);
                }elseif($data_tour_book2[0]->stnb_discount == 2) {
                 $tot_disc_price1 = $data_tour_book2[0]->discount;
                }
                
         $discount_tambahan = $this->global_models->get_query("SELECT id_product_tour_discount_tambahan,discount_request,status_discount,status"
          . " FROM product_tour_discount_tambahan"
          . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}'"); 
//        $aa = $this->db->last_query();
        
           if($discount_tambahan[0]->discount_request){
                       if($discount_tambahan[0]->status_discount == 1){
                   
                    $tot_disc_tambahan =  (($total_debit * $discount_tambahan[0]->discount_request)/100);
                  }elseif($discount_tambahan[0]->status_discount == 2) {
                   $tot_disc_tambahan = $discount_tambahan[0]->discount_request;
                  }
           }
         
            
            if($data_payment2[0]->status_discount == 1){
                  $status_price = $book['discount'];
                  $tot_disc_price1 =  (($total_debit * $book['discount'])/100);
                }elseif($data_payment2[0]->status_discount == 1) {
                 $tot_disc_price1 = number_format($book['status_discount'],0,",",".");
                }
                $ppn = (1 * (($total_debit + $total_debit2 + $total_visa + $data_tax)-$tot_disc_price1)/100);
            $total_all =    (($total_debit + $total_debit2 + $ppn + $total_visa + $data_tax)- ($total_kredit2 + $total_kredit + $tot_disc_price1 + $tot_disc_tambahan) );
//    print $total_price_awal."<br>".$total_all; die;
      //  if($total_price_awal >= $total_all){
//            print $total_debit.".<br>".$total_debit2."..<br>".$ppn."...<br>".$data_tax."....<br>";
//            print $total_all; die;
      if($total_all <= 0){
          // $kirim_st = array("status" => 3);
           $this->db->query("UPDATE product_tour_customer SET status = 3 WHERE id_product_tour_book ={$data_payment2[0]->id_product_tour_book} AND (status = 1 OR status = 2)");
           // $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $data_payment2[0]->id_product_tour_book,"status" => 1,), $kirim_st);
       //  $wes =  $this->db->last_query();
      }
     // print $wes; die;
      if($status_data){
            $this->session->set_flashdata('success', 'Confirm');
            redirect("inventory/tour-book/payment");
      }else{
          $this->session->set_flashdata('notice', 'Gagal');
            redirect("inventory/tour-book/payment");
        }
      }else{
        $this->session->set_flashdata('notice', 'Code Not Found');
         redirect("inventory/tour-book/payment");
      }
    /*  $balance = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1  THEN nominal ELSE 0 END) AS debit"
            . " ,SUM(CASE WHEN pos = 2  THEN nominal ELSE 0 END) AS kredit"
            . " FROM product_tour_book_payment"
            . " WHERE id_product_tour_book = '{$book[0]->id_product_tour_book}'");
       print_r($balance); die;    */ 
      
    }
       
      $list = $this->global_models->get_query("SELECT COUNT(A.id_product_tour_book_payment) AS total"
        . " FROM product_tour_book_payment AS A"
        . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " LEFT JOIN users_channel AS C ON A.id_users = C.id_users"
        . " LEFT JOIN users_channel AS D ON A.update_by_users = D.id_users"
        . " WHERE 1=1 AND A.pos=2 "
        . " ORDER BY A.create_date ASC");
        
      $jumlah_list = $list[0]->total;
     //$jumlah_list = $this->global_models->get_field("product_tour", "count(id_product_tour)");
    
    $url_list = site_url("inventory/tour-book/ajax-payment/".$jumlah_list);
    $url_list_halaman = site_url("inventory/tour-book/ajax-halaman-payment/".$jumlah_list);

//      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    
    $foot = "<script type='text/javascript'>"

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
    
    $this->template->build('tour-book/payment-list', 
      array(
          'url'           => base_url()."themes/".DEFAULTTHEMES."/",
          'url_image'     => base_url()."themes/antavaya/",
          'menu'          => "grouptour/product-tour/-list",
          'data'          => $list,
          'foot'        => $foot,
          'title'         => lang("tour_payment_list"),
          
        ));
    $this->template
      ->set_layout("tableajax")
      ->build('tour-book/payment-list');
  
  }
  
  function ajax_payment($total = 0, $start = 0){
    
   
      $list = $this->global_models->get_query("SELECT A.id_product_tour_book_payment,A.id_currency,A.nominal,A.tanggal,A.status,B.kode,B.first_name,B.last_name,C.name AS name_tc,D.name AS name_konfirm"
        . " FROM product_tour_book_payment AS A"
        . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " LEFT JOIN users_channel AS C ON A.id_users = C.id_users"
        . " LEFT JOIN users_channel AS D ON A.update_by_users = D.id_users"
        . " WHERE 1=1 AND A.pos=2 "
        . " ORDER BY A.create_date ASC"
        . " LIMIT {$start}, 10");
        $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));        
   
  
    $status = array(
      1 => "<b>Draft</b>",
      2 => "<b>Confirm</b>",
      3 => "<b>Not Paid</b>",
      4 => "<b>Cancal</b>",
    );
    
    foreach ($list as $value) {
      $nom = number_format($value->nominal,2,".",",");
    $nominal = $dropdown[$value->id_currency]." ".$nom;
      $data2 = "<tr>"
        . "<td>{$value->tanggal}</td>"
        . "<td>{$value->name_tc}</td>"
        . "<td>{$value->name_konfirm}</td>"
        . "<td><a href='".site_url("inventory/tour-book/book-information/{$value->kode}")."'>{$value->kode}</a></td>"
        . "<td>{$value->first_name} {$value->last_name}</td>"
        . "<td>{$nominal}</td>"
        . "<td>{$status[$value->status]}</td>";
       if($value->status == 1){
        $data2 .= "<td>"
          . "<div class='btn-group'>"
          . "<button data-toggle='dropdown' class='btn btn-small dropdown-toggle'>Action<span class='caret'></span></button>"
          . "<ul class='dropdown-menu'>"
            . "<li><a href='".site_url("inventory/tour-book/payment/{$value->id_product_tour_book_payment}")."'>Confirm</a></li>"
          
        . "</td>";
       }else{
         $data2 .=  "<td></td>";
       }
      $data2 .= "</tr>";
   print $data2;
  }

    
    die;
  }
  
  function ajax_halaman_payment($total = 0, $start = 0){
    
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
  
  function book_information($book_code,$customer_code){
   
    $pst = $this->input->post(NULL);
//    print "<pre>";
//    print_r($pst);
//    print "</pre>";
//    die;
    $tour_book2 = $this->global_models->get("product_tour_book", array("kode" => $book_code));
    $tour_info2 = $this->global_models->get("product_tour_information", array("id_product_tour_information" => $tour_book2[0]->id_product_tour_information));
      
    if($pst['input_additional']){
      if(isset($pst['name_additional'])){
          $st = array(1 => "Penambahan", 2 => "Pengurangan");
          foreach($pst['name_additional'] AS $key_na => $val_na){
           $this->olah_code($kode_additional,"product_tour_additional");
            if($val_na){
              $addtl = array(
                "name"                    => $pst['name_additional'][$key_na],
                "id_currency"             => $pst['currency'][$key_na],
                "id_user_pengaju"         => $this->session->userdata("id"),
                "id_product_tour_book"    => $tour_book2[0]->id_product_tour_book,
                "nominal"                 => $pst['nominal'][$key_na],
                "status"                  => 1,
                "pos"                     => $pst['pos'][$key_na],
                "pajak"                   => $pst['pajak'][$key_na],
                "kode"                    => $kode_additional,
                "create_by_users"         => $this->session->userdata("id"),
                "create_date"             => date("Y-m-d H:i:s")
              );
               $id_addnal = $this->global_models->insert("product_tour_additional", $addtl);
               
               $kirim_log_add = array(
                  "id_product_tour_book"      => $tour_book2[0]->id_product_tour_book,
                  "id_users"                  => $this->session->userdata("id"),
                  "name"                      => $this->session->userdata("name"),
                  "tanggal"                   => date("Y-m-d H:i:s"),
                  "status"                    => 2,
                  "note"                      => $pst['name_additional'][$key_na]." {$st[$addtl['pos']]} ".number_format($pst['nominal'][$key_na]),
                  "create_by_users"         => $this->session->userdata("id"),
                  "create_date"             => date("Y-m-d H:i:s")
               );
               $id_product_tour_log_request_additional = $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
            $flag .= $pst['nominal'][$key_na];
               $note_add .= $pst['name_additional'][$key_na]." {$st[$addtl['pos']]} ".number_format($pst['nominal'][$key_na])."<br>";
			}
        }
       
	   if($flag != ""){
           $this->email_input_additional($book_code,$note_add);
        }
      
        if($id_addnal){
        $this->session->set_flashdata('success', 'Input Additional');
            redirect("inventory/tour-book/book-information/{$book_code}");
          }else{
          $this->session->set_flashdata('notice', 'gagal');
            redirect("inventory/tour-book/book-information/{$book_code}");
        }
      }
    }
    
    if($pst['beban_biaya']){
      
      $add =array("name"              => "Potongan Biaya Customer Cancel",
                    "id_currency"     => $pst['id_currency'],
              "id_product_tour_book"  => $tour_book2[0]->id_product_tour_book,
                    "pos"             => 2,
                    "nominal"         => $pst['nom_add'], 
                    "create_by_users" => $this->session->userdata("id"),
                    "create_date"     => date("Y-m-d"));
        
        $id_product_tour_additional = $this->global_models->insert("product_tour_additional", $add);
        if($id_product_tour_additional){
        $this->session->set_flashdata('success', 'Success');
        redirect("inventory/tour-book/book-information/{$book_code}");
        }else{
          $this->session->set_flashdata('notice', 'Gagal');
          redirect("inventory/tour-book/book-information/{$book_code}");
        }
    }
    
    if($customer_code){
      
      $data_customer2 = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $tour_book2[0]->id_product_tour_book, "kode" => $customer_code));
    
      if($data_customer2[0]->status == 5){
        
      $cust_tour = $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $data_customer2[0]->id_product_tour_customer), array("status" => 4));
      }
      if($cust_tour > 0){
        
        $disc_tetap = $tour_info2[0]->discount_tetap;
        $status_disc_tetap = $tour_info2[0]->stnb_discount_tetap;
        if($data_customer2[0]->type == 1){
          if($status_disc_tetap == 1){
            $nominal_disc = (($disc_tetap * $tour_info2[0]->adult_triple_twin)/100);
          }elseif($status_disc_tetap == 2){
            $nominal_disc = $disc_tetap;
          }
          $data_nominal =  ($tour_info2[0]->adult_triple_twin - $nominal_disc) + $tour_info2[0]->airport_tax; 
        }elseif($data_customer2[0]->type == 2){
          $data_nominal = ($tour_info2[0]->child_twin_bed - $nominal_disc) + $tour_info2[0]->airport_tax;
        }elseif($data_customer2[0]->type == 3){
          $data_nominal = ($tour_info2[0]->child_extra_bed - $nominal_disc) + $tour_info2[0]->airport_tax;
        }elseif($data_customer2[0]->type == 4){
          $data_nominal = ($tour_info2[0]->child_no_bed - $nominal_disc) + $tour_info2[0]->airport_tax;
        }elseif($data_customer2[0]->type == 5){
          $data_nominal = ($tour_info2[0]->sgl_supp - $nominal_disc) + $tour_info2[0]->airport_tax;
        }
        $add =array("name"            => "Customer Cancel [{$data_customer2[0]->kode}]",
                    "id_currency"     => $tour_info2[0]->id_currency,
              "id_product_tour_book"  => $tour_book2[0]->id_product_tour_book,
                    "pos"             => 1,
                    "nominal"         => $data_nominal, 
                    "create_by_users" => $this->session->userdata("id"),
                    "create_date"     => date("Y-m-d"));
        
        $id_product_tour = $this->global_models->insert("product_tour_additional", $add);
        $this->session->set_flashdata('success', 'Customer Cancel');
            redirect("inventory/tour-book/book-information/{$book_code}");
          }else{
          $this->session->set_flashdata('notice', 'gagal');
            redirect("inventory/tour-book/book-information/{$book_code}");
        }
      
    }
    
    if($pst['committed_book']){
     $product_tour_book2 = $this->global_models->get("product_tour_book", array("kode" => $pst['code_book']));
          
           
         $data_committed2 = $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book2[0]->id_product_tour_book), array("status" => 2));
     //  $data_committed2 =  $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $status_payment[0]->id_product_tour_book_payment), array("status" => 2));
         $cust_tour8 = $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $product_tour_book2[0]->id_product_tour_book,"status" => 1), array("status" => 2));
     
       if($data_committed2){
            $this->session->set_flashdata('success', 'Committed Book');
            redirect("inventory/tour-book/book-information/{$book_code}");
          }else{
          $this->session->set_flashdata('notice', 'gagal');
            redirect("inventory/tour-book/book-information/{$book_code}");
        }
    }
    
    if($pst['approve_additional']){
      $kirim = array(
            "status_additional_request"         => 2,
            "update_by_users" => $this->session->userdata("id"),
        );
        $idadd_req = $this->global_models->update("product_tour_book", array("kode" => $pst['code_book']),$kirim);   
        if($idadd_req){
            $this->session->set_flashdata('success', 'Additional Request Disetujui');
            redirect("inventory/tour-book/book-information/{$book_code}");
          }else{
          $this->session->set_flashdata('notice', 'gagal');
            redirect("inventory/tour-book/book-information/{$book_code}");
        }
    }
    
    if($pst['request_additional_tour']){
        $product_tour_book2 = $this->global_models->get("product_tour_book", array("kode" => $pst['id_detail']));
      
	    
      $kirim = array(
            "id_product_tour_book"        => $product_tour_book2[0]->id_product_tour_book,
            "name"                        => $this->session->userdata("name"),
            "id_users"                    => $this->session->userdata("id"),
            "note"                        => $pst['note_additional_tour'],
            "tanggal"                     => date("Y-m-d H:i:s"),
              "create_by_users"           => $pst['create_by_users'],
            "create_date"                 => date("Y-m-d"),
        );

        $id_log_history_request_discount = $this->global_models->insert("product_tour_log_request_additional", $kirim);
		
		$this->email_chat_additional($pst['id_detail'],$pst['note_additional_tour']);
		
      if($id_log_history_request_discount){
            $this->session->set_flashdata('success', 'Additional Request Disetujui');
            redirect("inventory/tour-book/book-information/{$book_code}");
          }else{
          $this->session->set_flashdata('notice', 'gagal');
            redirect("inventory/tour-book/book-information/{$book_code}");
        }
    }
    
    if($pst['reject_additional']){
      $kirim = array(
            "status_additional_request"         => 3,
            "update_by_users" => $this->session->userdata("id"),
        );
        $idadd_req = $this->global_models->update("product_tour_book", array("kode" => $pst['code_book']),$kirim);   
        if($idadd_req){
            $this->session->set_flashdata('success', 'Additional Request Dibatalkan');
            redirect("inventory/tour-book/book-information/{$book_code}");
          }else{
          $this->session->set_flashdata('notice', 'gagal');
            redirect("inventory/tour-book/book-information/{$book_code}");
        }
    }
    
    if($pst['approve']){
      $kirim = array(
            "status"         => 1,
            "update_by_users" => $this->session->userdata("id"),
        );
        $iddisc_tambahan = $this->global_models->update("product_tour_discount_tambahan", array("id_product_tour_discount_tambahan" => $pst['id_detail']),$kirim);   
        if($iddisc_tambahan){
            $this->session->set_flashdata('success', 'Discount Request Disetujui');
            redirect("inventory/tour-book/book-information/{$book_code}");
          }else{
          $this->session->set_flashdata('notice', 'Gagal');
            redirect("inventory/tour-book/book-information/{$book_code}");
        }
    }
    if($pst['reject']){
      $kirim = array(
            "status"         => 3,
            "update_by_users" => $this->session->userdata("id"),
        );
        $iddisc_tambahan = $this->global_models->update("product_tour_discount_tambahan", array("id_product_tour_discount_tambahan" => $pst['id_detail']),$kirim);       
    if($iddisc_tambahan){
            $this->session->set_flashdata('success', 'Discount Request Dibatalkan');
            redirect("inventory/tour-book/book-information/{$book_code}");
          }else{
          $this->session->set_flashdata('notice', 'Gagal');
            redirect("inventory/tour-book/book-information/{$book_code}");
        }
    }
     
    $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
    $sub_category = array(1 => "Eropa", 2 => "Africa", 3 => "America", 4 => "Australia", 5 => "Asia");
//    $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));  
    $dropdown = array("2" => "IDR");
    $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
  
    $product_tour_book = $this->global_models->get_query("SELECT A.*,A.adult_triple_twin AS total_person_adult_ttwin,A.child_twin_bed AS total_person_child_twin,A.child_extra_bed AS total_person_child_extra,A.child_no_bed AS total_person_child_no_bed,A.sgl_supp AS total_person_sgl_supp,A.additional_request,A.status_additional_request"
        . " , B.title, B.sub_title, B.summary, B.file_thumb, B.category, B.sub_category, B.file, B.kode AS tour_code, B.note AS text, B.id_store, B.id_store_region"
        . " , C.kode AS tour_information_code, C.start_date,C.at_airport_date, C.end_date, C.available_seat, C.adult_triple_twin, C.child_twin_bed, C.child_extra_bed, C.child_no_bed,C.sgl_supp,C.airport_tax,C.id_currency,C.visa"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_additional AS D ON A.id_product_tour_book = D.id_product_tour_book"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " WHERE A.kode = '{$book_code}'");
      if($product_tour_book[0]->id_product_tour_book > 0){
        
        if($pst['request_dicount']){
        if($pst["discount"]){
          $this->olah_code($kode, "log_request_discount");
          $kirim_book = array(
            "id_product_tour_book"        => $product_tour_book[0]->id_product_tour_book,
            "name"                        => $this->session->userdata("name"),
            "kode"                        => $kode,
            "discount"                    => $pst['discount'],
            "status"                      => 3,
            "tanggal"                     => date("Y-m-d H:i:s"),
            "note"                        => $pst['note'],
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
          );
          $id_log_request_discount = $this->global_models->insert("log_request_discount", $kirim_book);

          if($id_log_request_discount){
            $this->session->set_flashdata('success', 'Request Discount Diajukan');
            redirect("inventory/tour-book/book-information/{$book_code}");
          }
        }else{
          $this->session->set_flashdata('notice', 'Nominal Discount Tidak boleh kosong');
            redirect("inventory/tour-book/book-information/{$book_code}");
        }
      }elseif($pst['approval']){
        $data_log1 = $this->global_models->get_query("
          SELECT 	discount
          FROM log_request_discount
          WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}' AND status = '2'
          ORDER BY id_log_request_discount DESC 
          LIMIT 0,1");

          $this->olah_code($kode, "log_request_discount");
          $kirim_book = array(
            "id_product_tour_book"        => $tour_book[0]->id_product_tour_book,
            "name"                        => $this->session->userdata("name"),
            "kode"                        => $kode,
            "discount"                    => $data_log1[0]->discount,
            "status"                      => 4,
            "tanggal"                     => date("Y-m-d H:i:s"),
            "note"                        => $pst['note'],
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
          );
          $id_log_request_discount = $this->global_models->insert("log_request_discount", $kirim_book);
          if($id_log_request_discount){
            $this->session->set_flashdata('success', 'Request Discount Diajukan');
            redirect("inventory/tour-book/book-information/{$book_code}");
          }
      }
      
        $passenger = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
        $additional = $this->global_models->get("product_tour_additional", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
        $note_type = array(
          1 => "Adult Triple Twin",
          2 => "Child Twin Bed",
          3 => "Child Extra Bed",
          4 => "Child No Bed",
          5 => "SGL SUPP"
        );
        
        foreach($passenger AS $psng){
          if($psng->status == 1){
            $status_cust = "Book";
          }elseif($psng->status == 2){
            $status_cust = "Deposit";
          }elseif($psng->status == 3){
            $status_cust = "Lunas";
          }elseif($psng->status == 4){
            $status_cust = "Cancel";
          }elseif($psng->status == 5){
            $status_cust = "[Cancel] Waiting Approval";
          }
          $passenger_tour[] = array(
            "first_name"            => $psng->first_name,
            "last_name"             => $psng->last_name,
            "tanggal_lahir"         => $psng->tanggal_lahir,
            "tempat_tanggal_lahir"         => $psng->tempat_tanggal_lahir,
            "type"                  => array("code" => $psng->type, "desc" => $note_type[$psng->type]),
            "room"                  => $psng->room,
            "no_passport"           => $psng->passport,
            "place_of_issued"       => $psng->place_of_issued,
            "date_of_issued"        => $psng->date_of_issued,
            "date_of_expired"       => $psng->date_of_expired,
            "telphone"              => $psng->telphone,
            "status"                => $status_cust,
            "customer_code"         => $psng->kode,
            "id_customer"           => $psng->id_product_tour_customer
          );
        }
        
        foreach($additional AS $add){
          $dt_pos = array(1 => "Penambahan Biaya",
                  2 => "Pengurangan Biaya");
          
//          $dt_add_status = 
          $additional_tour[] = array(
            "name_additional"            => $add->name,
            "nominal_additional"         => $add->nominal,
            "user_pengaju"               => $this->global_models->get_field("m_users", "name", array("id_users" => $add->id_user_pengaju)),
            "user_approval"               => $this->global_models->get_field("users_channel", "name", array("id_users" => $add->id_user_approval)),            
            "status"                     => $add->status,
            "pos"                         => $dt_pos->pos
          );
        }
        $discount_tambahan = $this->global_models->get_query("SELECT id_product_tour_discount_tambahan,discount_request,status_discount,status,create_by_users,id_user_approval"
          . " FROM product_tour_discount_tambahan"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"); 
        
           $user_request = $this->global_models->get_field("users_channel", "name", array("id_users" => $discount_tambahan[0]->create_by_users));
            $user_approval = $this->global_models->get_field("users_channel", "name", array("id_users" => $discount_tambahan[0]->id_user_approval));
     
          if($product_tour_book[0]->stnb_discount == 1){
            $stnb_discount = "Persen";
          }elseif($product_tour_book[0]->stnb_discount == 2){
            $stnb_discount = "Nominal";
          }
          if($product_tour_book[0]->id_currency > 0){
            $dt_currency = $dropdown[$product_tour_book[0]->id_currency];
          }
          $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}' AND status < '3' ");
        
        $log_request_discount_additional = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_log_request_additional"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
          . " ORDER BY id_product_tour_log_request_additional ASC");
         $data_log_additional = "";
          
          if($log_request_discount_additional){
            foreach($log_request_discount_additional AS $lrd){
         
          $data_log_additional[] = array(
            "name"            => $lrd->name,
            "tanggal"         => $lrd->tanggal,
            "note"            => $lrd->note
          );
        }
          }
          
        $book = array(
          "code"                                => $product_tour_book[0]->kode,
          "first_name"                          => $product_tour_book[0]->first_name,
          "last_name"                           => $product_tour_book[0]->last_name,
          "telphone"                            => $product_tour_book[0]->telphone,
          "email"                               => $product_tour_book[0]->email,
          "address"                             => $product_tour_book[0]->address,
          "tanggal"                             => $product_tour_book[0]->tanggal,
          "status"                              => $product_tour_book[0]->status,
          "agent"                               => $product_tour_book[0]->id_users,
          "room"                                => $product_tour_book[0]->room,
          "discount"                            => $product_tour_book[0]->discount,
          "total_visa"                          => $total_visa[0]->totl_visa,
          "status_discount"                     => $stnb_discount,
          "note_additional"                     => $product_tour_book[0]->additional_request,
          "status_additional_request"            => $product_tour_book[0]->status_additional_request,
          "discount_tambahan"                   => $discount_tambahan,
          "user_request_discount_tambahan"      => $user_request,
          "user_approval_discount_tambahan"      => $user_approval,
          "jumlah_person_adult_triple_twin"             => $product_tour_book[0]->total_person_adult_ttwin,
          "jumlah_person_child_twin"                    => $product_tour_book[0]->total_person_child_twin,
          "jumlah_person_child_extra"                   => $product_tour_book[0]->total_person_child_extra,
          "jumlah_person_child_no_bed"                  => $product_tour_book[0]->total_person_child_no_bed,
          "jumlah_person_sgl_supp"                      => $product_tour_book[0]->total_person_sgl_supp,
          "passenger"                                   => $passenger_tour,
          "additional"                                  => $additional_tour,
          'log_request_additional'                      => $data_log_additional,  
        );
        
        if($product_tour_book[0]->at_airport_date != "0000-00-00" AND $product_tour_book[0]->at_airport_date != ""){
            $date_start = $product_tour_book[0]->at_airport_date;
        }else{
            $date_start = $product_tour_book[0]->start_date;
        }  
        
        $information = array(
          "code"              => $product_tour_book[0]->tour_information_code,
          "start_date"        => $date_start,
          "end_date"          => $product_tour_book[0]->end_date,
          "seat"              => $product_tour_book[0]->available_seat,
          "price"             => array(
              "adult_triple_twin"   => $product_tour_book[0]->adult_triple_twin,
              "child_twin_bed"      => $product_tour_book[0]->child_twin_bed,
              "child_extra_bed"     => $product_tour_book[0]->child_extra_bed,
              "child_no_bed"        => $product_tour_book[0]->child_no_bed,
              "sgl_supp"            => $product_tour_book[0]->sgl_supp,
              "tax_and_insurance"   => $product_tour_book[0]->airport_tax,
              "visa"                  => $product_tour_book[0]->visa,
              "currency"            => $dt_currency)
           
        );
        
        $tour = array(
          "code"              => $product_tour_book[0]->tour_code,
          "id_store_region"          => $product_tour_book[0]->id_store_region,
          "title"             => $product_tour_book[0]->title,
          "sub_title"         => $product_tour_book[0]->sub_title,
          "summary"           => $product_tour_book[0]->summary,
          "category"          => array("id" => $product_tour_book[0]->category, "name" => $category[$product_tour_book[0]->category]),
          "sub_category"      => array("id" => $product_tour_book[0]->sub_category, "name" => $sub_category[$product_tour_book[0]->sub_category]),
          "information"       => $information
        );
        
        $tour_payment = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'  AND tampil IS NULL"
          . " ORDER BY pos ASC");
        foreach($tour_payment AS $tp){
          $payment[] = array(
            "agent"           => $tp->id_users,
            "nominal"         => $tp->nominal,
            "tanggal"         => $tp->tanggal,
            "pos"             => $tp->pos,
            "status"          => $tp->status,
            "status_payment"  => $tp->payment,
            "currency"        => $tp->id_currency,
               "note"                    => $tp->note
          );
        }
        
        $customer_cancel = $this->global_models->get_query("SELECT C.adult_triple_twin,C.child_twin_bed,C.child_extra_bed,C.child_no_bed,C.sgl_supp,C.id_currency,"
        . "B.type,B.status AS status_customer,B.first_name,B.last_name,B.kode AS customer_code"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_customer AS B ON B.id_product_tour_book = A.id_product_tour_book"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " WHERE A.kode = '{$book_code}' AND B.status > 3");
//      $log = $this->global_models->get_query("SELECT *"
//          . " FROM log_request_discount"
//          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
//          . " ORDER BY id_log_request_discount ASC"); 
      }
      
  
      $foot = "
        <script>
          
        function tambah_items(){
        $.ajax({
      type : 'POST',
      url : '".site_url("ajax/add-row-product-tour")."',
      dataType : 'html',
      success: function(data) {
            $('#tambah-items').append(data);
            $( '.start_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
              $( '.end_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
      },
      
    });
         
        }       
        </script>;
        ";
  $foot .= "<script>"
     ."function tambah_items_additional(){"
      ."var num = $('.number_additional').length;"
      ."var dataString2 = 'name='+ num;"
      ."$.ajax({"
      ."type : 'POST',"
      ."url : '".site_url("inventory/tour-book/ajax-add-row-additional")."',"
      ."data: dataString2,"
      ."dataType : 'html',"
      ."success: function(data) {"
            ."$('#tambah-additional').append(data);"
      ."},"
    ."});"
        ."}"
      ."</script>";
  
  $foot .= "  <script>
   function test(id){
   $.post('".site_url("inventory/ajax/customer-detail")."', {customer_kode: id}, function(data){
            var hasil = $.parseJSON(data);
            $('#name').val(hasil.name);
            $('#telp').val(hasil.telp);
            $('#tmpt_tgl_lahir').val(hasil.tmpt_tgl_lahir);
            $('#tgl_lahir').val(hasil.tgl_lahir);
            $('#passport').val(hasil.passport);
            $('#place_issued').val(hasil.place_issued);
            $('#date_issued').val(hasil.date_issued);
            $('#date_expired').val(hasil.date_expired);
            $('#type').val(hasil.type);
          });
    };
        $(document).on('click', '.tour-copy', function(evt){
          $.post('".site_url("inventory/ajax/customer-detail")."', {customer_kode: $(this).attr('isi')}, function(data){
            var hasil = $.parseJSON(data);
            $('#name').val(hasil.name);
            $('#telp').val(hasil.telp);
            $('#tmpt_tgl_lahir').val(hasil.tmpt_tgl_lahir);
            $('#tgl_lahir').val(hasil.tgl_lahir);
            $('#passport').val(hasil.passport);
            $('#place_issued').val(hasil.place_issued);
            $('#date_issued').val(hasil.date_issued);
            $('#date_expired').val(hasil.date_expired);
            $('#type').val(hasil.type);
          });
        });
        </script>";
//  $this->debug($tour, true);
    $this->template->build('tour-book/book-information', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
            'tour'              => $tour,
            'book'              => $book,
            'payment'           => $payment,
            'cust_cancel'       => $customer_cancel,
            'dropdown'          => $dropdown,
            'dropdown_rate'     => $dropdown_rate,
            'additional'  => $additional,
            'title'       => lang("Book {$book_code}"),
            'breadcrumb'  => array(
                "product_tour"  => "inventory/tour-book"
              ),
            'foot'        => $foot,
             // 'css'        => $css
          ));
    $this->template
      ->set_layout('default')
      ->build('tour-book/book-information');
  }
  
  
  public function add_product_tour($id_product_tour = 0){
      
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("product_tour", array("id_product_tour" => $id_product_tour));
      if($detail[0]->id_product_tour){
          $info = $this->global_models->get_query("SELECT A.id_product_tour_information,A.id_product_tour, A.start_date, A.end_date,A.available_seat,A.adult_triple_twin,A.child_twin_bed,A.child_extra_bed,A.child_no_bed,A.sgl_supp,A.airport_tax,A.kode"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.id_product_tour = '{$detail[0]->id_product_tour}'");
      }
     
      
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />";
      $foot = "
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>
       
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script>
        <script type='text/javascript'>
            $(function() {
              CKEDITOR.replace('editor2');
              $( '.start_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
              
              $( '.end_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
            });
        </script>
        
        <script type='text/javascript'>
       $(document).ready(function () {
            $('#datetimepicker').datepicker();     // Id may be different here, you need to inspect element and check the ID.
        })
    </script>
        <script>
          
        function tambah_items(){
        $.ajax({
      type : 'POST',
      url : '".site_url("ajax/add-row-product-tour")."',
      dataType : 'html',
      success: function(data) {
            $('#tambah-items').append(data);
            $( '.start_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
              $( '.end_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
      },
      
    });
         
        }       
        </script>;
        ";
      
      $this->template->build("product-tour/add-product-tour", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/product-tour',
              'title'       => lang("product_tour"),
             'detail'      => $detail,
            'info'          => $info,
              'breadcrumb'  => array(
                    "product_tour"  => "inventory/product-tour"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("product-tour/add-product-tour");
    }
    else{
      $pst = $this->input->post(NULL);
      
      $config['upload_path'] = './files/antavaya/product_tour/';
      $config['allowed_types'] = '*';
      $config['max_width']  = '1000';
      $config['max_height']  = '1000';

      $this->load->library('upload', $config);
      
      if($_FILES['file']['name']){
        if (  $this->upload->do_upload('file')){
          $data = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("inventory/product-tour/add-product-tour/".$id_product_tour)."'>Back</a>";
          die;
        }
      }
      
      if($_FILES['file_thumb']['name']){
        if (  $this->upload->do_upload('file_thumb')){
          $data_thumb = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("inventory/product-tour/add-product-tour/".$id_product_tour)."'>Back</a>";
          die;
        }
      }
      
//      $this->debug($data_thumb, true);
      if($pst['id_detail']){
        $kirim = array(
            "title"           => $pst['title'],
            "sub_title"       => $pst['sub_title'],
            "summary"         => $pst['summary'],
            "category"        => $pst['category'],
            "sub_category"    => $pst['sub_category'],
            "note"            => $pst['note'],
            "update_by_users" => $this->session->userdata("id"),
        );
        if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
        if($data_thumb['upload_data']['file_name']){
          $kirim['file_thumb'] = $data_thumb['upload_data']['file_name'];
        }
       
         $this->global_models->update("product_tour", array("id_product_tour" => $pst['id_detail']),$kirim);
         foreach ($pst['start_date'] as $key => $start_date) {
                if($start_date){
                  if($pst['kode'][$key]){
                    $kode_info = $pst['kode'][$key];
                  }else{
                    $this->olah_tour_code_information($kode_info);
                    $kode_info = $kode_info;
                  }
                $kirim_info = array(
              "id_product_tour"                         => $id_product_tour,
                "id_product_tour_information"           => $pst['id_product_tour_information'][$key],
                "start_date"                            => $pst['start_date'][$key],
                "kode"                                  => $kode_info,  
                "end_date"                              => $pst['end_date'][$key],
                "available_seat"                        => $pst['available_seat'][$key],
                "adult_triple_twin"                     => $pst['adult_triple_twin'][$key],
                "child_twin_bed"                        => $pst['child_twin_bed'][$key],
                "child_extra_bed"                       => $pst['child_extra_bed'][$key],
                "child_no_bed"                          => $pst['child_no_bed'][$key],  
                "sgl_supp"                              => $pst['sgl_supp'][$key],
                "airport_tax"                           => $pst['airport_tax'][$key],
              "create_by_users"                         => $this->session->userdata("id"),
              "create_date"                             => date("Y-m-d H:i:s")
            );
            
            $else_kirim_info = array(
            "id_product_tour"                      => $id_product_tour,
            "id_product_tour_information"           => $pst['id_product_tour_information'][$key],
            "start_date"                            => $pst['start_date'][$key],
              "kode"                                  => $kode_info, 
            "end_date"                              => $pst['end_date'][$key],
            "available_seat"                        => $pst['available_seat'][$key],
            "adult_triple_twin"                     => $pst['adult_triple_twin'][$key],
            "child_twin_bed"                        => $pst['child_twin_bed'][$key],
            "child_extra_bed"                       => $pst['child_extra_bed'][$key],
            "child_no_bed"                          => $pst['child_no_bed'][$key],  
            "sgl_supp"                              => $pst['sgl_supp'][$key],
            "airport_tax"                           => $pst['airport_tax'][$key],
            "update_by_users"                       => $this->session->userdata("id"),
            );
            $this->global_models->update_duplicate("product_tour_information", $kirim_info, $else_kirim_info);
          }
        }
        
      }
      else{
        $this->olah_tour_code($kode);
        $kirim = array(
            "id_store"        => $this->session->userdata('store'),
            "title"           => $pst['title'],
            "kode"            => $kode,
            "sub_title"       => $pst['sub_title'],
            "summary"         => $pst['summary'],
            "category"        => $pst['category'],
            "sub_category"    => $pst['sub_category'],
            "note"            => $pst['note'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
        if($data_thumb['upload_data']['file_name']){
          $kirim['file_thumb'] = $data_thumb['upload_data']['file_name'];
        }
       
        $id_product_tour = $this->global_models->insert("product_tour", $kirim);
        
        foreach ($pst['start_date'] as $key => $start_date) {
                if($start_date){
                $this->olah_tour_code_information($kode_info);
            $kirim_info = array(
                "kode"                                  => $kode_info,
                "id_product_tour"                       => $id_product_tour,
                "start_date"                            => $pst['start_date'][$key],
                "end_date"                              => $pst['end_date'][$key],
                "available_seat"                        => $pst['available_seat'][$key],
                "adult_triple_twin"                     => $pst['adult_triple_twin'][$key],
                "child_twin_bed"                        => $pst['child_twin_bed'][$key],
                "child_extra_bed"                       => $pst['child_extra_bed'][$key],
                "child_no_bed"                          => $pst['child_no_bed'][$key],  
                "sgl_supp"                              => $pst['sgl_supp'][$key],
                "airport_tax"                           => $pst['airport_tax'][$key],       
                "create_by_users"                       => $this->session->userdata("id"),
                "create_date"                           => date("Y-m-d H:i:s")
                );
                  //  print_r($kirim_info); die;
                $this->global_models->insert("product_tour_information", $kirim_info);
            }
        }
        
      }
      if($id_product_tour){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/product-tour");
    }
  }
  private function olah_code(&$kode, $table){
    $this->load->helper('string');
    $kode = random_string('alnum', 10);
    $cek = $this->global_models->get_field($table, "id_{$table}", array("kode" => $kode));
    if($cek > 0){
      $this->olah_tour_code($kode, $table);
    }
  }
  function ajax_add_row_additional(){
     $pos = $_POST['name'];
     $pos++;
//    $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));     
    
    $dropdown = array(2 => "IDR");
    $pos2 = array(1 => "Penambahan Biaya",
                  2 => "Pengurangan Biaya");
    $html = "<div class='box-body col-sm-12'>
      <div class='control-group'>
        <label><div class='number_additional'>Name {$pos}</div></label>";
        $html .= $this->form_eksternal->form_input('name_additional[]', "", 'class="form-control input-sm" placeholder="Name Additional"');
      
      $html .= "</div>"
    ."</div>";
      
    $html .= "<div class='box-body col-sm-12'>
      <div class='control-group'>
        <label>Nominal {$pos}</label><br>";
        $html .= $this->form_eksternal->form_dropdown('pos[]', $pos2, "", 'style="width:22%" class="form-control" placeholder="pos"')." ";
    $html .= $this->form_eksternal->form_dropdown('currency[]', $dropdown, "", 'style="width:13%" class="form-control" placeholder="Currency"')." ";
    $html .= $this->form_eksternal->form_input('nominal[]', "", 'style="width:60%" class="form-control input-sm" placeholder="Nominal"');
    $html .= "</div>"
    ."</div><br><br><br><br>";
    
    print $html;
    die;
  }
  
  function email_chat_additional($kode_book,$note){
      
        $product_tour_book2 = $this->global_models->get("product_tour_book", array("kode" => $kode_book));
	$own = $this->global_models->get("users_channel", array("id_users" => $product_tour_book2[0]->id_users));
       
	   $nama_tc  = $this->global_models->get_field("m_users", "name", array("id_users" => $this->session->userdata("id")));
        $id_store_region  = $this->global_models->get_field("m_users", "id_store_region", array("id_users" => $this->session->userdata("id")));
        $nama_store = $this->global_models->get_field("store_region", "title", array("id_store_region" => $id_store_region));
       
         
        $link     = "http://".$_SERVER['HTTP_HOST']."/store/grouptour/product-tour/book-information".$kode_book;
        $link_url = "<a href='{$link}'>{$kode_book}</a>";
        $this->load->library('email');
        $this->email->initialize($this->global_models->email_conf());
        $this->email->from('no-reply@antavaya.com', 'Administrator AV TMS');
        $this->email->to($own[0]->email);
        $this->email->bcc('hendri.prasetyo@antavaya.com');
        $this->email->subject('Notifikasi Chat di Request Additional dari User '.$nama_tc." [".$nama_store."]");
        $html = "<html>
            <body>
              Dear Bookers  {$own[0]->name}<br><br>
              
        User Tour Operation dari <b>{$nama_tc} [{$nama_store}]</b> Mengirimkan pesan chat di Request Additional dari kode booking customer <b>[{$link_url}]</b><br>
				
	pesan chat di request Additional :<br>
         <b>{$note}</b><br><br>
       
            </body>
          </html>";
         
        $this->email->message($html);
        $this->email->send();
//         print $this->email->print_debugger(); die;
           
  }
  
  function email_input_additional($book_code,$note_add){
      
        $product_tour_book2 = $this->global_models->get("product_tour_book", array("kode" => $book_code));
        $own = $this->global_models->get("users_channel", array("id_users" => $product_tour_book2[0]->id_users));
        
        $nama_tc  = $this->global_models->get_field("m_users", "name", array("id_users" => $this->session->userdata("id")));
        $id_store_region  = $this->global_models->get_field("m_users", "id_store_region", array("id_users" => $this->session->userdata("id")));
        $nama_store = $this->global_models->get_field("store_region", "title", array("id_store_region" => $id_store_region));
        $link     = "http://".$_SERVER['HTTP_HOST']."/store/grouptour/product-tour/book-information".$book_code;
         $link_url = "<a href='{$link}'>{$book_code}</a>";
        if($own[0]->email != ""){
            $this->load->library('email');
        $this->email->initialize($this->global_models->email_conf());
        $this->email->from('no-reply@antavaya.com', 'Administrator AV TMS');
        $this->email->to($own[0]->email);
        
        $this->email->subject('Notifikasi Biaya Additional dari User '.$nama_tc." [".$nama_store."]");
        $html = "<html>
            <body>
              Dear Bookers  {$own[0]->name}<br><br>
              
             User Tour Operation <b>{$nama_tc} [{$nama_store}]</b> Membuat Biaya Additional dari kode booking customer <b>[{$link_url}]</b><br>
				
		Biaya additional yang telah dibuat :<br>
         <b>{$note_add}</b><br><br>
             Bookers dapat menginfokan ke customer untuk biaya yang telah dibuat oleh Tour Operation,<br>
             Apabila customer setuju dengan biaya tersebut Bookers dapat Approve Additional tersebut,<br>
             apabila customer membatalkan addational tersebut Bookers dapat Reject additionalnya.
            </body>
          </html>";
         
        $this->email->message($html);
        $this->email->send();
        }
        
//         print $this->email->print_debugger(); die;
           
  }
  
  function group_status(){
      
   $pst = $this->input->post(NULL);
   
    $where = "";
       if($pst['code']){
          $where .= " AND LOWER(B.kode) LIKE '%".strtolower($pst['code'])."%' OR LOWER(A.kode) LIKE '%".strtolower($pst['code'])."%'"; 
      }
      
      if($pst['start_date'] || $pst['$end_date']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['start_date']} 00:00:00' AND '{$pst['end_date']} 23:59:59')";
      }
      
      $book = $this->global_models->get_query("SELECT A.*, B.start_date, B.end_date, B.kode AS code_info,B.airport_tax AS price_tax_insurance, C.title, C.kode AS code_tour"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " WHERE 1=1 $where "
        . " ORDER BY tanggal DESC");
        if($book){
        $additional_tour = "";
        foreach($book AS $ky => $bk){
        $price_tax_inusrance =  ($bk->adult_triple_twin + $bk->child_twin_bed + $bk->child_extra_bed + $bk->child_no_bed + $bk->sgl_supp) * $bk->price_tax_insurance;
          $balance = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE 0 END) AS debit"
            . " ,SUM(CASE WHEN pos = 2 THEN nominal ELSE 0 END) AS kredit"
            . " FROM product_tour_book_payment"
            . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
          
        //  $dp = $this->global_models->get_field("product_tour_information", "dp", array("id_product_tour_information" => $bk->id_product_tour_information));
          $nominal_pertama = $this->global_models->get_field("product_tour_book_payment", "nominal", array("pos" => 1, "status" => 1, "id_product_tour_book" => $bk->id_product_tour_book));
        
        $additional = $this->global_models->get_query("SELECT name,nominal,id_currency,pos "
        . " FROM product_tour_additional"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
         $passenger_book = $this->global_models->get_query("SELECT count(kode) as book"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='1' ");
        
        $passenger_commit = $this->global_models->get_query("SELECT count(kode) as commit_book"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='2' ");
       
        $passenger_lunas = $this->global_models->get_query("SELECT count(kode) as lunas"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='3' ");
        
        $passenger_cancel = $this->global_models->get_query("SELECT count(kode) as cancel"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='4' ");
        
        $passenger_cancel_waiting = $this->global_models->get_query("SELECT count(kode) as waiting"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='5' ");
           
        
        if($passenger_book[0]->book > 0){
          $st_book = "<b>".$passenger_book[0]." Book </b> <br>";
        }if($passenger_commit[0]->commit_book > 0){
          $st_commit = "<b>".$passenger_commit[0]->commit_book." Committed Book </b> <br>";
        }if($passenger_lunas[0]->lunas > 0){
          $st_lunas = "<b>".$passenger_lunas[0]->lunas." Lunas </b> <br>";
        }if($passenger_cancel[0]->cancel > 0){
          $st_cancel = "<b>".$passenger_cancel[0]->cancel." Cancel </b> <br>";
        }if($passenger_cancel_waiting->waiting > 0){
          $st_wtapp = "<b>".$passenger_cancel_waiting->waiting." [Cancel] Waiting Approval </b> <br>";
        }
  
      
         $statusPassager = $st_book.$st_commit.$st_lunas.$st_cancel.$st_wtapp;
        // $passenger = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
       
         $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
   
         if($discount_tambahan[0]->status == 1){
            $status_disc_tambahan = "Apprave";
          }  elseif($discount_tambahan[0]->status == 2) {
            $status_disc_tambahan = "Waiting Appraval";
          }
          if($bk->stnb_discount == 1){
            $stnb_discount = "Persen";
          }elseif($bk->stnb_discount == 2){
            $stnb_discount = "Nominal";
          }
          
          $book_detail[] = array(
            "id_product_tour"   => $bk->id_product_tour,
            "tour"              => $bk->title,
            "tour_code"         => $bk->code_tour,
            "start_date"        => $bk->start_date,
            "end_date"          => $bk->end_date,
            "info_code"       => $bk->code_info,
            "code"            => $bk->kode,
            "first_name"      => $bk->first_name,
            "last_name"       => $bk->last_name,
            "telp"            => $bk->telphone,
            "email"           => $bk->email,
            "tanggal"         => $bk->tanggal,
            "status"          => $bk->status,
            "discount"        => $bk->discount,
            "status_discount"         => $stnb_discount,
            "tax_and_insurance" => $price_tax_inusrance,
            "beban_awal"      => $nominal_pertama,
            "beban_awal"           => $balance[0]->debit,
            "pembayaran"      => $balance[0]->kredit,
            "currency_rate"   => $dropdown_rate[1],  
            "additional"      => $additional,
            "passenger"       => $statusPassager,
            "committed_book"  => $ps_comit
          );
        }
        }
        
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
            . "{$this->form_eksternal->form_input('start_date', $serach_data['start_date'], 'id="start_date" class="form-control input-sm" placeholder="Date"')}"
          . "</div>"
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%;'>"
          . "<div class='control-group'>"
            . "<label>End Date</label>"
            . "{$this->form_eksternal->form_input('end_date', $serach_data['end_date'], 'id="end_date" class="form-control input-sm" placeholder="Date"')}"
          . "</div>"
          
        . "</div>"
              
        . "<div class='box-body col-sm-6' style='padding-left:2%;'>"
          . "<div class='control-group'>"
            . "<button id='bb' class='btn btn-primary' type='submit'>Search</button>"
          . "</div>"
        . "</div>"
              . "<div class='box-body col-sm-6' style='padding-left:2%;padding-bottom:8%'>"
          . "<div class='control-group'>"
           
          . "</div>"
        . "</div>"
      . "</form>"
    . "</div>";
    
    $this->template->build('tour-book/group-status', 
      array(
          'url'           => base_url()."themes/".DEFAULTTHEMES."/",
          'url_image'     => base_url()."themes/antavaya/",
          'menu'          => "grouptour/product-tour/book-list",
          'data'          => $book_detail,
          'title'         => lang("tour_book_list"),
          'category'      => $category,
          'sub_category'  => $sub_category,
          'foot'          => $foot,
          'css'           => $css,
          'serach_data'   => $serach_data,
          'serach'        => $serach,
          'before_table'  => $before_table,
        ));
    $this->template
      ->set_layout("datatables")
      ->build('tour-book/group-status');
  
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
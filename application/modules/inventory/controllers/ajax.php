<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MX_Controller {
    
  function __construct() {      
  }
  function save_product_tour(){
    $pst = $this->input->post();
    if($pst['id_product_tour']){
      $id_product_tour = $pst['id_product_tour'];
      $kirim = array(
          "title"           => trim($pst['title']),
          "category"        => $pst['category'],
          "no_pn"           => $pst['no_pn'],
          "sub_category"    => $pst['sub_category'],
          "destination"     => $pst['destination'],
          "selling_poin"    => $pst['selling_poin'],
          "division"        => $pst['division'],
          "product_cabang"  => $pst['product_cabang'],
          "landmark"        => $pst['landmark'],
          "days"            => $pst['days'],
          "night"           => $pst['night'],
          "airlines"        => $pst['airlines'],
          "status"          => 2,
		  "category_product"  => $pst['category_product'],
          "note"            => $pst['note'],
          "update_by_users" => $this->session->userdata("id"),
      );

      $this->global_models->update("product_tour", array('id_product_tour' => $id_product_tour), $kirim);
    }
    else{
      $this->load->helper('string');
      $kode_data = random_string('alnum', 10);
      $kode = strtoupper($kode_data);
      $this->olah_tour_code($kode);
      $kirim = array(
          "id_store"        => $this->session->userdata('store'),
          "id_store_region"        => $this->session->userdata('store_region'),
          "title"           => trim($pst['title']),
          "kode"            => $kode,
          "category"        => $pst['category'],
          "selling_poin"    => $pst['selling_poin'],
          "division"        => $pst['division'],
          "product_cabang"  => $pst['product_cabang'],
          "no_pn"           => $pst['no_pn'],
          "sub_category"    => $pst['sub_category'],
          "destination"     => $pst['destination'],
          "landmark"        => $pst['landmark'],
          "days"            => $pst['days'],
          "night"           => $pst['night'],
          "airlines"        => $pst['airlines'],
          "status"          => 2,
          "category_product"  => $pst['category_product'],
          "note"            => $pst['note'],
          "create_by_users" => $this->session->userdata("id"),
          "create_date"     => date("Y-m-d H:i:s")
      );

      $id_product_tour = $this->global_models->insert("product_tour", $kirim);
    }
    print $id_product_tour;
    die;
  }
  
  function update_kode(){
    
    $product_tour = $this->global_models->get_query("SELECT * FROM `product_tour` where kode is NULL");
   
    foreach ($product_tour as $value) {
    
      $this->olah_tour_code($kode);
      $kirim = array(
         "kode"            => $kode,
         
      );
      $this->global_models->update("product_tour", array('id_product_tour' => $value->id_product_tour), $kirim);
    }
  }
  
  private function olah_tour_code(&$kode){
    $this->load->helper('string');
    $kode_data = random_string('alnum', 10);
    $kode = strtoupper($kode_data);
    $cek = $this->global_models->get_field("product_tour", "id_product_tour", array("kode" => $kode));
    if($cek > 0){
      $this->olah_tour_code($kode);
    }
  }
  
  function product_tour_detail(){
    $id_product_tour_information = $this->input->post('id_product_tour_information');
    $detail = $this->global_models->get("product_tour_information", array("id_product_tour_information" => $id_product_tour_information));
    
    if($detail[0]->id_product_tour_master_discount > 0){
      $tour_discount = $detail[0]->id_product_tour_master_discount;
    }else{
      $tour_discount = 0;
    }
    $hasil = array(
      'id_product_tour_information' => $detail[0]->id_product_tour_information,
      'kode'                  => $detail[0]->kode,
      'kode_ps'               => $detail[0]->kode_ps,
      'start_date'            => $detail[0]->start_date,
      'end_date'              => $detail[0]->end_date,
      'start_time'            => $detail[0]->start_time,
      'end_time'              => $detail[0]->end_time,
      'available_seat'        => $detail[0]->available_seat,
      'id_currency'           => $detail[0]->id_currency,
      'keberangkatan'           => $detail[0]->keberangkatan,
      'kurs_rate'             => number_format($detail[0]->kurs_rate, 0, ".",","),
      'adult_triple_twin_usd'     => number_format($detail[0]->adult_triple_twin_usd, 0, ".",","),
      'child_twin_bed_usd'        => number_format($detail[0]->child_twin_bed_usd, 0, ".",","),
      'child_extra_bed_usd'       => number_format($detail[0]->child_extra_bed_usd, 0, ".",","),
      'child_no_bed_usd'          => number_format($detail[0]->child_no_bed_usd, 0, ".",","),
      'sgl_supp_usd'              => number_format($detail[0]->sgl_supp_usd, 0, ".",","),
      'airport_tax_usd'           => number_format($detail[0]->airport_tax_usd, 0, ".",","),
      'visa_usd'                  => number_format($detail[0]->visa_usd, 0, ".",","),
      'less_ticket_adl_usd'       => number_format($detail[0]->less_ticket_adl_usd, 0, ".",","),
      'less_ticket_adl'           => number_format($detail[0]->less_ticket_adl, 0, ".",","),
      'less_ticket_chl_usd'       => number_format($detail[0]->less_ticket_chl_usd, 0, ".",","),
      'less_ticket_chl'           => number_format($detail[0]->less_ticket_chl, 0, ".",","),
      
      'adult_triple_twin'     => number_format($detail[0]->adult_triple_twin, 0, ".",","),
      'child_twin_bed'        => number_format($detail[0]->child_twin_bed, 0, ".",","),
      'child_extra_bed'       => number_format($detail[0]->child_extra_bed, 0, ".",","),
      'child_no_bed'          => number_format($detail[0]->child_no_bed, 0, ".",","),
      'sgl_supp'              => number_format($detail[0]->sgl_supp, 0, ".",","),
      'airport_tax'           => number_format($detail[0]->airport_tax, 0, ".",","),
      'visa'                  => number_format($detail[0]->visa, 0, ".",","),
      'tour_discount'         => $tour_discount,
      'days'                  => $detail[0]->days,
      'flt'                   => $detail[0]->flt,
      'in'                    => $detail[0]->in,
      'out'                   => $detail[0]->out,
	  'tampil'                => $detail[0]->tampil,
	  'condition'             => $detail[0]->status,
	  'sts'             => $detail[0]->sts,
	  'remarks'             => $detail[0]->remarks,
	  'at_airport'             => $detail[0]->at_airport,
         'at_airport_date'             => $detail[0]->at_airport_date,
    );
    print json_encode($hasil);
    die;
  }
  
  function tour_detail(){
    $id_product_tour_information = $this->input->post('id_product_tour_information');
    $detail = $this->global_models->get("product_tour_information", array("id_product_tour_information" => $id_product_tour_information));
    if($detail[0]->start_time){
      $start_time =  date("H:i", strtotime($detail[0]->start_time));
    }else{
      $start_time = "";
    }
    
    if($detail[0]->end_time){
      $end_time = date("H:i", strtotime($detail[0]->end_time));
    }else{
      $end_time = "";
    }
    
    $hasil = array(
      'id_product_tour_information' => $detail[0]->id_product_tour_information,
      'kode'                  => $detail[0]->kode,
      'kode_ps'               => $detail[0]->kode_ps,
      'start_date'            => date("d M Y", strtotime($detail[0]->start_date)),
      'end_date'              => date("d M Y", strtotime($detail[0]->end_date)),
      'start_time'            =>  $start_time,
      'end_time'              => $end_time,
      'available_seat'        => $detail[0]->available_seat,
      'id_currency'           => $detail[0]->id_currency,
      'adult_triple_twin'     => number_format($detail[0]->adult_triple_twin, 0, ".",","),
      'child_twin_bed'        => number_format($detail[0]->child_twin_bed, 0, ".",","),
      'child_extra_bed'       => number_format($detail[0]->child_extra_bed, 0, ".",","),
      'child_no_bed'          => number_format($detail[0]->child_no_bed, 0, ".",","),
      'sgl_supp'              => number_format($detail[0]->sgl_supp, 0, ".",","),
      'airport_tax'           => number_format($detail[0]->airport_tax, 0, ".",","),
      'visa'                  => number_format($detail[0]->visa, 0, ".",","),
     // 'discount_tetap'        => number_format($detail[0]->discount_tetap, 0, ".",","),
      'tour_discount'   => $detail[0]->id_product_tour_master_discount,
      'days'                  => $detail[0]->days,
      'flt'                   => $detail[0]->flt,
      'in'                    => $detail[0]->in,
      'out'                   => $detail[0]->out,
	  'tampil'                => $detail[0]->tampil,
	  
    );
//    print "<pre>";
//    print_r($hasil);
//    print "</pre>";
    print json_encode($hasil);
    die;
  }
  
  function delete_product_tour_information(){
    $id_product_tour_information = $this->input->post('id_product_tour_information');
	
	$this->load->model('inventory/mnotice_mail');
    $this->mnotice_mail->cancel_product_tour_information($id_product_tour_information);
      
    $kirim = array(
            "tampil"           => 3,
            "update_by_users" => $this->session->userdata("id"),
            "user_cancel"      => $this->session->userdata("id"),
            "date_cancel"       => date("Y-m-d H:i:s"),
            "update_date"      => date("Y-m-d H:i:s")
        );
         $this->global_models->update("product_tour_information", array("id_product_tour_information" => $id_product_tour_information),$kirim);
        
   // print json_encode($hasil);
    die;
  }
  
  function add_discount(){
    $pst = $this->input->post();
    
     $detail = $this->global_models->get("product_tour_setting_discount", array("id_product_tour_master_discount" => $pst['id_discount']));
  
     $status_nb = array(1 => "Persen (%)",
                        2 => "Nominal");
     //$inf = $this->global_models->get("product_tour_information", array("id_product_tour_information" => $id_product_tour_information));
   
    
     
    $html = "";
    foreach ($detail as $value) {
     $html .= "
                    <div class='form-group dt_discount'>
	<label>Discount <small style='font-weight: normal'>Jumlah Batas Discount, Discount</small></label>
	<div class='row'>
		<div class='col-xs-4'>
			 ".$this->form_eksternal->form_input('batas_discount', $value->batas_discount,  " disabled  class='form-control input-sm ' placeholder='Batas Discount'")."
		</div>
		<div class='col-xs-4'>
    ".$this->form_eksternal->form_input('stbn_discount', $status_nb[$value->stnb_discount],  " disabled  class='form-control input-sm ' placeholder=''")."
			
		</div>
		<div class='col-xs-4'>
       ".$this->form_eksternal->form_input('stbn_discount', $value->discount,  " disabled class='form-control input-sm' placeholder=''")."
			
		</div>
	</div>
</div>";
    }
     
     
    print $html;
    die;
  }
  
  function customer_detail(){
    $kode = $this->input->post('customer_kode');
    $detail = $this->global_models->get("product_tour_customer", array("id_product_tour_customer" => $kode));
    
    $note_type = array(
          1 => "Adult Triple Twin",
          2 => "Child Twin Bed",
          3 => "Child Extra Bed",
          4 => "Child No Bed",
          5 => "SGL SUPP"
        );
    $hasil = array(
      'name'                        => $detail[0]->first_name." ".$detail[0]->last_name,
      'telp'                        => $detail[0]->telphone,
      'tmpt_tgl_lahir'              => $detail[0]->tempat_tanggal_lahir,
      'tgl_lahir'                   => date("d F Y", strtotime($detail[0]->tanggal_lahir)),
      'passport'                    => $detail[0]->passport,
      'place_issued'                => $detail[0]->place_of_issued,
      'date_issued'                 => date("d F Y", strtotime($detail[0]->date_of_issued)),
      'date_expired'                => date("d F Y", strtotime($detail[0]->date_of_expired)),
      'type'                        => $note_type[$detail[0]->type]
      
    );
    print json_encode($hasil);
    die;
  }
  
  function product_tour($total = 0, $start = 0){
    
    if($this->session->userdata('tour_name')){
      $tour_name = " AND LOWER(A.title) LIKE '%".strtolower($this->session->userdata('tour_name'))."%'";
    }
    
    if($this->session->userdata('tour_season') > 0){
      $tour_season = " AND A.category ='{$this->session->userdata('tour_season')}'";
    }
    
    if($this->session->userdata('pn_news')){
      $pn = " AND LOWER(A.no_pn) LIKE '%".strtolower($this->session->userdata('pn_news'))."%'";
    }
    
    if($this->session->userdata('tour_kota')){
      $tour_kota = " AND LOWER(A.destination) LIKE '%".strtolower($this->session->userdata('tour_kota'))."%'";
    }
    
    if($this->session->userdata('tour_region') > 0){
      $tour_season = " AND A.sub_category ='{$this->session->userdata('tour_region')}'";
    }
    
    if($this->session->userdata('tour_status') > 0){
      $tour_status = " AND A.status ='{$this->session->userdata('tour_status')}'";
    }
    
    if($this->session->userdata('tour_store') > 0){
      $tour_store = " AND A.id_store_region ='{$this->session->userdata('tour_store')}'";
    }
    
      
    $items = $this->global_models->get_query("SELECT A.*, B.title AS store"
        . " FROM product_tour AS A"
    //  . " LEFT JOIN store AS B ON A.id_store = B.id_store"
      . " LEFT JOIN store_region AS B ON A.id_store_region = B.id_store_region"
      . " WHERE 1=1 {$tour_name} {$tour_season} {$tour_kota} {$tour_season} {$tour_status} {$tour_store} {$pn}"
      . " ORDER BY A.id_product_tour DESC"
      . " LIMIT {$start}, 10");
      
//    $users = $this->global_models->get_query("
//      SELECT A.*, B.name AS privilege, C.title AS jabatan
//      FROM m_users AS A
//      LEFT JOIN m_privilege AS B ON A.id_privilege = B.id_privilege
//      LEFT JOIN hrm_settings_level_organisasi AS C ON A.id_hrm_settings_level_organisasi = C.id_hrm_settings_level_organisasi
//      WHERE A.type = 1
//      ORDER BY name
//      LIMIT {$start}, 10
//      ");
   
       
    $category = array(
      1 => "Low Season",
      2 => "Hight Season Chrismast",
      3 => "Hight Season Lebaran",
      4 => "School Holiday Period",
    );
    $subcategory = array(
      1 => "Eropa",
      2 => "Africa",
      3 => "America",
      4 => "Australia",
      5 => "Asia",
	  6 => "China",
	  7 => "New Zealand"
    );
    $status = array(
      1 => "<span class='label label-success'>Publish</span>",
      2 => "<span class='label label-default'>Draft</span>",
      NULL => "<span class='label label-default'>Draft</span>",
    );
    foreach ($items as $value) {
      $schedule = $this->global_models->get_query("SELECT *"
        . " FROM product_tour_information"
        . " WHERE id_product_tour = {$value->id_product_tour}"
        . " ORDER BY start_date DESC LIMIT 0,10");
      $tooltips = "<table width='100%'>"
        . "<tr>"
          . "<th>Kode</th>"
          . "<th>Start</th>"
          . "<th>End</th>"
          . "<th>Seat</th>"
        . "</tr>";
      foreach($schedule AS $t => $i){
        $tooltips .= "<tr>"
          . "<td>{$i->kode_ps}</td>"
          . "<td>{$i->start_date}</td>"
          . "<td>{$i->end_date}</td>"
          . "<td>{$i->available_seat}</td>"
        . "</tr>";
      }
      if($t >= 9){
        $tooltips .= "<tfood>"
          . "<tr>"
            . "<td colspan='4'><a href='".site_url("inventory/product-tour/add-product-tour/{$value->id_product_tour}")."'>More...</a></td>"
          . "</tr>"
          . "</tfood>";
      }
      $tooltips .= "</table>";
      
      $kota = strip_tags($value->destination);
      $detail_kota = "";
      if (strlen($kota) > 20) {
          $kotaCut = substr($kota, 0, 20);
          $kota = substr($kotaCut, 0, strrpos($kotaCut, ' ')).'... <a href="javascript:void(0)" id="kota-'.$value->id_product_tour.'">View</a>';
          $detail_kota = "<div style='display: none' id='isi-kota-{$value->id_product_tour}'>{$value->destination}</div>";
          $script .= "$('#kota-{$value->id_product_tour}').tooltipster({"
            . "content: $('#isi-kota-{$value->id_product_tour}').html(),"
            . "minWidth: 300,"
            . "maxWidth: 300,"
            . "contentAsHTML: true,"
            . "interactive: true"
          . "});";
      }
      
      $tampil .= "<tr>"
        . "<td>{$value->store}</td>"
        . "<td>{$value->no_pn}</td>"
        . "<td>"
          . "<span style='display: none'>{$value->kode}</span>"
          . "<a href='".site_url("inventory/product-tour/add-product-tour/{$value->id_product_tour}")."' id='schedule-tour-{$value->id_product_tour}'>{$value->title}</a>"
          . "<div style='display: none' id='isi-schedule-tour-{$value->id_product_tour}'>{$tooltips}</div>"
        . "</td>"
        . "<td>{$value->days}</td>"
        . "<td>{$kota}{$detail_kota}</td>"
        . "<td>{$category[$value->category]}</td>"
        . "<td>{$subcategory[$value->sub_category]}</td>"
        . "<td>{$status[$value->status]}</td>"
        . "<td>"
          . "<div class='btn-group'>"
            . "<button data-toggle='dropdown' class='btn btn-small dropdown-toggle'>Action<span class='caret'></span></button>"
            . "<ul class='dropdown-menu'>"
              . "<li><a href='".site_url("inventory/product-tour/add-product-tour/{$value->id_product_tour}")."'>Edit</a></li>"
              . "<li><a href='".site_url("inventory/product-tour/copy-product-tour/{$value->id_product_tour}")."'>Copy</a></li>"
              . "<li><a href='".site_url("inventory/product-tour/tour-detail/{$value->id_product_tour}")."'>Detail</a></li>"
            . "</ul>"
          . "</div>"
        . "</td>"
      . "</tr>";
      $script .= "$('#schedule-tour-{$value->id_product_tour}').tooltipster({"
        . "content: $('#isi-schedule-tour-{$value->id_product_tour}').html(),"
        . "minWidth: 300,"
        . "maxWidth: 300,"
        . "contentAsHTML: true,"
        . "interactive: true"
      . "});";
    }
    
    $tampil .= "<script>"
        . "$(function() {"
          . "{$script}"
        . "});"
      . "</script>";
    
   
    print $tampil;
    die;
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
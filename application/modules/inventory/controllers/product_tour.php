<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_tour extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function delete_product_tour($id_product_tour){
    $this->global_models->delete("product_tour", array("id_product_tour" => $id_product_tour));
    $this->global_models->delete("product_tour_information", array("id_product_tour" => $id_product_tour));
    $this->session->set_flashdata('success', 'Data terhapus');
    redirect("inventory/product-tour");
  }
  
  function master_additional(){
      
    $list = $this->global_models->get("product_tour_master_additional");
   
    $menutable = '
      <li><a href="'.site_url("inventory/product-tour/add-master-additional").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('product-tour/master-additional', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
            'data'        => $list,
            'title'       => lang("master-additional"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('product-tour/master-additional');
  }
  
  function master_visa(){
      
    $list = $this->global_models->get("product_tour_master_visa");
   
    $menutable = '
      <li><a href="'.site_url("inventory/product-tour/add-master-visa").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('product-tour/master-visa', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
            'data'        => $list,
            'title'       => lang("master-visa"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('product-tour/master-visa');
  }
  
  public function add_master_additional($id_product_tour_master_additional = 0){
      
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("product_tour_master_additional", array("id_product_tour_master_additional" => $id_product_tour_master_additional));
      
      $this->template->build("product-tour/add-product-tour", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/product-tour',
              'title'       => lang("master-additional"),
             'detail'      => $detail,
            'info'          => $info,
              'breadcrumb'  => array(
                    "master-additional"  => "inventory/product-tour/master-additional"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("product-tour/add-product-tour-master-additional");
    }
    else{
      $pst = $this->input->post(NULL);
     // print_r($pst); die;
      if($pst['status']){
        $status = 1;
      }else{
        $status = 2;
      }
      if($pst['id_detail']){
        $kirim = array(
            "name"           => $pst['name'],
            "status"         => $status,
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_product_tour_master_additional = $this->global_models->update("product_tour_master_additional", array("id_product_tour_master_additional" => $pst['id_detail']),$kirim);
        
         $kirim_log = array(
             "name"             => $pst['name'],
              "status"          => $status,
              "create_by_users" => $this->session->userdata("id"),
              "create_date"     => date("Y-m-d"),
              "note"            => "Update"
        );
         $this->global_models->insert("log_product_tour_master_additional", $kirim_log);
      
      }
      else{
        $kirim = array(
             "name"           => $pst['name'],
            "status"          => $status,
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        $id_product_tour_master_additional = $this->global_models->insert("product_tour_master_additional", $kirim);
        
        $kirim_log = array(
             "name"             => $pst['name'],
              "status"          => $status,
              "create_by_users" => $this->session->userdata("id"),
              "create_date"     => date("Y-m-d"),
              "note"            => "Insert"
        );
         $this->global_models->insert("log_product_tour_master_additional", $kirim_log);
      }
      if($id_product_tour_master_additional){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/product-tour/master-additional");
    }
  }
  
  public function add_master_visa($id_product_tour_master_visa = 0){
      
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("product_tour_master_visa", array("id_product_tour_master_visa" => $id_product_tour_master_visa));
      $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));     
   
      $this->template->build("product-tour/add-product-tour-master-visa", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/product-tour',
              'title'       => lang("master-visa"),
             'detail'      => $detail,
             'dropdown'      => $dropdown,
            'info'          => $info,
              'breadcrumb'  => array(
                    "master-visa"  => "inventory/product-tour/master-visa"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("product-tour/add-product-tour-master-visa");
    }
    else{
      $pst = $this->input->post(NULL);
     // print_r($pst); die;
      if($pst['status']){
        $status = 1;
      }else{
        $status = 2;
      }
      if($pst['id_detail']){
        $kirim = array(
            "name"           => $pst['name'],
            "status"         => $status,
            "nominal"         => $pst['nominal'],
            "update_by_users" => $this->session->userdata("id"),
        );
        $data_id_product_tour_master_visa = $this->global_models->update("product_tour_master_visa", array("id_product_tour_master_visa" => $pst['id_detail']),$kirim);
        
        
      }
      else{
        $kirim = array(
            "name"           => $pst['name'],
            "status"          => $status,
            "nominal"         => $pst['nominal'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        $data_id_product_tour_master_visa = $this->global_models->insert("product_tour_master_visa", $kirim);
        
       
      }
      if($data_id_product_tour_master_visa){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/product-tour/master-visa");
    }
  }
  
  function ajax_add_row_additional(){
     $pos = $_POST['name'];
     $pos++;
    $dropdown = $this->global_models->get_dropdown("product_tour_master_additional", "id_product_tour_master_additional", "name", TRUE, array("status" => 1));     
    
    $html = "<div class='box-body col-sm-6'>
      <div class='control-group'>
        <label><div class='number_additional'>Additional {$pos}</div></label>";
        $html .= $this->form_eksternal->form_dropdown('name_additional[]', $dropdown, "", 'class="form-control" placeholder="Additional"');
      
      $html .= "</div>"
    ."</div>";
      
    $html .= "<div class='box-body col-sm-6'>
      <div class='control-group'>
        <label>Nominal {$pos}</label>";
    $html .= $this->form_eksternal->form_input('nominal_additional[]', "", ' class="form-control input-sm" placeholder="Nominal"');
    $html .= "</div>"
    ."</div><br><br><br><br>";
    
    print $html;
    die;
  }
  
  function ajax_add_row_setting_discount(){
     $pos = $_POST['name'];
     $pos++;
//    $dropdown = $this->global_models->get_dropdown("product_tour_master_additional", "id_product_tour_master_additional", "name", TRUE, array("status" => 1));     
    $dropdown = array(1 => "Persen %",
                      2 => "Nominal");
     
    $html = "<div class='box-body col-sm-4'>
      <div class='control-group'>
        <label>Batas Discount {$pos}</label>";
    $html .= $this->form_eksternal->form_input('batas_discount[]', "", ' class="form-control input-sm" placeholder="Batas Discount"');
    $html .= "</div></div>";
    
    $html .= "<div class='box-body col-sm-4'>
      <div class='control-group'>
        <label><div class='number_additional'>Discount {$pos}</div></label>";
        $html .= $this->form_eksternal->form_dropdown('stnb_discount[]', $dropdown, "", 'class="form-control" placeholder="Discount"');
      
      $html .= "</div>"
    ."</div>";
      
    $html .= "<div class='box-body col-sm-4'>
      <div class='control-group'>
        <label></label><br>";
    $html .= $this->form_eksternal->form_input('discount[]', "", ' class="form-control input-sm" placeholder=""');
    $html .= "</div>"
    ."</div><br><br><br><br>";
    
    print $html;
    die;
  }
  
  public function add_master_discount($id_product_tour_master_discount = 0){
      
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("product_tour_master_discount", array("id_product_tour_master_discount" => $id_product_tour_master_discount));
      
      $this->template->build("product-tour/add-product-tour-master-discount", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/product-tour',
              'title'       => lang("master-discount"),
             'detail'      => $detail,
            'info'          => $info,
              'breadcrumb'  => array(
                    "master-discount"  => "inventory/product-tour/master-discount"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("product-tour/add-product-tour-master-discount");
    }
    else{
      $pst = $this->input->post(NULL);
     // print_r($pst); die;
      if($pst['status']){
        $status = 1;
      }else{
        $status = 2;
      }
      if($pst['id_detail']){
        $kirim = array(
            "name"           => $pst['name'],
            "status"         => $status,
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_product_tour_master_disc = $this->global_models->update("product_tour_master_discount", array("id_product_tour_master_discount" => $pst['id_detail']),$kirim);
      
      }
      else{
        $kirim = array(
             "name"           => $pst['name'],
            "status"          => $status,
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        $id_product_tour_master_disc = $this->global_models->insert("product_tour_master_discount", $kirim);
        
        
      }
      if($id_product_tour_master_disc){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/product-tour/master-discount");
    }
  }
  
  public function master_discount(){
      
    $list = $this->global_models->get("product_tour_master_discount");
   
    $menutable = '
      <li><a href="'.site_url("inventory/product-tour/add-master-discount").'"><i class="icon-plus"></i> Add New</a></li>
        
      ';
    $this->template->build('product-tour/master-discount', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
            'data'        => $list,
            'title'       => lang("master-discount"),
            'menutable'   => $menutable,
          ));
      $this->template
      ->set_layout('datatables')
      ->build('product-tour/master-discount');
  }
  
  public function add_additional($id_product_tour_information = 0){
      
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("product_tour_information", array("id_product_tour_information" => $id_product_tour_information));
    $dropdown = $this->global_models->get_dropdown("product_tour_master_additional", "id_product_tour_master_additional", "name", TRUE, array("status" => 1));     
    $item = $this->global_models->get("product_tour_optional_additional", array("id_product_tour_information" => $id_product_tour_information));
     
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    $foot = "
        <link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>
       ";
    $foot .= "<script>"
     ."function tambah_items_additional(){"
      ."var num = $('.number_additional').length;"
      ."var dataString2 = 'name='+ num;"
      ."$.ajax({"
      ."type : 'POST',"
      ."url : '".site_url("inventory/product-tour/ajax-add-row-additional")."',"
      ."data: dataString2,"
      ."dataType : 'html',"
      ."success: function(data) {"
            ."$('#tambah-additional').append(data);"
      ."},"
    ."});"
        ."}"
      ."</script>";
    $this->template->build("product-tour/add-product-tour", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/product-tour',
              'title'       => lang("add-additional"),
              'detail'      => $detail,
              'dropdown'   => $dropdown,
              'item'        => $item,
              'breadcrumb'  => array(
                 "Product-tour"  => "inventory/product-tour"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("product-tour/add-additional");
    }
    else{
      $pst = $this->input->post(NULL);
     
      if($pst['id_product_tour_optional_additional']){
        if(isset($pst['name_additional'])){
      foreach($pst['name_additional'] AS $key_na => $val_na){
      if($val_na){
        $kirim_info = array(
            "id_product_tour_optional_additional" => $pst['id_product_tour_optional_additional'][$key_na],
            "id_product_tour_master_additional"   => $pst['name_additional'][$key_na],
            "id_product_tour_information"         => $pst['id_detail'],
            "nominal"                             => $pst['nominal_additional'][$key_na],
              "create_by_users"                         => $this->session->userdata("id"),
              "create_date"                             => date("Y-m-d H:i:s")
            );
            
            $else_kirim_info = array(
            "id_product_tour_master_additional"   => $pst['name_additional'][$key_na],
            "id_product_tour_optional_additional" => $pst['id_product_tour_optional_additional'][$key_na],
            "id_product_tour_information"         => $pst['id_detail'],
            "nominal"                             => $pst['nominal_additional'][$key_na],
            "update_by_users"                       => $this->session->userdata("id"),
            );
          $id_additional =  $this->global_models->update_duplicate("product_tour_optional_additional", $kirim_info, $else_kirim_info);
      }
    }
  // $id_additional = $this->global_models->insert_batch("product_tour_optional_additional", $addtional);
    }
      }
      else{
         if(isset($pst['name_additional'])){
      foreach($pst['name_additional'] AS $key_na => $val_na){
      if($val_na){
        $addtional[] = array(
          "id_product_tour_master_additional"   => $pst['name_additional'][$key_na],
          "id_product_tour_information"         => $pst['id_detail'],
          "nominal"                             => $pst['nominal_additional'][$key_na],
          "create_by_users"               => $users[0]->id_users,
          "create_date"                   => date("Y-m-d H:i:s")
        );
      }
    }
   $id_additional = $this->global_models->insert_batch("product_tour_optional_additional", $addtional);
    }
      } 
      if($id_additional){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/product-tour/");
    }
  }
  
  function delete_setting_discount($id){
   $detail = $this->global_models->get("product_tour_setting_discount", array("id_product_tour_setting_discount" => $id));
     
    $this->global_models->delete("product_tour_setting_discount", array("id_product_tour_setting_discount" => $id));
    $this->session->set_flashdata('success', 'Data terhapus');
    redirect("inventory/product-tour/setting-discount/{$detail[0]->id_product_tour_master_discount}");
  }
  
  public function setting_discount($id_product_tour_setting_discount = 0){
      
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("product_tour_setting_discount", array("id_product_tour_master_discount" => $id_product_tour_setting_discount));
   
     
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    $foot = "
        <link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>
       ";
    $foot .= "<script>"
     ."function tambah_items_setting_discount(){"
      ."var num = $('.number_additional').length;"
      ."var dataString2 = 'name='+ num;"
      ."$.ajax({"
      ."type : 'POST',"
      ."url : '".site_url("inventory/product-tour/ajax-add-row-setting-discount")."',"
      ."data: dataString2,"
      ."dataType : 'html',"
      ."success: function(data) {"
            ."$('#tambah-additional').append(data);"
      ."},"
    ."});"
        ."}"
      ."</script>";
    $this->template->build("product-tour/setting-discount", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/product-tour',
              'title'       => lang("setting-discount"),
              'detail'      => $detail,
              'id_data'   => $id_product_tour_setting_discount,
              'breadcrumb'  => array(
                 "master-discount"  => "inventory/product-tour/master-discount"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("product-tour/setting-discount");
    }
    else{
      $pst = $this->input->post(NULL);
     
//      print "<pre>";
//      print_r($pst);
//      print "</pre>"; die;
      if($pst['id_product_tour_setting_discount']){
        if(isset($pst['batas_discount'])){
      foreach($pst['batas_discount'] AS $key_na => $val_na){
      if($val_na){
        $kirim_info = array(
            "id_product_tour_setting_discount"      => $pst['id_product_tour_setting_discount'][$key_na],
            "id_product_tour_master_discount"       => $pst['id_detail'],
            "batas_discount"                        => $pst['batas_discount'][$key_na],
            "discount"                             => $pst['discount'][$key_na],
            "stnb_discount"                         => $pst['stnb_discount'][$key_na],
            "create_by_users"                      => $this->session->userdata("id"),
              "create_date"                       => date("Y-m-d H:i:s")
            );
            
            $else_kirim_info = array(
            "id_product_tour_setting_discount"   => $pst['id_product_tour_setting_discount'][$key_na],
            "id_product_tour_master_discount"     => $pst['id_detail'],
            "batas_discount"                      => $pst['batas_discount'][$key_na],
            "discount"                             => $pst['discount'][$key_na],
            "stnb_discount"                         => $pst['stnb_discount'][$key_na],  
            "update_by_users"                       => $this->session->userdata("id"),
            );
          $id_additional =  $this->global_models->update_duplicate("product_tour_setting_discount", $kirim_info, $else_kirim_info);
      }
    }
  // $id_additional = $this->global_models->insert_batch("product_tour_optional_additional", $addtional);
    }
      }
      else{
         if(isset($pst['batas_discount'])){
      foreach($pst['batas_discount'] AS $key_na => $val_na){
      if($val_na){
        $discount[] = array(
          "id_product_tour_master_discount"   => $pst['id_detail'],
          "batas_discount"                      => $pst['batas_discount'][$key_na],
          "discount"                            => $pst['discount'][$key_na],
            "stnb_discount"                     => $pst['stnb_discount'][$key_na],
          "create_by_users"                     => $this->session->userdata("id"),
          "create_date"                         => date("Y-m-d H:i:s")
        );
      }
    }
   
   $id_additional = $this->global_models->insert_batch("product_tour_setting_discount", $discount);
    }
      } 
      if($id_additional){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/product-tour/master-discount");
    }
  }
    
  function ajax_halaman_product_tour($total = 0, $start = 0){
    
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
  
  function index(){
    
    $pst = $this->input->post(NULL);
    if($pst){
    
        $newdata = array(
            'tour_name'   => $pst['name'],
            'tour_season'     => $pst['season'],
            'tour_kota'    => $pst['kota'],
            'tour_region'     => $pst['region'],
            'tour_status'      => $pst['status'],
            'tour_store'      => $pst['store_region'],
            'pn_news'      => $pst['pn_news'],
          );
          $this->session->set_userdata($newdata);
    }
    
    if($this->session->userdata('tour_name')){
      $tour_name = " AND LOWER(A.title) LIKE '%".strtolower($this->session->userdata('tour_name'))."%'";
    }
    
    if($this->session->userdata('pn_news')){
      $pn = " AND LOWER(A.no_pn) LIKE '%".strtolower($this->session->userdata('pn_news'))."%'";
    }
    
    if($this->session->userdata('tour_season') > 0){
      $tour_season = " AND A.category ='{$this->session->userdata('tour_season')}'";
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
    
    $list = $this->global_models->get_query("SELECT count(id_product_tour) AS total"
        . " FROM product_tour AS A"
      . " WHERE 1=1 {$tour_name} {$tour_season} {$tour_kota} {$tour_season} {$tour_status} {$tour_store} {$pn}");

    $jumlah_list = $list[0]->total;
     //$jumlah_list = $this->global_models->get_field("product_tour", "count(id_product_tour)");
    
    $url_list = site_url("inventory/ajax/product-tour/".$jumlah_list);
    $url_list_halaman = site_url("inventory/product-tour/ajax-halaman-product-tour/".$jumlah_list);

   $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>";
    
    $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
       ."<script type='text/javascript'>"

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
   
    $menutable = '
      <li><a href="'.site_url("inventory/product-tour/add-product-tour").'"><i class="icon-plus"></i> Add New</a></li>
      <li><a href="'.site_url("inventory/product-tour/upload-file").'"><i class="icon-plus"></i> Upload File Tour Master</a></li>
      <li><a href="'.site_url("inventory/product-tour/upload-file-tour-info").'"><i class="icon-plus"></i> Upload File Tour Detail Information</a></li>
      <li><a href="'.site_url("inventory/product-tour/upload-tour").'"><i class="icon-plus"></i>Upload File Tour</a></li>
      <li><a href="'.site_url("inventory/product-tour/list-file").'"><i class="icon-plus"></i> List File Upload</a></li>
      ';
    $this->template->build('product-tour/tour', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
            'data'        => $tampil,
            'foot'        => $foot,
            'css'         => $css,
            'title'       => lang("antavaya_tour"),
            'menutable'   => $menutable,
          //  'menu_action' => 5
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('product-tour/tour');
  }
  
  function book($id_product_tour = 0){
    $detail = $this->global_models->get("product_tour", array("id_product_tour" => $id_product_tour));
  if($detail[0]->id_product_tour){
          $info = $this->global_models->get_query("SELECT A.id_product_tour_information,A.id_product_tour, A.start_date, A.end_date,A.available_seat,A.price_adult_twin,A.price_child_twin,A.price_child_without_bed,A.price_child_with_extra_bed"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.id_product_tour = '{$detail[0]->id_product_tour}'");
      }
    $menutable = '
      <li><a href="'.site_url("inventory/product-tour/add-product-tour").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('product-tour/book', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
            'detail'        => $detail,
            'title'       => lang("Book Product Tour"),
          'breadcrumb'  => array(
                    "product_tour"  => "inventory/product_tour"
                ),
          'info'        => $info,
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('form')
      ->build('product-tour/book');
  }
  
   public function add_product_tour($id_product_tour = 0){
     
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("product_tour", array("id_product_tour" => $id_product_tour));
      if($detail){
        if($detail[0]->id_store_region != $this->session->userdata("store_region") AND $this->session->userdata("id") != 1){
          redirect("inventory/product-tour/tour-detail/{$id_product_tour}");
        }
      }
      $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));     
   
      if($detail[0]->id_product_tour){
          $info = $this->global_models->get_query("SELECT A.id_product_tour_information,A.id_product_tour, A.start_date,A.at_airport_date, A.end_date,A.start_time,A.end_time,A.available_seat,A.adult_triple_twin,A.child_twin_bed,A.child_extra_bed,A.child_no_bed,A.sgl_supp,A.airport_tax,A.kode,A.dp,A.discount_tetap,A.discount_tambahan,"
        . "id_currency,stnb_dp,stnb_discount_tetap,stnb_discount_tambahan,A.flt,A.in,A.out,A.kode_ps,A.visa,A.tampil,A.status"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.id_product_tour = '{$detail[0]->id_product_tour}' AND A.tampil < 3 ORDER BY A.start_date ASC");
      }
     
      
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />"
        . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />";
      $foot = ""
        . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>"
        . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.price_format.1.8.min.js' type='text/javascript'></script>
       
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script>
        
        <script type='text/javascript'>
            $(function() {
              $('#example1').dataTable();
              var isiedit = CKEDITOR.replace('editor2');
              var isiedit = CKEDITOR.replace('editor3');
              $(document).on('blur', '#adult_triple_twin_usd', function(evt){
                var kurs = MoneyToNumber($('#kurs_rate').val());
                var money = MoneyToNumber($(this).val());
                if(money > 0)
                  $('#adult_triple_twin').val(formatCurrency(kurs * money));
              });
              
              $(document).on('blur', '#sgl_supp_usd', function(evt){
                var kurs = MoneyToNumber($('#kurs_rate').val());
                var money = MoneyToNumber($(this).val());
                if(money > 0)
                  $('#sgl_supp').val(formatCurrency(kurs * money));
              });
              
              $(document).on('blur', '#child_twin_bed_usd', function(evt){
                var kurs = MoneyToNumber($('#kurs_rate').val());
                var money = MoneyToNumber($(this).val());
                if(money > 0)
                  $('#child_twin_bed').val(formatCurrency(kurs * money));
              });
              
              $(document).on('blur', '#child_extra_bed_usd', function(evt){
                var kurs = MoneyToNumber($('#kurs_rate').val());
                var money = MoneyToNumber($(this).val());
                if(money > 0)
                  $('#child_extra_bed').val(formatCurrency(kurs * money));
              });
              
              $(document).on('blur', '#child_no_bed_usd', function(evt){
                var kurs = MoneyToNumber($('#kurs_rate').val());
                var money = MoneyToNumber($(this).val());
                if(money > 0)
                  $('#child_no_bed').val(formatCurrency(kurs * money));
              });
              
              $(document).on('blur', '#visa_usd', function(evt){
                var kurs = MoneyToNumber($('#kurs_rate').val());
                var money = MoneyToNumber($(this).val());
                if(money > 0)
                  $('#visa').val(formatCurrency(kurs * money));
              });
              
              $(document).on('blur', '#airport_tax_usd', function(evt){
                var kurs = MoneyToNumber($('#kurs_rate').val());
                var money = MoneyToNumber($(this).val());
                if(money > 0)
                  $('#airport_tax').val(formatCurrency(kurs * money));
              });
              
              $(document).on('blur', '#less_ticket_adl_usd', function(evt){
                var kurs = MoneyToNumber($('#kurs_rate').val());
                var money = MoneyToNumber($(this).val());
                if(money > 0)
                  $('#less_ticket_adl').val(formatCurrency(kurs * money));
              });
              
              $(document).on('blur', '#less_ticket_chl_usd', function(evt){
                var kurs = MoneyToNumber($('#kurs_rate').val());
                var money = MoneyToNumber($(this).val());
                if(money > 0)
                  $('#less_ticket_chl').val(formatCurrency(kurs * money));
              });
              
              $(document).on('click', '#add-schedule', function(evt){
             //   var id_product_tour = $('#id_product_tour').val();
                var note = isiedit.getData();
                
                $.post('".site_url("inventory/ajax/save-product-tour")."', {id_product_tour: $('#id_product_tour').val(), division: $('#division-utama').val(), product_cabang: $('#product-cabang').val(), selling_poin: $('#selling-poin-utama').val(),no_pn: $('#no-pn-utama').val(), title: $('#title-utama').val(), destination: $('#destination-utama').val(), landmark: $('#landmark-utama').val(), days: $('#tot_days').val(),night: $('#night-utama').val(),airlines: $('#airlines-utama').val(),category: $('#category-utama').val(),sub_category: $('#sub-category-utama').val(),category_product: $('#category-product').val(),note: note}, function(data){
                  $('#id_product_tour').val(data);
                  $('#id-product-tour-utama').val(data);
                });

                $('#kode').val('');
                $('#id_product_tour_information').val('');
                $('#kode_ps').val('');
                $('#start_date_0').val('');
                $('#end_date_0').val('');
                $('#start_time_1').val('');
                $('#end_time_1').val('');
				$('#keberangkatan').val('');
                $('#available_seat').val('');
                $('#id_currency').val('2');
                $('#adult_triple_twin').val('');
                $('#child_twin_bed').val('');
                $('#child_extra_bed').val('');
                $('#child_no_bed').val('');
                $('#sgl_supp').val('');
                $('#airport_tax').val('');
                $('#visa').val('');
                $('#tampil').val('1');
				$('#condition').val('1');
                $('#kurs_rate').val('');
                $('#adult_triple_twin_usd').val('');
                $('#child_twin_bed_usd').val('');
                $('#child_extra_bed_usd').val('');
                $('#child_no_bed_usd').val('');
                $('#sgl_supp_usd').val('');
                $('#airport_tax_usd').val('');
                $('#visa_usd').val('');
                
                $('#discount_tetap').val('');
                $('#stnb_discount_tetap').val('0');
                $('#flt').val('');
                $('#in').val('');
                $('#out').val('');
                $('#sts').val('');
            $('#at_airport').val('');
            $('#at_airport_date').val('');
            $('#remarks').val('');
              });
              $( '.start_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
              $( '.start_time' ).timepicker({
              });
              $( '.end_date' ).datepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
            });
        </script>
        <script>

function MoneyToNumber(num)
{
   return (num.replace(/,/g, ''));
}

function AddCommas(num)
{
   numArr=new String(num).split('').reverse();
   for (i=3;i<numArr.length;i+=3)
   {
     numArr[i]+=',';
   }
   return numArr.reverse().join('');
} 
        
function formatCurrency(num) {
   num = num.toString().replace(/\$|\,/g,'');
   if(isNaN(num))
   num = '0';
   sign = (num == (num = Math.abs(num)));
   num = Math.floor(num*100+0.50000000001);
   cents = num;
   num = Math.floor(num/100).toString();
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
   num = num.substring(0,num.length-(4*i+3))+','+
   num.substring(num.length-(4*i+3));
   return (((sign)?'':'-') + num);
}
</script>
        
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
        var today_date = $('#start_date_0').val();
        var myDate = new Date(today_date);
        var totday = $('#tot_days').val()* 1;
        var newDate = addDays(myDate,totday);
        $('#end_date_0').val(newDate);
        var num_addrow = $('.type_bedq').length;
        
        }
        
        function changedate_airport(){
        var today_date = $('#at_airport_date').val();
        var myDate = new Date(today_date);
        var totday = (($('#tot_days').val()* 1)+1);
        var newDate = addDays(myDate,totday);
        $('#end_date_0').val(newDate);
        
        }
        
        function changedate2(){
        var num_addrow = $('.type_bedq').length;
        for (var i = 1; i <= num_addrow; i++) {
             var aa = 'start_date' + i;
             var bb = 'end_date' + i;
            var today_date = $('#'+aa+'').val();
            var myDate = new Date(today_date);
            var totday = $('#tot_days').val()* 1;
            var newDate = addDays(myDate,totday);
          
            $('#'+bb+'').val(newDate);
          }
        }
       $(document).ready(function () {
            $('#datetimepicker').datepicker();     // Id may be different here, you need to inspect element and check the ID.
            $('.uang').priceFormat({
                prefix: '',
                centsSeparator: '.',
                thousandsSeparator: ',',
                centsLimit: 0
            });
        })
        $(document).on('click', '.tour-edit', function(evt){
          $.post('".site_url("inventory/ajax/product-tour-detail")."', {id_product_tour_information: $(this).attr('isi')}, function(data){
            var hasil = $.parseJSON(data);
           
            $('#kode').val(hasil.kode);
            $('#id_product_tour_information').val(hasil.id_product_tour_information);
            $('#kode_ps').val(hasil.kode_ps);
            $('#start_date_0').val(hasil.start_date);
            $('#end_date_0').val(hasil.end_date);
            $('#start_time_1').val(hasil.start_time);
            $('#end_time_1').val(hasil.end_time);
            $('#available_seat').val(hasil.available_seat);
            $('#id_currency').val(hasil.id_currency);
            $('#keberangkatan').val(hasil.keberangkatan);
            $('#kurs_rate').val(hasil.kurs_rate);
            $('#adult_triple_twin_usd').val(hasil.adult_triple_twin_usd);
            $('#child_twin_bed_usd').val(hasil.child_twin_bed_usd);
            $('#child_extra_bed_usd').val(hasil.child_extra_bed_usd);
            $('#child_no_bed_usd').val(hasil.child_no_bed_usd);
            $('#sgl_supp_usd').val(hasil.sgl_supp_usd);
            $('#airport_tax_usd').val(hasil.airport_tax_usd);
            $('#visa_usd').val(hasil.visa_usd);
            $('#less_ticket_adl_usd').val(hasil.less_ticket_adl_usd);
            $('#less_ticket_adl').val(hasil.less_ticket_adl);
            $('#less_ticket_chl_usd').val(hasil.less_ticket_chl_usd);
            $('#less_ticket_chl').val(hasil.less_ticket_chl);
            
            $('#adult_triple_twin').val(hasil.adult_triple_twin);
            $('#child_twin_bed').val(hasil.child_twin_bed);
            $('#child_extra_bed').val(hasil.child_extra_bed);
            $('#child_no_bed').val(hasil.child_no_bed);
            $('#sgl_supp').val(hasil.sgl_supp);
            $('#airport_tax').val(hasil.airport_tax);
            $('#visa').val(hasil.visa);
            $('#stnb_discount_tetap').val(hasil.tour_discount);
            $('#flt').val(hasil.flt);
            $('#in').val(hasil.in);
            $('#out').val(hasil.out);
			$('#tampil').val(hasil.tampil);
			$('#condition').val(hasil.condition);
      
            $('#sts').val(hasil.sts);
            $('#at_airport').val(hasil.at_airport);
            $('#at_airport_date').val(hasil.at_airport_date);
            $('#remarks').val(hasil.remarks);
            
            tambah_discount(hasil.tour_discount);
          });
        });
        $(document).on('click', '.tour-copy', function(evt){
          $.post('".site_url("inventory/ajax/product-tour-detail")."', {id_product_tour_information: $(this).attr('isi')}, function(data){
            var hasil = $.parseJSON(data);
            
            $('#kode').val(hasil.kode);
            $('#id_product_tour_information').val('');
            $('#kode_ps').val(hasil.kode_ps);
            $('#start_date_0').val(hasil.start_date);
            $('#end_date_0').val(hasil.end_date);
            $('#start_time_1').val(hasil.start_time);
            $('#end_time_1').val(hasil.end_time);
            $('#available_seat').val(hasil.available_seat);
            $('#id_currency').val(hasil.id_currency);
            $('#keberangkatan').val(hasil.keberangkatan);
            $('#kurs_rate').val(hasil.kurs_rate);
            $('#adult_triple_twin_usd').val(hasil.adult_triple_twin_usd);
            $('#child_twin_bed_usd').val(hasil.child_twin_bed_usd);
            $('#child_extra_bed_usd').val(hasil.child_extra_bed_usd);
            $('#child_no_bed_usd').val(hasil.child_no_bed_usd);
            $('#sgl_supp_usd').val(hasil.sgl_supp_usd);
            $('#airport_tax_usd').val(hasil.airport_tax_usd);
            $('#visa_usd').val(hasil.visa_usd);
            $('#less_ticket_adl_usd').val(hasil.less_ticket_adl_usd);
            $('#less_ticket_adl').val(hasil.less_ticket_adl);
            $('#less_ticket_chl_usd').val(hasil.less_ticket_chl_usd);
            $('#less_ticket_chl').val(hasil.less_ticket_chl);

            $('#adult_triple_twin').val(hasil.adult_triple_twin);
            $('#child_twin_bed').val(hasil.child_twin_bed);
            $('#child_extra_bed').val(hasil.child_extra_bed);
            $('#child_no_bed').val(hasil.child_no_bed);
            $('#sgl_supp').val(hasil.sgl_supp);
            $('#airport_tax').val(hasil.airport_tax);
            $('#visa').val(hasil.visa);
            $('#stnb_discount_tetap').val(hasil.tour_discount);
            $('#flt').val(hasil.flt);
            $('#in').val(hasil.in);
            $('#out').val(hasil.out);
			$('#tampil').val(hasil.tampil);
			$('#condition').val(hasil.condition);
                        $('#sts').val(hasil.sts);
            $('#at_airport').val(hasil.at_airport);
            $('#at_airport_date').val(hasil.at_airport_date);
            $('#remarks').val(hasil.remarks);
            tambah_discount(hasil.tour_discount);
          });
        });
        
		$(document).on('click', '.tour-delete', function(evt){
         if (confirm('Do you wan\'t to delete this item?')) {
              $.post('".site_url("inventory/ajax/delete-product-tour-information")."', {id_product_tour_information: $(this).attr('isi')}, function(data){
              location.reload();
              });
          }
        });
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
        $(document).on('click', '#add-schedule', function(evt){
         tambah_discount();
        });
        $(document).on('click', '.tour-edit', function(evt){
         tambah_discount();
        });
        function tambah_discount(discount){
         $('.dt_discount').remove();
           var dataString2 = 'id_discount='+ discount;
      $.ajax({
      type : 'POST',
      url : '".site_url("inventory/ajax/add-discount")."',
      data: dataString2,
      dataType : 'html',
      success: function(data) {
            $('#tambah-items-discount').append(data);
      },
    });
        }
        
function add_tambah_discount(){
        var discount2 = $('#stnb_discount_tetap').val();
        
         $('.dt_discount').remove();
           var dataString2 = 'id_discount='+ discount2;
      $.ajax({
      type : 'POST',
      url : '".site_url("inventory/ajax/add-discount")."',
      data: dataString2,
      dataType : 'html',
      success: function(data) {
            $('#tambah-items-discount').append(data);
      },
    });
        }
        
        function copy_tambah_items(id){
      
        var dataString2 = 'id_tour_information='+ id;
      $.ajax({
      type : 'POST',
      url : '".site_url("ajax/copy-add-row-product-tour")."',
      data: dataString2,
      dataType : 'html',
      success: function(data) {
            $('#copy-tambah-items').append(data);
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
<script> 
        ";
      $dropdown_disc = $this->global_models->get_dropdown("product_tour_master_discount", "id_product_tour_master_discount", "name", TRUE, array("status" => 1));     
   
      $this->template->build("product-tour/add-product-tour", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/product-tour',
              'title'       => lang("product_tour"),
             'detail'      => $detail,
             'dropdown'        => $dropdown,
             'dropdown_disc'  => $dropdown_disc,
             'data_id_product_tour' => $id_product_tour,
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
//      $this->debug($pst, true);
      $config['upload_path'] = './files/antavaya/product_tour/';
      $config['allowed_types'] = '*';
      $config['max_width']  = '2000';
      $config['max_height']  = '2000';

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
      
      if($_FILES['file_itin']['name']){
        if (  $this->upload->do_upload('file_itin')){
          $data_itin = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("inventory/product-tour/add-product-tour/".$id_product_tour)."'>Back</a>";
          die;
        }
      }
      
      if($pst['push_selling'] == 1){
        $ph_sell = $pst['push_selling'];
      }else{
         $ph_sell = 2;
      }
//      $this->debug($data_thumb, true);
      if($pst['id_detail']){
	  
	   $data_mail = array("night"      => $pst['night'],
                     "airlines"   => $pst['airlines'],
                      "days"      => $pst['days'],
                      "id_product_tour" => $pst['id_detail']);
        $this->load->model('inventory/mnotice_mail');
        $this->mnotice_mail->notice_update_product_tour($data_mail);
        
        $data2 = array( "status" => $pst['status'],
                      "id_product_tour" => $pst['id_detail']);
        
      
        $this->mnotice_mail->notice_status_draft_product_tour($data2);
                
				
        $kirim = array(
            "title"           => $pst['title'],
          //  "sub_title"       => $pst['sub_title'],
         //   "summary"         => $pst['summary'],
            "category"        => $pst['category'],
            "no_pn"        => $pst['no_pn'],
            "sub_category"    => $pst['sub_category'],
            "destination"     => $pst['destination'],
            "landmark"        => $pst['landmark'],
            "product_cabang"  => $pst['product_cabang'],
            "selling_poin"    => $pst['selling_poin'],
            "days"            => $pst['days'],
            "night"           => $pst['night'],
            "airlines"        => $pst['airlines'],
            "status"          => $pst['status'],
            "push_selling"    => $ph_sell,
            "hot_deal"        => $pst['hot_deal'],
            "note"            => $pst['note'],
            "toc"            => $pst['toc'],
			"category_product"    => $pst['category_product'],
            "update_by_users" => $this->session->userdata("id"),
			"update_date"	=> date("Y-m-d H:i:s"),
        );
        if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
        if($data_thumb['upload_data']['file_name']){
          $kirim['file_thumb'] = $data_thumb['upload_data']['file_name'];
        }
        if($data_itin['upload_data']['file_name']){
          $kirim['file_itin'] = $data_itin['upload_data']['file_name'];
        }
       
         $this->global_models->update("product_tour", array("id_product_tour" => $pst['id_detail']),$kirim);
//         foreach ($pst['start_date'] as $key => $start_date) {
//                if($start_date){
//                  if($pst['kode'][$key]){
//                    $kode_info = $pst['kode'][$key];
//                  }else{
//                    $this->olah_tour_code_information($kode_info);
//                    $kode_info = $kode_info;
//                  }
//                  $adult_triple_twin = str_replace(",", "", $pst['adult_triple_twin'][$key]);
//                  $child_twin_bed = str_replace(",", "", $pst['child_twin_bed'][$key]);
//                  $child_extra_bed = str_replace(",", "", $pst['child_extra_bed'][$key]);
//                  $child_no_bed = str_replace(",", "", $pst['child_no_bed'][$key]);
//                  $sgl_supp = str_replace(",", "", $pst['sgl_supp'][$key]);
//                  $airport_tax = str_replace(",", "", $pst['airport_tax'][$key]);
//                  $visa = str_replace(",", "", $pst['visa'][$key]);
//               $kirim_info = array(
//                "id_product_tour"                         => $id_product_tour,
//                "id_product_tour_information"           => $pst['id_product_tour_information'][$key],
//                "start_date"                            => $pst['start_date'][$key],
//                "kode"                                  => $kode_info,  
//                "kode_ps"                               => $pst['kode_ps'][$key],
//                "end_date"                              => $pst['end_date'][$key],
//                "available_seat"                        => $pst['available_seat'][$key],
//                "adult_triple_twin"                     => $adult_triple_twin,
//                "child_twin_bed"                        => $child_twin_bed,
//                "child_extra_bed"                       => $child_extra_bed,
//                "child_no_bed"                          => $child_no_bed,  
//                "sgl_supp"                              => $sgl_supp,
//                "airport_tax"                           => $airport_tax,
//                  "visa"                                  => $visa,
//                "start_time"                           => $pst['etd'][$key],
//                  "end_time"                           => $pst['eta'][$key],
//                "id_currency"                           => $pst['id_currency'][$key],
//                "flt"                                 => $pst['flt'][$key],
//            //    "sts"                                 => $pst['sts'][$key],
//                "in"                                    => $pst['in'][$key],
//                "out"                                  => $pst['out'][$key],
//             //   "dp"                                    => $pst['dp'][$key],
//             //   "stnb_dp"                               => $pst['stnb_dp'][$key],
//                "discount_tetap"                        => $pst['discount_tetap'][$key],
//                "stnb_discount_tetap"                   => $pst['stnb_discount_tetap'][$key],
//             //   "discount_tambahan"                     => $pst['discount_tambahan'][$key],
//             //   "stnb_discount_tambahan"                => $pst['stnb_discount_tambahan'][$key],
//              "create_by_users"                         => $this->session->userdata("id"),
//              "create_date"                             => date("Y-m-d H:i:s")
//            );
//            
//            $else_kirim_info = array(
//            "id_product_tour"                      => $id_product_tour,
//            "id_product_tour_information"           => $pst['id_product_tour_information'][$key],
//            "start_date"                            => $pst['start_date'][$key],
//            "kode"                                  => $kode_info, 
//            "kode_ps"                               => $pst['kode_ps'][$key],
//            "end_date"                              => $pst['end_date'][$key],
//              "start_time"                           => $pst['etd'][$key],
//                  "end_time"                           => $pst['eta'][$key],
//            "available_seat"                        => $pst['available_seat'][$key],
//            "adult_triple_twin"                         => $adult_triple_twin,
//                "child_twin_bed"                        => $child_twin_bed,
//                "child_extra_bed"                       => $child_extra_bed,
//                "child_no_bed"                          => $child_no_bed,  
//                "sgl_supp"                              => $sgl_supp,
//                "airport_tax"                           => $airport_tax,
//                  "visa"                                => $visa,
//            "id_currency"                           => $pst['id_currency'][$key], 
//          //  "days"                                => $pst['days'][$key],
//            "flt"                                 => $pst['flt'][$key],
//         //   "sts"                                 => $pst['sts'][$key],
//            "in"                                    => $pst['in'][$key],
//                "out"                                  => $pst['out'][$key],
//          //  "dp"                                    => $pst['dp'][$key],
//          //  "stnb_dp"                               => $pst['stnb_dp'][$key],
//            "discount_tetap"                        => $pst['discount_tetap'][$key],
//            "stnb_discount_tetap"                   => $pst['stnb_discount_tetap'][$key],
//          //  "discount_tambahan"                     => $pst['discount_tambahan'][$key],
//          //  "stnb_discount_tambahan"                => $pst['stnb_discount_tambahan'][$key],
//            "update_by_users"                       => $this->session->userdata("id"),
//            );
//            $this->global_models->update_duplicate("product_tour_information", $kirim_info, $else_kirim_info);
//          }
//        }
        
      }
      else{
        $this->olah_tour_code($kode);
        $kirim = array(
            "id_store"        => $this->session->userdata('store'),
            "id_store_region"        => $this->session->userdata('store_region'),
            "title"           => trim($pst['title']),
            "kode"            => $kode,
          //  "sub_title"       => $pst['sub_title'],
          // "summary"         => $pst['summary'],
            "category"        => $pst['category'],
            "no_pn"           => $pst['no_pn'],
            "sub_category"    => $pst['sub_category'],
            "destination"     => $pst['destination'],
            "landmark"        => $pst['landmark'],
            "days"            => $pst['days'],
            "product_cabang"  => $pst['product_cabang'],
            "selling_poin"    => $pst['selling_poin'],
            "night"           => $pst['night'],
            "airlines"        => $pst['airlines'],
            "status"          => $pst['status'],
            "push_selling"    => $ph_sell,
            "hot_deal"        => $pst['hot_deal'],
            "note"            => $pst['note'],
            "toc"            => $pst['toc'],
			"category_product"    => $pst['category_product'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
//        $this->debug($kirim, true);
        if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
        if($data_thumb['upload_data']['file_name']){
          $kirim['file_thumb'] = $data_thumb['upload_data']['file_name'];
        }
//       print "<pre>";
//       print_r($pst);
//       print "</pre>";
//       die;
        $id_product_tour = $this->global_models->insert("product_tour", $kirim);
        
//        foreach ($pst['start_date'] as $key => $start_date) {
//                if($start_date){
//                $this->olah_tour_code_information($kode_info);
//                  $adult_triple_twin[$key] = str_replace(",", "", $pst['adult_triple_twin'][$key]);
//                  $child_twin_bed[$key] = str_replace(",", "", $pst['child_twin_bed'][$key]);
//                  $child_extra_bed[$key] = str_replace(",", "", $pst['child_extra_bed'][$key]);
//                  $child_no_bed[$key] = str_replace(",", "", $pst['child_no_bed'][$key]);
//                  $sgl_supp[$key] = str_replace(",", "", $pst['sgl_supp'][$key]);
//                  $airport_tax[$key] = str_replace(",", "", $pst['airport_tax'][$key]);
//                  $visa[$key] = str_replace(",", "", $pst['visa'][$key]);
//                
//            $kirim_info = array(
//                "kode"                                  => $kode_info,
//                 "kode_ps"                              => $pst['kode_ps'][$key],
//                "id_product_tour"                       => $id_product_tour,
//                "start_date"                            => $pst['start_date'][$key],
//                "end_date"                              => $pst['end_date'][$key],
//                "available_seat"                        => $pst['available_seat'][$key],
//                "adult_triple_twin"                     => $adult_triple_twin[$key],
//                "child_twin_bed"                        => $child_twin_bed[$key],
//                "child_extra_bed"                       => $child_extra_bed[$key],
//                "child_no_bed"                          => $child_no_bed[$key],  
//                "sgl_supp"                              => $sgl_supp[$key],
//                "airport_tax"                           => $airport_tax[$key],
//                "visa"                                  => $visa[$key],
//                "id_currency"                           => $pst['id_currency'][$key], 
//              "start_time"                           => $pst['etd'][$key],
//                  "end_time"                           => $pst['eta'][$key],
//             // "days"                                  => $pst['days'][$key],
//                "flt"                                 => $pst['flt'][$key],
//              //  "sts"                                 => $pst['sts'][$key],
//                 "in"                                    => $pst['in'][$key],
//                  "out"                                  => $pst['out'][$key],
//            //     "remarks"                             => $pst['remarks'][$key],
//           //     "dp"                                    => $pst['dp'][$key],
//             //   "stnb_dp"                               => $pst['stnb_dp'][$key],
//                "discount_tetap"                        => $pst['discount_tetap'][$key],
//                "stnb_discount_tetap"                   => $pst['stnb_discount_tetap'][$key],
//               // "discount_tambahan"                     => $pst['discount_tambahan'][$key],
//              //  "stnb_discount_tambahan"                => $pst['stnb_discount_tambahan'][$key],
//                "create_by_users"                       => $this->session->userdata("id"),
//                "create_date"                           => date("Y-m-d H:i:s")
//                );
//                  //  print_r($kirim_info); die;
//                $this->global_models->insert("product_tour_information", $kirim_info);
//            }
//        }
        
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
  
  public function copy_product_tour($id_product_tour = 0){
     
    
    $detail = $this->global_models->get("product_tour", array("id_product_tour" => $id_product_tour));
   $detail_info = $this->global_models->get("product_tour_information", array("id_product_tour" => $id_product_tour));
//    print "<pre>";
//   print_r($detail_info);
//   print "</pre>"; die;
   
    if($detail[0]->id_product_tour){
      
        $this->olah_tour_code($kode);
        $kirim = array(
            "id_store"        => $this->session->userdata('store'),
            "id_store_region"        => $this->session->userdata('store_region'),
            "title"           => trim($detail[0]->title),
            "kode"            => $kode,
            "category"        => $detail[0]->category,
            "no_pn"           => $detail[0]->no_pn,
            "sub_category"    => $detail[0]->sub_category,
            "destination"     => $detail[0]->destination,
            "landmark"        => $detail[0]->landmark,
            "days"            => $detail[0]->days,
            "night"           => $detail[0]->night,
            "airlines"        => $detail[0]->airlines,
            "status"          => $detail[0]->status,
            "push_selling"    => $detail[0]->push_selling,
            "note"            => $detail[0]->note,
			"category_product"   => $detail[0]->category_product,
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        $id_product_tour_dt = $this->global_models->insert("product_tour", $kirim);
        
        foreach ($detail_info as $key => $vl) {
                
          $this->olah_tour_code_information($kode_info);
          $kirim_info = array(
            "kode"                                  => $kode_info,
            "kode_ps"                               => $vl->kode_ps,
            "visa"                                  => $vl->visa,  
            "id_product_tour"                       => $id_product_tour_dt,
            "start_date"                            => $vl->start_date,
            "end_date"                              => $vl->end_date,
            "available_seat"                        => $vl->available_seat,
            "adult_triple_twin"                     => $vl->adult_triple_twin,
            "child_twin_bed"                        => $vl->child_twin_bed,
            "child_extra_bed"                       => $vl->child_extra_bed,
            "child_no_bed"                          => $vl->child_no_bed,  
            "sgl_supp"                              => $vl->sgl_supp,
            "airport_tax"                           => $vl->airport_tax, 
            "id_currency"                           => $vl->id_currency, 
            "start_time"                            => $vl->start_time,
            "end_time"                              => $vl->end_time,
            "flt"                                   => $vl->flt,
            "in"                                    => $vl->in,
            "out"                                   => $vl->out,
			"tampil"                                => $vl->tampil,
			"status"                                => $vl->status,
            "discount_tetap"                        => $vl->discount_tetap,
            "stnb_discount_tetap"                   => $vl->stnb_discount_tetap,
            "create_by_users"                       => $this->session->userdata("id"),
            "create_date"                           => date("Y-m-d H:i:s")
          );
          $this->global_models->insert("product_tour_information", $kirim_info);
        }
    
      if($id_product_tour_dt){
        $this->session->set_flashdata('success', 'Data Berhasil Di Copy dengan Tour Code '.$kode.'. Tour Code Sebelumnya '.$detail[0]->title);
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/product-tour");
    }
  }
  
  function tour_detail($id_product_tour = 0){
   $detail = $this->global_models->get("product_tour", array("id_product_tour" => $id_product_tour));
  // $this->debug($detail_array, true);
   $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));     
   
   $where = " AND id_product_tour = '".$detail[0]->id_product_tour."'";
   $info = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_information"
          . " WHERE 1 = 1 AND tampil=1"
          . " {$where}"
          . " ORDER BY start_date ASC");
          
        foreach($info AS $fo){
//          $book = $this->global_models->get_query("SELECT SUM(adult_triple_twin) AS a, SUM(child_twin_bed) AS c, SUM(child_extra_bed) AS d,SUM(child_no_bed) AS e,SUM(sgl_supp) AS f"
//              . " FROM product_tour_book"
//              . " WHERE id_product_tour_information = '{$fo->id_product_tour_information}'"
//              . " AND (status = 2 OR status = 3)");
              
               $book = $this->global_models->get_query("SELECT count(A.kode) AS aid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$fo->id_product_tour_information}'"
              . " AND (A.status = 2 OR A.status = 3)");
          $information[] = array(
            "id_product_tour_information"                  => $fo->id_product_tour_information,
            "code"              => $fo->kode,
            "start_date"        => $fo->start_date,
            "end_date"          => $fo->end_date,
            "start_time"        => $fo->start_time,
            "end_time"          => $fo->end_time,
            "days"              => $fo->days,
            "flt"               => $fo->flt,
          //  "sts"              => $fo->sts,
            "in"                => $fo->in,
            "out"               => $fo->out,
            "seat"              => $fo->available_seat,
            "dp"                => $fo->dp,
            "stnb_dp"           => $fo->stnb_dp,
            "id_currency"       => $fo->id_currency,
            "discount_tetap"    => $fo->discount_tetap,
            "stnb_discount_tetap" => $fo->stnb_discount_tetap,
            "discount_tambahan" => $fo->discount_tambahan,
            "stnb_discount_tambahan" => $fo->stnb_discount_tambahan,
            "available_seat"    => ($fo->available_seat - ($book[0]->aid )),
            "price"             => array("adult_triple_twin" => $fo->adult_triple_twin,"child_twin_bed" => $fo->child_twin_bed,"child_extra_bed" => $fo->child_extra_bed,"child_no_bed" => $fo->child_no_bed,"sgl_supp" => $fo->sgl_supp, "airport_tax" => $fo->airport_tax,),
          
          );
        }
        $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
        $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia & New Zealand", 5 => "Asia", 6 => "China");
        $css = ""
        . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />";
         $foot = "
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.price_format.1.8.min.js' type='text/javascript'></script>
        <script type='text/javascript'>
        $(function() {
              $('#example1').dataTable();
        });
        $(document).on('click', '.tour-edit', function(evt){
          $.post('".site_url("inventory/ajax/tour-detail")."', {id_product_tour_information: $(this).attr('isi')}, function(data){
            var hasil = $.parseJSON(data);
           
            $('#kode').val(hasil.kode);
            $('#id_product_tour_information').val(hasil.id_product_tour_information);
            $('#kode_ps').val(hasil.kode_ps);
            $('#start_date_0').val(hasil.start_date);
            $('#end_date_0').val(hasil.end_date);
            $('#start_time_1').val(hasil.start_time);
            $('#end_time_1').val(hasil.end_time);
            $('#available_seat').val(hasil.available_seat);
            $('#id_currency').val(hasil.id_currency);
            $('#adult_triple_twin').val(hasil.adult_triple_twin);
            $('#child_twin_bed').val(hasil.child_twin_bed);
            $('#child_extra_bed').val(hasil.child_extra_bed);
            $('#child_no_bed').val(hasil.child_no_bed);
            $('#sgl_supp').val(hasil.sgl_supp);
            $('#airport_tax').val(hasil.airport_tax);
            $('#visa').val(hasil.visa);
            $('#stnb_discount_tetap').val(hasil.tour_discount);
            $('#flt').val(hasil.flt);
            $('#in').val(hasil.in);
            $('#out').val(hasil.out);
         tambah_discount(hasil.tour_discount);
          });
          
        });
        
function tambah_discount(discount){
            $('.dt_discount').remove();
           var dataString2 = 'id_discount='+ discount;
      $.ajax({
      type : 'POST',
      url : '".site_url("inventory/ajax/add-discount")."',
      data: dataString2,
      dataType : 'html',
      success: function(data) {
            $('#tambah-items-discount').append(data);
      },
    });
        }
       
       
    </script>";
      $dropdown_disc = $this->global_models->get_dropdown("product_tour_master_discount", "id_product_tour_master_discount", "name", TRUE, array("status" => 1));     
   
    $this->template->build('product-tour/tour-detail', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "inventory/product-tour",
            'data'          => $detail,
            'information'   => $information,
            'category'     => $category,
            'foot'          =>$foot,
            'css'           =>$css,
            'dropdown'              => $dropdown,
            'dropdown_disc'              => $dropdown_disc,
            'sub_category' => $sub_category,
            'title'         => lang("Detail Book Product Tour"),
            'breadcrumb'    => array(
            "product_tour"  => "grouptour/product-tour"
            ),
          ));
    $this->template
      ->set_layout('form')
      ->build('product-tour/tour-detail');
  }
  function proses_file($id_csv_file){
      
    $detail = $this->global_models->get("csv_file", array("id_csv_file" => $id_csv_file));
      $this->load->library('excel_reader');
    
    if($detail[0]->id_csv_file > 0){
      if($detail[0]->kategori == 1){
        $file = "./files/antavaya/csv/".$detail[0]->file;

      $this->excel_reader->read($file);
    $worksheet = $this->excel_reader->sheets[0];
    $numRows = $worksheet['numRows']; // ex: 14
    $numCols = $worksheet['numCols']; // ex: 4
    $cells = $worksheet['cells']; // the 1

        for($i=10; $i <= $numRows; $i++){
          $this->olah_tour_code($kode);
          $kirim = array(
            "id_store"        => $this->session->userdata('store'),
            "id_store_region"        => $this->session->userdata('store_region'),
            "kode"            => $kode,
            "title"           => trim($cells[$i][1]),
            "destination"     => $cells[$i][2],
            "landmark"        => $cells[$i][3],
            "days"            => $cells[$i][4],
            "night"           => $cells[$i][5],
            "airlines"        => $cells[$i][6],
            "category"        => $cells[$i][7],
            "sub_category"    => $cells[$i][8],
            "push_selling"    => 2,
            "note"            => $cells[$i][9],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        $id_product_tour = $this->global_models->insert("product_tour", $kirim);
        }
    
    $kirim = array(
            "status"         => 2,
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_csv_file = $this->global_models->update("csv_file", array("id_csv_file" => $detail[0]->id_csv_file),$kirim);
        
     if($id_csv_file){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
       redirect("inventory/product-tour/list-file");
    
       
      }elseif($detail[0]->kategori == 2){
      $file = "./files/antavaya/csv/".$detail[0]->file;

      $this->excel_reader->read($file);
    $worksheet = $this->excel_reader->sheets[0];
    $numRows = $worksheet['numRows']; // ex: 14
    $numCols = $worksheet['numCols']; // ex: 4
    $cells = $worksheet['cells']; // the 1
    
//      $data = @file_get_contents($file);
//      $Data = explode(PHP_EOL, $data);
//     $this->debug($Data, true); die;
     for($i=9; $i <= $numRows; $i++){
       $tour_kode = $cells[$i][1];
       $pt = $this->global_models->get("product_tour", array("kode" =>$tour_kode ));
        $this->olah_tour_code_information($kode);
       $UNIX_DATE = ($cells[$i][2] - 25569) * 86400;
        $start_date = gmdate("Y-m-d", $UNIX_DATE);
         $start_time = gmdate("H:i", $UNIX_DATE);
         
        $UNIX_DATE2 = ($cells[$i][3] - 25569) * 86400;
        $end_date = gmdate("Y-m-d", $UNIX_DATE2);
        $end_time = gmdate("H:i", $UNIX_DATE2);
        
//        print $cells[$i][3]."<br>".$UNIX_DATE3."<br>".$cells[$i][5]."<br>".$end_time; die;
        $kirim = array(
            "id_product_tour"                 => $pt[0]->id_product_tour,
            "kode"                            => $kode,
            "start_date"                      => $start_date,
            "start_time"                      => $start_time,
            "end_date"                        => $end_date,
            "end_time"                        => $end_time,
            "available_seat"                  => $cells[$i][4],
            "id_currency"                     => $cells[$i][5],
            "flt"                             => $cells[$i][6],
            "in"                              => $cells[$i][7],
            "out"                             => $cells[$i][8],
            "adult_triple_twin"               => $cells[$i][9],
            "child_twin_bed"                  => $cells[$i][10],
            "child_extra_bed"                 => $cells[$i][11],
            "child_no_bed"                    => $cells[$i][12],
            "sgl_supp"                        => $cells[$i][13],
            "airport_tax"                     => $cells[$i][14],
            "stnb_discount_tetap"             => $cells[$i][15],  
            "discount_tetap"                  => $cells[$i][16],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        $id_product_tour = $this->global_models->insert("product_tour_information", $kirim);
     }
    
    $kirim = array(
            "status"         => 2,
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_csv_file = $this->global_models->update("csv_file", array("id_csv_file" => $detail[0]->id_csv_file),$kirim);
        
     if($id_csv_file){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
       redirect("inventory/product-tour/list-file");
    
   // $this->debug($Data, true);
      }elseif($detail[0]->kategori == 3){
       $file = "./files/antavaya/csv/".$detail[0]->file;

      $this->excel_reader->read($file);
      $worksheet = $this->excel_reader->sheets[0];
      $numRows = $worksheet['numRows']; // ex: 14
      $numCols = $worksheet['numCols']; // ex: 4
      $cells = $worksheet['cells']; // the 1
      
      $arr_region = array(1 =>"eropa",
                          2 => "africa",
                          3 => "america",
                          4 => "australia",
                          5 => "asia");
      
//      print "<pre>";
//      print_r($worksheet); 
//      print "</pre>"; die;
//      $data = @file_get_contents($file);
//      $Data = explode(PHP_EOL, $data);
//     $this->debug($Data, true); die;
    for($i=3; $i <= $numRows; $i++){
      
        if($cells[$i][3] == "eropa"){
        $sub_category = 1;
        }elseif($cells[$i][3] == "africa"){
        $sub_category = 2;
        }elseif($cells[$i][3] == "america"){
        $sub_category = 3;
        }elseif($cells[$i][3] == "australia"){
        $sub_category = 4;
        }elseif($cells[$i][3] == "asia"){
        $sub_category = 5;
        }
        
        $att = str_replace(",", ".", $cells[$i][18]);
        $ctb = str_replace(",", ".", $cells[$i][19]);
        $ceb = str_replace(",", ".", $cells[$i][20]);
        $cnb = str_replace(",", ".", $cells[$i][21]);
         $ss = str_replace(",", ".", $cells[$i][22]);
         $at = str_replace(",", ".", $cells[$i][23]);
         
        $jt = 1000000;
        if($att > 0){
          $att_1 = $att * $jt ;
        }else{
          $att_1 = 0;
        }
    
        
        if($ctb > 0){
          $ctb_1 = $ctb * $jt ;
        }else{
          $ctb_1 = 0;
        }
       
        
        if($ceb > 0){
          $ceb_1 = $ceb * $jt ;
        }else{
          $ceb_1 = 0;
        }
        
        
        if($cnb > 0){
          $cnb_1 = $cnb * $jt ;
        }else{
          $cnb_1 = 0;
        }
        
       
        if($ss > 0){
          $ss_1 = $ss * $jt ;
        }else{
          $ss_1 = 0;
        }    
         
        
        if($at > 0){
          $at_1 = $at * $jt ;
        }else{
          $at_1 = 0;
        }
		
        $str = $cells[$i][8];
        $data_start_date = (explode(" ",$str));
        $str2 = $cells[$i][10];
        $data_end_date = (explode(" ",$str2));
        $dttgl = array("AUG" => "08",
              "SEP"  => "09",
              "OCT"  => "10",
              "NOV"  => "11",
              "DEC"  => "12");
//        2015-12-03
//        print_r($data_start_date[1]); 
        $dtbln = $dttgl[$data_start_date[1]];
        $dthr = $data_start_date[0];
        $jmlh = count($dthr);
        if($jmlh == 1){
          $data_hari = "0".$dthr;
        }else{
          $data_hari = $dthr;
        }
        
        $start_date = "2015-".$dtbln."-".$data_hari;
        
        $dtbln2 = $dttgl[$data_end_date[1]];
        $dthr2 = $data_end_date[0];
      $jmlh2  = count($dthr2);
      if($jmlh2 == 1){
        $data_hari2 = "0".$dthr2;
      }else{
        $data_hari2 = $dthr2;
      }
      
      $end_date = "2015-".$dtbln2."-".$data_hari2;
       
//       $UNIX_DATE = ($cells[$i][8] - 25569) * 86400;
//        $start_date = gmdate("Y-m-d", $UNIX_DATE);
//         
//         
//        $UNIX_DATE2 = ($cells[$i][10] - 25569) * 86400;
//        $end_date = gmdate("Y-m-d", $UNIX_DATE2);
//        
//        
       $in_out = explode("/",$cells[$i][15]);
       $title = trim($cells[$i][2]);
       $days = $cells[$i][4];
//        
//        $UNIX_DATE_1 = ($cells[$i][9] - 25569) * 86400;
//      
//        $UNIX_DATE_2 = ($cells[$i][11] - 25569) * 86400;
        
          $str_time = $cells[$i][9];
         $data_start_time = (explode(".",$str_time));
         $jam = $data_start_time[0];
         $menit = $data_start_time[1];
         $start_time = $jam.":".$menit;
         
         $str_time2 = $cells[$i][11];
         $data_start_time2 = (explode(".",$str_time2));
         $jam2 = $data_start_time2[0];
         $menit2 = $data_start_time2[1];
         $end_time = $jam2.":".$menit2;
        
         
         
//        if($cells[$i][9]){
//          $start_time = gmdate("H:i", $UNIX_DATE_1);
//        }else{
//          $start_time = "";
//        }
//        
//        if($cells[$i][11]){
//          $end_time = gmdate("H:i", $UNIX_DATE_2);
//        }else{
//          $end_time = "";
//        }
        
        $dt = $this->global_models->get("product_tour", array("title" =>$title,"days" => $days, 'id_store' => '2' ));
        if($dt[0]->id_product_tour > 0){
          $dt_info = $this->global_models->get("product_tour_information", array("id_product_tour" =>$dt[0]->id_product_tour,"start_date" => $start_date ));
          if($dt_info[0]->id_product_tour_information > 0 ){
              $kirim8 = array(
            "available_seat"                  => $cells[$i][13],
            "seat_update"                  => $cells[$i][13],
          );
          $data_id_product_tour_master_visa = $this->global_models->update("product_tour_information", array("id_product_tour_information" => $dt_info[0]->id_product_tour_information),$kirim8);

          }else{
            $this->olah_tour_code_information($kode_tci);
          $kirim = array(
            "id_product_tour"                 => $dt[0]->id_product_tour,
            "kode"                            => $kode_tci,
            "start_date"                      => $start_date,
            "start_time"                      => $start_time,
            "end_date"                        => $end_date,
            "end_time"                        => $end_time,
            "available_seat"                  => $cells[$i][13],
            "seat_update"                     => $cells[$i][13],
            "id_currency"                     => 2,
            "flt"                             => $cells[$i][12],
            "in"                              => $in_out[0],
            "out"                             => $in_out[1],
            "adult_triple_twin"               => $att_1,
            "child_twin_bed"                  => $ctb_1,
            "child_extra_bed"                 => $ceb_1,
            "child_no_bed"                    => $cnb_1,
            "sgl_supp"                        => $ss_1,
            "airport_tax"                     => $at_1,
            "create_by_users"                 => $this->session->userdata("id"),
            "create_date"                     => date("Y-m-d H:i:s")
        );
        $id_product_tour = $this->global_models->insert("product_tour_information", $kirim);
          }
          
        }else{
          if($title){
              $this->olah_tour_code($kode_tc);
           $kirim = array(
            "kode"            => $kode_tc,
            "title"           => $title,
            "days"            => $days,
            "sub_category"    => $sub_category,
            "push_selling"    => 2,
            "status"          => 1,
             "id_store"			=> 2,
            "no_pn"           => $cells[$i][5],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d H:i:s")
          );
          $id_product_tour = $this->global_models->insert("product_tour", $kirim);
          
          $this->olah_tour_code_information($kode_tci);
          $kirim = array(
            "id_product_tour"                 => $id_product_tour,
            "kode"                            => $kode_tci,
            "start_date"                      => $start_date,
            "start_time"                      => $start_time,
            "end_date"                        => $end_date,
            "end_time"                        => $end_time,
            "available_seat"                  => $cells[$i][13],
            "seat_update"                     => $cells[$i][13],
            "id_currency"                     => 2,
            "flt"                             => $cells[$i][12],
            "in"                              => $in_out[0],
            "out"                             => $in_out[1],
            "adult_triple_twin"               => $att_1,
            "child_twin_bed"                  => $ctb_1,
            "child_extra_bed"                 => $ceb_1,
            "child_no_bed"                    => $cnb_1,
            "sgl_supp"                        => $ss_1,
            "airport_tax"                     => $at_1,
            "create_by_users"                 => $this->session->userdata("id"),
            "create_date"                     => date("Y-m-d H:i:s")
        );
        $id_product_tour_info = $this->global_models->insert("product_tour_information", $kirim);
          }
          
        }
         
     }
    
    $kirim = array(
            "status"         => 2,
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_csv_file = $this->global_models->update("csv_file", array("id_csv_file" => $detail[0]->id_csv_file),$kirim);
        
     if($id_csv_file){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
       redirect("inventory/product-tour/list-file");
      }
      
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
  
  private function olah_tour_code_information(&$kode_info){
    $this->load->helper('string');
    $kode_info_data = random_string('alnum', 8);
    $kode_info = strtoupper($kode_info_data);
    $cek = $this->global_models->get_field("product_tour_information", "id_product_tour_information", array("kode" => $kode_info));
    if($cek > 0){
      $this->olah_tour_code_information($kode_info);
    }
    return true;
  }
  
  function upload_file_tour_info(){
  
    if(!$this->input->post(NULL)){
      $this->template->build('product-tour/upload-file-tour-info', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "grouptour/product-tour",
            'data'          => $detail,
            'title'         => lang("Upload File Product Tour Information"),
            'breadcrumb'    => array(
            "product_tour"  => "inventory/product-tour"
            ),
          ));
    $this->template
      ->set_layout('form')
      ->build('product-tour/upload-file-tour-info');
    }else{
      $pst = $this->input->post(NULL);
//      print "<pre>";
//      print_r($pst); 
//      print "<pre>";
//      die;
      $config['upload_path'] = './files/antavaya/csv/';
      $config['allowed_types'] = '*';

      $this->load->library('upload', $config);
      
      if($_FILES['file']['name']){
        if (  $this->upload->do_upload('file')){
          $data = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("inventory/product-tour/upload-file/")."'>Back</a>";
          die;
        }
      }
       $kirim = array(
                "status"                                => 1,
                "kategori"                              => 2,
                "create_by_users"                       => $this->session->userdata("id"),
                "create_date"                           => date("Y-m-d H:i:s")
                );
//      $this->debug($data_thumb, true);
      if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
       
        $id_csv_file = $this->global_models->insert("csv_file", $kirim);
        
      if($id_csv_file){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/product-tour/upload-file");
    }
  }
  
  function upload_file(){
   // print_r($_FILES); die;
    if(!$this->input->post(NULL)){
	 $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>
       
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script>
        <script type='text/javascript'>
            $(function() {
              CKEDITOR.replace('editor2');
              $( '.start_date' ).datetimepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
              
              $( '.end_date' ).datetimepicker({
                showOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
            });
        </script>
        
   
       
        ";
      $this->template->build('product-tour/upload-file', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "grouptour/product-tour",
            'data'          => $detail,
            'title'         => lang("Upload File Product Tour"),
            'breadcrumb'    => array(
            "product_tour"  => "inventory/product-tour"
            ),
			 'css'         => $css,
              'foot'        => $foot
          ));
    $this->template
      ->set_layout('form')
      ->build('product-tour/upload-file');
    }else{
      $pst = $this->input->post(NULL);
//      print "<pre>";
//      print_r($pst); 
//      print "<pre>";
//      die;
      $config['upload_path'] = './files/antavaya/csv/';
      $config['allowed_types'] = '*';

      $this->load->library('upload', $config);
      
      if($_FILES['file']['name']){
        if (  $this->upload->do_upload('file')){
          $data = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("inventory/product-tour/upload-file/")."'>Back</a>";
          die;
        }
      }
       $kirim = array(
                "status"                                => 1,
                "kategori"                              => 1,
                "create_by_users"                       => $this->session->userdata("id"),
                "create_date"                           => date("Y-m-d H:i:s")
                );
//      $this->debug($data_thumb, true);
      if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
       
        $id_csv_file = $this->global_models->insert("csv_file", $kirim);
        
      if($id_csv_file){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/product-tour/upload-file");
    }
  }
  function upload_tour(){
   // print_r($_FILES); die;
    if(!$this->input->post(NULL)){
      $this->template->build('product-tour/upload-tour', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "grouptour/product-tour",
            'data'          => $detail,
            'title'         => lang("Upload File Product Tour"),
            'breadcrumb'    => array(
            "product_tour"  => "inventory/product-tour"
            ),
           'css'         => $css,
           'foot'        => $foot
          ));
    $this->template
      ->set_layout('form')
      ->build('product-tour/upload-tour');
    }else{
      $pst = $this->input->post(NULL);
//      print "<pre>";
//      print_r($pst); 
//      print "<pre>";
//      die;
      $config['upload_path'] = './files/antavaya/csv/';
      $config['allowed_types'] = '*';

      $this->load->library('upload', $config);
      
      if($_FILES['file']['name']){
        if (  $this->upload->do_upload('file')){
          $data = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("inventory/product-tour/upload-tour/")."'>Back</a>";
          die;
        }
      }
       $kirim = array(
                "status"                                => 1,
                "kategori"                              => 3,
                "create_by_users"                       => $this->session->userdata("id"),
                "create_date"                           => date("Y-m-d H:i:s")
                );
//      $this->debug($data_thumb, true);
      if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
       
        $id_csv_file = $this->global_models->insert("csv_file", $kirim);
        
      if($id_csv_file){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/product-tour/upload-tour");
    }
  }
  
  function list_file(){
      
    $list = $this->global_models->get("csv_file");
  
   
    $this->template->build('product-tour/list-file', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
            'data'        => $list,
            'title'       => lang("List File Upload"),
            
          ));
    $this->template
      ->set_layout('datatables')
      ->build('product-tour/list-file');
  }
  
  function tour_schedule(){
    $pst = $this->input->post();
//    $this->debug($pst, true);
    if($pst['id_product_tour_information']){
		
      $d_airport_tax        = str_replace(",","",$pst['airport_tax']);
      $d_visa               = str_replace(",","",$pst['visa']);
      $d_sgl_supp           = str_replace(",","",$pst['sgl_supp']);
      $d_child_no_bed       = str_replace(",","",$pst['child_no_bed']);
      $d_child_extra_bed    = str_replace(",","",$pst['child_extra_bed']);
      $d_child_twin_bed     = str_replace(",", "", $pst['child_twin_bed']);
      $d_adult_triple_twin  = str_replace(",","",$pst['adult_triple_twin']);
      
      
         $data6 = array("start_date" => $pst['start_date'], "end_date" => $pst['end_date'],"start_time" => $pst['etd'],
                        "end_time" => $pst['eta'], "available_seat" => $pst['available_seat'], "adult_triple_twin" => $d_adult_triple_twin,
                      "child_twin_bed" => $d_child_twin_bed, "child_extra_bed" => $d_child_extra_bed,"child_no_bed" =>$d_child_no_bed,
                      "sgl_supp" => $d_sgl_supp, "visa" => $d_visa, "airport_tax" => $d_airport_tax, "flt" =>$pst['flt'],
                      "in" =>$pst['in'], "out" => $pst['out'], "keberangkatan" =>$pst['keberangkatan'],"id_product_tour_information" => $pst['id_product_tour_information']);
         
          $this->load->model('inventory/mnotice_mail');
          $this->mnotice_mail->notice_update_schedule($data6);
	  
		$this->olah_tour_code_information($kode_info);
      $kirim = array(
        "id_product_tour"               => $pst['id_product_tour'],
        "kode"                          => $kode_info,
        "kode_ps"                       => $pst['kode_ps'],
        "start_date"                    => $pst['start_date'],
        "end_date"                      => $pst['end_date'],
        "start_time"                    => $pst['etd'],
        "end_time"                      => $pst['eta'],
        "available_seat"                => $pst['available_seat'],
        "id_currency"                   => 2,
        "keberangkatan"                 => $pst['keberangkatan'],
        "kurs_rate"                     => $pst['kurs_rate'],
        "adult_triple_twin_usd"             => str_replace(",","",$pst['adult_triple_twin_usd']),
        "child_twin_bed_usd"                => str_replace(",", "", $pst['child_twin_bed_usd']),
        "child_extra_bed_usd"               => str_replace(",","",$pst['child_extra_bed_usd']),
        "child_no_bed_usd"                  => str_replace(",","",$pst['child_no_bed_usd']),
        "sgl_supp_usd"                      => str_replace(",","",$pst['sgl_supp_usd']),
        "airport_tax_usd"                   => str_replace(",","",$pst['airport_tax_usd']),
        "visa_usd"                          => str_replace(",","",$pst['visa_usd']),
        "adult_triple_twin"             => str_replace(",","",$pst['adult_triple_twin']),
        "child_twin_bed"                => str_replace(",", "", $pst['child_twin_bed']),
        "child_extra_bed"               => str_replace(",","",$pst['child_extra_bed']),
        "child_no_bed"                  => str_replace(",","",$pst['child_no_bed']),
        "sgl_supp"                      => str_replace(",","",$pst['sgl_supp']),
        "airport_tax"                   => str_replace(",","",$pst['airport_tax']),
        "less_ticket_adl_usd"           => str_replace(",","",$pst['less_ticket_adl_usd']),
        "less_ticket_adl"               => str_replace(",","",$pst['less_ticket_adl']),
        "less_ticket_chl_usd"           => str_replace(",","",$pst['less_ticket_chl_usd']),
        "less_ticket_chl"               => str_replace(",","",$pst['less_ticket_chl']),
       "id_product_tour_master_discount"   => $pst['stnb_discount_tetap'],
        "visa"                          => str_replace(",","",$pst['visa']),
      //  "discount_tetap"                => str_replace(",","",$pst['discount_tetap']),
      //  "stnb_discount_tetap"           => $pst['stnb_discount_tetap'],
        "flt"                           => $pst['flt'],
        "in"                            => $pst['in'],
        "out"                           => $pst['out'],
        "tampil"                        => $pst['tampil'],
        "status"                        => $pst['condition'],
        "sts"                           => $pst['sts'],
        "at_airport"                    => $pst['at_airport'],
        "at_airport_date"               => $pst['at_airport_date'], 
        "remarks"                       => $pst['remarks'],
        "update_by_users"               => $this->session->userdata("id"),
      );
      $id_product_tour_information = $this->global_models->update("product_tour_information", array("id_product_tour_information" => $pst['id_product_tour_information']), $kirim);
    }
    
    else{
	  $this->olah_tour_code_information($kode_info);
      $kirim = array(
        "id_product_tour"               => $pst['id_product_tour'],
        "kode"                          => $kode_info,
        "kode_ps"                       => $pst['kode_ps'],
        "start_date"                    => $pst['start_date'],
        "end_date"                      => $pst['end_date'],
        "start_time"                    => $pst['etd'],
        "end_time"                      => $pst['eta'],
        "available_seat"                => $pst['available_seat'],
        "id_currency"                   => 2,
		"keberangkatan"                   => $pst['keberangkatan'],
        "kurs_rate"                     => $pst['kurs_rate'],
        "adult_triple_twin_usd"             => str_replace(",","",$pst['adult_triple_twin_usd']),
        "child_twin_bed_usd"                => str_replace(",", "", $pst['child_twin_bed_usd']),
        "child_extra_bed_usd"               => str_replace(",","",$pst['child_extra_bed_usd']),
        "child_no_bed_usd"                  => str_replace(",","",$pst['child_no_bed_usd']),
        "sgl_supp_usd"                      => str_replace(",","",$pst['sgl_supp_usd']),
        "airport_tax_usd"                   => str_replace(",","",$pst['airport_tax_usd']),
        "visa_usd"                          => str_replace(",","",$pst['visa_usd']),
        "adult_triple_twin"             => str_replace(",","",$pst['adult_triple_twin']),
        "child_twin_bed"                => str_replace(",", "", $pst['child_twin_bed']),
        "child_extra_bed"               => str_replace(",","",$pst['child_extra_bed']),
        "child_no_bed"                  => str_replace(",","",$pst['child_no_bed']),
        "sgl_supp"                      => str_replace(",","",$pst['sgl_supp']),
        "airport_tax"                   => str_replace(",","",$pst['airport_tax']),
        "less_ticket_adl_usd"           => str_replace(",","",$pst['less_ticket_adl_usd']),
        "less_ticket_adl"               => str_replace(",","",$pst['less_ticket_adl']),
        "less_ticket_chl_usd"           => str_replace(",","",$pst['less_ticket_chl_usd']),
        "less_ticket_chl"               => str_replace(",","",$pst['less_ticket_chl']),
        "visa"                          => str_replace(",","",$pst['visa']),
         "id_product_tour_master_discount"   => $pst['stnb_discount_tetap'],
      //  "discount_tetap"                => str_replace(",","",$pst['discount_tetap']),
      //  "stnb_discount_tetap"           => $pst['stnb_discount_tetap'],
        "flt"                           => $pst['flt'],
        "in"                            => $pst['in'],
        "out"                           => $pst['out'],
	"tampil"                        => $pst['tampil'],
	"status"                        => $pst['condition'],
         "sts"                           => $pst['sts'],
        "at_airport"                    => $pst['at_airport'],
        "at_airport_date"               => $pst['at_airport_date'], 
        "remarks"                       => $pst['remarks'],  
        "create_by_users"               => $this->session->userdata("id"),
        "create_date"                   => date("Y-m-d H:i:s"),
      );
      $id_product_tour_information = $this->global_models->insert("product_tour_information", $kirim);
    }
    if($id_product_tour_information){
      $this->session->set_flashdata('success', 'Data Schedule tersimpan');
    }
    else{
      $this->session->set_flashdata('notice', 'Data tidak tersimpan');
    }
    redirect("inventory/product-tour/add-product-tour/{$pst['id_product_tour']}");
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
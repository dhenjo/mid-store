<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_currency extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
 
  }
  function delete($id_master_currency){
    $this->global_models->delete("master_currency", array("id_master_currency" => $id_master_currency));
     $this->session->set_flashdata('success', 'Data terhapus');
    redirect("inventory/master-currency");
  }
  
	public function index(){

   $data = $this->global_models->get("master_currency");
   $menutable = '
      <li><a href="'.site_url("inventory/master-currency/add").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build("master-currency/main",
      array('url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
           'data'        => $data,
          //  'foot'              => $foot,
           // 'css'              => $css,
            'title'       => lang("Master_Currency"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build("master-currency/main");
	}
  public function add($id_master_currency = 0){
      
    if(!$this->input->post(NULL)){
    $detail = $this->global_models->get("master_currency",array("id_master_currency" => $id_master_currency));
    $this->template->build("master-currency/add-master-currency", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/Master-Currency',
              'title'       => lang("add-master-currency"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                 "Master-Currency"  => "inventory/master-currency"
                ),
              
            ));
      $this->template
        ->set_layout('form')
        ->build("master-currency/add-master-currency");
    }
    else{
      $pst = $this->input->post(NULL);
      
     if($pst['status']){
       $status = $pst['status'];
     }else{
       $status = 2;
     }
     
      if($pst['id_detail']){
        $kirim = array(
            "name"           => $pst['name'],
            "code"           => $pst['code'],
            "status"         => $status,
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_master_currency = $this->global_models->update("master_currency", array("id_master_currency" => $pst['id_detail']),$kirim);
        
      }
      else{
         $kirim = array(
            "name"           => $pst['name'],
            "code"           => $pst['code'],
            "status"         => $status,
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        $id_master_currency = $this->global_models->insert("master_currency", $kirim);
        
      } 
      if($id_master_currency){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/master-currency/");
    }
  }
  public function rate(){

   $data = $this->global_models->get("master_currency_rate");
   $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", TRUE, array("status" => 1));     
   
   $menutable = '
      <li><a href="'.site_url("inventory/master-currency/add_rate").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build("master-currency/main",
      array('url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
           'data'         => $data,
           'dropdown'     => $dropdown,
          //  'foot'              => $foot,
           // 'css'              => $css,
            'title'       => lang("Master_Currency_Rate"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build("master-currency/currency-rate");
	}
	public function add_rate($id_master_currency_rate = 0){
      
    if(!$this->input->post(NULL)){
    $detail = $this->global_models->get("master_currency_rate",array("id_master_currency_rate" => $id_master_currency_rate));
    $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", TRUE, array("status" => 1));     
   
    $this->template->build("master-currency/add-master-currency-rate", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/Master-currency/rate',
              'title'       => lang("add-master-currency-rate"),
              'detail'      => $detail,
              'dropdown'    => $dropdown,
              'breadcrumb'  => array(
                 "Master-Currency-rate"  => "inventory/master-currency/rate"
                ),
              
            ));
      $this->template
        ->set_layout('form')
        ->build("master-currency/add-master-currency-rate");
    }
    else{
      $pst = $this->input->post(NULL);
//      print_r($pst); die;
    
     
      if($pst['id_detail']){
        $kirim = array(
            "id_master_currency"            => $pst['id_currency'],
            "rate"                          => $pst['rate'],
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_master_currency_rate2 = $this->global_models->update("master_currency_rate", array("id_master_currency_rate" => $pst['id_detail']),$kirim);
        
      }
      else{
         $kirim = array(
           "id_master_currency"            => $pst['id_currency'],
            "rate"                          => $pst['rate'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        $id_master_currency_rate2 = $this->global_models->insert("master_currency_rate", $kirim);
        
      } 
      if($id_master_currency_rate2){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/master-currency/rate");
    }
  }
}

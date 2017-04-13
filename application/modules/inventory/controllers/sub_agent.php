<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_agent extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
 
  }
  
  function delete($id_sub_agent){
    $this->global_models->delete("master_sub_agent", array("id_master_sub_agent" => $id_sub_agent));
     $this->session->set_flashdata('success', 'Data terhapus');
      redirect("inventory/sub-agent");
  }
  
	public function index(){

   $data = $this->global_models->get("master_sub_agent");
   $menutable = '
      <li><a href="'.site_url("inventory/sub-agent/add").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build("sub-agent/main",
      array('url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/sub-agent",
           'data'        => $data,
          //  'foot'              => $foot,
           // 'css'              => $css,
            'title'       => lang("Sub Agent"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build("sub-agent/main");
	}
  
  public function add($id_sub_agent = 0){
      
    if(!$this->input->post(NULL)){
    $detail = $this->global_models->get("master_sub_agent",array("id_master_sub_agent" => $id_sub_agent));
//    print_r($detail); die;
    $this->template->build("sub-agent/add-sub-agent", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'inventory/sub-agent',
              'title'       => lang("add-Sub-Agent"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                 "Master-Sub-Agent"  => "inventory/sub-agent"
                ),
            ));
      $this->template
        ->set_layout('form')
        ->build("sub-agent/add-sub-agent");
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
            "status"         => $status,
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_sub_agent = $this->global_models->update("master_sub_agent", array("id_master_sub_agent" => $pst['id_detail']),$kirim);
        
      }
      else{
         $kirim = array(
            "name"           => $pst['name'],
            "status"         => $status,
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d H:i:s")
        );
        $id_sub_agent = $this->global_models->insert("master_sub_agent", $kirim);
        
      } 
      if($id_sub_agent){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("inventory/sub-agent/");
    }
  }
	
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_hrm extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function delete($id_internal_monitoring){
    $this->global_models->delete("internal_monitoring", array("id_internal_monitoring" => $id_internal_monitoring));
    $this->session->set_flashdata('success', 'Data tersimpan');
    redirect("monitoring");
  }

  function department(){
    $list = $this->global_models->get("hrm_department");
    
    $menutable = '
      <li><a href="'.site_url("hrm/master-hrm/add-department").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('master/department', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "hrm/master-hrm/department",
            'data'        => $list,
            'title'       => lang("hrm_master_department"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('master/department');
  }
  
  function lokasi(){
    $list = $this->global_models->get("internal_lokasi_client");
    
    $menutable = '
      <li><a href="'.site_url("monitoring/add-new-lokasi").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('master/lokasi', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "monitoring/lokasi",
            'data'        => $list,
            'title'       => lang("internal_lokasi"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('master/lokasi');
  }
  
  public function add_department($id_hrm_department = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("hrm_department", array("id_hrm_department" => $id_hrm_department));
      
      $this->template->build("master/add-department", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'hrm/master-hrm/department',
              'title'       => lang("hrm_master_add_department"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "hrm_master_department"  => "hrm/master-hrm/department"
                ),
            ));
      $this->template
        ->set_layout('form')
        ->build("master/add-department");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "title"                     => $pst['title'],
            "code"                      => $pst['code'],
            "update_by_users"           => $this->session->userdata("id"),
        );
        
        $id_hrm_department = $this->global_models->update("hrm_department", array("id_hrm_department" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "title"                     => $pst['title'],
            "code"                      => $pst['code'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        
        $id_hrm_department = $this->global_models->insert("hrm_department", $kirim);
      }
      if($id_hrm_department){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("hrm/master-hrm/department");
    }
  }
  
  public function add_new_lokasi($id_internal_lokasi_client = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("internal_lokasi_client", array("id_internal_lokasi_client" => $id_internal_lokasi_client));
      
      $this->template->build("master/add-new-lokasi", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'monitoring',
              'title'       => lang("internal_add_lokasi"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "internal_lokasi"  => "monitoring/lokasi"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("master/add-new-lokasi");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "title"                     => $pst['title'],
            "update_by_users"           => $this->session->userdata("id"),
        );
        
        $id_internal_lokasi_client = $this->global_models->update("internal_lokasi_client", array("id_internal_lokasi_client" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "title"                     => $pst['title'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        
        $id_internal_lokasi_client = $this->global_models->insert("id_internal_lokasi_client", $kirim);
      }
      if($id_internal_lokasi_client){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("monitoring/lokasi");
    }
  }
  
  function auto_lokasi(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM internal_lokasi_client
      WHERE 
      LOWER(title) LIKE '%{$q}%'
      LIMIT 0,10
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_internal_lokasi_client,
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
  
  function ajax_save_location(){
    $kirim = array(
      "title"   => $this->input->post("title"),
    );
    $this->global_models->insert("internal_lokasi_client", $kirim);
    print "TRUE";
    die;
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
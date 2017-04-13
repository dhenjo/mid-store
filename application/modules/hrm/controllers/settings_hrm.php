<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_hrm extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }

  function child_level_organisasi($id_hrm_settings_level_organisasi = NULL){
    if($id_hrm_settings_level_organisasi == 0){
      $where = "ISNULL(A.parent)";
    }
    else{
      $where = "A.parent = '{$id_hrm_settings_level_organisasi}'";
    }
    $list = $this->global_models->get_query("SELECT A.*, B.title AS kepala"
      . " FROM hrm_settings_level_organisasi AS A"
      . " LEFT JOIN hrm_settings_level_organisasi AS B ON B.id_hrm_settings_level_organisasi = A.parent"
      . " WHERE {$where}");
    
    $menutable = '
      <li><a href="'.site_url("hrm/settings-hrm/add-level-organisasi/{$id_hrm_settings_level_organisasi}").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('settings/level-organisasi', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "hrm/settings-hrm/child-level-organisasi",
            'data'        => $list,
            'title'       => lang("hrm_settigns_level_organisasi"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('settings/level-organisasi');
  }
  
  public function add_level_organisasi($parent, $id_hrm_settings_level_organisasi = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("hrm_settings_level_organisasi", array("id_hrm_settings_level_organisasi" => $id_hrm_settings_level_organisasi));
      
      $this->template->build("settings/add-level-organisasi", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'hrm/settings-hrm/child-level-organisasi',
              'title'       => lang("hrm_settings_add_level_organisasi"),
              'detail'      => $detail,
              'kepala'      => $parent,
              'parent'      => $this->global_models->get_dropdown("hrm_settings_level_organisasi", "id_hrm_settings_level_organisasi", "title"),
              'breadcrumb'  => array(
                    "hrm_settings_level_organisasi"  => "hrm/settings-hrm/child-level-organisasi/{$parent}"
                ),
            ));
      $this->template
        ->set_layout('form')
        ->build("settings/add-level-organisasi");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['parent'] == 0){
        $pst['parent'] = NULL;
      }
      
      if($pst['id_detail']){
        $kode_parent = $this->global_models->get_field("hrm_settings_level_organisasi", "code", array("id_hrm_settings_level_organisasi" => $pst['parent']));
        $kirim = array(
            "title"                     => $pst['title'],
            "code"                      => $kode_parent."-{$pst['parent']}-",
            "parent"                    => $pst['parent'],
            "update_by_users"           => $this->session->userdata("id"),
        );
        
        $id_hrm_settings_level_organisasi = $this->global_models->update("hrm_settings_level_organisasi", 
          array("id_hrm_settings_level_organisasi" => $pst['id_detail']),$kirim);
      }
      else{
        $kode_parent = $this->global_models->get_field("hrm_settings_level_organisasi", "code", array("id_hrm_settings_level_organisasi" => $pst['parent']));
        $kirim = array(
            "title"                     => $pst['title'],
            "code"                      => $kode_parent,
            "parent"                    => $pst['parent'],
            "create_by_users"           => $this->session->userdata("id"),
            "create_date"               => date("Y-m-d H:i:s")
        );
        
        $id_hrm_settings_level_organisasi = $this->global_models->insert("hrm_settings_level_organisasi", $kirim);
        $this->global_models->update("hrm_settings_level_organisasi", array("id_hrm_settings_level_organisasi" => $id_hrm_department), 
          array("code" => ("{$kode_parent}-{$pst['parent']}-")));
      }
      if($id_hrm_settings_level_organisasi){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("hrm/settings-hrm/child-level-organisasi/{$parent}");
    }
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
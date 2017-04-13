<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_tours extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function delete_haji($id_website_haji){
    $this->global_models->delete("website_haji", array("id_website_haji" => $id_website_haji));
    $this->session->set_flashdata('success', 'Data terhapus');
    redirect("haji/master-haji");
  }
  
  function hajj_umrah(){
    $list = $this->global_models->get("master_hajj_umrah");
    
    $menutable = '
      <li><a href="'.site_url("scm/master-tours/add-new-hajj-umrah").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('master-tours/hajj-umrah', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "scm/master-tours/hajj-umrah",
            'data'        => $list,
            'title'       => lang("antavaya_hajj_umrah"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('master-tours/hajj-umrah');
  }
  
  public function add_new_hajj_umrah($id_master_hajj_umrah = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("master_hajj_umrah", array("id_master_hajj_umrah" => $id_master_hajj_umrah));
      
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />"
        . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datepicker/datepicker3.css' rel='stylesheet' type='text/css' />";
      $foot = "
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datepicker/bootstrap-datepicker.js' type='text/javascript'></script>
        <script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script>
        <script type='text/javascript'>
            $(function() {
              CKEDITOR.replace('editor2');
              $( '#start_date' ).datepicker({
                showOtherMonths: true,
                format: 'yyyy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
              
              $( '#end_date' ).datepicker({
                showOtherMonths: true,
                format: 'yyyy-mm-dd',
                selectOtherMonths: true,
                selectOtherYears: true
              });
            });
        </script>
        ";
      
      $this->template->build("master-tours/add-new-hajj-umrah", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'scm/master-tours/hajj-umrah',
              'title'       => lang("antavaya_add_haji"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "antavaya_haji"  => "scm/master-tours/hajj-umrah"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("master-tours/add-new-hajj-umrah");
    }
    else{
      $pst = $this->input->post(NULL);
      
      $config['upload_path'] = './files/antavaya/master/hajj/';
      $config['allowed_types'] = '*';
      $config['max_width']  = '3100';
      $config['max_height']  = '3100';

      $this->load->library('upload', $config);
//      $this->debug($_FILES, true);
      if($_FILES['file']['name']){
        if (  $this->upload->do_upload('file')){
          $data = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("scm/master-tours/add-new-hajj-umrah/".$id_master_hajj_umrah)."'>Back</a>";
          die;
        }
      }
      if($_FILES['file_pdf']['name']){
        if (  $this->upload->do_upload('file_pdf')){
          $data_pdf = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("scm/master-tours/add-new-hajj-umrah/".$id_master_hajj_umrah)."'>Back</a>";
          die;
        }
      }
      if($_FILES['file_temp']['name']){
        if (  $this->upload->do_upload('file_temp')){
          $data_temp = array('upload_data' => $this->upload->data());
        }
        else{
          print $this->upload->display_errors();
          print "<br /> <a href='".site_url("scm/master-tours/add-new-hajj-umrah/".$id_master_hajj_umrah)."'>Back</a>";
          die;
        }
      }
      
      if($pst['id_detail']){
        $kirim = array(
            "title"           => $pst['title'],
            "nicename"        => $this->global_models->nicename(trim($pst['title']), "master_hajj_umrah", "id_master_hajj_umrah"),
            "sub_title"       => $pst['sub_title'],
            "harga_rata"      => $pst['harga_rata'],
            "note"            => $pst['note'],
            "status"          => $pst['status'],
            "update_by_users" => $this->session->userdata("id"),
        );
        if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
        if($data_pdf['upload_data']['file_name']){
          $kirim['file_pdf'] = $data_pdf['upload_data']['file_name'];
        }
        if($data_temp['upload_data']['file_name']){
          $kirim['file_temp'] = $data_temp['upload_data']['file_name'];
        }
        $id_master_hajj_umrah = $this->global_models->update("master_hajj_umrah", array("id_master_hajj_umrah" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "title"           => $pst['title'],
            "nicename"        => $this->global_models->nicename(trim($pst['title']), "master_hajj_umrah", "id_master_hajj_umrah"),
            "sub_title"       => $pst['sub_title'],
            "note"            => $pst['note'],
            "status"          => $pst['status'],
            "harga_rata"      => $pst['harga_rata'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        if($data['upload_data']['file_name']){
          $kirim['file'] = $data['upload_data']['file_name'];
        }
        if($data_pdf['upload_data']['file_name']){
          $kirim['file_pdf'] = $data_pdf['upload_data']['file_name'];
        }
        if($data_temp['upload_data']['file_name']){
          $kirim['file_temp'] = $data_temp['upload_data']['file_name'];
        }
        $id_master_hajj_umrah = $this->global_models->insert("master_hajj_umrah", $kirim);
      }
      if($id_master_hajj_umrah){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("scm/master-tours/hajj-umrah");
    }
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
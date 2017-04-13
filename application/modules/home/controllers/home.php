<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MX_Controller {
    
  function __construct() {      
    
    
//    $this->debug($this->menu, true);
  }
  
	public function index(){
//    $this->debug(site_url(), true);
//    set_cookie('test', 'coba');
//    setcookie("ci_session", "testsadsab dsgj sajh sgd agsfgyga");
//    $cookie = array(
//    'name'   => 'km',
//    'value'  => 'hilang',
//    'expire' => '86500'
//);
//delete_cookie("test");
//$this->input->set_cookie($cookie);
//    $this->debug($this->input->cookie('cart'), true);
//    $this->debug(get_cookie('cart', TRUE), true);
//    die;
    
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/datatables/dataTables.bootstrap.css' rel='stylesheet' type='text/css' />"
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";

    $foot = ""
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-1.10.3.min.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/jquery.dataTables.js' type='text/javascript'></script>"
      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/datatables/dataTables.bootstrap.js' type='text/javascript'></script>";
    $foot .= "<script>"
        . 'var table = '
        . '$("#tableboxy").DataTable({'
          . '"order": [[ 0, "desc" ]],'
        . '});'
        . "$('#history-log').slimScroll({"
          . "height: '250px'"
        . "});"
      . '</script>';
    $this->template->build("main", 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'title'       => "Home",
            'title_small' => "",
            'foot'          => $foot,
            'css'           => $css,
            'breadcrumb'  => array(),
          ));
    $this->template
      ->set_layout('default')
      ->build("main");
	}
	public function contact_us($id_contact, $stat = 0, $pesan = ""){
    if(!$this->input->post(NULL)){
      if($this->session->userdata('id') == 1){
        $detail = $this->global_models->get("d_contact_us", array("id_contact_us" => $id_contact));
      }
      $this->template->build("contact-us", 
        array('message'     => urldecode ($pesan),
              'url'         => base_url()."themes/srabon/",
              'title_table' => "Cotact Us",
              'stat'        => $stat,
              'detail'      => $detail,
              'foot'        => ""
            ));
      $this->template
        ->set_layout('default')
        ->build("contact-us");
    }
    else{
      $pst = $this->input->post(NULL);
      $today = time();
      $kirim = array(
          "title"     =>  $pst['title'],
          "name"      =>  $pst['name'],
          "tanggal"   =>  date("Y-m-d"),
          "email"     =>  $pst['email'],
          "telp"      =>  $pst['telp'],
          "note"      =>  $pst['note']
      );
      if($this->global_models->insert("d_contact_us", $kirim)){
        $this->email->from($pst['email'], $pst['name']);
        $this->email->to('project@nusato.com');

        $this->email->subject('Opportunity New Client');
        $this->email->message("
          title     =>  {$pst['title']}
          name      =>  {$pst['name']}
          tanggal   =>  ".date("Y-m-d")."
          email     =>  {$pst['email']}
          telp      =>  {$pst['telp']}
          note      =>  {$pst['note']}
          ");

        if($this->email->send() === TRUE){
          redirect("home/contact-us/0/1/Data telah disimpan dan mengirim email kepada kami. Kami akan membaca dan merespon keinginan anda. Terima Kasih");
        }
        else{
          redirect("home/contact-us/0/1/Data telah disimpan tapi gagal mengirim email. Kami tetap dapat membaca dan merespon keinginan anda. Terima Kasih");
        }
      }
      else{
        redirect("home/contact-us/0/2/Data gagal disimpan harap coba lagi atau langsung contact kami dengan info contact di bawah. Terima Kasih");
      }
    }
	}
	public function opportunity_list(){
    $data = $this->global_models->get("d_contact_us");
    $this->template->build("opportunity-list",
      array('message'     => $pesan,
            'url'         => base_url()."themes/srabon/",
            'title_table' => "Opportunity",
            'data'        => $data,
            'foot'        => ""
          ));
    $this->template
      ->set_layout('default')
      ->build("opportunity-list");
	}
  
  function store(){
    $data = $this->global_models->get_query("SELECT A.no_ttu, A.id_product_tour_book_payment"
      . " FROM product_tour_book_payment AS A"
      . " WHERE A.id_store IS NULL"
      . " LIMIT 0, 500");
    foreach($data AS $dt){
      $ttu = explode("-", $dt->no_ttu);
      $id_store = $this->global_models->get_field("store", "id_store", array("kode" => $ttu[0]));
      if(!$id_store)
        $id_store = $this->global_models->get_field("store", "id_store", array("kode" => $ttu[1]));
      if($id_store){
        $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $dt->id_product_tour_book_payment), array("id_store" => $id_store));
      }
    }
    print 'll';
    die;
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
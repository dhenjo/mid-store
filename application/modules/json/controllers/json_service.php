<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_service extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  
  function tour_series_cek_full_book(){
    $data = $this->global_models->get_query("SELECT A.*"
      . " ,(SELECT B.title FROM product_tour AS B WHERE B.id_product_tour = A.id_product_tour) AS name"
      . " FROM product_tour_information AS A"
      . " WHERE A.notice IS NULL AND (A.available_seat - A.seat_update) <= 1"
      . " AND A.status IN (1,5)"
      . " AND A.tampil = 1"
      . " AND A.start_date >= '".date("Y-m-d")."'"
      . " LIMIT 0, 10");
    if($data){
      $id = "0";
      foreach($data AS $dt){
        $itin .= "<a href='http://117.102.80.180/store/tour/tour-series/schedule-detail/{$dt->kode}'>{$dt->name} {$dt->start_date}</a><br />";
        $id .= ",".$dt->id_product_tour_information;
      }
      $this->global_models->query(""
        . "UPDATE product_tour_information SET notice = 1 WHERE id_product_tour_information IN ({$id})"
        . "");
      
      $this->load->library('email');
      $this->email->initialize($this->global_models->email_conf());
      $this->email->from('no-reply@antavaya.co.id', 'Administrator AV TMS');
      $this->email->to('tour@antavaya.com');
      $this->email->bcc('nugroho.budi@antavaya.com');
      $link = "http://".$_SERVER['HTTP_HOST']."/store/grouptour/product-tour/book-information/".$val->kode_booking;
      $link_url = "<a href='{$link}'>{$val->kode_booking}</a>";
      $this->email->subject('[TMS] Book Full');
      $html = "<html>
          <body>
            Dear Tour Team<br><br>
            Klik link di bawah (Jika masuk halaman login silahkan login menggunakan account TMS)<br />
            Lalu klik Close jika Itin sudah close<br />
            Perbesar Available Seat jika ingin menambah kuota<br />
            Klik Duplicate dan ceklish 'Duplikasi dengan relasi Itin' jika Itin ini akan digandakan <br />
            Itin berikut sudah mencapai batas max :<br />
            {$itin}
              <br> 
              </body>
        </html>";
      $this->email->message($html);
      if($this->email->send())
        print "send|";
      else
        print "fail|";
    }
    else{
      print "no-data|";
    }
    die;
  }
  
  function update_seat(){
    $info = $this->global_models->get_query("SELECT COUNT(id_product_tour_customer) AS jml, id_product_tour_information"
      . " FROM product_tour_customer"
      . " WHERE status IN (2,3,6,7,9)"
      . " GROUP BY id_product_tour_information");
//    $this->debug($info, true);
    foreach($info AS $in){
      if($in->id_product_tour_information){
        $this->global_models->query("UPDATE product_tour_information
	SET seat_update = {$in->jml}
	WHERE id_product_tour_information = '{$in->id_product_tour_information}'");
  print "finish";die;
      }
    }
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
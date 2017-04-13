<?php
class Mnotice_mail extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
      
    }
    
    function notice_update_product_tour($data){
//      print "<pre>";
//      print_r($data);
//      print "</pre>"; die;
        $l_night                   = $this->global_models->get_field("product_tour", "night", array("id_product_tour" => $data['id_product_tour'],"night" =>$data['night']));
       $l_airlines                = $this->global_models->get_field("product_tour", "airlines", array("id_product_tour" => $data['id_product_tour'],"airlines" =>$data['airlines']));
       $l_days                    = $this->global_models->get_field("product_tour", "days", array("id_product_tour" => $data['id_product_tour'],"days" =>$data['days']));
       
      
       $sql = "SELECT night,airlines,days,title,id_store_region"
        . " FROM product_tour"
        . " WHERE id_product_tour ='{$data['id_product_tour']}'";
      $data2 = $this->global_models->get_query($sql);
         
       $l_store  = $this->global_models->get_field("store_region", "title", array("id_store_region" => $data2[0]->id_store_region));
      
        
         if($l_night == ""){
           $dt_night = "Perubahan Night sebelumnya [{$data2[0]->night}] menjadi ".$data['night']."<br>";
         }else{
           $dt_night ="";
         }
         
         if($l_airlines ==""){
           $dt_airlines = "Perubahan Airlines sebelumnya [{$data2[0]->airlines}] menjadi ".$data['airlines']."<br>";
         }else{
           $dt_airlines = "";
         }
         
         if($l_days == ""){
           $dt_days = "Perubahan Days sebelumnya [{$data2[0]->days}] menjadi ".$data['days']."<br>";
         }else{
           $dt_days = "";
         }
         
         if($l_night =="" OR $l_airlines =="" OR $l_days ==""){
      $cc_email = $this->session->userdata("email");
       
        $pt_product_tour = $this->global_models->get("product_tour_book", array("id_product_tour" => $data['id_product_tour'],"status <" => 4));
       
      
        foreach ($pt_product_tour as $val_store) {
          $email_tc = $this->global_models->get_field("users_channel", "email", array("id_users" => $val_store->id_users));
         $flg_eml .= $email_tc;
          $dt_eml .= $email_tc.",";
          
         // $val_book2 .= $val_store->kode.",";
        }
       
        }
        
        $subject_email = "Notifikasi Perubahan Informasi Product Tour";
        $isi_email = "<html>
            <body>
              Dear Bookers Group tour Status<br><br>
             
                Team tour Operation <b>{$l_store}</b> telah melakukan Update Product Tour <b>{$data2[0]->title} </b><br>
                Data yang diupdate :<br><br>
                <b> {$dt_night}
                {$dt_airlines} 
                {$dt_days}
                <br><br>
                </b>
                <br> 
                </body>
          </html>";
            if($flg_eml){
              $this->send_mail($dt_eml,$cc_email,$subject_email,$isi_email);
            }
    }
    
    function notice_status_draft_product_tour($data){
        
        $l_status  = $this->global_models->get_field("product_tour", "status", array("id_product_tour" => $data['id_product_tour']));
      
        if($l_status == 1){
            
      $tgl =date("Y-m-d");
        if($data['status'] == "2"){
       $sql = "SELECT title,id_store_region"
        . " FROM product_tour"
        . " WHERE id_product_tour ='{$data['id_product_tour']}'";
      $data2 = $this->global_models->get_query($sql);
         
       $l_store  = $this->global_models->get_field("store_region", "title", array("id_store_region" => $data2[0]->id_store_region));
      
      $cc_email = $this->session->userdata("email");
       
//        $pt_product_tour = $this->global_models->get("product_tour_book", array("id_product_tour" => $data['id_product_tour'],"status <" => 4,""));
        $sql2 = "SELECT A.id_users,A.kode"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"       
        . " WHERE A.id_product_tour ='{$data['id_product_tour']}' AND A.status < '4' AND B.start_date > {$tgl} ";
      $pt_product_tour = $this->global_models->get_query($sql2);
         
      
        foreach ($pt_product_tour as $val_store) {
          $email_tc = $this->global_models->get_field("users_channel", "email", array("id_users" => $val_store->id_users));
         $flg_eml .= $email_tc;
          $dt_eml .= $email_tc.",";
          
          $val_book2 .= $val_store->kode.",";
        }
       
        $subject_email = "Notifikasi Status Product Tour";
        $isi_email = "<html>
            <body>
              Dear Bookers Group tour Status<br><br>
             
                Team tour Operation <b>{$l_store}</b> sudah tidak  mem-publish product tour <b>{$data2[0]->title} </b> ini <br>
                <br> 
                kode book customer yang sudah book pada product tour ini ($val_book2)
                </body>
          </html>";
            if($flg_eml){
              $this->send_mail($dt_eml,$cc_email,$subject_email,$isi_email);
            }
        }
      }
    }
    
    function cancel_product_tour_information($id_information){
       $sql = "SELECT B.title,B.id_store,B.id_store_region"
        . " ,A.start_date,A.end_date,A.start_time,A.end_time"
        . " ,A.id_product_tour_information,A.kode"  
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.id_product_tour_information ='{$id_information}'";
      $data = $this->global_models->get_query($sql);
      
      $sql = "SELECT COUNT(id_product_tour_information) AS total_book"
        . " FROM product_tour_book AS A"
        . " WHERE A.id_product_tour_information ='{$pst['id_product_tour_information']}' AND (A.status ='1' OR A.status ='2' OR A.status ='3')";
      $data_total_book = $this->global_models->get_query($sql);
      
      $l_store  = $this->global_models->get_field("store_region", "title", array("id_store_region" => $data[0]->id_store_region));
      
      if($data_total_book[0]->total_book >0){
        $total_bk = "Total Yang sudah Book : ".$data_total_book[0]->total_book;
      }
      
      $pt_product_tour_info = $this->global_models->get("product_tour_book", array("id_product_tour_information" => $id_information,"status <" => 4));
       
      
        foreach ($pt_product_tour_info as $val_store) {
          $email_tc = $this->global_models->get_field("users_channel", "email", array("id_users" => $val_store->id_users));
         $flg_eml .= $email_tc;
          $dt_eml .= $email_tc.",";
          $val_book2 .= $val_store->kode.",";
        }
        
       $tanggal = date("d-m-Y", strtotime($data[0]->start_date))." - ".date("d-m-Y", strtotime($data[0]->end_date));
        $cc_email = $this->session->userdata("email");
       
        $subject_email = "Notifikasi Cancel Tour Schedule";
        $isi_email = "<html>
            <body>
              Dear Bookers Group tour Status<br><br>
             
                Team tour Operation <b>{$l_store}</b> telah melakukan Cancel Tour Schedule di <b>{$data[0]->title} [ Tanggal: {$tanggal} (Kode Tour Schedule {$data[0]->kode}) ] </b><br>
                Silahkan Bookers menghubungi Team tour Operation Untuk informasi Pemindahan data customer ke product tour yang <b>Available</b> 
                <br><br><br>
                {$data[0]->id_product_tour_information}
                </b>
                <br> 
                </body>
          </html>";
            if($flg_eml){
              
               $this->send_mail($dt_eml,$cc_email,$subject_email,$isi_email);
            }
    }
    
    function notice_update_schedule($data){
      
      $l_start_date = $this->global_models->get_field("product_tour_information", "start_date", array("id_product_tour_information" => $data['id_product_tour_information'],"start_date" => $data['start_date']));
      $l_end_date   = $this->global_models->get_field("product_tour_information", "end_date", array("id_product_tour_information" => $data['id_product_tour_information'],"end_date" => $data['end_date']));
      $l_start_time = $this->global_models->get_field("product_tour_information", "start_time", array("id_product_tour_information" => $data['id_product_tour_information'],"start_time" => $data['start_time']));
      $l_end_time   = $this->global_models->get_field("product_tour_information", "end_time", array("id_product_tour_information" => $data['id_product_tour_information'],"end_time" => $data['end_time']));
      
      $l_available_seat       = $this->global_models->get_field("product_tour_information", "available_seat", array("id_product_tour_information" => $data['id_product_tour_information'],"available_seat" => $data['available_seat']));
      $l_adult_triple_twin    = $this->global_models->get_field("product_tour_information", "adult_triple_twin", array("id_product_tour_information" => $data['id_product_tour_information'],"adult_triple_twin" => $data['adult_triple_twin']));
   
      $l_child_twin_bed       = $this->global_models->get_field("product_tour_information", "child_twin_bed", array("id_product_tour_information" => $data['id_product_tour_information'],"child_twin_bed" => $data['child_twin_bed']));
      $l_child_extra_bed      = $this->global_models->get_field("product_tour_information", "child_extra_bed", array("id_product_tour_information" => $data['id_product_tour_information'],"child_extra_bed" => $data['child_extra_bed']));
      
      $l_child_no_bed     = $this->global_models->get_field("product_tour_information", "child_no_bed", array("id_product_tour_information" => $data['id_product_tour_information'],"child_no_bed" => $data['child_no_bed']));
      $l_sgl_supp         = $this->global_models->get_field("product_tour_information", "sgl_supp", array("id_product_tour_information" => $data['id_product_tour_information'],"sgl_supp" => $data['sgl_supp']));
      $l_visa             = $this->global_models->get_field("product_tour_information", "visa", array("id_product_tour_information" => $data['id_product_tour_information'],"visa" => $data['visa']));
       $l_airport_tax     = $this->global_models->get_field("product_tour_information", "visa", array("id_product_tour_information" => $data['id_product_tour_information'],"airport_tax" => $data['airport_tax']));
         
       $l_flt                   = $this->global_models->get_field("product_tour_information", "flt", array("id_product_tour_information" => $data['id_product_tour_information'],"flt" =>$data['flt']));
       $l_in                    = $this->global_models->get_field("product_tour_information", "in", array("id_product_tour_information" => $data['id_product_tour_information'],"in" =>$data['in']));
       $l_out                   = $this->global_models->get_field("product_tour_information", "out", array("id_product_tour_information" => $data['id_product_tour_information'],"out" =>$data['out']));
       $l_keberangkatan         = $this->global_models->get_field("product_tour_information", "keberangkatan", array("id_product_tour_information" => $data['id_product_tour_information'],"keberangkatan" =>$data['keberangkatan']));
      
       
      $sql = "SELECT B.title,B.id_store,B.id_store_region"
        . " ,A.flt,A.in,A.out,A.start_date,A.end_date,A.start_time,A.end_time,A.available_seat,A.adult_triple_twin,A.keberangkatan"
        . " ,A.child_twin_bed,A.child_extra_bed,A.child_no_bed,A.sgl_supp,A.airport_tax,A.visa,A.id_product_tour_information,A.kode"  
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.id_product_tour_information ='{$data['id_product_tour_information']}'";
      $data2 = $this->global_models->get_query($sql);
      
       if($data['start_date'] =="" OR $data['end_date'] =="" OR $data['start_time'] =="" OR $data['end_time'] =="" OR $data['available_seat'] ==""
        OR $data['adult_triple_twin'] =="" OR $data['child_twin_bed'] =="" OR $data['child_extra_bed'] =="" OR $data['child_no_bed'] == ""
        OR $data['sgl_supp'] == "" OR $data['visa'] =="" OR $data['airport_tax'] =="" OR $data['flt'] =="" OR $data['in'] =="" OR $data['out'] =="" OR $data['keberangkatan'] ==""){
         
         
        $sql = "SELECT COUNT(id_product_tour_information) AS total_book"
        . " FROM product_tour_book AS A"
        . " WHERE A.id_product_tour_information ='{$data['id_product_tour_information']}' AND (A.status ='1' OR A.status ='2' OR A.status ='3')";
      $data_total_book = $this->global_models->get_query($sql);
      
      $l_store  = $this->global_models->get_field("store_region", "title", array("id_store_region" => $data2[0]->id_store_region));
      
      if($data_total_book[0]->total_book >0){
        $total_bk = "Total Yang sudah Book : ".$data_total_book[0]->total_book;
      }
      
        if($l_flt ==""){
          $dt_flight = "Perubahan Flight : sebelumnya [{$data2[0]->flt}] menjadi ".$data['flt']."<br>";
        }else{
          $dt_flight ="";
        }
        
        if($l_in ==""){
          $dt_in = "Perubahan In : sebelumnya [{$data2[0]->in}] menjadi ".$data['in']."<br>";
        }else{
          $dt_in ="";
        }
        
        if($l_out ==""){
          $dt_out = "Perubahan Out : sebelumnya [{$data2[0]->out}] menjadi ".$data['out']."<br>";
        }else{
          $dt_out ="";
        }
        
        if($data2[0]->keberangkatan != $data['keberangkatan']){
          $dt_keberangkatan = "Perubahan Keberangkatan : sebelumnya [{$data2[0]->keberangkatan}] menjadi ".$data['keberangkatan']."<br>";
        }else{
          $dt_keberangkatan ="";
        } 
        
         if($l_start_date ==""){
           $dt_start_date = "Perubahan Tanggal Keberangkatan : sebelumnya [{$data2[0]->start_date}] menjadi ".$data['start_date']."<br>";
         }else{
           $dt_start_date = "";
         }
         
         if($l_end_date ==""){
           $dt_end_date = "Perubahan Tanggal Tiba : sebelumnya [{$data2[0]->end_date}] menjadi ".$data['end_date']."<br>";
         }else{
           $dt_end_date = "";
         }
         
         if($l_start_time ==""){
           $dt_start_time = "Perubahan Jam Keberangkatan : sebelumnya [{$data2[0]->start_time}] menjadi ".$data['start_time']."<br>";
         }else{
           $dt_start_time = "";
         }
         
         if($l_end_time ==""){
           $dt_end_time = "Perubahan Jam Tiba : sebelumnya [{$data2[0]->end_time}] menjadi ".$data['end_time']."<br>";
         }else{
           $dt_end_time = "";
         }
         
         if($l_available_seat ==""){
           $dt_available_seat = "Perubahan Available Seat : sebelumnya [{$data2[0]->available_seat}] menjadi ".$data['available_seat']."<br>";
         }else{
           $dt_available_seat = "";
         }
         
         if($l_adult_triple_twin ==""){
           $sb_att = number_format($data2[0]->adult_triple_twin);
          $dt_adult_triple_twin = "Perubahan Harga Adult Triple Twin : sebelumnya [{$sb_att}] menjadi ".$data['adult_triple_twin']."<br>";
         }else{
          $dt_adult_triple_twin = "";
         }
         
         if($l_child_twin_bed ==""){
          $ctb = number_format($data2[0]->child_twin_bed);
           $dt_child_twin_bed = "Perubahan Harga Child Twin Bed : sebelumnya [{$ctb}] menjadi ".$data['child_twin_bed']."<br>";
         }else{
           $dt_child_twin_bed = "";
         }
         
         if($l_child_extra_bed ==""){
           $ceb = number_format($data2[0]->child_extra_bed);
           $dt_child_extra_bed = "Perubahan Harga Child Extra Bed : sebelumnya [{$ceb}] menjadi ".$data['child_extra_bed']."<br>";
         }else{
           $dt_child_extra_bed = "";
         }
         
         if($l_child_no_bed ==""){
           $cnb = number_format($data2[0]->child_no_bed);
           $dt_child_no_bed = "Perubahan Harga Child No Bed : sebelumnya [{$cnb}] menjadi ".$data['child_no_bed']."<br>";
         }else{
           $dt_child_no_bed ="";
         }
         
         if($l_sgl_supp =="" OR $l_adult_triple_twin ==""){
           $sbl_single_adult = number_format($data2[0]->adult_triple_twin + $data2[0]->sgl_supp);
           $dt_sgl_supp = "Perubahan Harga Single Adult : sebelumnya [{$sbl_single_adult}] menjadi ".number_format($data['adult_triple_twin'] + $data['sgl_supp'])."<br>";
         }else{
           $dt_sgl_supp ="";
         }
         
         if($data['visa'] != $data2[0]->visa){
           $sb_visa = number_format($data2[0]->visa);
           $dt_visa = "Perubahan Harga Visa : sebelumnya [{$sb_visa}] menjadi ".$data['visa']."<br>";
         }else{
           $dt_visa = "";
         }
         
         if($l_airport_tax ==""){
           $sb_airpot = $data2[0]->airport_tax;
           $dt_airport_tax = "Perubahan Harga Airport Tax & Flight Insurance : sebelumnya [{$sb_airpot}] menjadi ".$data['airport_tax']."<br>";
         }else{
           $dt_airport_tax = "";
         }
         
        $pt_product_tour_info = $this->global_models->get("product_tour_book", array("id_product_tour_information" => $data['id_product_tour_information']));
       
      
        foreach ($pt_product_tour_info as $val_store) {
          $email_tc = $this->global_models->get_field("users_channel", "email", array("id_users" => $val_store->id_users));
         $flg_eml .= $email_tc;
          $dt_eml .= $email_tc.",";
          $val_book2 .= $val_store->kode.",";
        }
        
         $cc_email = $this->session->userdata("email");
        
        
        if($l_adult_triple_twin =="" OR $l_child_twin_bed =="" OR $l_child_extra_bed =="" OR $l_child_no_bed == ""
        OR $l_sgl_supp == "" OR $l_visa =="" OR $l_airport_tax ==""){
         
          $cc_email .= ","."nugroho.budi@antavaya.com";
        }
        
        $subject_email = "Notifikasi Perubahan Informasi Tour Schedule";
        $isi_email = "<html>
            <body>
              Dear Bookers Group tour Status<br><br>
             
                Team tour Operation <b>{$l_store}</b> telah melakukan Update Tour Schedule di <b>{$data2[0]->title} [ Tanggal: {$data2[0]->start_date} - {$data2[0]->end_date} (Kode Tour Schedule {$data2[0]->kode}) ] </b><br>
                Data yang diupdate :<br><br>
                <b> $dt_start_date
                $dt_end_date 
                $dt_start_time
                $dt_end_time
                $dt_available_seat
                $dt_adult_triple_twin
                $dt_child_twin_bed
                $dt_child_extra_bed
                $dt_child_no_bed
                $dt_sgl_supp
                $dt_visa
                $dt_airport_tax 
                $dt_flight
                $dt_in
                $dt_out
                $dt_keberangkatan
                <br><br>
                {$total_bk} (kode book {$val_book2})
                <br><br><br>
                Apabila ada perubahan harga bookers dapat menghubungi team operation untuk mengkonfirmasi apakah customer yang sudah book sebelumnya<br>
                mengikuti harga yang baru ini.
                <br><br>
                {$data[0]->id_product_tour_information}
                </b>
                <br> 
                </body>
          </html>";
            if($flg_eml){
              $this->send_mail($dt_eml,$cc_email,$subject_email,$isi_email);
            }
      }
      
    }


    function send_mail($to,$cc,$subject,$isi){
      
        $this->load->library('email');
        $this->email->initialize($this->global_models->email_conf());
        $this->email->from('no-reply@antavaya.com', 'Administrator AV TMS');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->bcc("hendri.prasetyo@antavaya.com");
        $this->email->subject($subject);
        $this->email->message($isi);
        $this->email->send();
//        print $this->email->print_debugger();
//        die;
  }
     
}
?>

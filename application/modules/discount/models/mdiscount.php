<?php
class Mdiscount extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    function btc_payment($tglbook, $harga_normal, $channel){
      $query = "SELECT *"
        . " FROM tiket_discount"
        . " WHERE ('{$tglbook}' BETWEEN mulai AND akhir) AND channel = '{$channel}' AND status = '1'";
      $discount = $this->db->query($query)->result();
      $nilai = 0;
      if($discount[0]->id_tiket_discount > 0){
        if($discount[0]->type == 1){
          $nilai = ceil($discount[0]->nilai/100 * $harga_normal);
        }
        else{
          $nilai = $discount[0]->nilai;
        }
        $kirim = array(
          "status"              => 2,
          "nilai"               => $nilai,
          "discount"            => $discount[0],
          "id_discount"         => $discount[0]->id_tiket_discount,
        );
      }
      else{
        $kirim = array(
          "status"              => 1,
          "nilai"               => $nilai,
          "discount"            => array()
        );
      }
      return $kirim;
    }
}
?>

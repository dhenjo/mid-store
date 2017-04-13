<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_tour_history extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function index($id_product_tour){
    $items = $this->global_models->get_query("SELECT A.*, B.name AS users"
        . " FROM product_tour_log AS A"
        . " LEFT JOIN m_users AS B ON A.create_by_users = B.id_users"
        . " WHERE A.id_product_tour = '{$id_product_tour}'"
      . "");
//    $this->debug($items, true);
    $css = ""
//      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />"
//      . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery1.10.2.min.js' type='text/javascript'></script>"
      . "";
    $foot = "";
    $menutable = '
      <li><a href="'.site_url("inventory/product-tour-history/add-new-tour").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('product-tour-history/tour', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "inventory/product-tour",
            'data'        => $items,
            'foot'        => $foot,
            'css'         => $css,
            'title'       => lang("History Records Tour"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc'
          ));
    $this->template
      ->set_layout('datatables')
      ->build('product-tour-history/tour');
  }
  
  function detail($id_product_tour_log = 0){
    $detail = $this->global_models->get("product_tour_log", array("id_product_tour_log" => $id_product_tour_log));
// $this->debug($detail_array, true);
    $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));     
   
    $where = " AND id_product_tour_log = '".$detail[0]->id_product_tour_log."'";
    $info = $this->global_models->get_query("SELECT *"
      . " FROM product_tour_information_log"
      . " WHERE 1 = 1"
      . " {$where}"
      . " ORDER BY start_date ASC");
//    $this->debug($info, true);
    $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
    $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia & New Zealand", 5 => "Asia", 6 => "China");
        
    $this->template->build('product-tour-history/tour-detail', 
      array(
            'url'           => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'          => "inventory/product-tour",
            'data'          => $detail,
            'information'   => $info,
            'category'     => $category,
            'dropdown'              => $dropdown,
            'sub_category' => $sub_category,
            'title'         => lang("Detail Book Product Tour")." Version ".$detail[0]->version,
            'breadcrumb'    => array(
              "History Records Tour "  => "inventory/product-tour-history/index/".$detail[0]->id_product_tour
            ),
          ));
    $this->template
      ->set_layout('form')
      ->build('product-tour-history/tour-detail');
  }
  
  function use_it($id_product_tour_log){
    $detail = $this->global_models->get("product_tour_log", array("id_product_tour_log" => $id_product_tour_log));
    $schedule = $this->global_models->get("product_tour_information_log", array("id_product_tour_log" => $id_product_tour_log));
    
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
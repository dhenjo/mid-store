<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mrp extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
 
  function pengajuan_asset(){
    
    $jml = $this->global_models->get_query("SELECT count(A.id_mrp_pengajuan_asset) AS jml"
      . " FROM mrp_pengajuan_asset AS A"
      . " INNER JOIN m_users AS B ON A.id_users = B.id_users"
      . " LEFT JOIN hrm_settings_level_organisasi AS C ON B.id_hrm_settings_level_organisasi = C.id_hrm_settings_level_organisasi"
      . " WHERE C.code LIKE '%{$this->session->userdata('id_hrm_settings_level_organisasi')}%'"
      . " OR A.create_by_users = '{$this->session->userdata('id')}'");
    $jumlah_list = $jml->jml;
    
    $url_list = site_url("mrp/ajax-pengajuan-asset/".$jumlah_list);
    $url_list_halaman = site_url("ajax/ajax-halaman-default/".$jumlah_list);
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />";
    $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
      . "<script>"
      
      . "$( document ).ajaxStart(function(){ "
        . "$( '#loading-page' ).show();"
      . "});"
      . "$( document ).ajaxStop(function(){ "
        . "$( '#loading-page' ).hide();"
      . "});"
      
      . "function get_list(start){"
        . "if(typeof start === 'undefined'){"
          . "start = 0;"
        . "}"
        . "$.post('{$url_list}/'+start, function(data){"
          . "$('#data_list').html(data);"
          . "$.post('{$url_list_halaman}/'+start, function(data){"
            . "$('#halaman_set').html(data);"
          . "});"
        . "});"
      . "}"
      . "get_list(0);"
      . "</script>";

    $menutable = "<li><a href='".site_url("mrp/add-pengajuan-asset")."'><i class='icon-plus'></i> Add New</a></li>";
    $this->template->build('pengajuan-asset', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => 'mrp/pengajuan-asset',
            'title'   => lang("mrp_pengajuan_asset"),
            'foot'    => $foot,
            'css'     => $css,
            'menutable'   => $menutable,
            'menu_action' => 1
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('pengajuan-asset');
  }
  
  function pengajuan_pengadaan(){
    
    $id_hrm_department = $this->global_models->get_field("m_privilege", "id_hrm_department", 
      array("id_privilege" => $this->session->userdata("id_privilege")));
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "hrm_procurtment", "edit") !== FALSE){
      $procurment = "OR A.status = 2 OR A.status > 3";
    }
    
    $jml = $this->global_models->get_query("SELECT count(A.id_mrp_pengajuan_pengadaan) AS jml"
      . " FROM mrp_pengajuan_pengadaan AS A"
      . " WHERE A.id_hrm_department = '{$id_hrm_department}'"
      . " {$procurment}");
    $jumlah_list = $jml->jml;
    
    $url_list = site_url("mrp/ajax-pengajuan-pengadaan/".$jumlah_list);
    $url_list_halaman = site_url("ajax/ajax-halaman-default/".$jumlah_list);
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />";
    $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
      . "<script>"
      
      . "$( document ).ajaxStart(function(){ "
        . "$( '#loading-page' ).show();"
      . "});"
      . "$( document ).ajaxStop(function(){ "
        . "$( '#loading-page' ).hide();"
      . "});"
      
      . "function get_list(start){"
        . "if(typeof start === 'undefined'){"
          . "start = 0;"
        . "}"
        . "$.post('{$url_list}/'+start, function(data){"
          . "$('#data_list').html(data);"
          . "$.post('{$url_list_halaman}/'+start, function(data){"
            . "$('#halaman_set').html(data);"
          . "});"
        . "});"
      . "}"
      . "get_list(0);"
      . "</script>";

    $menutable = "<li><a href='".site_url("mrp/add-pengajuan-pengadaan")."'><i class='icon-plus'></i> Add New</a></li>";
    $this->template->build('pengajuan-pengadaan', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => 'mrp/pengajuan-pengadaan',
            'title'   => lang("mrp_pengajuan_pengadaan"),
            'foot'    => $foot,
            'css'     => $css,
            'menutable'   => $menutable,
            'menu_action' => 1
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('pengajuan-pengadaan');
  }
  
  function po($id_mrp_po_asset = 0){
    
    $id_hrm_department = $this->global_models->get_field("m_privilege", "id_hrm_department", 
      array("id_privilege" => $this->session->userdata("id_privilege")));
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "hrm_procurtment", "edit") !== FALSE){
      $procurment = "OR A.status = 2 OR A.status > 3";
    }
    
    $jml = $this->global_models->get_query("SELECT count(A.id_mrp_pengajuan_pengadaan) AS jml"
      . " FROM mrp_pengajuan_pengadaan AS A"
      . " WHERE A.id_hrm_department = '{$id_hrm_department}'"
      . " {$procurment}");
    $jumlah_list = $jml->jml;
    
    $url_list = site_url("mrp/ajax-pengajuan-pengadaan/".$jumlah_list);
    $url_list_halaman = site_url("ajax/ajax-halaman-default/".$jumlah_list);
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />";
    $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
      . "<script>"
      
      . "$( document ).ajaxStart(function(){ "
        . "$( '#loading-page' ).show();"
      . "});"
      . "$( document ).ajaxStop(function(){ "
        . "$( '#loading-page' ).hide();"
      . "});"
      
      . "function get_list(start){"
        . "if(typeof start === 'undefined'){"
          . "start = 0;"
        . "}"
        . "$.post('{$url_list}/'+start, function(data){"
          . "$('#data_list').html(data);"
          . "$.post('{$url_list_halaman}/'+start, function(data){"
            . "$('#halaman_set').html(data);"
          . "});"
        . "});"
      . "}"
      . "get_list(0);"
      . "</script>";

    $menutable = "<li><a href='".site_url("mrp/add-po/{$id_mrp_po_asset}")."'><i class='icon-plus'></i> Add New</a></li>";
    $this->template->build('pengajuan-pengadaan', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => 'mrp/pengajuan-pengadaan',
            'title'   => lang("mrp_pengajuan_pengadaan"),
            'foot'    => $foot,
            'css'     => $css,
            'menutable'   => $menutable,
            'menu_action' => 1
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('pengajuan-pengadaan');
  }
  
  function permintaan_asset(){
    
    $id_hrm_department = $this->global_models->get_field("m_privilege", "id_hrm_department", 
      array("id_privilege" => $this->session->userdata("id_privilege")));
    
    $jumlah_list = $this->global_models->get_field("mrp_pengajuan_asset", "count(id_mrp_pengajuan_asset)", 
      array("id_hrm_department" => $id_hrm_department));
    
    $url_list = site_url("mrp/ajax-permintaan-asset/".$jumlah_list);
    $url_list_halaman = site_url("ajax/ajax-halaman-default/".$jumlah_list);
    $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/css/tooltipster.css' rel='stylesheet' type='text/css' />";
    $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/tooltipster-master/js/jquery.tooltipster.min.js' type='text/javascript'></script>"
      . "<script>"
      
      . "$( document ).ajaxStart(function(){ "
        . "$( '#loading-page' ).show();"
      . "});"
      . "$( document ).ajaxStop(function(){ "
        . "$( '#loading-page' ).hide();"
      . "});"
      
      . "function get_list(start){"
        . "if(typeof start === 'undefined'){"
          . "start = 0;"
        . "}"
        . "$.post('{$url_list}/'+start, function(data){"
          . "$('#data_list').html(data);"
          . "$.post('{$url_list_halaman}/'+start, function(data){"
            . "$('#halaman_set').html(data);"
          . "});"
        . "});"
      . "}"
      . "get_list(0);"
      . "</script>";

    $this->template->build('permintaan-asset', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => 'mrp/permintaan-asset',
            'title'   => lang("mrp_permintaan_asset"),
            'foot'    => $foot,
            'css'     => $css,
            'menutable'   => $menutable,
            'menu_action' => 2
          ));
    $this->template
      ->set_layout('tableajax')
      ->build('permintaan-asset');
  }
  
  function ajax_pengajuan_asset($total = 0, $start = 0){
    
    $pengajuan = $this->global_models->get_query("SELECT A.*, D.title AS department"
      . " FROM mrp_pengajuan_asset AS A"
      . " LEFT JOIN hrm_department AS D ON A.id_hrm_department = D.id_hrm_department"
      . " INNER JOIN m_users AS B ON A.id_users = B.id_users"
      . " LEFT JOIN hrm_settings_level_organisasi AS C ON B.id_hrm_settings_level_organisasi = C.id_hrm_settings_level_organisasi"
      . " WHERE C.code LIKE '%-{$this->session->userdata('id_hrm_settings_level_organisasi')}-%'"
      . " OR A.create_by_users = '{$this->session->userdata('id')}'"
      . " ORDER BY A.tanggal LIMIT {$start}, 10");
    
    $status = array(
        1 => "<span class='label label-info'>Diajukan</span>",
        2 => "<span class='label label-success'>Disetujui</span>",
        3 => "<span class='label label-danger'>Ditolak</span>",
        4 => "<span class='label label-warning'>Proses</span>",
        5 => "<span class='label label'>Digunakan</span>",
    );
    foreach($pengajuan AS $p){
      $items = $this->global_models->get_query("SELECT B.title, A.qty"
        . " FROM mrp_pengajuan_asset_items AS A"
        . " LEFT JOIN mrp_master_asset_pengajuan AS B ON A.id_mrp_master_asset_pengajuan = B.id_mrp_master_asset_pengajuan"
        . " WHERE A.id_mrp_pengajuan_asset = '{$p->id_mrp_pengajuan_asset}'");
      $tooltips = "<ul>";
      foreach($items AS $i){
        $tooltips .= "<li>{$i->title} || {$i->qty}</li>";
      }
      $tooltips .= "</ul>";
      $hasil .= "<tr>"
        . "<td>"
          . "<a href='javascript:void(0)' id='tooltip{$p->id_mrp_pengajuan_asset}'>{$p->title}</a>"
          . "<div style='display: none' id='isitooltip{$p->id_mrp_pengajuan_asset}'>{$tooltips}</div>"
        . "</td>"
        . "<td>{$p->pic}</td>"
        . "<td>{$p->tanggal}</td>"
        . "<td>{$p->department}</td>"
        . "<td>{$status[$p->status]}</td>"
        . "<td>"
          . "<div class='btn-group'>"
          . "<button data-toggle='dropdown' class='btn btn-small dropdown-toggle'>Action<span class='caret'></span></button>"
          . "<ul class='dropdown-menu'>"
          . "<li><a href='".site_url("mrp/add-pengajuan-asset/".$p->id_mrp_pengajuan_asset)."'>Detail</a></li>"
          . "</ul>"
          . "</div>"
        . "</td>"
        . "</tr>"
          . "<script>"
          . "$(function() {"
            . "$('#tooltip{$p->id_mrp_pengajuan_asset}').tooltipster({"
              . "content: $('#isitooltip{$p->id_mrp_pengajuan_asset}').html(),"
              . "minWidth: 300,"
              . "maxWidth: 300,"
              . "contentAsHTML: true,"
              . "interactive: true"
            . "});"
          . "});"
          . "</script>"
          . "";
    }
    print $hasil;
    die;
  }
  
  function ajax_pengajuan_pengadaan($total = 0, $start = 0){
    $id_hrm_department = $this->global_models->get_field("m_privilege", "id_hrm_department", 
      array("id_privilege" => $this->session->userdata("id_privilege")));
    
    if($this->nbscache->get_olahan("permission", $this->session->userdata("id_privilege"), "hrm_procurment", "edit") !== FALSE){
      $procurment = "OR A.status = 2 OR A.status > 3";
    }
    
    $pengajuan = $this->global_models->get_query("SELECT A.*, D.title AS department"
      . " FROM mrp_pengajuan_pengadaan AS A"
      . " LEFT JOIN hrm_department AS D ON A.id_hrm_department = D.id_hrm_department"
      . " WHERE A.id_hrm_department = '{$id_hrm_department}'"
      . " {$procurment}"
      . " ORDER BY A.tanggal LIMIT {$start}, 10");
    
    $status = array(
        1 => "<span class='label label-info'>Diajukan</span>",
        2 => "<span class='label label-success'>Disetujui</span>",
        3 => "<span class='label label-danger'>Ditolak</span>",
        4 => "<span class='label label-warning'>Proses</span>",
        5 => "<span class='label label'>Digunakan</span>",
    );
    foreach($pengajuan AS $p){
      $items = $this->global_models->get_query("SELECT B.title, A.qty"
        . " FROM mrp_pengajuan_pengadaan_items AS A"
        . " LEFT JOIN mrp_master_asset_pengajuan AS B ON A.id_mrp_master_asset_pengajuan = B.id_mrp_master_asset_pengajuan"
        . " WHERE A.id_mrp_pengajuan_pengadaan = '{$p->id_mrp_pengajuan_pengadaan}'");
      $tooltips = "<ul>";
      foreach($items AS $i){
        $tooltips .= "<li>{$i->title} || {$i->qty}</li>";
      }
      $po = "";
      if($p->status == 2){
        $po = "<li><a href='".site_url("mrp/pengajuan-pengadaan-to-po-asset/".$p->id_mrp_pengajuan_pengadaan)."'>PO Asset</a></li>";
      }
      else if($p->status > 3){
        $po = "<li><a href='".site_url("mrp/po/".$p->id_mrp_pengajuan_pengadaan)."'>PO Asset</a></li>";
      }
      
      $tooltips .= "</ul>";
      $hasil .= "<tr>"
        . "<td>"
          . "<a href='javascript:void(0)' id='tooltip{$p->id_mrp_pengajuan_pengadaan}'>{$p->title}</a>"
          . "<div style='display: none' id='isitooltip{$p->id_mrp_pengajuan_pengadaan}'>{$tooltips}</div>"
        . "</td>"
        . "<td>{$p->department}</td>"
        . "<td>{$p->tanggal}</td>"
        . "<td>{$p->tanggal_approve}</td>"
        . "<td>{$status[$p->status]}</td>"
        . "<td>"
          . "<div class='btn-group'>"
          . "<button data-toggle='dropdown' class='btn btn-small dropdown-toggle'>Action<span class='caret'></span></button>"
          . "<ul class='dropdown-menu'>"
          . "<li><a href='".site_url("mrp/add-pengajuan-pengadaan/".$p->id_mrp_pengajuan_pengadaan)."'>Detail</a></li>"
          . $po
          . "</ul>"
          . "</div>"
        . "</td>"
        . "</tr>"
          . "<script>"
          . "$(function() {"
            . "$('#tooltip{$p->id_mrp_pengajuan_pengadaan}').tooltipster({"
              . "content: $('#isitooltip{$p->id_mrp_pengajuan_pengadaan}').html(),"
              . "minWidth: 300,"
              . "maxWidth: 300,"
              . "contentAsHTML: true,"
              . "interactive: true"
            . "});"
          . "});"
          . "</script>"
          . "";
    }
    print $hasil;
    die;
  }
  
  function ajax_permintaan_asset($total = 0, $start = 0){
    
    $id_hrm_department = $this->global_models->get_field("m_privilege", "id_hrm_department", 
      array("id_privilege" => $this->session->userdata("id_privilege")));
    
    $pengajuan = $this->global_models->get_query("SELECT A.*, D.title AS department"
      . " FROM mrp_pengajuan_asset AS A"
      . " LEFT JOIN hrm_department AS D ON A.id_hrm_department_request = D.id_hrm_department"
      . " INNER JOIN m_users AS B ON A.id_users = B.id_users"
      . " LEFT JOIN hrm_settings_level_organisasi AS C ON B.id_hrm_settings_level_organisasi = C.id_hrm_settings_level_organisasi"
      . " WHERE A.id_hrm_department = '{$id_hrm_department}'"
      . " ORDER BY A.tanggal LIMIT {$start}, 10");
    
    $status = array(
        1 => "<span class='label label-info'>Diajukan</span>",
        2 => "<span class='label label-success'>Disetujui</span>",
        3 => "<span class='label label-danger'>Ditolak</span>",
        4 => "<span class='label label-warning'>Proses</span>",
        5 => "<span class='label label'>Digunakan</span>",
    );
    foreach($pengajuan AS $p){
      $items = $this->global_models->get_query("SELECT B.title, A.qty"
        . " FROM mrp_pengajuan_asset_items AS A"
        . " LEFT JOIN mrp_master_asset_pengajuan AS B ON A.id_mrp_master_asset_pengajuan = B.id_mrp_master_asset_pengajuan"
        . " WHERE A.id_mrp_pengajuan_asset = '{$p->id_mrp_pengajuan_asset}'");
      $tooltips = "<ul>";
      foreach($items AS $i){
        $tooltips .= "<li>{$i->title} || {$i->qty}</li>";
      }
      $tooltips .= "</ul>";
      $selanjutnya = "";
      if($p->status < 5){
        $selanjutnya = "<li><a href='".site_url("mrp/pengajuan-asset-to-pengajuan-pengadaan/".$p->id_mrp_pengajuan_asset)."'>Pengajuan Pengadaan</a></li>";
      }
      $hasil .= "<tr>"
        . "<td>"
          . "<a href='javascript:void(0)' id='tooltip{$p->id_mrp_pengajuan_asset}'>{$p->title}</a>"
          . "<div style='display: none' id='isitooltip{$p->id_mrp_pengajuan_asset}'>{$tooltips}</div>"
        . "</td>"
        . "<td>{$p->pic}</td>"
        . "<td>{$p->department}</td>"
        . "<td>{$p->tanggal}</td>"
        . "<td>{$p->tanggal_approve}</td>"
        . "<td>{$status[$p->status]}</td>"
        . "<td>"
          . "<div class='btn-group'>"
          . "<button data-toggle='dropdown' class='btn btn-small dropdown-toggle'>Action<span class='caret'></span></button>"
          . "<ul class='dropdown-menu'>"
          . "<li><a href='".site_url("mrp/add-pengajuan-asset/".$p->id_mrp_pengajuan_asset)."'>Detail</a></li>"
          . $selanjutnya
          . "</ul>"
          . "</div>"
        . "</td>"
        . "</tr>"
          . "<script>"
          . "$(function() {"
            . "$('#tooltip{$p->id_mrp_pengajuan_asset}').tooltipster({"
              . "content: $('#isitooltip{$p->id_mrp_pengajuan_asset}').html(),"
              . "minWidth: 300,"
              . "maxWidth: 300,"
              . "contentAsHTML: true,"
              . "interactive: true"
            . "});"
          . "});"
          . "</script>"
          . "";
    }
    print $hasil;
    die;
  }
  
  public function add_pengajuan_asset($id_mrp_pengajuan_asset = 0){
    
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("mrp_pengajuan_asset", array("id_mrp_pengajuan_asset" => $id_mrp_pengajuan_asset));
      if($detail[0]->id_mrp_pengajuan_asset){
        $items = $this->global_models->get_query("SELECT A.*, B.title AS items"
          . " FROM mrp_pengajuan_asset_items AS A"
          . " LEFT JOIN mrp_master_asset_pengajuan AS B ON A.id_mrp_master_asset_pengajuan = B.id_mrp_master_asset_pengajuan"
          . " WHERE A.id_mrp_pengajuan_asset = '{$id_mrp_pengajuan_asset}'");
      }
      
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
        . "<script>"
          . "$(function() {"
            . "$( '#hrm_department' ).autocomplete({"
              . "source: '".site_url("ajax/department")."',"
              . "minLength: 1,"
              . "search  : function(){ $(this).addClass('working');},"
              . "open    : function(){ $(this).removeClass('working');},"
              . "select: function( event, ui ) {"
                . "$('#id_hrm_department').val(ui.item.id);"
              . "}"
            . "});"
            . "$( '#m_users' ).autocomplete({"
              . "source: '".site_url("ajax/users")."',"
              . "minLength: 1,"
              . "search  : function(){ $(this).addClass('working');},"
              . "open    : function(){ $(this).removeClass('working');},"
              . "select: function( event, ui ) {"
                . "$('#id_users').val(ui.item.id);"
              . "}"
            . "});"
            . "$( '.items' ).autocomplete({"
              . "source: '".site_url("ajax/master-asset-pengajuan")."',"
              . "minLength: 1,"
              . "search  : function(){ $(this).addClass('working');},"
              . "open    : function(){ $(this).removeClass('working');},"
            . "});"
          . "});"
        
        . "function tambah_items(){"
          . "$.post('".site_url("ajax/add-row-pengajuan-asset")."', function(data){"
            . "$('#tambah-items').append(data);"
          . "});"
        . "}"
        
        . "</script>";
        
      $this->template->build("add-pengajuan-asset", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/pengajuan-asset',
              'title'       => lang("mrp_add_pengajuan_asset"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "mrp_pengajuan_asset"  => "mrp/pengajuan-asset"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("add-pengajuan-asset");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "id_hrm_department"   => $pst['id_hrm_department'],
            "title"               => $pst['title'],
            "update_by_users"     => $this->session->userdata("id"),
        );
        if($pst['status'] == 2){
          $kirim['tanggal_approve'] = date("Y-m-d H:i:s");
          $kirim['status'] = 2;
        }
        else if($pst['status']){
          $kirim['status'] = $pst['status'];
        }
        
        $id_mrp_pengajuan_asset = $this->global_models->update("mrp_pengajuan_asset", array("id_mrp_pengajuan_asset" => $pst['id_detail']),$kirim);
        
        $this->global_models->update("mrp_pengajuan_asset_items", array("id_mrp_pengajuan_asset" => $pst['id_detail']), array("status" => 1));
        
        foreach ($pst['items'] AS $key => $items){
          if(trim($items)){
            $id_mrp_master_asset_pengajuan = $this->global_models->get_field("mrp_master_asset_pengajuan", "id_mrp_master_asset_pengajuan", array("LOWER(title)" => strtolower($items)));
            if(!$id_mrp_master_asset_pengajuan){
              $id_mrp_master_asset_pengajuan = $this->global_models->insert("mrp_master_asset_pengajuan", array("title" => $items, "create_by_users" => $this->session->userdata("id"),"create_date" => date("Y-m-d H:i:s")));
            }
            $kirim_items = array(
              "id_mrp_pengajuan_asset_items"    => $pst['id_mrp_pengajuan_asset_items'][$key],
              "id_mrp_pengajuan_asset"          => $pst['id_detail'],
              "id_mrp_master_asset_pengajuan"   => $id_mrp_master_asset_pengajuan,
              "qty"                             => $pst['qty'][$key],
              "status"                          => 2,
              "note"                            => $pst['note'][$key],
              "create_by_users"                 => $this->session->userdata("id"),
              "create_date"                     => date("Y-m-d H:i:s")
            );
            $else_items = array(
              "id_mrp_master_asset_pengajuan"   => $id_mrp_master_asset_pengajuan,
              "id_mrp_pengajuan_asset"          => $pst['id_detail'],
              "qty"                             => $pst['qty'][$key],
              "status"                          => 2,
              "note"                            => $pst['note'][$key],
              "update_by_users"                 => $this->session->userdata("id"),
            );
            $this->global_models->update_duplicate("mrp_pengajuan_asset_items", $kirim_items, $else_items);
          }
        }
        
      }
      else{
        $pic = $pst['pic'];
        if(!$pst['pic']){
          $pic = $this->global_models->get_field("m_users", "name", array("id_users" => $this->session->userdata("id")));
          $id_users = $this->session->userdata("id");
        }
        else{
          if(!$pst['id_users']){
            $users = $this->global_models->get("m_users", array("name" => $pst['pic']));
            $id_users = $users[0]->id_users;
          }
          else{
            $id_users = $pst['id_users'];
          }
        }
        $users = $this->global_models->get("m_users", array("id_users" => $id_users));
        $kirim = array(
            "id_users"                    => $id_users,
            "id_hrm_department"           => $pst['id_hrm_department'],
            "id_hrm_department_request"   => $this->global_models->get_field("m_privilege", "id_hrm_department", array("id_privilege" => $users[0]->id_privilege)),
            "title"                       => $pst['title'],
            "pic"                         => $users[0]->name,
            "tanggal"                     => date("Y-m-d"),
            "status"                      => 1,
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
        );
        
        $id_mrp_pengajuan_asset = $this->global_models->insert("mrp_pengajuan_asset", $kirim);
        
        foreach ($pst['items'] AS $key => $items){
          if(trim($items)){
            $id_mrp_master_asset_pengajuan = $this->global_models->get_field("mrp_master_asset_pengajuan", "id_mrp_master_asset_pengajuan", array("LOWER(title)" => strtolower($items)));
            if(!$id_mrp_master_asset_pengajuan){
              $id_mrp_master_asset_pengajuan = $this->global_models->insert("mrp_master_asset_pengajuan", array("title" => $items, "create_by_users" => $this->session->userdata("id"),"create_date" => date("Y-m-d H:i:s")));
            }
            $kirim_items[] = array(
              "id_mrp_pengajuan_asset"        => $id_mrp_pengajuan_asset,
              "id_mrp_master_asset_pengajuan" => $id_mrp_master_asset_pengajuan,
              "qty"                           => $pst['qty'][$key],
              "status"                        => 2,
              "note"                          => $pst['note'][$key],
              "create_by_users"               => $this->session->userdata("id"),
              "create_date"                   => date("Y-m-d H:i:s")
            );
          }
        }
        $this->global_models->insert_batch("mrp_pengajuan_asset_items", $kirim_items);
        
      }
      if($id_mrp_pengajuan_asset){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/pengajuan-asset");
    }
  }
 
  public function add_pengajuan_pengadaan($id_mrp_pengajuan_pengadaan = 0){
    
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("mrp_pengajuan_pengadaan", array("id_mrp_pengajuan_pengadaan" => $id_mrp_pengajuan_pengadaan));
      if($detail[0]->id_mrp_pengajuan_pengadaan){
        $items = $this->global_models->get_query("SELECT A.*, B.title AS items"
          . " FROM mrp_pengajuan_pengadaan_items AS A"
          . " LEFT JOIN mrp_master_asset_pengajuan AS B ON A.id_mrp_master_asset_pengajuan = B.id_mrp_master_asset_pengajuan"
          . " WHERE A.id_mrp_pengajuan_pengadaan = '{$id_mrp_pengajuan_pengadaan}'");
      }
      
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
        . "<script>"
          . "$(function() {"
            . "$( '#mrp_pengajuan_asset' ).autocomplete({"
              . "source: '".site_url("ajax/mrp-pengajuan-asset")."',"
              . "minLength: 1,"
              . "search  : function(){ $(this).addClass('working');},"
              . "open    : function(){ $(this).removeClass('working');},"
              . "select: function( event, ui ) {"
                . "$('#id_mrp_pengajuan_asset').val(ui.item.id);"
              . "}"
            . "});"
            . "$( '.items' ).autocomplete({"
              . "source: '".site_url("ajax/master-asset-pengajuan")."',"
              . "minLength: 1,"
              . "search  : function(){ $(this).addClass('working');},"
              . "open    : function(){ $(this).removeClass('working');},"
            . "});"
          . "});"
        
        . "function tambah_items(){"
          . "$.post('".site_url("ajax/add-row-pengajuan-asset")."', function(data){"
            . "$('#tambah-items').append(data);"
          . "});"
        . "}"
        
        . "</script>";
        
      $this->template->build("add-pengajuan-pengadaan", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'mrp/pengajuan-pengadaan',
              'title'       => lang("mrp_add_pengajuan_pengadaan"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "mrp_pengajuan_pengadaan"  => "mrp/pengajuan-pengadaan"
                ),
              'css'         => $css,
              'foot'        => $foot,
              'items'       => $items
            ));
      $this->template
        ->set_layout('form')
        ->build("add-pengajuan-pengadaan");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "title"               => $pst['title'],
            "update_by_users"     => $this->session->userdata("id"),
        );
        if($pst['status'] == 2){
          $kirim['tanggal_approve'] = date("Y-m-d H:i:s");
          $kirim['status'] = 2;
        }
        else if($pst['status']){
          $kirim['status'] = $pst['status'];
        }
        
        $id_mrp_pengajuan_pengadaan = $this->global_models->update("mrp_pengajuan_pengadaan", 
          array("id_mrp_pengajuan_pengadaan" => $pst['id_detail']),$kirim);
        
        $this->global_models->update("mrp_pengajuan_pengadaan_items", array("id_mrp_pengajuan_pengadaan" => $pst['id_detail']), array("status" => 1));
        
        foreach ($pst['items'] AS $key => $items){
          if(trim($items)){
            $id_mrp_master_asset_pengajuan = $this->global_models->get_field("mrp_master_asset_pengajuan", "id_mrp_master_asset_pengajuan", array("LOWER(title)" => strtolower($items)));
            if(!$id_mrp_master_asset_pengajuan){
              $id_mrp_master_asset_pengajuan = $this->global_models->insert("mrp_master_asset_pengajuan", array("title" => $items, "create_by_users" => $this->session->userdata("id"),"create_date" => date("Y-m-d H:i:s")));
            }
            $kirim_items = array(
              "id_mrp_pengajuan_pengadaan_items"    => $pst['id_mrp_pengajuan_pengadaan_items'][$key],
              "id_mrp_pengajuan_pengadaan"          => $pst['id_detail'],
              "id_mrp_master_asset_pengajuan"       => $id_mrp_master_asset_pengajuan,
              "qty"                                 => $pst['qty'][$key],
              "status"                              => 2,
              "note"                                => $pst['note'][$key],
              "create_by_users"                     => $this->session->userdata("id"),
              "create_date"                         => date("Y-m-d H:i:s")
            );
            $else_items = array(
              "id_mrp_master_asset_pengajuan"   => $id_mrp_master_asset_pengajuan,
              "id_mrp_pengajuan_pengadaan"      => $pst['id_detail'],
              "qty"                             => $pst['qty'][$key],
              "status"                          => 2,
              "note"                            => $pst['note'][$key],
              "update_by_users"                 => $this->session->userdata("id"),
            );
            $this->global_models->update_duplicate("mrp_pengajuan_pengadaan_items", $kirim_items, $else_items);
          }
        }
        
      }
      else{
        $kirim = array(
            "id_users"                    => $this->session->userdata("id"),
            "id_hrm_department"           => $this->global_models->get_field("m_privilege", "id_hrm_department", array("id_privilege" => $users[0]->id_privilege)),
            "id_mrp_pengajuan_asset"      => $pst['id_mrp_pengajuan_asset'],
            "title"                       => $pst['title'],
            "tanggal"                     => date("Y-m-d"),
            "status"                      => 1,
            "create_by_users"             => $this->session->userdata("id"),
            "create_date"                 => date("Y-m-d H:i:s")
        );
        
        $id_mrp_pengajuan_pengadaan = $this->global_models->insert("mrp_pengajuan_pengadaan", $kirim);
        
        foreach ($pst['items'] AS $key => $items){
          if(trim($items)){
            $id_mrp_master_asset_pengajuan = $this->global_models->get_field("mrp_master_asset_pengajuan", "id_mrp_master_asset_pengajuan", array("LOWER(title)" => strtolower($items)));
            if(!$id_mrp_master_asset_pengajuan){
              $id_mrp_master_asset_pengajuan = $this->global_models->insert("mrp_master_asset_pengajuan", array("title" => $items, "create_by_users" => $this->session->userdata("id"),"create_date" => date("Y-m-d H:i:s")));
            }
            $kirim_items[] = array(
              "id_mrp_pengajuan_pengadaan"    => $id_mrp_pengajuan_pengadaan,
              "id_mrp_master_asset_pengajuan" => $id_mrp_master_asset_pengajuan,
              "qty"                           => $pst['qty'][$key],
              "status"                        => 2,
              "note"                          => $pst['note'][$key],
              "create_by_users"               => $this->session->userdata("id"),
              "create_date"                   => date("Y-m-d H:i:s")
            );
          }
        }
        $this->global_models->insert_batch("mrp_pengajuan_pengadaan_items", $kirim_items);
        
      }
      if($id_mrp_pengajuan_pengadaan){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("mrp/pengajuan-pengadaan");
    }
  }
  
  function pengajuan_asset_to_pengajuan_pengadaan($id_mrp_pengajuan_asset){
    $this->global_models->update("mrp_pengajuan_asset", array("id_mrp_pengajuan_asset" => $id_mrp_pengajuan_asset), array("status" => 4));
    $asset = $this->global_models->get("mrp_pengajuan_asset", array("id_mrp_pengajuan_asset" => $id_mrp_pengajuan_asset));
    $items = $this->global_models->get("mrp_pengajuan_asset_items", array("id_mrp_pengajuan_asset" => $id_mrp_pengajuan_asset, "status" => 2));
    
    $id_hrm_department = $this->global_models->get_field("m_privilege", "id_hrm_department", 
      array("id_privilege" => $this->session->userdata("id_privilege")));
    
    $kirim = array(
      "id_users"                => $this->session->userdata("id"),
      "id_hrm_department"       => $id_hrm_department,
      "id_mrp_pengajuan_asset"  => $id_mrp_pengajuan_asset,
      "title"                   => $asset[0]->title,
      "tanggal"                 => date("Y-m-d"),
      "status"                  => 1,
      "note"                    => $asset[0]->note,
      "create_by_users"         => $this->session->userdata("id"),
      "create_date"             => date("Y-m-d H:i:s")
    );
    
    $id_mrp_pengajuan_pengadaan = $this->global_models->insert("mrp_pengajuan_pengadaan", $kirim);
    foreach($items AS $i){
      $kirim_items[] = array(
        "id_mrp_pengajuan_pengadaan"    => $id_mrp_pengajuan_pengadaan,
        "id_mrp_master_asset_pengajuan" => $i->id_mrp_master_asset_pengajuan,
        "qty"                           => $i->qty,
        "status"                        => 2,
        "note"                          => $i->note,
        "create_by_users"               => $this->session->userdata("id"),
        "create_date"                   => date("Y-m-d H:i:s")
      );
    }
    $this->global_models->insert_batch("mrp_pengajuan_pengadaan_items", $kirim_items);
    
    if($id_mrp_pengajuan_pengadaan){
      $this->session->set_flashdata('success', 'Data tersimpan');
    }
    else{
      $this->session->set_flashdata('notice', 'Data tidak tersimpan');
    }
    redirect("mrp/add-pengajuan-pengadaan/{$id_mrp_pengajuan_pengadaan}");
  }
  
  function pengajuan_pengadaan_to_po_asset($id_mrp_pengajuan_pengadaan){
    $this->global_models->update("mrp_pengajuan_pengadaan", array("id_mrp_pengajuan_pengadaan" => $id_mrp_pengajuan_pengadaan), array("status" => 4));
    $asset = $this->global_models->get("mrp_pengajuan_pengadaan", array("id_mrp_pengajuan_pengadaan" => $id_mrp_pengajuan_pengadaan));
    $items = $this->global_models->get("mrp_pengajuan_pengadaan_items", 
      array("id_mrp_pengajuan_pengadaan" => $id_mrp_pengajuan_pengadaan, "status" => 2));
    
    $id_hrm_department = $this->global_models->get_field("m_privilege", "id_hrm_department", 
      array("id_privilege" => $this->session->userdata("id_privilege")));
    
    $kirim = array(
      "id_mrp_pengajuan_pengadaan"  => $id_mrp_pengajuan_pengadaan,
      "title"                   => $asset[0]->title,
      "tanggal"                 => date("Y-m-d"),
      "status"                  => 1,
      "note"                    => $asset[0]->note,
      "create_by_users"         => $this->session->userdata("id"),
      "create_date"             => date("Y-m-d H:i:s")
    );
    
    $id_mrp_po_asset = $this->global_models->insert("mrp_po_asset", $kirim);
    
    if($id_mrp_po_asset){
      $this->session->set_flashdata('success', 'Data tersimpan');
    }
    else{
      $this->session->set_flashdata('notice', 'Data tidak tersimpan');
    }
    redirect("mrp/po/{$id_mrp_po_asset}");
  }
  
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
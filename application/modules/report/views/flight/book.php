<?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => ""))?>
<div class="box-body col-sm-6">

      <div class="control-group">
        <label>Start Date</label>
       <?php print $this->form_eksternal->form_input('start_date', $this->session->userdata('flight_btc_start_date'), 
        'id="start_date" class="form-control input-sm" placeholder="Start Date"');?>
      </div>

      <div class="control-group">
        <label>Book Code</label>
        <?php print $this->form_eksternal->form_input('book_code', $this->session->userdata('flight_btc_book_code'), 'class="form-control" placeholder="Book Code"')?>
      </div>
      
    </div>

<div class="box-body col-sm-6">

      <div class="control-group">
        <label>End Date</label>
        <?php print $this->form_eksternal->form_input('end_date', $this->session->userdata('flight_btc_end_date'), 
        'id="end_date" class="form-control input-sm" placeholder="End Date"');?>      
      </div>

      <div class="control-group">
        <label>Maskapai</label>
         <?php
        $data_maskapai = array("0"	=> "All",
				  "GA" => "Garuda Air",
				  "ID"	=> "Batik Air",
				  "IW"  => "Wings Air",
				  "JT"	=> "Lion Air",
				  "QG"	=> "Citylink",
				  "QZ"	=> "Air Asia",
				  "SJ"	=> "Sriwijaya Air");
         print $this->form_eksternal->form_dropdown('maskapai', $data_maskapai, array($this->session->userdata('flight_btc_maskapai')), 'class="form-control" placeholder="Maskapai"');?>
     
      </div>
      
    </div>

<div class="box-body col-sm-6" >

      <div class="control-group">
          <label>Pemesan</label>
        <?php print $this->form_eksternal->form_input('pemesan', $this->session->userdata('flight_btc_pemesan'), 
        'id="pemesan" class="form-control input-sm" placeholder="Pemesan"');?>
      </div>

     
    </div>

<div class="box-body col-sm-6" >

      <div class="control-group">
          <label>Status</label>
        <?php 
        
       $status = array(
          0 => "All",
          1 => "Proses",
          3 => "Issued",
          5 => "IBA",
          4 => "Cancel",
          6 => "No Tiket Gagal",
          7 => "File Tiket Gagal",
          8 => "Refund"
        );
       
       print $this->form_eksternal->form_dropdown('status', $status, array($this->session->userdata('flight_btc_status')), 'class="form-control" placeholder="Status"');?></div>
       </div>

      
    </div>

<div class="box-body col-sm-6">
  <div class="box-footer">
      <div class="control-group">
        <button class="btn btn-primary" type="submit">Search</button> 
      </div>
  </div>
</div>
    
    <div class="box-body col-sm-6">
  <div class="box-footer">
      <div class="control-group">
        <input type="submit" name="export" value="Export Excel" class="btn btn-primary" type="submit"></input> 
      </div>
  </div>
</div>

</form>
<br />


<thead style="margin-bottom: 50%;">
    <tr>
        <th>Tanggal Book</th>
        <th>Book Code</th>
        <th>Tipe</th>
        <th>Maskapai</th>
        <th>Pemesan</th>
        <th>Time Limit</th>
        <th>Payment</th>
        <th>Status</th>
        <th>Harga</th>
    </tr>
</thead>
 <tbody id="data_list">
    
  </tbody>
  <tfoot>
    <tr>
      <th colspan="9" style="height: <?php print 20 * $menu_action?>px"></th>
    </tr>
  </tfoot>
</table>
<div class="box-footer clearfix" id="halaman_set">
    <ul class="pagination pagination-sm no-margin pull-right">
        <li><a href="#">«</a></li>
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">»</a></li>
    </ul>
</div>
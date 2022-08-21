<?php
require_once("../../../config/database.php");
require_once("model.php");
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
session_start(); 
if ( !isset($_SESSION['session_username']) or !isset($_SESSION['session_id']) or !isset($_SESSION['session_level']) or !isset($_SESSION['session_kode_akses']) or !isset($_SESSION['session_hak_akses']) )
{
  echo '<div class="callout callout-danger">
          <h4>Session Berakhir!!!</h4>
          <p>Silahkan logout dan login kembali. Terimakasih.</p>
        </div>';
} else {
$db = new db();
$view=$_POST['view'];
$username = $_SESSION['session_username'];
$id_menus = 7; 
$cekMenusUser = $db->cekMenusUser($username,$id_menus); 
    foreach($cekMenusUser[1] as $data){
      $create = $data['c'];
      $read = $data['r'];
      $update = $data['u'];
      $delete = $data['d'];
      $nama_menus = $data['nama_menus'];
      $keterangan = $data['keterangan'];
    }
if($cekMenusUser[2] == 1) {
?>

<?php 
if($view == 'table'){
  $kode = htmlspecialchars($_POST['kode']);
  $kriteria = htmlspecialchars($_POST['kriteria']);
  if($kode == null){
    $getTable = $db->getTable($kriteria);
  } else {
    $getTable = $db->getTableKode($kode,$kriteria); 
  }
?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Data Referensi</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped text-nowrap">
      <thead>
      <tr>
        <th style="text-align:center;">#</th>
        <th style="text-align:center;">Aksi</th>
        <th style="text-align:center;">Kode</th>
        <th style="text-align:center;">Item</th>
        <th style="text-align:center;">Keterangan</th>
        <th style="text-align:center;">Html</th>
        <th style="text-align:center;">Ket1</th>
        <th style="text-align:center;">Ket2</th>
        <th style="text-align:center;">Ket3</th>
        <th style="text-align:center;">Ket4</th>
        <th style="text-align:center;">Ket5</th>
        <th style="text-align:center;">Status</th>
      </tr>
      </thead>
      <tbody>
      <?php
      $no = 1;
      foreach($getTable[1] as $row){
      ?>
      <tr>
        <td style="text-align:center;"><?php echo $no++; ?></td>
        <td style="text-align:center;">
          <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
              <span class="fa fa-fw fa-cogs"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
              <ul class="dropdown-menu" role="menu">
              <?php if($create == "y") {?>
              <li><a href="javascript:void(0)" class="read" id="<?php echo $row['id'];?>"><i class="fa fa-fw fa-eye"></i>Detail</a></li>
              <?php } ?>
              <?php if($update == "y") {?>
              <li><a href="javascript:void(0)" class="update" id="<?php echo $row['id'];?>"><i class="fa fa-fw fa-edit"></i>Edit</a></li>
              <?php } ?>
              <?php if($delete == "y") {?>
              <li><a href="javascript:void(0)" class="delete text-red" id="<?php echo $row['id'];?>"><i class="fa fa-fw fa-trash-o"></i>Hapus</a></li>
              <?php } ?>
              </ul>
          </div>
        </td>
        <td style="text-align:center;"><?php echo $row['kode'];?></td>
        <td style="text-align:center;"><?php echo $row['item'];?></td>
        <td style="text-align:left;"><?php echo $row['keterangan'];?></td>
        <td style="text-align:center;"><?php echo $row['html'];?></td>
        <td style="text-align:left;"><?php echo $row['ket1'];?></td>
        <td style="text-align:left;"><?php echo $row['ket2'];?></td>
        <td style="text-align:left;"><?php echo $row['ket3'];?></td>
        <td style="text-align:left;"><?php echo $row['ket4'];?></td>
        <td style="text-align:left;"><?php echo $row['ket5'];?></td>
        <td style="text-align:center;">
          <?php 
            $kode = "status_referensi";
            $item = $row['status'];
            $getReferensi = $db->getReferensi($kode,$item); 
            foreach($getReferensi[1] as $ref){
              echo $ref['html'];
            }
          ?>
        </td>
      </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->
</div>
<!-- /.box -->
<script>
  // Form edit
  function formUpdate(id) {
    let value = {
      view : 'form_update',
      id : id,
    }
    $.ajax({
      url:"menus/<?php echo $id_menus;?>/view.php",
      type: "POST",
      data: value,
      success: function(data, textStatus, jqXHR)
      { 
        Swal.close()
        $('#pages').html(data);
      },
      error: function (request, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: textStatus,
          didOpen: () => {
            Swal.hideLoading()
          }
        });
      }
    }); 
  }

  $(document).off('click', '.update').on('click', '.update', function(){
    Swal.fire({
      title: 'Loading...',
      html: 'Mohon menunggu sebentar...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    formUpdate($(this).attr('id'));
  });

  $(document).off('click', '.delete').on('click', '.delete', function(){
    let id = $(this).attr('id');
    Swal.fire({
      title: 'Hapus Data?',
      text: "Data akan dihapus selamanya!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Loading...',
          html: 'Mohon menunggu sebentar...',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
          }
        });
        let value = {
          controller : 'delete',
          id : id,
        }
        $.ajax({
          url:"menus/<?php echo $id_menus;?>/controller.php",
          type: "POST",
          data: value,
          success: function(data, textStatus, jqXHR)
          { 
            loadTable();
            $resp = JSON.parse(data);
            if($resp['status'] == true){
              toastr.success($resp['message'], $resp['title'], {timeOut: 2000, progressBar: true});
            } else {
              toastr.error($resp['message'], $resp['title'], {closeButton: true});
            }
          },
          error: function (request, textStatus, errorThrown) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: textStatus,
              didOpen: () => {
                Swal.hideLoading()
              }
            });
          }
        }); 
      }
    })
  });

  // Form read
  function formRead(id) {
    let value = {
      view : 'form_read',
      id : id,
    }
    $.ajax({
      url:"menus/<?php echo $id_menus;?>/view.php",
      type: "POST",
      data: value,
      success: function(data, textStatus, jqXHR)
      { 
        Swal.close()
        $('#pages').html(data);
      },
      error: function (request, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: textStatus,
          didOpen: () => {
            Swal.hideLoading()
          }
        });
      }
    }); 
  }

  $(document).off('click', '.read').on('click', '.read', function(){
    Swal.fire({
      title: 'Loading...',
      html: 'Mohon menunggu sebentar...',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      }
    });
    formRead($(this).attr('id'));
  });
</script>
<?php }?>


<?php 
if($view == 'form_create' && $create == "y"){
?>
<div class="box box-success box-solid">
  <div class="box-header">
    <h3 class="box-title">Tambah Referensi</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form class="form-horizontal" id="input_form_create">
      <div class="box-body">
        <div class="form-group">
          <label for="kode" class="col-sm-2 control-label">Kode</label>
          <div class="col-sm-10">
            <input type="hidden" class="form-control" name="controller" value="create">
            <select class="form-control select2" id="kode" name="kode" style="width: 100%;">
              <option value="">-- Pilih --</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="item" class="col-sm-2 control-label">Item</label>
          <div class="col-sm-10">
            <select class="form-control select2" id="item" name="item" style="width: 100%;">
              <option value="">-- Pilih --</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" style="resize:vertical;" placeholder="Keterangan..."></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="html" class="col-sm-2 control-label">Html</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="html" name="html" rows="3" style="resize:vertical;" placeholder="Keterangan..."></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket1" class="col-sm-2 control-label">Ket1</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket1" name="ket1" rows="2" style="resize:vertical;" placeholder="Ket1..."></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket2" class="col-sm-2 control-label">Ket2</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket2" name="ket2" rows="2" style="resize:vertical;" placeholder="Ket2..."></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket3" class="col-sm-2 control-label">Ket3</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket3" name="ket3" rows="2" style="resize:vertical;" placeholder="Ket3..."></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket4" class="col-sm-2 control-label">Ket4</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket4" name="ket4" rows="2" style="resize:vertical;" placeholder="Ket4..."></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket5" class="col-sm-2 control-label">Ket5</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket5" name="ket5" rows="2" style="resize:vertical;" placeholder="Ket5..."></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="status" class="col-sm-2 control-label">Status</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="status" name="status" style="width: 100%;">
              <option value="">-- Pilih --</option>
              <?php 
                $kode = "status_referensi";
                $getReferensiByKode = $db->getReferensiByKode($kode); 
                foreach($getReferensiByKode[1] as $ref){
              ?>
              <option value="<?php echo $ref['item'];?>"><?php echo $ref['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>

      <div class="box-footer">
        <button type="button" class="btn btn-success pull-right" id="btn-save">Simpan</button>
      </div>

    </form>
  </div>
  <!-- /.box-body -->
</div>
<!-- /.box -->
<script>
  $('#kode').select2({
    tags: true,
    selectOnClose: true,
    ajax: {
      url: "menus/<?php echo $id_menus;?>/controller.php",
      type: "POST",
      dataType: "JSON",
      delay: 250,
      data: function (params) {
        return {
          controller: 'get_kode',
          kriteria: params.term // search term
        };
      },
      processResults: function (response) {
        return {
          results: response
        };
      },
      cache: false
    }
  });

  $("#kode").change(function(){
    $('#item').html('<option value="">-- Pilih --</option>');
  });

  $('#item').select2({
    tags: true,
    selectOnClose: true,
    ajax: {
      url: "menus/<?php echo $id_menus;?>/controller.php",
      type: "POST",
      dataType: "JSON",
      delay: 250,
      data: function (params) {
        return {
          controller: 'get_item',
          kode: $('#kode').val(),
          kriteria: params.term // search term
        };
      },
      processResults: function (response) {
        return {
          results: response
        };
      },
      cache: false
    }
  });
  $('#btn-save').click(function() {
    if($('#kode').val() == ''){
      $('#kode').focus();
      Swal.fire("Validasi!", "Kode wajib diisi.");
      return;
    }
    if($('#item').val() == ''){
      $('#item').focus();
      Swal.fire("Validasi!", "Item wajib diisi.");
      return;
    }
    if($('#keterangan').val() == ''){
      $('#keterangan').focus();
      Swal.fire("Validasi!", "Keterangan wajib diisi.");
      return;
    }
    if($('#status').val() == ''){
      $('#status').focus();
      Swal.fire("Validasi!", "Status wajib diisi.");
      return;
    }
    Swal.fire({
      title: 'Tambah Data?',
      text: "Data akan ditambahkan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Tambah',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Loading...',
          html: 'Mohon menunggu sebentar...',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
            var val = $('#input_form_create').serialize();
            $.ajax({
              url:"menus/<?php echo $id_menus;?>/controller.php",
              type: "POST",
              data: val,
              success: function(data, textStatus, jqXHR)
              { 
                loadTable();
                $resp = JSON.parse(data);
                if($resp['status'] == true){
                  toastr.success($resp['message'], $resp['title'], {timeOut: 2000, progressBar: true});
                } else {
                  toastr.error($resp['message'], $resp['title'], {closeButton: true});
                }
              },
              error: function (request, textStatus, errorThrown) {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: textStatus,
                  didOpen: () => {
                    Swal.hideLoading()
                  }
                });
              }
            });
          }
        });
      }
    })
  });
</script>
<?php }?>

<?php 
if($view == 'form_update' && $update == "y"){
  $id = $_POST['id'];
  $getDataById = $db->getDataById($id); 
  foreach($getDataById[1] as $data){
?>
<div class="box box-warning box-solid">
  <div class="box-header">
    <h3 class="box-title">Edit Referensi</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form class="form-horizontal" id="input_form_update">
      <div class="box-body">
        <div class="form-group">
          <label for="kode" class="col-sm-2 control-label">Kode</label>
          <div class="col-sm-10">
            <input type="hidden" class="form-control" name="controller" value="update">
            <input type="hidden" class="form-control" name="id" value="<?php echo $id;?>">
            <select class="form-control select2" id="kode" name="kode" style="width: 100%;">
              <option value="<?php echo $data['kode'];?>"><?php echo $data['kode'];?></option>
              <option value="">-- Pilih --</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="item" class="col-sm-2 control-label">Item</label>
          <div class="col-sm-10">
            <select class="form-control select2" id="item" name="item" style="width: 100%;">
              <option value="<?php echo $data['item'];?>"><?php echo $data['item'];?></option>
              <option value="">-- Pilih --</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" style="resize:vertical;" placeholder="Keterangan..."><?php echo $data['keterangan'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="html" class="col-sm-2 control-label">Html</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="html" name="html" rows="3" style="resize:vertical;" placeholder="Keterangan..."><?php echo $data['html'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket1" class="col-sm-2 control-label">Ket1</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket1" name="ket1" rows="2" style="resize:vertical;" placeholder="Ket1..."><?php echo $data['ket1'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket2" class="col-sm-2 control-label">Ket2</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket2" name="ket2" rows="2" style="resize:vertical;" placeholder="Ket2..."><?php echo $data['ket2'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket3" class="col-sm-2 control-label">Ket3</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket3" name="ket3" rows="2" style="resize:vertical;" placeholder="Ket3..."><?php echo $data['ket3'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket4" class="col-sm-2 control-label">Ket4</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket4" name="ket4" rows="2" style="resize:vertical;" placeholder="Ket4..."><?php echo $data['ket4'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket5" class="col-sm-2 control-label">Ket5</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket5" name="ket5" rows="2" style="resize:vertical;" placeholder="Ket5..."><?php echo $data['ket5'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="status" class="col-sm-2 control-label">Status</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="status" name="status" style="width: 100%;">
              <?php 
                $kode = "status_referensi";
                $item = $data['status'];
                $getReferensi = $db->getReferensi($kode,$item); 
                foreach($getReferensi[1] as $row){
              ?>
              <option value="<?php echo $row['item'];?>"><?php echo $row['keterangan'];?></option>
              <?php } ?>
              <option value="">-- Pilih --</option>
              <?php 
                $kode = "status_referensi";
                $getReferensiByKode = $db->getReferensiByKode($kode); 
                foreach($getReferensiByKode[1] as $ref){
              ?>
              <option value="<?php echo $ref['item'];?>"><?php echo $ref['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>

      <div class="box-footer">
        <button type="button" class="btn btn-warning pull-right" id="btn-save">Simpan</button>
      </div>

    </form>
  </div>
  <!-- /.box-body -->
</div>
<!-- /.box -->
<script>
  $('#kode').select2({
    tags: true,
    selectOnClose: true,
    ajax: {
      url: "menus/<?php echo $id_menus;?>/controller.php",
      type: "POST",
      dataType: "JSON",
      delay: 250,
      data: function (params) {
        return {
          controller: 'get_kode',
          kriteria: params.term // search term
        };
      },
      processResults: function (response) {
        return {
          results: response
        };
      },
      cache: false
    }
  });

  $("#kode").change(function(){
    $('#item').html('<option value="">-- Pilih --</option>');
  });

  $('#item').select2({
    tags: true,
    selectOnClose: true,
    ajax: {
      url: "menus/<?php echo $id_menus;?>/controller.php",
      type: "POST",
      dataType: "JSON",
      delay: 250,
      data: function (params) {
        return {
          controller: 'get_item',
          kode: $('#kode').val(),
          kriteria: params.term // search term
        };
      },
      processResults: function (response) {
        return {
          results: response
        };
      },
      cache: false
    }
  });

  $('#btn-save').click(function() {
    if($('#kode').val() == ''){
      $('#kode').focus();
      Swal.fire("Validasi!", "Kode wajib diisi.");
      return;
    }
    if($('#item').val() == ''){
      $('#item').focus();
      Swal.fire("Validasi!", "Item wajib diisi.");
      return;
    }
    if($('#keterangan').val() == ''){
      $('#keterangan').focus();
      Swal.fire("Validasi!", "Keterangan wajib diisi.");
      return;
    }
    if($('#status').val() == ''){
      $('#status').focus();
      Swal.fire("Validasi!", "Status wajib diisi.");
      return;
    }
    Swal.fire({
      title: 'Edit Data?',
      text: "Data akan dirubah!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Edit',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Loading...',
          html: 'Mohon menunggu sebentar...',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
            var val = $('#input_form_update').serialize();
            $.ajax({
              url:"menus/<?php echo $id_menus;?>/controller.php",
              type: "POST",
              data: val,
              success: function(data, textStatus, jqXHR)
              { 
                loadTable();
                $resp = JSON.parse(data);
                if($resp['status'] == true){
                  toastr.success($resp['message'], $resp['title'], {timeOut: 2000, progressBar: true});
                } else {
                  toastr.error($resp['message'], $resp['title'], {closeButton: true});
                }
              },
              error: function (request, textStatus, errorThrown) {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: textStatus,
                  didOpen: () => {
                    Swal.hideLoading()
                  }
                });
              }
            });
          }
        });
      }
    })
  });
</script>
<?php }}?>

<?php 
if($view == 'form_read' && $read == "y"){
  $id = $_POST['id'];
  $getDataById = $db->getDataById($id); 
  foreach($getDataById[1] as $data){
?>
<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title">Detail Referensi</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form class="form-horizontal">
      <div class="box-body">
        <div class="form-group">
          <label for="kode" class="col-sm-2 control-label">Kode</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="kode" name="kode" style="width: 100%;">
              <option value="<?php echo $data['kode'];?>"><?php echo $data['kode'];?></option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="item" class="col-sm-2 control-label">Item</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="item" name="item" style="width: 100%;">
              <option value="<?php echo $data['item'];?>"><?php echo $data['item'];?></option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" style="resize:vertical;" placeholder="Keterangan..." disabled><?php echo $data['keterangan'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="html" class="col-sm-2 control-label">Html</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="html" name="html" rows="3" style="resize:vertical;" placeholder="Keterangan..." disabled><?php echo $data['html'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket1" class="col-sm-2 control-label">Ket1</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket1" name="ket1" rows="2" style="resize:vertical;" placeholder="Ket1..." disabled><?php echo $data['ket1'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket2" class="col-sm-2 control-label">Ket2</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket2" name="ket2" rows="2" style="resize:vertical;" placeholder="Ket2..." disabled><?php echo $data['ket2'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket3" class="col-sm-2 control-label">Ket3</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket3" name="ket3" rows="2" style="resize:vertical;" placeholder="Ket3..." disabled><?php echo $data['ket3'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket4" class="col-sm-2 control-label">Ket4</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket4" name="ket4" rows="2" style="resize:vertical;" placeholder="Ket4..." disabled><?php echo $data['ket4'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="ket5" class="col-sm-2 control-label">Ket5</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="ket5" name="ket5" rows="2" style="resize:vertical;" placeholder="Ket5..." disabled><?php echo $data['ket5'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="status" class="col-sm-2 control-label">Status</label>
          <div class="col-sm-10">
            <select class="form-control select22" id="status" name="status" style="width: 100%;">
              <?php 
                $kode = "status_referensi";
                $item = $data['status'];
                $getReferensi = $db->getReferensi($kode,$item); 
                foreach($getReferensi[1] as $row){
              ?>
              <option value="<?php echo $row['item'];?>"><?php echo $row['keterangan'];?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- /.box-body -->

</div>
<!-- /.box -->
<?php }}?>

<script>
  $('.select22').select2()

  $(function () {
    $('.table').DataTable({
      'language': {
        "emptyTable": "Data tidak ditemukan.",
        "info": "Menampilkan _START_ - _END_ dari _TOTAL_",
        "infoEmpty": "Menampilkan 0 - 0 dari 0",
        "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
        "lengthMenu": "Tampilkan _MENU_ baris",
        "search": "Cari:",
        "zeroRecords": "Tidak ditemukan data yang sesuai.",
        "thousands": "'",
        "paginate": {
          "first": "<<",
          "last": ">>",
          "next": ">",
          "previous": "<"
        }
      },  
      'destroy'     : true,
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    })
  })
</script>
<?php 
}}} else {
  header("HTTP/1.1 401 Unauthorized");
  exit;
} ?>
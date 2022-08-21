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
if(isset($_POST['controller'])) {
  $controller = $_POST['controller'];
} else {
  $controller = "";
}
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

// start - controller
if($controller == 'get_kode'){
  if (isset($_POST['kriteria'])) {
    $kriteria = $_POST['kriteria'];
    $getKode = $db->getKode($kriteria);
    $data = array();
    foreach($getKode[1] as $option){
      $data[] = array("id"=>$option['kode'], "text"=>$option['kode']);
    } 
    echo json_encode($data);
  }
} else if($controller == 'get_item'){
  if (isset($_POST['kode']) && isset($_POST['kriteria'])) {
    $kode = $_POST['kode'];
    $kriteria = $_POST['kriteria'];
    $getItem = $db->getItem($kode,$kriteria);
    $data = array();
    foreach($getItem[1] as $option){
      $data[] = array("id"=>$option['item'], "text"=>$option['item']);
    } 
    echo json_encode($data);
  }
} else if($controller == 'delete' && $delete == "y"){
  $id = $_POST['id'];
 
  $run = $db->delete($id);
  $retval['status'] = $run[0];
  $retval['title'] = $run[1];
  $retval['message'] = $run[2];
  
  echo json_encode($retval);
} else if($controller == 'create' && $create == "y"){
  $kode = htmlspecialchars($_POST['kode']);
  $item = htmlspecialchars($_POST['item']);
  $id = $kode.".".$item;
  $keterangan = htmlspecialchars($_POST['keterangan']);
  $html = $_POST['html'];
  $ket1 = htmlspecialchars($_POST['ket1']);
  $ket2 = htmlspecialchars($_POST['ket2']);
  $ket3 = htmlspecialchars($_POST['ket3']);
  $ket4 = htmlspecialchars($_POST['ket4']);
  $ket5 = htmlspecialchars($_POST['ket5']);
  $status = htmlspecialchars($_POST['status']);

  $run = $db->create($kode,$item,$id,$keterangan,$html,$ket1,$ket2,$ket3,$ket4,$ket5,$status);
  $retval['status'] = $run[0];
  $retval['title'] = $run[1];
  $retval['message'] = $run[2];
  
  echo json_encode($retval);
} else if($controller == 'update' && $update == "y"){
  $id = htmlspecialchars($_POST['id']);
  $kode = htmlspecialchars($_POST['kode']);
  $item = htmlspecialchars($_POST['item']);
  $id_baru = $kode.".".$item;
  $keterangan = htmlspecialchars($_POST['keterangan']);
  $html = $_POST['html'];
  $ket1 = htmlspecialchars($_POST['ket1']);
  $ket2 = htmlspecialchars($_POST['ket2']);
  $ket3 = htmlspecialchars($_POST['ket3']);
  $ket4 = htmlspecialchars($_POST['ket4']);
  $ket5 = htmlspecialchars($_POST['ket5']);
  $status = htmlspecialchars($_POST['status']);

  $run = $db->update($id,$kode,$item,$id_baru,$keterangan,$html,$ket1,$ket2,$ket3,$ket4,$ket5,$status);
  $retval['status'] = $run[0];
  $retval['title'] = $run[1];
  $retval['message'] = $run[2];
  
  echo json_encode($retval);
} else {
  $retval['status'] = false;
  $retval['message'] = "Tidak memiliki hak akses.";
  $retval['title'] = "Error!";
  echo json_encode($retval); 
}
// end - controller

}}} else {
  header("HTTP/1.1 401 Unauthorized");
  exit;
} ?>
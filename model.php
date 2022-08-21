<?php 
class db extends dbconn {

    public function __construct()
    {
        $this->initDBO();
    }
    
    // -- START -- SELECT
    public function cekMenusUser($username,$id_menus)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                      tbl_users_menus.*,
                      tbl_menus.nama_menus,
                      tbl_menus.keterangan,
                      tbl_menus.status 
                    FROM
                      tbl_users_menus
                      INNER JOIN tbl_menus ON tbl_users_menus.id_menus = tbl_menus.id_menus
                    WHERE
                      tbl_users_menus.username = :username AND tbl_users_menus.id_menus = :id_menus AND tbl_menus.status = 'a' 
                    ";
            $stmt = $db->prepare($query);
            $stmt->bindParam("username",$username);
            $stmt->bindParam("id_menus",$id_menus);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getReferensi($kode,$item)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE kode = :kode AND item = :item";
            $stmt = $db->prepare($query);
            $stmt->bindParam("kode",$kode);
            $stmt->bindParam("item",$item);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getReferensiByKode($kode)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE kode = :kode AND status = 'a'";
            $stmt = $db->prepare($query);
            $stmt->bindParam("kode",$kode);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getKode($kriteria)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE kode LIKE '%$kriteria%'  GROUP BY kode ORDER BY kode ASC LIMIT 100";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getItem($kode,$kriteria)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE kode = :kode AND item LIKE '%$kriteria%' ORDER BY item ASC LIMIT 100";
            $stmt = $db->prepare($query);
            $stmt->bindParam("kode",$kode);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getTable($kriteria)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE kode LIKE '%$kriteria%' OR item LIKE '%$kriteria%' OR keterangan LIKE '%$kriteria%' ORDER BY kode ASC, keterangan ASC LIMIT 100";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getTableKode($kode,$kriteria)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE kode = :kode AND (kode LIKE '%$kriteria%' OR item LIKE '%$kriteria%' OR keterangan LIKE '%$kriteria%') ORDER BY kode ASC, keterangan ASC LIMIT 100";
            $stmt = $db->prepare($query);
            $stmt->bindParam("kode",$kode);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getDataById($id)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE id = :id  LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }
    // -- END -- SELECT

    // -- START -- DELETE
    public function delete($id)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "DELETE FROM tbl_referensi WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "HAPUS!";
            $stat[2] = "Data berhasil dihapus.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "HAPUS!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }
    // -- END -- DELETE

    // -- START -- CREATE
    public function create($kode,$item,$id,$keterangan,$html,$ket1,$ket2,$ket3,$ket4,$ket5,$status)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "INSERT INTO tbl_referensi (id, kode, item, keterangan, html, ket1, ket2, ket3, ket4, ket5, status) VALUES (:id, :kode, :item, :keterangan, :html, :ket1, :ket2, :ket3, :ket4, :ket5, :status)";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->bindParam("kode",$kode);
            $stmt->bindParam("item",$item);
            $stmt->bindParam("keterangan",$keterangan);
            $stmt->bindParam("html",$html);
            $stmt->bindParam("ket1",$ket1);
            $stmt->bindParam("ket2",$ket2);
            $stmt->bindParam("ket3",$ket3);
            $stmt->bindParam("ket4",$ket4);
            $stmt->bindParam("ket5",$ket5);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "TAMBAH!";
            $stat[2] = "Data berhasil ditambahkan.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "TAMBAH!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }
    // -- END -- CREATE

    // -- START -- UPDATE
    public function update($id,$kode,$item,$id_baru,$keterangan,$html,$ket1,$ket2,$ket3,$ket4,$ket5,$status)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "UPDATE tbl_referensi SET kode = :kode, item = :item, id = :id_baru, keterangan = :keterangan, html = :html, ket1 = :ket1, ket2 = :ket2, ket3 = :ket3, ket4 = :ket4, ket5 = :ket5, status = :status WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->bindParam("kode",$kode);
            $stmt->bindParam("item",$item);
            $stmt->bindParam("id_baru",$id_baru);
            $stmt->bindParam("keterangan",$keterangan);
            $stmt->bindParam("html",$html);
            $stmt->bindParam("ket1",$ket1);
            $stmt->bindParam("ket2",$ket2);
            $stmt->bindParam("ket3",$ket3);
            $stmt->bindParam("ket4",$ket4);
            $stmt->bindParam("ket5",$ket5);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "EDIT!";
            $stat[2] = "Data berhasil dirubah.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "EDIT!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }
    // -- END -- UPDATE

}
<?php

Class UsuarioModel extends CI_Model {

    function __construct()
    {
      parent::__construct();
    }

   public function login($data) {
     $condition = "nombreusuario =" . "'" . $data['username'] . "' AND " . "clave = crypt(" . "'" . $data['password'] . "'". ",'". $data['cripto'] ."'".")";
     $this->db->select('*');
     $this->db->from('tbl_ctrl_usuario');
     $this->db->where($condition);
     $this->db->where('estado',true);
     $this->db->limit(1);
     $query = $this->db->get();
     if ($query->num_rows() == 1) {
       return true;
     } else {
       return false;
     }
  }

  function validarJefeSupervisor($idUsuarioReq){
    $this->db->select('idempleado');
    $this->db->from('ft_ctrl_rel_jefetienda');
    $this->db->where('idempleado', $idUsuarioReq);
    $this->db->limit(1);
    $query = $this->db->get();
    if ($query->num_rows() == 1) {
      return true;
    } else {
      return false;
    }
  }

  public function obtenerUsuario($idUsuario){
  	$this->db->select('*');
    $this->db->from('tbl_ctrl_usuario');
    $this->db->where('idusuario',$idUsuario);
    $this->db->where('estado',true);
    $this->db->limit(1);
    $query = $this->db->get();
    if ($query->num_rows() == 1) {
      return $query->result();
    } else {
      return false;
    }
  }

  function update_usuario($id,$usuario){
    $this->db->where('idusuario',$id);
    $this->db->update('tbl_ctrl_usuario',$usuario);
    return true;
  }

  function accesoInvalido($fallido){
    $query = $this->db->insert('tbl_ctrl_usuariofallido', $fallido);
  return $this->db->insert_id();
  }

  public function read_user_information($username) {
    $condition = "nombreusuario =" . "'" . $username . "'";
    $this->db->select('*');
    $this->db->from('tbl_ctrl_usuario');
    $this->db->where($condition);
    $this->db->where('estado',true);
    $this->db->limit(1);
    $query = $this->db->get();
    if ($query->num_rows() == 1) {
      return $query->result();
    } else {
      return false;
    }
  }

}

?>

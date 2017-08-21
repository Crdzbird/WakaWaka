<?php

class BeneficiosModel extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function getAll(){
		$this->db->select('*');
		$this->db->from('vistaBeneficios');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      $data = array();
       if($query->num_rows() > 0){
         foreach ($query ->result_array() as $row) {
           $data[] = $row;
         }
       }
     }
     $query->free_result();
//		 echo json_encode(array("students"=>$temp_array));
     return json_encode($data);
	}

	function getBeneficioId($idBuscado){
		$this->db->select('IdBeneficio');
		$this->db->from('Beneficios');
		$this->db->where('IdBeneficio',$idBuscado);
		$this->db->where('Estado',true);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$data = array();
			foreach ($query ->result_array() as $row) {
				$data[] = $row;
			}
		}
		$query->free_result();
		return $data[0]['IdBeneficio'];
    //return json_encode($data);
	}

	function getBeneficiosEstado($estado){
		//echo $estado;
   $this->db->select('s.*');
	 $this->db->from('(select @state:='.$estado.')parm, vistaBeneficiosEstados s');
   $query = $this->db->get();
   if ($query->num_rows() > 0) {
     $data = array();
      if($query->num_rows() > 0){
        foreach ($query ->result_array() as $row) {
          $data[] = $row;
        }
      }
    }
    $query->free_result();
    return json_encode($data);
  }

	function insertBeneficioEmpleado($data){
		$query = $this->db->insert('beneficioRegistrado', $data);
		if ($this->db->insert_id() != 0) {
			return json_encode(array("estado"=>true));
		}else{
			return json_encode(array("estado"=>false));
		}
	}

	function getVersionBeneficio($lastId){
		$this->db->select('IdBeneficio');
		$this->db->from('Beneficios');
		$this->db->order_by('IdBeneficio','Desc');
		$this->db->limit(1);
		$query = $this->db->get();
    if ($query->num_rows() > 0) {
      $data = array();
       if($query->num_rows() > 0){
         foreach ($query ->result_array() as $row) {
           $data[] = $row;
         }
       }
     }
		 if ($lastId != $data[0]['IdBeneficio']) {
			 $query->free_result();
			 return json_encode(array("estado"=>true));
		 }else {
			 $query->free_result();
			 return json_encode(array("estado"=>false));
		 }
	}
}
 ?>

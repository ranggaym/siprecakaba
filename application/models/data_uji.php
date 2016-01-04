<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_uji extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function insert($data)
	{
		$this->db->insert('data_uji', $data); 
	}
	
	function insert_from_array($arr)
	{
		$this->db->truncate('data_uji'); 
		
		foreach($arr as $item)
		{
			$columns = array(
				'no' => $item['no'],
				'ipk' => $item['ipk'],
				'psi' => $item['psi'],
				'ww' => $item['ww'],
				'class' => $item['class']
			);
			$this->db->insert('data_uji', $columns);
		}
	}
	
	function update($data)
	{
		$this->db->update('data_uji', $data); 
	}
	
	function get_classes_only()
	{
		$this->db->select('class');
		$query = $this->db->get('data_uji');
		return $query->result();
	}
	
	function get_predicted_class()
	{
		$this->db->select('no,ipk,psi,ww,predicted,prediction');
		$this->db->from('data_uji,prediksi');
		$this->db->where('prediksi.inst = data_uji.no');
		
		$query = $this->db->get();
		return $query->result_array();
	}

}
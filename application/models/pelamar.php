<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pelamar extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function insert($data)
	{
		$this->db->insert('pelamar', $data); 
	}
	
	function insert_from_array($csv)
	{
		$this->db->truncate('pelamar'); 
		
		foreach($csv as $item)
		{
			$columns = array(
				'no' => $item['no'],
				'ipk' => $item['ipk'],
				'psi' => $item['psi'],
				'ww' => $item['ww'],
				'class' => $item['class']
			);
			$this->db->insert('pelamar', $columns);
		}
	}
	
	function update($data)
	{
		$this->db->update('pelamar', $data); 
	}
	
	function get_classes_only()
	{
		$this->db->select('class');
		$query = $this->db->get('pelamar');
		return $query->result();
	}

}
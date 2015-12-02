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
	
	function insert_from_csv($csv)
	{
		$this->db->truncate('pelamar'); 
	
		$first_row = true;

		
		$while(($data = fgetcsv($csv, 1000, ",")) !== FALSE) {
			if($first_row)
				$first_row = false;
			else {
				$columns = array(
					'ipk' => $csv[0],
					'psi' => $csv[1],
					'ww' => $csv[2],
					'class' => $csv[3]
				);
				$this->db->insert('pelamar', $columns);
			}
		}
		
		fclose($csv);
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
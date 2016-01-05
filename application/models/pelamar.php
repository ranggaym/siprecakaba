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
	
	function insert_from_array($arr)
	{
		$this->db->truncate('pelamar'); 
		
		foreach($arr as $item)
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
	
	function dump_to_csv($file_name)
	{
		$this->load->dbutil();
		$this->load->helper('file');
		$delimiter = ",";
		$newline = "\r\n";
		
		$this->db->select();
		$query = $this->db->get('pelamar');
		
		$csv_data = $this->dbutil->csv_from_result($query,$delimiter,$newline);
		
		$csv_data = str_replace($delimiter.$newline,$newline,$csv_data);
		$csv_data = str_replace('"','',$csv_data);
		
		if(!write_file($file_name,$csv_data))
		{
			show_error('Write file error');
			$this->output->set_header('refresh:3; url='.site_url()); 
		}
		
	}

}
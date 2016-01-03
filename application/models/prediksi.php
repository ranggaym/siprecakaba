<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prediksi extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function insert($data)
	{
		$this->db->insert('prediksi', $data); 
	}
	
	function insert_from_array($csv)
	{
		$this->db->truncate('prediksi'); 
		print_r($csv);
		foreach($csv as $item)
		{
			$columns = array(
				'inst#' => $item['inst#'],
				'actual' => $item['actual'],
				'predicted' => $item['predicted'],
				'error' => $item['error'],
				'prediction' => $item['prediction']
			);
			$this->db->insert('prediksi', $columns);
		}
	}
	
	function update($data)
	{
		$this->db->update('prediksi', $data); 
	}
	
	function get_predicted_only()
	{
		$this->db->select('predicted');
		$query = $this->db->get('prediksi');
		return $query->result();
	}

}
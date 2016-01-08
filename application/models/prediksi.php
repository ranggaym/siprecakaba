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
	
	function insert_from_array($arr)
	{
		$this->db->truncate('prediksi'); 
		foreach($arr as $item)
		{
			$columns = array(
				'inst' => $item['inst#'],
				'actual' => $item['actual'],
				'predicted' => substr($item['predicted'],2),
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
	
	function get_true_p()
	{
		$this->db->select('actual','predicted');
		$this->db->from('prediksi');
		$this->db->where('actual = predicted AND predicted = "ya" AND actual != "1:?"');
		return $this->db->count_all_results();
	}
	
	function get_true_n()
	{
		$this->db->select('actual','predicted');
		$this->db->from('prediksi');
		$this->db->where('actual = predicted AND predicted = "tidak" AND actual != "1:?"');
		return $this->db->count_all_results();
	}
	
	function get_false_p()
	{
		$this->db->select('actual','predicted');
		$this->db->from('prediksi');
		$this->db->where('actual != predicted AND predicted = "ya" AND actual != "1:?"');
		return $this->db->count_all_results();
	}
	
	function get_false_n()
	{
		$this->db->select('actual','predicted');
		$this->db->from('prediksi');
		$this->db->where('actual != predicted AND predicted = "tidak" AND actual != "1:?"');
		return $this->db->count_all_results();
	}

}
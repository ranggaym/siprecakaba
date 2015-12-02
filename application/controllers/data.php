<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('file', 'url'));
	}
	
	
	public function isi_dataset()
	{
		$config['allowed_types'] = 'csv';
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors(), 'notif' => ' ');
			$this->load->view('isidataset', $error);
		}
		else
		{
			$this->load->model('pelamar');
		
			$file_name = $this->upload->data()['file_name'];
			$csv_file = read_file($file_name);
			
			$this->pelamar->insert_from_csv($csv_file);
			
			$notif = 'Upload dataset berhasil';
			$this->load->view('isidataset', array('error' => ' ', 'notif' => $notif));
		}
	}
	
	
	public function index()
	{
		$this->load->model('pelamar');
	
		$output = shell_exec('java weka.classifiers.functions.Logistic -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv" -l model2.model -T testset.arff');
		
		// suppressed
		// $output = shell_exec('java weka.classifiers.functions.Logistic -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv -suppress" -l model2.model -T testset.arff');
		// *nix only
		// |tail -n+6 |head -n -1
		// #note: |tail -n+6  <-- removes the header of file
		// #      |head -n -1 <-- removes the footer of file
		
		
		echo "<pre>$output</pre>";
		echo "<br>";
		
		$csv = $this->csv_to_array('testresult.csv');//array_map('str_getcsv', file('input_test.csv'));
		//unset($csv[sizeof($csv)-1]);
		print_r ($csv);
		echo "<br>";
	}
	
	
	
	
	// fungsi CSV parser untuk mengubah
	// isi CSV ke array
	private function csv_to_array($filename='', $delimiter=',')
	{
		if(!file_exists($filename) || !is_readable($filename))
			return FALSE;

		$header = NULL;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
			{
				if(!$header)
					$header = $row;
				elseif (sizeof($header) = sizeof($row))
					$data[] = array_combine($header, $row);
			}
			fclose($handle);
		}
		return $data;
	}
	
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
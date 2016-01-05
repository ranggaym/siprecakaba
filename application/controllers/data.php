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
		$this->load->helper(array('file', 'url', 'security'));
	}
	
	
	// Pengisian dataset
	public function isi_dataset($latih = null)
	{
		$config['allowed_types'] = 'csv';
		$config['upload_path'] = '.';
		$config['overwrite'] = TRUE;
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload()) // upload gagal
		{
			$error = array('error' => $this->upload->display_errors(), 'notif' => ' ');
			
			$this->load->view('header');
			$this->load->view('isidataset', $error);
			$this->load->view('footer');
		}
		else // upload berhasil
		{
			$this->load->model('pelamar');
		
			// Pengamanan filename
			$file_name = sanitize_filename($this->upload->data()['file_name']);
			$raw_name = sanitize_filename($this->upload->data()['raw_name']);
			
			// Memasukkan CSV ke DB
			$arrayed_csv = $this->csv_to_array($file_name);
			$this->pelamar->insert_from_array($arrayed_csv);
			
			
			// Membuat model dengan melatih dataset dari CSV
			if($latih==='1')
			{
				shell_exec('java weka.core.converters.CSVLoader -N 2-last '.$file_name.' > '.$raw_name.'.arff');
				shell_exec('java weka.classifiers.functions.Logistic -d trainedmodel.model -t '.$raw_name.'.arff');
			}
			
			$f = fopen($raw_name.'.arff', 'r');
			$lineNo = 0;
			$text = "";
			$startLine = 1;
			$endLine = 7;
			while ($line = fgets($f)) {
				$lineNo++;
				if ($lineNo >= $startLine) {
					$text .= $line;
				}
				if ($lineNo == $endLine) {
					break;
				}
			}
			fclose($f);
			
			$fw = fopen('MasterHeader.arff', 'w');
			fwrite($fw, $text);
			fclose($fw);
			
			$notif = 'Upload dan latih dataset berhasil';
			
			$this->load->view('header');
			$this->load->view('isidataset', array('error' => ' ', 'notif' => $notif));
			$this->load->view('footer');
		}
	}
	
	
	public function train_from_db()
	{
		$this->load->model('pelamar');
		
		$raw_name = 'train_from_db';
		$this->pelamar->dump_to_csv($raw_name.'.csv');
		
		shell_exec('java weka.core.converters.CSVLoader -N 2-last '.$raw_name.'.csv > '.$raw_name.'.arff');
		shell_exec('java weka.classifiers.functions.Logistic -d trainedmodel.model -t '.$raw_name.'.arff');
		
		$f = fopen($raw_name.'.arff', 'r');
		$lineNo = 0;
		$text = "";
		$startLine = 1;
		$endLine = 7;
		while ($line = fgets($f)) {
			$lineNo++;
			if ($lineNo >= $startLine) {
				$text .= $line;
			}
			if ($lineNo == $endLine) {
				break;
			}
		}
		fclose($f);
		
		$fw = fopen('MasterHeader.arff', 'w');
		fwrite($fw, $text);
		fclose($fw);
		
		$notif = 'Latih dataset dari DB berhasil';
		
		$this->load->view('header');
		$this->load->view('isidataset', array('error' => ' ', 'notif' => $notif));
		$this->load->view('footer');
	}
	
	public function index()
	{
		$this->load->model('pelamar');
	
		//$output = shell_exec('java weka.classifiers.functions.Logistic -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv" -l model2.model -T testset.arff');
		
		// suppressed
		$output = shell_exec('java weka.classifiers.functions.Logistic -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv -suppress" -l model2.model -T testset.arff');
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
				elseif($header && (count($header)==count($row)))
					$data[] = array_combine($header, $row);
			}
			fclose($handle);
		}
		return $data;
	}
	
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
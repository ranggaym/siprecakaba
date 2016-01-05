<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TesData extends CI_Controller {

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
		$this->load->helper(array('form', 'url', 'security'));
	}
	
	public function uploading()
	{
		$config['allowed_types'] = 'csv';
		$config['upload_path'] = '.';
		$config['overwrite'] = TRUE;
		$this->load->library('upload',$config);
		
		//$this->load->library('upload');
		
		if (!$this->upload->do_upload()) //failed
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('header');
			$this->load->view('tesdata', $error);
			$this->load->view('footer');
		}
		else //success
		{
			$this->load->model('prediksi');
			$this->load->model('data_uji');
			$this->load->library('table');
			
			
			// File sanitizing
			$file_name = sanitize_filename($this->upload->data()['file_name']);
			$raw_name = sanitize_filename($this->upload->data()['raw_name']);
	
			
			// Memasukkan data uji dari CSV ke DB
			$arrayed_csv = $this->csv_to_array($file_name);
			$this->data_uji->insert_from_array($arrayed_csv);
	
			
			// ubah CSV ke ARFF lalu lakukan prediksi
			shell_exec('java weka.core.converters.CSVLoader -N 2-last '.$file_name.' > '.$raw_name.'.arff');
			
			$f = fopen('MasterHeader.arff', 'r');
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
			
			$f2 = fopen($raw_name.'.arff', 'r');
			$lineNo = 0;
			$startLine = 8;
			while ($line = fgets($f2)) {
				$lineNo++;
				if ($lineNo >= $startLine)
					$text .= $line;
				if ($line === FALSE)
					break;
			}
			fclose($f2);
			
			$fw = fopen($raw_name.'.arff', 'w');
			fwrite($fw, $text);
			fclose($fw);
			
			shell_exec('java weka.classifiers.functions.Logistic -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv -suppress" -l trainedmodel.model -T '.$raw_name.'.arff');
			
			
			// Memasukkan hasil prediksi ke DB
			$arrayed_prediction = $this->csv_to_array('testresult.csv');
			$this->prediksi->insert_from_array($arrayed_prediction);
			

			// Membuat tabel
			$tmpl = array ( 'table_open'  => '<table border="2" cellpadding="2" cellspacing="5" class="tabelhasil">' );
			$this->table->set_template($tmpl); 
			$this->table->set_heading('No','IPK','Hasil tes psi','Hasil wawancara','Prediksi kelas', 'Prob.');
			$table = $this->table->generate($this->data_uji->get_predicted_class());
			
			
			$this->load->view('header');
			$this->load->view('hasiltesdata', array('table' => $table));
			$this->load->view('footer');
		}
	}
	
	
	public function test_from_db()
	{
		$this->load->model('prediksi');
		$this->load->model('data_uji');
		$this->load->library('table');
		
		$raw_name = 'test_from_db';
		$this->data_uji->dump_to_csv($raw_name.'.csv');
		
		
		// ubah CSV ke ARFF lalu lakukan prediksi
		shell_exec('java weka.core.converters.CSVLoader -N 2-last '.$raw_name.'.csv > '.$raw_name.'.arff');
		
		$f = fopen('MasterHeader.arff', 'r');
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
		
		$f2 = fopen($raw_name.'.arff', 'r');
		$lineNo = 0;
		$startLine = 8;
		while ($line = fgets($f2)) {
			$lineNo++;
			if ($lineNo >= $startLine)
				$text .= $line;
			if ($line === FALSE)
				break;
		}
		fclose($f2);
		
		$fw = fopen($raw_name.'.arff', 'w');
		fwrite($fw, $text);
		fclose($fw);
		
		shell_exec('java weka.classifiers.functions.Logistic -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv -suppress" -l trainedmodel.model -T '.$raw_name.'.arff');
		
		
		// Memasukkan hasil prediksi ke DB
		$arrayed_prediction = $this->csv_to_array('testresult.csv');
		$this->prediksi->insert_from_array($arrayed_prediction);
		

		// Membuat tabel
		$tmpl = array ( 'table_open'  => '<table border="2" cellpadding="2" cellspacing="5" class="tabelhasil">' );
		$this->table->set_template($tmpl); 
		$this->table->set_heading('No','IPK','Hasil tes psi','Hasil wawancara','Prediksi kelas', 'Prob.');
		$table = $this->table->generate($this->data_uji->get_predicted_class());
		
		
		$this->load->view('header');
		$this->load->view('hasiltesdata', array('table' => $table));
		$this->load->view('footer');
	}
	
	
	
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
	
	
	public function index()
	{
		// load halaman
		$this->load->view('header');
		$this->load->view('tesdata', array('error'=>''));
		$this->load->view('footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
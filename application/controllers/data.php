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
	public function uploading_train_data()
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
			$buatmodel = $this->input->post('buatmodel');
		
			// Pengamanan filename
			$file_name = sanitize_filename($this->upload->data()['file_name']);
			$raw_name = sanitize_filename($this->upload->data()['raw_name']);
			
			// Memasukkan CSV ke DB
			$arrayed_csv = $this->csv_to_array($file_name);
			$this->pelamar->insert_from_array($arrayed_csv);
			
			
			// Membuat model dengan melatih dataset dari CSV
			if($buatmodel==='on')
			{
				shell_exec('java weka.core.converters.CSVLoader -N 2-last '.$file_name.' > '.$raw_name.'.arff');
				//shell_exec('java weka.classifiers.functions.Logistic -d trainedmodel.model -t '.$raw_name.'.arff');
				shell_exec('java weka.classifiers.meta.FilteredClassifier -t '.$raw_name.'.arff -d trainedmodel.model -F "weka.filters.unsupervised.attribute.Remove -R 1" -W weka.classifiers.functions.Logistic');
				
				$notif = 'Upload data latih dan pembuatan model prediksi berhasil';
			}
			else
				$notif = 'Upload data latih berhasil';
			
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
		// shell_exec('java weka.classifiers.functions.Logistic -d trainedmodel.model -t '.$raw_name.'.arff');
				shell_exec('java weka.classifiers.meta.FilteredClassifier -t '.$raw_name.'.arff -d trainedmodel.model -F "weka.filters.unsupervised.attribute.Remove -R 1" -W weka.classifiers.functions.Logistic');
		
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
		
		$notif = 'Latih dataset dan pembuatan model dari DB berhasil';
		
		$this->load->view('header');
		$this->load->view('isidataset', array('error' => ' ', 'notif' => $notif));
		$this->load->view('footer');
	}
	
	
	
	public function uploading_test_data()
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
			$buatprediksi = $this->input->post('buatprediksi');
			
			// File sanitizing
			$file_name = sanitize_filename($this->upload->data()['file_name']);
			
			// Memasukkan data uji dari CSV ke DB
			$arrayed_csv = $this->csv_to_array($file_name);
			$this->data_uji->insert_from_array($arrayed_csv);
			
			// ubah CSV ke ARFF lalu lakukan prediksi
			// jika disuruh
			if($buatprediksi==='on')
			{
				$raw_name = sanitize_filename($this->upload->data()['raw_name']);
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
				
				// shell_exec('java weka.classifiers.functions.Logistic -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv -suppress" -l trainedmodel.model -T '.$raw_name.'.arff');
				shell_exec('java weka.classifiers.meta.FilteredClassifier -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv" -l trainedmodel.model -T '.$raw_name.'.arff');
				$more_info = shell_exec('java weka.classifiers.meta.FilteredClassifier -no-predictions -l trainedmodel.model -T '.$raw_name.'.arff');
				
				
				// Memasukkan hasil prediksi ke DB
				$arrayed_prediction = $this->csv_to_array('testresult.csv');
				$this->prediksi->insert_from_array($arrayed_prediction);
				

				// Membuat tabel
				$tmpl = array ( 'table_open'  => '<table border="2" cellpadding="2" cellspacing="5" class="tabelhasil">' );
				$this->table->set_template($tmpl); 
				$this->table->set_heading('No','IPK','Hasil tes psi','Hasil wawancara','Prediksi kelas', 'Prob.');
				$table = $this->table->generate($this->data_uji->get_predicted_class());
			}
			else
				$table = '<p>Data uji sukses terupload. Tidak ada hasil prediksi karena prediksi data uji tidak dilakukan.</p>'.
						'<p><a href="'.site_url('data/test_from_db').'">Tes Data Uji dari DB</a></p>';
			
			$this->load->view('header');
			$this->load->view('hasiltesdata', array('table' => $table, 'more_info' => $more_info));
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
		
		// shell_exec('java weka.classifiers.functions.Logistic -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv -suppress" -l trainedmodel.model -T '.$raw_name.'.arff');
		shell_exec('java weka.classifiers.meta.FilteredClassifier -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv" -l trainedmodel.model -T '.$raw_name.'.arff');
		$more_info = shell_exec('java weka.classifiers.meta.FilteredClassifier -no-predictions -l trainedmodel.model -T '.$raw_name.'.arff');
		
		
		
		// Memasukkan hasil prediksi ke DB
		$arrayed_prediction = $this->csv_to_array('testresult.csv');
		$this->prediksi->insert_from_array($arrayed_prediction);
		

		// Membuat tabel
		$tmpl = array ( 'table_open'  => '<table border="2" cellpadding="2" cellspacing="5" class="tabelhasil">' );
		$this->table->set_template($tmpl); 
		$this->table->set_heading('No','IPK','Hasil tes psi','Hasil wawancara','Prediksi kelas', 'Prob.');
		$table = $this->table->generate($this->data_uji->get_predicted_class());
		
		
		$this->load->view('header');
		$this->load->view('hasiltesdata', array('table' => $table, 'more_info' => $more_info));
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
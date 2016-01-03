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
		
		if (!$this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('header');
			$this->load->view('tesdata', $error);
			$this->load->view('footer');
		}
		else
		{
			$data = array();//array('upload_data' => $this->upload->data());
			
			$this->load->model('prediksi');
		
			$file_name = sanitize_filename($this->upload->data()['file_name']);
			
			$output = shell_exec('java weka.classifiers.functions.Logistic -classifications "weka.classifiers.evaluation.output.prediction.CSV -p first -file testresult.csv -suppress" -l model2.model -T testset.arff');
			
			$arrayed_csv = $this->csv_to_array($file_name);
			$this->prediksi->insert_from_array($arrayed_csv);
			
			$this->load->view('header');
			$this->load->view('hasiltesdata', $data);
			$this->load->view('footer');
		}
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Depan extends CI_Controller {

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
	public function index()
	{
		$this->load->model('pelamar');
		$this->load->model('data_uji');
	
		$cekdatalatih = $this->pelamar->is_exists() ? '<p>Data latih sudah ada di DB</p>' : '<p>Data latih belum ada di DB. Silakan upload.</p>';
		$cekdatauji = $this->data_uji->is_exists() ? '<p>Data uji sudah ada di DB</p>' : '<p>Data uji belum ada di DB. Silakan upload.</p>';
	
		// load halaman
		$this->load->view('header');
		$this->load->view('depan', array('cekdatalatih'=>$cekdatalatih,'cekdatauji'=>$cekdatauji));
		$this->load->view('footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
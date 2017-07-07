
<?php


require_once("PDFLibrary.php");

require_once("dompdf/autoload.inc.php");

use Dompdf\Dompdf;

class DompdfLib implements PDFLibrary {

	private $paper_size;

	private $orientation;

	private $html;

	private $dompdf;

	private $CI;


	public function __construct(){

		$this->CI = & get_instance();
		$this->paper_size = 'A4';
		$this->orientation = 'portrait';
		$this->dompdf = new Dompdf();
	}


	public function set_paper_size($paper_size){
		$this->paper_size = $paper_size;
	}


	public function set_orientation($orientation){
		$this->orientation = $orientation;
	}


	public function load_html($html){
		$this->html .= $html;
		return true;
	}


	public function convert(){

		$this->dompdf->set_option('isHtml5ParserEnabled', true);

		$this->dompdf->setPaper($this->paper_size, $this->orientation);

		$this->dompdf->loadHtml($this->html);

		$this->dompdf->render();
		return true;
	}


	public function stream(){
		$this->dompdf->stream();
	}


	public function get_output(){
		return $this->dompdf->output();
	}


	public function get_output_file($file_name){
		$file_name = str_replace(" ", "_", $file_name);
		$output = $this->dompdf->output();

		$file_name = $file_name . ".pdf";
		$file_full_name = $this->CI->config->item("pdf_report_path") . $file_name;

		file_put_contents($file_full_name, $output);
		return $file_full_name;
	}


	public function output($file_name){
		$file_full_name = $this->get_output_file($filename);

		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="' . $file_name . '"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
  		@readfile($file_full_name);
	}

}

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

		$options = new \Dompdf\Options();
		$options->setIsHtml5ParserEnabled(true);
		$options->setLogOutputFile(APPPATH . "/logs/dompdf.txt");
		$this->dompdf->setOptions($options);
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
		$file_full_name = $this->get_output_file($file_name);

		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="' . $file_name . '"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
  		@readfile($file_full_name);
	}

	public function get_html_as_pdf_file($html, $file_name){
	    $this->load_html($html);
	    if($this->convert()) {
            $friendly_file_path = $this->CI->config->item('pdf_upload_base') . str_replace(" ", "_", $file_name) . ".pdf";
            $this->get_output_file($file_name);
            return base_url($friendly_file_path);
        }
        else return null;
    }

}


<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

interface PDFLibrary {

	public function set_paper_size($paper_size);

	public function set_orientation($orientation);

	public function load_html($html);

	public function convert();

	public function stream();

	public function get_output();

	public function get_output_file($file_name);
}
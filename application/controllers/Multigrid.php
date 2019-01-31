<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Name : myIgniter base controller.
 *
 * @version 3.9.5
 *
 * @author : Kotaxdev
 */

class Multigrid extends MY_Controller
{

public function index()
	{
		$this->load->library('grocery_CRUD');
		$this->load->library('grocery_CRUD_multi');

		$GCM = new Grocery_crud_multi();

		$GCM->grid_add(1);

		$GCM->grids[1]->set_table('guru_quran');
		$GCM->grids[1]->set_subject('Guru_quran');

		$GCM->grid_add(2);

		$GCM->grids[2]->set_table('perkembangan_siswa');
		$GCM->grids[2]->set_subject('Perkembangan Siswa');

		$output = $GCM->render();

		$data = (array) $output;
		
		
		$this->_example_output_multi($output);

	}
	
	function _example_output_multi($output = null)
	{
		if(is_array($output['output']))
			$output['output'] = implode(' ',$output['output']);
		
		$this->load->view('example',$output);
    } 
    
}
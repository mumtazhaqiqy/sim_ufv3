<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Profile Controller.
 */
class Profile extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

		$this->title = "pengguna";
		$this->load->model('crud_model');
    }

	public function index()
	{
		$this->load->model('pengguna_model','pengguna');
		$user_info = $this->ion_auth->user()->row();
		$additional_data = json_decode($user_info->additional)[0];

		$data['additional'] = $additional_data; 
		$data['user_info'] = $user_info; 


		$condition = array('id_customer' => $additional_data->customer_id);
		$data['profile_pengguna'] = $this->pengguna->single($condition,'view_pengguna');
		
		
		$this->layout->set_wrapper( 'profile', $data, 'page', false);

		$template_data["title"] = "Profil Pengguna";
		$template_data["crumb"] = ["Profil" => ""];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin

		

	}



	
}

/* End of file example.php */
/* Location: ./application/modules/pengguna/controllers/Pengguna.php */
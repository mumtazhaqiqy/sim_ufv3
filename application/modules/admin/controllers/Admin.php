<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Admin Controller.
 */
class Admin extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

		$this->title = "Admin Data";
		// $this->load->model('crud_model');    
    }

    public function create_user()
    {
        $username = 'soepo2';
        $password = '12345678';
        $email = 'beso33sp@gmail.com';
        $additional_data = array(
                    'first_name' => 'Ben',
                    'last_name' => 'Edmunds',
                    );
        $group = array('1'); // Sets user to admin.

        $this->ion_auth->register($username, $password, $email, $additional_data, $group);

        $update_data = array('additional' => '['.json_encode($additional_data).']');

        $this->db->where('email',$email);
        $this->db->update('users',$update_data);

        echo 'success';

    }


}

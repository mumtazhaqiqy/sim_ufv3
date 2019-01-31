<?php defined('BASEPATH') or exit('No direct script access allowed');


class Test extends CI_Controller
{

    public function index()
    {
        $this->load->view('test');

    }

    public function respons($id)
    {
       echo 'success'.$id;

    }


}
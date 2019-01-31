<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Order_sertifikat Controller.
 */
class Order_sertifikat extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('crud_model');
    }


    public function crud($sertifikasi_code) 
	{   
        $sertifikasi = db_get_row('sertifikasi', 'code = '.$sertifikasi_code);
        $sertifikasi_id = $sertifikasi->id;
        
		$crud = new grocery_CRUD();
		// $crud->set_model('My_Custom_model');
        
        $state = $crud->getState();

        if($state == 'add' || $state == 'edit'|| $state == 'insert' || $state == 'insert_validation'){
        
        } else {
            redirect('sertifikasi/peserta/daftar/'.$sertifikasi_code);
        }
        

		$crud->set_table("order_sertifikat_guru");
		$crud->set_subject("Order Sertifikat Guru");

		// Show in
		$crud->add_fields(["sertifikasi_id","nama_pemohon", "jumlah_sertifikat", "tanggal_order","peserta_lulus", "catatan"]);
		// $crud->edit_fields(["sertifikasi_id", "tanggal_order", "catatan", "peserta_lulus"]);
		$crud->columns(["tanggal_order","status", "peserta_lulus"]);

		// Fields type
		$crud->field_type("sertifikasi_id", "hidden", $sertifikasi_id);
		$crud->field_type("tanggal_order", "date");
		$crud->field_type("catatan", "string");
		$crud->field_type("tanggal_proses", "date");
		$crud->field_type("tangga_kirim", "date");
		$crud->field_type("status", "string");
		$crud->field_type("created_at", "datetime");
		$crud->field_type("user_id", "integer");

		// Relation n-n
		$crud->set_relation_n_n('peserta_lulus', 'sertifikat_peserta_lulus', 'biodata_guru', 'order_sertifikat_id', 'peserta_id', 'nama_lengkap','priority','sertifikasi_id = '.$sertifikasi_id.' and status = "peserta"');


		// Callback
		$crud->callback_before_insert(array($this,'_callback_before_insert'));

		
		// $crud->where('sertifikasi_id = 12');
		// Display As

		// Unset action
		// $crud->unset_delete();

        $data = (array) $crud->render();
        $data['sertifikasi'] = $sertifikasi;

		$this->layout->set_wrapper( 'order_sertifikat', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Order Sertifikat Guru";
		$template_data["crumb"] = ["table" => ""];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}

	public function _callback_before_insert($post_array)
	{
		$data = array('status' => 'terlaksana');
		$this->db->where('id',$post_array['sertifikasi_id']);
		$this->db->update('sertifikasi',$data);
		return $post_array;
	}

}
<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Order_sertifikat Controller.
 */
class Order_sertifikat extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

	public function crud()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("order_sertifikat_guru");
		$crud->set_subject("Order Sertifikat");

		// Show in
		$crud->add_fields([""]);
		$crud->edit_fields(["nama_pemohon", "jumlah_sertifikat", "catatan", "tanggal_proses", "tangga_kirim", "status", "tanggal_order", "created_at"]);
		$crud->columns(["actions","tanggal_order","nama_pemohon", "jumlah_sertifikat", "status", ]);

		// Fields type
		$crud->field_type("id_order_sertifikat", "integer");
		$crud->field_type("sertifikasi_id", "string");
		$crud->field_type("nama_pemohon", "string");
		$crud->field_type("jumlah_sertifikat", "integer");
		$crud->field_type("catatan", "string");
		$crud->field_type("tanggal_proses", "date");
		$crud->field_type("tangga_kirim", "date");
		$crud->field_type("status", "string");
		$crud->field_type("tanggal_order", "date");
		$crud->field_type("created_at", "datetime");
		$crud->field_type("user_id", "integer");

		$crud->callback_column('actions',array($this, '_callback_actions_column'));

		// Relation n-n

		// Display As

		// Unset action
		$crud->unset_action();
		// $crud->unset_add();

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Order Sertifikat";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}

	public function _callback_actions_column($value, $row)
	{	
		$html = '';

		return $html;

	}

	
}
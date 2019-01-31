<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Biodata_guru Controller.
 */
class Biodata_guru extends MY_Controller
{
	public function data()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("biodata_guru");
		$crud->set_subject("Biodata Guru");

		// Show in
		$crud->add_fields(["no_sertifikat", "nama_lengkap", "tempat_lahir", "tanggal_lahir", "alamat_lengkap", "provinsi_id", "kabupaten_id", "kecamatan", "no_hp", "email", "pendidikan_terakhir", "hafal_quran", "jumlah_hafalan", "sudah_mengajar", "di_lembaga", "data_lembaga", "pengalaman_mengajar", "pengalaman_kursus", "photo"]);
		$crud->edit_fields(["no_sertifikat", "nama_lengkap", "tempat_lahir", "tanggal_lahir", "alamat_lengkap", "provinsi_id", "kabupaten_id", "kecamatan", "no_hp", "email", "pendidikan_terakhir", "hafal_quran", "jumlah_hafalan", "sudah_mengajar", "di_lembaga", "data_lembaga", "pengalaman_mengajar", "pengalaman_kursus", "status", "photo"]);
		$crud->columns(["no_sertifikat", "nama_lengkap", "tempat_lahir", "tanggal_lahir", "provinsi_id", "kabupaten_id", "no_hp"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("sertifikasi_id", "integer");
		$crud->field_type("no_sertifikat", "integer");
		$crud->field_type("nama_lengkap", "string");
		$crud->field_type("tempat_lahir", "string");
		$crud->field_type("tanggal_lahir", "date");
		$crud->field_type("alamat_lengkap", "string");
		$crud->field_type("provinsi_id", "integer");
		$crud->field_type("kabupaten_id", "integer");
		$crud->field_type("kecamatan", "string");
		$crud->field_type("no_hp", "string");
		$crud->field_type("email", "string");
		$crud->field_type("pendidikan_terakhir", "integer");
		$crud->field_type("hafal_quran", "string");
		$crud->field_type("jumlah_hafalan", "integer");
		$crud->field_type("sudah_mengajar", "string");
		$crud->unset_texteditor("di_lembaga", 'full_text');
		$crud->field_type("di_lembaga", "text");
		$crud->field_type("data_lembaga", "string");
		$crud->unset_texteditor("pengalaman_mengajar", 'full_text');
		$crud->field_type("pengalaman_mengajar", "text");
		$crud->unset_texteditor("pengalaman_kursus", 'full_text');
		$crud->field_type("pengalaman_kursus", "text");
		$crud->field_type("status", "string");
		$crud->unset_texteditor("photo", 'full_text');
		$crud->field_type("photo", "text");
		$crud->field_type("agreement", "string");
		$crud->field_type("created_at", "datetime");

        // Add Actions
        $crud->add_action('Print', '', '', 'fa fa-print',array($this,'_callback_action_print'));

		// Relation n-n
		$crud->set_relation('provinsi_id','wilayah_provinsi','provinsi');
        $crud->set_relation('kabupaten_id', 'wilayah_kabupaten', 'kabupaten');
		// Display As

        // Unset action
        $crud->unset_add();
        $crud->unset_edit();

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Biodata Guru";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
    }
    
    public function _callback_action_print($primary_key , $row)
    {
        return site_url('sertifikasi/peserta/pdf_sertifikat_by_no/').$row->no_sertifikat;
    }
    
}
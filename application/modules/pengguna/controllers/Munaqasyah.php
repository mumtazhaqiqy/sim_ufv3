<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Munaqasyah Controller.
 */
class Munaqasyah extends MY_Controller
{

    public function data()
	{
        if($this->ion_auth->in_group('pengguna_ummi'))
		{
			$user_info = $this->ion_auth->user()->row();
			$user_data = json_decode($user_info->additional)[0];
            $customer_id = $user_data->customer_id;
        }

        $crud = new grocery_CRUD();

        $state = $crud->getState();
		
		$crud->set_table("tm_munaqasyah");
		$crud->set_subject("Munaqasyah");

		// Show in
		$crud->fields(["jenis_munaqasyah_id", "customer_id", "mulai", "usai", "tempat_pelaksanaan","penanggung_jawab","no_contact","pernyataan","status"]);
		$crud->columns(["actions", "tanggal_order", "customer_id", "penanggung_jawab", "status",]);

		// Fields type
		$crud->field_type("customer_id", "hidden", $customer_id);
		$crud->set_relation("jenis_munaqasyah_id", "tm_jenis_munaqasyah", "jenis_munaqasyah");
		$crud->field_type("tanggal_pelaksanaan", "date");
        $crud->field_type("pernyataan", "true_false", array('Tidak','Ya'));
        
        // callback
        $crud->callback_column('actions', array($this, '_callback_actions_column'));
        $crud->callback_column('tanggal_order', array($this,'_callback_tanggal'));
        
        // Relation n-n
        if($state == 'add' || $state == 'edit')
        {
            $crud->field_type("status", "hidden","permohonan");
        } else {
            $crud->set_relation("customer_id", "pengguna", "{nama}");
            
        }
        

        // Where Clause
        $crud->where('customer_id',$customer_id);

		// Display As
		$crud->display_as( array(
            'customer_id' =>'Nama Lembaga',
            'tanggal_order' => 'Order Date',
            'jenis_munaqasyah_id' => 'Jenis Munaqasyah',
            'pernyataan' => 'Saya bertanggung jawab penuh atas kebenaran data yang saya kirimkan'
        ));

        // Unset action
        $crud->unset_action();
        $crud->unset_print();
        $crud->unset_export();

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Munaqasyah Santri";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
    }  

    public function peserta($code)
    {
        $munaqasyah = db_get_row('tm_munaqasyah',array('code' => $code));
        // print_r($munaqasyah);

		$crud = new grocery_CRUD();
		
		$crud->set_table("tm_peserta_munaqasyah");
		$crud->set_subject("Peserta Munaqasyah");

		// // Show in
		$crud->fields(["munaqasyah_id","nama_lengkap", "tempat_lahir", "tanggal_lahir", "alamat", "kelas", "foto"]);
        $crud->columns(["nama_lengkap", "tanggal_lahir", "kelas", "foto"]);
        // Show alamat in callback nama lengkap

		// Fields type
		$crud->field_type("munaqasyah_id", "hidden", $munaqasyah->id_munaqasyah);
		$crud->field_type("nama_lengkap", "string");
		$crud->field_type("tempat_lahir", "string");
		$crud->field_type("tanggal_lahir", "date");
		$crud->field_type("alamat", "string");
		$crud->field_type("kelas", "string");
		$crud->set_field_upload('foto', 'assets/uploads/image');
        
        $crud->callback_column('foto',array($this,'_callback_photo_column'));
        $crud->callback_after_upload(array($this, '_callback_photo_upload'));
        $crud->callback_column('nama_lengkap', array($this, '_callback_nama_lengkap_column'));
        $crud->callback_column('tanggal_lahir', array($this, '_callback_tanggal_lahir_column'));
        $crud->callback_before_insert(array($this, '_callback_before_insert'));

        // Where clause
        $crud->where(array('munaqasyah_id'=>$munaqasyah->id_munaqasyah));

		// Relation n-n

        // Display As
        $crud->display_as(array(
            // 'tempat_lahir' = 'Tempat, Tanggal Lahir'
        ));

        // Unset action
        $crud->unset_action();
        $crud->unset_print();

        $data = (array) $crud->render();
        $lembaga = db_get_row('view_pengguna',array('id_customer' => $munaqasyah->customer_id));
        $data['jenis_sertifikasi'] = db_get_row('tm_jenis_munaqasyah',array('id_jenis_munaqasyah' => $munaqasyah->jenis_munaqasyah_id))->display_name;
        $data['nama_lembaga'] = $lembaga->nama.' | '.$lembaga->provinsi.' | '.$lembaga->kabupaten;
        $data['alamat_lembaga'] = $lembaga->alamat;
        $data['nama_ks'] = $lembaga->kepala_lembaga;
        $data['tanggal_pelaksanaan'] = DatetoIndo(date('Y-m-d',strtotime($munaqasyah->mulai))).' - '.DatetoIndo(date('Y-m-d',strtotime($munaqasyah->usai)));

		$this->layout->set_wrapper( 'list_peserta_munaqasyah', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Peserta Munaqasyah";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
    }


    function _callback_tanggal($value, $row)
    {
        return DatetoIndo($value);
    }

    function _callback_actions_column($value, $row)
    {
        $attr = array(
            'class'     => 'btn btn-primary btn-xs btn-flat',
        );
        if($row->status == 'permohonan' || $row->code == '' ){
            return 'menunggu';
        } else {
            return anchor('pengguna/munaqasyah/peserta/'.$row->code,'Daftar Peserta', $attr);
        }
    }

    public function _callback_photo_upload($uploader_response, $field_info, $files_to_upload)
    {
        $this->load->library('image_moo');
        $file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name;
        $this->image_moo->load($file_uploaded)->resize_crop(400, 600)->save($file_uploaded, true);
        return true;
    }

    public function _callback_photo_column($value, $row)
    {
        if($row->foto == ''){
            $val = '<i class="fa fa-close" style="color:red;"></i>';
        } else {
            $val = '<i class="fa fa-check" style="color:green;"></i>';
        }
        return $val;
    }

    function _callback_nama_lengkap_column($value, $row)
    {
        $munaqasyah = db_get_row('tm_munaqasyah',array('id_munaqasyah' => $row->munaqasyah_id));
        $html ='<div><a href="'.$munaqasyah->code.'/edit/'.$row->id_peserta_munaqasyah.'">'.$value.'</a></div>
        <div>'.$row->alamat.'</div>';

        return $html;
        
    }

    function _callback_tanggal_lahir_column($value, $row)
    {
        $html = $row->tempat_lahir.', '.DatetoIndo($value);

        return $html;
        
    }

    function _callback_before_insert($post_array)
    {
        $post_array['nama_lengkap'] = strtoupper($post_array['nama_lengkap']);

        return $post_array;
    }



}
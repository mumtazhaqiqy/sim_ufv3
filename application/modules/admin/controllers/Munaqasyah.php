<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Munaqasyah Controller.
 */
class Munaqasyah extends MY_Controller
{

    public function index()
    {


    }

	public function data()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("tm_munaqasyah");
		$crud->set_subject("Munaqasyah");

		// Show in
		$crud->add_fields(["jenis_munaqasyah_id", "customer_id", "tanggal_pelaksanaan", "tempat_pelaksanaan", "trainer", "status"]);
		$crud->edit_fields(["jenis_munaqasyah_id", "customer_id", "tanggal_pelaksanaan", "tempat_pelaksanaan", "trainer"]);
		$crud->columns(["actions", "customer_id", "tanggal_pelaksanaan", "tempat_pelaksanaan", "trainer", "code", "status",]);

		// Fields type
		$crud->field_type("id_munaqasyah", "integer");
		$crud->set_relation("jenis_munaqasyah_id", "tm_jenis_munaqasyah", "jenis_munaqasyah");
		$crud->set_relation("customer_id", "pengguna", "{nama}");
		$crud->field_type("tanggal_pelaksanaan", "date");
		$crud->field_type("tempat_pelaksanaan", "string");
		$crud->field_type("code", "string");
		$crud->unset_texteditor("trainer", 'full_text');
		$crud->field_type("trainer", "text");
		$crud->field_type("status", "string");
        $crud->field_type("created_at", "datetime");
        
        // callback
        $crud->callback_column('actions', array($this, '_callback_actions_column'));
        $crud->callback_column('customer_id', array($this, '_callback_customer_id_column'));

		// Relation n-n

		// Display As
		$crud->display_as( array(
            "customer_id" => "Lembaga",
            'tanggal_pelaksanaan' => 'Tanggal'
        ));

        // Unset action
        $crud->unset_action();

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

		$crud = new grocery_CRUD();
		
		$crud->set_table("tm_peserta_munaqasyah");
		$crud->set_subject("Peserta Munaqasyah");

		// Show in
		$crud->add_fields(["nama_lengkap", "tempat_lahir", "tanggal_lahir", "alamat", "kelas", "foto"]);
		$crud->edit_fields(["nama_lengkap", "tempat_lahir", "tanggal_lahir", "alamat", "kelas", "foto"]);
		$crud->columns(["nama_lengkap", "tempat_lahir", "tanggal_lahir", "alamat", "kelas", "foto"]);

		// Fields type
		$crud->field_type("id_peserta_munaqasyah", "integer");
		$crud->field_type("munaqasyah_id", "hidden", $munaqasyah->id_munaqasyah);
		$crud->field_type("nama_lengkap", "string");
		$crud->field_type("tempat_lahir", "string");
		$crud->field_type("tanggal_lahir", "date");
		$crud->field_type("alamat", "string");
		$crud->field_type("kelas", "string");
		$crud->set_field_upload('foto', 'assets/uploads/image');
        $crud->field_type("created_at", "integer");
        
        $crud->callback_column('foto',array($this,'_callback_photo_column'));
        $crud->callback_after_upload(array($this, '_callback_photo_upload'));

		// Relation n-n

		// Display As

		// Unset action

        $data = (array) $crud->render();
        $lembaga = db_get_row('view_pengguna',array('id_customer' => $munaqasyah->customer_id));
        $data['jenis_sertifikasi'] = db_get_row('tm_jenis_munaqasyah',array('id_jenis_munaqasyah' => $munaqasyah->jenis_munaqasyah_id))->display_name;
        $data['nama_lembaga'] = $lembaga->nama.' | '.$lembaga->provinsi.' | '.$lembaga->kabupaten;
        $data['alamat_lembaga'] = $lembaga->alamat;
        $data['nama_ks'] = $lembaga->kepala_lembaga;
        $data['tanggal_pelaksanaan'] = DatetoIndo(date('Y-m-d',strtotime($munaqasyah->tanggal_pelaksanaan)));

		$this->layout->set_wrapper( 'list_peserta_munaqasyah', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Peserta Munaqasyah";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
    
    public function rekap_nilai($code)
    {
        $munaqasyah = db_get_row('tm_munaqasyah',array('code' => $code));

		$crud = new grocery_CRUD();
		
		$crud->set_table("tm_peserta_munaqasyah");
		$crud->set_subject("Peserta Munaqasyah");

		// Show in
		$crud->add_fields([""]);
		$crud->edit_fields([""]);
		$crud->columns(["nama_lengkap", "kelas", "foto","daftar_nilai"]);

		// Fields type
        $crud->callback_column('foto',array($this,'_callback_photo_column'));
        $crud->callback_column('nama_lengkap',array($this,'_callback_nama_lengkap_column'));
        $crud->callback_column('daftar_nilai',array($this,'_callback_daftar_nilai_column'));
        $crud->callback_after_upload(array($this, '_callback_photo_upload'));

		// Relation n-n

		// Display As

        // Unset action
        $crud->unset_action();
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_read();
        $crud->unset_print();
        $crud->unset_export();


        $data = (array) $crud->render();
        $lembaga = db_get_row('view_pengguna',array('id_customer' => $munaqasyah->customer_id));
        $data['jenis_sertifikasi'] = db_get_row('tm_jenis_munaqasyah',array('id_jenis_munaqasyah' => $munaqasyah->jenis_munaqasyah_id))->display_name;
        $data['nama_lembaga'] = $lembaga->nama.' | '.$lembaga->provinsi.' | '.$lembaga->kabupaten;
        $data['alamat_lembaga'] = $lembaga->alamat;
        $data['nama_ks'] = $lembaga->kepala_lembaga;
        $data['tanggal_pelaksanaan'] = DatetoIndo(date('Y-m-d',strtotime($munaqasyah->tanggal_pelaksanaan)));

		$this->layout->set_wrapper( 'list_peserta_munaqasyah', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Peserta Munaqasyah";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function penilaian_peserta($id_peserta){
        $row = db_get_row('tm_peserta_munaqasyah', array('id_peserta_munaqasyah' => $id_peserta));
        $munaqasyah = db_get_row('tm_munaqasyah',array('id_munaqasyah' => $row->munaqasyah_id));
        $kriteria = db_get_all_data('tm_kriteria_nilai','jenis_munaqasyah_id = '.$munaqasyah->jenis_munaqasyah_id);
        
        $data['kriteria'] = $kriteria;
        $data['nama_peserta'] = $row->nama_lengkap;
        $data['peserta_id'] = $id_peserta;
        $data['alamat_peserta'] = $row->alamat;
        $data['nama_lembaga'] = db_get_row('pengguna',array('id_customer' => $munaqasyah->customer_id))->nama;
        $data['kelas'] = $row->kelas;
        $data['munaqasyah'] = $munaqasyah;
        $data['code'] = $munaqasyah->code;

        $this->layout->set_wrapper( 'nilai_peserta', $data,'page', false);

        $template_data["title"] = "Penilaian Peserta";
        $template_data["crumb"] = [];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
        
    }

    public function input_nilai($id_peserta,$kriteria_id,$method = null)
    {
        // if method is null redirect back

        $this->load->library('form_validation');
        $peserta = db_get_row('tm_peserta_munaqasyah',array('id_peserta_munaqasyah' => $id_peserta));
        $munaqasyah = db_get_row('tm_munaqasyah',array('id_munaqasyah' => $peserta->munaqasyah_id));
        $kriteria = db_get_row('tm_kriteria_nilai', 'id_kriteria_nilai = '.$kriteria_id);

        $this->form_validation->set_rules('id_peserta','id_peserta','required');

        if($this->form_validation->run())
        {
            foreach($this->input->post('aspek_nilai_id') as $index => $aspek ){
                $unique = $id_peserta.'-'.$aspek;
                $data = array(
                        'peserta_munaqasyah_id' => $id_peserta,
                        'aspek_nilai_id' => $aspek,
                        'nilai' => $this->input->post('nilai')[$index],
                        'unique' => $unique
                    );
                print_r($data);
                if($method == 'add')
                {
                    $this->db->insert('tm_nilai_munaqasyah', $data);
                } else if ($method == 'edit')
                {
                    $this->db->where('unique',$unique);
                    $this->db->update('tm_nilai_munaqasyah', $data);
                }
                
                $kriteria_nilai[] = $this->input->post('nilai')[$index];
            }
            
            $sum_nilai = array_sum($kriteria_nilai);
            
            $data_kriteria = array (
                    'peserta_munaqasyah_id' => $id_peserta,
                    'kriteria_nilai_id' => $kriteria_id,
                    'nilai' => $sum_nilai
                );
            $this->db->delete('tm_rekap_nilai',array('peserta_munaqasyah_id' => $id_peserta, 'kriteria_nilai_id' => $kriteria_id));
            $this->db->insert('tm_rekap_nilai', $data_kriteria);
            
            // print_r($sum_nilai);
            
            redirect('admin/munaqasyah/penilaian_peserta/'.$id_peserta);

        } else {
            $data['peserta'] = $peserta;
            $data['kriteria'] = $kriteria;
            $data['method'] = $method;

            $data['nama_peserta'] = $peserta->nama_lengkap;
            $data['alamat_peserta'] = $peserta->alamat;
            $data['nama_lembaga'] = db_get_row('pengguna',array('id_customer' => $munaqasyah->customer_id))->nama;
            $data['kelas'] = $peserta->kelas;


            $this->layout->set_wrapper( 'input_nilai', $data,'page', false);

            $template_data["title"] = "Penilaian Peserta";
            $template_data["crumb"] = [];
            $this->layout->auth();
            $this->layout->render('admin', $template_data); // front - auth - admin
            // $this->load->view('input_nilai', $data);
        }
    }

    public function pdf_sertifikat($code)
    {
        $munaqasyah = db_get_row('tm_munaqasyah', array('code' => $code));
        $peserta = db_get_all_data('tm_peserta_munaqasyah', array('munaqasyah_id' => $munaqasyah->id_munaqasyah));

        $$kriteria = db_get_all_data('tm_kriteria_nilai', array('jenis_munaqasyah_id' => $munaqasyah->jenis_munaqasyah_id));

        $filename = 'munaqasyah-'.$code.'.pdf';

        $this->load->library('pdf');
        $this->load->library('hijridate');

        
        $params = array(
            'mode' => 'utf-8',
            'orientation' => 'L',
            'margin_left' => '35',
            'margin_right' => '35',
            'margin_top' => '35',
            'margin_bottom' => '5',
            'default_font' => 'arial'
        );
        
        $pdf = $this->pdf->load($params);

        $data = array(
            'peserta' => $peserta,
            'nama_lembaga' => '',
            'tanggal_munaqasyah' => '',
            'jenis_munaqasyah' => '',
            'tanggal_hijri' =>'',
            'nama_lemabaga' => '',
            'kepala_lembaga' => '',
            'tanggal_masehi' => '',
        );
        
        // $data['peserta'] = $peserta;

        // print_r($peserta);
        $html = $this->load->view('munaqasyah_pdf', $data, true);
        // $html = $this->load->view('munaqasyah_pdf', $data);

        $pdf->WriteHTML($html,2);
        // $pdf->Output($filename,'F');
        $pdf->Output();
    }

    function _callback_daftar_nilai_column($value, $row)
    {
        $html = 'halo';

        $munaqasyah = db_get_row('tm_munaqasyah',array('id_munaqasyah' => $row->munaqasyah_id));

        $kriteria = db_get_all_data('tm_kriteria_nilai', array('jenis_munaqasyah_id' => $munaqasyah->jenis_munaqasyah_id));

        foreach($kriteria as $k)
        {
            $v = db_get_row('tm_rekap_nilai',array('peserta_munaqasyah_id' => $row->id_peserta_munaqasyah, 'kriteria_nilai_id' => $k->id_kriteria_nilai))->nilai;
            $h = '<dt>'.$k->display_name.'</dt><dd>'.$v.'</dd>';
            $a .= $h;

            if($v < 7.5){
                $s[] = 1;
            } else {
                $s[] = 0;
            }
        }
        $sa = array_sum($s);

        $html = '<dl class="dl-horizontal">'.$a.'</dl><div>'.$sa.'</div>';

        return $html;

    }


    function _callback_actions_column($value, $row)
    {
        $html = '<a href="peserta/'.$row->code.'">Daftar Peserta</a>';
        return $html;
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
        $html = '<a href="../penilaian_peserta/'.$row->id_peserta_munaqasyah.'">'.$value.'</a>';
        
        return $html;
    }


    public function test()
    {
        $munaqasyah_id = 1;

        $data['peserta'] = db_get_all_data('tm_peserta_munaqasyah');
        $data['kriteria'] = db_get_all_data('tm_kriteria_nilai',array('jenis_munaqasyah_id'=>$munaqasyah_id));

        $this->load->view('test',$data);
    }


}
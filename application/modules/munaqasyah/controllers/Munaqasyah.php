<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Munaqasyah Controller.
 */
class Munaqasyah extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('string');
    }

    public function index()
    {
    }

    public function data()
    {
        if ($this->ion_auth->in_group('ummi_daerah')) {
            $user_info = $this->ion_auth->user()->row();
            $user_data = json_decode($user_info->additional)[0];
            $ummi_daerah_id = $user_data->ummi_daerah_id;
        }

        $crud = new grocery_CRUD();

        $state = $crud->getState();

        if ($state == 'list' || $state == 'ajax_list' || $state == 'ajax_list_info'|| $state == 'export' || $state == 'success') {
            $crud->set_table("view_munaqasyah");
            if ($this->ion_auth->in_group('ummi_daerah')) {
                $crud->where('ummi_daerah_id', $ummi_daerah_id);
            }
        } else {
            $crud->set_table("tm_munaqasyah");
        }

        $crud->set_primary_key('id_munaqasyah');
        $crud->set_primary_key('id_customer', 'view_pengguna');
        $crud->set_subject("Munaqasyah");

        // Show in
        $crud->add_fields(["jenis_munaqasyah_id", "customer_id", "mulai","usai","trainer","tempat_pelaksanaan","penanggung_jawab","no_contact","status"]);
        $crud->edit_fields(["jenis_munaqasyah_id", "customer_id", "mulai","usai","trainer","tempat_pelaksanaan","penanggung_jawab","no_contact"]);
        $crud->columns(["actions","tanggal_order", "nama_lembaga","trainer", "status", "penilaian"]);

        // Fields type
        $crud->field_type("pernyataan", "true_false", array('Tidak','Ya'));
        $crud->field_type("penanggung_jawab", "readonly");
        $crud->field_type("no_contact", "readonly");
        $crud->field_type("status", "hidden", "permohonan");

        if ($this->ion_auth->in_group('ummi_daerah')) {
            $tableList = db_get_all_data('trainer', array('ummi_daerah_id' => $ummi_daerah_id));
        } else {
            $tableList = db_get_all_data('trainer');
        }

        foreach ($tableList as $listMulty) {
            $list[$listMulty->id_trainer] = $listMulty->nama_trainer;
        }
        $crud->field_type("trainer", "multiselect", $list);


        // callback
        $crud->callback_column('actions', array($this, '_callback_actions_column'));
        $crud->callback_column('tanggal_order', array($this,'_callback_tanggal'));
        $crud->callback_column('trainer', array($this , '_callback_trainer_column'));
        $crud->callback_column('nama_lembaga', array($this , '_callback_nama_lembaga_column'));
        $crud->callback_column('penilaian', array($this , '_callback_penilaian_column'));
        $crud->callback_column('status', array($this , '_callback_status_column'));

        // Relation n-n
        $crud->set_relation("jenis_munaqasyah_id", "tm_jenis_munaqasyah", "display_name");

        if ($this->ion_auth->in_group('ummi_daerah')) {
            $crud->set_relation("customer_id", "view_pengguna", "{nama}", array('id_ummi_daerah' =>$ummi_daerah_id));
        } else {
            $crud->set_relation("customer_id", "pengguna", "{nama}");
        }

        // Where Clause
        if ($this->uri->segment(3) == 'blmdisetujui') {
            $crud->where('view_munaqasyah.status = ', 'permohonan');
        }

        // Display As
        $crud->display_as(array(
            'nama_lembaga' =>'Pemohon Munaqasyah',
            'tanggal_order' => 'Order Date',
            'jenis_munaqasyah_id' => 'Jenis Munaqasyah',
            'pernyataan' => 'Saya bertanggung jawab penuh atas kebenaran data yang saya kirimkan'
        ));

        // Unset action
        $crud->unset_action();
        $crud->unset_read();
        $crud->unset_print();
        $crud->unset_export();

        $data = (array) $crud->render();

        $this->layout->set_wrapper('grocery', $data, 'page', false);

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Munaqasyah Santri";
        $template_data["crumb"] = [];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function peserta($code)
    {
        $munaqasyah = db_get_row('tm_munaqasyah', array('code' => $code));
        // print_r($munaqasyah);

        $crud = new grocery_CRUD();

        $crud->set_table("tm_peserta_munaqasyah");
        $crud->set_subject("Peserta");

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

        $crud->callback_column('foto', array($this,'_callback_photo_column'));
        $crud->callback_after_upload(array($this, '_callback_photo_upload'));
        $crud->callback_column('nama_lengkap', array($this, '_callback_nama_lengkap_column'));
        $crud->callback_column('tanggal_lahir', array($this, '_callback_tanggal_lahir_column'));
        $crud->callback_before_insert(array($this, '_callback_before_insert'));

        // Where clause
        $crud->where(array('munaqasyah_id'=>$munaqasyah->id_munaqasyah));

        // Relation n-n

        // Display As
        $crud->display_as(array(
            'tanggal_lahir' => 'Tempat, Tanggal Lahir'
        ));

        // Unset action
        $crud->unset_action();
        $crud->unset_print();

        $data = (array) $crud->render();
        $lembaga = db_get_row('view_pengguna', array('id_customer' => $munaqasyah->customer_id));
        $data['jenis_sertifikasi'] = db_get_row('tm_jenis_munaqasyah', array('id_jenis_munaqasyah' => $munaqasyah->jenis_munaqasyah_id))->display_name;
        $data['nama_lembaga'] = $lembaga->nama.' | '.$lembaga->provinsi.' | '.$lembaga->kabupaten;
        $data['alamat_lembaga'] = $lembaga->alamat;
        $data['nama_ks'] = $lembaga->kepala_lembaga;
        $data['munaqasyah'] = $munaqasyah;
        $data['tanggal_pelaksanaan'] = DatetoIndo(date('Y-m-d', strtotime($munaqasyah->mulai))).' - '.DatetoIndo(date('Y-m-d', strtotime($munaqasyah->usai)));

        $this->layout->set_wrapper('list_peserta_munaqasyah', $data, 'page', false);

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Peserta Munaqasyah";
        $template_data["crumb"] = [];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function penilaian($code)
    {
        $munaqasyah = db_get_row('tm_munaqasyah', array('code' => $code));
        // print_r($munaqasyah);

        $crud = new grocery_CRUD();

        $crud->set_table("tm_peserta_munaqasyah");
        $crud->set_subject("Peserta");

        // // Show in

        $crud->columns(["nama_lengkap", "kelas", "foto","status","rekap_nilai"]);
        // Show alamat in callback nama lengkap

        // Fields type
        $crud->field_type("munaqasyah_id", "hidden", $munaqasyah->id_munaqasyah);
        $crud->field_type("nama_lengkap", "string");
        $crud->field_type("tempat_lahir", "string");
        $crud->field_type("tanggal_lahir", "date");
        $crud->field_type("alamat", "string");
        $crud->field_type("kelas", "string");
        $crud->set_field_upload('foto', 'assets/uploads/image');

        $crud->callback_column('foto', array($this,'_callback_photo_column'));
        $crud->callback_after_upload(array($this, '_callback_photo_upload'));
        $crud->callback_column('nama_lengkap', array($this, '_callback_nama_lengkap_nilai_column'));
        $crud->callback_column('rekap_nilai', array($this, '_callback_rekap_nilai_column'));
        $crud->callback_column('status', array($this, '_callback_status_lulus_column'));
        $crud->callback_before_insert(array($this, '_callback_before_insert'));

        // Where clause
        $crud->where(array('munaqasyah_id'=>$munaqasyah->id_munaqasyah));

        // Relation n-n

        // Display As
        $crud->display_as(array(

        ));

        // Unset action
        $crud->unset_action();
        $crud->unset_print();
        $crud->unset_delete();
        $crud->unset_add();

        $data = (array) $crud->render();
        $lembaga = db_get_row('view_pengguna', array('id_customer' => $munaqasyah->customer_id));
        $data['jenis_sertifikasi'] = db_get_row('tm_jenis_munaqasyah', array('id_jenis_munaqasyah' => $munaqasyah->jenis_munaqasyah_id))->display_name;
        $data['nama_lembaga'] = $lembaga->nama.' | '.$lembaga->provinsi.' | '.$lembaga->kabupaten;
        $data['alamat_lembaga'] = $lembaga->alamat;
        $data['nama_ks'] = $lembaga->kepala_lembaga;
        $data['munaqasyah'] = $munaqasyah;
        $data['tanggal_pelaksanaan'] = DatetoIndo(date('Y-m-d', strtotime($munaqasyah->mulai))).' - '.DatetoIndo(date('Y-m-d', strtotime($munaqasyah->usai)));

        $this->layout->set_wrapper('list_peserta_munaqasyah', $data, 'page', false);

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Peserta Munaqasyah";
        $template_data["crumb"] = [];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function menu_penilaian($id_peserta)
    {
        $row = db_get_row('tm_peserta_munaqasyah', array('id_peserta_munaqasyah' => $id_peserta));
        $munaqasyah = db_get_row('tm_munaqasyah', array('id_munaqasyah' => $row->munaqasyah_id));
        $kriteria = db_get_all_data('tm_kriteria_nilai', 'jenis_munaqasyah_id = '.$munaqasyah->jenis_munaqasyah_id);

        $data['kriteria'] = $kriteria;
        $data['nama_peserta'] = $row->nama_lengkap;
        $data['peserta_id'] = $id_peserta;
        $data['alamat_peserta'] = $row->alamat;
        $data['nama_lembaga'] = db_get_row('pengguna', array('id_customer' => $munaqasyah->customer_id))->nama;
        $data['kelas'] = $row->kelas;
        $data['munaqasyah'] = $munaqasyah;
        $data['code'] = $munaqasyah->code;

        $this->layout->set_wrapper('nilai_peserta', $data, 'page', false);

        $template_data["title"] = "Penilaian Peserta";
        $template_data["crumb"] = [];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function input_nilai($id_peserta, $kriteria_id, $method = null)
    {
        // if method is null redirect back

        $this->load->library('form_validation');
        $peserta = db_get_row('tm_peserta_munaqasyah', array('id_peserta_munaqasyah' => $id_peserta));
        $munaqasyah = db_get_row('tm_munaqasyah', array('id_munaqasyah' => $peserta->munaqasyah_id));
        $kriteria = db_get_row('tm_kriteria_nilai', 'id_kriteria_nilai = '.$kriteria_id);

        $this->form_validation->set_rules('id_peserta', 'id_peserta', 'required');

        if ($this->form_validation->run()) {
            foreach ($this->input->post('aspek_nilai_id') as $index => $aspek) {
                $unique = $id_peserta.'-'.$aspek;
                $data = array(
                        'peserta_munaqasyah_id' => $id_peserta,
                        'aspek_nilai_id' => $aspek,
                        'nilai' => $this->input->post('nilai')[$index],
                        'unique' => $unique
                    );
                print_r($data);
                if ($method == 'add') {
                    $this->db->insert('tm_nilai_munaqasyah', $data);
                } elseif ($method == 'edit') {
                    $this->db->where('unique', $unique);
                    $this->db->update('tm_nilai_munaqasyah', $data);
                }

                $kriteria_nilai[] = $this->input->post('nilai')[$index];
            }

            $sum_nilai = array_sum($kriteria_nilai);

            $data_kriteria = array(
                    'peserta_munaqasyah_id' => $id_peserta,
                    'kriteria_nilai_id' => $kriteria_id,
                    'nilai' => $sum_nilai
                );
            $this->db->delete('tm_rekap_nilai', array('peserta_munaqasyah_id' => $id_peserta, 'kriteria_nilai_id' => $kriteria_id));
            $this->db->insert('tm_rekap_nilai', $data_kriteria);

            // print_r($sum_nilai);

            redirect('munaqasyah/menu_penilaian/'.$id_peserta);
        } else {
            $data['peserta'] = $peserta;
            $data['kriteria'] = $kriteria;
            $data['method'] = $method;

            $data['nama_peserta'] = $peserta->nama_lengkap;
            $data['alamat_peserta'] = $peserta->alamat;
            $data['nama_lembaga'] = db_get_row('pengguna', array('id_customer' => $munaqasyah->customer_id))->nama;
            $data['kelas'] = $peserta->kelas;


            $this->layout->set_wrapper('input_nilai', $data, 'page', false);

            $template_data["title"] = "Penilaian Peserta";
            $template_data["crumb"] = [];
            $this->layout->auth();
            $this->layout->render('admin', $template_data); // front - auth - admin
            // $this->load->view('input_nilai', $data);
        }
    }


    public function approve($id_munaqasyah)
    {
        $code = random_string('nozero', 6);

        if ($this->check_code($code)) {
            $this->db->set('status', 'disetujui');
            $this->db->set('code', $code);
            $this->db->where('id_munaqasyah', $id_munaqasyah);
            $this->db->update('tm_munaqasyah');
            $this->message->custom_success_msg('/munaqasyah/data', 'Permohonan Munaqasyah disetujui');
        }
    }

    public function berita_acara($code)
    {
        $munaqasyah = db_get_row('tm_munaqasyah', array('code' => $code));
        $munaqasyah_id = $munaqasyah->id_munaqasyah;

        $crud = new grocery_CRUD();

        $state = $crud->getState();

        if ($state == 'list' || $state == 'success') {
            redirect('munaqasyah/penilaian/'.$code);
        }

        $crud->set_table("tm_berita_acara");
        $crud->set_subject("Berita Acara");

        // Show in
        $crud->add_fields(["munaqasyah_id", "nama_trainer", "tanggal_berita_acara", "catatan"]);
        $crud->edit_fields(["munaqasyah_id", "nama_trainer", "tanggal_berita_acara", "catatan"]);
        $crud->columns(["munaqasyah_id", "nama_trainer", "tanggal_berita_acara"]);

        // Fields type
        $crud->field_type("id", "integer");
        $crud->field_type("munaqasyah_id", "hidden", $munaqasyah_id);
        $crud->field_type("nama_trainer", "string");
        $crud->field_type("tanggal_berita_acara", "date");
        $crud->unset_texteditor("catatan", 'full_text');
        $crud->field_type("catatan", "text");
        $crud->field_type("created_at", "datetime");

        $crud->callback_after_insert(array($this,'_callback_berita_acara_insert'));

        // Relation n-n

        // Display As

        // Unset action
        $crud->unset_list();

        $data = (array) $crud->render();

        $this->layout->set_wrapper('grocery', $data, 'page', false);

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Berita Acara";
        $template_data["crumb"] = [];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }


    public function pdf_sertifikat($code)
    {
        $munaqasyah = db_get_row('view_munaqasyah', array('code' => $code));
        $peserta = db_get_all_data('tm_peserta_munaqasyah', array('munaqasyah_id' => $munaqasyah->id_munaqasyah, 'status' => 'lulus'));
        $lembaga = db_get_row('view_pengguna', array('id_customer' => $munaqasyah->customer_id));

        $$kriteria = db_get_all_data('tm_kriteria_nilai', array('jenis_munaqasyah_id' => $munaqasyah->jenis_munaqasyah_id));

        $berita_acara = db_get_row('tm_berita_acara', array('id' => $munaqasyah->berita_acara_id));

        $filename = 'munaqasyah-'.$code.'.pdf';

        $this->load->library('pdf');
        $this->load->library('hijridate');


        $params = array(
            'mode' => 'utf-8',
            'orientation' => 'L',
            'margin_left' => '35',
            'margin_right' => '35',
            'margin_top' => '85',
            'margin_bottom' => '0',
            'default_font' => 'arial'
        );

        $pdf = $this->pdf->load($params);

        $hijri = new HijriDate(strtotime($berita_acara->tanggal_berita_acara));

        $data = array(
            'peserta' => $peserta,
            'nama_lembaga' => $munaqasyah->nama_lembaga,
            'tanggal_munaqasyah' => DatetoIndo($munaqasyah->mulai),
            'jenis_munaqasyah' => $munaqasyah->jenis_munaqasyah,
            'tanggal_hijri' => $hijri->get_date(),
            'kepala_lembaga' => $munaqasyah->kepala_lembaga,
            'tanggal_masehi' => DatetoIndo($berita_acara->tanggal_berita_acara),
            'bulan_tahun' => integerToRoman(date('m', strtotime($berita_acara->tanggal_berita_acara))).'/'.date('Y', strtotime($berita_acara->tanggal_berita_acara))
        );

        // $data['peserta'] = $peserta;

        // print_r($peserta);
        $html = $this->load->view('munaqasyah_pdf', $data, true);
        // $html = $this->load->view('munaqasyah_pdf', $data);

        $pdf->WriteHTML($html, 2);
        // $pdf->Output($filename,'F');
        $pdf->Output();
    }



    public function check_code($code)
    {
        $this->db->where('code', $code);
        $count = $this->db->count_all_results('tm_munaqasyah');
        if ($count == 0) {
            return true;
        } else {
            $code = random_string('nozero', 6);
            $this->check_code($code);
        }
    }

    public function _callback_berita_acara_insert($post_array, $primary_key)
    {
        $data = array(
            'berita_acara_id' => $primary_key,
            'status' => 'terlaksana'
        );

        $this->db->where(array('id_munaqasyah' => $post_array['munaqasyah_id']));
        $this->db->update('tm_munaqasyah', $data);


        // update status lulus peserta
        $munaqasyah_id = $post_array['munaqasyah_id'];

        $munaqasyah = db_get_row('tm_munaqasyah', array('id_munaqasyah'=> $munaqasyah_id));
        $peserta = db_get_all_data('tm_peserta_munaqasyah', array('munaqasyah_id' => $munaqasyah_id));
        $kriteria = db_get_all_data('tm_kriteria_nilai', array('jenis_munaqasyah_id' => $munaqasyah->jenis_munaqasyah_id));
        foreach ($peserta as $p) {
            foreach ($kriteria as $k) {
                $nilai = db_get_row('tm_rekap_nilai', array('peserta_munaqasyah_id' => $p->id_peserta_munaqasyah, 'kriteria_nilai_id' => $k->id_kriteria_nilai))->nilai;
                if ($nilai < 7.5) {
                    $s = 1;
                } else {
                    $s = 0;
                }
                $a[$p->id_peserta_munaqasyah][$k->id_kriteria_nilai] = $s;
            }
            $sum_array = array_sum($a[$p->id_peserta_munaqasyah]);
            if ($sum_array == 0) {
                $sl = 'lulus';
            } else {
                $sl = 'tidak_lulus';
            }
            $b[] =  array(
                'id_peserta_munaqasyah' => $p->id_peserta_munaqasyah,
                'status'                => $sl
            );
        }
        $result = $b;
        foreach ($result as $r) {
            $this->db->where('id_peserta_munaqasyah', $r['id_peserta_munaqasyah']);
            $this->db->update('tm_peserta_munaqasyah', array('status' => $r['status']));
        }

        return true;
    }

    public function generate_nosert($code)
    {
        $munaqasyah = db_get_row('tm_munaqasyah', array('code'=> $code));
        $munaqasyah_id = $munaqasyah->id_munaqasyah;

        $peserta_lulus = db_get_all_data('tm_peserta_munaqasyah', array('munaqasyah_id' => $munaqasyah_id , 'status' => 'lulus' ));

        foreach ($peserta_lulus as $ps) {
            $num = db_get_row('last_certificate_munaqasyah', array('jenis_munaqasyah_id' => $munaqasyah->jenis_munaqasyah_id));

            $cert_number = $num->number + 1;
            $this->db->where(array('jenis_munaqasyah_id' => $munaqasyah->jenis_munaqasyah_id));
            $this->db->update('last_certificate_munaqasyah', array('number'=>$cert_number));

            $this->db->where(array('id_peserta_munaqasyah' => $ps->id_peserta_munaqasyah));
            $this->db->update('tm_peserta_munaqasyah', array('no_sertifikat' => $cert_number));
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
        if ($row->foto == '') {
            $val = '<i class="fa fa-close" style="color:red;"></i>';
        } else {
            $val = '<i class="fa fa-check" style="color:green;"></i>';
        }
        return $val;
    }

    public function _callback_nama_lengkap_column($value, $row)
    {
        $munaqasyah = db_get_row('tm_munaqasyah', array('id_munaqasyah' => $row->munaqasyah_id));
        $html ='<div><a href="'.$munaqasyah->code.'/edit/'.$row->id_peserta_munaqasyah.'">'.$value.'</a></div>
        <div>'.$row->alamat.'</div>';

        return $html;
    }

    public function _callback_nama_lengkap_nilai_column($value, $row)
    {
        $munaqasyah = db_get_row('tm_munaqasyah', array('id_munaqasyah' => $row->munaqasyah_id));
        $html ='<div><a href="/munaqasyah/menu_penilaian/'.$row->id_peserta_munaqasyah.'">'.$value.'</a></div>
        <div>'.$row->alamat.'</div>';

        return $html;
    }

    public function _callback_tanggal_lahir_column($value, $row)
    {
        $html = $row->tempat_lahir.', '.DatetoIndo($value);

        return $html;
    }

    public function _callback_before_insert($post_array)
    {
        $post_array['nama_lengkap'] = strtoupper($post_array['nama_lengkap']);

        return $post_array;
    }

    public function _callback_tanggal($value, $row)
    {
        return DatetoIndo($value);
    }

    public function _callback_actions_column($value, $row)
    {
        $attr = array(
            'class'     => 'btn btn-primary btn-xs btn-flat',
        );
        if ($row->trainer == '') {
            $attr = array(
                'class'     => 'btn btn-danger btn-xs btn-flat',
            );
            return anchor('munaqasyah/data/edit/'.$row->id_munaqasyah, 'Lengkapi Data', $attr);
        } elseif ($row->code == '') {
            $attr = array(
                'class'     => 'btn btn-warning btn-xs btn-flat',
            );
            return anchor('munaqasyah/approve/'.$row->id_munaqasyah, 'Setujui Kegiatan', $attr);
        } else {
            return anchor('munaqasyah/peserta/'.$row->code, 'Daftar Peserta', $attr);
        }
    }

    public function _callback_trainer_column($value, $row)
    {
        $array = explode(',', $value);
        foreach ($array as $val) {
            $trainer = db_get_row('trainer', array('id_trainer' => $val))->nama_panggilan;
            $html .= '<div>'.$trainer.'</div>';
        }
        return $html;
    }

    public function _callback_nama_lembaga_column($value, $row)
    {
        $tanggal = '';

        $html ='<div><a href="data/edit/'.$row->id_munaqasyah.'">'.$value.'</a></div>
        <div>'.$row->jenis_munaqasyah.'</div>';

        return $html;
    }

    public function _callback_penilaian_column($value, $row)
    {
        if ($row->status !== 'permohonan') {
            $attr = array(
                'class'     => 'btn btn-warning btn-xs btn-flat',
            );
            return anchor('munaqasyah/penilaian/'.$row->code, 'Input Nilai', $attr);
        }
    }

    public function _callback_status_column($value, $row)
    {
        if ($value == 'terlaksana') {
            return '<span class="badge bg-green">Terlaksana</span>';
        } elseif ($value == 'disetujui') {
            return '<span class="badge bg-aqua">Disetujui</span>';
        } else {
            return '<span class="badge bg-yellow">Permohonan</span>';
        }
    }

    public function _callback_rekap_nilai_column($value, $row)
    {
        $html = 'halo';

        $munaqasyah = db_get_row('tm_munaqasyah', array('id_munaqasyah' => $row->munaqasyah_id));

        $kriteria = db_get_all_data('tm_kriteria_nilai', array('jenis_munaqasyah_id' => $munaqasyah->jenis_munaqasyah_id));

        foreach ($kriteria as $k) {
            $v = db_get_row('tm_rekap_nilai', array('peserta_munaqasyah_id' => $row->id_peserta_munaqasyah, 'kriteria_nilai_id' => $k->id_kriteria_nilai))->nilai;
            $h = '<dt>'.$k->display_name.'</dt><dd>'.$v.'</dd>';
            $a .= $h;
        }

        $html = '<dl class="dl-horizontal">'.$a.'</dl>';

        return $html;
    }

    public function _callback_status_lulus_column($value)
    {
        if ($value == 'lulus') {
            $val = '<i class="fa fa-check" style="color:green;"></i>';
        } else {
            $val = '<i class="fa fa-close" style="color:red;"></i>';
        }
        return $val;
    }
}

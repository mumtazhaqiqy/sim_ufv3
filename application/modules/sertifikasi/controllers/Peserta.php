<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Peserta Controller.
 */
class Peserta extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('crud_model');
    }

    public function index()
    {
        echo "peserta Index";
    }

    /**
     * CRUD
     */
    public function daftar($code)
    {
        $this->db->where('code', $code);
        $sertifikasi = $this->db->get('sertifikasi')->row();

        $crud = new grocery_CRUD();

        $state = $crud->getState();

        if ($state == 'edit' || $state == 'add' || $state == 'insert' || $state == 'insert_validation' || $state == 'update' || $state == 'update_validation' || $state == 'ajax_relation' || $state == 'ajax_relation_n_n' || $state == 'delete_file') {
            $crud->set_table("biodata_guru");
        } else {
            $crud->set_table('view_peserta_lulus');
        }

        if ($sertifikasi->status !== 'disetujui') {
            $crud->unset_add();

            $order_sertifikat = db_get_row('order_sertifikat_guru', 'sertifikasi_id = '.$sertifikasi->id);
        }

        $crud->set_primary_key('id');

        $crud->set_subject("Calon Peserta Sertifikasi");

        // Show in
        $crud->add_fields(["sertifikasi_id","nama_lengkap", "tempat_lahir", "tanggal_lahir", "alamat_lengkap", "provinsi_id", "kabupaten_id", "kecamatan", "no_hp", "email", "pendidikan_terakhir", "hafal_quran", "jumlah_hafalan", "sudah_mengajar", "di_lembaga", 'pengalaman_mengajar', 'pengalaman_kursus','status','photo','agreement']);
        $crud->edit_fields(["sertifikasi_id","nama_lengkap", "tempat_lahir", "tanggal_lahir", "alamat_lengkap", "provinsi_id", "kabupaten_id", "kecamatan", "no_hp", "email", "pendidikan_terakhir", "hafal_quran", "jumlah_hafalan", "sudah_mengajar", "di_lembaga", 'pengalaman_mengajar', 'pengalaman_kursus','photo','agreement']);
        $crud->required_fields(["nama_lengkap", "no_sertifikat", "tempat_lahir", "tanggal_lahir", "alamat_lengkap"]);

        if ($order_sertifikat->status == 'diproses') {
            $crud->columns(["no_sertifikat","nama_lengkap",  "tempat_lahir", "tanggal_lahir", "alamat_lengkap","photo","status_lulus"]);
        } else {
            $crud->columns(["nama_lengkap",  "tempat_lahir", "tanggal_lahir", "alamat_lengkap","photo","status_lulus"]);
        }


        // Fields type
        $crud->field_type("id", "integer");
        $state = $crud->getState();
        if ($state == "edit") {
            $crud->field_type("no_sertifikat", "readonly");
        } else {
            $crud->field_type("no_sertifikat", "string");
        }

        $crud->field_type("nama_lengkap", "string");
        $crud->field_type("tempat_lahir", "string");
        $crud->field_type("tanggal_lahir", "date");
        $crud->field_type("alamat_lengkap", "string");
        $crud->field_type("kecamatan", "string");
        $crud->field_type("no_hp", "string");
        $crud->field_type("email", "string");
        $crud->field_type("pendidikan_terakhir", "dropdown", array(
            '1' => 'SD/MI/Sederajat',
            '2' => 'SMP/MTS/Sederajat',
            '3' => 'SMA/MA/Sederajat',
            '4' => 'Diploma',
            '5' => 'S1/Sederajat',
            '6' => 'S2/Sederajat',
            '7' => '> S2',
        ));
        $crud->field_type("hafal_quran", "true_false", array('Tidak','Ya'));
        $crud->field_type("jumlah_hafalan", "integer");
        $crud->field_type("sudah_mengajar", "true_false", array('Tidak','Ya'));
        $crud->field_type("agreement", "true_false", array('Tidak','Ya'));
        $crud->field_type("di_lembaga", "string");
        $crud->field_type("data_lembaga", "string");
        $crud->field_type('sertifikasi_id', 'hidden', $sertifikasi->id);
        $crud->field_type('status', 'hidden', 'peserta');
        $crud->set_field_upload('photo', 'assets/uploads/image');

        $crud->display_as(array(
            'agreement' => 'saya bertanggung jawab penuh terhadap data yang saya tuliskan untuk digunakan sebagai data pada sertifikat',
            'tempat_lahir' => 'Tempat',
            'tanggal_lahir' => 'Tgl Lahir',
            'status_lulus' => 'Lulus',
        ));

        // Relation n-n
        $crud->set_relation('provinsi_id', 'wilayah_provinsi', 'provinsi');
        $crud->set_relation('kabupaten_id', 'wilayah_kabupaten', 'kabupaten');

        // Callback
        $crud->callback_field('pengalaman_mengajar', array($this->crud_model, 'pengalaman_mengajar_callback'));
        $crud->callback_field('pengalaman_kursus', array($this->crud_model, 'pengalaman_kursus_callback'));
        $crud->callback_before_insert(array($this->crud_model, 'c_insert'));
        $crud->callback_before_update(array($this->crud_model, 'c_insert'));
        $crud->callback_column('status', array($this->crud_model, '_callback_status_action'));
        $crud->callback_column('photo', array($this, '_callback_photo_column'));
        $crud->callback_column('status_lulus', array($this, '_callback_status_lulus_column'));
        $crud->callback_after_upload(array($this, '_callback_photo_upload'));

        // Unset action
        if (!$this->ion_auth->logged_in()) {
            $crud->unset_read();
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->unset_print();
            $crud->unset_export();
        } else {
            $crud->unset_read();
            // $crud->unset_edit();
            // $crud->unset_delete();
            $crud->unset_print();
            // $crud->unset_export();
        }

        // where
        $crud->where('sertifikasi_id', $sertifikasi->id);


        // Dependet Dropdown
        $this->load->library('gc_dependent_select');

        $fields = array(
            // provinsi
            'provinsi_id' => array(
                'table_name' => 'wilayah_provinsi',
                'title'      => 'provinsi',
                'relate'     => null
            ),

            // kabupaten
            'kabupaten_id' => array(
                'table_name' => 'wilayah_kabupaten',
                'title'      => 'kabupaten',
                'id_field'   => 'id',
                'relate'     => 'provinsi_id',
                'data-placeholder' => 'pilih kabupaten'
            )

        );

        $config = array(
            'main_table' => 'biodata_guru',
            'main_table_primary' => 'id',
            'url' => base_url().'sertifikasi/' . __CLASS__ . '/' . __FUNCTION__ . '/', //path to method
        );

        $wilayah = new gc_dependent_select($crud, $fields, $config);

        $js = $wilayah->get_js();


        $output = $crud->render();
        $output->output .= $js;


        // inject js

        $data = (array) $output;

        $data['sertifikasi'] = $sertifikasi;

        $this->layout->set_wrapper('list_peserta', $data, 'page', false);

        // $template_data['js_plugins'] = [
        //     base_url('assets/js/biodata.js')
        // ];


        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Biodata Guru Sertifikasi";
        $template_data["crumb"] = ["biodata" => ""];
        // $this->layout->auth();
        if (!$this->ion_auth->logged_in()) {
            $this->layout->render('blank', $template_data); // front - auth - admin
        } else {
            $this->layout->render('admin', $template_data); // front - auth - admin
        }
    }


    // Generate nomer sertifikat untuk peserta lulus sertifikasi

    public function generate($code)
    {
        $this->db->where('code', $code);
        $sertifikasi = $this->db->get('sertifikasi')->row();

        $order_sertifikat = db_get_row('order_sertifikat_guru', 'sertifikasi_id = '.$sertifikasi->id);

        if ($order_sertifikat->status !== 'diproses') {
            $peserta_lulus = db_get_all_data('biodata_guru', 'sertifikasi_id = '.$sertifikasi->id.' and status = "peserta"');
            // print_r($peserta_lulus);
            foreach ($peserta_lulus as $key => $peserta) {
                $num = db_get_row('last_certificate_number', 'id = 1');

                $cert_number = $num->number + 1;

                $this->db->where('id = 1');
                $this->db->update('last_certificate_number', array('number' => $cert_number));

                $this->db->where('id = '.$peserta->id);
                $this->db->update(
                    'biodata_guru',
                array(
                    'no_sertifikat' => $cert_number,
                    'status' => 'guru'

                )
                );
            }
            // update order_sertifikat status
            $this->db->where('sertifikasi_id = '.$sertifikasi->id);
            $this->db->update('order_sertifikat_guru', array('status' => 'diproses'));
            // redirect to peserta list
            redirect('sertifikasi/peserta/daftar/'.$code);
        // echo 'permohonan';
            // echo $cert_number;
        } else {
            // echo 'diproses';
            // return false;
            // redirect back to list peserta
            redirect('sertifikasi/peserta/daftar/'.$code);
        }
    }

    public function pdf_sertifikat($code)
    {
        $this->db->where('code', $code);
        $sertifikasi = $this->db->get('sertifikasi')->row();

        $order_sertifikat = db_get_row('order_sertifikat_guru', 'sertifikasi_id = '.$sertifikasi->id);

        if ($order_sertifikat->status == 'diproses') {
            $filename = 'sertifikasi-'.$code.'.pdf';
            $path = 'assets/uploads/files/sertifikat/'.$filename;

            if (file_exists($path)) {
                // force_download($path, NULL);
                // redirect('sertifikasi/peserta/daftar/'.$code);
                if ($this->input->get('ulang') == true) {
                    unlink($path);
                    redirect('sertifikasi/peserta/pdf_sertifikat/'.$code);
                } else {
                    redirect($path);
                }
            } else {
                $this->load->library('pdf');
                $this->load->library('hijridate');


                $params = array(
                    'mode' => 'utf-8',
                    'orientation' => 'L',
                    'margin_left' => '35',
                    'margin_right' => '35',
                    'margin_top' => '84',
                    'margin_bottom' => '5',
                    'default_font' => 'arial'
                );

                $pdf = $this->pdf->load($params);

                $peserta_lulus = db_get_all_data('biodata_guru', 'sertifikasi_id = '.$sertifikasi->id.' and status = "guru"');
                // print_r($peserta_lulus);
                $data['data_peserta'] = $peserta_lulus;
                $data['kabupaten'] = $sertifikasi->kabupaten;
                $data['tanggal_kegiatan'] = date('d M', strtotime($sertifikasi->hari1)).' - '.date('d M Y', strtotime($sertifikasi->hari3));
                $data['sampai'] = DatetoIndo_hb($order_sertifikat->tanggal_proses).' '.(date('Y', strtotime($sertifikasi->hari1))+4);
                $data['tanggal_proses'] = DatetoIndo($order_sertifikat->tanggal_proses);
                $data['bulan_tahun_sertifikat'] = integerToRoman(date('m', strtotime($order_sertifikat->tanggal_proses))).'/'.date('Y', strtotime($order_sertifikat->tanggal_proses));

                $hijri = new HijriDate(strtotime($order_sertifikat->tanggal_proses));

                $data['tanggal_hijri'] = $hijri->get_date();
                // $date['tanggal_hijri'] = "hao";

                $html = $this->load->view('test', $data, true);
                // $html = $this->load->view('test', $data);

                $pdf->WriteHTML($html, 2);
                $pdf->Output($filename, 'F');

                rename($filename, 'assets/uploads/files/sertifikat/'.$filename);
                // $pdf->Output();
                // force_download($path, NULL);

                redirect($path);
            }
        } else {
            redirect('sertifikasi/peserta/daftar/'.$code);
            // echo  "<script type='text/javascript'>";
            // echo "window.close();";
            // echo "</script>";
        }
    }

    public function pdf_sertifikat_by_no($no_sertifikat)
    {
        $peserta_lulus = db_get_row('biodata_guru', 'no_sertifikat = '.$no_sertifikat.' and status = "guru"');

        $this->db->where('id', $peserta_lulus->sertifikasi_id);
        $sertifikasi = $this->db->get('sertifikasi')->row();

        $filename = 'sertifikat_no_'.$no_sertifikat.'.pdf';
        $path = 'assets/uploads/files/sertifikatguru/'.$filename;


        if (file_exists($path)) {
            redirect($path);
        } else {
            $this->load->library('pdf');
            $this->load->library('hijridate');


            $params = array(
                'mode' => 'utf-8',
                'orientation' => 'L',
                'margin_left' => '35',
                'margin_right' => '35',
                'margin_top' => '84',
                'margin_bottom' => '5',
                'default_font' => 'arial'
            );

            $pdf = $this->pdf->load($params);
            // print_r($peserta_lulus);
            $data['p'] = $peserta_lulus;
            $data['kabupaten'] = $sertifikasi->kabupaten;
            $data['tanggal_kegiatan'] = date('d M', strtotime($sertifikasi->hari1)).' - '.date('d M Y', strtotime($sertifikasi->hari3));
            $data['sampai'] = DatetoIndo_hb($sertifikasi->hari3).' '.(date('Y', strtotime($sertifikasi->hari1))+4);
            $data['tanggal_proses'] = DatetoIndo($sertifikasi->hari3);
            $data['bulan_tahun_sertifikat'] = integerToRoman(date('m', strtotime($sertifikasi->hari3))).'/'.date('Y', strtotime($sertifikasi->hari3));

            $hijri = new HijriDate(strtotime($sertifikasi->hari3));

            $data['tanggal_hijri'] = $hijri->get_date();
            // $date['tanggal_hijri'] = "hao";

            $html = $this->load->view('pdf_guru', $data, true);
            // $html = $this->load->view('test', $data);

            $pdf->WriteHTML($html, 2);
            $pdf->Output($filename, 'F');

            rename($filename, $path);
            // $pdf->Output();
            // force_download($path, NULL);

            redirect($path);
        }
    }


    public function test($code)
    {
        $this->load->library('pdf');
        $params = array(
            'mode' => 'utf-8',
            'orientation' => 'L'
        );
        $filename = 'filename.pdf';

        $pdf = $this->pdf->load($params);
        $data = [];
        $html = $this->load->view('test', $data, true);

        // $pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
        // $stylesheet = file_get_contents(FCPATH.'assets/css/invoice.css');

        // $pdf->WriteHTML($stylesheet,1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output($filename, 'D');
    }

    /**
     * Avatar upload compress.
     *
     * @return Image
     **/
    public function _callback_photo_upload($uploader_response, $field_info, $files_to_upload)
    {
        $this->load->library('image_moo');
        $file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name;

        $this->image_moo->load($file_uploaded)->resize_crop(400, 600)->save($file_uploaded, true);

        return true;
    }

    public function _callback_photo_column($value, $row)
    {
        if ($row->photo == '') {
            $val = '<i class="fa fa-close" style="color:red;"></i>';
        } else {
            $val = '<i class="fa fa-check" style="color:green;"></i>';
        }
        return $val;
    }

    public function _callback_status_lulus_column($value, $row)
    {
        if ($value == '') {
            $val = '<i class="fa fa-close" style="color:red;"></i>';
        } else {
            $val = '<i class="fa fa-check" style="color:green;"></i>';
        }
        return $val;
    }
}

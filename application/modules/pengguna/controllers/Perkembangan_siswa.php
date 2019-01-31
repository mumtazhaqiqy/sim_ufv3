<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Perkembangan_siswa Controller.
 */
class Perkembangan_siswa extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Guru Quran";
        $this->load->library('form_validation');
        $this->load->model('crud_model');
        $this->load->model('pengguna_model', 'pengguna');
    }

    public function index()
    {
        $this->load->model('pengguna_model', 'pengguna');
        $user_info = $this->ion_auth->user()->row();
        $user_data = json_decode($user_info->additional)[0];

        $crud = new grocery_CRUD();
        $state = $crud->getState();

        if ($state == "list" || $state == "ajax_list" || $state == "ajax_list_info") {
            $crud->set_table("view_perkembangan_siswa");
        } else {
            $crud->set_table("perkembangan_siswa");
        }

        $crud->set_primary_key('id_perkembangan_siswa');

        $crud->set_subject("Perkembangan Santri");

        // Show in
        $crud->add_fields(["customer_id", "jumlah_siswa", "pra", "jilid1", "jilid2", "jilid3", "jilid4", "jilid5", "jilid6", "tajwid", "ghorib", "quran", "dewasa1", "dewasa2", "dewasa3", "tahfidz_30", "tahfidz_29", "tahfidz_28", "tahfidz_1", "tahfidz_2", "tahfidz_3", "tahfidz_4", "tahfidz_5", "tahfidz_6", "turjuman_1", "turjuman_2", "turjuman_3", "turjuman_4", "turjuman_5", "turjuman_6", "turjuman_7", "turjuman_8", "turjuman_9", "turjuman_10", "turjuman_11", "turjuman_12", "turjuman_13", "periode_bulan", "tahun", "unique"]);
        $crud->edit_fields(["customer_id", "jumlah_siswa", "pra", "jilid1", "jilid2", "jilid3", "jilid4", "jilid5", "jilid6", "tajwid", "ghorib", "quran", "dewasa1", "dewasa2", "dewasa3", "tahfidz_30", "tahfidz_29", "tahfidz_28", "tahfidz_1", "tahfidz_2", "tahfidz_3", "tahfidz_4", "tahfidz_5", "tahfidz_6", "turjuman_1", "turjuman_2", "turjuman_3", "turjuman_4", "turjuman_5", "turjuman_6", "turjuman_7", "turjuman_8", "turjuman_9", "turjuman_10", "turjuman_11", "turjuman_12", "turjuman_13", "periode_bulan", "tahun", "unique"]);
        $crud->columns(["id_perkembangan_siswa"]);
        // $crud->columns(["customer_id", "jumlah_siswa", "pra", "jilid1", "jilid2", "jilid3", "jilid4", "jilid5", "jilid6", "tajwid", "ghorib", "quran", "dewasa1", "dewasa2", "dewasa3", "tahfidz_30", "tahfidz_29", "tahfidz_28", "tahfidz_1", "tahfidz_2", "tahfidz_3", "tahfidz_4", "tahfidz_5", "tahfidz_6", "turjuman_1", "turjuman_2", "turjuman_3", "turjuman_4", "turjuman_5", "turjuman_6", "turjuman_7", "turjuman_8", "turjuman_9", "turjuman_10", "turjuman_11", "turjuman_12", "turjuman_13", "periode_bulan", "tahun", "unique"]);

        // State logic

        if ($state == 'add') {
            // redirect to add new perkembagan siswa form
            redirect('/pengguna/perkembangan_siswa/add');
        }


        // Callback goes here
        $crud->callback_column('id_perkembangan_siswa', array($this->crud_model, '_callback_perkembangan_siswa'));

        // Callback before insert (update data kondisi siswa),(Add log Info)



        // Where
        $crud->where('customer_id', $user_data->customer_id);
        $crud->order_by('id_perkembangan_siswa', 'desc');

        // Fields type

        // Relation n-n

        // Display As
        $crud->display_as('id_perkembangan_siswa', 'Perkembangan Siswa');

        // Unset action
        // $crud->unset_add();
        $crud->unset_read();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_export();
        $crud->unset_print();

        $data = (array) $crud->render();

        $this->layout->set_wrapper('crud', $data, 'page', false);

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Perkembangan Santri";
        $template_data["crumb"] = ["table" => ""];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function delete($unique)
    {
        $this->db->delete('jumlah_santri', array('unique' => $unique));
        $this->db->delete('perkembangan_siswa', array('unique' => $unique));

        set_message('success', 'Data berhasil dihapus');
        redirect('/pengguna/perkembangan_siswa');
    }


    public function edit()
    {
        $unique = $this->input->get('unique');
        $view_unique = db_get_row('perkembangan_siswa', 'unique = "'.$unique.'"');

        $customer = db_get_row('view_pengguna', 'id_customer ='.$view_unique->customer_id);
        $kelas = db_get_all_data('kelas', 'jenis_lembaga_id = '.$customer->jenis_lembaga_id);

        // print_r($customer);

        if ($unique == null) {
            redirect_back();
        } else {
            $data['kelas'] = $kelas;
            $data['jilid'] = db_get_all_data('jilid');
            $data['unique'] = $unique;
            $data['periode_bulan'] = $view_unique->periode_bulan;
            $data['tahun'] = $view_unique->tahun;

            $this->layout->set_wrapper('edit_perkembangan_siswa', $data, 'page', false);

            $template_data["title"] = "Edit Perkembangan Santri";
            $template_data["crumb"] = ["table" => ""];
            $this->layout->auth();
            $this->layout->render('admin', $template_data); // front - auth - admin
        }
    }

    public function save()
    {
        $user_info = $this->ion_auth->user()->row();
        $user_data = json_decode($user_info->additional)[0];
        $customer_id = $user_data->customer_id;

        $periode_bulan = $this->input->post('periode_bulan');
        $tahun = $this->input->post('tahun');

        if ($this->input->get('method') == 'edit') {
            $unique = $this->input->post('unique');
        } else {
            $unique = $customer_id.'_'.$tahun.'_'.$periode_bulan;

            $checkunique = $this->db->query('
            SELECT uni.unique
            FROM (SELECT s.`unique`
            FROM jumlah_santri as s
            GROUP BY s.`unique`) as uni
            WHERE uni.unique = "'.$unique.'"
            ')->row();

            if (count($checkunique) == 1) {
                set_message('warning', 'maaf anda sudah pernah melakukan input data santri untuk periode tersebut');
                redirect('/pengguna/perkembangan_siswa');
            }
        }

        $customer = db_get_row('view_pengguna', array('id_customer' => $customer_id));
        $kelas = db_get_all_data('kelas', 'jenis_lembaga_id = '.$customer->jenis_lembaga_id);

        $this->form_validation->set_rules('periode_bulan', 'periode_bulan', 'required');
        $this->form_validation->set_rules('tahun', 'tahun', 'required');

        if ($this->form_validation->run()) {
            $this->db->delete('jumlah_santri', array('unique' => $unique));

            foreach ($kelas as $k) {
                $santri_jilid = $this->input->post($k->id);
                foreach ($santri_jilid as $key => $sj) {
                    if ($sj != 0) {
                        $data = array(
                            'customer_id' => $customer_id,
                            'periode_bulan' => $periode_bulan,
                            'tahun' => $tahun,
                            'kelas_id' => $k->id,
                            'jilid_id' => $key,
                            'jumlah_santri' => $sj,
                            'unique' => $unique
                        );

                        $this->db->insert('jumlah_santri', $data);
                        // echo '<pre>';
                        // print_r($data);
                        // echo '</pre>';
                    }
                }
            }

            for ($i = 0; $i < 36; $i++) {
                // code...
                $query = $this->db->query('
            SELECT * FROM
            (SELECT s.jilid_id, sum(s.jumlah_santri) as total
            FROM jumlah_santri as s
            WHERE s.customer_id = '.$customer_id.' AND s.periode_bulan = '.$periode_bulan.' AND s.tahun = '.$tahun.'
            GROUP BY s.jilid_id) AS by_jilid
            WHERE jilid_id = '.$i.'
            ')->row();

                if (isset($query)) {
                    $value[$i] = $query->total;
                } else {
                    $value[$i] = 0;
                }
            }

            $jumlah_siswa = array_sum($value);

            $perkembangan_siswa = array(
            'customer_id' => $customer_id,
            'pra' => $value[1],
            'jilid1' => $value[2],
            'jilid2' => $value[3],
            'jilid3' => $value[4],
            'jilid4' => $value[5],
            'jilid5' => $value[6],
            'jilid6' => $value[7],
            'tajwid' => $value[8],
            'ghorib' => $value[9],
            'quran' => $value[10],
            'dewasa1' => $value[11],
            'dewasa2' => $value[12],
            'dewasa3' => $value[13],
            'tahfidz_30' => $value[14],
            'tahfidz_29' => $value[15],
            'tahfidz_28' => $value[16],
            'tahfidz_1' => $value[17],
            'tahfidz_2' => $value[18],
            'tahfidz_3' => $value[19],
            'tahfidz_4' => $value[20],
            'tahfidz_5' => $value[21],
            'tahfidz_6' => $value[22],
            'turjuman_1' => $value[23],
            'turjuman_2' => $value[24],
            'turjuman_3' => $value[25],
            'turjuman_4' => $value[26],
            'turjuman_5' => $value[27],
            'turjuman_6' => $value[28],
            'turjuman_7' => $value[29],
            'turjuman_8' => $value[30],
            'turjuman_9' => $value[31],
            'turjuman_10' => $value[32],
            'turjuman_11' => $value[33],
            'turjuman_12' => $value[34],
            'turjuman_13' => $value[35],
            'jumlah_siswa' => $jumlah_siswa,
            'periode_bulan' =>$periode_bulan,
            'tahun' => $tahun,
            'unique' =>$unique
        );

            $kondisi_siswa = array(
            'customer_id' => $customer_id,
            'pra' => $value[1],
            'jilid1' => $value[2],
            'jilid2' => $value[3],
            'jilid3' => $value[4],
            'jilid4' => $value[5],
            'jilid5' => $value[6],
            'jilid6' => $value[7],
            'tajwid' => $value[8],
            'ghorib' => $value[9],
            'quran' => $value[10],
            'dewasa1' => $value[11],
            'dewasa2' => $value[12],
            'dewasa3' => $value[13],
            'tahfidz_30' => $value[14],
            'tahfidz_29' => $value[15],
            'tahfidz_28' => $value[16],
            'tahfidz_1' => $value[17],
            'tahfidz_2' => $value[18],
            'tahfidz_3' => $value[19],
            'tahfidz_4' => $value[20],
            'tahfidz_5' => $value[21],
            'tahfidz_6' => $value[22],
            'turjuman_1' => $value[23],
            'turjuman_2' => $value[24],
            'turjuman_3' => $value[25],
            'turjuman_4' => $value[26],
            'turjuman_5' => $value[27],
            'turjuman_6' => $value[28],
            'turjuman_7' => $value[29],
            'turjuman_8' => $value[30],
            'turjuman_9' => $value[31],
            'turjuman_10' => $value[32],
            'turjuman_11' => $value[33],
            'turjuman_12' => $value[34],
            'turjuman_13' => $value[35],
            'jumlah_siswa' => $jumlah_siswa
        );

            $findrowps = db_get_row('perkembangan_siswa', 'unique = "'.$unique.'"');

            if (count($findrowps) == 1) {
                $this->db->where('unique', $unique);
                $this->db->update('perkembangan_siswa', $perkembangan_siswa);
            } else {
                $this->db->insert('perkembangan_siswa', $perkembangan_siswa, $where);
            }

            $this->db->delete('kondisi_siswa', 'customer_id = '.$customer_id);
            $this->db->insert('kondisi_siswa', $kondisi_siswa);

            redirect('/pengguna/perkembangan_siswa');
        } else {
            redirect('/pengguna/perkembangan_siswa');
        }
    }

    public function add()
    {
        $user_info = $this->ion_auth->user()->row();
        $user_data = json_decode($user_info->additional)[0];

        $customer = db_get_row('view_pengguna', 'id_customer ='.$user_data->customer_id);
        $kelas = db_get_all_data('kelas', 'jenis_lembaga_id = '.$customer->jenis_lembaga_id);

        // print_r($customer);

        $data['kelas'] = $kelas;
        $data['jilid'] = db_get_all_data('jilid');
        $data['customer_id'] = $user_data->customer_id;

        $this->layout->set_wrapper('add_perkembangan_siswa', $data, 'page', false);

        $template_data["title"] = "Tambah Perkembangan Santri";
        $template_data["crumb"] = ["table" => ""];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function print($unique)
    {
      // code...
      $perkembangan_siswa = db_get_row('perkembangan_siswa', array('unique' => $unique));
      $customer_id = $perkembangan_siswa->customer_id;

      $pengguna = db_get_row('view_pengguna', array('id_customer' => $customer_id));
      $kelas = db_get_all_data('kelas', array('jenis_lembaga_id' => $pengguna->jenis_lembaga_id));
      $jilid = db_get_all_data('jilid');
      $guru = db_get_all_data('guru_quran', array('customer_id' => $customer_id));

      $this->load->library('pdf');
      $this->load->library('hijridate');

      $filename = $unique;

      $params = array(
          'mode' => 'utf-8',
          'orientation' => 'P',
          'margin_left' => '20',
          'margin_right' => '20',
          'margin_top' => '15',
          'margin_bottom' => '10',
          'default_font' => 'arial'
      );

      $data = array(
        'unique' => $unique,
        'customer' => $pengguna,
        'kelas' => $kelas,
        'jilid' => $jilid,
        'guru' => $guru,
        'tanggal' => $perkembangan_siswa->created_at,

      );

      $stylesheet = file_get_contents(FCPATH.'assets/css/perkembangan.css');

      $pdf = $this->pdf->load($params);
      $html = $this->load->view('pdf_perkembangan', $data, true);
      // $html = $this->load->view('pdf_perkembangan', $data);
      // $html = $this->load->view('test', $data);

      $pdf->WriteHTML($stylesheet,1);
      $pdf->WriteHTML($html, 2);
      // $pdf->Output($filename, 'F');
      $pdf->Output();

      // rename($filename, 'assets/uploads/files/sertifikat/'.$filename);
      // // $pdf->Output();
      // // force_download($path, NULL);
      //
      // redirect($path);

    }
}

/* End of file example.php */
/* Location: ./application/modules/pengguna/controllers/Pengguna.php */

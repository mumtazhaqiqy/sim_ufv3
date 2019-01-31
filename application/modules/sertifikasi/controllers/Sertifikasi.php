<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Sertifikasi Controller.
 */
class Sertifikasi extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "sertifikasi";
        $this->load->model('crud_model');
        $this->load->helper('string');
    }

    /**
     * Index
     */
    public function index()
    {
        redirect('sertifikasi/crud');
    }

    /**
     * CRUD
     */
    public function crud()
    {
        $crud = new grocery_CRUD();

        $crud->set_table("sertifikasi");
        $crud->set_subject("Permohonan Sertifikasi");

        $crud->columns([ 'actions', "order_date", "pemohon", "trainers", "status", "code"]);

        // Show in
        $crud->fields(["pemohon", "user_id", "jenis_sertifikasi", "tempat_pelaksanaan", "hari1", "hari2","hari3","jumlah_peserta", "trainers",'status']);
        // $crud->edit_fields(["pemohon", "user_id", "tanggal_pelaksanaan", "tempat_pelaksanaan", "jumlah_peserta", "trainers"]);

        // Fields type
        $crud->field_type("id", "integer");
        $crud->field_type("pemohon", "string");
        $crud->field_type("tempat_pelaksanaan", "string");
        $crud->field_type("jumlah_peserta", "integer");
        $crud->field_type('status', 'hidden', 'permohonan');
        $jenis_sertifikasi = array('sertifikasi_guru' => 'Sertifikasi Guru Quran');
        $crud->field_type('jenis_sertifikasi', 'dropdown', $jenis_sertifikasi);

        // trainer list
        $list = [];

        // so the umda only get trainer from they're own trainers

        if ($this->ion_auth->in_group('ummi_daerah')) {
            $user_info = $this->ion_auth->user()->row();
            $additional_data = json_decode($user_info->additional)[0];

            $ummi_daerah_id = $additional_data->ummi_daerah_id;

            $this->db->where('ummi_daerah_id', $ummi_daerah_id);
            $crud->field_type("user_id", "hidden", $ummi_daerah_id);
        } elseif ($this->ion_auth->in_group('admin')) {
            $crud->set_relation("user_id", "ummi_daerah", "ummi_daerah");
        }


        $tableList = $this->db->get("trainer")->result_array();
        foreach ($tableList as $listMulty) {
            $list[$listMulty['id_trainer']] = $listMulty['nama_trainer'];
        }
        $crud->field_type("trainers", "multiselect", $list);

        $crud->unset_texteditor("notes", 'full_text');
        $crud->field_type("notes", "text");
        $crud->field_type("order_date", "datetime");

        // Relation n-n

        // Validation
        $crud->set_rules("pemohon", "Pemohon", "required");
        $crud->set_rules("user_id", "User id", "required");
        $crud->set_rules("tempat_pelaksanaan", "Tempat pelaksanaan", "required");
        $crud->set_rules("jumlah_peserta", "Jumlah peserta", "required");

        // Display As
        $crud->display_as(array(
            'order_date' => 'Tanggal Order',
            'pemohon'	=> 'Nama Pemohon',
            'jumlah_peserta' => 'Peserta',
            'hari1' => 'Hari ke-1',
            'hari2' => 'Hari ke-2',
            'hari3' => 'Hari ke-3',
            'user_id' => 'Ummi Daerah'
        ));


        // Callback
        if ($this->ion_auth->is_admin()) {
            if ($this->uri->segment(3) == "approval") {
                $crud->where('status', 'permohonan');
                $crud->callback_column('actions', array($this->crud_model,'_callback_approval_action'));
            } elseif ($this->uri->segment(3) == "unapproval") {
                $crud->where('status', 'disetujui');
                $crud->callback_column('actions', array($this->crud_model,'_callback_unapproval_action'));
            } else {
                $crud->callback_column('actions', array($this , '_callback_crud_action'));
            }
        } else {
            $crud->callback_column('actions', array($this , '_callback_crud_action'));
        }

        $crud->callback_before_insert(array($this->crud_model, 'initial_status_callback'));
        $crud->callback_column('order_date', array($this , '_callback_date'));
        $crud->callback_column('pemohon', array($this , '_callback_pemohon'));
        $crud->callback_column('trainers', array($this , '_callback_trainer_column'));
        $crud->callback_column('status', array($this , '_callback_status_column'));

        // Unset action
        if (!$this->ion_auth->is_admin()) {
            $crud->unset_action();
            $crud->unset_print();
            $crud->unset_export();
        }


        // where
        if (!$this->ion_auth->is_admin()) {
            $crud->where('user_id', $ummi_daerah_id);
        }


        // Crud Render
        $output = $crud->render();

        $data = (array) $output;

        $this->layout->set_wrapper('crud', $data, 'page', false);


        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Sertifikasi";
        $template_data["crumb"] = [
          "sertifikasi" => "",
        ];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function _callback_date($value, $row)
    {
        $date = dateFormat($value);
        return $date;
    }

    public function _callback_crud_action($value, $row)
    {
        $attr = array(
            'class'     => 'btn btn-primary btn-xs btn-flat',
        );
        if ($row->status == 'permohonan') {
            $attr = array(
                'class'     => 'btn btn-warning btn-xs btn-flat',
            );
            return '';
        } else {
            return anchor('sertifikasi/peserta/daftar/'.$row->code, 'Daftar Peserta', $attr);
        }
    }

    public function _callback_pemohon($value, $row)
    {
        $tanggal_pelaksanaan = date('d M Y', strtotime($row->tanggal_pelaksanaan));

        $html = '
    		<span><b>'.$value.'</b></span><br>
    		<span>tanggal : '.date('d', strtotime($row->hari1)).','.date('d', strtotime($row->hari2)).','.DatetoIndo($row->hari3).'</span><br>
    		<span>'.$row->tempat_pelaksanaan.'</span>

    		';


        return $html;
    }


    public function approve_action($id = null)
    {
        $code = random_string('nozero', 6);

        if ($this->check_code($code)) {
            $this->db->set('status', 'disetujui');
            $this->db->set('code', $code);
            $this->db->where('id', $id);
            $this->db->update('sertifikasi');
            $this->message->custom_success_msg('/sertifikasi/crud/approval', 'Permohonan Sertifikasi disetujui');
        }
    }

    public function check_code($code)
    {
        $this->db->where('code', $code);
        $count = $this->db->count_all_results('sertifikasi');

        if ($count == 0) {
            return true;
        } else {
            $code = random_string('nozero', 6);
            $this->check_code($code);
        }
    }


    public function unapprove_action($id = null)
    {
        $this->db->set('status', 'permohonan');
        $this->db->set('code', '');
        $this->db->where('id', $id);
        $this->db->update('sertifikasi');
        $this->message->custom_error_msg('/sertifikasi/crud/unapproval', 'Permohonan Sertifikasi dibatalkan');
    }

    public function pendaftaran()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('code', 'code', 'required');
        $this->form_validation->set_message('required', 'Please enter your code');

        if ($this->form_validation->run()) {
            $code = $this->input->post('code');
            $this->db->where('code', $code);
            $query = $this->db->get('sertifikasi');
            $count = $query->num_rows();

            if ($count == 1) {
                redirect('/sertifikasi/peserta/daftar/'.$code.'/add');
            } else {
                $this->message->custom_error_msg('/sertifikasi/pendaftaran', 'Maaf Kode Sertifikasi Tidak Ditemukan');
            }
        } else {
            $this->layout->set_wrapper('input_code', $data, 'page', false);

            $template_data["title"] = "Pendaftaran";
            $template_data["crumb"] = ["sertifikasi" => "",];
            $this->layout->render('front', $template_data);
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
}

/* End of file example.php */
/* Location: ./application/modules/sertifikasi/controllers/Sertifikasi.php */

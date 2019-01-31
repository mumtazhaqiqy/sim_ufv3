<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Guru_quran Controller.
 */
class Guru_quran extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Guru Quran";
        $this->load->model('crud_model');
        $this->load->library('mobile_detect');
    }

    public function index2()
    {
    }
    public function index()
    {
        $detect = new Mobile_Detect();

        $this->load->model('pengguna_model', 'pengguna');
        $user_info = $this->ion_auth->user()->row();
        $additional_data = json_decode($user_info->additional)[0];
        $customer_id = $additional_data->customer_id;

        $crud = new grocery_CRUD();

        $crud->set_table("guru_quran");
        $crud->set_subject("Guru Quran");

        // Show in
        $crud->fields(["customer_id", "nama_guru", "jenis_kelamin", "pendidikan_terakhir", "status_sertifikasi", "no_sertifikat", "tahun_sertifikasi", "alamat", "tanggal_lahir", "email", "nomor_hp", "foto"]);

        if ($detect->isMobile()) {
            $crud->columns(["foto","nama_guru"]);
            $crud->unset_delete();
            $crud->unset_export();
            $crud->unset_print();
        } else {
            $crud->columns(["foto","nama_guru","status_sertifikasi", "nomor_hp"]);
        }

        // Fields type
        $crud->field_type("id_guru_quran", "integer");
        $crud->field_type("customer_id", "integer");
        $crud->field_type("nama_guru", "string");
        $crud->field_type("jenis_kelamin", "dropdown", array('1' => 'Laki-laki', '2' => 'Perempuan'));
        $crud->field_type("pendidikan_terakhir", "dropdown", array(
            '1' => 'SD/MI/Sederajat',
            '2' => 'SMP/MTS/Sederajat',
            '3' => 'SMA/MA/Sederajat',
            '4' => 'Diploma',
            '5' => 'S1/Sederajat',
            '6' => 'S2/Sederajat',
            '7' => '> S2',
        ));
        $crud->field_type('customer_id', 'hidden', $customer_id);
        $crud->field_type("status_sertifikasi", "true_false", array('Belum','Sudah'));
        $crud->field_type("no_sertifikat", "integer");
        $crud->field_type("tahun_sertifikasi", "integer");
        $crud->unset_texteditor("alamat", 'full_text');
        $crud->field_type("alamat", "text");
        $crud->field_type("tanggal_lahir", "date");
        $crud->field_type("email", "string");
        $crud->field_type("nomor_hp", "string");
        $crud->set_field_upload("foto", 'assets/uploads');
        $crud->field_type("created_at", "datetime");
        $crud->field_type("updated_at", "datetime");
        $crud->field_type("deleted_at", "datetime");

        // set rules
        $crud->required_fields('nama_guru', 'jenis_kelamin', 'status_sertifikasi', 'alamat', 'tanggal_lahir');

        // Where
        $crud->where('customer_id', $additional_data->customer_id);

        // Relation n-n

        // Callback before Insert
        $crud->callback_after_insert(array($this, '_callback_insert_guru_quran'));
        $crud->callback_after_update(array($this, '_callback_insert_guru_quran'));
        $crud->callback_delete(array($this, '_callback_delete_guru_quran'));


        // Callback
        $crud->callback_column('foto', array($this->crud_model, '_callback_foto_guru_quran'));
        $crud->callback_column('nama_guru', array($this->crud_model, '_callback_nama_guru'));

        // Validation
        $crud->set_rules("foto", "Foto", "max_size[2MB]");

        // Display As

        // Unset action
        $crud->unset_action();

        $js = "";

        $output = $crud->render();
        $output->output .= $js;

        $data = (array) $output;

        $this->layout->set_wrapper('guru_quran', $data, 'page', false);

        $template_data['js_plugins'] = [
            base_url('assets/js/guruquran.js')
        ];

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "List Guru Quran";
        $template_data["crumb"] = ["Guru_Quran" => ""];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function _callback_delete_guru_quran($primary_key)
    {
        $this->db->where('id_guru_quran', $primary_key);
        $guru_quran = $this->db->get('guru_quran')->row();
        $customer_id = $guru_quran->customer_id;

        $kondisi_guru = $this->db->get_where('kondisi_guru', array('customer_id',$customer_id));

        if (!empty($kondisi_guru)) {
            $this->db->delete('kondisi_guru', array('customer_id' => $customer_id));
        }

        $this->db->delete('guru_quran', array('id_guru_quran' => $primary_key));

        $jumlah_guru = $this->db->query('SELECT COUNT(q.id_guru_quran) as jumlah_guru FROM guru_quran as q WHERE q.customer_id = '.$customer_id)->row();
        $sudah_sertifikasi = $this->db->query('SELECT COUNT(q.id_guru_quran) as jumlah_guru FROM guru_quran as q WHERE q.customer_id = '.$customer_id.' AND q.status_sertifikasi = 1')->row();
        $belum_sertifikasi = $this->db->query('SELECT COUNT(q.id_guru_quran) as jumlah_guru FROM guru_quran as q WHERE q.customer_id = '.$customer_id.' AND q.status_sertifikasi = 0')->row();


        $data = array(
            'customer_id' => $customer_id,
            'jumlah_guru' => $jumlah_guru->jumlah_guru,
            'sudah_sertifikasi' => $sudah_sertifikasi->jumlah_guru,
            'belum_sertifikasi' => $belum_sertifikasi->jumlah_guru
        );

        return $this->db->insert('kondisi_guru', $data);
        // redirect($customer_id);
    }

    public function _callback_insert_guru_quran($post_array)
    {
        $customer_id = $post_array['customer_id'];
        $this->db->where('customer_id', $customer_id);
        $kondisi_guru = $this->db->get('kondisi_guru')->row();

        if (!empty($kondisi_guru)) {
            $this->db->delete('kondisi_guru', array('customer_id' => $customer_id));
        }

        $jumlah_guru = $this->db->query('SELECT COUNT(q.id_guru_quran) as jumlah_guru FROM guru_quran as q WHERE q.customer_id = '.$customer_id)->row();
        $sudah_sertifikasi = $this->db->query('SELECT COUNT(q.id_guru_quran) as jumlah_guru FROM guru_quran as q WHERE q.customer_id = '.$customer_id.' AND q.status_sertifikasi = 1')->row();
        $belum_sertifikasi = $this->db->query('SELECT COUNT(q.id_guru_quran) as jumlah_guru FROM guru_quran as q WHERE q.customer_id = '.$customer_id.' AND q.status_sertifikasi = 0')->row();


        $data = array(
            'customer_id' => $customer_id,
            'jumlah_guru' => $jumlah_guru->jumlah_guru,
            'sudah_sertifikasi' => $sudah_sertifikasi->jumlah_guru,
            'belum_sertifikasi' => $belum_sertifikasi->jumlah_guru
        );

        $this->db->insert('kondisi_guru', $data);

        return $post_array;
    }
}

/* End of file example.php */
/* Location: ./application/modules/pengguna/controllers/Pengguna.php */

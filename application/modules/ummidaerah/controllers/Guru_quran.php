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

        $this->title = "List Lembaga";
        $this->load->model('crud_model');
    }

    public function index()
    {
        $detect = new Mobile_Detect();

        // $customer_id = $id;

        $crud = new grocery_CRUD();

        $state = $crud->getState();

        if ($state == 'list' || $state == 'ajax_list' || $state == 'ajax_list_info'|| $state == 'export' || $state == 'success') {
            $crud->set_table('view_guru_by_umda');
        } else {
            $crud->set_table("guru_quran");
        }

        if ($this->ion_auth->in_group('ummi_daerah')) {
            $user_info = $this->ion_auth->user()->row();
            $user_data = json_decode($user_info->additional)[0];
            $ummi_daerah_id = $user_data->ummi_daerah_id;
            $crud->where(array( 'id_ummi_daerah' => $ummi_daerah_id ));

            if ($this->uri->segment(4) == 'bersertifikat') {
                $crud->where('status_sertifikasi', 1);
                $crud->set_subject("Guru Quran Sudah Sertifikasi");
            } elseif ($this->uri->segment(4) == 'blmbersertifikat') {
                $crud->where('status_sertifikasi', 0);
                $crud->set_subject("Guru Quran Belum Sertifikasi");
            } else {
                $crud->set_subject("Guru Quran");
            }
        } else {
            $crud->set_subject("Guru Quran");
        }



        $crud->set_primary_key('id_guru_quran');

        // Show in
        $crud->fields(["customer_id", "nama_guru", "jenis_kelamin", "pendidikan_terakhir", "status_sertifikasi", "no_sertifikat", "tahun_sertifikasi", "alamat", "tanggal_lahir", "email", "nomor_hp", "foto"]);

        if ($detect->isMobile()) {
            $crud->columns(["foto","nama_guru"]);
            $crud->callback_column('nama_guru', array($this->crud_model, '_callback_nama_guru_mobile'));
            $crud->unset_delete();
            $crud->unset_export();
            $crud->unset_print();

        } else {
            if ($state == 'export') {
                $crud->columns(["nama", "nama_guru", "jenis_kelamin", "pendidikan_terakhir", "status_sertifikasi", "no_sertifikat", "tahun_sertifikasi", "alamat", "tanggal_lahir", "email", "nomor_hp"]);
            } else {
                $crud->columns(["foto","nama_guru","nama","status_sertifikasi", "nomor_hp"]);
                $crud->callback_column('nama_guru', array($this->crud_model, '_callback_nama_guru'));
                $crud->unset_print();
            }
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
        $crud->set_field_upload('foto', 'assets/uploads/image/guruquran');
        $crud->field_type("created_at", "datetime");
        $crud->field_type("updated_at", "datetime");
        $crud->field_type("deleted_at", "datetime");

        // set rules
        $crud->required_fields('nama_guru', 'jenis_kelamin', 'status_sertifikasi', 'alamat', 'tanggal_lahir');

        // Where
        // $crud->where('customer_id',$customer_id);

        // Relation n-n

        // Callback before Insert
        $crud->callback_after_insert(array($this->crud_model, '_callback_insert_guru_quran'));
        $crud->callback_after_update(array($this->crud_model, '_callback_insert_guru_quran'));
        $crud->callback_delete(array($this->crud_model, '_callback_delete_guru_quran'));


        // Callback
        $crud->callback_column('foto', array($this->crud_model, '_callback_foto_guru_quran'));
        $crud->callback_after_upload(array($this->crud_model, '_callback_foto_guru_upload'));


        // Validation

        // Display As
        $crud->display_as('nama', 'Nama Lembaga');

        // Unset action
        $crud->unset_action();
        $crud->unset_print();
        $crud->unset_add();

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
        $template_data["title"] = "List Guru Quran Lembaga";
        $template_data["crumb"] = [
          "ummi_daerah" => "ummidaerah/profile",
          "guru_quran" => "",
        ];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }
}

/* End of file example.php */
/* Location: ./application/modules/pengguna/controllers/Pengguna.php */

<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Pengguna Controller.
 */
class Pengguna extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "List Lembaga";
        $this->load->model('crud_model');
    }

    /**
     * CRUD
     */
    public function crud()
    {
        $detect = new Mobile_Detect();
        $crud = new grocery_CRUD();

        $state = $crud->getState();

        if ($state == 'list' || $state == 'ajax_list' || $state == 'ajax_list_info'|| $state == 'export' || $state == 'success') {
            $crud->set_table('view_pengguna');
        } else {
            $crud->set_table('pengguna');
        }

        $crud->set_primary_key('id_customer');


        // Show in
        $crud->fields(["nama", "jenis_lembaga_id", "alamat", "provinsi_id", "kabupaten_id", "kecamatan", "nomor_telp", "nomor_fax", "email", "perpekan", "perhari", "tanggal_mulai", "kepala_lembaga", "koordinator", "is_turjuman", "is_tahfidz", "is_dewasa","status_aktif"]);

        if ($detect->isMobile()) {
            if ($state == "list" || $state == 'ajax_list'|| $state == 'success') {
                // $crud->set_table('view_pengguna');
                $crud->columns(["no_register","nama"]);
                $crud->callback_column('nama', array($this->crud_model,'_callback_nama_is_mobile'));

                $js2 = '
            <script>$("td:nth-child(1),th:nth-child(1)").hide();
            $(document).ajaxComplete(function(){
            $("td:nth-child(1),th:nth-child(1)").hide();
            });

            </script>';
            }
        } else {
            if ($state == "list" || $state == 'ajax_list' || $state == 'success') {
                // $crud->set_table('view_pengguna');
                $crud->columns(["no_register","nama", "jenis_lembaga_id", "provinsi", "kabupaten",
            // "ummi_daerah",
            "status_aktif",'update_time']);
                $crud->callback_column('nama', array($this->crud_model,'_callback_nama'));
            } elseif ($state == 'export') {
                $crud->columns(['no_register',"nama", "jenis_lembaga_id", "alamat", "provinsi", "kabupaten", "kecamatan", "nomor_telp", "nomor_fax", "email", "perpekan", "perhari", "tanggal_mulai", "kepala_lembaga", "koordinator","jumlah_siswa", "jumlah_guru", "sudah_sertifikasi","belum_sertifikasi"]);
            } else {
                $crud->callback_column('nama', array($this->crud_model,'_callback_nama'));
            }

            $js2 = '
          <script>$("td:nth-child(5),th:nth-child(5)").hide();
          $("td:nth-child(6),th:nth-child(6)").hide();
          $("td:nth-child(4),th:nth-child(4)").hide();
          $(document).ajaxComplete(function(){
          $("td:nth-child(6),th:nth-child(6)").hide();
          $("td:nth-child(5),th:nth-child(5)").hide();
          $("td:nth-child(4),th:nth-child(4)").hide();
          });
          </script>';
        }


        // Fields type
        $crud->field_type("id_customer", "integer");
        $crud->field_type("nama", "string");
        $crud->unset_texteditor("alamat", 'full_text');
        $crud->field_type("alamat", "text");
        $crud->field_type("kecamatan", "string");
        $crud->field_type("nomor_telp", "string");
        $crud->field_type("nomor_fax", "string");
        $crud->field_type("email", "string");
        $crud->field_type("perpekan", "integer");
        $crud->field_type("perhari", "integer");
        $crud->field_type("tanggal_mulai", "date");
        $crud->field_type("kepala_lembaga", "string");
        $crud->field_type("koordinator", "string");
        if ($state == 'add' || $state == 'edit') {
            $crud->field_type("status_aktif", "true_false", array('inactive','active'));
        } else {
            $crud->field_type("status_aktif", "true_false", array('<i class="fa fa-times-circle-o" style="color:red"></i> inactive','<i class="fa fa-check-circle-o"></i> active'));
        }

        $crud->field_type("status", "string");
        $crud->field_type("is_turjuman", "true_false", array('Tidak', 'Ya'));
        $crud->field_type("is_tahfidz", "true_false", array('Tidak', 'Ya'));
        $crud->field_type("is_dewasa", "true_false", array('Tidak', 'Ya'));
        $crud->set_field_upload("logo", 'assets/uploads');

        // Relation n-n
        $crud->set_relation("jenis_lembaga_id", "jenis_lembaga", "jenis_lembaga");
        $crud->set_relation('provinsi_id', 'wilayah_provinsi', 'provinsi');
        $crud->set_relation('kabupaten_id', 'wilayah_kabupaten', 'kabupaten');

        // Validation
        $crud->set_rules("nama", "Nama", "required");
        $crud->set_rules("jenis_lembaga_id", "Jenis lembaga id", "required");
        $crud->set_rules("alamat", "Alamat", "required");
        $crud->set_rules("provinsi_id", "Provinsi id", "required");
        $crud->set_rules("kabupaten_id", "Kabupaten id", "required");
        $crud->set_rules("kecamatan", "Kecamatan", "required");
        $crud->set_rules("nomor_telp", "Nomor telp", "required");
        $crud->set_rules("email", "Email", "valid_email");
        $crud->set_rules("tanggal_mulai", "Tanggal mulai", "required");
        $crud->set_rules("perpekan", "Perpekan", "required");
        $crud->set_rules("perhari", "Perhari", "required");
        $crud->set_rules("kepala_lembaga", "Kepala lembaga", "required");
        $crud->set_rules("koordinator", "Koordinator", "required");

        // Display As
        $crud->display_as(array(
            'provinsi_id' => 'Provinsi',
            'kabupaten_id' => 'Kabupaten',
            'jenis_lembaga_id' => 'Jenis Lembaga',
            'status_aktif' => 'Aktif',
            'nama' => 'Lembaga'
        ));

        // Callback Insert (input customer Id)

        // Callback
        $crud->callback_column('update_time', array($this->crud_model,'_callback_update_time'));

        // where
        if ($this->uri->segment(4) == 'aktif') {
            $crud->where('status_aktif', 1);
            $crud->set_subject("Daftar Lembaga Aktif");
            $crud->unset_add();
        } elseif ($this->uri->segment(4) == 'nonaktif') {
            $crud->set_subject("Daftar Lembaga Non Aktif");
            $crud->where('status_aktif', 0);
            $crud->unset_add();
        } elseif ($this->uri->segment(4) == 'unregister') {
            $crud->where('no_register', null);
            $crud->set_subject("Calon Pengguna Ummi");

            $crud->callback_column('no_register', array($this->crud_model,'_callback_no_register_column'));
        } elseif ($this->uri->segment(4) == 'turjuman') {
            $crud->where('is_turjuman', 1);
            $crud->set_subject("Pengguna Turjuman");
            $crud->unset_add();
        } else {
            $crud->set_subject("Pengguna Ummi");
            // $crud->unset_add();
        }

        if ($this->uri->segment(4) == 'byumda') {
            $this->session->unset_userdata('provinsi_id');
            $ummi_daerah_id = $this->input->post('ummi_daerah');
            if (!empty($ummi_daerah_id)) {
                $crud->where('id_ummi_daerah', $ummi_daerah_id);
                $this->session->set_userdata(array('ummi_daerah_id' => $ummi_daerah_id));
            }
            $crud->unset_add();

        } elseif ($this->uri->segment(4) == 'byprovince') {
            $this->session->unset_userdata('ummi_daerah_id');
            $provinsi_id = $this->input->post('provinsi');
            if (!empty($provinsi_id)) {
                $crud->where('view_pengguna.provinsi_id', $provinsi_id);
                $this->session->set_userdata(array('provinsi_id' => $provinsi_id));
            }
            $crud->unset_add();

        }

        if(!empty($this->session->userdata('ummi_daerah_id'))){
          $crud->where('id_ummi_daerah', $this->session->userdata('ummi_daerah_id'));
        }
        if(!empty($this->session->userdata('provinsi_id'))){
          $crud->where('view_pengguna.provinsi_id', $this->session->userdata('provinsi_id'));
        }


        // Unset action
        $crud->unset_action();
        $crud->unset_print();
        // $crud->unset_delete();

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
            'main_table' => 'pengguna',
            'main_table_primary' => 'id_customer',
            'url' => base_url().'/ummidaerah/' . __CLASS__ . '/' . __FUNCTION__ . '/', //path to method
        );

        $wilayah = new gc_dependent_select($crud, $fields, $config);

        $js = $wilayah->get_js();

        $output = $crud->render();
        $output->output .= $js;
        $output->output .= $js2;

        $data = (array) $output;

        if ($this->uri->segment(4) == 'byumda') {
            $data['ummi_daerah_list'] = db_get_all_data('ummi_daerah');
            $data['ummi_daerah_id'] = $ummi_daerah_id;
            $this->layout->set_wrapper('pengguna_byumda', $data, 'page', false);
        } elseif ($this->uri->segment(4) == 'byprovince') {
            $data['provinsi_list'] = db_get_all_data('wilayah_provinsi');
            $data['provinsi_id'] = $provinsi_id;
            $this->layout->set_wrapper('pengguna_byprovince', $data, 'page', false);
        } else {
            // $data = array(
            //   'jumlah_lembaga'      => db_get_count('view_pengguna'),
            // );
            $data['jumlah_lembaga'] = db_get_count('view_pengguna');
            $data['lembaga_aktif']       = db_get_count('view_pengguna', array('status_aktif' => 1));
            $data['lembaga_not_aktif']   = db_get_count('view_pengguna', array('status_aktif' => 0));
            $data['lembaga_unregister']  = db_get_count('view_pengguna', array('no_register' => null));
            $this->layout->set_wrapper('crud', $data, 'page', false);
        }


        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Daftar Lembaga Pengguna Ummi";
        $template_data["crumb"] = [
          "ummi_daerah" => "",
          "lembaga"     => "",
        ];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }


    public function validate($id)
    {
        $this->db->where(array('id_customer' => $id ));
        $this->db->update('pengguna', array('status' => 1));
        redirect_referrer();
    }

    public function generate($id)
    {
        $row = db_get_row('pengguna', array('id_customer' => $id));
        $year = date('y', strtotime($row->tanggal_mulai));
        // $year = 2018;
        $check = db_get_count('register', array('year' => $year));

        if (empty($check)) {
            $no = '001';
        } else {
            $no = $check+1;
        }
        if (strlen($no) == 1) {
            $no = '00'.$no;
        }
        if (strlen($no) == 2) {
            $no = '0'.$no;
        } else {
            $no;
        }
        $jenis = $row->jenis_lembaga_id;
        if (strlen($jenis) == 1) {
            # code...
            $jenis = '0'.$jenis;
        } else {
            $jenis;
        }
        $pre =  $year.$jenis.$row->kabupaten_id;
        $no_register = $pre.$no;

        $insert = array(
      'customer_id' => $row->id_customer,
      'no_register' => $no_register,
      'year'        => $year
    );

        $this->db->insert('register', $insert);

        redirect_referrer();
    }


    public function profile($id)
    {
        $condition = array('id_customer' => $id);
        $data['profile_pengguna'] = db_get_row('view_pengguna', $condition);


        $this->layout->set_wrapper('profile_pengguna', $data, 'page', false);

        $template_data["title"] = "Profile Pengguna";
        $template_data["crumb"] = ["Profile" => ""];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function guru_quran($id)
    {
        $detect = new Mobile_Detect();

        $customer_id = $id;

        $crud = new grocery_CRUD();

        $crud->set_table("guru_quran");
        $crud->set_subject("Guru Quran");

        // Show in
        $crud->fields(["customer_id", "nama_guru", "jenis_kelamin", "pendidikan_terakhir", "status_sertifikasi", "no_sertifikat", "tahun_sertifikasi", "alamat", "tanggal_lahir", "email", "nomor_hp", "foto"]);

        if ($detect->isMobile()) {
            $crud->columns(["foto","nama_guru"]);
            $crud->unset_delete();
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
        $crud->where('customer_id', $customer_id);

        // Relation n-n

        // Callback before Insert
        $crud->callback_after_insert(array($this->crud_model, '_callback_insert_guru_quran'));
        $crud->callback_after_update(array($this->crud_model, '_callback_insert_guru_quran'));
        $crud->callback_delete(array($this->crud_model, '_callback_delete_guru_quran'));


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

        $this->layout->set_wrapper('iframe', $data, 'page', false);

        $template_data['js_plugins'] = [
            base_url('assets/js/guruquran.js')
        ];

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "List Guru Quran Lembaga";
        $template_data["crumb"] = ["Guru_Quran" => ""];
        $this->layout->auth();
        $this->layout->render('blank', $template_data); // front - auth - admin
    }
}

/* End of file example.php */
/* Location: ./application/modules/pengguna/controllers/Pengguna.php */

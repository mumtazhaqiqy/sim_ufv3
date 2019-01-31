<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Ummidaerah Controller.
 */
class Ummidaerah extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Ummi Daerah";
        // $this->load->model('crud_model');
    }

    /**
     * Index
     */
    public function menu()
    {
        // $this->layout->set_wrapper( 'crud', $data,'page', false);

        $template_data["title"] = "Ummi Daerah";
        $template_data["crumb"] = ["table" => ""];
        $this->layout->auth();
        $this->layout->render('admin', $template_data);
    }

    public function crud()
    {
        $crud = new grocery_CRUD();

        $state = $crud->getState();

        if ($state == 'list' || $state == 'ajax_list' || $state == 'ajax_list_info'|| $state == 'export') {
            if (!$this->ion_auth->in_group(array('admin','admin_data'))) {
                redirect('myigniter/profile');
            }
        }

        $crud->set_table("ummi_daerah");
        $crud->set_subject("Ummi Daerah");

        // Show in
        $crud->add_fields(["sk_umda", "ummi_daerah", "alamat_lengkap", "provinsi_id", "kabupaten_id", "kecamatan", "nomor_telp", "email", "ketua_umda", "hp_ketua_umda", "manager_buku", "hp_manager_buku", "admin", "hp_admin", "status_aktif"]);
        $crud->edit_fields(["sk_umda", "ummi_daerah", "alamat_lengkap", "provinsi_id", "kabupaten_id", "kecamatan", "nomor_telp", "email", "ketua_umda", "hp_ketua_umda", "manager_buku", "hp_manager_buku", "admin", "hp_admin", "status_aktif"]);
        $crud->columns(["sk_umda", "ummi_daerah", "alamat_lengkap", "provinsi_id", "kabupaten_id", "kecamatan", "nomor_telp", "email", "ketua_umda", "hp_ketua_umda", "manager_buku", "hp_manager_buku", "admin", "hp_admin", "status_aktif"]);

        // Fields type
        $crud->field_type("id_ummi_daerah", "integer");
        $crud->field_type("sk_umda", "string");
        $crud->field_type("ummi_daerah", "string");
        $crud->field_type("alamat_lengkap", "string");
        $crud->field_type("kecamatan", "string");
        $crud->field_type("nomor_telp", "string");
        $crud->field_type("email", "string");
        $crud->field_type("ketua_umda", "string");
        $crud->field_type("hp_ketua_umda", "string");
        $crud->field_type("manager_buku", "string");
        $crud->field_type("hp_manager_buku", "string");
        $crud->field_type("admin", "string");
        $crud->field_type("hp_admin", "string");
        $crud->field_type("status_aktif", "true_false");
        $crud->field_type("created_at", "datetime");
        $crud->field_type("updated_at", "datetime");
        $crud->field_type("deleted_at", "datetime");

        // Relation n-n
        $crud->set_relation('provinsi_id', 'wilayah_provinsi', 'provinsi');
        $crud->set_relation('kabupaten_id', 'wilayah_kabupaten', 'kabupaten');

        // Validation
        $crud->set_rules("sk_umda", "Sk umda", "required");
        $crud->set_rules("ummi_daerah", "Ummi daerah", "required");
        $crud->set_rules("alamat_lengkap", "Alamat lengkap", "required");
        $crud->set_rules("provinsi_id", "Provinsi id", "required");
        $crud->set_rules("kabupaten_id", "Kabupaten id", "required");
        $crud->set_rules("kecamatan", "Kecamatan", "required");
        $crud->set_rules("nomor_telp", "Nomor telp", "required");
        $crud->set_rules("email", "Email", "required");
        $crud->set_rules("ketua_umda", "Ketua umda", "required");
        $crud->set_rules("hp_ketua_umda", "Hp ketua umda", "required");
        $crud->set_rules("manager_buku", "Manager buku", "required");
        $crud->set_rules("hp_manager_buku", "Hp manager buku", "required");
        $crud->set_rules("admin", "Admin", "required");
        $crud->set_rules("hp_admin", "Hp admin", "required");
        $crud->set_rules("status_aktif", "Status aktif", "required");

        // Display As
        $crud->display_as("provinsi_id", "Provinsi");
        $crud->display_as("kabupaten_id", "Kabupaten");
        $crud->display_as("hp_ketua_umda", "No HP");
        $crud->display_as("hp_manager_buku", "No HP");
        $crud->display_as("hp_admin", "No HP");

        // Unset action
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

        $js2 = '
        <script>$("td:nth-child(3),th:nth-child(3)").hide();
        $("td:nth-child(5),th:nth-child(5)").hide();
        $("td:nth-child(4),th:nth-child(4)").hide();
        $(document).ajaxComplete(function(){
            $("td:nth-child(3),th:nth-child(3)").hide();
            $("td:nth-child(5),th:nth-child(5)").hide();
            $("td:nth-child(4),th:nth-child(4)").hide();

        });</script>';

        $output = $crud->render();
        $output->output .= $js;
        // $output->output .= $js2;

        $data = (array) $output;

        $this->layout->set_wrapper('grocery', $data, 'page', false);

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Ummi Daerah";
        $template_data["crumb"] = ["Ummi Daerah" => "" , "Daftar" => ""];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }
}

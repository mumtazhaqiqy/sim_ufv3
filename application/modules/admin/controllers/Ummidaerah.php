<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Ummidaerah Controller.
 */
class Ummidaerah extends MY_Controller
{
    public function index($value='')
    {
        // code...
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
        $crud->columns(["ummi_daerah"]);


        $crud->callback_column('ummi_daerah', array($this,'_callback_ummi_daerah_column'));

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
        $crud->unset_action();
        $crud->unset_export();
        $crud->unset_print();

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

    public function area()
    {
        $crud = new grocery_CRUD();

        $crud->set_table("ummi_daerah_area");
        $crud->set_subject("");

        // Show in
        $crud->add_fields(["ummi_daerah_id", "provinsi_id", "kabupaten_id"]);
        $crud->edit_fields(["ummi_daerah_id", "provinsi_id", "kabupaten_id"]);
        $crud->columns(["ummi_daerah_id", "provinsi_id", "kabupaten_id"]);

        // Fields type
        $crud->field_type("id", "integer");
        $crud->set_relation("ummi_daerah_id", "ummi_daerah", "ummi_daerah");

        // Relation n-n
        $crud->set_relation('provinsi_id', 'wilayah_provinsi', 'provinsi');
        $crud->set_relation('kabupaten_id', 'wilayah_kabupaten', 'kabupaten');


        // Validation
        $crud->set_rules("ummi_daerah_id", "Ummi daerah id", "required");

        // Display As

        // Unset action
        $crud->unset_read();


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
            'main_table' => 'ummi_daerah_area',
            'main_table_primary' => 'id',
            'url' => base_url().'admin/' . __CLASS__ . '/' . __FUNCTION__ . '/', //path to method
        );

        $wilayah = new gc_dependent_select($crud, $fields, $config);

        $js = $wilayah->get_js();

        $output = $crud->render();
        $output->output .= $js;

        $data = (array) $output;

        $this->layout->set_wrapper('grocery', $data, 'page', false);

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Ummi Daerah Area";
        $template_data["crumb"] = [];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function _callback_ummi_daerah_column($value, $row)
    {
        $kondisi = db_get_row('view_kondisi_umda', 'ummi_daerah_id = '.$row->id_ummi_daerah);

        $count_last_update = db_count_update($row->id_ummi_daerah);
        $percent = ($count_last_update / $kondisi->jumlah_lembaga)*100;

        $html = '
            <div class="flex">
                <div class="flex-1 pr-4">
                    <a target="_parent" class="text-3xl font-bold text-green-light hover:text-red hover:font-extrabold" href="'.base_url('ummidaerah/profile/?ummidaerah=').$row->id_ummi_daerah.'">'.$value.' '.round($percent).'%</a>

                    <p class="">'.$row->alamat_lengkap.'</p>
                    <div data-toggle="tooltip" title="prosentase : '.round($percent).'% | '.$count_last_update.' sudah update dari total jumlah lembaga" class="progress sm mt-4 mb-2">
                        <div class="progress-bar progress-bar-aqua" style="width:'.$percent.'%"></div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex">
                        <div class="flex-1 p-2">
                            <div class="p-4 bg-white shadow">
                                <p class="text-blue-light text-center font-bold text-3xl">'.$kondisi->jumlah_lembaga.'</p>
                                <p class="text-center text-grey-darker">Lembaga</p>
                            </diV>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="p-4 bg-white shadow">
                                <p class="text-blue-light text-center font-bold text-3xl">'.$kondisi->jumlah_siswa.'</p>
                                <p class="text-center text-grey-darker">Santri</p>
                            </diV>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="p-4 bg-white shadow">
                                <p class="text-blue-light text-center font-bold text-3xl">'.$kondisi->jumlah_guru.'</p>
                                <p class="text-center text-grey-darker">Guru Quran</p>
                            </diV>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="p-4 bg-white shadow">
                                <p class="text-blue-light text-center font-bold text-3xl">'.$kondisi->sdh_sertifikasi.' : '.$kondisi->blm_sertifikasi.'</p>
                                <p class="text-center text-grey-darker">Sertifikasi</p>
                            </diV>
                        </div>

                    </div>
                </div>
            </div>
        ';

        return $html;
    }

    public function _callback_status_column($value, $row)
    {
    }
}

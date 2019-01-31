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

		$this->title = "pengguna";
		$this->load->model('crud_model');
    }

    /**
     * Index
     */
    public function generate_user()
    {

      $no_register = db_get_all_data('register');

      foreach ($no_register as $n)
      {
        $pengguna = db_get_row('pengguna', array('id_customer' => $n->customer_id));

        $username = $n->no_register;
    		$password = 'bismillah';
    		$email = $username.'@uf.app';
    		$additional_data = array(
    					'customer_id' => $n->customer_id,
              'no_register' => $n->no_register
    					);
    		$group = array('3');

    		$this->ion_auth->register($username, $password, $email, $additional_data, $group);

    		$update_data = array(
          'additional' => '['.json_encode($additional_data).']',
          'full_name' => $pengguna->nama
        );
        
        $this->db->where('email',$email);
        $this->db->update('users',$update_data);



      }

    }

    /**
     * CRUD
     */
	public function crud()
	{

		$crud = new grocery_CRUD();

		$state = $crud->getState();

		if($state == 'list' || $state == 'ajax_list' || $state == 'ajax_list_info'|| $state == 'export')
		{
			$crud->set_table('view_pengguna');
		} else {
			$crud->set_table('pengguna');
		}

		if($this->ion_auth->in_group('pengguna_ummi'))
		{
			$user_info = $this->ion_auth->user()->row();
			$user_data = json_decode($user_info->additional)[0];
			$customer_id = $user_data->customer_id;

			if($state == 'edit'){
				$segment = $this->uri->segment(4);

				if($customer_id != $segment)
				{
					redirect('pengguna/profile');
				}
			} elseif($state == 'list' || $state == 'success') {
				redirect('pengguna/profile');
			}
		}


		$crud->set_primary_key('id_customer');
		$crud->set_subject("pengguna");

		// Show in
		$crud->fields(["nama", "jenis_lembaga_id", "alamat", "provinsi_id", "kabupaten_id", "kecamatan", "nomor_telp", "nomor_fax", "email", "perpekan", "perhari", "tanggal_mulai", "kepala_lembaga", "koordinator", "is_turjuman", "is_tahfidz", "is_dewasa", "status_aktif"]);

		if($state == "list" || $state == 'ajax_list'){
			// $crud->set_table('view_pengguna');
			$crud->columns(["no_register","nama", "jenis_lembaga_id", "provinsi", "kabupaten", "status_aktif",'update_time']);
		} elseif ($state == 'export'){
			$crud->columns(['no_register',"nama", "jenis_lembaga_id", "alamat", "provinsi", "kabupaten", "kecamatan", "nomor_telp", "nomor_fax", "email", "perpekan", "perhari", "tanggal_mulai", "kepala_lembaga", "koordinator"]);
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
		$crud->field_type("status_aktif", "true_false", array('<i class="fa fa-times-circle-o" style="color:red"></i> inactive','<i class="fa fa-check-circle-o"></i> active'));
		$crud->field_type("status", "string");
		$crud->field_type("is_turjuman", "true_false", array('Tidak', 'Ya'));
		$crud->field_type("is_tahfidz", "true_false", array('Tidak', 'Ya'));
		$crud->field_type("is_dewasa", "true_false", array('Tidak', 'Ya'));
		$crud->set_field_upload("logo", 'assets/uploads');

		// Relation n-n
		$crud->set_relation("jenis_lembaga_id", "jenis_lembaga", "jenis_lembaga");
		$crud->set_relation('provinsi_id','wilayah_provinsi','provinsi');
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
		$crud->set_rules("kepala_lembaga", "Kepala lembaga", "required");
		$crud->set_rules("koordinator", "Koordinator", "required");

		// Display As
		$crud->display_as(array(
			'provinsi_id' => 'Provinsi',
			'kabupaten_id' => 'Kabupaten',
			'jenis_lembaga_id' => 'Jenis Lembaga',
			'status_aktif' => 'Aktif'
		));

		// Callback Insert (input customer Id)

		// Callback
		$crud->callback_column('nama',array($this->crud_model,'_callback_nama'));
		$crud->callback_column('update_time',array($this->crud_model,'_callback_update_time'));

		// Unset action
		$crud->unset_action();
		$crud->unset_delete();
		$crud->unset_add();

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
            'url' => base_url().'/pengguna/' . __CLASS__ . '/' . __FUNCTION__ . '/', //path to method
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
		$output->output .= $js2;

		$data = (array) $output;

		$this->layout->set_wrapper( 'crud', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "pengguna";
		$template_data["crumb"] = ["table" => ""];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}





}

/* End of file example.php */
/* Location: ./application/modules/pengguna/controllers/Pengguna.php */

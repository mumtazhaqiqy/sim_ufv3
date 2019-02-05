<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Trainer Controller.
 */
class Trainer extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Trainer";
    }

    /**
     * Index
     */
    public function index()
    {
        redirect('trainer/list_trainer');
    }

    /**
     * CRUD
     */
    public function list_trainer()
    {
        $crud = new grocery_CRUD();

        $crud->set_table("trainer");
        $crud->set_subject("Trainer");

        $state = $crud->getState();

        $umda_list = db_get_all_data('ummi_daerah');
        foreach ($umda_list as $key => $value) {
            $umda_dropdown[$key+1] = $value->ummi_daerah;
        }

        if ($this->ion_auth->in_group('ummi_daerah')) {
            $user_info = $this->ion_auth->user()->row();
            $additional_data = json_decode($user_info->additional)[0];

            $ummi_daerah_id = $additional_data->ummi_daerah_id;

            $crud->fields(["nama_trainer", "ummi_daerah_id", "alamat_lengkap", "no_hp", "email", "tanggal_lahir" , "sk_trainer", "pendidikan_terakhir", "hafal_quran", "jumlah_hafalan", "status_trainer", "keahlian", 'foto']);

            $crud->field_type("ummi_daerah_id", "hidden", $ummi_daerah_id);
            $crud->where('ummi_daerah_id', $ummi_daerah_id);
        } else {
            $crud->fields(["nama_trainer", "ummi_daerah_id", "alamat_lengkap", "no_hp", "email", "tanggal_lahir" , "sk_trainer", "pendidikan_terakhir", "hafal_quran", "jumlah_hafalan", "status_trainer", "keahlian"]);
            $crud->field_type("ummi_daerah_id", "dropdown", $umda_dropdown);
        }

        // Show in
        $crud->columns(["nama_trainer", "no_hp", "status_trainer","keahlian"]);


        // Fields type
        $crud->field_type("id_trainer", "integer");
        $crud->field_type("nama_trainer", "string");
        $crud->unset_texteditor("alamat_lengkap", 'full_text');
        $crud->field_type("alamat_lengkap", "text");
        $crud->field_type("no_hp", "string");
        $crud->field_type("email", "string");
        $crud->field_type("sk_trainer", "string");
        $crud->field_type("pendidikan_terakhir", "dropdown", array(
            '1' => 'SD/MI/Sederajat',
            '2' => 'SMP/MTS/Sederajat',
            '3' => 'SMA/MA/Sederajat',
            '4' => 'Diploma',
            '5' => 'S1/Sederajat',
            '6' => 'S2/Sederajat',
            '7' => '> S2',
        ));
        $crud->field_type("hafal_quran", "true_false", array('tidak', 'ya'));
        $crud->field_type("jumlah_hafalan", "integer");
        $crud->field_type("status_trainer", "dropdown", array(
            '1' => 'Master Trainer',
            '2' => 'Trainer Ahli',
            '3' => 'Trainer Utama',
            '4' => 'Trainer Inti',
            '5' => 'Trainer Pemula',
            '6' => 'Trainer Baru'
        ));
        $crud->set_field_upload('foto', 'assets/uploads/image/trainer');
        $crud->unset_texteditor("keahlian", 'full_text');
        $crud->field_type("keahlian", "text");
        $crud->field_type("created_aat", "datetime");

        // Relation n-n
        $crud->set_relation_n_n('keahlian', 'trainer_keahlian', 'keahlian', 'trainer_id', 'keahlian_id', 'keahlian');

        // Callback Column
        $crud->callback_column('nama_trainer', array($this, '_callback_nama_trainer'));

        $crud->callback_field('sk_trainer', array($this, '_callback_sk_trainer_field'));
        $crud->callback_before_insert(array($this, '_callback_before_insert_trainer'));
        $crud->callback_before_update(array($this, '_callback_before_insert_trainer'));

        $crud->callback_after_insert(array($this, '_callback_after_insert_trainer'));

        $crud->callback_after_upload(array($this, '_callback_foto_upload'));

        // Display As

        // Unset action
        // $crud->unset_action();
        // $crud->unset_export();
        $crud->unset_print();

        $data = (array) $crud->render();

        $this->layout->set_wrapper('crud', $data, 'page', false);

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Trainer";
        $template_data["crumb"] = [
          "Trainer" => "",
          "list_trainer" => ""
        ];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    /**
     * Return form.
     *
     * @return HTML
     **/
    public function _callback_sk_trainer_field($value = '', $primary_key = null)
    {
        $crumbContent = '<div class="sk_trainer_content">';
        $listPm = '';
        $endPmContent = '</div><button type="button" class="btn btn-default btn-sm btn-block btn-flat" id="addSkTrainer"><i class="fa fa-plus-circle"></i> Tambah</button>';

        if ($value != 'NULL' && $value != '') {
            $decodePm = json_decode($value);
            if ($decodePm) {
                foreach ($decodePm as $value) {
                    $listPm .= $this->sk_trainer_form($value->pm_nomor, $value->pm_tahun);
                }
            } else {
                $listPm = $this->sk_trainer_form('', '');
            }
        } else {
            $listPm .= $this->sk_trainer_form();
        }

        return $crumbContent.$listPm.$endPmContent;
    }

    public function _callback_before_insert_trainer($post_array)
    {
        // pengalaman mengajar
        foreach ($post_array['pm_nomor'] as $key => $value) {
            if ($value != '') {
                $link = $post_array['pm_tahun'][$key] != '' ? $post_array['pm_tahun'][$key] : '';
                $pmArray[] = ['pm_nomor' => $value, 'pm_tahun' => $link];
            }
        }
        $post_array['sk_trainer'] = json_encode($pmArray);

        return $post_array;
    }

    public function _callback_after_insert_trainer($post_array, $primary_key)
    {
        $date = $post_array['tanggal_lahir'];

        $date2 = strtotime(str_replace("/", "-", $date));

        $code = date("dm", $date2);

        $username = substr(strtolower(trim($post_array['nama_trainer'])), 0, 5).$code;
        $password = 'bismillah';
        $email = $username.'@uf.app';
        $additional_data = array(
                    'trainer_id' => $primary_key
                    );
        $group = array('5');

        $this->ion_auth->register($username, $password, $email, $additional_data, $group);

        $update_data = array(
      'additional' => '['.json_encode($additional_data).']',
      'full_name' => $post_array['nama_trainer']
    );
        $this->db->where('email', $email);
        $this->db->update('users', $update_data);

        return $post_array;
    }

    /**
     * Return input breadcrumb.
     *
     * @return HTML
     **/
    public function sk_trainer_form($val_nomor = '', $val_tahun = '')
    {
        $pmItem = '<div class="row form-add-sk-trainer">
			<div class="col-xs-8">
				<input type="text" name="pm_nomor[]" value="'.$val_nomor.'" id="inputLabel" class="form-control" placeholder="Nomor SK">
				<br>
			</div>
			<div class="col-xs-2">
				<input type="text" name="pm_tahun[]" value="'.$val_tahun.'" id="inputLabel" class="form-control" placeholder="tahun">
				<br>
			</div>
			<div class="col-xs-2">
				<button type="button" class="remove-pm btn btn-danger btn-flat">
					<i class="fa fa-times-circle"></i>
				</button>
				<br>
			</div>
		</div>';
        return $pmItem;
    }


    public function _callback_nama_trainer($value, $row)
    {
        $html = '
			<div>
				<span><b>'.$value.'</b></span><br>
				'.$row->alamat_lengkap.'
			</div>
		';

        return $html;
    }

    /**
     * Avatar upload compress.
     *
     * @return Image
     **/
    public function _callback_foto_upload($uploader_response, $field_info, $files_to_upload)
    {
        $this->load->library('image_moo');
        $file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name;

        $this->image_moo->load($file_uploaded)->resize_crop(400, 600)->save($file_uploaded, true);

        return true;
    }
}

/* End of file example.php */
/* Location: ./application/modules/trainer/controllers/Trainer.php */

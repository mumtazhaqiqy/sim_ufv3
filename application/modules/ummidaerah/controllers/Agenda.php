<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Agenda Controller.
 */
class Agenda extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->title = "Agenda Ummi Daerah";

        if (!$this->ion_auth->in_group(array('admin','ummi_daerah'))) {
            redirect('myigniter/profile');
        }

        $crud = new grocery_CRUD();

        $crud->set_table("agenda_umda");
        $crud->set_subject("Agenda UMDA");

        // Show in
        $crud->fields(["ummi_daerah_id","jenis_kegiatan","tanggal_mulai", "tanggal_usai", "lembaga", "trainer", "catatan","status"]);
        $crud->columns(["tanggal_mulai", "tanggal_usai", "jenis_kegiatan", "lembaga", "trainer"]);

        // Fields type
        $crud->field_type("id", "integer");
        $crud->field_type("ummi_daerah_id", "integer");
        $crud->set_relation("jenis_kegiatan", "jenis_kegiatan_umda", "jenis_kegiatan");
        $crud->field_type("tanggal_mulai", "date");
        $crud->field_type("tanggal_usai", "date");
        $crud->field_type("user_id", "integer");
        $crud->field_type("created_at", "datetime");

        if ($this->ion_auth->in_group('ummi_daerah')) {
            $user_info = $this->ion_auth->user()->row();
            $user_data = json_decode($user_info->additional)[0];
            $ummi_daerah_id = $user_data->ummi_daerah_id;
            $crud->where(array( 'ummi_daerah_id' => $ummi_daerah_id ));
        }

        $crud->field_type("ummi_daerah_id", "hidden", $ummi_daerah_id);
        $list = [];
        $tableList = $this->db->get_where("view_pengguna", array('id_ummi_daerah' => $ummi_daerah_id))->result_array();
        foreach ($tableList as $listMulty) {
            $list[$listMulty["id_customer"]] = $listMulty["nama"];
        }
        $crud->field_type("lembaga", "multiselect", $list);
        $list = [];
        // $this->db->where('ummi_daerah_id', $ummi_daerah_id);
        $tableList = $this->db->get_where("trainer", array('ummi_daerah_id' => $ummi_daerah_id))->result_array();
        foreach ($tableList as $listMulty) {
            $list[$listMulty["id_trainer"]] = $listMulty["nama_panggilan"];
        }
        $crud->field_type("trainer", "multiselect", $list);
        // Relation n-n

        // Validation
        $crud->set_rules("jenis_kegiatan", "Jenis kegiatan", "required");
        $crud->set_rules("tanggal_mulai", "Tanggal mulai", "required");
        $crud->set_rules("tanggal_usai", "Tanggal usai", "required");

        $crud->callback_column('trainer', array($this,'_callback_trainer_column'));
        $crud->callback_column('lembaga', array($this,'_callback_lembaga_column'));

        // Display As
        $crud->display_as('tanggal_mulai', 'Mulai');
        $crud->display_as('tanggal_usai', 'Usai');

        if ($this->input->post('date')) {
            if ($this->input->post('date') !== 'all') {
                $date = $this->input->post('date');
                $crud->where(array('month(tanggal_mulai)' => $date));
            } else {
                $date = 0;
            }
        } else {
            $date = date('m', strtotime(date('now')));
            $crud->where(array('month(tanggal_mulai)' => $date));
        }

        // Callback
        $crud->callback_after_insert(array($this, '_callback_after_insert'));
        $crud->callback_after_update(array($this, '_callback_after_update'));
        $crud->callback_after_delete(array($this, '_callback_after_delete'));

        // Unset action
        $crud->unset_read();
        // $crud->unset_export();
        $crud->unset_print();

        $data = (array) $crud->render();
        $data['date'] = $date;

        $this->layout->set_wrapper('agenda', $data, 'page', false);

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js']  = $data['js_files'];
        $template_data["title"] = "Agenda";
        $template_data["crumb"] = ["Agenda" => ""];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin
    }

    public function calendar()
    {
      $caledar = array(
        'title' => 'Sertifikasi',
        'start' => '2019-01-01',
        'end'   => '2019-01-02'
      );


      $this->layout->set_wrapper('calendar', $data, 'page', false);


      $template_data["title"] = "Cooming Soon (Kalender UMDA)";
      $template_data["crumb"] = ["Agenda" => ""];
      $this->layout->auth();
      $this->layout->render('admin', $template_data); // front - auth - admin
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

    public function _callback_lembaga_column($value, $row)
    {
        $array = explode(',', $value);
        foreach ($array as $val) {
            $lembaga = db_get_row('pengguna', array('id_customer' => $val))->nama;
            $html .= '<div>'.$lembaga.'</div>';
        }
        return $html;
    }

    // Tambahkan riwayat kegiatan pada trainer
    public function _callback_after_insert()
    {
    }

    public function _callback_after_update()
    {
    }

    public function _callback_after_delete()
    {
    }
}

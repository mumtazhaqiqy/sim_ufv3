<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Profile Controller.
 */
class Profile extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

		$this->title = "Profil Ummi Daerah";
        $this->load->model('Ummi_daerah_model','ummi_daerah');
		// $this->load->model('crud_model');
    }

    /**
     * Index
     */
    public function index()
    {
    if(empty($this->input->get('ummidaerah'))){
      $user_info = $this->ion_auth->user()->row();
      $additional_data = json_decode($user_info->additional)[0];
      $ummi_daerah_id = $additional_data->ummi_daerah_id;
    } else {
      $ummi_daerah_id = $this->input->get('ummidaerah');
      
    }

    $data = array (
      'j_trainer' => $this->ummi_daerah->j_trainer($ummi_daerah_id),
      'j_lembaga' => $this->ummi_daerah->kondisi_lembaga($ummi_daerah_id)->jumlah_lembaga,
      'j_santri' => $this->ummi_daerah->kondisi_lembaga($ummi_daerah_id)->jumlah_siswa,
      'j_guru'  => $this->ummi_daerah->kondisi_guru($ummi_daerah_id)->jml_guru,
      'j_guru_ss' => $this->ummi_daerah->kondisi_guru($ummi_daerah_id)->sdh_sertifikasi,
      'j_guru_bs' => $this->ummi_daerah->kondisi_guru($ummi_daerah_id)->blm_sertifikasi,
      'j_lembaga_su' => $this->ummi_daerah->sudah_update($ummi_daerah_id),
      'j_lembaga_bu' => $j_lembaga_bu,
      'ummi_daerah' => $this->ummi_daerah->ummi_daerah($ummi_daerah_id),
      'lembaga_by_jenis' => $this->ummi_daerah->lembaga_by_jenis($ummi_daerah_id)
    );



		$condition = array('id_ummi_daerah' => $ummi_daerah_id);

		$this->layout->set_wrapper( 'profile', $data, 'page', false);

    $template_data["title"] = "Profil Ummi Daerah";
		$template_data["crumb"] = ["Ummidaerah" => "ummidaerah/menu", "Profile" => ""];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
    }
}

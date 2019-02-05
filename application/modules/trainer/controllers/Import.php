<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Import Controller.
 */
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Import extends MY_Controller
{
    private $title;
    private $filename = 'import_file';

    public function __construct()
    {
        parent::__construct();

        $this->title = "Import Trainer";
        $this->load->model('ImportModel');
    }

    public function form()
    {

        if(isset($_POST['preview']))
        {
            $upload = $this->ImportModel->upload_file($this->filename);

            if($upload['result'] == 'success')
            {

                include APPPATH.'third_party/PHPExcel/PHPExcel.php';

                $excelreader = new PHPExcel_Reader_Excel2007();
                $loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang tadi diupload ke folder excel
                $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);

                $data['sheet'] = $sheet;
                // echo "<pre>";
                // print_r($data);
                // $this->message->custom_success_msg('trainer/import/form','Berhasil');
            } else {
                $this->message->custom_error_msg('trainer/import/form','Error: Extensi file yang diijinkan hanya .xlsx cek file anda');
            }

        }

        $this->layout->set_wrapper('import_form', $data, 'page', false);

        $template_data["title"] = "Import Trainer";
        $template_data["crumb"] = [
          "Trainer" => "",
          "Import" => ""
        ];
        $this->layout->auth();
        $this->layout->render('admin', $template_data); // front - auth - admin

    }

    public function save()
    {
        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang telah diupload ke folder excel
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);

        // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
        $data = array();

        $numrow = 1;

        foreach ($sheet as $row) {
          // code...
          if ($numrow > 1) {
              // get data from row
              $data = array(
                'ummi_daerah_id' => $row['A'],
                'nama_trainer' => $row['B'] ,
                'nama_panggilan' => $row['C'] ,
                'alamat_lengkap' => $row['D'] ,
                'no_hp' => $row['E'] ,
                'email' =>  $row['F'],
                'tanggal_lahir' =>  date('Y-m-d',strtotime($row['G']))
              );


              // input to trainer table
              $this->db->insert('trainer', $data);

              // buat user baru dengan nama trainer
              $code = date("dm", strtotime($row['G']));
              $username = substr(strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $row['B'])), 0, 5).$code;
              $password = 'bismillah';
              $email = $username.'@uf.app';
              $additional_data = array(
              'trainer_id' => $primary_key
              );
              $group = array('5');

              $this->ion_auth->register($username, $password, $email, $additional_data, $group);

              $update_data = array(
                'additional' => '['.json_encode($additional_data).']',
                'full_name' => $row['B']
              );
              $this->db->where('email', $email);
              $this->db->update('users', $update_data);

              // notification TAMBAH_PENGGUNA dan IMPORT_TRAINE BARU

          }

          $numrow++;
        }

        redirect('trainer');
    }

}

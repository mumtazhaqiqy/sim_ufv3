<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Generate Controller.
 */
class Generate extends MY_Controller
{
    public function user_ummi_daerah()
    {
        $ummi_daerah = db_get_all_data('ummi_daerah');
        foreach ($ummi_daerah as $key => $ud) {
            // code...
            $ummi_daerah_nama = preg_replace("/[^A-Za-z0-9]/", "", $ud->ummi_daerah);

            $username = strtolower($ummi_daerah_nama);
            $password = 'bismillah';
            $email = strtolower($ummi_daerah_nama).'@ufcore.app';

            $additional_data = array(
        'ummi_daerah_id' => $ud->id_ummi_daerah
      );
            $group = array('4');

            $this->ion_auth->register($username, $password, $email, $additional_data, $group);

            $update_data = array(
        'additional' => '['.json_encode($additional_data).']',
        'full_name' => $ud->ummi_daerah
      );

            $this->db->where('email', $email);
            $this->db->update('users', $update_data);

            echo $ud->ummi_daerah;
        }
    }

    public function user_pengguna()
    {
        // code...
        $register = db_get_all_data('register');

        foreach ($register as $key => $r) {
            // code...
            $pengguna = db_get_row('pengguna', array('id_customer' => $r->customer_id));

            $username = $r->no_register;
            $password = 'bismillah';
            $email = $username.'@ufcore.app';

            $additional_data = array(
        'customer_id' => $r->customer_id,
        'no_register' => $r->no_register
      );
            $group = array('3');

            $this->ion_auth->register($username, $password, $email, $additional_data, $group);

            $update_data = array(
        'additional' => '['.json_encode($additional_data).']',
        'full_name' => $pengguna->nama
      );

            $this->db->where('email', $email);
            $this->db->update('users', $update_data);

            echo $pengguna->nama.'<br>';
        }
    }

    public function by_register($value)
    {
        // code...
        $count = db_get_count('register', array('no_register' => $value));
        $register = db_get_row('register', array('no_register' => $value));

        if ($count == 1) {
            $pengguna = db_get_row('pengguna', array('id_customer' => $r->customer_id));

            $username = $value;
            $password = 'bismillah';
            $email = $username.'@ufcore.app';

            $additional_data = array(
        'customer_id' => $register->customer_id,
        'no_register' => $register->no_register
      );
            $group = array('3');

            if ($this->ion_auth->username_check($username)) {
                redirect();
            } else {
                $this->ion_auth->register($username, $password, $email, $additional_data, $group);

                $update_data = array(
          'additional' => '['.json_encode($additional_data).']',
          'full_name' => $pengguna->nama
        );

                $this->db->where('email', $email);
                $this->db->update('users', $update_data);
            }

            redirect_back();
        }
    }

    public function kondisi_guru()
    {

      $customer = $this->db->query('select distinct guru_quran.customer_id from guru_quran')->result();

      foreach ($customer as $key => $c) {
        // code...
        $customer_id = $c->customer_id;

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
      }

      // print_r($customer);
    }
}

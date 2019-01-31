<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Smart model.
 */
class Crud_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('mobile_detect');
    }

    public function _callback_nama($value, $row)
    {
        $html = '

            <div><span><strong><a href="'. base_url().'ummidaerah/pengguna/profile/'.$row->id_customer.'">'.$value.'</a></strong></span>
            <span style="font-size: 12px;font-weight: 600;color: #f39c12;margin-left: 10px;">'.$row->jenis_lembaga.'</div>
            <div>'.substr($row->alamat,0,50).'...</div>
            <div>'.$row->provinsi.' | '.$row->kabupaten.'</div>
            ';

        return $html;
    }

    public function _callback_nama_is_mobile($value, $row)
    {
      // code...
      if (strtotime($row->update_time) < strtotime('-90 days')) {
          // this is true
          $fa = '<i class="fa fa-calendar-times-o" style="color:red"></i>';
      } else {
          $fa = '<i class="fa fa-calendar-check-o"></i>';
      }
      $html = '
          <div><b>'.$row->no_register.'</b></div>
          <div><span><strong><a href="'. base_url().'ummidaerah/pengguna/profile/'.$row->id_customer.'">'.$value.'</a></strong></span>
          <span style="font-size: 12px;font-weight: 600;color: #f39c12;margin-left: 10px;">'.$row->jenis_lembaga.'</div>
          <div>'.$row->provinsi.' | '.$row->kabupaten.'</div>
          <div>Santri : '.$row->jumlah_siswa.'</div>
          <div>Guru : '.$row->jumlah_guru.'( '.$row->sudah_sertifikasi.' : '.$row->belum_sertifikasi.')</div>
          <div class="float-right">'.$fa.' '.dateFormat($value).'</div>          ';

      return $html;
    }

    public function _callback_update_time($value, $row)
    {
        if (strtotime($value) < strtotime('-90 days')) {
            // this is true
            $fa = '<i class="fa fa-calendar-times-o" style="color:red"></i>';
        } else {
            $fa = '<i class="fa fa-calendar-check-o"></i>';
        }

        $html = '
			<div>Santri : '.$row->jumlah_siswa.'</div>
			<div>Guru : '.$row->jumlah_guru.'( '.$row->sudah_sertifikasi.' : '.$row->belum_sertifikasi.')</div>
			<div>'.$fa.' '.dateFormat($value).'</div>
		';

        return $html;
    }

    public function _callback_foto_guru_quran($value, $row)
    {
        if (empty($value)) {
            $html = '<img src="https://padu.ummifoundation.org/assets/user_img.png" class="img-circle" height="50" width="50">';
        } else {
            $html = '<img src="'.base_url().'assets/uploads/image/guruquran/'.$value.'" class="img-circle" height="50" width="50">';
        }

        return $html;
    }

    public function _callback_nama_guru($value, $row)
    {
        $html = '
			<span class=""><strong><a href="'.base_url().'ummidaerah/guru_quran/index/edit/'.$row->id_guru_quran.'">'.$value.'</a></strong></span><br>
			<span>'.$row->alamat.'</span>
		';
        return $html;
    }

    public function _callback_nama_guru_mobile($value, $row)
    {
        $html = '
			<span class=""><strong><a href="'.current_full_url().'/edit/'.$row->id_guru_quran.'">'.$value.'</a></strong></span><br>
			<span>'.$row->nama.'</span>
		';
        return $html;
    }

    /**
     * Avatar upload compress.
     *
     * @return Image
     **/
    public function _callback_foto_guru_upload($uploader_response, $field_info, $files_to_upload)
    {
        $this->load->library('image_moo');
        $file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name;

        $this->image_moo->load($file_uploaded)->resize_crop(400, 400)->save($file_uploaded, true);

        return true;
    }

    // public function

    public function _callback_perkembangan_siswa($value, $row)
    {
        $month = date("F", strtotime("2001-" . $row->periode_bulan . "-01"));

        if (count(db_get_row('jumlah_santri', array('unique' => $row->unique)))) {
            $isedit = '<a href="'.base_url().'ummidaerah/perkembangan_siswa/edit?customer_id='.$row->customer_id.'&unique='.$row->unique.'">Edit</a>  |
          <a href="'.base_url().'ummidaerah/perkembangan_siswa/delete/'.$row->unique.'" >Delete</a> |
          <a href="/pengguna/perkembangan_siswa/print/'.$row->unique.'" target="_blank">Print</a>
          ';
        } else {
            $isedit = '<a href="'.base_url().'ummidaerah/perkembangan_siswa/delete/'.$row->unique.'" >Delete</a>';
        }

        $detect = new Mobile_Detect();

        if ($detect->isMobile) {
            $style = 'style="font-size:10px"';
        } else {
            $style = '';
        }

        $html = '
            <p class="text-green font-semibold text-2xl">Periode Bulan '.$month.' '.$row->tahun.'</p>
            <div '.$style.'>
            <div class="row">
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Pra</td><td>: '.$row->pra.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">jilid 1</td><td>: '.$row->jilid1.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Jilid 2</td><td>: '.$row->jilid2.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Jilid 3</td><td>: '.$row->jilid3.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Jilid 4</td><td>: '.$row->jilid4.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Jilid 5</td><td>: '.$row->jilid5.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Jilid 6</td><td>: '.$row->jilid6.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Dewasa 1</td><td>: '.$row->dewasa1.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Dewasa 2</td><td>: '.$row->dewasa2.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Dewasa 3</td><td>: '.$row->dewasa3.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Tahfidz</td><td>: '.$row->j_tahfidz.'</td></tr></table></div>
            <div class="col-xs-6 col-sm-4 col-md-3"><table><tr><td width="80">Turjuman</td><td>: '.$row->j_turjuman.'</td></tr></table></div>
            </div>
            <p>Jumlah Santri : <span class="text-bold">'.$row->jumlah_siswa.'</span></p>
            </div>
            '.$isedit.'<span class="text-bold text-orange pl-24">Created at : '.date('d M Y', strtotime($row->created_at)).'</span>
            ';
        return $html;
    }

    public function _callback_delete_guru_quran($primary_key)
    {
        $this->db->where('id_guru_quran', $primary_key);
        $guru_quran = $this->db->get('guru_quran')->row();
        $customer_id = $guru_quran->customer_id;

        $kondisi_guru = $this->db->get_where('kondisi_guru', array('customer_id',$customer_id));

        if (!empty($kondisi_guru)) {
            $this->db->delete('kondisi_guru', array('customer_id' => $customer_id));
        }

        $this->db->delete('guru_quran', array('id_guru_quran' => $primary_key));

        $jumlah_guru = $this->db->query('SELECT COUNT(q.id_guru_quran) as jumlah_guru FROM guru_quran as q WHERE q.customer_id = '.$customer_id)->row();
        $sudah_sertifikasi = $this->db->query('SELECT COUNT(q.id_guru_quran) as jumlah_guru FROM guru_quran as q WHERE q.customer_id = '.$customer_id.' AND q.status_sertifikasi = 1')->row();
        $belum_sertifikasi = $this->db->query('SELECT COUNT(q.id_guru_quran) as jumlah_guru FROM guru_quran as q WHERE q.customer_id = '.$customer_id.' AND q.status_sertifikasi = 0')->row();


        $data = array(
            'customer_id' => $customer_id,
            'jumlah_guru' => $jumlah_guru->jumlah_guru,
            'sudah_sertifikasi' => $sudah_sertifikasi->jumlah_guru,
            'belum_sertifikasi' => $belum_sertifikasi->jumlah_guru
        );

        return $this->db->insert('kondisi_guru', $data);
        // redirect($customer_id);
    }

    public function _callback_insert_guru_quran($post_array)
    {
        $customer_id = $post_array['customer_id'];
        $this->db->where('customer_id', $customer_id);
        $kondisi_guru = $this->db->get('kondisi_guru')->row();

        if (!empty($kondisi_guru)) {
            $this->db->delete('kondisi_guru', array('customer_id' => $customer_id));
        }

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

        return $post_array;
    }
}

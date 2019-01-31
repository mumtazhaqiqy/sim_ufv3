<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Smart model.
 */
class Ummi_daerah_model extends MY_Model
{

    function j_trainer($ummi_daerah_id)
    {
      $result = db_get_count('trainer', array('ummi_daerah_id' => $ummi_daerah_id));
      return $result;
    }

    function kondisi_lembaga($ummi_daerah_id)
    {
      $result = db_get_row('view_kondisi_siswa_umda',array('ummi_daerah_id' => $ummi_daerah_id));
      return $result;
    }

    function kondisi_guru($ummi_daerah_id)
    {
      $result = db_get_row('view_kondisi_guru_umda',array('ummi_daerah_id' => $ummi_daerah_id));
      return $result;
    }

    function ummi_daerah($ummi_daerah_id)
    {
      $result = db_get_row('view_ummi_daerah',array('id_ummi_daerah' => $ummi_daerah_id));
      return $result;
    }

    function sudah_update($ummi_daerah_id)
    {
      $result = $this->db->query('SELECT COUNT(*) AS res
          FROM
          (SELECT ua.ummi_daerah_id, ks.last_update_time
          FROM kondisi_siswa AS ks
          LEFT JOIN pengguna as c ON c.id_customer = ks.customer_id
          LEFT JOIN ummi_daerah_area AS ua ON ua.kabupaten_id = c.kabupaten_id
          WHERE ua.ummi_daerah_id = '.$ummi_daerah_id.' AND c.status_aktif = 1
          ) AS d
          WHERE last_update_time >= DATE_SUB(NOW(), INTERVAL 90 DAY)
          ')->row()->res;
      return $result;
    }


    function lembaga_by_jenis($ummi_daerah_id)
    {
      $query = $this->db->query('SELECT pengguna.jenis_lembaga_id, jenis_lembaga.jenis_lembaga,
      COUNT(pengguna.id_customer) AS jumlah
      FROM view_pengguna AS pengguna
      LEFT JOIN jenis_lembaga ON jenis_lembaga.id_jenis_lembaga = pengguna.jenis_lembaga_id
      WHERE id_ummi_daerah = '.$ummi_daerah_id.'
      GROUP BY pengguna.jenis_lembaga_id'
      )->result();
      return $query;
    }

}

<?php

/**
*
*/
class get_register_model extends CI_Model
{
    public function ceknoreg($noreg)
    {
        $this->db->where('noreg', $noreg);
        $query = $this->db->query('select left(`tr`.`no_register`,2) AS `noreg` from `register` `tr`');
        return $query->num_rows();
    }


    public function get_noreg($id)
    {
        $row = db_get_row('pengguna', array('id_customer' => $id));
        // $year = date('y', strtotime($row->tanggal_mulai));
        $year = 19;
        $jenis = $row->jenis_lembaga_id;
        if ($year == null) {
            # code...
            $year = date("y");
        }
        if (strlen($jenis) == 1) {
            # code...
            $jenis = '0'.$jenis;
        } else {
            $jenis;
        }

        $reg_id =  $year.$jenis.$row->kabupaten_id;
        $check =  $this->ceknoreg($year);
        // if ($check == 0) {
        //     # code...
        //     $no = '001';
        // } else {
        //     $no = $this->ceknoreg($year)+1;
        // }
        // if (strlen($no) == 1) {
        //     # code...
        //     $no = '00'.$no;
        // }
        // if (strlen($no) == 2) {
        //     $no = '0'.$no;
        // } else {
        //     $no;
        // }

        // return $reg_id.$no;
        return $check;
    }
}

<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crud_model extends MY_Model
{

    public function initial_status_callback($post_array)
	{
		$post_array['status'] = "permohonan";
		return $post_array;
	}

    public function _callback_approval_action($value, $row)
    {
        $rowId = strip_tags($row->id);
        return "<a class='btn btn-default btn-sm approval' href='" . base_url()."sertifikasi/approve_action/" . $rowId . "' ><i class='fa fa-check' ></i> Setujui</a>";
    }

    public function _callback_unapproval_action($value, $row)
    {
        $rowId = strip_tags($row->id);
        return "<a class='btn btn-default btn-sm approval' href='" . base_url()."sertifikasi/unapprove_action/" . $rowId . "' ><i class='fa fa-times' ></i> Batalkan</a>";
    }


        /**
     * Return form.
     *
     * @return HTML
     **/
    public function pengalaman_mengajar_callback($value = '', $primary_key = null)
    {
        $crumbContent = '<div class="pengalaman_mengajar-content">';
        $listPm = '';
        $endPmContent = '</div><button type="button" class="btn btn-default btn-sm btn-block btn-flat" id="addPengalamanMengajar"><i class="fa fa-plus-circle"></i> Tambah</button>';

        if ($value != 'NULL' && $value != '') {
            $decodePm = json_decode($value);
            if ($decodePm) {
                foreach ($decodePm as $value) {
                    $listPm .= $this->pengalaman_mengajar_form($value->pm_lembaga, $value->pm_tahun);
                }
            } else {
                $listPm = $this->pengalaman_mengajar_form('', '');
            }
        } else {
            $listPm .= $this->pengalaman_mengajar_form();
        }

        return $crumbContent.$listPm.$endPmContent;
	}
	
	/**
     * Return input breadcrumb.
     *
     * @return HTML
     **/
    public function pengalaman_mengajar_form($val_lembaga = '', $val_tahun = '')
    {
        $pmItem = '<div class="row form-pengalaman-mengajar">
			<div class="col-xs-12 col-sm-8">						
				<input type="text" name="pm_lembaga[]" value="'.$val_lembaga.'" id="inputLabel" class="form-control" placeholder="Lembaga">
				<br>
			</div>
			<div class="col-xs-4 col-sm-2">
				<input type="text" name="pm_tahun[]" value="'.$val_tahun.'" id="inputLabel" class="form-control" placeholder="tahun">
				<br>
			</div>
            <div class="col-xs-2 col-sm-2">
				<button type="button" class="remove-pm btn btn-danger btn-flat">
					<i class="fa fa-times-circle"></i>
				</button>
				<br>
			</div>
		</div>';
        return $pmItem;
    }

    /**
     * Return form.
     *
     * @return HTML
     **/
    public function pengalaman_kursus_callback($value = '', $primary_key = null)
    {
        $crumbContent = '<div class="pengalaman_kursus-content">';
        $listpk = '';
        $endpkContent = '</div><button type="button" class="btn btn-default btn-sm btn-block btn-flat" id="addPengalamanKursus"><i class="fa fa-plus-circle"></i> Tambah</button>';

        if ($value != 'NULL' && $value != '') {
            $decodepk = json_decode($value);
            if ($decodepk) {
                foreach ($decodepk as $value) {
                    $listpk .= $this->pengalaman_kursus_form($value->pk_kursus, $value->pk_tahun);
                }
            } else {
                $listpk = $this->pengalaman_kursus_form('', '');
            }
        } else {
            $listpk .= $this->pengalaman_kursus_form();
        }

        return $crumbContent.$listpk.$endpkContent;
	}
	
	/**
     * Return input breadcrumb.
     *
     * @return HTML
     **/
    public function pengalaman_kursus_form($val_kursus = '', $val_tahun = '')
    {
        $pkItem = '<div class="row form-pengalaman-kursus">
			<div class="col-xs-12 col-sm-8">						
				<input type="text" name="pk_kursus[]" value="'.$val_kursus.'" id="inputLabel" class="form-control" placeholder="kursus">
				<br>
			</div>
			<div class="col-xs-4 col-sm-2">
				<input type="text" name="pk_tahun[]" value="'.$val_tahun.'" id="inputLabel" class="form-control" placeholder="tahun">
				<br>
			</div>
			<div class="col-xs-2 col-sm-2">
				<button type="button" class="remove-pk btn btn-danger btn-flat">
					<i class="fa fa-times-circle"></i>
				</button>
				<br>
			</div>
		</div>';
        return $pkItem;
    }


    function c_insert($post_array)
    {
        // pengalaman mengajar
        foreach ($post_array['pm_lembaga'] as $key => $value) {
            if ($value != '') {
                $link = $post_array['pm_tahun'][$key] != '' ? $post_array['pm_tahun'][$key] : '';
                $pmArray[] = ['pm_lembaga' => $value, 'pm_tahun' => $link];
            }
        }
        $post_array['pengalaman_mengajar'] = json_encode($pmArray);
        
        // pengalaman kursus
        foreach ($post_array['pk_kursus'] as $key => $value) {
            if ($value != '') {
                $link = $post_array['pk_tahun'][$key] != '' ? $post_array['pk_tahun'][$key] : '';
                $pkArray[] = ['pk_kursus' => $value, 'pk_tahun' => $link];
            }
        }
        $post_array['pengalaman_kursus'] = json_encode($pkArray);
        
        return $post_array;
    }

    public function _callback_trainer_column($value, $row)
    {
        $array = explode(',',$value);
        foreach($array as $val){
            $trainer = db_get_row('trainer',array('id_trainer' => $val))->nama_panggilan;
            $html .= '<div>'.$trainer.'</div>';
        }
        return $html; 
    }

    public function _callback_status_action($value, $row)
    {
        $html = '<button>'.$value.'</button>';

        return $html;
    }

}

/* End of file Crud_model.php */
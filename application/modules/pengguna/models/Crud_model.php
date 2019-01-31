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
            
            <div><span><strong>'.$value.'</strong></span>
            <span style="font-size: 12px;font-weight: 600;color: #f39c12;margin-left: 10px;">'.$row->jenis_lembaga.'</div>
            <div>'.$row->alamat.'</div>
            <div>'.$row->provinsi.' | '.$row->kabupaten.'</div>
            ';

        return $html;
	}
	
	public function _callback_update_time($value, $row)
	{
		if(strtotime($value) < strtotime('-90 days')) {
			// this is true
			$fa = '<i class="fa fa-calendar-times-o" style="color:red"></i>';
		} else {
			$fa = '<i class="fa fa-calendar-check-o"></i>';
		}

		$html = '
			<div>Santri : '.$row->jumlah_siswa.'</div>
			<div>Guru : '.$row->jumlah_guru.'</div>
			<div>'.$fa.' '.dateFormat($value).'</div>
		';

		return $html;
	}

	public function _callback_foto_guru_quran($value, $row)
	{
		if( empty($value)){
			$html = '<img src="https://padu.ummifoundation.org/assets/user_img.png" class="img-circle" height="50" width="50">';
		} else {
			$html = '<img src="'.$value.'" class="img-circle" height="50" width="50">';
		}

		return $html;

	}

	public function _callback_nama_guru($value, $row)
	{
		$html = '
			<span class=""><strong><a href="'.base_url().'pengguna/guru_quran/index/edit/'.$row->id_guru_quran.'">'.$value.'</a></strong></span><br>
			<span>'.$row->alamat.'</span>
		';
		return $html;
	}

	// public function 
	
	public function _callback_perkembangan_siswa($value, $row)
	{	
		$month = date("F", strtotime("2001-" . $row->periode_bulan . "-01"));

        $isedit = '<a href="'.base_url().'pengguna/perkembangan_siswa/edit?unique='.$row->unique.'">Edit</a>  | 
        <a href="'.base_url().'pengguna/perkembangan_siswa/delete/'.$row->unique.'" >Delete</a> |
        <a href="'.base_url().'pengguna/perkembangan_siswa/print/'.$row->unique.'" target="_blank">Print</a> 
        
        ';

        $detect = new Mobile_Detect();

        if($detect->isMobile){
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
            '.$isedit.'<span class="text-bold text-orange pl-24">Created at : '.date('d M Y',strtotime($row->created_at)).'</span>
            ';
            return $html;

	}

}

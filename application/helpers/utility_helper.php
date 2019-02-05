<?php

// format size for humans ;)
function format_file_size($size)
{
    // bytes
    if ($size < 1024) {
        return $size . " bytes";
    } // kilobytes
    elseif ($size < (1024 * 1024)) {
        return round(($size / 1024), 1) . " KB";
    } // megabytes
    elseif ($size < (1024 * 1024 * 1024)) {
        return round(($size / (1024 * 1024)), 1) . " MB";
    } // gigabytes
    else {
        return round(($size / (1024 * 1024 * 1024)), 1) . " GB";
    }
}

/**
 *
 * @param string $param1
 * @param string $param2
 * @param string $result1
 * @param string $result2
 */
function short_if($param1 = '', $param2 = '', $result1 = '', $result2 = '')
{
    return ($param1 == $param2) ? $result1 : $result2;
}

/**
 *
 * @param string $param1
 * @param string $param2
 * @param string $result1
 * @param string $result2
 */
function short_if_not($param1 = '', $param2 = '', $result1 = '', $result2 = '')
{
    return ($param1 !== $param2) ? $result1 : $result2;
}

/**
 * @param $ptime time stamp format
 * @return string THE ELAPSED TIME STRING
 */
function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1) {
        return '0 seconds';
    }

    $a = array(
        365 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
        );
    $a_plural = array(
        'year' => 'years',
        'month' => 'months',
        'day' => 'days',
        'hour' => 'hours',
        'minute' => 'minutes',
        'second' => 'seconds'
        );

    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);

            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}

/**
 * @throws current day in this format (0000-00-00)
 * @return bool|string
 */
function current_date()
{
    return date("Y-m-d", time());
}

function uri_segment($number = 0)
{
    $CI =& get_instance();
    return $CI->uri->segment($number);
}


/**
 * Redirect to previous url.
 */

if (! function_exists('last_url')) {
    function last_url($action = 'get', $url = null)
    {
        $CI =& get_instance();
        if (!$CI->input->is_ajax_request()) {
            switch ($action) {
                case 'get':
                    $result = $CI->session->userdata('last_url');
                    break;

                case 'set':
                    if ($url == null) {
                        $CI->session->set_userdata('last_url', current_url());
                    } else {
                        $CI->session->set_userdata('last_url', $url);
                    }
                    break;
                default:
                    $result = $CI->session->userdata('last_url');
                    break;
            }
            return $result;
        } else {
            return false;
        }
    }
}

/**
 * advanced session destroy function.
 */
if (! function_exists('ci_session_destroy')) {
    function ci_session_destroy($exception_list = [])
    {
        $CI =& get_instance();
        $user_data = $CI->session->all_userdata();

        // dump($user_data);
        foreach ($user_data as $key => $value) {
            if (!in_array($key, $exception_list)) {
                // dump($key);
                $CI->session->unset_userdata($key);
            }
        }
        // exit;
    }
}


if(!function_exists('db_get_row')) {
	function db_get_row($table_name = null, $where = false) {
		$ci =& get_instance();
		if ($where) {
			$ci->db->where($where);
		}
	  	$query = $ci->db->get($table_name);

	    return $query->row();
	}
}

if(!function_exists('db_get_all_data')) {
	function db_get_all_data($table_name = null, $where = false) {
		$ci =& get_instance();
		if ($where) {
			$ci->db->where($where);
		}
	  	$query = $ci->db->get($table_name);

	    return $query->result();
	}
}


if(!function_exists('db_get_count_ul')) {
	function db_get_count_ul($table_name = null, $where = false) {
		$ci =& get_instance();
		if ($where) {
			$ci->db->where($where);
		}
	  	$query = $ci->db->count_all_results($table_name);
	    return $query;
	}
}

if(!function_exists('db_get_count')) {
	function db_get_count($table_name = null, $where = false) {
		$ci =& get_instance();
		if ($where) {
			$ci->db->where($where);
		}
	  	$query = $ci->db->count_all_results($table_name);
	    return $query;
	}
}

if(!function_exists('db_count_update')) {
  function db_count_update($numeric)
  {
    $ci =& get_instance();
    $result = $ci->db->query('SELECT COUNT(*) AS res
        FROM
        (SELECT ua.ummi_daerah_id, ks.last_update_time
        FROM kondisi_siswa AS ks
        LEFT JOIN pengguna as c ON c.id_customer = ks.customer_id
        LEFT JOIN ummi_daerah_area AS ua ON ua.kabupaten_id = c.kabupaten_id
        WHERE ua.ummi_daerah_id = '.$numeric.' AND c.status_aktif = 1
        ) AS d
        WHERE last_update_time >= DATE_SUB(NOW(), INTERVAL 90 DAY)
        ')->row()->res;
    return $result;
  }
}


if(!function_exists('redirect_back')) {
	function redirect_back($url = '')
	{
	    if(isset($_SERVER['HTTP_REFERER']))
	    {
	        header('Location: '.$_SERVER['HTTP_REFERER']);
	    }
	    else
	    {
	        redirect($url);
	    }
	    exit;
	}
}


if(!function_exists('DatetoIndo')) {
	function DatetoIndo($date)
	{
        $BulanIndo = array("Januari", "Februari", "Maret",
        "April", "Mei", "Juni",
        "Juli", "Agustus", "September",
        "Oktober", "November", "Desember");

        $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
        $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
        $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring

        $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
        return($result);
	}
}

if(!function_exists('DatetoIndo_hb')) {
	function DatetoIndo_hb($date)
	{
        $BulanIndo = array("Januari", "Februari", "Maret",
        "April", "Mei", "Juni",
        "Juli", "Agustus", "September",
        "Oktober", "November", "Desember");

        $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
        $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
        $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring

        $result = $tgl . " " . $BulanIndo[(int)$bulan-1];
        return($result);
	}
}

if(!function_exists('integerToRoman')) {
    function integerToRoman($integer)
    {
     // Convert the integer into an integer (just to make sure)
     $integer = intval($integer);
     $result = '';

     // Create a lookup array that contains all of the Roman numerals.
     $lookup = array('M' => 1000,
     'CM' => 900,
     'D' => 500,
     'CD' => 400,
     'C' => 100,
     'XC' => 90,
     'L' => 50,
     'XL' => 40,
     'X' => 10,
     'IX' => 9,
     'V' => 5,
     'IV' => 4,
     'I' => 1);

     foreach($lookup as $roman => $value){
      // Determine the number of matches
      $matches = intval($integer/$value);

      // Add the same number of characters to the string
      $result .= str_repeat($roman,$matches);

      // Set the integer to be the remainder of the integer and the value
      $integer = $integer % $value;
     }

     // The Roman numeral should be built, return it
     return $result;
    }

}


function ago($ptime)
{
	$etime = time() - $ptime;

	if ($etime < 1)
	{
		return '0 seconds';
	}

	$a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
				30 * 24 * 60 * 60       =>  'month',
				24 * 60 * 60            =>  'day',
				60 * 60                 =>  'hour',
				60                      =>  'minute',
				1                       =>  'second'
				);

	foreach ($a as $secs => $str)
	{
		$d = $etime / $secs;
		if ($d >= 1)
		{
			$r = round($d);
			return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
		}
	}
}

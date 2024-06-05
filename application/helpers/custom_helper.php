<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
const hashing_algo = 'whirlpool';
if (!function_exists('encode_id')) {

    /**
     * @param string $string data yang akan diencode
     */
    function encode_id($string): string
    {

        $string = $string . ' ' . date('YmdHis') . random_string('alnum', 8);
        $output = false;
        /*
        * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
        */
        $security       = parse_ini_file("security.ini");
        $secret_key     = $security["encryption_key"];
        $secret_iv      = $security["iv"];
        $encrypt_method = $security["encryption_mechanism"];

        // hash
        $key    = hash(hashing_algo, $secret_key);

        // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
        $iv     = substr(hash(hashing_algo, $secret_iv), 0, 16);

        //do the encryption given text/string/number
        $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($result);
        return $output;
    }
}

if (!function_exists('decode_id')) {

    /**
     * @param string $string data yang akan diencode
     */
    function decode_id($string): string
    {

        $output = false;
        /*
        * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
        */

        $security       = parse_ini_file("security.ini");
        $secret_key     = $security["encryption_key"];
        $secret_iv      = $security["iv"];
        $encrypt_method = $security["encryption_mechanism"];

        // hash
        $key    = hash(hashing_algo, $secret_key);

        // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
        $iv = substr(hash(hashing_algo, $secret_iv), 0, 16);

        //do the decryption given text/string/number

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        $hasil = explode(" ", $output);
        return $hasil[0];
    }
}

if (!function_exists('uploadFile')) {
    /**
     * @param string $name Nama Inputan
     * @param string $path Path tujuan tanpa trailing slash
     * @param boolean $encrypt_name encrypt file name
     * @param string @file_type allowed file type
     */
    function uploadFile($name, $path, $encrypt_name = FALSE, $file_type = [])
    {
        $CI = &get_instance();
        $CI->load->library('upload');

        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }

        if (empty($file_type)) {
            $file_type = implode('|', ['pdf']);
        }

        $realName = date('YmdHis') . '-' . $_FILES[$name]['name'];

        $config = [
            'upload_path' => "$path",
            'allowed_types' => $file_type,
            'encrypt_name' => $encrypt_name,
        ];

        $CI->upload->initialize($config);
        if ($CI->upload->do_upload($name)) {
            $status = 1;
            $data =  [
                'status' => true,
                'data' => [
                    'path' => $path . '/' . $CI->upload->data("file_name"),
                    'real_name' => $realName,
                    'name' => $CI->upload->data("file_name"),
                    'type' => $CI->upload->data("file_type"),
                    'size' => $CI->upload->data("file_size"),
                    'ext' => $CI->upload->data("file_ext"),
                ]
            ];
        } else {
            $status = 0;
            $data =  [
                'status' => false,
                'data' => $CI->upload->display_errors(),
            ];
        }

        $CI->db->insert('log_upload_file', [
            'status' => $status,
            'data' => json_encode($data['data'])
        ]);

        return $data;
    }
}

if (!function_exists('uploadFileMultiple')) {
    function uploadFileMultiple($name, $path, $type = null)
    {
        $CI = &get_instance();

        $upload = [];

        $length = count($_FILES[$name]['name']);
        // $CI->upload->initialize($config);

        for ($i = 0; $i < $length; $i++) {
            if (!empty($_FILES[$name]['name'][$i])) {

                $_FILES['file']['name'] = $_FILES[$name]['name'][$i];
                $_FILES['file']['type'] = $_FILES[$name]['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES[$name]['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES[$name]['error'][$i];
                $_FILES['file']['size'] = $_FILES[$name]['size'][$i];

                $upload[] = uploadFile('file', $path, $type);
            }
        }

        return $upload;
    }
}

if (!function_exists('generate_string')) {
    function generate_string($strength = 16)
    {
        $input = str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
}

if (!function_exists('upload_encrypt')) {
    function upload_encrypt($name, $dest, $is_encrypt_name = true)
    {
        $key = generate_string(8);
        $CI = &get_instance();

        $uploaded_file = uploadFile($name, $dest, $is_encrypt_name);

        $status_upload = $uploaded_file['status'];
        $data_upload = $uploaded_file['data'];

        if ($status_upload != false) {
            $encryptName = $data_upload['name'];

            $firstDest = substr($dest, 0, 1);
            $lastDest = substr($dest, -1);

            if ($firstDest != "/" && $lastDest != "/") {
                $dest = "/$dest/";
            } else if ($firstDest != "/" && $lastDest == "/") {
                $dest = "/$dest";
            } else if ($firstDest == "/" && $lastDest != "/") {
                $dest = "$dest/";
            }
            $file = getcwd() . $dest . $encryptName;
            // encryptFile(getcwd() . '/temp/' . $encryptName, $key, getcwd() . '/' . $dest . '/' . $name . '.enc');
            encryptFile($file, $key, $file . '.enc');
            unlink($file);

            $data = [
                'id' => uuid(time()),
                'file_name' => $encryptName,
                'real_name' => $data_upload['real_name'],
                'url' => $data_upload['path'],
                'key' => $key,
            ];

            $CI->db->insert('file_uploads', $data);
            return [
                'status' => true,
                'file_id' => $data['id']
            ];
            return $data['id'];
        } else {
            return [
                'status' => false,
                'data' => $data_upload
            ];
        }
    }
}


if (!function_exists('encryptFile')) {
    define('FILE_ENCRYPTION_BLOCKS', 10000);
    function encryptFile($source, $key, $dest)
    {
        $key = substr(sha1($key, true), 0, 16);
        $iv = openssl_random_pseudo_bytes(16);

        $error = false;
        if ($fpOut = fopen($dest, 'w')) {
            // Put the initialzation vector to the beginning of the file
            fwrite($fpOut, $iv);
            if ($fpIn = fopen($source, 'rb')) {
                while (!feof($fpIn)) {
                    $plaintext = fread($fpIn, 16 * FILE_ENCRYPTION_BLOCKS);
                    $ciphertext = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                    // Use the first 16 bytes of the ciphertext as the next initialization vector
                    $iv = substr($ciphertext, 0, 16);
                    fwrite($fpOut, $ciphertext);
                }
                fclose($fpIn);
            } else {
                $error = true;
            }
            fclose($fpOut);
        } else {
            $error = true;
        }

        return $error ? false : $dest;
    }
}

if (!function_exists('getPath')) {
    function getPath($path, $file_name)
    {
        return str_replace('/' . $file_name, "", $path);
    }
}

if (!function_exists('decryptFile')) {
    function decryptFile($source, $key, $dest)
    {
        // if ($source[0] == '.') {
        //     $source = ltrim($source, '.');
        // }
        $key = substr(sha1($key, true), 0, 16);

        $error = false;
        if ($fpOut = fopen($dest, 'w')) {
            if ($fpIn = fopen($source, 'rb')) {
                // Get the initialzation vector from the beginning of the file
                $iv = fread($fpIn, 16);
                while (!feof($fpIn)) {
                    $ciphertext = fread($fpIn, 16 * (FILE_ENCRYPTION_BLOCKS + 1)); // we have to read one block more for decrypting than for encrypting
                    $plaintext = openssl_decrypt($ciphertext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                    // Use the first 16 bytes of the ciphertext as the next initialization vector
                    $iv = substr($ciphertext, 0, 16);
                    fwrite($fpOut, $plaintext);
                }
                fclose($fpIn);
            } else {
                $error = true;
            }
            fclose($fpOut);
        } else {
            $error = true;
        }

        return $error ? false : $dest;
    }
}

if (!function_exists('download_encrypt')) {
    function download_encrypt($file_name, $key, $path, $download_name = null)
    {
        $CI = &get_instance();
        $CI->load->helper('download');

        $firstPath = substr($path, 0, 1);
        $lastPath = substr($path, -1);

        if ($firstPath != "/" && $lastPath != "/") {
            $path = "$path/";
        } else if ($firstPath != "/" && $lastPath == "/") {
            $path = "$path/";
        } else if ($firstPath == "/" && $lastPath != "/") {
            $path = "$path/";
        }

        decryptFile($path . $file_name . '.enc', $key, 'uploads/beta/' . $file_name);

        // $path = file_get_contents(base_url('uploads/beta/' . $file_name));
        redirect(base_url('uploads/beta/' . $file_name));

        if ($download_name == null) {
            $name = $file_name;
        } else {
            $name = $download_name;
        }
        $CI->db->insert('file_downloads', [
            'file_name' => $file_name,
            'file_path' => 'uploads/beta/' . $file_name,
            'created_at' => date('Y-m-d'),
        ]);
        // force_download($name, $path);
    }
}


if (!function_exists('rupiah')) {
    function rupiah($angka = 0)
    {
        $angka = (int) $angka;
        if ($angka == 0 || empty($angka)) {
            return '-';
        }
        $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }
}

if (!function_exists('rupiah_minified')) {
    function rupiah_minified($n, $presisi = 1)
    {

        if ($n < 900) {
            $format_angka = number_format($n, $presisi);
            $simbol = '';
        } else if ($n < 900000) {
            $format_angka = number_format($n / 1000, $presisi);
            $simbol = 'Rb';
        } else if ($n < 900000000) {
            $format_angka = number_format($n / 1000000, $presisi);
            $simbol = 'Jt';
        } else if ($n < 900000000000) {
            $format_angka = number_format($n / 1000000000, $presisi);
            $simbol = 'M';
        } else {
            $format_angka = number_format($n / 1000000000000, $presisi);
            $simbol = 'T';
        }

        if ($presisi > 0) {
            $pisah = '.' . str_repeat('0', $presisi);
            $format_angka = str_replace($pisah, '', $format_angka);
        }

        return $format_angka . ' ' . $simbol;
    }
}

if (!function_exists('bulan_indonesia')) {
    function bulan_indonesia(int $selected = null)
    {
        $arr = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        if (!empty($selected)) {
            return $arr[$selected];
        }

        return $arr;
    }
}

if (!function_exists('now')) {
    function now()
    {
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('get')) {
    function get($input = null, $clean = true)
    {
        $ci = &get_instance();

        if (!$input) return $ci->input->get();
        if ($clean) {
            $clean = strip_tags($ci->input->clean($input, true));
        } else {
            $clean = $ci->input->clean($input, true);
        }
        if (!$get) return null;
        return $get;
    }
}

if (!function_exists('post')) {
    function post($input = null, $clean = true)
    {
        $ci = &get_instance();

        if (!$input) return $ci->input->post();

        if ($clean) {
            $post = strip_tags($ci->input->post($input, true));
        } else {
            $post = $ci->input->post($input, true);
        }
        if (!$post) return null;
        return $post;
    }
}

if (!function_exists('userdata')) {
    function userdata($key = null, $value = null)
    {
        $ci = &get_instance();

        if (!$key) return $_SESSION;
        elseif (!$value) return $ci->session->userdata($key);

        return $ci->session->set_userdata($key, $value);
    }
}

if (!function_exists('flashdata')) {
    function flashdata($key = null, $value = null)
    {
        $ci = &get_instance();

        if (!$key) return $_SESSION;
        elseif (!$value) return $ci->session->userdata($key);

        return $ci->session->set_flashdata($key, $value);
    }
}

if (!function_exists('format_rencana')) {
    /**
     * @param string $str string dari data rencana yang tersimpan di database dalam format tahun-bulan
     */
    function format_rencana($str)
    {
        $arr = explode('-', $str);

        $bulan = (int) array_shift($arr);
        $tahun = end($arr);

        return bulan_indonesia($bulan) . ' ' . $tahun;
    }
}

if (!function_exists('loadGambar')) {
    /**
     * @param string $path path for any given image
     * this function only used to load image
     */
    function loadGambar(string $path = null)
    {
        $sample = 'assets/img/sample-product.png';
        if (empty($path)) {
            return $sample;
        }

        if (file_exists($path)) {
            return $path;
        }

        return $sample;
    }
}

if (!function_exists('generateOtp')) {
    /**
     * generate 6 digit otp code for first time login
     */
    function generateOtp($l = 6)
    {
        $l = 6;
        $str = '8SkLZFOax2WVRM7XzYewJAtPD1rmGs3HvnfKN4UTyliucb6oBdC5QjIhq9pEg';

        $res = '';

        for ($i = 0; $i < $l; $i++) {
            $res .= substr($str, rand() % (strlen($str)), 1);
        }

        return $res;
    }
}

if (!function_exists('tgl_indo')) {
    function format_date($tgl = null, $with_time = true)
    {
        if (empty($tgl)) $tgl = now();

        $tanggal = explode(" ", $tgl)[0];

        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        $fixed = $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];

        $time = date('H:i', strtotime($tgl));

        if ($with_time) {
            return "{$fixed} {$time}";
        }

        return $fixed;
    }
}

if (!function_exists('dayIndo')) {
    /**
     * @param String $str format l {lowercase L} (day full name)
     */
    function dayIndo($str)
    {
        switch (strtolower($str)) {
            case 'sunday':
                $ind = 'minggu';
                break;

            case 'monday':
                $ind = 'senin';
                break;

            case 'tuesday':
                $ind = 'selasa';
                break;

            case 'wednesday':
                $ind = 'rabu';
                break;

            case 'thursday':
                $ind = 'kamis';
                break;

            case 'friday':
                $ind = 'jumat';
                break;

            case 'saturday':
                $ind = 'sabtu';
                break;

            default:
                $ind = '';
                break;
        }

        return $ind;
    }
}

if (!function_exists('replacePlaceholder')) {
    function replacePlaceholder($string, $arr)
    {
        foreach ($arr as $key => $value) {
            if (empty($value)) {
                $value = ' ';
            }
            $string = str_replace(":$key", $value, $string);
        }

        return $string;
    }
}

if (!function_exists('slugify')) {
    function slugify($string)
    {
        // Get an instance of $this
        $CI = &get_instance();

        $CI->load->helper('text');
        $CI->load->helper('url');

        // Replace unsupported characters (add your owns if necessary)
        $string = str_replace("'", '-', $string);
        $string = str_replace(".", '-', $string);
        $string = str_replace("²", '2', $string);

        // Slugify and return the string
        return url_title(convert_accented_characters($string), 'dash', true);
    }
}

if (!function_exists('uuid')) {
    function uuid($data = null)
    {
        $data = $data ?? random_bytes(16);

        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

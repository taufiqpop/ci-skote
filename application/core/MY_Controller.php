<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    var $current_id, $token_password;
    public function __construct()
    {
        parent::__construct();
        $this->current_id = decode_id($this->session->userdata('id'));
        $this->role_id = decode_id($this->session->userdata('role_id'));
        $this->token_password = $this->config->item('token_password');

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger mt-1" role="alert">', '</div>');

        $this->form_validation->set_message('required', '{field} harus diisi');
        $this->form_validation->set_message('min_length', '{field} minimal {param} karakter');
        $this->form_validation->set_message('max_length', '{field} maksimal {param} karakter');
        $this->form_validation->set_message('matches', '{field} harus sama dengan inputan {param}');
        $this->form_validation->set_message('is_unique', '{field} sudah terdaftar dalam sistem');
    }

    /**
     * function ini digunakan untuk mengecek user sudah login atau belum
     */
    public function _auth()
    {
        if ($this->router->fetch_class() != 'auth') {
            if (empty($this->session->userdata('id')) && $this->session->userdata('logged_in') != true) {
                redirect('auth/login?page=' . urlencode(current_url()), 'refresh');
            }
        }

        if (empty($this->session->userdata('role_id'))) {
            redirect('auth/chooseRole/' . $this->session->userdata('id'));
        }
    }

    /**
     * @param string $string String yang akan di hash menjadi password
     *
     * @return string String yang sudah di hash
     */
    public function generatePassword($string)
    {
        return $this->auth->generatePassword($string);
    }

    /**
     * function ini bisa dipanggil saat url atau parameter yang diberikan tidak ditemukan
     */
    public function _not_found()
    {
        $this->load->view('_layouts/_404');
    }

    /**
     * @param string $menu_id Encrypted menu id yang diambil dari url
     * @param int $action_id Primary key dari table action
     *
     * @return bool Permission status
     */
    public function checkAccess($menu_id = null, $action_id = null): bool
    {
        if (empty($menu_id) || empty($action_id)) return false;

        $access = $this->_checkRBAC($menu_id, $action_id);

        if ($access) return true;

        return false;
    }

    /**
     * @param string $menu_id Encrypted menu id yang diambil dari url
     * @param int $action_id Primary key dari table action
     *
     * @return bool Permission status
     */
    public function checkPermission($menu_id = null, $action_id = null)
    {
        if (empty($menu_id) || empty($action_id)) redirect('error_page/access_denied');

        $access = $this->_checkRBAC($menu_id, $action_id);

        // if ($access) return true;

        // redirect('error_page/access_denied');
    }

    /**
     * @param string $menu_id Encrypted menu id yang diambil dari url
     * @param int $action_id Primary key dari table action
     *
     * @return bool Permission status
     */
    public function checkAccessAjax($menu_id = null, $action_id = null)
    {
        if (empty($menu_id) || empty($action_id)) $this->_set_no_access();

        $access = $this->_checkRBAC($menu_id, $action_id);

        if ($access) return true;

        $this->_set_no_access();
    }

    /**
     * @param string $menu_id Encrypted menu id yang diambil dari url
     * @param int $action_id Primary key dari table action
     *
     * @return bool Permission status
     */
    private function _checkRBAC($encrypted_menu_id, $action_id)
    {
        $str = explode('|', decode_id($encrypted_menu_id));


        // echo "<pre>";
        // print_r($str);
        // echo "</pre>";
        // die;

        $menu_id = $str[0];
        if (count($str) > 1) {
            $site_id = $str[1];
        }

        $menu_data = $this->db->get_where('menus', ['id' => $menu_id]);

        if ($menu_data->num_rows() == 0) {
            return false;
        }

        $menu_data = $menu_data->row();

        if ($menu_data->cross_link == 2) {
            $path = explode('/', $menu_data->route);

            $count_base = strlen(base_url());

            $current_url = current_url();
            $currently_accessed = explode('/', substr($current_url, $count_base));

            $diff = array_diff($path, $currently_accessed);

            for ($i = 0, $l = count($path) - 1; $i < $l; $i++) {
                if ($path[$i] !== $currently_accessed[$i]) {
                    return false;
                    break;
                }
            }
        }

        $access = $this->db->where([
            'active' => 1,
            'menu_id' => $menu_id,
            'action_id' => $action_id,
            'role_id' => $this->role_id,
        ])->from('menu_role')->get();


        if ($access->num_rows() == 0) return false;

        return true;
    }

    /**
     * @param array $data Data for ajax response
     */
    public function _set_success($data)
    {
        $this->response($data, 200);
    }

    /**
     * @param array $data Data for ajax response
     * @param int $status Status code number for failed request
     */
    public function _set_failed($data, $status = 400)
    {
        $this->response($data, $status);
    }

    /**
     * @param string $view Path untuk view modal
     * @param array $data Data untuk view modal
     */
    public function _response_modal($view, $data = [])
    {
        $this->_set_success([
            'status' => true,
            'msg' => 'Sukses',
            'modal' => $this->_modal($view, $data)
        ]);
    }

    /**
     * @param array $data Data for ajax response
     * @param int $status Status code number for failed request
     */
    public function response($data, $status_code)
    {
        if ($this->config->item('csrf_protection') == true) {
            $data['csrf'] = [
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_status_header($status_code)
            ->set_output(json_encode($data))
            ->_display();

        exit();
    }

    /**
     * function ini bisa di panggil untuk memberikan response 403
     */
    public function _set_no_access()
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode(['msg' => 'Anda tidak punya hak untuk mengakses halaman ini', 'status' => false]))
            ->_display();

        exit();
    }

    /**
     * function ini bisa di panggil untuk memberikan response 404
     */
    public function _set_not_found()
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode(['msg' => 'Halaman tidak ditemukan', 'status' => false]))
            ->_display();

        exit();
    }

    /**
     * @param array $data Data yang akan dipass ke view
     * @param string $view Path view yang akan digunakan
     * @param string $js Path view js yang akan digunakan
     *
     * @return void view
     */
    public function _display($data, $view = null, $js = null)
    {
        $content_data = $this->getContentJsView($data, $view, $js);

        $this->load->view('_layouts/_base_layout', $content_data);
    }

    /**
     * @param array $data Data yang akan dipass ke view
     * @param string $view Path view yang akan digunakan
     * @param string $js Path view js yang akan digunakan
     *
     * @return void view
     */
    public function _front($data, $view = null, $js = null)
    {
        $content_data = $this->getContentJsView($data, $view, $js);

        $this->load->view('_layouts/_base_front', $content_data);
    }

    /**
     * @param array $data Data yang akan dipass ke view
     * @param string $view Path view modal yang akan digunakan
     *
     * @return void view
     */
    public function _modal($view, $data = [])
    {
        $view_path = '_contents/';

        $view = $this->load->view($view_path . $view, $data, true);

        return $view;
    }

    /**
     * @param array $data Data yang akan dipass ke view
     * @param string $view Path view yang akan digunakan
     * @param string $js Path view js yang akan digunakan
     *
     * @return array array variable dan view yang digunakan
     */
    private function getContentJsView($data, $view, $js_view)
    {
        $view_path = '_contents/';

        if (array_key_exists('menu_id', $data)) {
            $data['access'] = [
                'tambah' => $this->checkAccess($data['menu_id'], 2),
                'edit' => $this->checkAccess($data['menu_id'], 3),
                'delete' => $this->checkAccess($data['menu_id'], 4),
                'download' => $this->checkAccess($data['menu_id'], 5),
            ];
        }

        $modal = null;
        if (array_key_exists('modal', $data)) {
            $view_modal = $data['modal'];

            if (is_array($view_modal)) {
                foreach ($view_modal as $key => $value) {
                    $modal[] = $this->load->view($view_path . $value, $data, true);
                }
            } else {
                $modal = $this->load->view($view_path . $view_modal, $data, true);
            }
        }

        if (array_key_exists('plugins', $data) === false) {
            $data['plugins'] = [];
        }

        if ($this->config->item('csrf_protection') == true) {
            $data['csrf'] = [
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            ];
        }

        if ($view == null) {
            $content = null;
        } else {
            $content = $this->load->view($view_path . $view, $data, true);
        }

        if ($js_view == null && $content != null) {
            $js_view = $view . '_js';
        } else {
            $js_view = null;
        }


        if ($js_view != null && file_exists(APPPATH . 'views/' . $view_path . $js_view . '.php')) {
            $javascript = $this->load->view($view_path . $js_view, $data, true);
        } else {
            $javascript = null;
        }

        $return_data = [
            'content' => $content,
            'javascript' => $javascript,
            'modal' => $modal,
        ];

        $array = array_merge($data, $return_data);

        return $array;
    }

    public function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    public function random_color()
    {
        return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }

    /**
     * @param array $arr Array berisi nama inputan yang memiliki validation rule
     *
     * @return array $data Array berisi error message per nama inputan
     */
    public function generateFormError($arr = [])
    {
        if (empty($arr)) return [];

        $data = [];
        foreach ($arr as $key => $value) {
            $data[$value] = form_error($value);
        }

        return $data;
    }
}

/* End of file MY_Controller.php */

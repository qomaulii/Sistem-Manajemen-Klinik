<?php

namespace App\Libraries;

use App\Libraries\Phpass;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Bitauth
{
    public $_table;
    public $_default_group_id;
    public $_admin_role;
    public $_remember_token_name;
    public $_remember_token_expires;
    public $_remember_token_updates;
    public $_require_user_activation;
    public $_pwd_max_age;
    public $_pwd_min_length;
    public $_pwd_max_length;
    public $_pwd_complexity;
    public $_pwd_complexity_chars;
    public $_error_delim_prefix = '<div class="alert alert-danger">';
    public $_error_delim_suffix = '</div>';
    public $_forgot_valid_for;
    public $_log_logins;
    public $_invalid_logins;
    public $_mins_login_attempts;
    public $_mins_locked_out;
    public $_date_format;
    public $_cookie_elem_prefix = 'ba_';

    private $_all_roles;
    private $_error;
    private $_login_fields;

    protected $db;
    protected $session;
    protected $request;
    protected $response;
    protected $encrypter;
    public $phpass;

    private $_data_fields = [
        'username', 'password', 'password_last_set', 'password_never_expires', 'remember_me', 'activation_code',
        'active', 'forgot_code', 'forgot_generated', 'enabled', 'last_login', 'last_login_ip'
    ];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->encrypter = \Config\Services::encrypter();
        
        helper('cookie');

        // Mengambil dari Config/Bitauth.php yang sudah dibuat sebelumnya
        $config = config('Bitauth');
        
        $this->_table                   = $config->table;
        $this->_default_group_id        = $config->default_group_id;
        $this->_remember_token_name     = $config->remember_token_name;
        $this->_remember_token_expires  = $config->remember_token_expires;
        $this->_remember_token_updates  = $config->remember_token_updates;
        $this->_require_user_activation = $config->require_user_activation;
        $this->_pwd_max_age             = $config->pwd_max_age;
        $this->_pwd_age_notification    = $config->pwd_age_notification;
        $this->_pwd_min_length          = $config->pwd_min_length;
        $this->_pwd_max_length          = $config->pwd_max_length;
        $this->_pwd_complexity          = $config->pwd_complexity;
        $this->_pwd_complexity_chars    = $config->pwd_complexity_chars;
        $this->_forgot_valid_for        = $config->forgot_valid_for;
        $this->_log_logins              = $config->log_logins;
        $this->_invalid_logins          = $config->invalid_logins;
        $this->_mins_login_attempts     = $config->mins_login_attempts;
        $this->_mins_locked_out         = $config->mins_locked_out;
        $this->_date_format             = $config->date_format;
        $this->_all_roles               = $config->roles;

        $slugs = array_keys($this->_all_roles);
        $this->_admin_role = $slugs[0];

        $this->_login_fields = [];

        $this->phpass = new Phpass([
            'iteration_count_log2' => $config->phpass_iterations,
            'portable_hashes'      => $config->phpass_portable
        ]);

        if ($this->logged_in()) {
            $this->get_session_values();
        } else if (get_cookie('' . $this->_remember_token_name)) {
            $this->login_from_token();
        }

        $this->set_error($this->session->getFlashdata('bitauth_error'), FALSE);
    }

    public function login($username, $password, $remember = FALSE, $extra = [], $token = NULL)
    {
        if (empty($username)) {
            $this->set_error(lang('Bitauth.bitauth_username_required', [lang('Bitauth.bitauth_username')]));
            return FALSE;
        }

        if ($this->locked_out()) {
            $this->set_error(lang('Bitauth.bitauth_user_locked_out', [$this->_mins_locked_out]));
            return FALSE;
        }

        $user = $this->get_user_by_username($username);

        if ($user !== FALSE) {
            if ($this->phpass->CheckPassword($password, $user->password) || ($password === NULL && $user->remember_me == $token)) {
                if (!empty($this->_login_fields) && !$this->check_login_fields($user, $extra)) {
                    $this->log_attempt($user->user_id, FALSE);
                    return FALSE;
                }

                if (!$user->active) {
                    $this->log_attempt($user->user_id, FALSE);
                    $this->set_error(lang('Bitauth.bitauth_user_inactive'));
                    return FALSE;
                }

                if ($this->password_is_expired($user)) {
                    $this->log_attempt($user->user_id, FALSE);
                    $this->set_error(lang('Bitauth.bitauth_pwd_expired'));
                    return FALSE;
                }

                $this->set_session_values($user);

                if ($remember != FALSE) {
                    $this->update_remember_token($user->username, $user->user_id);
                }

                $data = [
                    'last_login'    => $this->timestamp(),
                    'last_login_ip' => ip2long($this->request->getIPAddress())
                ];

                if (!empty($user->forgot_code)) {
                    $data['forgot_code'] = '';
                }

                $this->update_user($user->user_id, $data);
                $this->log_attempt($user->user_id, TRUE);
                return TRUE;
            }
            $this->log_attempt($user->user_id, FALSE);
        } else {
            $this->log_attempt(FALSE, FALSE);
        }

        $this->set_error(lang('Bitauth.bitauth_login_failed', [lang('Bitauth.bitauth_username')]));
        return FALSE;
    }

    public function login_from_token()
    {
        if (($token = get_cookie('' . $this->_remember_token_name))) {
            $token = explode("\n", $token);
            $username = $token[0];

            if ($this->login($username, NULL, (bool)$this->_remember_token_updates, [], $token[1])) {
                return TRUE;
            }
        }

        $this->logout();
        return FALSE;
    }

    public function logout()
    {
        $session_data = $this->session->get();
        foreach ($session_data as $_key => $_value) {
            if (substr($_key, 0, strlen($this->_cookie_elem_prefix)) !== $this->_cookie_elem_prefix) {
                $this->session->remove($_key);
            }
        }

        unset($this->username);
        $this->delete_remember_token();
        return;
    }

    public function check_login_fields($user, $data)
    {
        if (empty($this->_login_fields)) return TRUE;

        foreach ($this->_login_fields as $_field) {
            if (!isset($user->{$_field}) || $user->{$_field} != $data[$_field]) {
                $this->set_error(lang('Bitauth.bitauth_invalid_' . $_field));
                return FALSE;
            }
        }
        return TRUE;
    }

    public function add_login_field($field)
    {
        if (is_array($field)) {
            foreach ($field as $_field) {
                if (strlen(trim($_field))) $this->add_login_field($_field);
            }
            return;
        }

        if (strlen(trim($field))) {
            $this->_login_fields[] = trim($field);
        }
    }

    public function locked_out()
    {
        if ($this->_invalid_logins < 1) return FALSE;

        $builder = $this->db->table($this->_table['logins']);
        $query = $builder->where('ip_address', ip2long($this->request->getIPAddress()))
                         ->where('success', 0)
                         ->orderBy('time', 'DESC')
                         ->limit($this->_invalid_logins)
                         ->get();

        if ($query && $query->getNumRows() == $this->_invalid_logins) {
            $rows = $query->getResult();
            $first = $rows[0];
            $last = $rows[$this->_invalid_logins - 1];

            if ($this->timestamp(strtotime($last->time), 'U') - $this->timestamp(strtotime($first->time), 'U') <= ($this->_mins_login_attempts * 60)
                && $this->timestamp(strtotime($last->time), 'U') >= $this->timestamp(strtotime($this->_mins_login_attempts . ' minutes ago'), 'U')) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function log_attempt($user_id, $success = FALSE)
    {
        if ($this->_log_logins == TRUE) {
            $data = [
                'ip_address' => ip2long($this->request->getIPAddress()),
                'user_id'    => $user_id,
                'success'    => (int)$success,
                'time'       => $this->timestamp()
            ];
            return $this->db->table($this->_table['logins'])->insert($data);
        }
        return TRUE;
    }

    public function set_session_values($values)
    {
        $session_data = [];
        foreach ($values as $_key => $_value) {
            if ($_key !== 'password') {
                $this->$_key = $_value;

                if ($_key == 'roles') {
                    $_value = base64_encode($this->encrypter->encrypt($_value));
                }

                $session_data[$this->_cookie_elem_prefix . $_key] = $_value;
            }
        }
        $this->session->set($session_data);
    }

    public function get_session_values()
    {
        $session_data = $this->session->get();
        if($session_data) {
            foreach ($session_data as $_key => $_value) {
                if (substr($_key, 0, strlen($this->_cookie_elem_prefix)) !== $this->_cookie_elem_prefix) {
                    continue;
                }
    
                $_key = substr($_key, strlen($this->_cookie_elem_prefix));
    
                if (!isset($this->$_key)) {
                    if ($_key == 'roles') {
                        $_value = $this->encrypter->decrypt(base64_decode($_value));
                    }
                    $this->$_key = $_value;
                }
            }
        }
    }

    public function update_remember_token($username = NULL, $user_id = NULL)
    {
        if (!$this->logged_in()) return;

        if ($username === NULL) $username = $this->username;
        if ($user_id === NULL) $user_id = $this->user_id;

        $session_id = sha1(mt_rand(0, PHP_INT_MAX) . time());

        set_cookie([
            'name'   => $this->_remember_token_name,
            'value'  => $username . "\n" . $session_id,
            'expire' => $this->_remember_token_expires,
        ]);

        return $this->update_user($user_id, ['remember_me' => $session_id]);
    }

    public function delete_remember_token()
    {
        if (($token = get_cookie('' . $this->_remember_token_name))) {
            delete_cookie($this->_remember_token_name);
            
            $token = explode("\n", $token);
            $this->db->table($this->_table['users'])
                     ->set('remember_me', '')
                     ->where('username', $token[0])
                     ->where('remember_me', $token[1])
                     ->update();
        }
    }

    public function add_user($data, $require_activation = NULL)
    {
        if (!is_array($data) && !is_object($data)) return FALSE;

        if ($require_activation === NULL) $require_activation = $this->_require_user_activation;
        $data = (array)$data;

        $data['active'] = !(bool)$require_activation;
        if ($require_activation) $data['activation_code'] = $this->generate_code();

        if (!empty($data['user_id'])) unset($data['user_id']);

        if (isset($data['groups'])) {
            $groups = $data['groups'];
            unset($data['groups']);
        }

        $userdata = [];
        foreach ($data as $_key => $_val) {
            if (!in_array($_key, $this->_data_fields)) {
                $userdata[$_key] = $_val;
                unset($data[$_key]);
            }
        }

        $data['password'] = $this->hash_password($data['password']);
        $data['password_last_set'] = $this->timestamp();

        $this->db->transStart();
        $this->db->table($this->_table['users'])->insert($data);
        $user_id = $this->db->insertID();

        if (!empty($userdata)) {
            $userdata['user_id'] = $user_id;
            $this->db->table($this->_table['data'])->insert($userdata);
        }

        if (empty($groups)) {
            $this->db->table($this->_table['assoc'])->insert(['user_id' => $user_id, 'group_id' => $this->_default_group_id]);
        } else {
            $new_groups = [];
            foreach ($groups as $group_id) {
                $new_groups[] = ['user_id' => $user_id, 'group_id' => (int)$group_id];
            }
            $this->db->table($this->_table['assoc'])->insertBatch($new_groups);
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function update_user($id, $data)
    {
        $data = (array)$data;
        unset($data['user_id'], $data['roles'], $data['id']);

        if (isset($data['groups'])) {
            $groups = $data['groups'];
            unset($data['groups']);
        }

        $userdata = [];
        foreach ($data as $_key => $_val) {
            if (!in_array($_key, $this->_data_fields)) {
                $userdata[$_key] = $_val;
                unset($data[$_key]);
            }
        }

        if (isset($data['password']) && strlen($data['password'])) {
            $data['password'] = $this->hash_password($data['password']);
            $data['password_last_set'] = $this->timestamp();
        } else {
            unset($data['password']);
        }

        $this->db->transStart();

        if (!empty($data)) {
            $this->db->table($this->_table['users'])->where('user_id', $id)->update($data);
        }

        if (!empty($userdata)) {
            $this->db->table($this->_table['data'])->where('user_id', $id)->update($userdata);
        }

        if (isset($groups)) {
            $this->db->table($this->_table['assoc'])->where('user_id', $id)->delete();
            if (!empty($groups)) {
                $new_groups = [];
                foreach ($groups as $group_id) {
                    $new_groups[] = ['user_id' => $id, 'group_id' => (int)$group_id];
                }
                $this->db->table($this->_table['assoc'])->insertBatch($new_groups);
            }
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }
// Tambahkan parameter $where
    public function get_users($include_disabled = FALSE, $where = [])
    {
        $builder = $this->db->table($this->_table['users'] . ' users');
        $builder->select('users.*, userdata.*, GROUP_CONCAT(assoc.group_id SEPARATOR "|") AS groups, BIT_OR(groups.roles) AS roles')
                ->join($this->_table['data'] . ' userdata', 'userdata.user_id = users.user_id', 'left')
                ->join($this->_table['assoc'] . ' assoc', 'assoc.user_id = users.user_id', 'left')
                ->join($this->_table['groups'] . ' groups', 'groups.group_id = assoc.group_id', 'left')
                ->groupBy('users.user_id');

        if (!$include_disabled) {
            $builder->where('users.enabled', 1);
        }

        // --- TAMBAHAN BARU ---
        // Kalau ada kondisi where, terapkan ke builder
        if (!empty($where)) {
            $builder->where($where);
        }
        // ---------------------

        $query = $builder->get();
        if ($query && $query->getNumRows()) {
            $ret = [];
            foreach ($query->getResult() as $row) {
                $row->groups = explode('|', $row->groups);
                $row->last_login_ip = long2ip((int)$row->last_login_ip); // Casting ke int untuk mencegah error
                $ret[] = $row;
            }
            return $ret;
        }
        return FALSE;
    }

    public function get_user_by_username($username, $include_disabled = FALSE)
    {
        // Kirim kondisi where sebagai array
        $users = $this->get_users($include_disabled, ['users.username' => $username]);
        return (!empty($users)) ? $users[0] : FALSE;
    }

    public function get_user_by_id($id, $include_disabled = TRUE)
    {
        // Kirim kondisi where sebagai array
        $users = $this->get_users($include_disabled, ['users.user_id' => $id]);
        return (!empty($users)) ? $users[0] : FALSE;
    }

    public function get_groups()
    {
        $builder = $this->db->table($this->_table['groups'] . ' groups');
        $builder->select('groups.*, GROUP_CONCAT(assoc.user_id SEPARATOR "|") AS members')
                ->join($this->_table['assoc'] . ' assoc', 'assoc.group_id = groups.group_id', 'left')
                ->groupBy('groups.group_id');

        $query = $builder->get();
        if ($query && $query->getNumRows()) {
            $ret = [];
            foreach ($query->getResult() as $row) {
                $row->members = explode('|', $row->members);
                $row->roles = base64_encode($this->encrypter->encrypt($row->roles));
                $ret[] = $row;
            }
            return $ret;
        }
        return FALSE;
    }

    public function get_roles()
    {
        return $this->_all_roles;
    }

    public function has_role($slug, $mask = NULL, $bypass = TRUE)
    {
        if ($mask === NULL) $mask = $this->roles;
        if ($mask == 0) return FALSE;

        if (($index = array_search($slug, array_keys($this->_all_roles))) !== FALSE) {
            if ($bypass && $slug != $this->_admin_role && $this->has_role($this->_admin_role, $mask)) {
                return TRUE;
            }
            $check = gmp_init(0);
            gmp_setbit($check, $index);
            return gmp_strval(gmp_and($mask, $check)) === gmp_strval($check);
        }
        return FALSE;
    }

    public function is_admin($mask = NULL)
    {
        return $this->has_role($this->_admin_role, $mask);
    }

    public function logged_in()
    {
        return (bool)$this->session->get($this->_cookie_elem_prefix . 'username');
    }

    public function password_is_expired($user = NULL)
    {
        if ($user === NULL) $user = $this;
        $user = (object)$user;

        if ($this->_pwd_max_age == 0 || $user->password_never_expires == 1) return FALSE;
        return (bool)$this->timestamp(time(), 'U') > (strtotime($user->password_last_set) + ($this->_pwd_max_age * 86400));
    }

    public function timestamp($time = NULL, $format = NULL)
    {
        if ($time === NULL) $time = time();
        if ($format === NULL) $format = $this->_date_format;
        return date($format, $time);
    }

    public function set_error($str, $update_session = TRUE)
    {
        $this->_error = $str;
        if ($update_session == TRUE) {
            $this->session->setFlashdata('bitauth_error', $this->_error);
        }
    }

    public function get_error($incl_delim = TRUE)
    {
        if ($incl_delim) return $this->_error_delim_prefix . $this->_error . $this->_error_delim_suffix;
        return $this->_error;
    }

    public function generate_code()
    {
        return sha1(uniqid() . time());
    }

    public function hash_password($str)
    {
        return $this->phpass->HashPassword($str);
    }

    public function check_pass($password, $stored_hash)
    {
        return $this->phpass->CheckPassword($password, $stored_hash);
    }
}
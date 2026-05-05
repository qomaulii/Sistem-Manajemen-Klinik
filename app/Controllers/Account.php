<?php

namespace App\Controllers;

class Account extends BaseController
{
    // Aktifkan deklarasi properti ini
    protected $bitauth;

    public function __construct()
    {
        helper(['url', 'form', 'date']);
        
        // Aktifkan pemanggilan library ini
        $this->bitauth = new \App\Libraries\Bitauth(); 
    }

    public function login()
    {
        $data = [];

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'username' => 'required|trim',
                'password' => 'required'
            ];

            if ($this->validate($rules)) {
                $postData = $this->request->getPost();
                $remember = isset($postData['remember_me']) ? $postData['remember_me'] : '';

                // Asumsi pemanggilan bitauth
                if ($this->bitauth->login($postData['username'], $postData['password'], $remember)) {
                    $redir = session()->get('redir');
                    if ($redir) {
                        session()->remove('redir');
                    }
                    return redirect()->to($redir ? $redir : '/');
                } else {
                    $data['error'] = $this->bitauth->get_error();
                }
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }

        $data['title'] = 'Login';
        $path = 'account/login';

        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function users($limit = 10, $page = 1)
    {
        if (!$this->bitauth->logged_in()) {
            session()->set('redir', current_url());      
            return redirect()->to('account/login');
        }
        if (!$this->bitauth->is_admin()) {
            return $this->_no_access();
        }
        
        $data['title'] = 'User List';
        $data['bitauth'] = $this->bitauth;
        $data['users'] = $this->bitauth->get_users(TRUE);
        
        $data['total_rows'] = count($data['users']);
        $data['page'] = (int)$page;
        $data['per_page'] = (int)$limit;
        
        // Fitur pagination bawaan CI4 (menggantikan library my_pagination lamamu)
        $data['pagination'] = ""; 
        
        $path = 'account/users';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function signup()
    {
        if ($this->bitauth->get_users() && !$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }

        if ($this->bitauth->get_users() && !$this->bitauth->is_admin()) {
            return $this->_no_access();
        }
        
        $data = [];
        $data['roles_option'] = config('Bitauth')->roles; // Mengambil config dari CI4
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username'      => 'required|trim',
                'first_name'    => 'trim',
                'last_name'     => 'trim',
                'fname'         => 'trim',
                'gender'        => 'required',
                'email'         => 'required|trim|valid_email',
                'phone'         => 'required|trim',
                'social_id'     => 'required|trim',
                'id_type'       => 'required|trim',
                'position'      => 'required|trim',
                'password'      => 'required',
                'password_conf' => 'required|matches[password]',
            ];
            
            if ($this->validate($rules)) {
                $user = $this->request->getPost();
                unset($user['submit'], $user['password_conf']);
                
                $user['birth_date'] = strtotime($user['birth_date']);
                $user['create_date'] = time();
                
                $db = \Config\Database::connect();
                foreach (array_keys($data['roles_option']) as $key => $value) {
                    if ($value == $user['position']) {
                        $role = pow(2, $key);
                        $builder = $db->table('groups')->select('group_id')->where('roles', $role);
                        $query = $builder->get();
                        foreach ($query->getResult() as $row) {
                            $user['groups'] = [$row->group_id];
                            break;
                        }
                    }
                }
                
                if ($this->bitauth->add_user($user)) {
                    $data['script'] = '<script>alert("'. esc($user['username']). ' has been registered successfuly.");</script>';
                } else {
                    $data['error'] = '<div class="alert alert-danger">Registring user: '. esc($user['username']). ' is failed.</div>';
                }
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }
        
        $data['title'] = 'Sign up'; 
        $data['id_type_options'] = $this->_id_type_options();
        
        $path = 'account/add_user';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function edit_user($user_id = 0)
    {
        $data = [];
        if (!$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }

        if (!$this->bitauth->is_admin()) {
            if (session()->get('ba_user_id') != $user_id) {
                return $this->_no_access();
            }
        }
          
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username'  => 'required|trim',
                'email'     => 'required|trim|valid_email',
                'gender'    => 'required',
                'phone'     => 'required|trim',
                'social_id' => 'required|trim',
                'id_type'   => 'required|trim',
                'position'  => 'required|trim'
            ];

            $postData = $this->request->getPost();

            if (!empty($postData['password'])) {
                if (!$this->bitauth->is_admin()) {
                    $rules['old_password'] = 'required';
                }
                $rules['password'] = 'required';
                $rules['password_conf'] = 'required|matches[password]';
            }

            if ($this->validate($rules)) {
                $user = $postData;
                unset($user['submit'], $user['password_conf']);
                
                $user['active'] = isset($user['active']) ? $user['active'] : 0;
                $user['enabled'] = isset($user['enabled']) ? $user['enabled'] : 0;
                $user['password_never_expires'] = isset($user['password_never_expires']) ? $user['password_never_expires'] : 0;
          
                if (!$this->bitauth->is_admin()) {
                    unset($user['active'], $user['enabled'], $user['password_never_expires'], $user['groups[]']);
                }

                // Upload Picture dengan gaya CI4
                $picture = $this->request->getFile('picture');
                if ($picture && $picture->isValid() && !$picture->hasMoved()) {
                    $path = 'uploads/hospital/staff/' . $user_id . '/';
                    $newName = $user_id . '_profile_picture.' . $picture->getExtension();
                    
                    $picture->move($path, $newName);
                    $user['picture'] = $path . $newName;
                    
                    $_user = $this->bitauth->get_user_by_id($user_id);
                    if (isset($_user->picture) && $_user->picture != $user['picture']) {
                        @unlink('./' . $_user->picture); 
                    }
                }

                $user['birth_date'] = strtotime($user['birth_date']);
                
                if (!$this->bitauth->is_admin() && isset($user['password']) && strlen($user['password'])) {
                    $tmp = $this->bitauth->get_user_by_id($user_id);
                    if (isset($user['old_password']) && $this->bitauth->check_pass($user['old_password'], $tmp->password)) {
                        unset($user['old_password']);
                        $this->bitauth->update_user($user_id, $user);
                    }
                } else {
                    if (isset($user['old_password'])) unset($user['old_password']);
                    $this->bitauth->update_user($user_id, $user);
                }
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }

        $groups = [];
        foreach ($this->bitauth->get_groups() as $_group) {
            $groups[$_group->group_id] = $_group->name;
        }

        $data['title'] = 'Edit User';
        $data['bitauth'] = $this->bitauth;
        $data['groups'] = $groups;
        $data['user'] = $this->bitauth->get_user_by_id($user_id);
        $data['id_type_options'] = $this->_id_type_options();
        
        $path = 'account/edit_user';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function _id_type_options()
    {
        return [
            'Tazkara' => 'Tazkara',
            'Passport' => 'Passport',
            'Driver License' => 'Driver License',
            'Bank ID Card' => 'Bank ID Card',
        ];
    }

    public function groups($limit = 10, $page = 1)
    {
        if ($this->bitauth->get_users() && !$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }

        if ($this->bitauth->get_users() && !$this->bitauth->is_admin()) {
            return $this->_no_access();
        }
        
        $data['title'] = 'Groups';
        $data['bitauth'] = $this->bitauth;
        $data['groups'] = $this->bitauth->get_groups();
        
        $data['total_rows'] = count($data['groups']);
        $data['page'] = (int)$page;
        $data['per_page'] = (int)$limit;
        $data['pagination'] = ""; // Pagination CI4
        
        $path = 'account/groups';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function add_group()
    {
        // Cek Login & Admin
        if (!$this->bitauth->logged_in()) return redirect()->to('account/login');
        if (!$this->bitauth->is_admin()) return $this->_no_access();

        $data = [];

        // Perbaikan: Gunakan strtolower agar mendeteksi 'POST' dari browser
        if (strtolower($this->request->getMethod()) === 'post') {
            // Perbaikan: Hapus 'trim' dari rules validasi CI4
            $rules = [
                'name' => 'required' 
            ];

            if ($this->validate($rules)) {
                $postData = $this->request->getPost();
                unset($postData['submit']);
                
                if($this->bitauth->add_group($postData)) {
                    return redirect()->to('account/groups')->with('success', 'Group added successfully');
                }
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }

        // Ambil data User untuk pilihan Members
        $users = [];
        $all_users = $this->bitauth->get_users();
        if ($all_users) {
            foreach ($all_users as $_user) {
                $users[$_user->user_id] = $_user->first_name . ' ' . $_user->last_name;
            }
        }

        $data['title'] = 'Add Group';
        $data['roles'] = $this->bitauth->get_roles();
        $data['users'] = $users;
        
        // Pastikan variabel ini dikirim sebagai array kosong agar View tidak error
        $data['selected_users'] = []; 

        $path = 'account/add_group';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function remove_group($group_id = 0)
    {
        if ($this->bitauth->get_users() && !$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }

        if ($this->bitauth->get_users() && !$this->bitauth->is_admin()) {
            return $this->_no_access();
        }
        
        if ($this->request->getMethod() === 'post') {
            if ($this->request->getPost('del')) {
                $this->bitauth->delete_group($group_id);
                return redirect()->to('account/groups');
            }
        }
        
        $data['title'] = 'Delete Group';
        $data['url'] = 'account/remove_group/' . $group_id;
        $group = $this->bitauth->get_group_by_id($group_id);
        $data['id'] = $group->group_id;
        $data['name'] = $group->name;
        
        return view('account/confirm_delete', $data);
    }

    public function edit_group($group_id = 0)
    {
        if ($this->bitauth->get_users() && !$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }

        if ($this->bitauth->get_users() && !$this->bitauth->is_admin()) {
            return $this->_no_access();
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|trim'
            ];

            if ($this->validate($rules)) {
                $postData = $this->request->getPost();
                unset($postData['submit']);
                $this->bitauth->update_group($group_id, $postData);
                echo '<script>alert("Group edited successfully.");</script>';
                return;
            }
        }

        $data = [];
        $users = [];
        foreach ($this->bitauth->get_users() as $_user) {
            $users[$_user->user_id] = $_user->first_name . ' ' . $_user->last_name;
        }

        if ($group = $this->bitauth->get_group_by_id($group_id)) {
            $role_list = [];
            $roles = $this->bitauth->get_roles();
            
            $encrypter = \Config\Services::encrypter(); // Memanggil library encrypt CI4
            
            foreach ($roles as $_slug => $_desc) {
                // Konversi enkripsi lama ke baru
                if ($this->bitauth->has_role($_slug, $encrypter->decrypt($group->roles), FALSE)) {
                    $role_list[] = $_slug;
                }
            }
            $data['title'] = 'Edit Group ' . $group->name;
            $data['bitauth'] = $this->bitauth;
            $data['roles'] = $roles;
            $data['group'] = $group;
            $data['group_roles'] = $role_list;
            $data['users'] = $users;
        } else {
            $data['title'] = 'Edit Group';
        }
        
        $path = 'account/edit_group';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function activate($activation_code)
    {
        if ($this->bitauth->activate($activation_code)) {
            echo "User successfully activated";
            return;
        }
        echo "Activation failed!";
    }

    public function deactivate($user_id)
    {
        if (!$this->bitauth->is_admin()) {
            echo 'no';
            return;
        }
        if ($this->bitauth->deactivate($user_id)) {
            echo 'true';
            return;
        }
        echo 'false';
    }

    public function checkAuth($perm_key = 0) 
    {
        if (!$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }
        if ($this->bitauth->has_permission($perm_key)) {
            echo "Has $perm_key privilege";
        } else {
            echo "$perm_key Restricted ";
        }
    }

    public function logout()
    {
        $this->bitauth->logout();
        return redirect()->to('home');
    }
  
    public function _no_access()
    {
        $data['title'] = 'Unauthorized Access';
        $path = 'account/no_access';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function generate_dummy()
    {
        $db = \Config\Database::connect();
        
        // 1. Buat data grup (Role)
        $groups = [
            ['name' => 'Administrator', 'description' => 'Sistem Admin', 'roles' => 1],
            ['name' => 'Doctor', 'description' => 'Dokter Klinik', 'roles' => 2],
            ['name' => 'Receptionist', 'description' => 'Resepsionis Klinik', 'roles' => 4],
        ];
        
        foreach ($groups as $g) {
            if ($db->table('groups')->where('name', $g['name'])->countAllResults() == 0) {
                $db->table('groups')->insert($g);
            }
        }

        // Ambil ID grup yang baru dibuat
        $idAdmin = $db->table('groups')->where('name', 'Administrator')->get()->getRow()->group_id;
        $idDoctor = $db->table('groups')->where('name', 'Doctor')->get()->getRow()->group_id;
        $idResep = $db->table('groups')->where('name', 'Receptionist')->get()->getRow()->group_id;

        // 2. Setup Data Dasar User (menghindari error wajib isi di database)
        $baseData = [
            'gender'      => 'Male',
            'email'       => 'dummy@klinik.com',
            'phone'       => '08123456789',
            'social_id'   => '-',
            'id_type'     => 'Tazkara',
            'active'      => 1,
            'enabled'     => 1,
            'birth_date'  => time(), 
            'create_date' => time()
        ];

        // 3. Buat Akun Dummy
        $usersToCreate = [
            array_merge($baseData, [
                'username'   => 'admin',
                'password'   => 'admin123',
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'position'   => 'Administrator',
                'groups'     => [$idAdmin]
            ]),
            array_merge($baseData, [
                'username'   => 'dokter1',
                'password'   => 'dokter123',
                'first_name' => 'Dr. Budi',
                'last_name'  => 'Santoso',
                'position'   => 'Doctor',
                'groups'     => [$idDoctor]
            ]),
            array_merge($baseData, [
                'username'   => 'resep1',
                'password'   => 'resep123',
                'first_name' => 'Siti',
                'last_name'  => 'Rahayu',
                'position'   => 'Receptionist',
                'gender'     => 'Female',
                'groups'     => [$idResep]
            ])
        ];

        foreach ($usersToCreate as $user) {
            // Cek apakah username sudah ada biar tidak dobel
            if ($db->table('users')->where('username', $user['username'])->countAllResults() == 0) {
                $this->bitauth->add_user($user);
            }
        }

        echo "<h1 style='color:green;'>Sukses!</h1>";
        echo "<p>Data dummy berhasil dimasukkan ke SQL. Silakan login menggunakan akun berikut:</p>";
        echo "<ul>
                <li><b>Admin:</b> admin <br> <b>Password:</b> admin123</li>
                <li><b>Dokter:</b> dokter1 <br> <b>Password:</b> dokter123</li>
                <li><b>Resepsionis:</b> resep1 <br> <b>Password:</b> resep123</li>
              </ul>";
    }
}
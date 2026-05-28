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

    public function register()
    {
        // PERBAIKAN FATAL: Menghancurkan sesi secara diam-diam tanpa memicu Infinite Redirect Loop.
        if ($this->bitauth->logged_in()) {
            $this->bitauth->logout();
            session()->destroy(); // Memastikan sesi CI4 benar-benar dibersihkan
        }

        $data = [];

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'username'          => 'required|is_unique[users.username]',
                'password'          => 'required',
                'password_conf'     => 'required|matches[password]',
                'first_name'        => 'required',
                'nik'               => 'required',
                'birth_date'        => 'required',
                'email'             => 'required|valid_email',
                'phone'             => 'required',
                'identity_document' => 'uploaded[identity_document]|is_image[identity_document]|mime_in[identity_document,image/jpg,image/jpeg,image/png]'
            ];

            if ($this->validate($rules)) {
                $postData = $this->request->getPost();
                $db = \Config\Database::connect();
                
                $pasienGroup = $db->table('groups')->where('name', 'Pasien')->get()->getRow();
                $pasienGroupId = $pasienGroup ? $pasienGroup->group_id : 8; 

                $user = [
                    'username'   => $postData['username'],
                    'password'   => $postData['password'],
                    'first_name' => $postData['first_name'],
                    'last_name'  => $postData['last_name'] ?? '',
                    'active'     => 1, 
                    'enabled'    => 1,
                    'password_never_expires' => 1,
                    'groups'     => [$pasienGroupId] // Mutlak masuk ke grup Pasien
                ];

                if ($this->bitauth->add_user($user)) {
                    $newUser = $db->table('users')->where('username', $user['username'])->get()->getRow();
                    
                    if ($newUser) {
                        $updateData = [
                            'nik'        => $postData['nik'],
                            'nip'        => $postData['nip'] ?? '', 
                            'gender'     => $postData['gender'] ?? 0,
                            'address'    => $postData['address'] ?? '',
                            'position'   => 'Pasien', // Jabatan mutlak sebagai Pasien
                            'email'      => $postData['email'],
                            'phone'      => $postData['phone'],
                            'birth_date' => strtotime($postData['birth_date'])
                        ];

                        $identityFile = $this->request->getFile('identity_document');
                        if ($identityFile && $identityFile->isValid() && !$identityFile->hasMoved()) {
                            $path    = 'uploads/patients/identity/';
                            $newName = $newUser->user_id . '_identitas.' . $identityFile->getExtension();
                            $identityFile->move($path, $newName);
                            $updateData['identity_document'] = $path . $newName;
                        }

                        $pictureFile = $this->request->getFile('picture');
                        if ($pictureFile && $pictureFile->isValid() && !$pictureFile->hasMoved()) {
                            $pathPic    = 'uploads/hospital/staff/' . $newUser->user_id . '/';
                            $newPicName = $newUser->user_id . '_profile_picture.' . $pictureFile->getExtension();
                            $pictureFile->move($pathPic, $newPicName);
                            $updateData['picture'] = $pathPic . $newPicName;
                        }
                        
                        $db->table('userdata')->where('user_id', $newUser->user_id)->update($updateData);
                    }

                    // Registrasi berhasil, arahkan ke Login
                    return redirect()->to('account/login')->with('success', 'Registrasi berhasil! Silakan masuk menggunakan Username dan Kata Sandi yang baru saja Anda buat.');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Sistem gagal mendaftarkan akun Anda. Silakan coba lagi.');
                }
            } else {
                return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
            }
        }

        $data['title'] = 'Registrasi Pasien';

        $path = 'account/register';
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
            // PERBAIKAN ERROR 1: Casting current_url() menjadi tipe data string
            session()->set('redir', (string) current_url());      
            return redirect()->to('account/login');
        }
        if (!$this->bitauth->is_admin()) {
            return $this->_no_access();
        }
        
        $data['title']   = 'Daftar Pengguna'; 
        $data['bitauth'] = $this->bitauth;
        $data['users']   = $this->bitauth->get_users(TRUE);
        
        $data['total_rows'] = count($data['users']);
        $data['page']       = (int)$page;
        $data['per_page']   = (int)$limit;
        
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
            session()->set('redir', (string) current_url());
            return redirect()->to('account/login');
        }

        if ($this->bitauth->get_users() && !$this->bitauth->is_admin()) {
            return $this->_no_access();
        }
        
        $data = [];
        
        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'username'      => 'required|is_unique[users.username]', 
                'password'      => 'required',
                'password_conf' => 'required|matches[password]',
                'first_name'    => 'required',
                'nik'           => 'required',
                'email'         => 'required|valid_email',
                'phone'         => 'required',
                'position'      => 'required',
                'groups'        => 'required' 
            ];
            
            if ($this->validate($rules)) {
                $user = $this->request->getPost();
                unset($user['submit'], $user['password_conf']);
                
                $user['birth_date']  = !empty($user['birth_date']) ? strtotime($user['birth_date']) : 0;
                $user['create_date'] = time();
                
                $user['active']  = isset($user['active']) ? (int)$user['active'] : 1;
                $user['enabled'] = 1;
                $user['password_never_expires'] = 1;
                
                if ($this->bitauth->add_user($user)) {
                    $db = \Config\Database::connect();
                    $newUser = $db->table('users')->where('username', $user['username'])->get()->getRow();
                    
                    if ($newUser) {
                        $newUserId = $newUser->user_id;
                        $picture   = $this->request->getFile('picture');
                        
                        $updateData = [
                            'nik'        => $user['nik'],
                            'nip'        => $user['nip'] ?? '',
                            'gender'     => $user['gender'] ?? 0,
                            'address'    => $user['address'] ?? '',
                            'position'   => $user['position'],
                            'birth_date' => $user['birth_date']
                        ];
                        
                        if ($picture && $picture->isValid() && !$picture->hasMoved()) {
                            $path    = 'uploads/hospital/staff/' . $newUserId . '/';
                            $newName = $newUserId . '_profile_picture.' . $picture->getExtension();
                            
                            $picture->move($path, $newName);
                            $updateData['picture'] = $path . $newName;
                        }
                        
                        // PERBAIKAN: Menyimpan NIK, NIP, dsb secara dinamis ke userdata
                        $db->table('userdata')->where('user_id', $newUserId)->update($updateData);
                    }

                    return redirect()->to('account/users')->with('success', 'Pengguna baru berhasil ditambahkan.');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pengguna ke basis data.');
                }
            } else {
                return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
            }
        }
        
        $groups = [];
        foreach ($this->bitauth->get_groups() as $_group) {
            $groups[$_group->group_id] = $_group->name;
        }
        $data['groups'] = $groups;
        
        $data['title'] = 'Tambah Pengguna Baru'; 
        
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
            // PERBAIKAN ERROR 1: Casting objek URL ke (string)
            session()->set('redir', (string) current_url());
            return redirect()->to('account/login');
        }

        if (!$this->bitauth->is_admin()) {
            if (session()->get('ba_user_id') != $user_id) {
                return $this->_no_access();
            }
        }
          
        if (strtolower($this->request->getMethod()) === 'post') {
            
            $rules = [
                'first_name' => 'required',
                'email'      => 'required|valid_email',
                'phone'      => 'required',
                'position'   => 'required',
                'nik'        => 'required'
            ];

            $postData = $this->request->getPost();

            if (!empty($postData['password'])) {
                if (!$this->bitauth->is_admin()) {
                    $rules['old_password'] = 'required';
                }
                $rules['password']      = 'required';
                $rules['password_conf'] = 'required|matches[password]';
            }

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
            }

            $user = $postData;
            unset($user['submit'], $user['password_conf']);

            if ($this->bitauth->is_admin()) {
                $user['active'] = isset($user['active']) ? (int)$user['active'] : 0;
            } else {
                unset($user['active'], $user['enabled'], $user['password_never_expires'], $user['groups[]']);
            }

            $user['enabled'] = 1; 
            $user['password_never_expires'] = 1;

            $picture = $this->request->getFile('picture');
            if ($picture && $picture->isValid() && !$picture->hasMoved()) {
                $path    = 'uploads/hospital/staff/' . $user_id . '/';
                $newName = $user_id . '_profile_picture.' . $picture->getExtension();
                
                $picture->move($path, $newName);
                $user['picture'] = $path . $newName;
                
                $_user = $this->bitauth->get_user_by_id($user_id);
                if (isset($_user->picture) && $_user->picture != $user['picture'] && file_exists('./' . $_user->picture)) {
                    @unlink('./' . $_user->picture); 
                }
            }

            // PERBAIKAN ERROR 1: Validasi waktu agar nilai kembalian stabil (Integer)
            $user['birth_date'] = !empty($user['birth_date']) ? strtotime($user['birth_date']) : 0;
            
            if (!$this->bitauth->is_admin() && isset($user['password']) && strlen($user['password'])) {
                $tmp = $this->bitauth->get_user_by_id($user_id);
                if (isset($user['old_password']) && $this->bitauth->check_pass($user['old_password'], $tmp->password)) {
                    unset($user['old_password']);
                    $this->bitauth->update_user($user_id, $user);
                } else {
                    return redirect()->back()->withInput()->with('error', 'Kata sandi lama tidak cocok.');
                }
            } else {
                if (isset($user['old_password'])) unset($user['old_password']);
                $this->bitauth->update_user($user_id, $user);
            }

            $db = \Config\Database::connect();
            $updateData = [
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'] ?? '',
                'nip'        => $user['nip'] ?? '',
                'nik'        => $user['nik'],
                'gender'     => $user['gender'] ?? 0,
                'email'      => $user['email'],
                'phone'      => $user['phone'],
                'address'    => $user['address'] ?? '',
                'position'   => $user['position'],
                'birth_date' => $user['birth_date']
            ];
            
            if (isset($user['picture'])) {
                $updateData['picture'] = $user['picture'];
            }
            
            $db->table('userdata')->where('user_id', $user_id)->update($updateData);

            return redirect()->to('account/users')->with('success', 'Perubahan berhasil dilakukan.');
        }

        $groups = [];
        foreach ($this->bitauth->get_groups() as $_group) {
            $groups[$_group->group_id] = $_group->name;
        }

        $data['title']  = 'Edit Pengguna';
        $data['bitauth']= $this->bitauth;
        $data['groups'] = $groups;
        $data['user']   = $this->bitauth->get_user_by_id($user_id);
        
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
        if (!$this->bitauth->logged_in() || !$this->bitauth->is_admin()) {
            return redirect()->to('account/login');
        }
        
        $data['title']   = 'Direktori Grup';
        $data['groups']  = $this->bitauth->get_groups();
        
        // BARIS PERBAIKAN: Mengirimkan identitas admin ke View agar tombol Aksi muncul
        $data['bitauth'] = $this->bitauth; 
        
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
        if (!$this->bitauth->logged_in() || !$this->bitauth->is_admin()) {
            return redirect()->to('account/login');
        }

        $data = [];

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = ['name' => 'required'];

            if ($this->validate($rules)) {
                $postData = $this->request->getPost();
                $db = \Config\Database::connect();
                
                // Kalkulasi Bitmask untuk Hak Akses (Roles)
                $rolesMask = 0;
                if (!empty($postData['roles'])) {
                    $all_roles = $this->bitauth->get_roles();
                    $role_keys = array_keys($all_roles);
                    foreach ($postData['roles'] as $r) {
                        $idx = array_search($r, $role_keys);
                        if ($idx !== false) {
                            $rolesMask |= (1 << $idx); // Formula 2^n
                        }
                    }
                }

                // Bypass Library: Insert langsung ke database
                $db->table('groups')->insert([
                    'name'        => $postData['name'],
                    'description' => $postData['description'] ?? '',
                    'roles'       => $rolesMask
                ]);
                $newGroupId = $db->insertID();

                // Memasukkan anggota (members) jika ada
                if (!empty($postData['members'])) {
                    $insertMemberData = [];
                    foreach ($postData['members'] as $userId) {
                        $insertMemberData[] = [
                            'user_id'  => (int)$userId,
                            'group_id' => $newGroupId
                        ];
                    }
                    $db->table('user_group')->insertBatch($insertMemberData);
                }

                return redirect()->to('account/groups')->with('success', 'Grup baru berhasil dibuat beserta anggotanya.');
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }

        $users = [];
        if ($all_users = $this->bitauth->get_users()) {
            foreach ($all_users as $_user) {
                $users[$_user->user_id] = trim($_user->first_name . ' ' . $_user->last_name);
            }
            asort($users); 
        }

        $data['title'] = 'Membuat Grup Baru';
        $data['roles'] = $this->bitauth->get_roles();
        $data['users'] = $users;
        
        $path = 'account/add_group';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function edit_group($group_id = 0)
    {
        if (!$this->bitauth->logged_in() || !$this->bitauth->is_admin()) {
            return redirect()->to('account/login');
        }

        $db = \Config\Database::connect();

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = ['name' => 'required'];

            if ($this->validate($rules)) {
                $postData = $this->request->getPost();
                
                // Kalkulasi ulang Bitmask Hak Akses
                $rolesMask = 0;
                if (!empty($postData['roles'])) {
                    $all_roles = $this->bitauth->get_roles();
                    $role_keys = array_keys($all_roles);
                    foreach ($postData['roles'] as $r) {
                        $idx = array_search($r, $role_keys);
                        if ($idx !== false) {
                            $rolesMask |= (1 << $idx);
                        }
                    }
                }

                $groupUpdateData = [
                    'name'  => $postData['name'],
                    'roles' => $rolesMask // PERBAIKAN: Memastikan pembaruan hak akses tersimpan
                ];
                if (isset($postData['description'])) {
                    $groupUpdateData['description'] = $postData['description'];
                }

                $db->table('groups')->where('group_id', $group_id)->update($groupUpdateData);

                $members = isset($postData['members']) ? $postData['members'] : [];
                
                $db->table('user_group')->where('group_id', $group_id)->delete();
                
                if (!empty($members)) {
                    $insertMemberData = [];
                    foreach ($members as $userId) {
                        $insertMemberData[] = [
                            'user_id'  => (int)$userId,
                            'group_id' => (int)$group_id
                        ];
                    }
                    $db->table('user_group')->insertBatch($insertMemberData);
                }

                return redirect()->to('account/groups')->with('success', 'Pembaruan grup dan otorisasi berhasil disimpan.');
            }
        }

        $data = [];
        $users = [];
        if ($all_users = $this->bitauth->get_users()) {
            foreach ($all_users as $_user) {
                $users[$_user->user_id] = trim($_user->first_name . ' ' . $_user->last_name);
            }
            asort($users);
        }

        $group = $db->table('groups')->where('group_id', $group_id)->get()->getRow();

        if ($group) {
            $membersQuery = $db->table('user_group')->where('group_id', $group_id)->get()->getResult();
            $group->members = [];
            foreach ($membersQuery as $row) {
                $group->members[] = $row->user_id;
            }

            $role_list = [];
            $roles = $this->bitauth->get_roles();
            
            foreach ($roles as $_slug => $_desc) {
                if ($this->bitauth->has_role($_slug, $group->roles)) {
                    $role_list[] = $_slug;
                }
            }
            
            $data['title']       = 'Edit Grup: ' . $group->name;
            $data['roles']       = $roles;
            $data['group']       = $group;
            $data['group_roles'] = $role_list;
            $data['users']       = $users;
        } else {
            return redirect()->to('account/groups')->with('error', 'Grup tidak ditemukan.');
        }
        
        $path = 'account/edit_group';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function delete_group($group_id = 0)
    {
        if (!$this->bitauth->logged_in() || !$this->bitauth->is_admin()) {
            return redirect()->to('account/login');
        }
        
        $db = \Config\Database::connect();
        
        // PERBAIKAN: Membuka transaksi untuk menjamin kedua tabel terhapus bersamaan
        $db->transStart();
        
        // 1. Hapus semua relasi pengguna yang tergabung di grup ini terlebih dahulu
        $db->table('user_group')->where('group_id', $group_id)->delete();
        
        // 2. Hapus data utama grup tersebut
        $db->table('groups')->where('group_id', $group_id)->delete();
        
        // Menutup dan mengevaluasi transaksi
        $db->transComplete();

        // Jika terjadi kegagalan di level database, batalkan dan beri tahu admin
        if ($db->transStatus() === false) {
            return redirect()->to('account/groups')->with('error', 'Gagal menghapus grup karena kesalahan sistem basis data.');
        }

        return redirect()->to('account/groups')->with('success', 'Grup beserta seluruh hak akses anggotanya berhasil dihapus secara permanen.');
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
            // PERBAIKAN ERROR 1: Casting objek URL ke (string)
            session()->set('redir', (string) current_url());
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
        // Mengubah arah redirect dari 'home' menjadi halaman login
        return redirect()->to('account/login');
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
        
        // PERBAIKAN: Penyesuaian nama grup dan deskripsi agar sinkron dengan tabel `groups` di clinic.sql
        $groups = [
            ['name' => 'Administrator', 'description' => 'Memiliki kendali penuh atas manajemen pengguna, grup hak akses, dan pengaturan inti sistem.', 'roles' => 1],
            ['name' => 'Dokter', 'description' => 'Menganalisis rekam medis, membuat resep obat, serta merujuk tes lab dan radiologi.', 'roles' => 4],
            ['name' => 'Radiografer', 'description' => 'Mengelola jadwal pasien, mengunggah hasil pindai X-Ray, dan memperbarui rekam radiologi.', 'roles' => 8],
            ['name' => 'Analis Lab', 'description' => 'Mengelola jadwal tes laboratorium, menginput hasil tes sampel, dan menerbitkan dokumen lab.', 'roles' => 16],
            ['name' => 'Apoteker', 'description' => 'Mengelola inventaris obat, memperbarui stok, dan mencatat transaksi pengambilan resep.', 'roles' => 32],
            ['name' => 'Resepsionis', 'description' => 'Mengatur pendaftaran pasien, mengelola antrean harian, dan mencetak tagihan pembayaran.', 'roles' => 64],
            ['name' => 'Pasien', 'description' => 'Akses mandiri untuk melihat riwayat kunjungan, hasil lab/X-Ray, dan status antrean.', 'roles' => 128],
        ];
        
        foreach ($groups as $g) {
            if ($db->table('groups')->where('name', $g['name'])->countAllResults() == 0) {
                $db->table('groups')->insert($g);
            }
        }

        // PERBAIKAN: Pemanggilan string nama grup disesuaikan dengan bahasa Indonesia
        $idAdmin  = $db->table('groups')->where('name', 'Administrator')->get()->getRow()->group_id;
        $idDoctor = $db->table('groups')->where('name', 'Dokter')->get()->getRow()->group_id;
        $idResep  = $db->table('groups')->where('name', 'Resepsionis')->get()->getRow()->group_id;
        $idLab    = $db->table('groups')->where('name', 'Analis Lab')->get()->getRow()->group_id;
        $idApotek = $db->table('groups')->where('name', 'Apoteker')->get()->getRow()->group_id;
        $idXray   = $db->table('groups')->where('name', 'Radiografer')->get()->getRow()->group_id;
        $idPasien = $db->table('groups')->where('name', 'Pasien')->get()->getRow()->group_id;

        // Setup Data Dasar User
        // PERBAIKAN: gender disesuaikan menjadi integer (1/0) karena kolom di database adalah tinyint(1)
        $baseData = [
            'gender'      => 1, // Asumsi 1 = Laki-laki, 0 = Perempuan
            'email'       => 'dummy@klinik.com',
            'phone'       => '08123456789',
            'active'      => 1,
            'enabled'     => 1,
            'birth_date'  => time(), 
            'create_date' => time()
        ];

        // Buat Akun Dummy
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
                'position'   => 'Dokter Umum',
                'groups'     => [$idDoctor]
            ]),
            array_merge($baseData, [
                'username'   => 'resep1',
                'password'   => 'resep123',
                'first_name' => 'Siti',
                'last_name'  => 'Rahayu',
                'position'   => 'Resepsionis',
                'gender'     => 0,
                'groups'     => [$idResep]
            ]),
            array_merge($baseData, [
                'username'   => 'lab1',
                'password'   => 'lab123',
                'first_name' => 'Budi',
                'last_name'  => 'Laboratorium',
                'position'   => 'Analis Lab',
                'groups'     => [$idLab]
            ]),
            array_merge($baseData, [
                'username'   => 'apotek1',
                'password'   => 'apotek123',
                'first_name' => 'Ani',
                'last_name'  => 'Apoteker',
                'position'   => 'Apoteker',
                'gender'     => 0,
                'groups'     => [$idApotek]
            ]),
            array_merge($baseData, [
                'username'   => 'xray1',
                'password'   => 'xray123',
                'first_name' => 'Joko',
                'last_name'  => 'Radiologi',
                'position'   => 'Radiografer',
                'groups'     => [$idXray]
            ]),
            array_merge($baseData, [
                'username'   => 'pasien1',
                'password'   => 'pasien123',
                'first_name' => 'Pasien',
                'last_name'  => 'Satu',
                'position'   => 'Pasien',
                'groups'     => [$idPasien]
            ])
        ];

        foreach ($usersToCreate as $user) {
            if ($db->table('users')->where('username', $user['username'])->countAllResults() == 0) {
                $this->bitauth->add_user($user);
            }
        }

        $html = "<h1 style='color:green;'>Sukses!</h1>";
        $html .= "<p>Data dummy berhasil disinkronkan ke SQL. Silakan login menggunakan akun berikut:</p>";
        $html .= "<ul>
                    <li><b>Admin:</b> admin <br> <b>Password:</b> admin123</li>
                    <li><b>Dokter:</b> dokter1 <br> <b>Password:</b> dokter123</li>
                    <li><b>Resepsionis:</b> resep1 <br> <b>Password:</b> resep123</li>
                    <li><b>Lab:</b> lab1 <br> <b>Password:</b> lab123</li>
                    <li><b>Apoteker:</b> apotek1 <br> <b>Password:</b> apotek123</li>
                    <li><b>Radiologi:</b> xray1 <br> <b>Password:</b> xray123</li>
                    <li><b>Pasien:</b> pasien1 <br> <b>Password:</b> pasien123</li>
                  </ul>";
                  
        return $html;
    }

    /**
     * Mengubah status akun (Suspend / Aktifkan)
     */
    public function toggle_status($user_id = 0)
    {
        if (!$this->bitauth->logged_in() || !$this->bitauth->is_admin()) {
            return redirect()->to('account/login');
        }

        $user = $this->bitauth->get_user_by_id($user_id);
        
        if ($user) {
            // Jika status 1 (Aktif) maka ubah ke 0 (Suspend), dan sebaliknya
            $new_status = $user->active == 1 ? 0 : 1;
            
            // Eksekusi perubahan ke database
            $this->bitauth->update_user($user_id, ['active' => $new_status]);
            
            $msg = $new_status == 0 ? 'Status akun berhasil diubah menjadi SUSPEND.' : 'Akun berhasil DIAKTIFKAN kembali.';
            return redirect()->to('account/users')->with('success', $msg);
        }
        
        return redirect()->to('account/users')->with('error', 'Pengguna tidak ditemukan.');
    }
}
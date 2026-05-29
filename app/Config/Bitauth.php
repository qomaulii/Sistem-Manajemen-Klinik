<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Bitauth extends BaseConfig
{
    /**
     * Users must be activated before they can login
     */
    public $require_user_activation = false;

    /**
     * Default group_id users are added to when they first register
     */
    public $default_group_id = 2; // guest

    public $remember_token_name = 'rememberme';
    public $remember_token_expires = 604800;
    public $remember_token_updates = true;

    public $pwd_max_age = 180;
    public $pwd_age_notification = 7;
    public $pwd_min_length = 1;
    public $pwd_max_length = 20;

    public $pwd_complexity = [
        'uppercase' => 0,
        'number'    => 0,
        'special'   => 0,
        'spaces'    => 0,
    ];

    public $pwd_complexity_chars = [
        'uppercase' => '[[:upper:]]',
        'number'    => '[[:digit:]]',
        'special'   => '[[:punct:]]',
        'spaces'    => '\s'
    ];

    public $forgot_valid_for = 3600;
    public $log_logins = true;
    public $invalid_logins = 5;
    public $mins_login_attempts = 5;
    public $mins_locked_out = 10;

    /**
     * Tables used by BitAuth
     */
    public $table = [
        'users'      => 'users',
        'data'       => 'userdata',
        'groups'     => 'groups',
        'assoc'      => 'user_group',
        'logins'     => 'logins',
        'perms'      => 'perms',
        'perm_assoc' => 'perm_groups'
    ];

    public $phpass_iterations = 8;
    public $phpass_portable = false;
    public $date_format = 'Y-m-d H:i:s';

    /**
     * Roles/Permissions
     */
    public $roles = [
        'admin'        => 'Administrator',
        'guest'        => 'Tamu',
        'doctor'       => 'Dokter',
        'xray'         => 'Petugas Radiologi',
        'lab'          => 'Petugas Laboratorium',
        'pharmacy'     => 'Apoteker',
        'receptionist' => 'Resepsionis',
        'patient'      => 'Pasien',
    ];
}
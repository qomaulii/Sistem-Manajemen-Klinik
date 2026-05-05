<?php

// Konversi bitauth_lang.php ke standar CI4
return [
    'bitauth_username'                 => 'Username',
    'bitauth_pwd_uppercase'            => 'Uppercase Letters',
    'bitauth_pwd_number'               => 'Numbers',
    'bitauth_pwd_special'              => 'Special Characters',
    'bitauth_pwd_spaces'               => 'Spaces',
    
    'bitauth_login_failed'             => 'Invalid {0} or Password',
    'bitauth_user_inactive'            => 'You must activate this account before you can login.',
    'bitauth_user_locked_out'          => 'You have been locked out for {0} minutes for too many invalid login attempts, please try again later.',
    'bitauth_pwd_expired'              => 'Your password has expired.',
    
    'has_no_schar'                     => 'The {0} field must not contains forbidden special charachters.',
    'bitauth_unique_username'          => 'The {0} field must be unique.',
    'bitauth_password_is_valid'        => '{0} does not meet the complexity requirements: ',
    'bitauth_username_required'        => 'The {0} field is required.',
    'bitauth_password_required'        => 'The {0} field is required.',
    'bitauth_passwd_complexity'        => 'Password does not meet complexity requirements: {0}',
    'bitauth_passwd_min_length'        => 'Password must be at least {0} characters.',
    'bitauth_passwd_max_length'        => 'Password may not be longer than {0} characters.',
    
    'bitauth_unique_group'             => 'The {0} field must be unique.',
    'bitauth_groupname_required'       => 'Group name is required.',
    
    'bitauth_unique_permission_key'    => 'The {0} field must be unique.',
    'bitauth_permission_key_required'  => 'Permission key is required.',
    'bitauth_unique_permission_name'   => 'The {0} field must be unique.',
    'bitauth_permission_name_required' => 'Permission name is required.',
    
    'bitauth_instance_na'              => "BitAuth was unable to get the CodeIgniter instance.",
    'bitauth_data_error'               => 'You can\'t overwrite default BitAuth properties with custom userdata. Please change the name of the field: {0}',
    'bitauth_enable_gmp'               => 'You must enable php_gmp to use Bitauth.',
    'bitauth_user_not_found'           => 'User not found: {0}',
    'bitauth_activate_failed'          => 'Unable to activate user with this activation code.',
    'bitauth_expired_datatype'         => '$user must be an array or an object in Bitauth::password_is_expired()',
    'bitauth_expiring_datatype'        => '$user must be an array or an object in Bitauth::password_almost_expired()',
    'bitauth_add_user_datatype'        => '$data must be an array or an object in Bitauth::add_user()',
    'bitauth_add_user_failed'          => 'Adding user failed, please notify an administrator.',
    'bitauth_code_not_found'           => 'Activation Code Not Found.',
    'bitauth_edit_user_datatype'       => '$data must be an array or an object in Bitauth::update_user()',
    'bitauth_edit_user_failed'         => 'Updating user failed, please notify an administrator.',
    'bitauth_del_user_failed'          => 'Deleting user failed, please notify an administrator.',
    'bitauth_set_pw_failed'            => 'Unable to set user\'s password, please notify an administrator.',
    'bitauth_no_default_group'         => 'Default group was either not specified or not found, please notify an administrator.',
    'bitauth_add_group_datatype'       => '$data must be an array or an object in Bitauth::add_group()',
    'bitauth_add_group_failed'         => 'Adding group failed, please notify an administrator.',
    'bitauth_edit_group_datatype'      => '$data must be an array or an object in Bitauth::update_group()',
    'bitauth_edit_group_failed'        => 'Updating group failed, please notify an administrator.',
    'bitauth_del_group_failed'         => 'Deleting group failed, please notify an administrator.',
    
    'bitauth_no_default_perm'          => 'Default permission was either not specified or not found, please notify an administrator.',
    'bitauth_add_perm_datatype'        => '$data must be an array or an object in Bitauth::add_permission()',
    'bitauth_add_perm_failed'          => 'Adding permission failed, please notify an administrator.',
    'bitauth_edit_perm_datatype'       => '$data must be an array or an object in Bitauth::edit_permission()',
    'bitauth_edit_perm_failed'         => 'Updating permission failed, please notify an administrator.',
    'bitauth_del_perm_failed'          => 'Deleting permission failed, please notify an administrator.',
    
    'bitauth_lang_not_found'           => '(No language entry for "{0}" found!)'
];
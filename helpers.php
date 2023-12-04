<?php

use Models\User;

function dump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function dd($data)
{
    dump($data);
    die();
}

function createUser() {
    $user = new User();
    $user->first_name = 'John';
    $user->last_name = 'Doe';
    $user->email = 'test@test.com';
    $user->mobile_number = '1234567890';
    $user->address = '123 Main St';
    $user->city = 'New York';
    $user->state = 'NY';
    $user->zip = '12345';
    $user->country = 'US';
    $user->timezone = 'America/New_York';
    $user->created = date('Y-m-d H:i:s');
    $result = $user->save();
    if (!$result) {
        dd($user->getErrors());
    }
    dd($result);
}
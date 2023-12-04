<?php

require 'vendor/autoload.php';
require_once 'helpers.php';

use Components\Database;
use Models\User;

$users = User::findAll();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
</head>
<body>
    <h2>Users</h2>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email Address</th>
                <th>Actions</th>
            </tr>   
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->first_name ?></td>
                    <td><?= $user->last_name ?></td>
                    <td><?= $user->email ?></td>
                    <td>
                        <a href="view.php?id=<?= $user->id ?>">View</a>
                        <a href="edit.php?id=<?= $user->id ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
</body>
</html>
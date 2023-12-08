<?php

require 'vendor/autoload.php';
require_once 'helpers.php';

use Models\User;

$users = User::findAll();

include 'layout/header.php';
?>
    <main style="margin-top: 58px">
        <div class="container pt-4">
            <section class="mb-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="mb-0 text-left"><strong>Users</strong></h5>
                    </div>
                    <div class="card-body">
                        <a href="create.php" class="btn btn-primary btn-sm mb-3">Create User</a>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">First</th>
                                    <th scope="col">Last</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user) : ?>
                                    <tr>
                                        <th scope="row"><?= encode($user->id); ?></th>
                                        <td><?= encode($user->first_name); ?></td>
                                        <td><?= encode($user->last_name); ?></td>
                                        <td>
                                            <a href="view.php?id=<?= $user->id ?>" class="btn btn-secondary btn-sm">View</a>
                                            <a href="edit.php?id=<?= $user->id ?>" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="delete.php?id=<?= $user->id ?>" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </section>
        </div>
    </main>
<?php
include 'layout/footer.php';
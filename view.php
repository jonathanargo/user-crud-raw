<?php

require 'vendor/autoload.php';
require 'helpers.php';

use Models\User;

$id = ($_GET['id'] ?? null);
if (empty($id)) {
    // Just redirect to home if no ID provided
    header('Location: index.php');
    exit;
}

$user = User::find($id);
if (!$user) {
    // Redirect to home if no user found.
    // TODO JSA - Throw exception or something
    header('Location: index.php');
    exit;
}

include 'layout/header.php';
?>
<main style="margin-top: 58px">
    <div class="container pt-4">
        <section class="mb-4">
            <div class="card-header py-3">
                <h3 class="mb-0 text-left text-2xl"><strong>User Profile: <?= encode($user->getFullName()); ?></strong></h3>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-4">
                        <div class="col">
                            <div data-mdb-input-init class="form-outline">
                                <label class="form-label font-bold" for="first_name">First name</label>
                                <div><?= encode($user->first_name); ?></div>
                            </div>
                        </div>
                        <div class="col">
                            <div data-mdb-input-init class="form-outline">
                                <label class="form-label font-bold" for="last_name">Last name</label>
                                <div><?= encode($user->last_name); ?></div>
                            </div>
                        </div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label font-bold" for="email">Email</label>
                        <div><?= encode($user->email); ?></div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label font-bold" for="mobile_number">Phone</label>
                        <div><?= encode($user->mobile_number); ?></div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label font-bold" for="address">Address</label>
                        <div><?= encode($user->address); ?></div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label font-bold" for="city">City</label>
                        <div><?= encode($user->city); ?></div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label font-bold" for="state">State / Province</label>
                        <div><?= encode($user->state); ?></div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label font-bold" for="zip">Postal Code</label>
                        <div><?= encode($user->zip); ?></div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label font-bold" for="country">Country</label>
                        <div><?= encode($user->country); ?></div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label font-bold" for="created">Created At</label>
                        <div><?= date('m/d/Y H:i:s', strtotime($user->created)); ?></div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label font-bold" for="last_updated">Updated At</label>
                        <div><?= ($user->last_updated ? date('m/d/Y H:i:s', strtotime($user->last_updated)) : 'N/A'); ?></div>
                    </div>
                    <a href="edit.php?id=<?= $user->id ?>" class="btn btn-primary">Edit</a>
                    <a href="index.php" class="btn btn-secondary">Return</a>
                </form>

            </div>
        </section>
    </div>
</main>

<?php
include 'layout/footer.php';

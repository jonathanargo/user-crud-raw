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
    http_response_code(404);
    throw new Exception("User $id not found.");
}

$errors = [];
$post = ($_POST['User'] ?? null);
if ($post) {
    // Assign the attributes from the post
    $user->assign($post);
    if (!$user->save()) {
        $errors = $user->getErrors();
    } else {
        // Redirect to the view page
        header('Location: view.php?id=' . $user->id);
        exit;
    }
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
                <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger" role="alert">
                        One or more fields are invalid.
                    </div>
                <?php endif; ?>
                <form action="edit.php?id=<?= $user->id ?>" method="post">
                    <div class="row mb-5">
                        <div class="col">
                            <div data-mdb-input-init class="form-outline">
                                <input 
                                    type="text"
                                    id="first_name"
                                    name="User[first_name]"
                                    class="form-control <?= !empty($errors['first_name']) ? 'is-invalid' : ''; ?>"
                                    value="<?= encode($user->first_name); ?>"
                                />
                                <label class="form-label" for="first_name">First Name</label>
                                <?php if (!empty($errors['first_name'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['first_name'][0]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col">
                            <div data-mdb-input-init class="form-outline">
                                <input 
                                    type="text"
                                    id="last_name"
                                    name="User[last_name]"
                                    class="form-control <?= !empty($errors['last_name']) ? 'is-invalid' : ''; ?>"
                                    value="<?= encode($user->last_name); ?>"
                                />
                                <label class="form-label" for="last_name">Last Name</label>
                                <?php if (!empty($errors['last_name'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['last_name'][0]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-5">
                        <input 
                            type="email"
                            id="email"
                            name="User[email]"
                            class="form-control <?= !empty($errors['email']) ? 'is-invalid' : ''; ?>"
                            value="<?= encode($user->email); ?>"
                        />
                        <label class="form-label font-bold" for="email">Email</label>
                        <?php if (!empty($errors['email'])) : ?>
                            <div class="invalid-feedback">
                                <?= $errors['email'][0]; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-5">
                        <input 
                            type="tel"
                            id="mobile_number"
                            name="User[mobile_number]"
                            class="form-control <?= !empty($errors['mobile_number']) ? 'is-invalid' : ''; ?>"
                            value="<?= encode($user->mobile_number); ?>"
                        />
                        <label class="form-label font-bold" for="mobile_number">Phone</label>
                        <?php if (!empty($errors['mobile_number'])) : ?>
                            <div class="invalid-feedback">
                                <?= $errors['mobile_number'][0]; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-5">
                        <input 
                            type="text"
                            id="address"
                            name="User[address]"
                            class="form-control <?= !empty($errors['address']) ? 'is-invalid' : ''; ?>"
                            value="<?= encode($user->address); ?>"
                        />
                        <label class="form-label font-bold" for="address">Address</label>
                        <?php if (!empty($errors['address'])) : ?>
                            <div class="invalid-feedback">
                                <?= $errors['address'][0]; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-5">
                        <input 
                            type="text"
                            id="city"
                            name="User[city]"
                            class="form-control <?= !empty($errors['city']) ? 'is-invalid' : ''; ?>"
                            value="<?= encode($user->city); ?>"
                        />
                        <label class="form-label font-bold" for="city">City</label>
                        <?php if (!empty($errors['city'])) : ?>
                            <div class="invalid-feedback">
                                <?= $errors['city'][0]; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-5">
                        <input 
                            type="text"
                            id="state"
                            name="User[state]"
                            class="form-control <?= !empty($errors['state']) ? 'is-invalid' : ''; ?>"
                            value="<?= encode($user->state); ?>"
                        />
                        <label class="form-label font-bold" for="state">State / Province</label>
                        <?php if (!empty($errors['state'])) : ?>
                            <div class="invalid-feedback">
                                <?= $errors['state'][0]; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-5">
                        <input 
                            type="text"
                            id="zip"
                            name="User[zip]"
                            class="form-control <?= !empty($errors['zip']) ? 'is-invalid' : ''; ?>"
                            value="<?= encode($user->zip); ?>"
                        />
                        <label class="form-label font-bold" for="zip">Postal Code</label>
                        <?php if (!empty($errors['zip'])) : ?>
                            <div class="invalid-feedback">
                                <?= $errors['zip'][0]; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-5">
                        <input 
                            type="text"
                            id="country"
                            name="User[country]"
                            class="form-control <?= !empty($errors['country']) ? 'is-invalid' : ''; ?>"
                            value="<?= encode($user->country); ?>"
                        />
                        <label class="form-label font-bold" for="country">Country</label>
                        <?php if (!empty($errors['country'])) : ?>
                            <div class="invalid-feedback">
                                <?= $errors['country'][0]; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="User[id]" value="<?= encode($user->id); ?>" />
                    <button data-mdb-ripple-init type="submit" class="btn btn-primary bg-primary">Save</button>
                    <a href="index.php" class="btn btn-secondary">Return</a>
                </form>
            </div>
        </section>
    </div>
</main>

<?php
include 'layout/footer.php';

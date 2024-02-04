<?php if (isset($errors)) { ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error) { ?>
            <p><?= $error ?></p>
        <?php }; ?>
    </div>
<?php }; ?>

<?php if (isset($message)) { ?>
    <div class="alert alert-<?= $message['type'] ?>">
        <p><?= $message['body'] ?></p>
    </div>
<?php }; ?>

<form action="/login" method="post">
    <input type="email" placeholder="Email" value="<?= isset($old['email']) ? $old['email'] : '' ?>" name="email" id="email">
    <input type="password" placeholder="Password" value="<?= isset($old['password']) ? $old['password'] : '' ?>" name="password" id="password">
    <button type="submit">Login</button>
</form>
<?php
use \core\helpers\Url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Core-Lite Base Application</title>
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <?= $this->head() ?>
</head>
<body class="admin-body">
<?= $this->beginBody() ?>
<header class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a href="#" class="navbar-brand">
                Core-Lite Base Application
            </a>
        </div>
        <a href="<?= Url::toAction('login'); ?>" class="btn btn-default navbar-btn navbar-right">Sign In</a>
    </div>
</header>
<div class="container">
    <?= $this->getViewContent(); ?>
</div>

<?= $this->endBody() ?>
</body>
</html>


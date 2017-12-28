<h2>PHP Configuration</h2>
<div class="metrics">
    <div class="metric">
        <span class="value"><?= $data['php_version'] ?></span>
        <span class="label">PHP version</span>
    </div>
    <div class="metric">
        <span class="value"><?= $data['architecture'] ?> <span class="unit">bits</span></span>
        <span class="label">Architecture</span>
    </div>
    <div class="metric">
        <span class="value"><?= $data['timezone'] ?></span>
        <span class="label">Timezone</span>
    </div>
    <div class="metric">
        <span class="value"><?= strtr($data['memory_peak'], ['M' => '']) ?> <span class="unit">mb</span></span>
        <span class="label">Memory peak</span>
    </div>
    <div class="metric">
        <span class="value"><?= strtr($data['memory_limit'], ['M' => '']) ?> <span class="unit">mb</span></span>
        <span class="label">Memory limit</span>
    </div>
    <div class="metric">
        <span class="value"><span class="icon icon-<?= $data['xdebug'] ? 'enabled' : 'disabled' ?>"></span></span>
        <span class="label">XDebug</span>
    </div>
</div>
<h2>Core Configuration</h2>
<div class="metrics">
    <div class="metric">
        <span class="value"><?= $data['core_version'] ?></span>
        <span class="label">Core version</span>
    </div>
    <div class="metric">
        <span class="value"><?= $data['environment'] ?></span>
        <span class="label">Environment</span>
    </div>
    <div class="metric">
        <span class="value"><span class="icon icon-<?= $data['debug'] ? 'enabled' : 'disabled' ?>"></span></span>
        <span class="label">Debug</span>
    </div>
</div>
<?= \core\components\View::renderPartial('@crl-debug/views/default/environment_tables.php', ['config' => $data['config']]) ?>

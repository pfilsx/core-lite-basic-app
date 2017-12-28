<?php
/**
 * @var array $config
 */
?>
<table class="crl-table crl-table-bordered crl-table-colored">
    <thead>
    <tr>
        <th>Name</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($config as $key => $value) { ?>
        <?php if ($key == 'modules' || $key == 'assets') {
            continue;
        } ?>
        <?php if (is_array($value)) { ?>
            <tr>
                <th colspan="2"><?= $key ?></th>
            </tr>
            <?php foreach ($value as $key2 => $value2) {
                if ($key2 == 'rules') continue; ?>
                <tr>
                    <td><?= $key2 ?></td>
                    <td><?= '"' . $value2 . '"' ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td><?= $key ?></td>
                <td><?= '"' . $value . '"' ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>

<?php if (!empty($config['modules'])) { ?>
    <h2>Loaded Modules</h2>
    <table class="crl-table crl-table-bordered crl-table-colored">
        <thead>
        <tr>
            <th>Id</th>
            <th>Class</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($config['modules'] as $module) { ?>
            <tr>
                <td>
                    <?php $moduleClass = new $module['class'];
                    echo $moduleClass->id; ?>
                </td>
                <td><?= '"' . $module['class'] . '"' ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>

<?php if (!empty($config['assets']['bundles'])) { ?>
    <h2>Assets Bundles</h2>
    <table class="crl-table crl-table-bordered">
        <thead>
        <tr>
            <th>Class</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($config['assets']['bundles'] as $asset) { ?>
            <tr>
                <td><?= $asset ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>


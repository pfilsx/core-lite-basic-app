<?php
?>

<h2>Summary</h2>
<div class="metrics">
    <div class="metric">
        <span class="value"><?= count($data) ?></span>
        <span class="label">Queries</span>
    </div>
    <div class="metric">
        <span class="value"><?= $time ?> <span class="unit">ms</span></span>
        <span class="label">Queries time</span>
    </div>
</div>
<h2>Executed Queries</h2>
<?php if (!empty($data)) {  ?>
<table class="crl-table crl-table-bordered">
    <thead>
        <tr>
            <th>Query</th>
            <th>Time(ms)</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $query) { ?>
        <tr>
            <td><?= $query['sql'] ?></td>
            <td><?= sprintf('%.1f', $query['time']) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php } else { ?>
<div class="empty">
    <p>
        No queries was executed during the request.
    </p>
</div>
<?php } ?>


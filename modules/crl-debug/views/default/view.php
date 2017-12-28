<?php
?>

    <h2>Summary</h2>
    <div class="metrics">
        <div class="metric">
            <span class="value"><?= $renderer ?></span>
            <span class="label">Renderer</span>
        </div>
        <div class="metric">
            <span class="value"><?= count($data) ?></span>
            <span class="label">Rendered Views</span>
        </div>
        <div class="metric">
            <span class="value"><?= $time ?> <span class="unit">ms</span></span>
            <span class="label">Total Render Time</span>
        </div>
    </div>
    <h2>Rendered Views</h2>
<?php if (!empty($data)) {  ?>
    <table class="crl-table crl-table-bordered">
        <thead>
        <tr>
            <th>File</th>
            <th>Time(ms)</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $view) { ?>
            <tr>
                <td><?= $view['file'] ?></td>
                <td><?= sprintf('%.1f', $view['time']) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <div class="empty">
        <p>
            No views was rendered during the request.
        </p>
    </div>
<?php } ?>
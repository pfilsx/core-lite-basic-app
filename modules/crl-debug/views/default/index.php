<h2>Saved sessions</h2>
<table class="crl-table crl-table-bordered">
    <thead>
    <tr>
        <th>Time</th>
        <th>Url</th>
        <th>Method</th>
        <th>Status Code</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $key => $value) {?>
        <tr data-id="<?= isset($value['id']) ? $value['id'] : $key ?>" style="cursor: pointer;" onclick="chooseSession(this)">
            <td><?= date('d.m.Y H:i:s', (isset($value['request']) ? $value['request']['time'] : $value['time'])) ?></td>
            <td><?= (isset($value['request']) ? $value['request']['url'] : $value['url']) ?></td>
            <td><?= (isset($value['request']) ? $value['request']['method'] : $value['method']) ?></td>
            <td><?= (isset($value['request']) ? $value['request']['statusCode'] : $value['statusCode']) ?></td>
        </tr>

    <?php } ?>
    </tbody>
</table>
<script>
    'use strict';
    function chooseSession(obj){
        var id = obj.getAttribute('data-id');
        window.location.href = '<?= \core\helpers\Url::toAction('request') ?>?id='+id;
    }
</script>

<h2>
<?php $parts = explode('\\',$data['controller']);
echo array_pop($parts).'::'.$data['action'];
?>
</h2>
<div class="crl-debug-tabs">
    <ul class="crl-tab-navigation">
        <li data-target="request" class="active">Request</li>
        <li data-target="response">Response</li>
        <li data-target="cookies">Cookies</li>
<!--        <li data-target="session">Session</li>-->
    </ul>
    <div class="crl-tab-panel active" id="request">
        <h3>GET Parameters</h3>
        <?php if (empty($data['get'])) { ?>
            <div class="empty">
                <p>No GET parameters</p>
            </div>
        <?php } else {?>
            <table class="crl-table crl-table-bordered crl-table-colored">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data['get'] as $key => $value) { ?>
                    <tr>
                        <td><?= $key ?></td>
                        <td>"<?= $value ?>"</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        <h3>POST Parameters</h3>
        <?php if (empty($data['post'])) { ?>
            <div class="empty">
                <p>No POST parameters</p>
            </div>
        <?php } else {?>
            <table class="crl-table crl-table-bordered crl-table-colored">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data['post'] as $key => $value) { ?>
                    <tr>
                        <td><?= $key ?></td>
                        <td>"<?= $value ?>"</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        <h3>Request Headers</h3>
        <table class="crl-table crl-table-bordered crl-table-colored">
            <thead>
            <tr>
                <th>Header</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['request_headers'] as $key => $value) { ?>
                <tr>
                    <td><?= $key ?></td>
                    <td>"<span><?= is_array($value) ? $value[0] : $value ?></span>"</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <h3>Server Parameters</h3>
        <table class="crl-table crl-table-bordered crl-table-colored">
            <thead>
            <tr>
                <th>Key</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['server'] as $key => $value) { ?>
                <tr>
                    <td><?= $key ?></td>
                    <td>
                        <?php $value = (is_array($value) ? $value[0] : $value);
                            echo is_string($value) ? '"<span>'.$value.'</span>"' : '<span class="blue">'.$value.'</span>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="crl-tab-panel" id="response">
        <h3>Response Headers</h3>
        <?php if (empty($data['response_headers'])) { ?>
            <div class="empty">
                <p>No response headers</p>
            </div>
        <?php } else { ?>
            <table class="crl-table crl-table-bordered crl-table-colored">
                <thead>
                <tr>
                    <th>Header</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data['response_headers'] as $key => $value) { ?>
                    <tr>
                        <td><?= $key ?></td>
                        <td>
                            <?php $value = (is_array($value) ? $value[0] : $value);
                            echo is_string($value) ? '"<span>'.$value.'</span>"' : '<span class="blue">'.$value.'</span>';
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <div class="crl-tab-panel" id="cookies">
        <h3>Request Cookies</h3>
        <?php if (empty($data['request_cookies'])) { ?>
            <div class="empty">
                <p>No request cookies</p>
            </div>
        <?php } else {?>
            <table class="crl-table crl-table-bordered crl-table-colored">
                <thead>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data['request_cookies'] as $key => $value) { ?>
                    <tr>
                        <td><?= $key ?></td>
                        <td>"<?= is_array($value) ? $value[0] : $value ?>"</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        <h3>Response Cookies</h3>
        <?php if (empty($data['response_cookies'])) { ?>
            <div class="empty">
                <p>No response cookies</p>
            </div>
        <?php } else {?>
            <table class="crl-table crl-table-bordered crl-table-colored">
                <thead>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data['response_cookies'] as $key => $value) { ?>
                    <tr>
                        <td><?= $key ?></td>
                        <td>"<?= is_array($value) ? $value[0] : $value ?>"</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
<!--    <div class="crl-tab-panel" id="session">-->
<!---->
<!--    </div>-->
</div>

<script>
    (function () {
        'use strict';

        var findNavigation = function () {
                return document.querySelectorAll('.crl-tab-navigation li');
            },
            dNavigation = findNavigation();


        dNavigation.forEach(function(el){
            el.addEventListener('click', function(e){
                var obj = e.target,
                target = obj.getAttribute('data-target'),
                panels = document.querySelectorAll('.crl-tab-panel');
                dNavigation.forEach(function(elem){
                    elem.classList.remove('active');
                });
                obj.classList.add('active');
                panels.forEach(function(panel){
                    panel.classList.remove('active');
                });
                document.getElementById(target).classList.add('active');
            });
        });
    })();
</script>



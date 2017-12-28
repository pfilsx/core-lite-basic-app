<?php

use \core\base\App;
use core\helpers\Url;
use \core\web\Html;

/**
 * @var \app\modules\crl_debug\CrlDebug $debug
 */
?>
<div class="crl-debug-panel" data-id="<?= Html::encode($debug->getSessionId()) ?>">
    <div class="crl-debug-bar">
        <img src="<?= Core::smallLogo() ?>" height="30" width="30"/>
        <div class="crl-debug-info">
            <div class="crl-debug-block"> <!-- PHP -->
                <a href="<?= Url::toRoute([$debug->id, 'environment'], ['id' => $debug->getSessionId()]) ?>">
                    PHP
                    <span class="crl-debug-block-label"><?= $debug->getPHPVersion() ?></span>
                    Core-Lite
                    <span class="crl-debug-block-label"><?= $debug->getCoreVersion() ?></span>
                    <div class="crl-debug-detail-block">
                        <div class="crl-debug-detail-row"><b>PHP Version</b> <span><?= $debug->getPHPVersion() ?></span>
                        </div>
                        <div class="crl-debug-detail-row"><b>Core-Lite Version</b>
                            <span><?= $debug->getCoreVersion() ?></span></div>
                        <div class="crl-debug-detail-row"><b>Peak memory usage</b>
                            <span><?= $debug->getMemoryPeak() ?></span></div>
                        <div class="crl-debug-detail-row"><b>PHP memory limit</b>
                            <span><?= $debug->getMemoryLimit() ?></span></div>
                    </div>
                </a>
            </div>
            <div class="crl-debug-block"> <!-- Request -->
                <a href="<?= Url::toRoute([$debug->id, 'request'], ['id' => $debug->getSessionId()]) ?>">
                    Status
                    <?= $debug->renderStatus() ?>
                    Route
                    <span class="crl-debug-block-label"><?= $debug->getRequestRoute() ?></span>
                    <div class="crl-debug-detail-block">
                        <div class="crl-debug-detail-row"><b>HTTP Status</b> <span><?= $debug->getStatus() ?></span></div>
                        <div class="crl-debug-detail-row"><b>Request Route</b> <span><?= $debug->getRequestRoute() ?></span>
                        </div>
                        <?= $debug->getActiveModule() ?>
                        <div class="crl-debug-detail-row"><b>Controller</b>
                            <span><?= $debug->getActiveController() ?></span></div>
                        <div class="crl-debug-detail-row"><b>Action</b> <span><?= $debug->getActiveAction() ?></span></div>
                    </div>
                </a>
            </div>
            <div class="crl-debug-block"><!-- DB -->
                <a href="<?= Url::toRoute([$debug->id, 'database'], ['id' => $debug->getSessionId()]) ?>">
                    DB
                    <span class="crl-debug-block-label crl-debug-label-info"><?= $debug->getDbQueriesCount() ?></span>
                    <span class="crl-debug-block-label"><?= $debug->getDbQueriesTotalTime() ?></span>
                    <?= $debug->getDbLog() ?>
                </a>
            </div>
        </div>
        <div class="crl-debug-toggle">
            <div class="crl-debug-icon">
                <i></i>
            </div>
        </div>
    </div>
</div>


<style>
    .crl-debug-panel {
        position: fixed;
        bottom: 0;
        right: 0;
        width: 100%;
        z-index: 1000;
        font-family: sans-serif;
    }

    .crl-debug-bar {
        float: right;
        position: relative;
        background: #333;
        color: #fff;
        height: 40px;
        width: 85px;
        transition: all 1s ease;
        overflow: hidden;
    }
    .crl-debug-hidden{
        display: none;
    }
    .crl-debug-bar a,
    .crl-debug-bar a:hover,
    .crl-debug-bar a:active,
    .crl-debug-bar a:visited,
    .crl-exception-debug a,
    .crl-exception-debug a:hover,
    .crl-exception-debug a:active,
    .crl-debug-bar a:visited{
        color: #fff;
        text-decoration: none;
        display: inline-block;
    }

    .crl-debug-bar.active {
        width: 100%;
        overflow: visible;
    }

    .crl-debug-bar img {
        margin: 5px 5px 5px 50px;
        float: left;
    }

    .crl-debug-bar.active img {
        margin: 5px;
    }

    .crl-debug-toggle {
        position: absolute;
        right: 45px;
        bottom: 0;
        cursor: pointer;
        display: inline-block;
        background: #333;
        width: 40px;
        height: 40px;
        text-align: center;
        border-right: 1px solid #fff;
        transition: all .4s ease;
    }

    .crl-debug-bar.active .crl-debug-toggle {
        right: 0;
        border-right: 0;
    }

    .crl-debug-icon {
        position: relative;
        height: 40px;
    }

    .crl-debug-icon i {
        transform: rotate(135deg);
        -webkit-transform: rotate(135deg);
        transition: all .4s ease;
        border: solid #fff;
        border-width: 0 3px 3px 0;
        display: inline-block;
        padding: 5px;
        margin: 13px 15px;
    }

    .crl-debug-bar.active .crl-debug-icon i {
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
    }

    .crl-debug-info {
        display: none;
        font-size: 0;
    }

    .crl-debug-block {
        font-size: 14px;
        margin: 0;
        padding: 4px 8px;
        line-height: 36px;
        white-space: nowrap;
        font-weight: bold;
        transition: all .3s ease;
        cursor: pointer;
        display: inline-block;
        position: relative;
    }

    .crl-debug-block:hover {
        background-color: #444;
    }

    .crl-debug-bar.active .crl-debug-info {
        display: inline-block;
    }

    .crl-debug-block-label {
        min-width: 10px;
        padding: 3px 4px;
        font-size: 12px;
        font-weight: 700;
        line-height: 2;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        background-color: #777;
        border-radius: 5px;
    }

    .crl-debug-label-info {
        background: #3a87ad;
    }

    .crl-debug-label-success {
        background: #4f805d;
    }

    .crl-debug-label-danger {
        background: #b0413e;
    }

    .crl-debug-detail-block {
        display: none;
        padding: 10px;
        min-width: 100%;
        max-width: 480px;
        max-height: 480px;
        word-wrap: break-word;
        overflow: hidden;
        overflow-y: auto;
        background-color: #444;
        bottom: 40px;
        line-height: 14px;
        color: #F5F5F5;
        position: absolute;
        z-index: 10002;
        left: 0;
    }

    .crl-debug-block:hover .crl-debug-detail-block {
        display: block;
    }

    .crl-debug-detail-row {
        display: table-row;
    }

    .crl-debug-detail-row b {
        color: #AAA;
        display: table-cell;
        font-size: 12px;
        padding: 4px 8px 4px 0;
    }

    .crl-debug-detail-row span {
        display: inline-block;
        color: #F5F5F5;
        font-size: 13px;
    }
</style>
<script>
    (function () {
        'use strict';

        var exFooter = document.querySelector('.crl-exception-footer');
        if (exFooter) {
            document.querySelector('.crl-debug-panel').classList.add('crl-debug-hidden');
            document.querySelector('.crl-exception-debug').innerHTML = document.querySelector('.crl-debug-info').innerHTML;
        } else {
            var findToolbar = function () {
                    return document.querySelector('.crl-debug-bar');
                },
                dToolbar = findToolbar(),
                dToolbarToggle = dToolbar.querySelector('.crl-debug-toggle');

            dToolbarToggle.onclick = function (e) {
                var target = e.target,
                    toggle = findAncestor(target, 'crl-debug-toggle'),
                    toolbar = toggle.parentNode;
                if (!toolbar.classList.contains('active')) {
                    toolbar.classList.add('active');
                } else {
                    toolbar.classList.remove('active');
                }
            };
        }

        function findAncestor(el, cls) {
            while ((el = el.parentElement) && !el.classList.contains(cls)) ;
            return el;
        }
    })();
</script>
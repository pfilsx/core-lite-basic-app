<?php

use \core\helpers\Url;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Core-Lite Debug Profiler</title>
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <style>
        * {
            margin: 0;
            box-sizing: border-box;
            color: #333;
            padding: 0;
            font-family: Helvetica, Arial, sans-serif;
        }

        html, body {
            height: 100%;
            width: 100%;
            font-size: 14px;
            line-height: 1.4
        }

        header {
            width: 100%;
            height: 50px;
            background: #333;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
        }

        header img {
            float: left;
            margin: 5px;
            height: 40px
        }

        header .title {
            float: left;
            line-height: 40px;
            margin: 5px 0;
            font-size: 21px;
            color: #fff;
            font-weight: bold
        }

        header .title span {
            color: #ccc;
            font-weight: normal
        }

        .content {
            min-height: 100%;
            width: 100%;
            padding-top: 50px;
            padding-bottom: 77px;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding-top: 55px;
            padding-bottom: 30px;
            width: 220px;
            z-index: 9998;
            background: #444;
            color: #fff
        }
        .summary {
            position: fixed;
            bottom: 0;
            height: 75px;
            width: 100%;
            z-index: 9999;
            border: solid rgba(0, 0, 0, 0.1);
            border-width: 2px 0;
            padding: 10px;
            color: #fff;
        }
        .summary-success{
            background: #4f805d;
        }
        .summary-error{
            background: #b0413e;
        }
        .summary h3{
            font-size: 21px;
        }
        dl.metadata dt, dl.metadata dd {
            display: inline-block;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
        }
        dl.metadata dt {
            font-weight: bold;
        }
        dl.metadata dd{
            margin: 0 1.5em 0 0;
        }
        dl.metadata {
            margin: 5px 0 0;
        }

        .sidebar-menu {
            list-style: none;
            position: relative
        }

        .sidebar-menu li {
            position: relative
        }

        .sidebar-menu li.active {
            background: #222
        }

        .sidebar-menu a {
            padding: 10px 15px 10px 50px;
            display: block
        }

        .sidebar-menu a:hover {
            background: #333
        }

        .view {
            max-width: 1400px;
            height: 100%;
            padding-left: 234px;
            padding-top: 14px;
            padding-right: 14px;
        ]
        }

        .metrics {
            display: block;
            overflow: auto;
        }

        .metrics .metric {
            float: left;
            margin: 0 1em 1em 0
        }

        .metric {
            background: #FFF;
            border: 1px solid #E0E0E0;
            box-shadow: 0 0 1px rgba(128, 128, 128, .2);
            min-width: 100px;
            min-height: 70px
        }

        .metric .value {
            display: block;
            font-size: 28px;
            padding: 8px 15px 4px;
            text-align: center;
            min-height: 51px
        }

        .label {
            font-weight: bold;
            white-space: nowrap
        }

        .metric .label {
            background: #E0E0E0;
            color: #222;
            display: block;
            font-size: 12px;
            padding: 5px;
            text-align: center
        }

        .metric .unit {
            color: #999;
            font-size: 18px;
            margin-left: -4px
        }

        .icon {
            position: relative
        }

        .icon:after, .icon:before {
            content: ' ';
            position: absolute;
            top: 1px;
            left: 50%;
            display: block;
            transform: rotate(-45deg);
        }

        .icon-enabled:after {
            padding: 4px 8px;
            border: 6px solid transparent;
            border-bottom-color: #5e976e;
            border-left-color: #5e976e;
            margin-left: -12px;
        }

        .icon-disabled:after, .icon-disabled:before {
            height: 28px;
            width: 6px;
            background-color: #b0413e;
            margin-left: -3px;
            margin-top: 3px;
        }

        .icon-disabled:before {
            transform: rotate(45deg);
        }

        .crl-table {
            width: 100%;
            text-align: left;
            background: #FFF;
            border-collapse: collapse;
            margin: 1em 0;
            font-weight: bold;
        }

        .crl-table-bordered {
            border: 1px solid #e0e0e0;
            box-shadow: 0 0 1px rgba(128, 128, 128, .2);
        }

        .crl-table tr, .crl-table th {
            background: #FFF;
            border-collapse: collapse;
            line-height: 1.5;
            vertical-align: top;
        }

        .crl-table th, .crl-table td {
            padding: 8px 10px;
            border: 0;
        }

        .crl-table th {
            background-color: #e0e0e0;
        }

        .crl-table-colored tr td:last-child {
            color: #cc7832;
        }

        .crl-table-colored tr td:last-child span {
            color: #629755;
        }

        .crl-table-colored tr td:last-child span.blue {
            color: #1299da;
        }

        .crl-table-colored tr td:first-child {
            color: #333;
        }

        .crl-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .crl-table tr:hover td {
            background: #eee;
        }

        .crl-tab-navigation {
            list-style: none;
            font-size: 0;
            padding: 0;
            display: block;
        }

        .crl-tab-navigation li {
            display: inline-block;
            background: #FFF;
            border: 1px solid #DDD;
            color: #444;
            cursor: pointer;
            font-size: 16px;
            margin: 0 0 0 -1px;
            padding: .5em .75em;
            z-index: 1;
        }

        .crl-tab-navigation li:hover {
            background: #EEE;
        }

        .crl-tab-navigation li.active {
            background: #666;
            border-color: #666;
            color: #FAFAFA;
            z-index: 1100;
        }

        .crl-tab-panel {
            display: none;
            margin: 1em 0 0 0;
        }

        .crl-tab-panel table tr td:last-child {
            -ms-word-break: break-all;
            word-break: break-all;
            -webkit-hyphens: auto;
            -moz-hyphens: auto;
            hyphens: auto;
        }

        .crl-tab-panel table tr td:first-child {
            min-width: 200px;
        }

        .crl-tab-panel.active {
            display: block;
        }

        .empty {
            border: 4px dashed #E0E0E0;
            margin: 1em 0;
            padding: .5em 2em;
            width: 100%;
        }

        .empty p {
            font-size: 16px;
            margin: 1em;
            color: #999;
        }

        a {
            color: #fff;
            text-decoration: none !important;
        }

        h2 {
            font-weight: normal;
            font-size: 24px;
            margin: 1.5em 0 .5em
        }

        h2:first-of-type {
            margin-top: 0
        }

        .crl-icon {
            position: absolute;
            width: 50px;
            top: 8px;
            left: 0;
            display: block;
            text-align: center;
        }
        .crl-icon svg {
            height: 24px;
            max-height: 24px;
        }
        .crl-debug-exception-message {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 15px;
            color: #b34446;
            font-size: 24px;
        }
        pre{position:relative;z-index:200;left:50px;line-height:20px;font-size:12px;font-family:Consolas,Courier New,monospace;display:inline;margin:0;padding:0;border:0;vertical-align:baseline}pre .comment{color:gray;font-style:italic}pre .keyword{color:navy}pre .number{color:#00a;font-weight:400}pre .string,pre .value{color:#0a0}pre .symbol,pre .char{color:#505050;background:#d0eded;font-style:italic}pre .phpdoc{text-decoration:underline}pre .variable{color:#a00}
        .call-stack-item{display:none}.call-stack-item .error-line,.call-stack-item .hover-line{background-color:#ffebeb;position:absolute;width:100%;z-index:100;margin-top:0}.call-stack-item .hover-line{background:none}.call-stack-item .hover-line:hover{background:#edf9ff!important}.call-stack-item .code-wrap{position:relative}.call-stack-item .code{min-width:860px;margin:15px auto;padding:0 50px;position:relative}.call-stack-item .code .lines-item{position:absolute;z-index:200;display:block;width:25px;text-align:right;color:#aaa;line-height:20px;font-size:12px;margin-top:1px;font-family:Consolas,Courier New,monospace}body pre{pointer-events:none}body.mousedown pre{pointer-events:auto}
        .crl-exception-line{background:#e5e5e5;padding:15px 20px;cursor:pointer}.crl-exception-line *{cursor:pointer}.crl-exception-line div{display:inline-block}.crl-exception-file{color:grey;padding-left:10px}.crl-exception-row{float:right;text-align:right}
    </style>
</head>
<body>
<header>
    <img src="<?= Core::smallLogo() ?>">
    <p class="title">Core-Lite <span>Profiler</span></p>
</header>
<?php if (!empty($fullData)) { ?>
<div class="summary <?= $fullData['request']['statusCode'] >= 400 ? 'summary-error' : 'summary-success' ?>">
    <h3><a href="<?= $fullData['request']['url'] ?>"><?= $fullData['request']['url'] ?></a></h3>
    <dl class="metadata">
        <dt>Method</dt>
        <dd><?= $fullData['request']['method'] ?></dd>
        <dt>Status Code</dt>
        <dd><?= $fullData['request']['statusCode'] ?></dd>
        <dt>Profiled Time</dt>
        <dd><?= date('d.m.Y H:i:s', $fullData['request']['time']) ?></dd>
    </dl>
</div>
<?php } ?>
<div class="content">
    <div class="sidebar">
        <ul class="sidebar-menu">
            <?php if ($id != null) { ?>
            <li <?= $action == 'request' ? 'class="active"' : '' ?>>
                <span class="crl-icon">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                         viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve">
                        <path fill="#AAAAAA" d="M14.999,8.543c0,0.229-0.188,0.417-0.416,0.417H5.417C5.187,8.959,5,8.772,5,8.543s0.188-0.417,0.417-0.417h9.167C14.812,8.126,14.999,8.314,14.999,8.543 M12.037,10.213H5.417C5.187,10.213,5,10.4,5,10.63c0,0.229,0.188,0.416,0.417,0.416h6.621c0.229,0,0.416-0.188,0.416-0.416C12.453,10.4,12.266,10.213,12.037,10.213 M14.583,6.046H5.417C5.187,6.046,5,6.233,5,6.463c0,0.229,0.188,0.417,0.417,0.417h9.167c0.229,0,0.416-0.188,0.416-0.417C14.999,6.233,14.812,6.046,14.583,6.046 M17.916,3.542v10c0,0.229-0.188,0.417-0.417,0.417H9.373l-2.829,2.796c-0.117,0.116-0.71,0.297-0.71-0.296v-2.5H2.5c-0.229,0-0.417-0.188-0.417-0.417v-10c0-0.229,0.188-0.417,0.417-0.417h15C17.729,3.126,17.916,3.313,17.916,3.542 M17.083,3.959H2.917v9.167H6.25c0.229,0,0.417,0.187,0.417,0.416v1.919l2.242-2.215c0.079-0.077,0.184-0.12,0.294-0.12h7.881V3.959z"></path>
                    </svg>
                </span>
                <a href="<?= Url::toAction('request', ['id' => $id]) ?>">Request/Response</a>
            </li>
            <li <?= $action == 'exceptions' ? 'class="active"' : '' ?>>
                <span class="crl-icon">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve">
                        <path fill="#AAAAAA" d="M23.5,9.5c0-0.2-1.2,0.2-1.6,0.2c0,0,0,0,0,0c0.1-0.3,0.3-0.6,0.4-0.8C23,7.4,22,6.6,21,7.5
                            c-0.4,0.4,0,1.1,0,1.8c0,0.1,0,0.2,0,0.3c-0.2-0.1-0.4-0.1-0.6-0.3c-0.5-0.8-1.1-0.2-1.1,0c0,0.3,0.7,0.9,1.1,0.9c0.1,0,0.1,0,0.2,0
                            c0,0.1,0,0.3,0,0.5c0,0.7-0.8,1.1-1.7,1.2V9.1c0-4.3-3.3-6.4-6.9-6.4c-3.5,0-6.9,2-6.9,6.4v2.8c-0.9-0.2-1.8-0.5-1.8-1.2
                            c0-0.1,0-0.1,0-0.2c0.1,0,0.1,0,0.2,0c0.5,0,1.1-0.2,1.1-0.4C4.8,8.7,4,9.6,3.5,9.6c-0.1,0-0.2,0-0.3,0c0-0.1,0.1-0.2,0.1-0.4
                            c0-0.5,1.2-1.7-0.8-1.9C2.1,7.3,2,8.2,2.1,8.6C2.3,9,2.4,9.5,2.5,9.8C2.4,9.6,2.2,9.6,2,9.5C1.8,9.3,0.4,7.6,0.1,9.5
                            c-0.1,1.1,1,1.2,1.9,1c0.1,0,0.2-0.1,0.3-0.1c-0.1,0.3-0.2,0.7-0.2,1.2c0,1.3,1.5,1.6,2.9,1.7c0,1.7,0,5.2,0,5.2
                            c0,1.6,0.5,2.8,2.2,2.8c1.8,0,2.4-1.3,2.4-2.9c0,1.6,0.6,2.9,2.3,2.9s2.3-2.2,2.3-2.8c0,1.7,0.7,2.8,2.4,2.8c1.7,0,2.2-1.2,2.2-2.9
                            v-5.1c1.4-0.1,2.9-0.4,2.9-1.7c0-0.4-0.1-0.7-0.1-1c0.4,0.5,1.1,0.8,1.7,0.5C24.5,10.4,23.5,9.7,23.5,9.5z M6.8,8.4
                            c0-1.5,1-2.5,2.3-2.5c1.3,0,2.3,1.1,2.3,2.5c0,1.4-1,2.6-2.2,2.6c0.6,0,1.1-0.5,1.1-1.2c0-0.6-0.5-1.2-1.2-1.2
                            c-0.6,0-1.2,0.5-1.2,1.2c0,0.6,0.5,1.2,1.2,1.2c0,0,0,0,0,0C7.8,11,6.8,9.9,6.8,8.4z M11.9,15.9c-2.9-0.1-3.1-1.6-3.1-2.5
                            c0-0.9,1.7-0.3,3.2-0.3c1.5,0,3.1-0.7,3.1,0.2C15.1,14.3,14.3,16,11.9,15.9z M15,11c0.6-0.1,1-0.6,1-1.2c0-0.6-0.5-1.2-1.2-1.2
                            c-0.6,0-1.2,0.5-1.2,1.2c0,0.6,0.5,1.2,1.1,1.2c0,0,0,0,0,0c-1.3,0-2.3-1.2-2.3-2.6c0-1.5,1-2.5,2.3-2.5c1.3,0,2.3,1.1,2.3,2.5
                            C17.1,9.8,16.2,10.9,15,11z">
                        </path>
                    </svg>
                </span>
                <a href="<?= Url::toAction('exceptions', ['id' => $id]) ?>">Exceptions</a>
            </li>
            <li <?= $action == 'database' ? 'class="active"' : '' ?>>
                <span class="crl-icon">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24"
                    height="24" viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve">
                        <path fill="#AAAAAA" d="M5,8h14c1.7,0,3-1.3,3-3s-1.3-3-3-3H5C3.3,2,2,3.3,2,5S3.3,8,5,8z
                            M18,3.6c0.8,0,1.5,0.7,1.5,1.5S18.8,6.6,18,6.6s-1.5-0.7-1.5-1.5S17.2,3.6,18,3.6z
                            M19,9H5c-1.7,0-3,1.3-3,3s1.3,3,3,3h14c1.7,0,3-1.3,3-3S20.7,9,19,9z M18,13.6
                            c-0.8,0-1.5-0.7-1.5-1.5s0.7-1.5,1.5-1.5s1.5,0.7,1.5,1.5S18.8,13.6,18,13.6z
                            M19,16H5c-1.7,0-3,1.3-3,3s1.3,3,3,3h14c1.7,0,3-1.3,3-3S20.7,16,19,16z
                            M18,20.6c-0.8,0-1.5-0.7-1.5-1.5s0.7-1.5,1.5-1.5s1.5,0.7,1.5,1.5S18.8,20.6,18,20.6z">
                        </path>
                    </svg>
                </span>
                <a href="<?= Url::toAction('database', ['id' => $id]) ?>">Database</a>
            </li>
                <li <?= $action == 'view' ? 'class="active"' : '' ?>>
                <span class="crl-icon">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24"
                         enable-background="new 0 0 24 24" xml:space="preserve">
                        <path fill="#AAAAAA" d="M20.1,1H3.9C2.3,1,1,2.3,1,3.9v16.3C1,21.7,2.3,23,3.9,23h16.3c1.6,0,2.9-1.3,2.9-2.9V3.9
                        C23,2.3,21.7,1,20.1,1z
                        M21,20.1c0,0.5-0.4,0.9-0.9,0.9H3.9C3.4,21,3,20.6,3,20.1V3.9C3,3.4,3.4,3,3.9,3h16.3C20.6,3,21,3.4,21,3.9
                        V20.1z M5,5h14v3H5V5z M5,10h3v9H5V10z M10,10h9v9h-9V10z"></path>
                    </svg>
                </span>
                    <a href="<?= Url::toAction('view', ['id' => $id]) ?>">Views</a>
                </li>
            <li <?= $action == 'environment' ? 'class="active"' : '' ?>>
                <span class="crl-icon">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                         viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve">
                        <path fill="#AAAAAA" d="M15.8,6.4h-1.1c0,0-0.1,0.1-0.1,0l0.8-0.7c0.5-0.5,0.5-1.3,0-1.9l-1.4-1.4c-0.5-0.5-1.4-0.5-1.9,0l-0.6,0.8
                            c-0.1,0,0,0,0-0.1V2.1c0-0.8-1-1.4-1.8-1.4h-2c-0.8,0-1.9,0.6-1.9,1.4v1.1c0,0,0.1,0.1,0.1,0.1L5.1,2.5c-0.5-0.5-1.3-0.5-1.9,0
                            L1.8,3.9c-0.5,0.5-0.5,1.4,0,1.9l0.8,0.6c0,0.1,0,0-0.1,0H1.4C0.7,6.4,0,7.5,0,8.2v2c0,0.8,0.7,1.8,1.4,1.8h1.2c0,0,0.1-0.1,0.1-0.1
                            l-0.8,0.7c-0.5,0.5-0.5,1.3,0,1.9L3.3,16c0.5,0.5,1.4,0.5,1.9,0l0.6-0.8c0.1,0-0.1,0-0.1,0.1v1.2c0,0.8,1.1,1.4,1.9,1.4h2
                            c0.8,0,1.8-0.6,1.8-1.4v-1.2c0,0-0.1-0.1,0-0.1l0.7,0.8c0.5,0.5,1.3,0.5,1.9,0l1.4-1.4c0.5-0.5,0.5-1.4,0-1.9L14.6,12
                            c0-0.1,0,0.1,0.1,0.1h1.1c0.8,0,1.3-1.1,1.3-1.8v-2C17.1,7.5,16.5,6.4,15.8,6.4z M8.6,13c-2.1,0-3.8-1.7-3.8-3.8
                            c0-2.1,1.7-3.8,3.8-3.8c2.1,0,3.8,1.7,3.8,3.8C12.3,11.3,10.6,13,8.6,13z"></path>
                        <path fill="#AAAAAA" d="M22.3,15.6l-0.6,0.2c0,0,0,0.1,0,0l0.3-0.5c0.2-0.4,0-0.8-0.4-1l-1-0.4c-0.4-0.2-0.8,0-1,0.4l-0.1,0.5
                            c0,0,0,0,0,0l-0.2-0.6c-0.2-0.4-0.8-0.5-1.2-0.3l-1.1,0.4c-0.4,0.2-0.8,0.7-0.7,1.1l0.2,0.6c0,0,0.1,0,0.1,0l-0.5-0.3
                            c-0.4-0.2-0.8,0-1,0.4l-0.4,1c-0.2,0.4,0,0.8,0.4,1l0.5,0.1c0,0,0,0,0,0l-0.6,0.2c-0.4,0.2-0.5,0.8-0.4,1.2l0.4,1.1
                            c0.2,0.4,0.7,0.8,1.1,0.7l0.6-0.2c0,0,0-0.1,0,0l-0.3,0.5c-0.2,0.4,0,0.8,0.4,1l1,0.4c0.4,0.2,0.8,0,1-0.4l0.1-0.5c0,0,0,0,0,0
                            l0.2,0.6c0.2,0.4,0.9,0.5,1.2,0.3l1.1-0.4c0.4-0.2,0.8-0.7,0.6-1.1l-0.2-0.6c0,0-0.1,0,0,0l0.5,0.3c0.4,0.2,0.8,0,1-0.4l0.4-1
                            c0.2-0.4,0-0.8-0.4-1l-0.5-0.1c0,0,0,0,0,0l0.6-0.2c0.4-0.2,0.5-0.8,0.3-1.2l-0.4-1.1C23.2,15.9,22.7,15.5,22.3,15.6z M19.9,20.5
                            c-1.1,0.4-2.3-0.1-2.7-1.2c-0.4-1.1,0.1-2.3,1.2-2.7c1.1-0.4,2.3,0.1,2.7,1.2C21.5,18.9,21,20.1,19.9,20.5z"></path>
</svg>
                </span>
                <a href="<?= Url::toAction('environment', ['id' => $id]) ?>">Environment</a>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div class="view"><?= $this->getViewContent(); ?></div>
</div>
</body>
</html>

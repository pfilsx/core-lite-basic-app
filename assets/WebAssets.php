<?php


namespace app\assets;


use core\components\AssetBundle;

class WebAssets extends AssetBundle
{
    public function depends()
    {
        return [
            'core\assets\MainAssets',
            'core\bootstrap\BootstrapAsset'
        ];
    }
}
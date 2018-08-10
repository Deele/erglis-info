<?php
namespace app\widgets\site_header\assets;

use yii\web\AssetBundle;

/**
 * User login & register widget asset bundle.
 */
class SiteHeaderAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/widgets/site_header/assets/dist';
    public $css = [
        'siteHeader.css',
    ];
    public $js = [
//        'siteHeader.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}

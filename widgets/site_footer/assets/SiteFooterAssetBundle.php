<?php
namespace app\widgets\site_footer\assets;

use yii\web\AssetBundle;

/**
 * User login & register widget asset bundle.
 */
class SiteFooterAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/widgets/site_footer/assets/dist';
    public $css = [
        'siteFooter.css',
    ];
    public $js = [
//        'siteFooter.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}

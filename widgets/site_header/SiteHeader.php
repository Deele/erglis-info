<?php
namespace app\widgets\site_header;

use app\widgets\site_header\assets\SiteHeaderAssetBundle;
use yii\base\Widget;
use yii\helpers\Html;

class SiteHeader extends Widget
{
    public $name = 'site-header-register';
    public $options = [
        'class' => 'site-header'
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        Html::addCssClass($this->options, $this->name . '-widget');
        $assets = SiteHeaderAssetBundle::register($this->view);

        return Html::tag(
            'header',
            $this->render(
                'siteHeader',
                [
                    'assetBaseUrl' => $assets->baseUrl
                ]
            ),
            $this->options
        );
    }
}

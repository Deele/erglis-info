<?php
namespace app\widgets\site_footer;

use app\widgets\site_footer\assets\SiteFooterAssetBundle;
use yii\base\Widget;
use yii\helpers\Html;

class SiteFooter extends Widget
{
    public $name = 'site-footer-register';
    public $options = [
        'class' => 'site-footer'
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        Html::addCssClass($this->options, $this->name . '-widget');
        SiteFooterAssetBundle::register($this->view);

        return Html::tag(
            'footer',
            $this->render(
                'siteFooter'
            ),
            $this->options
        );
    }
}

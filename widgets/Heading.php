<?php
/**
 * Contains \app\widgets\Heading
 */

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Heading widget renders a heading tag with some text
 */
class Heading extends Widget
{

    const HEADING_DISPLAY_SHOW = 1;
    const HEADING_DISPLAY_HIDE = 2;
    const HEADING_DISPLAY_DISABLE = 3;

    /**
     * @var string
     */
    public $tag = 'h1';

    /**
     * @var string
     */
    public $heading;

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var int
     */
    public $headingDisplay = self::HEADING_DISPLAY_SHOW;

    public function init()
    {
        parent::init();

        Html::addCssClass($this->options, 'heading-widget');
        if ($this->headingDisplay == self::HEADING_DISPLAY_HIDE) {
            Html::addCssClass($this->options, 'sr-only');
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $html = '';
        if ($this->headingDisplay != self::HEADING_DISPLAY_DISABLE && strlen($this->heading) > 0) {
            $html .= Html::tag('h1', $this->heading, $this->options);
        }
        return $html;
    }
}

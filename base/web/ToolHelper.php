<?php

namespace app\base\web;

use DateInterval;
use Yii;
use yii\base\Component;
use yii\helpers\Html;
use yii\helpers\VarDumper;

/**
 * Class ToolHelper
 *
 * @property-read $timePeriod
 *
 * @package app\base\web
 */
class ToolHelper extends Component
{
    const TYPE_TIME_PERIOD_REQUESTED = 'requested';
    const TYPE_TIME_PERIOD_LATEST = 'latest';

    public $timePeriodParamName = 'date';
    public $invalidTimePeriodRedirectRoute = [
        'site/index'
    ];
    public $timePeriodQuarterLimits = [3, 8];

    protected $_timePeriod;

    /**
     * @return array
     */
    public function getTimePeriod()
    {
        if (is_null($this->_timePeriod)) {
            $this->prepareTimePeriod();
        }

        return $this->_timePeriod;
    }

    /**
     * @param $month
     *
     * @return mixed
     */
    public function createQuarterFromMonth($month)
    {
        switch ($month) {
            case 1:
            case 2:
            case 3:
                $quarter = 1;
                break;
            case 4:
            case 5:
            case 6:
                $quarter = 2;
                break;
            case 7:
            case 8:
            case 9:
                $quarter = 3;
                break;
            default:
                $quarter = 4;
        }

        return $quarter;
    }

    /**
     * Prepares time period
     */
    protected function prepareTimePeriod()
    {
        $_timePeriod = \Yii::$app->request->get($this->timePeriodParamName);
        $timePeriod = null;
        list($minQuarters, $maxQuarters) = $this->timePeriodQuarterLimits;
        $minMonths = ($minQuarters * 3);
        $maxMonths = ($maxQuarters * 3);
        if (strlen($_timePeriod) > 0) {
            list($fromDate, $toDate) = explode('-', $_timePeriod);
            if (!is_null($fromDate) && !is_null($toDate)) {
                list($fromYear, $fromQuarter) = explode('q', $fromDate);
                list($toYear, $toQuarter) = explode('q', $toDate);
                $timePeriod = [
                    'type'        => static::TYPE_TIME_PERIOD_REQUESTED,
                    'fromYear'    => (is_numeric($fromYear) ? (int)$fromYear : null),
                    'fromQuarter' => (is_numeric($fromQuarter) ? (int)$fromQuarter : null),
                    'toYear'      => (is_numeric($toYear) ? (int)$toYear : null),
                    'toQuarter'   => (is_numeric($toQuarter) ? (int)$toQuarter : null),
                ];
                if (
                    is_null($timePeriod['fromYear']) ||
                    is_null($timePeriod['fromQuarter']) ||
                    is_null($timePeriod['toYear']) ||
                    is_null($timePeriod['toQuarter'])
                ) {
                    $this->endWithError(
                        'Please, select proper date range and try again.',
                        $this->invalidTimePeriodRedirectRoute
                    );
                }
            }
        }
        if (is_null($timePeriod)) {
            $timePeriod = [];
            $now = new \DateTime();
            $timePeriod['type'] = static::TYPE_TIME_PERIOD_LATEST;
            $timePeriod['toYear'] = (int)$now->format('Y');
            $timePeriod['toQuarter'] = $this->createQuarterFromMonth((int)$now->format('n'));
            $timePeriod['toDate'] = new \DateTime(
                sprintf(
                    "%d-%02d-01 00:00:00",
                    $timePeriod['toYear'],
                    $timePeriod['toQuarter'] * 3
                )
            );
            $timePeriod['fromDate'] = new \DateTime(
                sprintf(
                    "%d-%02d-01 00:00:00",
                    $timePeriod['toYear'],
                    $timePeriod['toQuarter'] * 3
                )
            );
            $timePeriod['fromDate']->sub(new DateInterval('P' . ($minMonths - 1) . 'M'));
            $timePeriod['fromYear'] = (int)$timePeriod['fromDate']->format('Y');
            $timePeriod['fromQuarter'] = $this
                ->createQuarterFromMonth((int)$timePeriod['fromDate']->format('n'));
        } else {
            $timePeriod['fromDate'] = \DateTime::createFromFormat(
                'Y-m-d H:i:s',
                sprintf(
                    "%d-%02d-01 00:00:00",
                    $timePeriod['fromYear'],
                    (($timePeriod['fromQuarter'] - 1) * 3 ?: 1)
                )
            );
            $timePeriod['toDate'] = \DateTime::createFromFormat(
                'Y-m-d H:i:s',
                sprintf(
                    "%d-%02d-01 00:00:00",
                    $timePeriod['toYear'],
                    $timePeriod['toQuarter'] * 3
                )
            );
        }
        $timePeriod['toDate']->add(new DateInterval('P1M'));
        $interval = $timePeriod['toDate']->diff($timePeriod['fromDate']);
//        echo \yii\helpers\VarDumper::dumpAsString([
//            '{timePeriod}' => $timePeriod,
//            '{interval}' => $interval,
//            '{minMonths}' => $minMonths,
//            '{maxMonths}' => $maxMonths,
//        ]);
        $timePeriod['toDate']->sub(new DateInterval('PT1S'));
        $timePeriod['interval'] = $timePeriod['toDate']->diff($timePeriod['fromDate']);
        $durationInMonths = $interval->m + ($interval->y * 12) + ($interval->d > 1 ? 1 : 0);
        if ($durationInMonths < $minMonths || $durationInMonths > $maxMonths) {
            if (!is_null($_timePeriod)) {
                $this->endWithError(
                    strtr(
                        'You can choose between {minQuarters} to {maxQuarters} quarters. To remove limits, buy premium now!',
                        [
                            '{minQuarters}' => $minQuarters,
                            '{maxQuarters}' => $maxQuarters,
                        ]
                    ),
                    $this->invalidTimePeriodRedirectRoute
                );
            }
        }
//        echo \yii\helpers\VarDumper::dumpAsString([
//            '{durationInMonths}' => $durationInMonths,
//            '{timePeriod}' => $timePeriod,
//        ]);

        $this->_timePeriod = $timePeriod;
    }

    protected function endWithError($message, $redirectRoute = null)
    {
        Yii::$app->session->addFlash(
            'error',
            $message
        );
        $url = Yii::$app->urlManager->createUrl($redirectRoute);
        Yii::$app->response->clear();
        if (YII_ENV_PROD) {
            Yii::info('Redirect to ' . VarDumper::dumpAsString($url));
            Yii::$app->response->redirect($url);
        } else {
            Yii::$app->session->addFlash(
                'info',
                Html::a('Redirect to ' . VarDumper::dumpAsString($url), $url)
            );
        }
//        Yii::$app->end();
    }
}

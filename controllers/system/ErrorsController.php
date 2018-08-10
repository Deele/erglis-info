<?php

namespace app\controllers\system;

use yii\web\Controller;
use Yii;
use yii\web\Response;

/**
 * Error controller handles error display
 */
class ErrorsController extends Controller
{
    public $layout = 'blank';

    public function init()
    {
        parent::init();
        Response::$httpStatuses = [
            100 => Yii::t('app.common.misc.ErrorMessages', 'Continue'),
            101 => Yii::t('app.common.misc.ErrorMessages', 'Switching Protocols'),
            102 => Yii::t('app.common.misc.ErrorMessages', 'Processing'),
            118 => Yii::t('app.common.misc.ErrorMessages', 'Connection timed out'),
            200 => Yii::t('app.common.misc.ErrorMessages', 'OK'),
            201 => Yii::t('app.common.misc.ErrorMessages', 'Created'),
            202 => Yii::t('app.common.misc.ErrorMessages', 'Accepted'),
            203 => Yii::t('app.common.misc.ErrorMessages', 'Non-Authoritative'),
            204 => Yii::t('app.common.misc.ErrorMessages', 'No Content'),
            205 => Yii::t('app.common.misc.ErrorMessages', 'Reset Content'),
            206 => Yii::t('app.common.misc.ErrorMessages', 'Partial Content'),
            207 => Yii::t('app.common.misc.ErrorMessages', 'Multi-Status'),
            208 => Yii::t('app.common.misc.ErrorMessages', 'Already Reported'),
            210 => Yii::t('app.common.misc.ErrorMessages', 'Content Different'),
            226 => Yii::t('app.common.misc.ErrorMessages', 'IM Used'),
            300 => Yii::t('app.common.misc.ErrorMessages', 'Multiple Choices'),
            301 => Yii::t('app.common.misc.ErrorMessages', 'Moved Permanently'),
            302 => Yii::t('app.common.misc.ErrorMessages', 'Found'),
            303 => Yii::t('app.common.misc.ErrorMessages', 'See Other'),
            304 => Yii::t('app.common.misc.ErrorMessages', 'Not Modified'),
            305 => Yii::t('app.common.misc.ErrorMessages', 'Use Proxy'),
            306 => Yii::t('app.common.misc.ErrorMessages', 'Reserved'),
            307 => Yii::t('app.common.misc.ErrorMessages', 'Temporary Redirect'),
            308 => Yii::t('app.common.misc.ErrorMessages', 'Permanent Redirect'),
            310 => Yii::t('app.common.misc.ErrorMessages', 'Too many Redirect'),
            400 => Yii::t('app.common.misc.ErrorMessages', 'Bad Request'),
            401 => Yii::t('app.common.misc.ErrorMessages', 'Unauthorized'),
            402 => Yii::t('app.common.misc.ErrorMessages', 'Payment Required'),
            403 => Yii::t('app.common.misc.ErrorMessages', 'Forbidden'),
            404 => Yii::t('app.common.misc.ErrorMessages', 'Not Found'),
            405 => Yii::t('app.common.misc.ErrorMessages', 'Method Not Allowed'),
            406 => Yii::t('app.common.misc.ErrorMessages', 'Not Acceptable'),
            407 => Yii::t('app.common.misc.ErrorMessages', 'Proxy Authentication Required'),
            408 => Yii::t('app.common.misc.ErrorMessages', 'Request Time-out'),
            409 => Yii::t('app.common.misc.ErrorMessages', 'Conflict'),
            410 => Yii::t('app.common.misc.ErrorMessages', 'Gone'),
            411 => Yii::t('app.common.misc.ErrorMessages', 'Length Required'),
            412 => Yii::t('app.common.misc.ErrorMessages', 'Precondition Failed'),
            413 => Yii::t('app.common.misc.ErrorMessages', 'Request Entity Too Large'),
            414 => Yii::t('app.common.misc.ErrorMessages', 'Request-URI Too Long'),
            415 => Yii::t('app.common.misc.ErrorMessages', 'Unsupported Media Type'),
            416 => Yii::t('app.common.misc.ErrorMessages', 'Requested range unsatisfiable'),
            417 => Yii::t('app.common.misc.ErrorMessages', 'Expectation failed'),
            418 => Yii::t('app.common.misc.ErrorMessages', 'I\')m a teapot'),
            421 => Yii::t('app.common.misc.ErrorMessages', 'Misdirected Request'),
            422 => Yii::t('app.common.misc.ErrorMessages', 'Unprocessable entity'),
            423 => Yii::t('app.common.misc.ErrorMessages', 'Locked'),
            424 => Yii::t('app.common.misc.ErrorMessages', 'Method failure'),
            425 => Yii::t('app.common.misc.ErrorMessages', 'Unordered Collection'),
            426 => Yii::t('app.common.misc.ErrorMessages', 'Upgrade Required'),
            428 => Yii::t('app.common.misc.ErrorMessages', 'Precondition Required'),
            429 => Yii::t('app.common.misc.ErrorMessages', 'Too Many Requests'),
            431 => Yii::t('app.common.misc.ErrorMessages', 'Request Header Fields Too Large'),
            449 => Yii::t('app.common.misc.ErrorMessages', 'Retry With'),
            450 => Yii::t('app.common.misc.ErrorMessages', 'Blocked by Windows Parental Controls'),
            500 => Yii::t('app.common.misc.ErrorMessages', 'Internal Server Error'),
            501 => Yii::t('app.common.misc.ErrorMessages', 'Not Implemented'),
            502 => Yii::t('app.common.misc.ErrorMessages', 'Bad Gateway or Proxy Error'),
            503 => Yii::t('app.common.misc.ErrorMessages', 'Service Unavailable'),
            504 => Yii::t('app.common.misc.ErrorMessages', 'Gateway Time-out'),
            505 => Yii::t('app.common.misc.ErrorMessages', 'HTTP Version not supported'),
            507 => Yii::t('app.common.misc.ErrorMessages', 'Insufficient storage'),
            508 => Yii::t('app.common.misc.ErrorMessages', 'Loop Detected'),
            509 => Yii::t('app.common.misc.ErrorMessages', 'Bandwidth Limit Exceeded'),
            510 => Yii::t('app.common.misc.ErrorMessages', 'Not Extended'),
            511 => Yii::t('app.common.misc.ErrorMessages', 'Network Authentication Required'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => 'error'
            ],
        ];
    }
}

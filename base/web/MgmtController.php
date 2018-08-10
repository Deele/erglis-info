<?php

namespace app\base\web;

/**
 * MgmtController
 */
abstract class MgmtController extends Controller
{
    public $layout = 'management';
    public $enableCsrfValidation = false;
}

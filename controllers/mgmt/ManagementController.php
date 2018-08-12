<?php

namespace app\controllers\mgmt;

use app\base\web\MgmtController;

class ManagementController extends MgmtController
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}

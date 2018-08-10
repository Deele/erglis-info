<?php
/**
 * Contains \app\base\web\WebUser
 *
 * @link http://www.webmultishop.com/
 * @copyright 2016 SIA "Web Multishop Company"
 * @license http://www.webmultishop.com/license/
 */

namespace app\base\web;

use Yii;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\web\User as YiiWebUser;

/**
 * User is the class for the `user` application component that manages the user
 * authentication status.
 *
 * @property WebUserIdentity|null $identity The identity object associated with the currently logged-in
 * user. `null` is returned if the user is not logged in (not authenticated).
 * @property-read array|string homeRoute {@link User::getHomeRoute()}
 * @property-read array|string loginRoute {@link User::getLoginRoute()}
 * @property-read array|string logoutRoute {@link User::getLogoutRoute()}
 * @property-read array|string registerRoute {@link User::getRegisterRoute()}
 * @property-read array|string passwordResetRoute {@link
 *     User::getPasswordResetRoute()}
 */
class WebUser extends YiiWebUser
{

    /**
     * @inheritdoc
     */
    public function loginRequired($checkAjax = true, $checkAcceptHeader = true)
    {
        $request = Yii::$app->getRequest();
        $canRedirect = !$checkAcceptHeader || $this->checkRedirectAcceptable();
        $destination = null;
        if ($this->enableSession
            && $request->getIsGet()
            && (!$checkAjax || !$request->getIsAjax())
            && $canRedirect
        ) {
            $destination = $request->getUrl();
            $this->setReturnUrl($destination);
        }
        $loginRoute = $this->loginRoute;
        if ($loginRoute !== null && $canRedirect) {
            if ($loginRoute[0] !== Yii::$app->requestedRoute) {
                if (!is_null($destination)) {
                    $loginRoute['destination'] = $destination;
                }
                $loginUrl = Yii::$app->urlManager->createUrl($loginRoute);
                Yii::info(
                    'Login required, redirecting to login url: ' .
                    VarDumper::dumpAsString($loginUrl)
                );

                return Yii::$app->getResponse()->redirect($loginUrl);
            }
        }
        throw new ForbiddenHttpException(Yii::t('yii', 'Login Required'));
    }
}

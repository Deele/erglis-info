<?php
namespace app\widgets\user_login_register\assets;

use yii\web\AssetBundle;

/**
 * User login & register widget asset bundle.
 */
class UserLoginRegisterAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/widgets/user_login_register/assets/dist';
    public $css = [
//        'userLoginRegister.css',
    ];
    public $js = [
//        'userLoginRegister.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}

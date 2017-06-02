<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends Controller
{
	public $enableCsrfValidation = false;
	
	
	public function init()
	{
		parent::init();
		
		
		\Yii::$app->errorHandler->errorAction = '/error/index';
	}
	
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','auth'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
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
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
    	echo date("Y-m-d H:i:s",time());
//     	$this->layout = 'myloyout';
//         return $this->render('trip_index',[
//         		'admin_user '=> Yii::$app->user->identity->admin_user,
//         		'admin_real_name' => Yii::$app->user->identity->admin_nickname,
//         ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'login_loyout';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
        	
        	$admin_id = Yii::$app->user->identity->admin_id;
        	$last_login_ip = $_SERVER['REMOTE_ADDR'];
        	$last_login_time = date('Y-m-d H:i:s',time());
//         	$sql = "UPDATE `zh_admin` SET `last_login_ip`='$last_login_ip' ,`last_login_time`='$last_login_time' WHERE `admin_id`= $admin_id";
//         	Yii::$app->db->createCommand($sql)->execute();
        	
            return $this->goBack();
        } else {
            return $this->render('trip_login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionAuth()
    {
    	$this->layout = 'myloyout';
    	return $this->render('auth');
    }
    
}

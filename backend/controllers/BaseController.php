<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class BaseController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
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
    public function init(){
//         $admin_tag = isset(Yii::$app->user->identity->admin_grade) ? Yii::$app->user->identity->admin_grade : 1 ;
        //获取菜单
//         $menu_arr = array();
//         if($admin_tag == 1){
//             $sql = "SELECT * FROM `y_menu` WHERE tag='1' AND status='1' AND parent_menu_id='0' ORDER BY sort  ";
//             $menu = Yii::$app->db->createCommand($sql)->queryAll();
//             foreach ($menu as $key => $value) {
//                 $sql = "SELECT * FROM `y_menu` WHERE parent_menu_id='{$value['menu_id']}' AND tag='1' AND status='1' ORDER BY sort  ";
//                 $menu1 = Yii::$app->db->createCommand($sql)->queryAll();
//                 $value['child'] = $menu1;
//                 $menu_arr[] = $value;
//             }
//         }else{
//             $sql = "SELECT * FROM `y_menu` WHERE status='1' AND parent_menu_id='0' ORDER BY sort  ";
//             $menu = Yii::$app->db->createCommand($sql)->queryAll();
//              foreach ($menu as $key => $value) {
//                 $sql = "SELECT * FROM `y_menu` WHERE parent_menu_id='{$value['menu_id']}' AND status='1' ORDER BY sort  ";
//                 $menu1 = Yii::$app->db->createCommand($sql)->queryAll();
//                 $value['child'] = $menu1;
//                 $menu_arr[] = $value;
//             }
//         }

    	parent::init();
    	
    	//判断是否登录，如果没有，则跳回login/login
    	if(empty(Yii::$app->user->identity)) {
    		$this->redirect(Url::toRoute(['/site/login']));
    		Yii::$app->end();
    	}
    	
        $menu_arr = array();
        $sql = "SELECT * FROM `y_menu` WHERE  status='1' AND parent_menu_id='0' ORDER BY sort  ";
        $menu = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($menu as $key => $value) {
        	$sql = "SELECT * FROM `y_menu` WHERE parent_menu_id='{$value['menu_id']}' AND tag='1' AND status='1' ORDER BY sort  ";
        	$menu1 = Yii::$app->db->createCommand($sql)->queryAll();
        	$value['child'] = $menu1;
        	$menu_arr[] = $value;
        }
        
        Yii::$app->view->params['menu'] = $menu_arr;
        
        \Yii::$app->errorHandler->errorAction = '/error/index';
    }


//    public function beforeAction_($action)
//    {
//        if (!parent::beforeAction($action)) {
//            return false;
//        }
//        $module = Yii::$app->controller->module->id;
//        $controller = Yii::$app->controller->id;
//        $action = Yii::$app->controller->action->id;
//        $permissionName = $module.'/'.$controller.'/'.$action;
//        if(!\Yii::$app->user->can($permissionName) && Yii::$app->getErrorHandler()->exception === null){
//            throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//        }
//        return true;
//    }

    public function beforeAction($action)
    {
    	if (!parent::beforeAction($action)) {
    		return false;
    	}

    	$admin_tag = Yii::$app->user->identity->admin_grade;		//0是超级管理员
    	$controller = Yii::$app->controller->id;
    	$action = Yii::$app->controller->action->id;
    	$permissionName = $controller.'/'.$action;

    	$sql = "SELECT tag FROM `y_menu` WHERE `controller` = '$controller' AND `action` = '$action' ";
    	$menu_tag = Yii::$app->db->createCommand($sql)->queryOne()['tag'];

    	if($admin_tag != 0 && $menu_tag != $admin_tag) {
//     		return $this->goHome();
    		// return $this->redirect(Url::toRoute(['site/auth']));
    	}

    	return true;
    }
}

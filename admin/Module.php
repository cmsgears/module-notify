<?php
namespace cmsgears\notify\admin;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\notify\common\config\CmsGlobal;

class Module extends \cmsgears\core\common\base\Module {

	public $controllerNamespace = 'cmsgears\notify\admin\controllers';

	public function init() {

		parent::init();

		$this -> setViewPath('@cmsgears/module-notify/admin/views');
	}

	public function getSidebarHtml() {

		$path = Yii::getAlias("@cmsgears") . "/module-notify/admin/views/sidebar.php";

		return $path;
	}
}
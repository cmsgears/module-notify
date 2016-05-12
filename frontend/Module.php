<?php
namespace cmsgears\notify\frontend;

// Yii Imports
use \Yii;

class Module extends \cmsgears\core\common\base\Module {

    public $controllerNamespace = 'cmsgears\notify\frontend\controllers';

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-notify/frontend/views' );
    }
}

?>
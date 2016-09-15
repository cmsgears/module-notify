<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;

class m160622_015413_notify_data extends \yii\db\Migration {

    public $prefix;

    private $site;

    private $master;

    public function init() {

        $this->prefix		= 'cmg_';

        $this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
        $this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

        Yii::$app->core->setSite( $this->site );
    }

    public function up() {

    }

    public function down() {

        echo "m160622_015413_notify_data will be deleted with m160621_014408_core and m160622_015405_notify.\n";

        return true;
    }
}

?>

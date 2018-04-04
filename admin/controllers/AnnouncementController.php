<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\admin\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\admin\controllers\base\Controller as BaseController;

/**
 * AnnouncementController provide actions specific to Announcement model.
 *
 * @since 1.0.0
 */
class AnnouncementController extends BaseController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Services
		$this->modelService = Yii::$app->factory->get( 'announcementService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-announcement', 'child' => 'announcement' ];

		// Return Url
		$this->returnUrl = Url::previous( 'announcements' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/announcement/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'all' => [ [ 'label' => 'Reminders' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'index' => [ 'permission' => $this->crudPermission ],
					'all' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get' ],
					'all' => [ 'get' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AnnouncementController ----------------

	public function actionIndex() {

		return $this->redirect( [ 'all' ] );
	}

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'announcements' );

		$dataProvider = $this->modelService->getPageForAdmin();

		return $this->render( 'all', [
			'dataProvider' => $dataProvider
		]);
	}

}

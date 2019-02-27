<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\controllers\base\Controller;

use cmsgears\core\common\utilities\AjaxUtil;
use cmsgears\core\common\utilities\DateUtil;

/**
 * CalendarController provides actions specific to event model.
 *
 * @since 1.0.0
 */
class CalendarController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission	= CoreGlobal::PERM_USER;
		$this->modelService 	= Yii::$app->factory->get( 'eventService' );
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
					'delete' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'events' => [ 'permission' => $this->crudPermission ],
					'event' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'delete' => [ 'post' ],
					'bulk' => [ 'post' ],
					'events' => [ 'post' ],
					'event' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'delete' => [ 'class' => 'cmsgears\notify\common\actions\event\Delete' ],
			'bulk' => [ 'class' => 'cmsgears\notify\common\actions\event\Bulk' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CalendarController --------------------

	public function actionEvents() {

		$user = Yii::$app->user->GetIdentity();

		$startDate	= Yii::$app->request->post( 'startDate' );
		$endDate	= Yii::$app->request->post( 'endDate' );

		$events = $this->modelService->getByRangeUserId( $startDate, $endDate, $user->id );
		$data	= [];

		foreach( $events as $event ) {

			$data[] = [ 'id' => $event->id, 'title' => "$event->name - $event->description", 'start' => $event->scheduledAt ];
		}

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}

	public function actionEvent( $id ) {

		$event = $this->model;

		$data = $event->getAttributeArray([
			'id', 'name', 'icon', 'description', 'content', 'scheduledAt',
			'preReminderCount', 'preReminderInterval', 'preIntervalUnit',
			'postReminderCount', 'postReminderInterval', 'postIntervalUnit'
		]);

		$data[ 'preIntervalUnit' ]	= DateUtil::$durationMap[ $data[ 'preIntervalUnit' ] ];
		$data[ 'postIntervalUnit' ] = DateUtil::$durationMap[ $data[ 'postIntervalUnit' ] ];
		$data[ 'bannerUrl' ]		= isset( $event->banner ) ? $event->banner->getFileUrl() : null;
		$data[ 'scheduledAt' ]		= date( 'F d, Y - g:i:s A', strtotime( $data[ 'scheduledAt' ] ) );

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}
}

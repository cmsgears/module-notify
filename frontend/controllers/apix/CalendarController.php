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

use cmsgears\core\common\utilities\AjaxUtil;
use cmsgears\core\common\utilities\DateUtil;

/**
 * CalendarController provides actions specific to event model.
 *
 * @since 1.0.0
 */
class CalendarController extends \cmsgears\core\frontend\controllers\apix\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_USER;

		// Services
		$this->modelService = Yii::$app->factory->get( 'eventService' );
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
					'delete' => [ 'permission' => $this->crudPermission ],
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
			'delete' => [ 'class' => 'cmsgears\notify\common\actions\event\Delete', 'user' => true ],
			'bulk' => [ 'class' => 'cmsgears\notify\common\actions\event\Bulk', 'user' => true ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CalendarController --------------------

	public function actionEvents() {

		$user = Yii::$app->core->getUser();

		$startDate	= Yii::$app->request->post( 'startDate' );
		$endDate	= Yii::$app->request->post( 'endDate' );

		$events = $this->modelService->getByRangeUserId( $startDate, $endDate, $user->id );
		$data	= [];

		foreach( $events as $event ) {

			$data[] = [ 'id' => $event->id, 'title' => $event->displayName, 'desc' => $event->description, 'start' => $event->scheduledAt ];
		}

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}

	public function actionEvent( $id ) {

		$event = $this->model;

		$data = $event->getAttributeArray([
			'id', 'name', 'icon', 'title', 'description', 'content', 'scheduledAt',
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

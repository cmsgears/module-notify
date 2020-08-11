<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\base\Migration;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\common\utilities\DateUtil;

class m160628_015413_notify_data extends Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;

	private $master;

	public function init() {

		// Table prefix
		$this->prefix	= Yii::$app->migration->cmgPrefix;

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		Yii::$app->core->setSite( $this->site );
	}

	public function up() {

		// Create RBAC and Site Members
		$this->insertRolePermission();

		// Notification Templates
		$this->insertStatusTemplates();
	}

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Notify Admin', 'notify-admin', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Notify Admin is limited to manage notifications, reminders and activities from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole		= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$ntAdminRole		= Role::findBySlugType( 'notify-admin', CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin Notify', 'admin-notify', CoreGlobal::TYPE_SYSTEM, null, 'The permission admin notify is to manage notifications, reminders and activities from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm		= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$ntAdminPerm	= Permission::findBySlugType( 'admin-notify', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $ntAdminPerm->id ],
			[ $adminRole->id, $ntAdminPerm->id ],
			[ $ntAdminRole->id, $adminPerm->id ], [ $ntAdminRole->id, $userPerm->id ], [ $ntAdminRole->id, $ntAdminPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	public function insertStatusTemplates() {

		$site	= $this->site;
		$master	= $this->master;

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'icon', 'type', 'active', 'description', 'classPath', 'dataForm', 'renderer', 'fileRender', 'layout', 'layoutGroup', 'viewPath', 'view', 'createdAt', 'modifiedAt', 'htmlOptions', 'message', 'content', 'data' ];

		$templates = [
			// Status Templates
			[ $master->id, $master->id, 'Status New', CoreGlobal::TPL_NOTIFY_STATUS_NEW, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective admin on creating a new model.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} created', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been created. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"1","adminEmail":"1","userEmail":"0","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Accepted', CoreGlobal::TPL_NOTIFY_STATUS_ACCEPT, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective admin on accepting a new model.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} accepted', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been accepted for further modifications. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Submitted', CoreGlobal::TPL_NOTIFY_STATUS_SUBMIT, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective admin on submitting a new model.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} submitted', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been submitted for approval. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"1","adminEmail":"1","userEmail":"0","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Rejected', CoreGlobal::TPL_NOTIFY_STATUS_REJECT, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective model owner on rejection.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} rejected', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been reviewed and got rejected. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Re-Submitted', CoreGlobal::TPL_NOTIFY_STATUS_RESUBMIT, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective admin on re-submitting a new model.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} re-submitted', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been re-submitted for approval. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"1","adminEmail":"1","userEmail":"0","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Confirmed', CoreGlobal::TPL_NOTIFY_STATUS_CONFIRM, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective admin on creating a new model.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} confirmed', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been confirmed. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Approved', CoreGlobal::TPL_NOTIFY_STATUS_APPROVE, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective model owner on approval.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} approved', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been approved. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Active', CoreGlobal::TPL_NOTIFY_STATUS_ACTIVE, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective model owner on activation.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} activated', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been activated. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Frozen', CoreGlobal::TPL_NOTIFY_STATUS_FREEZE, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective model owner on freeze.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} frozen', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been frozen. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Blocked', CoreGlobal::TPL_NOTIFY_STATUS_BLOCK, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective model owner on block.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} blocked', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been blocked. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Un-Freeze', CoreGlobal::TPL_NOTIFY_STATUS_UP_FREEZE, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective admin on un-freeze request.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} submitted', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been submitted for activation from frozen state. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"1","adminEmail":"1","userEmail":"0","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Un-Block', CoreGlobal::TPL_NOTIFY_STATUS_UP_BLOCK, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective admin on un-block request.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} submitted', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been submitted for activation from blocked state. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"1","adminEmail":"1","userEmail":"0","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Terminated', CoreGlobal::TPL_NOTIFY_STATUS_TERMINATE, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective model owner on termination.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} terminated', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been terminated and available for historic purpose.', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Status Deleted', CoreGlobal::TPL_NOTIFY_STATUS_DELETE, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective model owner on deletion.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} deleted', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been deleted and no more available.', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			// Status Changed
			[ $master->id, $master->id, 'Status Changed', CoreGlobal::TPL_NOTIFY_STATUS_CHANGE, null, NotifyGlobal::TYPE_NOTIFICATION, true, 'Notification triggered for respective model on status change.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} status changed from <b>{{oldStatus}}</b> to <b>{{newStatus}}</b>', 'Status of <b>{{model.displayName}}</b> has been changed from <b>{{oldStatus}}</b> to <b>{{newStatus}}</b>. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			// Activity Templates
			[ $master->id, $master->id, 'Log Create', NotifyGlobal::TPL_LOG_CREATE, null, NotifyGlobal::TYPE_ACTIVITY, true, 'Activity Log triggered on creating a model.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} created', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been created. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"storeContent":"1","storeData":"1","storeCache":"1"}}' ],
			[ $master->id, $master->id, 'Log Update', NotifyGlobal::TPL_LOG_UPDATE, null, NotifyGlobal::TYPE_ACTIVITY, true, 'Activity Log triggered on updating a model.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} updated', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been updated. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"storeContent":"1","storeData":"1","storeCache":"1"}}' ],
			[ $master->id, $master->id, 'Log Delete', NotifyGlobal::TPL_LOG_DELETE, null, NotifyGlobal::TYPE_ACTIVITY, true, 'Activity Log triggered on deleting a model.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{parentType}} deleted', '<b>{{parentType}}</b> - <b>{{model.displayName}}</b> has been deleted. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"storeContent":"1","storeData":"1","storeCache":"1"}}' ]
		];

		$this->batchInsert( $this->prefix . 'core_template', $columns, $templates );
	}

	public function down() {

		echo "m160628_015413_notify_data will be deleted with m160621_014408_core and m160628_015405_notify.\n";

		return true;
	}

}

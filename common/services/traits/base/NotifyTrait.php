<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\services\traits\base;

// Yii Imports
use Yii;

/**
 * NotifyTrait provide methods specific to notifications, reminders and activities.
 *
 * @since 1.0.0
 */
trait NotifyTrait {

	public function getPageForAdmin() {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.admin" => true ] ] );
	}

	public function getPageByUserId( $userId ) {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.userId" => $userId ] ] );
	}

	public function getPageByParent( $parentId, $parentType, $admin = false ) {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType, "$modelTable.admin" => $admin ] ] );
	}

	public function getRecent( $limit = 5, $config = [] ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::find()->where( $config[ 'conditions' ] )->andWhere( [ 'siteId' => $siteId ] )->limit( $limit )->orderBy( 'createdAt DESC' )->all();
	}

	public function getRecentByParent( $parentId, $parentType, $limit = 5, $config = [] ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::queryByParent( $parentId, $parentType )->andWhere( $config[ 'conditions' ] )->limit( $limit )->orderBy( 'createdAt ASC' )->andWhere([ 'siteId' => $siteId ])->all();
	}

	public function getCount( $consumed = false, $admin = false ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::find()->where( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->andWhere([ 'siteId' => $siteId ])->count();
	}

	public function getUserCount( $userId, $consumed = false, $admin = false ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::queryByUserId( $userId )->andWhere( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->andWhere([ 'siteId' => $siteId ])->count();
	}

	public function getCountByParent( $parentId, $parentType, $consumed = false, $admin = false ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::queryByParent( $parentId, $parentType )->andWhere( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->andWhere([ 'siteId' => $siteId ])->count();
	}

}

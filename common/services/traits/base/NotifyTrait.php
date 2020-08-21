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

	public function getPageForAdmin( $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.admin" ] = true;

		return $this->getPage( $config );
	}

	public function getPageByUserId( $userId, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.admin" ]	= $admin;
		$config[ 'conditions' ][ "$modelTable.userId" ] = $userId;

		return $this->getPage( $config );
	}

	public function getPageByParent( $parentId, $parentType, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.admin" ]		= $admin;
		$config[ 'conditions' ][ "$modelTable.parentId" ]	= $parentId;
		$config[ 'conditions' ][ "$modelTable.parentType" ]	= $parentType;

		return $this->getPage( $config );
	}

	public function getNotifyRecent( $limit = 5, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::find()->where( 'admin=:admin', [ ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		$query->limit( $limit )->orderBy( 'createdAt DESC' );

		return $query->all();
	}

	public function getNotifyRecentByUserId( $userId, $limit = 5, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::queryByUserId( $userId )->andWhere( 'admin=:admin', [ ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		$query->limit( $limit )->orderBy( 'createdAt DESC' );

		return $query->all();
	}

	public function getNotifyRecentByParent( $parentId, $parentType, $limit = 5, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::queryByParent( $parentId, $parentType )->where( 'admin=:admin', [ ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		$query->limit( $limit )->orderBy( 'createdAt DESC' );

		return $query->all();
	}

	public function getNotifyCount( $config = [] ) {

		$consumed	= isset( $config[ 'consumed' ] ) ? $config[ 'consumed' ] : false;
		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::find()->where( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		return $query->count();
	}

	public function getNotifyCountByUserId( $userId, $config = [] ) {

		$consumed	= isset( $config[ 'consumed' ] ) ? $config[ 'consumed' ] : false;
		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::queryByUserId( $userId )->andWhere( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		return $query->count();
	}

	public function getNotifyCountByParent( $parentId, $parentType, $config = [] ) {

		$consumed	= isset( $config[ 'consumed' ] ) ? $config[ 'consumed' ] : false;
		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::queryByParent( $parentId, $parentType )->where( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		return $query->count();
	}

}

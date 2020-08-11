<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\services\interfaces\base;

/**
 * INotify declare methods specific to notifications, reminders and activities.
 *
 * @since 1.0.0
 */
interface INotify {

	public function getPageForAdmin( $config = [] );

	public function getPageByUserId( $userId, $config = [] );

	public function getPageByParent( $parentId, $parentType, $config = [] );

	public function getNotifyRecent( $limit = 5, $config = [] );

	public function getNotifyRecentByUserId( $userId, $limit = 5, $config = [] );

	public function getNotifyRecentByParent( $parentId, $parentType, $limit = 5, $config = [] );

	public function getNotifyCount( $config = [] );

	public function getNotifyCountByUserId( $userId, $config = [] );

	public function getNotifyCountByParent( $parentId, $parentType, $config = [] );

}

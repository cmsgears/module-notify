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

	public function getPageForAdmin();

	public function getPageByUserId( $userId );

	public function getPageByParent( $parentId, $parentType, $admin = false );

	public function getRecent( $limit = 5, $config = [] );

	public function getRecentByParent( $parentId, $parentType, $limit = 5, $config = [] );

	public function getCount( $consumed = false, $admin = false );

	public function getUserCount( $userId, $consumed = false, $admin = false );

	public function getCountByParent( $parentId, $parentType, $consumed = false, $admin = false );

}

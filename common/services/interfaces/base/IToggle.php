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
 * IToggle declare methods specific to toggle read, consumed and trash status.
 *
 * @since 1.0.0
 */
interface IToggle {

	public function toggleRead( $model );

	public function markNew( $model );

	public function markConsumed( $model );

	public function toggleTrash( $model );

	public function unTrash( $model );

	public function markTrash( $model );

}

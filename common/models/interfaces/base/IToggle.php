<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\models\interfaces\base;

/**
 * The IToggle interface provide methods specific to models supporting consumed and trash features.
 *
 * @since 1.0.0
 */
interface IToggle {

	/**
	 * Check whether activity status is new.
	 *
	 * @return boolean
	 */
	public function isNew();

	/**
	 * Check whether activity status is consumed.
	 *
	 * @return boolean
	 */
	public function isConsumed();

	/**
	 * Returns string representation of consumed flag.
	 *
	 * @return string
	 */
	public function getConsumedStr();

	/**
	 * Check whether activity status is trash.
	 *
	 * @return boolean
	 */
	public function isTrash();

	/**
	 * Returns string representation of trash flag.
	 *
	 * @return string
	 */
	public function getTrashStr();

	/**
	 * Returns the list item html.
	 *
	 * @return string
	 */
	public function toHtml();

}

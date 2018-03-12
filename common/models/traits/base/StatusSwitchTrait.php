<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\models\traits\base;

// Yii Imports
use Yii;
use yii\helpers\Url;

/**
 * The models having siteId column and supporting multi-site must use this trait.
 *
 * @property boolean $consumed
 * @property boolean $trash
 *
 * @since 1.0.0
 */
trait StatusSwitchTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// StatusSwitchTrait ---------------------

	/**
	 * Check whether activity status is new.
	 *
	 * @return boolean
	 */
	public function isNew() {

		return !$this->consumed;
	}

	/**
	 * Check whether activity status is consumed.
	 *
	 * @return boolean
	 */
	public function isConsumed() {

		return $this->consumed;
	}

	/**
	 * Returns string representation of consumed flag.
	 *
	 * @return string
	 */
	public function getConsumedStr() {

		return Yii::$app->formatter->asBoolean( $this->consumed );
	}

	/**
	 * Check whether activity status is trash.
	 *
	 * @return boolean
	 */
	public function isTrash() {

		return $this->trash;
	}

	/**
	 * Returns string representation of trash flag.
	 *
	 * @return string
	 */
	public function getTrashStr() {

		return Yii::$app->formatter->asBoolean( $this->trash );
	}

	/**
	 * Returns the list item html.
	 *
	 * @return string
	 */
	public function toHtml() {

		$content	= "<li class='new'>";

		if( $this->isConsumed() ) {

			$content	= "<li class='consumed'>";
		}

		if( $this->isTrash() ) {

			$content	= "<li class='trash'>";
		}

		if( $this->admin && !empty( $this->adminLink ) ) {

			$link		= Url::toRoute( [ $this->adminLink ], true );
			$content	= $content . "<a href='$link'>$this->content</a></li>";
		}
		else if( !empty( $this->link ) ) {

			$link		= Url::toRoute( [ $this->link ], true );
			$content	= $content . "<a href='$link'>$this->content</a></li>";
		}
		else {

			$content	= $content . "$this->content</li>";
		}

		return $content;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// StatusSwitchTrait ---------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

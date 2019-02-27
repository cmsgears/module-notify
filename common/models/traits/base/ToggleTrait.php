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
 * The models having consumed and trash must use this trait.
 *
 * @property boolean $consumed
 * @property boolean $trash
 *
 * @since 1.0.0
 */
trait ToggleTrait {

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

	// ToggleTrait ---------------------------

	/**
	 * @inheritdoc
	 */
	public function isNew() {

		return !$this->consumed;
	}

	/**
	 * @inheritdoc
	 */
	public function isConsumed() {

		return $this->consumed;
	}

	/**
	 * @inheritdoc
	 */
	public function getConsumedStr() {

		return Yii::$app->formatter->asBoolean( $this->consumed );
	}

	/**
	 * @inheritdoc
	 */
	public function isTrash() {

		return $this->trash;
	}

	/**
	 * @inheritdoc
	 */
	public function getTrashStr() {

		return Yii::$app->formatter->asBoolean( $this->trash );
	}

	/**
	 * @inheritdoc
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

	// ToggleTrait ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

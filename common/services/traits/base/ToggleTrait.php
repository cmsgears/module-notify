<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\services\traits\base;

/**
 * ToggleTrait provide methods specific to toggle read, consumed and trash status.
 *
 * @since 1.0.0
 */
trait ToggleTrait {

	public function toggleRead( $model ) {

		if( $model->isConsumed() ) {

			return $this->markNew( $model );
		}

		return $this->markConsumed( $model );
	}

	public function markNew( $model ) {

		$model->consumed = false;

		return parent::update( $model, [
			'attributes' => [ 'consumed' ]
		]);
	}

	public function markConsumed( $model ) {

		$model->consumed = true;

		return parent::update( $model, [
			'attributes' => [ 'consumed' ]
		]);
	}

	public function markTrash( $model ) {

		$model->trash = true;

		return parent::update( $model, [
			'attributes' => [ 'trash' ]
		]);
	}

}

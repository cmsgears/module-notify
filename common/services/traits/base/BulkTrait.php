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
 * BulkTrait provide methods specific to bulk actions.
 *
 * @since 1.0.0
 */
trait BulkTrait {

	public function applyBulkByAdmin( $column, $action, $target ) {

		foreach( $target as $id ) {

			$model = $this->getById( $id );

			if( isset( $model ) && $model->admin ) {

				$this->applyBulk( $model, $column, $action, $target );
			}
		}
	}

}

<?php
/**
 * Gifts class
 *
 */

class Gifts extends ElggObject {

	/**
	 * A single-word arbitrary string that defines what
	 * kind of object this is
	 *
	 * @var string
	 */
	const SUBTYPE = 'gift';

	/**
	 * {@inheritDoc}
	 * @see ElggObject::initializeAttributes()
	 */
	protected function initializeAttributes() {

		parent::initializeAttributes();

		$this->attributes['subtype'] = self::SUBTYPE;
	}
}

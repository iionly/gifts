<?php
/**
 * Gifts File class
 *
 */

class GiftsFile extends ElggFile {

	/**
	 * A single-word arbitrary string that defines what
	 * kind of object this is
	 *
	 * @var string
	 */
	const SUBTYPE = 'giftsfile';

	/**
	 * {@inheritDoc}
	 * @see ElggObject::initializeAttributes()
	 */
	protected function initializeAttributes() {

		parent::initializeAttributes();

		$this->attributes['subtype'] = self::SUBTYPE;
	}

	/**
	 * {@inheritDoc}
	 * @see ElggEntity::canDelete()
	 */
	public function canDelete($user_guid = 0) {

		$user_guid = (int) $user_guid;
		if (empty($user_guid)) {
			$user_guid = elgg_get_logged_in_user_guid();
		}

		return elgg_is_admin_user($user_guid);
	}
}

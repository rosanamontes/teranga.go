<?php
/**
 * Class for trip discussion reply
 * 
 * We extend ElggComment to get the future thread support.
 */
class ElggDiscussionReply extends ElggComment 
{
	/**
	 * Set subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "discussion_reply";
	}
}

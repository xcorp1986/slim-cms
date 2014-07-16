<?php namespace Illuminate\Support;

use Countable;

class MessageBag implements Countable {

	/**
	 * All of the registered messages.
	 *
	 * @var array
	 */
	protected $messages = array();

	/**
	 * Default format for message output.
	 *
	 * @var string
	 */	
	protected $format = '<span class="help-inline">:message</span>';

	/**
	 * Add a message to the bag.
	 *
	 * @param  string  $key
	 * @param  string  $message
	 * @return void
	 */
	public function add($key, $message)
	{
		if ($this->isUnique($key, $message))
		{
			$this->messages[$key][] = $message;
		}
	}

	/**
	 * Determine if a key and message combination already exists.
	 *
	 * @param  string  $key
	 * @param  string  $message
	 * @return bool
	 */
	protected function isUnique($key, $message)
	{
		$messages = (array) $this->messages;

		return ! isset($messages[$key]) or ! in_array($message, $messages[$key]);
	}

	/**
	 * Determine if messages exist for a given key.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function has($key = null)
	{
		return $this->first($key) !== '';
	}

	/**
	 * Get the first message from the bag for a given key.
	 *
	 * @param  string  $key
	 * @param  string  $format
	 * @return string
	 */
	public function first($key = null, $format = null)
	{
		$messages = $this->get($key, $format);

		return (count($messages) > 0) ? $messages[0] : '';
	}

	/**
	 * Get all of the messages from the bag for a given key.
	 *
	 * @param  string  $key
	 * @param  string  $format
	 * @return array
	 */
	public function get($key, $format = null)
	{
		$format = $this->checkFormat($format);

		// If the message exists in the container, we will transform it and return
		// the message. Otherwise, we'll return an empty array since the entire
		// methods is to return back an array of messages in the first place.
		if (array_key_exists($key, $this->messages))
		{
			return $this->transform($this->messages[$key], $format);
		}

		return array();
	}

	/**
	 * Get all of the messages for every key in the bag.
	 *
	 * @param  string  $format
	 * @return array
	 */
	public function all($format = null)
	{
		$format = $this->checkFormat($format);

		$all = array();

		foreach ($this->messages as $messages)
		{
			$all = array_merge($all, $this->transform($messages, $format));
		}

		return $all;
	}

	/**
	 * Format an array of messages.
	 *
	 * @param  array   $messages
	 * @param  string  $format
	 * @return array
	 */
	protected function transform($messages, $format)
	{
		$messages = (array) $messages;

		// We will simply spin through the given messages and transform each one
		// replacing the :message place holder with the real message allowing
		// the messages to be easily formatted to each developer's desires.
		foreach ($messages as $key => &$message)
		{
			$message = str_replace(':message', $message, $format);
		}

		return $messages;
	}

	/**
	 * Get the appropriate format based on the given format.
	 *
	 * @param  string  $format
	 * @return string
	 */
	protected function checkFormat($format)
	{
		return ($format === null) ? $this->format : $format;
	}

	/**
	 * Get the raw messages in the container.
	 *
	 * @return array
	 */
	public function getMessages()
	{
		return $this->messages;
	}

	/**
	 * Get the default message format.
	 *
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
	}

	/**
	 * Set the default message format.
	 *
	 * @param  string  $format
	 */
	public function setFormat($format = ':message')
	{
		$this->format = $format;
	}

	/**
	 * Get the number of messages in the container.
	 *
	 * @return int
	 */
	public function count()
	{
		return count($this->messages);
	}

}
<?php

/**
 * Baseclass for simpleSAML Exceptions
 *
 * This class tries to make sure that every exception is serializable.
 *
 * @author Thomas Graff <thomas.graff@uninett.no>
 * @package simpleSAMLphp_base
 * @version $Id$
 */
class SimpleSAML_Error_Exception extends Exception {

	/**
	 * The backtrace for this exception.
	 *
	 * We need to save the backtrace, since we cannot rely on
	 * serializing the Exception::trace-variable.
	 *
	 * @var string
	 */
	private $backtrace;


	/**
	 * Constructor for this error.
	 *
	 * @param string $message Exception message
	 * @param int $code Error code
	 */
	public function __construct($message, $code = 0) {
		assert('is_string($message) || is_int($code)');

		parent::__construct($message, $code);

		$this->backtrace = SimpleSAML_Utilities::buildBacktrace($this);
	}


	/**
	 * Retrieve the backtrace.
	 *
	 * @return array  An array where each function call is a single item.
	 */
	public function getBacktrace() {
		return $this->backtrace;
	}


	/**
	 * Replace the backtrace.
	 *
	 * This function is meant for subclasses which needs to replace the backtrace
	 * of this exception, such as the SimpleSAML_Error_Unserializable class.
	 *
	 * @param array $backtrace  The new backtrace.
	 */
	protected function setBacktrace($backtrace) {
		assert('is_array($backtrace)');

		$this->backtrace = $backtrace;
	}


	/**
	 * Function for serialization.
	 *
	 * This function builds a list of all variables which should be serialized.
	 * It will serialize all variables except the Exception::trace variable.
	 *
	 * @return array  Array with the variables which should be serialized.
	 */
	public function __sleep() {

		$ret = array();

		$ret = array_keys((array)$this);

		foreach ($ret as $i => $e) {
			if ($e === "\0Exception\0trace") {
				unset($ret[$i]);
			}
		}

		return $ret;
	}

}

?>
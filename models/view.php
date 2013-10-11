<?php
/**
 * @file
 * Handles the view functionality
 */
class View_Model
{
	/**
	 * Holds variables assigned to template
	 */
	private $data = array();

	/**
	 * Holds render status of view.
	 */
	private $render = FALSE;

	/**
	 * Accept a template to load
	 */
	public function __construct($template)
	{
		//compose file name
		$file = SERVER_ROOT . '/views/' . strtolower($template) . '.php';

		if (file_exists($file))
		{
			/**
			 * trigger render to include file when this model is destroyed
			 * if we render it now, we wouldn't be able to assign variables
			 * to the view!
			 */
			$this->render = $file;
		}
	}

	/**
	 * Receives assignments from controller and stores in local data array
	 *
	 * @param $variable
	 * @param $value
	 */
	public function assign($variable , $value)
	{
		$this->data[$variable] = $value;
	}

	/**
	 * Render the output directly to the page, or optionally, return the
	 * generated output to caller.
	 *
	 * @param $direct_output Set to any non-TRUE value to have the
	 * output returned rather than displayed directly.
	 */
	public function render($direct_output = TRUE)
	{
		// Turn output buffering on, capturing all output
		if ($direct_output !== TRUE)
		{
			ob_start();
		}

		// Parse data variables into local variables
		$data = $this->data;

    //Create variable variables
    //In the templates you can use the name of the variabled
    //named in the assign function
    foreach ($data as $variable => $value) {
      $$variable = $value;
    }
		// Get template
		include($this->render);

		// Get the contents of the buffer and return it
		if ($direct_output !== TRUE)
		{
			return ob_get_clean();
		}
	}

}

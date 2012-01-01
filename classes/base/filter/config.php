<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Copyright 2011 Spadefoot
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * This class handles a filter's configurations.
 *
 * @package Intercepting Filters
 * @category Filter
 * @version 2011-12-22
 */
class Base_Filter_Config extends Kohana_Object {

	/**
	 * This variable stores the description given for the filter.
	 *
	 * @access protected
	 * @var string
	 */
	protected $description = NULL;

	/**
	 * This variable stores the name of the filter.
	 *
	 * @access protected
	 * @var string
	 */
	protected $filter_name = NULL;

	/**
	 * This variable stores the initial parameters specified for the filter.
	 *
	 * @access protected
	 * @var array
	 */
	protected $parameters = NULL;

	/**
	 * This constructor initializes this class with the specified arguments.
	 *
	 * @access public
	 * @param string $filter_name           the name of the filter
	 * @param array $parameters             the initial parameters
	 * @param string $description           a description of the filter
	 * @return Filter_Config                an instance of this class
	 */
	public function __construct($filter_name, $parameters, $description) {
		$this->filter_name = $filter_name;
		$this->parameters = $parameters;
		$this->description = $description;
	}

	/**
	 * This function returns the description given to the filter.
	 *
	 * @access public
	 * @return string                       the description given to the filter
	 */
	public function get_description() {
		return (!empty($this->description)) ? $this->description : '';
	}

	/**
	 * This function returns the name of the filter.
	 *
	 * @access public
	 * @return string                       the name of the filter
	 */
	public function get_filter_name() {
		return (!empty($this->filter_name)) ? $this->filter_name : '';
	}

	/**
	 * This function returns the value of the named parameter; otherwise, will return
	 * NULL if the named parameter is not set.
	 *
	 * @access public
	 * @return mixed                        the value of the named parameter
	 */
	public function get_parameter($name) {
		if (isset($this->parameters[$name])) {
			return $this->parameters[$name];
		}
		return NULL;
	}

	/**
	 * This function returns an array of all of the parameter names.
	 *
	 * @access public
	 * @return array                        an array of all of the parameter names
	 */
	public function get_parameter_names() {
		return array_keys($this->parameters);
	}

}
?>
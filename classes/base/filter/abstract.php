<?php defined('SYSPATH') OR die('No direct access allowed.');

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
 * This abstract class specifies the functions that a Filter class must implement.
 *
 * @package Intercepting Filters
 * @category Filter
 * @version 2011-12-22
 *
 * @abstract
 */
abstract class Base_Filter_Abstract extends Kohana_Object {

	/**
	 * This variable stores the Filter configurations.
	 *
	 * @access protected
	 * @var Filter_Config
	 */
	protected $config = NULL;

	/**
	 * This constructor initializes the class with the specified filter
	 * configurations.
	 *
	 * @access public
	 * @final
	 * @param Filter_Config $config         the filter configurations
	 * @return Filter_Abstract              an instance of this class
	 */
	public final function __construct(Filter_Config $config) {
		$this->config = $config;
	}

	/**
	 * This function will causes the filter to be executed during the pre-processing
	 * phase of execution.
	 *
	 * @access public
	 * @abstract
	 * @param Request $request              the client's request
	 * @param Response $response            the server's response
	 */
	public abstract function pre_process(Request $request, Response $response);

	/**
	 * This function will causes the filter to be executed during the post-processing
	 * phase of execution.
	 *
	 * @access public
	 * @abstract
	 * @param Request $request              the client's request
	 * @param Response $response            the server's response
	 */
	public abstract function post_process(Request $request, Response $response);

}
?>
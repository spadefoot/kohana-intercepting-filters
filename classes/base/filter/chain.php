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
 * This class handles the processing of filters.
 *
 * @package Intercepting Filters
 * @category Filter
 * @version 2011-12-22
 */
class Base_Filter_Chain extends Kohana_Object {

	/**
	 * This variable stores a reference to a Request instance.
	 *
	 * @access protected
	 * @var Request
	 */
    protected $request = NULL;

	/**
	 * This variable stores a reference to a Response instance.
	 *
	 * @access protected
	 * @var Response
	 */
    protected $response = NULL;

    /**
     * This variable stores an array of filters.
     *
     * @access public
     * @var array
     */
    protected $filters = array();

    /**
     * This construct creates an instance of this class.
     *
     * @access public
     * @param Request $request              the client's request
     * @param Response $response            the server's response
     * @return Filter_Chain                 an instance of this class
     */
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * This function adds a filter to the ordered chain.
     *
     * @access public
     * @param Filter_Abstract $filter       the filter to be added
     */
    public function add_filter(Filter_Abstract $filter) {
        $this->filters[] = $filter;
    }

    /**
     * This function will causes the filter to be executed during the pre-processing
     * phase of execution.
     *
     * @access public
     */
    public function pre_process() {
        for ($i = 0; $i < count($this->filters); $i++) {
            $this->filters[$i]->pre_process($this->request, $this->response);
        }
    }

    /**
     * This function will causes the filter to be executed during the post-processing
     * phase of execution.
     *
     * @access public
     */
    public function post_process() {
		for ($i = count($this->filters) - 1; $i >= 0; $i--) {
            $this->filters[$i]->post_process($this->request, $this->response);
        }
    }

}
?>
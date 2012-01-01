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
 * This class overrides Kohana's Request_Class_Internal class so that a filter manager
 * could be added.
 *
 * @package Intercepting Filters
 * @category Filter
 * @version 2011-12-22
 */
class Base_Filter_Request extends Kohana_Request_Client_Internal {

	/**
	 * Processes the request, executing the controller action that handles this
	 * request, determined by the [Route].
	 *
	 * 1. Before the controller action is called, the [Controller::before] method
	 * will be called.
	 * 2. Next the controller action will be called.
	 * 3. After the controller action is called, the [Controller::after] method
	 * will be called.
	 *
	 * By default, the output from the controller is captured and returned, and
	 * no headers are sent.
	 *
	 *     $request->execute();
	 *
	 * @param   Request $request
	 * @return  Response
	 * @throws  Kohana_Exception
	 * @uses    [Kohana::$profiling]
	 * @uses    [Profiler]
	 * @deprecated passing $params to controller methods deprecated since version 3.1
	 *             will be removed in 3.2
	 * @license http://kohanaframework.org/license
	 */
	public function execute(Request $request)
	{
		// Check for cache existance
		if ($this->_cache instanceof Cache AND ($response = $this->cache_response($request)) instanceof Response)
			return $response;

		// Create the class prefix
		$prefix = 'controller_';

		// Directory
		$directory = $request->directory();

		// Controller
		$controller = $request->controller();

		if ($directory)
		{
			// Add the directory name to the class prefix
			$prefix .= str_replace(array('\\', '/'), '_', trim($directory, '/')).'_';
		}

		if (Kohana::$profiling)
		{
			// Set the benchmark name
			$benchmark = '"'.$request->uri().'"';

			if ($request !== Request::$initial AND Request::$current)
			{
				// Add the parent request uri
				$benchmark .= ' « "'.Request::$current->uri().'"';
			}

			// Start benchmarking
			$benchmark = Profiler::start('Requests', $benchmark);
		}

		// Store the currently active request
		$previous = Request::$current;

		// Change the current request to this request
		Request::$current = $request;

		// Is this the initial request
		$initial_request = ($request === Request::$initial);

		try {
			// Initiate response time
			$this->_response_time = time();

			if ( ! class_exists($prefix.$controller)) {
				throw new HTTP_Exception_404('The requested URL :uri was not found on this server.', array(':uri' => $request->uri()));
			}

			// Load XML resource that contains the filter definitions
			$resource = XML::load('web.xml');

			// Initialize filter parser
			$parser = new Filter_Parser($resource);

			// Get a response object
			$response = ($request->response()) ? $request->response() : $request->create_response();

			// Create the filter chain
			$filter_chain = new Filter_Chain($request, $response);

			// Determine the action to use
			$action = $request->action();

			// Create the URL pattern for fetching filters
			$url_pattern = $controller . '/' . $action;

			// Add filters to filter chain
			$parser->parse($filter_chain, $url_pattern);

			// Perform the pre-processing of the filter
			$filter_chain->pre_process();

			// Load the controller using reflection
			$class = new ReflectionClass($prefix.$controller);

			// Check whether the controller class is abstract
			if ($class->isAbstract()) {
				throw new Kohana_Exception('Cannot create instances of abstract :controller', array(':controller' => $prefix.$controller));
			}

			// Create a new instance of the controller
			$controller = $class->newInstance($request, $response);

			// Execute the "before action" method
			$class->getMethod('before')->invoke($controller);

			// Fetch the request parameters
			$params = $request->param();

			// If the action doesn't exist, it's a 404
			if ( ! $class->hasMethod('action_'.$action)) {
				throw new HTTP_Exception_404('The requested URL :uri was not found on this server.', array(':uri' => $request->uri()));
			}

			/**
			 * Execute the main action with the parameters
			 *
			 * @deprecated $params passing is deprecated since version 3.1
			 *             will be removed in 3.2.
			 */
			$class->getMethod('action_'.$action)->invokeArgs($controller, $params);

			// Execute the "after action" method
			$class->getMethod('after')->invoke($controller);

			// Perform the post-processing of the filter
			$filter_chain->post_process();

			// Stop response time
			$this->_response_time = (time() - $this->_response_time);

			// Add the default Content-Type header to initial request if not present
			if ($initial_request AND ! $request->headers('content-type'))
			{
				$request->headers('content-type', Kohana::$content_type.'; charset='.Kohana::$charset);
			}
		}
		catch (Exception $e)
		{
			// Restore the previous request
			Request::$current = $previous;

			if (isset($benchmark))
			{
				// Delete the benchmark, it is invalid
				Profiler::delete($benchmark);
			}

			// Re-throw the exception
			throw $e;
		}

		// Restore the previous request
		Request::$current = $previous;

		if (isset($benchmark))
		{
			// Stop the benchmark
			Profiler::stop($benchmark);
		}

		// Cache the response if cache is available
		if ($this->_cache instanceof Cache)
		{
			$this->cache_response($request, $request->response());
		}

		// Return the response
		return $request->response();
	}

}
?>
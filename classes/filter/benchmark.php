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
 * This filter provides a way to test the load time of a particular controller.
 *
 * @package Intercepting Filters
 * @category Filter
 * @version 2011-12-22
 */
class Filter_Benchmark extends Filter_Adaptor {

    /**
     * This variable stores the start time.
     *
     * @access protected
     * @var timestamp
     */
    protected $start_time = NULL;

    /**
     * This function will calculate the start time and temporarily store it.
     *
     * @access public
     * @param Request $request              the client's request
     * @param Response $response            the server's response
     */
    public function pre_process(Request $request, Response $response) {
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $this->start_time = $mtime;
    }

    /**
     * This function will calculate the end time and will echo out the difference.
     *
     * @access public
     * @param Request $request              the client's request
     * @param Response $response            the server's response
     */
    public function post_process(Request $request, Response $response) {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $end_time = $mtime;
        $total_time = ($end_time - $this->start_time);
        echo 'This page was created in ' . $total_time . ' seconds.';
    }

}
?>
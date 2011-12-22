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
 * This class minifies the content of a response.
 *
 * @package Intercepting Filters
 * @category Filter
 * @version 2011-12-22
 */
class Filter_Minify extends Filter_Adaptor {

    /**
     * This function removes any extra whitespace from the response's content body.
     *
     * @access public
     * @param Request $request              the client's request
     * @param Response $response            the server's response
     */
    public function post_process(Request $request, Response $response) {
        $content = $response->body();
        $content = preg_replace('/(?:(?)|(?))(\s+)(?=\<\/?)/', '', $content);
        $response->body($content);
    }

}
?>
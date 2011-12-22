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
 * This filter provides a way to redirect a client for a particular page.
 *
 * @package Intercepting Filters
 * @category Filter
 * @version 2011-12-22
 */
class Filter_Redirect extends Filter_Adaptor {

    /**
     * This function will redirect the client.
     *
     * @access public
     * @param Request $request              the client's request
     * @param Response $response            the server's response
     */
    public function pre_process(Request $request, Response $response) {
        $redirect = $this->config->get_parameter('redirect');
        $request->redirect($redirect);
    }

}
?>
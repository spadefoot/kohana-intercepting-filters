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
class Request_Client_Internal extends Filter_Request {}
?>
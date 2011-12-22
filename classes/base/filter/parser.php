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
 * This class parses the specified XML file for the needed filters.
 *
 * @package Intercepting Filters
 * @category Filter
 * @version 2011-12-22
 */
class Base_Filter_Parser extends Kohana_Object {

    /**
     * This variable stores the XML resource with the filter definitions.
     *
     * @access protected
     * @var SimpleXMLElement
     */
    protected $resource = NULL;

    /**
     * This function initializes the class with the specified arguments.
     *
     * @access public
     * @param SimpleXMLElement $resource        the XML resource with the filter definitions
     * @return Filter_Parser                    an instance of this class
     */
    public function __construct(SimpleXMLElement $resource) {
        $this->resource = $resource;
    }

    /**
     * This function parses the filter definitions using the specified URL pattern and adds the
     * needed filters to the filter chain after instantiating them.  A filter is added to the filter
     * chain in the same order as in the filter definitions file.
     *
     * @access public
     * @param Filter_Chain $filter_chain        the filter chain to be populated
     * @param string $url_pattern               the URL pattern that will be used to locate
     *                                          the needed filter definitions
     */
    public function parse(Filter_Chain $filter_chain, $url_pattern) {
        // Gets the URL segments
        $url_segments = explode('/', $url_pattern);
        // Gets all filter node matching the URL pattern
        $nodes = $this->resource->xpath("/web-app/filter[filter-name=/web-app/filter-mapping[url-pattern='*/*' or url-pattern='{$url_segments[0]}/*' or url-pattern='{$url_pattern}' or url-pattern='*/{$url_segments[1]}']/filter-name]");
        // Loops through the filters
        foreach ($nodes as $node) {
            $children = $node->children();
		    if (count($children) > 0) {
		        $filter_name = '';
		        $filter_class = '';
		        $init_param = array();
		        $description = '';
		        foreach ($children as $child) {
		            switch (strtolower($child->getName())) {
    	                case 'filter-name':
    	                    $filter_name = $this->get_filter_name($child);
    	                break;
		                case 'filter-class':
		                    $filter_class = $this->get_filter_class($child);
		                break;
		                case 'init-param':
		                    $init_param = array_merge($this->get_init_param($child), $init_param);
		                break;
                        case 'description':
                            $description = $this->get_description($child);
                        break;
		                default:
		                    throw new Filter_Exception('Invalid node in filter definition', array(':node' => $child));
		                break;
		            }
		        }
		        if (!empty($filter_class)) {
    		        $filter_config = new Filter_Config($filter_name, $init_param, $description);
                    $filter = new $filter_class($filter_config);
                    $filter_chain->add_filter($filter);
		        }
		    }
	    }
    }

    ///////////////////////////////////////////////////////////////HELPERS//////////////////////////////////////////////////////////////

    /**
     * This function processes a "description" node.
     *
     * @access protected
     * @param object &$node                     a reference to the "description" node
     * @return string                           a description of the filter
     */
    protected function get_description(&$node) {
        $description = trim((string)$node[0]);
        return $description;
    }

    /**
     * This function processes a "filter-name" node.
     *
     * @access protected
     * @param object &$node                     a reference to the "filter-name" node
     * @return string                           the name of the filter
     */
    protected function get_filter_name(&$node) {
        $filter_name = trim((string)$node[0]);
        return $filter_name;
    }

    /**
     * This function processes a "filter-class" node.
     *
     * @access protected
     * @param object &$node                     a reference to the "filter-class" node
     * @return string                           the name of the filter class
     */
    protected function get_filter_class(&$node) {
        $filter_class = trim((string)$node[0]);
        return $filter_class;
    }

    /**
     * This function processes a "init-param" node.
     *
     * @access protected
     * @param object &$node                     a reference to the "init-param" node
     * @return array                            a name/value pair for a parameter
     */
    protected function get_init_param(&$node) {
        $init_param = array();
        $children = $node->children();
	    if (count($children) > 0) {
	        $param_name = '';
	        $param_value = '';
	        foreach ($children as $child) {
	            switch (strtolower($child->getName())) {
	                case 'param-name':
	                    $param_name = $this->get_param_name($child);
	                break;
	                case 'param-value':
	                    $param_value = $this->get_param_value($child);
	                break;
	                default:
	                    throw new Filter_Exception('Invalid node in filter definition', array(':node' => $child));
	                break;
	            }
	        }
	        if (!empty($param_name)) {
	            $init_param[$param_name] = $param_value;
            }
	    }
	    return $init_param;
    }

    /**
     * This function processes a "param-name" node.
     *
     * @access protected
     * @param object &$node                     a reference to the "param-name" node
     * @return string                           the name of a parameter
     */
    protected function get_param_name(&$node) {
        $param_name = trim((string)$node[0]);
        return $param_name;
    }

    /**
     * This function processes a "param-value" node.
     *
     * @access protected
     * @param object &$node                     a reference to the "param-value" node
     * @return string                           the value of a parameter
     */
    protected function get_param_value(&$node) {
        $param_value = trim((string)$node[0]);
        return $param_value;
    }

}
?>
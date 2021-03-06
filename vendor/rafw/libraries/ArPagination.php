<?php

/*
 * Copyright 2008 Wilker Lucio <wilkerlucio@gmail.com>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License. 
 */

/**
 * Pagination class to work with ActiveRecord
 *
 * This classe provides a easy way to create pagination using ActiveRecord
 * as data layer of application
 *
 * @version 1.0.2
 * @author Wilker Lucio <wilkerlucio@gmail.com>
 */
class Ra_ArPagination
{
	private $model;
	public $query;
	private $per_page;
	private $config;

	private $q;
	
	/**
	 * Creates a new pagination object
	 *
	 * @param $model A string containg the name of model to be used or the model itself (can use named scopes)
	 * @param $per_page The number of records per pege to display
	 * @param $query Query data to be passed when quering ActiveRecord
	 * @return ArPagination
	 */
	public function __construct($model, $per_page = 10, $query = array())
	{
		$this->model = is_string($model) ? ActiveRecord::model($model) : $model;
		$this->query = $query;
		$this->per_page = $per_page;
		
		$this->config = $this->default_config();
	}



	private function default_config()
	{
		return array(
			'cur_page'        => 1,
			'num_links'       => 5,
			'base_url'        => '',
			
			'full_tag_open'   => '<p>',
			'full_tag_close'  => '</p>',
			
			'first_link'      => '&laquo;',
			'first_tag_open'  => ' ',
			'first_class'     => '',
			'first_tag_close' => ' ',
			
			'last_link'       => '&raquo;',
			'last_class'      => '',
			'last_tag_open'   => ' ',
			'last_tag_close'  => ' ',
			
			'next_link'       => '&gt;',
			'next_class'      => 'next',
			'next_tag_open'   => ' ',
			'next_tag_close'  => ' ',
			
			'prev_link'       => '&lt;',
			'prev_class'      => '',
			'prev_tag_open'   => ' ',
			'prev_tag_close'  => ' ',
			
			'first_inactive_link'      => '',
			'first_inactive_class'     => '',
			'first_inactive_tag_open'  => '',
			'first_inactive_tag_close' => '',
			
			'last_inactive_link'       => '',
			'last_inactive_class'      => '',
			'last_inactive_tag_open'   => '',
			'last_inactive_tag_close'  => '',
			
			'next_inactive_link'       => '',
			'next_inactive_class'      => '',
			'next_inactive_tag_open'   => '',
			'next_inactive_tag_close'  => '',
			
			'prev_inactive_link'       => '',
			'prev_inactive_class'      => '',
			'prev_inactive_tag_open'   => '',
			'prev_inactive_tag_close'  => '',
			
			'cur_tag_open'   => '<b>',
			'cur_tag_close'  => '</b>',
			
			'num_tag_open'   => ' ',
			'num_tag_close'  => ' ',
			
			'num_separator'  => ' ',
		);
	}

	private function make_link($page, $text, $base_url = "", $class = "")
	{
		$l = "";
		if($this->q)
			$l = "?q={$this->q}";
		if($class){$class = "class=\"$class\"";}
		$link = "<a href=\"{$this->config['base_url']}{$page}{$l}\" {$class}>";
		$link .= $text;
		$link .= "</a>";
		
		return $link;
	}

	private function make_wrap($page, $prefix, $link = null)
	{
		$wrapper = $this->config[$prefix . '_tag_open'];
		$wrapper .= $this->make_link($page, $link ? $link : $this->config[$prefix . '_link'], "", isset($this->config[$prefix . '_class'])?$this->config[$prefix . '_class']:null);
		$wrapper .= $this->config[$prefix . '_tag_close'];
		
		return $wrapper;
	}
	
	private function make_wrap_wl($page, $prefix, $link = null) {
		$wrapper = $this->config[$prefix . '_tag_open'];
		$wrapper .= $link ? $link : $this->config[$prefix . '_link'];
		$wrapper .= $this->config[$prefix . '_tag_close'];
		
		return $wrapper;
	}
	
	/**
	 * Get a link to a page
	 *
	 * @param integer $page The number of page
	 * @return string
	 */
	public function link_to_page($page)
	{
		return $this->config['base_url'] . $page;
	}

	/**
	 * Set a configuration option
	 * You can pass one associative array to set many options at once
	 *
	 * @param $data A string containg the name of configuration to change (or one
	 *	associative array to set many options at once)
	 * @param $value The value of option (if you passed a string at first argument)
	 * @return void
	 */
	public function set_config($data, $value = null)
	{
		if (is_array($data) && $value === null) {
			$this->config = array_merge($this->config, $data);
		} else {
			$this->config[$data] = $value;
		}
	}

	/**
	 * Get the total number of records
	 *
	 * @return integer The number of records to be paginated
	 */
	public function get_total()
	{
		$query = $this->query;
		if(isset($query['order']))
			$query['order'] = $this->model->table() . '.id';
		return $this->model->count($query);
	}
	
	/**
	 * Get current page (normalized)
	 *
	 * @return integer
	 */
	public function get_cur_page()
	{
		$total = $this->get_total();
		$pages = ceil($total / $this->per_page);
		$page = $this->config['cur_page'];
		
		if ($page < 1) {
			$page = 1;
		} elseif ($page > $pages) {
			$page = max($pages, 1);
		}
		
		return $page;
	}

	/**
	 * Get data of current page
	 *
	 * @return array Array containg data of current page
	 */
	public function data()
	{
		$query = $this->query;
		$query['limit'] = $this->per_page;
		$query['offset'] = ($this->get_cur_page() - 1) * $this->per_page;
		return $this->model->all($query);
	}
	
	/**
	 * Get the number of pages
	 *
	 * @return integer Number of pages
	 */
	public function get_total_pages()
	{
		return ceil($this->get_total() / $this->per_page);
	}
	
	/**
	 * Test if there is a previous page
	 *
	 * @return boolean
	 */
	public function has_prev()
	{
		return $this->get_cur_page() > 1;
	}
	
	/**
	 * Test if there is a next page
	 *
	 * @return boolean
	 */
	public function has_next()
	{
		return $this->get_cur_page() < $this->get_total_pages();
	}
	
	/**
	 * Get a link to the previous page
	 *
	 * @return string
	 */
	public function prev_link()
	{
		return $this->link_to_page($this->get_cur_page() - 1);
	}
	
	/**
	 * Get a link to the next page
	 *
	 * @return string
	 */
	public function next_link()
	{
		return $this->link_to_page($this->get_cur_page() + 1);
	}

	/**
	 * Generate and return the links to navigation
	 *
	 * @return string Navigation links
	 */
	public function create_links($flag = true, $q = null)
	{
		$total = $this->get_total();
		$pages = $this->get_total_pages();
		$cur_page = $this->get_cur_page();
		
		$page_range = ($this->config['num_links'] - 1) / 2;
		
		$page_start = $cur_page - ceil($page_range);
		$page_end = $cur_page + floor($page_range);
		
		if($q)
			$this->q = $q;

		if ($page_start < 1) {
			$page_end += 1 - $page_start;
			$page_start = 1;
		}
		
		if ($page_end > $pages) {
			$page_start = max(1, $page_start - ($page_end - $pages));
			$page_end = $pages;
		}
		
		$links = '';
		
		$links .= $this->config['full_tag_open'];
		
		if ($this->has_prev()) {
			if ($this->config['first_link']) $links .= $this->make_wrap(1, 'first');
			if ($this->config['prev_link']) $links .= $this->make_wrap($cur_page - 1, 'prev');
		} else {
			if ($this->config['first_inactive_link']) $links .= $this->make_wrap_wl(1, 'first_inactive');
			if ($this->config['prev_inactive_link']) $links .= $this->make_wrap_wl($cur_page - 1, 'prev_inactive');
		}
		
		if($flag)
		for ($i = $page_start; $i <= $page_end; $i++) {
			if ($i != $cur_page) {
				$links .= $this->make_wrap($i, 'num', $i);
			} else {
				$links .= $this->make_wrap_wl($i, 'cur', $i);
			}

			$links .= $i < $page_end ? $this->config['num_separator'] : '';
		}
		
		if ($this->has_next()) {
			if ($this->config['next_link']) $links .= $this->make_wrap($cur_page + 1, 'next');
			if ($this->config['last_link']) $links .= $this->make_wrap($pages, 'last');
		} else {
			if ($this->config['next_inactive_link']) $links .= $this->make_wrap_wl($cur_page + 1, 'next_inactive');
			if ($this->config['last_inactive_link']) $links .= $this->make_wrap_wl($pages, 'last_inactive');
		}
		
		$links .= $this->config['full_tag_close'];
		
		return $links;
	}
}

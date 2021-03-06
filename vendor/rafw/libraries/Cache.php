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

class Ra_Cache
{
	public static function normalize_path($path)
	{
		return RA_CACHE_PATH . '/' . trim($path, '/');
	}
	
	public static function expire($uri)
	{
		if (is_array($uri)) {
			//TODO: implement to delete cache by url given arguments
		}
		
		$uri = self::normalize_path($uri);
		
		if (is_file($uri)) {
			unlink($uri);
		}
	}
	
	public static function cache($route, $data)
	{
		$file = self::normalize_path($route) . '.html';
		
		Ra_DirectoryHelper::mkdir($file, true);
		
		file_put_contents($file, $data);
	}
}

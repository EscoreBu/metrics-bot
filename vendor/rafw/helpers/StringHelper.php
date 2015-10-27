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
 * This class provides some string util functions for strings and texts
 *
 * @author Wilker Lucio
 */
class Ra_StringHelper
{
	/**
	 * Remove accents from string
	 *
	 * @param string $string The string to remove accents
	 * @return string
	 */
	public static function remove_accents($string)
	{
		$with_accents    = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$without_accents = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYbsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		
		return strtr(utf8_decode($string), utf8_decode($with_accents), $without_accents);
	}
	
	/**
	 * Check if a string starts with another string
	 *
	 * @param $haystack The string to search in
	 * @param $needle The string to be searched
	 * @return boolean True if found, false otherwise
	 */
	public static function starts_with($haystack, $needle)
	{
		$end_len = strlen($needle);
		
		if (strlen($haystack) < $end_len) {
			return false;
		}
		
		$bit = substr($haystack, 0, $end_len);
		
		return $bit == $needle;
	}
	
	/**
	 * Check if a string ends with another string
	 *
	 * @param $haystack The string to search in
	 * @param $needle The string to be searched
	 * @return boolean True if found, false otherwise
	 */
	public static function ends_with($haystack, $needle)
	{
		$end_len = strlen($needle);
		
		if (strlen($haystack) < $end_len) {
			return false;
		}
		
		$bit = substr($haystack, -$end_len);
		
		return $bit == $needle;
	}
	
	public static function camelize($name)
	{
		$name = strtolower($name);
		$normalized = "";
		
		$upper = true;
		
		for ($i = 0; $i < strlen($name); $i++) {
			$normalized .= $upper ? strtoupper($name[$i]) : $name[$i];
			if ($upper) $upper = false;
			if ($name[$i] == '_') $upper = true;
		}
		
		return $normalized;
	}
	
	public function underscore($string)
	{
		$string = str_replace("\\", "/", $string);
		$string = preg_replace("/([A-Z]+)([A-Z][a-z])/", '$1_$2', $string);
		$string = preg_replace("/([a-z\d])([A-Z])/", '$1_$2', $string);
		$string = strtr($string, "-", "_");
		
		return strtolower($string);
	}
	
	/**
	 * Gives a normalized string that can be used at urls
	 *
	 * @param string $string The string to be converted
	 * @return string Converted string
	 */
	public static function normalize($string)
	{
		//first, set all to lowercase
		$string = strtolower($string);
		
		//convert spaces into dashes
		$string = str_replace(' ', '-', $string);
		
		//remove out of range characters
		$out = '';
		
		for ($i = 0; $i < strlen($string); $i++) { 
			$c = ord($string[$i]);
			
			if ($c == 45 || ($c > 47 && $c < 58) || ($c > 96 && $c < 123)) {
				$out .= $string[$i];
			}
		}
		
		return $out;
	}
	
	/**
	 * Truncate a string
	 * 
	 * @return string The truncated string
	 * @param string $string The string to be truncated
	 * @param integer $length The max length of string
	 * @param boolean $preserve_words[optional] Pass true if you want to preserve the words of string
	 * @param string $padding[optional] You can use this to change the default padding string
	 */
	public static function truncate($string, $length, $preserve_words = false, $padding = "...")
	{
		if (strlen($string) <= $length) return $string;
		
		$string = str_replace("\r\n", "\n", $string);
		$truncated = substr($string, 0, $length - strlen($padding));
		
		if ($preserve_words) {
			while (!in_array($string[strlen($truncated)], array(' ', "\n", "\t"))) {
				$truncated = substr($truncated, 0, strlen($truncated) - 1);
			}
		}
		
		$truncated .= $padding;
		
		return $truncated;
	}
	
	/**
	 * Parse a simple string template with given parameters
	 * 
	 * You should use # to define variables, example:
	 *   Ra_StringHelper::simple_template("Hello #some, welcome!", array("some" => "World"));
	 * 
	 * This sample will output: Hello World, welcome!
	 * 
	 * The variables inside template should contains only alphabetic chars, any other char will
	 * stop the variable name parsing.
	 * 
	 * @return string
	 * @param string $template The string containing the template to be parsed
	 * @param array $vars The parameters to include into template
	 */
	public static function simple_template($template, $vars)
	{
		$output = "";
		$var_reg = "";
		$mode = 0;
		
		for ($i = 0; $i < strlen($template); $i++) {
			$char = $template[$i];
			
			if ($mode == 0) {
				switch ($char) {
					case '#':
						$mode = 1;
						$var_reg = "";
						break;
					default:
						$output .= $char;
				}
			} elseif ($mode == 1) {
				$code = ord($char);
				
				if ($code > 96 && $code < 123) {
					$var_reg .= $char;
					
					if ($i == (strlen($template) - 1)) {
						$output .= $vars[$var_reg];
					}
				} else {
					$output .= $vars[$var_reg];
					$output .= $char;
					$mode = 0;
				}
			}
		}
		
		return $output;
	}
	
	/**
	 * Generates a random string
	 *
	 * @param integer $length The length of generated string
	 * @param string $charset The charset to be used
	 * @return string The string generated
	 */
	public static function random($length, $charset = 'abcdefghijklmnopqrstuvxywzABCDEFGHIJKLMNOPQRSTUVXYWZ0123456789')
	{
		$out = "";
		
		for ($i = 0; $i < $length; $i++) { 
			$index = rand(0, strlen($charset) - 1);
			
			$out .= $charset[$index];
		}
		
		return $out;
	}
	
	public static function zero_fill($string, $n)
	{
		$string = $string . '';
		
		while(strlen($string) < $n) {
			$string = '0' . $string;
		}
		
		return $string;
	}
	
	public static function force_http($string)
	{
		if (!self::starts_with($string, 'http://')) {
			$string = 'http://' . $string;
		}
		
		return $string;
	}


	public static function create_slug($string, $slug = '-')
	{
		$string = strtolower($string);
		$string = self::remove_accents($string);
		// Código ASCII das vogais
		$ascii['a'] = range(224, 230);
		$ascii['e'] = range(232, 235);
		$ascii['i'] = range(236, 239);
		$ascii['o'] = array_merge(range(242, 246), array(240, 248));
		$ascii['u'] = range(249, 252);

		// Código ASCII dos outros caracteres
		$ascii['b'] = array(223);
		$ascii['c'] = array(231);
		$ascii['d'] = array(208);
		$ascii['n'] = array(241);
		$ascii['y'] = array(253, 255);

		foreach ($ascii as $key=>$item) {
			$acentos = '';
			foreach ($item AS $codigo) $acentos .= chr($codigo);
			$troca[$key] = '/['.$acentos.']/i';
		}

		$string = preg_replace(array_values($troca), array_keys($troca), $string);

		// Slug?
		if ($slug) {
			// Troca tudo que não for letra ou número por um caractere ($slug)
			$string = preg_replace('/[^a-z0-9]/i', $slug, $string);
			// Tira os caracteres ($slug) repetidos
			$string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
			$string = trim($string, $slug);
		}

		return $string;
	}

	// public static function create_slug($field) 
	// { 
	// 	$field = preg_replace("[^a-zA-Z0-9_]", "-", self::remove_accents($field));

	// 	$slug = strtolower(str_replace(' ', '-', $field)); 

	// 	return $slug; 
	// }

	public static function substr($str, $max = 300, $end = '...')
	{
		$str        = strip_tags($str); // Retira HTML da String
		$countChar  = strlen($str); // Conta o numéro de caracteres em uma string

		if($countChar <= $max)
			return $str;
		else
		{
			$str    = substr($str, 0, $max);
			$space  = strrpos($str, ' ');
			$str    = substr($str, 0, $space);
			return $str.$end;
		}
	}
}

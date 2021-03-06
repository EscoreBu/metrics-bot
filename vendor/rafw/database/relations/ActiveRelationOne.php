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

require_once(dirname(__FILE__) . '/ActiveRelation.php');

/**
 * This class provides one to one relations
 *
 * @package default
 * @author wilker
 */
class ActiveRelationOne extends ActiveRelation
{
    public function refresh()
    {
        if (isset($this->options['polymorphic']) && $this->options['polymorphic'] == true) {
        	$as = Ra_StringHelper::underscore($this->foreign_model);
        	$id_field = $as . "_id";
        	$type_field = $as . "_type";
        	
        	$value = $this->local_model->$id_field;
        	
        	return ActiveRecord::model($this->local_model->$type_field)->find($value);
        } else {
	        $foreign_field = $this->get_foreign_field($this->foreign_model);
	        $foreign_key_field = $this->foreign_model->primary_key();
	        
	        $data = $this->foreign_model->find($this->local_model->$foreign_field);
	        
	        return $data;
        }
    }
    
    public function push($data)
    {
  		if (isset($this->options['polymorphic']) && $this->options['polymorphic'] == true) {
      	$as = $this->foreign_model;
      	$id_field = $as . "_id";
      	$type_field = $as . "_type";
      	
      	$this->local_model->$id_field = $data->primary_key_value();
      	$this->local_model->$type_field = get_class($data);
      } else {
        $foreign_field = $this->get_foreign_field($this->foreign_model);
        
        $this->local_model->$foreign_field = $data->primary_key_value();
      }
    }
} // END class ActiveRelationOne extends ActiveRelation

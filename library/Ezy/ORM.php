<?php
/**
 * @author Kevin Thant
 * Abstract base class for ORM style classes to extend for common methods and functionalities
 */
abstract class Ngashint_ORM{
	
	//Please dont change this array in the child classes
	public static $MYSQL_NUMERIC_TYPES = array('TINYINT','SMALLINT','MEDIUMINT','INT','BIGINT','DECIMAL','FLOAT','DOUBLE','REAL','BIT','BOOL','SERIAL');
	
	/**
	 * Constructor - to load the record from DB and intialize it
	 * @param unknown_type $object_id (Assuming there is only one primary key to load the record from db)
	 * @return void
	 */
	public function __construct($object_id)
	{
	
		if(empty($object_id))
			throw new Exception('NULL or empty object ID is given');
			
		$this->_mdl = new $this->_mdl_name;
		$record = $this->_mdl->get($object_id);
		
		if($record == NULL)
		{
			throw new Ngashint_ORM_RecordNotFoundException('Record cannot be found for '.$this->_mdl_name . ':'. get_class($this));
		}
		
		$this->_record = $record;
	}
	
	/*
	 * overloaded getter method, return the user record's column value
	 * @access - public
	 * @param string $varname
	 * @return - mixed
	 */
	public function __get($varname)
	{
		if(isset($this->_record->{$varname}))
			return $this->_record->{$varname};
	}
	
	/**
	 * overloaded setter method, set the value for user record
	 * @param string $varname
	 * @param mixed $value
	 * @return false on invalid variable name
	 */
	public function __set($varname, $value)
	{
		if(isset($this->_record->{$varname})){
			$this->_record->{$varname} = $value;
		}
		else
			return false;
	}
	
	public function toArray()
	{
		return $this->_record->toArray();
	}
	
	/**
	 * Save the current state of the record (into the database)
	 * @return unknown_type
	 */
	public function save()
	{
		if(isset($this->_record))
		{
			$this->_record->save();
		}
		else
			throw new Ngashint_Exception("Record is not initialized yet", get_class($this), "save");
	}
	
	/**
	 * Delete the current object
	 * @return void
	 */
	public function delete()
	{
		$this->_record->delete();
		unset($this); //unset the currenct instance object, not yet tested
	}
	
	/*
	 * Get record information in array
	 * @access - public
	 * @return array
	 */
	public function getInfo()
	{
		return $this->_record->toArray();
	}
	
	/**
	 * Return the model of the current Feed Base
	 * @return Thant_Model inherited object
	 */
	public function getModel()
	{
		return $this->_mdl;
	}
	
	public static function getModelName()
	{
		
	}
	
	/**
	 * Search records with given "query" in given varname is array if field name to search for is more than one
	 * @param unknown_type $query
	 * @param unknown_type $search)by(array if multiple fields to search)
	 * @param string $order_by
	 * @param unknown_type $block_size
	 * @param unknown_type $offset
	 * @param mixed $cols (* = all columns, array for specific column(s)) 
	 * @return array
	 */
	public static function search($query, $search_by = '*', $wildcard = true, $order_by = '', $block_size = 20, $offset = 0, $cols = '*')
	{
		//TODO : defunct method
		$model_name = self::getModelName();
		
		$mdl = new $model_name;
		$table_info = $mdl->info();
		$columns = $table_info['cols'];
		$metadata = $table_info['metadata'];
		
		$where = "";
		
		if(is_array($search_by))
		{
			foreach($search_by as $var)
			{
				$var = trim($var);
				if(!in_array($var, $columns))
					throw new Ngashint_ORM_KeyNotFoundException($var.' is not found', get_class($this), 'search');
			    
				if(in_array(strtoupper($metadata[$var]['DATA_TYPE']), self::$MYSQL_NUMERIC_TYPES))
					$where .= $var. " = ".addslashes($query)." OR "; //wildcard does not make sense for comparing numeric values, so leave it out
				else
					$where .= $var. ($wildcard ? " LIKE '%".addslashes($query)."%'" : " = '".addslashes($query)."'")." OR ";
			}
			$where = substr($where, 0, -4);
		}
		else
		{
			if(!in_array(trim($search_by), $columns))	
				throw new Ngashint_ORM_KeyNotFoundException($search_by.' is not found', get_class($this), 'search');
			
			if(in_array(strtoupper($metadata[$search_by]['DATA_TYPE']), self::$MYSQL_NUMERIC_TYPES))
				$where .= $search_by. " = ".addslashes($query); //wildcard does not make sense for comparing numeric values, so leave it out
			else
				$where = $search_by. ($wildcard ? " LIKE '%".addslashes($query)."%'" : " = '".addslashes($query)."'");
		}
		
		$result = $mdl->search($cols, $where, $order, $offset, $blocksize);
			
		if($result != NULL)
			return $result->toArray();
		else
			return NULL;
	}
}
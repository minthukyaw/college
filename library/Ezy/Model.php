<?
abstract class Ezy_Model extends Zend_Db_Table_Abstract{
	
	private static $_transDepth = 0;
	
	public static function getNewInstance()
	{
		$className = get_called_class();
		$obj = new $className;
		return $obj->get();
	}
	
        public static function getInstance($id = null){
            
            if($id != NULL){
                $className = get_called_class();
                $obj = new $className;
                return $obj->get($id);
            }
            else{
                $className = get_called_class();
                $obj = new $className;
                return $obj->get();
            }
            
            
        }
        
	public function countAll()
	{
		$select = $this->select();
                $select->from($this->_name,'COUNT(*) AS num');
                return $this->fetchRow($select)->num;
	}
	
	public function getAll($sortedColumn = null, $sortedOrder = 'ASC')
	{
		$select = $this->select();
                if($sortedColumn != null){
                    $order = $sortedColumn.' '.$sortedOrder;
                    $select->order($order);
                }
                
		return $this->fetchAll($select);
	}
        
        public function getChunk($length = 20, $offset = 0, $sortedColumn = null, $sortedOrder = 'ASC'){
                $select = $this->select();
                $select->limit($length, $start);
                
                if($sortedColumn != null){
                    $order = $sortedColumn.' '.$sortedOrder;
                    $select->order($order);
                }
                
                return $this->fetchAll($select);
                
        }
	
	public function search($select, $where = "", $order = '', $start = 0, $length = NULL, $join = array(), $distinctRow = false)
	{
		if($select != "*")
		{
			$select  = $this->select()->from($this, $select);
		}
		else{
			
			$select = $this->select();	
		}
		
		//Parameter bounding
		if(is_array($where))
		{
			foreach($where as $columnCondition => $value)
			{
				$select->where($columnCondition, $value);
			}
		}
		else if($where != "")
		{
			$select->where($where);
		}
		
		if($order != "")
		{
			$select->order($order);	
		}
		
		if($length != NULL)
		{
			$select->limit($length, $start);
			//echo $select->__toString();
			
		}
		
		
		if(count($join) > 1)
		{
			$select->setIntegrityCheck(false);
			
			if(count($join['tables']) == 1)
			{
				$select->join($join['tables'], $join['conditions'], $join['columns']);
			}
			else
			{
				$i = 0;
				foreach($join['tables'] as $key => $table)
				{
					if(is_numeric($key))
					{
						$columns = isset($join['columns'][$i]) ? $join['columns'][$i]: '*';
						$select->join($table, $join['conditions'][$i], $columns);
					}
					else{
						$columns = isset($join['columns'][$i]) ? $join['columns'][$i]: '*';
						$select->join(array($key => $table), $join['conditions'][$i], $columns);
					}
					$i++;
				}	
			}
		}
	 	
		if($distinctRow)
		{
			$select->distinctRow();	
		}
		
		try{
			
			$rowset = $this->fetchAll($select);
		}
		catch(Exception $e)
		{
			echo $e->getMessage()."\n<br/>";	
			exit();
		}
		if($rowset->count() < 1) return NULL;
		return $rowset;
	}
	
	public function search2($select, $where = "", $order = '', $start = 0, $length = NULL, $joins = array(), $distinctRow = false)
	{
		if($select != "*")
		{
			$select  = $this->select()->from($this, $select);
		}
		else{
			
			$select = $this->select();	
		}
		
		//Parameter bounding
		if(is_array($where))
		{
			foreach($where as $columnCondition => $value)
			{
				$select->where($columnCondition, $value);
			}
		}
		else if($where != "")
		{
			$select->where($where);
		}
		
		
		if($order != "")
		{
			$select->order($order);	
		}
		
		if($length != NULL)
		{
			$select->limit($length, $start);
			//echo $select->__toString();
			
		}
		
		/* Example join params
		 *
			$joins = array(
				'type' => 'left',
				'key' => 'p',
				'table' => 'tbl_test',
				'conditinos' => 'tbl_test.id > 0',
				'columns' => array('tbl_test.name)
			);
		 */
		if(count($joins) > 0)
		{
			$select->setIntegrityCheck(false);
			
			foreach($joins as $join)
			{
                           
				if(!isset($join['table'])) continue;
				
				$table = $join['table'];
				$join_method = isset($join['type']) ? $join['type'] : 'join';
				$conditions = isset($join['conditions']) ? $join['conditions'] : '';
				$columns = isset($join['columns']) ? $join['columns'] : array();
				$select->{$join_method}($table, $conditions, $columns);
			}
		}
                
	 	
		if($distinctRow)
		{
			$select->distinctRow();	
		}
		
		try{
			$rowset = $this->fetchAll($select);
		}
		catch(Exception $e)
		{
                        error_log($e->getTraceAsString()."\nQuery:".$select->__toString());
			throw $e;
		}
		
	
		if($rowset->count() < 1) return NULL;
		return $rowset;
		
	}
	
	public function customsearch($sql)
	{
		return $this->fetchAll($sql);	
	}
	
	public function simplesearch($select, $col, $criteria, $order = '', $join = array())
	{
		if($select != "*")
		{
			$select  = $this->select($select)->where($col.' = ?', $criteria);
		}
		else
		{
			$select  = $this->select()->where($col.' = ?', $criteria);
		}
		if($order != "")
		{
			$select->order($order);	
		}
		
		if(count($join) > 1)
		{
			$select->join($join['tables'], $join['conditions']);
			
		}
		
		$rowset = $this->fetchAll($select);
		if($rowset->count() < 1) return NULL;
		return $rowset;
	}
	
	public function get($id = '')
	{
		if($id != null && $id != '')
		{
			if(is_array($id))
			{
				$rowset = call_user_func_array(array($this, "find"), $id);
			}
			else
			{
				$rowset = $this->find($id);
			}
			if($rowset->count() < 1)
			{
				return NULL;	
			}
			else
			{
				return $rowset->current();	
			}
		}
		else
		{
			return $this->createRow();
		}
	}
	
	public function beginTransaction()
	{
		self::$_transDepth++;
		
		if(self::$_transDepth == 1)
		{
			$db = $this->getDefaultAdapter();
			$db->beginTransaction();
		}
		
	}
	
	public function rollBack()
	{
		if(self::$_transDepth > 0)
		{
			$db = $this->getDefaultAdapter();
			$db->rollBack();
			self::$_transDepth = 0;
		}
	}
	
	public function commit()
	{
		if(self::$_transDepth == 1)
		{
			$db = $this->getDefaultAdapter();
			$db->commit();
		}
		
		self::$_transDepth--;
	}
	
}
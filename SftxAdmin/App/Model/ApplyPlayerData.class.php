<?php

/**
 * 玩家数据修改申请
 * @author 陈成禧
 *
 */
class Model_ApplyPlayerData extends Model{
	protected $_tableName = 'apply_player_data';
	
	/**
	 * 根据ID取得记录集
	 * @param array $ids
	 */
	public function getListByIds($ids){
			$htmlpSqlSearch = $this->_loadCore ( 'Help_SqlSearch' );
			$htmlpSqlSearch = new Help_SqlSearch ();
			$htmlpSqlSearch->set_tableName ( $this->tName () );
			if(is_array($ids)){
				$params = "Id in(";
				$i=0;
				foreach ($ids as $id){
					if($i++==0){
						$params.=$id;
					}else{
						$params.=','.$id;
					}
				}
				$params.=")";
				$htmlpSqlSearch->set_conditions($params);
			}
			$sql = $htmlpSqlSearch->createSql ();
			return $this->select($sql);
	}
	
}
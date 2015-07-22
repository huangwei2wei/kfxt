<?php
class Util_QuickForm extends Control {

	/**
	 * 忽略的标签
	 * @param array 忽略的标签
	 */
	private $_ignoreLabel=array(
		'title',
		'description',
		'required',
		'options',
	);

	/**
	 * 创建后的html数组
	 * @param 创建后的html数组
	 */
	private $_formHtml=array();

	/**
	 * 用户所选游戏类型
	 * @param 用户所选游戏类型
	 */
	private $_selectedGameType;

	public function __construct(){

	}

	/**
	 * 增加HTML配置数组生成HTML
	 * @param array $arr
	 */
	public function addManyElementArray($arr){
		if (!is_array($arr))return false;
		foreach ($arr as $key=>$value){
			switch (strtolower($value['type'])){
				case 'text' :{
					$label=$this->_createText($value);
					break;
				}
				case 'select' :{
					$label=$this->_createSelect($value);
					break;
				}
				case 'textarea' :{
					$label=$this->_createTextArea($value);
					break;
				}
				case 'game_server_list' :{
					$label=$this->_createGameServerSelect($value);
					break;
				}
				default:{
					break;
				}
			}
			if (is_array($label))array_push($this->_formHtml,$label);
			unset($label);
		}
	}

	/**
	 * @return the $_formHtml
	 */
	public function get_formHtml() {
		return $this->_formHtml;
	}

	public function addElementArray(){

	}

	/**
	 * 生成服务器列表
	 * @param array $params
	 */
	private function _createGameServerSelect($params){
		$labelParams='';
		$label='<select name="game_server_id" >';
		$options='';
		$operator=$this->_getGlobalData('operator_list');
		$serverList=$this->_getGlobalData('gameser_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operator,'Id','operator_name');
		foreach ($serverList as $value){
			if ($value['game_type_id']==$this->_selectedGameType){
				$options.="<option value=\"{$value['operator_id']},{$value['Id']}\">{$value['server_name']}({$operatorList[$value['operator_id']]})</option>";
			}
		}

		$label.=$options;
		$label.='</select>';

		return array(
			'title'=>'请选择游戏服务器',
			'description'=>'选择服务器列表',
			'label'=>$label,
		);
	}
	/**
	 * 建立标签标题
	 * @param array $labelTitelParams 标签参数
	 */
	private function _createLabelTitle($labelTitelParams){
		$label='<label>';
		$label.=$labelTitelParams['required']?'<em>*</em>':'';
		$label.=$labelTitelParams['title'];
		$label.='</label>';
		return $label;
	}
	/**
	 * 建立标签说明
	 * @param array $params
	 */
	private function _createLabelDescription($params){
		$label='<label>';
		$label.=$params['description'];
		$label.='</label>';
		return $label;
	}

	/**
	 * 建立Text标签
	 * @param array $params
	 */
	private function _createText($params){
		$labelParams='';
		foreach ($params as $key=>$value){
			if (in_array($key,$this->_ignoreLabel))continue;
			$labelParams.=" {$key}=\"{$value}\" ";
		}
		$label="<input ";
		$label.=$labelParams;
		$label.=" />";
		if ($params['title'])$labelTitle=$this->_createLabelTitle($params);
		if ($params['description'])$labelDescription=$this->_createLabelDescription($params);
		return array(
			'title'=>$labelTitle,
			'description'=>$labelDescription,
			'label'=>$label,
		);
	}

	/**
	 * 建立textarea
	 * @param array $params
	 */
	private function _createTextArea($params){
		$labelParams='';
		foreach ($params as $key=>$value){
			if (in_array($key,$this->_ignoreLabel))continue;
			$labelParams.=" {$key}=\"{$value}\" ";
		}
		$label="<textarea {$labelParams} >";
		$label.="</textarea>";
		if ($params['title'])$labelTitle=$this->_createLabelTitle($params);
		if ($params['description'])$labelDescription=$this->_createLabelDescription($params);
		return array(
			'title'=>$labelTitle,
			'description'=>$labelDescription,
			'label'=>$label,
		);
	}

	private function _createSelect($params){
		$labelParams='';
		foreach ($params as $key=>$value){
			if (in_array($key,$this->_ignoreLabel))continue;
			$labelParams.=" {$key}=\"{$value}\" ";
		}
		$label="<select {$labelParams} >";
		$options='';
		foreach ($params['options'] as $key=>$value){
			$options.="<option value=\"{$key}\">{$value}</option>";
		}
		$label.=$options;
		$label.='</select>';

		if ($params['title'])$labelTitle=$this->_createLabelTitle($params);
		if ($params['description'])$labelDescription=$this->_createLabelDescription($params);
		return array(
			'title'=>$labelTitle,
			'description'=>$labelDescription,
			'label'=>$label,
		);
	}

	/**
	 * @return the $_selectedGameType
	 */
	public function get_selectedGameType() {
		return $this->_selectedGameType;
	}

	/**
	 * @param $_selectedGameType the $_selectedGameType to set
	 */
	public function set_selectedGameType($_selectedGameType) {
		$this->_selectedGameType = $_selectedGameType;
	}


}
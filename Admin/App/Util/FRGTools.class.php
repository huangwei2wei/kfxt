<?php
/**
 * 富人国工具
 * @author php-朱磊
 *
 */
class Util_FRGTools extends Control {
	private $_objData;
	private $_toolData;
	private $_effData;
	private $_editResult=array();
	private $_editNum=array();
	
	/**
	 * 生成三个道具信息
	 * @param array $objData
	 * @param array $toolData
	 * @param array $objprop
	 */
	public function __construct($objData,$toolData,$objprop){
		$this->_objData=$objData;
		$this->_toolData=$toolData;
		$newEffData=array();
		foreach ($objprop as $key=>$value){
			$newEffData[$key]=array();
			foreach ($value as $childValue){
				$newEffData[$key][$childValue]['Key']="{$key}.{$childValue}";
				$newEffData[$key][$childValue]['Name']=$objData[$key][$childValue]['Name'];
			}
		}
		$this->_effData=$newEffData;
	}
	
	public function getEditResult(){
		return $this->_editResult;
	}
	
	public function getEditNum(){
		return $this->_editNum;
	}
	
	/**
	 * 返回编辑信息
	 * @param array $dataResult
	 */
	public function setEditResult($dataResult){
		if (count($dataResult['GetCond'])){
			$chageCond=array();
			$i=1;
			foreach ($dataResult['GetCond'] as $value){
				$chageCond[$i]['key']="{$value[0]}.{$value[1]}";
				$chageCond[$i]['name']=$this->_objData[$value[0]][$value[1]]['Name'];
				$chageCond[$i]['opcode']=$value[2];
				$chageCond[$i]['value']=$value[3];
				$chageCond[$i]['num']=$i;
				$select=$this->_getSelected('cond',$value[2],$i);
				$chageCond[$i]['html']="<div id='cond_{$i}' class='addline'><input type='hidden' value='{$chageCond[$i]['key']}' name='GetObj[{$i}]' /><input type='hidden' value='{$chageCond[$i]['name']}' name='GetObjName[{$i}]' />对象属性名称：<input type='text' class='text' value='{$chageCond[$i]['name']}' name='GetObj_name_{$i}' num='{$i}' onclick='showObjMenu($(this))' /> {$select} <input type='text' class='text' name='GetValue[{$i}]' value='{$value[3]}' /> <input class='btn-blue' type='button' value='删除本行' onclick=\"$('#cond_{$i}').remove();\" /></div>";
				$i++;
			}
			$this->_editResult['chageCond']=$chageCond;
		}
	
		if (count($dataResult['Prize']['Effects'])){
			$chageEffect=array();
			$i=1;
			foreach ($dataResult['Prize']['Effects'] as $value){
				$chageEffect[$i]['key']="{$value[0]}.{$value[1]}";
				$chageEffect[$i]['name']=$this->_objData[$value[0]][$value[1]]['Name'];
				$chageEffect[$i]['opcode']=$value[2];
				$chageEffect[$i]['value']=$value[3];
				$chageEffect[$i]['num']=$i;
				$select=$this->_getSelected('effect',$value[2],$i);
				$chageEffect[$i]['html']="<div id='effect_{$i}' class='addline'><input type='hidden' value='{$chageEffect[$i]['key']}' name='EffObj[{$i}]' /><input type='hidden' value='{$chageEffect[$i]['name']}' name='EffObjName[{$i}]' />对象属性名称：<input type='text' class='text' value='{$chageEffect[$i]['name']}' name='EffObj_name_{$i}' num='{$i}' onclick='showEffMenu($(this))' /> {$select} <input type='text' class='text' name='EffValue[{$i}]' value='{$value[3]}' /> <input type='button' class='btn-blue' value='删除本行' onclick=\"$('#effect_{$i}').remove();\" /></div>";
				$i++;
			}
			$this->_editResult['chageEffect']=$chageEffect;
		}
	
		if (count($dataResult['Prize']['Tools'])){
			$chageTool=array();
			$i=1;
			foreach ($dataResult['Prize']['Tools'] as $value){
				$chageTool[$i]['key']=$value[0];
				$chageTool[$i]['name']=$this->_toolData[$value[0]]['toolsname'];
				$chageTool[$i]['img']=$this->_toolData['ToolData'][$value[0]]['toolsimg'];
				$chageTool[$i]['value']=$value[1];
				$chageTool[$i]['num']=$i;
				$chageTool[$i]['html']="<div id='tool_{$i}' class='addline'><input type='hidden' value='{$value[0]}' name='Tool[{$i}]' /><input type='hidden' value='{$value[2]}' name='ToolName[{$i}]' /><input type='hidden' value='{$value[3]}' name='ToolImg[{$i}]' /><input type='hidden' value='{$value[4]}' name='ToolIdEName[{$i}]' />对象属性名称：<input value='{$chageTool[$i]['name']}' type='text' class='text' name='Tool_name_{$i}' num='{$i}' onclick='showToolMenu($(this))' /> <input type='text' class='text' name='ToolNum[{$i}]' value='{$value[1]}' /> <input type='button' class='btn-blue' value='删除本行' onclick=\"$('#tool_{$i}').remove();\" /></div>";
				$i++;
			}
			$this->_editResult['chageTool']=$chageTool;
		}
		$num=array();
		$num['cond']=count($dataResult['GetCond'])?count($dataResult['GetCond'])+1:1;
		$num['effect']=count($dataResult['Prize']['Effects'])?count($dataResult['Prize']['Effects'])+1:1;
		$num['tool']=count($dataResult['Prize']['Tools'])?count($dataResult['Prize']['Tools'])+1:1;
		$this->_editNum=$num;
	}
	
	/**
	 * 返回select
	 * @param string $type 类型 cond/effect
	 * @param int $id 选中的ID
	 * @param int $num 第几个
	 */
	private function _getSelected($type,$id,$num){
		if ($type=='cond'){//cond
			$arr=array('1'=>'大于','2'=>'小于','3'=>'等于');
			$string="<select name='GetOpcode[{$num}]'>";
			foreach ($arr as $key=>$value){
				if ($key==$id){
					$string.="<option value='{$key}' selected='selected'>{$value}</option>";
				}else {
					$string.="<option value='{$key}'>{$value}</option>";
				}
			}
			$string.='</select>';
		}else {//effect
			$arr=array('1'=>'增加','2'=>'减少','3'=>'改为','4'=>'增加倍数');
			$string="<select name='EffOpcode[{$num}]'>";
			foreach ($arr as $key=>$value){
				if ($key==$id){
					$string.="<option value='{$key}' selected='selected'>{$value}</option>";
				}else {
					$string.="<option value='{$key}'>{$value}</option>";
				}
			}
			$string.='</select>';
		}
		return $string;
	}	
	
	/**
	 * 返回活动html
	 */
	public static function getActivityHtml($acceptCond,$affectedProperties,$affectedTools){
		$retArr=array();
		if (count($acceptCond)) {
			foreach ($acceptCond as $key=>$list){
				$curSelect=self::_getActivitySelected('AccpetCond', $list['Opcode'], $key);
				$retArr['acceptCond'][$key]='<div class="addline" id="cond_'.$key.'">
				<input type="hidden" value="'.$list['PropName'].'" name="Activity[AcceptCond]['.$key.'][PropName]" />
				对象属性名称：<input type="text" value="'.$list['PropDesc'].'" onclick="showObjMenu($(this))" num="'.$key.'" name="Activity[AcceptCond]['.$key.'][PropDesc]" class="text" />
				 '.$curSelect.' 
				 <input type="text" value="'.$list['Num'].'" name="Activity[AcceptCound]['.$key.'][Num]" class="text" /> 
				 <input type="button" onclick="$(\'#cond_\'+'.$key.').remove();" value="删除本行" class="btn-blue" />
				 </div>';
			}
		}
		if (count($affectedProperties)) {
			foreach ($affectedProperties as $key=>$list){
				$curSelect=self::_getActivitySelected('AffectedProperties',$list['Opcode'],$key);
				$retArr['affectedProperties'][$key]='
				<div class="addline" id="effect_'.$key.'">
				<input type="hidden" value="'.$list['PropName'].'" name="Activity[AffectedProperties]['.$key.'][PropName]" />
				对象属性名称：<input type="text" value="'.$list['PropDesc'].'" onclick="showEffMenu($(this))" name="Activity[AffectedProperties]['.$key.'][PropDesc]" class="text" num="'.$key.'" /> 
				 '.$curSelect.' 
				<input type="text" value="'.$list['Num'].'" name="Activity[AffectedProperties]['.$key.'][Num]" class="text"> 
				获得几率 <input type="text" name="Activity[AffectedProperties]['.$key.'][Rate]" value="'.$list['Rate'].'" class="text"> 
				获得后忽略其它属性 <input type="checkbox" name="Activity[AffectedProperties]['.$key.'][Unique]" value="1"> 
				有效期 <input type="text" name="Activity[AffectedProperties]['.$key.'][Expire]" value="'.$list['Expire'].'" class="text"> 
				<input type="button" onclick="$(\'#effect_\'+'.$key.').remove();" value="删除本行" class="btn-blue">
				</div>';
			}
		}
		if (count($affectedTools)) {
			foreach ($affectedTools as $key=>$list){
				$retArr['affectedTools'][$key]='
				<div class="addline" id="tool_'.$key.'">
				<input type="hidden" value="'.$list['toolsid'].'" name="Activity[AffectedTools]['.$key.'][toolsid]">
				对象属性名称：<input type="text" onclick="showToolMenu($(this))" value="'.$list['toolsname'].'" name="Activity[AffectedTools]['.$key.'][toolsname]" num="'.$key.'" class="text"> 
				<input type="text" value="'.$list['Num'].'" name="Activity[AffectedTools]['.$key.'][Num]" class="text"> 
				获得几率 <input type="text" name="Activity[AffectedTools]['.$key.'][Rate]" value="'.$list['Rate'].'" class="text"> 
				获得后忽略其它属性 <input type="checkbox" name="Activity[AffectedTools]['.$key.'][Unique]" value="1"> 
				<input type="button" onclick="$(\'#tool_\'+'.$key.').remove();" value="删除本行" class="btn-blue"></div>';
			}
		}
		
		return $retArr;

	}
	
	private static function _getActivitySelected($type,$id,$num){
		if ($type=='AccpetCond'){//AccpetCond
			$arr=array('1'=>'大于','2'=>'小于','3'=>'等于');
			$string="<select name='Activity[AcceptCond][{$num}][Opcode]'>";
			foreach ($arr as $key=>$value){
				if ($key==$id){
					$string.="<option value='{$key}' selected='selected'>{$value}</option>";
				}else {
					$string.="<option value='{$key}'>{$value}</option>";
				}
			}
			$string.='</select>';
		}else {//effect
			$arr=array('1'=>'增加','2'=>'减少','3'=>'改为');
			$string="<select name='Activity[AffectedProperties][{$num}][Opcode]'>";
			foreach ($arr as $key=>$value){
				if ($key==$id){
					$string.="<option value='{$key}' selected='selected'>{$value}</option>";
				}else {
					$string.="<option value='{$key}'>{$value}</option>";
				}
			}
			$string.='</select>';
		}
		return $string;
	}
	
	
	/**
	 * @return the $_objData
	 */
	public function get_objData() {
		return $this->_objData;
	}

	/**
	 * @return the $_toolData
	 */
	public function get_toolData() {
		return $this->_toolData;
	}

	/**
	 * @return the $_effData
	 */
	public function get_effData() {
		return $this->_effData;
	}

	/**
	 * @param $_objData the $_objData to set
	 */
	public function set_objData($_objData) {
		$this->_objData = $_objData;
	}

	/**
	 * @param $_toolData the $_toolData to set
	 */
	public function set_toolData($_toolData) {
		$this->_toolData = $_toolData;
	}

	/**
	 * @param $_effData the $_effData to set
	 */
	public function set_effData($_effData) {
		$this->_effData = $_effData;
	}

	
	
	
}
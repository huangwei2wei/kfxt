<?php
class Control_Z extends Control {

	public function actionTaaa(){
		$this->_createView();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$Form = $this->_getGlobalData('Model_Form','Object');
		$Form->setTable(array('width'=>'100%'));
		$formvalue	=	array(
			array(
				'name'	=>	"单选按钮",
				'type'	=>	"input",
				'value'	=>	array(
					'testradio'	=>	array(
						'_title'	=>	"按钮一",
						'id'		=>	'testradio',
						'name'		=>	'testradio',
						'type'		=>	'radio',
						'checked'	=>	'checked',
						'onlick'	=>	'form()',
						'value'		=>	'1',
					),
					'testradio2'	=>	array(
						'id'		=>	'testradio',
						'name'		=>	'testradio',
						'type'		=>	'radio',
						'onlick'	=>	'form()',
						'value'		=>	'0',
					),
				),
			),
			array(
				'name'	=>	"文本",
				'type'	=>	"input",
				'value'	=>	array(
						'_title'	=>	"按钮一",
						'id'		=>	'testtext',
						'name'		=>	'testtext',
						'type'		=>	'text',
						'value'		=>	'初始内容',
						'class'		=>	'text',
				),
			),
			array(
				'name'	=>	"文本",
				'type'	=>	"select",
				'value'	=>	array(
						'_title'	=>	"按钮一",
						'id'		=>	'testtext',
						'name'		=>	'testtext',
						'option'	=>	array(
							array(
								'value'		=>	'1',
								'content'	=>	'简单内容1',
							),
							array(
								'value'		=>	'2',
								'content'	=>	'简单内容2',
							),
							array(
								'value'		=>	'3',
								'content'	=>	'简单内容3',
							),
							array(
								'value'		=>	'4',
								'content'	=>	'简单内容4',
							),
						),
				),
			),
			array(
				'name'	=>	"文本",
				'type'	=>	"textarea",
				'value'	=>	array(
						'rows'		=>	'8',
						'cols'		=>	'60',
						'_title'	=>	"按钮一",
						'id'		=>	'textarea1',
						'name'		=>	'textarea1',
						'value'		=>	'初始内容',
				),
			),
			 
			array(
				'name'	=>	"",
				'type'	=>	"input",
				'value'	=>	array(
						'name'		=>	'testtext',
						'type'		=>	'submit',
						'value'		=>	'提交',
						'class'		=>	'btn-blue',
				),
			),
			
			array(
				'name'	=>	"文字",
				'type'	=>	"font",
				'value'	=>	array(
						'value'		=>	'下周维护前和维护前一天结束的活动，合作方那边都延长一天。',
				),
			),
		);
		$Form->setValue($formvalue);
		$this->_view->assign('form',$Form->getForm());
		$this->_view->set_tpl(array('body'=>'form/form.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	
	public function actionIndex(){
		$this->_createView();
		$outputTypes = array(
			0=>'原输出',
			1=>'json_decode -> var_export',
		);
		$this->_view->assign('outputTypes',$outputTypes);
		if($this->_isPost()){
			$sendUrl = trim($_POST['sendUrl']);
			if(empty($sendUrl)){
				$this->_view->assign('error','sendUrl NULL');
			}else{
				$tmp = array('getData'=>$_POST['getData'],'postData'=>$_POST['postData']);
				$sendData = array();
				foreach($tmp as $field => $sub){
					$sub = split("[\n&]",$sub);
					$sub = array_map('trim',$sub);
					$sub = array_filter($sub);
					foreach($sub as $val){
						list($k,$v) = explode('=',$val,2);
						$sendData[$field][trim($k)] = trim($v);
					}
				}
				$http = $this->_getGlobalData('Util_HttpInterface','object');
				$data = $http->result($sendUrl,null,$sendData['getData'],$sendData['postData']);
				$_POST['outputType'] = intval($_POST['outputType']);
				switch ($_POST['outputType']){
					case 1:
						$data = var_export(json_decode($data,true),true);
						break;
				}
				$parseGet = parse_url($sendUrl);
				$urlJoin = "{$parseGet['scheme']}://{$parseGet['host']}{$parseGet['path']}?";
				$parseGet = explode('&',$parseGet['query']);
				$parseGet = array_filter($parseGet);
				$queryGet = array();
				foreach($parseGet as $val){
					list($k,$v) = explode('=',$val,2);
					$queryGet[trim($k)] = urldecode(trim($v));
				}
				$getData = array_merge($queryGet,(array)$sendData['getData']);
				if($getData){
					$urlJoin .= http_build_query($getData);
				}
				foreach($getData as $k=>&$v){
					$v = "{$k}={$v}";
				}
				$postData = (array)$sendData['postData'];
				if($_POST['postDataAddToUrl'] && $postData){
					$urlJoin.='&'.http_build_query($postData);
				}
				foreach($postData as $k=>&$v){
					$v = "{$k}={$v}";
				}
				if($_POST['splite']){
					$splite = "&";
				}else{
					$splite = "\n";
				}
				$this->_view->assign('urlJoin',$urlJoin);
				$this->_view->assign('getData',implode($splite,$getData));
				$this->_view->assign('postData',implode($splite,$postData));
				
				$this->_view->assign('data',$data);
			}
		}
		$this->_view->assign('current_time',CURRENT_TIME);
		$this->_view->display();
	}
	
	public function actionA(){
		$this->_createView();
		$this->_view->set_tpl_dir('ActionGame');
		$this->_view->display();
	}
	
	public function actionTest(){
		$key = 'FIDU!)(#U!E_!DS!#ECS@';
		$sendData['timestamp'] = CURRENT_TIME;
		$sendData['sign'] = md5(CURRENT_TIME.$key);
		$sendData['name'] ='a';
		$sendData['url'] ='b';
		$sendData['mark'] ='1321';
		$sendUrl ='http://192.168.12.165:8080/kungfucross/server?action=addServerClass';
		$utilHttpMInterface = $this->_getGlobalData('Util_HttpMInterface','object');
		$utilHttpMInterface ->curlInit();
		$utilHttpMInterface->addHttp($sendUrl,$UrlAppend=NULL,$get=NULL,$sendData,$serverId=1);
		$data = $utilHttpMInterface->getResult();
		print_r($data);
	}
	
	public function actionTT(){
		$_utilHttpDown = $this->_getGlobalData('Util_Httpdown','object');
		
		$data=array(
			'a'=>1,'b'=>1,'c'=>1,
		);
		foreach ($data as $k=>$v){
			$_utilHttpDown->AddForm($k,$v);
		}
		
		$file_data = 'D:\web\kfCRM\www\Upload\client\20111011\113701_33856.png';

		if($file_data!=''){
			$_utilHttpDown->AddFileContent('pictrue',basename($file_data),file_get_contents($file_data));	
		}
		$sendUrl='http://192.168.12.75/333.php';
		$_utilHttpDown->OpenUrl($sendUrl);
		if($_utilHttpDown->IsGetOK()){
			$dataResult=$_utilHttpDown->GetRaw();
			echo $dataResult;
		}
	}
	
	public function actionT(){
		
		$str = 'C:15:"Object_UserInfo":2405:{a:19:{s:2:"Id";s:3:"445";s:9:"user_name";s:13:"huanglongfeng";s:13:"department_id";s:1:"1";s:10:"service_id";s:3:"101";s:5:"roles";a:2:{i:0;s:4:"cs_b";i:1;s:5:"cs_xx";}s:9:"nick_name";s:9:"黄龙锋";s:12:"date_created";i:1315362250;s:12:"date_updated";i:1315362250;s:6:"roomId";i:22;s:12:"operator_ids";a:3:{i:0;a:3:{s:12:"game_type_id";s:1:"7";s:11:"operator_id";s:1:"9";s:14:"priority_level";s:1:"1";}i:1;a:3:{s:12:"game_type_id";s:1:"5";s:11:"operator_id";s:1:"9";s:14:"priority_level";s:1:"1";}i:2;a:3:{s:12:"game_type_id";s:1:"7";s:11:"operator_id";s:2:"83";s:14:"priority_level";s:1:"1";}}s:8:"orderNum";a:43:{i:20110908;i:3;s:5:"total";i:7902;i:20110909;i:57;i:20110912;i:111;i:20110913;i:56;i:20110916;i:103;i:20110917;i:277;i:20110920;i:148;i:20110921;i:126;i:20110924;i:253;i:20110925;i:290;i:20110928;i:138;i:20110929;i:146;i:20111002;i:74;i:20111003;i:149;i:20111004;i:102;i:20111006;i:60;i:20111007;i:122;i:20111008;i:80;i:20111010;i:84;i:20111011;i:198;i:20111012;i:121;i:20111014;i:113;i:20111015;i:242;i:20111016;i:172;i:20111018;i:142;i:20111019;i:374;i:20111020;i:216;i:20111022;i:313;i:20111023;i:489;i:20111024;i:161;i:20111026;i:456;i:20111027;i:786;i:20111028;i:196;i:20111030;i:31;i:20111031;i:427;i:20111101;i:157;i:20111103;i:193;i:20111104;i:272;i:20111105;i:102;i:20111107;i:90;i:20111108;i:255;i:20111109;i:17;}s:5:"orgId";s:1:"3";s:9:"full_name";s:37:"黄龙锋(寻侠双龙组)[客服部]";s:9:"reply_num";a:43:{i:20110908;i:14;s:5:"total";i:6489;i:20110909;i:62;i:20110912;i:110;i:20110913;i:62;i:20110916;i:108;i:20110917;i:235;i:20110920;i:156;i:20110921;i:141;i:20110924;i:249;i:20110925;i:299;i:20110928;i:138;i:20110929;i:148;i:20111002;i:79;i:20111003;i:145;i:20111004;i:103;i:20111006;i:52;i:20111007;i:135;i:20111008;i:83;i:20111010;i:78;i:20111011;i:206;i:20111012;i:130;i:20111014;i:111;i:20111015;i:249;i:20111016;i:183;i:20111018;i:81;i:20111019;i:367;i:20111020;i:287;i:20111022;i:81;i:20111023;i:352;i:20111024;i:281;i:20111026;i:64;i:20111027;i:342;i:20111028;i:275;i:20111030;i:23;i:20111031;i:118;i:20111101;i:214;i:20111103;i:61;i:20111104;i:206;i:20111105;i:134;i:20111107;i:51;i:20111108;i:255;i:20111109;i:21;}s:9:"vip_level";a:7:{i:0;s:1:"0";i:1;s:1:"1";i:2;s:1:"2";i:3;s:1:"3";i:4;s:1:"4";i:5;s:1:"5";i:6;s:1:"6";}s:18:"incompleteOrderNum";i:30;s:6:"status";s:1:"1";s:3:"act";a:0:{}s:7:"program";a:2:{s:11:"position_id";N;s:10:"project_id";s:1:"0";}}}'; 
	}



}
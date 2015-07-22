<?php
// 本类由系统自动生成，仅供测试用途
class TestAction extends BaseAction {
	public function index() {
		setcookie('KL_SSO','kunlun',time()+3600,'/','.b.com',0);
		setcookie('KL_PERSON','frg',time()+3600,'/','.b.com',0);
		echo '11';

		$tt = $_COOKIE['KL_SSO'];
		echo $tt;

		redirect('http://www.b.com/php/interface.php?m=User&c=Login&a=OperatorLogin&__hj_dt=_DP_API');

		exit();
	}

	public function loginInfo(){
		$klsso = $_POST['klsso'];
		$klperson = $_POST['klperson'];




		$returnData = array();

		if(true || $klsso == 'kunlun' && $klperson == 'frg'){

			$returnData['retcode'] = 0;
			$returnData['retmsg'] = '';


			$extData['uid'] = 'youxingyuan';
			$extData['uname'] = 'xingyuan';
			$extData['indulge'] = 3;
			$extData['ext'] = array(
				'domainid'=>1,
			);


			$returnData['data'] = $extData;


		}else{
			$returnData['retcode'] = 202;
			$returnData['retmsg'] = '缺少klsso';
		}

		echo json_encode($returnData);
	}

}
?>
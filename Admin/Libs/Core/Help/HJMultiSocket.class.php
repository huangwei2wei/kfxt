<?php
/**
 * 多路SOCKET类
 * author:HJ 8473803@QQ.com
 *
 */
define('READ_TIMEOUT',100000);

class HJMultiSocket{

	private $Sockets=array();

	//连接超时时间  秒
	private $ConnectTimeout=1;

	//流读写超时时间，微秒
	private $StreamTimeout=100000;

	//等待超时时间 秒
	private $WaitTimeout=25;
	
	private $DownSpeed=0;

	public function __construct($ConnectTimeout=1,$StreamTimeout=100000,$WaitTimeout=25){
		$this->ConnectTimeout=$ConnectTimeout;
		$this->StreamTimeout=$StreamTimeout;
		$this->WaitTimeout=$WaitTimeout;
	}

	public function AddUrl($Url,$Method='GET',$key=''){
		if($key==''){
			$this->Sockets[]=array('Url'=>$Url,'Method'=>'GET','Urls'=>parse_url($Url),'Data'=>'','Socket'=>false,'HttpStates'=>array(),'Succ'=>false);
		}else{
			$this->Sockets[$key]=array('Url'=>$Url,'Method'=>'GET','Urls'=>parse_url($Url),'Data'=>'','Socket'=>false,'HttpStates'=>array(),'Succ'=>false);
		}
	}


	public function AddUrls($Urls=array(),$Method='GET'){
		foreach ($Urls as $key=>$Url){
			$this->AddUrl($Url,$Method,$key);
		}
	}

	private function GetData(){
		$this->InitUrlSocket();
		$this->run();
	}

	private function InitUrlSocket(){
		$i=$CreateCount=$ErrCount=0;
		$errno=$errstr=0;
		foreach ($this->Sockets as $Key=>$UrlUnit){
			$Urls=$UrlUnit['Urls'];
			$Port=empty($Urls['port'])?'80':$Urls['port'];
			$s = stream_socket_client($Urls['host'].':'.$Port, $errno, $errstr,$this->ConnectTimeout,STREAM_CLIENT_ASYNC_CONNECT|STREAM_CLIENT_CONNECT);
			if ($s) {
				stream_set_timeout($s,0,$this->StreamTimeout);
				$this->Sockets[$Key]['Socket'] = $s;
				$this->Sockets[$Key]['Data'] = '';
				$this->Sockets[$Key]['Succ'] = false;
				$this->Sockets[$Key]['HttpStates'] =array();
				$this->Sockets[$Key]['State'] =0;
				$this->Sockets[$Key]['Location'] ='';
				$this->Sockets[$Key]['DataLen'] =0;
				$CreateCount++;
			}elseif($ErrCount==3){
				exit('cannot connection internet');
			}else{
				unset($this->Sockets[$Key]);
				$ErrCount++;
			}
		}
		return $CreateCount;
	}

	private function GetSockets(){
		$Sockets=array();
		foreach ($this->Sockets as $Key=>$Val){
			$Sockets[$Key]=$Val['Socket'];
		}
		return $Sockets;
	}
	private function run(){
		$RunTimes=microtime(true);
		$n=$GetBytes=0;
		$Sockets=$this->GetSockets();
		$WaitWriteSocketNum=$WaitReadSocketNum=count($Sockets);
		$ArrIds=array_keys($Sockets);
		$AKey=false;
		while (count($Sockets)>0&&$WaitReadSocketNum>0) {
			if((microtime(true)-$RunTimes)>=$this->WaitTimeout)break;
			if($WaitWriteSocketNum>0) {
				$Write = $Sockets;
				$n = stream_select($Read=null, $Write, $e=null, 1);
				if ($n > 0) {
					foreach ($Write as $w) {
						$id = array_search($w, $Sockets);

						if($this->Sockets[$id]['Method']!==''){
							$Urls=$this->Sockets[$id]['Urls'];
							$path=$Urls['path']==''?'/':$Urls['path'];
							$querystr=isset($Urls['query'])?'?'.$Urls['query']:'';
							$WriteOk=fwrite($w,$this->Sockets[$id]['Method'].' '.$path.$querystr." HTTP/1.0\r\nHost: ". $Urls['host']."\r\nUser-Agent:Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; Maxthon; .NET CLR 1.1.4322; InfoPath.2; .NET CLR 2.0.50727)\r\nConnection: ".($this->Sockets[$id]['Method']==='HEAD'?'Keep-Alive':'Close')."\r\n\r\n");
							if($WriteOk){
								$this->Sockets[$id]['Method']='';
								$WaitWriteSocketNum--;
							}
						}
						
					}
				}
			}

			$Read = $Sockets;

			$n = stream_select($Read, $Write=null, $e=null, 1);
			if($n>0){
				foreach ($Read as $r) {
					$id = array_search($r, $Sockets);
					$this->Sockets[$id]['Method']='';
					$AKey=array_search($id,$ArrIds);
					if($AKey!==false)unset($ArrIds[$AKey]);
					$t=microtime(true);
					$Data = fread($r, 10240);
					if (strlen($Data) === 0) {
						fclose($r);
						unset($Sockets[$id]);
						$this->Sockets[$id]['Succ']=true;
						$WaitReadSocketNum--;
						$this->OnFetchUrl($id);
					} else {
					
						if(empty($this->Sockets[$id]['HttpStates'])){
							$pos=strpos($Data,"\r\n\r\n");
							if($pos===false){
								$this->Sockets[$id]['Data'] .= $Data;
							}else{
								$HeadInfoStr=substr($Data,0,$pos);
								$HeadInfo=explode("\r\n",$HeadInfoStr);
								$Head=array();
								$State=0;
								if(count($HeadInfo)>0){
									$Arr=explode(" ",$HeadInfo[0]);
									$State=intval($Arr[1]);
									$Head['State']=$State;
									array_shift($HeadInfo);
								}

								foreach ($HeadInfo as $v){
									$posm=strpos($v,':');
									if($posm>0){
										$HeadKey=strtolower(trim(substr($v,0,$posm)));
										$HeadStrArr=explode(';',substr($v,$posm+1,strlen($v)-$posm));
										$Head[$HeadKey]=ltrim($HeadStrArr[0]);
										if(count($HeadStrArr)>1){
											$posm=strpos($HeadStrArr[1],'=');
											if($posm>0){
												$Head[strtolower(trim(substr($HeadStrArr[1],0,$posm)))]=substr($HeadStrArr[1],$posm+1,strlen($HeadStrArr[1])-$posm);
											}
										}

									}
								}

								$this->Sockets[$id]['State']=$State;
								if($State==200){
									$pos=$pos+4;
								}elseif($State>=300&&$State<400){
									if(!empty($Head['location'])&&substr($Head['location'],0,7)==='http://'){
										fclose($r);
										
										unset($Sockets[$id]);
										$this->Sockets[$id]['Location']=$Head['location'];
										$this->Sockets[$id]['Data']='';
										continue;
									}
								}else{
				
									fclose($r);
									$this->Sockets[$id]['Location']=$Head['location'];
									$this->Sockets[$id]['Data']='';
									continue;
								}

								$Head['last-modified']=isset($Head['last-modified'])?strtotime($Head['last-modified']):false;
								if($Head['last-modified']===false){
									$Head['last-modified']=isset($Head['date'])?strtotime($Head['date']):false;
								}
								$this->Sockets[$id]['Data'] .= substr($Data,$pos,strlen($Data)-$pos);
								$this->Sockets[$id]['HttpStates']=$Head;
							}
						}else{
							$this->Sockets[$id]['Data'] .= $Data;
							if(isset($this->Sockets[$id]['content-length'])&&$this->Sockets[$id]['content-length']=strlen($this->Sockets[$id]['Data'])){
								fclose($r);
								unset($Sockets[$id]);
								$this->Sockets[$id]['Succ']=true;
								$WaitReadSocketNum--;
								$this->OnFetchUrl($id);
							}
						}
						$this->Sockets[$id]['DataLen']+=strlen($Data);
						$GetBytes+=strlen($Data);
					}
				}
			}elseif(count($Sockets)==0){
				break;
			}else{
				$WaitWriteSocketNum=0;
				foreach ($ArrIds as $AKey){
					if(key_exists($AKey,$Sockets))$WaitWriteSocketNum++;
				}
			}
		}
		$this->DownSpeed=intval($GetBytes/(microtime(true)-$RunTimes));
	}
	
	private function OnFetchUrl($id){
		
	}
	
	public function GetHtmlData(){
		$this->GetData();
		foreach ($this->Sockets as $k=>&$v){
			unset($v['Socket'],$v['Method']);
		}
		return $this->Sockets;
	}
	
	
	public function GetDownSpeed(){
		return $this->DownSpeed;
	}
}


?>
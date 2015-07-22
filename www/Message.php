<?php
/**
 * 短信发送接口
 * Date: 2011/10/18
 */

class Message 
{
     const CMD_LOGIN = 0x00000001;
     const CMD_LOGIN_RSP = 0x80000001;
     const CMD_ADDMIN = 0x00000004;
     const CMD_ADDMIN_RSP = 0x80000004;
     const CMD_ADDTASK = 0x00000003;
     const CMD_ADDTASK_RSP = 0x80000003;

     const	MAX_FILESIZE	= 2097152; //1024*1024*2 2M

     protected $address_ip = "58.248.249.161";
     protected $address_port = 18889;
     protected $user = "yg10";
     protected $passwd = "password";
     protected $timeout = 60;

     protected $fs = null;
     protected $workDir = null;

     protected $error_no = null;
     protected $error_desc = null;

     protected $step = null;
     protected $mobiles = array();
     protected $mobiles_count = 0;
     protected $account_times = 0;

     protected $timezone_gmt = false;
     protected $timezone_offset = 8;

     protected $debug = false;

     public function __construct()
     {
          $this->log(PHP_EOL);
     }

     protected function connect()
     {
          $this->log("初始化连接");
          $this->fs = fsockopen($this->address_ip, $this->address_port, $error_no, $error_desc, $this->timeout);

          if (!$this->fs) {
               $this->set_error( $error_desc, $error_no);
          }

          $this->step = 0;
          return $this;
     }

     protected function is_connected()
     {
          return !$this->fs?false:true;
     }

     protected function login()
     {
          if ($this->step >= 1) {
               return true;
          }
          
//           $this->connect();

//           if ($this->is_connected() !== true) {
//                return false;
//           }

          //生成消息
          $body = pack('a20a16', $this->user, $this->passwd);
          $header = pack('N3a16', self::CMD_LOGIN, strlen($body), 0, date('YmdHis', time() + ($this->timezone_gmt?3600*$this->timezone_offset:0)));
          $msg = $header . $body;
echo date('Y m d  His', time() + ($this->timezone_gmt?3600*$this->timezone_offset:0));
var_dump($header);
exit;

          //发送请求
          $this->log("发起登录请求");
          fwrite($this->fs, $msg, strlen($msg));

          $rsp = '';
          if (!feof($this->fs)) {
               $rsp = fgets($this->fs, strlen($header) + strlen(self::CMD_LOGIN_RSP));
          }

          //取出响应信息
          if ($rsp != '') {
               $this->log($rsp);
               if ($this->debug === true)
                    file_put_contents($this->getFilePath('rsp_1.txt'), $rsp);
               $decode_rsp = unpack('Ncode/Nlen/Nstatus/a16date/Ncount', $rsp);
               if (isset($decode_rsp['code']) && sprintf("0x%x", $decode_rsp['code']) == self::CMD_LOGIN_RSP && isset($decode_rsp['status'])) {
                    if ($decode_rsp['status'] == 1) {
                         $this->step = 1;
                         $this->log("登录成功");
                         $this->log("剩余{$decode_rsp['count']}条");
                         $this->account_times = $decode_rsp['count'];
                         //判断账号余额是否够用
                         if (count($this->mobiles) > $this->account_times) {
                              $this->set_error("余额不足，取消发送请求");
                              $this->step = 0;
                              $this->close_connect();
                              return false;
                         }
                         return true;
                    } else if($decode_rsp['status'] == 0) {
                         $this->set_error("用户锁定，需联系管理员");
                    } else if($decode_rsp['status'] == 2) {
                         $this->set_error("新用户，需联系管理员");
                    } else if($decode_rsp['status'] == 999) {
                         $this->set_error("用户名或密码错误");
                    }
               }
          } else {
               $this->set_error("未得到返回信息");
          }
          
          $this->close_connect();
          return false;
     }

     protected function add_min($mobiles=array())
     {
          if ($this->step >= 2) {
               return true;
          }

          if (empty($mobiles)) {
               $mobiles = array($mobiles);
               $this->log("无号码");
               return false;
          }

          if (!is_array($mobiles)) {
               $mobiles = array($mobiles);
          }

          //供登录时判断发送条数是否够用
          $this->mobiles = $mobiles;

          if ($this->login() !== true) {
               return false;
          }

          $body = "";
          $this->log($mobiles);
          $this->mobiles = array();
          foreach ($mobiles as $mobile) {
               $mobile = trim($mobile);
               $len = strlen($mobile);
               if ($len != 11) {
                    continue;
               }

               $this->mobiles[] = $mobile;

               //添加到消息体
               $mobile = 'F' . (string)$mobile;
               for ($i=0;$i<($len+1)/2;$i++) {
                    //$body .= pack('H2', hexdec(substr($mobile, $i*2, 2)));
                    $body .= chr(hexdec(substr($mobile, $i*2, 2)));
                    if ($this->debug === true)
                         $this->log("过程:" . substr($mobile, $i*2, 2));
               }
          }

          $header = pack('N3a16', self::CMD_ADDMIN, strlen($body), 0, date('YmdHis', time() + ($this->timezone_gmt?3600*$this->timezone_offset:0)));
          
          $this->log("长度:" . strlen($body));
          $msg = $header . $body;
          if (strlen($msg) >= 1024) {
               $this->set_error("超出最大允许条数");
               $this->close_connect();
               return false;
          }

          //发送请求
          $this->log("发起添加号码请求");
          fwrite($this->fs, $msg, strlen($msg));
          
          $rsp = '';
          if (!feof($this->fs)) {
               $rsp = fgets($this->fs, strlen($header));
          }

          //取出响应信息
          if ($rsp !== '') {
               $this->log($rsp);
               if ($this->debug === true)
                    file_put_contents($this->getFilePath('rsp_2.txt'), $rsp);
               $decode_rsp = unpack('Ncode/Nlen/Nstatus', substr($rsp, 0, 12));
               if (isset($decode_rsp['code']) && sprintf("0x%x", $decode_rsp['code']) == self::CMD_ADDMIN_RSP && isset($decode_rsp['status'])) {
                    $this->step = 2;
                    $this->log("成功添加了{$decode_rsp['status']}个号码");
                    $this->mobiles_count = $decode_rsp['status'];
                    if ($decode_rsp['status'] > 0) {
                         return $decode_rsp['status'];
                    }
               }
          } else {
               $this->set_error("未得到返回信息");
          }
          
          $this->close_connect();
          return false;
     }

     
     protected function add_task($mobiles ,$msg, $time=null, $note=null)
     {
          if ($this->step >= 3) {
               return true;
          }

          if (!$this->add_min($mobiles)) {
               return false;
          }

          $msg = trim($msg);
          if (empty($msg)) {
               $this->set_error("信息不能为空");
               return false;
          }

          if ($time === NULL) {
               $time = date('Y-m-d H:i:s', time() + ($this->timezone_gmt?3600*$this->timezone_offset:0));
          }

          if ($note !== NULL) {
               $note = trim($note);
          }

          $body = pack("a160a20a255", $msg, $time, $note);
          $this->log(array($msg, $time, $note));
          $header = pack('N3a16', self::CMD_ADDTASK, strlen($body), 0, date('YmdHis', time() + ($this->timezone_gmt?3600*$this->timezone_offset:0)));
          $msg_send = $header . $body;

          //发送请求
          $this->log("发起添加任务请求");
          fwrite($this->fs, $msg_send, strlen($msg_send));
          
          $rsp = '';
          if (!feof($this->fs)) {
               $rsp = fgets($this->fs, strlen($header));
          }

          //取出响应信息
          if ($rsp != '') {
               $this->log($rsp);
               if ($this->debug === true)
                    file_put_contents($this->getFilePath('rsp_3.txt'), $rsp);
               $decode_rsp = unpack('Ncode/Nlen/Nstatus', substr($rsp, 0, 12));
               if (/*isset($decode_rsp['code']) && sprintf("0x%x", $decode_rsp['code']) == self::CMD_ADDTASK_RSP && */isset($decode_rsp['status'])) {
                    $this->step = 3;
                    if ($decode_rsp['status'] > 0) {
                         $this->log("成功发送，任务ID#{$decode_rsp['status']}");
                         //return $decode_rsp['status'];
                         //记录最后余额
                         $fp = fopen($this->getFilePath('sms_account'), 'w');
                         if ($fp) {
                              fputs($fp, $this->account_times-$this->mobiles_count);
                              fclose($fp);
                         }
                         
                         return array(
                              'mobiles'=>$this->mobiles,
                              'mobiles_count' => $this->mobiles_count, 
                              'task_id' => $decode_rsp['status'], 
                              'message' => $msg, 
                              'note' => $note,
                              'send_time' => $time, 
                              'account' => $this->account_times-$this->mobiles_count
                              );
                    } else if($decode_rsp['status'] == -99) {
                         $this->set_error("提交失败");
                    } else if($decode_rsp['status'] <= 0) {
                         $this->set_error("余额不足");
                    }
               }
          } else {
               $this->set_error("未得到返回信息");
          }

          $this->close_connect();
          return false;
     }

     public function send($mobiles ,$msg, $time=null, $note=null)
     {
          return $this->add_task($mobiles ,$msg, $time, $note);
     }

     public function close_connect($fs = null)
     {
          if ($fs == null) {
               $fs = $this->fs;
          }
          try {
               if ($this->fs)
                    fclose($fs);
          } catch(Exception $e) {
               $this->set_error($e->getMessage());
          }
     }

     public function reset()
     {
          $this->step = 0;
          $this->close_connect();

          return $this;
     }

     protected function set_error($error_desc, $error_no=null)
     {
          $this->error_desc = $error_desc;
          $this->_error_no = $error_no;
          $this->log($this->error_desc);

          return $this;
     }

     public function get_error()
     {
          return array(
               'error_no' => $this->error_no, 
               'error_desc' => $this->error_desc
               );
     }

     public function set_debug($status)
     {
          $this->debug = $status;

          return $this;
     }

     protected function getWorkDir()
     {
          if ($this->workDir === NULL) {
               $this->workDir = realpath(dirname(__FILE__)) . '/../var/';
          }

          return $this->workDir;
     }

     protected function getFilePath($filename)
     {
          return $this->getWorkDir() . $filename;
     }

     protected function log($data)
     {
          if (is_array($data)) {
               $data = json_encode($data);
          }

          $filename = $this->getFilePath('message.log');
          $fp = fopen($filename, "a");
          if (!$fp) {
               return false;
          }

          //判断一下文件大小
          $fstat = fstat($fp);
          if ( isset($fstat['size']) && $fstat['size'] >= self::MAX_FILESIZE ) {
               fclose($fp);
               if ( $this->_rename($filename) ) {
					$fp = fopen($filename, "w");
               } else {
					$fp = fopen($filename, "a");
               }
          }

          if ($this->debug === true) {
               print $data . PHP_EOL;
          }
          if (!empty($data) && $data != PHP_EOL)
               fputs($fp, date("Y-m-d H:i:s", time() + ($this->timezone_gmt?3600*$this->timezone_offset:0)). ': ' . $data);
          fputs($fp, PHP_EOL);
          fclose($fp);

          return $this;
     }

     protected function _rename($filename)
     {
          if ( empty($filename) ) {
               return false;
          }
		
          $searchExpr =  $filename . ".{[0123456789]*}";
          $fileList = glob($searchExpr, GLOB_BRACE);
		
          if (!empty($fileList) ) {
               sort($fileList);
               $lastFilename = array_pop($fileList);
               $lastFilenameNum = str_replace("{$filename}.", "", $lastFilename);
               if ( $lastFilenameNum !== NULL && $lastFilenameNum !== "" ) {
                    $lastFilenameNum += 1;
               }
          } else {
               $lastFilenameNum = 0;
          }
		
          return rename($filename, $filename . "." . (string)($lastFilenameNum));
     }
}
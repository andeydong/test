<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('IN_ANWSION'))
{
	die;
}
class link_class extends AWS_MODEL
{
	//插入数据，第四步调用的就是这个函数了，将数据插入到表中
	function apply($site_name,$site_url)
	{		
		$this->insert('links', 
		array(
			'site_name' => $site_name,
			'site_url' => $site_url,
			'time' => time()
		));
	}
        //检查该URL是否存在
        public function is_exist_url1($site_name,$site_url)
    {
        //$mysqlSelect='SELEC question_id FROM `aws_question` WHERE question_content='.$this->Email.' AND question_detail='.$this->Question;
       $sss= $this->query_all('SELECT id FROM ' . $this->get_table('links') . ' WHERE site_name = ' . '\'' . $site_name . '\'' . ' AND ' . 'site_url=' .'\''. $site_url .'\'');
       
       return $sss;
    }
        function is_exist_url($site_url)
    {       
        //根据site_url字段从表aws_links中获取id，如果存在将会返回一个id，如果不存在则返回空
        //aws_links的sql在文章末尾放出
        return $this->fetch_one('links', 'id', "site_url = '" . $site_url . "'");
    }
    //检查输入的邮箱是否正确
        function checkEmail($inAddress)   
    {   
        return (ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+",$inAddress));   
    }
        // 模拟提交数据函数 
    public function sendEmail($site_name,$site_url)
    {      
        $url = 'http://develop.bbwc.cn/jinxin/yaf_mail/mail/index.php/api/customSend/datatype/2';
        $to=$site_name;
        $subject='问题回复';
        $contents=$site_url;
        $from='270965897@qq.com';
        $fromname='测试';
        $data="to=$to&subject=$subject&contents=$contents&from=$from&fromname=$fromname";
        //$data = http_build_query($param);
        //$data=json_encode($param,JSON_UNESCAPED_UNICODE);
        
        $curl = curl_init(); // 启动一个CURL会话      
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址                        
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求      
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环            
        $tmpInfo = curl_exec($curl); // 执行操作      
        if (curl_errno($curl)) {      
           echo 'Errno'.curl_error($curl);      
        }      
        curl_close($curl); // 关键CURL会话 
        
        return $tmpInfo; // 返回数据 
        
    }
    //使用sendcloud发送邮件
    public function send_allmail($toemail,$emaildetail) 
    {
      
      $url = 'develop.bbwc.cn/jinxin/yaf_mail/api/customSend/datatype/2';
      $param = array(
          'to'=>$site_name,
          'subject'=>'问题回复',
          'html'=>$site_url,
          'from'=>'270965897@qq.com',
          'fromname'=>'测试'
          );
      $data = http_build_query($param);
      $options = array(
          'http' => array(
              'method'  => 'POST',
              'header' => 'Content-Type: application/x-www-form-urlencoded',
              'content' => $data
      ));

      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);
      return $result;
      //return json_encode($param,JSON_UNESCAPED_UNICODE);
    }
    //使用sendcloud发送邮件
    public function send_mail($toemail,$emaildetail) 
    {
      
      $url = 'http://sendcloud.sohu.com/webapi/mail.send.json';
      $API_USER = '270965897_test_TJBTi8';
      $API_KEY = 'VLSH82H6dDgfa65W';

      //不同于登录SendCloud站点的帐号，您需要登录后台创建发信子帐号，使用子帐号和密码才可以进行邮件的发送。
      $param = array(
          'api_user' => $API_USER,
          'api_key' => $API_KEY,
          'from' => 'service@sendcloud.im',
          'fromname' => 'SendCloud测试邮件',
          'to' => $toemail,
          'subject' => '问题回复',
          'html' => $emaildetail,
          'resp_email_id' => 'true');
      
      $data = http_build_query($param);

      $options = array(
          'http' => array(
              'method'  => 'POST',
              'header' => 'Content-Type: application/x-www-form-urlencoded',
              'content' => $data
      ));

      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);
      echo $result;
      return $result;
    }

}
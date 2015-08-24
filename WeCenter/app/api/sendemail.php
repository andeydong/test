<?php
    
      function send_mail($toemail,$biaoti,$emaildetail) {
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
          'subject' => $biaoti,
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

      return $result;
    }
  echo send_mail($toemail,$biaoti,$emaildetail);
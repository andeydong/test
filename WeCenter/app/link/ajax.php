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


class ajax extends AWS_CONTROLLER
{
         public function get_access_rule()
    {
	  $rule_action['rule_type'] = 'white';
	  if ($this->user_info['permission']['visit_site'])
	  {
		  $rule_action['actions'][] = 'apply'; //表示在非登录情况下可以访问，这个QuestionApi行为被允许操作
	  }
	  return $rule_action;
    }
    //当用户点击“提交申请”时的处理
        
        public function apply_action()
	{
            
                $site_name = $_POST['site_name'];
		$site_url = $_POST['site_url'];
		//此处就是控制器（C）指派模型（M）的过程，表示调用models\link.php中的is_exist_url()
		//用于判断该网站地址是否已经存在
		//对提交的参数进行简单的判断
                if ($this->model('link')->is_exist_url1($site_name,$site_url))
                {
                        H::ajax_json_output(AWS_APP::RSM(null, '-1', AWS_APP::lang()->_t('邮件已发送，请勿重复发送！')));
                }
		if (trim($site_name) == '')
		{
			H::ajax_json_output(AWS_APP::RSM(null, '-1', AWS_APP::lang()->_t('请输入客户邮箱！')));
		}
                if (!$this->model('link')->checkEmail($site_name))
		{
			H::ajax_json_output(AWS_APP::RSM(null, '-1', AWS_APP::lang()->_t('请输入正确的邮箱！')));
		}
		if (trim($site_url) == '')
		{
			H::ajax_json_output(AWS_APP::RSM(null, '-1', AWS_APP::lang()->_t('请输入邮件内容！')));
		}
		//表示调用models\link.php中的apply()，将申请数据插入数据库中
		if(!$this->model('link')->apply($site_name,$site_url))
                {
                    
                    $this->model('link')->sendEmail($site_name,$site_url);
                    
                    
                }
		H::ajax_json_output(AWS_APP::RSM(null, '-1', AWS_APP::lang()->_t('发送成功，请关闭对话框！')));
	}	
}
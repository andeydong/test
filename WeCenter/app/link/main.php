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


class main extends AWS_CONTROLLER
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
  
  public function apply_action() 
  {
	  //根据MVC构架，控制器指负责显示某个视图(V)，和使用哪个模型（M）
	  //此处只需输出view\default\link\ajax_apply.tpl.htm这个页面给用户，即用户点击申请后所看到的页面
	  TPL::output('link/ajax_apply');
  }	
}
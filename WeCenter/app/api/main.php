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
    public $UserId;
    public $DefaultId;
    public $Email;
    public $Question;
    public $AppId;
    public $Version;
    public $AddTime;
    public $outPut = array();
    public function __construct($process_setup = true) {
        parent::__construct($process_setup);
        $output=array();
        header('Content-Type: text/html; charset=UTF-8');
        //判断是否是指定的data变量   
        $data1=urldecode(file_get_contents('php://input'));
        if(empty($data1))
        {
            $mess=  array('status'=>-8,'msg'=>'请填写相关参数');
            return $this->echoJson($mess);
            
        }
        $data2=  substr($data1,0,6);
        if(!($data2==='data={'))
        {
            $mess=  array('status'=>-7,'msg'=>'提交的数据不是指定类型数据');
            return $this->echoJson($mess);
        }
        $data=  substr($data1,5);
        //转换json成数组
        $de_json = json_decode($data,TRUE);
        $this->UserId=$de_json['UserId'];
        $this->DefaultId = $de_json['DefaultId'];
        $this->Email = $de_json['Email'];
        $this->Question = $de_json['Question'];
        $this->AppId = $de_json['AppId'];
        $this->Version = $de_json['Version'];
        $this->AddTime = $de_json['AddTime'];

    }
     public function get_access_rule()
    {
	  $rule_action['rule_type'] = 'white';
	  if ($this->user_info['permission']['visit_site'])
	  {
		  $rule_action['actions'][] = 'QuestionApi'; //表示在非登录情况下可以访问，这个QuestionApi行为被允许操作
	  }
	  return $rule_action;
    }
    
     public function QuestionApi_action()
    {
         
         
        if($this->UserId==$this->DefaultId)
        {
            $output=array('status'=>-1,'msg'=>'用户登录Id和未登录默认Id都为空');
            return $this->echoJson($output);
        }                        
        
        else if(empty ($this->Email))
        {
            $output=array('status'=>-2,'msg'=>'用户邮箱为空');
            return $this->echoJson($output);
        }
        else if(empty ($this->Question))
        {
            $output=array('status'=>-3,'msg'=>'用户填写的问题内容为空');
            return $this->echoJson($output);
        }
        else if(empty ($this->AppId))
        {
            $output=array('status'=>-4,'msg'=>'用户AppId为空');
            return $this->echoJson($output);
        }
        else if(empty ($this->Version))
        {
            $output=array('status'=>-5,'msg'=>'用户版本号为空');
            return $this->echoJson($output);
        }
        else 
        {
            
            $ddd=$this->model('QuestionApi')->save_QuestionApi($this->UserId,$this->DefaultId,$this->Email,$this->Question,$this->AppId,$this->Version,$this->AddTime);
            
            
            if($ddd>=0)
            {
                
                $sss=$this->model('question')->save_question($this->Question, $this->Email, '12', $anonymous = 0, $ip_address = null, $from = null);
                
                if($sss>=0)
                {
                 $quesIds=$this->model('QuestionApi')->get_questionId($this->Email,  $this->Question);       
                 $this->model('question')->update_question($quesIds[0][question_id], $this->Email, $this->Question, '2', $verified = true, $modify_reason = null, $anonymous = null, $category_id = null);
                 $output=array('status'=>0,'msg'=>'提交成功');
                 return $this->echoJson($output);
                }
                else 
                {
                    $output=array('status'=>0,'msg'=>'提交成功,未显示在用户界面');
                    return $this->echoJson($output);
                    
                }
                
            
            }
            else
            {
                $output=array('status'=>-6,'msg'=>'其他错误'); 
                return $this->echoJson($output);
            }
            
        }

    
    }
    //返回json格式数据
    public function echoJson($data){
        return json_encode($data,JSON_UNESCAPED_UNICODE);
        
    }
    
      
}
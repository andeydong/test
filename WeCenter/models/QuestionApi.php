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

class QuestionApi_class extends AWS_MODEL
{
    public function save_QuestionApi($UserId,$DefaultId,$Email,$Question,$AppId,$Version,$AddTime)
	{
		
		$to_save_QuestionApi = array(
			'UserId' => ($UserId),
			'DefaultId' => ($DefaultId),
			'Email' => ($Email),
			'Question' => ($Question),
			'AppId' => ($AppId),
			'Version' => ($Version),
			'AddTime' => ($AddTime)
		);
		$QuestionApi_id = $this->insert('questionapi', $to_save_QuestionApi);
                
		if (!$QuestionApi_id)
		{
			return false;
                        
		}
                
		return $QuestionApi_id;
	}
    public function get_questionId($Email,$Question)
    {
        //$mysqlSelect='SELEC question_id FROM `aws_question` WHERE question_content='.$this->Email.' AND question_detail='.$this->Question;
       $sss= $this->query_all('SELECT question_id FROM ' . $this->get_table('question') . ' WHERE question_content = ' . '\'' . $Question . '\'' . ' AND ' . 'question_detail=' .'\''. $Email .'\'');
       
       return $sss;
    }
}
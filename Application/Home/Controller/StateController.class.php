<?php
namespace Home\Controller;
use Think\Controller;
import('ORG.Util.Page');
class StateController  extends BaseController{

	public function selectPitchState(){

		$kind=$this->checkNotEmptyAndGetParam('kind');
		$pagenow=$this->checkNotEmptyAndGetParam('pagenow');
		$pageeach=$this->checkNotEmptyAndGetParam('pageeach');
		$users=D('users');
		$departmentUserId=$this->departmentId;
		switch ($kind) {
			case '全部':
			    //$kind=0;
			    $count=$users->where(array('department_id'=>$departmentUserId))->count();
				# code...
				break;
			case '未审核':
				$count=$users->where(array('department_id'=>$departmentUserId,'completed'=>0))->count();# code...
				break;
			case '已审核':
				$count=$users->where(array('department_id'=>$departmentUserId,'completed'=>1))->count();# code...
				break;
			default:
				$this->AssignOwn('code',205);# code...
				break;
		}
		
		$Page=new\Think\Page($count,$pageeach);

		if($pageeach!=0){
			$pageall=ceil($count/$pageeach);

		}else{
			$this->AssignOwn('code',203);//每页的页数不能为0

		}
		switch ($kind) {
			case '全部':
				$userData=$users->where(array('department_id'=>$departmentUserId))->limit($pageeach)->page($pagenow)->select();# code...
				break;
			case '未审核':
				$userData=$users->where(array('department_id'=>$departmentUserId,'completed'=>0))->limit($pageeach)->page($pagenow)->select();# code...
				break;
			case '已审核':
				$userData=$users->where(array('department_id'=>$departmentUserId,'completed'=>1))->limit($pageeach)->page($pagenow)->select();# code...
				break;
			default:
				$this->AssignOwn('code',205);# 输入的kind不为全部已审核未审核之一
				break;
		}
		$userData=$users->where(array('department_id'=>$departmentUserId))->limit($pageeach)->page($pagenow)->select();
		foreach ($userData as $key => $value) {
			$scnumber=$value['student_number'];
			$name=$value['name'];
			$position=$value['group_id'];
			$pitch_user=D('pitch_user');
			$pitchUserData=$pitch_user->where(array('studentNumber'=>$scnumber))->select();
			$pitchUserId=$pitchUserData['userId'];
			$pitchnumber=$pitchUserData['pitchTimes'];
			$pitch_timetable=D('pitch_timetable');
			$pitchTimeData=$pitch_timetable->where(array('userid'=>$pitchUserId))->find();
			$pitchstatus=$pitchTimeData['state'];
			$record=array();//list中的一条记录
			$record['scnumber']=$scnumber;
			$record['name']=$name;
			$record['positon']=$position;
			$record['pitchnumber']=$pitchnumber;
			$record['pitchstatus']=$pitchstatus;
			//$list=array();
			$list[$key]=$record;


		}

		$this->setData('pageall',$pageall);
		$this->setData('pagenow',$pagenow);
		$this->setData('pageeach',$pageeach);
		$this->setData('list',$list);

		$this->code = 200;
		$this->finish();


  }

}
?>
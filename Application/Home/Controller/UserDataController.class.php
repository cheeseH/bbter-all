<?php
namespace Home\Controller;
use Think\Controller;
class UserDataController extends BaseController{


	//用户基本信息的获取函数
	private function getUser($id){
		$pitchTimes;
		$position;
		$department;
		$sex;
		$pitchTimes;
		$classstatus;
		
		$User = D('user');
		$userData = $User->find($id);
		$noClass = D('pitch_user');
		$noClassData = $noClass->where("userId = '$id'")->find();

		$this->setData('name',$userData['name']);					//name	
		$this->setData('photo','');
		
		$positionData = D('groups')->find($this->groupId);				//position
		$position = $positionData['name'];
		$this->setData('position',$position);
		
		$departmentData = D('departments')->find($this->departmentId);		//department
		$department = $departmentData['name'];
		$this->setData('department',$department);

		
		$sex = 'UNKNOWN';										//sex
		switch($userData['sex']){
			case 'MALE':
			$sex = '男';
			break;
			
			case 'FEMALE':
			$sex = '女';
			break;
			
			case 'UNKNOWN':
			$sex = '';
			break;
		}
		$this->setData('sex',$sex);
		
		$this->setData('tel',$userData['mobile']);						//长短号
		$this->setData('shorttel', $userData['short_mobile']);
		
		$classstatus = '审核中';
		$noClassData = D('pitch_timetable')->where(" userid = '$userid' ")->find();	
		if($noClassData['state']==0){
			$classStatus='已通过';
		}else{
			$classStatus='审核中';
		}
		
		$pitchTimes = $noClassData['pitchTimes'];
		
		$this->setData('classstatus',$classstatus);
		$this->setData('pitchnumber',$pitchTimes);		
		
		$this->code = 200;
		$this->finish();
	}	
	//没课表的获取函数
	private function getNoClass($userid){
		$classStatus;
		
		$noClassData = D('pitch_timetable')->where(" userid = $userid ")->find();	
		$transferInteger = (double)$noClassData['newtable'];
		if($noClassData['state']==0){
			$classStatus='审核中';
		}else{
			$classStatus='已通过';
		}
		//将整数转换成数组
		$transferClass=array();
		$transferClass = getTransferClass($transferInteger);
		$this->setData('noclass',$transferClass);
		$this->setData('classstatus',$classstatus);
		$this->code=200;
		$this->finish();

		
	}
	
	//查看他人没课表的函数
	private function getOtherPersonNoClass($userId){
		//查找基本信息
		$userId;
		$classstatus;	
		$data = array();
		
		$userData = D('users')->where("student_number = $userId")->find();
		if(empty($userData)){
			$this->dataNoFound($userId);
		}
		
		$this->setData('name',$userData['name']);
		$userId=$userData['id'];
		$positionData = D('groups')->find($this->groupId);
		$this->setData('position',$positionData['name']);
	
		
		$noClassData = D('pitch_timetable')->where(" userid = '$userid' ")->find();	
		if($noClassData['state']==0){
			$classStatus='已通过';
		}else{
			$classStatus='审核中';
		}
		
		$this->setData('classstatus',$classstatus);
		$noClassData = D('pitch_timetable')->where(" userid = '$userId' ")->find();
		$transferInteger = (double)$noClassData['table'];

		//将整数转换成数组
		$transferClass=array();
		$transferClass = getTransferClass($transferInteger);
		$this->setData('class',$transferClass);
		
		$this->code = 200;
		$this->finish();
	}
	
	//审核他人没课表的函数
	private function checkOtherPersonNoClass($scnumber){
		$otherPersonId;
		$studentDepartmentId;
		
		$otherPersonData = D('users')->where(" student_number =  $scnumber" )->find();
		if(empty($otherPersonData)){
			$this->dataNoFound($scnumber);
		}
		
		$otherPersonId = $otherPersonData['id'];
		$studentDepartmentId = $otherPersonData['department_id'];
		
		if($this->groupId<3||$this->departmentId != $studentDepartmentId||$this->userId == $otherPersonId){
			$this->forbidden();
		}
		
		$noClassData = D('pitch_timetable')->where(" userid = '$otherPersonId' ")->find();
		$data = array(	'state' => 0,
						'table' => $noClassData['newtable'],
						'newTable' => 1);
		D('pitch_timetable')->where(" userid=  '$otherPersonId' ")->save($data);
		$data1 = array( 'classstatus' => 'NORMAL');
		D('users')->where(" id = '$otherPersonId' ")->save($data1);
		
		$this->code = 200;
		$this->finish();
	}
	
	
	public function checkOtherPersonNoClassData(){
	
		$scNumber = $this->checkNotEmptyAndGetParam('scnumber');
		if($this->groupId)
		$this->checkOtherPersonNoClass($scNumber);
	}
		
	public function getOtherPersonNoClassData(){
		$scNumber = $this->checkNotEmptyAndGetParam('scnumber');
		
		$this->getOtherPersonNoClass($scNumber);
	}
	
	public function getNoClassData(){
		$id = $this->userId;
		$this->getNoClass($id);
	}
	
	public function getUserData(){
		
		$this->getUser($this->userId);
	}


}
?>

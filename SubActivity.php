<?php
namespace Home\Controller;
use Think\Controller;
class SubActivityController extends BaseController{
public function addactivity(){
	$sActivity = D('pitch_sub_activity');
	$data = array();
	$data['date'] = $this->checkNotEmptyAndGetParam('date');
	switch ($this->checkNotEmptyAndGetParam('place')) {
		case '一饭':
			$data['place'] = '1';
			break;
		case '二饭':
			$data['place'] = '2';
			break;
		case '':
			break;
		default:
			$data['place'] = '0';
			break;
		}
	switch ($this->checkNotEmptyAndGetParam('lesson')) {
		case '一二节':
			$data['lesson'] = '1';
			break;
		case '三四节':
			$data['lesson'] = '2';
			break;
		case '五六节':
			$data['lesson'] = '3';
			break;
		case '七八节':
			$data['lesson'] = '4';
		default:
			$data['lesson'] = '0';
			break;
		}
	$data['header'] = $this->checkNotEmptyAndGetParam('header');
	$data['needNumber'] = $this->checkNotEmptyAndGetParam('needNumber');
	$data['needDepartmentId'] = $this->checkNotEmptyAndGetParam('needDepartmentId');
	$data['boyfirst'] = $this->checkNotEmptyAndGetParam('boyfirst');
	$datearr = explode("-",$data['date']);  
	$year = $datearr[0];      
	$month = sprintf('%02d',$datearr[1]); 
	$day = sprintf('%02d',$datearr[2]); 
	$hour = $minute = $second = 0; 
	$dayofweek = mktime($hour,$minute,$second,$month,$day,$year);   
	$printdate = date("w",$dayofweek);  
	$data['day'] = $printdate; 
	$sActivity->data($data)->add();
	$this->successWithEmptyData();
}
public function getSubactivity($id){
	$sActivity = D('pitch_sub_activity');
	$sAdata = $sActivity->find($id);
	$this->setData('date',$sAdata['date']);
	switch ($sAdata['place']) {
			case '1':
				$place = '一饭';	
				break;
			case '2':
				$place = '二饭';
				break;
			case '0':
				$place = '';
				break;
			default:
				break;
			}
	$this->setData('place',$place);
		switch ($sAdata['lesson']) {
			case '1':
				$lesson = '一二节';	
				break;
			case '2':
				$lesson = '三四节';
				break;
			case '3':
				$lesson = '五六节';
				break;
			case '4':
				$lesson = '七八节';
			default:
				break;
			}
		switch ($sAdata['day']) {
			case '0':
				$lesson = '周日';	
				break;
			case '1':
				$lesson = '周一';
				break;
			case '2':
				$lesson = '周二';
				break;
			case '3':
				$lesson = '周三';
			case '4':
				$lesson = '周四';
				break;
			case '5':
				$lesson = '周五';
				break;
			case '6':
				$lesson = '周六';
			default:
				break;
			}
	$this->setData('day',$day);
	$this->setData('lesson',$lesson);
	$this->setData('needNumber',$sAdata['needNumber']);
	$this->setData('boyfirst',$sAdata['boyfirst']);
	$this->setData('needDepartmentId',$sAdata['needDepartmentId']);
	$studentid = $sAdata['header'];
	$user = D('users');
	$userData = $user->where('student_number' == $studentid)->find();
	$this->setData('header',$userData['name']);
	$this->code = 200;
	$this->finish();
}
public function editactivity($id){
	$sActivity = D('pitch_sub_activity');
	$data = array();
	$data['date'] = $this->checkNotEmptyAndGetParam('date');
	switch ($this->checkNotEmptyAndGetParam('place')) {
		case '一饭':
			$data['place'] = '1';
			break;
		case '二饭':
			$data['place'] = '2';
			break;
		case '':
			break;
		default:
			$data['place'] = '0';
			break;
		}
	switch ($this->checkNotEmptyAndGetParam('lesson')) {
		case '一二节':
			$data['lesson'] = '1';
			break;
		case '三四节':
			$data['lesson'] = '2';
			break;
		case '五六节':
			$data['lesson'] = '3';
			break;
		case '七八节':
			$data['lesson'] = '4';
		default:
			$data['lesson'] = '0';
			break;
		}
	$data['header'] = $this->checkNotEmptyAndGetParam('header');
	$data['needNumber'] = $this->checkNotEmptyAndGetParam('needNumber');
	$data['needDepartmentId'] = $this->checkNotEmptyAndGetParam('needDepartmentId');
	$data['boyfirst'] = $this->checkNotEmptyAndGetParam('boyfirst');
	$datearr = explode("-",$data['date']);  
	$year = $datearr[0];      
	$month = sprintf('%02d',$datearr[1]); 
	$day = sprintf('%02d',$datearr[2]); 
	$hour = $minute = $second = 0; 
	$dayofweek = mktime($hour,$minute,$second,$month,$day,$year);   
	$printdate = date("w",$dayofweek);  
	$data['day'] = $printdate; 
	$sActivity->where("id=$id")->sava($data);
	$this->successWithEmptyData();
}
public function getpublishsa($id){
	$sActivity = D('pitch_sub_activity');
	$sAdata = $sActivity->find($id);
	$this->setData('date',$sAdata['date']);
	switch ($sAdata['place']) {
			case '1':
				$place = '一饭';	
				break;
			case '2':
				$place = '二饭';
				break;
			case '0':
				$place = '';
				break;
			default:
				break;
			}
	$this->setData('place',$place);
		switch ($sAdata['lesson']) {
			case '1':
				$lesson = '一二节';	
				break;
			case '2':
				$lesson = '三四节';
				break;
			case '3':
				$lesson = '五六节';
				break;
			case '4':
				$lesson = '七八节';
			default:
				break;
			}
		switch ($sAdata['day']) {
			case '0':
				$lesson = '周日';	
				break;
			case '1':
				$lesson = '周一';
				break;
			case '2':
				$lesson = '周二';
				break;
			case '3':
				$lesson = '周三';
			case '4':
				$lesson = '周四';
				break;
			case '5':
				$lesson = '周五';
				break;
			case '6':
				$lesson = '周六';
			default:
				break;
			}
	$this->setData('day',$day);
	$this->setData('lesson',$lesson);
	$this->setData('needNumber',$sAdata['needNumber']);
	$this->setData('boyfirst',$sAdata['boyfirst']);
	$this->setData('needDepartmentId',$sAdata['needDepartmentId']);
	$studentid = $sAdata['header'];
	$user = D('users');
	$userData = $user->where('student_number' == $studentid)->find();
	$this->setData('header',$userData['name']);
	$this->code = 200;
	$assignment = D('pitch_assignment');
	$list = $assignment->where($subActivityId = $id)->select();
	$this->setData('list',$list);
	$this->finish();
}
public function submit($id){
	$assignment = D('pitch_activity');
	$data['assigned'] = 1; 
	$assignment->where("id=$id")->sava($data);
}
}
?>

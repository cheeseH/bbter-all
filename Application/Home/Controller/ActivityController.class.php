<?php
namespace Home\Controller;
use Think\Controller;
class ActivityController  extends BaseController{

	public function obtainActivityList(){
		$kind=$this->checkNotEmptyAndGetParam('kind');


		$pitch_activity=D('pitch_activity');
		
		switch ($kind) {
			case '全部':
		        $kind=0;
			    $activity=$pitch_activity->select();
		 		break;
		 	case '未发布':
		 	    $kind=1;
		 		$activity=$pitch_activity->where(array('assigned'=>0))->select();# code...
		 		break;//array('department_id'=>$departmentUserId)
		 	case '已发布':
		 	    $kind=2;
		 	    $activity=$pitch_activity->where(array('assigned'=>1))->select();
		 		# code...
		 		break;
			
			default:
			    $this->AssignOwn('code',204);//输入的kind参数不为全部已发布未发布之一
			// 	# code...
				break;
		 }
		 
		 
		foreach ($activity as $key => $value) {
			$actid=$value['id'];
			$actname=$value['name'];
			$acttime=$value['time'];
			$actstatus=$value['assigned'];
			$record=array();
			$record['actid']=$actid;
			$record['actname']=$actname;
			$record['acttime']=$acttime;
			$record['actstatus']=$actstatus;
			//$list=array();
			$list[$key]=$record;
		}

		 $this->setData('number',10);
		 $this->setData('list',$list);
		 $this->code=200;
		 $this->finish();
		
	}

}
?>
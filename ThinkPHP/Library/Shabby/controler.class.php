<?php
namespace Shabby;
require_once("list.php");
class _controler{
	public $activity_id;//摆摊ID
	public $freenumber_in_each_lession;
	//freenumber_in_each_lession[$lessionInWeek][0]=该课时总共有多人有空
	//$this->freenumber_in_each_lession[$lessionInWeek][1]=array()，数组值为在该课时段里的子活动在sub_activitys数组的key
	public $order;//一维数组，对应上面的freenumber_in_each_lession
	//order=array(),保存freenumber_in_each_lession不为空的课时的索引
	public $bodys;//符合条件的所有人的信息吧
	public $nodes;//保存所有人信息的节点,避免节点被回收
	public $list;
	public $sub_activitys;//数组,每个元素是一个子活动，子活动的内容如下
	/*
	包括下面几个内容
	public $id;//sub_activitys_id
	public $activity_id;
	public $date;//活动开始时间
	public $lession;//活动需要的课程时间
	public $day;//周几.0是周一，以此类推
	public $header;//负责人
	public $needDepartmentId;//数组
	public $boyFirst;
	public $needNumber;//活动需求人员数目
	public $place;//活动地点
	public $tempnum;//保存当前分配状态下,符合当前分配条件的人数,用于调用list的rebuild
	public $boy_num;//需要男生的数目,按照是否男优来计算
	public $girl_num;//可有可无,$needNumber-$boy_num;
	 */
	public $pitch_assignment;
	/*
	userid
	sub_activitys_id
	 */
	public function __construct($activity_id,$sub_activitys){
		//初始化
		$this->activity_id=$activity_id;
		$this->sub_activitys=$sub_activitys;
		$this->freenumber_in_each_lession=array();
		$this->nodes=array();
		$this->bodys=array();
		$tmp_num=0;
		for($i=0;$i<=41;$i++){
				$this->freenumber_in_each_lession[$i]=array(0,array());
		}
		foreach ($sub_activitys as $subkey => $value) {
			$derpnum=0;
			$needdepartmentid=json_decode($value['needdepartmentid']);
			for ($i=0; $i < count($needdepartmentid); $i++) { 
				if($derpids[$i]==1)
					$derpnum++;
			}
			$needNumber=$value['neednumber']-$derpnum;
			if($value['boyfirst']!=0){
				$this->sub_activitys[$subkey]['boy_num']=ceil($needNumber/2+1);
				$this->sub_activitys[$subkey]['girl_num']=$needNumber-$this->sub_activitys[$subkey]['boy_num'];
			}else{
				$this->sub_activitys[$subkey]['boy_num']=ceil($needNumber/2);
				$this->sub_activitys[$subkey]['girl_num']=$needNumber-$this->sub_activitys[$subkey]['boy_num'];
			}
			//构建 链表 
			$ass = D('pitch_assignment');
			$day = $value['day'];
			$lession = $value['lession'];
			$lessionInWeek = ($lession-1)*7+$day;
			$init = 1;
			//$init<<=$lessionInWeek;
			$init=pow(2,$lessionInWeek);
			//echo "</br>";
			//echo "</br>";
			//(tt.table & $init>0) AND  可能要去除
			$useDates=$ass->field('u.id,u.sex,u.department_id,tt.table,pu.pitchTimes')->table("users u,departments dprt,pitch_timetable tt,pitch_user pu")->where("(tt.table & $init>0) AND tt.userid=u.id AND u.department_id=dprt.id AND u.id = pu.userid AND u.completed = 1 AND u.status = 'NORMAL'")->select();
			//var_dump($useDates);
			foreach ($useDates as $key => $udate) {
				$uid=$udate['id'];
				$sex=$udate['sex'];
				$department_id=$udate['department_id'];
				$table=$udate['table'];
				$pitchTimes=$udate['pitchtimes'];
				$this->bodys[$uid]=New body($uid,$sex,$department_id,$pitchTimes,$table);
			}
			$this->freenumber_in_each_lession[$lessionInWeek][0]=count($this->bodys)-$tmp_num;//当前课时没课的人数
			$this->freenumber_in_each_lession[$lessionInWeek][1][]=$subkey;//添加key到该数组,避免数据库查询
			$tmp_num=count($this->bodys);
		}
		
		foreach ($this->bodys as $key =>&$value) {
			if (empty($this->nodes)) {
				$this->list=New _list($value);
				$this->nodes[]=&$this->list->get_node(0);
			}else
				$this->nodes[]=&$this->list->add_node($value);
		}
		
		//排序没课表		
		for($i=0;$i<42;$i++){
			if($this->freenumber_in_each_lession[$i][0]==0)
				continue;
			$tmp_array[$i]=$this->freenumber_in_each_lession[$i][0];
		}
		asort($tmp_array);
		foreach ($tmp_array as $key => $value) {
			if($value!=0)
				$this->order[]=$key;
		}
	}

	public function main(){//分配的入口函数
		foreach ($this->order as $key1 => $value1) {
			$sub_activitys_keys=$this->freenumber_in_each_lession[$value1][1];
				foreach ($sub_activitys_keys as $key2 => $value2) {
					$sub_activity=$this->sub_activitys[$value2];
					//var_dump($sub_activity);
					$subid=$sub_activity['id'];
					$day = $sub_activity['day'];
					$lession = $sub_activity['lession'];
					$lessionInWeek = ($lession-1)*7+$day;
					//$init = 1;
					//$init<<=$lessionInWeek;//需要修改成bcpow(2,$lessionInWeek)
					$init=pow(2,$lessionInWeek);
					$boy_num=$sub_activity['boy_num'];
					$girl_num=$sub_activity['girl_num'];
					$needNumber=$sub_activity['needNumber'];
					$needDepartmentId=$sub_activity['needdepartmentid'];
					$freenum=$this->freenumber_in_each_lession[$value1][0];
					$controlnum=max($freenum,$needNumber);//避免空闲人数比需求人数少，每次分配都要同时减少freenum和controlnum以及neednumber
					//现将needDepartmentId转换为数组
					$derpids=json_decode($needDepartmentId);
					for ($i=0; $i < count($derpids); $i++) { 
						if($derpids[$i]==1)
							$needDepartmentId_array[]=$i+1;
					}
					$this->list->rebuild_by_and($init,$needNumber,1);//将空闲人员放置到链表前端
					$this->list->rebuild_between_node(array('field'=>'pitchTimes','keyword'=>-5,'last'=>$controlnum));//按照升序排列pitchTimes
					foreach ($needDepartmentId_array as $key => $value) {//按照需求部门分配,不管性别
						//echo "department_id";
						$this->allot_with_keyword('department_id',$value,$controlnum,$subid);
						$needNumber--;
						$controlnum--;
						$this->freenumber_in_each_lession[$value1][0]--;
					}
					for($i=0;$i<$boy_num;$i++){//分配男生
						//echo "boy";
						$this->allot_with_keyword('sex','MALE',$controlnum,$subid);
						$needNumber--;
						$controlnum--;
						$this->freenumber_in_each_lession[$value1][0]--;
					}
					for($j=0;$j<$girl_num;$j++){//分配女生
						//echo "girl";
						$this->allot_with_keyword('sex','FEMALE',$controlnum,$subid);
						$needNumber--;
						$controlnum--;
						$this->freenumber_in_each_lession[$value1][0]--;
					}
					for($k=$needNumber;$k>0 && $k > $controlnum ;$k--){//避免计算出错导致分配少人
						$this->allot($subid,$k);
						$this->freenumber_in_each_lession[$value1][0]--;						
					}
					//echo "</br>".$this->list->num."</br>";
					//return;
				}
		}
		//echo "end";
	}
	private function allot_with_keyword($field,$keyword,$controlnum,$subid){//按照keyword,在$controlnum范围内搜索,找到keyword后,返回节点在链表的索引,失败返回$controlnum
		$result=$this->list->search_with_keyword($field,$keyword,$controlnum);
		$this->allot($subid,$result);
	}
	private function allot($subid,$lid){//根据链表的索引,分配人员到subid的活动
		$tmp_node=$this->list->get_node($lid);
		$tmp_body=$tmp_node->body;
		$ass = D('pitch_assignment');
		$usr = D('pitch_user');
		$uid=$tmp_body->get_field('id');
		$data['userId'] = $uid;
		$data['subActivityId'] = $subid;
		$ass->data($data)->add();
		/*
		$ud = $usr->where("userId = $uid")->find();
		$pt = $ud['pitchTimes'];
		$pt++;
		$d = array();
		$d['pitchTimes'] = $pt;
		$usr->where("userId = $uid")->save($d);
		*/
		$usr->where('userId = '.$uid)->setInc('pitchTimes',1);
		$this->list->de_node($lid);
		//echo $subid."=> ".$lid."</br>";
	}
}
?>
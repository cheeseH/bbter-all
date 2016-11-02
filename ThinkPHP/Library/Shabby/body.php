<?php
namespace Shabby;
require_once("node.php");
class body{
	/*
	id:个人资料存在数据库里面的主键id
	sex:枚举 男女 male,female
	department_id:看数据库里面的ID,每个ID对应一个部门
	pitchTimes:int
	table: long型，二进制位为1表示该节课有空
	times:int
	 */
	private $id;//id
	public $sex;//性别,用于个别的男士优先情况,例如12.7节帮东西
	public $department_id;//部门,用于处理部门优先的情况
	public $pitchTimes;//总摆摊次数,用于避免一个人多次摆摊
	public $table;//long型，用位操作，0代表没课，1代表有课,从第一个位开始数,每个位代表一个课时，一天有6个课时，7天，占42个位
	public $times;//该次活动的摆摊分配次数,在不多于2次的前提下,如果遇到实在没课的情况,会酌情分配该人.（考虑他的总摆摊次数，如果少于5次,就分配.多于5次,留空,到最后检测是否满表的时候把摆摊次数少的人,补上去
	public function __construct($id,$sex,$department_id,$pitchTimes,$table){//未完善！！
		$this->id=$id;
		$this->sex=$sex;
		$this->department_id=$department_id;
		$this->pitchTimes=$pitchTimes;
		$this->table=$table;
		$this->times=0;
	}
	public function &get_field($field)
	{
		switch($field){
			case 'id':return $this->id;break;
			case 'sex':return $this->sex;break;
			case 'department_id':return $this->department_id;break;
			case 'pitchTimes':return $this->pitchTimes;break;
			case 'times':return $this->times;break;
			case 'table':return $this->table;break;
			default:return 0;
		}
	}
	public function _echo()
	{
		echo '<hr/>';
		echo '[姓名]:'.$this->id.'<br/>';
		echo '[性别]:'.$this->sex.'<br/>';
		echo '[部门]:'.$this->department_id.'<br/>';
		echo '[总次]:'.$this->pitchTimes.'<br/>';
		echo '[空闲]:<br/>';

	}
	public function allot(){
		$this->times++;
	}
}
?>
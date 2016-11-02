<?php
namespace Shabby;
require_once("body.php");
class _list{//把人员信息用链表串起来
	private $head;//链表头,指向node的实例
	private $last;//链表尾,方便添加节点
	public $num;//链表节点数目
	public function __construct(&$body){
		$tmp=new node($body);
		$this->head=$tmp;
		$this->num=1;
		$this->last=$tmp;
		return $tmp;
	}
	public function __destruct(){
		echo "";
	}
	public function rebuild_with_keyword($option=array('field'=>'sex','keyword'=>'male','last'=>7)){//根据选项重构链表,选项要求有  {排序字段,keyword,末端节点},当keyword是数值时请用下面的re_build_compare
		$last=min($option['last'],$this->num);
		for($i=0;$i<$last;$i++)
		{
			$tmp=$this->get_node($i);
			$flag=$i;
			if($this->match($tmp->body->get_field($option['field']),$option['keyword']))
				continue;
			for($j=$i;$j<$last;$j++)
			{
					$tmpp=$this->get_node($j);
					switch($this->match($tmpp->body->get_field($option['field']),$option['keyword'])){
						case 0:break;
						case 1:$flag=$j;break;
						case 2:;break(2);
						default:break;
					}
			}
			if($flag!=$i)
				$tmpp=$this->get_node($flag);
			$this->exchange($tmp,$tmpp);
				
		}

	}
	public function rebuild_between_node($option=array('field'=>'pitchTimes','keyword'=>0,'last'=>10)){ //keyword>=0是降序排
		$last=min($option['last'],$this->num);
		for($i=0;$i<$last;$i++){
			$tmp=$this->get_node($i);
			$tmpp=$this->get_node($i);
			$flag=$tmp;
			for($j=$i;$j<$last;$j++){
				if($tmpp->next!=NULL)
					$tmpp=$tmpp->next;
				//var_dump($tmpp);
				if(isset($option['keyword'])&&$option['keyword']>=0)
				{
				 if($flag->body->get_field($option['field'])<$tmpp->body->get_field($option['field']))
					$flag=$tmpp;
				}else if($flag->body->get_field($option['field'])>$tmpp->body->get_field($option['field']))
					$flag=$tmpp;
				
			}
			if($flag!=$tmp){
				echo "xxx";
				echo $flag->body->pitchTimes."<=>".$tmp->body->pitchTimes;
				$this->exchange($tmp,$flag);
			}
		}
	}
	public function rebuild_by_and($init,$last,$first=0){
		if($first!=0)
			$last=max($last,$this->num);
		else
			$last=min($last,$this->num);
		$flag;
		for($i=0;$i<$last;$i++){
			for($j=$i;$j<$last;$j++){
				$tmp=$this->get_node($j);
				$flag=$j;
				if($tmp->body->get_field['table']&$init)//需要修改成字符串的按位与方法
					break;
			}
			if($flag!=$i){
					$tmpp=$this->get_node($i);
					$this->exchange($tmp,$tmpp);				
			}
		}
	}
	public function search_with_keyword($field,$keyword,$controlnum){
		$last=min($controlnum,$this->num);
		$result=$last;
		$tmp=$this->head;
		for($i=0;$i<$last;$i++){
			if($tmp->body->get_field($field)==$keyword){
				$result=$i;
				break;
			}else
				$tmp=$tmp->next;
		}
		return $result;
	}
	public function de_node($id){//删掉某节点
		if($id==0 && $this->num>1)
			{
				$tmp=$this->head;
				$this->head=$tmp->next;
			}
		elseif($id==($this->num-1)&& $this->num>1)
			{
				$tmp=$this->last;
				$this->last=$tmp->previous;
			}
		else{
			$tmp=$this->get_node($id);
		}
		$tmp->__destruct();
		$this->num--;
	}
	public function &add_node(&$body){//在末尾链表后加上一个节点,节点总数加1
		$tmp=New node($body);
		$this->num++;
		$tmp->previous=$this->last;
		$this->last->next=$tmp;
		$this->last=$tmp;
		return $tmp;
	}
	public function &get_node($num){//顺序遍历到第$num个节点,由0开始计算,返回node的实例
		$tmp=$this->head;
		for($i=0;$i<$num;$i++)
		{
			if($tmp->next!=NULL)
				$tmp=$tmp->next;
		}
		return $tmp;
	}
	public function exchange(&$node1,&$node2){
		$tmp=$node1->body;
		$node1->body=$node2->body;
		$node2->body=$tmp;
	}
	private function match($left,$right){
		if(is_numeric($right)){
			if($left>=$right)
				return 1;
		}else if($left==$right) return 2;
		else return 0;
	}
}
?>
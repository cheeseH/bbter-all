<?php
namespace Shabby;
class node{
	public $body;//指向一个自定义类型,用于保存人员信息
	public $previous;//指向上一个node的实例
	public $next;//指向下一个node的实例
	public function __construct(&$body){//根据数据库的信息构建node节点
		$this->body=$body;
		$this->next=null;
	}
	public function __destruct(){
		if($this->previous!=NULL && $this->next!=NULL ){
			$this->previous->next=$this->next;
			$this->next->previous=$this->previous;
		}
		elseif($this->previous==NULL && $this->next!=NULL ){
			$this->next->previous=NULL;
		}elseif($this->previous!=NULL && $this->next==NULL ){
			$this->previous->next==NULL;
		}
		$this->body=NULL;
		$this->previous=NULL;
		$this->next=NULL;
	}
	public function add_next(&$node){
		$this->next=$node;
	}

}
?>
<?php
namespace Home\Controller;
use Think\Controller;
class ModifyCourseController  extends BaseController{
	 

	public function addDataToNewTable(){

        //获取到的data数组变成整数
        $timeInt=$this->changeArrayIntoInt($this->checkNotEmptyAndGetParam('class'));
		$timetable = D('pitch_timetable');
		$courseData= $timetable->where('userid=$this->userid')->setField(array('newtable','state'),array('$timeInt',0));
        
		$this->code = 200;
		$this->finish();
	}

	private function changeArrayIntoInt($class){
		//$class=array();
	    $transferInteger = 0;
	     $temp = 1;
	    for($i = 0 ; $i < 6 ; $i ++){
		    for($j = 0 ; $j < 7 ; $j++){
			$temp = 1;
			//echo $temp1 ."<br>";
			if( ($class[$i][$j] & $temp > 0) ){
				$transferInteger += pow(2, (7* $i + $j) );
				//echo pow(2, (7* $i + $j) );
				//echo '<br>';
			}else{
				$transferInteger += 0;
				//echo 'meijia';
			}
		}
	} 
	return  $transferInteger;
    echo "change 函数的调用";

    }

}

?>
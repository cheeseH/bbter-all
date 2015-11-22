<?php
function test(){
	echo 'hehe';
}
function getTransferClass($transferInteger){
	for($i = 0 ; $i < 6 ; $i ++){
		for($j = 0 ; $j < 7 ; $j ++){
			if($transferInteger & 1 == 1){
				$transferClass[$i][$j] = 1;
					
			
			}else{
				$transferClass[$i][$j] = 0;
		
			}

			$transferInteger = (double)($transferInteger / 2);
			if($transferInteger < 0)
				break;
		}
		if($transferInteger < 0)
			break;
	}
	return $transferClass;
}
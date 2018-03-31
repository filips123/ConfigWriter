<?php
$GLOBALS['showClass'];
$GLOBALS['showOther'];
$GLOBALS['stevilo'] = 0;
$GLOBALS['komentar'];
$GLOBALS['status']=1;
$GLOBALS['status_message'];
class Config {
	function language($a) {
		if(file_exists('languages/'.$a.'.php')) {
			include('languages/'.$a.'.php');
			$GLOBALS['language'] = $a;
		}else {
			$GLOBALS['status_message']="I can't find the selected language.";
			$GLOBALS['ststus']=0;
		}
	}
	
	function showClass ($a) {
		if($GLOBALS['status']!=0) {
		$GLOBALS['showClass']=$a;
		}
	}
	
	function showOther ($a) {
		if($GLOBALS['status']!=0) {
		$GLOBALS['showOther']=$a;
		}
	}
	
	function set($a,$b,$c){
		if($GLOBALS['status']!=0) {
		$GLOBALS['stevilo']++;
		$this->$a=array($b,$c,$GLOBALS['stevilo']);
		}
	}
	
	function toString($class='',$addclass='',$addnoclass=''){
		if($GLOBALS['status']!=0) {
		if($GLOBALS['showClass']==''or $GLOBALS['showOther']=='') {
			$GLOBALS['status_message']=$GLOBALS[$GLOBALS['language']]['showClass/showOther'] ;
			$GLOBALS['status']=0;}
		$string .='<?php
';		if($GLOBALS['showClass']==1) {
		$string .= "class ".$class." { 
	";
		if (isset($GLOBALS['komentar'][0])) {
		$string .= '/*'.$GLOBALS['komentar'][0].'*/
	';
	}
		foreach ($this as $k=>$v) {
			if($v['1'] == '') {
			$string .= 'public $'.$k." = '".$v[0]."'; 
	";		
			}else{
			$string .= 'public $'.$k." = '".$v[0]."'; //".$v[1]."
	";	
		}
		if($GLOBALS['komentar'][$v[2]] != ''){
		$string .= '
	/*'.$GLOBALS['komentar'][$v[2]].'*/
	';
	}
		}
		if($addclass!='') {
		$string .='
'.$addclass.'';}
		$string .="
}";$string .="

";}
		if($GLOBALS['showOther']==1) {
if (isset($GLOBALS['komentar'][0])) {
		$string .= '/*'.$GLOBALS['komentar'][0].'*/';
	}
foreach ($this as $k=>$v) {
			if($v['1'] == '') {
			$string .= '
$'.$k.' = "'.$v[0].'"; ';
			}else{
			$string .= '
$'.$k.' = "'.$v[0].'"; //'.$v[1];
if($GLOBALS['komentar'][$v[2]] != ''){
		$string .= '

/*'.$GLOBALS['komentar'][$v[2]].'*/';
	}
			}

}
}
if($addnoclass!='') {		
$string .='

'.$addnoclass.'';	}	
$string .="
?>";
		return $string;
	}
	}
	
	function comment($a) {
		if($$GLOBALS['status']!=0) {
		$GLOBALS['komentar'][''.$GLOBALS['stevilo'].''] = $a;
		}
	}
	
	function toFile($file,$data) {
		if($GLOBALS['status']!=0) {
		$fd = fopen ($file , "w") or $statusek=0;
		if($statusek==0) {
			$GLOBALS['status_message']=$GLOBALS[$GLOBALS['language']]["can't open"];
			$GLOBALS['status']=0;
		}
		fwrite($fd, $data);
		fclose($fd) ;
		
		$fd = fopen ($file , "r") or $statusek2=0;
		if($statusek2==0) {
			$GLOBALS['status_message']=$GLOBALS[$GLOBALS['language']]["can't open"];
			$GLOBALS['status']=0;
		}
		$data2=fread ($fd , filesize ($file));
		if($data==$data2) {
			$GLOBALS['status_message']=$GLOBALS[$GLOBALS['language']]["ok"];
			$GLOBALS['status']=1;
		}
		fclose($fd) ;	
		}
	}
	
	function status() {
		return $GLOBALS['status'];
	}
	
	function status_message() {
		return $GLOBALS['status_message'];
	}
}
?>

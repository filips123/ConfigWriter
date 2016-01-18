<?php
$GLOBALS['showClass'];
$GLOBALS['showOther'];
$GLOBALS['stevilo'] = 0;
$GLOBALS['komentar'];
class Config {
	function language($a) {
		if(file_exists('languages/'.$a.'.php')) {
			include('languages/'.$a.'.php');
			$GLOBALS['language'] = $a;
		}else {
			die("I can't find the selected language.");
		}
	}
	
	function showClass ($a) {
		$GLOBALS['showClass']=$a;
	}
	
	function showOther ($a) {
		$GLOBALS['showOther']=$a;
	}
	
	function set($a,$b,$c){
		$GLOBALS['stevilo']++;
		$this->$a=array($b,$c,$GLOBALS['stevilo']);
	}
	
	function toString($class='',$addclass='',$addnoclass=''){
		if($GLOBALS['showClass']==''or $GLOBALS['showOther']=='') {die ($GLOBALS[$GLOBALS['language']]['showClass/showOther']) ;}
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
	
	function comment($a) {
		$GLOBALS['komentar'][''.$GLOBALS['stevilo'].''] = $a;
	}
	
	function toFile($file,$data) {
		$fd = fopen ($file , "w") or die ($GLOBALS[$GLOBALS['language']]["can't open"]) ;
		fwrite($fd, $data);
		fclose($fd) ;
		
		$fd = fopen ($file , "r") or die ($GLOBALS[$GLOBALS['language']]["can't open"]) ;
		$data2=fread ($fd , filesize ($file));
		if($data==$data2) {
			die($GLOBALS[$GLOBALS['language']]["ok"]);
		}
		fclose($fd) ;	
	}
}
?>

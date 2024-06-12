<?php
ini_set ( 'max_execution_time' , 0);

date_default_timezone_set ( "Europe/Paris" );
$basePathH    = 'C:\Users\lucas\AppData\Roaming\Sublime Text 3\.sublime\Local History\C\Program Files\Ampps\www';
$basePath    = 'C:\Program Files\Ampps\www';
$baseDate = "2019/04/17";


$baseDate   = new DateTime($baseDate); 

if (isset($_GET['p'])) {
	$basePath2 = $basePath.$_GET['p'];
} else {
	$basePath2 = $basePath;
}

function getListH($lvl,$path) {
	global $baseDate;
	$return = "";
	$files = array_diff(scandir($path), array('.', '..','.DS_Store'));
	foreach ($files as $value) {
		if (!is_dir($path.'/'.$value)) {
			$dateTime = substr($value, strrpos($value, '.', -1)-14, 14);
			$anne = $dateTime[0].$dateTime[1].$dateTime[2].$dateTime[3];
			$mois = $dateTime[4].$dateTime[5];
			$jour = $dateTime[6].$dateTime[7];
			$heure = $dateTime[8].$dateTime[9];
			$minute = $dateTime[10].$dateTime[11];
			$margin = $lvl * 40;

			$date = date("d/m/Y H:i", filemtime($path.'/'.$value));
			$date2 = date("Y/m/d", filemtime($path.'/'.$value));
			$dtDate = new DateTime($date2);
			if ($date != "01/01/1970 00:00" && $dtDate >= $baseDate) {
				$return .=  '<a  style="margin-left: '.$margin.'px;" href="file://'.$path.'/'.$value.'" target="_blank">'.$jour.'/'.$mois.'/'.$anne.' '.$heure.':'.$minute.' ('.$value.')</a><br />';
			}
		}
	}
	return $return;
}

function getList($lvl,$path) {
	$hasFile = false;
	global $basePathH,$basePath,$baseDate;
	$return = "";
	$test = 0;
	$files = array_diff(scandir($path), array('.', '..','.DS_Store'));

	foreach ($files as $value) {
		$margin = $lvl * 40;
		if (is_dir($path.'/'.$value)) {
			$return .=  '<a style="margin-left:'.$margin.'px;" href="?p='.substr($path,strlen($basePath)).'/'.$value.'">'.$value.'</a><br />';
			$temp = getList($lvl+1,$path.'/'.$value);
			if ($temp != false) {
				$return .= $temp;
			}
			if (is_dir($basePathH.substr($path,strlen($basePath)).'/'.$value)) {
				$return .= getListH($lvl+1,$basePathH.substr($path,strlen($basePath)).'/'.$value);
			}
		} else {
			if (file_exists($path.'/'.$value)) {

				$date = date("d/m/Y H:i", filemtime($path.'/'.$value));
				$date2 = date("Y/m/d", filemtime($path.'/'.$value));
				$dtDate = new DateTime($date2);
				if ($date != "01/01/1970 00:00" && $dtDate >= $baseDate) {
					$return .=  '<span style="margin-left:'.$margin .'px;">'.$date.' ('.$value.')</span><br />';
					$hasFile = true;
				}
			}
		}
		/*
		$test++;
		if ($test == 5) {
			return $return;
		}
		*/
	}
	return $return;
	if ($hasFile) {
		return $return;
	} else {
		return false;
	}
}

echo getList(0,$basePath2);

?>
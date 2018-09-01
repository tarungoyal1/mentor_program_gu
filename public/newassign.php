<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/initialize.php';
require('../includes/spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
require('../includes/spreadsheet-reader-master/SpreadsheetReader.php');

class Faculty
{
public $fac_name;
public $stud_enroll;
}

$a=0;
$filesmentors =  array();
$mentor_mentee_data=array();
$reader = new SpreadsheetReader('checklist_14.7.2017.xls');
$Sheets = $reader -> Sheets();
	foreach ($Sheets as $Index => $Name){
		// 	// echo 'Sheet #'.$Index.': '.$Name."<br />";
	
			$reader -> ChangeSheet($Index);

		foreach ($reader as $row){
			if (!trim($row[6]=="Mentor Name")) {
		            $fac = new Faculty();
					$fac->stud_enroll = $row[2];
					$fac->fac_name = trim(preg_replace("/[^a-z]+/","", strtolower($row[6])));
				if (!in_array($fac, $mentor_mentee_data)) {
					$mentor_mentee_data[++$a] = $fac;
				}
				
			}
		}
			// echo "<hr />";

	}

$matched_mentor=array();	    
$c=0;
$mc=0;
$mentors = Mentor::find_all_mentors();
foreach ($mentor_mentee_data as $fm) {
	if (trim($fm->fac_name)!='') {
		foreach ($mentors as $m) {
          $mname = trim(preg_replace("/[^a-z]+/","", strtolower($m->employee_name)));
          similar_text($mname,$fm->fac_name, $percent);
		  if($percent>85){
		  	$fac = new Faculty();
		  	$fac->fac_name = $m->employee_id;
		  	$fac->stud_enroll = $fm->stud_enroll;
		  	if (!in_array($fac, $matched_mentor)) {
					$matched_mentor[++$mc] = $fac;
			}
		  	// echo $c.' - '.$fm." - ".$mname." ~ ".$percent."<br />";
		  }
		}
	}
}

foreach ($matched_mentor as $fm) {
	if (trim($fm->fac_name)==''||trim($fm->stud_enroll)=='')continue;

	$mentor= Mentor::find_mentor_by_id($fm->fac_name);
	if (Assign::assign_student_to_mentor($mentor->employee_id, $fm->stud_enroll)){
		echo "Assigned ".$mentor->employee_id." - ".$fm->stud_enroll."<br />";
	}
}

// var_dump($reader);

	// }
// if(count($mentors)>0){
// 	foreach ($mentors as $m) {
// 		$mname = trim(preg_replace("/[^a-z]+/","", strtolower($m->employee_name)));
// 		// echo $mname."<br />";
// 		foreach ($filesmentors as $fm){
// 			// echo $fm->sid."<br />";
// 			$fmname = trim(preg_replace("/[^a-z]+/","", strtolower($fm->ename)));
// 			if ($mname!=$fmname&&$fm->sid!="General"&&$fm->sid!="Enroll No") {
// 				// if(Assign::assign_student_to_mentor($m->employee_id, $fm->sid))
// 				// 	echo "Student ".$fm->sid." has been assigned to ".$m->employee_id;
// 				// similar_text($mname, $fmname, $percent);
// 			 //   if($percent>60)echo $fmname." - ".$mname." ~ ".$percent."<br />";

// 			}
// 		}
		
// 	}
// }
// $unassigned = array();
// $c=0;
// $mentors = Mentor::find_all_mentors();
// foreach ($mentors as $m) {
// 	if(count(Assign::find_all_students_of_mentor($m->employee_id))==0)$unassigned[++$c]=$m;
// }
// echo count($unassigned)." mentors data is  yet to be automated"."<br />";
// foreach ($unassigned as $um) { 
// 	$mname = trim(preg_replace("/[^a-z]+/","", strtolower($um->employee_name)));
// 	// echo "<br />".$unassigned[$i];
//     // echo $um->employee_id." - ".$um->employee_name."<br />";
// 	foreach ($filesmentors as $fm){
// 			// echo $fm->sid."<br />";
// 			$fmname = trim(preg_replace("/[^a-z]+/","", strtolower($fm->ename)));
// 			if ($mname!=$fmname&&$fm->sid!="General"&&$fm->sid!="Enroll No") {
// 				similar_text($mname, $fmname, $percent);
// 			   if($percent>=84){
// 			   	if(Assign::assign_student_to_mentor($um->employee_id, $fm->sid))
// 					echo "Student ".$fm->sid." has been assigned to ".$um->employee_id."<br />";
// 			   	// echo $fmname." - ".$mname." ~ ".$percent."<br />";
// 			   }

// 			}
// 		}
// }

//GU02137101316 - S. Vijayalakshmi
//GU02137101329 - S. Ghosh


?>
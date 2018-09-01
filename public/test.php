<?php
include '../includes/initialize.php';
require('../includes/spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
require('../includes/spreadsheet-reader-master/SpreadsheetReader.php');

class Student
{
public $ename;
public $sid;
}
$mentors = Mentor::find_all_mentors();
$reader = new SpreadsheetReader('../includes/mentors2.xls');
$Sheets = $reader -> Sheets();
// $dbmentors = array();
// $c=0;
// if(count($mentors)>0){
// 	foreach ($mentors as $m) {
// 		$mentor = trim(preg_replace("/[^a-z]+/","", strtolower($m->employee_name)));
// 		$dbmentors[$c++] = $mentor;
// 	}
// }

// for ($i=0; $i < count($dbmentors); $i++) { 
// 	echo $dbmentors[$i]."<br />";
// }

$c=0;
$student = new Student();
$filesmentors =  array();
	foreach ($Sheets as $Index => $Name){
		// echo 'Sheet #'.$Index.': '.$Name."<br />";

		$reader -> ChangeSheet($Index);

		foreach ($reader as $row){
			foreach ($row as $r){
				$student = new Student();
					$student->ename = $row[6];
					$student->sid = $row[2];

				if(!in_array($student, $filesmentors)){
					$filesmentors[$c++] = $student;
					// echo $student->ename."<br />";
					// echo $student->sid."<br />";
				}
					


				
				

			}
			// echo "<hr />";
	    }

	}
if(count($mentors)>0){
	foreach ($mentors as $m) {
		$mname = trim(preg_replace("/[^a-z]+/","", strtolower($m->employee_name)));
		// echo $mname."<br />";
		foreach ($filesmentors as $fm){
			// echo $fm->sid."<br />";
			$fmname = trim(preg_replace("/[^a-z]+/","", strtolower($fm->ename)));
			if ($mname!=$fmname&&$fm->sid!="General"&&$fm->sid!="Enroll No") {
				// if(Assign::assign_student_to_mentor($m->employee_id, $fm->sid))
				// 	echo "Student ".$fm->sid." has been assigned to ".$m->employee_id;
				// similar_text($mname, $fmname, $percent);
			 //   if($percent>60)echo $fmname." - ".$mname." ~ ".$percent."<br />";

			 
			}
		}
		
	}
}
$unassigned = array();
$c=0;
$mentors = Mentor::find_all_mentors();
foreach ($mentors as $m) {
	if(count(Assign::find_all_students_of_mentor($m->employee_id))==0)$unassigned[++$c]=$m;
}
echo count($unassigned)." mentors data is  yet to be automated"."<br />";
foreach ($unassigned as $um) { 
	$mname = trim(preg_replace("/[^a-z]+/","", strtolower($um->employee_name)));
	// echo "<br />".$unassigned[$i];
    // echo $um->employee_id." - ".$um->employee_name."<br />";
	foreach ($filesmentors as $fm){
			// echo $fm->sid."<br />";
			$fmname = trim(preg_replace("/[^a-z]+/","", strtolower($fm->ename)));
			if ($mname!=$fmname&&$fm->sid!="General"&&$fm->sid!="Enroll No") {
				similar_text($mname, $fmname, $percent);
			   if($percent>=84){
			   	if(Assign::assign_student_to_mentor($um->employee_id, $fm->sid))
					echo "Student ".$fm->sid." has been assigned to ".$um->employee_id."<br />";
			   	// echo $fmname." - ".$mname." ~ ".$percent."<br />";
			   }

			}
		}
}

//GU02137101316 - S. Vijayalakshmi
//GU02137101329 - S. Ghosh


?>
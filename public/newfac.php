<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/initialize.php';
require('../includes/spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
require('../includes/spreadsheet-reader-master/SpreadsheetReader.php');

class Faculty
{
public $id;
public $name;
}

$c=0;
$filesmentors =  array();

$reader = new SpreadsheetReader('updatedmentors.xls');
		foreach ($reader as $row){
			$fac= new Faculty();
			$fac->id = trim($row[1]);
			$fac->name = trim($row[0]);

			if(!in_array($fac, $filesmentors)){
				$filesmentors[$c++] = $fac;
			}
		}
$c=0;
if(count($filesmentors)>0){
	foreach ($filesmentors as $m) {
		if (!Mentor::validate_mentor(trim($m->id))) {
			Mentor::insert_mentor(trim($m->id), trim($m->name));
			echo ++$c." - ".$m->name;
		    echo "<br />";
		}
		// echo ++$c." - ".$m;
		// echo "<br />";
	}
}

// for ($i=0; $i < count($dbmentors); $i++) { 
// 	// echo $dbmentors[$i]."<br />";
// }

// $c=0;
// $student = new Student();


	// foreach ($Sheets as $Index => $Name){
	// 	// echo 'Sheet #'.$Index.': '.$Name."<br />";

	// 	$reader -> ChangeSheet($Index);

		// foreach ($reader as $row){
		// 	foreach ($row as $r){
	// 			echo $row[2]."<br />";
	// 			// $student = new Student();
	// 			// 	$student->ename = $row[6];
	// 			// 	// $student->sid = $row[2];

	// 			// if(!in_array($student, $filesmentors)){
	// 			// 	$filesmentors[$c++] = $student;
	// 			// 	// echo $student->ename."<br />";
	// 			// 	// echo $student->sid."<br />";
	// 			// }
					


				
				

			// }
			// echo "<hr />";
	    //
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
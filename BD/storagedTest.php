<?php 
include dirname(__FILE__) . '/conexionBD.php';

$bd = new conexionBD();   

$result = $bd -> storedProcedure("CALL SPBUSCARPARTICIPANTEPORCEDULA(1023008030)");
echo json_encode($result);
// foreach ($result as $row) {
// 	for ($i=0; $i < count($row); $i++) { 
// 		echo $row[$i];
// 		echo "<br>";
// 	}
// }
$rows = $bd -> select("SELECT * FROM `TMATRICULA`");
	foreach ($rows as $row) {
		echo '<script language="javascript">';
		echo 'alert('.$row["Id"].')';
		echo '</script>';
		break;
	}
?>
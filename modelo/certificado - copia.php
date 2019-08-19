<?php

	$nombre = $_POST['hiddNombEstu'];  
	$idTercero = $_POST['hiddIdenEstu'];
	$codigo = $_POST['hiddcodigo'];
	$lugar = $_POST['hiddLugarExp'];
	// echo $nombre;
	// echo "hola";
	require('../includes/fpdf181/fpdf.php');
		
	if($codigo != ""){
		// $pdf=new FPDF();
		// $pdf->AddPage();
		// $pdf->SetFont('Arial','B',16);
		// $pdf->Cell(40,10,$nombre."-".$idTercero."-".$codigo);
		// // $pdf->Output();
		// $identificacion=1233;
		// $pdf->Output('certificado'.$identificacion.'.pdf','D');
		
		class PDF extends FPDF
		{
			//Cabecera de página
			function Header()
			{
				if ($_POST['hiddModulo'] == "modulo"){
				//---- VERTICAL -------
				// //Logo
				// $this->Image('../vista/images/logo-colsubsidio.png',10,8,40);
				// //Arial bold 15
				// $this->SetFont('Arial','B',15);
				// //Movernos a la derecha
				// $this->Cell(80);
				// //Título
				// //$this->Cell(30,10,'Title',1,0,'C');
				// $this->Image('../vista/images/logo-cet.png',85,8,45);
				// //Movernos a la derecha
				// $this->Cell(80);
				// //Título
				// //$this->Cell(30,10,'Title',1,0,'C');
				// $this->Image('../vista/images/logo-airbus.png',150,8,40);
				// //Salto de línea
				// $this->Ln(20);
				
				// //parte media
				// $this->SetFont('Arial','B',25);
				// //Movernos a la derecha
				// $this->Cell(190,30,"Certificado de estudios",1,2,'C');
				// //this->Ln(20);
				
				// //parte media
				// $this->SetFont('Arial','B',10);
				// //Movernos a la derecha
				// $this->Cell(190,10,"Cet extiende la presente para acreditar que",1,2,'C');
				// //this->Ln(20);
				// //---- FIN VERTICAL -------
				
					//---- HORIZONTAL -------
					//Logo
					$this->Image('../vista/images/logo-colsubsidio.png',20,11,50);
					//Arial bold 15
					$this->SetFont('Arial','B',15);
					//Movernos a la derecha
					$this->Cell(80);
					//Título
					//$this->Cell(30,10,'Title',1,0,'C');
					$this->Image('../vista/images/logo-cet.png',130,19,55);
					//Movernos a la derecha
					$this->Cell(80);
					//Título
					//$this->Cell(30,10,'Title',1,0,'C');
					$this->Image('../vista/images/logo-airbus.png',230,11,50);
					//Salto de línea
					$this->Ln(20);
					
					//parte media
					$this->SetFont('Arial','B',15);
					//Movernos a la derecha
					$this->Cell(270,20,utf8_decode("CORPORACIÓN DE EDUCACIÓN TECNOLOGICA COLSUBSIDIO - AIRBUS GROUP"),0,2,'C');
					//this->Ln(20);
					
					$this->SetFont('Arial','B',10);
					//Movernos a la derecha
					$this->Cell(270,10,utf8_decode("Autorización Oficial según resolución No. 9150 del 22 de Octubre de 2010 del Ministerio de Educación Nacional"),0,2,'C');
					//this->Ln(20);
					
					$this->SetFont('Arial','B',15);
					//Movernos a la derecha
					$this->Cell(270,10,utf8_decode("HACE CONSTAR QUE"),0,2,'C');
					//this->Ln(20);
					
					$this->SetFont('Arial','B',25);
					//Movernos a la derecha
					$this->Cell(270,20,utf8_decode($_POST['hiddNombEstu']),0,2,'C');
					
					$this->SetFont('Arial','B',15);
					//Movernos a la derecha
					$this->Cell(270,10,utf8_decode("Identificado(a) con C.C ".$_POST['hiddIdenEstu']." expedida en ".$_POST['hiddLugarExp']),0,2,'C');
					
					$this->SetFont('Arial','B',12);
					//Movernos a la derecha
					$this->Cell(270,10,utf8_decode("Asistió al módulo ".$_POST['hiddNombreMod']." correspondiente al programa de FOSFEC del Curso "),0,2,'C');
					
					$this->SetFont('Arial','B',25);
					//Movernos a la derecha
					$this->Cell(270,15,utf8_decode($_POST['hiddNombreCur']),0,2,'C');
					
					$this->SetFont('Arial','B',15);
					//Movernos a la derecha
					$this->Cell(270,15,utf8_decode("Con una duración total de ".$_POST['hiddDuracion']." HORAS"),0,2,'C');
					
					//Firma
					$this->Cell(80);
					$this->Image('../vista/images/firma.PNG',125,137,50);
					//Salto de línea
					$this->Ln(20);
					
					$this->SetFont('Arial','B',10);
					//Movernos a la derecha
					$this->Cell(270,5,"_____________________________________________",0,2,'C');
					
					$this->SetFont('Arial','B',12);
					//Movernos a la derecha
					$this->Cell(270,5,utf8_decode("BLANCA CECILIA HERNÁNDEZ GUERRERO"),0,2,'C');
					
					$this->SetFont('Arial','B',10);
					//Movernos a la derecha
					$this->Cell(270,5,utf8_decode("Secretaría Académica"),0,2,'C');
					
					$this->SetFont('Arial','B',10);
					//Movernos a la derecha
					$this->Cell(270,5,"VIGILADA",0,2,'C');
					
					$this->SetFont('Arial','B',10);
					$dia = date("d");
					$mes = date("m");
					switch ($mes) {
						case 01:
							$mes="Enero";
							break;
						case 02:
							$mes="Febrero";
							break;
						case 03:
							$mes="Marzo";
							break;
						case 04:
							$mes="Abril";
							break;
						case 05:
							$mes="Mayo";
							break;
						case 06:
							$mes="Junio";
							break;
						case 07:
							$mes="Julio";
							break;
						case 08:
							$mes="Agosto";
							break;
						case 09:
							$mes="Septiembre";
							break;
						case 10:
							$mes="Octubre";
							break;
						case 11:
							$mes="Noviembre";
							break;
						case 12:
							$mes="Diciembre";
							break;
						}
						$anio = date("Y");
						//Movernos a la derecha
						$this->Cell(270,10,utf8_decode("Dado en Bogotá D.C., a los ".$dia." días del mes de ".$mes." de ".$anio),0,2,'C');
						//---- FIN HORIZONTAL -------
				}
				
			}

			//Pie de página
			// function Footer()
			// {
				// //Posición: a 1,5 cm del final
				// $this->SetY(-15);
				// //Arial italic 8
				// $this->SetFont('Arial','I',8);
				// //Número de página
				// $this->Cell(0,10,'Pie de pagina',0,0,'C');
			// }
			
			// function datos($nombre $idTercero $codigo)
			// {
				
			// }
		}

		//Creación del objeto de la clase heredada
		$pdf=new PDF('L','mm','A4');
		$pdf->Output();
	}
		

?>

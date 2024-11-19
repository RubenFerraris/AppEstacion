<?php

	include_once 'env.php';


	include 'lib/mp-mailer/Mailer/src/PHPMailer.php';
	include 'lib/mp-mailer/Mailer/src/SMTP.php';
	include 'lib/mp-mailer/Mailer/src/Exception.php';


	// incluimos a User para poder hacer uso de la variable cargada en session
	include_once 'models/User.php';
	include_once './lib/mp-mailer/Mailer.php';

	// Inicia la sesión
	session_start();

	// motor de plantillas
	include 'lib/kiwi/Kiwi.php';  

	// para pasar variables a las plantillas
	$vars = [];

	// por defecto se va a landing
	$controlador = "landing";

	
	$slug = explode("/", $_GET['slug'])[0];

	// si pidieron una seccion lo llevamos a ella
	if(strlen($slug)!=0){
		$controlador = $slug;	
	}

	// averiguamos si existe el controlador
	if(!is_file('controllers/'.$controlador.'Controller.php')){
		$controlador = "error404";
	}

	//=== firewall

	// Listas de acceso dependiendo del estado del usuario
	$controlador_login = ["panel","logout", "abandonar","administrator"];
	$controlador_anonimo = ["landing", "login", "register","reset","recovery","verify"];

	// sesion iniciada
	if(isset($_SESSION[$_ENV['PROJECT_NAME']])){
		
		// recorre la lista de secciones no permitidas
		foreach ($controlador_anonimo as $key => $value) {
			// si esta solicitando una sección no permitida
			if($controlador==$value){
				$controlador = "panel";
				break;
			}
		}
		if (($controlador=="map"||$controlador=="administrator")&&$_SESSION[$_ENV["PROJECT_NAME"]]["user"]->email != "admin-estacion") {
			$controlador = "panel";
		}
		
	}else{ // sesión no iniciada

			// recorre la lista de secciones no permitidas
			foreach ($controlador_login as $key => $value) {
			// si esta solicitando una sección no permitida
			if($controlador==$value){
				$controlador = "landing";
				break;
			}
		}

	}
	// === fin firewall


	include 'controllers/'.$controlador.'Controller.php';

 ?>
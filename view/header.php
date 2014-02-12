<!-- Generiert den ganzen head inklusive header, wird immer am anfang geladen -->
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="/view/css/style.css"/>
		
		<?php if (!empty($currentcss)){?>
		<link rel="stylesheet" type="text/css" href="/view/css/<?php echo $currentcss?>.css"/>
		<?php }?>
		
		<!-- Script für jQuery -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		
		<!-- Scripts/CSS für Dropdownmenu -->
		<link type="text/css" rel="stylesheet" href="/view/css/jquery.dropdown.css" />
		<script type="text/javascript" src="/view/js/jquery.dropdown.js"></script>
		
		<!-- Scripts für Validation -->
		<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
        
        <!-- Scripts für das Admincenter -->
		<script type="text/javascript" src="/view/js/jquery.dataTables.js"></script>
		
		<script type="text/javascript" src="/view/js/jquery.raty.js"></script>
		
		<script type="text/javascript" src="/view/js/onstart.js"></script>
		
		<title><?php echo $title; ?></title>
	</head>
	<body>
		<div id="container">
			<header>
				<a id="logo" href="/"><img src="/view/images/<?php echo !empty($logo)? $logo: 'logo';?>.png" alt="logo"/></a>
			</header>
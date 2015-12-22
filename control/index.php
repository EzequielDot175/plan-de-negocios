<?php require_once('../../core_nufarm/libs.php');
	//error_reporting(0)
	$auth = Auth::UserAdmin();
	$pn = new PlanDeNegocios();
	//$pn->setInit();
?>
<!DOCTYPE html>
<html lang="es" ng-app="pn">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>Nufarm - Vendedor Estrella</title>
		<meta name="auth-role" content="<?php echo $auth->role ?>">
		<meta name="auth" content="<?php echo $auth->id ?>">
		<!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- CSS de Bootstrap -->
		<link href="../assets_old/bootstrap-3.3.4/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="../assets_old/bootstrap-3.3.4/css/bootstrap-social.css" rel="stylesheet" media="screen">

		<!-- CSS de font-awesome-4.3.0 para iconos sociales-->
		<link href="../assets_old/fonts/font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" media="screen">

		<!-- CSS -->
		<link href="../assets_old/css/estilos.css" rel="stylesheet" media="screen">

		<!-- GRAFICOS -->
		<script src="../assets_old/js/Chart.js"></script>
	</head>


	<body ng-controller="CtrlFilter">

		<div class="head">
			<div class="contenedor">
                 <img src="../assets_old/images/Nufarm-max-logo.png" id="Nufarm" title="Nufarm" alt="Imagen no encontrada">
                 <div class="block">
                 <img class="icon-select " src="../assets_old/images/flecha-select.png" id="Nufarm" title="Nufarm" alt="Imagen no encontrada">
                 <select class="form-control">
				  		<option>MARKETING NET</option>
				  		<option>PLAN DE NEGOCIOS</option>
				  		<option>VENDEDOR ESTRELLA</option>
					</select>
					<div class="logout">
						<p class="text-uppercase">salir</p>
						<img src="../assets_old/images/cerrar.png" id="Nufarm" title="Nufarm" alt="Imagen no encontrada">
					</div>
                </div>
            </div>
        </div>

        <div class="titulo-general">
        	<h3 class="text-uppercase">Vendedor: <?php echo $auth->nombre." ".$auth->apellido ?></h3>
        </div>


		<!-- CONTENEDOR GENERAL***********************************************************-->
		<div class="contenedor ">

			<!--base-->
			<div class="base">

				<div class="menu">
				        	<a href="#">
				            	<li class="seleccionado">FACTURACION</li>
				        	</a>
				        	<a href="#">
				        		<li class=" ">PREMIOS</li>
				        	</a>
				</div>

				<!--contenido-->
				<div class="contenido col-xs-12 col-sm-12 col-md-12 ol-lg-12">


<!--Admin -->
	<div class="admin col-xs-12 col-sm-12 col-md-12 ol-lg-12">

		<!-- contenedor A -->
		<div class="contenedor-A col-xs-12 col-sm-12 col-md-12 ol-lg-12">
			<div class="sub-contenedor">
				<div class="filtros">
				            <form ng-submit="submitFilter()">
				            <input type="hidden" name="filter">
				                  	<h3> FILTRAR POR:</h3>
				                  	<?php if($auth->role != 3): ?>
				                 	<select name="vendedor"  ng-model="vendedores"  ng-change="setClientes()" ng-show="isAdmin">
								 		<option value="">TODOS LOS VENDEDORES</option>
								 		<option value="{{v.id}}" ng-repeat="(k, v) in selVendedores">{{v.nombre}} {{v.apellido}}</option>
								 	</select>
				                 	<?php endif; ?>
				                  	<select  name="clientes" ng-model="clientes" id=""> 
										<option class="text-uppercase" value="">TODOS LOS CLIENTES</option>
										<option class="text-uppercase" value="{{v.id}}" ng-repeat="(k, v) in selClientes">{{v.strEmpresa}}</option>   
									</select>
				                    <!--<select name="date" ng-model="filter_date" id="" >
										<option value="">FACTURACION</option>
										<option value="">{{set}}</option>
									</select>-->
									<select name="date" ng-model="filter_date" ng-options="val.value as val.text for val in selFilterDate" >
										<option value="">FACTURACIÓN</option>
									</select>
				                  	<button class="button-image button-B" type="submit"><img src="../assets_old/images/ver.png" alt=""> VER RESULTADOS </button>
				            </form>
			      	</div>
			</div>
		</div>
		<!-- end / contenedor A -->

		<!-- contenedor B -->
		<div class="contenedor-B contenedor-HEAD col-xs-12 col-sm-12 col-md-12 ol-lg-12">
			<div class="sub-contenedor">

				<h3 class="titulo-A">FACTURACIÓN 2014/2015</h3>

			</div>
		</div>
		<!-- end / contenedor B -->

		<hr class="hr-SELECT">

		<!-- contenedor SELECT -->
		<div class="contenedor-SELECT col-xs-12 col-sm-12 col-md-12 ol-lg-12" ng-show="inEdit">
			<div class="sub-contenedor">


				<div class='cerrar-pantalla col-xs-12 col-sm-12 col-md-12 ol-lg-12'>
					<img src="../assets_old/images/cerrar-A.png" ng-click="inEdit = false">
				</div>

				<h3 class="titulo-B">SANCHEZ AGRONEGOCIOS S.A.</h3>

				<div class="block-resumen-A">
					<div class="block-resumen col-xs-12 col-sm-3 col-md-3 ol-lg-3">
						<div class="num">{{inEditItemData.obj_fact_clave | number:0 | reverse}}</div>
						<hr class="hr-resumen">
						 <div class="text">
							Objetivo Productos Clave
						</div>
					</div>

					<div class="block-resumen col-xs-12 col-sm-3 col-md-3 ol-lg-3">
						<div class="num num2">{{inEditItemData.avance}}%</div>
						<div class="text2">{{inEditItemData.total | number:0 | reverse}}</div>
						<hr class="hr-resumen">
						 <div class="text">
							Avance Productos Clave
						</div>
					</div>

					<div class="block-resumen col-xs-12 col-sm-3 col-md-3 ol-lg-3">
						<img class="img" src="../assets_old/images/editar.png" alt="">
						<div class="text2">{{inEditItemData.premio}}</div>
						<hr class="hr-resumen">
						 <div class="text">
							Bono 2%
						</div>
					</div>

				</div>


				<!-- Inputs -->
				<div class="inputs col-xs-12 col-sm-12 col-md-12 ol-lg-12" >

					<h3 class="titulo-A titulo-bottom">DETALLE MENSUAL</h3>

					<div class="titulo-meses titulo-meses-B col-xs-12 col-sm-12 col-md-12 ol-lg-12">
						<h3 class="item item-a">2014</h3>
						<h3 class="item item-b">2015</h3>
					</div>

    				<!-- Tabla -->
					<table class="tabla-A tabla-mes tabla-mes-vendedor"  >
						<thead>
							<tr>
								<th class="text-uppercase col-mes"></th>
								<th  ng-repeat="(key, value) in meses">
									{{key}}
								</th>
							</tr>
						</thead>
						<tbody>

							<!-- item-->
							<tr>
								<td class="sin-borde">
									P.Clave
								</td>
								<td class=" background-A text-uppercase " ng-repeat="(key, value) in meses">
									<input type="text" value="{{value.total_prod_clave}}" ng-model="meses[key].total_prod_clave" disabled>
								</td>
								

							</tr>
							<!-- end / item-->

						</tbody>
					</table>
					<!-- end / Tabla -->
					<section class="graph">
						<canvas id="canvas-nufarm" width="1000" height="300" ng-mousemove="canvas.displayinfo($event)">
							Tu navegador no soporta este grafico, Intenta usar Google Chrome o Mozilla Firefox
						</canvas>
					</section>
				</div>
				<!-- end / Inputs -->


			</div>
		</div>
		<!-- end /ontenedor SELECT -->

		<hr class="hr-SELECT">

		<!-- contenedor B -->
		<div class="contenedor-B contenedor-TABLA col-xs-12 col-sm-12 col-md-12 ol-lg-12">
			<div class="sub-contenedor">
				<!-- Tabla -->
				<table class="tabla-A format-C">
					<thead>
						<tr>
							<th class="col-1">Empresa</th>
							<th class="col-2">Objetivo Fact. Prod.<br>Clave Plan de Negocio 2014/2015</th>
							<th class="col-3">% Avance</th>
							<th class="col-4">Bono 2%</th>
							<th class="botones"></th>
						</tr>
					</thead>
					<tbody>
						<!-- item-->
								<tr ng-repeat="(key, value) in resultados">
									<td class="background-A text-uppercase col-1">
										{{value.cliente}}
									</td>
									<td class="background-B text-uppercase col-2 center">
										{{value.obj_fact_clave || 0 | number:0 | reverse}}
									</td>
									<td class="background-B text-uppercase col-3 center sub-item">
										<div class="item"><p>{{value.total_prod_clave | number:0 | reverse}}</p></div>
										<div class="item background-A violeta"><p>{{avance(value.obj_fact_clave , value.total_prod_clave )}}%</p></div>
									</td>
									<td class="background-A text-uppercase col-4 center">
										<p>
											{{premio(avance(value.obj_fact_clave , value.total_prod_clave ))}}
										</p>
									</td>

									<td class="botones">
										<img class="boton" src="../assets_old/images/editar.png" alt="" ng-click="editItem(value)">
									</td>

								</tr>
								<!-- end / item-->
					</tbody>
				</table>
				<!-- end / Tabla -->


			</div>
		</div>
		<!-- end / contenedor B -->


	</div>
	<!-- end / Admin -->





				</div>
				<!--end / contenido-->

			</div>
			<!--end / base-->

		</div>
		<!-- // CONTENEDOR GENERAL*********************************************-->


		<!-- Librería jS -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="../assets_old/bootstrap-3.3.4/js/bootstrap.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script src="../assets_old/js/eventos.js"></script>
		<script src="../js/graph.js"></script>
		<script src="../js/angular/angular.min.js"></script>
		<script src="../js/angular/app.js"></script>
		<script src="../js/controllers/filters.js"></script>
		<script src="../js/controllers/service.js"></script>
		<script src="../js/controllers/controller.js"></script>
		
	</body>
</html>
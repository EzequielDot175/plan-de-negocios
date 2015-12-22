app.controller('CtrlFilter', ['$scope','ajax','$rootScope','$filter', function(scope,ajax,root,$filter){

	scope.vendedores = "";
	scope.clientes = "";
	scope.filter_date = "";
	scope.selVendedores = [];
	scope.selClientes = [];
	scope.selFilterDate = [];
	scope.resultados = [];
	scope.facturacion_total = 0;
	scope.facturacion_prod_clave = 0;
	scope.isAdmin = false;
	scope.canEdit = false;
	scope.inEdit = false;
	scope.inEditItemData = [];
	scope.chart = [];
	scope.id_current_edit = 0;
	scope.avance_producto = 0;
	scope.accede_categoria = 0;
	scope.start_app = false;
	scope.meses = {};
	scope.canvas = new veCanvas("canvas-nufarm");

	scope.canvas.setWorkSpace({
		paddingLeft: 90,
		paddingBottom: 70,
		paddingTop: 90
	});
	scope.canvas.setColumnProperties({hColumn: 80});
	/**
	 * Scope meses
	 */
	
	scope.monthOriginal = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
	scope.monthPeriod = ["agosto", "septiembre", "octubre", "noviembre", "diciembre","enero", "febrero", "marzo"];


	/**
	 * seteo el scope
	 */
	angular.forEach(scope.monthPeriod,function(element, index){
		scope.meses[element] = {
			total : 0,
			total_prod_clave : 0,
			disabled: true,
		}
	});

	scope.periodMonthData = {
		current: null,
		next: null
	}

	var user = root.user;
	// ajax.user();
	
	if (user.role != 3) {
		scope.isAdmin = true;
	};	

	if (user.role != 3) {
		ajax.ve({method: 'vendedores', user: user},function(a){
			scope.selVendedores = a;
			// console.info('Reporting vendedores:', a);
		});
	};



	ajax.ve({method: 'periodos'}, function (a) {
		var inicio = $filter('date')(a.inicio, 'yyyy');
		var fin = $filter('date')(a.fin, 'yyyy');
		scope.selFilterDate = [{value: a.inicio + "_" + a.fin, text: "FACTURACION " + inicio + "/" + fin}];
		scope.filter_date = a.inicio + "_" + a.fin;
		scope.submitFilter();
	});

	ajax.ve({method: 'clientesPN', user: user},function(a){
		console.info('Reporting setClientes:', a);

		scope.selClientes = a;
	});


	scope.setClientes = function(){
		ajax.ve({method: 'clientes', id: scope.vendedores, user: user},function(a){
			scope.selClientes = a;
		});
	}

	scope.submitFilter = function(){


		if (scope.filter_date == "") {
			alert('Por favor ingrese un periodo primero');
			return "";
		};
		var submit = {
			cliente: scope.clientes,
			date: scope.filter_date
		};
		if (user.role != 3) {
			submit.vendedor = scope.vendedores;
		};

		scope.inEdit = false;

		ajax.ve({method: 'filterPN', params: submit, user: user},function(a){
			console.info('Reporting FILTRO:', a);
			scope.resultados = a;
			scope

		});
		
		// ajax.ve({method: 'totalByPeriod', date: scope.filter_date},function(a){
		// 	scope.facturacion_total = Math.round(a.total);
		// 	scope.facturacion_prod_clave = Math.round(a.producto_clave);
		// });

		ajax.ve({method : 'checkPeriod' , date: scope.filter_date}, function(a){
			var result = Boolean(parseInt(a));
			scope.canEdit = result;
		});

		scope.start_app = true;
	}


	scope.percentage = function(a,b){
		if (b == 0) {
			return 0;
		}else{
			var result = Math.round((parseFloat(b)/parseFloat(a))*100);
			return result;
		}
	}

	scope.avance = function(objetivo, actual){
		if(objetivo != 0){
			return Math.round( (actual / objetivo) * 100 );
		}else{
			return 0;
		}
	}

	scope.avancetotal = function(curr_total,old_total){
		
		// console.info('Reporting curr_total:', curr_total);
		// console.info('Reporting old_total:', old_total);

		// return 0;
		if (curr_total != 0 && old_total != 0) {
			return Math.round( ( parseFloat(curr_total) / parseFloat(old_total) ) * 100 );
		}else{
			return 0;
		}
	}

	scope.oldPeriod = function(a){

		// console.info('Reporting check period:', a != undefined);
		if (a != undefined) {
			return true;
		}else{
			return false;
		}
		return false;
	}

	scope.prodClave = function(curr_total, curr_prod_clave){
		if (curr_total != 0 && curr_prod_clave != 0) {
			return Math.round( (parseFloat(curr_prod_clave) / parseFloat(curr_total)) * 100 );
		}else{
			return 0;
		}
	}

	scope.premio = function(percent){
		
		if(percent >= 100){
			return 'Accede';
		}else{
			return 'No Accede';
		}



	}

	scope.editItem = function(val){

		
		scope.inEdit = true;

		var json_data = JSON.parse(val.facturacion);

		// angular.forEach(json_data,function(element, index){
				
		// 	scope.meses[scope.firstLetterLower(index)] = {
		// 		total : element.facturacion_total,
		// 		total_prod_clave : element.facturacion_prod_clave,
		// 		disabled: true
		// 	}
	
		// });
		// console.info('REPORTING COLLECTION:', val);
		scope.id_current_edit = val.id;
		scope.inEditItemData = {
			avance: scope.avance(val.obj_fact_clave, val.total_prod_clave) ,
			total: val.total_prod_clave,
			obj_fact_clave: val.obj_fact_clave,
			premio: scope.premio(scope.avance(val.obj_fact_clave , val.total_prod_clave ))
		}

		scope.setDataMonth(json_data);
		scope.graph(json_data,val.obj_fact_clave);




		// console.info('Reporting :', val);
		// scope.avance_producto = scope.avancetotal(val.total,val.ultimo_total);
		// scope.accede_categoria = scope.categoria(val.total_prod_clave, val.total , val.ultimo_total);

	}

	scope.setDataMonth = function(data){

		var date = new Date();
		var month = date.getMonth();
		/**
		 * Mes actual
		 */
		var curr_month_original = scope.monthOriginal[month];

		/**
		 * Index de los meses del periodo
		 */
		var index_curr_period = scope.monthPeriod.indexOf(curr_month_original);

		/**
		 * Guardo estos datos, mes que se esta cargando actualmente
		 */
		scope.periodMonthData.current = {
			month: curr_month_original,
			index: index_curr_period
		};
		scope.periodMonthData.next = {
			month: scope.monthPeriod[index_curr_period + 1],
			index: index_curr_period + 1
		};

		$.each(scope.meses, function(index, val) {
			var each_index_month = scope.monthPeriod.indexOf(index);
			if (each_index_month <=  index_curr_period || each_index_month <=  (index_curr_period + 1) ) {
				scope.meses[index].disabled = false;
				scope.meses[index].total = data[scope.firstLetterUpper(index)].facturacion_total;
				scope.meses[index].total_prod_clave = data[scope.firstLetterUpper(index)].facturacion_prod_clave;
				
			};
		});
	}



	scope.firstLetterUpper = function(string){
		 return string.charAt(0).toUpperCase() + string.slice(1);
	}
	scope.firstLetterLower = function(string){
		 return string.charAt(0).toLowerCase() + string.slice(1);
	}

	scope.ArraySelector = function(collection,index_sel){
		var date = new Date();
		var month = date.getMonth();
		/**
		 * Mes actual
		 */
		var curr_month_original = scope.monthOriginal[month];

		/**
		 * Index del mes actual en el periodo
		 */
		var index_curr_period = scope.monthPeriod.indexOf(curr_month_original);

		var format = [];
		var sum = 0;



		$.each(collection, function(index, val) {
			
			var mes = val.index;
			var month_each_index = scope.monthPeriod.indexOf(val.index);
			var value = val.obj[index_sel];
	
			// si es el primero
			if (month_each_index <= index_curr_period) {

				if (index > 0) {
						
					sum += value;
					format.push({value: sum, label: mes});
				
				}else{
					sum += value;
					format.push({value: value, label: mes});
				}
			}else{
				format.push({value: 0, label: mes});
			}
		});

		return format;
	}

	scope.graphObject = function(){

		var newMeses = [];
		$.each(scope.meses, function(index, val) {
			newMeses.push({obj: val, index: index});
		});

		var graphObject = {};
			graphObject.total = scope.ArraySelector(newMeses,'total');
			graphObject.prod_clave = scope.ArraySelector(newMeses,'total_prod_clave');
	
		return graphObject;
	}

	scope.updateFacturacion = function(){
		// console.info('Reporting :', scope.meses);
		// scope.updateGraph(scope.graphArray());
		ajax.ve({method: 'updateDataFacturacion', data: scope.meses ,id : scope.id_current_edit},function(a){

			/**
			 * Update datos de de la pantalla
			 */
			scope.avance_producto = scope.avancetotal(a.total,a.ultimo_total);
			scope.accede_categoria = scope.categoria(a.total_prod_clave, a.total , a.ultimo_total);

			scope.resultados.map(function(elem, index) {
				if (a.id == elem.id) {
					scope.resultados[index].total = a.total;
					scope.resultados[index].total_prod_clave = a.total_prod_clave;
					scope.resultados[index].ultimo_prod_clave = a.ultimo_prod_clave;
					scope.resultados[index].ultimo_prod_clave = a.ultimo_prod_clave;
					scope.resultados[index].ultimo_total = a.ultimo_total;
					scope.resultados[index].facturacion = a.facturacion;
				};
			})
		});


	}


	scope.updateGraph = function(array){
		
		scope.graph(array);
	}
	scope.graph = function(object,total_obj){

		var sum = 0;
		var collection = [];
		angular.forEach(object,function(obj,ind){
			sum += obj.facturacion_total;
			collection.push({percent: scope.avancetotal(sum,total_obj), value: sum});
		});




		var data = {
			label: ['','','','','','','',''],
			lastColumnCharged : scope.periodMonthData.next.index + 1,
			total: collection.reverse()
		}

		console.info('Array', data);


		//console.info("info object graph:", object);
		//console.info("info formatted object graph:", data);
		scope.canvas.data(data);
		scope.canvas.commit();
		scope.canvas.draw();
	}

				








}])
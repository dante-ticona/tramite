<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5>{{ plural }}</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<table class="table table-hover table-striped " id="divTable">
							<thead class="thead-dark">
								<tr>
									<th>#</th>
									<th></th>
									<th>NODO</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(r, index) in registros">
									<td width="10%">{{ r.nodo_id }}</td>
									<td width="15%">{{ r.nodo_codigo }}</td>
									<td width="65%">{{ r.nodo_descripcion }}</td>
									<td width="10%">
										<button type="button" class="btn btn-primary btn-circle btn-sm" v-on:click="listarPendientes(r.nodo_id)" >
											<i class="fa fa-eye white" aria-hidden="true"></i>
										</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<table class="table table-hover table-striped " id="divTable2">
							<thead class="thead-dark">
								<tr>
									<th>Funcionaria/o</th>
									<th>Código</th>
									<th>Descripción</th>
									<th>Conteo</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(r, index) in pendientes">
									<td width="40%">
										{{ r.name}}
									</td>
									<td width="20%">
										{{ r.prc_codigo }}
									</td>
									<td width="30%">
										{{ r.prc_descripcion }}
									</td>
									<td width="10%" align="right">
										{{ r.conteo}}
									</td>
								</tr>
							</tbody>
						</table>
						<br>
						<h5>Resumen</h5>
							{{ conteos }}
						<table class="table table-hover table-striped ">
							<tr v-for="(s, index) in conteos">
								<td width="30%">
									dddd {{ s }}
								</td>
								<td width="50%">
									{{ index }}
								</td>
								<td width="20%">
									{{ s }}
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
    import datatables from 'datatables';

	export default {
		name: 'nodos_trabajos',
		data() {
			return {
				plural: 'Nodos - Trabajos',
				singular: 'Nodo - Trabajo',
				registros: [],
				registro: {},
				pendientes: [],

				conteos: [],

				dataTable: null, 
				dataTable2: null
			}
		},

		mounted() {
			this.listarRegistros();

			this.dataTable = $('#divTable').DataTable({ responsive: true });
			this.dataTable2 = $('#divTable2').DataTable({ responsive: true });
		},

		methods: {
			listarRegistros() {
				let url = "api/nodos";
				axios.get(url).then(response => {
					this.registros = response.data.data; //twice data
				});
			},

			listarPendientes(nodo_id) {
				var that = this;
				that.conteos = [];
				let url = "api/procesosXNodoId/" + nodo_id;
				axios.get(url).then(response => {
					this.pendientes = response.data.data; //twice data
					this.pendientes.forEach(function (row) {
                        //row.cas_data = JSON.parse(row.cas_data);
						that.conteos[row.name] = ((that.conteos[row.name]) ? that.conteos[row.name] : 0) + row.conteo;
					});
					console.log(that.conteos);
				});
			},			
		},

		beforeUpdate: function () {
            if (this.dataTable) {
                this.dataTable.destroy();
            }

            if (this.dataTable2) {
                this.dataTable2.destroy();
            }
        },

        updated: function () {
            this.dataTable = $("#divTable").DataTable({
                responsive: true,
                order: [[1, "desc"]],
				"language": {
					"lengthMenu": "Desplegar _MENU_ registros por página",
					"zeroRecords": "Sin registros",
					"info": "Página _PAGE_ de _PAGES_",
					"infoEmpty": "No existen registros disponibles",
					"infoFiltered": "(filtrados de _MAX_ registros en total)",

				    "search": "Buscar",
					"paginate": {
    				    "first":      "Primera",
    				    "last":       "Última",
    				    "next":       "Siguiente",
    				    "previous":   "Anterior"
    				},

				}
			});

            this.dataTable2 = $("#divTable2").DataTable({
                responsive: true,
                order: [[1, "desc"]],
				"language": {
					"lengthMenu": "Desplegar _MENU_ registros por página",
					"zeroRecords": "Sin registros",
					"info": "Página _PAGE_ de _PAGES_",
					"infoEmpty": "No existen registros disponibles",
					"infoFiltered": "(filtrados de _MAX_ registros en total)",

				    "search": "Buscar",
					"paginate": {
    				    "first":      "Primera",
    				    "last":       "Última",
    				    "next":       "Siguiente",
    				    "previous":   "Anterior"
    				},

				}
			});
        }

	}
</script>
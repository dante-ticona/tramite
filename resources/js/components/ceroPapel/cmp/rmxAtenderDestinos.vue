<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-md-10">
						<h5>{{ plural }} - {{ crrId }}</h5>
					</div>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-hover table-striped table-responsive" id="divTableNodos">
					<thead class="thead-dark">
						<tr>
							<th scope="col">#</th>
							<th></th>
							<th scope="col">CODIGO</th>
							<th scope="col">DESCRIPCION</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(r, index) in registrosNodos">
							<td scope="row">{{ r.nodo_id }}</td>
							<td scope="row">
								<button type="button" class="btn btn-primary btn-circle" title="Derivar"
									v-on:click="nuevoDestino(r.nodo_id)" data-toggle="modal"
									data-target="#modalNuevoDestino">
									<i class="fa fa-check white" aria-hidden="true"></i>
								</button>
							</td>
							<td>{{ r.nodo_codigo }}</td>
							<td>{{ r.nodo_descripcion }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['crrId'],
		data() {
			return {
				//usrId: window.Laravel.usr_id,
				plural: "NODOS",
				singular: "Nodo",
				seleccionado: "",
				errores: [],
				registrosNodos: [],
				registroNodo: {},
			};
		},

		mounted() {
			console.log("entro mounted Destinos ", this.crrId);
			this.listarRegistrosNodos();
		},

		methods: {
			listarRegistrosNodos() {
				let url = "api/nodos";
				axios.get(url).then((response) => {
					this.registrosNodos = response.data.data; //twice data
					console.log("Nodos >>>>>", this.registrosNodos);
				});
			},

			doVer(index) {
				this.registroNodo = this.registrosNodos[index];
			},

			doLimpiar() {
				this.registroNodo = {};
			},
		},

	};
</script>
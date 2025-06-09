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
							<th scope="col">No Copia</th>
							<th scope="col">Código</th>
							<th scope="col">Descripción</th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="cargando">
							<td colspan="5" valign="center">Cargando ...</td>
						</tr>
						<tr v-for="(r, index) in registrosCopias">
							<td scope="row">{{ r.nodo_id }}</td>
							<td scope="row">
								<button type="button" class="btn btn-danger btn-circle" title="Eliminar"
									v-on:click="doVer(index)" data-toggle="modal" data-target="#modalEliminarCopia">
									<i class="fa fa-trash white" aria-hidden="true"></i>
								</button>
							</td>
							<td>{{ r.copia_nro_copia }}</td>
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
		name: "atenderCopias",
		props: ['crrId'],
		data() {
			return {
				//usrId: window.Laravel.usr_id,
				plural: "COPIAS",
				singular: "Copia",
				cargando: true,
				seleccionado: "",
				errores: [],
				registrosCopias: [],
				registroCopia: {},
			};
		},

		mounted() {
			console.log("entro copias de ", this.crrId);
			setTimeout(() => {
				this.listarRegistrosCopias();
			}, 1500);
		},

		methods: {
			listarRegistrosCopias() {
				let url = "api/copiasXCrrId/" + this.crrId;
				axios.get(url).then((response) => {
					this.registrosCopias = response.data.data; //twice data
					console.log("Copias >>>>>", this.registrosCopias);
					this.cargando = !this.cargando;
				});
			},

			doVer(index) {
				this.crrId = 200;

//				this.registroCopia = this.registrosCopias[index];
			},

			doLimpiar() {
				this.registroCopia = {};
			},
		},

	};
</script>
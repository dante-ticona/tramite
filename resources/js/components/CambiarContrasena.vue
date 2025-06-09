<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5>{{ plural }}</h5>
					<div class="form-group row">
						<label for="email" class="col-sm-4 col-form-label text-md-right">Usuario</label>
						<div class="col-md-3 input-group mb-3">     
							<input id="email" type="text" v-model="this.nom_usuario" class="form-control" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label text-md-right">Contraseña</label>
						<div class="col-md-3 input-group mb-3">     
							<input id="password" type="password" v-model="filtro.contrasena"
								@input="contrasenaValida = (filtro.confirmarContrasena === filtro.contrasena)"
								class="form-control" placeholder="Contraseña">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label text-md-right">Confirmar Contraseña</label>
						<div class="col-md-3 input-group mb-3"> 
							<input id="passwordConfirmar" type="password" v-model="filtro.confirmarContrasena" 
								@input="contrasenaValida = (filtro.confirmarContrasena === filtro.contrasena) && true"
								class="form-control" placeholder="Contraseña nueva">
						</div>
						<div><span v-if="!contrasenaValida" class="text-danger">Las contraseñas no coinciden.</span></div>
					</div>
							
					<div class="form-group row">	
						<div class="col-md-2">
							<button class="form-control btn btn-primary" :disabled="!isValidUpdate()" data-toggle="modal" data-target="#modalConfirmar">
								<i class="fa fa-search white" aria-hidden="true"></i> Enviar
							</button>
						</div>
					</div>
			</div>
		</div>

		<!-- modalConfirmar -->
        <div class="modal fade" id="modalConfirmar" tabindex="-1" role="dialog" aria-labelledby="modalConfirmar"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="exampleModalLabel">Confirmar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>¿Confirma cambiar contraseña?</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-success" @click="actualizarUsuario()" data-dismiss="modal">Si,
                            confirmar</button>
                    </div>
                </div>
            </div>
        </div>

	</div>
</template>

<script>
export default {
	name: 'servicios',
	data() {
		return {
			usrId: window.Laravel.usr_id,
			plural: 'Cambiar Contraseña',	
			nom_usuario: '',
			success: false,
			filtro: { contrasena: '', confirmarContrasena: ''},
			contrasenaValida: true,
			dataTable: null,
		}
	},

	mounted() {
		this.listarUsuario(this.usrId);
	},

	methods: {
		async openModal(htc_id) {
			window.open(`${htc_id}`, '_blank');
		},

		listarUsuario(usrId){
			const datos = {usrId: usrId};
			axios.get('api/obtenerUsuario/'+usrId)
			.then(response => {
				this.nom_usuario =response.data.data[0].nom_usuario;
			})
		},

		 actualizarUsuario() {
			if (this.contrasenaValida) {
			const datos = {
				contrasena: this.filtro.contrasena,
				usr_id: this.usrId
			};
			//this.$router.push("logout");
			//this.$router.go(-1);
			axios.post('api/actualizarUsuario',datos)
			.then(response => {
				console.log(response.data);
				this.success = true;
				this.filtro.contrasena = '';
      			this.filtro.confirmarContrasena = '';
					})
			.catch(error => {
				console.error('Error al cambiar la contraseña', error);
			});
			} else {
			console.error('Las contraseñas no coinciden!');
			}
		},

		isValidUpdate() {
			return (this.filtro.contrasena.trim() !== '' &&
				this.filtro.confirmarContrasena === this.filtro.contrasena);
		}
	},
}
</script>
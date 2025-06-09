<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <label for="listarUsuarios" class="form-label"
                            >Selecciona un Usuario:</label
                        >
                        <v-select
                            :options="
                                listaUsuarios.map((usuario) => ({
                                    label: usuario.email,
                                    value: usuario,
                                }))
                            "
                            v-model="vSelectedNodeId"
                            placeholder="Seleccionar un Usuario..."
                        ></v-select>
                    </div>
                    <div class="col-md-2">
                        <button
                            class="form-control btn btn-primary"
                            @click="enviarUsuario()"
                        >
                            <i
                                class="fa fa-search white"
                                aria-hidden="true"
                            ></i>
                            Buscar
                        </button>
                    </div>

                    <div class="col-md-3">
                        <!--<Hijo :valorRecibido="NumeroAEnviar" @escucharHijo="VariableHijo"></Hijo>-->
                    </div>
                </div>
            </div>
            <MisCasos :valorRecibido="NumeroAEnviar" />
        </div>
    </div>
</template>

<script>
import MisCasos from "./MisCasos.vue";
import vSelect from "vue-select";

export default {
    data() {
        return {
            NumeroInsertadoPadre: null,
            NumeroAEnviar: null,
            VariableRecibida: null,
            listaUsuarios: [],
            vSelectedNodeId: null,
        };
    },
    components: {
        MisCasos,
        "v-select": vSelect,
    },
    methods: {
        EnviarHijo() {
            this.NumeroAEnviar = this.NumeroInsertadoPadre;
        },
        VariableHijo(value) {
            this.VariableRecibida = value;
        },
        listarProcesos() {
            let url = "api/users";
            axios.get(url).then((response) => {
                this.listaUsuarios = response.data.data;
            });
        },
        enviarUsuario() {
            this.NumeroAEnviar = this.vSelectedNodeId.value;
        },
    },
    mounted() {
        this.listarProcesos();
    },
};
</script>
<style>
@import "vue-select/dist/vue-select.css";
</style>

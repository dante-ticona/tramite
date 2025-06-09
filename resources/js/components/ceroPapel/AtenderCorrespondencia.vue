<template>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-1">
            <router-link :to="'/miCorrespondencia'">
              <button
                type="button"
                class="btn btn-success btn-circle"
                title="Volver"
              >
                <i class="fa fa-backward white" aria-hidden="true"></i>
              </button>
            </router-link>
          </div>
          <div class="col-md-3">
            <h5>{{ plural }}</h5>
          </div>
          <div class="col-md-6">
            <!-- button v-if="registro.crr_usr_id == usr_id" type="button" class="btn btn-primary" title="Derivar" @click="getModificar">
							<i class="fa fa-pen white" aria-hidden="true"></i> Modificar
						</button -->
          </div>
          <div class="col-md-2">
            <button
              type="button"
              class="btn btn-danger"
              title="Derivar"
              data-toggle="modal"
              data-target="#modalDerivar"
            >
              <i class="fa fa-paper-plane white" aria-hidden="true"></i> Derivar
            </button>
          </div>
        </div>
        <div class="row justify-content-left">
          <div class="col-md-6">
            <div class="row col-md-12">
              <div class="col-md-6">
                <label>Tipo Correspondencia:</label>
                <select
                  v-model="registro.crr_tcrr_id"
                  class="form-control"
                  :disabled="disable"
                >
                  <option v-for="s in tipos" v-bind:value="s.tcrr_id">
                    {{ s.tcrr_codigo }} - {{ s.tcrr_descripcion }}
                  </option>
                </select>
              </div>
              <div class="col-md-6">
                <label>CITE:</label>
                <input
                  v-model="registro.crr_data.crr_cite"
                  class="form-control"
                  :disabled="disable"
                />
              </div>
              <div class="col-md-12">
                <label>Asunto:</label>
                <input
                  v-model="registro.crr_data.crr_asunto"
                  class="form-control"
                  :disabled="disable"
                />
              </div>
              <div v-if="registro.crr_tcrr_id == 2" class="col-md-2">
                <label>CI:</label>
                <input
                  v-model="registro.crr_data.crr_asunto"
                  class="form-control"
                  :disabled="disable"
                />
              </div>
              <div v-if="registro.crr_tcrr_id == 2" class="col-md-6">
                <label>Nombre Completo:</label>
                <input
                  v-model="registro.crr_data.crr_asunto"
                  class="form-control"
                  :disabled="disable"
                />
              </div>
              <div v-if="registro.crr_tcrr_id == 2" class="col-md-2">
                <label>Celular:</label>
                <input
                  v-model="registro.crr_data.crr_asunto"
                  class="form-control"
                  :disabled="disable"
                />
              </div>
              <div v-if="registro.crr_tcrr_id == 2" class="col-md-2">
                <label>Teléfono:</label>
                <input
                  v-model="registro.crr_data.crr_asunto"
                  class="form-control"
                  :disabled="disable"
                />
              </div>
            </div>
            <div class="row col-md-12">
              <hr />
            </div>
            <div class="row col-md-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-md-10">
                      <h5>{{ plural }}</h5>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <table
                    class="table table-hover table-striped table-responsive"
                    id="divTableNodos"
                  >
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
                      <tr v-for="(r, index) in copias">
                        <td scope="row">{{ r.nodo_id }}</td>
                        <td scope="row">
                          <button
                            type="button"
                            class="btn btn-danger btn-circle"
                            title="Eliminar"
                            v-on:click="delCopia(r.copia_id)"
                          >
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
          </div>

          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-3">
                    <h5>NODOS</h5>
                  </div>
                  <div class="col-md-6">
                    <input
                      type="search"
                      class="form-control"
                      placeholder="Texto a buscar"
                      v-model="buscado"
                    />
                  </div>
                  <div class="col-md-2">
                    <button
                      type="button"
                      class="btn btn-success"
                      title="Buscar"
                      @click="buscarNodo()"
                    >
                      <i class="fa fa-search white" aria-hidden="true"></i>
                      Buscar
                    </button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <table
                  class="table table-hover table-striped table-responsive"
                  id="divTableNodos"
                >
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">#</th>
                      <th></th>
                      <th scope="col">CODIGO</th>
                      <th scope="col">DESCRIPCION</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(r, index) in pajar">
                      <td scope="row">{{ r.nodo_id }}</td>
                      <td scope="row">
                        <button
                          type="button"
                          class="btn btn-primary btn-circle"
                          title="Derivar"
                          v-on:click="setCopia(r.nodo_id)"
                        >
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
        </div>
      </div>
    </div>

    <!-- modalDerivar -->
    <div
      class="modal fade"
      id="modalDerivar"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalDerivar"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-danger">
            <h5 class="modal-title" id="exampleModalLabel">
              Derivar la {{ singular }}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row justify-content-left">
              <div class="col-md-12">
                <label>Asunto: {{ registro.crr_data.crr_asunto }}</label>
              </div>
            </div>
            <div class="row justify-content-left">
              <!-- aqui poner for-->
              <div class="col-md-12">
                <label>aqui listar cada DESTINO de COPIAS</label>
                <label>¿ Confirma derivar {{ singular }} ?</label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              No
            </button>
            <button
              type="button"
              class="btn btn-danger"
              @click="derivar()"
              data-dismiss="modal"
            >
              Sí, derivar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "atenderCorrespondencia",
  props: ["crr_id"],
  data() {
    return {
      plural: "Atender Correspondencia",
      singular: "Correspondencia",
      registro: { crr_data: {} },
      tipos: [],
      nodos: [],
      copias: [],
      nodo_id: 0,
      usr_id: window.Laravel.usr_id,
      buscado: "",
      pajar: [],
      disable: true,
    };
  },

  mounted() {
    this.getCorrespondencia();
    this.listarTipos();
    this.listarNodos();
    this.listarCopias();
  },

  methods: {
    getCorrespondencia() {
      let url = "api/correspondenciaXId/" + this.crr_id;
      axios.get(url).then((response) => {
        this.registro = response.data.data[0]; //twice data
        this.registro.crr_data = JSON.parse(this.registro.crr_data);
      });
    },

    listarTipos() {
      let url = "api/tiposCorrespondencia";
      axios.get(url).then((response) => {
        this.tipos = response.data.data; //twice data
      });
    },

    listarNodos() {
      let url = "api/nodos";
      axios.get(url).then((response) => {
        this.nodos = response.data.data; //twice data
        this.pajar = this.nodos;
      });
    },

    listarCopias() {
      let url = "api/copiasXCrrId/" + this.crr_id;
      axios.get(url).then((response) => {
        this.copias = response.data.data; //twice data
      });
    },

    setCopia(id) {
      let url = "api/crrSetCopia/" + this.crr_id + "/" + id;
      axios.post(url).then((response) => {
        this.listarCopias();
      });
    },

    delCopia(id) {
      let url = "api/crrDelCopia/" + id;
      axios.post(url).then((response) => {
        this.listarCopias();
      });
    },

    derivar() {
      //  Copiar a históricos
      let url = "api/crrSetHistorico/" + this.crr_id;
      axios.post(url).then((response) => {
        // Derivar y borrar copias
        url = "api/crrDerivar/" + this.crr_id + "/" + this.nodo_id;
        axios.post(url);
      });
    },

    buscarNodo() {
      this.pajar = this.nodos.filter((nodo) => {
        return nodo.nodo_descripcion
          .toUpperCase()
          .includes(this.buscado.toUpperCase());
      });
    },

    getModificar() {
      this.disable = !this.disable;
    },
  },
};
</script>
<style>
@import 'vue-select/dist/vue-select.css';

.custom-width-select {
    width: 70%;
    /* O ajusta el ancho según tus necesidades */
}
</style>

<style scoped>
@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }

    50% {
        transform: rotate(180deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.rotate-animation {
    animation: rotate 2s linear infinite;
}

.modalNoti1 {
    position: fixed;
    top: 90%;
    right: 10px;
    margin: 0;
    width: 220px;
}

.modalNoti3 {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    background-color: rgba(255, 255, 255, 0.9);
    max-width: 400px;
}

.modalnoti2 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.notification-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    color: #333333;
    text-align: center;
}

.noti-icon {
    margin-right: 10px;
    background-color: rgba(40, 167, 69, 0.2);
    padding: 5px;
    border-radius: 50%;
}

.noti-icon i {
    color: #28a745;
}

.notificacion-menssage {
    display: flex;
    align-items: center;
    padding-left: 10px;
    border-left: 2px solid #ccc;
    font-size: 15px;
    font-family: 'Roboto', sans-serif;
}

/* Estilo para el mensaje de carga */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    /* Fondo semi-transparente */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.loading-message {
    background-color: #fff;
    /* Fondo del mensaje */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    /* Sombra ligera */
    text-align: center;
}

.loading-message img {
    width: 50px;
    /* Ajusta el tamaño del ícono según sea necesario */
    height: 50px;
    margin-bottom: 10px;
    /* Espacio entre el ícono y el texto */
}

.tabla-con-borde-transparente {
    border-collapse: collapse;
    margin: 0;
    /* Para fusionar los bordes de las celdas */
}

.tabla-con-borde-transparente th,
.tabla-con-borde-transparente td {
    border: 1px solid transparent;
    margin: 0;
    padding: 0px;
    /* Establece el borde transparente */
}

.navegacion {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #1a1e23;
}

.selectRegistros {
    padding: 0.5rem 1rem;
    font-family: "Helvetica Neue", Arial, sans-serif;
    font-size: 15px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    background-color: #fff;
    line-height: 1.5;
    color: #212529;
}

.btnEAvance {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    font-weight: 400;
    text-align: center;
    line-height: 1.5;
    background-color: #007bff;
    color: #fff;
    user-select: none;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btnEAvance:hover {
    color: #fff;
    background-color: #0056b3;
    border-color: #0056b3;
}

.btnEAvance.anterior {
    margin-right: 5px;
}

.btnEAvance.siguiente {
    margin-left: 5px;
}

@media only screen and (max-width: 768px) {
    .navegacion {
        flex-direction: column;
        align-items: center;
    }

    .btnEAvance {
        margin: 5px 0;
    }

    .selectRegistros {
        align-content: center;
    }

    .btnEAvance.anterior {
        margin-right: 0;
    }

    .btnEAvance.siguiente {
        margin-left: 0;
    }
}

@media only screen and (max-width: 1135px) {
    .navegacion {
        flex-direction: column;
        align-items: center;
    }

    .btnEAvance {
        margin: 5px 0;
    }

    .selectRegistros {
        align-content: center;
    }

    .btnEAvance.anterior {
        margin-right: 0;
    }

    .btnEAvance.siguiente {
        margin-left: 0;
    }
}

@media only screen and (max-width: 486px) {
    .navegacion {
        flex-direction: column;
        align-items: center;
    }

    .btnEAvance {
        margin: 5px 0;
    }

    .selectRegistros {
        align-content: center;
    }

    .btnEAvance.anterior {
        margin-right: 0;
    }

    .btnEAvance.siguiente {
        margin-left: 0;
    }
}
</style>

<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3">
                        <h5>{{ plural }}</h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2" style="padding-right: 5px;">
                        <button class="form-control btn btn-warning" @click="swListarArchivados()">
                            <i class="fa" :class="swArchivo ? 'fa-archive' : 'fa-folder'" :aria-hidden="true"></i>
                            {{ !swArchivo ? "Ir a Archivo" : "Ir a Pendientes" }}
                        </button>
                    </div>

                    <div class="col-md-2" style="padding-left: 5px;">
                        <button class="form-control btn btn-primary" @click="recargar()" :disabled="swArchivo">
                            <i class="fas fa-sync-alt white" aria-hidden="true"></i> Refrescar
                        </button>
                    </div>

                    <div class="col-md-4">
                        <label for="nodos" class="form-label">Seleccionar nodo:</label>
                        <v-select :options="nodos.map(nodo => ({ label: nodo.nodo_descripcion, value: nodo.nodo_id }))"
                            v-model="vSelectedNodeId" @input="handleNodeChange"
                            placeholder="Seleccionar Nodo"></v-select>
                    </div>

                    <div class="col-md-3">
                        <label for="nodos" class="form-label">Seleccionar Actividad:</label>
                        <select class="form-control" v-model="selectOptionNodo" @change="handleSelectChange">
                            <option value="---------------" selected>Seleccione</option>
                            <option v-for="(act, index) in listaNodosG" :key="index" :value="act">
                                {{ act.orden }} - {{ act.descripcion }} - {{ act.codigop }}
                            </option>
                        </select>
                    </div>

                    <template v-if="nodos.some(nodo => nodo.nodo_id === 57)">
                        <button class="btn btn-success ml-2" @click="exportarExcelGNLPendientes()" title="Exportar a Excel">
                            <i class="fa fa-file-excel"></i> Pendientes GNL
                        </button>
                    </template>
                </div>
            </div>

            <div class="row mt-3 justify-content-end">
                <div class="col-md-2 offset-md-5">
                    <button id="btnFirmaMasiva" class="form-control btn btn-info" @click="firmaMasivaToken()">
                        <i class="fas fa-file-signature white" aria-hidden="true"></i> Firma Masiva
                    </button>
                </div>
                <div class="col-md-2">
                    <button id="btnFirmaMasivaRubrica" class="form-control btn btn-info"
                        @click="firmaMasivaTokenRubrica()">
                        <i class="fas fa-file-signature white" aria-hidden="true"></i> Firma Masiva Rubrica
                    </button>
                </div>
                <div class="col-md-2">
                    <button v-if="selectOptionNodo.tipoMasivo == 1" id="btnDerivacionMasivaLegal"
                        class="form-control btn btn-danger" @click="derivacionMasivaNodo()" type="button"
                        title="Derivar" data-toggle="modal" data-target="#modalDerivarMultiple">
                        <i class="fas fa-user-friends white" aria-hidden="true"></i> Derivación Masiva Nodo
                    </button>
                </div>
                <div class="col-md-2">
                    <button id="btnDerivacionMasivaLegal" class="form-control btn btn-danger"
                        @click="derivacionMasivaLegal()">
                        <i class="fas fa-user-friends white" aria-hidden="true"></i> Derivación Masiva
                    </button>
                </div>
                <div class="col-md-2">
                    <button id="btnAsignacionMasiva" class="form-control btn-success" @click="asignacionMasiva()"
                        v-if="selectedNodeId == 16 || selectedNodeId == 108"> Asignación Masiva 5
                        <i class="fas fa-people-arrows white" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="container-fluid">
                    <div v-if="!swArchivo">
                        <div class="navegacion">
                            <select name="porPagina" @change="listarRegistros" v-model="RegistrosXPagina"
                                class="selectRegistros">
                                <option v-for="n in opcionesRegistrosPorPagina" :key="n" :value="n">{{ n }}</option>
                            </select>

                            <div class="select-container">
                                <label style="color: white; font-size: 1.2em;">{{ drawPaginatorText() }}</label>
                            </div>

                            <div class="select-container">

                                <button @click="anteriorPagina()" class="btnEAvance"> <i class="fa fa-arrow-left white"
                                        aria-hidden="true"></i> Anterior</button> &nbsp; &nbsp;

                                <select name="paginacion" @change="listarRegistros()" v-model="PaginaActual"
                                    class="selectRegistros">
                                    <option v-for="pagina in paginas" :key="pagina" :value="pagina">
                                        {{ pagina }}
                                    </option>
                                </select>

                                &nbsp; &nbsp; <button @click="siguientePagina()" class="btnEAvance"> Siguiente <i
                                        class="fa fa-arrow-right white" aria-hidden="true"></i> </button>
                            </div>

                            <!-- <div class="buscarEAvance">
								<label style="color: white;">Buscar: </label>
								<input type="search" v-model="buscarRegistro" @keyup.enter="bRegistros()"
									@input="" class="selectRegistros" placeholder="Buscar">
								<button @click="convertirAMayusculas(); bRegistros()" class="btnEAvance">Buscar</button>
							</div> -->

                            <!-- <div class="buscarEAvance">
								<label style="color: white;">Buscar: </label>
								<input type="search" v-model="buscarRegistro"
									@keyup.enter="convertirAMayusculas(); bRegistros()" @input="vacio(); convertirAMayusculas(); bRegistros()" class="selectRegistros"
									placeholder="Buscar">
							</div> -->

                            <template v-if="nodos.some(nodo => nodo.nodo_id === 57 || nodo.nodo_id === 15 || nodo.nodo_id === 114)">
                                <div class="col-md-2">
                                    <label style="color: white;">Filtro por días:</label>
                                    <select class="form-control glowing-select" v-model="selectOptionDia" @change="handleSelectChange" style="width: 100%; min-width: 130px;">
                                        <option value="---------------" selected> Mostrar Todo </option>
                                        <option value="A" style="background-color: #28a745; color: white;">
                                            Próximos a vencer (día 9 al 7)
                                        </option>
                                        <option value="B" style="background-color: #02A2B9; color: white;">
                                            Vencidos (10 días en adelante)
                                        </option>
                                    </select>
                                </div>
                            </template>

                            <div class="buscarEAvance">
                                <label style="color: white;">Buscar: </label>
                                <input type="search" v-model="buscarRegistro"
                                    @keyup.enter="convertirAMayusculas(); bRegistros()" class="selectRegistros"
                                    placeholder="Buscar">
                            </div>

                        </div>

                        <div class="table-responsive background-container">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Nro</th>
                                        <th scope="col">#</th>
                                        <th scope="col">
                                            <input type='checkbox' @click='checkAll()' v-model='isCheckAll'>
                                        </th>
                                        <th> </th>
                                        <th scope="col">PROCESO / ACTIVIDAD</th>
                                        <th scope="col">NRO. CASO</th>
                                        <th scope="col">CAMPOS CLAVE</th>
                                        <th scope="col">REGISTRADO</th>
                                        <th scope="col">ESTADO</th>
                                        <th scope="col">NOTIFICACIONES</th>
                                        <th scope="col">DEPARTAMENTO</th>
                                    </tr>
                                </thead>

                                <tbody v-if="registros">
                                    <tr v-for="(r, index) in registros" :key="r.id">
                                        <td width="3%" scope="row">{{ (index + 1) }}</td>
                                        <td width="3%" scope="row">{{ r.cas_id }}</td>
                                        <td width="3%" scope="row">
                                            <input type="checkbox"
                                                v-if="(r.tact_codigo !== 'B' || r.tact_codigo !== 'I' || r.tact_codigo !== 'F') && (r.cas_estado !== 'A')"
                                                class="green" aria-hidden="true" @change="handleCheckboxChange(r)"
                                                v-model="r.checked">
                                        </td>
                                        <td width="17%" scope="row">
                                            <span v-if="r.cas_usr_id === usrId || r.cas_estado === 'A'">
                                                <button
                                                    v-if="r.cas_estado === 'T' && r.cas_cod_id.includes('JUB1582/') && mostrarEstadoDerivacion(r.cas_data_valores) == ''"
                                                    type="button" class="btn btn-success btn-circle "
                                                    title="Actualizar Datos" v-on:click="antesDerivar(index)">
                                                    <i class="fa fa-history white" aria-hidden="true"></i>
                                                </button>

                                                <button type="button" class="btn btn-primary btn-circle"
                                                    v-if="r.cas_estado === 'T'" title="Atender"
                                                    v-on:click="redirigir(r)">
                                                    <i class="fa fa-pen white" aria-hidden="true"></i>
                                                </button>

                                                <button v-if="r.cas_estado === 'A' && (!es_atc && !es_supervisor)"
                                                    type="button" class="btn btn-warning btn-circle" title="Tomar"
                                                    v-on:click="doVer(index)" data-toggle="modal"
                                                    data-target="#modalTomar">
                                                    <i class="fa fa-lock white" aria-hidden="true"></i>
                                                </button>

                                                <button v-if="r.cas_estado === 'T' && (!es_atc && !es_supervisor)"
                                                    type="button" class="btn btn-success btn-circle" title="Liberar"
                                                    v-on:click="doVer(index)" data-toggle="modal"
                                                    data-target="#modalLiberar">
                                                    <i class="fa fa-lock-open white" aria-hidden="true"></i>
                                                </button>

                                                <button v-if="r.cas_estado === 'T' || r.cas_estado === 'E'"
                                                    type="button" class="btn btn-primary btn-circle" title="Histórico"
                                                    v-on:click="doHistorico(r.cas_id, r.cas_padre_id)"
                                                    data-target="#modalHistorico">
                                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                                </button>

                                                <button
                                                    v-if="r.cantidad_casos_asociados_activos === 0 && r.cas_estado === 'T' && (!r.cas_cod_id.includes('JUB1582/') || mostrarEstadoDerivacion(r.cas_data_valores) != '')"
                                                    type="button" class="btn btn-danger btn-circle " title="Derivar"
                                                    v-on:click="doLimpiar(index)" data-toggle="modal"
                                                    data-target="#modalDerivar">
                                                    <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                                                </button>

                                                <button v-if="r.caso_hijo_tipo_caso_heredaro == 'true'" type="button"
                                                    class="btn btn-circle" title="Tramites Legal"
                                                    v-on:click="doLegal(r.cas_id, r.cas_padre_id)" data-toggle="modal"
                                                    data-target="#modalLegal" style="background-color: #B06218;">
                                                    <i class="fa fa-balance-scale hammer" style="color: white;"
                                                        aria-hidden="true"></i>
                                                </button>
                                            </span>
                                        </td>

                                        <td>
                                            <strong>[{{ r.prc_data.prc_codigo }}] {{ r.prc_data.prc_descripcion }}</strong><br>
                                            <span class="badge badge-dark">{{ r.act_data.act_orden }}</span> - {{ r.act_data.act_descripcion }} <br>

                                            <template v-if="r.cas_data.AS_TIPO_EAP && typeof r.cas_data.AS_TIPO_EAP === 'string' && r.cas_data.AS_TIPO_EAP.trim() !== '' && !r.cas_cod_id.includes('LEGAL/')">
                                                <span class="badge" style="background: linear-gradient(45deg, #EDCD4E, #f2dd9b); font-size: 0.8em;">
                                                    <strong class="break-text"> {{ r.cas_data.AS_TIPO_EAP }} </strong>
                                                </span>
                                            </template>

                                            <template v-else-if="r.cas_cod_id.includes('LEGAL/')">
                                                <div class="expandable">
                                                    <div style="display: flex; align-items: center;" @click="toggle(r.cas_id)">
                                                        <span class="badge" style="background: linear-gradient(45deg, #EDCD4E, #f2dd9b); font-size: 0.8em; margin-right: 5px;">
                                                            <strong class="break-text"> {{ r.cas_data_valores.find(item => item.frm_campo === 'AS_TIPO_EAP_LEGAL')?.frm_value_label }} </strong>
                                                        </span>
                                                        <button
                                                            style="background-color: #B06218; color: white; border: none; padding: 2px 4px; border-radius: 2px; cursor: pointer; font-size: 0.8em;"
                                                            :title="expandedItems[r.cas_id] ? 'Contraer' : 'Expandir'">
                                                            <i :class="expandedItems[r.cas_id] ? 'fa fa-chevron-up' : 'fa fa-chevron-down'" style="font-size: 0.8em;"></i>
                                                        </button>
                                                    </div>

                                                    <transition name="fade">
                                                        <div v-if="expandedItems[r.cas_id]" class="expandable-content">
                                                            <ul>
                                                                <li>
                                                                    <span class="badge" style="background: linear-gradient(45deg, #EDCD4E, #f2dd9b); font-size: 0.8em;">
                                                                        {{ r.cas_data_valores.find(item => item.frm_campo === 'AS_SUB_SOLICITUD')?.frm_value_label }}
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <span class="badge" style="background: linear-gradient(45deg, #EDCD4E, #f2dd9b); font-size: 0.8em;">
                                                                        {{ r.cas_data_valores.find(item => item.frm_campo === 'AS_TIPO_EAP')?.frm_value_label }}
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </transition>
                                                </div>
                                            </template>

                                            <span v-if="r.cas_estado === 'A'" class="badge badge-success">LI</span>
                                            <span v-else-if="r.cas_estado === 'T'" class="badge badge-success">AS</span>
                                            <span v-else-if="r.cas_estado === 'E'" class="badge badge-danger">E</span>
                                            <span v-else class="badge badge-success">{{ r.cas_estado }}</span>
                                        </td>
                                        <td>
                                            {{ r.cas_cod_id }} <br>
                                            <template v-if="mostrarEstadoGeneracionEAP(r.cas_data_valores)">
                                                <span class="badge"
                                                    style="background: linear-gradient(45deg, #17A2B8, #6fd3e2); font-size: 0.8em;">
                                                    <strong v-if="mostrarEstadoGeneracionEAP(r.cas_data_valores) === 'M'">MANUAL</strong>
                                                    <strong v-else-if="mostrarEstadoGeneracionEAP(r.cas_data_valores) === 'A'"> AUTOMATICO </strong>
                                                    <strong v-else>{{ mostrarEstadoGeneracionEAP(r.cas_data_valores) }}
                                                    </strong>
                                                </span>
                                            </template><br>

                                            <template v-if="mostrarEstadoDerivacion(r.cas_data_valores)">
                                                <span class="badge"
                                                    style="background: linear-gradient(45deg, #17A2B8, #6fd3e2); font-size: 0.8em;">
                                                    <strong>{{ mostrarEstadoDerivacion(r.cas_data_valores) }} </strong>
                                                </span>
                                                <p>De usuario:
                                                    <strong>
                                                        {{ r.antepenultimo_usuario }}
                                                    </strong>
                                                </p>
                                            </template>

                                            <template v-if="r.cas_cod_id.includes('LEGAL/')">
                                                <hr></hr>
                                                <div class="d-flex align-items-center">
                                                    <button
                                                        type="button"
                                                        class="btn btn-primary btn-circle d-flex align-items-center justify-content-center text-white"
                                                        title="Legal"
                                                        :style="{
                                                            display: 'inline-flex',
                                                            alignItems: 'center',
                                                            justifyContent: 'center',
                                                            gap: '5px',
                                                            backgroundColor: r.dias_habiles_transcurridos <= 9 ? 'rgba(40, 167, 69, 0.2)' : r.dias_habiles_transcurridos <= 10 ? 'rgba(2, 162, 185, 0.2)' : 'rgba(220, 53, 69, 0.2)',
                                                            border: 'none',
                                                            padding: '10px',
                                                            borderRadius: '50px'
                                                        }"
                                                        data-toggle="modal" data-target="#modalDiasTranscurridos">
                                                        <i
                                                            class="fa fa-gavel"
                                                            :style="{ color: r.dias_habiles_transcurridos <= 9 ? '#28a745' : r.dias_habiles_transcurridos <= 10 ? '#02A2B9' : '#dc3545' }"
                                                        ></i>
                                                    </button>
                                                    <span
                                                        class="badge ml-2"
                                                        :class="{
                                                            'badge-success': r.dias_habiles_transcurridos <= 9,
                                                            'badge-info': r.dias_habiles_transcurridos > 9 && r.dias_habiles_transcurridos <= 10,
                                                            'badge-danger': r.dias_habiles_transcurridos > 10
                                                        }"
                                                        style="color: #ffffff; font-weight: bold;"
                                                    >
                                                        <strong>{{ r.dias_habiles_transcurridos }}</strong> Dias Transcurridos
                                                    </span>
                                                </div>
                                            </template>
                                        </td>
                                        <td>
                                            <span v-html="r.cas_data.cas_nombre_caso"></span>
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar" style="color:#274690"></i> {{ r.cas_registrado.substr(0, 10) }} <br> <i class="fas fa-clock" style="color:#274690"></i>
                                            {{ r.cas_registrado.substr(10, 5) }} <br>
                                            <hr>
                                            <i class="fas fa-calendar" style="color:#297373"></i> {{ r.cas_modificado ? r.cas_modificado.substr(0, 10) : '-' }}
                                            <br> <i class="fas fa-clock" style="color:#297373"></i> {{ r.cas_modificado ? r.cas_modificado.substr(10, 5) : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-warning"><strong>{{ r.est_codigo }}</strong></span>
                                        </td>
                                        <td>
                                            <button
                                                v-if="r.cas_nodo_id === 18 || r.cas_nodo_id === 19 || r.cas_nodo_id === 20"
                                                type="button" class="btn btn-success btn-circle" title="Notificaciones"
                                                @click="notificacionesMC(r.cas_id)">
                                                <i class="fa fa-bell" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                        <td> {{ r.cas_data.cas_departamento }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <template v-if="registros.length === 0">
                                <tr>
                                    <td colspan="11" class="text-center" style="font-size: 1.2em; color: red;">
                                        <strong>No se encontraron datos</strong>
                                    </td>
                                </tr>
                            </template>
                        </div>
                    </div>
                    <div v-if="swArchivo">
                        <div class="navegacion">
                            <select name="porPagina" @change="listarRegistros" v-model="RegistrosXPagina"
                                class="selectRegistros">
                                <option v-for="n in opcionesRegistrosPorPagina" :key="n" :value="n">{{ n }}</option>
                            </select>

                            <div class="select-container">
                                <button @click="anteriorPagina()" class="btnEAvance"> <i class="fa fa-arrow-left white"
                                        aria-hidden="true"></i> Anterior </button> &nbsp; &nbsp;

                                <select name="paginacion" @change="listarRegistros()" v-model="PaginaActual"
                                    class="selectRegistros">
                                    <option v-for="pagina in paginas" :key="pagina" :value="pagina">
                                        {{ pagina }}
                                    </option>
                                </select>
                                &nbsp; &nbsp; <button @click="siguientePagina()" class="btnEAvance"> Siguiente <i
                                        class="fa fa-arrow-right white" aria-hidden="true"></i></button>
                            </div>

                            <div class="buscarEAvance">
                                <label style="color: white;">Buscar: </label>
                                <input type="search" v-model="buscarRegistro"
                                    @keyup.enter="convertirAMayusculas(); bRegistros()" class="selectRegistros"
                                    placeholder="Buscar">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <!-- <table class="table table-hover table-striped table-responsive" id="divTable"> -->
                            <table class="table table-bordered table-striped">
                                <thead cl ass="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th> </th>
                                        <th scope="col">PROCESO / ACTIVIDAD</th>
                                        <th scope="col">No. CASO</th>
                                        <th scope="col">CAMPOS CLAVE</th>
                                        <th scope="col">REGISTRADO</th>
                                        <th scope="col">ESTADO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(r, index) in archivos">
                                        <td width="3%" scope="row">{{ r.cas_id }}</td>
                                        <td width="17%" scope="row">
                                            <button type="button" class="btn btn-success btn-circle"
                                                title="desarchivar">
                                                <i class="fa fa-lock-open white" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <strong>{{ r.prc_data.prc_descripcion }}</strong><br>
                                            <span class="badge badge-dark">{{ r.act_data.act_orden }}</span> -
                                            {{ r.act_data.act_descripcion }}
                                        </td>
                                        <td>
                                            {{ r.cas_data.cas_nro_caso }} / {{ r.cas_data.cas_gestion }}
                                        </td>
                                        <td>
                                            <span v-html="r.cas_data.cas_nombre_caso"></span>
                                        </td>
                                        <td>{{ r.cas_registrado.substr(0, 16) }}</td>
                                        <td v-html="getEstado(r.cas_estado)"> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- #modalNotificaciones -->
        <div class="modal fade" id="modalNotificaciones" tabindex="-1" role="dialog"
            aria-labelledby="modalNotificaciones" aria-hidden="true">
            <div class="modal-dialog modal-dialog-right modalNoti1" role="document"
                style="max-width: 500px; margin: 0;">
                <div class="modal-content modalNoti3 rounded shadow-lg">
                    <div class="modal-body">
                        <div class="notification-item">
                            <div class="noti-icon">
                                <i class="fa fa-bell fa-2x" aria-hidden="true"></i>
                            </div>

                            <div class="notificacion-menssage">
                                Casos no Leidos: {{ notificacion }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- -------------- -->

        <!-- modalRubrica -->
        <div class="modal fade" id="modalRubrica" tabindex="-1" role="dialog" aria-labelledby="modalRubrica"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Firma ATC </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-2">
                                <label for="cmbToken">TOKEN:</label>
                                <div class="d-flex">
                                    <select id="cmbToken" v-model="token" class="form-control" style="width: 350px">
                                        <option value="" disabled>Seleccione</option>
                                        <option v-for="token in storeToken" :value="token.slot">{{ token.dispositivo }}
                                        </option>
                                    </select>

                                    <button type="button" class="btn btn-success ml-2" @click="actualizarToken"> <i
                                            class="fas fa-address-card"></i> Actualizar Token</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="firmado_pin">PIN:</label>
                            <div class="input-group">
                                <input :type="showPassword ? 'text' : 'password'" class="form-control" id="firmado_pin"
                                    v-model="pin" @keyup.enter="actualizar" />
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"
                                        @click="cambioVisibilidadPassword">
                                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                                <div style="margin-left: 5px;"></div>
                                <button type="button" class="btn btn-primary" @click="actualizar"> <i
                                        class="fas fa-key"></i>
                                    Autenticarse</button>
                            </div>
                        </div>

                        <br />
                        <div class="form-group">
                            <label for="cmbCertificado">CERTIFICADOS:</label>
                            <br />
                            <select id="cmbCertificado" v-model="selectedCertificado" class="form-control" required>
                                <option value="" disabled>Seleccione</option>
                                <option v-for="certificado in storeCertificados" :value="certificado.alias">
                                    {{ certificado.titular }} - {{ certificado.t }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="firmado_archivo">ARCHIVO:</label>
                            <input type="file" id="firmado_archivo" name="firmado_archivo" class="form-control"
                                @change="subirPdfParafirmar($event)" required />
                        </div>

                        <!--<br />

						<div class="col-md-8">
							<label for="firmado_archivo">ARCHIVO:</label>
							<input type="file" id="firmado_archivo" name="firmado_archivo" class="form-control" @change="handleFileSelected" required />
						</div>
						<div class="col-md-8">
							<label for="numeroPaginas">Número de Páginas:</label>
							<span id="numeroPaginas">{{ numero_de_pagina }}</span>
						</div>-->
                        <!--<button type="button" class="btn btn-primary" @click="btnVerDocumento()"><font color=black; style="color:#000000;"><b> Rubricar </b></font></button>
					 Botón o método para acceder al modal de VisorPDF
						<button @click="mostrarModalVisorPDF">Abrir Modal de VisorPDF</button>-->

                        <!-- Incluir el componente VisorPDF -->
                        <VisorPDF ref="visorPDFComponent" /> <!-- Aquí se incluye el componente VisorPDF -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="btnVerDocumento3()"
                            data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" @click="handleFileSelected">Rubricar</button>
                        <!--<button type="button" class="btn btn-warning" @click="tomarCaso($event)"
							data-dismiss="modal">Si, tomar</button>-->
                    </div>
                </div>
            </div>
        </div>
        <!-- modalTomar -->
        <div class="modal fade" id="modalTomar" tabindex="-1" role="dialog" aria-labelledby="modalTomar"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Tomar {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <table class="table table-responsive tabla-con-borde-transparente" id="tabla_tomar">
                                    <tr>
                                        <td colspan="4"><label>Caso: </label><span>&nbsp;&nbsp;&nbsp;</span><span
                                                v-html="registro.cas_data.cas_cod_id"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 3%;"><label>CUA:</label></td>
                                        <td style="width: 15%;"><span v-html="registro.cas_data.AS_CUA"></span></td>
                                        <td style="width: 10%;"><label>Nombre Completo :</label> </td>
                                        <td style="width: 25%;"><span
                                                v-html="registro.cas_data.AS_PRIMER_NOMBRE + ' ' + registro.cas_data.AS_SEGUNDO_NOMBRE + ' ' + registro.cas_data.AS_PRIMER_APELLIDO + ' ' + registro.cas_data.AS_SEGUNDO_APELLIDO"></span>
                                        </td>
                                    </tr>
                                </table>
                                <label>¿ Confirma tomar el {{ singular }} ?</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-warning" @click="tomarCaso($event)"
                            data-dismiss="modal">Si,
                            tomar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalLiberar -->
        <div class="modal fade" id="modalLiberar" tabindex="-1" role="dialog" aria-labelledby="modalLiberar"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="exampleModalLabel">Liberar el {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>{{ registro.cas_data.cas_nombre_caso }}</label>
                                <label>¿ Confirma liberar el {{ singular }} ?</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-success" @click="liberarCaso($event)"
                            data-dismiss="modal">Si,
                            liberar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modalSubirArchivos -->
        <div class="modal fade" id="modalSubirArchivos" tabindex="-1" role="dialog" aria-labelledby="modalSubirArchivos"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="exampleModalLabel">Derivar el {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <table class="table table-responsive tabla-con-borde-transparente"
                                    id="tabla_documento_form">
                                    <tr>
                                        <td colspan="4"><label>Caso: </label><span>&nbsp;&nbsp;&nbsp;</span><span
                                                v-html="registro.cas_data.cas_cod_id"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>CUA:</label></td>
                                        <td><span v-html="registro.cas_data.AS_CUA"></span></td>
                                        <td colspan="2" rowspan="2" style="width: 25%;"> <label><img ref="imagen"
                                                    style="width: 500px; height: 300;"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Nombre Completo :</label> </td>
                                        <td>
                                            <span
                                                v-html="registro.cas_data.AS_PRIMER_NOMBRE + ' ' + registro.cas_data.AS_SEGUNDO_NOMBRE + ' ' + registro.cas_data.AS_PRIMER_APELLIDO + ' ' + registro.cas_data.AS_SEGUNDO_APELLIDO">
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td>
                                            <button v-if="habilitarFirmaPad" @click="navegarAVistaLaravel"
                                                class="btn btn-success  float-right">Ir a Firmar </button>
                                        </td>
                                    </tr>
                                </table>

                                <table class="table table-hover table-striped table-responsive"
                                    id="tabla_documento_form">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro</th>
                                            <th>Nombre del formulario </th>
                                            <th>Firma ATC</th>
                                            <th>Documento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="impresiones.length > 0">
                                            <tr v-for="(r, index) in impresiones">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ r.imp_nombre }}</td>
                                                <td align="left">
                                                    <button v-if="!habilitarFirmaPad && r.firma == 0" type="button"
                                                        class="btn btn-primary btn-circle" title="Firma Token ATC"
                                                        v-on:click="tokenSlot(r.imp_id, r.imp_nombre, r.imp_tipo, r.imp_nombre, index);"
                                                        data-toggle="modal">
                                                        <i class="fa fa-file-signature white" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                                <td align="left">
                                                    <button v-if="!habilitarFirmaPad && r.firma != 0" type="button"
                                                        class="btn btn-danger btn-circle"
                                                        @click="verDocumento(r.doc_id, r.url_documento)">
                                                        <i class="fa fa-print white" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                        <!-- Si no hay registros -->
                                        <template v-else>
                                            <tr>
                                                <td colspan="3" class="text-center">No hay registros disponibles
                                                </td>
                                            </tr>
                                        </template>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!--<button type="button" class="btn btn-danger btn-circle "  title="Derivar" v-on:click="derivarCasoForzado(d)" data-dismiss="modal">
												<i class="fa fa-paper-plane white" aria-hidden="true"></i>
										</button>-->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" @click="derivarCaso($event)" data-dismiss="modal"
                            v-if="paralelos.length <= 1 && siguiente.act_data.act_tipo_derivacion != 'FORZADA'">Sí,
                            derivar
                        </button>

                        <button type="button" class="btn btn-warning" @click="derivarCasoParalelo($event)"
                            data-dismiss="modal"
                            v-if="paralelos.length > 1 && siguiente.act_data.act_tipo_derivacion != 'FORZADA'">Sí,
                            derivar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalDerivar  SIP-->
        <div class="modal fade" id="modalDerivar" tabindex="-1" role="dialog" aria-labelledby="modalDerivar"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="exampleModalLabel">Derivar el {{ singular }} </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <table class="table table-responsive tabla-con-borde-transparente" id="tabla_derivar">
                                    <tr>
                                        <td colspan="4"><label>Caso: </label><span>&nbsp;&nbsp;&nbsp;</span>
                                            <span v-html="registro.cas_data.cas_cod_id"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 3%;"><label>CUA:</label></td>
                                        <td style="width: 15%;"><span v-html="registro.cas_data.AS_CUA"></span></td>
                                        <td style="width: 10%;"><label>Nombre Completo :</label> </td>
                                        <td style="width: 25%;"><span v-html="this.nombre_completo_"></span>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 3%;">Nro</th>
                                            <th style="width: 25%;">Nombre del formulario</th>
                                            <th style="width: 25%;">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <div v-if="showLoader" class="espaciado"></div>

                                        <div v-if="showLoader" class="loader-container">
                                            <div class="loader-wrapper">
                                                <div class="loader"></div>
                                                <span class="loader-text">TramiteSip</span>
                                                <span class="loading-text">Cargando...</span>
                                            </div>
                                        </div>

                                        <!-- {{ impresiones }} -->

                                        <template v-if="impresiones.length > 0">
                                            <tr v-for="(r, index) in impresiones"  @click="onRowClick(r, index)">
                                                <td>{{ index + 1 }}</td>
                                                <!--	<label v-if="r.imp_tipo_firma == 'Firma Digital'"
														for="'pdf_' + index" class="btn btn-success"
														style=" margin: 0; ">
														<input :id="'pdf_' + index" type="file" name="file"
															accept=".pdf"
															@change="subirPdf($event, index, r.imp_nombre, r.imp_tipo, r.imp_nombre, index);"
															style="position: absolute; opacity: 0; width: 2%; height: 2%; cursor: pointer;">
														<i style="font-size:20px; " class="fas fa-file-import"></i>
													</label>-->
                                                <td>{{ r.imp_nombre }}</td>
                                                <td>
                                                    <button v-if="r.imp_tipo_firma == 'Firma Manual'" type="button"
                                                        class="btn btn-success" title="Visualiar Documento"
                                                        @click="doImprimir1(r.imp_id, r.imp_nombre, r.imp_tipo, false)"
                                                        data-toggle="modal" data-target="#modalPrevisualizar">
                                                        <i class="fa fa-print white" aria-hidden="true"></i>
                                                    </button>

                                                    <button v-if="r.imp_tipo_firma == 'Firma Manual'" type="button"
                                                        class="btn btn-primary " title="Firma Token documento"
                                                        v-on:click="loadDocumentLogic(r);firmaToken(r.imp_id, r.imp_nombre, r.imp_tipo, r.imp_nombre, index, r);"
                                                        data-toggle="modal">
                                                        <i class="fa fa-file-signature white" aria-hidden="true"></i>
                                                    </button>

                                                    <button v-if="r.imp_tipo_firma == 'Firma Manual' && r.firma != 0"
                                                        type="button" class="btn btn-danger" title="Firma Token"
                                                        v-on:click="descargarPdf(r.imp_id, r.imp_nombre, r.imp_tipo, r.imp_nombre, index);"
                                                        data-toggle="modal" data-target="#modalPrevisualizar">
                                                        <i class=" fa fa-download pull-right" aria-hidden="true"></i>
                                                    </button>

                                                    <label v-if="r.imp_tipo_firma == 'Firma Digital'"
                                                        for="'pdf_' + index" class="btn btn-success"
                                                        style=" margin: 0; ">
                                                        <input :id="'pdf_' + index" type="file" name="file"
                                                            accept=".pdf"
                                                            @change="subirPdf($event, index, r.imp_nombre, r.imp_tipo, r.imp_nombre, index);"
                                                            style="position: absolute; opacity: 0; width: 2%; height: 2%; cursor: pointer;">
                                                        <i style="font-size:20px; " class="fas fa-file-import"></i>
                                                    </label>
                                                    <button v-if="r.imp_tipo_firma == 'Firma Digital'"
                                                        class="btn btn-primary btn-xs"
                                                        @click="verPdf(index, r.imp_nombre, r.imp_tipo, r.imp_nombre, index);"
                                                        data-toggle="modal" data-target="#modalPrevisualizar">
                                                        <i class="fa fa-eye white" aria-hidden="true"></i>
                                                    </button>

                                                    <button v-if="r.imp_tipo_firma == 'Firma Contrato'" type="button"
                                                        class="btn btn-primary " title="Firma Token documento"
                                                        v-on:click="loadDocumentLogic(r);firmaToken(r.imp_id, r.imp_nombre, r.imp_tipo, r.imp_nombre, index, r);"
                                                        data-toggle="modal">
                                                        <i class="fa fa-file-signature white" aria-hidden="true"></i>
                                                    </button>

                                                    <button v-if="r.imp_tipo_firma == 'Firma Contrato' && r.firma != 0 && r.doc_firmado == 1"
                                                        type="button" class="btn btn-danger" title="Ver Documento"
                                                        v-on:click="descargarPdf(r.imp_id, r.imp_nombre, r.imp_tipo, r.imp_nombre, index);"
                                                        data-toggle="modal" data-target="#modalPrevisualizar">
                                                        <i class=" fa fa-download pull-right" aria-hidden="true"></i>
                                                    </button>

                                                </td>
                                            </tr>
                                        </template>
                                        <!-- Si no hay registros -->
                                        <template v-else>
                                            <tr>
                                                <td v-if="showLoader !== true" colspan="3" class="text-center">
                                                    No hay registros disponibles
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <label v-if="siguiente && siguiente.act_data">Siguiente Actividad: {{
                                    siguiente.act_data.act_tipo_derivacion }} </label>
                                <table v-if="paralelos.length <= 1"
                                    class="table table-hover table-striped table-responsive">
                                    <tr>
                                        <td><span class="badge badge-dark">{{ siguiente.act_data.act_orden }}</span>
                                        </td>
                                        <td>{{ siguiente.act_data.act_descripcion }}</td>
                                        <td><label>{{ siguiente.act_data.act_duracion_horas_maximo }}
                                                hora(s)</label>
                                        </td>
                                        <td><label>{{ siguiente.act_data.act_duracion_horas_minimo }}
                                                hora(s)</label>
                                        </td>
                                    </tr>
                                </table>
                                <table v-if="paralelos.length > 1"
                                    class="table table-hover table-striped table-responsive">
                                    <tr v-for="(s, index) in paralelos">
                                        <td><span class="badge badge-dark">{{ s.siguiente_data.act_data.act_orden
                                        }}</span></td>
                                        <td>{{ s.siguiente_data.act_data.act_descripcion }}</td>
                                        <td><label>{{ s.siguiente_data.act_data.act_duracion_horas_maximo }}
                                                hora(s)</label>
                                        </td>
                                        <td><label>{{ s.siguiente_data.act_data.act_duracion_horas_minimo }}
                                                hora(s)</label>
                                        </td>
                                    </tr>
                                </table>

                                <table
                                    v-if="siguiente.act_data.act_tipo_derivacion == 'FORZADA' && registro.act_data.act_orden != '100' && this.habilitarDerivacion">
                                    <!--<table	v-if="siguiente.act_data.act_tipo_derivacion == 'FORZADA' && registro.act_data.act_orden != '100' ">-->
                                    <tr>
                                        <td>
                                            <label>Derivación Forzada</label><br />
                                        </td>
                                        <td>
                                            <!-- <label>SUPERVISORES DE AGENCIA</label> -->
                                            <table>
                                                <tr>
                                                    <th>Acción</th>
                                                    <th>Destinatario</th>
                                                </tr>
                                                <tr v-for="(d, index) in supervisores">
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-circle "
                                                            title="Derivar" v-on:click="derivarCasoForzado(d)"
                                                            data-dismiss="modal">
                                                            <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ d.nom_usuario }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table
                                    v-if="siguiente.act_data.act_tipo_derivacion == 'UNITARIA' && registro.act_data.act_orden != '100' && this.habilitarDerivacion && this.habilitarDerivacion2">
                                    <tr>
                                        <td>
                                            <label>Derivación Unitaria</label><br />
                                        </td>
                                        <td>
                                            <!-- <label>SUPERVISORES DE AGENCIA</label> -->
                                            <table>
                                                <tr>
                                                    <th>Acción</th>
                                                    <th>Destinatario</th>
                                                </tr>
                                                <tr v-for="(d, index) in userNodo">
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-circle "
                                                            title="Derivar"
                                                            v-on:click="funderivarCasoForzadoUnitaria(d); resetearFirmaDocDesp()"
                                                            data-dismiss="modal">
                                                            <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ d.nom_usuario }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table
                                    v-if="siguiente.act_data.act_tipo_derivacion == 'REGIONAL' && registro.act_data.act_orden != '100' && this.habilitarDerivacion2">
                                    <tr>
                                        <td>
                                            <label>Derivación Unitaria REGIONAL</label><br />
                                        </td>
                                        <td>
                                            <!-- <label>SUPERVISORES DE AGENCIA</label> -->
                                            <table>
                                                <tr>
                                                    <th>Acción</th>
                                                    <th>Destinatario</th>
                                                </tr>
                                                <tr v-for="(d, index) in userNodo">
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-circle "
                                                            title="Derivar"
                                                            v-on:click="funderivarCasoForzadoUnitaria(d)"
                                                            data-dismiss="modal">
                                                            <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ d.nom_usuario }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <label v-if="siguiente.act_data.act_tipo_derivacion != 'FORZADA'">¿ Confirma derivar
                                    el {{ singular }} ? </label><!--{{  registro.act_data.act_orden}}-->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar
                            Derivación</button>
                        <button type="button" class="btn btn-danger" @click="derivarCasoUnion($event)"
                            data-dismiss="modal"
                            v-if="siguiente.act_data.act_tipo_derivacion == 'UNION' && this.habilitarDerivacion2"
                            :disabled="siguiente.act_data.act_tipo_derivacion == null">Sí, derivar</button>
                        <button type="button" class="btn btn-danger" @click="derivarCaso($event); resetearFirmaDocDesp()" data-dismiss="modal"
                            v-if="paralelos.length <= 1 && siguiente.act_data.act_tipo_derivacion != 'FORZADA' && siguiente.act_data.act_tipo_derivacion != 'UNION' && siguiente.act_data.act_tipo_derivacion != 'REGIONAL' && siguiente.act_data.act_tipo_derivacion != 'UNITARIA' && registro.act_data.act_orden != '100' && this.habilitarDerivacion2"
                            :disabled="siguiente.act_data.act_tipo_derivacion == null">Sí, derivar</button>
                        <button type="button" class="btn btn-warning" @click="derivarCasoParalelo($event); "
                            data-dismiss="modal"
                            v-if="paralelos.length > 1 && siguiente.act_data.act_tipo_derivacion != 'FORZADA' && siguiente.act_data.act_tipo_derivacion != 'UNION' && siguiente.act_data.act_tipo_derivacion != 'UNITARIA' && siguiente.act_data.act_tipo_derivacion != 'REGIONAL' && registro.act_data.act_orden != '100' && this.habilitarDerivacion2"
                            :disabled="siguiente.act_data.act_tipo_derivacion == null">Sí, derivar</button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal"
                            @click="derivarCasoEnmienda($event)"
                            v-if="registro.act_data.act_orden == '100' && this.habilitarDerivacion2">Sí,
                            derivar
                            enmienda
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modalDerivarMultiple Nodo  SIP-->
        <div v-if="showModalDerivarMultiple" class="modal fade" id="modalDerivarMultiple" tabindex="-1" role="dialog"
            aria-labelledby="modalDerivarMultiple" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="exampleModalLabel">Derivar Multiple</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            @click="closeModalDerivacionMasiva()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label v-if="siguiente && siguiente.act_data">Siguiente Actividad: {{
                                    siguiente.act_data.act_tipo_derivacion }} </label>
                                <table v-if="paralelos.length <= 1"
                                    class="table table-hover table-striped table-responsive">
                                    <tr>
                                        <td><span class="badge badge-dark">{{ siguiente.act_data.act_orden }}</span>
                                        </td>
                                        <td>{{ siguiente.act_data.act_descripcion }}</td>
                                        <td><label>{{ siguiente.act_data.act_duracion_horas_maximo }}
                                                hora(s)</label>
                                        </td>
                                        <td><label>{{ siguiente.act_data.act_duracion_horas_minimo }}
                                                hora(s)</label>
                                        </td>
                                    </tr>
                                </table>
                                <!-- ################################### 2025-01-21 send the email or sms configured begin -->
                                <!-- 2025-01-23 setup the same condition on the javascript side  -->
                                <div v-if="showCheckboxSendEmailSms(registro.act_data.act_orden, siguiente.act_data.act_orden)"
                                    class="checkbox-container">
                                    <input type="checkbox" v-model="isCheckedSendEmailSms" class="custom-checkbox"
                                        id="pulse-checkbox" />
                                    <label for="pulse-checkbox" class="checkbox-label">
                                        <span class="checkbox-custom"></span>
                                        <span class="checkbox-text">
                                            <slot>Enviar correo y/o sms al momento de la derivacion.</slot>
                                        </span>
                                    </label>
                                </div>

                                <!-- ################################### 2025-01-21 send the email or sms configured end -->
                                <table>
                                    <tr>
                                        <td><label>Derivación </label><br /></td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <th>Acción</th>
                                                    <th>Destinatario </th>
                                                </tr>
                                                <tr v-for="(d, index) in userNodo">
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-circle "
                                                            title="Derivar" v-on:click="derivarMultipleUsuario(d)"
                                                            data-dismiss="modal">
                                                            <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ d.email }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <label v-if="siguiente.act_data.act_tipo_derivacion != 'FORZADA'">¿ Confirma derivar el
                                    {{
                                        singular }} ? </label><!--{{  registro.act_data.act_orden}}-->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click="closeModalDerivacionMasiva()">Cancelar Derivación
                        </button>
                        <button type="button" class="btn btn-danger" @click="derivarCasoUnion($event)"
                            data-dismiss="modal"
                            v-if="siguiente.act_data.act_tipo_derivacion == 'UNION' && this.habilitarDerivacion2"
                            :disabled="siguiente.act_data.act_tipo_derivacion == null">Sí, derivar
                        </button>
                        <button type="button" class="btn btn-danger" @click="derivarCaso($event)" data-dismiss="modal"
                            v-if="paralelos.length <= 1 && siguiente.act_data.act_tipo_derivacion != 'FORZADA' && siguiente.act_data.act_tipo_derivacion != 'UNION' && siguiente.act_data.act_tipo_derivacion != 'UNITARIA' && registro.act_data.act_orden != '100' && this.habilitarDerivacion2"
                            :disabled="siguiente.act_data.act_tipo_derivacion == null">Sí, derivar
                        </button>
                        <button type="button" class="btn btn-warning" @click="derivarCasoParalelo($event)"
                            data-dismiss="modal"
                            v-if="paralelos.length > 1 && siguiente.act_data.act_tipo_derivacion != 'FORZADA' && siguiente.act_data.act_tipo_derivacion != 'UNION' && siguiente.act_data.act_tipo_derivacion != 'UNITARIA' && registro.act_data.act_orden != '100' && this.habilitarDerivacion2"
                            :disabled="siguiente.act_data.act_tipo_derivacion == null">Sí, derivar
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal" @click="derivarCasoEnmienda($event)"
                            v-if="registro.act_data.act_orden == '100' && this.habilitarDerivacion2">Sí, derivar enmienda
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div v-if="pdfSrc === ''"> no muestra nada </div>
		<div v-else>
			<embed :src="sanitizedPdfSrc" type="application/pdf" width="100%" height="300">
		  </div> -->
        <!-- modal Previzualizar PDF -->
        <div class="modal fade" id="modalPrevisualizar" tabindex="-1" role="dialog" aria-labelledby="modalPrevisualizar"
            aria-hidden="true">
            <div class="modal-dialog lg" role="document" style="max-width: 40%; ">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Mostrar PDF</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="pdfSrc === ''"> Cargando ... </div>
                        <div v-else>
                            <div>
                                <!--<a :href="pdfData" :download="registro.cas_data.cas_nombre_caso+this.nombre_formulario+'.pdf'">Descargar PDF</a>-->
                            </div>
                            <!--<embed :src="getSanitizedPdfSrc()" type="application/pdf" width="100%" height="800px">-->
                            <div>
                                <button type="button" class="btn btn-primary" @click="previousPage"
                                    :disabled="currentPage === 1">Anterior</button>
                                <span>Página {{ currentPage }} de {{ totalPage }}</span>
                                <button type="button" class="btn btn-primary" @click="nextPage"
                                    :disabled="currentPage === totalPage">Siguiente</button>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                <button type="button" class="btn btn-primary"
                                    @click="downloadPDF(registro.cas_data.cas_nombre_caso)">Descargar PDF</button>
                            </div>
                            <canvas id="pdfCanvas" style="max-width: 150%; max-height: 800px;"></canvas>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalHistorico con LEGAL GESTORA -->
        <div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="modalHistorico"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width:55%;">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Historico {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Caso:</label> {{ id_caso }} <br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro</th>
                                            <th>Actividad / ESTADO</th>
                                            <th>Nodo</th>
                                            <th>Modificado</th>
                                            <th>Usuario</th>
                                            <th>Estado de Derivación</th>
                                            <th>Descripción</th>
                                            <th>Listado de Documentos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(h, index) in historico">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ (JSON.parse(h.act_data)).act_orden + " - " +
                                                (JSON.parse(h.act_data)).act_descripcion }} <br>
                                                <span class="badge badge-warning"><strong>{{ h.est_codigo }}</strong>
                                                </span>
                                                <span v-if="h.htc_cas_cod_id.includes('LEGAL/')"
                                                    :class="{ 'badge': true, 'badge-warning': true }"
                                                    :style="'background-color: #C97322;'"><strong>{{ h.htc_cas_cod_id
                                                    }}</strong> </span>
                                            </td>
                                            <td>{{ h.nodo_descripcion }}</td>
                                            <td>{{ h.htc_cas_modificado }}</td>
                                            <td>{{ h.name }}</td>
                                            <td>{{ (JSON.parse(h.htc_cas_data)).ESTADO_DERIVACION }}</td>
                                            <td>{{ (JSON.parse(h.htc_cas_data)).DESCRIPCION_DERIVACION }}</td>
                                            <td align="center">
                                                <button type="button" class="btn btn-primary btn-circle"
                                                    title="Histórico" v-on:click="doDocumentoPdf(h.htc_id)"
                                                    data-toggle="modal" data-target="#modalDocumentoPdf">
                                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                                </button>
                                                <button
                                                    v-if="h.htc_cas_estado === 'E' && (JSON.parse(h.htc_cas_data)).ESTADO_DERIVACION === 'OBSERVADO'"
                                                    type="button" class="btn btn-warning btn-circle"
                                                    title="Observaciones UCPP"
                                                    v-on:click="observacionesUCPP(JSON.parse(h.htc_cas_data).OBSERVACIONES)"
                                                    data-toggle="modal" data-target="#modalObservaciones"
                                                    :style="{ backgroundColor: '#60f172', borderColor: '#60f172' }">
                                                    <i class="fa fa-comment" aria-hidden="true"></i>
                                                </button>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <HistoricoLegalComplementoComponent
                            :casId="casIdForLegalFlow"
                            :casCodId="id_caso"
                            ></HistoricoLegalComplementoComponent>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL DE LEGAL -->
        <div class="modal fade" id="modalLegal" tabindex="-1" role="dialog" aria-labelledby="esos" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary" style="background-color: #944b12 !important;">
                        <h5 class="modal-title" id="exampleModalLabel">Información Legal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Caso:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Nro Trámite</th>
                                            <th>Estado</th>
                                            <th>Descripción derivación</th>
                                            <th>Documento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="respuestaLegal.length > 0">
                                            <tr v-for="(h, index) in respuestaLegal">
                                                <td align="center"><span class="badge badge-dark">{{ index + 1 }}</span>
                                                </td>
                                                <td>
                                                    {{ h.cas_cod_id }}
                                                </td>
                                                <td v-html="getEstado(h.cas_estado)"> </td>
                                                <td> {{ JSON.parse(h.cas_data).DESCRIPCION_DERIVACION }}</td>
                                                <td>
                                                    <!-- {{ h.htc_id }} -->
                                                    <button type="button" class="btn btn-primary btn-circle"
                                                        title="Histórico Legal"
                                                        v-on:click="doDocumentoPdfLegal(h.htc_cas_cod_id)"
                                                        data-toggle="modal" data-target="#modalDocumentoPdf">
                                                        <i class="far fa-file-pdf white" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                        <template v-else>
                                            <tr>
                                                <td colspan="5" class="text-center">Sin registros</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL DE LEGAL -->


        <!-- modalDocumentoPdf -->
        <div class="modal fade" id="modalDocumentoPdf" tabindex="-3" role="dialog" aria-labelledby="modalDocumentoPdf"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 40%;">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Documentos {{ singular }} {{ id_caso }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Listado de Documentos:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro</th>
                                            <th>tipo</th>
                                            <th>Descripción </th>
                                            <th>Ver Documento </th>
                                            <th>Firmas </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(h, index) in documento">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ h.tipo }}</td>
                                            <td>{{ h.descripcion }}</td>
                                            <td align="center">
                                                <button v-if="h.nombre === ''" type="button"
                                                    class="btn  btn-danger  btn-circle " title="Documento">
                                                    <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                </button>
                                                <!-- <button v-if="h.nombre != ''" type="button"
                                                    class="btn  btn-success btn-circle " title="Documento222"
                                                    v-on:click="verDocumento(h.doc_id, h.nombre)">
                                                    <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                </button> -->

                                                <template v-if ="h.nombre!= '' && h.id_doc_base64 == 1">
                                                    <button type="button"
                                                        class="btn  btn-secondary btn-circle " title="Documento"
                                                        v-on:click="verDocumentoXId(h.nombre)">
                                                        <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                    </button>
                                                </template>
                                                <template v-else>
                                                    <button v-if="h.nombre != ''" type="button"
                                                        class="btn  btn-success btn-circle " title="Documento"
                                                        v-on:click="verDocumento(h.doc_id, h.nombre)">
                                                        <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                    </button>
                                                </template>
                                            </td>
                                            <td align="center">
                                                <button v-if="h.nombre === ''" type="button" class="btn btn-danger btn-circle"
                                                    title="Firmas">
                                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                                </button>
                                                <button v-if="h.nombre != ''" type="button" class="btn btn-primary btn-circle"
                                                    title="Firmas" v-on:click="doFirmaPdf(h.doc_id)"
                                                    data-toggle="modal" data-target="#modalFirmaPdf">
                                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- modalObservaciones -->
        <div class="modal fade" id="modalObservaciones" tabindex="-3" role="dialog" aria-labelledby="modalObservaciones"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 40%;">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #60f172; border-bottom: 1px solid #60f172;">
                        <h5 class="modal-title" id="exampleModalLabel">Observaciones {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Observaciones UCPP:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro</th>
                                            <th>Unidad</th>
                                            <th>Descripción </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(h, index) in observaciones" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ h.unidad }}</td>
                                            <td>{{ h.descripcion }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalFirmaMasiva -->
        <div class="modal fade" id="modalFirmaMasiva" tabindex="-1" role="dialog" aria-labelledby="modalFirmaMasiva"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">Firma Masiva</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-2">
                                <label for="cmbToken">TOKEN:</label>
                                <div class="d-flex">
                                    <select id="cmbToken" v-model="token">
                                        <option value="" disabled>Seleccione</option>
                                        <option v-for="token in storeToken" :value="token.slot">{{ token.dispositivo
                                        }}
                                        </option>
                                    </select>
                                    <button type="button" class="btn btn-primary ml-2"
                                        @click="actualizarTokenFirmaMasiva">Actualizar Token
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="firmado_pin">PIN:</label>
                            <input type="password" class="form-control" id="firmado_pin" v-model="pin"
                                @keyup.enter="actualizar" />
                        </div>

                        <button type="button" class="btn btn-info" @click="actualizar">Autenticarse</button>
                        <br />
                        <div class="form-group">
                            <label for="cmbCertificado">CERTIFICADOS:</label>
                            <br />
                            <select id="cmbCertificado" v-model="selectedCertificado" required>
                                <option value="" disabled>Seleccione</option>
                                <option v-for="certificado in storeCertificados" :value="certificado.alias">
                                    {{ certificado.titular }} - {{ certificado.t }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="cancelarFirmaMasiva" type="button" class="btn btn-secondary"
                            @click="cerrarModalFirmaMasiva()" data-dismiss="modal">No</button>
                        <button id="aceptarFirmaMasiva" type="button" class="btn btn-info"
                            @click="aceptarModalFirmaMasiva()">Firmar</button>
                        <!--<button type="button" class="btn btn-warning" @click="tomarCaso($event)"
							data-dismiss="modal">Si, tomar</button>-->
                    </div>
                </div>
            </div>
        </div>
        <!-- modalFirmaMasivaRubrica -->
        <div class="modal fade" id="modalFirmaMasivaRubrica" tabindex="-1" role="dialog"
            aria-labelledby="modalFirmaMasivaRubrica" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">Firma Masiva Rubrica</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-2">
                                <label for="cmbToken">TOKEN:</label>
                                <div class="d-flex">
                                    <select id="cmbToken" v-model="token">
                                        <option value="" disabled>Seleccione</option>
                                        <option v-for="token in storeToken" :value="token.slot">{{ token.dispositivo
                                            }}
                                        </option>
                                    </select>
                                    <button type="button" class="btn btn-primary ml-2"
                                        @click="actualizarTokenFirmaMasiva">Actualizar Token
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firmado_pin">PIN:</label>
                            <input type="password" class="form-control" id="firmado_pin" v-model="pin"
                                @keyup.enter="actualizar" />
                        </div>
                        <button type="button" class="btn btn-info" @click="actualizar">Autenticarse</button>
                        <br />
                        <div class="form-group">
                            <label for="cmbCertificado">CERTIFICADOS:</label>
                            <br />
                            <select id="cmbCertificado" v-model="selectedCertificado" required>
                                <option value="" disabled>Seleccione</option>
                                <option v-for="certificado in storeCertificados" :value="certificado.alias">
                                    {{ certificado.titular }} - {{ certificado.t }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="cancelarFirmaMasivaRubrica" type="button" class="btn btn-secondary"
                            @click="cerrarModalFirmaMasivaRubrica()" data-dismiss="modal">No</button>
                        <button id="aceptarFirmaMasivaRubrica" type="button" class="btn btn-info"
                            @click="aceptarModalFirmaMasivaRubrica()">Firmar</button>
                        <!--<button type="button" class="btn btn-warning" @click="tomarCaso($event)"
                                                                data-dismiss="modal">Si, tomar</button>-->
                    </div>
                </div>
            </div>
        </div>


        <!-- modalDerivarMasivoLegal -->
        <div class="modal fade" id="modalDerivarMasivoLegal" tabindex="-1" role="dialog"
            aria-labelledby="modalDerivarMasivoLegal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="exampleModalLabel">Derivación Masiva</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label v-if="siguiente && siguiente.act_data">Siguiente Actividad: {{
                                    siguiente.act_data.act_tipo_derivacion }} </label>
                                <table v-if="paralelos.length <= 1"
                                    class="table table-hover table-striped table-responsive">
                                    <tr>
                                        <td><span class="badge badge-dark">{{ siguiente.act_data.act_orden }}</span>
                                        </td>
                                        <td>{{ siguiente.act_data.act_descripcion }}</td>
                                        <td><label>{{ siguiente.act_data.act_duracion_horas_maximo }}
                                                hora(s)</label>
                                        </td>
                                        <td><label>{{ siguiente.act_data.act_duracion_horas_minimo }}
                                                hora(s)</label>
                                        </td>
                                    </tr>
                                </table>
                                <table v-if="siguiente.act_data.act_tipo_derivacion == 'UNITARIA'">
                                    <tr>
                                        <td>
                                            <label>Derivación Unitaria</label><br />
                                        </td>
                                        <td>
                                            <!-- <label>SUPERVISORES DE AGENCIA</label> -->
                                            <table>
                                                <tr>
                                                    <th>Acción</th>
                                                    <th>Destinatario</th>
                                                </tr>
                                                <tr v-for="(d, index) in userNodo">
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-circle "
                                                            title="Derivar"
                                                            v-on:click="derivacionMasivaLegalDestinatario(d)"
                                                            data-dismiss="modal">
                                                            <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ d.nom_usuario }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar
                            Derivación</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalAsignacionMasivaCasos -->
        <div class="modal fade" id="modalAsignacionMasivaCasos" tabindex="-1" role="dialog"
            aria-labelledby="modalAsignacionMasivaCasos" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="exampleModalLabel">Asignación de Casos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <label>
                                Seleccionar Usuario: <span style="color: red;">(*)</span>
                            </label>
                            <p v-if="valUsuarioAsignacion" style="color: red;">El usuario es requerido.</p>
                            <v-select
                                :options="usuariosNodo.map(usuario => ({ label: usuario.name, value: usuario.id }))"
                                v-model="usuarioNodo" @input="hideValUsuarioAsignacion"
                                placeholder="Seleccionar Usuario..."></v-select>
                        </div>
                        <div class="col-md-12">
                            <label>Descripción:</label>
                            <textarea v-model="descripcionUsuarioAsignacion" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-success" @click="confirmarAsignacionMasiva">Si,
                            asignar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación Masiva-->
        <div class="modal fade" id="modalConfirmacionMasiva" tabindex="-1" role="dialog"
            aria-labelledby="modalConfirmacionMasiva" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="modalConfirmacionLabel">Confirmación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Está seguro de asignar los #{{ selected_ids }} casos al usuario/a {{ nombreUsuarioAsignacion
                        }}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-warning" @click="asignarCaso">Si, Asignar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensaje de carga con imagen GIF animada -->
        <div v-if="loading" class="loading-overlay">
            <div class="loading-message">
                <!--<img :src="spinnerImageUrl" alt="Cargando..." />-->
                <p>Cargando...</p>
            </div>
        </div>

        <Cargando :cargando="cargando" />

        <!-- Modal para dias transcurridos -->
        <div class="modal fade" id="modalDiasTranscurridos" tabindex="-1" role="dialog" aria-labelledby="modalDiasTranscurridosLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDiasTranscurridosLabel">Dias Transcurridos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul>
                            <li style="list-style-type: none; display: flex; align-items: center; margin-bottom: 10px;">
                                <span style="width: 20px; height: 20px; background-color: #28a745; display: inline-block; margin-right: 10px; border-radius: 100%;"></span>
                                Menos de 9 días transcurridos.
                            </li>
                            <li style="list-style-type: none; display: flex; align-items: center; margin-bottom: 10px;">
                                <span style="width: 20px; height: 20px; background-color: #02A2B9; display: inline-block; margin-right: 10px; border-radius: 100%;"></span>
                                Igual a 10 días transcurridos.
                            </li>
                            <li style="list-style-type: none; display: flex; align-items: center; margin-bottom: 10px;">
                                <span style="width: 20px; height: 20px; background-color: #dc3545; display: inline-block; margin-right: 10px; border-radius: 100%;"></span>
                                Más de 10 días transcurridos.
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalFirmaPdf -->
        <div class="modal fade" id="modalFirmaPdf" tabindex="-3" role="dialog" aria-labelledby="modalFirmaPdf"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 40%;">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Firmantes</h5>
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>-->
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Cargo</th>
                                            <th>Fecha Registro</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="listaFirma.length > 0">
                                            <tr v-for="(h, index) in listaFirma">
                                                <td>{{ h.certificado.nombreSignatario }}</td>
                                                <td>{{ h.certificado.cargoSignatario}}</td>
                                                <td>{{ new Date(h.fechaFirma).toLocaleDateString() }} </br>{{ formatoTiempo(h.fechaFirma) }}</td>
                                            </tr>
                                        </template>
                                        <template v-else>
                                            <tr>
                                                <td colspan="4" align="center">Sin Firmas</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import config from './config.js'; // Importa la configuración
    import datatables from 'datatables';
    import jsPDF from 'jspdf';
    import Swal from 'sweetalert2';
    import VisorPDF from './VisorPDF.vue';
    import Cargando from './Cargando.vue';
    import vSelect from 'vue-select'
    import axios from 'axios';
    import { LegalActividadesFirmaEnum, LegalActivitiesConfigService } from '../enum/legal-enum.js';
    import HistoricoLegalComplementoComponent from './shared/HistoricoLegalComplementoComponent.vue';
    import { EstaticValuesEnum } from '../enum/static-values.enum.js';
    import {encryptId, getDataConfigurationAboutActiveCase} from './shared/AuxiliaryFunctions';
    export default {
        props: ['data', 'valorRecibido'],
        watch: {
            'valorRecibido'(nuevoValor) {
                if (nuevoValor) {
                    this.nodos = [];
                    this.registros = [];
                    this.actualizarLaravel(nuevoValor);
                }
            },
        },
        name: 'servicios',
        components: {
            VisorPDF,
            Cargando,
            'v-select': vSelect,
            HistoricoLegalComplementoComponent,
        },
        data() {
            return {
                showLoader: false,
                showPassword: false,

                PaginaActual: 1,
                RegistrosXPagina: 10,

                opcionesRegistrosPorPagina: [10, 15, 20, 25],

                paginas: Array.from({ length: 100 }, (_, index) => index + 1),
                PaginaActual: 1,

                buscarRegistro: '',

                //registros: [],

                selected_ids: [],
                isCheckAll: false,

                pdfData: '',
                nombre_formulario: '',
                firma: '',
                loading: false,
                spinnerImageUrl: window.spinnerImageUrl, // Ruta completa de la imagen
                plural: 'Mis Pendientes',
                singular: 'Caso  ',
                isButtonDisabled: false, // Declaración de la propiedad isButtonDisabled
                usrId: window.Laravel.usr_id,
                usrUser: window.Laravel.usr_user,
                id_regional: window.Laravel.id_regional,
                id_agencia: window.Laravel.id_agencia,
                es_atc: window.Laravel.es_atc,
                es_supervisor: window.Laravel.es_supervisor,
                seleccionado: '',
                errores: [],
                registro: { cas_data: {}, act_data: {} },
                registros: [],
                archivos: [],
                procesos: [],
                siguiente: { act_data: {} },
                impresiones: [],
                historico: [],
                observaciones: [],
                documento: [],
                dataTable: null,
                swArchivo: false,
                nodos: [], // Aquí almacenaremos los nodos del JSON
                selectedNodeId: null, // ID del nodo seleccionado
                selectedNode: {},// Datos del nodo seleccionado
                checkboxStates: [],
                showButton: false, // Flag to control button visibility
                pdfSrc: '',
                isActive: true,
                hasError: false,
                derivacionForzada: 'Agencia',
                supervisores: [],
                userNodo: [],
                id_caso: '',
                paralelos: [],
                paralelo: { siguiente_data: {} },
                siguiente_data: { act_data: {} },
                s: { siguiente_data: {} },
                //**********VISOR DE PDF */
                storeToken: [],
                storeTokenFondos: [],
                selectedToken: '',
                token: '',
                pin: '',
                fojas: '',
                storeCertificados: [],
                storeCertificadosFondos: [],
                selectedCertificado: null,
                cmbCertificadoFondos: null,
                numero_de_pagina: null,
                miAplicacion: {},
                habilitarFirmaPad: true,
                currentPage: 1,
                totalPage: 0,
                pdf: null,
                cargando: false,
                vSelectedNodeId: null,
                urlGestora: window.Laravel.url_gestora, //config.URL_GESTORA + '',
                urlGestoraSgg: window.Laravel.url_gestora_sgg,
                CREDENCIALES: window.Laravel.credenciales, //config.CREDENCIALES,
                token: '',
                nombre_completo_: '',
                habilitarDerivacion: false,
                habilitarDerivacion2: false,
                EstadoSinRegistrados: false,
                notificacion: '',
                impresionesContrato: [],
                respuestaLegal: [],
                valUsuarioAsignacion: false,
                descripcionUsuarioAsignacion: '',
                nombreUsuarioAsignacion: null,
                usuarioNodo: null,
                usuariosNodo: [],
                totalCasos: null,
                showModalDerivarMultiple: false,
                listaNodosG: [],
                selectOptionNodo: '',
                selectOptionNodoG: '',

                isExpanded: false,
                expandedItems: {},
                casIdForLegalFlow:null,
                selectedRowSignature: null,
                listaFirma: [],

                selectOptionDia : '',
            }
        },
        computed: {
            isButtonDisabled() {
                // Disable the button if checkboxStates is empty
                return Object.keys(this.checkboxStates).length === 0;
            },
        },
        mounted() {
            this.listarNodo();
            //this.bRegistros();  // no deberia ir aqui
            this.sanitizedPdfSrc();
            document.getElementById('btnFirmaMasiva').style.display = 'none';
            document.getElementById('btnDerivacionMasivaLegal').style.display = 'none';
            document.getElementById('btnFirmaMasivaRubrica').style.display = 'none';
        },

        provide() {
            return {
                openModal: this.btnVerDocumentoInicial
            };
        },

        created() {
        },

        computed: {
            paginas() {
                return Array.from({ length: this.listarRegistros }, (_, index) => index + 1);
            }
        },
        props: {
            valorRecibido: null
        },

        methods: {
            toggle(casId) {
                this.$set(this.expandedItems, casId, !this.expandedItems[casId]);
                this.isExpanded = this.expandedItems[casId];
            },

            loadDocumentLogic: async function(r){
                //2025-04-09
                try{
                    this.pdfSrc = '';
                    const dataTipoCaso = await getDataConfigurationAboutActiveCase(this.registro.cas_id);
                    if(dataTipoCaso.prc_codigo !== "LEGAL"){
                        return;// we don't need to do anything
                    }
                    if(LegalActivitiesConfigService.data === null){
                        LegalActivitiesConfigService.loadData();
                    }
                    //2025-05-08
                    if(r.imp_tipo_firma === 'Firma Manual' && Number(dataTipoCaso.act_orden) === LegalActividadesFirmaEnum.LEGAL_ACT_ORDEN_40){
                        // generamos el documento configurado (reutilizamos el codigo existente)
                        this.doImprimir1(r.imp_id, r.imp_nombre, r.imp_tipo, false);
                        document.getElementById('firmado_archivo').disabled = true;
                    } else if(r.imp_tipo_firma ==='Firma Contrato' && Number(dataTipoCaso.act_orden) === LegalActividadesFirmaEnum.LEGAL_ACT_ORDEN_32){
                        // recuperamos el documento listado y previamente guardado (reutilizamos el metodo existente)
                        this.descargarPdf(r.imp_id, r.imp_nombre, r.imp_tipo, r.imp_nombre, 0)
                    }
                } catch(e){
                    console.error(e.message);
                }
            },
            showCheckboxSendEmailSms: function (strActividadOrdenActual, strActividadOrdenSiguiente) {
                let showCheckbox = false;

                if (strActividadOrdenActual === '35' && strActividadOrdenSiguiente === '40') {
                    showCheckbox = true;
                }
                return showCheckbox;
            },
            cambioVisibilidadPassword() {
                this.showPassword = !this.showPassword;
            },
            resetPagination: function (totalCasos) {
                this.totalCasos = totalCasos;
                const crearArrayPaginado = Number(this.totalCasos) / Number(this.RegistrosXPagina);
                const arrayPaginado = [];
                for (let i = 0; i <= crearArrayPaginado; i++) {
                    arrayPaginado.push(i + 1);
                }
                this.paginas = arrayPaginado;
            },
            closeModalDerivacionMasiva: function () {
                this.showModalDerivarMultiple = false;
            },
            drawPaginatorText: function () {
                if (!this.PaginaActual || !this.RegistrosXPagina || !this.totalCasos) {
                    return "";
                }
                let hasta = this.PaginaActual * this.RegistrosXPagina;
                let desde = hasta - this.RegistrosXPagina + 1;
                if (this.totalCasos < hasta) {
                    hasta = this.totalCasos;
                }
                if (desde > hasta) {
                    return "";
                }
                return desde + " - " + hasta + ' de un total ' + this.totalCasos + ' registros.'
            },

            actualizarLaravel(newValue) {
                this.usrId = newValue.id;
                this.id_regional = newValue.id_regional;
                this.id_agencia = newValue.id_agencia;
                this.es_atc = newValue.es_atc,
                    this.es_supervisor = newValue.es_supervisor,
                    this.listarNodo();
            },

            getEstado(estado) {
                switch (estado) {
                    case "A":
                        return '<span class="badge badge-success">Libre</span>';
                    case "T":
                        return '<span class="badge badge-warning">Asignado</span>';
                    case "H":
                        return '<span class="badge badge-primary">Archivado</span>';
                    case "E":
                        return '<span class="badge badge-info">Enviado</span>';
                    case "W":
                        return '<span class="badge badge-secondary">Paralelo</span>';
                    case "X":
                        return '<span class="badge badge-primary">Rama Cerrada</span>';
                }
            },

            getCasoHeredadoValue(cas_data_valores) {
                const casoHeredaroField = cas_data_valores.find(field => field.frm_campo === 'CASO_HEREDARO');
                if (casoHeredaroField) {
                    var casoHeredaro = casoHeredaroField.frm_value;
                    if (casoHeredaro) {
                        return true;
                    }
                }
                return false;
            },

            doLegal(id, id_padre) {
                var id_caso;
                let that = this;
                let url = "api/listadolegal/" + id;

                axios.get(url)
                    .then((response) => {
                        this.respuestaLegal = response.data.data;
                        console.log("La respuesta de Legal >> ", this.respuestaLegal);
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            },

            notificacionesMC(cas_id) {
                axios.get(`/api/notificaionesMC/${cas_id}`)
                    .then(response => {
                        if (response.data.success.code === 200) {
                            this.notificacion = response.data.count;
                            $('#modalNotificaciones').modal('show');
                            setTimeout(() => {
                                $('#modalNotificaciones').modal('hide');
                            }, 1000);
                        } else {
                            console.error(response.data.error.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching notification count:', error);
                    });
            },

            checkAll: function () {
                this.isCheckAll = !this.isCheckAll;
                this.selected_ids = [];

                if (this.isCheckAll) {
                    for (var key in this.registros) {
                        this.selected_ids.push(this.registros[key].cas_id);
                        this.registros[key].checked = true;
                    }
                } else {
                    for (var key in this.registros) {
                        this.registros[key].checked = false;
                    }
                }
            },
            convertirAMayusculas() {
                this.buscarRegistro = this.buscarRegistro.toUpperCase();
            },

            countVisibleButtons() {
                return this.impresiones.filter(r => r.imp_tipo_firma == 'Firma Manual' && r.firma != 0).length;
            },

            anteriorPagina() {
                if (this.PaginaActual > 1) {
                    this.PaginaActual--;
                    this.listarRegistros(); // Llama a la función que necesites al cambiar de página
                }
            },
            siguientePagina() {
                if (this.PaginaActual < this.paginas.length) {
                    this.PaginaActual++;
                    this.listarRegistros(); // Llama a la función que necesites al cambiar de página
                }
            },

            bRegistros() {
                console.log("555", this.registros);
                let apiUrl;

                console.log("selectOptionDia TRAMITESIP>>> ", this.selectOptionDia);

                if (this.es_atc || this.es_supervisor) {
                    console.log("######################################### TRUE");
                    apiUrl = `api/busquedaCasosXNodoXUsuario/${this.usrId}/${this.selectedNodeId}?search=${this.buscarRegistro}&dia=${this.selectOptionDia}`;
                    // apiUrl = `api/busquedaCasosXNodoXUsuario/${this.usrId}/${this.selectedNodeId}?search=${this.buscarRegistro}`;
                } else {
                    console.log("######################################### FALSE");
                    apiUrl = `api/busquedaCasosXNodo/${this.usrId}/${this.selectedNodeId}?search=${this.buscarRegistro}&dia=${this.selectOptionDia}`;
                }

                axios.get(apiUrl)
                    .then(response => {
                        if (Array.isArray(response.data.data)) {
                            console.log("LA DATA >>> ", response.data.data);
                            this.registros = response.data.data.map((row) => {
                                // Procesar cas_data
                                row.cas_data = row.cas_data || {};
                                row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso || '';
                                row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-')
                                    .replaceAll('null', '')
                                    .replaceAll('||', '<br/>')
                                    .replaceAll('|', '<br/>')
                                    .replaceAll('<br/><br/>', '<br/>');
                                // Procesar prc_data
                                row.prc_data = row.prc_data || {};
                                // Procesar act_data
                                row.act_data = row.act_data || {};
                                // Procesar act_data_reglas
                                row.act_data_reglas = row.act_data_reglas ? JSON.parse(row.act_data_reglas) : [];
                                return row;
                            });
                        }

                        console.log("🚀 ~ bRegistros push(row) this.registros:", this.registros)
                    })
                    .catch(error => {
                        console.error("Error al buscar los datos:", error);
                    });
                console.log("666", this.registros);
            },

            verDocumento: function (ruta, nombre) {
                var url = "/api/verDocumentoPdfNfs/" + ruta + '?usuario=' + this.usrUser + '@gestora.bo&pro=mis_casos';
                const partes = nombre.split('.');
                const partes2 = nombre.split('/');
                axios.get(url, { responseType: 'blob' })
                    .then(response => {
                        console.log("verDocumentoData", response.data);
                        if (partes[1] == 'pdf') {
                            const documento = response.data;
                            const url = window.URL.createObjectURL(documento);
                            const windowProps = 'top=0,left=0,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=650';
                            const newWindow = window.open(url, '_blank', windowProps);
                            newWindow.document.body.innerHTML = `<iframe src="${url}" width="100%" height="100%"></iframe>`;
                        } else {
                            const documento = response.data;
                            const blob = new Blob([documento], { type: 'application/pdf' });
                            const url = window.URL.createObjectURL(blob);
                            const link = document.createElement('a');
                            link.href = url;
                            link.setAttribute('download', partes2[6]); // Cambia 'nombre_del_archivo.pdf' según necesites
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    })
                    .catch(error => {
                        console.error('Error al mostrar el documento:', error);
                    });
            },

            verDocumentoXId(id_documento) {
                console.log('id_documento', id_documento);

                const payload = {
                    id_documento: id_documento
                };

                axios.post('/api/getDocumentosIdApiUcpp', payload, { responseType: 'blob' })
                    .then(response => {
                        if (response && response.data) {
                            const pdfBlob = new Blob([response.data], { type: 'application/pdf' });
                            const url = URL.createObjectURL(pdfBlob);

                            let modal = document.getElementById('modalPdfBase64');
                            if (!modal) {
                                modal = document.createElement('div');
                                modal.id = 'modalPdfBase64';
                                modal.className = 'modal fade';
                                modal.tabIndex = -1;
                                modal.role = 'dialog';
                                modal.innerHTML = `
                                    <div class="modal-dialog modal-lg" role="document" style="max-width: 50%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title">Documento PDF</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#modalPdfBase64').modal('hide')">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="height:80vh;">
                                                <iframe id="iframePdfBase64" src="" width="100%" height="100%" style="border:none;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                document.body.appendChild(modal);
                            }
                            document.getElementById('iframePdfBase64').src = url;
                            $('#modalPdfBase64').modal('show');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo obtener el PDF.',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al llamar getDocumentosIdApiUcpp:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo obtener el PDF.',
                        });
                    });
            },

            navegarAVistaLaravel() {
                // Redirigir a la vista de Laravel
                window.location.href = '/firmaSolicitante/' + this.registro.cas_id;
            },

            exportarExcelGNLPendientes() {
                const url = "api/v1/generarPendientesGNL";
                axios.get(url, { responseType: 'blob' })
                .then(response => {
                const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'PendientesGNL.xlsx';
                link.click();
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Reporte Generado ...",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    })
                .catch(error => {
                console.error('Error al descargar el archivo Excel de pendientes GNL:', error);
                });
            },

            async openModal(htc_id) {
                window.open(`${htc_id}`, '_blank');
            },

            getSanitizedPdfSrc() {
                // Llama al método que calcula la URL del PDF sanitizado
                return this.sanitizedPdfSrc();
            },

            sanitizedPdfSrc() {
                // Convierte la cadena Base64 a un formato seguro usando btoa()
                this.pdfData = `data:application/pdf;base64,` + this.pdfSrc;
                return `data:application/pdf;base64,` + this.pdfSrc;
            },

            recargar() {
                // this.vSelectedNodeId = null; // Restablecer el nodo seleccionado
                this.selectOptionNodo = "---------------"; // Restablecer la actividad seleccionada

                this.handleNodeChange();
                if (this.es_atc || this.es_supervisor) {
                    if (this.id_agencia <= 999) {
                        this.listarRegistrosUsuario();
                    }
                    else {
                        this.listarRegistros();
                    }
                    this.listarSupervisores();
                } else {
                    this.listarRegistros();
                    this.listarSupervisores();
                }
            },

            handleCheckboxChange(r) {
                if (this.selected_ids.includes(r.cas_id)) {
                    this.selected_ids = _.without(this.selected_ids, r.cas_id);
                } else {
                    this.selected_ids.push(r.cas_id);
                }
                const index = this.checkboxStates.findIndex(entry => entry.cas_id === r.cas_id);
                if (index !== -1) {
                    // If entry with the same cas_id exists, update its value
                    //this.registro.prc_id + "/" + this.registro.act_data.act_siguiente;
                    this.$set(this.checkboxStates, index, { cas_id: r.cas_id, prc_id: r.prc_id, act_siguiente: r.act_data.act_siguiente, value: !this.checkboxStates[index].value, cas_data_valores: r.cas_data_valores, cas_data: r.cas_data });
                } else {
                    // If entry with cas_id doesn't exist, add a new entry
                    this.checkboxStates.push({ cas_id: r.cas_id, prc_id: r.prc_id, act_siguiente: r.act_data.act_siguiente, value: true, cas_data_valores: r.cas_data_valores, cas_data: r.cas_data });
                }
                // Update the showButton flag based on checkboxStates
                this.showButton = this.checkboxStates.length > 0;
            },

            handleNodeChange() {
                this.selectedNodeId = this.vSelectedNodeId.value;
                localStorage.setItem('nodo', this.selectedNodeId);
                this.listarRegistrosXNodo();
                var nodId = this.vSelectedNodeId.value;
                //if (nodId == 73 || nodId == 6 || nodId == 3 || nodId == 57) {
                //if (nodId == 3 ) {
                    //document.getElementById('btnFirmaMasiva').style.display = 'block';
                //} else {
                    document.getElementById('btnFirmaMasiva').style.display = 'none';
                //}

                if (nodId == 57 || nodId == 3 ) {
                    document.getElementById('btnFirmaMasivaRubrica').style.display = 'block';
                } else {
                    document.getElementById('btnFirmaMasivaRubrica').style.display = 'none';
                }

                /* if (nodId == 57) {
                    document.getElementById('btnDerivacionMasivaLegal').style.display = 'block';
                } else {
                    document.getElementById('btnDerivacionMasivaLegal').style.display = 'none';
                }*/
            },

            derivacionMultiple() {
                const checkboxStatesString = JSON.stringify(this.checkboxStates);
                var params = { "cas_usr_id": this.usrId, "nodo_id": this.selectedNodeId, "casos": checkboxStatesString };
                let url = "api/casosDerivacionMple";
                axios.post(url, params).then(response => {
                    console.log(response.data);
                });
            },

            listarRegistros(selectOptionNodo, selectOptionDia = null) {
                var act_id = '';
                var dia = '';
                console.log("select dia en la funcion de: listarRegistros() >>>> ", this.selectOptionDia);

                if (this.selectOptionNodo && this.selectOptionNodo.act_id !== undefined || this.selectOptionDia && this.selectOptionDia !== undefined) {
                    act_id = this.selectOptionNodo.act_id;
                    dia = this.selectOptionDia ?? '';
                } else {
                    console.log("SIN EL VALOR DE ACT_ID");
                }

                var params = { "act_id": act_id, "dia": dia };

                console.log("EL PARAMS ",params);

                let url = "api/casos/" + this.usrId + "/" + this.selectedNodeId + '/' + this.RegistrosXPagina + '/' + this.PaginaActual;
                axios.get(url, { params: params }).then(response => {
                    this.registros = response.data.data; //twice data
                    console.log("🚀 ~ axios.get 1 lt~ this.registros:", this.registros)
                    this.registros.forEach(function (row) {
                        row.cas_data = JSON.parse(row.cas_data);
                        // corregir undefined en cas_nombre_caso
                        row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso ? row.cas_data.cas_nombre_caso : ''
                        row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-');

                        row.prc_data = JSON.parse(row.prc_data);
                        row.act_data = JSON.parse(row.act_data);
                        row.cas_data_valores = JSON.parse(row.cas_data_valores);
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('null', "") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('||', "<br/>") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('|', "<br/>") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('<br/><br/>', "<br/>") : '';
                        row.act_data_reglas = JSON.parse(row.act_data_reglas);
                    });
                    this.cargando = false;
                });
            },

            listarRegistrosUsuario() {
                let url = "api/casosUsuario/" + this.usrId + "/" + this.selectedNodeId + "/" + this.RegistrosXPagina + '/' + this.PaginaActual;
                axios.get(url).then(response => {
                    const totalCasos = response.data.totalRegistros[0].total_registros;
                    console.log('totalCasos ====================>>>>', totalCasos);
                    this.resetPagination(totalCasos);
                    let actividades = response.data.actividades; // Asignar actividades
                    this.listaNodosG = [];
                    actividades.forEach(nodo => {
                        const descripcion = nodo.descripcion;
                        const tipoMasivo = nodo.tipo_masivo;
                        const siguiente = nodo.siguiente;
                        const orden = nodo.orden;
                        const act_prc_id = nodo.act_prc_id;
                        const nodo_id = nodo.nodo_id;
                        const act_id = nodo.act_id;
                        const codigop = nodo.codigop;

                        this.listaNodosG.push({
                            descripcion: descripcion,
                            tipoMasivo: tipoMasivo,
                            siguiente: siguiente,
                            act_prc_id: act_prc_id,
                            orden: orden,
                            nodo_id: nodo_id,
                            act_id: act_id,
                            codigop: codigop,
                        });
                    });

                    this.registros = response.data.data; //twice data
                    this.registros.forEach(function (row) {
                        row.cas_data = JSON.parse(row.cas_data);
                        // corregir undefined en cas_nombre_caso
                        row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso ? row.cas_data.cas_nombre_caso : ''
                        row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-');

                        row.prc_data = JSON.parse(row.prc_data);
                        row.act_data = JSON.parse(row.act_data);
                        row.cas_data_valores = JSON.parse(row.cas_data_valores);
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('null', "") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('||', "<br/>") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('|', "<br/>") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('<br/><br/>', "<br/>") : '';
                        row.act_data_reglas = JSON.parse(row.act_data_reglas);
                    });
                });
            },

            listarRegistrosXNodo(selectOptionNodo, selectOptionDia = null) {
                console.log("listarRegistrosXNodo: " + this.selectOptionDia);
                var act_id = '';
                var dia = '';

                if (this.selectOptionNodo && this.selectOptionNodo.act_id !== undefined || this.selectOptionDia && this.selectOptionDia !== undefined) {
                    act_id = this.selectOptionNodo.act_id;
                    dia = this.selectOptionDia ?? '';
                } else {
                    console.log("SIN EL VALOR DE ACT_ID");
                }

                var params = { "usr_id": this.usrId, "nodo_id": this.selectedNodeId, "act_id": act_id, "dia": dia };

                let url = '';
                if (this.es_atc || this.es_supervisor) {
                    url = "api/casosXNodoXUsuario" + '/' + this.RegistrosXPagina + '/' + this.PaginaActual;
                } else {
                    url = "api/casosXNodo" + '/' + this.RegistrosXPagina + '/' + this.PaginaActual;
                }
                axios.post(url, params).then(response => {
                    const totalCasos = response.data.totalRegistros[0].total_registros;
                    console.log('totalCasos ====================>>>>', totalCasos);
                    this.resetPagination(totalCasos);

                    this.registros = response.data.data; //twice data
                    this.actividades = response.data.actividades;
                    this.listaNodosG = [];

                    this.registros.forEach(function (row) {
                        row.cas_data = JSON.parse(row.cas_data);
                        // corregir undefined en cas_nombre_caso
                        row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso ? row.cas_data.cas_nombre_caso : ''
                        row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-');

                        row.prc_data = JSON.parse(row.prc_data);
                        row.act_data = JSON.parse(row.act_data);
                        row.cas_data_valores = JSON.parse(row.cas_data_valores);
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('null', "") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('||', "<br/>") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('|', "<br/>") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('<br/><br/>', "<br/>") : '';
                        row.act_data_reglas = JSON.parse(row.act_data_reglas);
                    });

                    this.actividades.forEach(nodo => {
                        const descripcion = nodo.descripcion;
                        const tipoMasivo = nodo.tipo_masivo;
                        const siguiente = nodo.siguiente;
                        const orden = nodo.orden;
                        const act_prc_id = nodo.act_prc_id;
                        const nodo_id = nodo.nodo_id;
                        const act_id = nodo.act_id;
                        const codigop = nodo.codigop;

                        this.listaNodosG.push({
                            descripcion: descripcion,
                            tipoMasivo: tipoMasivo,
                            siguiente: siguiente,
                            act_prc_id: act_prc_id,
                            orden: orden,
                            nodo_id: nodo_id,
                            act_id: act_id,
                            codigop: codigop,
                        });
                    });
                });
            },

            handleSelectChange(event) {
                const selectedNodo = this.selectOptionNodo;
                this.listarRegistrosXNodo(this.selectOptionNodo, this.selectOptionDia);
                this.listarRegistros(this.selectOptionNodo, this.selectOptionDia);
                console.log(">> selectOptionDia", this.selectOptionDia);
            },

            listarSupervisores() {
                var params = this.id_agencia;
                let url = "api/obtenerSupervisoresAgencia/" + params;
                axios.get(url).then(response => {
                    this.supervisores = response.data.data; //twice data
                    this.cargando = false;
                });
            },

            listarArchivados() {
                let url = "api/casosArchivados/" + this.usrId;
                axios.get(url).then(response => {
                    this.archivos = response.data.data; //twice data
                    this.archivos.forEach(function (row) {
                        row.cas_data = JSON.parse(row.cas_data);
                        // corregir undefined en cas_nombre_caso
                        row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso ? row.cas_data.cas_nombre_caso : ''
                        row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-');
                        row.cas_data_valores = JSON.parse(row.cas_data_valores);
                        row.prc_data = JSON.parse(row.prc_data);
                        row.act_data = JSON.parse(row.act_data);
                        row.cas_data_valores = JSON.parse(row.cas_data_valores);
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('null', "") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('||', "<br/>") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('|', "<br/>") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('<br/><br/>', "<br/>") : '';
                        row.act_data_reglas = JSON.parse(row.act_data_reglas);
                    });
                });
            },

            swListarArchivados() {
                this.swArchivo = !this.swArchivo;
                if (this.swArchivo)
                    this.listarArchivados();
            },

            doVer(index) { // Para tomarCaso() y liberarCaso()
                this.registro = this.registros[index];
            },

            doAntesLimpiar(index) { // verificar que tiene los datos para avanzar
                this.registro = this.registros[index];
                let orden = this.registro.act_data.act_orden;
                if (orden == '30') {
                    if (this.registro.cas_data.ID_SOLICITUDPRESTACION) {
                        this.doLimpiar(index);
                    } else {
                        Swal.fire('Debe ingresar al trámite y registrar los valores correspondientes 1', '', 'warning');
                        this.doLimpiar(index);
                    }
                } else {
                    this.doLimpiar(index);
                }
            },

            async jub1185ActulizarContrato() {
                // Definir apiUrl y payload correctamente
                const apiUrl = "/api/actualizarDatosContratoJUB";
                const payload = {
                    cas_id: this.registro.cas_id,
                    cua: this.registro.cas_data.AS_CUA//"32345513"//"32358453" //"31747139"
                };
                try {
                    const response = await axios.post(apiUrl, payload, {
                        headers: {
                            "Content-Type": "application/json", // Asegura el tipo de contenido
                        },
                    });
                    console.log("Respuesta del servidor:", response.data);
                    if (response.data.codigoRespuesta == '200') {

                    }
                } catch (error) {

                    // Manejar errores
                    console.error("Error al enviar los datos:", error.response?.data || error.message);
                    //alert("Ocurrió un error al enviar los datos.");
                }
            }, //this.registro
            async antesDerivar(index) {
                this.loading = true;
                if (index < 0 || index >= this.registros.length) {
                    console.error("Índice fuera de límites:", index);
                    return;
                }
                this.$store.dispatch('updateIndexDoc', index);
                this.paralelos = [];
                this.impresionesContrato = [];
                this.paralelo = { siguiente_data: {} };
                this.siguiente_data = { act_data: {} };
                this.siguiente = { act_data: {} };
                this.registro = this.registros[index];
                if (this.registro.cas_act_id == 253) {
                    const respuesta = await this.jub1185ActulizarContrato();
                    this.recargar();
                    console.log("respuesta: ", respuesta);
                    Swal.fire({
                        title: 'El caso se actualizó correctamente ',
                        text: 'Favor proceder a derivar',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                }
                this.loading = false;
                //this.doLimpiar(index);
            },
            async doLimpiar(index) {
                if (index < 0 || index >= this.registros.length) {

                    console.error("Índice fuera de límites:", index);
                    return;
                }
                this.$store.dispatch('updateIndexDoc', index);
                this.paralelos = [];
                this.impresionesContrato = [];
                this.paralelo = { siguiente_data: {} };
                this.siguiente_data = { act_data: {} };
                this.siguiente = { act_data: {} };
                this.registro = this.registros[index];
                try {
                    let nombre_completo = '';
                    if (this.registro.cas_data.AS_PRIMER_NOMBRE != null) {
                        nombre_completo = this.registro.cas_data.AS_PRIMER_NOMBRE;
                    }
                    if (this.registro.cas_data.AS_SEGUNDO_NOMBRE != null) {
                        nombre_completo = nombre_completo + ' ' + this.registro.cas_data.AS_SEGUNDO_NOMBRE;
                    }
                    if (this.registro.cas_data.AS_PRIMER_APELLIDO != null) {
                        nombre_completo = nombre_completo + ' ' + this.registro.cas_data.AS_PRIMER_APELLIDO;
                    }
                    if (this.registro.cas_data.AS_SEGUNDO_APELLIDO != null) {
                        nombre_completo = nombre_completo + ' ' + this.registro.cas_data.AS_SEGUNDO_APELLIDO;
                    }

                    this.nombre_completo_ = nombre_completo;
                    this.habilitarDerivacion2 = true;
                    // Manejando reglas
                    if (this.registro.act_data_reglas && this.registro.cas_data_valores) {

                        for (const regla of this.registro.act_data_reglas) {
                            let reg = regla.act_regla;
                            for (const campito of this.registro.cas_data_valores) {
                                reg = reg.replaceAll('#' + campito.frm_campo + '#', campito.frm_value);
                            }
                            let evaluacion = eval(reg);

                            if ((reg.indexOf('#') < 0) && evaluacion) {
                                this.registro.act_data.act_siguiente = regla.act_siguiente;
                                this.paralelos.push({ "siguiente": regla.act_siguiente });
                            }
                        }
                    }

                    let url = "";
                    if (this.registro.act_tact_id == 5) { // Paralelo
                        if (this.paralelos.length == 1) {
                            url = "api/actividad/" + this.registro.prc_id + "/" + this.registro.act_data.act_siguiente;
                            const response = await axios.get(url);
                            this.siguiente = response.data.data.map(row => ({
                                ...row,
                                act_data: JSON.parse(row.act_data)
                            }))[0];
                        } else if (this.paralelos.length > 1) {
                            for (const paralelo of this.paralelos) {
                                url = "api/actividad/" + this.registro.prc_id + "/" + paralelo.siguiente;
                                const response = await axios.get(url);
                                this.siguiente = response.data.data.map(row => ({
                                    ...row,
                                    act_data: JSON.parse(row.act_data)
                                }))[0];
                                paralelo["siguiente_data"] = this.siguiente;
                            }
                        }
                    } else { // fork
                        url = "api/actividad/" + this.registro.prc_id + "/" + this.registro.act_data.act_siguiente;
                        const response = await axios.get(url);
                        this.siguiente = response.data.data.map(row => ({
                            ...row,
                            act_data: JSON.parse(row.act_data)
                        }))[0];
                        if (this.siguiente.act_data.act_tipo_derivacion == 'UNITARIA') {
                            const params = this.siguiente.act_nodo_id;
                            const response = await axios.get("api/obtenerUsuariosDelNodo/" + params);
                            this.userNodo = response.data.data;
                            this.cargando = false;
                        } if (this.siguiente.act_data.act_tipo_derivacion == 'REGIONAL') {
                            const params = this.siguiente.act_nodo_id;
                            const response = await axios.get("api/obtenerUsuariosDelNodo/" + params);
                            this.userNodo = response.data.data;
                            this.cargando = false;
                        }
                    }
                    url = "api/impresionesCasos/" + this.registro.act_id + "/" + this.registro.cas_id;
                    this.impresiones = [];
                    this.showLoader = true;

                    try {
                        const responseImpresiones = await axios.get(url);
                        this.impresiones = responseImpresiones.data.data;
                        this.habilitarDerivacion = false;
                        this.EstadoSinRegistrados = true;

                    } catch (error) {
                        if (error.response) {
                            console.error('Error en la respuesta del servidor:', error.response.status, error.response.data);
                            this.impresiones = [];
                            // this.habilitarDerivacion = false;
                            this.EstadoSinRegistrados = false;
                        } else if (error.request) {
                            console.error('Sin respuesta del servidor:', error.request);
                            this.impresiones = [];
                            // this.habilitarDerivacion = false;
                            this.EstadoSinRegistrados = false;
                        }
                    } finally {
                        this.showLoader = false;
                        // this.habilitarDerivacion = false;
                        // this.EstadoSinRegistrados = true;
                    }

                    this.impresionesContrato = this.impresiones.filter(impresion => impresion.imp_nombre.includes("CONTRATO"));
                    this.showLoader = false;
                    var documentosReFirmados = 0;

                    if ((this.registro.act_data.act_siguiente == 67 || this.registro.act_data.act_siguiente == 167 || this.registro.act_data.act_siguiente == 68 || this.registro.act_data.act_siguiente == 168 || this.registro.act_data.act_siguiente == 69 || this.registro.act_data.act_siguiente == 169 || this.registro.act_data.act_siguiente == 35) && this.impresiones) {
                        if (this.impresionesContrato.length > 0 && this.registro.act_data.act_siguiente == 68) {
                            this.impresiones = this.impresionesContrato;
                            const documentosFirmados = this.impresiones.reduce((cantidad, impresion) => {
                                return impresion.firma === 0 ? cantidad + 1 : cantidad;
                            }, 0);
                            if (documentosFirmados === 0) {
                                this.habilitarDerivacion2 = true;
                            } else {
                                this.habilitarDerivacion2 = false;
                            }
                        }
                        else {
                            //alert("No se encontraron documentos para firmar");
                            url = "api/contratosCasos/" + this.registro.act_id + "/" + this.registro.cas_id;
                            const responseImpresiones = await axios.get(url);
                            this.impresiones = responseImpresiones.data.data;

                            // console.log("DOCUMENTOS PARA RESETEAR ");
                            // this.resetFirmaDoc();
                            document.getElementById('firmado_archivo').disabled = true;
                            this.$store.dispatch('updateActividad', this.registro.act_data.act_siguiente);

                            documentosReFirmados = this.impresiones.reduce((cantidad, impresion) => {
                                return impresion.doc_detalle_documento !== this.registro.act_data.act_siguiente ? cantidad + 1 : cantidad;
                            }, 0);
                            if (documentosReFirmados === 0) {
                                this.habilitarDerivacion2 = true;
                            } else {
                                this.habilitarDerivacion2 = false;
                            }

                        }
                    }
                    const documentosFirmados = this.impresiones.reduce((cantidad, impresion) => {
                        return impresion.firma === 0 ? cantidad + 1 : cantidad;
                    }, 0);
                    if (documentosFirmados === 0) {
                        this.habilitarDerivacion = true;
                    }
                    const datos = { cas_id: this.registro.cas_id };
                    const responseFirma = await axios.post('api/obtenerFirma', datos);
                    this.$refs.imagen.src = "data:image/png;base64," + responseFirma.data.data;
                    this.firma = responseFirma.data.data;
                    this.habilitarFirmaPad = false;

                } catch (error) {
                    console.error('Error en doLimpiar:', error);
                    this.habilitarFirmaPad = true;
                }
            },

            redirigir(dato) {
                if (dato.cas_cod_id.includes('LEGAL')) {
                    console.log("legal");
                    this.$router.push("/atenderCasoRender/" + encryptId(dato.cas_id));
                } else {
                    console.log("otros");
                    this.$router.push("/atenderCaso/" + encryptId(dato.cas_id));
                }
            },
            tomarCaso(e) {
                let gRegistro = {
                    "cas_estado": "T",
                    "cas_usr_id": this.usrId
                };
                let that = this;
                let url = "api/estadoCaso/" + this.registro.cas_id;
                axios.put(url, gRegistro)
                    .then((response) => {
                        if (this.registro.cas_cod_id.includes('LEGAL')) {
                            // this.$router.push("/atenderCasoRender/" + this.registro.cas_id);
                        } else {
                            // this.$router.push("/atenderCaso/" + this.registro.cas_id);
                        }
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            },

            tomarCasoUsuarioEnvio(usr, casId) {
                let act_actual = this.registro.act_data.act_orden;
                let tipoEap = this.registro.cas_data.AS_TIPO_EAP;
                //derivar
                let gRegistro = this.registro;
                gRegistro.cas_estado = 'E';
                gRegistro.cas_act_id = this.siguiente.act_id;
                gRegistro.cas_nodo_id = this.siguiente.act_nodo_id;
                gRegistro.cas_usr_nom = usr;
                gRegistro.casId = casId;
                let that = this;

                let url = "api/asignarUsuarioRespEnvio/" + casId;
                axios.post(url, gRegistro)
                    .then((response) => {
                        that.recargar();
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            },

            tomarCasoUsuario(usr, casId) {
                let gRegistro = {
                    "cas_estado": "E", //enviado
                    "cas_nom": usr
                };
                let that = this;
                let url = "api/asignarUsuarioResp/" + casId;
                axios.post(url, gRegistro)
                    .then((response) => {
                        that.recargar();
                        //this.$router.push("/atenderCaso/" + this.registro.cas_id);
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            },

            tomarCasoRoundRobin(usrId, casId) {
                let gRegistro = {
                    "cas_estado": "T",
                    "cas_usr_id": usrId
                };
                let that = this;
                let url = "api/estadoCaso/" + casId;
                axios.put(url, gRegistro)
                    .then((response) => {
                        //this.$router.push("/atenderCaso/" + this.registro.cas_id);
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            },

            //historico
            doHistorico: async function (id, id_padre) {
                this.casIdForLegalFlow = id;
                console.log("doHistorico", id);
                console.log("id_padre", id_padre);
                var id_caso;
                if (id_padre == 0) {
                    id_caso = id;
                } else {
                    id_caso = id_padre;
                }
                let that = this;
                let url = "api/casosHistorico/" + id_caso;
                axios.get(url)
                    .then((response) => {
                        this.historico = response.data.data;
                        console.log("el historico", this.historico);
                        this.id_caso = response.data.data[0].htc_cas_cod_id;
                        $("#modalHistorico").modal("show");
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            },
            //historico
            doDocumentoPdf(htc_id) {
                console.log('htc_id ==>', htc_id);
                const datos = { htc_id: htc_id };
                axios.post('api/obtenerDocumento', datos)
                    .then(response => {
                        console.log('respuesta', response.data);
                        this.documento = response.data.data;
                    })
                    .catch(error => {
                        console.error('Error al generar al listado', error);
                    });
            },

            doDocumentoPdfLegal(htc_cas_cod_id) {
                console.log('LEGAL >>> htc_cas_cod_id ==>', htc_cas_cod_id);
                const datos = { htc_cas_cod_id: htc_cas_cod_id };
                axios.post('api/obtenerDocumentoLegal', datos)
                    .then(response => {
                        console.log('respuesta', response.data);
                        this.documento = response.data.data;
                    })
                    .catch(error => {
                        console.error('Error al generar al listado', error);
                    });
            },

            observacionesUCPP(observaciones) {
                console.log("Observaciones", observaciones);
                this.observaciones = observaciones;
            },
            cargando() {
                this.mostrarCargando = true;
            },

            terminarCargando() {
                this.mostrarCargando = false;
            },
            listarNodo() {
                this.cargando = false;
                let that = this;
                var url = "api/nodosUsuario/" + this.usrId;
                axios.get(url).then(response => {
                    console.log(response.data.data);
                    if (response.data.data && response.data.data.length > 0) {
                        this.nodos = response.data.data;
                        console.log(this.nodos);
                        if (localStorage.getItem('nodo') != null) {
                            this.selectedNodeId = localStorage.getItem('nodo');
                            //localStorage.removeItem('nodo');
                            this.vSelectedNodeId = { label: this.nodos.find(nodo => nodo.nodo_id === Number(this.selectedNodeId)).nodo_descripcion, value: this.nodos.find(nodo => nodo.nodo_id === Number(this.selectedNodeId)).nodo_id }; //ESTA LINEA SE PUEDE COMENTAR
                        } else {
                            this.selectedNodeId = this.nodos[0].nodo_id;
                            this.vSelectedNodeId = { label: this.nodos[0].nodo_descripcion, value: this.nodos[0].nodo_id };
                        }
                        this.recargar();
                        // Asegúrate de que haya datos en la respuesta
                    } else {
                        console.log("No hay datos de nodos en la respuesta.");
                    }
                }).catch(error => {
                    console.error("Error al cargar datos de nodos:", error);
                });
            },

            liberarCaso(e) {
                let gRegistro = {
                    "cas_estado": "A",
                    "cas_usr_id": this.usrId
                };
                let that = this;
                let url = "api/estadoCaso/" + this.registro.cas_id;
                axios.put(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        that.recargar();
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            },

            enviarseguimientoTramites(cas_id, area, tipo) {
                console.log("enviarseguimientoTramitess1",cas_id, area, tipo);
                this.loading = true;
                var urlDatosSeguimientoTramite = "api/datosSeguimientoTramite/" + this.registro.cas_id;
                axios.get(urlDatosSeguimientoTramite)
                    .then(response => {
                        var params = JSON.parse(response.data.data);
                        var user_envio;
                        if (area == 'EAP') {
                            user_envio = params.usuario_registro;
                        } else {
                            user_envio = this.usrUser;
                        }
                        const url = `${this.urlGestoraSgg}/otorgamiento-prestaciones/api/v2/seguimientoTramites/registrar?area=${area}&usuReg=${user_envio}`;
                        axios.post(url, params)
                            .then(respuesta => {
                                this.loading = false;
                                let mensaje = respuesta.data.mensaje;
                                let datosEnviar = {
                                        cas_id: this.registro.cas_id,
                                        cas_usr_id: this.registro.act_usr_id,
                                        tipo: 'PLANILLAS',
                                        url: url,
                                        output: params,
                                        config: '',
                                        response: respuesta,
                                        usuario: this.usrUser,
                                        cas_cod_id: params.numeroTramite,
                                    };
                                    axios.put('api/guardarLogApi', datosEnviar)
                                        .then(response => {
                                            if (response.data.codigoRespuesta === 200) {
                                                console.log("Log guardado exitosamente:", response.data);
                                            } else {
                                                console.warn("no se guardará el log.");
                                            }
                                        });
                                if (respuesta.data.codigo == '200') {
                                    let userAsignado = respuesta.data.data;
                                    if (tipo == 'uno')
                                        this.tomarCasoUsuarioEnvio(userAsignado, cas_id);
                                    else
                                        this.tomarCasoUsuario(userAsignado, cas_id);
                                    Swal.fire({
                                        title: 'El caso fue derivado a: ',
                                        text: userAsignado,
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar'
                                    });
                                } else if (mensaje.includes('Numero de Tramite Existente')) {
                                    let userAsignado = respuesta.data.data;
                                    if (tipo == 'uno')
                                        this.tomarCasoUsuarioEnvio(userAsignado, cas_id);
                                    else
                                        this.tomarCasoUsuario(userAsignado, cas_id);
                                    Swal.fire({
                                        title: 'El caso fue derivado a: ',
                                        text: userAsignado,
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                                else {
                                    let resp = respuesta.data.data;
                                    Swal.fire(mensaje, resp, 'error');
                                }
                            })
                            .catch(error => {
                                Swal.close();  // Cerrar la alerta de carga
                                let datosEnviar = {
                                        cas_id: this.registro.cas_id,
                                        cas_usr_id: this.registro.act_usr_id,
                                        tipo: 'PLANILLAS',
                                        url: url,
                                        output: params,
                                        config: '',
                                        response: error,
                                        usuario: this.usrUser,
                                        cas_cod_id: params.numeroTramite,
                                    };
                                    axios.put('api/guardarLogApi', datosEnviar)
                                        .then(response => {
                                            console.log("Log guardado exitosamente:", response.data);
                                        })
                                        .catch(error => {
                                            console.error("Error al guardar el log:", error);
                                        });
                                console.error('Error en la solicitud:', error);
                                Swal.fire({
                                    title: 'Hubo un incomveniente en El envío a la UCPP, Enviar otra vez? ',
                                    icon: 'question',
                                    showCancelButton: false,
                                    confirmButtonText: 'Sí',
                                    cancelButtonText: 'No',
                                    onBeforeOpen: () => {
                                        Swal.showLoading();  // Mostrar el indicador de carga
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        this.loading = true;
                                        this.enviarseguimientoTramites(cas_id, area, tipo);
                                        Swal.close();
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        // Lógica a ejecutar si el usuario selecciona 'No' o cancela
                                        Swal.close();  // Cerrar la alerta de carga
                                        //Swal.fire('Acción cancelada', '', 'info');
                                    }
                                });
                                // Manejar el error según tus necesidades
                            });
                    })
                    .catch(error => {
                        Swal.close();  // Cerrar la alerta de carga
                        console.error('Error en la solicitud:', error);
                        // Manejar el error según tus necesidades
                    });
            },

            seguimientoTramitesUpdate() {
                console.log('seguimientoTramitesUpdate', this.urlGestoraSgg);
                //const url = `${this.urlGestoraSgg}/otorgamiento-prestaciones/api/v2/seguimientoTramites/registrar?area=${area}&usuReg=${params.usuario_registro}`;
                const url = `${this.urlGestoraSgg}/otorgamiento-prestaciones-calculos/api/v1/definicion/updateStateTramite?idSeguimientoTramite=2921&codeTransaction=3&usuMod=juan.flores@gestora.bo`;
                const params = [];
                axios.post(url, params)
                    .then(respuesta => {
                        console.log("=ZZ =======>>>>>>>>>", respuesta.data);
                        this.loading = false;
                        if (respuesta.data.codigo == '0') {
                            let userAsignado = respuesta.data.data;
                            ////-------------------------------------------------------------------------------------------------------------------------------
                            const url = `https://desa-sipre.gestora.bo/spr-tram-rest/api/solicitudPrestacion/actualizar/estado?idSolicitudPrestacion=2921&nuevoEstadoPrestacion=CON&usuarioModificacion=adad.flores`;
                            const params2 = {
                                "siglaEnteGestorSalud": "SSM",
                                "subtipoPrestacion": "FMV",
                                "fechaContrato": "2024-08-06"
                            };
                            axios.post(url, params2)
                                .then(respuesta => {
                                    console.log("=ZZ =======>>>>>>>>>", respuesta.data);
                                })
                                .catch(error => {
                                    Swal.close();
                                    console.error('Error en la solicitud:', error);
                                });
                            ////-------------------------------------------------------------------------------------------------------------------------------

                            Swal.fire(userAsignado.mensaje, '', 'warning');
                        } else if (respuesta.data.codigo == '1') {

                            const urlToken = this.urlGestora + '/str-seg-aut-rest/autenticacion/funcionarios/token/obtener/pass';
                            const datos = this.CREDENCIALES;
                            axios.post(urlToken, datos)
                                .then(respuesta => {
                                    console.log("=ZZ =======>>>>>>>>> dsd asd sadasd sad asd asdsad asd ", respuesta.data.data.accessToken);
                                    this.token = respuesta.data.data.accessToken;
                                    const url = this.urlGestora + `/spr-tram-rest/api/solicitudPrestacion/actualizar/estado?idSolicitudPrestacion=2981&nuevoEstadoPrestacion=CON&usuarioModificacion=adad.flores`;
                                    const params2 = {
                                        "siglaEnteGestorSalud": "SSM",
                                        "subtipoPrestacion": "FMV",
                                        "fechaContrato": "2024-08-06"
                                    };

                                    let config = {
                                        headers: {
                                            'Authorization': 'Bearer ' + this.token
                                        }
                                    }
                                    console.log('config ==>>', config);
                                    axios.put(url, params2, config)
                                        .then(respuesta => {
                                            if (respuesta.data.ok) {
                                                Swal.fire('El caso fue  ', respuesta.data.data.apiEstado, 'success');
                                            }
                                            console.log(" respuestas de la data ======>", respuesta.data.ok);
                                        })
                                        .catch(error => {
                                            Swal.close();  // Cerrar la alerta de carga
                                            console.error('Error en la solicitud:', error);
                                        });
                                })
                                .catch(error => {
                                    Swal.close();  // Cerrar la alerta de carga
                                    console.error('Error en la solicitud:', error);
                                });
                            ////-------------------------------------------------------------------------------------------------------------------------------
                            ////-------------------------------------------------------------------------------------------------------------------------------
                        }

                    })
                    .catch(error => {
                        Swal.close();  // Cerrar la alerta de carga
                        console.error('Error en la solicitud:', error);
                        Swal.fire({
                            title: 'Hubo un incomveniente en El envío a la UCPP, Enviar otra vez? ',
                            icon: 'question',
                            showCancelButton: false,
                            confirmButtonText: 'Sí',
                            cancelButtonText: 'No',
                            onBeforeOpen: () => {
                                Swal.showLoading();  // Mostrar el indicador de carga
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.loading = true;
                                this.seguimientoTramitesUpdate();
                                Swal.close();
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                // Lógica a ejecutar si el usuario selecciona 'No' o cancela
                                Swal.close();  // Cerrar la alerta de carga
                                //Swal.fire('Acción cancelada', '', 'info');
                            }
                        });
                        // Manejar el error según tus necesidades
                    });
            },

            enviarseguimientoTramites_volver(cas_id, area) {
                var urlDatosSeguimientoTramite = "api/datosSeguimientoTramite/" + this.registro.cas_id;
                axios.get(urlDatosSeguimientoTramite)
                    .then(response => {
                        var params = JSON.parse(response.data.data);
                        console.log("params: ", params);
                        console.log("params.observacion: ", params.observacion);
                        //var aaa= 'aaa';
                        const url = `${this.urlGestoraSgg}/otorgamiento-prestaciones/api/v1/devolucionTramites/tramiteObservado?procesoEapPres=${area}&usuario=${this.usrUser}&observacion=${params.observacion}`;
                        console.log("url: ", url);
                        axios.post(url, params)
                            .then(respuesta => {
                                console.log("=ZZ");
                                this.loading = false;
                                let mensaje = respuesta.data.mensaje;
                                let datosEnviar = {
                                        cas_id: this.registro.cas_id,
                                        cas_usr_id: this.registro.act_usr_id,
                                        tipo: 'PLANILLAS',
                                        url: url,
                                        output: params,
                                        config: '',
                                        response: respuesta,
                                        usuario: this.usrUser,
                                        cas_cod_id: params.numeroTramite,
                                    };
                                    axios.put('api/guardarLogApi', datosEnviar)
                                        .then(response => {
                                            if (response.data.codigoRespuesta === 200) {
                                                console.log("Log guardado exitosamente:", response.data);
                                            } else {
                                                console.warn("no se guardará el log.");
                                            }
                                        });
                                if (respuesta.data.codigo == '200') {
                                    let userAsignado = respuesta.data.data;
                                    this.tomarCasoUsuarioEnvio(userAsignado, cas_id);

                                    Swal.fire({
                                        title: 'El caso fue derivado a: ',
                                        text: userAsignado,
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar'
                                    });
                                } else if (mensaje.includes('Numero de Tramite Existente') || mensaje.includes('REVISANDO_EAP') || mensaje.includes('OBS_NOTIFICADO')) {
                                    let userAsignado = respuesta.data.data;
                                    this.tomarCasoUsuarioEnvio(userAsignado, cas_id);

                                    Swal.fire({
                                        title: 'El caso fue derivado a: ',
                                        text: userAsignado,
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar'
                                    });
                                } else {
                                    Swal.fire('El caso no pudo ser enviado a UCPP', '', 'warning');
                                }
                            })
                            .catch(error => {
                                Swal.close();  // Cerrar la alerta de carga
                                let datosEnviar = {
                                        cas_id: this.registro.cas_id,
                                        cas_usr_id: this.registro.act_usr_id,
                                        tipo: 'PLANILLAS',
                                        url: url,
                                        output: params,
                                        config: '',
                                        response: error,
                                        usuario: this.usrUser,
                                        cas_cod_id: params.numeroTramite,
                                    };
                                    axios.put('api/guardarLogApi', datosEnviar)
                                        .then(response => {
                                            console.log("Log guardado exitosamente:", response.data);
                                        })
                                        .catch(error => {
                                            console.error("Error al guardar el log:", error);
                                        });
                                console.error('Error en la solicitud:', error);
                                Swal.fire({
                                    title: 'Hubo un incomveniente en El envío a la UCPP, Enviar otra vez? ',
                                    icon: 'question',
                                    showCancelButton: false,
                                    confirmButtonText: 'Sí',
                                    cancelButtonText: 'No',
                                    onBeforeOpen: () => {
                                        Swal.showLoading();  // Mostrar el indicador de carga
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        this.loading = true;
                                        this.enviarseguimientoTramites_volver(cas_id, area);
                                        Swal.close();
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        // Lógica a ejecutar si el usuario selecciona 'No' o cancela
                                        Swal.close();  // Cerrar la alerta de carga
                                        //Swal.fire('Acción cancelada', '', 'info');
                                    }
                                });
                                // Manejar el error según tus necesidades
                            });
                    })
                    .catch(error => {
                        Swal.close();  // Cerrar la alerta de carga
                        console.error('Error en la solicitud:', error);
                        // Manejar el error según tus necesidades
                    });
            },

            funderivarCasoForzadoUnitaria(e) {
                let orden = this.registro.act_data.act_orden;
                console.log("🚀 ~ funderivarCasoForzadoUnitaria ~ orden:", this.registro)
                let that = this;
                if (orden == '30' && this.registro.act_data.act_siguiente != 20) {
                    this.GetSolicitudPrestaciones(this.registro.cas_id, e, this.registro.cas_data)
                        .then(idprestaciones => {
                            //alert(idprestaciones);
                            that.funderivarCasoForzado(e);
                        })
                        .catch(error => {
                            console.error("Error al obtener las prestaciones:", error);
                            // Manejar el error de acuerdo a tu lógica
                        });
                } else {
                    that.funderivarCasoForzado(e);
                }
            },

            derivarCasoParalelo(e) {
                let orden = this.registro.act_data.act_orden;
                let that = this;
                if (orden == '30' && this.registro.act_data.act_siguiente != 20) {
                    this.GetSolicitudPrestaciones(this.registro.cas_id, e, this.registro.cas_data)
                        .then(idprestaciones => {
                            //alert(idprestaciones);
                            that.derivarCasoParalelo2(e);
                        })
                        .catch(error => {
                            console.error("Error al obtener las prestaciones:", error);
                            // Manejar el error de acuerdo a tu lógica
                        });
                }
                else {
                    that.derivarCasoParalelo2(e);
                }
            },

            derivarCasoParalelo2(e) {
                this.loading = true;
                this.paralelos.forEach(paralelo => {
                    let gRegistro = this.registro;
                    gRegistro.cas_estado = 'A';
                    gRegistro.cas_act_id = paralelo.siguiente_data.act_id;
                    gRegistro.cas_nodo_id = paralelo.siguiente_data.act_nodo_id;
                    gRegistro.cas_usr_id = this.usrId;
                    let that = this;

                    console.log('gRegistroresponse.data', JSON.stringify(gRegistro));
                    let url = "api/casosDerivarParalelo/" + this.registro.cas_id;
                    let arrayUsrNodo = [];
                    let ultimoUsr = 0;
                    let usrTomar = 0;
                    let act_orden = paralelo.siguiente_data.act_data.act_orden;
                    let act_nodo = paralelo.siguiente_data.act_nodo_id;
                    let act_tipo_derivacion = paralelo.siguiente_data.act_data.act_tipo_derivacion;

                    axios.put(url, gRegistro)
                        .then(function (response) {
                            that.output = response.data;
                            console.log('response.data', response.data);
                            if (response.status == "200") {
                                let tipoDer = paralelo.siguiente_data.act_data.act_tipo_derivacion;
                                // const datosv = {  cas_id: that.registro.cas_id};
                                // axios.post('api/VerificacionNodoSiguienteParalelo', datosv)
                                // .then(responsev => {
                                if (tipoDer == 'ENVIO' && act_nodo == 82) {
                                    console.log("mando a Jose");

                                    that.enviarseguimientoTramites(response.data.data, 'EAP', 'paralelo');
                                } else {
                                    if (tipoDer == 'ENVIO' && act_nodo == 73) {
                                        that.enviarseguimientoTramites(response.data.data, 'PRES', 'paralelo');
                                    } else {
                                        console.log("NO mando");
                                        // aqui validar round robijn o sel service.
                                        console.log("tipoDer: ", tipoDer);
                                        if (tipoDer == 'ROUND_ROBIN') {
                                            console.log("entra aqui");
                                            // a partir de aquí Round-robin
                                            // indice = -1
                                            let indice = -1;
                                            // 1. leer los usuarios del nodo
                                            // con el servicio GET usrNodosXNodoId/{nodo_id} OK servicio programado
                                            console.log("paralelo.siguiente_data.act_nodo_id: ", paralelo.siguiente_data.act_nodo_id);
                                            let url2 = "api/usrNodosXNodoId/" + paralelo.siguiente_data.act_nodo_id;
                                            axios.get(url2)
                                                .then(function (response2) {
                                                    let array = response2.data.data;
                                                    // 2. leer los registros en un array local
                                                    for (var i = 0; i < array.length; i++) {
                                                        var a = array[i].id;
                                                        arrayUsrNodo[i] = a;
                                                    }
                                                    // 3. leer el ultimo_usr de rmx_vys_usrnodos_roundrobin
                                                    // con el servicio GET usrNodosRR/{nodo_id}
                                                    // si no hay registros LEN==0,
                                                    // entonces index=0 para JUGAR con arreglo
                                                    // si es -1 va a 4. sino 5.
                                                    let url3 = "api/usrNodosRR/" + paralelo.siguiente_data.act_nodo_id;
                                                    axios.get(url3)
                                                        .then(function (response3) {
                                                            ultimoUsr = response3.data.data[0].rr_ultimo_usr_id;
                                                            // 4. JUGAR con el arreglo para saber el "siguiente usuario"
                                                            // forzar usar el servicio PUT estadoCaso/{cas_id}
                                                            // con body
                                                            // para tomar el usuario <------??? analizar
                                                            if (ultimoUsr == 0) {
                                                                usrTomar = arrayUsrNodo[0];
                                                            } else {
                                                                for (var j = 0; j < arrayUsrNodo.length; j++) {
                                                                    if (arrayUsrNodo[j] == ultimoUsr) {
                                                                        if (j + 1 == arrayUsrNodo.length) {
                                                                            usrTomar = arrayUsrNodo[0];
                                                                        } else {
                                                                            usrTomar = arrayUsrNodo[j + 1];
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            that.tomarCasoRoundRobin(usrTomar, response.data.data);
                                                            // 5. Actualizar el ultimo usuario
                                                            // en servicio PUT usrNodosRR/{nodo_id}
                                                            // con body "siguiente usuario"
                                                            let gData = {};
                                                            gData.nodo_id = paralelo.siguiente_data.act_nodo_id;
                                                            gData.ult_usr = usrTomar;
                                                            let url = "api/usrNodosRR";
                                                            axios.put(url, gData)
                                                                .then(function (response) {
                                                                    that.output = response.data;
                                                                    that.recargar();
                                                                })
                                                                .catch(function (error) {
                                                                    that.output = error;
                                                                });
                                                        })
                                                        .catch(function (error2) {
                                                            that.output = error2;
                                                        });
                                                })
                                                .catch(function (error2) {
                                                    that.output = error2;
                                                });
                                        } else { //if( tipoDer == 'SELF_SERVICE'){
                                            that.recargar();
                                        }
                                        that.loading = false;
                                    }
                                }
                            }
                        })
                        .catch(function (error) {
                            that.output = error;
                            that.loading = false;
                        });
                });
            },

            derivarCasoEnmienda(e) {
                this.loading = true;
                let that = this;
                const data = {
                    cas_id: this.registro.cas_id,
                    cas_usr_id: this.usrId,
                    cas_estado: 'T',
                };
                console.log('documentos datoss 5=====>', data);
                axios.post('api/derivarenmienda', data)
                    .then(response => {
                        that.loading = false;
                        that.recargar();
                        Swal.fire('El caso fue derivado a: ', response.data.data[0].vrespuesta, 'success');

                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                        that.loading = false;
                    });
            },

            derivarCasoUnion(e) {
                console.log('derivarCasoUnion');
                this.loading = true;
                //derivar
                let gRegistro = this.registro;
                gRegistro.cas_estado = 'A';
                gRegistro.cas_act_id = this.siguiente.act_id;
                gRegistro.cas_nodo_id = this.siguiente.act_nodo_id;
                gRegistro.cas_usr_id = this.usrId;
                let that = this;
                let url = "api/casosDerivarUnion/" + this.registro.cas_id;
                let act_nodo = this.siguiente.act_nodo_id;
                axios.put(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        if (response.status == "200") {
                            that.recargar();
                        }
                        that.loading = false;
                    })
                    .catch(function (error) {
                        that.output = error;
                        that.loading = false;
                    });
            },
            cambioAutomatico(cas_id) {
                console.log('cambioAutomatico');
                console.log('cambioAutomatico', this.registro.cas_id);
                console.log('cas_id ::', cas_id);
                const datos = {
                    cas_id: this.registro.cas_id,
                    estado: 'A'
                };
                axios.post('api/cambioEstadoEap', datos)
                    .then(response => {
                        console.log('response datos ', response.data.data);
                    })
                    .catch(error => {
                        console.error('Error al registrar el ID_SOLICITUDPRESTACION', error);
                    });
            },

            async registrarPoder(idCaso){
                const url = "api/quarkusTramitesPoder";
                const parameters = {"casId":idCaso};
                return axios.post(url, parameters);
            },

            async derivarCaso(e) {
                let orden = this.registro.act_data.act_orden;
                let that = this;
                let act_nodo = this.siguiente.act_nodo_id;
                let act_id = this.siguiente.act_id;
                let act_orden_v = this.siguiente.act_data.act_orden;
                let act_actual = this.registro.act_data.act_orden;
                let uid = this.registro.cas_data.UID;
                const partesArray = that.registro.cas_cod_id.split('/');
                let tipoTramite = this.registro.cas_cod_id.split('/')[0];
                //2025-04-15
                if(tipoTramite === 'LEGAL' && act_actual === '51' && act_orden_v === '60'){
                    try{
                        //1) verificamos si corresponde registrar el poder = f(estado-derivacion)
                        const responseRegistroPoder = await this.registrarPoder(that.registro.cas_id);
                    } catch(e){
                        // Extract the error message
                        const errorMessage = (e.response && e.response.data && e.response.data.message)
                                            ? e.response.data.message
                                            : 'Caso LEGAL: Error al registrar el Poder...';
                            Swal.fire({
                            title: '<strong>Error</strong>',
                            html: `
                            <div> Error al Registrar el Poder:
                            ${errorMessage}
                            </div>
                            `,
                            icon: 'error'
                        });
                        return;
                    }
                }

                if (tipoTramite == 'JUB1582' && this.registro.act_data.act_siguiente == 80) {
                    var params = {
                        "_id": uid,
                        "_tipoActualizacion": 'C',
                        "_rutaDocumento": 'ddd',
                        "_nroTramite": this.registro.cas_cod_id,
                        "_usuario": this.usrUser
                    };
                    let url2 = "api/prestaciones1582";
                    axios.put(url2, params).then(response => {
                        console.log(response); //twice data
                    });
                }
                if (orden == '30' && this.registro.act_data.act_siguiente != 20) {
                    console.log("that..registro.cas_data.ESTADO_DERIVACION: ", this.registro.cas_data.ESTADO_DERIVACION );
                    this.GetSolicitudPrestaciones(this.registro.cas_id, e, this.registro.cas_data)
                        .then(idprestaciones => {
                            if (that.siguiente.act_data.act_tipo_derivacion == 'ENVIO') { //********
                                if (act_nodo == 82) {// UCPP
                                    if (act_actual == '65' || act_actual == '61' || act_actual == '55' || act_actual == '63' || act_actual == '116' || act_actual == '109') {
                                        if ((act_actual == '65' || act_actual == '61') && that.registro.cas_data.ESTADO_DERIVACION=='OBSERVADO'){
                                            console.log("that.enviarseguimientoTramites " );
                                            //that.enviarseguimientoTramites(that.registro.cas_id, 'EAP', 'uno');
                                        } else {
                                            console.log("that.enviarseguimientoTramites_volver" );
                                            //that.enviarseguimientoTramites_volver(that.registro.cas_id, 'EAP');
                                        }
                                    } else if (act_actual == '50' && partesArray[0] == 'PM') {
                                        that.enviarseguimientoTramites_volver(that.registro.cas_id, 'EAP');
                                    }
                                    else {
                                        that.enviarseguimientoTramites(that.registro.cas_id, 'EAP', 'uno');
                                    }
                                } else {
                                    if (act_nodo == 73 && partesArray[0] == 'PAGCC') {

                                        that.enviarseguimientoTramites(that.registro.cas_id, 'CCM', 'uno');
                                        this.cambioAutomatico(that.registro.cas_id);

                                    } else if (act_nodo == 73 && partesArray[0] == 'RMIN') {
                                        console.log(' if de  RMIN===> ', act_nodo);
                                        that.enviarseguimientoTramites(that.registro.cas_id, 'RMIN', 'uno');
                                        this.cambioAutomatico(that.registro.cas_id);
                                    }
                                    else if (act_nodo == 73 && partesArray[0] == 'MAHER') {
                                        console.log(' if de  MAHER===> ', act_nodo);
                                        that.enviarseguimientoTramites(that.registro.cas_id, 'MH', 'uno');
                                        this.cambioAutomatico(that.registro.cas_id);
                                    }
                                    else if (act_nodo == 73 && partesArray[0] == 'GFU') {
                                        console.log(' if de  GASTOS FUNERARIOS ===> uno', act_nodo);
                                        that.enviarseguimientoTramites(that.registro.cas_id, 'GFU', 'uno');
                                        this.cambioAutomatico(that.registro.cas_id);
                                    }
                                    else if (act_nodo == 73) {
                                        that.enviarseguimientoTramites(that.registro.cas_id, 'PRES', 'uno');
                                    }
                                }
                            } else {
                                that.derivarCaso2(e);
                            }
                        })
                        .catch(error => {
                            console.error("Error al obtener las prestaciones:", error);
                            // Manejar el error de acuerdo a tu lógica
                        });
                } else {
                    if (
                        (orden == '116' && tipoTramite == 'INV' && this.registro.act_data.act_siguiente == 62) ||
                        (orden == '52' && tipoTramite == 'INV' && this.registro.act_data.act_siguiente == 62) ||
                        (orden == '108' && tipoTramite == 'INV' && this.registro.act_data.act_siguiente == 62) ||
                        (orden == '48' && tipoTramite == 'PM' && this.registro.act_data.act_siguiente == 62) ||
                        (orden == '116' && tipoTramite == 'PM' && this.registro.act_data.act_siguiente == 62) ||
                        (orden == '108' && tipoTramite == 'PM' && this.registro.act_data.act_siguiente == 62)
                    ) {
                        // if ((orden == '52' && tipoTramite == 'INV') || (orden == '48' && tipoTramite == 'PM')) {
                    // alert('entra aca');
                        that.dictamenRegistro();
                    } else if ((orden == '63' && tipoTramite == 'INV' && this.registro.act_data.act_siguiente == 62) || (orden == '63' && tipoTramite == 'PM' && this.registro.act_data.act_siguiente == 62)) {
                        //alert('entra aca2');
                        that.dictamenRegistroObservado();
                    }
                    else {
                    console.log("that..registro.cas_data.ESTADO_DERIVACION: ", that.registro.cas_data.ESTADO_DERIVACION );
                    console.log("act_actual: ", act_actual );
                        if (that.siguiente.act_data.act_tipo_derivacion == 'ENVIO') { //********
                            if (act_nodo == 82) {// UCPP
                                if (act_actual == '65' || act_actual == '61' || act_actual == '55' || act_actual == '63' || act_actual == '116' || act_actual == '109') {
                                        if ((act_actual == '65' || act_actual == '61') && that.registro.cas_data.ESTADO_DERIVACION=='OBSERVADO'){
                                            //console.log("that.enviarseguimientoTramites " );
                                            that.enviarseguimientoTramites(that.registro.cas_id, 'EAP', 'uno');
                                        } else {
                                            //console.log("that.enviarseguimientoTramites_volver" );
                                            that.enviarseguimientoTramites_volver(that.registro.cas_id, 'EAP');
                                        }
                                } else if (act_actual == '50' && partesArray[0] == 'PM') {
                                    that.enviarseguimientoTramites_volver(that.registro.cas_id, 'EAP');
                                }
                                else {
                                    that.enviarseguimientoTramites(that.registro.cas_id, 'EAP', 'uno');
                                }
                            } else {
                                if (act_nodo == 73 && partesArray[0] == 'PAGCC') {
                                    that.enviarseguimientoTramites(that.registro.cas_id, 'CCM', 'uno');
                                    this.cambioAutomatico(that.registro.cas_id);
                                } else if (act_nodo == 73 && partesArray[0] == 'RMIN') {
                                    console.log(' if de  RMIN===> ', act_nodo);
                                    that.enviarseguimientoTramites(that.registro.cas_id, 'RMIN', 'uno');
                                    this.cambioAutomatico(that.registro.cas_id);
                                } else if (act_nodo == 73 && partesArray[0] == 'MAHER') {
                                    console.log(' if de  MAHER===> ', act_nodo);
                                    that.enviarseguimientoTramites(that.registro.cas_id, 'MH', 'uno');
                                    this.cambioAutomatico(that.registro.cas_id);
                                }
                                else if (act_nodo == 73 && partesArray[0] == 'GFU') {
                                    console.log(' if de  GASTOS FUNERARIOS ===> uno', act_nodo);
                                    that.enviarseguimientoTramites(that.registro.cas_id, 'GFU', 'uno');
                                    this.cambioAutomatico(that.registro.cas_id);
                                }
                                else if (act_nodo == 73) {
                                    that.enviarseguimientoTramites(that.registro.cas_id, 'PRES', 'uno');
                                }
                            }
                        } else {
                            that.derivarCaso2(e);
                        }
                    }
                }
            },

            async resetFirmaDoc(e) {
                const cas_id = this.registro.cas_id;
                const cas_act_id = this.registro.cas_act_id;

                const params = { "cas_id": cas_id, "act_id": cas_act_id, "tipo": "derivar" };

                const url = "api/resetearFirmaDoc";
                return axios.post(url, params).then(response => {
                    return true;
                });
            },

            async resetearFirmaDocDesp(e) {
                const cas_id = this.registro.cas_id;
                const cas_act_id = this.registro.cas_act_id;

                const params = { "cas_id": cas_id, "act_id": cas_act_id, "tipo": "derivar" };
                console.log("#### >>> params resetearFirmaDocDesp", params);
                const url = "api/resetearFirmaDocDesp";
                return axios.post(url, params).then(response => {
                    return true;
                });
            },

            derivarCaso2(e) {
                console.log("IMPRESIONES FINAL >>> ", this.impresiones);

                let act_actual = this.registro.act_data.act_orden;
                this.loading = true;
                let tipoEap = this.registro.cas_data.AS_TIPO_EAP;
                //derivar
                let gRegistro = this.registro;
                gRegistro.cas_estado = 'A';
                gRegistro.cas_act_id = this.siguiente.act_id;
                gRegistro.cas_nodo_id = this.siguiente.act_nodo_id;
                gRegistro.cas_usr_id = this.usrId;
                let that = this;
                console.log(JSON.stringify(gRegistro));
                //********************* */

                let url = "api/casosDerivar/" + this.registro.cas_id + '?usuario=' + this.usrUser;
                let arrayUsrNodo = [];
                let ultimoUsr = 0;
                let usrTomar = 0;
                let act_nodo = this.siguiente.act_nodo_id;
                let act_id = this.siguiente.act_id;
                let act_orden_v = this.siguiente.act_data.act_orden;
                axios.put(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        if (response.status == "200") {
                            // aqui validar round robijn o sel service.
                            let tipoDer = that.siguiente.act_data.act_tipo_derivacion;
                            if (tipoDer == 'ROUND_ROBIN') {
                                console.log("🚀 ~ tipoDer:", tipoDer)
                                // a partir de aquí Round-robin
                                // indice = -1
                                let indice = -1;
                                // 1. leer los usuarios del nodo
                                // con el servicio GET usrNodosXNodoId/{nodo_id} OK servicio programado
                                console.log("🚀 ~ that.siguiente:", that.siguiente.act_nodo_id)
                                let url2 = "api/usrNodosXNodoId/" + that.siguiente.act_nodo_id;

                                axios.get(url2)
                                    .then(function (response2) {
                                        let array = response2.data.data;
                                        // 2. leer los registros en un array local
                                        for (var i = 0; i < array.length; i++) {
                                            var a = array[i].id;
                                            arrayUsrNodo[i] = a;
                                        }
                                        // 3. leer el ultimo_usr de rmx_vys_usrnodos_roundrobin
                                        // con el servicio GET usrNodosRR/{nodo_id}
                                        // si no hay registros LEN==0,
                                        // entonces index=0 para JUGAR con arreglo
                                        // si es -1 va a 4. sino 5.
                                        console.log("🚀 ~ that.siguiente.act_nodo_id usrNodosRR:", that.siguiente.act_nodo_id)
                                        let url3 = "api/usrNodosRR/" + that.siguiente.act_nodo_id;

                                        axios.get(url3)
                                            .then(function (response3) {

                                                ultimoUsr = response3.data.data[0].rr_ultimo_usr_id;
                                                console.log("🚀 ~  response3.data.data[0].rr_ultimo_usr_id:", response3.data.data[0].rr_ultimo_usr_id)
                                                // 4. JUGAR con el arreglo para saber el "siguiente usuario"
                                                // forzar usar el servicio PUT estadoCaso/{cas_id}
                                                // con body
                                                // para tomar el usuario <------??? analizar
                                                console.log("🚀 ~ arrayUsrNodo[0]:", arrayUsrNodo)
                                                if (ultimoUsr == 0) {
                                                    usrTomar = arrayUsrNodo[0];

                                                } else {
                                                    for (var j = 0; j < arrayUsrNodo.length; j++) {
                                                        if (arrayUsrNodo[j] == ultimoUsr) {
                                                            if (j + 1 == arrayUsrNodo.length) {
                                                                usrTomar = arrayUsrNodo[0];
                                                            } else {
                                                                usrTomar = arrayUsrNodo[j + 1];
                                                            }
                                                        }
                                                    }
                                                }
                                                that.tomarCasoRoundRobin(usrTomar, that.registro.cas_id);
                                                // 5. Actualizar el ultimo usuario
                                                // en servicio PUT usrNodosRR/{nodo_id}
                                                // con body "siguiente usuario"
                                                let gData = {};
                                                gData.nodo_id = that.siguiente.act_nodo_id;
                                                gData.ult_usr = usrTomar;
                                                let url = "api/usrNodosRR";
                                                axios.put(url, gData)
                                                    .then(function (response) {
                                                        that.output = response.data;
                                                        that.recargar();
                                                    })
                                                    .catch(function (error) {
                                                        that.output = error;
                                                    });
                                            })
                                            .catch(function (error2) {
                                                that.output = error2;
                                            });
                                    })
                                    .catch(function (error2) {
                                        that.output = error2;
                                    });
                            } else { //if( tipoDer == 'SELF_SERVICE'){
                                that.recargar();
                            }
                            that.loading = false;
                        }
                    })
                    .catch(function (error) {
                        that.output = error;
                        that.loading = false;
                    });

                // //**************************** */

            },

            //***********SERVICIO DE ADAD *************** */
            GetSolicitudPrestaciones(idcaso, e, cas_data) {
                return new Promise((resolve, reject) => {
                    console.log("INGRESANDO===>ADAD = >");
                    let usuario_registro = cas_data.USUARIO_REGISTRO;
                    let that = this;
                    const datos = {
                        cas_act_id: idcaso
                    };

                    axios.post('/api/SolicitudPrestacion', datos)
                        .then(response => {
                            if (response.data.data.length === 0) {
                                Swal.fire('Caso sin datos, no se puede derivar', '', 'warnig');
                            } else {
                                // Convertir las cadenas JSON en objetos JavaScript
                                const jsonData = JSON.parse(response.data.data[0].vjsondata) ?? [];

                                console.log("jsonData##################### >>> ", jsonData);

                                const jsonDataDoc = JSON.parse(response.data.data[0].vjsondoc) ?? [];
                                const jsonDataDH = JSON.parse(response.data.data[0].vderechohabiente) ?? [];
                                const jvcodigogeograficoid = response.data.data[0].vcodigogeograficoid; //
                                let vtipoproceso = response.data.data[0].vtipoproceso; // Cambiar const a let

                                if (vtipoproceso === 'JUB1582') {
                                    var _vtipoproceso = vtipoproceso;
                                    vtipoproceso = 'JUB';
                                } else {
                                    var _vtipoproceso = vtipoproceso;
                                }

                                // Inicializar el array de datosReferenciales
                                const varReferenciales = [];
                                const varReferencialesTitular = [];
                                // Recorrer jsonData y llenar datosReferenciales
                                var SOL_IDPERSONA = '';
                                var AS_IDPERSONA = '';
                                var AS_ENTE_GESTOR = '';
                                var FECHA_SOLICITUD = '';
                                var FECHA = '';

                                var tipoproceso = '';
                                jsonData.forEach(item => {

                                    let codigoTipoReferencia;

                                    let codigoTipoReferenciaTitular;
                                    let datoReferencia;
                                    let datoReferenciaTitular;
                                    // Llenar valores según el frm_campo

                                    switch (item.frm_campo.trim()) {
                                        case "TIPO_PROCESO":
                                            tipoproceso = item.frm_value || ""; // Puedes ajustar cómo manejas los valores nulos
                                            break;
                                        case "SOL_CELULAR":
                                            codigoTipoReferencia = "TELEF";
                                            datoReferencia = item.frm_value || ""; // Puedes ajustar cómo manejas los valores nulos
                                            break;
                                        case "SOL_CORREO":
                                            codigoTipoReferencia = "EMAIL";
                                            datoReferencia = item.frm_value || "";
                                            break;
                                        case "SOL_IDPERSONA":
                                            SOL_IDPERSONA = item.frm_value;
                                            break;
                                        case "AS_CELULAR":
                                            codigoTipoReferenciaTitular = "TELEF";
                                            datoReferenciaTitular = item.frm_value || ""; // Puedes ajustar cómo manejas los valores nulos
                                            break;
                                        case "AS_CORREO":
                                            codigoTipoReferenciaTitular = "EMAIL";
                                            datoReferenciaTitular = item.frm_value || "";
                                            break;
                                        case "AS_IDPERSONA":
                                            AS_IDPERSONA = item.frm_value;
                                            break;
                                        case "SOL_DIRECCION":
                                            codigoTipoReferencia = "DIREC-DOM";
                                            datoReferencia = item.frm_value || "";
                                            break;
                                        case "AS_ENTE_GESTOR":
                                            AS_ENTE_GESTOR = item.frm_value || "";
                                            break;
                                        case "FECHA_INICIO_TRAMITE":
                                            FECHA_SOLICITUD = item.frm_value || "";
                                            break;
                                        case "FORM_JUB_FECHA":
                                            FECHA_SOLICITUD = item.frm_value || "";
                                            break;
                                        case "_FECHA":
                                            FECHA = item.frm_value || "";
                                            break;
                                        default:
                                            // Puedes manejar otros casos o simplemente ignorarlos
                                            break;
                                    }

                                    if (FECHA_SOLICITUD) {
                                        FECHA_SOLICITUD = FECHA_SOLICITUD;
                                    } else if (FECHA) {
                                        FECHA_SOLICITUD = FECHA;
                                    }

                                    // Agregar el objeto al array datosReferenciales
                                    if (codigoTipoReferencia == undefined) { } else {
                                        varReferenciales.push({
                                            "codigoTipoReferencia": codigoTipoReferencia,
                                            "datoReferencia": datoReferencia
                                        });
                                    }
                                    if (codigoTipoReferenciaTitular == undefined) { } else {
                                        varReferencialesTitular.push({
                                            "codigoTipoReferencia": codigoTipoReferenciaTitular,
                                            "datoReferencia": datoReferenciaTitular
                                        });
                                    }
                                });

                                // console.log("FECHA_SOLICITUD", FECHA_SOLICITUD);
                                // //2025-04-11

                                // Parsear los strings JSON
                                // Inicializar el array participantesSolicitud
                                const participantesSolicituddh = [];
                                // Recorrer jsonDataDH y unir con jsonDataDoc

                                if (vtipoproceso != 'GFU') {
                                    console.log("INGRESANDO .... ", jsonDataDH);
                                    jsonDataDH.forEach((dhArray, index) => {
                                        var respaldosArray = this.buscarContenidoPorIdPersonaSip(jsonDataDoc, dhArray.find(item => item.col_campo === "DH_IDPERSONA_GRILLA_PROP").col_value);
                                        console.log("respaldosArray>>>>>> GESTORA", respaldosArray);

                                        var valorOriginal = dhArray.find(item => item.col_campo === "DH_PARENTESCO").col_value;
                                        var valorDeseado = "";
                                        if (/^\d-/.test(valorOriginal)) {
                                            var partes = valorOriginal.split('-');
                                            if (partes.length === 2) {
                                                valorDeseado = partes[1];
                                            }
                                        } else {
                                            valorDeseado = valorOriginal;
                                        }

                                        const participante = {
                                            "codigoTipoParentesco": valorDeseado,
                                            "idPersonaSip": dhArray.find(item => item.col_campo === "DH_IDPERSONA_GRILLA_PROP").col_value,
                                            "porcentajeBeneficiario": dhArray.find(item => item.col_campo === "DH_GRADO") ? dhArray.find(item => item.col_campo === "DH_GRADO").col_value : null,
                                            "estadoInvalidez": dhArray.find(item => item.col_campo === "DH_INVALIDEZ") ? dhArray.find(item => item.col_campo === "DH_INVALIDEZ").col_value : null,
                                            "datosReferenciales": [{
                                                "codigoTipoReferencia": "TELEF",
                                                "datoReferencia": dhArray.find(item => item.col_campo === "DH_NRO_CELULAR") ? dhArray.find(item => item.col_campo === "DH_NRO_CELULAR").col_value : null
                                            }, {
                                                "codigoTipoReferencia": "EMAIL",
                                                "datoReferencia": dhArray.find(item => item.col_campo === "DH_CORREO") ? dhArray.find(item => item.col_campo === "DH_CORREO").col_value : null
                                            }],
                                            "respaldos": respaldosArray
                                        };
                                        participantesSolicituddh.push(participante);
                                    });
                                }
                                //*************************TITULAR******************* */
                                const respaldosArrayT = this.buscarContenidoPorIdPersonaSip(jsonDataDoc, AS_IDPERSONA.replace(/\s/g, ''));
                                const participanteT = {
                                    "codigoTipoParentesco": "TIT",
                                    "idPersonaSip": AS_IDPERSONA.replace(/\s/g, ''),
                                    "estadoInvalidez": false, // Puedes cambiar este valor según tus necesidades
                                    "datosReferenciales": varReferencialesTitular,
                                    "respaldos": respaldosArrayT
                                };
                                participantesSolicituddh.push(participanteT);
                                // El array de datosReferenciales estará lleno con los valores correspondientes

                                if ((vtipoproceso == 'GFU' || vtipoproceso == 'MAHER') || _vtipoproceso == 'JUB1582') {
                                    AS_ENTE_GESTOR = null;
                                }
                                console.log("AS_ENTE_GESTOR", AS_ENTE_GESTOR);
                                const output = {
                                    codigoSolicitud: response.data.data[0].vcodigosolicitud,
                                    codigoEnteGestorSalud: AS_ENTE_GESTOR === "null" || AS_ENTE_GESTOR === "" ? null : AS_ENTE_GESTOR,
                                    fechaSolicitud: FECHA_SOLICITUD,
                                    usuarioRegistro: usuario_registro,
                                    idCodigoGeograficoRegistro: jvcodigogeograficoid,
                                    idCodigoGeograficoPago: jvcodigogeograficoid,
                                    solicitante: {
                                        idPersonaSip: SOL_IDPERSONA, // Puedes obtener este valor de alguna parte del JSON
                                        datosReferenciales: varReferenciales,
                                        respaldos: null
                                    },
                                    participantesSolicitud: participantesSolicituddh
                                };

                                console.log("output", output);

                                //*******ini***servicio de token
                                const cadenaJSON = JSON.stringify(output);
                                console.log("cadenaJSON >> ", cadenaJSON);
                                const urlToken = this.urlGestora + '/str-seg-aut-rest/autenticacion/funcionarios/token/obtener/pass';
                                const datos = this.CREDENCIALES;

                                axios.post(urlToken, datos)
                                    .then(respuesta => {
                                        if (respuesta.data.ok) {
                                            this.token = respuesta.data.data.accessToken;

                                            //*****ini servicio adad

                                            const url = this.urlGestora + '/spr-tram-rest/api/solicitudPrestacion/crearPorTipoPrestacionOrigen?codigoTipoPrestacion=' + vtipoproceso;
                                            let config = {
                                                headers: {
                                                    'Authorization': 'Bearer ' + this.token
                                                    //'Authorization': 'Bearer ' + window.Laravel.access_token
                                                }
                                            }
                                            axios.post(url, output, config)
                                                .then(respuesta => {
                                                    const datos = {
                                                        cas_id: this.registro.cas_id,
                                                        cas_usr_id: this.registro.act_usr_id,
                                                        tipo: 'PLANILLAS',
                                                        url: url,
                                                        output: output,
                                                        config: config,
                                                        response: respuesta,
                                                        usuario: this.usrUser,
                                                        cas_cod_id: output.codigoSolicitud
                                                    };
                                                    console.log("respuesta logs enviar ",datos);
                                                    axios.put('api/guardarLogApi', datos)
                                                        .then(response => {
                                                            if (response.data.codigoRespuesta === 200) {
                                                                console.log("Log guardado exitosamente:", response.data);
                                                            } else {
                                                                console.warn("no se guardará el log.");
                                                            }
                                                        })
                                                        .catch(error => {
                                                            console.error("error al guardar el log:", error);
                                                        });

                                                    if (respuesta.data.mensaje.codigo == "0" || respuesta.data.mensaje.codigo == null) {
                                                        const datos = {
                                                            cas_id: this.registro.cas_id,
                                                            idsolicitudprestacion: respuesta.data.data.idSolicitudPrestacion,
                                                            cas_usr_id: this.registro.act_usr_id
                                                        };

                                                        axios.post('api/SetIDSolitudPrestacion', datos)
                                                            .then(response => {
                                                                const message = response.data.message;
                                                                if (message == "El valor ha sido modificado correctamente.") {
                                                                    console.log("===2==si");
                                                                    resolve(respuesta.data.data.idSolicitudPrestacion);
                                                                } else {
                                                                    if (message == "Se agregó el campo 'ID_SOLICITUDPRESTACION' correctamente.") {
                                                                        resolve(respuesta.data.data.idSolicitudPrestacion);
                                                                    }
                                                                }

                                                            })
                                                            .catch(error => {
                                                                Swal.close(); // Cerrar la alerta de carga
                                                                console.error('Error al registrar el ID_SOLICITUDPRESTACION', error);
                                                            });

                                                    } else if (respuesta.data.mensaje.codigo == "1000") {
                                                        console.log("POR CODIGO 1000");
                                                        const datos = {
                                                            cas_id: this.registro.cas_id,
                                                            idsolicitudprestacion: respuesta.data.data.idSolicitudPrestacion
                                                        };

                                                        console.log("datos", datos);
                                                        axios.post('api/GetIdsolicitudprestacion', datos)
                                                            .then(response => {
                                                                console.log("EL TAMAÑO ", response.data.data.length);
                                                                console.log("LO QUE LLEGA DEL RESPONSE >>> ", response.data.data);

                                                                if (response.data.data.length > 0) {
                                                                    console.log("POR TRUE");
                                                                    resolve(response.data.data[0].frm_value);
                                                                }
                                                                else {
                                                                    console.log("POR FALSE");
                                                                    Swal.fire('El caso tiene : ', respuesta.data.mensaje.detalles[0], 'warning');
                                                                }
                                                            })
                                                            .catch(error => {
                                                                Swal.close(); // Cerrar la alerta de carga
                                                                console.error('Error al registrar el ID_SOLICITUDPRESTACION', error);
                                                            });


                                                    }

                                                })
                                                .catch(error => {
                                                    console.log("ERROR 500");

                                                    // console.log("EL ERROR >> ", error);
                                                    // const datos = {
                                                    //     cas_id: this.registro.cas_id,
                                                    //     cas_usr_id: this.registro.act_usr_id,
                                                    //     tipo: 'PLANILLAS',
                                                    //     url: url,
                                                    //     output: output,
                                                    //     config: config,
                                                    //     response: error,
                                                    //     usuario: this.usrUser,
                                                    //     cas_cod_id: this.codigoSolicitud,
                                                    //     codigo: 500,
                                                    // };

                                                    // axios.put('api/guardarLogApi', datos)
                                                    //     .then(response => {
                                                    //         console.log("Log guardado exitosamente:", response.data);
                                                    //     })
                                                    //     .catch(error => {
                                                    //         console.error("Error al guardar el log:", error);
                                                    //     });

                                                    Swal.close(); // Cerrar la alerta de carga
                                                    console.error('Error en la solicitud:', error);
                                                    // Manejar el error según tus necesidades
                                                });
                                            //*****fin servicio de adad
                                        }
                                    })
                                    .catch(error => {
                                        Swal.close(); // Cerrar la alerta de carga
                                        console.error('Error en la solicitud:', error);
                                        // Manejar el error según tus necesidades
                                    });
                                //*******fin***servicio de token

                            }
                            that.loading = false;

                        })
                        .catch(error => {
                            console.error('Erbror al generar la solicitud', error);
                        });
                });
            },
            //************FIN DE SERVICIO ADAD*********** */

            buscarContenidoPorIdPersonaSip(data, idPersonaSip) {
                if (data === null) {
                    return [];
                }
                const result = [];
                data.forEach(docArray => {
                    if (Array.isArray(docArray)) {
                        docArray.forEach(item => {
                            if (item.doc_id_persona_sip.replace(/\s/g, '') === idPersonaSip) {
                                result.push({
                                    "idTipoDocumentoSolicitud": item.idTipoDocumentoSolicitud,
                                    "tipoDocumentoGuardado": item.tipoDocumentoGuardado,
                                    "idCodigoArchivo": item.idCodigoArchivo
                                });
                            }
                        });
                    }
                });
                return result.length > 0 ? result : [];
            },

            solicitudPrestacion(idcaso, e, cas_data) {
                let usuario_registro = cas_data.USUARIO_REGISTRO;
                let that = this;
                const datos = { cas_act_id: idcaso };

                axios.post('/api/SolicitudPrestacion', datos)
                    .then(response => {
                        if (response.data.data.length === 0) {
                            Swal.fire('Caso sin datos, no se puede derivar', '', 'warnig');
                        } else {
                            //console.log(response.data.data[0].vcodigosolicitud);
                            // Convertir las cadenas JSON en objetos JavaScript
                            const jsonData = JSON.parse(response.data.data[0].vjsondata) ?? [];
                            const jsonDataDoc = JSON.parse(response.data.data[0].vjsondoc) ?? [];
                            const jsonDataDH = JSON.parse(response.data.data[0].vderechohabiente) ?? [];
                            const jvcodigogeograficoid = response.data.data[0].vcodigogeograficoid;//
                            const vtipoproceso = response.data.data[0].vtipoproceso;//
                            // Inicializar el array de datosReferenciales
                            const varReferenciales = [];
                            const varReferencialesTitular = [];
                            // Recorrer jsonData y llenar datosReferenciales
                            var SOL_IDPERSONA = '';
                            var AS_IDPERSONA = '';
                            var AS_ENTE_GESTOR = '';
                            var tipoproceso = '';
                            jsonData.forEach(item => {
                                let codigoTipoReferencia;

                                let codigoTipoReferenciaTitular;
                                let datoReferencia;
                                let datoReferenciaTitular;
                                // Llenar valores según el frm_campo
                                switch (item.frm_campo) {
                                    case "TIPO_PROCESO":
                                        tipoproceso = item.frm_value || ""; // Puedes ajustar cómo manejas los valores nulos
                                        break;
                                    case "SOL_CELULAR":
                                        codigoTipoReferencia = "TELEF";
                                        datoReferencia = item.frm_value || ""; // Puedes ajustar cómo manejas los valores nulos
                                        break;
                                    case "SOL_CORREO":
                                        codigoTipoReferencia = "EMAIL";
                                        datoReferencia = item.frm_value || "";
                                        break;
                                    case "SOL_IDPERSONA":
                                        SOL_IDPERSONA = item.frm_value;
                                        break;
                                    case "AS_CELULAR":
                                        codigoTipoReferenciaTitular = "TELEF";
                                        datoReferenciaTitular = item.frm_value || ""; // Puedes ajustar cómo manejas los valores nulos
                                        break;
                                    case "AS_CORREO":
                                        codigoTipoReferenciaTitular = "EMAIL";
                                        datoReferenciaTitular = item.frm_value || "";
                                        break;
                                    case "AS_IDPERSONA":
                                        AS_IDPERSONA = item.frm_value;
                                        break;
                                    case "SOL_DIRECCION":
                                        codigoTipoReferencia = "DIREC-DOM";
                                        datoReferencia = item.frm_value || "";
                                        break;
                                    case "AS_ENTE_GESTOR":
                                        AS_ENTE_GESTOR = item.frm_value || "";
                                        break;
                                    default:
                                        // Puedes manejar otros casos o simplemente ignorarlos
                                        break;
                                }

                                // Agregar el objeto al array datosReferenciales
                                if (codigoTipoReferencia == undefined) { }
                                else {
                                    varReferenciales.push({
                                        "codigoTipoReferencia": codigoTipoReferencia,
                                        "datoReferencia": datoReferencia
                                    });
                                }
                                if (codigoTipoReferenciaTitular == undefined) { }
                                else {
                                    varReferencialesTitular.push({
                                        "codigoTipoReferencia": codigoTipoReferenciaTitular,
                                        "datoReferencia": datoReferenciaTitular
                                    });
                                }
                            });
                            // Parsear los strings JSON
                            // Inicializar el array participantesSolicitud
                            const participantesSolicituddh = [];
                            // Recorrer jsonDataDH y unir con jsonDataDoc
                            if (vtipoproceso != 'GFU') {
                                jsonDataDH.forEach((dhArray, index) => {
                                    const respaldosArray = this.buscarContenidoPorIdPersonaSip(jsonDataDoc, dhArray.find(item => item.col_campo === "DH_IDPERSONA_GRILLA_PROP").col_value);

                                    console.log("respaldosArray>>>> ", respaldosArray);

                                    const valorOriginal = dhArray.find(item => item.col_campo === "DH_PARENTESCO").col_value;
                                    const partes = valorOriginal.split('-');
                                    var valorDeseado = "";
                                    if (partes.length === 2) {
                                        valorDeseado = partes[1];
                                    }

                                    const participante = {
                                        "codigoTipoParentesco": valorDeseado,
                                        "idPersonaSip": dhArray.find(item => item.col_campo === "DH_IDPERSONA_GRILLA_PROP").col_value,
                                        "porcentajeBeneficiario": dhArray.find(item => item.col_campo === "DH_GRADO").col_value || null,
                                        "estadoInvalidez": dhArray.find(item => item.col_campo === "DH_INVALIDEZ").col_value || null,  // Puedes cambiar este valor según tus necesidades
                                        "datosReferenciales": [
                                            {
                                                "codigoTipoReferencia": "TELEF",
                                                "datoReferencia": dhArray.find(item => item.col_campo === "DH_NRO_CELULAR").col_value || null
                                            }, {
                                                "codigoTipoReferencia": "EMAIL",
                                                "datoReferencia": dhArray.find(item => item.col_campo === "DH_CORREO").col_value || null
                                            }
                                        ],
                                        "respaldos": respaldosArray
                                    };
                                    participantesSolicituddh.push(participante);
                                });
                            }
                            //*************************TITULAR******************* */
                            const respaldosArrayT = this.buscarContenidoPorIdPersonaSip(jsonDataDoc, AS_IDPERSONA.replace(/\s/g, ''));

                            const participanteT = {
                                "codigoTipoParentesco": "TIT",
                                "idPersonaSip": AS_IDPERSONA.replace(/\s/g, ''),
                                "estadoInvalidez": false,  // Puedes cambiar este valor según tus necesidades
                                "datosReferenciales": varReferencialesTitular,
                                "respaldos": respaldosArrayT
                            };
                            participantesSolicituddh.push(participanteT);
                            // El array de datosReferenciales estará lleno con los valores correspondientes
                            if (vtipoproceso != 'GFU' || vtipoproceso != 'MAHER') AS_ENTE_GESTOR = null;
                            const output = {
                                codigoSolicitud: response.data.data[0].vcodigosolicitud,
                                codigoEnteGestorSalud: AS_ENTE_GESTOR,
                                usuarioRegistro: usuario_registro,
                                idCodigoGeograficoRegistro: jvcodigogeograficoid,
                                idCodigoGeograficoPago: 2030109,
                                solicitante: {
                                    idPersonaSip: SOL_IDPERSONA,  // Puedes obtener este valor de alguna parte del JSON
                                    datosReferenciales: varReferenciales,
                                    respaldos: null
                                },
                                participantesSolicitud: participantesSolicituddh
                            };

                            const urlToken = this.urlGestora + '/str-seg-aut-rest/autenticacion/funcionarios/token/obtener/pass';
                            const datos = {
                                "login": "demo.gestora@gestora-demo.bo",
                                "password": "Tempo.2024@"
                            };
                            console.log("respuesta token: ");
                            axios.post(urlToken, datos)
                                .then(respuesta => {
                                    if (respuesta.data.ok) {
                                        this.token = respuesta.data.data.accessToken;
                                        console.log("respuesta token: ", this.token);

                                        const cadenaJSON = JSON.stringify(output);
                                        const url = this.urlGestora + '/spr-tram-rest/api/solicitudPrestacion/crearPorTipoPrestacionOrigen?codigoTipoPrestacion=' + vtipoproceso;
                                        let config = {
                                            headers: {
                                                'Authorization': 'Bearer ' + this.token
                                            }
                                        }
                                        axios.post(url, output, config)
                                            .then(respuesta => {
                                                console.log
                                                if (respuesta.data.mensaje.codigo == "5000") {
                                                    Swal.close();  // Cerrar la alerta de carga
                                                } else if (respuesta.data.mensaje.codigo == "1000") {//vuelve a derivar
                                                    that.funderivarCasoForzado(e);
                                                } else {
                                                    // Manejar la respuesta del servidor según tus necesidades
                                                    const datos = { cas_id: this.registro.cas_id, idsolicitudprestacion: respuesta.data.data.idSolicitudPrestacion, cas_usr_id: this.registro.act_usr_id };
                                                    axios.post('api/SetIDSolitudPrestacion', datos)
                                                        .then(response => {
                                                            const message = response.data.message;
                                                            if (message == "El valor ha sido modificado correctamente.") {
                                                                that.funderivarCasoForzado(e);
                                                            } else {
                                                                if (message == "Se agregó el campo 'ID_SOLICITUDPRESTACION' correctamente.") {
                                                                    that.funderivarCasoForzado(e);
                                                                }
                                                            }
                                                            Swal.close();  // Cerrar la alerta de carga
                                                        })
                                                        .catch(error => {
                                                            Swal.close();  // Cerrar la alerta de carga
                                                            console.error('Error al generar o abrir el PDF', error);
                                                        });
                                                }
                                            })
                                            .catch(error => {
                                                Swal.close();  // Cerrar la alerta de carga
                                                console.error('Error en la solicitud:', error);
                                                // Manejar el error según tus necesidades
                                            });
                                    }
                                })
                                .catch(error => {
                                    Swal.close();  // Cerrar la alerta de carga
                                    console.error('Error en la solicitud:', error);
                                    // Manejar el error según tus necesidades
                                });


                        }
                        that.loading = false;
                    })
                    .catch(error => {
                        console.error('Erbror al generar la solicitud', error);
                    });
            },

            funderivarCasoForzado(e) {
                console.log('    funderivarCasoForzado vercion dos ');


                //this.loading = true; // Mostrar mensaje de carga
                let usuarioDerivar = e.id;
                //derivar
                this.registro.cas_data.de_usuario = this.registro.cas_data.a_usuario;
                this.registro.cas_data.a_usuario = e.nom_usuario;
                let gRegistro = this.registro;
                gRegistro.cas_estado = 'T';
                gRegistro.cas_act_id = this.siguiente.act_id;
                gRegistro.cas_nodo_id = this.siguiente.act_nodo_id;
                gRegistro.cas_usr_id = usuarioDerivar;
                let that = this;
                let url = "api/casosDerivar/" + this.registro.cas_id + '?usuario=' + this.usrUser;
                let arrayUsrNodo = [];
                let ultimoUsr = 0;
                let usrTomar = 0;
                axios.put(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        that.recargar();
                        Swal.fire({
                            title: 'Derivado',
                            text: '',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });

                        that.loading = false;
                    })
                    .catch(function (error) {
                        that.output = error;
                        that.loading = false;
                    });
            },

            derivarCasoForzado(e) {
                Swal.fire({
                    title: '¿Estás seguro de realizar la derivación?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No',
                    onBeforeOpen: () => {
                        Swal.showLoading();  // Mostrar el indicador de carga
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.loading = true;
                        //SOLO DERIVAR
                        //this.solicitudPrestacion(this.registro.cas_id, e, this.registro.cas_data);
                        let that = this;
                        that.funderivarCasoForzado(e);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Lógica a ejecutar si el usuario selecciona 'No' o cancela
                        Swal.close();  // Cerrar la alerta de carga
                        //Swal.fire('Acción cancelada', '', 'info');
                    }
                });
            },

            doImprimir1(i, nombre, imp_tipo, firma) {
                //this.clearCanvas();
                this.$store.dispatch('updateNombreDocumento', nombre);
                if (imp_tipo == 2) {
                    const datos = { cas_id: this.registro.cas_id, tipo: 'Dibujar', firma: this.firma };
                    axios.post('api/generateFormRescepcionDocumento', datos)
                        .then(response => {
                            this.pdfSrc = response.data.mensaje;
                            // if (!firma) {
                            //     this.renderPDF(this.pdfSrc, 'pdfCanvas');
                            // }
                            this.renderPDF(this.pdfSrc, 'pdfCanvas');
                            this.$store.dispatch('updateRegistro', this.registro);
                            this.$refs.visorPDFComponent.initVisor(this.pdfSrc);

                            console.log("documento pdf para VISOR PDF");
                            console.log("this.pdfSrc: ", this.pdfSrc);
                        })
                        .catch(error => {
                            console.error('Error al generar o abrir el PDF', error);
                        });
                } else {
                    const datos = { act_id: this.registro.act_id, cas_id: this.registro.cas_id, act_usr_id: this.registro.cas_usr_id, impid: i, nombre_doc: nombre, tipo: 'Dibujar', firma: this.firma };

                    console.log("## PARA GENERAR EL PDF ##", datos);

                    axios.post('api/generatepdf', datos)
                        .then(response => {
                            console.log(response.data)
                            if (response.data.codigoRespuesta.code == '200') {
                                this.pdfSrc = response.data.data;
                                console.log("this.pdfSrc: ", this.pdfSrc);
                                // if (!firma) {
                                //     this.renderPDF(this.pdfSrc, 'pdfCanvas');
                                // }
                                this.renderPDF(this.pdfSrc, 'pdfCanvas');
                                this.$store.dispatch('updateRegistro', this.registro);
                                this.$refs.visorPDFComponent.initVisor(this.pdfSrc);
                            }
                            else if (response.data.codigoRespuesta.code == '300') {
                                $('#modalPrevisualizar').modal('hide');
                                Swal.fire('Datos Incompletos!', 'Favor de completar los campos del formulario', 'warning');
                            } else {
                                $('#modalPrevisualizar').modal('hide');
                                Swal.fire('Datos Incompletos!', 'Favor verificar los datos y documentos de los Derechohabientes', 'warning');
                            }
                        })
                        .catch(error => {
                            console.error('Error al generar o abrir el PDF', error);
                        });
                }
            },

            renderPDF(base64, canvasContainer) {
                //2025-04-15
                var pdfData = atob(base64);
                var pdfAsArray = new Uint8Array(pdfData.length);
                for (var i = 0; i < pdfData.length; i++) {
                    pdfAsArray[i] = pdfData.charCodeAt(i);
                }
                const that = this;
                // Carga del PDF
                pdfjsLib.getDocument({ data: pdfAsArray }).promise.then(function (pdf) {
                    that.pdf = pdf;
                    that.totalPage = pdf.numPages;
                    if (that.currentPage > that.totalPage) {
                        that.currentPage = that.totalPage;
                    }
                    if (that.currentPage < 1) {
                        that.currentPage = 1;
                    }
                    that.renderPage(that.currentPage, canvasContainer);
                });
            },

            renderPage(pageNumber, canvasContainer) {
                //2025-04-15
                this.pdf.getPage(pageNumber).then(page => {
                    var viewport = page.getViewport({ scale: 1.0 });
                    var canvas = document.getElementById(canvasContainer);
                    var context = canvas.getContext("2d");

                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    context.clearRect(0, 0, canvas.width, canvas.height);

                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(renderContext);
                });
            },

            nextPage() {
                if (this.currentPage < this.totalPage) {
                    this.currentPage++;
                    this.renderPage(this.currentPage, 'pdfCanvas');
                }
            },

            previousPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.renderPage(this.currentPage, 'pdfCanvas');
                }
            },

            downloadPDF(nombre) {
                // Crear un enlace temporal para descargar el PDF
                var link = document.createElement('a');
                link.href = 'data:application/pdf;base64,' + this.pdfSrc;
                link.download = nombre + this.$store.state.nombreDocumento + '.pdf';
                link.click();
            },

            doImprimir(i) {
                var impress = this.impresiones[i].imp_data;
                this.registro.cas_data_valores.forEach(valor => {
                    if (Array.isArray(valor.frm_value)) {
                        var grilla = '<table cellpadding="0" cellspacing="0">';
                        valor.frm_value.forEach(fila => {
                            grilla += '<tr style="height: 6pt; font-size: 6pt; font-family: arial, helvetica, sans-serif;">';
                            //11.1/var col = 0;
                            //11.1/var colsValidas = [0, 1, 2, 3];
                            fila.forEach(campo => {
                                //11.1/if ( colsValidas.includes(col)) {
                                grilla += '<td style="height: 6pt; font-size: 6pt; font-family: arial, helvetica, sans-serif;">' + campo.col_value + '</td>';
                                //11.1/}
                                //11.1/col++;
                            });
                            grilla += '</tr>';
                        });
                        grilla += '</table>'
                        impress = impress.replaceAll('#' + valor.frm_campo.trim() + '#', grilla);
                    } else {
                        var vValue = (typeof valor.frm_value === "undefined") ? "-?-" : valor.frm_value;
                        var vValueLabel = (typeof valor.frm_value_label === "undefined") ? "-?-" : valor.frm_value_label;

                        impress = impress.replaceAll("#" + valor.frm_campo.trim() + "!LABEL#", vValueLabel);
                        impress = impress.replaceAll("#" + valor.frm_campo.trim() + "!EVAL#", '"' + vValue + '"');
                        impress = impress.replaceAll('#' + valor.frm_campo.trim() + '#', vValue);
                    }
                });

                const patron = /#\b["0-9A-Za-z_=?]*\B#/ig;
                var evaluados = impress.match(patron);
                if (Array.isArray(evaluados)) {
                    console.log(evaluados);
                    evaluados.forEach(function (ev, idx) {
                        var tok = ev.replaceAll("#", "").split("?");
                        if (tok.length > 1) {
                            var result = "";
                            try {
                                if (result = eval(tok[1])) {
                                    impress = impress.replaceAll(evaluados[idx], "+++");//vValue);
                                } else {
                                    impress = impress.replaceAll(evaluados[idx], "...");
                                }
                            } catch (error) {
                                console.log(error);
                            }
                        }
                    });
                }
                var doc = new jsPDF('p', 'pt', 'letter');
                doc.setFont("arial");
                doc.html(impress, {
                    callback: function (pdf) {
                        doc.output('dataurlnewwindow');
                    },
                    x: 30,
                    y: 30,
                    width: 800,
                    margin: [30, 30, 30, 30]
                });
            },
            //****************FIRMA PARA RUBRICAS******************* */

            tokenCertificados(_slot, _pin) {
                var parametros = {
                    slot: _slot,
                    pin: _pin
                }
                var self = this; // Captura el contexto de 'this'
                $.ajax({
                    url: 'https://localhost:9000/api/token/data',
                    type: 'POST',
                    data: JSON.stringify(parametros),
                    contentType: "application/json; charset=utf-8",
                    dataType: 'json',
                    success: function (json) {
                        self.$store.dispatch('updateToken', _slot);
                        self.$store.dispatch('updatePin', _pin);
                        if (json.finalizado) {
                            //console.log(json);
                            const elements = json.datos.data_token.data;
                            var valores = [];
                            for (let i = 0; i < elements.length; i++) {
                                if (elements[i].tipo === 'X509_CERTIFICATE' && elements[i].titular.description && (elements[i].titular.description).includes('Persona Juridica')) {
                                    //valores.push([elements[i].alias,elements[i].titular.CN,elements[i].titular.T]);
                                    valores.push({
                                        alias: elements[i].alias,
                                        titular: elements[i].titular.CN,
                                        t: elements[i].titular.T
                                    });
                                    self.$store.dispatch('updateAlias', elements[i].alias);
                                }
                            }
                            self.storeCertificados = valores;
                            if (self.storeCertificados.length > 0) {
                                self.selectedCertificado = self.storeCertificados[0].alias;
                            }
                        } else {
                            self.$swal({
                                title: '<b>ATENCIÓN</b>',
                                text: json.mensaje,
                                icon: 'warning',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function (xhr, status) {
                        Ext.MessageBox.alert('<b>ATENCIÓN</b>', 'Hubo un problema con su Token, revise su Pin.');
                    }
                });
            },
            btnVerDocumentoInicial() {
                if (this.bandera_modal == 1) {
                    this.btnVerDocumento3();
                } else {
                    this.btnVerDocumento2();
                }
            },
            btnVerDocumento2() {
                this.doLimpiar(this.$store.state.indexDoc);
                $('#modalSubirArchivos').modal('show');
                $('#modalRubrica').modal('hide');
            },
            btnVerDocumento3() {
                this.doLimpiar(this.$store.state.indexDoc);
                $('#modalDerivar').modal('show');
                $('#modalRubrica').modal('hide');
            },
            tokenSlot(i, nombre, imp_tipo, nombreDocumento, index) {
                var slots = [];
                var self = this; // Captura el contexto de 'this'
                setTimeout(() => {
                    $.ajax({
                        url: 'https://localhost:9000/api/token/connected',
                        type: 'GET',
                        dataType: 'json',
                        success: function (json) {
                            self.doImprimir1(i, nombre, imp_tipo, true);
                            self.$store.dispatch('updateNombreDocumento', nombreDocumento);
                            console.log('nombreDocumento ', nombreDocumento);
                            self.$store.dispatch('updateIndexDocFirma', index);
                            const tokens = json.datos.tokens;
                            if (tokens.length > 0) {
                                for (let i = 0; i < tokens.length; i++) {

                                    slots.push({
                                        slot: tokens[i].slot,
                                        dispositivo: tokens[i].name
                                    });
                                }
                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                                console.log(self.storeToken);

                            } else {
                                slots.push({
                                    slot: 0,
                                    dispositivo: json.mensaje
                                });

                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                                self.$swal({
                                    title: '<b>ATENCIÓN</b>',
                                    text: json.mensaje,
                                    icon: 'warning',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                            $('#modalSubirArchivos').modal('hide');
                            $('#modalRubrica').modal('show');
                        },
                        error: function (xhr, status) {
                            console.log(xhr, status);
                            this.$swal({
                                title: '<b>ATENCIÓN</b>',
                                text: 'Hubo un problema.',
                                icon: 'warning',
                                confirmButtonText: 'Aceptar'
                            });
                            //Ext.MessageBox.alert('<b>ATENCIÓN</b>', 'Hubo un problema.');
                        }
                    });
                }, 1000); // Simulamos un retardo de 1 segundo (1000 ms)

            },
            descargarPdf(i, nombre, imp_tipo, nombreDocumento, index) {
                //this.clearCanvas();
                const datos = {
                    cas_cod_id: this.registro.cas_cod_id,
                    nombreDocumento: nombreDocumento,
                    doc_id: this.retrieveDocIdIfExist(),
                };
                console.log('documentos datoss 6=====>', datos);
                axios.post('api/obtenerDocumentoPdf64', datos)
                    .then(response => {
                        //2025-04-16
                        this.pdfSrc = response.data;
                        this.renderPDF(this.pdfSrc, 'pdfCanvas');
                        this.$store.dispatch('updateRegistro', this.registro);
                        this.$refs.visorPDFComponent.initVisor(this.pdfSrc);
                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                    });
            },
            subirPdf(event, id, nombre, imp_tipo, nombreDocumento, index) {

                const file = event.target.files[0];
                if (!file) {
                } else {
                    const reader = new FileReader();
                    reader.onload = () => {
                        const base64data = reader.result.split(',')[1];
                        const ci = this.registro.cas_data.AS_CI;
                        let id_persona = '';
                        const items = this.registro.cas_data_valores;
                        for (var i = 0; i < items.length; i++) {
                            if (items[i].frm_campo == 'AS_IDPERSONA') {
                                id_persona = items[i].frm_value;
                            }
                        }

                        const datos = {
                            tam: 2000,
                            valor_id: 5000 + id,
                            valor_descripcion: nombreDocumento,
                            pdf: base64data,
                            caso: this.registro.cas_cod_id,
                            id_caso: this.registro.cas_id,
                            documentoOriginalObligatorio: '',
                            presentacionObligatoria: '',
                            ci: ci,
                            parentesco: 'referencia',
                            switch: '',
                            pfrm_value: '',
                            id_persona_sip: id_persona,
                            id_observacion: "1",
                            usr_id: this.usrId,
                        };
                        axios.post('api/guardarDocumentosRequisitos', datos)
                            .then(response => {
                                console.log('respuesta', response.data);
                            })
                            .catch(error => {
                                console.error('Error al generar o abrir el PDF', error);
                            });
                    };
                    reader.readAsDataURL(file);
                }
            },
            verPdf(i, nombre, imp_tipo, nombreDocumento, index) {
                console.log('ver el pdf');
                console.log(i, nombre, imp_tipo, nombreDocumento, index);
                console.log('athis.registro', this.registro.cas_cod_id, nombreDocumento);
                const datos = {
                    cas_cod_id: this.registro.cas_cod_id,
                    nombreDocumento: nombreDocumento,
                    codigo: 5000 + i,
                };

                console.log('documentos datoss 1 =====>', datos);
                axios.post('api/obtenerDocumentoPdf64Codigo', datos)
                    .then(response => {
                        console.log('respuesta', response.data);
                        this.pdfSrc = response.data;

                        this.renderPDF(this.pdfSrc, 'pdfCanvas');


                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                    });


            },
            clearCanvas(){
                try{
                    let base64Loading = EstaticValuesEnum.PDF_LOADING_MESSAGE;
                    // replace URL‑safe chars
                    base64Loading = base64Loading.replace(/-/g, '+').replace(/_/g, '/');
                    // pad with '=' so length % 4 === 0
                    const pad = base64Loading.length % 4;
                    if (pad) {
                        base64Loading += '='.repeat(4 - pad);
                    }
                    this.pdfSrc = base64Loading;//et-this variable is used to enable to create dom elements and also must be in base64 format (analisis)
                    this.$refs.visorPDFComponent.initVisor(base64Loading);//et-canvas-1, aim of this line is to clear the visor-sign-pdf to sign with the jacobitus.
                    this.renderPDF(base64Loading,'pdfCanvas');// et-canvas-2,aim of this line is to clear the visor-pdf (only show the pdf -readonly)
                }catch(e){
                    console.error("Error: clearCanvas-method error ...");
                    console.log(e);
                }
            },
            firmaToken(i, nombre, imp_tipo, nombreDocumento, index, r) {
                // this.clearCanvas(true);
                document.getElementById('firmado_archivo').value = '';
                this.bandera_modal = 1;
                var slots = [];
                var self = this;
                setTimeout(() => {
                    $.ajax({
                        url: 'https://localhost:9000/api/token/connected',
                        type: 'GET',
                        dataType: 'json',
                        success: function (json) {
                            //self.doImprimir1(i, nombre, imp_tipo, true);
                            self.$store.dispatch('updateNombreDocumento', nombreDocumento);
                            console.log('nombreDocumento ', nombreDocumento);
                            self.$store.dispatch('updateIndexDocFirma', index);
                            self.$store.dispatch('updateDocReferencia', r.doc_referencia);
                            self.$store.dispatch('updateDocCategoria', r.doc_categoria);
                            self.$store.dispatch('updateDocDocId', r.doc_doc_id);
                            const tokens = json.datos.tokens;
                            if (tokens.length > 0) {
                                for (let i = 0; i < tokens.length; i++) {

                                    slots.push({
                                        slot: tokens[i].slot,
                                        dispositivo: tokens[i].name
                                    });
                                }
                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                                console.log(self.storeToken);

                            } else {
                                slots.push({
                                    slot: 0,
                                    dispositivo: json.mensaje
                                });

                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                                self.$swal({
                                    title: '<b>ATENCIÓN</b>',
                                    text: json.mensaje,
                                    icon: 'warning',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                            $('#modalDerivar').modal('hide');
                            $('#modalRubrica').modal('show');
                        },
                        error: function (xhr, status) {
                            console.log('xhr===>>', xhr, 'status==>', status);
                            Swal.fire({
                                title: '<b>ATENCIÓN</b>',
                                text: 'Hubo un problema, Revisar JACOBITUS',
                                icon: 'warning',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                }, 1000);
            },

            subirPdfParafirmar(event) {
                const file = event.target.files[0];
                if (!file) {
                } else {
                    const reader = new FileReader();
                    reader.onload = () => {
                        const base64data = reader.result.split(',')[1];
                        this.pdfSrc = base64data;
                        console.log('base64data', base64data);
                        //this.renderPDF(this.pdfSrc, 'pdfCanvas');
                        this.renderPDF(this.pdfSrc, 'pdfCanvas');
                    };
                    reader.readAsDataURL(file);
                }
            },
            handleFileSelected: async function (event){
                //2025-04-09
                if (this.selectedCertificado != null && this.selectedCertificado != '') {
                    const activeCaseConfigData = await getDataConfigurationAboutActiveCase(this.registro.cas_id);
                    if(LegalActivitiesConfigService.data === null){
                        await LegalActivitiesConfigService.loadData();
                    }
                    const legalActOrden40Data = LegalActivitiesConfigService.data.find(activity => activity.act_orden === LegalActividadesFirmaEnum.LEGAL_ACT_ORDEN_40);
                    //2025-05-08
                    // if (document.getElementById('firmado_archivo').value === '' && (this.registro.act_data.act_siguiente != 68 && this.registro.act_data.act_siguiente != 67 && this.registro.act_data.act_siguiente != 69) && (this.registro.act_data.act_siguiente != 168 && this.registro.act_data.act_siguiente != 167 && this.registro.act_data.act_siguiente != 169 && this.registro.act_data.act_siguiente != 35) && !(Number(this.registro.act_data.act_siguiente) === 51 && this.registro.cas_act_id === LegalActivitiesConfigService.LEGAL_ACT_ORDEN_40)) {
                    if (document.getElementById('firmado_archivo').value === '' &&
                        (this.registro.act_data.act_siguiente != 68 && this.registro.act_data.act_siguiente != 67 && this.registro.act_data.act_siguiente != 69) &&
                        (this.registro.act_data.act_siguiente != 168 && this.registro.act_data.act_siguiente != 167 && this.registro.act_data.act_siguiente != 169 && this.registro.act_data.act_siguiente != 35) &&
                        !(Number(this.registro.act_data.act_siguiente) === 51 && activeCaseConfigData.prc_codigo === 'LEGAL' && this.registro.cas_act_id === legalActOrden40Data.act_id)) {
                        this.$swal({
                            title: '<b>ATENCIÓN</b>',
                            text: 'Favor cargar el archivo para realizar la rúbrica 1.',
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'

                        });
                    } else {
                        //fileReader.readAsArrayBuffer(file);
                        this.$refs.visorPDFComponent.incrustar('normal');
                        this.cargarArchivoUploadVisor(event);
                    }
                } else {
                    this.$swal({
                        title: '<b>ATENCIÓN</b>',
                        text: 'Favor seleccionar el certificado para realizar la rúbrica 2.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },

            actualizar() {
                // Lógica para manejar el evento click del botón btnActualizar
                // Accede a this.token, this.pin y this.fojas para obtener los valores de los campos
                this.tokenCertificados(this.token, this.pin);
            },

            btnVerDocumento() {
                alert(12);
                //incrustar('normal');
                //cargarPdfVisualizador();
            },
            mostrarModalVisorPDF() {
                // Accede al componente VisorPDF a través de su referencia y llama al método mostrarModal
                this.$refs.visorPDFComponent.incrustar('normal');
                this.cargarPdfVisualizador();
            },
            cargarPdfVisualizador() {
                var windowprops = "top=0,left=0,toolbar=no,location=no,status=no, menubar=no,scrollbars=no, resizable=no,width=500,height=600";
                this.cargarArchivoUploadVisor(function (base64) {
                    if (base64) {
                        //initVisor(base64);
                    }
                });
            },
            cargarArchivoUploadVisor(event) {
                if (this.registro.act_data.act_siguiente == 68 ||
                    this.registro.act_data.act_siguiente == 67 ||
                    this.registro.act_data.act_siguiente == 69 ||
                    (this.registro.prc_data && this.registro.prc_data.prc_codigo && this.registro.prc_data.prc_codigo === "PM" && Number(this.registro.act_data.act_siguiente) == 167) ||
                    (this.registro.prc_data && this.registro.prc_data.prc_codigo && this.registro.prc_data.prc_codigo === "PM" && Number(this.registro.act_data.act_siguiente) == 168) ||
                    (this.registro.prc_data && this.registro.prc_data.prc_codigo && this.registro.prc_data.prc_codigo === "PM" && Number(this.registro.act_data.act_siguiente) == 169)
                ) {
                    //PARA LA IMPRESION DE INV DE INCLUSION DE DH
                    if (this.impresionesContrato.length > 0 && this.registro.act_data.act_siguiente == 68) {
                        this.callback(this.pdfSrc);
                    }
                    else {
                        this.documentoPdfB64(this.$store.state.nombreDocumento,);
                    }


                } else {
                    this.callback(this.pdfSrc);
                }
            },
            callback(base64String) {
                console.log("🚀 ~ callback ~ base64String:", base64String)
                // Hacer algo con la cadena base64, por ejemplo, mostrarla en la consola
                if (base64String) {
                    console.log('this.registro ', this.registro);
                    this.$store.dispatch('updateRegistro', this.registro);
                    this.$refs.visorPDFComponent.initVisor(base64String);
                }
            },
            actualizarToken() {
                var slots = [];
                var self = this; // Captura el contexto de 'this'
                setTimeout(() => {
                    $.ajax({
                        url: 'https://localhost:9000/api/token/connected',
                        type: 'GET',
                        dataType: 'json',
                        success: function (json) {
                            const tokens = json.datos.tokens;
                            if (tokens.length > 0) {
                                for (let i = 0; i < tokens.length; i++) {

                                    slots.push({
                                        slot: tokens[i].slot,
                                        dispositivo: tokens[i].name
                                    });
                                }
                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                                console.log(self.storeToken);

                            } else {
                                slots.push({
                                    slot: 0,
                                    dispositivo: json.mensaje
                                });

                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                                self.$swal({
                                    title: '<b>ATENCIÓN</b>',
                                    text: json.mensaje,
                                    icon: 'warning',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                            $('#modalSubirArchivos').modal('hide');
                            $('#modalRubrica').modal('show');
                        },
                        error: function (xhr, status) {
                            console.log(xhr, status);
                            this.$swal({
                                title: '<b>ATENCIÓN</b>',
                                text: 'Hubo un problema.',
                                icon: 'warning',
                                confirmButtonText: 'Aceptar'
                            });
                            //Ext.MessageBox.alert('<b>ATENCIÓN</b>', 'Hubo un problema.');
                        }
                    });
                }, 1000); // Simulamos un retardo de 1 segundo (1000 ms)

            },
            retrieveDocIdIfExist(){
                try{
                    //1) we assume that our point of entry is grid-signature.
                    //2) ensure that we are on the same case.
                    if(this.registro.cas_cod_id === this.selectedRowSignature.doc_codigo &&
                        this.selectedRowSignature.doc_id &&
                        !Number.isNaN(Number(this.selectedRowSignature.doc_id))){
                        return this.selectedRowSignature.doc_id;
                    }
                }catch(e){}
                return null;
            },
            onRowClick(row, index){
                this.selectedRowSignature = row;
            },
            documentoPdfB64(nombreDocumento) {
                //this.clearCanvas();
                const datos = {
                    cas_cod_id: this.registro.cas_cod_id,
                    nombreDocumento: nombreDocumento,
                    doc_id: this.retrieveDocIdIfExist(),
                };
                console.log('documentos datoss 2=====>', datos);
                axios.post('api/obtenerDocumentoPdf64', datos)
                    .then(response => {
                        //2025-04-16
                        /* this.pdfSrc = response.data;
                        console.log("🚀 ~ documentoPdfB64 ~ response.data===================:", response.data)
                        this.callback(this.pdfSrc);*/



                        // Limpia la cadena Base64: elimina espacios, saltos de línea, y tabs
                        const cleanBase64 = response.data.replace(/\s+/g, '');

                        console.log("🚀 ~ Cleaned Base64 PDF:", cleanBase64);

                        // Asigna la cadena limpia a la propiedad pdfSrc
                        this.pdfSrc = cleanBase64;
                        //this.callback(this.pdfSrc);
                        this.$store.dispatch('updateRegistro', this.registro);
                        this.$refs.visorPDFComponent.initVisor(this.pdfSrc);
                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                    });
            },

            firmaMasivaTokenRubrica() {
                console.log("🚀 ~ firmaMasivaTokenRubrica ~ slots:", this.registro)
                var slots = [];
                var self = this;
                setTimeout(() => {
                    $.ajax({
                        url: 'https://localhost:9000/api/token/connected',
                        type: 'GET',
                        dataType: 'json',
                        success: function (json) {
                            const tokens = json.datos.tokens;
                            if (tokens.length > 0) {
                                for (let i = 0; i < tokens.length; i++) {
                                    slots.push({
                                        slot: tokens[i].slot,
                                        dispositivo: tokens[i].name
                                    });
                                }
                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                            } else {
                                slots.push({
                                    slot: 0,
                                    dispositivo: json.mensaje
                                });
                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                                self.$swal({
                                    title: '<b>ATENCIÓN</b>',
                                    text: json.mensaje,
                                    icon: 'warning',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                            document.getElementById('aceptarFirmaMasivaRubrica').disabled = false;
                            document.getElementById('cancelarFirmaMasivaRubrica').disabled = false;
                            $('#modalFirmaMasivaRubrica').modal('show');
                        },
                        error: function (xhr, status) {
                            Swal.fire({
                                title: '<b>ATENCIÓN</b>',
                                text: 'Hubo un problema, Revisar JACOBITUS',
                                icon: 'warning',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                }, 1000);
            },
            cerrarModalFirmaMasivaRubrica() {
                $('#modalFirmaMasivaRubrica').modal('hide');
            },
            async firmaMasivaRubrica() {
                console.log('rmodalFirmaMasivaRubrica', this.registro);
                console.log("rosos");
                document.getElementById('aceptarFirmaMasiva').disabled = true;
                document.getElementById('cancelarFirmaMasiva').disabled = true;
                console.log('this.pin', this.pin);
                console.log(" this.selectedCertificado", this.selectedCertificado);
                var casIds = this.selected_ids;
                var that = this;
                var dataLoteFirma = {
                    slot: 1,
                    pin: this.pin,
                    alias: this.selectedCertificado,
                    pdfs: []
                };
                var url = "api/contratosCasos/" + that.registro.act_id + "/" + casIds;
                var that = this;
                try {
                    const rubricaConfig = {
                        "x": 382.5,
                        "y": 653,
                        "ancho": 200,
                        "alto": 100,
                        "pagina": 1,
                        "tamanioFuente": 6,
                        "imagenEscala": 0.2,
                        "imageBase64": "/9j/4AAQSkZJRgABAQEAYABgAAD/4QBmRXhpZgAATU0AKgAAAAgABgESAAMAAAABAAEAAAMBAAUAAAABAAAAVgMDAAEAAAABAAAAAFEQAAEAAAABAQAAAFERAAQAAAABAAAOw1ESAAQAAAABAAAOwwAAAAAAAYagAACxj//bAEMAAgEBAgEBAgICAgICAgIDBQMDAwMDBgQEAwUHBgcHBwYHBwgJCwkICAoIBwcKDQoKCwwMDAwHCQ4PDQwOCwwMDP/bAEMBAgICAwMDBgMDBgwIBwgMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDP/AABEIACcAJQMBIgACEQEDEQH/xAAfAAABBQEBAQEBAQAAAAAAAAAAAQIDBAUGBwgJCgv/xAC1EAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+fr/xAAfAQADAQEBAQEBAQEBAAAAAAAAAQIDBAUGBwgJCgv/xAC1EQACAQIEBAMEBwUEBAABAncAAQIDEQQFITEGEkFRB2FxEyIygQgUQpGhscEJIzNS8BVictEKFiQ04SXxFxgZGiYnKCkqNTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqCg4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2dri4+Tl5ufo6ery8/T19vf4+fr/2gAMAwEAAhEDEQA/AP3V+JXxW0X4U6Xa3GsXRjk1C4SzsbWJfMub+dzhYooxy7H24AySQATXwl/wUn/4LH3fwc8R3vgH4XrZ/wDCRWI8rWdauAs8WlS4+aCFfuySr0ZjlVPGCc456+/acvviV8fvjP8AFi4maay+Evhq/XwrbscxWkrMbeKYDpvYhmLdfmA6Cvj/AEPxR4S/YY/Zv0343eP9FtPHnxC8eXdwngDwzqRLWkhjb9/q96OrxrIflU/eJGOW3L+m5fwxhcBL2uPXtJRUfc7zkuZR/wC3Y2bb01fbX8Nx3GuZZ/8A7HkTVOFSU/3nVU6b5XP1lO6ilqkl1elXRfC3x4/bb8V6fHcyfEjxVDrt5Hatf3KXcmn24kYKZGOPKVFBJJAAAFfuz8I/hpp3wX+GHh/wnpMfl6d4fsIrGAY5ZY1A3H3Y5J9zX83ukfH79sz/AIKO+LNRn8L618VvFgsGBmtPC0smmaRpQI+WNVhaOGPjGFZixHPPWt74H/8ABVX9qr/gnN8bYfDvi7UvGWtGzmjjv/BfjQS3E1zGx4FvI4M0btzseNmUnHDDIq+I6OIzOEaNPkh7O75Ivv12X5JH0HBfDtDIpVK9SpOrOrZOcl0XRat2vruz+koHIorlfgr8UF+M3wk8OeLI9H1rw+viDT4r7+zdYtWtb6xLqCYpo25V1PB/OivzFxadmfqUdVc/K39mPwFceILT9oD4Vybv7c1rwrf2lmjD5prqzlZgoHqTzXwV/wAFj/FF1L8fvA+h7ZI9N8H/AAw8PWthARhYxNafaZSB6tJIcnqdvtX6yf8ABQL9nbxV+zh+0Na/Hb4f2sk9mtyt7qkUSlvsc4G2RnUcmCZchj/CSSeox8Q/8F2vhXo/7Svg7wj+0p8O7RZNGg0+Hw14106FR5vh+ZGJtpZVX7sR3vFv+7/qjnnj9qx2NWNrUc1pu9OqkpW+xVUVFp9rpe73+Z/O/AOBWUVq/D2LXLXouXs7/wDLyjKfOpRfWzb5l00vsz9E/wBn3xHo/wDwTU/4IjaH4y8OaFaasvhfwPB4oubUTC3/ALXu54kmmeSUKTuZpPvEEgBR0Ar4J1v/AIOZNJ8V/EbSfGGpfs0+EdR8VaDbva6dq1xrSy3lhE7BmWKQ2xZASAeCO/qa6r/gmD/wW9+D+mfscaf8D/2iLGWOx0Kw/seC+uNMfU9M1rThny4riNQzLIi4TBUqwVTkHIr5Z/4K5/tj/s+fHCTRvDP7P/wx8O+FdC0e5a91LxLDoUemXOrOFKJBEgUSCEbixL4LsFwoCkn43AZX/tVSni6Lk22+e7St9/U/aMRjP3MZUZpJJadbn7If8Ejv+Cm17/wU7+G3jDxBeeDYfBreFtWj0xYY9RN6LrdCsu/dsTbjdjHNFcz/AMG//wCyNrH7J3/BP/TZPEtjLpviTx9fy+Jbu0lXbNaRSKiW8Tg9GEMaMR1BkIPIor5PMqdGOKnGgvdTsj2cK5ulF1Nz7angjnhZHVXjkG1lYZDA9QRXz/49/wCCcXgXW/Ed9rXhlr7wPq2pRPDerpgSTT9Rjf78dxZyq0MsbAnKFQDmiipwePxGGb9hNxvo+zXZrZ/M5MyyfBY9R+t01Lld03vF94tap+jR8seOv+DZ74P+PddkvftV94ZaVy8kfhyV7S2bPJ2wS+csf0QhR2Ar1z9lz/ggt+zn+yv4wsvEVn4b1Hxh4g011mtLzxNem+S0lHIkjhwsQYHkEoSOoOaKK6K2dY2rH2cqjt2Wn5FYfLcPRtyq/q2/zufZ4G4UUUV5Z6B//9k=",
                        "margenTextos": 10,
                        "dimesionLogo": 10,
                        "fecha": true
                    };
                    const response = await axios.get(url);
                    var contratos = response.data.data;
                    const pdfPromises = contratos.map(async response1 => {
                        var urlDocumentos = "api/obtenerDocumentoPdf64/" + response1.doc_id;
                        try {
                            const pdfResponse = await axios.get(urlDocumentos);
                            //2025-04-16
                            const cleanBase64 = pdfResponse.data.replace(/\s+/g, '');
                            const pdfData = (cleanBase64).trim();
                            if (this.esBase64(pdfData)) {
                                dataLoteFirma.pdfs.push({
                                    id: response1.doc_id + '^' + response1.doc_cas_id + '^' + response1.doc_doc_id + '^' + response1.imp_nombre + '^' + response1.doc_categoria + '^' + response1.doc_referencia,
                                    pdf: pdfData
                                });
                            }
                        } catch (error) {
                            console.error('Error al generar o abrir el PDF', error);
                        }
                    });
                    await Promise.all(pdfPromises);
                    dataLoteFirma.rubrica = rubricaConfig;
                    $.ajax({
                        url: "https://localhost:9000/api/token/firmar_lote_pdfs",
                        type: "POST",
                        data: JSON.stringify(dataLoteFirma),
                        contentType: "application/json",
                        dataType: "json",
                        success: function (json) {
                            console.log('json ', json);
                            console.log('json ', json.datos.pdfs_firmados);
                            json.datos.pdfs_firmados.forEach(datos1 => {
                                that.registarArchivoUnoPorUno(datos1);
                            });
                            that.$swal({
                                title: '<b>ATENCIÓN</b>',
                                text: 'Los documentos fueron Firmados.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                            $('#modalFirmaMasivaRubrica').modal('hide');
                        },
                        error: function (xhr, status) {
                            Ext.MessageBox.alert(
                                "<b>ATENCIÓN</b>",
                                "Hubo un problema con su Token."
                            );
                        },
                    });
                } catch (error) {
                    console.error('Error al obtener contratos', error);
                }
            },
            aceptarModalFirmaMasivaRubrica() {
                console.log('this.impresiones  siguiente hhhh1  aceptarModalFirmaMasivaRubrica ', this.registro.act_data.act_siguiente);
                console.log("🚀 ~ firmaMasiva RRRR ~ that.registro:", this.registro)
                if (this.selectedCertificado != null && this.selectedCertificado != '') {
                    if (this.selected_ids.length > 0) {
                        this.firmaMasivaRubrica();
                    } else {
                        this.$swal({
                            title: '<b>ATENCIÓN</b>',
                            text: 'Favor seleccionar el o los tramites a Firmar.',
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } else {
                    this.$swal({
                        title: '<b>ATENCIÓN</b>',
                        text: 'Favor seleccionar el certificado para realizar la rúbrica 3.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                }

            },
            firmaMasivaToken() {
                console.log("🚀 ~ firmaMasivaToken ~ slots:", this.registro)
                var slots = [];
                var self = this;
                setTimeout(() => {
                    $.ajax({
                        url: 'https://localhost:9000/api/token/connected',
                        type: 'GET',
                        dataType: 'json',
                        success: function (json) {
                            const tokens = json.datos.tokens;
                            if (tokens.length > 0) {
                                for (let i = 0; i < tokens.length; i++) {
                                    slots.push({
                                        slot: tokens[i].slot,
                                        dispositivo: tokens[i].name
                                    });
                                }
                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                            } else {
                                slots.push({
                                    slot: 0,
                                    dispositivo: json.mensaje
                                });
                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                                self.$swal({
                                    title: '<b>ATENCIÓN</b>',
                                    text: json.mensaje,
                                    icon: 'warning',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                            document.getElementById('aceptarFirmaMasiva').disabled = false;
                            document.getElementById('cancelarFirmaMasiva').disabled = false;
                            $('#modalFirmaMasiva').modal('show');
                        },
                        error: function (xhr, status) {
                            Swal.fire({
                                title: '<b>ATENCIÓN</b>',
                                text: 'Hubo un problema, Revisar JACOBITUS',
                                icon: 'warning',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                }, 1000);
            },
            cerrarModalFirmaMasiva() {
                $('#modalFirmaMasiva').modal('hide');
            },
            async firmaMasiva() {

                console.log(this.registro);
                console.log("rosos");
                document.getElementById('aceptarFirmaMasiva').disabled = true;
                document.getElementById('cancelarFirmaMasiva').disabled = true;
                var casIds = this.selected_ids;
                var that = this;
                var dataLoteFirma = {
                    slot: 1,
                    pin: this.pin,
                    alias: this.selectedCertificado,
                    pdfs: []
                };
                var url = "api/contratosCasos/" + that.registro.act_id + "/" + casIds;
                var that = this;
                try {
                    const response = await axios.get(url);
                    var contratos = response.data.data;
                    const pdfPromises = contratos.map(async response1 => {
                        var urlDocumentos = "api/obtenerDocumentoPdf64/" + response1.doc_id;
                        try {
                            const pdfResponse = await axios.get(urlDocumentos);
                            //2025-04-16
                            // Limpia la cadena Base64: elimina espacios, saltos de línea, y tabs
                            const cleanBase64 = pdfResponse.data.replace(/\s+/g, '');
                            const pdfData = (cleanBase64).trim();
                            if (this.esBase64(pdfData)) {
                                dataLoteFirma.pdfs.push({
                                    id: response1.doc_id + '^' + response1.doc_cas_id + '^' + response1.doc_doc_id + '^' + response1.imp_nombre + '^' + response1.doc_categoria + '^' + response1.doc_referencia,
                                    pdf: pdfData
                                });
                            }
                        } catch (error) {
                            console.error('Error al generar o abrir el PDF', error);
                        }
                    });
                    await Promise.all(pdfPromises);
                    $.ajax({
                        url: "https://localhost:9000/api/token/firmar_lote_pdfs",
                        type: "POST",
                        data: JSON.stringify(dataLoteFirma),
                        contentType: "application/json",
                        dataType: "json",
                        success: function (json) {
                            console.log('json ', json);
                            console.log('json ', json.datos.pdfs_firmados);
                            json.datos.pdfs_firmados.forEach(datos1 => {
                                that.registarArchivoUnoPorUno(datos1);
                            });
                            that.$swal({
                                title: '<b>ATENCIÓN</b>',
                                text: 'Los documentos fueron Firmados.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                            $('#modalFirmaMasiva').modal('hide');
                        },
                        error: function (xhr, status) {
                            Ext.MessageBox.alert(
                                "<b>ATENCIÓN</b>",
                                "Hubo un problema con su Token."
                            );
                        },
                    });
                } catch (error) {
                    console.error('Error al obtener contratos', error);
                }
            },
            aceptarModalFirmaMasiva() {
                console.log('this.impresiones  siguiente hhhh1 ', this.registro.act_data.act_siguiente);
                console.log("🚀 ~ firmaMasiva RRRR ~ that.registro:", this.registro)
                if (this.selectedCertificado != null && this.selectedCertificado != '') {
                    if (this.selected_ids.length > 0) {
                        this.firmaMasiva();
                    } else {
                        this.$swal({
                            title: '<b>ATENCIÓN</b>',
                            text: 'Favor seleccionar el o los tramites a Firmar.',
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } else {
                    this.$swal({
                        title: '<b>ATENCIÓN</b>',
                        text: 'Favor seleccionar el certificado para realizar la rúbrica 3.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            /*esBase64(cadena) {
                const base64Regex = /^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?$/;
                return base64Regex.test(cadena) && (cadena.length % 4 === 0);
            },*/
            esBase64(cadena) {
                const maxBase64Length = 10485760; // 10MB en base64
                if (cadena.length > maxBase64Length) {
                    console.error("La cadena es demasiado grande para ser validada como base64");
                    //return false;
                    return true;
                }

                console.log("La cadena es base64");
                console.log(cadena);
                const base64Regex = /^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?$/;
                return base64Regex.test(cadena) && (cadena.length % 4 === 0);
            }
            ,
            registarArchivoUnoPorUno(pdfFimado) {
                console.log('registarArchivoUnoPorUno');
                console.log('pdfFimado ', pdfFimado.id);
                const [doc_id, doc_cas_id, doc_doc_id, imp_nombre, doc_categoria, doc_referencia] = pdfFimado.id.split('^');
                let that = this;
                const registroCaso = this.registros.find(registro => registro.cas_id == doc_cas_id);
                console.log('registroCaso ', registroCaso);

                var urlActividad = "api/actividad/" + registroCaso.prc_id + "/" + registroCaso.act_data.act_siguiente;

                axios.get(urlActividad).then(responseActividad => {
                    var actividad = responseActividad.data.data[0];
                    var act_nodo_id = actividad.act_nodo_id;
                    var act_id = actividad.act_id;
                    console.log('urlActividad', act_nodo_id);

                    const items = registroCaso.cas_data_valores;
                    let id_persona = "";
                    for (var i = 0; i < items.length; i++) {
                        if (items[i].frm_campo == "AS_IDPERSONA") {
                            id_persona = items[i].frm_value;
                        }
                    }

                    const datos = {
                        tam: 2000,
                        valor_id: doc_doc_id,
                        valor_descripcion: imp_nombre,
                        pdf: pdfFimado.pdf_firmado,
                        caso: registroCaso.cas_cod_id,
                        id_caso: registroCaso.cas_id,
                        documentoOriginalObligatorio: "",
                        presentacionObligatoria: "",
                        ci: doc_categoria,
                        parentesco: doc_referencia,
                        switch: "",
                        pfrm_value: "",
                        id_persona_sip: id_persona,
                        id_observacion: "1",
                        usr_id: this.usrId,
                    };

                    console.log("documentos datoss 3=====>", datos);
                    axios.post("api/guardarDocumentosRequisitos", datos).then((response) => {
                        console.log("respuesta", response.data);


                        const data = {
                            cas_id: registroCaso.cas_id,
                            cas_act_id: act_id,
                            cas_nodo_id: act_nodo_id,
                            cas_usr_id: registroCaso.cas_usr_id,
                            estado_derivacion: registroCaso.act_data.act_orden == 68 ? 'APROBADO' : 'FIRMADO',
                            cas_estado: registroCaso.act_data.act_orden == 68 ? 'A' : 'T'
                        };
                        console.log('documentos datoss 4=====>', data);
                        axios.post('api/derivarCasosFirma', data)
                            .then(response => {
                                that.loading = false;
                                that.recargar();
                                Swal.fire('El caso fue derivado a: ', response.data.data[0].vrespuesta, 'success');

                            })
                            .catch(error => {
                                console.error('Error al generar o abrir el PDF', error);
                                that.loading = false;
                            });


                    }).catch((error) => {
                        console.error("Error al generar o abrir el PDF", error);
                    });
                }).catch(error => {
                    console.error('Error al obtener la actividad:', error);
                });
            },
            actualizarTokenFirmaMasiva() {
                var slots = [];
                var self = this; // Captura el contexto de 'this'
                setTimeout(() => {
                    $.ajax({
                        url: 'https://localhost:9000/api/token/connected',
                        type: 'GET',
                        dataType: 'json',
                        success: function (json) {
                            const tokens = json.datos.tokens;
                            if (tokens.length > 0) {
                                for (let i = 0; i < tokens.length; i++) {

                                    slots.push({
                                        slot: tokens[i].slot,
                                        dispositivo: tokens[i].name
                                    });
                                }
                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                                console.log(self.storeToken);

                            } else {
                                slots.push({
                                    slot: 0,
                                    dispositivo: json.mensaje
                                });

                                self.storeToken = slots;
                                self.storeTokenFondos = slots;
                                self.$swal({
                                    title: '<b>ATENCIÓN</b>',
                                    text: json.mensaje,
                                    icon: 'warning',
                                    confirmButtonText: 'Ok'
                                });
                            }
                            $('#modalFirmaMasiva').modal('show');
                        },
                        error: function (xhr, status) {
                            console.log(xhr, status);
                            this.$swal({
                                title: '<b>ATENCIÓN</b>',
                                text: 'Hubo un problema.',
                                icon: 'warning',
                                confirmButtonText: 'Aceptar'
                            });
                            //Ext.MessageBox.alert('<b>ATENCIÓN</b>', 'Hubo un problema.');
                        }
                    });
                }, 1000); // Simulamos un retardo de 1 segundo (1000 ms)

            },
            seguimientoTramites(cas_id, area) {
                console.log("seguimientoTramites");
                //var paramss = { "cas_id": this.registro.cas_id };
                //let url = "api/SetSeguimientoTramites";
                //axios.post(url, paramss)
                var urlDatosSeguimientoTramite = "api/datosSeguimientoTramite/" + this.registro.cas_id;
                axios.get(urlDatosSeguimientoTramite)
                    .then(response => {
                        /*var jsonbAggString = response.data.data[0].cas_data;
                        var jsonbArray = JSON.parse(jsonbAggString);
                        var codigosolicitud = response.data.data[0].codigosolicitud;
                        var now = response.data.data[0].formatted_date;
                        var usuario_registro = response.data.data[0].usuario_registro;
                        var ID_SOLICITUDPRESTACIONN = response.data.data[0].id_solicitudprestacionn;
                        let auxval = '';
                        const items = this.registro.cas_data_valores;
                        for (var i = 0; i < items.length; i++) {
                            if (items[i].frm_campo === 'AS_TIPO_DOCUMENTO' || items[i].frm_campo === 'AS_CI' || items[i].frm_campo === 'AS_COMPLEMENTO') {
                                const value = items[i].frm_value ?? "";
                                auxval += value + "-";
                            }
                        }

                        // Eliminar el último guion si existe
                        if (auxval.endsWith("-")) {
                            auxval = auxval.slice(0, -1);
                        }

                        const varReferencialesPost = [];
                        var params = {
                            "numeroTramite": "",
                            "fechaInicioTramite": "",
                            "departamento": "",
                            "regionalOrigen": "",
                            "cua": "",
                            "nombresApellidosAsegurados": " ",
                            "nombreTramite": "",
                            "vigenciaDias": 0,
                            "numeroFCA": "",
                            "id_solicitud_tramites": 0,
                            "documento_asegurado": ""
                        };
                        //params.fechaInicioTramite=now;
                        params.documento_asegurado = auxval;
                        params.numeroTramite = codigosolicitud;
                        params.fechaInicioTramite = now;
                        params.id_solicitud_tramites = ID_SOLICITUDPRESTACIONN ? ID_SOLICITUDPRESTACIONN : 0;
                        params.departamento = jsonbArray['cas_departamento'];
                        params.regionalOrigen = jsonbArray['cas_regional'];
                        params.cua = jsonbArray['AS_CUA'];
                        params.nombreTramite = jsonbArray['NOMBRE_PROCESO'];
                        var AS_PRIMER_APELLIDO = jsonbArray['AS_PRIMER_APELLIDO'];
                        var AS_SEGUNDO_APELLIDO = jsonbArray['AS_SEGUNDO_APELLIDO'];
                        var AS_APELLIDO_CASADA = jsonbArray['AS_APELLIDO_CASADA'];
                        var AS_PRIMER_NOMBRE = jsonbArray['AS_PRIMER_NOMBRE'];
                        var AS_SEGUNDO_NOMBRE = jsonbArray['AS_SEGUNDO_NOMBRE'];
                        // Llenar valores según el frm_campo
                        params.nombresApellidosAsegurados = [
                            AS_PRIMER_NOMBRE,
                            AS_SEGUNDO_NOMBRE,
                            AS_PRIMER_APELLIDO,
                            AS_SEGUNDO_APELLIDO,
                            AS_APELLIDO_CASADA
                        ].filter(Boolean).join(" ");*/
                        //************************************* */
                        var params = JSON.parse(response.data.data);
                        const url = `${this.urlGestoraSgg}/otorgamiento-prestaciones/api/v2/seguimientoTramites/registrar?area=${area}&usuReg=${params.usuario_registro}`;
                        axios.post(url, params)
                            .then(respuesta => {
                                this.loading = false;
                                if (respuesta.data.codigo == '200') {
                                    let userAsignado = respuesta.data.data;
                                    //DERIVACION
                                    this.derivarCasoSeguimientoTramite();////
                                    this.tomarCasoUsuario(userAsignado, cas_id);
                                    Swal.fire({
                                        title: 'El caso fue derivado a: ',
                                        text: userAsignado,
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar'
                                    });
                                } else {
                                    if (respuesta.data.codigo == '201') {
                                        let userAsignado = respuesta.data.data;
                                        ////DERIVACION
                                        this.derivarCasoSeguimientoTramite();////
                                        this.tomarCasoUsuario(userAsignado, cas_id);
                                        Swal.fire('Error: ', respuesta.data.data, 'warning');
                                    } else {
                                        Swal.fire('El caso no pudo ser enviado a UCPP', '', 'warning');
                                    }
                                }
                            })
                            .catch(error => {
                                Swal.close();  // Cerrar la alerta de carga
                                console.error('Error en la solicitud:', error);
                                Swal.fire({
                                    title: 'Hubo un incomveniente en El envío a la UCPP, Enviar otra vez? ',
                                    icon: 'question',
                                    showCancelButton: false,
                                    confirmButtonText: 'Sí',
                                    cancelButtonText: 'No',
                                    onBeforeOpen: () => {
                                        Swal.showLoading();  // Mostrar el indicador de carga
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        this.loading = true;
                                        this.seguimientoTramites(cas_id, area);
                                        Swal.close();
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        // Lógica a ejecutar si el usuario selecciona 'No' o cancela
                                        Swal.close();  // Cerrar la alerta de carga
                                        //Swal.fire('Acción cancelada', '', 'info');
                                    }
                                });
                                // Manejar el error según tus necesidades
                            });
                    })
                    .catch(error => {
                        Swal.close();  // Cerrar la alerta de carga
                        console.error('Error en la solicitud:', error);
                        // Manejar el error según tus necesidades
                    });
            },
            derivarCasoSeguimientoTramite() {
                console.log('derivarCasoSeguimientoTramite');
                //this.loading = true;
                //derivar
                let gRegistro = this.registro;
                gRegistro.cas_estado = 'A';
                gRegistro.cas_act_id = this.siguiente.act_id;
                gRegistro.cas_nodo_id = this.siguiente.act_nodo_id;
                gRegistro.cas_usr_id = this.usrId;
                let that = this;
                console.log(JSON.stringify(gRegistro));
                //********************* */
                let url = "api/casosDerivar/" + this.registro.cas_id + '?usuario=' + this.usrUser;
                let arrayUsrNodo = [];
                let ultimoUsr = 0;
                let usrTomar = 0;
                let act_nodo = this.siguiente.act_nodo_id;
                axios.put(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        if (response.status == "200") {
                            /*const datosv = {  cas_id: that.registro.cas_id};
                            axios.post('api/VerificacionNodoSiguiente', datosv)
                            .then(responsev => {*/
                            let tipoDer = that.siguiente.act_data.act_tipo_derivacion;
                            // aqui validar round robijn o sel service.
                            if (tipoDer == 'ROUND_ROBIN') {
                                console.log("🚀 ~ tipoDer:", tipoDer)
                                // a partir de aquí Round-robin
                                // indice = -1
                                let indice = -1;
                                // 1. leer los usuarios del nodo
                                // con el servicio GET usrNodosXNodoId/{nodo_id} OK servicio programado
                                console.log("🚀 ~ that.siguiente:", that.siguiente.act_nodo_id)
                                let url2 = "api/usrNodosXNodoId/" + that.siguiente.act_nodo_id;

                                axios.get(url2)
                                    .then(function (response2) {
                                        let array = response2.data.data;
                                        // 2. leer los registros en un array local
                                        for (var i = 0; i < array.length; i++) {
                                            var a = array[i].id;
                                            arrayUsrNodo[i] = a;
                                        }
                                        // 3. leer el ultimo_usr de rmx_vys_usrnodos_roundrobin
                                        // con el servicio GET usrNodosRR/{nodo_id}
                                        // si no hay registros LEN==0,
                                        // entonces index=0 para JUGAR con arreglo
                                        // si es -1 va a 4. sino 5.
                                        console.log("🚀 ~ that.siguiente.act_nodo_id usrNodosRR:", that.siguiente.act_nodo_id)
                                        let url3 = "api/usrNodosRR/" + that.siguiente.act_nodo_id;

                                        axios.get(url3)
                                            .then(function (response3) {

                                                ultimoUsr = response3.data.data[0].rr_ultimo_usr_id;
                                                console.log("🚀 ~  response3.data.data[0].rr_ultimo_usr_id:", response3.data.data[0].rr_ultimo_usr_id)
                                                // 4. JUGAR con el arreglo para saber el "siguiente usuario"
                                                // forzar usar el servicio PUT estadoCaso/{cas_id}
                                                // con body
                                                // para tomar el usuario <------??? analizar
                                                console.log("🚀 ~ arrayUsrNodo[0]:", arrayUsrNodo)
                                                if (ultimoUsr == 0) {
                                                    usrTomar = arrayUsrNodo[0];

                                                } else {
                                                    for (var j = 0; j < arrayUsrNodo.length; j++) {
                                                        if (arrayUsrNodo[j] == ultimoUsr) {
                                                            if (j + 1 == arrayUsrNodo.length) {
                                                                usrTomar = arrayUsrNodo[0];
                                                            } else {
                                                                usrTomar = arrayUsrNodo[j + 1];
                                                            }
                                                        }
                                                    }
                                                }
                                                that.tomarCasoRoundRobin(usrTomar, that.registro.cas_id);
                                                // 5. Actualizar el ultimo usuario
                                                // en servicio PUT usrNodosRR/{nodo_id}
                                                // con body "siguiente usuario"
                                                let gData = {};
                                                gData.nodo_id = that.siguiente.act_nodo_id;
                                                gData.ult_usr = usrTomar;
                                                let url = "api/usrNodosRR";
                                                axios.put(url, gData)
                                                    .then(function (response) {
                                                        that.output = response.data;
                                                        that.recargar();
                                                    })
                                                    .catch(function (error) {
                                                        that.output = error;
                                                    });
                                            })
                                            .catch(function (error2) {
                                                that.output = error2;
                                            });
                                    })
                                    .catch(function (error2) {
                                        that.output = error2;
                                    });
                            } else { //if( tipoDer == 'SELF_SERVICE'){
                                console.log("🚀 ~ that.recargar();:")
                                that.recargar();
                            }

                            that.loading = false;
                        }
                    })
                    .catch(function (error) {
                        that.output = error;
                        that.loading = false;
                    });

                //**************************** */

            },
            async derivacionMasivaLegal() {
                this.loading = true;
                this.siguiente = { act_data: {} };
                let url = "api/actividad/15/40";
                const response = await axios.get(url);
                this.siguiente = response.data.data.map(row => ({
                    ...row,
                    act_data: JSON.parse(row.act_data)
                }))[0];
                console.log(this.siguiente);
                if (this.siguiente.act_data.act_tipo_derivacion == 'UNITARIA') {
                    const params = this.siguiente.act_nodo_id;
                    const response = await axios.get("api/obtenerUsuariosDelNodo/" + params);
                    this.userNodo = response.data.data;
                }
                $('#modalDerivarMasivoLegal').modal('show');
                this.loading = false;
            },
            async derivacionMasivaNodo() {
                this.isCheckedSendEmailSms = true;
                if (this.selected_ids.length === 0) {
                    Swal.fire('Debe seleccionar al menos un caso para la derivacion masiva.', '', 'warning');
                    return;
                }
                this.showModalDerivarMultiple = true;
                const url = "api/actividad/" + this.selectOptionNodo.act_prc_id + "/" + this.selectOptionNodo.siguiente;
                const response1 = await axios.get(url);
                this.siguiente = response1.data.data.map(row => ({
                    ...row,
                    act_data: JSON.parse(row.act_data)
                }))[0];
                const response = await axios.get("api/obtenerUsuariosDelNodo/" + this.siguiente.act_nodo_id);
                this.userNodo = response.data.data;
                this.cargando = false;
            },
            async derivarMultipleUsuario(e) {
                this.closeModalDerivacionMasiva();
                let usuarioDerivar = e.id;
                var casIds = this.selected_ids;
                let datos = { casIds: casIds, usr_id: usuarioDerivar, act_id: this.siguiente.act_id, nodo_id: this.siguiente.act_nodo_id }
                let that = this;
                let url = "api/casosDerivarMultiple/" + casIds;
                axios.put(url, datos)
                    .then(function (response) {
                        that.output = response.data;
                        that.listarRegistros(that.selectOptionNodo.act_id);
                        that.selected_ids = [];
                        that.selectOptionNodo = '';
                    })
                    .catch(function (error) {
                        that.output = error;
                        that.loading = false;
                    });
            },
            async asignacionMasiva() {
                this.loading = true;
                if (this.selected_ids.length == 0) {
                    Swal.fire('No selecciono ningun tramite para asignar', '', 'warnig');
                } else {
                    this.usuarioNodo = null;
                    this.descripcionUsuarioAsignacion = '';
                    this.valUsuarioAsignacion = false;
                    var url = "api/usuariosNodo/" + this.selectedNodeId;
                    axios.get(url).then(response => {
                        this.usuariosNodo = response.data.data;
                        $('#modalAsignacionMasivaCasos').modal('show');
                    });
                }
                this.loading = false;
            },

            confirmarAsignacionMasiva() {
                if (!this.usuarioNodo) {
                    this.valUsuarioAsignacion = true;
                    return;
                }
                this.nombreUsuarioAsignacion = this.usuarioNodo ? this.usuarioNodo.label : '';
                $('#modalConfirmacionMasiva').modal('show');
            },
            hideValUsuarioAsignacion() {
                this.valUsuarioAsignacion = false;
            },

            asignarCaso() {
                let datos = { cas_ids: this.selected_ids, cas_usr_id: this.usuarioNodo.value, descripcion: this.descripcionUsuarioAsignacion };
                axios.post('api/asignarCasoMasivo', datos)
                    .then(response => {
                        Swal.fire('El caso fue asignado', '', 'success');
                        setTimeout(() => {
                            $('#modalConfirmacionMasiva').modal('hide');
                            $('#modalAsignacionMasivaCasos').modal('hide');
                            this.$router.push('/misCasos');
                        }, 300);
                        this.recargar();
                    })
                    .catch(error => {
                        $('#modalConfirmacionMasiva').modal('hide');
                        $('#modalAsignacionMasivaCasos').modal('hide');
                        console.error('Error al generar la asignacion', error);
                    });
            },

            derivacionMasivaLegalDestinatario(e) {
                this.selected_ids;
                let usuarioDerivar = e.id;

                this.selected_ids.forEach((id) => {
                    this.registro = this.registros.find(registro => registro.cas_id == id);
                    this.registro.cas_data.de_usuario = this.registro.cas_data.a_usuario;
                    this.registro.cas_data.a_usuario = e.nom_usuario;

                    let gRegistro = this.registro;
                    gRegistro.cas_estado = 'T';
                    gRegistro.cas_act_id = this.siguiente.act_id;
                    gRegistro.cas_nodo_id = this.siguiente.act_nodo_id;
                    gRegistro.cas_usr_id = usuarioDerivar;

                    let url = "api/casosDerivar/" + this.registro.cas_id + '?usuario=' + this.usrUser;

                    let that = this;

                    axios.put(url, gRegistro)
                        .then(function (response) {
                            that.output = response.data;
                            that.recargar();
                            //Swal.fire('Derivado', '', 'success');
                            //that.loading = false;
                        })
                        .catch(function (error) {
                            //that.output = error;
                            //that.loading = false;
                        });

                });
            },
            async dictamenRegistroObservado() {
                try {
                    let that = this;
                    var idsol = String(this.registro.cas_data.ID_SOLICITUDPRESTACION).trim();
                    const urlActualizaTramite = `${this.urlGestoraSgg}/prestaciones-riesgos/api/v1/verificar/actualizaTramite?idSolicitudTramite=` + idsol + `&usuMod=` + this.usrUser + `&codeTransaccion=10`;
                    let respuesta = await axios.put(urlActualizaTramite);
                    if (respuesta.data.codigo === '0') {
                        let userAsignado = respuesta.data.data.usuarioPres;
                        console.log("🚀 ~ axios.get ~ userAsignado:", userAsignado.usuarioPres);

                        let gRegistro = {
                            "emailUser": userAsignado
                        };
                        let url = "api/casosDerivarINVPMSgte/" + this.registro.cas_id;
                        axios.put(url, gRegistro)
                            .then(function (response) {
                                console.log("🚀 ~ response:", response)
                                if (response.data.codigoRespuesta == "200") {
                                    that.recargar();
                                    Swal.fire({
                                        title: 'El caso fue derivado a: ',
                                        text: userAsignado,
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                                else {
                                    Swal.fire('El caso no pudo ser enviado', response.data, 'warning');
                                }

                            })
                            .catch(function (error) {
                                Swal.fire('El caso no pudo ser enviado', respuesta.data, 'warning');
                            });


                    } else {
                        Swal.fire('El caso no pudo ser enviado', respuesta.data, 'warning');
                    }
                } catch (error) {
                    Swal.close();
                    console.error('Error en la solicitud:', error);

                    Swal.fire({
                        title: 'Hubo un inconveniente en el envío. ¿Desea enviar de nuevo?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí',
                        cancelButtonText: 'No',
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            this.loading = true;
                            await this.dictamenRegistroObservado(); // Reintento de la operación
                            Swal.close();
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.close();
                        }
                    });
                }
            },

            mostrarEstadoGeneracionEAP(cas_data_valores) {
                if (typeof cas_data_valores === 'string') {
                    try {
                        cas_data_valores = JSON.parse(cas_data_valores);
                        console.log('cas_data_valores convertido:', cas_data_valores);
                    } catch (error) {
                        console.error('Error al convertir cas_data_valores a objeto:', error);
                        return null;
                    }
                }

                let valorEstadoGeneracionEAP = null;
                if (cas_data_valores.some(item => item.frm_campo === 'ESTADO_GENERACION_EAP')) {
                    valorEstadoGeneracionEAP = cas_data_valores.find(item => item.frm_campo === 'ESTADO_GENERACION_EAP').frm_value;
                    //console.log('ESTADO_GENERACION_EAP:', valorEstadoGeneracionEAP);
                    return valorEstadoGeneracionEAP;
                }
                return null;
            },

            mostrarEstadoDerivacion(cas_data_valores) {
                if (typeof cas_data_valores === 'string') {
                    try {
                        cas_data_valores = JSON.parse(cas_data_valores);
                        console.log('cas_data_valores convertido:', cas_data_valores);
                    } catch (error) {
                        console.error('Error al convertir cas_data_valores a objeto:', error);
                        return null;
                    }
                }

                let valorEstadoGeneracionEAP = null;
                if (cas_data_valores.some(item => item.frm_campo === 'ESTADO_DERIVACION')) {
                    valorEstadoGeneracionEAP = cas_data_valores.find(item => item.frm_campo === 'ESTADO_DERIVACION').frm_value;
                    //console.log('ESTADO_GENERACION_EAP:', valorEstadoGeneracionEAP);
                    return valorEstadoGeneracionEAP;
                }
                return null;
            },

            async dictamenRegistro() {
                try {
                    var urlDatosDictamenRegistro = "api/datosDictamenRegistro/" + this.registro.cas_id;

                    // Obtener datos de dictamen
                    let response = await axios.get(urlDatosDictamenRegistro);
                    var params = JSON.parse(response.data.data);
                    console.log("🚀 ~ dictamenRegistro ~ params:", params)

                    // Enviar el dictamen a la URL correspondiente
                    const urlDictamenRegistro = `${this.urlGestoraSgg}/prestaciones-riesgos/api/v1/tribunalMedico/dictamenRegistro?sys=1`;
                    let respuesta = await axios.post(urlDictamenRegistro, params);

                    console.log(respuesta);
                    this.loading = false;
                    let that = this;
                    if (respuesta.data && respuesta.data.codigo === '0') {
                        let userAsignado = respuesta.data.data;
                        // Validar si `userAsignado` está definido
                        if (userAsignado) {
                            console.log("🚀 ~ axios.get ~ userAsignado:", userAsignado);

                            // DERIVACION usando await para promesas
                            /* await this.derivarCasoSeguimientoTramite();
                            await this.tomarCasoUsuario(userAsignado, this.registro.cas_id);*/
                            let gRegistro = {
                                "emailUser": userAsignado
                            };
                            let url = "api/casosDerivarINVPMSgte/" + this.registro.cas_id;
                            axios.put(url, gRegistro)
                                .then(function (response) {
                                    console.log("🚀 ~ response:", response)
                                    if (response.data.codigoRespuesta == "200") {
                                        that.recargar();

                                        Swal.fire({
                                            title: 'El caso fue derivado a: ',
                                            text: userAsignado,
                                            icon: 'success',
                                            confirmButtonText: 'Aceptar'
                                        });

                                    }
                                    else {
                                        Swal.fire('El caso no pudo ser enviado', response.data, 'warning');
                                    }
                                })
                                .catch(function (error) {
                                    Swal.fire('El caso no pudo ser enviado', respuesta.data, 'warning');
                                });
                                Swal.fire({
                                    title: 'El caso fue derivado a: ',
                                    text: userAsignado,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                });
                        } else {
                            // Si `userAsignado` es nulo o no está definido
                            console.error('Error: `userAsignado` no está definido.');
                            Swal.fire('Error', 'No se pudo asignar el usuario correctamente.', 'error');
                        }

                    } else if (respuesta.data.codigo === '1') {
                        Swal.fire('El caso no pudo ser enviado', respuesta.data.data, 'warning');

                    } else if (respuesta.data.codigo === '2') {
                        if (respuesta.data.data && respuesta.data.data.includes("El estado del tramite no es el correcto: REVISADO_PRES")) {

                            var idsol = String(this.registro.cas_data.ID_SOLICITUDPRESTACION).trim();
                            const urlActualizaTramite = `${this.urlGestoraSgg}/prestaciones-riesgos/api/v1/verificar/actualizaTramite?idSolicitudTramite=` + idsol + `&usuMod=` + this.usrUser + `&codeTransaccion=11`;
                            let respuesta = await axios.put(urlActualizaTramite, params);
                            if (respuesta.data.codigo === '0') {
                                let userAsignado = respuesta.data.data.usuarioPres;

                                /*  await this.derivarCasoSeguimientoTramite();
                                await this.tomarCasoUsuario(userAsignado, this.registro.cas_id);*/
                                let gRegistro = {
                                    "emailUser": userAsignado
                                };
                                let url = "api/casosDerivarINVPMSgte/" + this.registro.cas_id;
                                axios.put(url, gRegistro)
                                    .then(function (response) {
                                        console.log("🚀 ~ response:", response)

                                        if (response.data.codigoRespuesta == "200") {
                                            that.recargar();

                                            Swal.fire({
                                                title: 'El caso fue derivado a: ',
                                                text: userAsignado,
                                                icon: 'success',
                                                confirmButtonText: 'Aceptar'
                                            });
                                        }
                                        else {
                                            Swal.fire('El caso no pudo ser enviado', response.data, 'warning');
                                        }
                                    })
                                    .catch(function (error) {
                                        Swal.fire('El caso no pudo ser enviado', respuesta.data, 'warning');
                                    });


                            }
                            else {
                                Swal.fire('El caso no pudo ser enviado', respuesta.data, 'warning');
                            }

                        } else {

                            Swal.fire('El caso no pudo ser enviado', respuesta.data.data, 'warning');
                        }

                    } else {
                        Swal.fire('El caso no pudo ser enviado', '', 'warning');
                    }
                } catch (error) {
                    Swal.close();
                    console.error('Error en la solicitud:', error);

                    Swal.fire({
                        title: 'Hubo un inconveniente en el envío. ¿Desea enviar de nuevo?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí',
                        cancelButtonText: 'No',
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            this.loading = true;
                            await this.dictamenRegistro(); // Reintento de la operación
                            Swal.close();
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.close();
                        }
                    });
                }
            },

            doFirmaPdf(ruta) {
                var url = "/api/verFirmaDocumentoPdfNfs/" + ruta;
                axios.get(url)
                    .then(response => {
                        this.listaFirma = response.data;
                    })
                    .catch(error => {
                        console.error('Error al mostrar el documento:', error);
                    });
            },

            formatoTiempo(dateString) {
                const date = new Date(dateString);
                const options = { hour: '2-digit', minute: '2-digit' }; // Ejemplo: solo horas y minutos
                return new Intl.DateTimeFormat(undefined, options).format(date);
            },

        },

        /* beforeUpdate: function () {
            if (this.dataTable) {
                this.dataTable.destroy()
            }
        }, */

        updated: function () {
            console.log('updated');
            /* this.dataTable = $("#divTable").DataTable({
                responsive: true,
                order: [],
                "language": {
                    "lengthMenu": "Desplegar _MENU_ registros por página",
                    "zeroRecords": "Sin registros",
                    "info": "Página _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen registros disponibles",
                    "infoFiltered": "(filtrados de _MAX_ registros en total)",

                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Última",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },

                }
            }); */
            $(document).on('hidden.bs.modal', function (event) {
                if ($('.modal:visible').length) {
                    $('body').addClass('modal-open');
                }
            });
        }

    }
</script>

<style scoped>
    .break-text {
        display: inline-block;
        max-width: 70ch;
        word-wrap: break-word;
        white-space: pre-wrap;
        text-align: left;
    }

    .background-container {
        position: relative;
        background: url('/img/marca_agua_gestora_bandeja.png') no-repeat center center;
        background-size: 50%;
    }
    #modalDerivar .loader-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.91);
    }

    #modalDerivar .loader-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #modalDerivar .loader {
        border: 12px solid #f3f3f3;
        border-top: 12px solid #3498db;
        border-radius: 50%;
        width: 100px;
        height: 100px;
        animation: spin 2s linear infinite;
    }

    #modalDerivar .loader-text {
        font-size: 12px;
        color: #3498db;
        position: absolute;
        font-weight: bold;
    }

    #modalDerivar .loading-text {
        font-size: 10px;
        color: #3498db;
        z-index: 1;
        position: absolute;
        top: 50%;
        margin-top: 5px;
        animation: blink 1.5s step-start infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .espaciado {
        margin-top: 150px;
    }

    @keyframes hammer {
        0% {
            transform: rotate(0deg);
        }

        20% {
            transform: rotate(-20deg);
        }

        40% {
            transform: rotate(0deg);
        }

        60% {
            transform: rotate(-20deg);
        }

        80% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    .expandable {
        /* border: 1px solid #ddd;
        border-radius: 8px; */
        margin: 5px 0;
        overflow: hidden;
        /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
        transition: box-shadow 0.3s ease;
    }

    /* .expandable:hover {
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
    } */

    .expandable-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #007bff;
        color: #fff;
        padding: 1px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .expandable-header:hover {
        background-color: #0056b3;
    }

    .expandable-title {
        margin: 0;
        font-size: 16px;
        font-weight: bold;
    }

    .toggle-button {
        background: none;
        border: none;
        color: #fff;
        font-size: 10px;
        padding: 2px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .toggle-button i {
        font-size: 6px;
        transition: transform 0.3s ease;
    }

    .expandable-content {
        padding: 6px;
        /* background-color: #f9f9f9; */
        font-size: 13px;
        line-height: 1.5;
    }

    .content-actions {
        margin-top: 15px;
    }

    .content-actions .btn {
        margin-right: 10px;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s ease;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }
</style>

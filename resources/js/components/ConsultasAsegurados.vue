<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h1>{{ plural }}</h1>


                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="row">

                            <div class="col-md-2">
                                        <strong> Trámite</strong>
                                <input type="text" v-model="filtro.cas_nro_caso" class="form-control" placeholder="Nro. trmite"
                                    @keyup.enter="onEnter">
                            </div>



                            <div class="col-md-3">
                                <strong> Tipo de Identificación</strong>
                                <select v-model="filtro.cas_tipo" class="form-control"
                                    placeholder="Tipo Identificacion">
                                    <option value="">-- Tipo de Identificacion --</option>
                                    <option value='AS_CI'>CI</option>
                                    <option value='AS_CUA'>CUA</option>
                                </select>
                            </div>
                            <div class="col-md-2" v-if="filtro.cas_tipo">
                                <input type="number" v-model="filtro.num_identificacion" class="form-control"
                                    placeholder="Numero" v-validate="{ required: filtro.cas_tipo }"
                                    @keyup.enter="onEnter">
                            </div>

                            <div class="col-md-2">
                                <button class="form-control btn btn-primary" @click="listarRegistros()">
                                    <i class="fa fa-search white" aria-hidden="true"></i> Buscar
                                </button>
                            </div>

                            <div class="col-md-2">
                                <button class="form-control btn btn-primary" @click="limpiarFiltrosUrl()">
                                    <i class="fa fa-eraser white" aria-hidden="true"></i> Limpiar Filtros
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="navegacion">
                        <select name="porPagina" @change="listarRegistros" v-model="RegistrosXPagina"
                            class="selectRegistros">
                            <option v-for="n in opcionesRegistrosPorPagina" :key="n" :value="n">{{ n }}</option>
                        </select>
                        <div class="select-container">
                            <label style="color: white; font-size: 1.2em;">{{ drawPaginatorText()}}</label>
                        </div>

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
                            <input type="search" v-model="buscarRegistro" @input="bRegistros()"
                                @keyup.enter="bRegistros" class="selectRegistros" placeholder="Buscar">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Nro</th>
                                <th scope="col">ESTADO</th>
                                <th scope="col">UNIDAD </th>
                                <th scope="col">No. CASO</th>
                                <th scope="col">NOMBRE DEL ASEGURADO</th>


                            </tr>
                        </thead>
                        <tbody>

                            <div id="overlay" ref="overlay" class="overlay">
                                <div class="loader-wrapper">
                                    <div class="loader"></div>
                                    <span class="loader-text">TramiteSIP</span>
                                    <span class="loading-text">Cargando...</span>
                                </div>
                            </div>

                            <tr v-for="(r, index) in ordenarDatos" :key="r.cas_id">
                                <td width="3%" scope="row">{{ (index+1) }}</td>

                                <td scope="row">

                                    {{ r.nodo_descripcion }}


                                </td>
                                <td>
                                    <strong>{{ r.prc_data.prc_descripcion }}</strong><br>

                                    {{ r.act_data.act_descripcion }} <br>


                                </td>
                                <td align="center">
                                    {{ r.cas_cod_id }} <br>

                                </td>
                                <td>


                                {{ formatNombreCaso(r.cas_data.cas_nombre_caso) }}
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal de consultas TramiteSIP -->
        <div v-if="showModal" class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog" role="document" style="max-width: 30%;">
                <div class="modal-content">
                    <div class="modal-header" style="background:#007bff; color: white;">
                        <div style="display: flex; align-items: center;">
                            <img src="/img/animacion-chat-bot-gestora3.gif" alt="GESTORA - TRAMITESIP" style="margin-left: 10px; width: 60px; height: auto;" />
                            <marquee behavior="scroll" direction="left" style="flex-grow: 1;">
                                <h5 class="modal-title" style="margin: 0;">
                                    Consultas TramiteSIP - Tu Jubilación digna y segura.
                                </h5>
                            </marquee>
                        </div>
                        <button type="button" class="close" @click="closeChatModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <p><strong>Número de Tramite: {{ this.currentCasCodId }} </strong></p>
                            <hr>
                            <div v-if="showLoader" class="loader-container">
                                <div class="loader-wrapper">
                                    <div class="loader"></div>
                                    <span class="loader-text">TramiteSip</span>
                                    <span class="loading-text">Cargando...</span>
                                </div>
                            </div>

                            <div class="direct-chat-messages" v-if="!showLoader">
                                <div v-for="(message, index) in messages" :key="message.id"
                                     :class="['chat-bubble', message.isMine ? 'mine' : 'theirs', index % 2 === 0 ? 'even' : 'odd']"
                                     :style="message.isMine ? {} : { maxWidth: '95%' }" ref="messages">
                                     <small class="chat-info">
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> {{ message.regional }}
                                        </span>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-user"></i> {{ message.userName }}
                                        </span> :
                                    </small>
                                    <p>{{ message.mensaje }}</p>
                                    <small class="chat-info">
                                        <span class="badge badge-primary">
                                            <i class="fas fa-calendar-alt"></i> {{ new Date(message.createdAt).toLocaleString() }}
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" v-if="this.currentActOrden == '40'">
                            <form @submit.prevent="sendMessage">
                                <div class="input-group">
                                    <button type="button" class="btn btn-secondary" @click="fetchMessages(usrUser, currentCasCodId, true)" alt="Actualizar ...">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <div style="width: 10px;"></div>
                                    <input v-model="newMessage" placeholder="Escribir mensaje ..." class="form-control" style="height: 40px;">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-warning" :disabled="newMessage.length <= 7 || newMessage.length >= 250">
                                            <strong>Enviar</strong>
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="char-counter">
                                    {{ newMessage.length }} / 250
                                </div>
                            </form>
                        </div>
                        <div v-else>
                            <div class="alert alert-warning" role="alert">
                                <strong>¡Atención!</strong> No se puede enviar mensajes en esta actividad.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal mensajes -->
        <div class="modal fade" id="modalMensaje" tabindex="-1" role="dialog" aria-labelledby="modalMensajeLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMensajeLabel">Mensaje de Rechazo Desistido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Mostrar el mensaje con saltos de línea -->
                    <p><b>Es Rechazado el Desitimiento por los siguiente motivo:</b></p>
                    <p v-html="mensajeRechazoFormateado"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        <!-- #modalAdjuntos -->
        <div class="modal fade" id="modalAdjuntar" tabindex="-1" role="dialog" aria-labelledby="modalAdjuntar"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document" style="max-width: 55%;">
                <div class="modal-content">

                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="exampleModalLabel"> Adjuntar Documentos - {{ nro_tramite }} </h5>
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button> -->
                    </div>

                    <div class="modal-body">

                        <div v-if="mensajeAdvertencia" class="alert alert-danger" role="alert">
                            {{ mensajeAdvertencia }}
                        </div>

                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <div style="background-color: lightgrey; margin-top: 10px">
                                    <h5>Adjuntos</h5>
                                </div>

                                <table class="table table-responsive table table-bordered table-hover table-striped">
                                    <tr scope="col">
                                        <table id="tabla_adjuntar"
                                            class="table table-hover table-striped table-responsive">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col"> Documento </th>
                                                    <th scope="col"> Descripción </th>
                                                    <th scope="col"> Referencia </th>
                                                    <th scope="col"> Original / Fotocopia </th>
                                                    <th scope="col"> Adjuntar</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td style="width: 150px;">
                                                        <input :id="'adjuntarFile_0'" type="file" name="file"
                                                            @change="getDocument($event, documento[0].frm_cols[0].col_campo, 0)"
                                                            accept=".xlsx, .xls, .doc, .docx, .ppt, .pptx, .txt, .pdf">
                                                    </td>

                                                    <td>
                                                        <textarea class="form-control" id="adjuntarDesc_0"
                                                            name="adjuntarDesc[]" rows="1" cols="30"
                                                            @input="validarUnicidad()">
                                                </textarea>
                                                    </td>

                                                    <td>
                                                        <select id="adjuntarSelect_0" name="adjuntarSelect[]"
                                                            class="form-control">
                                                            <option value="" disabled selected>Seleccione una Opción
                                                            </option>
                                                            <option value="Complementario">Complementario</option>
                                                            <option value="Desistido">Desistido</option>
                                                            <option value="RCH Por Fallecimiento">RCH Por Fallecimiento
                                                            </option>
                                                            <option value="RCH Por Legal">RCH Por Legal</option>
                                                            <option value="NOTIF DIC Empleador">NOTIF DIC Empleador
                                                            </option>
                                                            <option value="RCH_2DA_SOLICITUD">RCH 2DA SOLICITUD
                                                                INVALIDEZ MONTO
                                                                PENSION MENOR A LA INICIAL</option>
                                                                <option value="2_RCH_POR_GRP_FAM">RCH 2DA SOL GRUPO FAMILIAR MUERTE ORDEN DE PRELACION</option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <label class="switch">
                                                            <input id="switch_adj_0" type="checkbox">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>

                                                    <td>
                                                        <button type="button" id="adicionar" name="adicionar"
                                                            class="btn_respaldos_plus" v-on:click="adicionarAdjunto">
                                                            +
                                                        </button>
                                                    </td>
                                                </tr>
                                                ---
                                                <tr v-for="(item, index) in items" :key="index">
                                                    <td style="width: 150px;">
                                                        <input :id="'adjuntarFile_' + (index + 1)" type="file"
                                                            @change="validarExtensiones(item.adjuntarFile)"
                                                            accept=".xlsx, .xls, .doc, .docx, .ppt, .pptx, .txt, .pdf">

                                                        <!-- <input :id="'adjuntarFile_' + index" type="file"
															@change="validarExtensiones($event, index)"
															accept=".xlsx, .xls, .doc, .docx, .ppt, .pptx, .txt, .pdf"> -->
                                                    </td>

                                                    <td>
                                                        <textarea :id="'adjuntarDesc_' + (index + 1)"
                                                            class="form-control" v-model="item.adjuntarDesc" rows="1"
                                                            cols="30" @input="descripcionUnico(index)">
                                                </textarea>
                                                    </td>

                                                    <!-- <td>
														<select :id="'adjuntarSelect_0' + (index + 1)"
															v-model="item.adjuntarSelect">
															<option value="Complementario">Complementario</option>
															<option value="Desistido">Desistido</option>
															<option value="Rechazado">Rechazado</option>
														</select>
													</td> -->
                                                    <td>
                                                        <select id="adjuntarSelect_0" name="adjuntarSelect[]"
                                                            class="form-control">
                                                            <option value="" disabled selected>Seleccione una Opción
                                                            </option>
                                                            <option value="Complementario">Complementario</option>
                                                            <option value="Desistido">Desistido</option>
                                                            <option value="RCH Por Fallecimiento">RCH Por Fallecimiento
                                                            </option>
                                                            <option value="RCH Por Legal">RCH Por Legal</option>
                                                            <option value="NOTIF DIC Empleador">NOTIF DIC Empleador
                                                            </option>
                                                            <option value="RCH_2DA_SOLICITUD">RCH 2DA SOLICITUD
                                                                INVALIDEZ MONTO
                                                                PENSION MENOR A LA INICIAL</option>
                                                            <option value="2_RCH_POR_GRP_FAM">RCH 2DA SOL GRUPO FAMILIAR MUERTE ORDEN DE PRELACION</option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <!-- <label :for="'switch_adj_' + index" class="switch">
															<input :id="'switch_adj_' + (index + 1)" type="checkbox">
															<span class="slider round"></span>
														</label> -->

                                                        <label class="switch">
                                                            <input :id="'switch_adj_' + (index + 1)" type="checkbox">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>

                                                    <td>
                                                        <button type="button" class="btn_respaldos_minus"
                                                            @click="removerAdjunto(index)">
                                                            -
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </tr>

                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="subirArchivos">
                            Subir Archivo
                        </button>
                        <span v-if="mensajeArchivoDuplicado" style="color: red;">No puedes subir el mismo archivo más de
                            una vez.
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- -------------- -->


        <!-- modalAnularDesistir -->
        <div class="modal fade" id="modalAnularDesistir" tabindex="-1" role="dialog"
            aria-labelledby="modalAnularDesistir" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 45%;">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="exampleModalLabel">Anulación o Desistimiento de Trámite</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Caso Seleccionado:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Caso ID</th>
                                            <th>Actividad</th>
                                            <th>Nodo</th>
                                            <th>Nro Trámite</th>
                                            <th>Tipo Trámite</th>
                                            <th>Datos Asegurado</th>
                                            <th>Estado Actual</th>
                                            <th>Descripción Derivación</th>
                                            <th>Usuario Actual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="a in recuperarDatosAnular">
                                            <td><span class="badge badge-dark">{{ a.cas_id }}</span></td>
                                            <td><span class="badge badge-danger">{{ (JSON.parse(a.act_data)).act_orden
                                                    }}</span>
                                                -
                                                {{ (JSON.parse(a.act_data)).act_descripcion }}</td>
                                            <td>{{ a.nodo_id }} - {{ a.nodo_descripcion }}</td>
                                            <td><span class="badge badge-success">{{ a.cas_cod_id }}</span></td>
                                            <td>{{ a.cas_data.NOMBRE_PROCESO }}</td>
                                            <td><span v-html="a.cas_data.cas_nombre_caso"></span></td>
                                            <td><span class="badge badge-warning">{{ a.cas_data.ESTADO_DERIVACION
                                                    }}</span></td>
                                            <td>{{ a.cas_data.DESCRIPCION_DERIVACION }}</td>
                                            <td><span class="badge badge-primary">{{ a.nom_usuario }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-4">
                                <label>Tipo:</label>
                                <select class="form-control" v-model="registrosAnularDesistir.estado_derivacion">
                                    <option value='' disabled>--Seleccione--</option>
                                    <option value='ANULADO'>ANULACIÓN DE TRÁMITE</option>
                                    <option value='DESISTIDO'>DESISTIMIENTO DE TRÁMITE</option>
                                </select>
                                <p v-if="!registrosAnularDesistir.estado_derivacion" class="mensaje">Obligatorio</p>
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Descripción:</label>
                                <textarea class="form-control" rows="7"
                                    v-model="registrosAnularDesistir.descripcion_derivacion"
                                    placeholder="Ingrese Descripción de la anulación o desistimiento del Tramite"
                                    @input="registrosAnularDesistir.descripcion_derivacion = registrosAnularDesistir.descripcion_derivacion.toUpperCase()">
                        </textarea>
                                <p v-if="!registrosAnularDesistir.descripcion_derivacion" class="mensaje">Obligatorio
                                </p>
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-5">
                                <label>Archivo de Referencia:</label>
                                <input type="file" accept=".pdf" class="form-control" :id="'archivoAnularDesistir'"
                                    ref="ArchivoAD" rows="5"></input>
                                <p class="mensaje">Seleccione</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            v-on:click="limpiarCamposModalAnularDesistir()">Cancelar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                            v-on:click="doAnularDesistir()">Anular
                            / Desistir Caso</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalCandadito -->
        <div class="modal fade" id="modalCandadito" tabindex="-1" role="dialog" aria-labelledby="modalCandadito"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Obtener {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label for="">Detalle Ramas Paralelas</label>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro</th>
                                            <th>Actividad</th>
                                            <th>Nodo</th>
                                            <th>Usuario</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(c, index) in historicos">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ (JSON.parse(c.act_data)).act_orden + " - " +
                                                (JSON.parse(c.act_data)).act_descripcion }}</td>
                                            <td>{{ c.nodo_descripcion }}</td>
                                            <td>{{ c.nom_usuario }}</td>
                                            <td>{{ c.cas_modificado }}</td>
                                            <td v-html="getEstado(c.cas_estado)"></td>
                                            <td>

                                                <template v-if="JSON.parse(c.act_data).act_orden !== '200'">
                                                    <div v-for="nodo in usrNodos" style="display: inline-block;">
                                                        <button
                                                            v-if="c.cas_estado === 'T' && nodo.nodo_codigo === c.nodo_codigo"
                                                            v-on:click="doTomarCaso(c.cas_id, c.cas_padre_id, c)"
                                                            type="button" class="btn btn-warning btn-circle"
                                                            title="Obtener">
                                                            <i class="fas fa-hands" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </template>
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

        <!-- modalHistorico -->
        <div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="esos"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" style="max-width:55%;">
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
                                <label>Caso:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro</th>
                                            <th>Actividad / ESTADO</th>
                                            <th>Nodo</th>
                                            <th>Fecha Derivación</th>
                                            <th style="text-align: center;">Usuario</th>
                                            <th>Estado</th>
                                            <th>Descripcion</th>
                                            <th style="text-align: center;">Listado de Documentos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(h, index) in historico">
                                            <td align="center"><span class="badge badge-dark">{{ index + 1 }}</span>
                                            </td>
                                            <td>{{ (JSON.parse(h.act_data)).act_orden + " - " +
                                                (JSON.parse(h.act_data)).act_descripcion }} <br>
                                                <span class="badge badge-warning"><strong>{{h.est_codigo}}</strong></span>
                                            </td>
                                            <td>{{ h.nodo_descripcion }}</td>
                                            <td>{{ h.htc_cas_registrado }}</td>
                                            <td align="center">
                                                <span v-if="h.nom_usuario === ''" class="badge badge-dark">SIN
                                                    USUARIO</span>
                                                <span v-else-if="h.nom_usuario !== ''">{{ h.nom_usuario }}</span>
                                            </td>
                                            <td><span
                                                    v-if="(JSON.parse(h.htc_cas_data)).ESTADO_DERIVACION !== 'ANULADO' && (JSON.parse(h.htc_cas_data)).ESTADO_DERIVACION !== 'DESISTIDO'"
                                                    class="badge badge-success">
                                                    {{ (JSON.parse(h.htc_cas_data)).ESTADO_DERIVACION }}</span>
                                                <span v-else class="badge badge-danger">{{
                                                    (JSON.parse(h.htc_cas_data)).ESTADO_DERIVACION }}</span>
                                            </td>
                                            <td>{{ (JSON.parse(h.htc_cas_data)).DESCRIPCION_DERIVACION }}</td>
                                            <td align="center">
                                                <button type="button" class="btn btn-primary btn-circle"
                                                    title="Histórico" v-on:click="doDocumentoPdf(h.htc_id)"
                                                    data-toggle="modal" data-target="#modalDocumentoPdf">
                                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                                </button>
                                                <!-- Nuevo botón al lado de Histórico -->
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL DE LEGAL -->
        <div class="modal fade" id="modalLegal" tabindex="-1" role="dialog" aria-labelledby="esos" aria-hidden="true">
            <!-- <div class="modal-dialog modal-dialog-centered" role="document"> -->
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
                                            <th>Nro Tramite</th>
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
                                                    {{ h.htc_id }}
                                                    <button type="button" class="btn btn-primary btn-circle"
                                                        title="Histórico" v-on:click="doDocumentoPdf(h.htc_id)"
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
                        <h5 class="modal-title" id="exampleModalLabel">Documentos {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Documento:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro</th>
                                            <th>tipo</th>
                                            <th>Descripcion </th>
                                            <th>Ver Documento </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="documento.length > 0">
                                            <tr v-for="(h, index) in documento">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ h.tipo }}</td>
                                                <td>{{ h.descripcion }}</td>
                                                <td align="center">
                                                    <button v-if="h.nombre === ''" type="button"
                                                        class="btn  btn-danger  btn-circle " title="Documento">
                                                        <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                    </button>

                                                    <button v-if="h.nombre != ''" type="button"
                                                        class="btn  btn-success btn-circle " title="Documento"
                                                        v-on:click="openModal(h.doc_id, h.nombre)">
                                                        <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                        <template v-else>
                                            <tr>
                                                <td colspan="4" align="center">Sin documentos</td>
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
                                            <th>Descripcion </th>
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

        <!-- modalDocumentoPdfEdit -->
        <div class="modal fade" id="modalDocumentoPdfEdit" tabindex="-3" role="dialog"
            aria-labelledby="modalDocumentoPdf" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 60%;">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Completar Documentos {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form2" @submit.prevent="actualizarDocumentoPdfEdit()">
                        <div class="modal-body">
                            <div class="row justify-content-left">
                                <div class="col-md-12">
                                    <label>Documento:</label><br>
                                    <table class="table table-hover table-striped table-responsive" id="tabla_titular">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Id</th>
                                                <th>tipo</th>
                                                <th>Descripcion </th>
                                                <th>Ver Documento </th>
                                                <th scope="col">Original/Fotocopia</th>
                                                <th scope="col">Observacion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(h, index) in documento">
                                                <td :id="'doc_id_' + index">{{ h.doc_id }}</td>
                                                <td>{{ h.tipo }}</td>
                                                <td :id="'descripcion_tit_' + index">{{ h.descripcion }}</td>
                                                <td>
                                                    <div class="input-group mb-3">
                                                        <input :id="'documento_' + index" v-model="h.url"
                                                            class="form-control" placeholder="Ingrese Documento"
                                                            disabled>
                                                    </div>
                                                    <input :id="'pdf_tit_' + index" type="file" name="file"
                                                        @change="getDocument($event, h.frm_cols[index].col_campo, index)"
                                                        accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf">
                                                </td>
                                                <td>
                                                    <label :for="'switch_tit_' + index" class="switch">
                                                        <input :id="'switch_tit_' + index"
                                                            v-model="h.doc_copia_original" type="checkbox">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <select :id="'id_observacion_tit_' + index"
                                                        v-model="h.doc_id_onbservacion">
                                                        <option v-for="opcion in observacion"
                                                            :value="opcion.id_observacion">{{
                                                            opcion.codigo }}
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" form="form2" class="form-control btn-primary">
                                        <i class="fa fa-floppy-o white" aria-hidden="true"></i>
                                        Guardar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.break-text {
    display: inline-block;
    max-width: 70ch;
    word-wrap: break-word;
    white-space: pre-wrap;
    text-align: left;
}
.char-counter {
    text-align: right;
    font-size: 0.9em;
    color: #555;
    margin-top: 5px;
}
</style>

<script>
import datatables from 'datatables';
import jsPDF from 'jspdf';
import Swal from 'sweetalert2';
import config from './config.js';
import axios from "axios";

import { EventoMensaje } from './eventoMensajes.js';

export default {
    name: 'servicios',
    data() {
        return {
            showLoader: false,
            messages: [],
            newMessage: '',
            showModal: false,
            currentCasCodId: '',
            currentActOrden: '',

            plural: 'Consulta de Trámites',
            singular: 'Caso',

            filtro: {
                cas_gestion: '',
            },
            years: [],

            PaginaActual: 1,
            RegistrosXPagina: 10,

            opcionesRegistrosPorPagina: [10, 15, 20, 25],

            paginas: Array.from({ length: 100 }, (_, index) => index + 1),
            PaginaActual: 1,

            buscarRegistro: '',

            ordenadito: {
                cod: 'cas_id',
                ordenar: 'asc'
            },

            usrId: window.Laravel.usr_id,
            id_regional: window.Laravel.id_regional,
            nombre_regional: window.Laravel.nombre_regional,
            id_agencia: window.Laravel.id_agencia,
            es_atc: window.Laravel.es_atc,
            usrUser: window.Laravel.usr_user,
            es_supervisor: window.Laravel.es_supervisor,
            usr_name: window.Laravel.usr_name,
            seleccionado: '',
            errores: [],
            registro: { cas_data: {} },
            registros: [],
            procesos: [],
            siguiente: { act_data: {} },
            impresiones: [],
            filtro: { prc_codigo: '', cas_nro_caso: '', cas_correlativo: '', cas_gestion: '', cas_tipo: '' },
            dataTable: null,
            casos: [],
            historicos: [],
            usrNodos: [],
            historico: [],
            observaciones: [],
            documento: [],
            observacion: [],
            recuperarDatosAnular: [],
            registrosAnularDesistir: {},
            items: [],
            bandera: 0,
            mensajeArchivoDuplicado: false,
            numero_caso: '',
            estado_caso: '',
            urlGestora: window.Laravel.url_gestora, //config.URL_GESTORA + '',
            urlGestoraSgg: window.Laravel.url_gestora_sgg, //config.URL_GESTORA_SGG + '',
            respuestaLegal: [],
            mensajeAdvertencia: '',
            nro_tramite: 'Gestora',
            tramite: '',
            mensajeRechazo: '',  // Mensaje original del servicio
            mensajeRechazoFormateado: '',  // Mensaje formateado para HTML
            totalCasos: null,

            filtro: {
                prc_codigo: '',
                cas_nro_caso: ''
            },

            mostrarBoton: false,
        }
    },

    mounted() {
        var overlay = document.getElementById("overlay");
        overlay.style.display = 'none';

        const current = new Date();
        const yyyy = current.getFullYear();
        this.filtro = { prc_codigo: '', cas_nro_caso: '', cas_correlativo: '', cas_gestion: '', cas_tipo: '' };

        //this.dataTable = $('#divTable').DataTable({});
        ///this.listarProcesos();
        this.cas_cod_id = '';
        this.listarUsrNodos();
        this.populateYears();

        const urlParams = new URLSearchParams(window.location.search);
        this.filtro.prc_codigo = urlParams.get('prc_codigo') || '';
        this.filtro.cas_nro_caso = urlParams.get('cas_nro_caso') || '';
        this.filtro.cas_gestion = urlParams.get('cas_gestion') || '';

        ///this.listarRegistros(); // Aseguramos que los registros se carguen antes de verificar el histórico
    },

    computed: {
        ordenarDatos() {
            return this.registros.slice().sort((a, b) => {
                let aValue = a[this.ordenadito.cod];
                let bValue = b[this.ordenadito.cod];
                const modifier = this.ordenadito.ordenar === 'asc' ? 1 : -1;
                if (aValue < bValue) return -1 * modifier;
                if (aValue > bValue) return 1 * modifier;
                return 0;
            });
        }
    },

    created() {
        this.fetchMessages(this.usrUser, this.currentCasCodId);
    },

    methods: {

        formatNombreCaso(texto) {
            return texto.replace(/<br\s*\/?>/g, ' ');
        },
        fetchMessages(usuario, nroTramite, shouldScroll = false) {
            this.showLoader = true;
            const payload = {
                usuario: usuario,
                nroTramite: nroTramite
            };

            axios.get('/api/v1/listarMensajesSip', { params: payload })
                .then(response => {
                    console.log("Respuesta del servidor1: ", response.data);
                    if (response.data.codigoRespuesta === 400) {
                        this.messages = [];
                    } else if (response.data.codigoRespuesta === 500) {
                        this.messages = [];
                    } else if (response.data.codigoRespuesta === 200) {
                        this.messages = response.data.data;                    }
                    this.showLoader = false;
                    if (shouldScroll) {
                        this.scrollToEnd();
                    }
                })
                .catch(error => {
                    console.error('Error al consumir mensajes:', error);
                    this.showLoader = false;
                });
        },

        verificarHistorico(cas_cod_id) {
            return new Promise((resolve, reject) => {
                axios.get('api/v1/verificarHistorico', { params: { nroTramite: cas_cod_id } })
                    .then(response => {
                        if (response.data.codigoRespuesta === 200 && response.data.data === 1) {
                            resolve(1);
                        } else {
                            resolve(0);
                        }
                    })
                    .catch(error => {
                        console.error('Error al verificar el histórico chat:', error);
                        reject(error);
                    });
            });
        },
        sendMessage() {
            if (this.newMessage.length <= 7 || this.newMessage.length >= 250) {
                return;
            }
            const payload_sgg = {
                usuarioTramite: this.usrUser + '@gestora.bo',
                numeroTramite: this.currentCasCodId,
                mensaje: this.newMessage
            };

            axios.post('/api/enviarMensajes', payload_sgg)
                .then(response => {
                    if (response.data.codigoRespuesta === 200) {
                        const payload = {
                            usuario: this.usrUser + '@gestora.bo',
                            nroTramite: this.currentCasCodId,
                            mensaje: this.newMessage
                        };

                        axios.post('/api/v1/mensajeriatramiteSip', payload)
                            .then(response => {
                                if (response.data.codigoRespuesta === 200) {
                                    this.newMessage = '';
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "success",
                                        title: "Mensaje enviado correctamente ...",
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                    this.fetchMessages(this.usrUser, this.currentCasCodId, true);
                                    EventoMensaje.$emit('mensajeEnviado');

                                } else {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "error",
                                        title: "Error al enviar el mensaje",
                                        text: response.data.mensaje || "Error desconocido",
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error al enviar el mensaje:', error);
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: "Error al enviar el mensaje",
                                    text: error.message || "Error desconocido",
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            });
                    } else if (response.data.codigoRespuesta === 201) {
                        Swal.fire({
                            position: "top-end",
                            icon: "warning",
                            title: "No se pudo enviar el mensaje",
                            text: response.data.data,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Error al enviar el mensaje",
                            text: response.data.mensaje || "Error desconocido",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al enviar el mensaje:', error);
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Error al enviar el mensaje",
                        text: error.message || "Error desconocido",
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
        },
        scrollToEnd() {
            this.$nextTick(() => {
                const messagesContainer = this.$refs.messages;
                if (messagesContainer && messagesContainer.length > 0) {
                    messagesContainer[messagesContainer.length - 1].scrollIntoView({ behavior: 'smooth' });
                }
            });
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
        onEnter() {
            this.listarRegistros();
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
            // console.log(JSON.parse(cas_data_valores));
            const parsedData = JSON.parse(cas_data_valores);
            // console.log("parsedData>>>",parsedData);

            const casoHeredaroField = parsedData.find(field => field.frm_campo === 'CASO_HEREDARO');
            // console.log("casoHeredaroField>>>>>>>>>>", casoHeredaroField ? casoHeredaroField : "undefined");
            if (casoHeredaroField) {
                var casoHeredaro = casoHeredaroField.frm_value;
                if (casoHeredaro) {
                    return true;
                }
            }
            // console.log("SIN COINCIDENCIAS");
            return false;
        },

            populateYears() {
                const currentYear = new Date().getFullYear();
                const startYear = 2020;
                this.years = [];
                this.years.push({ year: '', label: '-- Todas --' });
                for (let year = startYear; year <= currentYear; year++) {
                    this.years.push({ year: year, label: year.toString() });
                }
                console.log(this.years);

            },

        descripcionUnico(index) {
            const valorActual = this.items[index].adjuntarDesc;
            const duplicado = this.items.some((item, i) => i !== index && item.adjuntarDesc === valorActual);
            if (duplicado) {
                this.mensajeAdvertencia = 'El valor ya ha sido ingresado en otro campo.';
            } else {
                this.mensajeAdvertencia = ''; // Limpiar el mensaje si no hay duplicado
            }
        },

        cerrarModal() {
            Swal.fire({
                title: '¿Está seguro de que desea cerrar la ventana?',
                text: "Los cambios realizados no se guardaran",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#modalAdjuntar').modal('hide');
                }
            })
        },

        adicionarAdjunto() {
            this.items.push({ adjuntarFile: "", adjuntarDesc: "" });
        },

        removerAdjunto(index) {
            this.items.splice(index, 1);
        },

        validarExtensiones(file) {

        },

        subirArchivos() {
            if (this.mensajeAdvertencia) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Por favor, corrija los campos duplicados antes de subir los archivos.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: true
                });
                return;
            }

            // Función para validar descripciones duplicadas
            const validarDescripcionesDuplicadas = () => {
                const descripciones = [];
                const tabla = document.getElementById("tabla_adjuntar");
                const tr = tabla.querySelectorAll("tr");

                for (let i = 0; i < tr.length - 1; i++) {
                    const descripcion = document.getElementById("adjuntarDesc_" + i).value.trim();
                    if (descripcion) {
                        if (descripciones.includes(descripcion)) {
                            return true; // Encontró una descripción duplicada
                        }
                        descripciones.push(descripcion);
                    }
                }
                return false;
            };

            if (validarDescripcionesDuplicadas()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'No puedes subir documentos con descripciones duplicadas.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: true
                });
                return;
            }

            const tabla = document.getElementById("tabla_adjuntar");
            const tr = tabla.querySelectorAll("tr");
            const tam = tr.length - 1;

            let archivosSubidos = [];
            let allUploadPromises = [];

            for (var i = 0; i < tam; i++) {
                let id_referencia = "adjuntarDesc_" + i;
                const referencia = document.getElementById(id_referencia).value;

                let switch_adj = "switch_adj_" + i;
                const switch_elemento = document.querySelector("#" + switch_adj);
                const valor_switch = switch_elemento.checked;

                const fileInput = document.getElementById("adjuntarFile_" + i);
                const file = fileInput.files[0];

                if (!file) {
                    continue;
                }

                if (archivosSubidos.includes(file.name)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'No puedes subir el mismo archivo más de una vez.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: true
                    });
                    return;
                }

                archivosSubidos.push(file.name);

                let adSe = "adjuntarSelect_" + i;
                const selectad = document.getElementById(adSe).value;

                const formData = new FormData();
                formData.append("tam", i);
                formData.append("valor_id", i);
                formData.append("valor_descripcion", referencia);
                formData.append("file", file);
                formData.append("caso", this.cas_cod_id);
                formData.append("id_caso", this.cas_id);
                formData.append("switch", valor_switch);
                formData.append("SelecRef", selectad);
                formData.append("usrId", this.usrId);

                allUploadPromises.push(
                    new Promise(async (resolve, reject) => {
                        try {
                            await axios.post("/api/guardarDocumentosAdjuntosNfsBC", formData)
                                .then(response => {
                                    resolve(response);
                                });
                        } catch (error) {
                            console.error("Error al subir el archivo:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al subir el adjunto.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                showConfirmButton: true
                            });
                            reject(error);
                        }
                    })
                );
            }

            Promise.all(allUploadPromises)
                .then(() => {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Tus Documentos Fueron Guardados",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    this.bandera = 1;
                })
                .catch(error => {
                    console.error("Error durante la subida de archivos:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al subir los adjuntos.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: true
                    });
                });
        },

        agarrar(cas_id, cas_cod_id) {
            this.resetearValor();

            this.cas_id = cas_id;
            this.cas_cod_id = cas_cod_id;

            this.nro_tramite = cas_cod_id;

            let url2 = "api/casosHistorico/" + cas_id;
            axios.get(url2)
                .then((response) => {
                    console.log(response, "valores recuperadoos")
                })
                .catch(function (error) {
                    console.error('Error al obtener casos históricos', error);
                });
        },

        resetearValor() {
            this.items = [];
            this.documento = [];
            this.documentoPath = '';
            this.documentoDir = '';
        },

        doDocumentoPdfAdjunto(id, id_padre, cas_cod_id) {
            console.log("doDocumentoPdfAdjunto", id);
            console.log("id_padre_pdf_adjunto", id_padre);
            console.log("cas1", cas_cod_id);

            /* this.cas_id = cas_id;
            this.cas_cod_id = cas_cod_id; */

            var id_caso;
            if (id_padre == 0) {
                id_caso = id;
            } else {
                id_caso = id_padre;
            }
            let that = this;

            let url2 = "api/casosHistorico/" + id_caso;
            axios.get(url2)
                .then((response) => {
                    const tamano = response.data.data.length - 1;
                    const datos = { htc_id: response.data.data[1].htc_id };
                    axios.post('api/obtenerDocumentoAdjunto', datos)
                        .then(response => {
                            this.documento = response.data.data;
                        })
                        .catch(error => {
                            console.error('Error al generar al listado', error);
                        });
                })
                .catch(function (error) {
                    that.output = error;
                });
        },


        sort(cod, ordenar) {
            this.ordenadito.cod = cod;
            this.ordenadito.ordenar = ordenar;
        },

        anteriorPagina() {
            if (this.PaginaActual > 1) {
                this.PaginaActual--;
                this.listarRegistros();
            }
        },
        siguientePagina() {
            if (this.PaginaActual < this.paginas.length) {
                this.PaginaActual++;
                this.listarRegistros();
            }
        },

        bRegistros() {
            axios.post(`api/buscarTramiteAsegurado1/?search=${this.buscarRegistro}`)
                .then(response => {
                    this.registros = response.data.data;
                })
                .catch(error => {
                    console.error("Error al Buscar los Datos: ", error)
                })
        },

        mostrarEstadoGeneracionEAP(cas_data_valores) {
            if (typeof cas_data_valores === 'string') {
                try {
                    cas_data_valores = JSON.parse(cas_data_valores);
                } catch (error) {
                    console.error('Error al convertir cas_data_valores a objeto:', error);
                    return null;
                }
            }

            let valorEstadoGeneracionEAP = null;
            if (cas_data_valores.some(item => item.frm_campo === 'ESTADO_GENERACION_EAP')) {
                valorEstadoGeneracionEAP = cas_data_valores.find(item => item.frm_campo === 'ESTADO_GENERACION_EAP').frm_value;
                // console.log('ESTADO_GENERACION_EAP:', valorEstadoGeneracionEAP);
                return valorEstadoGeneracionEAP;
            }
            return null;
        },

        openModal(id_documento, url_documento) {
            console.log('url_documento', url_documento);
            console.log('url_documento', this.tramite);
            var url = "/api/verDocumentoPdfNfs/" + id_documento + '?usuario=' + this.usrUser + '@gestora.bo&pro=buscar_caso_documento&tramite='+ this.tramite;
            const partes = url_documento.split('.');
            const partes2 = url_documento.split('/');
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

        listarRegistros() {
            var overlay = document.getElementById("overlay");
            overlay.style.display = 'flex';

            setTimeout(() => {
                overlay.style.display = 'none';
            }, 320);

            let url = "api/buscarTramiteAsegurado";
            let gRegistro = this.filtro;
            axios.post(url, gRegistro).then(response => {
                this.registros = response.data.data;
                const totalCasos = response.data.totalRegistros[0].total_registros;
                this.resetPagination(totalCasos);

                this.registros.forEach((row, index) => {
                    row.cas_data = JSON.parse(row.cas_data);
                    row.prc_data = JSON.parse(row.prc_data);
                    row.act_data = JSON.parse(row.act_data);

                    this.$set(row, 'mostrarBoton', false);

                    this.verificarHistorico(row.cas_cod_id)
                        .then(resultado => {
                            this.$set(row, 'mostrarBoton', resultado === 1);
                        })
                        .catch(error => {
                            console.error(`Error al verificar el histórico chat para ${row.cas_cod_id}:`, error);
                        });

                    // corregir undefined en cas_nombre_caso
                    row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso ? row.cas_data.cas_nombre_caso : ''
                    row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-');
                    row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('null', "") : '';
                    row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('||', "<br/>") : '';
                    row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('|', "<br/>") : '';
                    row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('<br/><br/>', "<br/>") : '';

                    row.act_data_reglas = JSON.parse(row.act_data_reglas);

                    row.cas_data.cas_motivo_archivo = row.cas_data.cas_motivo_archivo ? row.cas_data.cas_motivo_archivo : ''

                    row.cas_data.cas_motivo_archivo = (row.cas_data.cas_motivo_archivo) ? row.cas_data.cas_motivo_archivo.replaceAll('_', ' ') : '';
                    row.cas_data.cas_motivo_archivo = (row.cas_data.cas_motivo_archivo) ? row.cas_data.cas_motivo_archivo.replaceAll('null', "") : '';
                });
            });
        },

        listarProcesos() {
            var params = { "usr_id": this.usrId };
            let url = "api/procesosTodos";
            axios.get(url).then(response => {
                this.procesos = response.data.data; //twice data
                this.procesos.forEach(function (row) {
                    row.prc_data = JSON.parse(row.prc_data);
                });
            });
        },

        doVer(index) { // Para tomarCaso() y liberarCaso()
            this.registro = this.registros[index];
        },

        doLimpiar(index) { // Para derivarCaso()
            this.registro = [];
        },

        //historico
        doHistorico(id, id_padre, tramite) {
            console.log("doHistorico", id);
            console.log("id_padre", id_padre);
            console.log("tramite", tramite);
            this.tramite  = tramite;
            var id_caso;
            if (id_padre == 0) {
                id_caso = id;
            } else {
                id_caso = id_padre;
            }
            let that = this;
            let url = "api/casosHistorico/" + id_caso + '?usuario=' + this.usrUser + '@gestora.bo&pro=buscar_caso_historial&tramite=' + this.tramite;
            axios.get(url)
                .then((response) => {
                    this.historico = response.data.data;
                })
                .catch(function (error) {
                    that.output = error;
                });
        },

        doLegal(id, id_padre) {
            console.log("doHistorico", id);
            console.log("id_padre", id_padre);
            var id_caso;
            // if (id_padre == 0) {
            // 	id_caso = id;
            // } else {
            // 	id_caso = id_padre;
            // }
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

        cadadito(id, id_padre) {
            console.log("candadito", id);
            console.log("id_padre", id_padre);

            const id_caso = id_padre === 0 ? id : id_padre;
            const datos = { id_caso: id_caso, cas_usr_id: window.Laravel.usr_user };

            axios.post('api/v2/casosHistoricoCandidato', datos)
                .then(response => {
                    this.casos = response.data.data;
                    this.historicos = response.data.data_paralelo;
                })
                .catch(error => {
                    this.output = error;
                    console.error('Error al generar al listado', error);
                });
        },

        //anular desistir caso
        doAnularDesistir() {
            Swal.fire({
                title: '¿Está seguro de anular o desistir el caso?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                willOpen: () => {
                    Swal.showLoading();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (this.recuperarDatosAnular.length > 0) {
                        if (
                            this.registrosAnularDesistir.estado_derivacion &&
                            this.registrosAnularDesistir.descripcion_derivacion &&
                            this.$refs.ArchivoAD.files.length > 0) {
                            let var_uccp = false;
                            const caso = this.recuperarDatosAnular[0];
                            this.registrosAnularDesistir.cas_id = caso.cas_id;
                            this.registrosAnularDesistir.act_prc_id = caso.act_prc_id;
                            this.registrosAnularDesistir.cas_nodo_id = caso.cas_nodo_id;
                            this.registrosAnularDesistir.cas_usr_id = caso.cas_usr_id;
                            this.registrosAnularDesistir.numero_caso = this.numero_caso;
                            this.registrosAnularDesistir.usr_name = this.usr_name;
                            this.registrosAnularDesistir.estado_caso = this.estado_caso;
                            let gRegistros = this.registrosAnularDesistir;
                            let url = "api/anularDesistirCaso";
                            axios.put(url, gRegistros)
                                .then((response) => {
                                    if (response.data.codigo == '200') {
                                        Swal.fire('El caso fue Anulado/Desistido Exitosamente desde UCPP!', '', 'success');
                                        //subir documento pdf de anular/desistir
                                        let allUploadPromises = [];
                                        const fileInput = document.getElementById("archivoAnularDesistir");
                                        const file = fileInput.files[0];
                                        const formData = new FormData();
                                        formData.append("tam", 1);
                                        formData.append("valor_id", 1);
                                        formData.append("valor_descripcion", this.registrosAnularDesistir.descripcion_derivacion);
                                        formData.append("file", file);
                                        formData.append("caso", caso.cas_cod_id);
                                        formData.append("id_caso", caso.cas_id);
                                        formData.append("ci", caso.cas_data.AS_CI);
                                        formData.append("id_persona_sip", '');
                                        formData.append("parentesco", 'ADJUNTOS');
                                        formData.append("switch", 'false');
                                        allUploadPromises.push(
                                            new Promise(async (resolve, reject) => {
                                                try {
                                                    await axios.post("/api/guardarDocumentosAdjuntosNfs", formData)
                                                        .then(response => {
                                                            resolve(response);
                                                            //Swal.fire('Archivo Cargado Exitosamente', '', 'success');
                                                            this.limpiarCamposModalAnularDesistir();
                                                            this.listarRegistros();
                                                        });
                                                } catch (error) {
                                                    Swal.fire('Error al Cargar Archivo', 'Anular o Desistir', 'error');
                                                }
                                            })
                                        );
                                    } else if (response.data.codigo == '400') {
                                        Swal.fire(response.data.mensaje, 'UCPP', 'error');
                                    } else {
                                        var_uccp = true;
                                    }
                                    if (var_uccp) {
                                        //subir documento pdf de anular/desistir
                                        let allUploadPromises = [];
                                        const fileInput = document.getElementById("archivoAnularDesistir");
                                        const file = fileInput.files[0];
                                        const formData = new FormData();
                                        formData.append("tam", 1);
                                        formData.append("valor_id", 1);
                                        formData.append("valor_descripcion", this.registrosAnularDesistir.descripcion_derivacion);
                                        formData.append("file", file);
                                        formData.append("caso", caso.cas_cod_id);
                                        formData.append("id_caso", caso.cas_id);
                                        formData.append("ci", caso.cas_data.AS_CI);
                                        formData.append("id_persona_sip", '');
                                        formData.append("parentesco", 'ADJUNTOS');
                                        formData.append("switch", 'false');
                                        allUploadPromises.push(
                                            new Promise(async (resolve, reject) => {
                                                try {
                                                    await axios.post("/api/guardarDocumentosAdjuntosNfs", formData)
                                                        .then(response => {
                                                            resolve(response);
                                                            Swal.fire('Archivo Cargado Exitosamente', '', 'success');
                                                            this.limpiarCamposModalAnularDesistir();
                                                            this.listarRegistros();
                                                        });
                                                } catch (error) {
                                                    console.error("Error al Subir el Archivo:", error);
                                                    Swal.fire('Error al Cargar Archivo', 'Anular o Desistir', 'error');
                                                }
                                            })
                                        );
                                    }
                                })
                                .catch((error) => {
                                    console.error(error);
                                    Swal.fire('Error al Anular o Desistir el Caso', '', 'error');
                                });

                        } else {
                            Swal.fire('Datos Incompletos', 'Por favor, complete todos los campos antes de Anular o Desistir el Trámite', 'warning');
                        }
                    } else {
                        Swal.fire("No Hay Datos Para Anular o Desistir", '', 'warning');
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                }
            });
        },

        recuperarDatosParaAnular(id, numero_caso, estado_caso) {
            this.numero_caso = numero_caso;
            this.estado_caso = estado_caso;
            let url = "api/recuperarDatosParaAnular/" + id;
            axios.get(url)
                .then((response) => {
                    this.recuperarDatosAnular = response.data.data;
                    this.recuperarDatosAnular.forEach(function (row) {
                        row.cas_data = JSON.parse(row.cas_data);
                        row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso ? row.cas_data.cas_nombre_caso : ''
                        row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-');
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('null', "") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('||', "<br/>") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('|', "<br/>") : '';
                        row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('<br/><br/>', "<br/>") : '';
                    });
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        limpiarCamposModalAnularDesistir() {
            this.registrosAnularDesistir.estado_derivacion = '';
            this.registrosAnularDesistir.descripcion_derivacion = '';
            this.$refs.ArchivoAD.value = '';
            this.numero_caso = '';
            this.estado_caso = '';
        },
        doTomarCaso(id, id_padre, r) {
            Swal.fire({
                title: '¿Esta seguro de realizar la obtención de tramite?',
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
                    const datos = { cas_id: id, cas_usr_id: this.usrId };
                    axios.post('api/tomarcaso', datos)
                        .then(response => {
                            Swal.fire('El caso fue obtenido', '', 'success');
                            console.log(response);
                            this.listarRegistros();
                        })
                        .catch(error => {
                            console.error('Error al generar al listado', error);
                        });

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Lógica a ejecutar si el usuario selecciona 'No' o cancela
                    Swal.close();  // Cerrar la alerta de carga
                    //Swal.fire('Acción cancelada', '', 'info');
                }
            });
        },

        doTomarCasoOV(id, as_cua) {

            console.log("AS_CUA", as_cua);

            const apiUrl = "/api/actualizarDatosContratoJUB";
            const payload = {
                cas_id: id,
                cua:as_cua
            };

            try {
                this.mostrarOverlay();

                axios.post(apiUrl, payload, {
                    headers: {
                        "Content-Type": "application/json",
                    },
                }).then(response => {
                    console.log("Respuesta del servidor:", response.data);
                    console.log("Respuesta del servidor:", response.data.codigo_prestaciones);

                    if (response.data.codigoRespuesta == '200') {
                        if (response.data.codigo_prestaciones == '200'){
                            this.ocultarOverlay();

                            Swal.fire({
                                title: response.data.mensaje_prestaciones,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                Swal.fire({
                                    title: '¿Esta seguro de realizar la obtención de tramite?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí',
                                    cancelButtonText: 'No',
                                    backdrop: `
                                        rgba(0, 115, 179, 0.4)
                                        left top
                                        no-repeat
                                    `,
                                    willOpen: () => {
                                        Swal.showLoading();
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        this.loading = true;
                                        const datos = { cas_id: id, cas_usr_id: this.usrId };
                                        axios.post('api/tomarcasoOv', datos)
                                            .then(response => {
                                                Swal.fire('El caso fue obtenido', '', 'success');
                                                console.log(response);
                                                this.listarRegistros();
                                            })
                                            .catch(error => {
                                                console.error('Error al generar al listado', error);
                                            });

                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        // Lógica a ejecutar si el usuario selecciona 'No' o cancela
                                        Swal.close();  // Cerrar la alerta de carga
                                        console.log("cancelado ... ");
                                        //Swal.fire('Acción cancelada', '', 'info');
                                    }
                                });
                            });
                        } else if(response.data.codigo_prestaciones == '201'){
                            Swal.fire({
                                title: "Comunicarse con el Área de Calculo",
                                text: response.data.mensaje_prestaciones,
                                icon: 'warning',
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                this.ocultarOverlay();
                            });
                        }
                    }
                }).catch(error => {
                    this.ocultarOverlay();
                    console.error("Error al enviar los datos:", error.response?.data || error.message);
                });

            } catch (error) {
                this.ocultarOverlay();
                console.error("Error al enviar los datos:", error.response?.data || error.message);
            }
        },

        async jub1185ActulizarContrato() {
            const apiUrl = "/api/actualizarDatosContratoJUB";
            const payload = {
                cas_id: this.cas_id,
                cua:this.registro.cas_data.AS_CUA//"32345513"//"32358453" //"31747139"
            };

            try {
                this.mostrarOverlay();

                const response = await axios.post(apiUrl, payload, {
                    headers: {
                        "Content-Type": "application/json",
                    },
                });

                console.log("Respuesta del servidor:", response.data);
                if (response.data.codigoRespuesta == '200') {
                    this.onEstadoChange(response.data.estado);
                    alert("Datos enviados correctamente.");
                    this.ocultarOverlay();
                }

            } catch (error) {
                this.ocultarOverlay();
                console.error("Error al enviar los datos:", error.response?.data || error.message);
            }
        },


        mostrarOverlay() {
            // Mostrar el overlay cambiando el estilo de display
            this.$refs.overlay.style.display = 'flex';
        },
        ocultarOverlay() {
            // Ocultar el overlay cambiando el estilo de display
            this.$refs.overlay.style.display = 'none';
        },

        doDocumentoPdf(htc_id) {
            console.log("htc_id", htc_id);
            const datos = { htc_id: htc_id };
            axios.post('api/obtenerDocumento' + '?usuario=' + this.usrUser + '@gestora.bo&pro=buscar_caso_obtener_documentos&tramite=' + this.tramite, datos)
                .then(response => {
                    console.log("doDocumentoPdf", response.data.data);
                    this.documento = response.data.data;
                })
                .catch(error => {
                    console.error('Error al generar al listado documento pdf', error);
                });
        },

        // observacionesUCPP(aaa) {
        // 	console.log("unidad", aaa);

        // 	this.observaciones= aaa;


        // },

        observacionesUCPP(observaciones) {
            console.log("Observaciones", observaciones);
            this.observaciones = observaciones;
        },

        doDocumentoPdfEdit(cas_id) {
            console.log("cas_id", cas_id);
            this.cas_cod_id = cas_id;
            const datos = { cas_id: cas_id };
            axios.post('api/obtenerDocumentoEdit', datos)
                .then(response => {
                    console.log("doDocumentoPdfEdit", response.data.data);
                    this.documento = response.data.data;
                    axios.post('api/obtenerObservacion')
                        .then(response => {
                            this.observacion = response.data.data;
                        })
                })
                .catch(error => {
                    console.error('Error al generar al listado documento pdf edit', error);
                });
        },

        getDocument(event, frm_campo, index) {
            this.documento = event.target.files[0];
            this.documentoPath = "store/vys2022/" + this.cas_id + "/" + frm_campo + "/" + this.documento.name;
            this.documentoDir = "store/vys2022/" + this.cas_id + "/" + frm_campo;
            let ext = this.documento.name.split('.')[1];
            if (ext == 'pdf' || ext == 'xlsx' || ext == 'xls' || ext == 'docx' || ext == 'doc' || ext == 'pptx' || ext == 'ppt' || ext == 'txt'
                || ext == 'PDF' || ext == 'XLSX' || ext == 'XLS' || ext == 'DOCX' || ext == 'DOC' || ext == 'PPTX' || ext == 'PPT' || ext == 'TXT') {
                this.createB64Document(this.documento, frm_campo, index);
                this.campos[index].frm_value = this.documentoPath;
            }
            else {
                this.$swal({
                    title: 'Advertencia!',
                    text: 'Debe seleccionar un documento con formato .pdf, .xlsx, .docx , .pptx, .txt o .PNG',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                this.campos[index].frm_value = '';
                this.documento = null;
            }
        },

        verImagen: function (ruta) {
            window.open(ruta, '_blank');
        },

        actualizarDocumentoPdfEdit() {
            const tabla = document.getElementById("tabla_titular");
            const filas = tabla.querySelectorAll("tr");
            const tam = filas.length - 1;
            for (var i = 0; i < tam; i++) {
                const id_doc = document.getElementById('doc_id_' + i).textContent;
                const id_switch = "switch_tit_" + i;
                const switchElement = document.querySelector(`#${id_switch}`);
                const valorSwitch = switchElement.checked;
                const fileInput = document.getElementById('pdf_tit_' + i);
                const file = fileInput.files[0];
                const observacionTit = "id_observacion_tit_" + i;
                const idObservacionTit = document.getElementById(observacionTit).value;
                if (!file) {
                    console.log('No se ha seleccionado ningún archivo');
                    const datos = {
                        valor_id: i,
                        id_doc: id_doc,
                        pdf: '',
                        id_caso: this.cas_cod_id,
                        switch: valorSwitch,
                        id_observacion: idObservacionTit
                    };
                    console.log('documentos datoss =====>', datos);
                    axios.post('api/guardarDocumentosCompletarPdf', datos)
                        .then(response => {
                            console.log('respuesta', response.data);
                        })
                        .catch(error => {
                            console.error('Error al generar o abrir el PDF', error);
                        });
                } else {
                    const reader = new FileReader();
                    reader.onload = () => {
                        const base64data = reader.result.split(',')[1];
                        const datos = {
                            valor_id: i,
                            id_doc: id_doc,
                            pdf: base64data,
                            id_caso: this.cas_cod_id,
                            switch: valorSwitch,
                            id_observacion: idObservacionTit
                        };
                        console.log('documentos datoss =====>', datos);
                        axios.post('api/guardarDocumentosCompletarPdf', datos)
                            .then(response => {
                                console.log('respuesta', response.data);
                            })
                            .catch(error => {
                                console.error('Error al generar o abrir el PDF', error);
                            });
                    };
                    reader.readAsDataURL(file);
                }
            }
            $('#modalDocumentoPdfEdit').modal('hide');
        },

        listarUsrNodos() {
            var url = "api/usrnodosXId/" + this.usrId;
            axios.get(url).then(response => {
                this.usrNodos = response.data.data;
            });
        },
        abrirModalMensaje(mensaje) {
            try {
                console.log(mensaje);

                this.mensajeRechazo = mensaje;

                // Reemplazar saltos de línea por <br> para HTML
                this.mensajeRechazoFormateado = this.mensajeRechazo.replace(/\n/g, '<br>');
            } catch (error) {
                console.error('Error al obtener el mensaje:', error);
            }
        },
        openChatModal(casCodId, act_orden) {
            this.currentCasCodId = casCodId;
            this.currentActOrden = act_orden;
            this.showModal = true;
            this.showLoader = true;
            this.fetchMessages(this.usrUser, casCodId, true);
        },
        closeChatModal() {
            this.showModal = false;
            this.showLoader = false;
        },
        limpiarFiltrosUrl() {
            //console.log("encryptId: ", this.encryptId(147599));
            this.filtro.prc_codigo = '';
            this.filtro.cas_nro_caso = '';
            this.filtro.cas_correlativo = '';
            this.filtro.cas_gestion = '';
            this.filtro.cas_tipo = '';
            this.filtro.num_identificacion = '';

            const url = new URL(window.location);
            url.searchParams.delete('prc_codigo');
            url.searchParams.delete('cas_nro_caso');
            url.searchParams.delete('cas_gestion');
            window.history.pushState({}, '', url);
        },
            encryptId(id) {
                let idString = String(id);
                let encryptedId = encodeURIComponent( btoa(idString));
                return encryptedId;
            }
    },

    beforeUpdate: function () {
        if (this.dataTable) {
            this.dataTable.destroy()
        }
    },

    updated: function () {
        $(document).on('hidden.bs.modal', function (event) {
            if ($('.modal:visible').length) {
                $('body').addClass('modal-open');
            }
        });
    }

}
</script>

<style scoped>
    .loader-container {
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
        z-index: 10;
    }

    .loader-wrapper {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .loader {
        border: 12px solid #f3f3f3;
        border-top: 12px solid #3498db;
        border-radius: 50%;
        width: 100px;
        height: 100px;
        animation: spin 2s linear infinite;
    }

    .loader-text {
        font-size: 12px;
        color: #3498db;
        position: absolute;
        font-weight: bold;
    }

    .loading-text {
        font-size: 10px;
        color: #3498db;
        z-index: 1;
        position: absolute;
        top: 50%;
        margin-top: 5px;
        animation: blink 1.5s step-start infinite;
    }

    .chat-info {
        font-size: 0.9em;
        display: block;
        margin-top: 3px;
    }

    .mensaje {
        color: #ff0000;
        font-size: x-small;
        font-weight: bold;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .loader {
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #3498db;
        /* Azul */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    .dropdown-menu {
        min-width: 14rem;
    }

    .custom-color {
        background-color: #B06218 !important;
        border-color: #B06218 !important;
        color: white !important;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 50px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .custom-color:hover {
        background-color: #944b12;
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
    }

    .custom-color:active {
        background-color: #7a3e0e;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .hammer {
        display: inline-block;
        animation: hammer 2s infinite;
    }

    @keyframes hammer {
        0% {
            transform: rotate(0deg);
        }

        20% {
            transform: rotate(-20deg);
        }

        /* 40% {
            transform 0deg;
        } */

        60% {
            transform: rotate(-20deg);
        }

        /* 80% {
            transform 0deg;
        } */

        /* 100% {
            transform 0deg;
        } */
    }

    .pulseBtn {
        background: #0b63bb;
        color: #fff;
        border: 1px solid #0b63bb;
        font-size: 1rem;
        box-shadow: 0 0 0 0 rgba(88, 120, 243, 0.4);
        -moz-animation: pulse 2s infinite;
        -webkit-animation: pulse 2s infinite;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(88, 120, 243, 1);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(88, 120, 243, 0);
        }
        100% {
            box-shadow: 0 0 0 50px rgba(88, 120, 243, 0);

        }
    }

    .chat-container {
        display: flex;
        flex-direction: column;
        padding: 10px;
        max-width: 600px;
        margin: 0 auto;
    }

    .chat-bubble {
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 10px;
        max-width: 75%;
        word-wrap: break-word;
        position: relative;
    }

    .mine {
        background-color: #dcf8c6;
        align-self: flex-end;
    }

    .theirs {
        background-color: #f1f0f0;
        align-self: flex-start;
    }

    .chat-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
        position: absolute;
        top: 10px;
        left: -40px;
    }

    .chat-timestamp {
        font-weight: bold;
        font-size: 0.8em;
        display: block;
        margin-top: 5px;
    }

    .chat-input {
        padding: 10px;
        border-radius: 20px;
        border: 1px solid #ccc;
        margin-top: 10px;
    }

    .chat-bubble.even {
        background-color: #d3d3d3;
    }
    .chat-bubble.odd {
        background-color: #c2eafc;
    }
</style>

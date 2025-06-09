<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>{{ plural }}</h5>

                <div class="row col-md-12">
                    <div class="col-6">
                        Catálogo:
                        <select v-model="seleccionado" class="form-control mt3"
                            @change="listarProcesos(); seleccionado2 = ''; seleccionado3 = ''; procesos = []; actividades = []; registros = []; "
                            size="10">
                            <option value="-1" disabled>-- Seleccione Catálogo --</option>
                            <option v-for="item in catalogos" :value="item.cat_id">
                                <span v-for="index in item.cat_codigo.length" :key="index">&nbsp;&nbsp;</span>
                                [ {{ item.cat_codigo}} ] {{ item.cat_descripcion}}
                            </option>
                        </select>
                    </div>
                    <div class="col-6">
                        Proceso:
                        <select v-model="seleccionado2" class="form-control mt3"
                            @change="listarActividades(); seleccionado3 = ''; actividades = []; registros = []; "
                            size="10">
                            <option value="-1" disabled>-- Seleccione Proceso --</option>
                            <option v-for="item in procesos" :value="item.prc_id">
                                {{ item.prc_data.prc_descripcion}} ( {{ item.prc_data.prc_codigo}} )</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row col-md-12">
                    <!-- col izquierda -->
                    <div class="col-md-6">
                        Actividades:
                        <select v-model="seleccionado3" class="form-control mt3" @change="listarRegistros()" size="20">
                            <option value="-1" disabled>-- Seleccione Actividad --</option>
                            <option v-for="item in actividades" :value="item.act_id" :disabled="item.act_tact_id==1 || item.act_tact_id==4">
                                [ {{ item.act_data.act_orden}} ] {{ item.act_data.act_descripcion}} - ({{
                                item.tact_descripcion }})
                            </option>
                        </select>
                    </div>
                    <!-- col derecha -->
                    <div class="col-md-6">
                        Formularios:
                        <button v-if="seleccionado3 && validarTipoActividad()" class="btn btn-success btn-xs" size="sm"
                            @click="doLimpiar()" data-toggle="modal" data-target="#modalNuevo" title="Nuevo">
                            <i class="fa fa-plus white" aria-hidden="true"></i>
                        </button>
                        <div class="card-group">
                            <div v-for="(r, index) in registros" class="card">
                                <div class="card-header">
                                    {{ r.frm_data.frm_codigo }} &nbsp;
                                    <span v-if="r.frm_estado === 'A'" class="badge badge-success">A</span>
                                    <span v-else class="badge badge-warning">{{r.frm_estado}}</span>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Orden: {{ r.frm_data.frm_orden }}</h6>
                                    <p class="card-text">{{ r.frm_data.frm_descripcion }}</p>
                                    <button class="btn btn-primary btn-xs" v-on:click="doVer( index )"
                                        data-toggle="modal" data-target="#modalVer" title="Ver">
                                        <i class="fa fa-eye white" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-warning btn-xs" v-on:click="doVer( index )"
                                        data-toggle="modal" data-target="#modalEditar" title="Editar">
                                        <i class="fa fa-pen white" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-danger btn-xs" v-on:click="doVer( index )"
                                        data-toggle="modal" data-target="#modalEliminar" title="Eliminar">
                                        <i class="fa fa-trash white" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalVer -->
        <div class="modal fade" id="modalVer" tabindex="-1" role="dialog" aria-labelledby="modalVer" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Ver {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Orden Formulario (10, 20, etc.)</label>
                                <input v-model="registro.frm_data.frm_orden" class="form-control" placeholder="Orden"
                                    disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Código del Formulario</label>
                                <input v-model="registro.frm_data.frm_codigo" class="form-control" placeholder="Código"
                                    disabled>
                            </div>
                            <div class="col-md-12">
                                <label>Descripción Formulario</label>
                                <input v-model="registro.frm_data.frm_descripcion" class="form-control"
                                    placeholder="Descripción" disabled>
                            </div>
                            <div class="col-md-12">
                                <label>Campos del Formulario (en formato JSON)</label>
                                <textarea v-model="registro.frm_data_campos" class="form-control" rows="10"
                                    placeholder="Duracion en Horas" disabled></textarea>
                            </div>
                        </div>
                        <div style="margin:10px 20px 10px 20px;">
                            <label>Detalle de Campos</label>
                            <div class="row justify-content-left bg-primary">
                                <div class="col-md-1"><small>#</small></div>
                                <div class="col-md-2"><small>TIPO</small></div>
                                <div class="col-md-3"><small>CAMPO</small></div>
                                <div class="col-md-4"><small>ETIQUETA</small></div>
                                <div class="col-md-2"><small>DESHABILITADO</small></div>
                                <div class="col-md-2"><small>FUNCIÓN</small></div>
                                <div class="col-md-2"><small>CLASS</small></div>
                            </div>
                            <div v-for="(c, index) in campos" class="row justify-content-left"
                                style="margin-left:200 !important">
                                <div class="col-md-1"><small>{{ index + 1 }}</small></div>
                                <div class="col-md-2"><small>{{ c.frm_tipo }}</small></div>
                                <div class="col-md-3"><small>{{ c.frm_campo }}</small></div>
                                <div class="col-md-4"><small>{{ c.frm_etiqueta}}</small></div>
                                <div class="col-md-2">
                                    <span v-if="c.frm_deshabilitado == 'true'" class="badge badge-danger">
                                        <small>{{ c.frm_deshabilitado }}</small></span>
                                    <span v-else-if="c.frm_deshabilitado == 'false'" class="badge badge-success">
                                        <small>{{ c.frm_deshabilitado }}</small></span>
                                    <span v-else class="badge badge-dark">
                                        <small>n/a{{ c.frm_deshabilitado }}</small></span>
                                </div>
                                <div class="col-md-4"><small>{{ c.frm_funcion}}</small></div>
                                <div class="col-md-4"><small>{{ c.frm_class}}</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

        <!-- modalNuevo -->
        <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevo"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div v-for="error in errores" class="col-md-3">
                                <span class="badge badge-danger">{{ error }}</span>
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Orden Formulario (10, 20, etc.)</label>
                                <input type="number" v-model="registro.frm_data.frm_orden" class="form-control" placeholder="Orden">
                                <p v-if="!registro.frm_data.frm_orden" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Código Formulario</label>
                                <input v-model="registro.frm_data.frm_codigo" class="form-control" 
                                    placeholder="Código">
                                <p v-if="!registro.frm_data.frm_codigo" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-12">
                                <label>Descripción Formulario</label>
                                <input v-model="registro.frm_data.frm_descripcion" class="form-control"
                                    placeholder="Descripción del Formulario">
                                <p v-if="!registro.frm_data.frm_descripcion" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-12">
                                <label>Campos del Formulario (escriba en formato JSON)</label>
                                <button class="btn btn-primary btn-xl" v-on:click="doEjemplo()" title="Eliminar">
                                    <i class="far fa-lightbulb white" aria-hidden="true"></i> Ejemplo
                                </button>
                                <textarea v-model="registro.frm_data_campos" class="form-control" rows="10"
                                    placeholder="Campos en formato JSON"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label>Funciones Javascript</label>
                                <textarea v-model="registro.frm_funciones" class="form-control" rows="10"
                                    placeholder="script"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="insertarRegistro($event)"
                            data-dismiss="modal">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalEditar -->
        <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditar"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Editar {{ singular}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="height: 80vh;">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Orden Formulario</label>
                                <input v-model="registro.frm_data.frm_orden" class="form-control" placeholder="Orden">
                                <p v-if="!registro.frm_data.frm_orden" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Código Formulario</label>
                                <input v-model="registro.frm_data.frm_codigo" class="form-control" placeholder="Código">
                            </div>
                            <div class="col-md-9">
                                <label>Descripción Formulario</label>
                                <input v-model="registro.frm_data.frm_descripcion" class="form-control"
                                    placeholder="Descripción">
                                <p v-if="!registro.frm_data.frm_descripcion" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <button id="btnSwVerCodigo" type="button" class="btn btn-primary form-control" @click="doSwVerCodigo()">
                                    <i class="fa fa-eye white" aria-hidden="true"></i> &nbsp; 
                                    {{ textSwVerCodigo }}
                                </button>
                            </div>
                            <div v-if="swVerCodigo" class="col-md-12">
                                <label>Campos del Formulario (escriba en formato JSON)</label>
                                <textarea v-model="registro.frm_data_campos" class="form-control" rows="10"
                                    placeholder="Campos en formato JSON"></textarea>
                            </div>
                            <div v-if="swVerCodigo" class="col-md-12">
                                <label>Funciones Javascript</label> &nbsp; 
                                <button class="btn btn-primary btn-xs btn-circle" size="sm"
                                    data-toggle="modal" data-target="#modalAyuda" title="Ayuda">
                                    <i class="fa fa-question white" aria-hidden="true"></i>
                                </button>
                                <span class="badge rounded-pill bg-danger">NO usar caracter (') comilla simple</span>
                                <span class="badge rounded-pill bg-success">En su lugar usar caracter (") comillas dobles</span>
                                <textarea v-model="registro.frm_funciones" class="form-control" rows="10"
                                    placeholder="javascript" style="font-family: monospace;"></textarea>
                            </div>
                        </div>

                        <div v-if="!swVerCodigo" class="scroll-me" style="margin:10px 15px 10px 15px; height: 60vh;">
                            <!--label>Detalle Campos</label-->
                            <table width="100%" class="table table-hover table-striped table-responsive" id="divTable">
                                <thead class="thead-dark">
                                    <tr align="center" style="font-size:x-small !important;">
                                        <th width="200px" scope="col">#</th>
                                        <th width="300px" scope="col">TIPO</th>
                                        <th width="30px" scope="col"></th>
                                        <th width="300px" scope="col">CAMPO</th>
                                        <th width="300px" scope="col">ETIQUETA</th>
                                        <th width="150px" scope="col">DESHAB.?</th>
                                        <th width="200px" scope="col">FUNCIÓN</th>
                                        <th width="200px" scope="col">CLASS</th>
                                        <th width="200px" scope="col">OBLIG.?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="200">
                                            <i class="fa fa-save white" aria-hidden="true" v-on:click="guardarCampo()" >
                                                &nbsp;<small>Nuevo</small>
                                            </i>
                                        </td>
                                        <td width="300">
                                            <select v-model="nuevo.frm_tipo" class="form-control combo-me small-me" > 
                                                <option value='TITLE'>TITLE </option>
                                                <option value='SUBTITLE'>SUBTITLE </option>
                                                <option value='HIDDEN'>HIDDEN </option>
                                                <option value='TEXT'>TEXT </option>
                                                <option value='NUMBER'>NUMBER </option>
                                                <option value='TEXTAREA'>TEXTAREA </option>
                                                <option value='DATE'>DATE </option>
                                                <option value='TIME'>TIME </option>
                                                <option value='CHECKBOX'>CHECKBOX </option>
                                                <option value='DROPDOWNLIST'>DROPDOWNLIST </option>
                                                <option value='SELECT'>SELECT </option>
                                                <option value='MASK'>MASK </option>
                                                <option value='BUTTON'>BUTTON </option>
                                                <option value='IMAGE'>IMAGEN </option>
                                                <option value='DOCUMENT'>DOCUMENTO </option>
                                                <option value='GRID'>GRILLA </option>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td width="300">
                                            <input v-model="nuevo.frm_campo" class="form-control small-me" placeholder="Campo" >
                                        </td>
                                        <td width="300">
                                            <input v-model="nuevo.frm_etiqueta" class="form-control small-me" placeholder="Etiqueta" >
                                        </td>
                                        <td width="200" >
                                            <toggle-button v-if="nuevo.frm_tipo == 'TEXT' || nuevo.frm_tipo == 'NUMBER' || nuevo.frm_tipo == 'TEXTAREA' || nuevo.frm_tipo == 'DATE' || nuevo.frm_tipo == 'TIME' || nuevo.frm_tipo == 'CHECKBOX' || nuevo.frm_tipo == 'DROPDOWNLIST' || nuevo.frm_tipo == 'SELECT'" v-model="nuevo.frm_deshabilitadoo" />
                                        </td>
                                        <td width="200" >
                                            <input v-if="nuevo.frm_tipo == 'BUTTON' || nuevo.frm_tipo == 'DROPDOWNLIST' || nuevo.frm_tipo == 'SELECT'" v-model="nuevo.frm_funcion" class="form-control small-me" placeholder="Función" >
                                        </td>
                                        <td width="200" >
                                            <input v-model="nuevo.frm_class" class="form-control small-me" placeholder="Class" >
                                        </td>
                                        <td></td>
                                    </tr>

						            <tr v-for="(c, index) in campos">
                                        <td width="200" style="margin:2px; padding:2px;">
                                            <i class="fa fa-trash white" aria-hidden="true" v-on:click="eliminarCampo(index)" ></i>
                                            &nbsp;
                                            <i v-if="index > 0" class="fa fa-arrow-up white" aria-hidden="true" v-on:click="subir(index)" ></i>
                                            &nbsp;<small>{{ index + 1 }}</small>&nbsp;
                                            <i v-if="index != campos.length - 1" class="fa fa-arrow-down white" aria-hidden="true" v-on:click="bajar(index)" ></i>
                                        </td>
                                        <td width="300" style="margin:2px; padding:2px;">
                                            <select v-model="c.frm_tipo" class="form-control combo-me small-me" @change="actualizarCampos();"> 
                                                <option value='TITLE'>TITLE </option>
                                                <option value='SUBTITLE'>SUBTITLE </option>
                                                <option value='HIDDEN'>HIDDEN </option>
                                                <option value='TEXT'>TEXT </option>
                                                <option value='NUMBER'>NUMBER </option>
                                                <option value='TEXTAREA'>TEXTAREA </option>
                                                <option value='DATE'>DATE </option>
                                                <option value='TIME'>TIME </option>
                                                <option value='CHECKBOX'>CHECKBOX </option>
                                                <option value='DROPDOWNLIST'>DROPDOWNLIST </option>
                                                <option value='SELECT'>SELECT </option>
                                                <option value='MASK'>MASK </option>
                                                <option value='BUTTON'>BUTTON </option>
                                                <option value='IMAGE'>IMAGEN </option>
                                                <option value='DOCUMENT'>DOCUMENTO </option>
                                                <option value='GRID'>GRILLA </option>
                                            </select>
                                        </td>
                                        <td style="margin:2px; padding:2px;">
                                            <i v-if="['DROPDOWNLIST', 'SELECT'].includes(c.frm_tipo)" 
                                                data-toggle="modal" data-target="#modalAvanzado" title="Avanzado"
                                                class="fa fa-list white" v-on:click="listarItems(index)" ></i>
                                            <i v-if="['GRID'].includes(c.frm_tipo)" 
                                                data-toggle="modal" data-target="#modalAvanzado" title="Avanzado"
                                                class="fa fa-table white" v-on:click="listarCols(index)" ></i>
                                        </td>
                                        <td width="300" style="margin:2px; padding:2px;">
                                            <input v-model="c.frm_campo" class="form-control small-me" placeholder="Campo" @change="actualizarCampos();">
                                        </td>
                                        <td width="300" style="margin:2px; padding:2px;">
                                            <input v-model="c.frm_etiqueta" class="form-control small-me" placeholder="Etiqueta" @change="actualizarCampos();" >
                                        </td>
                                        <td align="center" width="150" style="margin:2px; padding:2px;">
                                            <div v-if="c.frm_deshabilitado">
                                                <toggle-button v-model="c.frm_deshabilitadoo"  @change="actualizarCamposDesabilitado(index);" />
                                            </div>
                                            <div v-if="!c.frm_deshabilitado">
                                                n/a
                                            </div>
                                        </td>
                                        <td width="200" style="margin:2px; padding:2px;">
                                            <input v-if="c.frm_tipo == 'BUTTON' || c.frm_tipo == 'DROPDOWNLIST' || c.frm_tipo == 'SELECT'"  v-model="c.frm_funcion" class="form-control small-me" placeholder="Función" @change="actualizarCampos();" >
                                        </td>
                                        <td width="200" style="margin:2px; padding:2px;">
                                            <input v-model="c.frm_class" class="form-control small-me" placeholder="Class" @change="actualizarCampos();" >
                                        </td>
                                        <td>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer" v-if="swVerCodigo">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarRegistro($event)"
                            data-dismiss="modal">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalEliminar -->
        <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar {{ singular}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>¿ Confirma eliminar el {{ singular }} ?</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger" @click="eliminarRegistro($event)"
                            data-dismiss="modal">Si, eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalAvanzado -->
        <div class="modal fade" id="modalAvanzado" tabindex="-1" role="dialog" aria-labelledby="modalAvanzado"
            aria-hidden="true">
            <div class="modal-dialog modal-top-right" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="modal-title" id="exampleModalLabel">Edición Avanzada</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Solo para componentes DROPDOWNLIST, SELECT y GRID: </label>
                                <div class="groups">
                                    <div class="group">
                                        <Container
                                            group-name="1"
                                            :get-child-payload="getChildPayload1"
                                            @drop="onDrop('cols', $event)"
                                            :should-accept-drop="(src, payload) =>
                                                getShouldAcceptDrop(1, this.items1.length, src, payload)
                                        ">
                                            <Draggable v-for="item in items" :key="item.frm_campo">
                                                <div class="draggable-item">
                                                    <table width="100%" style="background: beige;"><tr>
                                                        <td class="small-me" width="20%">
                                                            <i class="fa fa-trash white" title="Eliminar" v-on:click="eliminarItem(index2)"></i>
                                                        </td>
                                                        <td class="small-me" width="40%"><strong>{{ item.frm_value }}</strong></td>
                                                        <td class="small-me" width="40%">{{ item.frm_etiqueta }}</td>
                                                    </tr></table>
                                                </div>
                                            </Draggable>
                                            <Draggable v-for="item in cols" :key="item.col_campo">
                                                <div class="draggable-item">
                                                    <table width="100%" style="background: lightgreen;"><tr>
                                                        <td class="small-me" width="10%">
                                                            <i class="fa fa-trash white" title="Eliminar" v-on:click="eliminarItem(index2)"></i>
                                                        </td>
                                                        <td class="small-me" width="20%"><strong>{{ item.col_campo }}</strong></td>
                                                        <td class="small-me" width="20%">{{ item.col_etiqueta }}</td>
                                                        <td class="small-me" width="20%">{{ item.col_tipo }}</td>
                                                        <td class="small-me" width="15%">{{ item.col_deshabilitado }}</td>
                                                        <td class="small-me" width="15%">{{ item.col_obligatorio }}</td>
                                                    </tr></table>
                                                </div>
                                            </Draggable>
                                        </Container>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarItem($event)"
                            data-dismiss="modal">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalAyuda -->
        <div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="modalAyuda"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-top-right" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="modal-title" id="exampleModalLabel">Ayuda</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <table class="table table-hover table-striped table-responsive">
                                <tr><td width="60%">var puntero = _get("CAMPO");<br>puntero.style.background = "beige";</td>
                                <td>Devuelve un puntero directo al campo.</td></tr>
                                <tr><td>_setValue("CAMPO", valor);</td>
                                <td>Asigna el valor a un campo.</td></tr>
                                <tr><td>var variable = _getValue("CAMPO");</td>
                                <td>Devuelve el valor de un campo a una variable.</td></tr>
                                <tr><td>_enable("CAMPO");</td>
                                <td>Habilita escritura de un campo.</td></tr>
                                <tr><td>_disable("CAMPO");</td>
                                <td>Desabilita un campo.</td></tr>
                                <tr><td>_show("CAMPO");</td>
                                <td>Muestra un campo.</td></tr>
                                <tr><td>_hide("CAMPO");</td>
                                <td>Esconde un campo.</td></tr>
                                <tr><td>_setStyle("clase", "estilo");</td>
                                <td>Aplica a los componentes de la <strong>clase</strong> el <strong>estilo</strong> indicado.</td></tr>
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
import { Container, Draggable } from "vue-smooth-dnd";
import { applyDrag, generateItems } from "./helpers";

    export default {
        name: 'formularios',
        components: { Container, Draggable },
        data() {
            return {
                currentState: false,
                filtro: {},
                plural: 'Formularios',
                singular: 'Formulario',
                seleccionado: '',
                seleccionado2: '',
                seleccionado3: '',
                errores: [],
                registro: { frm_data: {}, frm_data_campos: {} },
                nuevo: { frm_tipo: '', frm_campo: '', frm_etiqueta: '', frm_deshabilitado: '' , frm_deshabilitadoo: false },
                registros: [],
                catalogos: [],
                nodos: [],
                procesos: [],
                actividades: [],
                tactividades: [],
                campos: [],
                data_campos: {},
                idEditar: -1,
                // DROPDOWNLIST, SLECT
                items: [],
                // GRID
                cols: {}, values: [],

                swVerCodigo: false,
                textSwVerCodigo: 'Ver código',

                items1: generateItems(3, (i) => ({
                    id: "1" + i,
                    data: `Draggable 1 - ${i}`,
                })),
            }
        },

        mounted() {
            this.listarCatalogos();
        },

        methods: {
            // --- inicio Draggable
            onDrop(collection, dropResult) {
                this[collection] = applyDrag(this[collection], dropResult);
            },

            getChildPayload1(index) {
                return this.items1[index];
            },

            getShouldAcceptDrop(index, listSize, sourceContainerOptions, payload) {
                if (listSize === 1) {
                    return false;
                } else {
                    return true;
                }
            },
            // --- fin draggable

            listarItems(index) {
                this.items = [];
                this.cols = [];
                this.items = this.campos[index].frm_items;
            }, 

            listarCols(index) {
                this.items = [];
                this.cols = [];
                this.cols = this.campos[index].frm_cols;
            }, 

            listarRegistros() {
                let url = "api/formularios/" + this.seleccionado3;
                axios.get(url).then(response => {
                    let that = this;
                    this.registros = response.data.data; //twice data
                    this.registros.forEach(function (row) {
                        row.frm_data = JSON.parse(row.frm_data);
                        that.campos = JSON.parse(row.frm_data_campos);
                        for ( var i = 0; i < that.campos.length; i++){
                            if (['DROPDOWNLIST', 'SELECT'].includes(that.campos[i].frm_tipo)) {
                                console.log("DDL, SELECT >>>", that.campos[i].frm_items);
                                //that.items = that.campos[i].frm_items; // = JSON.parse(that.campos[i].frm_items); 
                            } else if (['GRID'].includes(that.campos[i].frm_tipo)) {
                                console.log("GRID >>>", that.campos[i].frm_cols);
                                //that.campos[i].frm_items = JSON.parse(that.campos[i].frm_items); 
                            }
                            if(that.campos[i].frm_deshabilitado)
                                that.campos[i].frm_deshabilitadoo = JSON.parse(that.campos[i].frm_deshabilitado);
                            console.log(that.campos[i].frm_class);
                        }
                        row.frm_data_campos = JSON.stringify(that.campos);
                    });
                    this.listarNodos();
                    this.listarTActividades();
                });
            },
            
            guardarCampo() {
                this.errores = [];
                if (this.nuevo.frm_tipo == '') {
                    this.errores.push('Falta Tipo');
                }
                if (this.nuevo.frm_campo == '') {
                    this.errores.push('Falta Campo');
                }
                if (this.nuevo.frm_etiqueta == '') {
                    this.errores.push('Falta Etiqueta');
                }

                if (this.errores.length === 0) {
                    if (this.nuevo.frm_tipo == 'TEXT' || this.nuevo.frm_tipo == 'NUMBER' || this.nuevo.frm_tipo == 'TEXTAREA' || 
                        this.nuevo.frm_tipo == 'DATE' || this.nuevo.frm_tipo == 'CHECKBOX' || this.nuevo.frm_tipo == 'DROPDOWNLIST' || 
                        this.nuevo.frm_tipo == 'TIME' || this.nuevo.frm_tipo == 'SELECT' ) {
                        // copia campo de deshabilitadoo a deshabilitado
                        this.nuevo.frm_deshabilitado = this.nuevo.frm_deshabilitadoo.toString();
                    }
                    this.campos.push({
                        frm_tipo: this.nuevo.frm_tipo,
                        frm_campo: this.nuevo.frm_campo,
                        frm_etiqueta: this.nuevo.frm_etiqueta,
                        frm_deshabilitado: this.nuevo.frm_deshabilitado,
                        frm_deshabilitadoo: this.nuevo.frm_deshabilitadoo
                    })
                    let gRegistro = {};
                    //gRegistro.frm_data = JSON.stringify(gRegistro.frm_data);
                    gRegistro.frm_data_campos = JSON.stringify(this.campos);
                    gRegistro.frm_id = this.registro.frm_id;

                    let that = this;
                    let url = "api/formularios_campos/" + this.registro.frm_id;
                    axios.put(url, gRegistro)
                    .then(function (response) {
                        that.nuevo = { frm_tipo: '', frm_campo: '', frm_etiqueta: '', frm_deshabilitado: '' , frm_deshabilitadoo: false };
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
                } else {
                    //e.preventDefault();
                    this.$swal({
                        title: 'Error!',
                        text: 'Faltan datos del campo',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            },
            
            actualizarCampos() {
                let gRegistro = {};
                //gRegistro.frm_data = JSON.stringify(gRegistro.frm_data);
                gRegistro.frm_data_campos = JSON.stringify(this.campos);
                gRegistro.frm_id = this.registro.frm_id;

                let that = this;
                let url = "api/formularios_campos/" + this.registro.frm_id;
                axios.put(url, gRegistro)
                .then(function (response) {
                })
                .catch(function (error) {
                    that.output = error;
                });
            },

            actualizarCamposDesabilitado(index) {
                if(this.campos[index].frm_deshabilitado)
                    this.campos[index].frm_deshabilitado = this.campos[index].frm_deshabilitadoo.toString();
                
                let gRegistro = {};
                //gRegistro.frm_data = JSON.stringify(gRegistro.frm_data);
                gRegistro.frm_data_campos = JSON.stringify(this.campos);
                gRegistro.frm_id = this.registro.frm_id;

                let that = this;
                let url = "api/formularios_campos/" + this.registro.frm_id;
                axios.put(url, gRegistro)
                .then(function (response) {
                })
                .catch(function (error) {
                    that.output = error;
                });
            },

            eliminarCampo(index) {
                this.$swal({
                    title: "Eliminar",
                    text: "Está seguro de eliminar el campo " + this.campos[index].frm_etiqueta + "?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Eliminar"
                }).then((result) => { // <--
                    if (result.value) { // <-- if confirmed
                        this.campos.splice(index,1);
                        this.actualizarCampos();
                    }
                });
            },

            subir(index) {
                let a = this.campos[index];
                this.campos.splice(index,1);
                this.campos.splice(index - 1, 0 , a);
                this.actualizarCampos();
            },

            bajar(index) {
                let a = this.campos[index];
                this.campos.splice(index,1);
                this.campos.splice(index + 1, 0 , a);
                this.actualizarCampos();
            },

            listarCatalogos() {
                let url = "api/catalogos/";
                axios.get(url).then(response => {
                    this.catalogos = response.data.data; //twice data
                });
            },

            listarProcesos() {
                let url = "api/procesos/" + this.seleccionado;
                axios.get(url).then(response => {
                    this.procesos = response.data.data; //twice data
                    this.procesos.forEach(function (row) {
                        row.prc_data = JSON.parse(row.prc_data);
                    });
                });
            },

            listarActividades() {
                let url = "api/actividades/" + this.seleccionado2;
                axios.get(url).then(response => {
                    this.actividades = response.data.data; //twice data
                    this.actividades.forEach(function (row) {
                        row.act_data = JSON.parse(row.act_data);
                    });
                });
            },

            listarTActividades() {
                let url = "api/tactividades/";
                axios.get(url).then(response => {
                    this.tactividades = response.data.data; //twice data
                });
            },

            listarNodos() {
                let url = "api/nodos/";
                axios.get(url).then(response => {
                    this.nodos = response.data.data; //twice data
                });
            },

            doVer(index) {
                this.registro = this.registros[index];
            },


            doLimpiar() {
                this.registro = {
                    frm_data: {},
                    frm_data_campos: [],
                };
            },

            insertarRegistro(e) {
                this.errores = [];
                
				if (this.registro.frm_data && !this.registro.frm_data.frm_orden) {
                    this.errores.push('Falta Orden');
                }
                if (this.registro.frm_data && !this.registro.frm_data.frm_codigo) {
                    this.errores.push('Falta Código');
                }
				if (this.registro.frm_data && !this.registro.frm_data.frm_descripcion) {
                    this.errores.push('Falta Descripción');
                }
                if (this.registro && !this.registro.frm_data_campos) {
                    this.errores.push('Falta Campos Clave');
                }
                if (this.seleccionado == '-1') {
                    this.errores.push('Falta Catalogo');
                }
                if (this.seleccionado2 == '-1') {
                    this.errores.push('Falta Proceso');
                }
				
                if (this.errores.length === 0) {
                    let gRegistro = this.registro;
                    gRegistro.frm_act_id = this.seleccionado3;
                    gRegistro.frm_data = JSON.stringify(gRegistro.frm_data);
                    gRegistro.frm_data_campos = JSON.stringify(JSON.parse(gRegistro.frm_data_campos));
                    gRegistro.frm_usr_id = 1;
                    let that = this;
                    let url = "api/formularios";
                    axios.post(url, gRegistro)
                        .then(function (response) {
                            that.output = response.data;
                            that.listarRegistros();
                        })
                        .catch(function (error) {
                            that.output = error;
                        });
                } else {
                    e.preventDefault();
                    this.$swal({
                        title: 'Error!',
                        text: 'Corrija errores',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            },

            actualizarRegistro(e) {
                this.errores = [];
                
				if (this.registro.frm_data && !this.registro.frm_data.frm_orden) {
                    this.errores.push('Falta Orden');
                }
                if (this.registro.frm_data && !this.registro.frm_data.frm_codigo) {
                    this.errores.push('Falta Código');
                }
				if (this.registro.frm_data && !this.registro.frm_data.frm_descripcion) {
                    this.errores.push('Falta Descripción');
                }
                if (this.registro && !this.registro.frm_data_campos) {
                    this.errores.push('Falta Campos Clave');
                }
                if (this.seleccionado == '-1') {
                    this.errores.push('Falta Catalogo');
                }
                if (this.seleccionado2 == '-1') {
                    this.errores.push('Falta Proceso');
                }
				
                if (this.errores.length === 0) {
                    let gRegistro = this.registro;
                    //gRegistro.frm_data = JSON.stringify(gRegistro.frm_data);
                    gRegistro.frm_data_campos = this.registro.frm_data_campos;
                    gRegistro.frm_usr_id = 1;

                    let that = this;
                    let url = "api/formularios/" + gRegistro.frm_id;
                    axios.put(url, gRegistro)
                        .then(function (response) {
                            that.output = response.data;
                            that.listarRegistros();
                        })
                        .catch(function (error) {
                            that.output = error;
                        });
                } else {
                    e.preventDefault();
                    this.$swal({
                        title: 'Error!',
                        text: 'Corrija errores',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            },

            eliminarRegistro() {
                let gRegistro = this.registro;
                gRegistro.frm_usr_id = 1;

                let that = this;
                let url = "api/formularios/" + gRegistro.frm_id;
                axios.post(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        that.listarRegistros();
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            },

            doEjemplo() {
                this.registro.frm_data_campos = '['
                    + '{"frm_tipo":"TITLE","frm_campo":"TITULO_1","frm_etiqueta":"DATOS PERSONALES","frm_deshabilitado":""},'
                    + '{"frm_tipo":"TEXT","frm_campo":"CI_1","frm_etiqueta":"CI","frm_deshabilitado":"true","frm_obligatorio":"true"},'
                    + '{"frm_tipo":"TEXT","frm_campo":"NOMBRES_1","frm_etiqueta":"Nombres","frm_deshabilitado":"true","frm_obligatorio":"true"},'
                    + '{"frm_tipo":"TEXT","frm_campo":"PATERNO_1","frm_etiqueta":"Paterno","frm_deshabilitado":"true"},'
                    + '{"frm_tipo":"TEXT","frm_campo":"MATERNO_1","frm_etiqueta":"Materno","frm_deshabilitado":"true","frm_obligatorio":"false"},'
                    + '{"frm_tipo":"DATE","frm_campo":"NACIMIENTO_1","frm_etiqueta":"Fecha Nacimiento","frm_deshabilitado":"true"},'
                    + '{"frm_tipo":"MASK","frm_campo":"TELEFONO_1","frm_etiqueta":"TELÉFONO","frm_mask":"########","frm_deshabilitado":"true","frm_obligatorio":"false"},'
                    + '{"frm_tipo":"TEXTAREA","frm_campo":"DIRECCION_1","frm_etiqueta":"Direccion","frm_deshabilitado":"true"},'
                    + '{"frm_tipo":"TITLE","frm_campo":"TITULO_2","frm_etiqueta":"REVISIÓN","frm_deshabilitado":""},'
                    + '{"frm_tipo":"TEXTAREA","frm_campo":"INFORME_REVISION_1","frm_etiqueta":"Informe Revisión","frm_deshabilitado":"false"},'
                    + '{"frm_tipo":"DROPDOWNLIST","frm_campo":"RESULTADO_REVISION_1","frm_etiqueta":"Resultado Revisión","frm_deshabilitado":"false", '
                    + '"frm_items":['
                    + '{"frm_value":"1","frm_etiqueta":"REPROBADO"},'
                    + '{"frm_value":"2","frm_etiqueta":"APROBADO"}'
                    + ']'
                    + '}'
                    + ']';
            },

            validarTipoActividad() {
                let that = this;
                let res = true;
                this.actividades.forEach(function (row) {
                    if (that.seleccionado3 == row.act_id) {
                        if ((row.act_tact_id == 1) || (row.act_tact_id == 4)) {
                            res = false;
                        }
                    }
                });
                return res;
            },

            doSwVerCodigo() {
				this.swVerCodigo = !this.swVerCodigo;
                this.textSwVerCodigo = this.swVerCodigo ? 'Ver INTERFAZ' : 'Ver código'
			},

        },
        computed: {
            isActive() {
                return this.currentState;
            },

            checkedValue: {
                get() {
                    return this.currentState
                },
                set(newValue) {
                    this.currentState = newValue;
                }
            }
        }
    }
</script>

<style>
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
    }

    .combo-me {
        font-size: 10px;
        height: 30px !important;
    }

    .small-me {
        font-size: 10px !important;
        height: 30px !important;
    }

    .scroll-me {
        height: 400px;
        overflow-x: scroll;
        background-color: #feedc5;
    }
</style>

<style scoped>
.toggle__button {
    vertical-align: middle;
    user-select: none;
    cursor: pointer;
}
.toggle__button input[type="checkbox"] {
    opacity: 0;
    position: absolute;
    width: 1px;
    height: 1px;
}
.toggle__button .toggle__switch {
    display:inline-block;
    height:12px;
    border-radius:6px;
    width:40px;
    background: #BFCBD9;
    box-shadow: inset 0 0 1px #BFCBD9;
    position:relative;
    margin-left: 10px;
    transition: all .25s;
}

.toggle__button .toggle__switch::after, 
.toggle__button .toggle__switch::before {
    content: "";
    position: absolute;
    display: block;
    height: 18px;
    width: 18px;
    border-radius: 50%;
    left: 0;
    top: -3px;
    transform: translateX(0);
    transition: all .25s cubic-bezier(.5, -.6, .5, 1.6);
}

.toggle__button .toggle__switch::after {
    background: #4D4D4D;
    box-shadow: 0 0 1px #666;
}
.toggle__button .toggle__switch::before {
    background: #4D4D4D;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
    opacity:0;
}




.groups {
  display: flex;
  justify-content: stretch;
  margin-top: 10px;
  margin-right: 10px;
}
.group {
  margin-left: 10px;
  flex: 1;
}

.draggable-item {
  height: 50px;
  line-height: 50px;
  text-align: center;
  width: 100%;
  display: block;
  background-color: #fff;
  outline: 0;
  border: 1px solid rgba(0, 0, 0, 0.125);
  margin-bottom: 2px;
  margin-top: 2px;
  cursor: move;
  user-select: none;
}
</style>
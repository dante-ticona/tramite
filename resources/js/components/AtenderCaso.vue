<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="form-control btn btn-success" title="Descartar y Volver"
                            @click="$router.push('/misCasos')">
                            <i class="fa fa-backward white" aria-hidden="true"></i> Descartar y Volver
                        </button>
                    </div>
                    <div class="col-md-6">
                        <h5>{{ plural }}</h5>
                    </div>
                    <div class="col-md-3 float-end pull-right ">
                        <button class="btn btn-primary btn-circle" title="Histórico" data-target="#modalHistorico"
                            v-on:click="doHistorico(cas_id)">
                            <i class="fa fa-list-ol" aria-hidden="true"></i>
                        </button>
                        <!--
						<button class="btn btn-primary btn-circle" data-target="#modalMensajeria"
							title="Ver Mensajeria" v-on:click="doGetMensajes()" data-toggle="modal">
							<i class="fa fa-envelope white" aria-hidden="true"></i>
						</button>
						-->
                        <button class="btn btn-warning btn-circle" data-target="#modalArchivar"
                            v-if="actividad.act_orden == 30 || actividad.act_orden == 20 || actividad.act_orden == 32 || (actividad.act_orden == 100 && (codProceso == 'INV' || codProceso == 'PM'))"
                            title="Archivar" data-toggle="modal">
                            <i class="fa fa-archive white" aria-hidden="true"></i>
                        </button>

                        <button class="btn btn-secondary btn-circle" data-target="#modalCampos" title="Ver Campos"
                            v-on:click="verCampos()" data-toggle="modal">
                            <i class="fa fa-eye white" aria-hidden="true"></i>
                        </button>

                        <!-- <button type="button" class="btn btn-danger btn-circle " title="Enmienda"
							v-on:click="enmienda()"
							v-if="actividad.act_orden != 30 && actividad.act_orden != 20 && actividad.act_orden != 100">
							<i class="fa fa-paper-plane white" aria-hidden="true"></i>
						</button> -->

                        <button class="btn btn-danger btn-circle " data-target="#modalEmnienda" title="Enmienda"
                            data-toggle="modal"
                            v-if="actividad.act_orden != 30 && actividad.act_orden != 20 && actividad.act_orden != 100 && actividad.act_orden != 32">
                            <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                        </button>

                        <button class="btn btn-success btn-circle" title="Asignación de Casos"
                            v-on:click="listarUsuariosNodo()">
                            <i class="fa fas fa-people-arrows white" aria-hidden="true"></i>
                        </button>

                        <button  type="button"
                            class="btn btn-circle" title="Tramites Legal"
                            v-on:click="getCasosLegal()" style="background-color: #B06218;">
                            <i class="fa fa-balance-scale hammer" style="color: white;"
                                aria-hidden="true"></i>
                        </button>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="col-12">
                            Proceso:
                            <strong>{{ proceso.prc_descripcion }}</strong>
                        </div>
                        <div class="col-12">
                            Actividad:
                            <span class="badge badge-dark">{{ actividad.act_orden }}</span>
                            <strong>{{ actividad.act_descripcion }}</strong>
                        </div>
                        <div class="col-12">
                            ESTADO:
                            <span class="badge badge-warning"><strong>{{ registro.est_codigo }}</strong></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-12">
                            No. Caso:
                            <h1>
                                {{ cas_cod_id }}
                            </h1>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="col-12">
                            No. Correlativo:
                            <h1>
                                {{ correlativo_aps }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body" id="editor">
                <form id="form1" @submit.prevent="actualizarCaso()">
                    <div class="row col-md-12">
                        <div v-for="(c, index) in campos" :class="doDefinirClass(c)">
                            <div v-if="c.frm_tipo == 'TITLE'" :id="c.frm_campo + '_idd'"
                                style="background-color:grey; color:white; margin-top:10px;">
                                <h3>{{ c.frm_etiqueta }}</h3>
                            </div>
                            <div v-else-if="c.frm_tipo == 'SUBTITLE'" :id="c.frm_campo + '_idd'"
                                style="background-color:lightgrey; margin-top:10px;">
                                <h5>{{ c.frm_etiqueta }}</h5>
                            </div>
                            <div v-else-if="c.frm_tipo == 'SPAM'" :id="c.frm_campo + '_idd'"
                                style="background-color:red; margin-top:10px;">
                                <h5>{{ c.frm_etiqueta }}</h5>
                            </div>
                            <div v-else-if="c.frm_tipo == 'HIDDEN'">
                                {{ c.frm_etiqueta }}
                                <input :id="c.frm_campo" type="hidden" v-model="c.frm_value">
                            </div>
                            <div v-else-if="c.frm_tipo == 'TEXT'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" v-model="c.frm_value" class="form-control" type="text"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'"
                                    @input="c.frm_value = c.frm_value.toUpperCase()"
                                    @keypress="validarEntradaText($event)">
                            </div>
                            <div v-else-if="c.frm_tipo == 'TEXT_LABEL'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" v-model="c.frm_value_label" class="form-control" type="text"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'">
                            </div>
                            <div v-else-if="c.frm_tipo == 'TEXT_MIN'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" v-model="c.frm_value" class="form-control" type="text"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'"
                                    @keypress="validarEntradaText($event)">
                            </div>
                            <div v-else-if="c.frm_tipo == 'MAIL'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" v-model="c.frm_value" class="form-control" type="email"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'">
                            </div>
                            <div v-else-if="c.frm_tipo == 'TEXTSELECT'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" v-model="c.frm_value_label" class="form-control" type="text"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'">
                            </div>
                            <div v-else-if="c.frm_tipo == 'NUMBER'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" v-model="c.frm_value" class="form-control" type="text"
                                    v-on:keypress="isNumber($event)" :disabled="c.frm_deshabilitado == 'true'"
                                    :required="c.frm_obligatorio == 'true'">
                            </div>
                            <div v-else-if="c.frm_tipo == 'TEXTAREA'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <textarea :id="c.frm_campo" v-model="c.frm_value" class="form-control" rows="3"
                                    :disabled="c.frm_deshabilitado == 'true'"
                                    @input="c.frm_value = c.frm_value.toUpperCase()"
                                    :required="c.frm_obligatorio == 'true'"></textarea>
                            </div>
                            <div v-else-if="c.frm_tipo == 'TEXTAREA_MIN'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <textarea :id="c.frm_campo" v-model="c.frm_value" class="form-control" rows="3"
                                    :disabled="c.frm_deshabilitado == 'true'"
                                    :required="c.frm_obligatorio == 'true'"></textarea>
                            </div>
                            <div v-else-if="c.frm_tipo == 'DATE'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" type="date" v-model="c.frm_value" class="form-control"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'"
                                    @change="ejecutarFuncionesLocales(c); ejecutar(c.frm_funcion)" :max="fechaMaxima()">
                            </div>
                            <div v-else-if="c.frm_tipo == 'MONTH'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" type="month" v-model="c.frm_value" class="form-control"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'"
                                    @change="ejecutarFuncionesLocales(c); ejecutar(c.frm_funcion)" >
                            </div>
                            <div v-else-if="c.frm_tipo == 'DATE_MAX'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" type="date" v-model="c.frm_value" class="form-control"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'"
                                    @change="ejecutarFuncionesLocales(c); ejecutar(c.frm_funcion)" :min="fechaMinima()">
                            </div>
                            <div v-else-if="c.frm_tipo == 'DATE_NOR'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" type="date" v-model="c.frm_value" class="form-control"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'"
                                    @change="ejecutarFuncionesLocales(c); ejecutar(c.frm_funcion)">
                            </div>
                            <div v-else-if="c.frm_tipo == 'TIME'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" type="time" v-model="c.frm_value" class="form-control"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'">
                            </div>
                            <div v-else-if="c.frm_tipo == 'CHECKBOX'" :id="c.frm_campo + '_idd'">
                                <label>
                                    <br /> <label v-show="c.frm_obligatorio == 'true'" style="color:rgb(228, 31, 31);">
                                        (*)</label>
                                    <input :id="c.frm_campo" type="checkbox" v-model="c.frm_value"
                                        :disabled="c.frm_deshabilitado == 'true'"
                                        :required="c.frm_obligatorio == 'true'"
                                        @change="ejecutarLocal(c); ejecutar(c.frm_funcion);"> {{ c.frm_etiqueta }}
                                </label>
                            </div>
                            <div v-else-if="c.frm_tipo == 'DROPDOWNLIST'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }} <label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <select :id="c.frm_campo" v-model="c.frm_value" class="form-control"
                                    @change="ejecutar(c.frm_funcion); ejecutarFuncionesLocales(c);"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'"
                                    data-live-search="true">
                                    <option v-bind:value="i.frm_value" v-for="(i, index2) in c.frm_items">
                                        {{ i.frm_value }} - {{ i.frm_etiqueta }}
                                    </option>
                                </select>
                            </div>
                            <!-- 2025-02-11 new custom comonent of render begin -->
                            <div v-else-if="c.frm_tipo == 'MULTISELECT'" :id="c.frm_campo + '_idd'">
                                    <MultiselectComponent
                                        v-model="c.frm_value"
                                        :options="c.frm_items??[]"
                                        :domId="c.frm_campo"
                                        :disabled="c.frm_deshabilitado ==='true'"
                                        :required="c.frm_obligatorio === 'true'"
                                        :componentLabel="c.frm_etiqueta"></MultiselectComponent>
                            </div>
                            <!-- 2025-02-11 new custom comonent of render end -->
                            <div v-else-if="c.frm_tipo == 'SELECT'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }} <label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <select :id="c.frm_campo" v-model="c.frm_value" class="form-control"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'">
                                    <option v-bind:value="i.frm_value" v-for="(i, index2) in c.frm_items">
                                        {{ i.frm_value }} - {{ i.frm_etiqueta }}
                                    </option>
                                </select>
                            </div>
                            <div v-else-if="c.frm_tipo == 'MASK'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }} <label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" v-model="c.frm_value" class="form-control" type="tel"
                                    v-mask="c.frm_mask" :disabled="c.frm_deshabilitado == 'true'"
                                    :placeholder="c.frm_mask" :required="c.frm_obligatorio == 'true'">
                            </div>
                            <!-- div v-else-if="c.frm_tipo=='SCRIPT'">
								<p v-show="ejecutar_BAK(c.frm_value)"/>
							</div -->
                            <div v-else-if="c.frm_tipo == 'BUTTON'" :id="c.frm_campo + '_idd'">
                                <br />
                                <button :id="c.frm_campo" type="button" class="btn btn-success"
                                    @click="ejecutarFuncionesLocales(c); ejecutar(c.frm_funcion, cas_registrado);">{{
                                        c.frm_etiqueta
                                    }}</button>
                            </div>
                            <div v-else-if="c.frm_tipo == 'IMAGE'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }} <label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <div class="input-group mb-3">
                                    <input :id="c.frm_campo" v-model="c.frm_value" class="form-control"
                                        placeholder="Ingrese Imagen en formato JPG, JPEG o PNG" disabled>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-xs"
                                            v-on:click.stop.prevent="verImagen(c.frm_value)">
                                            <i class="fa fa-eye white" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="file" name="capturaFoto" @change="getImage($event, c.frm_campo, index)"
                                    accept="image/png, image/jpg, image/jpeg">
                                <img :src="c.frm_value" alt="Foto" class="img-responsive">
                            </div>
                            <div v-else-if="c.frm_tipo == 'DOCUMENT'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }} <label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <div class="input-group mb-3">
                                    <input :id="c.frm_campo" v-model="c.frm_value" class="form-control"
                                        placeholder="Ingrese Documento" disabled>
                                </div>
                                <input :id="c.frm_id_campo" type="file" name="file"
                                    @change=" tamanoDocumento($event); ejecutar(c.frm_funcion, c.frm_id_campo);"
                                    accept=".pdf" :required="c.frm_obligatorio == 'true'">
                            </div>
                            <div v-else-if="c.frm_tipo == 'DOCUMENT_VER'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }} <label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <div class="input-group mb-3">
                                    <input :id="c.frm_campo" v-model="c.frm_value" class="form-control"
                                        placeholder="Ingrese Documento" disabled>
                                    <div class="input-group-append">
                                        <button :id="c.frm_id_campo" class="btn btn-primary btn-xs"
                                            v-on:click.stop.prevent="verImagen(c.frm_value)">
                                            <i class="fa fa-eye white" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div v-else-if="c.frm_tipo == 'GRID'" :id="c.frm_campo + '_idd'">
                                <table border="0" width="100%" class="table table-hover table-striped table-responsive"
                                    :id="'divTable' + c.frm_campo">
                                    <tr class="thead-light">
                                        <th>
                                            <i class="fa fa-plus white" title="Nuevo"
                                                v-if="c.frm_deshabilitado == 'true' ? false : true"
                                                v-on:click="gridAddRow(c.frm_cols, c.frm_value)"></i>
                                        </th>
                                        <th v-for="col in c.frm_cols" :key="col.campo">
                                            <div v-if="col.col_tipo != 'HIDDEN'">
                                                {{ col.col_etiqueta }}
                                            </div>
                                        </th>
                                    </tr>
                                    <tr v-for="(row, rowIndex) in c.frm_value" :key="rowIndex">
                                        <td>
                                            {{ rowIndex + 1 }}&nbsp;
                                            <i class="fa fa-trash white" title="Eliminar"
                                                v-if="c.frm_deshabilitado == 'true' ? false : true"
                                                v-on:click="gridDeleteRow(c.frm_value, rowIndex, row);"></i>
                                        </td>

                                        <td v-for="(col, colIndex) in row" :key="colIndex">
                                            <div v-if="c.frm_cols[colIndex].col_tipo == 'LABEL'">
                                                <label>{{ c.frm_cols[colIndex].col_etiqueta }}</label>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'TEXT'">
                                                <input :id="col.col_campo + rowIndex" :placeholder="col.col_etiqueta"
                                                    v-model="col.col_value"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'"
                                                    @input="col.col_value = col.col_value.toUpperCase()"
                                                    @keypress="validarEntradaText($event)">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'MAIL'">
                                                <input :id="col.col_campo + rowIndex" :placeholder="col.col_etiqueta"
                                                    v-model="col.col_value"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'TEXTAREA'">
                                                <textarea :id="col.col_campo + rowIndex" :placeholder="col.col_etiqueta"
                                                    v-model="col.col_value" cols="30" rows="3"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'"></textarea>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'NUMBER'">
                                                <input :id="col.col_campo + rowIndex" type="text"
                                                    v-on:keypress="isNumber($event)" v-model="col.col_value"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'DATE'">
                                                <input :id="col.col_campo + rowIndex" type="date"
                                                    :placeholder="col.col_etiqueta" v-model="col.col_value"
                                                    @change="ejecutar(c.frm_cols[colIndex].col_funcion, rowIndex)"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'" :max="fechaMaxima()">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'CHECKBOX'">
                                                <input :id="col.col_campo + rowIndex" type="checkbox"
                                                    :placeholder="col.col_etiqueta" v-model="col.col_value"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'LINK'">
                                                <a target="_blank" :href="col.col_value">Ver</a>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'BUTTON'">
                                                <button :id="c.frm_cols[colIndex].col_campo"
                                                    v-if="c.frm_deshabilitado == 'true' ? false : true" type="button"
                                                    class="btn btn-success"
                                                    @click="ejecutar(c.frm_cols[colIndex].col_funcion, rowIndex)"
                                                    style="margin:0!important;"> {{ c.frm_cols[colIndex].col_etiqueta
                                                    }}</button>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'HIDDEN'">
                                                <input :id="col.col_campo + rowIndex" type="hidden"
                                                    v-model="col.col_value">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'DROPDOWNLIST'">
                                                <select :id="col.col_campo + rowIndex" v-model="col.col_value"
                                                    @change="ejecutar(c.frm_cols[colIndex].col_funcion, rowIndex);"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'">
                                                    <option v-bind:value="itm.itm_value"
                                                        v-for="(itm, itmIndex) in c.frm_cols[colIndex].col_items"
                                                        :key="itmIndex">
                                                        {{ itm.itm_etiqueta }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'DOCUMENT'"
                                                :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                :required="col.col_obligatorio == 'true'">
                                                {{ c.frm_etiqueta }} <label v-show="c.col_obligatorio == 'true'"
                                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                                <div class="input-group mb-3">
                                                    <input :id="c.frm_cols[colIndex].col_campo"
                                                        v-model="c.frm_cols[colIndex].col_value" class="form-control"
                                                        placeholder="Ingrese Documento" disabled>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary btn-xs"
                                                            v-on:click.stop.prevent="verImagen(c.frm_cols[colIndex].col_value)">
                                                            <i class="fa fa-eye white" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="file" name="capturaFoto" @change="tamanoDocumento($event);"
                                                    accept=".pdf">
                                                <!--img :src="foto" alt="Foto" class="img-responsive" -->
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'MODAL'">
                                                <button type="button" class="btn btn-danger btn-circle"
                                                    title="Subir documentos"
                                                    v-on:click="listarRequisitos(c.frm_value[rowIndex], rowIndex)"
                                                    data-toggle="modal" data-target="#modalDocumentos">
                                                    <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'MODAL2'">
                                                <button type="button" class="btn btn-success btn-circle pulseBtn"
                                                    title="" v-on:click="" data-toggle="modal" data-target="#modalTutor"
                                                    @click="geTutor(c.frm_value[rowIndex], rowIndex)">
                                                    <i class="nav-icon fas fa-user blue" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <div v-else>
                                                NO IDENTIFICADO
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div v-else-if="c.frm_tipo == 'GRID_1582'" :id="c.frm_campo + '_idd'">
                                <table border="0" width="100%" class="table table-hover table-striped table-responsive" :id="'divTable' + c.frm_campo">
                                    <tr class="thead-light">
                                        <th v-for="col in c.frm_cols" :key="col.campo">
                                            <div v-if="col.col_tipo != 'HIDDEN'">
                                                {{ col.col_etiqueta }}
                                            </div>
                                        </th>
                                    </tr>
                                    <tr v-for="(row, rowIndex) in c.frm_value" :key="rowIndex">
                                        <td v-for="(col, colIndex) in row" :key="colIndex">
                                            <div v-if="c.frm_cols[colIndex].col_tipo == 'LABEL'">
                                                <label>{{ c.frm_cols[colIndex].col_etiqueta }}</label>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'TEXT'">
                                                <input :id="col.col_campo + rowIndex" :placeholder="col.col_etiqueta"
                                                    v-model="col.col_value"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'"
                                                    @input="col.col_value = col.col_value.toUpperCase()"
                                                    @keypress="validarEntradaText($event)">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'MAIL'">
                                                <input :id="col.col_campo + rowIndex" :placeholder="col.col_etiqueta"
                                                    v-model="col.col_value"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'TEXTAREA'">
                                                <textarea :id="col.col_campo + rowIndex" :placeholder="col.col_etiqueta"
                                                    v-model="col.col_value" cols="30" rows="3"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'"></textarea>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'NUMBER'">
                                                <input :id="col.col_campo + rowIndex" type="text"
                                                    v-on:keypress="isNumber($event)" v-model="col.col_value"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'DATE'">
                                                <input :id="col.col_campo + rowIndex" type="date"
                                                    :placeholder="col.col_etiqueta" v-model="col.col_value"
                                                    @change="ejecutar(c.frm_cols[colIndex].col_funcion, rowIndex)"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'" :max="fechaMaxima()">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'CHECKBOX'">
                                                <input :id="col.col_campo + rowIndex" type="checkbox"
                                                    :placeholder="col.col_etiqueta" v-model="col.col_value"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'LINK'">
                                                <a target="_blank" :href="col.col_value">Ver</a>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'BUTTON'">
                                                <button :id="c.frm_cols[colIndex].col_campo"
                                                    v-if="c.frm_deshabilitado == 'true' ? false : true" type="button"
                                                    class="btn btn-success"
                                                    @click="ejecutar(c.frm_cols[colIndex].col_funcion, rowIndex)"
                                                    style="margin:0!important;"> {{ c.frm_cols[colIndex].col_etiqueta
                                                    }}</button>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'HIDDEN'">
                                                <input :id="col.col_campo + rowIndex" type="hidden"
                                                    v-model="col.col_value">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'DROPDOWNLIST'">
                                                <select :id="col.col_campo + rowIndex" v-model="col.col_value"
                                                    @change="ejecutar(c.frm_cols[colIndex].col_funcion, rowIndex);"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'">
                                                    <option v-bind:value="itm.itm_value"
                                                        v-for="(itm, itmIndex) in c.frm_cols[colIndex].col_items"
                                                        :key="itmIndex">
                                                        {{ itm.itm_etiqueta }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'DOCUMENT'"
                                                :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                :required="col.col_obligatorio == 'true'">
                                                {{ c.frm_etiqueta }} <label v-show="c.col_obligatorio == 'true'"
                                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                                <div class="input-group mb-3">
                                                    <input :id="c.frm_cols[colIndex].col_campo"
                                                        v-model="c.frm_cols[colIndex].col_value" class="form-control"
                                                        placeholder="Ingrese Documento" disabled>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary btn-xs"
                                                            v-on:click.stop.prevent="verImagen(c.frm_cols[colIndex].col_value)">
                                                            <i class="fa fa-eye white" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="file" name="capturaFoto" @change="tamanoDocumento($event);"
                                                    accept=".pdf">
                                                <!--img :src="foto" alt="Foto" class="img-responsive" -->
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'MODAL'">
                                                <button type="button" class="btn btn-danger btn-circle"
                                                    title="Subir documentos"
                                                    v-on:click="listarRequisitos(c.frm_value[rowIndex], rowIndex)"
                                                    data-toggle="modal" data-target="#modalDocumentos">
                                                    <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                                                </button>
                                            </div>

                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'MODAL2'">
                                                <button type="button" class="btn btn-success btn-circle pulseBtn"
                                                    title="" v-on:click="" data-toggle="modal" data-target="#modalTutor"
                                                    @click="geTutor(c.frm_value[rowIndex], rowIndex)">
                                                    <i class="nav-icon fas fa-user blue" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <div v-else>
                                                NO IDENTIFICADO
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div v-else-if="c.frm_tipo == 'GPS_MARKER'" id="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="mapContainer"></div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                {{ c.frm_etiqueta }}
                                <span>Componente no determinado</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="documentosTitular.length != 0">
                        <div style="background-color:lightgrey; margin-top:10px;">
                            <h5>Documentación del Titular </h5>
                        </div>
                        <table class="table table-hover table-striped table-responsive" id="tabla_titular">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" style="text-align: center;">#</th>
                                    <th scope="col" style="text-align: center;">Descripción</th>
                                    <th scope="col" style="text-align: center;">Documento</th>
                                    <th scope="col" style="text-align: center;">Original/Fotocopia</th>
                                    <th scope="col" style="text-align: center;">Observación</th>
                                    <th scope="col" style="text-align: center;">Códigos</th>
                                    <th scope="col" style="text-align: center;">Detalle Documento</th>
                                    <th scope="col" style="text-align: center;">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(d, index) in documentosTitular">
                                    <td width="3%" scope="row">{{ d.id }}</td>
                                    <td>{{ d.descripcionTipoDocumentoRespaldo }}
                                        <input :id="'descripcion_tit_' + index"
                                            v-model="d.descripcionTipoDocumentoRespaldo" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>
                                        <input :id="'id_tit_' + index" v-model="d.id" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>
                                        <input :id="'documentoOriginalObligatorio_tit_' + index"
                                            v-model="d.documentoOriginalObligatorio" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>
                                        <input :id="'presentacionObligatoria_tit_' + index"
                                            v-model="d.presentacionObligatoria" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>
                                        <input :id="'doc_copia_original_tit_' + index" v-model="d.rdoc_copia_original"
                                            class="form-control" placeholder="Ingrese Documento" hidden="false">
                                    </td>
                                    <td>
                                        <div class="input-group mb-3">
                                            <input :id="'documento_' + index" v-model="d.descripcionRespaldo"
                                                v-if="d.nombre != ''" class="form-control"
                                                placeholder="Ingrese Documento" disabled>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary btn-xs"
                                                    v-on:click.stop.prevent="verDocumento(d.rdoc_id, d.nombre)">
                                                    <i class="fa fa-eye white" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input v-if="actividad.act_orden == '20' || actividad.act_orden == '50' || actividad.act_orden == '47' || actividad.act_orden == '49' || actividad.act_orden == '100'"
                                            :id="'pdf_tit_' + index" type="file" name="file"
                                            @change="tamanoDocumento($event)" accept=".pdf">
                                    </td>
                                    <td style="text-align: center;">
                                        <label :for="'switch_tit_' + index" class="switch">
                                            <input :id="'switch_tit_' + index" v-model="d.rdoc_copia_original"
                                                type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td style="text-align: center;">
                                        <select :id="'id_observacion_tit_' + index" v-model="d.obs_id_observacion">
                                            <option v-for="opcion in observacion" :value="opcion.id_observacion">{{
                                                opcion.codigo }}</option>
                                        </select>
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-warning btn-circle" title="Descripcion"
                                            @click="openDescripcionModal" data-toggle="modal"
                                            data-target="#modalDescripcionObs">
                                            <i class="fa fa-question" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    <td style="text-align: center;">
                                        <input :id="'detalleDocumento_tit_' + index" class="form-control"
                                            v-model="d.rdoc_detalle_documento"
                                            @input="d.rdoc_detalle_documento = d.rdoc_detalle_documento.toUpperCase()">
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-danger btn-circle"
                                            title="Dar baja documento"
                                            @click="limpiarDocumentoTitular(d.rdoc_id, d.rdoc_categoria);">
                                            <i class="fa fa-eraser" style="font-size:16px;color:white"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="swSolicitante && documentosSolicitante.length != 0">
                        <div style="background-color:lightgrey; margin-top:10px;">
                            <h5>Documentación del Solicitante</h5>
                        </div>
                        <table class="table table-hover table-striped table-responsive" id="tabla_solicitante">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" style="text-align: center;">#</th>
                                    <th scope="col" style="text-align: center;">Descripción</th>
                                    <th scope="col" style="text-align: center;">Documento</th>
                                    <th scope="col" style="text-align: center;">Original/Fotocopia</th>
                                    <th scope="col" style="text-align: center;">Observación</th>
                                    <th scope="col" style="text-align: center;">Códigos</th>
                                    <th scope="col" style="text-align: center;">Detalle Documento</th>
                                    <th scope="col" style="text-align: center;">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(d, index) in documentosSolicitante">
                                    <td width="3%" scope="row">{{ d.id }}</td>
                                    <td>{{ d.descripcionTipoDocumentoRespaldo }}
                                        <input :id="'descripcion_sol_' + index"
                                            v-model="d.descripcionTipoDocumentoRespaldo" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>
                                        <input :id="'id_sol_' + index" v-model="d.id" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>
                                        <input :id="'documentoOriginalObligatorio_sol_' + index"
                                            v-model="d.documentoOriginalObligatorio" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>
                                        <input :id="'presentacionObligatoria_sol_' + index"
                                            v-model="d.presentacionObligatoria" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>

                                        <input :id="'doc_copia_original_sol_' + index" v-model="d.rdoc_copia_original"
                                            class="form-control" placeholder="Ingrese Documento" hidden="false">
                                    </td>
                                    <td>
                                        <div class="input-group mb-3">
                                            <input v-if="d.nombre != ''" v-model="d.descripcionRespaldo"
                                                class="form-control" placeholder="Ingrese Documento" disabled>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary btn-xs"
                                                    v-on:click.stop.prevent="verDocumento(d.rdoc_id, d.nombre)">
                                                    <i class="fa fa-eye white" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input v-if="actividad.act_orden == '20' || actividad.act_orden == '50' || actividad.act_orden == '47' || actividad.act_orden == '49' || actividad.act_orden == '100'"
                                            :id="'pdf_sol_' + index" type="file" name="file"
                                            @change="tamanoDocumento($event)" accept=".pdf">
                                    </td>
                                    <td style="text-align: center;">
                                        <label :for="'switch_sol_' + index" class="switch">
                                            <input :id="'switch_sol_' + index" v-model="d.rdoc_copia_original"
                                                type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td style="text-align: center;">
                                        <select :id="'id_observacion_sol_' + index" v-model="d.obs_id_observacion">
                                            <option v-for="opcion in observacion" :value="opcion.id_observacion">{{
                                                opcion.codigo }}</option>
                                        </select>
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-warning btn-circle" title="Descripcion"
                                            @click="openDescripcionModal" data-toggle="modal"
                                            data-target="#modalDescripcionObs">
                                            <i class="fa fa-question" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    <td style="text-align: center;">
                                        <input :id="'detalleDocumento_sol_' + index" type="text" class="form-control"
                                            v-model="d.rdoc_detalle_documento"
                                            @input="d.rdoc_detalle_documento = d.rdoc_detalle_documento.toUpperCase()">
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-danger btn-circle"
                                            title="Dar baja documento"
                                            @click="limpiarDocumentoSolicitante(d.rdoc_id, d.rdoc_categoria);">
                                            <i class="fa fa-eraser" style="font-size:16px;color:white"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
                <div style="background-color: lightgrey; margin-top: 10px">
                    <h5>Adjuntos</h5>
                </div>
                <table class="table table-hover table-striped table-responsive">
                    <tr>
                        <td scope="col">
                            <table class="table table-hover table-striped table-responsive" id="tabla_dinamica">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Referencia</th>
                                        <th scope="col">Original / Fotocopia</th>
                                        <th scope="col">Adjuntar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input :id="'RespaldoFile_0'" type="file" name="file"
                                                @change="getDocument($event, c.frm_cols[colIndex].col_campo, index)"
                                                accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf" />
                                        </td>
                                        <td width="40%">
                                            <textarea name="RespaldoDesc[]" id="RespaldoDesc_0" rows="1" cols="50"
                                                @input="$event.target.value = $event.target.value.toUpperCase()"></textarea>
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input id='switch_adj_0' type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <button type="button" name="adicionar" id="adicionar"
                                                class="btn_respaldos_plus" v-on:click="adicionarAdjunto()">
                                                +
                                            </button>
                                        </td>
                                    </tr>
                                    -
                                    <tr v-for="(item, index) in items" :key="index">
                                        <td>
                                            <input :id="'RespaldoFile_' + (index + 1)" type="file" @change="
                                                validarExtensiones(item.RespaldoFile)
                                                " accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf" />
                                            <!-- <span @click="borrarCampo(index + 1)" style="cursor: pointer">Cancelar</span> -->
                                        </td>
                                        <td>
                                            <textarea :id="'RespaldoDesc_' + (index + 1)" v-model="item.RespaldoDesc"
                                                rows="1" cols="50"></textarea>
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input :id="'switch_adj_' + (index + 1)" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <button type="button" @click="removeCampo(index)"
                                                class="btn_respaldos_minus">
                                                -
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td scope="col"><button type="button" class="btn btn-primary   btn_respaldos_plus2"
                                title="Adjuntos" v-on:click="doDocumentoPdfAdjunto()" data-toggle="modal"
                                data-target="#modalDocumentoAdjuntoPdf">
                                <i class="fa fa-list-ol" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                </table>
                <div>
                    <button @click="uploadFile" class="form-control btn-primary"> Subir Archivo </button>
                </div>
                <!-- -------------------------------------------------------------------------------------------------------------- -->
                <div v-if="codProceso == 'INV'">
                    <div style="background-color: lightgrey; margin-top: 10px">
                        <h5>Adjuntos Medicos</h5>
                    </div>
                    <table>
                        <tr>
                            <td scope="col">
                                <table class="table table-hover table-striped table-responsive"
                                    id="tabla_dinamicaMedicos">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Documento</th>
                                            <th scope="col">Referencia</th>
                                            <th scope="col">Original / Fotocopia</th>
                                            <th scope="col">Adjuntar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input :id="'RespaldoFileMedicos_0'" type="file" name="file"
                                                    @change="getDocument($event, c.frm_cols[colIndex].col_campo, index)"
                                                    accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf" />
                                            </td>
                                            <td width="40%">
                                                <textarea name="RespaldoDescMedicos[]" id="RespaldoDescMedicos_0"
                                                    rows="1" cols="50"
                                                    @input="$event.target.value = $event.target.value.toUpperCase()"></textarea>
                                            </td>
                                            <td>
                                                <label class="switch">
                                                    <input id='switch_adj_medicos_0' type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <button type="button" name="adicionar_m" id="adicionar_m"
                                                    class="btn_respaldos_plus" v-on:click="adicionarAdjuntoMedico()">
                                                    +
                                                </button>
                                            </td>
                                        </tr>
                                        -
                                        <tr v-for="(medicoitem, index) in medicoitems" :key="index">
                                            <td>
                                                <input :id="'RespaldoFileMedicos_' + (index + 1)" type="file" @change="
                                                    validarExtensiones(medicoitem.RespaldoFile)
                                                    " accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf" />
                                            </td>
                                            <td>
                                                <textarea :id="'RespaldoDescMedicos_' + (index + 1)"
                                                    v-model="medicoitem.RespaldoDesc" rows="1" cols="50"
                                                    @input="$event.target.value = $event.target.value.toUpperCase()"></textarea>
                                            </td>
                                            <td>
                                                <label class="switch">
                                                    <input :id="'switch_adj_medicos_' + (index + 1)" type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <button type="button" @click="removeCampoMedicos(index)"
                                                    class="btn_respaldos_minus">
                                                    -
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td scope="col"><button type="button" class="btn btn-primary   btn_respaldos_plus2"
                                    title="Adjuntos Medicos" v-on:click="doDocumentoPdfAdjuntoMedico()"
                                    data-toggle="modal" data-target="#modalDocumentoMedicoPdf">
                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                    <div>
                        <button @click="uploadFileMedicos()" class="form-control btn-primary"> Subir Archivo </button>
                    </div>
                </div>
                <div class="row col-md-12"> </div>
            </div>
            <span>
                <h5>
                    <center>Los campos marcados con <font color="red">
                            <font color="red">(*)</font>
                        </font> son obligatorios</center>
                </h5>
            </span>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <button type="submit" class="form-control btn-success" @click="$router.push('/misCasos')">
                            <i class="fa fa-backward white" aria-hidden="true"></i>
                            Descartar y Volver</button>
                    </div>
                    <div class="col-md-3">
                        <!-- <button class="form-control btn-primary" @click="actualizarCaso()" form="form1" >
							<i class="fa fa-floppy-o white" aria-hidden="true"></i>
							Grabar y Volver</button> -->
                        <button type="submit" form="form1" class="form-control btn-primary">
                            <i class="fa fa-floppy-o white" aria-hidden="true"></i>
                            Grabar y Volver</button>
                        <div id="overlay" ref="overlay" class="overlay" style="z-index: 2000;">
                            <div class="loader-wrapper">
                                <div class="loader"></div>
                                <span class="loader-text">TramiteSip</span>
                                <span class="loading-text">Cargando...</span>
                            </div>
                        </div>
                        <!-- <button class=" btn-primary" @click="registarArchivoTitular()">
								documentos</button> -->
                    </div>

                </div>
                <br>
            </div>
        </div>
        <!-- Modal Generico -->
        <div id="modalGenerico" class="modal-generico">
            <!-- Contenido del modal -->
            <div class="modal-generico-contenido-atender-caso">
                <span class="cerrar-modal-generico" @click="cerrarModalGenerico()">&times;</span>
                <h2 id="modalGenerico-titulo">Título del Modal</h2>
                <div class="modal-icono">
                    <img src="" alt="Advertencia" class="icono-advertencia" />
                </div>
                <p id="modalGenerico-mensaje"></p>
                <div style="padding: 10px;">
                    <button class="cerrar-btn" id="modalGenerico-boton" @click="cerrarModalGenerico()">Cerrar</button>
                </div>
            </div>
        </div>
            <!-- Modal Sub Generico -->
            <div id="subModalGenerico" class="sub-modal-generico">
                <!-- Contenido del sub modal -->
                <div class="sub-modal-generico-contenido-atender-caso">
                    <span class="sub-cerrar-modal-generico" @click="cerrarSubModalGenerico()">&times;</span>
                    <h2 id="sub-modalGenerico-titulo">Título del Modal</h2>
                    <div class="sub-modal-icono">
                        <img src="" alt="Advertencia" class="sub-icono-advertencia" />
                    </div>
                    <p id="sub-modalGenerico-mensaje"></p>
                    <div style="padding: 10px;">
                        <button class="cerrar-btn" id="modalGenerico-boton" @click="cerrarSubModalGenerico()">Cerrar</button>
                    </div>
                </div>
            </div>

        <div>
            <p v-show="addScript(registro.frm_funciones)"></p>
        </div>
        <!-- modalTutor PDF -->
        <div class="modal fade" id="modalTutor" tabindex="-1" role="dialog" aria-labelledby="modalTutor"
            aria-hidden="true">
            <div class="modal-dialog lg" role="document" style="max-width: 60%;">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Registro Tutor </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <input type="text" id="id_persona_sip" style="display: none;" />
                        </div>
                        <div>
                            <input type="text" id="numero_documento" style="display: none;" />
                        </div>
                        <template>
                            <div class="row col-md-12">
                                <div class="col-md-4">
                                    <label for="ci_tutor">CI:</label>
                                    <input type="text" id="ci_tutor" class="form-control" placeholder="ci_tutor"
                                        required />
                                </div>
                                <div class="col-md-3">
                                    <label for="ci_tutor">complemento:</label>
                                    <input type="text" id="complemento" class="form-control" placeholder="complemento"
                                        required />
                                </div>
                                <div class="col-md-3 d-flex justify-content-center align-items-center"
                                    style="height: 100px;">
                                    <button type="button" class="btn btn-success" @click="BuscarTutor()">Buscar</button>
                                </div>
                            </div>
                        </template>
                        <template>
                            <div class="row col-md-12">
                                <div class="col-md-3">
                                    <label for="nombre_">Nombre:</label>
                                    <input type="text" id="nombre_" class="form-control" placeholder="Nombre del tutor"
                                        required />
                                </div>
                                <div class="col-md-3">
                                    <label for="primer_apellido">Primer Apellido:</label>
                                    <input type="text" id="primer_apellido" class="form-control"
                                        placeholder="Primer Apellido" required />
                                </div>
                                <div class="col-md-3">
                                    <label for="segundo_apellido">Segundo Apellido:</label>
                                    <input type="text" id="segundo_apellido" class="form-control"
                                        placeholder="Segundo Apellido" required />
                                </div>
                                <div class="col-md-3">
                                    <label for="apellido_casada">Apellido Casada:</label>
                                    <input type="text" id="apellido_casada" class="form-control"
                                        placeholder="Apellido Casada" required />
                                </div>
                                <div class="col-md-3">
                                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                                    <input type="date" id="fecha_nacimiento" class="form-control"
                                        placeholder="Fecha de Nacimiento" required />
                                </div>
                                <div class="col-md-3">
                                    <label for="genero">Genero:</label>
                                    <input type="text" id="genero" class="form-control" placeholder="Genero" required />
                                </div>
                                <div class="col-md-3">
                                    <label for="direccion">Direccion:</label>
                                    <input type="text" id="direccion" class="form-control" placeholder="Direccion"
                                        required />
                                </div>
                                <div class="col-md-3">
                                    <label for="numero">Numero:</label>
                                    <input type="text" id="numero" class="form-control" placeholder="Numero" required />
                                </div>
                                <div class="col-md-3">
                                    <label for="nro_celular">Nro Celular:</label>
                                    <input type="tel" id="nro_celular" class="form-control" placeholder="Nro Celular"
                                        required />
                                </div>
                                <div class="col-md-3">
                                    <label for="correo">Correo electrónico:</label>
                                    <input type="email" id="correo" class="form-control"
                                        placeholder="Correo electrónico" required />
                                </div>
                                <div class="col-md-3">
                                    <label for="parentesco">Parentesco:</label>
                                    <select id="parentesco" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        <option value="natural">Natural</option>
                                        <option value="legal">Legal</option>
                                    </select>
                                </div>
                            </div>
                        </template>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-success" data-dismiss="modal"
                                @click="guardarTutor()">Guardar Tutor</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalDocumentos PDF -->
        <div class="modal fade" id="modalDocumentos" tabindex="-1" role="dialog" aria-labelledby="modalDocumentos"
            aria-hidden="true">
            <div class="modal-dialog lg" role="document" style="max-width: 60%;">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Adjuntar Requisitos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover table-striped table-responsive" id="tabla_requisitos">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" style="text-align: center;">#</th>
                                    <th scope="col" style="text-align: center;">Descripción</th>
                                    <th scope="col" style="text-align: center;">Documento</th>
                                    <th scope="col" style="text-align: center;">Original/Fotocopia</th>
                                    <th scope="col" v-if="mostrarObservacion" style="text-align: center;">Observación
                                    </th>
                                    <th scope="col" v-if="mostrarObservacion" style="text-align: center;">Codigos</th>
                                    <th scope="col" style="text-align: center;">Detalle Documento</th>
                                    <th scope="col" style="text-align: center;">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(d, index) in documento">
                                    <td width="3%" scope="row">{{ d.id }}</td>
                                    <td>{{ d.descripcionTipoDocumentoRespaldo }}
                                        <input :id="'descripcion_' + index" v-model="d.descripcionTipoDocumentoRespaldo"
                                            class="form-control" placeholder="Ingrese Documento" hidden=false>
                                        <input :id="'id_' + index" v-model="d.id" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>
                                        <input :id="'documentoOriginalObligatorio_' + index"
                                            v-model="d.documentoOriginalObligatorio" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>
                                        <input :id="'presentacionObligatoria_' + index"
                                            v-model="d.presentacionObligatoria" class="form-control"
                                            placeholder="Ingrese Documento" hidden=false>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="input-group mb-3">
                                            <input v-if="d.nombre != ''" v-model="d.descripcionRespaldo"
                                                class="form-control" placeholder="Ingrese Documento" disabled>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary btn-xs"
                                                    v-on:click.stop.prevent="verDocumento(d.rdoc_id, d.nombre)">
                                                    <i class="fa fa-eye white" aria-hidden="true"
                                                        title="Ver Documento"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input v-if="actividad.act_orden == '20' || actividad.act_orden == '50' || actividad.act_orden == '47' || actividad.act_orden == '49' || actividad.act_orden == '100'"
                                            :id="'pdf_' + index" type="file" name="file" accept=".pdf"
                                            @change="tamanoDocumento($event)">
                                    </td>
                                    <td style="text-align: center;">
                                        <label :for="'switch_' + index" class="switch">
                                            <input :id="'switch_' + index" v-model="d.rdoc_copia_original"
                                                type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td v-if="mostrarObservacion" style="text-align: center;">
                                        <select :id="'id_observacion_' + index" v-model="d.obs_id_observacion">
                                            <option v-for="opcion in observacion" :value="opcion.id_observacion">{{
                                                opcion.codigo }}</option>
                                        </select>
                                    </td>
                                    <td v-if="mostrarObservacion" style="text-align: center;">
                                        <button type="button" class="btn btn-warning btn-circle" title="Descripcion"
                                            @click="openDescripcionModal" data-toggle="modal"
                                            data-target="#modalDescripcionObs">
                                            <i class="fa fa-question" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    <td style="text-align: center;">
                                        <input :id="'detalleDocumento_' + index" type="text" class="form-control"
                                            v-model="d.rdoc_detalle_documento"
                                            @input="d.rdoc_detalle_documento = rdoc_detalle_documento.toUpperCase()">
                                    </td>
                                    <td style="text-align: center;">
                                        <button id="btn-adjuntarRequisitos" type="button"
                                            class="btn btn-danger btn-circle btn-adjuntarRequisitos"
                                            title="Dar baja documento"
                                            @click="limpiarDocumento(d.rdoc_id, d.rdoc_categoria);">
                                            <i class="fa fa-eraser" style="font-size:16px;color:white"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success"
                            @click="registarArchivo($event, index)">Registrar Requisitos</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modalArchivar -->
        <div class="modal fade" id="modalArchivar" tabindex="-1" role="dialog" aria-labelledby="modalHistorico"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Archivar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <!-- Show this select when codProceso is 'INV' -->
                            <div v-if="codProceso == 'INV'">
                                <div class="col-md-12">
                                    <label>Motívo Archivo:</label>
                                    <select v-model="archivo.cas_motivo_archivo" class="form-control">
                                        <option value='ANU'>ANULADO </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Show this select when codProceso is 'PM' -->
                            <div v-if="codProceso == 'PM'">
                                <div class="col-md-12">
                                    <label>Motívo Archivo:</label>
                                    <select v-model="archivo.cas_motivo_archivo" class="form-control">
                                        <option value='ANU'>ANULADO </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Show this select for all other codProceso values -->
                            <div v-if="codProceso != 'INV' && codProceso != 'PM'">
                                <div class="col-md-12">
                                    <label>Motívo Archivo:</label>
                                    <select v-model="archivo.cas_motivo_archivo" class="form-control">
                                        <option value='NO_COMPLETO'>NO completó los requisitos</option>
                                        <option value='SIN_SEGUIMIENTO'>Falta de seguimiento</option>
                                        <option value='NO_SUBSANABLES'>Observaciones NO subsanables</option>
                                        <option value='FINALIZADO'>Finalizado </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Descripción:</label>
                                <textarea v-model="archivo.cas_descripcion_archivo" class="form-control"
                                    rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-warning" @click="archivarCaso($event)"
                            data-dismiss="modal">Si,
                            archivar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalEmnienda -->
        <div class="modal fade" id="modalEmnienda" tabindex="-1" role="dialog" aria-labelledby="modalHistorico"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Enmienda</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Observación:</label>
                                <textarea v-model="archivo.cas_descripcion_archivo" class="form-control"
                                    rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-warning" @click="enmienda($event)" data-dismiss="modal">Si,
                            enmienda</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modalHistorico -->
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
                                            <td>{{ (JSON.parse(h.act_data)).act_orden + ' - ' +
                                                (JSON.parse(h.act_data)).act_descripcion }} <br>
                                                <span class="badge badge-warning"><strong>{{ h.est_codigo
                                                        }}</strong></span>
                                            </td>
                                            <td>{{ h.nodo_descripcion }}</td>
                                            <td>{{ h.htc_cas_modificado }}</td>
                                            <td>{{ h.name }}</td>
                                            <td>{{ (JSON.parse(h.htc_cas_data)).ESTADO_DERIVACION }}</td>
                                            <td>{{ (JSON.parse(h.htc_cas_data)).DESCRIPCION_DERIVACION }}</td>
                                            <td align="center">
                                                <button type="button" class="btn btn-primary btn-circle"
                                                    title="Histórico" v-on:click="doDocumentoPdf(h.htc_id)"
                                                    data-toggle="modal" data-target="#modalDocumentoMedicoPdf">
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
                        <h2>VALIDACIONES DEL TRÁMITE</h2>
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Caso:</label>{{ id_caso }} <br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Nro Trámite</th>
                                            <th>Actividad</th>
                                            <th>Estado</th>
                                            <th>Descripción derivación</th>
                                            <th>Documento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="resultadosLegalTramite.length > 0">
                                            <tr v-for="(h, index) in resultadosLegalTramite">
                                                <td align="center"><span class="badge badge-dark">{{ index + 1 }}</span>
                                                </td>
                                                <td>
                                                    {{ h.cas_cod_id }}
                                                </td>
                                                <td>
                                                    {{ h.act_orden }} - {{ h.act_descripcion }} <br>
                                                    <strong> {{ h.nodo_descripcion }} </strong>
                                                </td>
                                                <td>{{ JSON.parse(h.cas_data).ESTADO_DERIVACION }} </td>
                                                <td> {{ JSON.parse(h.cas_data).DESCRIPCION_DERIVACION }}</td>
                                                <td>
                                                    <!-- {{ h.htc_id }} -->
                                                    <button type="button" class="btn btn-primary btn-circle"
                                                        title="Histórico Legal"
                                                        v-on:click="obtenerDocumentoLegalGral(h.cas_id)"
                                                        data-toggle="modal" data-target="#modalDocumentoLegalPdf">
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
        <div class="modal fade" id="modalLegal" tabindex="-3" role="dialog" aria-labelledby="esos" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary" style="background-color: #944b12 !important;">
                        <h5 class="modal-title" id="exampleModalLabel">Información Legal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h2>VALIDACIONES DEL CUA</h2>
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Caso:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Nro Trámite</th>
                                            <th>Actividad</th>
                                            <th>Estado</th>
                                            <th>Descripción derivación</th>
                                            <th>Documento</th>
                                            <th>PRESTACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="resultadosLegal.length > 0">
                                            <tr v-for="(h, index) in resultadosLegal">
                                                <td align="center"><span class="badge badge-dark">{{ index + 1 }}</span>
                                                </td>
                                                <td>
                                                    {{ h.cas_cod_id }}
                                                </td>
                                                <td>
                                                    {{ h.act_orden }} - {{ h.act_descripcion }} <br>
                                                    <strong> {{ h.nodo_descripcion }} </strong>
                                                </td>
                                                <td>{{ JSON.parse(h.cas_data).ESTADO_DERIVACION }} </td>
                                                <td> {{ JSON.parse(h.cas_data).DESCRIPCION_DERIVACION }}</td>
                                                <td>
                                                    <!-- {{ h.htc_id }} -->
                                                    <button type="button" class="btn btn-primary btn-circle"
                                                        title="Histórico Legal"
                                                        v-on:click="obtenerDocumentoLegalGral(h.cas_id)"
                                                        data-toggle="modal" data-target="#modalDocumentoLegalPdf">
                                                        <i class="far fa-file-pdf white" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button v-if="h.tram == 0"
                                                        type="button" class="btn btn-success btn-circle" title="Adjuntar"
                                                        data-toggle="modal" @click="enlazarPrestacion(h.cas_id, h.cas_cod_id, cas_id, cas_cod_id)"
                                                        data-target="#modalAdjuntar">
                                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    </button>
                                                    <p v-if="h.total > 0">VALIDACIÓN UTILIZADA</p>
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
                        <h2>VALIDACIONES DEL TRÁMITE</h2>
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Caso:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Nro Trámite</th>
                                            <th>Actividad</th>
                                            <th>Estado</th>
                                            <th>Descripción derivación</th>
                                            <th>Documento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="resultadosLegalTramite.length > 0">
                                            <tr v-for="(h, index) in resultadosLegalTramite">
                                                <td align="center"><span class="badge badge-dark">{{ index + 1 }}</span>
                                                </td>
                                                <td>
                                                    {{ h.cas_cod_id }}
                                                </td>
                                                <td>
                                                    {{ h.act_orden }} - {{ h.act_descripcion }} <br>
                                                    <strong> {{ h.nodo_descripcion }} </strong>
                                                </td>
                                                <td>{{ JSON.parse(h.cas_data).ESTADO_DERIVACION }} </td>
                                                <td> {{ JSON.parse(h.cas_data).DESCRIPCION_DERIVACION }}</td>
                                                <td>
                                                    <!-- {{ h.htc_id }} -->
                                                    <button type="button" class="btn btn-primary btn-circle"
                                                        title="Histórico Legal"
                                                        v-on:click="obtenerDocumentoLegalGral(h.cas_id)"
                                                        data-toggle="modal" data-target="#modalDocumentoLegalPdf">
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
        <!-- modalDocumentoMedicoPdf -->
        <div class="modal fade" id="modalDocumentoMedicoPdf" tabindex="-3" role="dialog"
            aria-labelledby="modalDocumentoMedicoPdf" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 40%;">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Documentos Medicos {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Listado de Documentos Medicos:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro</th>
                                            <th>tipo</th>
                                            <th>Descripcion</th>
                                            <th>Ver Documento</th>
                                            <th>Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(h, index) in documento">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ h.tipo }}</td>
                                            <td>{{ h.descripcion }}</td>
                                            <td align="center">
                                                <button v-if="h.nombre === ''" type="button"
                                                    class="btn  btn-danger  btn-circle " title="Ver Documento">
                                                    <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                </button>

                                                <!-- <button v-if="h.nombre != ''" type="button"
                                                    class="btn  btn-success btn-circle " title="Ver Documento222"
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
                                            <td>
                                                <button type="button" class="btn btn-danger btn-circle"
                                                    title="Dar baja documento"
                                                    @click="limpiarDocumentoMedicoAdjunto(h.doc_id, h);"
                                                    :disabled="h.nombre == ''">
                                                    <i class="fa fa-eraser" style="font-size:16px;color:white"></i>
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
        <!-- modalDocumentoLegalPdf -->
        <div class="modal fade" id="modalDocumentoLegalPdf" tabindex="-3" role="dialog"
            aria-labelledby="modalDocumentoLegalPdf" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 40%;">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Documentos Medicos {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Listado de Documentos Medicos:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro</th>
                                            <th>tipo</th>
                                            <th>Descripcion</th>
                                            <th>Ver Documento</th>
                                            <th>Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(h, index) in documento">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ h.tipo }}</td>
                                            <td>{{ h.descripcion }}</td>
                                            <td align="center">
                                                <button v-if="h.nombre === ''" type="button"
                                                    class="btn  btn-danger  btn-circle " title="Ver Documento">
                                                    <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                </button>

                                                <button v-if="h.nombre != ''" type="button"
                                                    class="btn  btn-success btn-circle " title="Ver Documento"
                                                    v-on:click="verDocumento(h.doc_id, h.nombre)">
                                                    <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-circle"
                                                    title="Dar baja documento"
                                                    @click="limpiarDocumentoMedicoAdjunto(h.doc_id, h);"
                                                    :disabled="h.nombre == ''">
                                                    <i class="fa fa-eraser" style="font-size:16px;color:white"></i>
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

        <!-- modalDocumentoAdjuntoPdf -->
        <div class="modal fade" id="modalDocumentoAdjuntoPdf" tabindex="-3" role="dialog"
            aria-labelledby="modalDocumentoAdjuntoPdf" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 40%;">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Documentos Adjuntos</h5>
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
                                            <th>Descripcion</th>
                                            <th>Ver Documento</th>
                                            <th>Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(h, index) in documento">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ h.tipo }}</td>
                                            <td>{{ h.descripcion }}</td>
                                            <td align="center">
                                                <button v-if="h.nombre === ''" type="button"
                                                    class="btn  btn-danger  btn-circle " title="Ver Documento">
                                                    <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                </button>
                                                <button v-if="h.nombre != ''" type="button"
                                                    class="btn  btn-success btn-circle " title="Ver Documento"
                                                    v-on:click="verDocumento(h.doc_id, h.nombre)">
                                                    <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-circle"
                                                    title="Dar baja documento"
                                                    @click="limpiarDocumentoAdjunto(h.doc_id, h);"
                                                    :disabled="h.nombre == ''">
                                                    <i class="fa fa-eraser" style="font-size:16px;color:white"></i>
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

        <!-- modal Previzualizar PDF -->
        <!-- <div class="modal fade" id="modalPrevisualizar" tabindex="-1" role="dialog" aria-labelledby="modalPrevisualizar"
            aria-hidden="true">
            <div class="modal-dialog lg" role="document" style="max-width: 60%;">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Mostrar PDF</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="pdfSrc === ''"> no muestra nada </div>
                        <div v-else>
                            <embed :src="sanitizedPdfSrc" type="application/pdf" width="100%" height="800px">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div> -->
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

        <!-- modalCampos -->
        <div class="modal fade" id="modalCampos" tabindex="-1" role="dialog" aria-labelledby="modalCampos"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="modal-title" id="exampleModalLabel">Ver Campos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <pre id="misCampos"></pre>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Respuesta Contrato -->
        <div class="modal fade" id="modalRespuestaContrato" tabindex="-1" role="dialog"
            aria-labelledby="modalRespuestaContrato" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="modalRespuestaContratoLabel">Emisión de Contrato para Firma de
                            Asegurado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-11">
                                <label id="mensajeRespuestaContrato"
                                    style="display: block; text-align: justify;"></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnRespuestaContrato" type="button" class="btn btn-primary"
                            data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mensaje de carga con imagen GIF animada -->
        <div v-if="loading" class="loading-overlay">
            <div class="loading-message">
                <p>Cargando...</p>
            </div>
        </div>
        <!-- modalDescripcionObs -->
        <div class="modal fade" id="modalDescripcionObs" aria-labelledby="modalDescripcionObs" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Descripción de Observaciones</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <img src="../../../public/img/descripcionObservacion.jpg"
                                    style="width: 690px; height: 520px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modalAsignacionCasos -->
        <div class="modal fade" id="modalAsignacionCasos" tabindex="-1" role="dialog"
            aria-labelledby="modalAsignacionCasos" aria-hidden="true">
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
                        <button type="button" class="btn btn-success" @click="confirmarAsignacion">Si, asignar</button>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <!-- Incluir el componente VisorPDF -->
            <legal ref="legalComponent" /> <!-- Aquí se incluye el componente VisorPDF -->
        </div>
        <!-- Modal de Confirmación -->
        <div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-labelledby="modalConfirmacion"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="modalConfirmacionLabel">Confirmación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Está seguro de que desea asignar el caso a {{ nombreUsuarioAsignacion }}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-warning" @click="asignarCaso">Si, Asignar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalcalculosSemiAutomaticos" tabindex="-1" role="dialog" aria-labelledby="modalcalculosSemiAutomaticos"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 70%;">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #10748F; color: white;">
                        <h5 class="modal-title" id="modalcalculosSemiAutomaticosLabel">Resultados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="background-color: #10748F; color: white;">
                                    <th style="width:350px;"><strong>Concepto</strong></th>
                                    <th style="width:100px;"><strong>Valor Vejez</strong></th>
                                    <th style="width:100px;"><strong>Valor Solidario</strong></th>
                                    <th style="width:100px;"><strong>Valor Solidario con IBERO</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(resultado, index) in resultados" :key="index">
                                    <td style="background-color: #10748F; color: white;">{{ resultado.concepto }}</td>
                                    <td>{{ resultado.valorVejez }}</td>
                                    <td>{{ resultado.valorSolidario }}</td>
                                    <td>{{ resultado.valorSolidarioIbero }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import legal from './legal/Adjuntos.vue';
//import jsPDF from 'jspdf';
import { mask } from 'vue-the-mask';
import config from './config.js';
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import { LMap, LTileLayer, LMarker, LIcon, LPopup, LCircleMarker } from "vue2-leaflet";
import data from "./HL.json";
import axios from "axios";
import Swal from 'sweetalert2';
import vSelect from 'vue-select';
import { Alert } from 'bootstrap';
import {decryptId, clearLockerLayout} from './shared/AuxiliaryFunctions.js';
// import { Exception } from 'sass';//throws an error in building time ("Critical dependency: require function is used in a way in which dependencies cannot be statically extracted")
import MultiselectComponent from './externo/MultiselectComponent.vue';
L.Icon.Default.imagePath = '.';

L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

export default {
    name: 'atenderCaso',

    components: {
        LMap, LMarker, LCircleMarker, LPopup, LTileLayer, 'v-select': vSelect, legal, MultiselectComponent,
    },
    props: ['url_encode_cas_id'],
    directives: { mask },
    created() {
        try {
            const raw = this.$route.params.url_encode_cas_id
            // const decoded = atob(decodeURIComponent(raw))
            this.cas_id = decryptId(raw);
            // console.log('decoded cas_id:', this.cas_id)
        }
        catch (error) {
            console.error('Failed to decode cas_id:', error);
            this.$router.push("/misCasos");
        }
    },
    data() {
        return {
            cas_id:null,
            currentPage: 1,
            totalPage: 0,

            esMilitarPdf: false,
            selectedFile: null,
            loading: false,
            plural: 'Atender Casos',
            singular: 'Formulario',
            usrId: window.Laravel.usr_id,
            usrUser: window.Laravel.usr_user,
            usrName: window.Laravel.usr_name,
            registro: [],
            proceso: [{ prc_data: {} }],
            actividad: [{ act_data: {} }],
            caso: [{ cas_data: {} }],
            campos: [],
            camposTexto: '',
            selects: [],
            ejecute: false,
            foto: null,
            fotoPath: null,
            fotoDir: null,
            documento: [],
            documentoPath: null,
            documentoDir: null,
            archivo: {},
            mensaje: "",
            mensaje_tipo: "1",
            mensajes: [],
            documentos: [],
            documentosTitular: [],
            documentosSolicitante: [],
            cas_cod_id: '',
            cas_act_id: '',
            cas_act_orden: '',
            codProceso: null,
            historico: [],
            observaciones: [],
            pdfSrc: '',
            cas_cod_id: '',
            codProceso: null,
            codigoTipoParentesco: '',
            index: null,
            cas_departamento: '',
            ESTADO_DERIVACION: '',
            DESCRIPCION_DERIVACION: '',
            correlativo_aps: '',
            swSolicitante: false,
            id_caso: '',
            observacion: [],
            bandera: 0,
            urls: [],
            items: [],
            medicoitems: [],
            showAdjuntosMedicos: !true,
            id_cas_departamento: '',
            id_cas_agencia: '',
            id_cas_regional: '',
            contador_gfu: 0,
            TIPO_PROCESO: '',
            cas_registro: '',
            fecha_supera_6: "false",
            urlGestora: window.Laravel.url_gestora,       //config.URL_GESTORA + ''
            urlGestoraSgg: window.Laravel.url_gestora_sgg, //config.URL_GESTORA_SGG + '',
            usuariosNodo: [],
            usuarioNodo: null,
            descripcionUsuarioAsignacion: '',
            valUsuarioAsignacion: false,
            nombreUsuarioAsignacion: null,
            es_fallecido: false,
            derechoHabientes: {},
            prc_id: null,
            idsgg: 0,
            tutor: {
                ci: '',
                nombres: '',
                primerApellido: '',
                segundoApellido: '',
                apellidoCasada: '',
                genero: '',
                direccion: '',
                numero: '',
                nroCelular: '',
                correo: '',
                parentesco: '',
            },

            resultados: [],
            resultadosLegal: [],
            resultadosLegalTramite: [],

            estado_derivacion_val_semiAutomaticos : '',
            getidSolicitudPrestacion : '',
        }
    },

    mounted() {
        clearLockerLayout();
        //this.setupLeafletMap();
        this.render();
        this.ocultarOverlay();
        //this.getCiudades();
        //this.checkProceso();
        this.$once('hook:mounted', () => {
            var url = "api/caso/" + this.cas_id;
            axios.get(url).then(response => {
                const formScript = response.data.data[0].frm_funciones; //twice data
                this.addScript_v1(formScript);
            });
        });
    },

    computed: {
        sanitizedPdfSrc() {
            // Convierte la cadena Base64 a un formato seguro usando btoa()
            this.pdfData = `data:application/pdf;base64,` + this.pdfSrc;
            return `data:application/pdf;base64,` + this.pdfSrc;
        },

        mostrarObservacion() {
            return this.TIPO_PROCESO !== 'GFU';
        },
    },

    methods: {

            async openModal(htc_id) {
                window.open(`${htc_id}`, '_blank');
            },

        /* checkProceso() {
            const _COD_PROCESO = document.getElementById("_COD_PROCESO").value;
            if (_COD_PROCESO == 'INV') {
                this.showAdjuntosMedicos = true;
            } else {
                this.showAdjuntosMedicos = false;
            }
        }, */

        async asignarCampos() {
            this.campos = this.registro.frm_data_campos;
            let camposArray = JSON.parse(this.campos);
            this.campos = camposArray;
            this.campos.forEach(campo => {
                var res = this.registro.cas_data_valores.find(item => item.frm_campo === campo.frm_campo);
                if (res) {
                    campo.frm_value = res.frm_value;
                    //campo.frm_deshabilitado = res.frm_deshabilitado;
                    //campo.frm_deshabilitadoo = res.frm_deshabilitadoo;
                }
            });
            await console.log("campos: ", this.campos);
            this.campos.forEach(campo => {
                var res = this.registro.cas_data_valores.find(item => item.frm_campo === campo.frm_campo);
                var datalength = this.campos.find(item => item.frm_campo === 'GRILLA_DERECHOHABIENTES');
                if (datalength) {
                    this.contador_gfu = datalength.frm_value.length;
                }
                if (res) {
                    if (campo.frm_tipo === "DROPDOWNLIST") {
                        const dropdown = document.getElementById(campo.frm_campo);
                        dropdown.setAttribute("data-frm-value", res.frm_value);
                    }
                }
            });

            if (document.getElementById('BTN_ACEPTACION_V_RM_RF')) {
                document.getElementById('BTN_ACEPTACION_V_RM_RF').disabled = true;
            }
            if (document.getElementById('CONFIRMAR_DERECHOHABIENTE')) {
                document.getElementById('CONFIRMAR_DERECHOHABIENTE').disabled = true;
            }
        },
        pruebamodal() { console.log('pruebamodal'); },
        ejecutarLocal(campo) {
            if (campo.frm_campo == 'SOL_DIFERENTE_AS') {
                this.listarRequisitosSolicitante();
            }
        },
        ejecutarFuncionesLocales(campo) {
            if (campo.frm_campo == 'AS_FECHA_FALLECIMIENTO' && document.getElementById("_COD_PROCESO").value == 'RMIN') {
                this.es_fallecido = true;
                this.listarRequisitosTitular();
            }
            if (campo.frm_campo == 'AS_BUSCAR') {
                this.es_fallecido = false;
                setTimeout(() => {/*this.tramitesCurso();*/this.listarRequisitosTitular();
                    //this.getJubilacion();
                    this.getCasosLegal();
                }, 2250);
            }
            /*if(campo.frm_campo=='EMITIR_CONTRATO'){
                console.log("campos: ", campo);
                this.mostrarContrato();
            }*/
            if (campo.frm_campo == 'SOL_PARENTESCO') {

                this.listarRequisitosSolicitante();
            }

            if (campo.frm_campo == 'VERIFICAR DERECHOHABIENTES') {
                //this.verificarDerechoHabientes();
                console.log('campo',campo);
                console.log('esMilitarPdf', this.esMilitarPdf);
                console.log('VERIFICAR DERECHOHABIENTES', this.registro.cas_data_valores.length);
                for (var i = 0; i < this.registro.cas_data_valores.length; i++) {
                    if (this.registro.cas_data_valores[i].frm_campo == 'AS_MILITAR') {
                        console.log('VERIFICAR DERECHOHABIENTES', this.registro.cas_data_valores[i].frm_campo);
                        if (this.registro.cas_data_valores[i].frm_value == 'ASEGURADO MILITAR') {
                            this.esMilitarPdf = true;
                        }
                    }
                }
                this.verificaVejez();
            }

            if (campo.frm_campo == 'VERIFICAR COMTRATO PAGCC') {
                //this.verificarDerechoHabientes();
                this.verificarContratoPagosCc();
            }

            if (campo.frm_campo == 'VERIFICAR COMTRATO MAHER') {
                //this.verificarDerechoHabientes();
                this.verificarContratoMaher();
            }

            if (campo.frm_campo == 'VERIFICAR COMTRATO RMIN') {
                //this.verificarDerechoHabientes();
                this.verificarContratoRetirosMinimos();
            }

            if (campo.frm_campo == 'CONFIRMAR_DERECHOHABIENTE') {
                this.procesarEleccionDerechohabiente();
            }
            if (campo.frm_campo == 'BTN_ACEPTACION_V_RM_RF') {
                this.vejezCNU();
            }
            if (campo.frm_campo == 'REC_BUSCAR') {
                ///window.location.reload();
                this.getDocumentos();
            }
            if (campo.frm_campo == 'REC_BUSCAR_RMIN') {
                ///window.location.reload();
                this.getDocumentosRmin();
            }
            if (campo.frm_campo == 'AS_BUSCAR') {
                ///window.location.reload();
                this.validarDatosMasaHereditaria();
            }

            if (campo.frm_campo == 'CALCULOS_SEMI_AUTOMATICOS_TRAMITES_PROCESADOS') {
                if(this.TIPO_PROCESO == 'JUB' && this.cas_act_id == 53){
                    console.log("la actividad >>> ", this.cas_act_id);
                    axios.get('api/calculosSemiAutomaticos', {
                        params: {
                            numeroContrato: this.correlativo_aps,
                            cuaAsegurado: this.registro.cas_data.AS_CUA,
                            usuario: this.usrUser,
                            numeroTramite: this.cas_cod_id // para fines de log
                        }
                    }).then(response => {
                            if (response.data.codigoRespuesta === 200) {
                                const respuesta = response.data.data;

                                this.resultados = [
                                        { concepto: "1. Capital Necesario Unitario para Pago de Pensión (CNUPP)", valorVejez: respuesta?.cnupp, valorSolidario: respuesta?.cnupp, valorSolidarioIbero: '' },
                                        { concepto: "2. Capital Necesario Unitario para Gastos Funerarios (CNUGF)", valorVejez: respuesta?.cnugf, valorSolidario: respuesta?.cnugf, valorSolidarioIbero:'' },
                                        { concepto: "3. CNUGF A SER FINANCIADO (Bs)", valorVejez: respuesta?.cnugf_bs, valorSolidario: respuesta?.cnugf_bs, valorSolidarioIbero:'' },
                                        { concepto: "4. CNUGF a ser financiado (Coutas)", valorVejez: respuesta?.cnugf_cuotas , valorSolidario: respuesta?.cnugf_cuotas, valorSolidarioIbero:'' },
                                        { concepto: "5. Previsión Gastos Funerarios (PREGF)", valorVejez: respuesta?.prev_cnugf, valorSolidario: respuesta?.prev_cnugf, valorSolidarioIbero:'' },
                                        { concepto: "6. Número de Unidades de Vejez", valorVejez: respuesta?.uv, valorSolidario: respuesta?.uv, valorSolidarioIbero:'' },
                                        { concepto: "7. Fracción de Saldo Acumulado (FSA)", valorVejez: respuesta?.fsa, valorSolidario: respuesta?.fsa, valorSolidarioIbero:'' },
                                        { concepto: "8. Pensión Base Referencial (PBR)", valorVejez: 180, valorSolidario: 280, valorSolidarioIbero:''},
                                        { concepto: respuesta?.tipo_asegurado == "MILITAR" ? "9. Fracción Complementaria" : "9. Fracción Solidaria", valorVejez: '-', valorSolidario: '-' , valorSolidarioIbero:''},
                                        { concepto: "10. Pensión total", valorVejez: '', valorSolidario: '', valorSolidarioIbero:''},
                                        { concepto: "11. Pensión Solidaria de Vejez Teórica", valorVejez: '', valorSolidario: '', valorSolidarioIbero:''},
                                        { concepto: "12. Pensión Solidaria de Vejez Real", valorVejez: '', valorSolidario: '' , valorSolidarioIbero:''},
                                        { concepto: "13. Resultado verificación", valorVejez: '', valorSolidario: '', valorSolidarioIbero:''},
                                        { concepto: "14. Resultado Final",
                                            valorVejez: respuesta?.tipo_contrato == "RECHAZO" ? "RECHAZADO" : respuesta?.tipo_pvs == "VEJEZ" ? respuesta?.tipo_pvs : "NO CORRESPONDE",
                                            valorSolidario: respuesta?.tipo_contrato == "RECHAZO" ? "RECHAZADO" : respuesta?.tipo_pvs == "SOLIDARIA" ? respuesta?.tipo_pvs : "NO CORRESPONDE", valorSolidarioIbero:''
                                        }
                                ]

                                console.log("EL VALOR DE LA DATA >> ", this.registro.cas_data.DATA_CALCULOS);

                                axios.put('api/guardarDatosServicioSemiAutomaticos', {
                                    resultados: this.resultados,
                                    cas_nro_caso: this.registro.cas_data.cas_nro_caso,
                                    data_calculos: this.registro.cas_data.DATA_CALCULOS || null
                                })
                                .then(response => {
                                    if (response.data.codigoRespuesta === 200) {
                                        console.log('enviado correctamente:', response.data);

                                        var url = "api/caso/" + this.cas_id;
                                        return axios.get(url);
                                    } else if (response.data.codigoRespuesta === 201) {
                                        console.log('ya fue registrado anteriormente:', response.data);

                                        var url = "api/caso/" + this.cas_id;
                                        return axios.get(url);
                                    } else {
                                        throw new Error('error en respuesta del servidor: ' + response.data.mensaje);
                                    }
                                })
                                .then(response => {
                                    this.registro = response.data.data[0];
                                    this.estado_derivacion_val_semiAutomaticos.disabled = false;

                                    if (typeof this.registro.cas_data === 'string') {
                                        try {
                                            this.registro.cas_data = JSON.parse(this.registro.cas_data);
                                        } catch (error) {
                                            console.error('Error al convertir cas_data a un objeto:', error);
                                            console.warn('cas_data no es un objeto válido:', this.registro.cas_data);
                                            this.registro.cas_data = {};
                                        }
                                    }

                                    Vue.set(this.registro, 'prc_data', typeof this.registro.prc_data === 'string' ? JSON.parse(this.registro.prc_data || '{}') : this.registro.prc_data || {});
                                    Vue.set(this.registro, 'prc_data_campos_clave', typeof this.registro.prc_data_campos_clave === 'string' ? JSON.parse(this.registro.prc_data_campos_clave || '{}') : this.registro.prc_data_campos_clave || {});
                                    Vue.set(this.registro, 'act_data', typeof this.registro.act_data === 'string' ? JSON.parse(this.registro.act_data || '{}') : this.registro.act_data || {});
                                    Vue.set(this.registro, 'cas_data_valores', typeof this.registro.cas_data_valores === 'string' ? JSON.parse(this.registro.cas_data_valores || '{}') : this.registro.cas_data_valores || {});
                                    Vue.set(this.registro, 'frm_data', typeof this.registro.frm_data === 'string' ? JSON.parse(this.registro.frm_data || '{}') : this.registro.frm_data || {});

                                    console.log('datos actualizados despues de guardar:', this.registro.cas_data);
                                    console.log("nuevo valor en DATA_CALCULOS:", this.registro.cas_data.DATA_CALCULOS);

                                    this.render();

                                    this.mostrarOverlay();
                                    setTimeout(() => {
                                        this.render();
                                        this.ocultarOverlay();
                                    }, 500);

                                })
                                .catch(error => {
                                    Swal.fire({
                                        position: "center",
                                        icon: "error",
                                        title: "Error al guardar los datos de calculos",
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    console.error('Error al enviar los datos:', error);
                                });

                                $('#modalcalculosSemiAutomaticos').modal('show');
                            } else if (response.data.codigoRespuesta === 201) {
                                Swal.fire({
                                    title: response.data.data,
                                    icon: 'warning',
                                    confirmButtonText: 'Aceptar'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error al consultar datos de calculos',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        }).catch(error => {
                            Swal.fire('Error al consumir el servicio de calculos', 'error');
                        });
                    }
            }

            //**********LEGAL */
            // if (campo.frm_campo == 'TIENE_PODER_SOL_1') {
            //     if(document.getElementById("TIENE_PODER_SOL_1").value=="1"){
            //       const ci = document.getElementById("SOL_CI").value;
            // 		const id_persona_sip = document.getElementById("SOL_IDPERSONA").value;
            // 		const caso = this.cas_cod_id;
            // 		const id_caso = this.cas_id;
            // 		const  prc_id = this.prc_id;
            // 		console.log('registro=>', this.registro, ci, id_persona_sip, caso, id_caso);
            // 		console.log('registro proceso =>', this.proceso);
            // 		console.log('registro prc_id =>', this.prc_id);
            // 		this.$refs.legalComponent.getPrestaciones(ci, id_persona_sip, caso, id_caso, prc_id);
            //     }
            else if (campo.frm_campo == 'GENERAR_CONTRATO_INV') { //***************CONTRATO DE INV Y PM */original_1
                this.invContrato();
            } else if (campo.frm_campo == 'GENERAR_CONTRATO_PM') { //***************CONTRATO DE INV Y PM */original_1
                this.pmContrato();
            } else if (campo.frm_campo == 'SOL_ACTUALIZAR') { //***************CONTRATO DE INV Y PM */original_1
               // this.jub1185ActulizarContrato();
            } else if (campo.frm_campo == 'VER_CONTRATO') { //***************CONTRATO DE INV Y PM */original_1
                this.jub1185VerContrato();
            }else if (campo.frm_campo == 'VER_RECHAZO') { //***************CONTRATO DE INV Y PM */original_1
                console.log("🚀 ~ this.registro VER_RECHAZO:", this.registro.cas_data.AS_CUA)
                $('#modalPrevisualizar').modal('show');
                    this.pdfSrc = document.getElementById("RECHAZO").value;
                    this.renderPDF(this.pdfSrc, 'pdfCanvas');
            }
            // }
        },
        //*******************LEY 1185 */
        async jub1185ActulizarContrato() {
            // Definir apiUrl y payload correctamente
            const apiUrl = "/api/actualizarDatosContratoJUB";
            const payload = {
                cas_id: this.cas_id,
                cua:this.registro.cas_data.AS_CUA//"32345513"//"32358453" //"31747139"
            };
            try {
                this.mostrarOverlay(); // Mostrar overlay (si se usa)
                const response = await axios.post(apiUrl, payload, {
                    headers: {
                        "Content-Type": "application/json", // Asegura el tipo de contenido
                    },
                });
                console.log("Respuesta del servidor:", response.data);
                if (response.data.codigoRespuesta == '200') {
                    this.onEstadoChange(response.data.estado);
                    alert("Datos enviados correctamente.");
                    this.ocultarOverlay();
                }
            } catch (error) {
                this.ocultarOverlay(); // Ocultar overlay (si se usa)
                // Manejar errores
                console.error("Error al enviar los datos:", error.response?.data || error.message);
                //alert("Ocurrió un error al enviar los datos.");
            }
        }, //this.registro

        onEstadoChange(valor) {
            document.getElementById("ESTADO_DERIVACION").value = valor;
            if (valor.trim() === "RECHAZADO" || valor.trim() === "") {
                  _hide("VER_CONTRATO_idd");
                  _hide("VER_RECHAZO_idd");
                  _show("ADVERTENCIA_CALCULO_idd");
            }else if (valor.trim() === "ACCEDE") {
                  _show("VER_CONTRATO_idd");
                  _hide("VER_RECHAZO_idd");
                  _hide("ADVERTENCIA_CALCULO_idd");
            }else if (valor.trim() === "NO ACCEDE") {
                  _hide("VER_CONTRATO_idd");
                  _show("VER_RECHAZO_idd");
                  _hide("ADVERTENCIA_CALCULO_idd");
            }
            else  {}
        }
        ,
        async jub1185VerContrato() {
            console.log("🚀 ~ this.registro:", this.registro)
            const apiUrl = "/api/generatepdf"; // URL de la API
            const payload = {
                act_id: this.registro.cas_act_id,
                cas_id: this.registro.cas_id,
                act_usr_id: this.registro.cas_usr_id,
                impid: this.registro.impid,//
                nombre_doc: "ADENDA LEY 1582/2024",
                tipo: "Dibujar",
                firma: "" // Si no hay firma, dejar vacío o se puede modificar según el caso
            };

            try {
                this.mostrarOverlay(); // Mostrar el overlay si se está utilizando
                const response = await axios.post(apiUrl, payload, {
                    headers: {
                        "Content-Type": "application/json", // Asegura que el tipo de contenido es JSON
                    },
                    responseType: 'json', // Asegura que recibimos un objeto JSON (puede cambiar si esperas un blob)
                });
                console.log("Respuesta del servidor:", response.data);
                console.log("Respuesta del servidor:", response.data.data);
                // Si el PDF se devuelve como archivo binario o URL de descarga
                if (response.data.codigoRespuesta.code == 200) {
                    this.ocultarOverlay();
                    $('#modalPrevisualizar').modal('show');
                    this.pdfSrc = response.data.data;
                    this.renderPDF(this.pdfSrc, 'pdfCanvas');
                }
                else {
                    Swal.fire(response.data.mensaje, '', 'warning');
                }
            } catch (error) {
                this.ocultarOverlay(); // Ocultar overlay en caso de error
                console.error("Error al generar el PDF:", error.response?.data || error.message);
                alert("Ocurrió un error al generar el PDF.");
            }
        }
        ,
        //*******************FIN LEY 1185 */
        mostrarContrato() {

            var idSol = this.registro.cas_data.ID_SOLICITUDPRESTACION;
            var url = this.urlGestoraSgg + '/otorgamiento-prestaciones-calculos/api/v1/reporte/contrato?idSeguimientoTramite=' + idSol + '&usuMod=' + this.usrUser + '@gestora.bo';
            //var url = 'https://sgg.test.gestora.bo/otorgamiento-prestaciones-calculos/api/v1/reporte/contrato?idSeguimientoTramite='+idSol+'&usuMod='+this.usrUser+'@gestora.bo';
            var AS_DIRECCION, AS_CORREO, AS_CELULAR;
            if (this.registro.cas_data.AS_DIRECCION == null || this.registro.cas_data.AS_DIRECCION == undefined) AS_DIRECCION = '';
            if (this.registro.cas_data.AS_CORREO == null || this.registro.cas_data.AS_CORREO == undefined) AS_CORREO = '';
            if (this.registro.cas_data.AS_CELULAR == null || this.registro.cas_data.AS_CELULAR == undefined) AS_CELULAR = '';
            var datos = {
                // "direccionAsegurado": AS_DIRECCION,
                // "correoAsegurado": AS_CORREO,
                // "telefonoAsegurado": AS_CELULAR,
                // "regional":  this.registro.cas_data.cas_regional
                "direccionAsegurado": "Villa Puntazo",
                "correoAsegurado": "correoAsegurado@gmail.com",
                "telefonoAsegurado": "62345678",
                "regional": "LA PAZ"
            };
            axios.post(url, datos)
                .then(response => {
                    if (response.data.codigo == '0') {
                        $('#modalPrevisualizar').modal('show');
                        this.pdfSrc = response.data.data;
                               this.renderPDF(this.pdfSrc, 'pdfCanvas');
                               $('#modalPrevisualizar').modal('show');
                    }
                    else {
                        Swal.fire(response.data.mensaje, '', 'warning');
                    }
                })
                .catch(error => {
                    console.error('Error al generar al listado', error);
                });
        },


        adicionarAdjunto() {
            this.items.push({ RespaldoFile: "", RespaldoDesc: "" });
        },

        adicionarAdjuntoMedico() {
            this.medicoitems.push({ RespaldoFile: "", RespaldoDesc: "" });
        },

        removeCampo(index) {
            this.items.splice(index, 1);
        },

        removeCampoMedicos(index) {
            this.medicoitems.splice(index, 1);
        },

        borrarCampo(index) {
            this.items.splice(index, 1);
        },

        borrarCampoMedicos(index) {
            this.medicoitems.splice(index, 1);
        },

        validarExtensiones(file) {
            // Implemente a lógica de validação de extensões aqui
        },

        async uploadFile() {
            this.mostrarOverlay();
            const tabla = document.getElementById("tabla_dinamica");
            const filas = tabla.querySelectorAll("tr");
            const tam = filas.length - 1;
            const ci = document.getElementById("AS_CI").value;
            const id_persona_sip = document.getElementById("AS_IDPERSONA").value;
            let allUploadPromises = [];

            for (var i = 0; i < tam; i++) {
                var id_referencia = "RespaldoDesc_" + i;
                let switch_adj = "switch_adj_" + i;
                const referencia = document.getElementById(id_referencia).value;
                const fileInput = document.getElementById("RespaldoFile_" + i);
                const file = fileInput.files[0];
                const formData = new FormData();
                const switch_elemento = document.querySelector(`#${switch_adj}`);
                const valor_switch = switch_elemento.checked;
                formData.append("tam", i);
                formData.append("valor_id", i);
                formData.append("valor_descripcion", referencia);
                formData.append("file", file);
                formData.append("caso", this.cas_cod_id);
                formData.append("id_caso", this.cas_id);
                formData.append("ci", ci);
                formData.append("id_persona_sip", id_persona_sip);
                /* formData.append("parentesco", 'ADJUNTO_MEDICOS'); */
                formData.append("parentesco", 'ADJUNTOS');
                formData.append("switch", valor_switch);
                formData.append("usr_id", this.usrId);
                allUploadPromises.push(
                    new Promise(async (resolve, reject) => {
                        try {
                            await axios.post("/api/guardarDocumentosAdjuntosNfs", formData)
                                .then(response => {
                                    resolve(response); // Resolve the promise on successful upload
                                });
                        } catch (error) {
                            console.error("Error al subir el archivo:", error);
                            alert("Error al subir el adjunto.");
                        }
                    })
                );
            }

            Promise.all(allUploadPromises)
                .then(() => {
                    this.ocultarOverlay();
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
                    console.error("Error during uploads:", error);
                    alert("Error al subir los adjuntos.");
                });
        },

        async uploadFileMedicos() {
            this.mostrarOverlay();
            const tabla = document.getElementById("tabla_dinamicaMedicos");
            const filas = tabla.querySelectorAll("tr");
            const tam = filas.length - 1;
            const ci = document.getElementById("AS_CI").value;
            const id_persona_sip = document.getElementById("AS_IDPERSONA").value;
            let allUploadPromises = [];

            for (var i = 0; i < tam; i++) {
                var id_referencia = "RespaldoDescMedicos_" + i;
                let switch_adj_medicos = "switch_adj_medicos_" + i;
                const referencia = document.getElementById(id_referencia).value;
                const fileInput = document.getElementById("RespaldoFileMedicos_" + i);
                const file = fileInput.files[0];
                const formData = new FormData();
                const switch_elemento = document.querySelector(`#${switch_adj_medicos}`);
                const valor_switch = switch_elemento.checked;
                formData.append("tam", i);
                formData.append("valor_id", i);
                formData.append("valor_descripcion", referencia);
                formData.append("file", file);
                formData.append("caso", this.cas_cod_id);
                formData.append("id_caso", this.cas_id);
                formData.append("ci", ci);
                formData.append("id_persona_sip", id_persona_sip);
                formData.append("parentesco", 'ADJUNTO_MEDICOS');
                formData.append("switch", valor_switch);
                formData.append("usr_id", this.usrId);
                allUploadPromises.push(
                    new Promise(async (resolve, reject) => {
                        try {
                            await axios.post("/api/guardarDocumentosAdjuntosNfsMedicos", formData)
                                .then(response => {
                                    resolve(response); // Resolve the promise on successful upload
                                });
                        } catch (error) {
                            console.error("Error al subir el archivo:", error);
                            alert("Error al subir el adjunto.");
                        }
                    })
                );
            }

            Promise.all(allUploadPromises)
                .then(() => {
                    this.ocultarOverlay();
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
                    console.error("Error during uploads:", error);
                    alert("Error al subir los adjuntos.");
                });
        },

        limpiarDocumentoTitular(rdoc_id, rdoc_categoria) {
            console.log(rdoc_id, '-', rdoc_categoria);
            Swal.fire({
                title: '¿Estás seguro de limpiar ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    const datos = {
                        caso: this.cas_cod_id,
                        id_caso: this.cas_id,
                        parentesco: this.codigoTipoParentesco,
                        doc_id: rdoc_id,
                    };

                    axios.post('api/limpiarAdjunto', datos)
                        .then(response => {
                            console.log('respuesta', response.data.codigoRespuesta.code);
                            if (response.data.codigoRespuesta.code == '200') {
                                const data = {
                                    pdoc_codigo: this.cas_cod_id,
                                    pdoc_referencia: '0-TIT',
                                    pdoc_categoria: rdoc_categoria,
                                };
                                console.log('datos del  data data ', data);
                                axios.post('api/obtenerDocumentosRequisitos', data)
                                    .then(response => {
                                        console.log('datos del requisito ', response);
                                        this.documentosTitular = response.data.data;
                                        axios.post('api/obtenerObservacion')
                                            .then(response => {
                                                this.observacion = response.data.data;
                                            })
                                    })
                                    .catch(error => {
                                        console.error('Error al generar o abrir el PDF', error);
                                    });
                                this.ocultarOverlay();
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Documento Limpiado Correctamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            } else {
                                this.ocultarOverlay();
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: " NO se  Limpiado el Documento Correctamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error al generar o abrir el PDF', error);
                        });
                    console.log('verdad ');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.close();
                }
            });
        },

        limpiarDocumentoSolicitante(rdoc_id, rdoc_categoria) {
            console.log(rdoc_id, '-', rdoc_categoria);
            Swal.fire({
                title: '¿Estás seguro de limpiar ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    const datos = {
                        caso: this.cas_cod_id,
                        id_caso: this.cas_id,
                        parentesco: this.codigoTipoParentesco,
                        doc_id: rdoc_id,
                    };
                    axios.post('api/limpiarAdjunto', datos)
                        .then(response => {
                            console.log('respuesta', response.data.codigoRespuesta.code);
                            if (response.data.codigoRespuesta.code == '200') {
                                const data = {
                                    pdoc_codigo: this.cas_cod_id,
                                    pdoc_referencia: '0-SOL',
                                    pdoc_categoria: rdoc_categoria,
                                };
                                console.log('datos del  data ', data);
                                axios.post('api/obtenerDocumentosRequisitos', data)
                                    .then(response => {
                                        console.log('datos del requisito ', response);
                                        this.documentosSolicitante = response.data.data;
                                        axios.post('api/obtenerObservacion')
                                            .then(response => {
                                                this.observacion = response.data.data;
                                            })
                                    })
                                    .catch(error => {
                                        console.error('Error al generar o abrir el PDF', error);
                                    });
                                this.ocultarOverlay();
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Documento Limpiado Correctamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            } else {
                                this.ocultarOverlay();
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: " NO se Limpió el Documento Correctamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error al generar o abrir el PDF', error);
                        });
                    console.log('verdad ');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.close();
                }
            });
        },

        limpiarDocumento(rdoc_id, rdoc_categoria) {
            console.log(rdoc_id, '-', rdoc_categoria);
            Swal.fire({
                title: '¿Estás seguro de limpiar ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    const datos = {
                        caso: this.cas_cod_id,
                        id_caso: this.cas_id,
                        parentesco: this.codigoTipoParentesco,
                        doc_id: rdoc_id,
                    };
                    axios.post('api/limpiarAdjunto', datos)
                        .then(response => {
                            console.log('respuesta', response.data.codigoRespuesta.code);
                            if (response.data.codigoRespuesta.code == '200') {
                                const data = {
                                    pdoc_codigo: this.cas_cod_id,
                                    pdoc_referencia: '0-DHABIENTE',
                                    pdoc_categoria: rdoc_categoria,
                                };
                                console.log('datos del  data ', data);
                                axios.post('api/obtenerDocumentosRequisitos', data)
                                    .then(response => {
                                        console.log('datos del requisito ', response);
                                        this.documento = response.data.data;
                                        axios.post('api/obtenerObservacion')
                                            .then(response => {
                                                this.observacion = response.data.data;
                                            })
                                    })
                                    .catch(error => {
                                        console.error('Error al generar o abrir el PDF', error);
                                    });
                                this.ocultarOverlay();
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Documento Limpiado Correctamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    $('#modalDocumentos').modal('hide');
                                    setTimeout(() => { }, 500)
                                });
                            } else {
                                this.ocultarOverlay();
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: " NO se Limpió el Documento Correctamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error al generar o abrir el PDF', error);
                        });
                    console.log('verdad ');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.close();
                }
            });
        },

        limpiarDocumentoAdjunto(doc_id_adjunto, h) {
            Swal.fire({
                title: '¿Estás seguro de limpiar ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.mostrarOverlay();
                    let doc_id_adj = { doc_id_adjunto: doc_id_adjunto };
                    axios.post('api/limpiarDocumentoAdjunto', doc_id_adj)
                        .then(response => {
                            if (response.data.codigoRespuesta.code == '200') {
                                this.doDocumentoPdfAdjunto();
                                this.ocultarOverlay();
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Documento Limpiado Correctamente",
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        })
                }
            })
        },

        limpiarDocumentoMedicoAdjunto(doc_id_adjunto, h) {
            Swal.fire({
                title: '¿Estás seguro de limpiar ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.mostrarOverlay();
                    let doc_id_adj = { doc_id_adjunto: doc_id_adjunto };
                    axios.post('api/limpiarDocumentoAdjunto', doc_id_adj)
                        .then(response => {
                            if (response.data.codigoRespuesta.code == '200') {
                                this.doDocumentoPdfAdjuntoMedico();
                                this.ocultarOverlay();
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Documento Medico Limpiado Correctamente",
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        })
                }
            })
        },

        tamanoDocumento(event) {
            const file = event.target.files[0];
            const maxSizeInBytes = 10485760;
            if (file.size > maxSizeInBytes) {
                Swal.fire('El archivo seleccionado supera el tamaño máximo de 10 MB.', '', 'warning');
                event.target.value = '';
            } else {
                // El archivo cumple con el tamaño máximo permitido
            }
            if (file.type !== 'application/pdf') {
                Swal.fire('Por favor, selecciona solo archivos PDF.', '', 'warning');
                event.target.value = '';
            }
        },

        verDocumento: function (ruta, nombre) {

            var url = "/api/verDocumentoPdfNfs/"  + ruta + '?usuario=' + this.usrUser + '@gestora.bo&pro=atender_caso';
            const partes = nombre.split('.');
            const partes2 = nombre.split('/');
            console.log('nombre', nombre);
            axios.get(url, { responseType: 'blob' })
                .then(response => {
                    if (partes[1] == 'pdf') {
                        const documento = response.data;
                        const url = window.URL.createObjectURL(documento);
                        const windowProps = 'top=0,left=0,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=650';
                        const newWindow = window.open(url, '_blank', windowProps);
                        newWindow.document.body.innerHTML = `<iframe src="${url}" width="100%" height="100%"></iframe>`;
                    } else if (nombre == '') {
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

        listarRequisitosTitular() {
            const ci = document.getElementById("AS_CI").value;
            const data = {
                pdoc_codigo: this.cas_cod_id,
                pdoc_referencia: '0-TIT',
                pdoc_categoria: ci,
            };
            axios.post('api/existeDocumentosRequisitos', data)
                .then(response => {
                    console.log('existeDocumentosRequisitos 1', response.data.codigoRespuesta.code);
                    let AS_TIPO_EAP = document.getElementById("AS_TIPO_EAP").value;
                    if (response.data.codigoRespuesta.code == 200) {
                        const data = {
                            pdoc_codigo: this.cas_cod_id,
                            pdoc_referencia: '0-TIT',
                            pdoc_categoria: ci,
                        };
                        axios.post('api/obtenerDocumentosRequisitos', data)
                            .then(response => {
                                this.documentosTitular = response.data.data;
                                axios.post('api/obtenerObservacion')
                                    .then(response => {
                                        this.observacion = response.data.data;
                                    })
                            })
                            .catch(error => {
                                console.error('Error al generar o abrir el PDF', error);
                            });
                    } else {
                        const _COD_PROCESO = document.getElementById("_COD_PROCESO").value;
                        var a = document.getElementById('AS_API_ESTADO').value;
                        var est = 'V';
                        if (a == 'FALLECIDO') {
                            est = 'M';
                        }
                        if (_COD_PROCESO == 'MAHER' || _COD_PROCESO == 'PM' || _COD_PROCESO == 'GFU') {
                            est = 'M';
                        }
                        if (AS_TIPO_EAP == 'CVEAP-B2' && _COD_PROCESO == 'PAGCC') {
                            est = 'M';
                        }
                        if (_COD_PROCESO == 'RMIN' && this.es_fallecido) {
                            est = 'M';
                        }
                        //var url = this.urlGestora + "/spr-tram-rest/clasificador/documentosSolicitudPrestacionPorTipoPrestacionOrigenEstadoTitularParentesco?codigoTipoPrestacion=" + _COD_PROCESO + "&estadoTitular=" + est + "&codigoTipoParentesco=TIT&estadoInvalidez=false"
                        var url = this.urlGestora + "/spr-tram-rest/clasificador/documentosSolicitudPrestacionPorTipoPrestacionOrigenEstadoTitularParentesco?codigoTipoPrestacion=" + _COD_PROCESO + "&estadoTitular=" + est + "&codigoTipoParentesco=TIT&estadoInvalidez=false&estadoDefuncion=V";
                        if (_COD_PROCESO == 'PM' || _COD_PROCESO == 'MAHER') this.listarRequisitosSolicitante();

                        if (_COD_PROCESO == 'JUB1582') {
                            console.log("CVEAP-B9  ");
                            this.documentosTitular = [
                                {
                                    "id": 1,
                                    "descripcionTipoDocumentoRespaldo": "Cedula de Identidad ",
                                    "documentoOriginalObligatorio": false,
                                    "presentacionObligatoria": true,
                                    "obs_id_observacion": 1,
                                }
                            ]
                        } else {
                            console.log("DIFERENTE CVEAP-B9  ");
                            axios.get(url).then(response => {
                                const nuevoDocumento = {
                                    "id": 6,
                                    "descripcionTipoDocumentoRespaldo": "Documento del militar",
                                    "documentoOriginalObligatorio": false,
                                    "presentacionObligatoria": true,
                                    "obs_id_observacion": 1,
                                };
                                let AS_MILITAR  ;
                               if( _COD_PROCESO == 'JUB'){
                                    AS_MILITAR = document.getElementById("AS_MILITAR") ? document.getElementById("AS_MILITAR").value : null;
                                    if (!AS_MILITAR) {
                                            console.log('AS_MILITAR ===> 1', AS_MILITAR);
                                    } else {
                                        console.log("AS_MILITAR ===> 2", AS_MILITAR);
                                    if (AS_MILITAR == 'ASEGURADO MILITAR') {
                                            console.log('es militar y aumentar la documentacion');
                                            response.data.data.push(nuevoDocumento);
                                        }
                                    }
                               }
                                this.documentosTitular = response.data.data;
                                response.data.data.forEach(doc => {
                                    doc.obs_id_observacion = 1;
                                });
                                axios.post('api/obtenerObservacion')
                                    .then(response => {
                                        this.observacion = response.data.data;
                                    });
                                console.log("documentoTitular", this.documentosTitular);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al generar o abrir el PDF', error);
                });
        },

        listarRequisitosSolicitante() {
            var valor = document.getElementById("SOL_DIFERENTE_AS").checked;
            if (document.getElementById("FECHA_SUPERA_6")) {
                this.fecha_supera_6 = document.getElementById("FECHA_SUPERA_6").value;
                if (this.fecha_supera_6 == '') {
                    this.fecha_supera_6 = 'false';
                }
            }
            if (!valor) {
                this.swSolicitante = true;
                const ci = document.getElementById("AS_CI").value;
                const data = {
                    pdoc_codigo: this.cas_cod_id,
                    pdoc_referencia: '0-SOL',
                    pdoc_categoria: ci,
                };
                axios.post('api/existeDocumentosRequisitos', data)
                    .then(response => {
                        console.log('existeDocumentosRequisitos 2', response.data.codigoRespuesta.code);
                        if (response.data.codigoRespuesta.code == 200) {
                            const data = {
                                pdoc_codigo: this.cas_cod_id,
                                pdoc_referencia: '0-SOL',
                                pdoc_categoria: ci,
                            };
                            axios.post('api/obtenerDocumentosRequisitos', data)
                                .then(response => {
                                    this.documentosSolicitante = response.data.data;
                                })
                                .catch(error => {
                                    console.error('Error al generar o abrir el PDF', error);
                                });
                        } else {
                            const elementoCodProceso = document.getElementById("_COD_PROCESO");
                            let _COD_PROCESO = elementoCodProceso.value;
                            var a = document.getElementById('AS_API_ESTADO').value;
                            var est = '';
                            var parentesco = 'SOL';
                            if (a == 'FALLECIDO') {
                                est = 'M';
                            } else {
                                if (_COD_PROCESO == 'MAHER' || _COD_PROCESO == 'PM' || _COD_PROCESO == 'GFU') {
                                    est = 'M';
                                } else {
                                    est = 'V';
                                }
                            }
                            if (_COD_PROCESO == 'GFU' && this.fecha_supera_6 == 'true') {
                                parentesco = document.getElementById('SOL_PARENTESCO').value;
                            }
                            if (_COD_PROCESO == 'JUB1582') {
                                elementoCodProceso.value = 'JUB'; // Aquí modificamos directamente el valor en el DOM
                                _COD_PROCESO = elementoCodProceso.value; // Actualizamos la variable con el nuevo valor
                            }
                            //var url = "https://desa-sipre.gestora.bo/spr-tram-rest/clasificador/documentosSolicitudPrestacionPorEstadoTitularParentesco?codigoTipoPrestacion="+_COD_PROCESO+"&estadoTitular="+est+"&codigoTipoParentesco=SOL";
                            var url = this.urlGestora + "/spr-tram-rest/clasificador/documentosSolicitudPrestacionPorTipoPrestacionOrigenEstadoTitularParentesco?codigoTipoPrestacion=" + _COD_PROCESO + "&estadoTitular=" + est + "&codigoTipoParentesco=" + parentesco + "&estadoInvalidez=false&estadoDefuncion=V";
                            axios.get(url).then(response => {
                                response.data.data.forEach(doc => {
                                    doc.obs_id_observacion = 1;
                                });
                                this.documentosSolicitante = response.data.data;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                    });
            } else {
                this.swSolicitante = false;
                this.documentosSolicitante = [];
            }
        },

        limpiarRequisitos() {
            const tabla = document.getElementById("tabla_requisitos");
            const filas = tabla.querySelectorAll("tr");
            const tam = filas.length - 1;
            for (var i = 0; i < tam; i++) {
                const inputElement = document.getElementById('pdf_' + i);
                if (inputElement) {
                    inputElement.value = null;
                }
            }
        },
        listarRequisitos(datos, index) {
            // $.each(datos, function() {
            //   var key = Object.keys(this)[0];
            //   var value = this[key];
            //   console.log("Llave !!! ",key , "Valor !!!", value);
            //   if (value == 'DH_PARENTESCO'){
            //     console.log(">>>> encontrado >>> DH_PARENTESCO");
            //   }else if(value == 'DH_INVALIDEZ'){
            //     console.log(">>>> encontrado >>> DH_INVALIDEZ");
            //   } else {
            //     console.log("ES OTRO VALOR >>> ",value);
            //   }
            // });

            this.limpiarRequisitos();
            const _COD_PROCESO = document.getElementById("_COD_PROCESO").value;
            let tam = 0;
            let tamInv = 0;
            let DH_FECHA_FALLECIDO = 'V';
            if (_COD_PROCESO == 'JUB') {
                tam = datos.length - 5; // PARENTESCO
                tamInv = datos.length - 6; // INVALIDO
                if (datos[0].col_value == '1') {
                    DH_FECHA_FALLECIDO = 'M';
                };
                const _ES_DH_FALLECIDO = document.getElementById("ES_DH_FALLECIDO"+index).value;

                const _DH_FECHA_FALLECIDO = document.getElementById("DH_FECHA_FALLECIDO"+index).value;

                if (_ES_DH_FALLECIDO === '1' && !_DH_FECHA_FALLECIDO) {
                    Swal.fire({
                        title: 'Advertencia!',
                        text: 'Debe llenar el campo de fecha de fallecimiento.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
            } else if (_COD_PROCESO == 'RMIN') {
                tam = datos.length - 4; // PARENTESCO
                tamInv = datos.length - 5; // INVALIDO
            }
            else if (_COD_PROCESO == 'MAHER') {
                //tam = datos.length - 4; // MAHER_v2
                //tamInv = datos.length - 5; // INVALIDO
                tam = datos.length - 3; // PARENTESCO
                tamInv = datos.length - 4; // INVALIDO
            } else {
                tam = datos.length - 3; // PARENTESCO
                tamInv = datos.length - 4; // INVALIDO
            }

            if (_COD_PROCESO == 'MAHER' && datos[0].col_value == '1') {
                DH_FECHA_FALLECIDO = 'M';
            }

            var valor = datos[tam].col_value;
            var valorInv = datos[tamInv].col_value;

            if (valorInv == null || valorInv == '') {
                valorInv = false;
                //Swal.fire('Debe ingresar el Parentesco', '', 'warning');
            }
            if ((valor == null || valor == undefined || valor == 'undefined' || valor == '')) {
                valor = '';
                Swal.fire({
                    title: 'Debe ingresar los datos de Parentesco y Estado de Invalidez',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
            }
            this.codigoTipoParentesco = valor;

            let valores = valor.split("-");
            const valor2 = valores[1];
            console.log("VALORES >> ", valores);
            this.index = index;
            const ci_ = 'DH_CI_GRILLA_PROP' + index;
            const ci = document.getElementById(ci_).value;
            const data = {
                pdoc_codigo: this.cas_cod_id,
                pdoc_referencia: valor,
                pdoc_categoria: ci,
                //documentos_: url2
            };

            axios.post('api/existeDocumentosRequisitos', data)
                .then(response => {
                    console.log('existeDocumentosRequisitos 3', response.data.codigoRespuesta.code);
                    if (response.data.codigoRespuesta.code == 200) {
                        const data = {
                            pdoc_codigo: this.cas_cod_id,
                            pdoc_referencia: valor,
                            pdoc_categoria: ci,
                        };
                        axios.post('api/obtenerDocumentosRequisitos', data)
                            .then(response => {
                                this.documento = response.data.data;
                            })
                            .catch(error => {
                                console.error('Error al generar o abrir el PDF', error);
                            });
                    } else {
                        var a = document.getElementById('AS_API_ESTADO').value;
                        var est = '';
                        if (a == 'FALLECIDO') {
                            est = 'M';
                        }
                        else {
                            if (_COD_PROCESO == 'MAHER' || _COD_PROCESO == 'PM' || _COD_PROCESO == 'GFU') {
                                est = 'M';
                            }
                            else {
                                est = 'V';
                            }
                        }
                        if (_COD_PROCESO == 'MAHER') valorInv = 'false';
                        //var url = "https://desa-sipre.gestora.bo/spr-tram-rest/clasificador/documentosSolicitudPrestacionPorEstadoTitularParentesco?codigoTipoPrestacion="+_COD_PROCESO+"&estadoTitular="+est+"&codigoTipoParentesco=" + valor2;
                        var url = this.urlGestora + "/spr-tram-rest/clasificador/documentosSolicitudPrestacionPorTipoPrestacionOrigenEstadoTitularParentesco?codigoTipoPrestacion=" + _COD_PROCESO + "&estadoTitular=" + est + "&codigoTipoParentesco=" + valor2 + "&estadoInvalidez=" + valorInv + "&estadoDefuncion=" + DH_FECHA_FALLECIDO;
                        if (_COD_PROCESO == 'GFU') {
                            this.documento = [
                                {
                                    "id": 237,
                                    "descripcionTipoDocumentoRespaldo": "Cedula de Identidad ",
                                    "documentoOriginalObligatorio": false,
                                    "presentacionObligatoria": true,
                                    "obs_id_observacion": 1,
                                }
                            ]
                        } else {
                            axios.get(url).then(response => {
                                response.data.data.forEach(doc => {
                                    doc.obs_id_observacion = 1;
                                });
                                this.documento = response.data.data;
                            });
                            //this.limpiarTabla();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al generar o abrir el PDF', error);
                });
        },

        render() {
            var url = "api/caso/" + this.cas_id;
            axios.get(url).then(response => {
                this.registro = response.data.data[0]; //twice data
                this.registro.prc_data = JSON.parse(this.registro.prc_data);
                this.registro.prc_data_campos_clave = JSON.parse(this.registro.prc_data_campos_clave);
                this.registro.act_data = JSON.parse(this.registro.act_data);
                this.registro.cas_data = JSON.parse(this.registro.cas_data);
                this.registro.cas_data_valores = JSON.parse(this.registro.cas_data_valores);
                this.registro.frm_data = JSON.parse(this.registro.frm_data);
                //this.registro.frm_data_campos = JSON.parse(this.registro.frm_data_campos);

                if (this.registro.cas_data.TIPO_PROCESO == 'JUB') {
                    this.$nextTick(() => {
                        this.estado_derivacion_val_semiAutomaticos = document.getElementById("ESTADO_DERIVACION");

                        if(this.registro.cas_data.DATA_CALCULOS == 1 && this.actividad.act_orden == 65 && this.TIPO_PROCESO=='JUB'){
                            console.log("ingresa por true << DATA_CALCULOS >> igual a 1 y act_orden igual a 65");
                            this.estado_derivacion_val_semiAutomaticos.disabled = false;
                        } else if (this.estado_derivacion_val_semiAutomaticos && this.actividad.act_orden == 65) {
                            this.estado_derivacion_val_semiAutomaticos.disabled = true;
                            console.log("POR EL ELSE");
                        }
                    });
                }

                this.proceso = this.registro.prc_data;
                this.codProceso = this.proceso.prc_codigo;
                this.proceso_campos_clave = this.registro.prc_data_campos_clave;
                this.actividad = this.registro.act_data;
                this.caso = this.registro.cas_data;
                this.prc_id = this.registro.prc_id;

                this.cas_cod_id = this.registro.cas_cod_id;
                this.cas_act_id = this.registro.cas_act_id;
                this.cas_act_orden = this.actividad.act_orden;
                this.correlativo_aps = this.registro.cas_correlativo;

                this.cas_departamento = this.caso.cas_departamento;
                this.ESTADO_DERIVACION = this.caso.ESTADO_DERIVACION;
                this.DESCRIPCION_DERIVACION = this.caso.DESCRIPCION_DERIVACION;
                this.id_cas_departamento = this.caso.id_cas_departamento;
                this.cas_agencia = this.caso.cas_agencia;
                this.id_cas_agencia = this.caso.id_cas_agencia;
                this.id_cas_regional = this.caso.id_cas_regional;
                this.cas_regional = this.caso.cas_regional;
                this.TIPO_PROCESO = this.caso.TIPO_PROCESO;
                this.cas_registrado = this.caso.cas_registrado;
                if(this.TIPO_PROCESO=='JUB1582' && this.cas_act_id==249){
                    this.jub1185ActulizarContrato();
                }
                this.asignarCampos();
                // revisa y busca campos tipo SELECT
                this.campos.forEach(function (row) {
                    if (row.frm_tipo == 'SELECT') {
                        url = row.frm_endpoint;
                        let acumulado = [];
                        axios.get(url).then(response => {
                            let items = response.data.data; //twice data
                            items.forEach(function (fila) {
                                var filita = Object.values(fila);
                                acumulado.push({ "frm_value": filita[0], "frm_etiqueta": filita[1] });
                            });
                            row.frm_items = acumulado;
                        });
                    }
                });

                // Adiciona Constantes de PLATAFORMA
                var d = new Date(this.registro.cas_registrado);
                var mm = d.getMonth() + 1;
                this.campos.push({
                    "frm_value": d.getFullYear() + '-' + mm.toString().padStart(2, '0') + '-' + d.getDate().toString().padStart(2, '0'),
                    "frm_tipo": "HIDDEN", "frm_campo": "_FECHA", "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0'),
                    "frm_tipo": "HIDDEN", "frm_campo": "_HORA", "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": d.getFullYear(), "frm_tipo": "HIDDEN", "frm_campo": "_CASO_GESTION",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": (d.getMonth() + 1).toString().padStart(2, '0'), "frm_tipo": "HIDDEN", "frm_campo": "_CASO_PERIODO",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.cas_id, "frm_tipo": "HIDDEN", "frm_campo": "_CASO_NRO",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                console.log("mmm5:  ");
                this.campos.push({
                    "frm_value": this.cas_cod_id, "frm_tipo": "HIDDEN", "frm_campo": "_CASO_NOMBRE",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.cas_act_id, "frm_tipo": "HIDDEN", "frm_campo": "_CASO_ACT_ID",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.cas_act_orden, "frm_tipo": "HIDDEN", "frm_campo": "_CASO_ACT_ORDEN",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                //
                this.campos.push({
                    "frm_value": this.usrId, "frm_tipo": "HIDDEN", "frm_campo": "_USR_ID",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.usrName, "frm_tipo": "HIDDEN", "frm_campo": "_USR_NOMBRE",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.codProceso, "frm_tipo": "HIDDEN", "frm_campo": "_COD_PROCESO",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.cas_departamento, "frm_tipo": "HIDDEN", "frm_campo": "_DEPARTAMENTO",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.cas_regional, "frm_tipo": "HIDDEN", "frm_campo": "_REGIONAL",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.cas_agencia, "frm_tipo": "HIDDEN", "frm_campo": "_AGENCIA",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.correlativo_aps, "frm_tipo": "HIDDEN", "frm_campo": "_APS_CORRELATIVO",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.caso.NOMBRE_PROCESO, "frm_tipo": "HIDDEN", "frm_campo": "_NOMBRE_PROCESO",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.caso.NOMBRE_PROCESO, "frm_tipo": "HIDDEN", "frm_campo": "_NOMBRE_PROCESO",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.id_cas_departamento, "frm_tipo": "HIDDEN", "frm_campo": "_ID_DEPARTAMENTO",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.id_cas_regional, "frm_tipo": "HIDDEN", "frm_campo": "_ID_REGIONAL",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                this.campos.push({
                    "frm_value": this.id_cas_agencia, "frm_tipo": "HIDDEN", "frm_campo": "_ID_AGENCIA",
                    "frm_etiqueta": "", "frm_deshabilitadoo": false
                });
                // solo para ver los campos en tiempo de ejecución
                this.camposTexto = JSON.stringify(this.campos, null, 4);
            });
            setTimeout(() => {
                this.listarRequisitosTitular();
                this.listarRequisitosSolicitante();
            }, 1000);
        },

        asignarValores() {
            this.urls = [];
            var i = 0;
            this.campos.forEach(campo => {
                //copia estados DISABLED de componentes
                var url = {};
                if (campo.frm_tipo == "DOCUMENT") {
                    console.log("campo: ", campo);
                    url = {
                        "frm_campo": campo.frm_campo,
                        "frm_id_campo": campo.frm_id_campo,
                        "frm_etiqueta": campo.frm_etiqueta
                    }
                    this.urls[i] = url;
                    i++
                }
                var elemento = document.getElementById(campo.frm_campo);
                var atributoDisabled = "";
                if (elemento) {
                    atributoDisabled = elemento.getAttribute('disabled');
                    if (atributoDisabled == null) {
                        campo.frm_deshabilitado = "false";
                        campo.frm_deshabilitadoo = false;
                    } else {
                        campo.frm_deshabilitado = "true";
                        campo.frm_deshabilitadoo = true;
                    }
                }

                var dRes = ""
                if (campo.frm_tipo == "DROPDOWNLIST") {
                    var dIndex = campo.frm_items.findIndex(dItem => dItem.frm_value === campo.frm_value)
                    if (dIndex > -1) {
                        dRes = campo.frm_items[dIndex]["frm_etiqueta"];
                    }
                    if (dRes === "") {
                        const dropdown = document.getElementById(campo.frm_campo);
                        if (dropdown.selectedIndex !== -1) {
                            const selectedOption = dropdown.options[dropdown.selectedIndex];
                            dRes = selectedOption.text;
                        }
                    }
                }

                // colecta campos, valores y "estado de deshabilitado"
                var iIndex = this.registro.cas_data_valores.findIndex(item => item.frm_campo === campo.frm_campo);
                if (iIndex > -1) {
                    this.registro.cas_data_valores[iIndex].frm_value = (typeof campo.frm_value === 'string' && campo.frm_value.includes("'")) ? campo.frm_value.replace(/'/g, "''") : campo.frm_value;//campo.frm_value;
                    this.registro.cas_data_valores[iIndex].frm_deshabilitado = atributoDisabled == null ? "false" : "true";
                    this.registro.cas_data_valores[iIndex].frm_deshabilitadoo = atributoDisabled == null ? false : true;
                    if (campo.frm_tipo == "DROPDOWNLIST") {
                        this.registro.cas_data_valores[iIndex].frm_value_label = dRes;
                    }
                } else {
                    var campito = {};
                    if (campo.frm_tipo == "DROPDOWNLIST") {
                        campito = {
                            "frm_campo": campo.frm_campo,
                            "frm_value": (typeof campo.frm_value === 'string' && campo.frm_value.includes("'")) ? campo.frm_value.replace(/'/g, "''") : campo.frm_value,//campo.frm_value,
                            "frm_deshabilitado": atributoDisabled == null ? "false" : "true",
                            "frm_deshabilitadoo": atributoDisabled == null ? false : true,
                            "frm_value_label": dRes,
                            "frm_tipo": campo.frm_tipo
                        };
                    } else {
                        campito = {
                            "frm_campo": campo.frm_campo,
                            "frm_value": (typeof campo.frm_value === 'string' && campo.frm_value.includes("'")) ? campo.frm_value.replace(/'/g, "''") : campo.frm_value,//campo.frm_value,
                            "frm_tipo": campo.frm_tipo,
                            "frm_deshabilitado": atributoDisabled == null ? "false" : "true",
                            "frm_deshabilitadoo": atributoDisabled == null ? false : true
                        };
                    }
                    this.registro.cas_data_valores.push(campito);
                }
            });
        },

        guardarCasoyVolver(option) {
            this.asignarValores();
            // obtiene información de CAMPOS CLAVE
            var campos_clave = '';
            let a = 0;
            this.proceso_campos_clave.forEach(row => {
                var res = this.registro.cas_data_valores.find(item => item.frm_campo === row.prc_campo_clave);
                if (res) {
                    if(res.frm_value == null || res.frm_value == ''){
                        res.frm_value='';
                    }
                    if (res.frm_tipo == 'DROPDOWNLIST') {
                        if (a < 5) {
                            campos_clave += '|' + res.frm_value;
                            a++;
                        }
                        var d = res.frm_campo;
                        this.caso[d] = res.frm_value_label;
                    } else {
                        if (a < 5) {
                            campos_clave += '|' + res.frm_value;
                            a++;
                        }
                        var d = res.frm_campo;
                        this.caso[d] = res.frm_value;
                    }
                }
            });
            // recolecta y actualiza datos del caso y campos
            this.caso.cas_nombre_caso = campos_clave.substring(1);
            this.caso.cas_nro_caso = this.cas_id;				////
            this.caso.cas_cod_id = this.cas_cod_id;
            let valores = this.caso.cas_nombre_caso.split("|");
            var gRegistro = this.registro;
            gRegistro.cas_data = this.caso;
            gRegistro.cas_usr_id = this.usrId;
            var url = "api/casos/" + this.cas_id;
            axios.put(url, gRegistro).then(response => {
                this.$router.push('/misCasos');
            });
        },

        async actualizarCaso(option) {
            // if (this.actividad.act_orden == 50 && document.getElementById("_COD_PROCESO").value == 'MAHER') {
            // 	const CVEAP_FIRMADO_ID = document.getElementById("CVEAP_FIRMADO_ID").value;
            // 	const VALIDACION_LEGAL_ID = document.getElementById("VALIDACION_LEGAL_ID").value;
            // 	if (CVEAP_FIRMADO_ID === null || CVEAP_FIRMADO_ID.trim() === ""
            // 		|| VALIDACION_LEGAL_ID === null || VALIDACION_LEGAL_ID.trim() === "") {
            // 		Swal.fire({
            // 			title: 'Advertencia!',
            // 			text: 'RESPUESTA ASEGURADO: Debe subir los archivos de respaldo.',
            // 			icon: 'error',
            // 			confirmButtonText: 'OK',
            // 		});
            // 		return;
            // 	}
            // }
            let promiseTitular, promiseSolicitante;
            const tabla = document.getElementById("tabla_dinamica");
            const filas = tabla.querySelectorAll("tr");
            const tam = filas.length - 1;
            const fileInput = document.getElementById("RespaldoFile_0");
            //const fileInput = document.getElementById("RespaldoFileMedicos_0");
            const file = fileInput.files[0];
            let continuar = 0;
            if (file == undefined) {
                continuar = 1;
            } else {
                if (this.bandera == 1) {
                    continuar = 1;
                } else {
                    Swal.fire({
                        title: 'Por favor, guarde el documento que seleccionó primero.',
                        text: '',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }
            if (continuar == 1) {
                this.mostrarOverlay();
                if (this.actividad.act_orden == 20 || this.actividad.act_orden == 100 || this.actividad.act_orden == 50 || this.actividad.act_orden == 47 || this.actividad.act_orden == 49) {
                    promiseSolicitante = this.registarArchivoSolicitante();
                    promiseTitular = this.registarArchivoTitular();
                    this.caso.ESTADO_DERIVACION = 'CERRADO';
                }
                //si pasa de la actividad 30 se adiciona el campo usuario_supervisor al json cas_data
                if (this.actividad.act_orden == 30) {
                    this.caso.USUARIO_SUPERVISOR = this.usrUser;
                }
                //si pasa de la actividad 30 se adiciona el campo usuario_supervisor al json cas_data
                if (this.actividad.act_orden == 61 || this.actividad.act_orden == 65) {
                    this.caso.USUARIO_CALCULADOR = this.usrUser;
                }
                this.asignarValores();
                // obtiene información de CAMPOS CLAVE
                var campos_clave = '';
                let a = 0;
                this.proceso_campos_clave.forEach(row => {
                    var res = this.registro.cas_data_valores.find(item => item.frm_campo === row.prc_campo_clave);
                    if (res) {
                        if(res.frm_value == null || res.frm_value == ''){
                            res.frm_value='';
                        }
                        if (res.frm_tipo == 'DROPDOWNLIST') {
                            if (a < 5) {
                                campos_clave += '|' + res.frm_value;
                                a++;
                            }
                            var d = res.frm_campo;
                            this.caso[d] = res.frm_value_label;
                        } else {
                            if (a < 5) {
                                campos_clave += '|' + res.frm_value;
                                a++;
                            }
                            var d = res.frm_campo;
                            this.caso[d] = res.frm_value;
                        }
                    }
                });
                // recolecta y actualiza datos del caso y campos
                this.caso.cas_nombre_caso = campos_clave.substring(1);
                this.caso.cas_nro_caso = this.cas_id;				////
                this.caso.cas_cod_id = this.cas_cod_id;
                //console.log("this.caso",this.caso);
                let valores = this.caso.cas_nombre_caso.split("|");
                var gRegistro = this.registro;
                gRegistro.cas_data = this.caso;
                gRegistro.cas_usr_id = this.usrId;
                //Elimina las filas vacias de la grilla de derechohabientes
                const objetoEncontrado = gRegistro.cas_data_valores.find(item => item.frm_campo === "GRILLA_DERECHOHABIENTES");
                if (objetoEncontrado && Array.isArray(objetoEncontrado.frm_value)) {
                    for (let i = 0; i < objetoEncontrado.frm_value.length; i++) {
                        const fila = objetoEncontrado.frm_value[i];
                        for (let j = 0; j < fila.length; j++) {
                            const objeto = fila[j];
                            if (objeto.col_campo === "DH_IDPERSONA_GRILLA_PROP" && (objeto.col_value === null || objeto.col_value === "")) {
                                objetoEncontrado.frm_value.splice(i, 1);
                                i--; // Ajustar el índice después de eliminar una fila
                                break;
                            }
                        }
                    }
                } else {
                    console.warn("El objeto GRILLA_DERECHOHABIENTES no se encontró o no tiene un frm_value válido.");
                }
                var url = "api/casos/" + this.cas_id;
                const promiseDOCUMENTComponent = this.registarArchivoRespaldo();
                const promiseActualizarCasoFormularioDinamico = axios.put(url, gRegistro);
                Promise.all([promiseTitular, promiseSolicitante, promiseDOCUMENTComponent, promiseActualizarCasoFormularioDinamico])
                    .then(([responseTitular, responseSolicitante,responseDOCUMENTComponent, responseActualizarCasoFormularioDinamico])=>{
                        this.ocultarOverlay();
                        Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Tus Documentos Fueron Guardados",
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                this.$router.push('/misCasos');
                            });
                    })
                    .catch(error =>{
                        this.ocultarOverlay();
                        Swal.fire('Error al guardar la informacion, intentelo de nuevo.', '', 'error');
                    });
            }
        },
        BuscarTutor() {
            var tipoDocumento = document.getElementById("ci_tutor") ? document.getElementById("ci_tutor").value : '';
            var requestData = {
                "tipoDocumento": 'I',
                "numeroDocumento": tipoDocumento,
                "complemento": '',
                "fechaNacimiento": ''
            };
            axios.post('https://pruebas.gestora.bo/servicios/cenpersonas/api/v1/personasip/buscaPersonaRegistroDirectoSip', requestData, {
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(function (response) {
                    console.log(response.data.data);
                    document.getElementById("nombre_").value = response.data.data.primerNombre + ' ' + response.data.data.segundoNombre;
                    document.getElementById("primer_apellido").value = response.data.data.primerApellido;
                    document.getElementById("segundo_apellido").value = response.data.data.segundoApellido;
                    document.getElementById("fecha_nacimiento").value = response.data.data.fechaNacimiento;
                    document.getElementById("apellido_casada").value = response.data.data.apellidoCasada;
                    if (response.data.data.idGenero === 'M') {
                        document.getElementById("genero").value = 'Masculino';
                    } else {
                        document.getElementById("genero").value = 'Femenino';
                    }
                    document.getElementById("direccion").value = response.data.data.direccion;
                })
                .catch(function (error) {
                    console.error(error);
                });
        },
        guardarTutor() {
            this.tutor.nombres = document.getElementById("nombre_").value;
            this.tutor.primerApellido = document.getElementById("primer_apellido").value;
            this.tutor.segundoApellido = document.getElementById("segundo_apellido").value;
            this.tutor.apellidoCasada = document.getElementById("apellido_casada").value;
            this.tutor.fechaNacimiento = document.getElementById("fecha_nacimiento").value;
            this.tutor.genero = document.getElementById("genero").value;
            this.tutor.direccion = document.getElementById("direccion").value;
            this.tutor.nroCelular = document.getElementById("nro_celular").value;
            this.tutor.numero = document.getElementById("numero").value;
            this.tutor.correo = document.getElementById("correo").value;
            this.tutor.parentesco = document.getElementById("parentesco").value;
            const id_persona_sip = document.getElementById('id_persona_sip').value;
            const numero_documento = document.getElementById('numero_documento').value;
            const datos = { tutor: JSON.stringify(this.tutor), cas_cod_id: this.cas_cod_id, numero_documento: numero_documento, id_persona_sip: id_persona_sip, tut_cas_id_: this.cas_id };
            axios.post('api/grabarTutor', datos)
                .then(response => {
                    Swal.fire('Tutor Guardado ', 'success');
                    this.$swal({
                        title: '<b>Tutor Guardado </b>',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    })
                })
                .catch(error => {
                    console.error('Error al guardar', error);
                });
        },

        geTutor(datos, rowIndex) {
            let act_nodo = this.registro.act_data.act_orden;
            const valores = Object.values(datos);
            if (this.registro.act_data.act_orden != 20) {
                var numeroDocumento = document.getElementById("DH_CI_GRILLA_PROP" + rowIndex) ? document.getElementById("DH_CI_GRILLA_PROP" + rowIndex).value : '';
                var id_prsona_sip = document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex) ? document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).value : '';
                document.getElementById("id_persona_sip").value = id_prsona_sip;
                document.getElementById("numero_documento").value = numeroDocumento;
                const datos = { cas_cod_id: this.cas_cod_id, numero_documento: numeroDocumento, id_persona_sip: id_prsona_sip, tut_cas_id_: this.cas_id };
                axios.post('api/getTutor', datos)
                    .then(response => {
                        if (response.data.codigoRespuesta.code == '200') {
                            const tut_data = JSON.parse(response.data.data.tut_data);
                            document.getElementById("nombre_").value = tut_data.nombres;
                            document.getElementById("nombre_").disabled = true;
                            document.getElementById("primer_apellido").value = tut_data.primerApellido;
                            document.getElementById("primer_apellido").disabled = true;
                            document.getElementById("segundo_apellido").value = tut_data.segundoApellido;
                            document.getElementById("segundo_apellido").disabled = true;
                            document.getElementById("apellido_casada").value = tut_data.apellidoCasada;
                            document.getElementById("apellido_casada").disabled = true;
                            document.getElementById("fecha_nacimiento").value = tut_data.fechaNacimiento;
                            document.getElementById("fecha_nacimiento").disabled = true;
                            document.getElementById("genero").value = tut_data.genero;
                            document.getElementById("genero").disabled = true;
                            document.getElementById("direccion").value = tut_data.direccion;
                            document.getElementById("direccion").disabled = true;
                            document.getElementById("nro_celular").value = tut_data.nroCelular;
                            document.getElementById("nro_celular").disabled = true;
                            document.getElementById("numero").value = tut_data.nroCelular;
                            document.getElementById("numero").disabled = true;
                            document.getElementById("correo").value = tut_data.numero;
                            document.getElementById("correo").disabled = true;
                            document.getElementById("parentesco").value = tut_data.parentesco;
                            document.getElementById("parentesco").disabled = true;
                        } else {
                            Swal.fire('Error al Eliminar Este Derechohabiente', '', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                    });
            } else {
                document.getElementById("id_persona_sip").value = valores[3].col_value;
                document.getElementById("numero_documento").value = valores[4].col_value;
            }
        },
        enmienda() {
            Swal.fire({
                title: '¿Estás seguro de realizar la derivación por enmienda?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                onBeforeOpen: () => {
                    Swal.showLoading();  // Mostrar el indicador de carga
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    //*******************INCIO */
                    this.asignarValores();
                    // obtiene información de CAMPOS CLAVE
                    var campos_clave = '';
                    let a = 0;
                    this.proceso_campos_clave.forEach(row => {
                        var res = this.registro.cas_data_valores.find(item => item.frm_campo === row.prc_campo_clave);
                        if (res) {
                            if(res.frm_value == null || res.frm_value == ''){
                                res.frm_value='';
                            }
                            if (res.frm_tipo == 'DROPDOWNLIST') {
                                if (a < 5) {
                                    campos_clave += '|' + res.frm_value;
                                    a++;
                                }
                                var d = res.frm_campo;
                                this.caso[d] = res.frm_value_label;
                            } else {
                                if (a < 5) {
                                    campos_clave += '|' + res.frm_value;
                                    a++;
                                }
                                var d = res.frm_campo;
                                this.caso[d] = res.frm_value;
                            }
                        }
                    });
                    // recolecta y actualiza datos del caso y campos
                    this.caso.cas_nombre_caso = campos_clave.substring(1);
                    this.caso.cas_nro_caso = this.cas_id;				////
                    this.caso.cas_cod_id = this.cas_cod_id;
                    ////
                    var gRegistro = this.registro;
                    gRegistro.cas_data = this.caso;
                    gRegistro.cas_usr_id = this.usrId;
                    ///	gRegistro.cas_descripcion_archivo = this.archivo.cas_descripcion_archivo;
                    ///////console.log(gRegistro);
                    var url = "api/casos/" + this.cas_id;
                    axios.put(url, gRegistro).then(response => {
                        setTimeout(() => {
                            this.setenmienda();
                        }, 5000);
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Lógica a ejecutar si el usuario selecciona 'No' o cancela
                    Swal.close();  // Cerrar la alerta de carga
                    //Swal.fire('Acción cancelada', '', 'info');
                }
            });
        },

        setenmienda() {
            const data = {
                cas_id: this.cas_id,
                cas_usr_id: this.usrId,
                cas_estado: 'T',
                cas_observacion: this.archivo.cas_descripcion_archivo,
            };
            console.log(data);
            axios.post('api/enmienda', data)
                .then(response => {
                    Swal.fire('El caso fue derivado a: ', response.data.data[0].vrespuesta, 'success');
                    this.$router.push('/misCasos');
                })
                .catch(error => {
                    console.error('Error al generar o abrir el PDF', error);
                });
        },

        verCampos() {
            document.getElementById("misCampos").innerHTML = this.camposTexto;
        },

        doDefinirClass(campo) {
            let res = ''
            if (campo.frm_tipo == 'TITLE' || campo.frm_tipo == 'SUBTITLE' || campo.frm_tipo == 'GRID' || campo.frm_tipo == 'GRID_1582' ) {
                res = 'col-md-12';
            } else {
                res = campo.frm_class;
            }
            return res;
        },

        /*isNumber: function(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        },*/
        isNumber: function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            var fieldId = evt.target.id;
            fieldId = fieldId.replace(/[0-9]+$/, '');
            if (fieldId === "DH_GRADO") {
                if (charCode < 48 || charCode > 57) {
                    evt.preventDefault();
                } else {
                    var value = parseInt(evt.target.value + String.fromCharCode(charCode));
                    if (value < 0 || value > 60) {
                        evt.preventDefault();
                    }
                }
            } else {
                if (fieldId.includes('DH_CI')) {
                    if (!((charCode >= 48 && charCode <= 57) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122)) && charCode !== 46) {
                        evt.preventDefault();
                    }
                } else {
                    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                        evt.preventDefault();
                    }
                }
            }
        },

        validarEntradaText: function (evt) {
            const key = evt.key;
            const campoId = evt.target.id;

            // if ((evt.ctrlKey || evt.metaKey) && key === 'v') {
            //     console.log('CTRL + V'); // Detecta CTRL + V y previene la acción por defecto
            //     evt.preventDefault();
            // } else if (!['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Backspace'].includes(key)) {
                if (campoId.includes('CORREO')) {
                    const regex = /[a-zA-Z0-9\s\-.@_ñÑ]/;

                    if (!regex.test(key)) {
                        evt.preventDefault();
                    }
                } else {
                    const regex = /[a-zA-Z0-9\s\-\/áéíóúÁÉÍÓÚäëïöüÄËÏÖÜñÑ]/;
                    if (!regex.test(key)) {
                        evt.preventDefault();
                    }
                }
            //}
        },

        ejecutar(cod, rowIndex) {
            if (rowIndex) {
                eval(cod)(rowIndex);
            } else {
                eval(cod);
            }
            return true;
        },

        loadError(oError) {
            throw new URIError("The script " + oError.target.src + " didn't load correctly.");
        },
        //'	 //this.campos.find(item => item.frm_campo === "' + campo + '")' +
        addScript(jsCode) { },
        addScript_v1(codigoScriptFormulario) {
            // codigo core
            var codigoScriptCore = `
					// Behavior
					function _show(campo) {
						document.getElementById(campo).style.display="block";
					}
                    function _funcion_prueba(campo) {

						console.log('como estas funcion_prueba');
                        ///this.render();
                        console.log('como estas funcion_prueba ===>>> 2');
					}
					function _hide(campo) {
						document.getElementById(campo).style.display="none";
					}

					function _enable(campo) {
						document.getElementById(campo).disabled = false;
						//document.getElementById(campo).toggleAttribute("disabled", false);
					}

					function _disable(campo) {
						document.getElementById(campo).disabled = true;
						//document.getElementById(campo).toggleAttribute("disabled", true);
					}

					// Value setValue/getValue
					function _setValue(campo, value) {
						document.getElementById(campo).value = value;
					}

					function _getValue(campo) {
						return document.getElementById(campo).value;
					}

					// Get Pointer
					function _get(campo) {
						return (document.getElementById(campo));
					}

					function _required(campo) {
						return document.querySelector('#'+campo).required = true;
					}

					function _notRequired(campo) {
						return document.querySelector('#'+campo).required = false;
					}

					// Set Style
					function _setStyle(clase, estilo) {
						const campos = document.querySelectorAll(clase);
						campos.forEach(campo => {
							campo.setAttribute("style", estilo);
						});
					}

					function validarFirma(a){
						const fileInput = document.getElementById(a);
						if(fileInput.files && fileInput.files.length > 0) {
								const file = fileInput.files[0];
								const reader = new FileReader();
								reader.onload = (event) => {
								const base64String = event.target.result;
								const indexOfComma = base64String.indexOf(',');
								const cleanBase64String = indexOfComma > -1 ? base64String.slice(indexOfComma + 1) : base64String;
										$.ajax({
										dataType: 'json',
										contentType: 'application/json',
										url:'https://localhost:9000/api/validar_pdf',
										type: 'POST',
										data: JSON.stringify({
											pdf: cleanBase64String
										}),
										success: function(data){
											if (data.datos.firmas.length == 0 ) {
											alert('INCORRECTO, ARCHIVO NO CUENTA CON FIRMA DIGITAL');
											fileInput.value='';
											}
										}
										});
								};
								reader.readAsDataURL(file);
							}
						}
				`;
            // codigo formulario
            var newScript = document.createElement("script");
            newScript.onerror = this.loadError;
            document.head.appendChild(newScript);
            newScript.text = codigoScriptCore + codigoScriptFormulario;
        },

        verImagen: function (ruta) {
            var url = "/api/verDocumentoPdfNfsRuta";
            const partes = ruta.split('.');
            const partes2 = ruta.split('/');
            let data = { ruta: ruta };
            axios.post(url, data, { responseType: 'blob' })
                .then(response => {
                    console.log('response.dataVerImagen', response.data);
                    if (partes[1] == 'pdf') {
                        const documento = response.data;
                        console.log('documento', documento);
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
                        link.setAttribute('download', partes2[6]);
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                })
                .catch(error => {
                    console.error('Error al mostrar el documento:', error);
                });
        },

        getDocumentos() {
            const CASO_RECHAZADO = document.getElementById("CASO_RECHAZADO").value;
            if (CASO_RECHAZADO != '') {
                const AS_TIPO_EAP = document.getElementById("AS_TIPO_EAP").value;
                if (AS_TIPO_EAP == 'CVEAP-B1') {
                    const CASO_RECHAZADO4 = document.getElementById("CASO_RECHAZADO").value;
                    const divisiones = CASO_RECHAZADO4.split("/");
                    if (divisiones[0] == 'JUB') {
                        console.log('documentos===========================================================>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>', CASO_RECHAZADO, this.cas_cod_id);
                        const datos4 = { cas_cod: CASO_RECHAZADO4, caso_nombre: this.cas_cod_id };
                        axios.post('api/ValidarDatosJubPcc', datos4)
                            .then(response => {
                                if (response.data.data[0].r_val_codigo == 'VALIDO' && (response.data.data[0].r_codigo == 'RC4' || response.data.data[0].r_codigo == 'RC1' || response.data.data[0].r_codigo == 'RC13')) {
                                    const CASO_RECHAZADO1 = document.getElementById("CASO_RECHAZADO").value;
                                    const cua = response.data.data[0].r_cua;
                                    var urlCompezacion = `${this.urlGestoraSgg}/compensacion-cotizacion/api/v1/calculosPrevios/obtenerFechaCargaCC?c=${cua}&user=fernando.flores@gestora.bo`;
                                    console.log('response.data.data[0]', response.data.data[0]);
                                    axios.get(urlCompezacion).then(response1 => {
                                        console.log('response.codigoRespuest', response1.data.codigoRespuesta);
                                        if (response1.data.codigoRespuesta == 200) {
                                            console.log('dasdsad asdas dasd ', response1);
                                            const datos3 = { cas_cod: CASO_RECHAZADO1, caso_nombre: this.cas_cod_id, descripcion_rechazo: response.data.data[0].r_descripcion };
                                            this.$swal({
                                                title: '<b>TRÁMITE VÁLIDO </b>',
                                                html: `
                                                            <p>Con ${response.data.data[0].r_dias} dias ya recorridos,<br><b>Codigo de Rechazo : </b>  ${response.data.data[0].r_descripcion}</p>
                                                            <strong> cuenta con CCM de Fecha :  </strong>  ${response1.data.data.CCM.fechaCargaOfechaEmision} <br>
                                                             ¿Desea continuar?
                                                            `,
                                                icon: 'success',
                                                showCancelButton: true,
                                                confirmButtonText: 'Sí',
                                                cancelButtonText: 'No'
                                            }).then(result => {
                                                if (result.isConfirmed) {
                                                    axios.post('api/datosJubPcc', datos3)
                                                        .then(response2 => {
                                                            window.location.reload();
                                                        });
                                                    this.render();
                                                } else if (result.dismiss === this.$swal.DismissReason.cancel) {
                                                    console.log("El usuario seleccionó 'No'");
                                                }
                                            });
                                        } else {
                                            Swal.fire({
                                                title: 'TRAMÍTE ' + CASO_RECHAZADO4 + ' NO VALIDO',
                                                text: response1.data.data,
                                                icon: 'warning',
                                                confirmButtonText: 'Aceptar'
                                            })
                                        }
                                    });
                                } else if (response.data.data[0].r_estado_deribacion == 'CON NOTA DE RECHAZO') {

                                    Swal.fire({
                                        title: 'TRAMÍTE ' + CASO_RECHAZADO4 + ' NO VALIDO',
                                        html: `
                                    <p>El Tramíte está en orden: ${response.data.data[0].r_act_orden}<br> En estado  ${response.data.data[0].r_descripcion_derivacion}</p>

                                    <strong> Si desea continuar elija un Tipo de Rechazo </strong>

                                    <select id="rejectionType" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                                        <option value="">Selecciona un tipo de rechazo</option>
                                        <option value="RC1">RC1: MENOS DE 120 APORTES CON CCM</option>
                                        <option value="RC4">RC4: CON RETIROS MINIMO Y MAS DE 120 APORTES CON CCM</option>
                                        <option value="RC13">RC13: RECHAZO PARA TRAMITES DE ASEGURADOS FALLECIDOS CON CCM</option>
                                    </select>`,
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Continuar',
                                        cancelButtonText: 'Rechazar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const selectedOption = document.getElementById('rejectionType').value;
                                            if (selectedOption) {
                                                const CASO_RECHAZADO1 = document.getElementById("CASO_RECHAZADO").value;
                                                console.log('documentos===========================================================>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>', CASO_RECHAZADO, this.cas_cod_id);
                                                const datos3 = { cas_cod: CASO_RECHAZADO1, caso_nombre: this.cas_cod_id };
                                                axios.post('api/datosJubPcc', datos3)
                                                    .then(response1 => {
                                                        console.log('datos documentos RRR222 ============================>', response1.data.data[0].r_codigo);
                                                        this.render();
                                                        window.location.reload();
                                                        this.$swal({
                                                            title: '<b>TRAMITE VALIDO juan</b>',
                                                            text: 'Con ' + response1.data.data[0].r_dias + ' dias ya recorridos, Codigo de Rechazo : ' + selectedOption,
                                                            icon: 'success',
                                                            confirmButtonText: 'Aceptar'
                                                        });
                                                    })
                                                    .catch(error => {
                                                        console.error('Error al generar al listado', error);
                                                    });
                                            } else {
                                                Swal.fire({
                                                    title: 'No Procede ',
                                                    text: 'No se seleccionó ninguna opción.',
                                                    icon: 'error',
                                                    confirmButtonText: 'Aceptar'
                                                });
                                            }
                                        } else if (result.isDismissed) {
                                            console.log('El usuario seleccionó Rechazar');
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'TRAMÍTE ' + CASO_RECHAZADO4 + ' NO VÁLIDO PARA INICIO',
                                        text: 'No se identificó la notificación de Rechazo de Jubilación para este trámite ',
                                        icon: 'warning',
                                        confirmButtonText: 'Aceptar'
                                    })
                                }
                            })
                            .catch(error => {
                                console.error('Error al generar al listado', error);
                            });
                    } else if (divisiones[0] == 'INV' || divisiones[0] == 'GFU' || divisiones[0] == 'MAHER' || divisiones[0] == 'PAGCC' || divisiones[0] == 'RMIN' || divisiones[0] == 'PM') {
                        Swal.fire({
                            title: 'TRAMÍTE ' + CASO_RECHAZADO4 + ' NO CORRESPONDE A JUBILACIÓN',
                            html: ` <p> Favor revise   </p> `,
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'
                        })
                    } else {
                        Swal.fire({
                            title: 'NO SE ENCONTRÓ EL TRÁMITE  ' + CASO_RECHAZADO4,
                            html: ` <p> Revise que cuente con un Rechazo de Jubilación que le permita acceder a Pago de CCM </p> `,
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'
                        })
                    }
                } else {
                    const CASO_RECHAZADO4 = document.getElementById("CASO_RECHAZADO").value;
                    const divisiones = CASO_RECHAZADO4.split("/");
                    console.log('divisiones', divisiones);
                    if (divisiones[0] == 'PM') {
                        const datos4 = { cas_cod: CASO_RECHAZADO4, caso_nombre: this.cas_cod_id };
                        axios.post('api/ValidarDatosPmPcc', datos4)
                            .then(response => {
                                const cua = response.data.data[0].r_cua;

                                var urlCompezacion = `${this.urlGestoraSgg}/compensacion-cotizacion/api/v1/calculosPrevios/obtenerFechaCargaCC?c=${cua}&user=fernando.flores@gestora.bo`;
                                console.log('response.data.data[0]', response.data.data[0]);
                                axios.get(urlCompezacion).then(response1 => {
                                    if (response1.data.codigoRespuesta == 200) {
                                        const r_fecha_nacimiento = response.data.data[0].r_fecha_fallecimiento;
                                        const date = new Date(r_fecha_nacimiento);
                                        const today = new Date();
                                        let edad = today.getFullYear() - date.getFullYear();
                                        console.log('edad  ======>>>>>', edad);
                                        if (edad >= 58) {
                                            if (response.data.data[0].r_val_codigo == 'VALIDO') {

                                                this.$swal({
                                                    title: '<b>TRÁMITE ENCONTRADO </b>',
                                                    html: `<p> VERIFIQUE QUE CUENTE CON NOTIFICACIÓN DEL RECHAZO DE PENSIÓN POR MUERTE DERIVADA DE RIESGOS, DE VEJEZ Y SOLIDARIA DE VEJEZ </p>
                                                            ¿Desea continuar?`,
                                                    icon: 'success',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Sí',
                                                    cancelButtonText: 'No'
                                                }).then(result => {
                                                    if (result.isConfirmed) {
                                                        // Acción si el usuario elige "Sí"
                                                        const CASO_RECHAZADO1 = document.getElementById("CASO_RECHAZADO").value;
                                                        const datos3 = { cas_cod: CASO_RECHAZADO1, caso_nombre: this.cas_cod_id, descripcion_rechazo: '' };
                                                        axios.post('api/datosPmPcc', datos3)
                                                            .then(response2 => {
                                                                window.location.reload();
                                                            });
                                                        this.render();
                                                    } else if (result.dismiss === this.$swal.DismissReason.cancel) {
                                                        console.log("El usuario seleccionó 'No'");
                                                    }
                                                });
                                            } else {
                                                this.$swal({
                                                    title: '<b>NO SE ENCONTRÓ EL TRÁMITE </b>',
                                                    html: `<p> Verifique que cuente con rechazo de Pensión por Muerte derivada de Riesgos, de Vejez y solidaria de vejez</p>
                                                                ¿Desea continuar?`,
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Sí',
                                                    cancelButtonText: 'No'
                                                }).then(result => {
                                                    if (result.isConfirmed) {
                                                    } else if (result.dismiss === this.$swal.DismissReason.cancel) {
                                                        console.log("El usuario seleccionó 'No'");
                                                    }
                                                });
                                            }
                                        } else {
                                            Swal.fire({
                                                title: ' EL TRÁMITE  ' + CASO_RECHAZADO4,
                                                html: ` <p> no cuenta con la edad permitida para continuar  </p>
                                                Edad del Asegurado : ${edad}  Años `,
                                                icon: 'warning',
                                                confirmButtonText: 'Aceptar'
                                            })
                                        }
                                    } else {
                                        Swal.fire({
                                            title: 'TRAMÍTE ' + CASO_RECHAZADO4 + ' NO VALIDO',
                                            text: response1.data.data,
                                            icon: 'warning',
                                            confirmButtonText: 'Aceptar'
                                        })
                                    }
                                })
                            })
                            .catch(error => {
                                console.error('Error al generar al listado', error);
                            });
                    } else if (divisiones[0] == 'INV' || divisiones[0] == 'GFU' || divisiones[0] == 'MAHER' || divisiones[0] == 'PAGCC' || divisiones[0] == 'RMIN' || divisiones[0] == 'JUB') {
                        Swal.fire({
                            title: 'TRAMÍTE ' + CASO_RECHAZADO4 + ' NO CORRESPONDE A  PENSIÓN POR MUERTE',
                            html: ` <p> Favor revise   </p> `,
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'
                        })
                    } else {
                        Swal.fire({
                            title: 'NO SE ENCONTRÓ EL TRÁMITE  ' + CASO_RECHAZADO4,
                            html: ` <p> Revise que cuente con un Rechazo de Pensión por Muerte que le permita acceder a Pago de CCM </p> `,
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'
                        })
                    }
                }
            }
        },
        validarDatosMasaHereditaria(){
            console.log('CASO_RECHAZADO  validarDatosMasaHereditaria');
            // setTimeout(() => {
            //     window.location.reload();
            //     this.render();
            // console.log('CASO_RECHAZADO  validarDatosMasaHereditaria 10 despues ');

            // }, 9000);


        },
        getDocumentosRmin() {
            console.log('CASO_RECHAZADO');
            const CASO_RECHAZADO = document.getElementById("CASO_RECHAZADO").value;
            console.log('CASO_RECHAZADO', CASO_RECHAZADO);
            const AS_TIPO_EAP = document.getElementById("AS_TIPO_EAP").value;
            console.log('AS_TIPO_EAP', AS_TIPO_EAP);
            if (CASO_RECHAZADO != '') {
                if (AS_TIPO_EAP == 'CVEAP-B8') {
                    console.log(CASO_RECHAZADO);
                    const CASO_RECHAZADO4 = document.getElementById("CASO_RECHAZADO").value;
                    console.log('CASO_RECHAZADO4', CASO_RECHAZADO4);
                    console.log('this.cas_cod_id', this.cas_cod_id);
                    const divisiones = CASO_RECHAZADO4.split("/");
                    if (divisiones[0] == 'JUB') {
                        console.log('divisiones', divisiones);
                        const datos4 = { cas_cod: CASO_RECHAZADO4, caso_nombre: this.cas_cod_id };
                        console.log('datos4', datos4);
                        axios.post('api/ValidarDatosJubRmin', datos4)
                            .then(response => {
                                if (response.data.data[0].r_val_codigo == 'VALIDO' && (response.data.data[0].r_codigo == 'RC1' || response.data.data[0].r_codigo == 'RC2' || response.data.data[0].r_codigo == 'RC3' || response.data.data[0].r_codigo == 'RC4' || response.data.data[0].r_codigo == 'RC5' || response.data.data[0].r_codigo == 'RC7' || response.data.data[0].r_codigo == 'RC10' || response.data.data[0].r_codigo == 'RC11' || response.data.data[0].r_codigo == 'RC12' || response.data.data[0].r_codigo == 'RC13' || response.data.data[0].r_codigo == 'RC14')) {
                                    const CASO_RECHAZADO1 = document.getElementById("CASO_RECHAZADO").value;
                                    const datos3 = { cas_cod: CASO_RECHAZADO1, caso_nombre: this.cas_cod_id };
                                    axios.post('api/datosJubRmin', datos3)
                                        .then(response1 => {
                                            this.render();
                                            this.$swal({
                                                title: '<b>TRÁMITE VÁLIDO PARA RETIRO MÍNIMO </b>',
                                                html: 'Con ' + response1.data.data[0].r_dias + ' días transcuridos desde la notificación de rechazo, <br> Codigo : ' + response.data.data[0].r_codigo + '<br> Descripcion : ' + response.data.data[0].r_descripcion,
                                                icon: 'success',
                                                confirmButtonText: 'Continuar'
                                            }).then(() => {
                                                window.location.reload();
                                                this.render();
                                            });
                                        })
                                        .catch(error => {
                                            console.error('Error al generar al listado', error);
                                        });
                                } else if (response.data.data[0].r_estado_derivacion == 'CON NOTA DE RECHAZO') {
                                    Swal.fire({
                                        title: 'TRÁMITE ' + CASO_RECHAZADO4 + ' NO VALIDO',
                                        html: `
                                    <p>El Tramíte está en orden: ${response.data.data[0].r_act_orden}<br> En estado  ${response.data.data[0].r_descripcion_derivacion}</p>
                                    <strong> Si desea continuar elija un Tipo de Rechazo </strong>
                                    <select id="rejectionType" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                                        <option value="">Selecciona un tipo de rechazo</option>
                                        <option value="RC1">RC1: MENOS DE 120 APORTES CON CCM</option>
                                        <option value="RC2">RC2: MENOS DE 120 APORTES SIN CCM</option>
                                        <option value="RC3">RC3: MENOS DE 120 APORTES Y CON CCG</option>
                                        <option value="RC4">RC4: CON RETIROS MINIMO Y MAS DE 120 APORTES CON CCM</option>
                                        <option value="RC5">RC5: DEVOLUCION DE APORTES 1392 SIN CC</option>
                                        <option value="RC7">RC7: RECHAZO CON RETIROS EFECTUADOS CON ANTERIORIDAD Y SIN REFERENTE SALARIAL Y SIN CCM</option>
                                        <option value="RC10">RC10: RECHAZO CON RETIROS EFECTUADOS CON ANTERIORIDAD Y SIN REFERENTE SALARIAL Y SIN CCM</option>
                                        <option value="RC11">RC11: RECHAZO CON RETIROS EFECTUADOS CON ANTERIORIDAD Y EN CASO DE REPONER ACCEDE A SOLIDARIA</option>
                                        <option value="RC12">RC12: RECHAZO PARA TRAMITES DE ASEGURADOS FALLECIDOS SIN CC</option>
                                        <option value="RC13">RC13: RECHAZO PARA TRAMITES DE ASEGURADOS FALLECIDOS CON CCM</option>
                                        <option value="RC14">RC14: RECHAZO PARA TRAMITES DE ASEGURADOS FALLECIDOS EN CASO DE REPONER LOS DERECHOHABIENTES ACCEDEN A PSV</option>
                                    </select>`,
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Continuar',
                                        cancelButtonText: 'Rechazar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const selectedOption = document.getElementById('rejectionType').value;
                                            if (selectedOption) {
                                                const CASO_RECHAZADO1 = document.getElementById("CASO_RECHAZADO").value;
                                                console.log('documentos===========================================================>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>', CASO_RECHAZADO, this.cas_cod_id);
                                                const datos3 = { cas_cod: CASO_RECHAZADO1, caso_nombre: this.cas_cod_id };
                                                axios.post('api/datosJubRmin', datos3)
                                                    .then(response1 => {
                                                        console.log('datos documentos RRR222 ============================>', response1.data.codigoRespuesta.code);
                                                        if (response1.data.codigoRespuesta.code == 200) {
                                                            console.log('datos documentos RRR222 ============================>', response1.data.data[0].r_codigo);
                                                            this.render();
                                                            window.location.reload();
                                                            this.$swal({
                                                                title: '<b>TRÁMITE VÁLIDO</b>',
                                                                text: 'Con ' + response1.data.data[0].r_dias + ' dias ya recorridos, Codigo de Rechazo : ' + selectedOption,
                                                                icon: 'success',
                                                                confirmButtonText: 'Aceptar'
                                                            });
                                                        } else {
                                                            this.$swal({
                                                                title: '<b>TRÁMITE NO VÁLIDO</b>',
                                                                text: 'Llene de manera manual los datos requeridos',
                                                                icon: 'warning',
                                                                confirmButtonText: 'Aceptar'
                                                            });
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error al generar al listado', error);
                                                    });
                                            } else {
                                                Swal.fire({
                                                    title: 'No Procede ',
                                                    text: 'No se seleccionó ninguna opción.',
                                                    icon: 'error',
                                                    confirmButtonText: 'Aceptar'
                                                });
                                            }
                                        } else if (result.isDismissed) {
                                            console.log('El usuario seleccionó Rechazar');
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'TRÁMITE ' + CASO_RECHAZADO4 + ' NO VÁLIDO PARA INICIO',
                                        html: 'No se identificó la notificación de Rechazo de Jubilación para este trámite. <br> Favor revise',
                                        icon: 'warning',
                                        confirmButtonText: 'Aceptar'
                                    })
                                }
                            })
                            .catch(error => {
                                console.error('Error al generar al listado', error);
                            });
                    } else if (divisiones[0] == 'INV' || divisiones[0] == 'GFU' || divisiones[0] == 'MAHER' || divisiones[0] == 'PAGCC' || divisiones[0] == 'RMIN' || divisiones[0] == 'PM') {
                        Swal.fire({
                            title: 'TRÁMITE  ' + CASO_RECHAZADO4 + ' NO CORRESPONDE A JUBILACIÓN',
                            html: ` <p> Favor revise   </p> `,
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'
                        })
                    } else {
                        Swal.fire({
                            title: 'NO SE ENCONTRÓ EL TRÁMITE  ' + CASO_RECHAZADO4,
                            html: ` <p> Revise que cuente con un Rechazo de Jubilación que le permita acceder a RM/RF </p> `,
                            icon: 'warning',
                            confirmButtonText: 'Aceptar'
                        })
                    }
                } else if (AS_TIPO_EAP == 'CVEAP-A10') {
                    const CASO_RECHAZADO4 = document.getElementById("CASO_RECHAZADO").value;
                    const datos4 = { cas_cod: CASO_RECHAZADO4, caso_nombre: this.cas_cod_id };
                    axios.post('api/ValidarDatosJubRmin', datos4)
                        .then(response => {
                            const CASO_RECHAZADO1 = document.getElementById("CASO_RECHAZADO").value;
                            console.log('documentos=================>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>', CASO_RECHAZADO, this.cas_cod_id);
                            const datos3 = { cas_cod: CASO_RECHAZADO1, caso_nombre: this.cas_cod_id };
                            const r_fecha_nacimiento = response.data.data[0].r_fecha_nacimiento;
                            const r_fecha_fallecimiento = response.data.data[0].r_fecha_fallecimiento;
                            console.log('fechas de nacieminto y fallecimiento    =================>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
                            console.log(r_fecha_nacimiento, r_fecha_fallecimiento);
                            const date = new Date(r_fecha_nacimiento);
                            const today = new Date();
                            let edad = today.getFullYear() - date.getFullYear();
                            console.log('edad  ======>>>>>', edad);
                            const date1 = new Date(r_fecha_fallecimiento);
                            const today2 = new Date();
                            let muerte = today2.getFullYear() - date1.getFullYear();
                            console.log('falleciemiento  ======>>>>>', muerte);
                            if (edad >= 58) {
                                  this.$swal({
                                    title: '<b>TRÁMITE ENCONTRADO </b>',
                                    html: `<p> VERIFIQUE QUE CUENTE CON NOTIFICACIÓN DEL RECHAZO DE PENSIÓN POR MUERTE DERIVADA DE RIESGOS, DE VEJEZ Y SOLIDARIA DE VEJEZ </p>
                                                            ¿Desea continuar?`,
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí',
                                    cancelButtonText: 'No'
                                }).then(result => {
                                    if (result.isConfirmed) {
                                        // Acción si el usuario elige "Sí"
                                        const CASO_RECHAZADO1 = document.getElementById("CASO_RECHAZADO").value;
                                        const datos3 = { cas_cod: CASO_RECHAZADO1, caso_nombre: this.cas_cod_id, descripcion_rechazo: '' };
                                        axios.post('api/datosRminPm', datos3)
                                            .then(response2 => {
                                                window.location.reload();
                                            });
                                        this.render();
                                    } else if (result.dismiss === this.$swal.DismissReason.cancel) {
                                        console.log("El usuario seleccionó 'No'");
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: ' EL TRÁMITE  ' + CASO_RECHAZADO4,
                                    html: ` <p> no cuenta con la edad permitida para continuar RMIN </p>
                                                Edad del Asegurado : ${edad}  Años `,
                                    icon: 'warning',
                                    confirmButtonText: 'Ok'
                                })
                            }
                        })
                        .catch(error => {
                            console.error('Error al generar al listado', error);
                        });
                }
            } else {
                Swal.fire({
                    title: ' NO CUENTA CON CASO TRAMÍTE ',
                    text: 'Escoja un Tramíte Valido',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                })
            }
        },

        getCasosLegal() {
            const AS_CUA = document.getElementById("AS_CUA").value;
            console.log("AS_CUA: ", AS_CUA);
            const datos4 = {"cua": AS_CUA, "cas_id": this.cas_id};
            axios.post('api/getCasosLegalCua', datos4)
                .then(response => {
                    console.log("response4: ", response.data.data);
                    console.log("response4_tamaño: ", (response.data.data).length);
                    this.resultadosLegal=response.data.data;
                    console.log("this.resultadosLegal.length: ", this.resultadosLegal.length);
                    if (this.resultadosLegal.length){
            const datos5 = {"cas_id": this.cas_id};
            axios.post('api/getCasosLegalTramite', datos5)
                .then(response => {
                    console.log("response4: ", response.data.data);
                    console.log("response4_tamaño: ", (response.data.data).length);
                    this.resultadosLegalTramite=response.data.data;
                            })
                            .catch(error => {
                                console.error('Error al generar al listado', error);
                            });
                    $('#modalLegal').modal('show');
                    }
                })
                .catch(error => {
                    console.error('Error al generar al listado', error);
                });
        },

        getJubilacion() {
            const AS_CUA = document.getElementById("AS_CUA").value;
            const AS_TIPO_EAP = document.getElementById("AS_TIPO_EAP").value;
            const _COD_PROCESO = document.getElementById("_COD_PROCESO").value;
            if (AS_TIPO_EAP == 'CVEAP-B9' && _COD_PROCESO == 'JUB' && AS_CUA != '') {
                const datos4 = { cas_cua: AS_CUA, cas_new_id: this.cas_id };
                const datos5 = { cas_cua: AS_CUA };
                axios.post('api/validarBoleta', datos5)
                    .then(response => {
                        let respuesta = response.data.data;
                        if (respuesta == null) {
                            axios.post('api/duplicarCasoJubilacion', datos4)
                                .then(response => {
                                    if (response.data.data[0].r_codigo == 'Existe') {
                                        this.render();
                                        // this.$swal({
                                        //     title: '<b>TRAMITE RECUPERADO </b>',
                                        //     text: ' ',
                                        //     icon: 'success',
                                        //     confirmButtonText: 'Ok'
                                        // });
                                    } else {
                                        this.$swal({
                                            title: '<b>SIN PAGOS </b>',
                                            text: 'NO CORRESPONDE RECALCULO YA QUE NO CUENTA CON UNA JUBILACIÓN EN CURSO DE PAGO',
                                            icon: 'warning',
                                            confirmButtonText: 'Ok'
                                        });
                                        this.vaciarAsegurado();
                                    }
                                })
                                .catch(error => {
                                    console.error('Error al generar al listado', error);
                                });
                        } else {
                            this.$swal({
                                title: '<b>DATOS DE ULTIMA BOLETA </b>',
                                text: 'PERIODO: ' + this.obtenerPeriodo(respuesta.periodo) + '         ,            ESTADO: ' + respuesta.estado,
                                icon: 'warning',
                                confirmButtonText: 'Ok'
                            });
                            axios.post('api/duplicarCasoJubilacion', datos4)
                                .then(response => {
                                    if (response.data.data[0].r_codigo == 'Existe') {
                                        this.render();
                                        // this.$swal({
                                        //     title: '<b>TRAMITE RECUPERADO </b>',
                                        //     text: ' ',
                                        //     icon: 'success',
                                        //     confirmButtonText: 'Ok'
                                        // });
                                    } else {
                                    }
                                })
                                .catch(error => {
                                    console.error('Error al generar al listado', error);
                                });
                        }
                    })
                    .catch(error => {
                        console.error('Error al generar al listado', error);
                    });
            }
        },

        obtenerPeriodo(codigo) {
            // Diccionario para mapear números de mes a nombres
            const meses = {
                '01': 'ENERO',
                '02': 'FEBRERO',
                '03': 'MARZO',
                '04': 'ABRIL',
                '05': 'MAYO',
                '06': 'JUNIO',
                '07': 'JULIO',
                '08': 'AGOSTO',
                '09': 'SEPTIEMBRE',
                '10': 'OCTUBRE',
                '11': 'NOVIEMBRE',
                '12': 'DICIEMBRE'
            };

            // Convertir el código a string en caso de que sea un número
            let codigoStr = codigo.toString();

            // Extraer el año y el mes del código
            let anio = codigoStr.slice(0, 4); // Primeros 4 caracteres
            let mes = codigoStr.slice(4, 6);  // Últimos 2 caracteres

            // Devolver el año seguido del nombre del mes
            return `${anio} - ${meses[mes]}`;
        },

        getImage(event, frm_campo, index) {
            this.foto = event.target.files[0];
            this.fotoPath = "store/vys2022/" + this.cas_id + "/" + frm_campo + "/" + this.foto.name;
            this.fotoDir = "store/vys2022/" + this.cas_id + "/" + frm_campo;
            let ext = this.foto.name.split('.')[1];
            if (ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'JPG' || ext == 'JPEG' || ext == 'PNG') {
                this.createB64(this.foto, frm_campo, index);
                this.campos[index].frm_value = this.fotoPath;
            }
            else {
                this.$swal({
                    title: 'Advertencia!',
                    text: 'Debe seleccionar un archivo de tipo imagen con formato .JPG, .JPEG o .PNG',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                this.campos[index].frm_value = '';
                this.foto = null;
            }
        },

        createB64(fileObject, frm_campo, index) {
            let _this = this;
            const reader = new FileReader();
            reader.readAsDataURL(fileObject);
            reader.onload = (e) => {
                _this.foto = e.target.result;
                let gRegistro = {};
                let b64 = _this.foto.split(',');
                gRegistro.foto = b64[1];
                gRegistro.fotoPath = _this.fotoPath;
                gRegistro.fotoDir = _this.fotoDir;
                let url = "api/subirAdjunto";
                axios.post(url, gRegistro)
                    .then(function (response) {
                        _this.campos[index].frm_value = _this.fotoPath;
                        _this.foto = null;
                        _this.fotoPath = null;
                        _this.fotoDir = null;
                        _this.$forceUpdate();
                    })
                    .catch(function (error) {
                        _this.output = error;
                        _this.campos[index].frm_value = '';
                        this.$swal({
                            title: 'Error!',
                            text: 'Archivo no subido, intente nuevamente por favor',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    });
            };
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

        createB64Document(fileObject, frm_campo, index) {
            let _this = this;
            const reader = new FileReader();
            reader.readAsDataURL(fileObject);
            reader.onload = (e) => {
                _this.documento = e.target.result;
                let gRegistro = {};
                let b64 = _this.documento.split(',');
                gRegistro.foto = b64[1];
                gRegistro.fotoPath = _this.documentoPath;
                gRegistro.fotoDir = _this.documentoDir;
                let url = "api/subirAdjunto";
                axios.post(url, gRegistro)
                    .then(function (response) {
                        _this.campos[index].frm_value = _this.documentoPath;
                        _this.documento = null;
                        _this.documentoPath = null;
                        _this.documentoDir = null;
                        _this.$forceUpdate();
                    })
                    .catch(function (error) {
                        _this.output = error;
                        _this.campos[index].frm_value = '';
                        this.$swal({
                            title: 'Error!',
                            text: 'Archivo no subido, intente nuevamente por favor',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    });
            };
        },

        // GRID functions
        gridAddRow(cols, rows) {
            let _COD_PROCESO = document.getElementById("_COD_PROCESO").value;
            if (this.contador_gfu < 2 && _COD_PROCESO == 'GFU') {
                var fila = [];
                var i = 0;
                cols.forEach(campo => {
                    i++;
                    var parteFila = { "col_campo": campo.col_campo, "col_value": "" };
                    fila.push(parteFila);
                });
                rows.push(fila);
                this.$forceUpdate();
                this.contador_gfu = this.contador_gfu + 1;
            } else if (_COD_PROCESO == 'MAHER') {
                console.log("fale MAHER");
                Swal.fire({
                    title: '¿Estás Seguro de crear este HEREDERO?',
                    text: 'Cuenta con la Declaratoria de Herederos, aprobado por Asesoría Legal ? ',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, Crear',
                    cancelButtonText: 'Cancelar',
                    willOpen: () => { Swal.showLoading(); }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var fila = [];
                        var i = 0;
                        cols.forEach(campo => {
                            i++;
                            var parteFila = { "col_campo": campo.col_campo, "col_value": "" };
                            fila.push(parteFila);
                        });
                        rows.push(fila);
                        this.$forceUpdate();

                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.close();
                    }
                });
            } else if (_COD_PROCESO != 'GFU') {
                console.log("fale");
                var fila = [];
                var i = 0;
                cols.forEach(campo => {
                    i++;
                    var parteFila = { "col_campo": campo.col_campo, "col_value": "" };
                    fila.push(parteFila);
                });
                rows.push(fila);
                this.$forceUpdate();
            }
        },
        gridDeleteRow(rows, index, row) {
            if (row.length === 17 || row.length === 18 || row.length === 14) {
                console.log('row', row.length);
                if (row[1].col_value !== '' && row[2].col_value !== '') {
                    Swal.fire({
                        title: '¿Estás Seguro de Eliminar Este Derechohabiente?',
                        text: 'Los datos introducidos se perderán',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        willOpen: () => { Swal.showLoading(); }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.derechoHabientes.id_persona_sip = row[1].col_value;
                            this.derechoHabientes.ci_dh = row[2].col_value;
                            this.derechoHabientes.cas_id = this.cas_cod_id;
                            this.derechoHabientes.usrId = this.usrId;
                            let gDatos = this.derechoHabientes;
                            axios.post('api/limpiarDerechohabiente', gDatos)
                                .then(response => {
                                    console.log(response.data);
                                    this.contador_gfu = this.contador_gfu - 1;
                                    rows.splice(index, 1);
                                    this.$forceUpdate();
                                    Swal.fire('Eliminado Exitosamente!', '', 'success');
                                })
                                .catch(error => {
                                    console.error(error);
                                    Swal.fire('Error al Eliminar Este Derechohabiente', '', 'error');
                                });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.close();
                        }
                    });
                } else {
                    this.contador_gfu = this.contador_gfu - 1;
                    rows.splice(index, 1);
                    this.$forceUpdate();
                }
            } else if (row.length === 21) {
                console.log('row', row.length);
                if (row[2].col_value !== '' && row[3].col_value !== '') {
                    Swal.fire({
                        title: '¿Estás Seguro de Eliminar Este Derechohabiente?',
                        text: 'Los datos introducidos se perderán',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        willOpen: () => { Swal.showLoading(); }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.derechoHabientes.id_persona_sip = row[2].col_value;
                            this.derechoHabientes.ci_dh = row[3].col_value;
                            this.derechoHabientes.cas_id = this.cas_cod_id;
                            this.derechoHabientes.usrId = this.usrId;
                            let gDatos = this.derechoHabientes;
                            axios.post('api/limpiarDerechohabiente', gDatos)
                                .then(response => {
                                    console.log(response.data);
                                    this.contador_gfu = this.contador_gfu - 1;
                                    rows.splice(index, 1);
                                    this.$forceUpdate();
                                    Swal.fire('Eliminado Exitosamente!', '', 'success');
                                })
                                .catch(error => {
                                    console.error(error);
                                    Swal.fire('Error al Eliminar Este Derechohabiente', '', 'error');
                                });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.close();
                        }
                    });
                } else {
                    this.contador_gfu = this.contador_gfu - 1;
                    rows.splice(index, 1);
                    this.$forceUpdate();
                }
            } else if (row.length === 23) {
                console.log('row', row.length);
                if (row[3].col_value !== '' && row[4].col_value !== '') {
                    Swal.fire({
                        title: '¿Estás Seguro de Eliminar Este HEREDERO?',
                        text: 'Los datos introducidos se perderán',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        willOpen: () => { Swal.showLoading(); }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.derechoHabientes.id_persona_sip = row[3].col_value;
                            this.derechoHabientes.ci_dh = row[4].col_value;
                            this.derechoHabientes.cas_id = this.cas_cod_id;
                            this.derechoHabientes.usrId = this.usrId;
                            let gDatos = this.derechoHabientes;
                            axios.post('api/limpiarDerechohabiente', gDatos)
                                .then(response => {
                                    console.log(response.data);
                                    this.contador_gfu = this.contador_gfu - 1;
                                    rows.splice(index, 1);
                                    this.$forceUpdate();
                                    Swal.fire('HEREDERO Eliminado Exitosamente!', '', 'success');
                                })
                                .catch(error => {
                                    console.error(error);
                                    Swal.fire('Error al Eliminar Este Derechohabiente', '', 'error');
                                });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.close();
                        }
                    });
                } else {
                    this.contador_gfu = this.contador_gfu - 1;
                    rows.splice(index, 1);
                    this.$forceUpdate();
                }
            }
        },

        // ARCHIVAR
        archivarCaso() {
            var cas_data = this.registro.cas_data;
            cas_data.cas_motivo_archivo = this.archivo.cas_motivo_archivo;
            cas_data.cas_descripcion_archivo = this.archivo.cas_descripcion_archivo;
            // Ensure codProceso is accessed correctly
            if (this.codProceso == 'INV' || this.codProceso == 'PM') {
                cas_data.ESTADO_DERIVACION = this.archivo.cas_motivo_archivo;
            }
            var gRegistro = {};
            gRegistro.cas_data = cas_data;
            gRegistro.cas_id = this.cas_id;
            gRegistro.cas_usr_id = this.usrId;
            gRegistro.uid = cas_data.UID;
            gRegistro.cas_cod_id =cas_data.cas_cod_id;
            var url = "api/casosArchivar/" + this.cas_id;
            axios.put(url, gRegistro).then(response => {
                this.$router.push('/misCasos');
            });
        },

        doHistorico(id) {
            let that = this;
            let url2 = "api/casosHistorico/" + id;
            axios.get(url2)
                .then((response) => {
                    this.historico = response.data.data;
                    this.id_caso = response.data.data[0].htc_cas_cod_id;
                    $('#modalHistorico').modal('show');
                })
                .catch(function (error) {
                    that.output = error;
                });
            const datos5 = {"cas_id": this.cas_id};
            axios.post('api/getCasosLegalTramite', datos5)
                .then(response => {
                    this.resultadosLegalTramite=response.data.data;
                    // $('#modalHistorico').modal('show');
                })
                .catch(error => {
                    console.error('Error al generar al listado', error);
                });
        },

        doDocumentoPdf(htc_id) {
            const datos = { htc_id: htc_id };
            axios.post('api/obtenerDocumento', datos)
                .then(response => {
                    this.documento = response.data.data;
                })
                .catch(error => {
                    console.error('Error al generar al listado', error);
                });
        },
        obtenerDocumentoLegalGral(htc_cas_id) {
            const datos = { htc_cas_id: htc_cas_id };
            axios.post('api/obtenerDocumentoLegalGral', datos)
                .then(response => {
                    this.documento = response.data.data;
                })
                .catch(error => {
                    console.error('Error al generar al listado', error);
                });
        },

        enlazarPrestacion(cas_legal_id,cas_legal_cod_id, cas_prestacion_id, cas_prestacion_cod_id) {
            console.log("cas_legal_id: ", cas_legal_id);
            console.log("cas_prestacion_id: ", cas_prestacion_id);
            const datos = { cas_legal_id: cas_legal_id, cas_legal_cod_id: cas_legal_cod_id,
                cas_prestacion_id: cas_prestacion_id, cas_prestacion_cod_id: cas_prestacion_cod_id ,
                userid:this.usrId
             };
            Swal.fire({
                title: 'Esta acción no se puede deshacer',
                icon: 'question',
                text: '¿Estás seguro de enlazar esta Validación Legal al trámite actual?',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('api/enlazarPrestacionLegal', datos)
                        .then(response => {
                            this.documento = response.data.data;
                            this.getCasosLegal()
                        })
                        .catch(error => {
                            console.error('Error al generar al listado', error);
                        });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.close();
                }
                });
        },

        observacionesUCPP(observaciones) {
            console.log("Observaciones", observaciones);
            this.observaciones = observaciones;
        },

        doDocumentoPdfAdjunto() {
            const datos = { cas_id: this.registro.cas_id };
            axios.post('api/obtenerDocumentoAdjunto', datos)
                .then(response => {
                    this.documento = response.data.data;
                })
                .catch(error => {
                    console.error('Error al generar al listado', error);
                });
        },

        doDocumentoPdfAdjuntoMedico() {
            const datos = { cas_id: this.registro.cas_id };
            axios.post('api/obtenerDocumentoAdjuntoMedico', datos)
                .then(response => {
                    this.documento = response.data.data;
                })
                .catch(error => {
                    console.error('Error al generar al listado documentos medicos', error);
                });
        },
        // MENSAJES

        doImprimir1() {
            const datos = { cas_id: this.registro.cas_id };
            axios.post('api/generateFormRescepcionDocumento', datos)
                .then(response => {
                    // Obtener la ruta del archivo PDF desde la respuesta
                    const rutaPDF = response.data.ruta_pdf;
                    // Descargar el archivo PDF
                    this.pdfSrc = response.data;
                       this.renderPDF(this.pdfSrc, 'pdfCanvas');
                               $('#modalPrevisualizar').modal('show');
                    //window.open(`api/downloadpdf?ruta=${rutaPDF}`, '_blank');
                })
                .catch(error => {
                    console.error('Error al generar o abrir el PDF', error);
                });
        },

        setupLeafletMap() {
            //--- carga Iconos
            this.iconoBase = L.Icon.extend({
                options: {
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                }
            });

            this.blackIcon = new this.iconoBase({
                iconUrl: '/img/_marcadores/vh-2x-black.png',
                shadowUrl: '/img/_marcadores/marker-shadow.png',
            });

            this.redIcon = new this.iconoBase({
                iconUrl: '/img/_marcadores/vh-2x-red.png',
                shadowUrl: '/img/_marcadores/marker-shadow.png',
            });

            this.greenIcon = new this.iconoBase({
                iconUrl: '/img/_marcadores/vh-2x-green.png',
                shadowUrl: '/img/_marcadores/marker-shadow.png',
            });

            //--- Define Mapa
            this.map = L.map("mapContainer").setView(this.center, 15);
            L.tileLayer(
                "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}",
                {
                    attribution:
                        'Map data © <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery (c) <a href="https://www.mapbox.com/">Mapbox</a>',
                    maxZoom: 18,
                    id: "mapbox/streets-v11",
                    accessToken:
                        "pk.eyJ1IjoiYWJpZGlzaGFqaWEiLCJhIjoiY2l3aDFiMG96MDB4eDJva2l6czN3MDN0ZSJ9.p9SUzPUBrCbH7RQLZ4W4lQ",
                }
            ).addTo(this.map);
            L.geoJSON(data, {
                onEachFeature: this.onEachFeature,
                style: this.styleMap,
            }).addTo(this.map);
            //.on("click", this.onClick);
            this.listarCarreras();
        },

        styleMap(feature) {
            const year = feature.properties.datelisted
                ? parseInt(feature.properties.datelisted.slice(0, 4))
                : 0;
            const color = year > 2000 ? "red" : "blue";
            return { color: color };
        },
        onEachFeature(feature, layer) {
            if (feature.properties && feature.properties.name) {
                layer.bindPopup(feature.properties.name);
                layer.on('mouseover', () => { layer.openPopup(); });
                layer.on('mouseout', () => { layer.closePopup(); });
            }
        },
        async registarArchivo() {
            try{
                this.mostrarOverlay();
                const arregloDatos = [];
                const tabla = document.getElementById("tabla_requisitos");
                const ci_ = 'DH_CI_GRILLA_PROP' + this.index;
                const id_sip_ = 'DH_IDPERSONA_GRILLA_PROP' + this.index;
                const ci = document.getElementById(ci_).value;
                const id_sip = document.getElementById(id_sip_).value;
                const filas = tabla.querySelectorAll("tr");
                const tam = filas.length - 1;
                var bandera = 0;
                let datos;
                for (var i = 0; i < tam; i++) {
                    const descripcion = "descripcion_" + bandera;
                    const id = "id_" + bandera;
                    const documentoOriginalObligatorio_ = 'documentoOriginalObligatorio_' + bandera;
                    const presentacionObligatoria_ = 'presentacionObligatoria_' + bandera;
                    const switch_ = 'switch_' + bandera;
                    const valor_id = document.getElementById(id).value;
                    const valoe_descripcion = document.getElementById(descripcion).value;
                    const documentoOriginalObligatorio = document.getElementById(documentoOriginalObligatorio_).value;
                    const presentacionObligatoria = document.getElementById(presentacionObligatoria_).value;
                    const switchElement = document.querySelector(`#${switch_}`);
                    const valorSwitch = switchElement.checked;
                    const fileInput = document.getElementById('pdf_' + bandera);
                    const file = fileInput.files[0];
                    const observacionArc = "id_observacion_" + bandera;
                    const elemento = document.getElementById(observacionArc);
                    var detalleDocumento_ = "detalleDocumento_" + i;
                    const detalleDocumento = document.getElementById(detalleDocumento_).value;
                    let idObservacion = '';
                    if (elemento != null) {
                        idObservacion = elemento.value;
                    }
                    if (!file) {
                        datos = {
                            tam: tam,
                            valor_id: valor_id,
                            valor_descripcion: valoe_descripcion,
                            pdf: '',
                            caso: this.cas_cod_id,
                            id_caso: this.cas_id,
                            documentoOriginalObligatorio: documentoOriginalObligatorio,
                            presentacionObligatoria: presentacionObligatoria,
                            ci: ci,
                            parentesco: this.codigoTipoParentesco,
                            switch: valorSwitch,
                            id_persona_sip: id_sip,
                            id_observacion: idObservacion,
                            detalle_documento: detalleDocumento,
                            usr_id: this.usrId,
                        };
                        arregloDatos.push(datos);
                    } else {
                        //2025-02-21
                        const base64data = await processFile(file);
                        //2025-02-21
                        const datos = {
                                tam: tam,
                                valor_id: valor_id,
                                valor_descripcion: valoe_descripcion,
                                pdf: base64data,
                                caso: this.cas_cod_id,
                                id_caso: this.cas_id,
                                documentoOriginalObligatorio: documentoOriginalObligatorio,
                                presentacionObligatoria: presentacionObligatoria,
                                ci: ci,
                                parentesco: this.codigoTipoParentesco,
                                switch: valorSwitch,
                                id_persona_sip: id_sip,
                                id_observacion: idObservacion,
                                detalle_documento: detalleDocumento,
                                usr_id: this.usrId,
                            };
                            arregloDatos.push(datos);
                    }
                    bandera++;
                }
                const responseSaveDocuments = await axios.post('api/guardarDocumentosRequisitosNfs', arregloDatos);
                $('#modalDocumentos').modal('hide');
                this.ocultarOverlay();
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Tus Documentos Fueron Guardados",
                    showConfirmButton: false,
                    timer: 2000
                });
            }catch(e){
                this.ocultarOverlay();
                // Safely extract the error message
                const message = (e.response && e.response.data && e.response.data.message) || e.message || 'Error desconocido al procesar esta operacion.';
                Swal.fire({
                            title: '<b>ATENCIÓN</b>',
                            text: message,
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        });
            }
        },

        async registarArchivoTitular() {
            try{
                const tabla = document.getElementById("tabla_titular");
                const filas = tabla.querySelectorAll("tr");
                const tam = filas.length - 1;
                var bandera = 0;
                const ci = document.getElementById("AS_CI").value;
                const id_persona_sip = document.getElementById("AS_IDPERSONA").value;
                const arregloDatos = [];
                for (var i = 0; i < tam; i++) {
                    const id_switch = "switch_tit_" + bandera;
                    const switchElement = document.querySelector(`#${id_switch}`);
                    const valorSwitch = switchElement.checked;
                    const descripcion = "descripcion_tit_" + bandera;
                    const id = "id_tit_" + bandera;
                    const documentoOriginalObligatorio_ = 'documentoOriginalObligatorio_tit_' + bandera;
                    const presentacionObligatoria_ = 'presentacionObligatoria_tit_' + bandera;
                    const valor_id = document.getElementById(id).value;
                    const valoe_descripcion = document.getElementById(descripcion).value;
                    const documentoOriginalObligatorio = document.getElementById(documentoOriginalObligatorio_).value;
                    const presentacionObligatoria = document.getElementById(presentacionObligatoria_).value;
                    const fileInput = document.getElementById('pdf_tit_' + bandera);
                    const observacionTit = "id_observacion_tit_" + bandera;
                    const idObservacionTit = document.getElementById(observacionTit).value;
                    var detalleDocumento_tit = "detalleDocumento_tit_" + i;
                    const detalleDocumentoTit = document.getElementById(detalleDocumento_tit).value;
                    const file = fileInput.files[0];
                    if (!file) {
                        const datos = {
                            tam: tam,
                            valor_id: valor_id,
                            valor_descripcion: valoe_descripcion,
                            pdf: '',
                            caso: this.cas_cod_id,
                            id_caso: this.cas_id,
                            documentoOriginalObligatorio: documentoOriginalObligatorio,
                            presentacionObligatoria: presentacionObligatoria,
                            ci: ci,
                            parentesco: '0-TIT',
                            switch: valorSwitch,
                            id_persona_sip: id_persona_sip,
                            id_observacion: idObservacionTit,
                            detalle_documento: detalleDocumentoTit,
                            usr_id: this.usrId,
                        };
                        arregloDatos.push(datos);
                    } else {
                        const base64data = await processFile(file);
                        const datos = {
                            tam: tam,
                            valor_id: valor_id,
                            valor_descripcion: valoe_descripcion,
                            pdf: base64data,
                            caso: this.cas_cod_id,
                            id_caso: this.cas_id,
                            documentoOriginalObligatorio: documentoOriginalObligatorio,
                            presentacionObligatoria: presentacionObligatoria,
                            ci: ci,
                            parentesco: '0-TIT',
                            switch: valorSwitch,
                            id_persona_sip: id_persona_sip,
                            id_observacion: idObservacionTit,
                            detalle_documento: detalleDocumentoTit,
                            usr_id: this.usrId,
                        };
                        arregloDatos.push(datos);
                    }
                    bandera++;
                }
                const responseSaveDocuments = await axios.post('api/guardarDocumentosRequisitosNfs', arregloDatos);
                this.ocultarOverlay();
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Tus Documentos Fueron Guardados",
                    showConfirmButton: false,
                    timer: 2000
                });
            }catch(e){
                this.ocultarOverlay();
                // Safely extract the error message
                const message = (e.response && e.response.data && e.response.data.message) || e.message || 'Error desconocido al procesar esta operacion.';
                Swal.fire({
                            title: '<b>ATENCIÓN</b>',
                            text: message,
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        });
            }
        },
        async registarArchivoSolicitante() {
            try{
                const tabla = document.getElementById("tabla_solicitante");
                // Verificar si la tabla existe
                if (!tabla) {
                    console.error("El elemento con ID 'tabla_solicitante' no existe.");
                    return;
                }
                const filas = tabla.querySelectorAll("tr");
                // Validar que hay filas
                if (!filas || filas.length === 0) {
                    console.error("No se encontraron filas en la tabla.");
                    return;
                }
                const tam = filas.length - 1;
                let bandera = 0;
                const ci = document.getElementById("AS_CI")?.value || "";
                const id_persona_sip = document.getElementById("AS_IDPERSONA")?.value || "";
                const arregloDatos = [];
                for (let i = 0; i < tam; i++) {
                    const id_switch = `switch_sol_${bandera}`;
                    const switchElement = document.querySelector(`#${id_switch}`);
                    const valorSwitch = switchElement?.checked || false;
                    const descripcion = `descripcion_sol_${bandera}`;
                    const id = `id_sol_${bandera}`;
                    const documentoOriginalObligatorio_ = `documentoOriginalObligatorio_sol_${bandera}`;
                    const presentacionObligatoria_ = `presentacionObligatoria_sol_${bandera}`;
                    const valor_id = document.getElementById(id)?.value || "";
                    const valoe_descripcion = document.getElementById(descripcion)?.value || "";
                    const documentoOriginalObligatorio = document.getElementById(documentoOriginalObligatorio_)?.value || "";
                    const presentacionObligatoria = document.getElementById(presentacionObligatoria_)?.value || "";
                    const fileInput = document.getElementById(`pdf_sol_${bandera}`);
                    const file = fileInput?.files ? fileInput.files[0] : null;
                    const observacionSol = `id_observacion_sol_${bandera}`;
                    const idObservacionSol = document.getElementById(observacionSol)?.value || "";
                    const detalleDocumento_sol = `detalleDocumento_sol_${i}`;
                    const detalleDocumentoSol = document.getElementById(detalleDocumento_sol)?.value || "";
                    if (!file) {
                        const datos = {
                            tam: tam,
                            valor_id: valor_id,
                            valor_descripcion: valoe_descripcion,
                            pdf: '',
                            caso: this.cas_cod_id,
                            id_caso: this.cas_id,
                            documentoOriginalObligatorio: documentoOriginalObligatorio,
                            presentacionObligatoria: presentacionObligatoria,
                            ci: ci,
                            parentesco: '0-SOL',
                            switch: valorSwitch,
                            id_persona_sip: id_persona_sip,
                            id_observacion: idObservacionSol,
                            detalle_documento: detalleDocumentoSol,
                            usr_id: this.usrId,
                        };
                        arregloDatos.push(datos);
                    } else {
                        const base64data = await processFile(file);
                        const datos = {
                            tam: tam,
                            valor_id: valor_id,
                            valor_descripcion: valoe_descripcion,
                            pdf: base64data,
                            caso: this.cas_cod_id,
                            id_caso: this.cas_id,
                            documentoOriginalObligatorio: documentoOriginalObligatorio,
                            presentacionObligatoria: presentacionObligatoria,
                            ci: ci,
                            parentesco: '0-SOL',
                            switch: valorSwitch,
                            id_persona_sip: id_persona_sip,
                            id_observacion: idObservacionSol,
                            detalle_documento: detalleDocumentoSol,
                            usr_id: this.usrId,
                        };
                        arregloDatos.push(datos);
                    }
                    bandera++;
                }
                const responseSaveDocuments = await axios.post('api/guardarDocumentosRequisitosNfs', arregloDatos);
                this.ocultarOverlay();
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Tus Documentos Fueron Guardados",
                    showConfirmButton: false,
                    timer: 2000
                });
            } catch(e){
                this.ocultarOverlay();
                // Safely extract the error message
                const message = (e.response && e.response.data && e.response.data.message) || e.message || 'Error desconocido al procesar esta operacion.';
                Swal.fire({
                            title: '<b>ATENCIÓN</b>',
                            text: message,
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        });
            }
        },
        documentosAdjunto() {
            window.location.href = '/documentosAdjunto/' + this.registro.cas_id;
        },
        async registarArchivoRespaldo() {
            try{
                const arregloDatos = [];
                this.asignarValores();
                const items = this.registro.cas_data_valores;
                const as_ci = this.registro.cas_data.AS_CI;
                let id_persona = '';
                for (var i = 0; i < items.length; i++) {
                    if (items[i].frm_campo == 'AS_IDPERSONA') {
                        id_persona = items[i].frm_value;
                    }
                }
                for (var i = 0; i < this.urls.length; i++) {
                    const frm_etiqueta = this.urls[i].frm_etiqueta;
                    const frm_campo = this.urls[i].frm_campo;
                    const frm_id_campo = this.urls[i].frm_id_campo;
                    const fileInput = document.getElementById(frm_id_campo);
                    const file = fileInput.files[0];
                    let datos;
                    if (file) {
                        const base64data = await processFile(file);
                        const datos = {
                            tam: 2000,
                            valor_id: 1,
                            valor_descripcion: frm_etiqueta,
                            pdf: base64data,
                            caso: this.cas_cod_id,
                            id_caso: this.cas_id,
                            documentoOriginalObligatorio: '',
                            presentacionObligatoria: '',
                            ci: as_ci,
                            parentesco: '4-RES',
                            switch: '',
                            pfrm_value: frm_campo,
                            id_persona_sip: id_persona,
                            id_observacion: "1",
                            detalle_documento: '',
                            usr_id: this.usrId,
                        };
                        arregloDatos.push(datos);
                    }
                }
                const responseSaveDocuments = await axios.post('api/guardarDocumentosRequisitosNfs', arregloDatos);
            }catch(e){
                const message = (e.response && e.response.data && e.response.data.message) || e.message || 'Error desconocido al procesar esta operacion.';
                throw new Exception(`Error, method: registroArchivoRespaldo, message: ${message}`);
            }
        },

        registarArchivoUnoPorUno(fileInput, id_persona, descripcion, as_ci, valor_id, pfrm_value) {
            const file = fileInput.files[0];
            if (!file) {
            } else {
                const reader = new FileReader();
                reader.onload = () => {
                    const base64data = reader.result.split(',')[1];
                    const datos = {
                        tam: 2000,
                        valor_id: valor_id,
                        valor_descripcion: descripcion,
                        pdf: base64data,
                        caso: this.cas_cod_id,
                        id_caso: this.cas_id,
                        documentoOriginalObligatorio: '',
                        presentacionObligatoria: '',
                        ci: as_ci,
                        parentesco: '4-RES',
                        switch: '',
                        pfrm_value: pfrm_value,
                        id_persona_sip: id_persona,
                        id_observacion: "1",
                        detalle_documento: "prueba",
                        usr_id: this.usrId,
                    };
                    axios.post('api/guardarDocumentosRequisitos', datos)
                        .then(response => {
                        })
                        .catch(error => {
                            console.error('Error al generar o abrir el PDF', error);
                        });
                };
                reader.readAsDataURL(file);
            }
        },

        sanitizedPdfSrc() {
            // Convierte la cadena Base64 a un formato seguro usando btoa()
            this.pdfData = `data:application/pdf;base64,` + this.pdfSrc;
            return `data:application/pdf;base64,` + this.pdfSrc;
        },

        mostrarOverlay() {
            //2025-04-11
            // Mostrar el overlay cambiando el estilo de display
            this.$refs.overlay.style.display = 'flex';
        },
        ocultarOverlay() {
            // Ocultar el overlay cambiando el estilo de display
            this.$refs.overlay.style.display = 'none';
        },

        cerrarModalGenerico() {
            var modal = document.getElementById("modalGenerico");
            modal.style.display = "none";
        },
        cerrarSubModalGenerico(){
            var modal = document.getElementById("subModalGenerico");
            modal.style.display = "none";
        },
        openDescripcionModal() {
            return;
        },
        verificarDerechoHabientes() {
            var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;
            //var idSeguimientoTramite = 2399;
            var urlVerificaTercerGrado = this.urlGestoraSgg + `/otorgamiento-prestaciones-calculos/api/v1/definicion/verificarTercerGrado?idSeguimientoTramite=${idSeguimientoTramite}`;
            const that = this;
            axios.get(urlVerificaTercerGrado).then(response => {
                if (response.data.codigo === "0") {
                    if (response.data.data === "SI") {
                        document.getElementById('mensajeRespuestaContrato').textContent = 'Se tiene declarados a Derechohabientes de Tercer Grado. Se procedera al Calculo de los montos de la Pensión Solidaria de Vejez';
                        $('#modalRespuestaContrato').modal('show');
                        $('#btnRespuestaContrato').off('click').click(function () {
                            that.procesarMontos();
                            console.log("POR EL TRUE");
                        });
                    } else {
                        document.getElementById('mensajeRespuestaContrato').textContent = 'El asegurado no cuenta con derechohabientes de tercer grado. Se procedera a la "Emisión de Contrato para Firma del Asegurado"';
                        $('#btnRespuestaContrato').off('click').click(function () {
                            that.procesarContrato();
                            console.log("POR EL ELSE");
                        });
                        $('#modalRespuestaContrato').modal('show');
                    }
                } else {
                    console.log('Error: ', response.data.mensaje);
                }
            });
        },

        procesarContrato(valor = null) {
            var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;
            var urlContrato = '';
            if (valor !== null) {
                if (valor == 0) {
                    console.log("Se recibió un valor:",);
                    urlContrato = `${this.urlGestoraSgg}/otorgamiento-prestaciones-calculos/api/v1/reporte/contrato?idSeguimientoTramite=${idSeguimientoTramite}&usuMod=${this.usrUser}@gestora.bo`;
                    console.log(urlContrato);
                } else if (valor == 1) {
                    console.log("Se recibió un valor:", valor);
                    urlContrato = `${this.urlGestoraSgg}/otorgamiento-prestaciones-calculos/api/v1/reporte/secondContrato?idSeguimientoTramite=${idSeguimientoTramite}&usuMod=${this.usrUser}@gestora.bo`;
                    console.log(urlContrato);
                }
            }
            else {
                console.log("Se recibió un valor sin DH:",);
                urlContrato = `${this.urlGestoraSgg}/otorgamiento-prestaciones-calculos/api/v1/reporte/contrato?idSeguimientoTramite=${idSeguimientoTramite}&usuMod=${this.usrUser}@gestora.bo`;
                console.log(urlContrato);
            }
            var direccionAsegurado = (this.registro.cas_data.AS_DIRECCION == null || this.registro.cas_data.AS_DIRECCION == undefined) ? direccionAsegurado = '' : this.registro.cas_data.AS_DIRECCION;
            var correoAsegurado = (this.registro.cas_data.AS_CORREO == null || this.registro.cas_data.AS_CORREO == undefined) ? correoAsegurado = '' : this.registro.cas_data.AS_CORREO;
            var telefonoAsegurado = (this.registro.cas_data.AS_CELULAR == null || this.registro.cas_data.AS_CELULAR == undefined) ? telefonoAsegurado = '' : this.registro.cas_data.AS_CELULAR;
            var regional = this.registro.cas_data.cas_regional;
            var params = {
                "direccionAsegurado": direccionAsegurado,
                "correoAsegurado": correoAsegurado,
                "telefonoAsegurado": telefonoAsegurado,
                "regional": regional
            };
            axios.post(urlContrato, params).then(response => {
                if (response.data.codigo === "0") {
                    this.pdfSrc = response.data.data;
                    this.renderPDF(this.pdfSrc, 'pdfCanvas');
                    $('#modalPrevisualizar').modal('show');
                }
            });
        },
        procesarMontos() {
            var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;
            //var idSeguimientoTramite = 880;
            var telefonoAsegurado = (this.registro.cas_data.AS_CELULAR == null || this.registro.cas_data.AS_CELULAR == undefined) ? telefonoAsegurado = '' : this.registro.cas_data.AS_CELULAR;
            var urlMontosJubilacion = `${this.urlGestoraSgg}/otorgamiento-prestaciones-calculos/api/v1/reporte/obtenerEleccionTercerGradoPdf?idSeguimientoTramite=${idSeguimientoTramite}&celular=${telefonoAsegurado}&usuMod=${this.usrUser}@gestora.bo`;
            axios.post(urlMontosJubilacion).then(response => {
                if (response.data.codigo === "0") {
                    this.pdfSrc = response.data.data;
                    this.renderPDF(this.pdfSrc, 'pdfCanvas');
                    $('#modalPrevisualizar').modal('show');
                    document.getElementById('Confirmación Derechohabiente').disabled = false;
                    document.getElementById('CONFIRMAR_DERECHOHABIENTE').disabled = false;
                }
            });
        },
        procesarEleccionDerechohabiente() {
            var valor = document.getElementById('Confirmación Derechohabiente').value;
            var that = this;
            if (valor === '') {
                document.getElementById('mensajeRespuestaContrato').textContent = 'Se debe selecionar la Confirmación de Derechohabientes';
                $('#btnRespuestaContrato').off('click');
                $('#modalRespuestaContrato').modal('show');
            } else {
                var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;
                //var idSeguimientoTramite = 1192;
                var telefonoAsegurado = (this.registro.cas_data.AS_CELULAR == null || this.registro.cas_data.AS_CELULAR == undefined) ? telefonoAsegurado = '' : this.registro.cas_data.AS_CELULAR;
                var urlSelecion = `${this.urlGestoraSgg}/otorgamiento-prestaciones-calculos/api/v1/definicion/marcarEleccionTercerGrado?idSeguimientoTramite=${idSeguimientoTramite}&eleccion=${valor}&usuMod=${this.usrUser}@gestora.bo`;
                axios.post(urlSelecion).then(response => {
                    if (response.data.codigo === "0") {
                        document.getElementById('mensajeRespuestaContrato').textContent = response.data.data + ' Se procedera a generar el contrato';
                        $('#btnRespuestaContrato').off('click').click(function () {
                            that.procesarContrato(valor);
                        });
                        $('#modalRespuestaContrato').modal('show');
                    }
                });
            }
        },
        fechaMaxima() {
            const today = new Date();
            let day = today.getDate();
            let month = today.getMonth() + 1;
            const year = today.getFullYear();
            if (day < 10) {
                day = '0' + day;
            }
            if (month < 10) {
                month = '0' + month;
            }
            return year + '-' + month + '-' + day;
        },
        fechaMinima() {
            const today = new Date();
            let day = today.getDate();
            let month = today.getMonth() + 1;
            const year = today.getFullYear();
            if (day < 10) {
                day = '0' + day;
            }
            if (month < 10) {
                month = '0' + month;
            }
            return year + '-' + month + '-' + day;
        },
        tramitesCurso() {
            let url = "api/tramitesCurso";
            var params = {
                "cuaAsegurado": document.getElementById("AS_CUA").value,
                "tipoTramite": this.TIPO_PROCESO
            };
            axios.post(url, params).then(response => {
                if (response.data[0].data !== 0) {
                    if (document.getElementById("AS_CUA").value !== null && document.getElementById("AS_CUA").value !== "") {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "El Asegurado ya cuenta con TRAMITE EN CURSO",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                    this.vaciarAsegurado();
                }
            });
        },

        vaciarAsegurado() {
            document.getElementById("AS_FECHA_DEFUNCION").value = "";
            document.getElementById("AS_FECHA_DEFUNCION").dispatchEvent(new Event('input'));
            document.getElementById("AS_CI").value = "";
            document.getElementById("AS_CI").dispatchEvent(new Event('input'));
            document.getElementById("AS_PRIMER_NOMBRE").value = "";
            document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
            document.getElementById("AS_SEGUNDO_NOMBRE").value = "";
            document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
            document.getElementById("AS_PRIMER_APELLIDO").value = "";
            document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
            document.getElementById("AS_SEGUNDO_APELLIDO").value = "";
            document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
            document.getElementById("AS_NACIMIENTO").value = "";
            document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
            document.getElementById("AS_APELLIDO_CASADA").value = "";
            document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
            document.getElementById("AS_CUA").value = "";
            document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
            document.getElementById("AS_GENERO").value = "";
            document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
            document.getElementById("AS_ESTADO_CIVIL").value = "";
            document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
            document.getElementById("AS_API_ESTADO").value = "";
            document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
            document.getElementById("AS_IDPERSONA").value = "";
            document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
            document.getElementById("AS_APELLIDO_CASADA").value = "";
            document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
            document.getElementById("AS_ZONA").value = "";
            document.getElementById("AS_ZONA").dispatchEvent(new Event('input'));
            document.getElementById("AS_DIRECCION").value = "";
            document.getElementById("AS_DIRECCION").dispatchEvent(new Event('input'));
            document.getElementById("AS_NUM").value = "";
            document.getElementById("AS_NUM").dispatchEvent(new Event('input'));
            document.getElementById("AS_TELEFONO").value = "";
            document.getElementById("AS_TELEFONO").dispatchEvent(new Event('input'));
            document.getElementById("AS_CELULAR").value = "";
            document.getElementById("AS_CELULAR").dispatchEvent(new Event('input'));
            document.getElementById("AS_CORREO").value = "";
            document.getElementById("AS_CORREO").dispatchEvent(new Event('input'));
            document.getElementById("AS_COMPLEMENTO").value = "";
            document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
            document.getElementById("AS_ENTE_HERRAMIENTA").value = "";
            document.getElementById("AS_ENTE_HERRAMIENTA").dispatchEvent(new Event('input'));
            document.getElementById("AS_ENTE_GESTOR").value = "";
            document.getElementById("AS_ENTE_GESTOR").dispatchEvent(new Event('input'));
            document.getElementById("AS_IDPERSONA").value = "";
            document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
        },
        verificarContratoPagosCc() {
            var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;
            console.log(idSeguimientoTramite, idSeguimientoTramite);
            console.log('this.registro', this.registro);
            var urlContrato = `${this.urlGestoraSgg}/compensacion-cotizacion/api/v1/reporte/pago_ccm?nroTramite=${this.registro.cas_data.cas_cod_id}&usuario=${this.registro.cas_data.de_usuario}@gestora.bo`;
            var params = {};
            axios.post(urlContrato, params).then(response => {
                console.log(response);
                if (response.data.codigo === "200") {
                    this.pdfSrc = response.data.data;
                    this.renderPDF(this.pdfSrc, 'pdfCanvas');
                    $('#modalPrevisualizar').modal('show');
                } else {
                    alert('no existe documento');

                }
            });
        },
        renderPDF(base64, canvasContainer) {
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
            if (!this.pdfSrc) {
                console.error("No hay contenido en this.pdfSrc");
                return;
            }
            // Crear un enlace temporal
            const link = document.createElement('a');
            // Convertir Base64 en Blob directamente
            const blob = this.base64ToBlob(this.pdfSrc, 'application/pdf');
            // Crear una URL para el Blob
            const url = URL.createObjectURL(blob);
            // Configurar el enlace de descarga
            link.href = url;
            link.download = nombre || 'documento.pdf';
            // Agregarlo al DOM y hacer clic
            document.body.appendChild(link);
            link.click();
            // Remover el enlace y liberar memoria
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        },

        base64ToBlob(base64, mimeType) {
            const byteCharacters = atob(base64);
            const byteArrays = [];
            for (let i = 0; i < byteCharacters.length; i += 512) {
                const slice = byteCharacters.slice(i, i + 512);
                const byteNumbers = new Array(slice.length);
                for (let j = 0; j < slice.length; j++) {
                    byteNumbers[j] = slice.charCodeAt(j);
                }
                byteArrays.push(new Uint8Array(byteNumbers));
            }
            return new Blob(byteArrays, { type: mimeType });
        },

           verificarContratoMilitar() {
            var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;
           var urlContrato = `${this.urlGestoraSgg}/otorgamiento-prestaciones-calculos/api/v1/reporteMilitares/pdfMilitar?idSeguimientoTramite=${idSeguimientoTramite}&usuario=${this.registro.cas_data.de_usuario}@gestora.bo&conDH=CONDH`;
            //var urlContrato = `https://sgg.gestora.bo/otorgamiento-prestaciones-calculos/api/v1/reporteMilitares/pdfMilitar?idSeguimientoTramite=942524&usuario=vanderley.martinez@gestora.bo&conDH=CONDH `;
            var params = {
            };
            axios.get(urlContrato, params).then(response => {
                if (response.data.codigo === "200") {
                    this.pdfSrc = response.data.data;
                    this.renderPDF(this.pdfSrc, 'pdfCanvas');
                        $('#modalPrevisualizar').modal('show');
                } else {
                    alert('no existe documento');
                }
            });
        },

        verificarContratoMaher() {
            var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;
            var urlContrato = `${this.urlGestoraSgg}/otorgamiento-prestaciones-retiros/api//v1/reportesMasaHereditaria/devolucionMasaHereditaria?idSeguimientoTramite=${idSeguimientoTramite}&usuario=${this.registro.cas_data.de_usuario}@gestora.bo`;
            var params = {
            };
            axios.post(urlContrato, params).then(response => {
                console.log(response);
                if (response.data.codigo === "200") {
                    this.pdfSrc = response.data.data;
                    this.renderPDF(this.pdfSrc, 'pdfCanvas');
                    $('#modalPrevisualizar').modal('show');
                } else {
                    alert('no existe documento');

                }
            });
        },
        verificarContratoRetirosMinimos() {
            var idSeguimientoTramite = this.registro.cas_data.cas_cod_id;
            console.log(this.registro.cas_data_valores);
            let  varia_impre ='';
               for (var i = 0; i < this.registro.cas_data_valores.length; i++) {
                    if (this.registro.cas_data_valores[i].frm_campo == 'RMI_OPCION') {
                        console.log('RMI_OPCION', this.registro.cas_data_valores[i].frm_value_label);
                        if (this.registro.cas_data_valores[i].frm_value_label == 'RETIRO FINAL'){
                           varia_impre = 'RETIRO-TOTAL';
                           // varia_impre = 'RETIRO-MINIMO';

                        } else {
                          varia_impre = 'RETIRO-MINIMO';
                            //varia_impre = 'RETIRO-TOTAL';
                        }
                    }
                }
            // var urlContrato = `${this.urlGestoraSgg}/compensacion-cotizacion/api/v1/reporte/pago_ccm?nroTramite=${this.registro.cas_data.cas_cod_id}&usuario=${this.registro.cas_data.de_usuario}@gestora.bo`;
            var urlContrato = `${this.urlGestoraSgg}/otorgamiento-prestaciones-retiros/api/v1/reportesRetiro/retiroFinal?idSeguimientoTramite=${idSeguimientoTramite}&usuario=${this.registro.cas_data.de_usuario}@gestora.bo&tipoRetiro=${varia_impre}`;
            var params = { };
            axios.post(urlContrato, params).then(response => {
                console.log(response);
                if (response.data.codigo === "200") {
                    this.pdfSrc = response.data.data;
                    this.renderPDF(this.pdfSrc, 'pdfCanvas');
                    $('#modalPrevisualizar').modal('show');
                } else {
                    alert(response.data.mensaje);
                }
            });
        },

        verificaVejez() {
            if(this.esMilitarPdf == true){
                this.verificarContratoMilitar();
            } else {
                console.log('this.registro.dsdasd', this.registro.cas_data_valores);
                var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;
                //var idSeguimientoTramite = 2399;
                var urlVerificaVejez = `${this.urlGestoraSgg}/otorgamiento-prestaciones-calculos/api/v1/definicionV1/verificarTercerGrado?idSeguimientoTramite=${idSeguimientoTramite}`;
                const that = this;
                axios.get(urlVerificaVejez).then(response => {
                    if (response.data.codigo === "0") {
                        let condicion = response.data.data.condicion;
                        if (condicion.includes('RM') || condicion.includes('RF')) {
                            document.getElementById('mensajeRespuestaContrato').textContent = 'El asegurado cuenta con las opciones de Jubilizacion por Vejez por un monto de ' + response.data.data.monto + ', Retiro Minimo o Retiro Final."';
                            $('#btnRespuestaContrato').off('click').click(function () {
                                const montoInput = document.getElementById("MONTO_V_RM_RF");
                                montoInput.type = 'number';
                                montoInput.style.textAlign = 'right';
                                montoInput.value = response.data.data.monto;
                                that.idsgg = response.data.data.idSeguimientoTramite;
                                document.getElementById("MONTO_V_RM_RF").dispatchEvent(new Event('input'));
                                document.getElementById('ACEPTACION_V_RM_RF').disabled = false;
                                document.getElementById('BTN_ACEPTACION_V_RM_RF').disabled = false;
                            });
                            $('#modalRespuestaContrato').modal('show');
                        } else {
                            if (condicion == 'CUMPLE VEJEZ' || condicion == 'NO CUMPLE VEJEZ') {
                                document.getElementById('mensajeRespuestaContrato').textContent = 'Se procedera a la "Emisión de Contrato para Firma del Asegurado."';
                                $('#btnRespuestaContrato').off('click').click(function () {
                                    that.procesarContrato();
                                });
                                $('#modalRespuestaContrato').modal('show');
                            } else {
                                if (condicion == 'CUMPLE SOLIDARIA') {
                                    that.verificarDerechoHabientes();
                                }
                            }
                        }
                    }
                });
            }
        },
        //**************FUNCION DE CONTRATO INV-PM */
        async invContrato() {
            try {
                var that = this;
                this.mostrarOverlay();
                var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;//4569;
                var urlinv = `${this.urlGestoraSgg}/prestaciones-riesgos/api/v1/reportesPDFmandar/comparacionPDFinvalidez?idSolicitudTramite=${idSeguimientoTramite}&usuMod=${this.usrUser}@gestora.bo`;
                const datos = { "enteGestorSalud": "" };
                const response = await axios.post(urlinv, datos);
                if (response.data.codigo === "200") {
                    console.log(response.data.data);
                    const datosAdjuntar = {
                        nroTramite: this.cas_cod_id,
                        documentos: [
                            {
                                documento: response.data.data,
                                descripcion: "DECLARACION_DE_PENSION"
                            }
                        ]
                    };
                    const responsePut = await axios.put("api/adjuntarContrato", datosAdjuntar);
                    document.getElementById("DECLARACION_DE_PENSION").value = "rosos";
                    document.getElementById("DECLARACION_DE_PENSION").dispatchEvent(new Event('input'));
                    document.getElementById("DECLARACION_DE_PENSION").value = responsePut.data[0].data;
                    document.getElementById("DECLARACION_DE_PENSION").dispatchEvent(new Event('input'));
                    //alert(document.getElementById("DECLARACION_DE_PENSION_ID").value);
                    //alert(document.getElementById("DECLARACION_DE_PENSION").value);
                    //document.getElementById("DECLARACION_DE_PENSION_ID").value = responsePut.data[0].data;
                    //document.getElementById("DECLARACION_DE_PENSION_ID").dispatchEvent(new Event('input'));
                    //document.getElementById("DECLARACION_DE_PENSION").value = responsePut.data[0].data;
                    //document.getElementById("DECLARACION_DE_PENSION").dispatchEvent(new Event('input'));
                    // Esperar a que verImagen complete si es una función que retorna una promesa
                    await that.verImagen(document.getElementById("DECLARACION_DE_PENSION").value);
                    //console.log('Imagen visualizada exitosamente');
                    this.ocultarOverlay();
                } else if (response.data.codigo === "201") {
                    this.ocultarOverlay();
                }
            } catch (error) {
                this.ocultarOverlay();
                console.error('Error al generar la asignacion', error);
            }
        },
        async pmContrato() {
            try {
                var that = this;
                this.mostrarOverlay();
                var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;//4569;
                var urlinv = `${this.urlGestoraSgg}/prestaciones-riesgos/api/v1/reporteContratosPDF/contrato?idSeguimientoTramite=${idSeguimientoTramite}&usuMod=${this.usrUser}@gestora.bo`;
                const datos = { "enteGestorSalud": "" };
                const response = await axios.post(urlinv, datos);
                if (response.data.codigo === "200") {
                    console.log(response.data.data);
                    const datosAdjuntar = {
                        nroTramite: this.cas_cod_id,
                        documentos: [
                            {
                                documento: response.data.data,
                                descripcion: "DECLARACION_DE_PENSION"
                            }
                        ]
                    };
                    const responsePut = await axios.put("api/adjuntarContrato", datosAdjuntar);
                    console.log("respuesta", responsePut.data[0].data);
                    document.getElementById("DECLARACION_DE_PENSION").value = "rosos";
                    document.getElementById("DECLARACION_DE_PENSION").dispatchEvent(new Event('input'));
                    document.getElementById("DECLARACION_DE_PENSION").value = responsePut.data[0].data;
                    document.getElementById("DECLARACION_DE_PENSION").dispatchEvent(new Event('input'));
                    //alert(document.getElementById("DECLARACION_DE_PENSION_ID").value);
                    //alert(document.getElementById("DECLARACION_DE_PENSION").value);
                    //document.getElementById("DECLARACION_DE_PENSION_ID").value = responsePut.data[0].data;
                    //document.getElementById("DECLARACION_DE_PENSION_ID").dispatchEvent(new Event('input'));
                    //document.getElementById("DECLARACION_DE_PENSION").value = responsePut.data[0].data;
                    //document.getElementById("DECLARACION_DE_PENSION").dispatchEvent(new Event('input'));
                    // Esperar a que verImagen complete si es una función que retorna una promesa
                    await that.verImagen(document.getElementById("DECLARACION_DE_PENSION").value);
                    //console.log('Imagen visualizada exitosamente');
                    this.ocultarOverlay();
                } else if (response.data.codigo === "201") {
                    this.ocultarOverlay();
                }
            } catch (error) {
                this.ocultarOverlay();
                console.error('Error al generar la asignacion', error);
            }
        },

        vejezCNU() {
            var opcion = document.getElementById('ACEPTACION_V_RM_RF').value;
            var that = this;
            if (opcion === '') {
                document.getElementById('mensajeRespuestaContrato').textContent = 'Se debe selecionar el Tipo de Aceptación. (Vejez, Retiro Minimo o Retiro Final)';
                $('#btnRespuestaContrato').off('click');
                $('#modalRespuestaContrato').modal('show');
            } else {
                var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;
                //var idSeguimientoTramite = 2441;
                //var opcion = 2; // obtener valor desde combo de pantalla
                // 1 Vejez
                // 2 Acepta RM
                // 3 Retiro Final
                var urlVejezCNU = `${this.urlGestoraSgg}/otorgamiento-prestaciones-calculos/api/v1/definicionV1/guardarVejezCNU?idSeguimientoTramite=${this.idsgg}&opcion=${opcion}&usuario=${this.usrUser}@gestora.bo`;
                //Recuperar datos
                const datos = this.registro.cas_data_valores;
                var params = {
                    "enteGestorSalud": "",
                    "matricula": "",
                    "departamento": "",
                    "provincia": "",
                    "ciudadLocalidad": "",
                    "zonaBarrio": "",
                    "avenidadCallePasaje": "",
                    "casilla": "",
                    "celular": "",
                    "apPaternoApo": "",
                    "apMaternoApo": "",
                    "apCasadaApo": "",
                    "priNombreApo": "",
                    "segNombreApo": "",
                    "tipDocumentoApo": "",
                    "numDocumentoApo": "",
                    "relacionApo": "",
                    "estadoCivilApo": "",
                    "generoApo": "",
                    "avenidadCallePasajeApo": "",
                    "ciudadLocalidadApo": "",
                    "zonaBarrioApo": "",
                    "casillaApo": "",
                    "telefonoApo": "",
                    "nroTestimonioApo": "",
                    "nroNotariaApo": "",
                    "notarioApo": "",
                    "lugarEmisionApo": "",
                    "fechaApo": ""
                };

                for (var i = 0; i < datos.length; i++) {
                    switch (datos[i].frm_campo) {
                        case 'AS_ENTE_GESTOR': params['enteGestorSalud'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'AS_MATRICULA_ASEGURADO': params['matricula'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'AS_DEPARTAMENTO': params['departamento'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'PROVINCIA': params['provincia'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'AS_CIUDAD': params['ciudadLocalidad'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'AS_ZONA': params['zonaBarrio'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'AS_DIRECCION': params['avenidadCallePasaje'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'AS_POSTAL': params['casilla'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'AS_CELULAR': params['celular'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_PRIMER_APELLIDO': params['apPaternoApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_SEGUNDO_APELLIDO': params['apMaternoApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_APELLIDO_CASADA': params['apCasadaApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_PRIMER_NOMBRE': params['priNombreApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_SEGUNDO_NOMBRE': params['segNombreApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_TIPO_DOCUMENTO': params['tipDocumentoApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_CI': params['numDocumentoApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_PARENTESCO': params['relacionApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_ESTADO_CIVIL': params['estadoCivilApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_GENERO': params['generoApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : (datos[i].frm_value == 'MASCULINO' ? 'M' : 'F'); break;
                        case 'SOL_DIRECCION': params['avenidadCallePasajeApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_CIUDAD': params['ciudadLocalidadApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_ZONA': params['zonaBarrioApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_POSTAL': params['casillaApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_CELULAR': params['telefonoApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'NRO_PODER_SOL_1': params['nroTestimonioApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'NRO_NOTARIA_SOL_1': params['nroNotariaApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'NOMBRE_NOTARIO_SOL_1': params['notarioApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'SOL_CIUDAD': params['lugarEmisionApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                        case 'FECHA_REVISION': params['fechaApo'] = (datos[i].frm_value == null || datos[i].frm_value == undefined) ? '' : datos[i].frm_value; break;
                    }
                }
                axios.post(urlVejezCNU, params).then(response => {
                    if (response.data.codigo === "0") {
                        that.pdfSrc = response.data.data;
                        this.renderPDF(that.pdfSrc, 'pdfCanvas');
                        $('#modalPrevisualizar').modal('show');
                    }
                });
            }
        },
        listarUsuariosNodo() {
            this.usuarioNodo = null;
            this.descripcionUsuarioAsignacion = '';
            this.valUsuarioAsignacion = false;
            var nodId = localStorage.getItem('nodo');
            console.log(nodId);
            var url = "api/usuariosNodo/" + this.registro.act_nodo_id;//nodId;
            axios.get(url).then(response => {
                this.usuariosNodo = response.data.data;
                console.log(this.usuariosNodo);
                $('#modalAsignacionCasos').modal('show');
            });
        },
        confirmarAsignacion() {
            if (!this.usuarioNodo) {
                this.valUsuarioAsignacion = true;
                return;
            }
            this.nombreUsuarioAsignacion = this.usuarioNodo ? this.usuarioNodo.label : '';
            $('#modalConfirmacion').modal('show');
        },
        hideValUsuarioAsignacion() {
            this.valUsuarioAsignacion = false;
        },
        asignarCaso() {
            console.log('asignarCaso');
            console.log(this.usuarioNodo.value);
            console.log(this.cas_id);
            console.log(this.descripcionUsuarioAsignacion);
            const datos = { cas_id: this.cas_id, cas_usr_id: this.usuarioNodo.value, descripcion: this.descripcionUsuarioAsignacion };
            axios.post('api/asignarCaso', datos)
                .then(response => {
                    Swal.fire('El caso fue asignado', '', 'success');
                    console.log(response);
                    setTimeout(() => {
                        $('#modalConfirmacion').modal('hide');
                        $('#modalAsignacionCasos').modal('hide');
                        this.$router.push('/misCasos');
                    }, 300);
                })
                .catch(error => {
                    $('#modalConfirmacion').modal('hide');
                    $('#modalAsignacionCasos').modal('hide');
                    console.error('Error al generar la asignacion', error);
                });
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

// private functions on vue
function readFileAsDataURL(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

async function processFile(file) {
    try {
        const dataURL = await readFileAsDataURL(file);
        const base64data = dataURL.split(',')[1];
        return base64data;
    } catch (error) {
        console.error("Error processing file:", error);
        throw error;
    }
}
</script>
<style>
img {
    width: 30%;
    margin: auto;
    display: block;
    margin-bottom: 10px;
}

.btn_respaldos_minus {
    height: 50px;
    line-height: 45px;
    width: 50px;
    font-size: 2em;
    font-weight: bold;
    border-radius: 25%;
    background-color: #e3342f;
    border-color: #e3342f;
    color: white;
    text-align: center;
    cursor: pointer;
}

.btn_respaldos_plus2 {
    height: 70px;
    line-height: 55px;
    width: 70px;
    font-size: 2em;
    font-weight: bold;
    border-radius: 45%;
    background-color: #38c1b7;
    color: white;
    text-align: center;
    cursor: pointer;
}

.btn_respaldos_plus {
    height: 50px;
    line-height: 45px;
    width: 50px;
    font-size: 2em;
    font-weight: bold;
    border-radius: 25%;
    background-color: #38c172;
    color: white;
    text-align: center;
    cursor: pointer;
}

/* Estilos CSS para el switch */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 50%;
}

input:checked+.slider {
    background-color: #2196F3;
}

input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Estilos adicionales para el diseño del switch */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.loader {
    border: 16px solid #1505fa;
    border-top: 16px solid #db3434;
    border-radius: 50%;
    width: 200px;
    height: 200px;
    animation: spin 2s linear infinite;
}

.cerrar-modal-generico {
    color: #aaa;
    padding: 5px;
    float: right;
    font-size: 20px;
    font-weight: bold;
}

.cerrar-modal-generico:hover,
.cerrar-modal-generico:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}


.sub-cerrar-modal-generico {
    color: #aaa;
    padding: 5px;
    float: right;
    font-size: 20px;
    font-weight: bold;
}

.sub-cerrar-modal-generico:hover,
.sub-cerrar-modal-generico:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>
<style>
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
}

.loader-wrapper {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}


.loader-text {
    font-size: 15px;
    color: #0a7dca;
    position: absolute;
    font-weight: bold;
}

.loading-text {
    font-size: 12px;
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

label {
    font-weight: bold;
    margin-bottom: 5px;
}

input.form-control {
    margin-bottom: 10px;
    padding: 8px;
    border-radius: 5px;
}

.row {
    margin-top: 20px;
}

.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    justify-content: center;
    align-items: center;
}

.modal-generico {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    /* Fondo oscuro con opacidad */
    display: none;
    /* Oculto por defecto */
    justify-content: center;
    align-items: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease-out;
    /* Animación de aparición */
}

.sub-modal-generico {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    /* Fondo oscuro con opacidad */
    display: none;
    /* Oculto por defecto */
    justify-content: center;
    align-items: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease-out;
    /* Animación de aparición */
}

/* Animación de desvanecimiento del fondo */
@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Estilo del contenido del modal */
.modal-generico-contenido-atender-caso {
    background-color: #fff;
    padding: 0px;
    border-radius: 20px;
    /* Bordes redondeados */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    /* Sombra suave */
    width: 90%;
    max-width: 600px;
    /* Limitar el tamaño del modal */
    transition: transform 0.3s ease-out;
    transform: scale(0.9);
    margin: 20% auto;
}
.sub-modal-generico-contenido-atender-caso {
    background-color: #fff;
    padding: 0px;
    border-radius: 20px;
    /* Bordes redondeados */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    /* Sombra suave */
    width: 90%;
    max-width: 600px;
    /* Limitar el tamaño del modal */
    transition: transform 0.3s ease-out;
    transform: scale(0.9);
    margin: 20% auto;
}
/* Animación de escalado del modal */
.modal-generico.show .modal-generico-contenido-atender-caso {
    transform: scale(1);
    /* Cuando se muestra, se escala al tamaño normal */
}

.sub-modal-generico.show .sub-modal-generico-contenido-atender-caso {
    transform: scale(1);
    /* Cuando se muestra, se escala al tamaño normal */
}


/* Estilo del título del modal */
#modalGenerico-titulo {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    text-align: center;
    border-top-left-radius: 20px;
    /* Redondea solo la esquina superior izquierda */
    border-top-right-radius: 20px;
    /* Redondea solo la esquina superior derecha */
}
#sub-modalGenerico-titulo {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    text-align: center;
    border-top-left-radius: 20px;
    /* Redondea solo la esquina superior izquierda */
    border-top-right-radius: 20px;
    /* Redondea solo la esquina superior derecha */
}

/* Estilo del mensaje del modal */
#modalGenerico-mensaje {
    font-size: 18px;
    color: #1f0997;
    margin-bottom: 20px;
}

#sub-modalGenerico-mensaje {
    font-size: 18px;
    color: #1f0997;
    margin-bottom: 20px;
}
/* Estilo del botón "Cerrar" */
.cerrar-btn {
    background-color: #FF5733;
    /* Color llamativo */
    color: white;
    border: none;
    padding: 12px 25px;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cerrar-btn:hover {
    background-color: #e64a19;
    /* Cambio de color al pasar el mouse */
}

/* Estilo del botón de cierre (×) en la esquina superior derecha */
.cerrar-modal-generico {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 30px;
    color: #333;
    cursor: pointer;
    transition: color 0.3s ease;
}

.cerrar-modal-generico:hover {
    color: #FF5733;
    /* Cambio de color cuando el mouse está encima */
}

.dub-cerrar-modal-generico {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 30px;
    color: #333;
    cursor: pointer;
    transition: color 0.3s ease;
}

.sub-cerrar-modal-generico:hover {
    color: #FF5733;
    /* Cambio de color cuando el mouse está encima */
}
.modal-icono {
    margin-bottom: 20px;
}

.icono-advertencia {
    width: 60px;
    /* Puedes ajustar el tamaño */
    height: 60px;
    margin: 0 auto;
}

.sub-icono-advertencia {
    width: 60px;
    /* Puedes ajustar el tamaño */
    height: 60px;
    margin: 0 auto;
}
</style>

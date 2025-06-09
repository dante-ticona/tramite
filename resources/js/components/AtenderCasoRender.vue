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
                            v-on:click="doHistorico(cas_id)" data-toggle="modal">
                            <i class="fa fa-list-ol" aria-hidden="true"></i>
                        </button>
                        <!--
						<button class="btn btn-primary btn-circle" data-target="#modalMensajeria"
							title="Ver Mensajeria" v-on:click="doGetMensajes()" data-toggle="modal">
							<i class="fa fa-envelope white" aria-hidden="true"></i>
						</button>
						-->
                        <button class="btn btn-warning btn-circle" data-target="#modalArchivar"
                            v-if="actividad.act_orden == 30 || actividad.act_orden == 20" title="Archivar"
                            data-toggle="modal">
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
                            v-if="actividad.act_orden != 32 && actividad.act_orden != 20 && actividad.act_orden != 100">
                            <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                        </button>

                        <button class="btn btn-success btn-circle" title="Asignación de Casos"
                            v-on:click="listarUsuariosNodo()">
                            <i class="fa fas fa-people-arrows white" aria-hidden="true"></i>
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
                                    @keypress="validarEntradaText($event)" @keydown="validarEntradaText($event)">
                            </div>
                            <div v-else-if="c.frm_tipo == 'TEXT_MIN'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" v-model="c.frm_value" class="form-control" type="text"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'"
                                    @keypress="validarEntradaText($event)" @keydown="validarEntradaText($event)">
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
                            <div v-else-if="c.frm_tipo == 'MONTH'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}<label v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <input :id="c.frm_campo" type="month" v-model="c.frm_value" class="form-control"
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
                                        :required="c.frm_obligatorio == 'true'" @change="ejecutar(c.frm_funcion);"> {{
                                            c.frm_etiqueta }}
                                </label>
                            </div>
                            <div v-else-if="c.frm_tipo == 'DROPDOWNLIST'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}
                                <label :id="c.frm_campo + '_REQUIRED'" v-show="c.frm_obligatorio == 'true'"
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
                                <MultiselectComponent v-model="c.frm_value" :options="c.frm_items ?? []"
                                    :domId="c.frm_campo" :disabled="c.frm_deshabilitado === 'true'"
                                    :required="c.frm_obligatorio === 'true'" :componentLabel="c.frm_etiqueta">
                                </MultiselectComponent>
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

                            <div v-else-if="c.frm_tipo == 'SELECTMULTIPLE'" :id="c.frm_campo + '_idd'">
                                {{ c.frm_etiqueta }}
                                <label v-show="c.frm_obligatorio == 'true'" style="color:rgb(228, 31, 31);"> (*)</label>

                                <select :id="c.frm_campo" v-model="c.frm_value" class="form-control"
                                    :disabled="c.frm_deshabilitado == 'true'" :required="c.frm_obligatorio == 'true'"
                                    multiple>
                                    <option v-for="(i, index2) in c.frm_items" :key="index2" :value="i.frm_value">
                                        {{ i.frm_value }} - {{ i.frm_etiqueta }}
                                    </option>
                                </select>

                                <!-- Mostrar etiquetas de los seleccionados -->

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
                                <button :disabled="c.frm_deshabilitado == 'true' ? true : false" :id="c.frm_campo"
                                    type="button" class="btn btn-success"
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
                                {{ c.frm_etiqueta }}
                                <label :id="c.frm_campo + '_REQUIRED'" v-show="c.frm_obligatorio == 'true'"
                                    style="color:rgb(228, 31, 31);"> (*)</label>
                                <div class="input-group mb-3">
                                    <input :id="c.frm_campo" v-model="c.frm_value" class="form-control"
                                        placeholder="Ingrese Documento" disabled>
                                </div>
                                <input :id="c.frm_id_campo" type="file" name="file"
                                    @change="ejecutar(c.frm_funcion, c.frm_id_campo); tamanoDocumento($event);"
                                    accept=".pdf"
                                    :required="c.frm_obligatorio == 'true' && (c.frm_value === '' || c.frm_value === null || c.frm_value === undefined)">
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
                                        <td v-for="(col, colIndex) in row" :key="colIndex"
                                            :style="{ width: col.col_width }">

                                            <div v-if="c.frm_cols[colIndex].col_tipo == 'LABEL'">
                                                <label>{{ c.frm_cols[colIndex].col_etiqueta }}</label>
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'TEXT'">
                                                <input :id="col.col_campo + rowIndex" :placeholder="col.col_etiqueta"
                                                    v-model="col.col_value"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'"
                                                    @input="col.col_value = col.col_value.toUpperCase()"
                                                    @keypress="validarEntradaText($event)"
                                                    @keydown="validarEntradaText($event)">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'MAIL'">
                                                <input :id="col.col_campo + rowIndex" :placeholder="col.col_etiqueta"
                                                    v-model="col.col_value"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'">
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'TEXTAREA'"
                                                style="width: 100%;">
                                                <textarea :id="col.col_campo + rowIndex" :placeholder="col.col_etiqueta"
                                                    v-model="col.col_value" cols="30" rows="3"
                                                    :disabled="c.frm_cols[colIndex].col_deshabilitado == 'true' ? true : false"
                                                    :required="c.col_obligatorio == 'true'"
                                                    style="width: 100%;"></textarea>
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
                                                <input type="file" name="capturaFoto"
                                                    @change="getDocument($event, c.frm_cols[colIndex].col_campo, index)"
                                                    accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf">
                                                <!--img :src="foto" alt="Foto" class="img-responsive" -->
                                            </div>
                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'MODAL'">
                                                <button type="button" class="btn btn-danger btn-circle"
                                                    title="Subir documentos 1"
                                                    v-on:click="listarRequisitos(c.frm_value[rowIndex], rowIndex)"
                                                    data-toggle="modal" data-target="#modalDocumentos">
                                                    <i class="fa fa-paper-plane white" aria-hidden="true"></i>
                                                </button>
                                            </div>

                                            <div v-else-if="c.frm_cols[colIndex].col_tipo == 'MODAL_CI'">
                                                <button type="button" class="btn btn-danger btn-circle"
                                                    title="Subir documentos 1"
                                                    v-on:click="listarRequisitosCi(c.frm_value[rowIndex], rowIndex, c.frm_cols[colIndex].col_campo)"
                                                    data-toggle="modal" data-target="#modalDocumentosCi">
                                                    <i class="fa fa-paper-plane white" aria-hidden="true"></i>
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
                            <h5>Documentación </h5>
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
                                        <input v-if="actividad.act_orden == '20' || actividad.act_orden == '100'"
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
                </form>
                <!--*********************INICIO DE LEGAL*************************-->
                <legal :casId="cas_id" ref="legalComponent" />
                <div class="row col-md-12"> </div>
                <!--**********************FIN DE LEGAL************************-->
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
                        <!-- <button type="submit" form="form1" class="form-control btn-primary">
                            <i class="fa fa-floppy-o white" aria-hidden="true"></i>
                            Grabar y Volver</button> -->


                        <template v-if="actividad.act_orden == '20'">
                            <button type="submit" form="form1" class="form-control btn-primary"
                                @click.prevent="validacionSeguimientoDuplicados">
                                <i class="fa fa-floppy-o white" aria-hidden="true"></i>
                                Grabar y Volver.
                            </button>
                        </template>

                        <template v-else>
                            <button type="submit" form="form1" class="form-control btn-primary">
                                <i class="fa fa-floppy-o white" aria-hidden="true"></i>
                                Grabar y Volver
                            </button>
                        </template>


                        <!-- <button type="submit" form="form1" class="form-control btn-primary" @click="actividad.act_orden === 20 ? validacionSeguimientoDuplicados() : actualizarCaso()">
                            <i class="fa fa-floppy-o white" aria-hidden="true"></i>
                            Grabar y Volver LEGAL... {{ actividad.act_orden }}
                        </button> -->

                        <div id="overlay" ref="overlay" class="overlay">
                            <div class="loader"></div>
                            <span class="loader-text">TramiteSIP</span>
                            <span class="loading-text">Cargando...</span>
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
            <div class="modal-generico-contenido">
                <span class="cerrar-modal-generico" @click="cerrarModalGenerico()">&times;</span>
                <h2 id="modalGenerico-titulo">Título del Modal</h2>
                <p id="modalGenerico-mensaje"></p>
                <div style="padding: 10px;">
                    <button class="cerrar-btn" @click="cerrarModalGenerico()">Cerrar</button>
                </div>
            </div>
        </div>
        <div>
            <p v-show="addScript(registro.frm_funciones)"></p>
        </div>

        <!-- modalDocumentos de docuemnto de CI -->
        <div class="modal fade" id="modalDocumentosCi" tabindex="-1" role="dialog" aria-labelledby="modalDocumentosCi"
            aria-hidden="true">
            <div class="modal-dialog lg" role="document" style="max-width: 60%;">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Adjuntar Cedula de Identidad </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-hover table-striped table-responsive" id="tabla_requisitos_ci">
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
                                        <input v-if="actividad.act_orden == '20' || actividad.act_orden == '100'"
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
                        <button type="button" class="btn btn-success" data-dismiss="modal"
                            @click="registarArchivoCi($event, index)">Registrar Requisitos</button>
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
                        <h5 class="modal-title" id="exampleModalLabel">Adjuntar Requisitos </h5>
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
                                        <input v-if="actividad.act_orden == '20' || actividad.act_orden == '100'"
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
                        <button type="button" class="btn btn-success" data-dismiss="modal"
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
            <div class="modal-dialog modal-lg" role="document">
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
                                            <th>Actividad</th>
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
                                                (JSON.parse(h.act_data)).act_descripcion }}</td>
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
                                <label>Listado de Documentos:</label><br>
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro</th>
                                            <th>tipo</th>
                                            <th>Descripcion</th>
                                            <th>Ver Documento</th>
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
        <div class="modal fade" id="modalPrevisualizar" tabindex="-1" role="dialog" aria-labelledby="modalPrevisualizar"
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
                            <div class="col-md-9">
                                <label id="mensajeRespuestaContrato"></label>
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
import MultiselectComponent from './externo/MultiselectComponent.vue';
import { clearLockerLayout, decryptId } from './shared/AuxiliaryFunctions.js';
L.Icon.Default.imagePath = '.';


L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

export default {
    name: 'atenderCasoRender',

    components: {
        LMap, LMarker, LCircleMarker, LPopup, LTileLayer, 'v-select': vSelect, legal, MultiselectComponent,
    },
    props: ['url_encode_cas_id'],
    directives: { mask },
    created() {
        try {
            // Use atob to decode the Base64 encoded cas_id
            const raw = this.$route.params.url_encode_cas_id
            this.cas_id = decryptId(raw);
        } catch (error) {
            console.error('Failed to decode cas_id:', error);
            this.$router.push("/misCasos");
        }
    },
    data() {
        return {
            cas_id: null,
            selectedFile: null,
            loading: false,
            plural: 'Atender Caso',
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
            pdfSrc: '',
            cas_cod_id: '',
            codProceso: null,
            codigoTipoParentesco: '',
            index: null,
            tipo: '',
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
            urlGestora: window.Laravel.url_gestora,//config.URL_GESTORA + '',
            urlGestoraSgg: window.Laravel.url_gestora_sgg, //config.URL_GESTORA_SGG + '',
            usuariosNodo: [],
            usuarioNodo: null,
            descripcionUsuarioAsignacion: '',
            valUsuarioAsignacion: false,
            nombreUsuarioAsignacion: null,
            es_fallecido: false,
            derechoHabientes: {},
            documentosContratacionEventual: [],
        }
    },

    mounted() {
        //this.setupLeafletMap();
        clearLockerLayout();
        this.render();
        this.ocultarOverlay();

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
        selectedLabels() {
            return this.c.frm_value.map(val => {
                const item = this.c.frm_items.find(item => item.frm_value === val);
                return item ? item.frm_etiqueta : '';
            });
        }
    },

    methods: {

        validacionSeguimientoDuplicados(event) {
            event.preventDefault();

            let _AS_TIPO_EAPElement = document.getElementById("AS_TIPO_EAP");

            let _AS_TIPO_EAP = _AS_TIPO_EAPElement ? _AS_TIPO_EAPElement.options[_AS_TIPO_EAPElement.selectedIndex]?.value : null;

            const EAP_COBROS = [2, 31, 176, 199, 200, 201, 202, 219, 228];
            if (EAP_COBROS.includes(Number(_AS_TIPO_EAP))) {
                let _extrangero_poderElement = document.getElementById("EXTRANGERO_PODER");
                let _extrangero_poder = _extrangero_poderElement ? _extrangero_poderElement.options[_extrangero_poderElement.selectedIndex]?.value : null;

                console.log("EL PODER EXTRANGERO: ", _extrangero_poder);

                if (_extrangero_poder === "2") { // INTERNACIONAL cuando selecciona
                    console.log("Poder Extranjero");

                    let _nro_poder_solElement = document.getElementById("NRO_PODER_SOL_1");
                    let _nro_poder_sol = _nro_poder_solElement ? _nro_poder_solElement.value : null;

                    let _as_ci_Element = document.getElementById("AS_CI").value;
                    let _as_ci = _as_ci_Element ? _as_ci_Element : null;

                    let _as_cua_Element = document.getElementById("AS_CUA").value;
                    let _as_cua = _as_cua_Element ? _as_cua_Element : null;

                    console.log("EL VALOR DE NRO_PODER_SOL_1 DEL FORMULARIO: ", _nro_poder_sol);

                    axios.get(`api/datosSeguimientoTramLegaldDuplicados`, { params: { cas_id: _nro_poder_sol, nroTramite: this.cas_cod_id, ci: _as_ci, cua: _as_cua } })
                        .then(response => {
                            console.log("Response =>", response.data);
                            if (response.data && response.data.codigoRespuesta === 200) {
                                const codigo = response.data.data.codigo;
                                const datos = response.data.data.datos;

                                if (codigo === "02") {
                                    Swal.fire({
                                        title: 'Alerta',
                                        text: `El número de poder (${datos.NRO_PODER_SOL_1}) ya está siendo usado por el trámite: ${datos.cas_cod_id}`,
                                        icon: 'warning',
                                        confirmButtonText: 'Aceptar'
                                    });
                                } else if (codigo === "01" || codigo === "03") {
                                    console.log(`Código ${codigo}: Procediendo a guardar...`);
                                    this.actualizarCaso();
                                }
                            }
                        })
                        .catch(error => {
                            if (error.response && error.response.status === 400) {
                                console.log("Código de respuesta 400: Procediendo a guardar...");
                                const responseData = error.response.data;
                                console.log(responseData.data || 'No se encontraron coincidencias');
                                this.actualizarCaso();
                            } else {
                                console.error("Error fetching data:", error);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ocurrió un error al procesar la solicitud.',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });

                } else if (_extrangero_poder === "1") { // NACIONAL
                    console.log("Poder Nacional: Procediendo a guardar...");
                    this.actualizarCaso();
                } else {
                    console.log("No se encontró el componente EXTRANGERO_PODER o no tiene valor: Procediendo a guardar...");
                    this.actualizarCaso();
                }
            } else {
                this.actualizarCaso();
            }
        },

        resizeElement(amountElementToDraw) {
            let sizeElement = "200px;";
            if (amountElementToDraw <= 4) {
                sizeElement = Math.round(100 / amountElementToDraw) + "%";
            }
            return sizeElement;
        },
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
                if (res) {
                    if (campo.frm_tipo === "DROPDOWNLIST") {
                        const dropdown = document.getElementById(campo.frm_campo);
                        dropdown.setAttribute("data-frm-value", res.frm_value);
                    }
                }
            });
        },

        ejecutarFuncionesLocales(campo) {
            if (campo.frm_campo == 'AS_FECHA_FALLECIMIENTO' && document.getElementById("_COD_PROCESO").value == 'RMIN') {
                this.es_fallecido = true;
                this.listarRequisitosTitular();
            }
            if (campo.frm_campo == 'AS_BUSCAR') {
                this.es_fallecido = false;
                setTimeout(() => {/*this.tramitesCurso();*/this.listarRequisitosTitular(); }, 2250);
            }
            if (campo.frm_campo == 'VER_FUNDAMENTOS') {
                if (document.getElementById("VER_FUNDAMENTOS").value == "1" && this.actividad.act_orden == 40) {
                    this.aumentar();
                }
                else if (document.getElementById("VER_FUNDAMENTOS").value == "2" && this.actividad.act_orden == 40) {
                    this.vaciar();
                } else if (document.getElementById("VER_FUNDAMENTOS").value == "3" && this.actividad.act_orden == 40) {

                    this.aumentar();
                }
            }
            //**********LEGAL */
            /*if (campo.frm_campo == 'TIENE_PODER_SOL_1') {
                if (document.getElementById("TIENE_PODER_SOL_1").value == "1") {

                    console.log(':::::::::::::::::::::::'+this.registro.act_data);
                    const ci = document.getElementById("SOL_CI").value;
                    const id_persona_sip = document.getElementById("SOL_IDPERSONA").value;
                    const caso = this.cas_cod_id;
                    const id_caso = this.cas_id;
                    const  prc_id = document.getElementById("AS_TIPO_EAP_LEGAL").value;//this.prc_id;
                    console.log('registrolilililiii=>', this.registro, ci, id_persona_sip, caso, id_caso);
                    console.log('registro proceso =>', this.proceso);
                    console.log('registro prc_id =>', this.prc_id);
                    console.log('registro prc_id =>', this.prc_id);
                    this.$refs.legalComponent.getPrestaciones(prc_id,this.registro);
                    //this.$refs.legalComponent.getPrestaciones(ci, id_persona_sip, caso, id_caso, prc_id,this.registro);
                }
            }*/
            if (campo.frm_campo == 'AS_TIPO_EAP') {
                const prestaacion_id = Number(document.getElementById("AS_TIPO_EAP").value);

                if (
                    //************************ */
                    prestaacion_id == 2 ||
                    prestaacion_id == 31 ||
                    prestaacion_id == 176 ||
                    prestaacion_id == 199 ||
                    prestaacion_id == 200 ||
                    prestaacion_id == 201 ||
                    prestaacion_id == 202 ||
                    prestaacion_id == 219 ||
                    prestaacion_id == 228
                )//e) Poderes de Cobro
                {
                    //****BENIFICIARIO******* */
                    _hide("BE_DIFERENTE_AS_idd");
                    _hide("SUBTITULO_1_1_idd");
                    _hide("BE_TIPO_DOCUMENTO_idd");
                    _hide("BE_CI_idd");
                    _hide("BE_COMPLEMENTO_idd");
                    _hide("BE_BUSCAR_idd");
                    _hide("BE_NACIMIENTO_idd");
                    _hide("BE_CUA_idd");
                    _hide("BE_PRIMER_APELLIDO_idd");
                    _hide("BE_SEGUNDO_APELLIDO_idd");
                    _hide("BE_APELLIDO_CASADA_idd");
                    _hide("BE_PRIMER_NOMBRE_idd");
                    _hide("BE_SEGUNDO_NOMBRE_idd");
                    _hide("BE_ESTADO_CIVIL_idd");
                    _hide("BE_GENERO_idd");
                    _hide("BE_CELULAR_idd");
                    _hide("BE_PARENTESCO_idd");
                    _hide("BE_CI_DOC_idd");

                    document.querySelector('#BE_TIPO_DOCUMENTO').required = false;
                    document.querySelector('#BE_CI').required = false;
                    document.querySelector('#BE_PARENTESCO').required = false;
                    document.querySelector('#BE_ESTADO_CIVIL').required = false;
                    document.querySelector('#BE_CELULAR').required = false;
                    document.querySelector('#BE_CI_DOC').required = false;
                    //****FIN DE BENEFICIARIO********* */
                    //****TABLA DE BENIFICIARIO **********/
                    _show("SUBTITULO_4_4_BE_idd");
                    _show("GRILLA_DACO_idd");
                    //****FIN DE LA TABLA BENIFICIAIRO **/

                    //*****INICIO DE PODER*******/
                    _show("SUBTITLE_11_idd");
                    _show("NRO_PODER_SOL_1_idd");
                    _show("NRO_NOTARIA_SOL_1_idd");
                    _show("NOMBRE_NOTARIO_SOL_1_idd");
                    _show("FECHA_DE_EMISION_idd");
                    _show("DEPARTAMENTO_idd");
                    _show("MUNICIPIO_1_idd");
                    _show("PODER_SCANER_idd");
                    _show("ESTADO_PODER_PRESENTADO_idd");
                    _show("OBSERVACION_PODER_idd");
                    _show("PODER_DIRNOPLU_idd");
                    _show("SUBTITULO_4_4_idd");
                    _show("GRILLA_DAHE_idd");
                    _show("EXTRANGERO_PODER_idd");
                    _show("PAIS_idd");
                    _hide("NRO_PODER_REVOCATORIO_idd");

                    document.querySelector('#NRO_PODER_SOL_1').required = true;
                    document.querySelector('#NRO_NOTARIA_SOL_1').required = true;
                    document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = true;
                    document.querySelector('#FECHA_DE_EMISION').required = true;
                    document.querySelector('#DEPARTAMENTO').required = true;
                    document.querySelector('#MUNICIPIO_1').required = true;
                    document.querySelector('#PODER_SCANER').required = true;
                    document.querySelector('#ESTADO_PODER_PRESENTADO').required = true;
                    document.querySelector('#PODER_DIRNOPLU').required = true;
                    document.querySelector('#EXTRANGERO_PODER').required = true;
                    document.querySelector('#PAIS').required = true;
                    document.querySelector('#NRO_PODER_REVOCATORIO').required = false;

                    //*****FIN DE PODER*********/ HEREDEROS
                    _hide("SUBTITULO_4_4_1_idd");
                    _hide("GRILLA_DAHERDERO_idd");
                    _hide("ACEPTACION_DE_HERENCIA_idd");
                    _hide("NRO_SENTENCIA_RESOLUCION_idd");
                    _hide("JUZGADO_idd");

                    //******COBRO************ */
                    _show("SUBTITULO_4_44_idd");
                    _show("HA_PAGOS_SUPENDIDOS_idd");
                    _show("TIPO_PODER_idd");
                    _show("FORM_JUB_MES_INI_idd");
                    _show("FORM_JUB_MES_FIN_idd");
                    _show("HA_PAGO_AGUINALDO_idd");
                    _show("HA_GESTION_AGUINALDO_idd");
                    _show("PERIODOS_INGRESADOS_idd");
                    _show("MENSAJE_PERIODOS_INGRESADOS_idd");

                    document.querySelector('#HA_PAGOS_SUPENDIDOS').required = true;
                    document.querySelector('#TIPO_PODER').required = true;
                    document.querySelector('#FORM_JUB_MES_INI').required = true;
                    document.querySelector('#FORM_JUB_MES_FIN').required = true;
                    document.querySelector('#HA_PAGO_AGUINALDO').required = true;
                    document.querySelector('#HA_GESTION_AGUINALDO').required = true;
                    document.querySelector('#PERIODOS_INGRESADOS').required = true;
                    document.querySelector('#MENSAJE_PERIODOS_INGRESADOS').required = true;
                    //*****APOSTILLA */
                    _hide("SUBTITLE_11_APOS_idd");
                    _hide("NUMERO_APOSTILLA_idd");
                    _hide("CARTA_APOSTILLA_idd");
                    _hide("TIENE_PODER_idd");
                    document.querySelector('#NUMERO_APOSTILLA').required = false;
                    document.querySelector('#CARTA_APOSTILLA').required = false;
                    document.querySelector('#TIENE_PODER').required = false;
                    document.querySelector('#CARTA_APOSTILLA_ID').required = false;
                    const grupoCF = [199, 200, 201, 202];
                    if (grupoCF.includes(prestaacion_id)) {
                        _show("HA_PAGO_UNICO_idd");
                        document.querySelector('#HA_PAGO_UNICO').required = true;
                    }
                    else {
                        _hide("HA_PAGO_UNICO_idd");
                        document.querySelector('#HA_PAGO_UNICO').required = false;

                    }

                }  ///fin
                else {
                    const grupo1 = [203, 204, 205, 206, 208, 209, 210, 211, 212, 213, 214, 216, 217, 218, 210]; // c) Masa Hereditaria - Poderes de inicio, seguimiento conclusión de trámite
                    const grupo3 = [8, 15, 22, 29, 39, 47, 55, 63, 71, 79, 87, 95, 103, 111, 119, 127, 135, 143, 151, 159, 167, 175, 180, 189, 197, 199, 200, 201, 218, 227, 232, 236, 240]; //b) Validación de Poderes de inicio, seguimiento conclusión de trámite
                    const grupo4 = [207, 215]; //d) Masa Hereditaria – Declaratoria de Herederos
                    const grupo2 = [243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263]; // APOSTILLA
                    const grupo5 = [1, 264, 242, 241, 282, 281, 280, 279, 278, 277, 276, 275, 274, 273, 272, 271, 270, 269, 268, 267, 266, 265];
                    const grupo6 = [283];



                    //***************************** */
                    if (grupo3.includes(prestaacion_id)) //PODER
                    {
                        //alert('hhhhhh');
                        //****BENIFICIARIO******* */
                        _hide("BE_DIFERENTE_AS_idd");
                        _hide("SUBTITULO_1_1_idd");
                        _hide("BE_TIPO_DOCUMENTO_idd");
                        _hide("BE_CI_idd");
                        _hide("BE_COMPLEMENTO_idd");
                        _hide("BE_BUSCAR_idd");
                        _hide("BE_NACIMIENTO_idd");
                        _hide("BE_CUA_idd");
                        _hide("BE_PRIMER_APELLIDO_idd");
                        _hide("BE_SEGUNDO_APELLIDO_idd");
                        _hide("BE_APELLIDO_CASADA_idd");
                        _hide("BE_PRIMER_NOMBRE_idd");
                        _hide("BE_SEGUNDO_NOMBRE_idd");
                        _hide("BE_ESTADO_CIVIL_idd");
                        _hide("BE_GENERO_idd");
                        _hide("BE_CELULAR_idd");
                        _hide("BE_PARENTESCO_idd");
                        _hide("BE_CI_DOC_idd");
                        document.querySelector('#BE_TIPO_DOCUMENTO').required = false;
                        document.querySelector('#BE_CI').required = false;
                        document.querySelector('#BE_PARENTESCO').required = false;
                        document.querySelector('#BE_ESTADO_CIVIL').required = false;
                        document.querySelector('#BE_CELULAR').required = false;
                        document.querySelector('#BE_CI_DOC').required = false;
                        //****TABLA DE BENIFICIARIO **********/
                        _show("SUBTITULO_4_4_BE_idd");
                        _show("GRILLA_DACO_idd");
                        //****FIN DE LA TABLA BENIFICIAIRO **/
                        //*****INICIO DE PODER*******/
                        _show("SUBTITLE_11_idd");
                        _show("NRO_PODER_SOL_1_idd");
                        _show("NRO_NOTARIA_SOL_1_idd");
                        _show("NOMBRE_NOTARIO_SOL_1_idd");
                        _show("FECHA_DE_EMISION_idd");
                        _show("DEPARTAMENTO_idd");
                        _show("MUNICIPIO_1_idd");
                        _show("NOMBRE_NOTARIO_SOL_1_idd");
                        _show("PODER_SCANER_idd");
                        _show("ESTADO_PODER_PRESENTADO_idd");
                        _show("OBSERVACION_PODER_idd");
                        _show("PODER_DIRNOPLU_idd");
                        _show("SUBTITULO_4_4_idd");
                        _show("GRILLA_DAHE_idd");
                        _show("EXTRANGERO_PODER_idd");
                        _show("PAIS_idd");
                        _hide("NRO_PODER_REVOCATORIO_idd");
                        document.querySelector('#NRO_PODER_SOL_1').required = true;
                        document.querySelector('#NRO_NOTARIA_SOL_1').required = true;
                        document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = true;
                        document.querySelector('#FECHA_DE_EMISION').required = true;
                        document.querySelector('#DEPARTAMENTO').required = true;
                        document.querySelector('#MUNICIPIO_1').required = true;
                        document.querySelector('#PODER_SCANER').required = true;
                        document.querySelector('#ESTADO_PODER_PRESENTADO').required = true;
                        document.querySelector('#PODER_DIRNOPLU').required = true;
                        document.querySelector('#EXTRANGERO_PODER').required = true;
                        document.querySelector('#PAIS').required = true;
                        document.querySelector('#NRO_PODER_REVOCATORIO').required = false;
                        //*****FIN DE PODER*********/ HEREDEROS
                        _hide("SUBTITULO_4_4_1_idd");
                        _hide("GRILLA_DAHERDERO_idd");
                        _hide("ACEPTACION_DE_HERENCIA_idd");
                        _hide("NRO_SENTENCIA_RESOLUCION_idd");
                        _hide("JUZGADO_idd");
                        //******COBRO************ */
                        _hide("SUBTITULO_4_44_idd");
                        _hide("HA_PAGOS_SUPENDIDOS_idd");
                        _hide("TIPO_PODER_idd");
                        _hide("FORM_JUB_MES_INI_idd");
                        _hide("FORM_JUB_MES_FIN_idd");
                        _hide("HA_PAGO_AGUINALDO_idd");
                        _hide("HA_GESTION_AGUINALDO_idd");
                        _hide("PERIODOS_INGRESADOS_idd");
                        _hide("MENSAJE_PERIODOS_INGRESADOS_idd");
                        _hide("HA_PAGO_UNICO_idd");
                        document.querySelector('#HA_PAGOS_SUPENDIDOS').required = false;
                        document.querySelector('#TIPO_PODER').required = false;
                        document.querySelector('#FORM_JUB_MES_INI').required = false;
                        document.querySelector('#FORM_JUB_MES_FIN').required = false;
                        document.querySelector('#HA_PAGO_AGUINALDO').required = false;
                        document.querySelector('#HA_GESTION_AGUINALDO').required = false;
                        document.querySelector('#PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#MENSAJE_PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#HA_PAGO_UNICO').required = false;
                        //*****APOSTILLA */
                        _hide("SUBTITLE_11_APOS_idd");
                        _hide("NUMERO_APOSTILLA_idd");
                        _hide("CARTA_APOSTILLA_idd");
                        _hide("TIENE_PODER_idd");
                        document.querySelector('#NUMERO_APOSTILLA').required = false;
                        document.querySelector('#CARTA_APOSTILLA').required = false;
                        document.querySelector('#TIENE_PODER').required = false;
                        document.querySelector('#CARTA_APOSTILLA_ID').required = false;


                    } else if (grupo4.includes(prestaacion_id)) //Masa Hereditaria – Declaratoria de Herederos
                    {
                        //alert('hhhhhh1');
                        //****BENIFICIARIO******* */
                        _hide("BE_DIFERENTE_AS_idd");
                        _hide("SUBTITULO_1_1_idd");
                        _hide("BE_TIPO_DOCUMENTO_idd");
                        _hide("BE_CI_idd");
                        _hide("BE_COMPLEMENTO_idd");
                        _hide("BE_BUSCAR_idd");
                        _hide("BE_NACIMIENTO_idd");
                        _hide("BE_CUA_idd");
                        _hide("BE_PRIMER_APELLIDO_idd");
                        _hide("BE_SEGUNDO_APELLIDO_idd");
                        _hide("BE_APELLIDO_CASADA_idd");
                        _hide("BE_PRIMER_NOMBRE_idd");
                        _hide("BE_SEGUNDO_NOMBRE_idd");
                        _hide("BE_ESTADO_CIVIL_idd");
                        _hide("BE_GENERO_idd");
                        _hide("BE_CELULAR_idd");
                        _hide("BE_PARENTESCO_idd");
                        _hide("BE_CI_DOC_idd");
                        document.querySelector('#BE_TIPO_DOCUMENTO').required = false;
                        document.querySelector('#BE_CI').required = false;
                        document.querySelector('#BE_PARENTESCO').required = false;
                        document.querySelector('#BE_ESTADO_CIVIL').required = false;
                        document.querySelector('#BE_CELULAR').required = false;
                        document.querySelector('#BE_CI_DOC').required = false;
                        document.querySelector('#BE_CI_DOC_ID').required = false;
                        //****TABLA DE BENIFICIARIO **********/
                        _hide("SUBTITULO_4_4_BE_idd");
                        _hide("GRILLA_DACO_idd");
                        //****FIN DE LA TABLA BENIFICIAIRO **/
                        //*****INICIO DE PODER*******/
                        _show("SUBTITLE_11_idd");
                        _show("NRO_PODER_SOL_1_idd");
                        _show("NRO_NOTARIA_SOL_1_idd");
                        _show("NOMBRE_NOTARIO_SOL_1_idd");
                        _show("FECHA_DE_EMISION_idd");
                        _show("DEPARTAMENTO_idd");
                        _show("MUNICIPIO_1_idd");
                        _show("NOMBRE_NOTARIO_SOL_1_idd");
                        _show("PODER_SCANER_idd");
                        _show("ESTADO_PODER_PRESENTADO_idd");
                        _show("OBSERVACION_PODER_idd");
                        _show("PODER_DIRNOPLU_idd");
                        _hide("SUBTITULO_4_4_idd");
                        _hide("GRILLA_DAHE_idd");
                        _show("EXTRANGERO_PODER_idd");
                        _show("PAIS_idd");
                        _hide("NRO_PODER_REVOCATORIO_idd");
                        document.querySelector('#NRO_PODER_SOL_1').required = true;
                        document.querySelector('#NRO_NOTARIA_SOL_1').required = true;
                        document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = true;
                        document.querySelector('#FECHA_DE_EMISION').required = true;
                        document.querySelector('#DEPARTAMENTO').required = true;
                        document.querySelector('#MUNICIPIO_1').required = true;
                        document.querySelector('#PODER_SCANER').required = true;
                        document.querySelector('#ESTADO_PODER_PRESENTADO').required = true;
                        document.querySelector('#PODER_DIRNOPLU').required = true;
                        document.querySelector('#EXTRANGERO_PODER').required = true;
                        document.querySelector('#PAIS').required = true;
                        document.querySelector('#NRO_PODER_REVOCATORIO').required = false;
                        //*****FIN DE PODER*********/ HEREDEROS
                        _show("SUBTITULO_4_4_1_idd");
                        _show("GRILLA_DAHERDERO_idd");
                        _show("ACEPTACION_DE_HERENCIA_idd");
                        _show("NRO_SENTENCIA_RESOLUCION_idd");
                        _show("JUZGADO_idd");
                        //******COBRO************ */
                        _hide("SUBTITULO_4_44_idd");
                        _hide("HA_PAGOS_SUPENDIDOS_idd");
                        _hide("TIPO_PODER_idd");
                        _hide("FORM_JUB_MES_INI_idd");
                        _hide("FORM_JUB_MES_FIN_idd");
                        _hide("HA_PAGO_AGUINALDO_idd");
                        _hide("HA_GESTION_AGUINALDO_idd");
                        _hide("PERIODOS_INGRESADOS_idd");
                        _hide("MENSAJE_PERIODOS_INGRESADOS_idd");
                        _hide("HA_PAGO_UNICO_idd");
                        document.querySelector('#HA_PAGOS_SUPENDIDOS').required = false;
                        document.querySelector('#TIPO_PODER').required = false;
                        document.querySelector('#FORM_JUB_MES_INI').required = false;
                        document.querySelector('#FORM_JUB_MES_FIN').required = false;
                        document.querySelector('#HA_PAGO_AGUINALDO').required = false;
                        document.querySelector('#HA_GESTION_AGUINALDO').required = false;
                        document.querySelector('#PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#MENSAJE_PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#HA_PAGO_UNICO').required = false;
                        //*****APOSTILLA */
                        _hide("SUBTITLE_11_APOS_idd");
                        _hide("NUMERO_APOSTILLA_idd");
                        _hide("CARTA_APOSTILLA_idd");
                        _hide("TIENE_PODER_idd");
                        document.querySelector('#NUMERO_APOSTILLA').required = false;
                        document.querySelector('#CARTA_APOSTILLA').required = false;
                        document.querySelector('#TIENE_PODER').required = false;
                        document.querySelector('#CARTA_APOSTILLA_ID').required = false;

                    }
                    else if (grupo6.includes(prestaacion_id)) //PODER REVOCATORIO
                    {

                        //****BENIFICIARIO******* */
                        _hide("BE_DIFERENTE_AS_idd");
                        _hide("SUBTITULO_1_1_idd");
                        _hide("BE_TIPO_DOCUMENTO_idd");
                        _hide("BE_CI_idd");
                        _hide("BE_COMPLEMENTO_idd");
                        _hide("BE_BUSCAR_idd");
                        _hide("BE_NACIMIENTO_idd");
                        _hide("BE_CUA_idd");
                        _hide("BE_PRIMER_APELLIDO_idd");
                        _hide("BE_SEGUNDO_APELLIDO_idd");
                        _hide("BE_APELLIDO_CASADA_idd");
                        _hide("BE_PRIMER_NOMBRE_idd");
                        _hide("BE_SEGUNDO_NOMBRE_idd");
                        _hide("BE_ESTADO_CIVIL_idd");
                        _hide("BE_GENERO_idd");
                        _hide("BE_CELULAR_idd");
                        _hide("BE_PARENTESCO_idd");
                        _hide("BE_CI_DOC_idd");
                        document.querySelector('#BE_TIPO_DOCUMENTO').required = false;
                        document.querySelector('#BE_CI').required = false;
                        document.querySelector('#BE_PARENTESCO').required = false;
                        document.querySelector('#BE_ESTADO_CIVIL').required = false;
                        document.querySelector('#BE_CELULAR').required = false;
                        document.querySelector('#BE_CI_DOC').required = false;
                        document.querySelector('#BE_CI_DOC_ID').required = false;
                        //****TABLA DE BENIFICIARIO **********/
                        _hide("SUBTITULO_4_4_BE_idd");
                        _hide("GRILLA_DACO_idd");
                        //****FIN DE LA TABLA BENIFICIAIRO **/
                        //*****INICIO DE PODER*******/
                        _show("SUBTITLE_11_idd");
                        _show("NRO_PODER_SOL_1_idd");
                        _show("NRO_NOTARIA_SOL_1_idd");
                        _show("NOMBRE_NOTARIO_SOL_1_idd");
                        _show("FECHA_DE_EMISION_idd");
                        _show("DEPARTAMENTO_idd");
                        _show("MUNICIPIO_1_idd");
                        _show("NOMBRE_NOTARIO_SOL_1_idd");
                        _show("PODER_SCANER_idd");
                        _show("ESTADO_PODER_PRESENTADO_idd");
                        _show("OBSERVACION_PODER_idd");
                        _show("PODER_DIRNOPLU_idd");
                        _hide("SUBTITULO_4_4_idd");
                        _hide("GRILLA_DAHE_idd");
                        _show("EXTRANGERO_PODER_idd");
                        _show("PAIS_idd");
                        _show("NRO_PODER_REVOCATORIO_idd");
                        document.querySelector('#NRO_PODER_SOL_1').required = true;
                        document.querySelector('#NRO_NOTARIA_SOL_1').required = true;
                        document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = true;
                        document.querySelector('#FECHA_DE_EMISION').required = true;
                        document.querySelector('#DEPARTAMENTO').required = true;
                        document.querySelector('#MUNICIPIO_1').required = true;
                        document.querySelector('#PODER_SCANER').required = true;
                        document.querySelector('#ESTADO_PODER_PRESENTADO').required = true;
                        document.querySelector('#PODER_DIRNOPLU').required = true;
                        document.querySelector('#EXTRANGERO_PODER').required = true;
                        document.querySelector('#PAIS').required = true;
                        document.querySelector('#NRO_PODER_REVOCATORIO').required = true;
                        //*****FIN DE PODER*********/ HEREDEROS
                        _hide("SUBTITULO_4_4_1_idd");
                        _hide("GRILLA_DAHERDERO_idd");
                        _hide("ACEPTACION_DE_HERENCIA_idd");
                        _hide("NRO_SENTENCIA_RESOLUCION_idd");
                        _hide("JUZGADO_idd");
                        //******COBRO************ */
                        _hide("SUBTITULO_4_44_idd");
                        _hide("HA_PAGOS_SUPENDIDOS_idd");
                        _hide("TIPO_PODER_idd");
                        _hide("FORM_JUB_MES_INI_idd");
                        _hide("FORM_JUB_MES_FIN_idd");
                        _hide("HA_PAGO_AGUINALDO_idd");
                        _hide("HA_GESTION_AGUINALDO_idd");
                        _hide("PERIODOS_INGRESADOS_idd");
                        _hide("MENSAJE_PERIODOS_INGRESADOS_idd");
                        _hide("HA_PAGO_UNICO_idd");
                        document.querySelector('#HA_PAGOS_SUPENDIDOS').required = false;
                        document.querySelector('#TIPO_PODER').required = false;
                        document.querySelector('#FORM_JUB_MES_INI').required = false;
                        document.querySelector('#FORM_JUB_MES_FIN').required = false;
                        document.querySelector('#HA_PAGO_AGUINALDO').required = false;
                        document.querySelector('#HA_GESTION_AGUINALDO').required = false;
                        document.querySelector('#PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#MENSAJE_PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#HA_PAGO_UNICO').required = false;
                        //*****APOSTILLA */
                        _hide("SUBTITLE_11_APOS_idd");
                        _hide("NUMERO_APOSTILLA_idd");
                        _hide("CARTA_APOSTILLA_idd");
                        _hide("TIENE_PODER_idd");
                        document.querySelector('#NUMERO_APOSTILLA').required = false;
                        document.querySelector('#CARTA_APOSTILLA').required = false;
                        document.querySelector('#TIENE_PODER').required = false;
                        document.querySelector('#CARTA_APOSTILLA_ID').required = false;

                    }
                    else if (grupo5.includes(prestaacion_id)) //Masa Hereditaria – Declaratoria de Herederos
                    {
                        //alert('hhhfffhhh1');
                        //****BENIFICIARIO******* */
                        _hide("BE_DIFERENTE_AS_idd");
                        _hide("SUBTITULO_1_1_idd");
                        _hide("BE_TIPO_DOCUMENTO_idd");
                        _hide("BE_CI_idd");
                        _hide("BE_COMPLEMENTO_idd");
                        _hide("BE_BUSCAR_idd");
                        _hide("BE_NACIMIENTO_idd");
                        _hide("BE_CUA_idd");
                        _hide("BE_PRIMER_APELLIDO_idd");
                        _hide("BE_SEGUNDO_APELLIDO_idd");
                        _hide("BE_APELLIDO_CASADA_idd");
                        _hide("BE_PRIMER_NOMBRE_idd");
                        _hide("BE_SEGUNDO_NOMBRE_idd");
                        _hide("BE_ESTADO_CIVIL_idd");
                        _hide("BE_GENERO_idd");
                        _hide("BE_CELULAR_idd");
                        _hide("BE_PARENTESCO_idd");
                        _hide("BE_CI_DOC_idd");
                        document.querySelector('#BE_TIPO_DOCUMENTO').required = false;
                        document.querySelector('#BE_CI').required = false;
                        document.querySelector('#BE_PARENTESCO').required = false;
                        document.querySelector('#BE_ESTADO_CIVIL').required = false;
                        document.querySelector('#BE_CELULAR').required = false;
                        document.querySelector('#BE_CI_DOC').required = false;
                        document.querySelector('#BE_CI_DOC_ID').required = false;
                        //****TABLA DE BENIFICIARIO **********/
                        _hide("SUBTITULO_4_4_BE_idd");
                        _hide("GRILLA_DACO_idd");
                        //****FIN DE LA TABLA BENIFICIAIRO **/
                        //*****INICIO DE PODER*******/
                        _show("SUBTITLE_11_idd");
                        _show("NRO_PODER_SOL_1_idd");
                        _show("NRO_NOTARIA_SOL_1_idd");
                        _show("NOMBRE_NOTARIO_SOL_1_idd");
                        _show("FECHA_DE_EMISION_idd");
                        _show("DEPARTAMENTO_idd");
                        _show("MUNICIPIO_1_idd");
                        _show("NOMBRE_NOTARIO_SOL_1_idd");
                        _show("PODER_SCANER_idd");
                        _show("ESTADO_PODER_PRESENTADO_idd");
                        _show("OBSERVACION_PODER_idd");
                        _show("PODER_DIRNOPLU_idd");
                        _show("EXTRANGERO_PODER_idd");
                        _show("PAIS_idd");
                        _show("SUBTITULO_4_4_idd");
                        _show("GRILLA_DAHE_idd");
                        _hide("NRO_PODER_REVOCATORIO_idd");
                        document.querySelector('#NRO_PODER_SOL_1').required = true;
                        document.querySelector('#NRO_NOTARIA_SOL_1').required = true;
                        document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = true;
                        document.querySelector('#FECHA_DE_EMISION').required = true;
                        document.querySelector('#DEPARTAMENTO').required = true;
                        document.querySelector('#MUNICIPIO_1').required = true;
                        document.querySelector('#PODER_SCANER').required = true;
                        document.querySelector('#ESTADO_PODER_PRESENTADO').required = true;
                        document.querySelector('#PODER_DIRNOPLU').required = true;
                        document.querySelector('#EXTRANGERO_PODER').required = true;
                        document.querySelector('#PAIS').required = true;
                        document.querySelector('#NRO_PODER_REVOCATORIO').required = false;
                        //*****FIN DE PODER*********/ HEREDEROS
                        _hide("SUBTITULO_4_4_1_idd");
                        _hide("GRILLA_DAHERDERO_idd");
                        _hide("ACEPTACION_DE_HERENCIA_idd");
                        _hide("NRO_SENTENCIA_RESOLUCION_idd");
                        _hide("JUZGADO_idd");
                        //******COBRO************ */
                        _hide("SUBTITULO_4_44_idd");
                        _hide("HA_PAGOS_SUPENDIDOS_idd");
                        _hide("TIPO_PODER_idd");
                        _hide("FORM_JUB_MES_INI_idd");
                        _hide("FORM_JUB_MES_FIN_idd");
                        _hide("HA_PAGO_AGUINALDO_idd");
                        _hide("HA_GESTION_AGUINALDO_idd");
                        _hide("PERIODOS_INGRESADOS_idd");
                        _hide("MENSAJE_PERIODOS_INGRESADOS_idd");
                        _hide("HA_PAGO_UNICO_idd");
                        document.querySelector('#HA_PAGOS_SUPENDIDOS').required = false;
                        document.querySelector('#TIPO_PODER').required = false;
                        document.querySelector('#FORM_JUB_MES_INI').required = false;
                        document.querySelector('#FORM_JUB_MES_FIN').required = false;
                        document.querySelector('#HA_PAGO_AGUINALDO').required = false;
                        document.querySelector('#HA_GESTION_AGUINALDO').required = false;
                        document.querySelector('#PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#MENSAJE_PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#HA_PAGO_UNICO').required = false;
                        //*****APOSTILLA */
                        _hide("SUBTITLE_11_APOS_idd");
                        _hide("NUMERO_APOSTILLA_idd");
                        _hide("CARTA_APOSTILLA_idd");
                        _hide("TIENE_PODER_idd");
                        document.querySelector('#NUMERO_APOSTILLA').required = false;
                        document.querySelector('#CARTA_APOSTILLA').required = false;
                        document.querySelector('#TIENE_PODER').required = false;
                        document.querySelector('#CARTA_APOSTILLA_ID').required = false;

                    }

                    else if (grupo1.includes(prestaacion_id)) //Masa Hereditaria – Declaratoria de Herederos
                    {
                        //alert('hhhhhh2');
                        //****BENIFICIARIO******* */
                        _hide("BE_DIFERENTE_AS_idd");
                        _hide("SUBTITULO_1_1_idd");
                        _hide("BE_TIPO_DOCUMENTO_idd");
                        _hide("BE_CI_idd");
                        _hide("BE_COMPLEMENTO_idd");
                        _hide("BE_BUSCAR_idd");
                        _hide("BE_NACIMIENTO_idd");
                        _hide("BE_CUA_idd");
                        _hide("BE_PRIMER_APELLIDO_idd");
                        _hide("BE_SEGUNDO_APELLIDO_idd");
                        _hide("BE_APELLIDO_CASADA_idd");
                        _hide("BE_PRIMER_NOMBRE_idd");
                        _hide("BE_SEGUNDO_NOMBRE_idd");
                        _hide("BE_ESTADO_CIVIL_idd");
                        _hide("BE_GENERO_idd");
                        _hide("BE_CELULAR_idd");
                        _hide("BE_PARENTESCO_idd");
                        _hide("BE_CI_DOC_idd");
                        document.querySelector('#BE_TIPO_DOCUMENTO').required = false;
                        document.querySelector('#BE_CI').required = false;
                        document.querySelector('#BE_PARENTESCO').required = false;
                        document.querySelector('#BE_ESTADO_CIVIL').required = false;
                        document.querySelector('#BE_CELULAR').required = false;
                        document.querySelector('#BE_CI_DOC').required = false;
                        document.querySelector('#BE_CI_DOC_ID').required = false;
                        //****TABLA DE BENIFICIARIO **********/
                        _hide("SUBTITULO_4_4_BE_idd");
                        _hide("GRILLA_DACO_idd");
                        //****FIN DE LA TABLA BENIFICIAIRO **/
                        //*****INICIO DE PODER*******/
                        _show("SUBTITLE_11_idd");
                        _show("NRO_PODER_SOL_1_idd");
                        _show("NRO_NOTARIA_SOL_1_idd");
                        _show("NOMBRE_NOTARIO_SOL_1_idd");
                        _show("FECHA_DE_EMISION_idd");
                        _show("DEPARTAMENTO_idd");
                        _show("MUNICIPIO_1_idd");
                        _show("NOMBRE_NOTARIO_SOL_1_idd");
                        _show("PODER_SCANER_idd");
                        _show("ESTADO_PODER_PRESENTADO_idd");
                        _show("OBSERVACION_PODER_idd");
                        _show("PODER_DIRNOPLU_idd");
                        _show("EXTRANGERO_PODER_idd");
                        _show("PAIS_idd");
                        _show("SUBTITULO_4_4_idd");
                        _show("GRILLA_DAHE_idd");
                        _hide("NRO_PODER_REVOCATORIO_idd");
                        document.querySelector('#NRO_PODER_SOL_1').required = true;
                        document.querySelector('#NRO_NOTARIA_SOL_1').required = true;
                        document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = true;
                        document.querySelector('#FECHA_DE_EMISION').required = true;
                        document.querySelector('#DEPARTAMENTO').required = true;
                        document.querySelector('#MUNICIPIO_1').required = true;
                        document.querySelector('#PODER_SCANER').required = true;
                        document.querySelector('#ESTADO_PODER_PRESENTADO').required = true;
                        document.querySelector('#PODER_DIRNOPLU').required = true;
                        document.querySelector('#EXTRANGERO_PODER').required = true;
                        document.querySelector('#PAIS').required = true;
                        document.querySelector('#NRO_PODER_REVOCATORIO').required = false;
                        //*****FIN DE PODER*********/ HEREDEROS
                        _show("SUBTITULO_4_4_1_idd");
                        _show("GRILLA_DAHERDERO_idd");
                        _hide("ACEPTACION_DE_HERENCIA_idd");
                        _hide("NRO_SENTENCIA_RESOLUCION_idd");
                        _hide("JUZGADO_idd");
                        //******COBRO************ */
                        _hide("SUBTITULO_4_44_idd");
                        _hide("HA_PAGOS_SUPENDIDOS_idd");
                        _hide("TIPO_PODER_idd");
                        _hide("FORM_JUB_MES_INI_idd");
                        _hide("FORM_JUB_MES_FIN_idd");
                        _hide("HA_PAGO_AGUINALDO_idd");
                        _hide("HA_GESTION_AGUINALDO_idd");
                        _hide("PERIODOS_INGRESADOS_idd");
                        _hide("MENSAJE_PERIODOS_INGRESADOS_idd");
                        _hide("HA_PAGO_UNICO_idd");
                        document.querySelector('#HA_PAGOS_SUPENDIDOS').required = false;
                        document.querySelector('#TIPO_PODER').required = false;
                        document.querySelector('#FORM_JUB_MES_INI').required = false;
                        document.querySelector('#FORM_JUB_MES_FIN').required = false;
                        document.querySelector('#HA_PAGO_AGUINALDO').required = false;
                        document.querySelector('#HA_GESTION_AGUINALDO').required = false;
                        document.querySelector('#PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#MENSAJE_PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#HA_PAGO_UNICO').required = false;
                        //*****APOSTILLA */
                        _hide("SUBTITLE_11_APOS_idd");
                        _hide("NUMERO_APOSTILLA_idd");
                        _hide("CARTA_APOSTILLA_idd");
                        _hide("TIENE_PODER_idd");
                        document.querySelector('#NUMERO_APOSTILLA').required = false;
                        document.querySelector('#CARTA_APOSTILLA').required = false;
                        document.querySelector('#TIENE_PODER').required = false;
                        document.querySelector('#CARTA_APOSTILLA_ID').required = false;
                    }
                    else if (grupo2.includes(prestaacion_id))// APOSTILLA
                    {
                        _hide("BE_DIFERENTE_AS_idd");
                        _hide("SUBTITULO_1_1_idd");
                        _hide("BE_TIPO_DOCUMENTO_idd");
                        _hide("BE_CI_idd");
                        _hide("BE_COMPLEMENTO_idd");
                        _hide("BE_BUSCAR_idd");
                        _hide("BE_NACIMIENTO_idd");
                        _hide("BE_CUA_idd");
                        _hide("BE_PRIMER_APELLIDO_idd");
                        _hide("BE_SEGUNDO_APELLIDO_idd");
                        _hide("BE_APELLIDO_CASADA_idd");
                        _hide("BE_PRIMER_NOMBRE_idd");
                        _hide("BE_SEGUNDO_NOMBRE_idd");
                        _hide("BE_ESTADO_CIVIL_idd");
                        _hide("BE_GENERO_idd");
                        _hide("BE_CELULAR_idd");
                        _hide("BE_PARENTESCO_idd");
                        _hide("BE_CI_DOC_idd");
                        document.querySelector('#BE_TIPO_DOCUMENTO').required = false;
                        document.querySelector('#BE_CI').required = false;
                        document.querySelector('#BE_PARENTESCO').required = false;
                        document.querySelector('#BE_ESTADO_CIVIL').required = false;
                        document.querySelector('#BE_CELULAR').required = false;
                        document.querySelector('#BE_CI_DOC').required = false;
                        document.querySelector('#BE_CI_DOC_ID').required = false;
                        //****TABLA DE BENIFICIARIO **********/
                        _hide("SUBTITULO_4_4_BE_idd");
                        _hide("GRILLA_DACO_idd");
                        //****FIN DE LA TABLA BENIFICIAIRO **/
                        //*****INICIO DE PODER*******/
                        _hide("SUBTITLE_11_idd");
                        _hide("NRO_PODER_SOL_1_idd");
                        _hide("NRO_NOTARIA_SOL_1_idd");
                        _hide("NOMBRE_NOTARIO_SOL_1_idd");
                        _hide("FECHA_DE_EMISION_idd");
                        _hide("DEPARTAMENTO_idd");
                        _hide("MUNICIPIO_1_idd");
                        _hide("NOMBRE_NOTARIO_SOL_1_idd");
                        _hide("PODER_SCANER_idd");
                        _hide("ESTADO_PODER_PRESENTADO_idd");
                        _hide("OBSERVACION_PODER_idd");
                        _hide("PODER_DIRNOPLU_idd");
                        _hide("EXTRANGERO_PODER_idd");
                        _hide("PAIS_idd");
                        _hide("SUBTITULO_4_4_idd");
                        _hide("GRILLA_DAHE_idd");
                        _hide("NRO_PODER_REVOCATORIO_idd");

                        document.querySelector('#NRO_PODER_SOL_1').required = false;
                        document.querySelector('#NRO_NOTARIA_SOL_1').required = false;
                        document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = false;
                        document.querySelector('#FECHA_DE_EMISION').required = false;
                        document.querySelector('#DEPARTAMENTO').required = false;
                        document.querySelector('#MUNICIPIO_1').required = false;
                        document.querySelector('#PODER_SCANER').required = false;
                        document.querySelector('#ESTADO_PODER_PRESENTADO').required = false;
                        document.querySelector('#PODER_DIRNOPLU').required = false;
                        document.querySelector('#EXTRANGERO_PODER').required = false;
                        document.querySelector('#PAIS').required = false;
                        document.querySelector('#NRO_PODER_REVOCATORIO').required = false;
                        //*****FIN DE PODER*********/ HEREDEROS
                        _hide("SUBTITULO_4_4_1_idd");
                        _hide("GRILLA_DAHERDERO_idd");
                        _hide("ACEPTACION_DE_HERENCIA_idd");
                        _hide("NRO_SENTENCIA_RESOLUCION_idd");
                        _hide("JUZGADO_idd");
                        //******COBRO************ */
                        _hide("SUBTITULO_4_44_idd");
                        _hide("HA_PAGOS_SUPENDIDOS_idd");
                        _hide("TIPO_PODER_idd");
                        _hide("FORM_JUB_MES_INI_idd");
                        _hide("FORM_JUB_MES_FIN_idd");
                        _hide("HA_PAGO_AGUINALDO_idd");
                        _hide("HA_GESTION_AGUINALDO_idd");
                        _hide("PERIODOS_INGRESADOS_idd");
                        _hide("MENSAJE_PERIODOS_INGRESADOS_idd");
                        _hide("HA_PAGO_UNICO_idd");
                        document.querySelector('#HA_PAGOS_SUPENDIDOS').required = false;
                        document.querySelector('#TIPO_PODER').required = false;
                        document.querySelector('#FORM_JUB_MES_INI').required = false;
                        document.querySelector('#FORM_JUB_MES_FIN').required = false;
                        document.querySelector('#HA_PAGO_AGUINALDO').required = false;
                        document.querySelector('#HA_GESTION_AGUINALDO').required = false;
                        document.querySelector('#PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#MENSAJE_PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#HA_PAGO_UNICO').required = false;
                        //*****APOSTILLA */
                        _show("SUBTITLE_11_APOS_idd");
                        _show("NUMERO_APOSTILLA_idd");
                        _show("CARTA_APOSTILLA_idd");
                        _show("TIENE_PODER_idd");
                        document.querySelector('#NUMERO_APOSTILLA').required = true;
                        document.querySelector('#CARTA_APOSTILLA').required = true;
                        document.querySelector('#TIENE_PODER').required = true;
                        document.querySelector('#CARTA_APOSTILLA_ID').required = true;

                    }
                    else  //a) Validación de Certificados y documentos
                    {
                        //alert('hhhhhh3');
                        //****BENIFICIARIO******* */
                        _show("BE_DIFERENTE_AS_idd");
                        _show("SUBTITULO_1_1_idd");
                        _show("BE_TIPO_DOCUMENTO_idd");
                        _show("BE_CI_idd");
                        _show("BE_COMPLEMENTO_idd");
                        _show("BE_BUSCAR_idd");
                        _show("BE_NACIMIENTO_idd");
                        _show("BE_CUA_idd");
                        _show("BE_PRIMER_APELLIDO_idd");
                        _show("BE_SEGUNDO_APELLIDO_idd");
                        _show("BE_APELLIDO_CASADA_idd");
                        _show("BE_PRIMER_NOMBRE_idd");
                        _show("BE_SEGUNDO_NOMBRE_idd");
                        _show("BE_ESTADO_CIVIL_idd");
                        _show("BE_GENERO_idd");
                        _show("BE_CELULAR_idd");
                        _show("BE_PARENTESCO_idd");
                        _show("BE_CI_DOC_idd");
                        document.querySelector('#BE_TIPO_DOCUMENTO').required = true;
                        document.querySelector('#BE_CI').required = true;
                        document.querySelector('#BE_PARENTESCO').required = true;
                        document.querySelector('#BE_ESTADO_CIVIL').required = true;
                        document.querySelector('#BE_CELULAR').required = true;
                        document.querySelector('#BE_CI_DOC').required = true;
                        document.querySelector('#BE_CI_DOC_ID').required = true;
                        //****TABLA DE BENIFICIARIO **********/
                        _hide("SUBTITULO_4_4_BE_idd");
                        _hide("GRILLA_DACO_idd");
                        //****FIN DE LA TABLA BENIFICIAIRO **/
                        //*****INICIO DE PODER*******/
                        _hide("SUBTITLE_11_idd");
                        _hide("NRO_PODER_SOL_1_idd");
                        _hide("NRO_NOTARIA_SOL_1_idd");
                        _hide("NOMBRE_NOTARIO_SOL_1_idd");
                        _hide("FECHA_DE_EMISION_idd");
                        _hide("DEPARTAMENTO_idd");
                        _hide("MUNICIPIO_1_idd");
                        _hide("NOMBRE_NOTARIO_SOL_1_idd");
                        _hide("PODER_SCANER_idd");
                        _hide("ESTADO_PODER_PRESENTADO_idd");
                        _hide("OBSERVACION_PODER_idd");
                        _hide("PODER_DIRNOPLU_idd");
                        _hide("EXTRANGERO_PODER_idd");
                        _hide("PAIS_idd");
                        _hide("NRO_PODER_REVOCATORIO_idd");

                        _hide("SUBTITULO_4_4_idd");
                        _hide("GRILLA_DAHE_idd");

                        document.querySelector('#NRO_PODER_SOL_1').required = false;
                        document.querySelector('#NRO_NOTARIA_SOL_1').required = false;
                        document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = false;
                        document.querySelector('#FECHA_DE_EMISION').required = false;
                        document.querySelector('#DEPARTAMENTO').required = false;
                        document.querySelector('#MUNICIPIO_1').required = false;
                        document.querySelector('#PODER_SCANER').required = false;
                        document.querySelector('#ESTADO_PODER_PRESENTADO').required = false;
                        document.querySelector('#PODER_DIRNOPLU').required = false;
                        document.querySelector('#EXTRANGERO_PODER').required = false;
                        document.querySelector('#PAIS').required = false;
                        document.querySelector('#NRO_PODER_REVOCATORIO').required = false;
                        //*****FIN DE PODER*********/ HEREDEROS
                        _hide("SUBTITULO_4_4_1_idd");
                        _hide("GRILLA_DAHERDERO_idd");
                        _hide("ACEPTACION_DE_HERENCIA_idd");
                        _hide("NRO_SENTENCIA_RESOLUCION_idd");
                        _hide("JUZGADO_idd");
                        //******COBRO************ */
                        _hide("SUBTITULO_4_44_idd");
                        _hide("HA_PAGOS_SUPENDIDOS_idd");
                        _hide("TIPO_PODER_idd");
                        _hide("FORM_JUB_MES_INI_idd");
                        _hide("FORM_JUB_MES_FIN_idd");
                        _hide("HA_PAGO_AGUINALDO_idd");
                        _hide("HA_GESTION_AGUINALDO_idd");
                        _hide("PERIODOS_INGRESADOS_idd");
                        _hide("MENSAJE_PERIODOS_INGRESADOS_idd");
                        _hide("HA_PAGO_UNICO_idd");
                        document.querySelector('#HA_PAGOS_SUPENDIDOS').required = false;
                        document.querySelector('#TIPO_PODER').required = false;
                        document.querySelector('#FORM_JUB_MES_INI').required = false;
                        document.querySelector('#FORM_JUB_MES_FIN').required = false;
                        document.querySelector('#HA_PAGO_AGUINALDO').required = false;
                        document.querySelector('#HA_GESTION_AGUINALDO').required = false;
                        document.querySelector('#PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#MENSAJE_PERIODOS_INGRESADOS').required = false;
                        document.querySelector('#HA_PAGO_UNICO').required = false;
                        //*****APOSTILLA */
                        _hide("SUBTITLE_11_APOS_idd");
                        _hide("NUMERO_APOSTILLA_idd");
                        _hide("CARTA_APOSTILLA_idd");
                        _hide("TIENE_PODER_idd");
                        document.querySelector('#NUMERO_APOSTILLA').required = false;
                        document.querySelector('#CARTA_APOSTILLA').required = false;
                        document.querySelector('#TIENE_PODER').required = false;
                        document.querySelector('#CARTA_APOSTILLA_ID').required = false;
                        document.querySelector('#PODER_SCANER_ID').required = false;
                        document.querySelector('#PODER_DIRNOPLU_ID').required = false;
                    }



                }
                const ci = document.getElementById("SOL_CI").value;
                const id_persona_sip = document.getElementById("SOL_IDPERSONA").value;
                const caso = this.cas_cod_id;
                const id_caso = this.cas_id;
                const prc_id = document.getElementById("AS_TIPO_EAP_LEGAL").value;

                this.$refs.legalComponent.getRequisitos(prestaacion_id, prc_id, this.registro);


            }


        },

        async vaciar() {
            this.campos.forEach(campo => {
                //var res = this.registro.cas_data_valores.find(item => item.frm_campo === 'GRILLA_DOCUMENTOS');
                if (campo.frm_campo == 'GRILLA_MRCHZ') {
                    campo.frm_value = [];
                }
            });
            await console.log("campos: ", this.campos);
        },

        async aumentar() {
            this.campos.forEach(campo => {
                //var res = this.registro.cas_data_valores.find(item => item.frm_campo === 'GRILLA_DOCUMENTOS');
                if (campo.frm_campo == 'GRILLA_MRCHZ') {
                    /*campo.frm_value = [
                        [{
                            "col_campo": "MRCHZ_CUMPLE",
                            "col_value": false
                        },{
                            "col_campo": "MRCHZ_DESCRIPCION",
                            "col_value": "HIJO"
                        }],[{
                            "col_campo": "MRCHZ_CUMPLE",
                            "col_value": false
                        },{
                            "col_campo": "MRCHZ_DESCRIPCION",
                            "col_value": "Coyuge"
                        }]
                    ]*/
                    const data = {
                        tipo_proceso: document.getElementById("AS_TIPO_EAP_LEGAL").value
                    };
                    axios.post('/api/reasons', data)
                        .then(response => {
                            // Verifica la estructura de los datos recibidos
                            if (Array.isArray(response.data.data)) {
                                console.log("Datos recibidos: ", response.data.data);

                                // Mapear los datos recibidos al formato necesario para frm_value
                                let transformedData = response.data.data.map(item => {
                                    if (item.text) {
                                        return [
                                            {
                                                "col_campo": "MRCHZ_CUMPLE",
                                                "col_value": false,
                                                "col_width": "10%"
                                            },
                                            {
                                                "col_campo": "MRCHZ_DESCRIPCION",
                                                "col_value": item.text, // Asegúrate de que 'lgp_nombre' existe
                                                "col_width": "90%"
                                            }
                                        ];
                                    } else {
                                        console.error("Error: 'lgp_nombre' no encontrado en item", item);
                                        return []; // Retornar una fila vacía si no existe 'lgp_nombre'
                                    }
                                });

                                // Asigna los datos transformados a frm_value
                                campo.frm_value = transformedData;
                            } else {
                                console.error("Error: Datos no son un array", response.data.data);
                            }
                            //this.showReasons = !this.showReasons;
                        })
                        .catch(error => {
                            console.error('Error al obtener las razones de rechazo', error);
                        });

                }
            });
            await console.log("campos: ", this.campos);
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
            var url = "/api/verDocumentoPdfNfs/" + ruta;
            const partes = nombre.split('.');
            const partes2 = nombre.split('/');
            axios.get(url, { responseType: 'blob' })
                .then(response => {
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
        listarRequisitosTitular() {
            const ci = document.getElementById("AS_CI").value;
            const data = {
                pdoc_codigo: this.cas_cod_id,
                pdoc_referencia: '0-TIT',
                pdoc_categoria: ci,
            };
            axios.post('api/existeDocumentosRequisitos', data)
                .then(response => {
                    let AS_TIPO_EAP = document.getElementById("AS_TIPO_EAP").value;
                    if (response.data == 1) {
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
                        axios.get(url).then(response => {
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
                })
                .catch(error => {
                    console.error('Error al generar o abrir el PDF', error);
                });
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

        listarRequisitosCi(datos, index, tipo) {

            console.log('listarRequisitosCi', datos, tipo);
            console.log('listarRequisitosCi', datos.length, tipo);
            this.limpiarRequisitos();
            const _COD_PROCESO = document.getElementById("_COD_PROCESO").value;
            console.log('_COD_PROCESO', _COD_PROCESO);
            this.index = index;
            this.tipo = tipo;
            let parentesco = '';
            let ci = 0;
            if (tipo == 'DACO_DOCUMENTOS') {
                const ci_ = 'DACO_CI_GRILLA_PROP' + index;
                ci = document.getElementById(ci_).value;
                console.log('ci', ci);
                parentesco = 'DACO';
            } else if (tipo == 'DAHERDERO_DOCUMENTOS') {
                const ci_ = 'DAHERDERO_CI_GRILLA_PROP' + index;
                ci = document.getElementById(ci_).value;
                parentesco = 'DAHERDERO';
                console.log('ci', ci);
            } else if (tipo == 'DAHE_DOCUMENTOS') {
                const ci_ = 'DAHE_CI_GRILLA_PROP' + index;
                ci = document.getElementById(ci_).value;
                parentesco = 'DAHE';
                console.log('ci', ci);
            }
            const data = {
                pdoc_codigo: this.cas_cod_id,
                pdoc_referencia: parentesco,
                pdoc_categoria: ci,
            };

            axios.post('api/existeDocumentosRequisitos', data)
                .then(response => {
                    if (response.data.codigoRespuesta.code == 200) {
                        const data = {
                            pdoc_codigo: this.cas_cod_id,
                            pdoc_referencia: parentesco,
                            pdoc_categoria: ci,
                        };
                        axios.post('api/obtenerDocumentosRequisitos', data)
                            .then(response => {
                                this.documento = response.data.data;
                            })
                            .catch(error => {
                                console.error('Error al generar o abrir el PDF', error);
                            });
                    }
                    else if (document.getElementById("AS_TIPO_EAP_LEGAL").value=="3"){
                        this.documento = [
                                {
                                    "id": 1,
                                    "descripcionTipoDocumentoRespaldo": "Cedula de Identidad",
                                    "documentoOriginalObligatorio": false,
                                    "presentacionObligatoria": true,
                                    "obs_id_observacion": 1,
                                }
                            ]
                    }
                    else {

                        const AS_TIPO_EAP_LEGAL = document.getElementById("AS_TIPO_EAP_LEGAL").value;
                        console.log('AS_TIPO_EAP_LEGAL', AS_TIPO_EAP_LEGAL);

                        var parentescolg = '';
                        var fallecidocolg = false;
                        //alert(datos[15].col_value);
                        if (datos[14].col_campo == 'DAHERDERO_PARENTESCO') {
                            //alert(1231);
                            parentescolg = datos[14].col_value;
                            if (datos[14].col_value == null) { alert('Debe ingresar el Parentesco'); }
                        }
                        if (datos[15].col_campo == 'ES_FALLECIDO') {

                            fallecidocolg = datos[15].col_value === '1' ? true : false; // Convierte '1' en true y cualquier otro valor en false
                            //alert(fallecidocolg);
                            console.log(datos[15].col_value, 'fallecidocolg', fallecidocolg);
                        }
                        if (datos.length == 18 && tipo == 'DAHERDERO_DOCUMENTOS') {

                            var datap = {
                                relacion: parentescolg,
                                es_fallecido: fallecidocolg,
                            };
                            console.log('data==', datap);

                            axios.post('api/lst_parentesco', datap)
                                .then(response => {
                                    this.documento = response.data.data;
                                })
                                .catch(error => {
                                    console.error('Error al generar o abrir el PDF', error);
                                });
                        } else if (datos.length == 18 && tipo == 'DACO_DOCUMENTOS') {


                            if (datos[15].col_campo == 'DACO_PARENTESCO') {

                                parentescolg = datos[15].col_value;
                                if (datos[15].col_value == null) { alert('Debe ingresar el Parentesco'); }
                            }


                            var datap = {
                                relacion: parentescolg,
                                es_fallecido: false,
                            };
                            console.log('data==', datap);

                            axios.post('api/lst_parentesco', datap)
                                .then(response => {
                                    this.documento = response.data.data;
                                })
                                .catch(error => {
                                    console.error('Error al generar o abrir el PDF', error);
                                });
                        }
                        else {
                            //alert('oooo');
                            var datap = {
                                relacion: parentescolg,
                                es_fallecido: fallecidocolg,
                            };
                            console.log('data==', datap);

                            axios.post('api/lst_sinparentesco', datap)
                                .then(response => {
                                    this.documento = response.data.data;
                                })
                                .catch(error => {
                                    console.error('Error al generar o abrir el PDF', error);
                                });

                        }





                        /*if (AS_TIPO_EAP_LEGAL == 12) {
                            this.documento = [
                                {
                                    "id": 1,
                                    "descripcionTipoDocumentoRespaldo": "Cedula de Identidad",
                                    "documentoOriginalObligatorio": false,
                                    "presentacionObligatoria": true,
                                    "obs_id_observacion": 1,
                                },
                                {
                                    "id": 2,
                                    "descripcionTipoDocumentoRespaldo": "Certificado de Nacimiento",
                                    "documentoOriginalObligatorio": false,
                                    "presentacionObligatoria": true,
                                    "obs_id_observacion": 1,
                                },
                                {
                                    "id": 3,
                                    "descripcionTipoDocumentoRespaldo": "Certificado de Matrimonio",
                                    "documentoOriginalObligatorio": false,
                                    "presentacionObligatoria": true,
                                    "obs_id_observacion": 1,
                                }
                            ]
                        } else {
                            this.documento = [
                                {
                                    "id": 1,
                                    "descripcionTipoDocumentoRespaldo": "Cedula de Identidad",
                                    "documentoOriginalObligatorio": false,
                                    "presentacionObligatoria": true,
                                    "obs_id_observacion": 1,
                                }
                            ]
                        }*/
                    }
                })
                .catch(error => {
                    console.error('Error al generar o abrir el PDF', error);
                });
        },

        listarRequisitos(datos, index) {
            console.log('dsadasdasdsad');
            this.limpiarRequisitos();
            const _COD_PROCESO = document.getElementById("_COD_PROCESO").value;
            console.log('_COD_PROCESO', _COD_PROCESO);
            let tam = 0;
            let tamInv = 0;
            let DH_FECHA_FALLECIDO = 'V';
            if (_COD_PROCESO == 'JUB') {
                tam = datos.length - 5; // PARENTESCO
                tamInv = datos.length - 6; // INVALIDO
                if (datos[0].col_value == '1') {
                    DH_FECHA_FALLECIDO = 'M';
                }
            } else if (_COD_PROCESO == 'RMIN') {
                tam = datos.length - 4; // PARENTESCO
                tamInv = datos.length - 5; // INVALIDO
            }
            else {
                tam = datos.length - 3; // PARENTESCO
                tamInv = datos.length - 4; // INVALIDO
            }

            if (_COD_PROCESO == 'MAHER' && datos[0].col_value == '1') {
                DH_FECHA_FALLECIDO = 'M';
            }
            if (_COD_PROCESO == 'LEGAL') {
                this.documento = [
                    {
                        "id": 1,
                        "descripcionTipoDocumentoRespaldo": "Cedula de Identidad ",
                        "documentoOriginalObligatorio": false,
                        "presentacionObligatoria": true,
                        "obs_id_observacion": 1,
                    }
                ]
            }

            var valor = datos[tam].col_value;
            var valorInv = datos[tamInv].col_value;

            if (valorInv == null || valorInv == '') {
                valorInv = false;
                //Swal.fire('Debe ingresar el Parentesco', '', 'warning');
            }
            if (valor == null || valor == undefined || valor == 'undefined' || valor == '') {
                valor = '';
                Swal.fire('Debe ingresar los datos de Parentesco y Estado de Invalidez', '', 'warning');
            }
            console.log('valoasasasasa', valor);

            this.codigoTipoParentesco = valor;
            let valores = valor.split("-");
            const valor2 = valores[1];
            this.index = index;
            const ci_ = 'DH_CI_GRILLA_PROP' + index;
            const ci = document.getElementById(ci_).value;
            console.log('ci', ci);
            //const url2 = this.urlGestora + "/spr-tram-rest/clasificador/documentosSolicitudPrestacionPorTipoPrestacionOrigenEstadoTitularParentesco?codigoTipoPrestacion=" + _COD_PROCESO + "&estadoTitular=" + est + "&codigoTipoParentesco=" + valor2 + "&estadoInvalidez=" + valorInv + "&estadoDefuncion=" + DH_FECHA_FALLECIDO;

            //console.log(url2);
            const data = {
                pdoc_codigo: this.cas_cod_id,
                pdoc_referencia: valor,
                pdoc_categoria: ci,
                //documentos_: url2
            };

            axios.post('api/existeDocumentosRequisitos', data)
                .then(response => {
                    if (response.data == 1) {
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
                        } else if (_COD_PROCESO == 'LEGAL') {
                            this.documento = [
                                {
                                    "id": 237,
                                    "descripcionTipoDocumentoRespaldo": "Cedula de Identidad ",
                                    "documentoOriginalObligatorio": false,
                                    "presentacionObligatoria": true,
                                    "obs_id_observacion": 1,
                                }
                            ]
                        }


                        else {
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
                console.log("this.caso.cas_registrado:  ", this.registro.cas_registrado);
                var d = new Date(this.registro.cas_registrado);
                console.log("Date:  ", d);
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
                setTimeout(() => {
                    this.listarRequisitosTitular();
                }, 100);
            });
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
                            "frm_value_label": dRes
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
            console.log("urls: ", this.urls);
        },

        guardarCasoyVolver(option) {
            this.asignarValores();
            // obtiene información de CAMPOS CLAVE
            var campos_clave = '';
            let a = 0;
            this.proceso_campos_clave.forEach(row => {
                var res = this.registro.cas_data_valores.find(item => item.frm_campo === row.prc_campo_clave);
                if (res) {
                    if (res.frm_value == null || res.frm_value == '') {
                        res.frm_value = '';
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
            if (this.actividad.act_orden == 50 && document.getElementById("_COD_PROCESO").value == 'MAHER') {
                const CVEAP_FIRMADO_ID = document.getElementById("CVEAP_FIRMADO_ID").value;
                const VALIDACION_LEGAL_ID = document.getElementById("VALIDACION_LEGAL_ID").value;
                if (CVEAP_FIRMADO_ID === null || CVEAP_FIRMADO_ID.trim() === ""
                    || VALIDACION_LEGAL_ID === null || VALIDACION_LEGAL_ID.trim() === "") {
                    Swal.fire({
                        title: 'Advertencia!',
                        text: 'RESPUESTA ASEGURADO: Debe subir los archivos de respaldo.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                    return;
                }
            }
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
                    Swal.fire('Por favor, guarde el documento que selecciono primero.', '', 'warning');
                }
            }
            let promiseTitular;
            if (continuar == 1) {
                this.mostrarOverlay();
                if (this.actividad.act_orden == 20 || this.actividad.act_orden == 100) {
                    this.mostrarOverlay();
                    const documentosHijo = this.$refs.legalComponent.documentosContratacionEventual;
                    console.log("Valor recibido del hijo:", documentosHijo);
                    // Actualizar el valor en el padre
                    this.documentosContratacionEventual = documentosHijo;
                    promiseTitular = this.registarArchivoTitularRequisitos();

                    /*setTimeout(() => {
                            // Acceder al hijo y obtener el valor

                        this.registarArchivoSolicitante();
                    }, 1000);

                    setTimeout(() => {
                        this.registarArchivoTitular();

                    }, 1000);*/
                    this.caso.ESTADO_DERIVACION = 'CERRADO';
                }
                //si pasa de la actividad 30 se adiciona el campo usuario_supervisor al json cas_data
                if (this.actividad.act_orden == 30 || this.actividad.act_orden == 32) {
                    this.caso.USUARIO_SUPERVISOR = this.usrUser;
                }

                if (this.actividad.act_orden == 40) {
                    this.caso.ABOGADO_REVISOR = this.usrUser;
                }

                this.asignarValores();
                // obtiene información de CAMPOS CLAVE
                var campos_clave = '';
                let a = 0;
                this.proceso_campos_clave.forEach(row => {
                    var res = this.registro.cas_data_valores.find(item => item.frm_campo === row.prc_campo_clave);
                    if (res) {
                        if (res.frm_value == null || res.frm_value == '') {
                            res.frm_value = '';
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

                try {
                    // Busca el objeto con frm_campo igual a "GRILLA_DAHE"
                    const objetoEncontrado = gRegistro.cas_data_valores.find(item => item.frm_campo === "GRILLA_DAHE");

                    if (!objetoEncontrado || !objetoEncontrado.frm_value) {
                        throw new Error("GRILLA_DAHE no encontrado o frm_value es undefined");
                    }

                    // Itera sobre las filas y elimina las vacías
                    for (let i = 0; i < objetoEncontrado.frm_value.length; i++) {
                        const fila = objetoEncontrado.frm_value[i];
                        for (let j = 0; j < fila.length; j++) {
                            const objeto = fila[j];
                            if (objeto.col_campo === "DAHE_IDPERSONA_GRILLA_PROP" && (objeto.col_value === null || objeto.col_value === "")) {
                                objetoEncontrado.frm_value.splice(i, 1);
                                i--; // Ajusta el índice después de eliminar la fila
                                break;
                            }
                        }
                    }
                } catch (error) {
                    console.error("Error al procesar GRILLA_DAHE:", error.message);
                    // Manejar el error aquí, por ejemplo, mostrando un mensaje de alerta o registrándolo
                }

                var url = "api/casos/" + this.cas_id;
                const promiseDOCUMENTComponent = this.registarArchivoRespaldo();
                const promiseActualizarCasoFormularioDinamico = axios.put(url, gRegistro);
                Promise.all([promiseDOCUMENTComponent, promiseActualizarCasoFormularioDinamico])
                    .then(([responseDOCUMENTComponent, responseActualizarCasoFormularioDinamico]) => {
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
                    .catch(error => {
                        this.ocultarOverlay();
                        Swal.fire('Error al guardar la informacion, intentelo de nuevo.', '', 'error');
                    });
                //his.ocultarOverlay();
            }
        },

        async registarArchivoTitularRequisitos() {
            const frm_dinamico_ci = document.getElementById("AS_CI").value;
            const frm_dinamico_persona_sip = document.getElementById("AS_IDPERSONA").value;
            const arregloDatos = [];
            console.log("rorororodocumentosContratacionEventual", this.documentosContratacionEventual);
            for (const element of this.documentosContratacionEventual) {
                if (element.front__has_change_record) {
                    const fileInput = document.getElementById(element.front__file_dom_id);
                    const file = fileInput.files[0];
                    const base64data = await processFile(file);
                    const datos = {
                        ...element,
                        cmp__file_base64data: base64data,
                        cmp__cas_id: this.cas_id,
                        cmp__user_id: window.Laravel.usr_id,
                        cmp__user_name: window.Laravel.usr_user,
                        cmp__categoria: frm_dinamico_ci,
                        cmp__id_persona_sip: frm_dinamico_persona_sip,
                        cmp__cas_cod_id: this.cas_cod_id,
                        cmp__cite: 'pendiente',
                        cmp__cas_act_id: this.cas_act_id
                    };
                    arregloDatos.push(datos);
                }
            }
            return axios.post('api/guardarDocumentosRequisitosNfs__CPE', arregloDatos);
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
                            if (res.frm_value == null || res.frm_value == '') {
                                res.frm_value = '';
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
            if (campo.frm_tipo == 'TITLE' || campo.frm_tipo == 'SUBTITLE' || campo.frm_tipo == 'GRID') {
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
            // }
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
        addScript(jsCode) { },
        addScript_v1(codigoScriptFormulario) {
            // codigo core
            var codigoScriptCore = `
					// Behavior
					function _show(campo) {
						document.getElementById(campo).style.display="block";
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
                })
                .catch(function (error) {
                    that.output = error;
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

        doDocumentoPdfAdjunto() {
            //let url2 = "api/casosHistorico/" + this.registro.cas_id;
            //axios.get(url2)
            //	.then((response) => {
            //const tamano = response.data.data.length - 1;
            //const datos = { htc_id: response.data.data[1].htc_id };
            const datos = { cas_id: this.registro.cas_id };
            axios.post('api/obtenerDocumentoAdjunto', datos)
                .then(response => {
                    this.documento = response.data.data;
                })
                .catch(error => {
                    console.error('Error al generar al listado', error);
                });
            //})
            //.catch(function (error) {
            //	that.output = error;
            //});
        },

        doDocumentoPdfAdjuntoMedico() {
            let url2 = "api/casosHistorico/" + this.registro.cas_id;
            axios.get(url2)
                .then((response) => {
                    const tamano = response.data.data.length - 1;
                    const datos = { htc_id: response.data.data[1].htc_id };
                    axios.post('api/obtenerDocumentoAdjuntoMedico', datos)
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
        // MENSAJES

        doImprimir1() {
            const datos = { cas_id: this.registro.cas_id };
            axios.post('api/generateFormRescepcionDocumento', datos)
                .then(response => {
                    // Obtener la ruta del archivo PDF desde la respuesta
                    const rutaPDF = response.data.ruta_pdf;
                    // Descargar el archivo PDF
                    this.pdfSrc = response.data;
                    //window.open(`api/downloadpdf?ruta=${rutaPDF}`, '_blank');
                })
                .catch(error => {
                    console.error('Error al generar o abrir el PDF', error);
                });
        },

        /*
          MAPA
        */

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
            })
                .addTo(this.map);
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

        registarArchivoCi() {

            this.mostrarOverlay();
            const arregloDatos = [];
            const tabla = document.getElementById("tabla_requisitos_ci");
            let parentesco = '';


            console.log('this.tipo', this.tipo);
            let ci_ = '';
            let id_sip_ = '';
            if (this.tipo == 'DACO_DOCUMENTOS') {
                ci_ = 'DACO_CI_GRILLA_PROP' + this.index;
                id_sip_ = 'DACO_IDPERSONA_GRILLA_PROP' + this.index;
                parentesco = 'DACO';
            } else if (this.tipo == 'DAHERDERO_DOCUMENTOS') {
                ci_ = 'DAHERDERO_CI_GRILLA_PROP' + this.index;
                id_sip_ = 'DAHERDERO_IDPERSONA_GRILLA_PROP' + this.index;
                parentesco = 'DAHERDERO';
            } if (this.tipo == 'DAHE_DOCUMENTOS') {
                ci_ = 'DAHE_CI_GRILLA_PROP' + this.index;
                id_sip_ = 'DAHE_IDPERSONA_GRILLA_PROP' + this.index;
                parentesco = 'DAHE';
            }
            const ci = document.getElementById(ci_).value;
            const id_sip = document.getElementById(id_sip_).value;

            console.log('ci', ci);
            console.log('id_sip', id_sip);

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
                        parentesco: parentesco,
                        switch: valorSwitch,
                        id_persona_sip: id_sip,
                        id_observacion: idObservacion,
                        detalle_documento: detalleDocumento,
                        usr_id: this.usrId,
                    };
                    arregloDatos.push(datos);
                } else {
                    const reader = new FileReader();
                    reader.onload = () => {
                        const base64data = reader.result.split(',')[1];
                        datos = {
                            tam: tam,
                            valor_id: valor_id,
                            valor_descripcion: valoe_descripcion,
                            pdf: base64data,
                            caso: this.cas_cod_id,
                            id_caso: this.cas_id,
                            documentoOriginalObligatorio: documentoOriginalObligatorio,
                            presentacionObligatoria: presentacionObligatoria,
                            ci: ci,
                            parentesco: parentesco,
                            switch: valorSwitch,
                            id_persona_sip: id_sip,
                            id_observacion: idObservacion,
                            detalle_documento: detalleDocumento,
                            usr_id: this.usrId,
                        };
                        arregloDatos.push(datos);
                    };
                    reader.readAsDataURL(file);
                }
                bandera++;
            };
            setTimeout(() => {
                axios.post('api/guardarDocumentosRequisitosNfs', arregloDatos)
                    .then(response => {
                        this.ocultarOverlay();
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Tus Documentos Fueron Guardados",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                    });
            }, 1500);
        },

        registarArchivo() {
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
                    const reader = new FileReader();
                    reader.onload = () => {
                        const base64data = reader.result.split(',')[1];
                        datos = {
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
                    };
                    reader.readAsDataURL(file);
                }
                bandera++;
            };
            setTimeout(() => {
                axios.post('api/guardarDocumentosRequisitosNfs', arregloDatos)
                    .then(response => {
                        this.ocultarOverlay();
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Tus Documentos Fueron Guardados",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                    });
            }, 1500);
        },

        registarArchivoTitular() {
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
                    const reader = new FileReader();
                    reader.onload = () => {
                        const base64data = reader.result.split(',')[1];
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
                    };
                    reader.readAsDataURL(file);
                }
                bandera++;
            }
            setTimeout(() => {
                axios.post('api/guardarDocumentosRequisitosNfs', arregloDatos)
                    .then(response => {
                        this.ocultarOverlay();
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Tus Documentos Fueron Guardados",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                    });
            }, 1500);
        },
        registarArchivoSolicitante() {
            const tabla = document.getElementById("tabla_solicitante");
            const filas = tabla.querySelectorAll("tr");
            const tam = filas.length - 1;
            var bandera = 0;
            const ci = document.getElementById("AS_CI").value;
            const id_persona_sip = document.getElementById("AS_IDPERSONA").value;
            const arregloDatos = [];
            for (var i = 0; i < tam; i++) {
                const id_switch = "switch_sol_" + bandera;
                const switchElement = document.querySelector(`#${id_switch}`);
                const valorSwitch = switchElement.checked;
                const descripcion = "descripcion_sol_" + bandera;
                const id = "id_sol_" + bandera;
                const documentoOriginalObligatorio_ = 'documentoOriginalObligatorio_sol_' + bandera;
                const presentacionObligatoria_ = 'presentacionObligatoria_sol_' + bandera;
                const valor_id = document.getElementById(id).value;
                const valoe_descripcion = document.getElementById(descripcion).value;
                const documentoOriginalObligatorio = document.getElementById(documentoOriginalObligatorio_).value;
                const presentacionObligatoria = document.getElementById(presentacionObligatoria_).value;
                const fileInput = document.getElementById('pdf_sol_' + bandera);
                const file = fileInput.files[0];
                const observacionSol = "id_observacion_sol_" + bandera;
                const idObservacionSol = document.getElementById(observacionSol).value;
                var detalleDocumento_sol = "detalleDocumento_sol_" + i;
                const detalleDocumentoSol = document.getElementById(detalleDocumento_sol).value;
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
                    const reader = new FileReader();
                    reader.onload = () => {
                        const base64data = reader.result.split(',')[1];
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
                    };
                    reader.readAsDataURL(file);
                }
                bandera++;
            }
            setTimeout(() => {
                axios.post('api/guardarDocumentosRequisitosNfs', arregloDatos)
                    .then(response => {
                        this.ocultarOverlay();
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Tus Documentos Fueron Guardados",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                    });
            }, 1500);
        },
        documentosAdjunto() {
            window.location.href = '/documentosAdjunto/' + this.registro.cas_id;
        },
        async registarArchivoRespaldo() {
            try {

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

            } catch (e) {
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
                        detalle_documento: '',
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
        renderPDF(base64, canvasContainer) {
            var pdfData = atob(base64);
            var pdfAsArray = new Uint8Array(pdfData.length);
            for (var i = 0; i < pdfData.length; i++) {
                pdfAsArray[i] = pdfData.charCodeAt(i);
            }
            // Carga del PDF
            pdfjsLib.getDocument({ data: pdfAsArray }).promise.then(function (pdf) {
                // Renderización de la primera página
                pdf.getPage(1).then(function (page) {
                    var viewport = page.getViewport({ scale: 1.0 });
                    var canvas = document.getElementById(canvasContainer);
                    var context = canvas.getContext("2d");
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(renderContext);
                });
            });
        },
        mostrarOverlay() {
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
        openDescripcionModal() {
            return;
        },
        verificarDerechoHabientes() {
            console.log('verificarDerechoHabientes');
            var idSeguimientoTramite = this.registro.cas_data.ID_SOLICITUDPRESTACION;
            //var idSeguimientoTramite = 2399;
            var urlVerificaTercerGrado = `${this.urlGestoraSgg}/otorgamiento-prestaciones-calculos/api/v1/definicion/verificarTercerGrado?idSeguimientoTramite=${idSeguimientoTramite}`;
            const that = this;
            axios.get(urlVerificaTercerGrado).then(response => {
                if (response.data.codigo === "0") {
                    if (response.data.cantidad !== null || response.data.cantidad > 0) {
                    } else {
                        document.getElementById('mensajeRespuestaContrato').textContent = 'El asegurado no cuenta con dercho-habientes de tercer grado. Se procedera a la "Emisión de Contrato para Firma del Asegurado"';
                        $('#btnRespuestaContrato').off('click').click(function () {
                            that.procesarContrato();
                        });
                        $('#modalRespuestaContrato').modal('show');
                    }
                } else {
                    document.getElementById('mensajeRespuestaContrato').textContent = 'Se tiene declarados a Derechohabientes de Tercer Grado. Se procedera al Calculo de los montos de la Pensión Solidaria de Vejez';
                    $('#modalRespuestaContrato').modal('show');
                    $('#btnRespuestaContrato').off('click').click(function () {
                        that.procesarMontos();
                    });
                }
            });

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
        }
        ,
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
                }
            });
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

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    /* Color oscuro con opacidad */
    z-index: 999;
    /* Asegura que esté sobre otros elementos */
    display: flex;
    justify-content: center;
    align-items: center;
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

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.modal-generico {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
    z-index: 9999;
}

.modal-generico-contenido {
    background-color: #fefefe;
    margin: 20% auto;
    padding: 0;
    border: 1px solid #888;
    width: 90%;
    max-width: 400px;
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

#modalGenerico-titulo {
    background-color: #007bff;
    /* Color de fondo del título */
    color: #fff;
    /* Color del texto del título */
    padding: 10px;
    /* Espaciado interno del título */
    margin: 0;
    /* Eliminar márgenes del título */
    font-size: 1.2em;
    /* Tamaño de fuente del título */
    text-transform: uppercase;
    /* Convertir texto a mayúsculas */
    font-weight: bold;
    /* Negrita */
}

#modalGenerico-mensaje {
    padding: 10px;
    color: #666;
    /* Color del texto del mensaje */
    font-size: 16px;
    /* Tamaño de fuente del mensaje */
}

.cerrar-btn {
    bottom: 80px;
    right: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.cerrar-btn:hover {
    background-color: #0056b3;
}

.gestora-grid-div-element {
    width: 100%;
}

.gestora-grid-input-element {
    width: 100%;
}
</style>

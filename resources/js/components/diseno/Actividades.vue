<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5>{{ plural }}</h5>

				<!-- Hola :3 -->

				<div class="row col-md-12">
					<div class="col-6">
						Catalogo:
						<select v-model="seleccionado" class="form-control mt3"
							@change="listarProcesos(); seleccionado2 = ''; registros = []; btnDiagrama = true; btnImprimir = true;"
							size="10">
							<option value="-1" disabled>-- Seleccione Catalogo --</option>
							<option v-for="item in catalogos" :value="item.cat_id">
								<span v-for="index in item.cat_codigo.length" :key="index">&nbsp;&nbsp;</span>
								[ {{ item.cat_codigo }} ] {{ item.cat_descripcion }}
							</option>
						</select>
					</div>
					<div class="col-6">
						Proceso:
						<select v-model="seleccionado2" class="form-control mt3"
							@change="listarRegistros(); btnDiagrama = false; btnImprimir = false;" size="10">
							<option value="-1" disabled>-- Seleccione Proceso --</option>
							<option v-for="item in procesos" :value="item.prc_id"> {{ item.prc_data.prc_descripcion }} (
								{{ item.prc_data.prc_codigo }} )</option>
						</select>
					</div>
				</div>
			</div>

			<div class="card-body">
				<div class="row">
					<div class="col-md-2">
						<button type="button" class="form-control btn btn-primary" @click="doSwVerDiagrama()"
							:disabled="btnDiagrama">{{ textSwVerCodigo }}</button>
					</div>
					<div class="col-md-2">
						<button type="button" class="form-control btn btn-primary" @click="doImprimir()"
							:disabled="btnImprimir">Imprimir</button>
					</div>
					<div class="col-md-8">
						<h5>{{ seleccionado2_label }}</h5>
					</div>
				</div>
				<br>
				<div v-if="!swVerDiagrama">
					<table class="table table-hover table-striped table-responsive" id="divTable">
						<thead class="thead-dark">
							<tr align="center">
								<th scope="col">#</th>
								<th>
									<button v-if="seleccionado2" class="btn btn-success form-control"
										@click="doLimpiar()" data-toggle="modal" data-target="#modalNuevo"
										title="Nuevo Proceso">
										<i class="fa fa-plus white" aria-hidden="true"></i> Nuevo
									</button>
								</th>
								<th scope="col"></th>
								<th scope="col">DESCRIPCIÓN / NODO</th>
								<th scope="col">ESTADO AVANCE</th>
								<th scope="col">MIN</th>
								<th scope="col">MAX</th>
								<th scope="col">SIG</th>
								<th scope="col">E</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(r, index) in registros">
								<td width="3%" scope="row">{{ r.act_id }}</td>
								<td width="15%" scope="row" align="center">
									<button type="button" class="btn btn-primary btn-circle btn-sm"
										v-on:click="doVer(index)" data-toggle="modal" data-target="#modalVer"
										title="Ver">
										<i class="fa fa-eye white" aria-hidden="true"></i>
									</button>
									<button type="button" class="btn btn-warning btn-circle btn-sm"
										v-on:click="doVer(index)" data-toggle="modal" data-target="#modalEditar"
										@change="listarEAvance()" title="Editar">
										<i class="fa fa-pen white" aria-hidden="true"></i>
									</button>
									<button type="button" class="btn btn-danger btn-circle btn-sm"
										v-on:click="doVer(index)" data-toggle="modal" data-target="#modalEliminar"
										title="Eliminar">
										<i class="fa fa-trash white" aria-hidden="true"></i>
									</button>
								</td>
								<td>
									{{ r.act_data.act_orden }}
									<i v-if="r.tact_codigo == 'I'" class="far fa-circle green" aria-hidden="true"></i>
									<i v-if="r.tact_codigo == 'A'" class="far fa-square blue" aria-hidden="true"></i>
									<i v-if="r.tact_codigo == 'B'" class="fas fa-project-diagram blue"
										aria-hidden="true"></i>
									<i v-if="r.tact_codigo == 'F'" class="far fa-circle red" aria-hidden="true"></i>
									<i v-if="r.tact_codigo == 'P'" class="fas fa-project-diagram orange"
										aria-hidden="true"></i>
								</td>
								<td>
									{{ r.act_data.act_descripcion }}
									<span v-if="r.act_data.act_tipo_derivacion == 'FORZADA'"
										class="badge badge-warning">{{ r.act_data.act_tipo_derivacion }}</span>
									<span v-else class="badge badge-secondary">{{ r.act_data.act_tipo_derivacion
										}}</span>
									<br>
									{{ r.nodo_codigo }} - {{ r.nodo_descripcion }}
								</td>
								<td align="center">
									{{ r.est_codigo }} - {{ r.est_descripcion }}
								</td>
								<td align="center">
									{{ r.act_data.act_duracion_horas_minimo }}
								</td>
								<td align="center">
									{{ r.act_data.act_duracion_horas_maximo }}
								</td>
								<td align="center">
									{{ r.act_data.act_siguiente }}
								</td>
								<td align="center">
									<span v-if="r.act_estado === 'A'" class="badge badge-success">ACTIVO</span>
									<span v-else-if="r.act_estado !== 'A'" class="badge badge-warning">{{
										r.act_estado }}</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="swVerDiagrama">
					<!--button type="button"
						@click="$refs.chart.add({id: +new Date(), x: 10, y: 10, name: 'New', type: 'operation', approvers: []})">
						Add
					</button>
					<button type="button" @click="$refs.chart.remove()">
						Del
					</button>
					<button type="button" @click="$refs.chart.editCurrent()">
						Edit
					</button>
					<button type="button" @click="$refs.chart.save()">
						Save
					</button-->
					<flowchart :nodes="nodes" :connections="connections" :width="'100%'" :height="'900px'" ref="chart">
					</flowchart>
				</div>
			</div>
		</div>

		<!-- modalVer -->
		<div class="modal fade" id="modalVer" tabindex="-1" role="dialog" aria-labelledby="modalVer" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Ver {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-4">
								<label>Orden</label>
								<input v-model="registro.act_data.act_orden" class="form-control" placeholder="Oeder"
									disabled>
							</div>
							<div class="col-md-4">
								<label>Tipo de Actividad</label>
								<select v-model="registro.act_tact_id" class="form-control"
									placeholder="Tipos de Actividades" disabled>
									<option value="-1">-- Seleccione Tipo de Actividad --</option>
									<option v-for="s in tactividades" v-bind:value="s.tact_id">
										{{ s.tact_descripcion }} - {{ s.tact_codigo }}
									</option>
								</select>
							</div>
							<div class="col-md-4">
								<label>Tipo Derivación</label>
								<select v-model="registro.act_tipo_derivacion" class="form-control"
									placeholder="Tipo Derivación" disabled>
									<option value="-1">-- Tipo Derivación --</option>
									<option value="SELF_SERVICE">Self Service</option>
									<option value="ROUND_ROBIN">Round Robin</option>
									<option value="FORZADA">Forzada</option>
									<option value="ENVIO">ENVÍO</option>
									<option value="ENMIENDA">Enmienda</option>
									<option value="UNION">UNION</option>
									<option value="UNITARIA">UNITARIA</option>
                                    <option value="REGIONAL">REGIONAL</option>
								</select>
							</div>
							<div class="col-md-6">
								<label>Nodo</label>
								<select v-model="registro.act_nodo_id" class="form-control" placeholder="Procesos"
									disabled>
									<option value="-1">-- Seleccione Nodo --</option>
									<option v-for="s in procesos" v-bind:value="s.nodo_id">
										{{ s.nodo_descripcion }} [{{ s.nodo_codigo }}]
									</option>
								</select>
							</div>
							<div class="col-md-6">
								<label>Estado Avance</label>
								<select v-model="registro.act_est_id" class="form-control" placeholder="Procesos"
									disabled>
									<option value="-1">-- Seleccione Estado de Avance --</option>
									<option v-for="s in estadosAvance" v-bind:value="s.est_id">
										{{ s.est_codigo }} [{{ s.est_descripcion }}]
									</option>
								</select>
							</div>

							<div class="col-md-12">
								<label>Descripción Actividad</label>
								<input v-model="registro.act_data.act_descripcion" class="form-control"
									placeholder="Descripción" disabled>
							</div>
							<div class="col-md-12">
								<label>Detalle (Procedimiento)</label>
								<textarea v-model="registro.act_data.act_detalle" class="form-control" rows="4"
									placeholder="Detalle (Procedimiento)" disabled></textarea>
							</div>
							<div class="col-md-4">
								<label>Siguiente Actividad</label>
								<input type="number" v-model="registro.act_data.act_siguiente" class="form-control"
									placeholder="Siguiente" disabled>
							</div>
							<div class="col-md-4">
								<label>Duración Mínimo (Horas)</label>
								<input type="number" v-model="registro.act_data.act_duracion_horas_minimo"
									class="form-control" placeholder="Duración en Horas Mínimo" disabled>
							</div>
							<div class="col-md-4">
								<label>Duración Máximo (Horas)</label>
								<input type="number" v-model="registro.act_data.act_duracion_horas_maximo"
									class="form-control" placeholder="Duración en Horas Máximo" disabled>
							</div>
							<div class="col-md-12">
								<hr>
							</div>
							<div class="col-md-12">
								<label>Reglas de Bifurcación</label>
								<textarea type="number" v-model="registro.act_data_reglas" class="form-control" rows="6"
									placeholder="Reglas de derivación [{}, {}]" disabled></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- modalNuevo -->
		<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevo"
			aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Nuevo {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div v-for="error in errores" class="col-md-3"><span class="badge badge-danger">{{ error
									}}</span></div>
						</div>
						<div class="row justify-content-left">
							<div class="col-md-4">
								<label>Orden Actividad (10, 20, etc.)</label>
								<input type="number" v-model="registro.act_data.act_orden" class="form-control"
									placeholder="Orden">
								<p v-if="!registro.act_data.act_orden" class="mensaje">Complete</p>
							</div>
							<div class="col-md-4">
								<label>Tipo de Actividad</label>
								<select v-model="registro.act_tact_id" class="form-control" @change="doVerBifurcacion()"
									placeholder="Tipos de Actividades">
									<option value="-1" disabled>-- Seleccione Tipo de Actividad --</option>
									<option v-for="s in tactividades" v-bind:value="s.tact_id">
										{{ s.tact_descripcion }} - {{ s.tact_codigo }}
									</option>
								</select>
								<p v-if="registro.act_tact_id == '-1'" class="mensaje">Seleccione</p>
							</div>
							<div class="col-md-4">
								<label>Tipo Derivación</label>
								<select v-model="registro.act_data.act_tipo_derivacion" class="form-control"
									placeholder="Tipo Derivación">
									<option value="-1">-- Tipo Derivación --</option>
									<option value="SELF_SERVICE">Self Service</option>
									<option value="ROUND_ROBIN">Round Robin</option>
									<option value="FORZADA">Forzada</option>
									<option value="ENVIO">ENVÍO</option>
									<option value="ENMIENDA">Enmienda</option>
									<option value="UNION">UNION</option>
									<option value="UNITARIA">UNITARIA</option>
                                    <option value="REGIONAL">REGIONAL</option>
								</select>
								<p v-if="['-1', '', ' ', null].includes(registro.act_data.act_tipo_derivacion)"
									class="mensaje">Seleccione</p>
							</div>
							<div class="col-md-6">
								<label>Nodo</label>
								<select v-model="registro.act_nodo_id" class="form-control" placeholder="Procesos">
									<option value="-1">-- Seleccione Nodo --</option>
									<option v-for="s in nodos" v-bind:value="s.nodo_id">
										{{ s.nodo_descripcion }} - {{ s.nodo_codigo }}
									</option>
								</select>
								<p v-if="registro.act_nodo_id == '-1'" class="mensaje">Seleccione</p>
							</div>

							<!-- Combo box Estado Avance -->
							<div class="col-md-6">
								<label>Estados de Avance</label>
								<select v-model="registro.act_est_id" class="form-control" placeholder="Estados Avance">
									<option value="-1">-- Seleccione Estado de Avance --</option>
									<option v-for="s in estadosAvance" v-bind:value="s.est_id">
										{{ s.est_codigo }} - {{ s.est_descripcion }}
										<!-- <v-select :options="estadosAvance.map(estado => ({ label: estado.est_descripcion, value: estado.est_id }))" v-model="vSelectedEstId" placeholder="Seleccionar un estado"></v-select>
									 -->
									</option>
								</select>
								<p v-if="registro.act_est_id == '-1'" class="mensaje">Seleccione</p>
							</div>
							<!-- FIN Combo box Estado Avance -->

							<div class="col-md-12">
								<label>Descripción Actividad</label>
								<input v-model="registro.act_data.act_descripcion" class="form-control"
									placeholder="Descripción">
								<p v-if="!registro.act_data.act_descripcion" class="mensaje">Complete</p>
							</div>
							<div class="col-md-12">
								<label>Detalle (Procedimiento)</label>
								<textarea v-model="registro.act_data.act_detalle" class="form-control" rows="4"
									placeholder="Detalle (Procedimiento)"></textarea>
							</div>
							<div class="col-md-4">
								<label>Siguiente Actividad (10, 20, etc, 9999 = 'Fin')</label>
								<input type="number" v-model="registro.act_data.act_siguiente" class="form-control"
									min="0" max="10000" placeholder="Siguiente">
								<p v-if="!registro.act_data.act_siguiente" class="mensaje">Complete</p>
							</div>
							<div class="col-md-4">
								<label>Duración Horas Mínimo</label>
								<input type="number" v-model="registro.act_data.act_duracion_horas_minimo"
									class="form-control" min="0" max="100" placeholder="Duración en Horas Mínimo"
									:disabled="[1, 4].includes(registro.act_tact_id)">
								<p v-if="!registro.act_data.act_duracion_horas_minimo && ![1, 4].includes(registro.act_tact_id)"
									class="mensaje">Complete</p>
							</div>
							<div class="col-md-4">
								<label>Duración Horas Máximo</label>
								<input type="number" v-model="registro.act_data.act_duracion_horas_maximo"
									class="form-control" min="0" max="100" placeholder="Duración en Horas Máximo"
									:disabled="[1, 4].includes(registro.act_tact_id)">
								<p v-if="!registro.act_data.act_duracion_horas_maximo && ![1, 4].includes(registro.act_tact_id)"
									class="mensaje">Complete</p>
							</div>
							<div class="col-md-12">
								<hr>
							</div>
							<div class="col-md-12">
								<label>Reglas de Bifurcación
									<button class="btn btn-primary btn-sm" v-on:click="doEjemplo()" title="ejemplo"
										:disabled="!swVerBifurcacion">
										<i class="far fa-lightbulb yellow" aria-hidden="true"></i> Ejemplo
									</button>
									<label style="font-size:smaller;"> Utilizar (`) en vez de (')</label>
								</label>
								<textarea type="number" v-model="registro.act_data_reglas" class="form-control" rows="6"
									placeholder="Reglas de derivación [{}, {}]"
									:disabled="!swVerBifurcacion"></textarea>
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
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Editar {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-4">
								<label>Orden Actividad (10, 20, etc.)</label>
								<input type="number" v-model="registro.act_data.act_orden" class="form-control"
									placeholder="Orden">
								<p v-if="!registro.act_data.act_orden" class="mensaje">Complete</p>
							</div>
							<div class="col-md-4">
								<label>Tipo de Actividad</label>
								<select v-model="registro.act_tact_id" class="form-control" @change="doVerBifurcacion()"
									placeholder="Tipos de Actividades">
									<option value="-1" disabled>-- Seleccione Tipo de Actividad --</option>
									<option v-for="s in tactividades" v-bind:value="s.tact_id">
										{{ s.tact_descripcion }} - {{ s.tact_codigo }}
									</option>
								</select>
								<p v-if="registro.act_tact_id == '-1'" class="mensaje">Seleccione</p>
							</div>
							<div class="col-md-4">
								<label>Tipo Derivación</label>
								<select v-model="registro.act_data.act_tipo_derivacion" class="form-control"
									placeholder="Tipo Derivación">
									<option value="-1">-- Tipo Derivación --</option>
									<option value="SELF_SERVICE">Self Service</option>
									<option value="ROUND_ROBIN">Round Robin</option>
									<option value="FORZADA">Forzada</option>
									<option value="ENVIO">ENVÍO</option>
									<option value="ENMIENDA">Enmienda</option>
									<option value="UNION">UNION</option>
									<option value="UNITARIA">UNITARIA</option>
                                    <option value="REGIONAL">REGIONAL</option>
								</select>
								<p v-if="['-1', '', ' ', null].includes(registro.act_data.act_tipo_derivacion)"
									class="mensaje">Seleccione</p>
							</div>
							<div class="col-md-6">
								<label>Nodo</label>
								<select v-model="registro.act_nodo_id" class="form-control" placeholder="Procesos">
									<option value="-1" disabled>-- Seleccione Nodo --</option>
									<option v-for="s in nodos" v-bind:value="s.nodo_id">
										{{ s.nodo_descripcion }} - {{ s.nodo_codigo }}
									</option>
								</select>
								<p v-if="registro.act_nodo_id == '-1'" class="mensaje">Seleccione</p>
							</div>

							<div class="col-md-6">
								<label>Estados de Avance</label>
								<select v-model="registro.act_est_id" class="form-control" placeholder="Estados Avance">
									<option value="-1" disabled>-- Seleccione Estado de Avance --</option>
									<option v-for="s in estadosAvance" v-bind:value="s.est_id">
										{{ s.est_codigo }} - {{ s.est_descripcion }}
									</option>
								</select>
								<p v-if="registro.act_est_id == '-1'" class="mensaje">Seleccione</p>
							</div>

							<div class="col-md-12">
								<label>Descripción Actividad</label>
								<input v-model="registro.act_data.act_descripcion" class="form-control"
									placeholder="Descripción">
								<p v-if="!registro.act_data.act_descripcion" class="mensaje">Complete</p>
							</div>
							<div class="col-md-12">
								<label>Detalle (Procedimiento)</label>
								<textarea v-model="registro.act_data.act_detalle" class="form-control" rows="4"
									placeholder="Detalle (Procedimiento)"></textarea>
							</div>
							<div class="col-md-4">
								<label>Siguiente Actividad (10, 20, etc, 9999 = 'Fin')</label>
								<input type="number" v-model="registro.act_data.act_siguiente" class="form-control"
									min="0" max="10000" placeholder="Siguiente">
								<p v-if="!registro.act_data.act_siguiente" class="mensaje">Complete</p>
							</div>
							<div class="col-md-4">
								<label>Duración Horas Mínimo</label>
								<input type="number" v-model="registro.act_data.act_duracion_horas_minimo"
									class="form-control" min="0" max="100" placeholder="Duración en Horas Mínimo"
									:disabled="[1, 4].includes(registro.act_tact_id)">
								<p v-if="!registro.act_data.act_duracion_horas_minimo && ![1, 4].includes(registro.act_tact_id)"
									class="mensaje">Complete</p>
							</div>
							<div class="col-md-4">
								<label>Duración Horas Máximo</label>
								<input type="number" v-model="registro.act_data.act_duracion_horas_maximo"
									class="form-control" min="0" max="100" placeholder="Duración en Horas Máximo"
									:disabled="[1, 4].includes(registro.act_tact_id)">
								<p v-if="!registro.act_data.act_duracion_horas_maximo && ![1, 4].includes(registro.act_tact_id)"
									class="mensaje">Complete</p>
							</div>
							<div class="col-md-12" v-show="swVerBifurcacion">
								<hr>
							</div>
							<div class="col-md-12" v-show="swVerBifurcacion">
								<label>Reglas de Bifurcación ó Paralelo
									<button class="btn btn-primary btn-sm" v-on:click="doEjemplo()" title="Ejemplo"
										:disabled="!swVerBifurcacion">
										<i class="far fa-lightbulb yellow" aria-hidden="true"></i> Ejemplo
									</button></label>
								<label style="font-size:smaller;"> Utilizar (`) en vez de (')
								</label>
								<textarea type="number" v-model="registro.act_data_reglas" class="form-control" rows="5"
									placeholder="Reglas de derivación [{}, {}]"
									:disabled="!swVerBifurcacion"></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
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
						<h5 class="modal-title" id="exampleModalLabel">Eliminar {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
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
	</div>
</template>

<script>
import datatables from 'datatables';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

//import Vue from 'vue';
//import FlowChart from 'flowchart-vue';
//Vue.use(FlowChart);

export default {
	name: 'actividades',
	data() {
		return {
			filtro: {},
			plural: 'Actividades',
			singular: 'Actividad',
			seleccionado: '',
			seleccionado2: '',
			seleccionado2_label: '',
			errores: [],
			registro: { act_data: {}, act_data_reglas: {}, act_data_form: {} },
			registros: [],
			procesos: [],
			tactividades: [],
			catalogos: [],
			nodos: [],
			estadosAvance: [],
			/* 			estadoAvance: [], */

			swVerBifurcacion: false,
			swVerDiagrama: false,
			textSwVerCodigo: 'Ver GRÁFICO',
			btnDiagrama: true,
			btnImprimir: true,

			dataTable: null,

			nodes: [

			],
			connections: [

			],

		}
	},

	mounted() {
		this.listarCatalogos();
		this.dataTable = $('#divTable').DataTable({ responsive: true });
	},

	created() {
	},

	methods: {
		listarRegistros() {
			let url = "api/actividades/" + this.seleccionado2;
			let that = this;
			axios.get(url).then(response => {
				this.registros = response.data.data; //twice data
				this.registros.forEach(function (row) {
					row.prc_data = JSON.parse(row.prc_data);
					row.act_data = JSON.parse(row.act_data);
					that.seleccionado2_label = '[' + row.prc_data.prc_codigo + '] ' + row.prc_data.prc_descripcion;
				});

				let x = 15, y = 20;
				let nodos = [], conectores = [], tipo = '', siguiente = 0;
				this.registros.forEach(function (row) {
					if (row.tact_codigo == 'I') { // inicio
						tipo = 'start';
						nodos.push({ id: row.act_id, x: x, y: y, name: row.act_data.act_orden, type: tipo, width: 50, height: 50 });
						x += 70; y += 0;
						siguiente = 1;
						siguiente = that.buscarSigNodo(row.act_data.act_siguiente);
						conectores.push({
							source: { id: row.act_id, position: 'right' },
							destination: { id: siguiente, position: 'left' },
							id: 1, type: 'pass',
						});
					} else if (row.tact_codigo == 'F') { // fin
						tipo = 'end';
						nodos.push({ id: row.act_id, x: x, y: y, name: row.act_data.act_orden, type: tipo, width: 50, height: 50 });
						x += 80; y += 70;
					} else if (row.tact_codigo == 'B') { // bifurcacion
						tipo = 'operation';
						nodos.push({ id: row.act_id, x: x, y: y, name: row.act_data.act_orden + '  <x>', type: tipo, width: 150, height: 50, approvers: [{ id: 1, name: row.act_data.act_descripcion }] });
						x += 85; y += 70;
						siguiente = 1;
						siguiente = that.buscarSigNodo(row.act_data.act_siguiente);
						conectores.push({
							source: { id: row.act_id, position: 'bottom' },
							destination: { id: siguiente, position: 'left' },
							id: 2, type: 'reject',
						});
						var reglas = JSON.parse(row.act_data_reglas);
						reglas.forEach(regla => {
							conectores.push({
								source: { id: row.act_id, position: 'right' },
								destination: { id: that.buscarSigNodo(regla.act_siguiente), position: 'top' },
								id: 3, type: 'pass',
							});
						});
					} else if (row.tact_codigo == 'P') { // paralelo
						tipo = 'operation';
						nodos.push({ id: row.act_id, x: x, y: y, name: row.act_data.act_orden + '  <+>', type: tipo, width: 150, height: 50, approvers: [{ id: 1, name: row.act_data.act_descripcion }] });
						x += 85; y += 70;
						siguiente = 1;
						siguiente = that.buscarSigNodo(row.act_data.act_siguiente);
						conectores.push({
							source: { id: row.act_id, position: 'bottom' },
							destination: { id: siguiente, position: 'left' },
							id: 4, type: 'reject',
						});
						var reglas = JSON.parse(row.act_data_reglas);
						reglas.forEach(regla => {
							conectores.push({
								source: { id: row.act_id, position: 'right' },
								destination: { id: that.buscarSigNodo(regla.act_siguiente), position: 'top' },
								id: 5, type: 'pass',
							});
						});
					} else if (row.tact_codigo == 'A') {
						tipo = 'operation';
						nodos.push({ id: row.act_id, x: x, y: y, name: row.act_data.act_orden, type: tipo, width: 150, height: 50, approvers: [{ id: 1, name: row.act_data.act_descripcion }] });
						x += 0; y += 70;
						siguiente = 1;
						siguiente = that.buscarSigNodo(row.act_data.act_siguiente);
						conectores.push({
							source: { id: row.act_id, position: 'bottom' },
							destination: { id: siguiente, position: 'top' },
							id: 6, type: 'pass',
						});
					}
				});
				setTimeout(() => {
					that.nodes = nodos;
					that.connections = conectores;
				}, 3000);
				this.listarEAvanceXProceso();
				this.listarNodos();
				this.listarTActividades();

			});
		},

		listarCatalogos() {
			let url = "api/catalogos/";
			axios.get(url).then(response => {
				this.catalogos = response.data.data; //twice data
				this.seleccionado2 = '';
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

		/* 			listarEAvance(){
						let url = "/api/estadosAvance/";
						axios.get(url).then(response => {
							this.estadoAvance = response.data.data; //twice data

						});
					}, */
		listarEAvanceXProceso() {
			let url = "/api/estadosAvance/" + this.seleccionado2;
			axios.get(url).then(response => {
				this.estadosAvance = response.data.data; //twice data

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
			this.doVerBifurcacion();
		},


		doLimpiar() {
			this.registro = {
				act_tact_id: "-1",
				act_nodo_id: "-1",
				act_est_id: "-1",
				act_tipo_derivacion: "-1",
				act_data: {},
				act_data_reglas: [],
				act_data_form: [],
			};
		},

		insertarRegistro(e) {
			this.errores = [];

			if (this.registro.act_data && !this.registro.act_data.act_orden) {
				this.errores.push('Falta Orden');
			}
			if (this.registro.act_tact_id == '-1') {
				this.errores.push('Falta Tipo de Actividad');
			}
			if (this.registro.act_nodo_id == '-1') {
				this.errores.push('Falta Nodo');
			}
			if (this.registro.act_est_id == '-1') {
				this.errores.push('Falta Estado de Avance');
			}
			if (['-1', '', ' ', null].includes(this.registro.act_data.act_tipo_derivacion)) {
				this.errores.push('Falta Tipo de Derivación');
			}
			if (this.registro.act_data && !this.registro.act_data.act_descripcion) {
				this.errores.push('Falta Descripción');
			}
			if (this.registro.act_data && !this.registro.act_data.act_siguiente) {
				this.errores.push('Falta Siguiente');
			}
			if (this.registro.act_data_reglas && !this.registro.act_data_reglas.length) {
				this.errores.push('Falta Reglas');
			}
			if (this.seleccionado == '-1') {
				this.errores.push('Falta Catalogo');
			}
			if (this.seleccionado2 == '-1') {
				this.errores.push('Falta Proceso');
			}

			if (this.errores.length === 0) {
				let gRegistro = this.registro;
				gRegistro.act_prc_id = this.seleccionado2;
				gRegistro.act_data = JSON.stringify(gRegistro.act_data);
				gRegistro.act_data_reglas = JSON.stringify(JSON.parse(gRegistro.act_data_reglas));
				gRegistro.act_data_form = JSON.stringify(gRegistro.act_data_form);
				let that = this;
				let url = "api/actividades";
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

			if (this.registro.act_data && !this.registro.act_data.act_orden) {
				this.errores.push('Falta Orden');
			}
			if (this.registro.act_tact_id == '-1') {
				this.errores.push('Falta Tipo de Actividad');
			}
			if (['-1', '', ' ', null].includes(this.registro.act_data.act_tipo_derivacion)) {
				this.errores.push('Falta Tipo de Derivación');
			}
			if (this.registro.act_nodo_id == '-1') {
				this.errores.push('Falta Nodo');
			}
			if (this.registro.act_est_id == '-1') {
				this.errores.push('Falta Estado de Avance');
			}
			if (this.registro.act_data && !this.registro.act_data.act_descripcion) {
				this.errores.push('Falta Descripción');
			}
			if (this.registro.act_data && !this.registro.act_data.act_siguiente) {
				this.errores.push('Falta Siguiente');
			}
			if (this.registro.act_data_reglas && !this.registro.act_data_reglas.length) {
				this.errores.push('Falta Reglas');
			}
			if (this.seleccionado == '-1') {
				this.errores.push('Falta Catalogo');
			}
			if (this.seleccionado2 == '-1') {
				this.errores.push('Falta Proceso');
			}

			if (this.errores.length === 0) {
				let gRegistro = this.registro;
				// gRegistro.srv_usr_id = 1;
				// gRegistro.trb_data = JSON.stringify(gRegistro.trb_data);
				gRegistro.act_data = JSON.stringify(gRegistro.act_data);
				gRegistro.act_data_reglas = JSON.stringify(JSON.parse(gRegistro.act_data_reglas));
				gRegistro.act_data_form = JSON.stringify(gRegistro.act_data_form);
				let that = this;
				let url = "api/actividades/" + gRegistro.act_id;
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
			gRegistro.act_usr_id = 1;

			let that = this;
			let url = "api/actividades/" + gRegistro.act_id;
			axios.post(url, gRegistro)
				.then(function (response) {
					that.output = response.data;
					that.listarRegistros();
				})
				.catch(function (error) {
					that.output = error;
				});
		},

		doVerBifurcacion() {
			let codigo = '';
			let that = this;
			this.tactividades.forEach(function (row) {
				if (row.tact_id == that.registro.act_tact_id) {
					codigo = row.tact_codigo;
				}
			});
			if (codigo == 'B') {
				this.swVerBifurcacion = true;
			} else if (codigo == 'P') {
				this.swVerBifurcacion = true;
			} else {
				this.swVerBifurcacion = false;
				that.registro.act_data_reglas = '[]';
			}
		},

		doEjemplo(tipoActividad) {
			let codigo = '';
			let that = this;
			this.tactividades.forEach(function (row) {
				if (row.tact_id == that.registro.act_tact_id) {
					codigo = row.tact_codigo;
				}
			});
			if (codigo == 'B') {
				this.registro.act_data_reglas = '['
					+ '{'
					+ '"act_regla": "`#RESULTADO_1#` == `1`", '
					+ '"act_siguiente": "40"'
					+ '}, {'
					+ '"act_regla": "`#RESULTADO_1#` == `2`", '
					+ '"act_siguiente": "60"'
					+ '}'
					+ ']';
			} else if (codigo == 'P') {
				this.registro.act_data_reglas = '['
					+ '{'
					+ '"act_regla": "true", '
					+ '"act_siguiente": "40"'
					+ '}, {'
					+ '"act_regla": "`#RESULTADO_1#` == `2`", '
					+ '"act_siguiente": "60"'
					+ '}'
					+ ']';
			} else {
				this.registro.act_data_reglas = '[]';
			}
		},

		doSwVerDiagrama() {
			this.swVerDiagrama = !this.swVerDiagrama;
			this.textSwVerCodigo = this.swVerDiagrama ? 'Ver LISTADO' : 'Ver GRÁFICO';
		},

		// --- funciones para Diagrama ---
		buscarSigNodo(sigNodo) {
			let resultado = 0;
			this.registros.forEach(function (row) {
				if (row.act_data.act_orden == sigNodo) {
					resultado = row.act_id;
				}
			});
			return resultado;
		},

		// --- Diagrama ---
		handleChartSave(nodes, connections) {
			// axios.post(url, {nodes, connections}).then(resp => {
			//   this.nodes = resp.data.nodes;
			//   this.connections = resp.data.connections;
			//   // Flowchart will refresh after this.nodes and this.connections changed
			// });
		},

		handleEditNode(node) {
			if (node.id === 2) {
				console.log(node.description);
			}
		},

		handleEditConnection(connection) {
		},

		handleDblClick(position) {
			this.$refs.chart.add({
				id: +new Date(),
				x: position.x,
				y: position.y,
				name: '100',
				type: 'operation',
				approvers: [{ id: 1, name: 'Solicitud' },],
			});
		},

		// --- Imprimir ---
		doImprimir() {
			let pdfName = 'procedimiento.pdf';
			var doc = new jsPDF({ orientation: "landscape", unit: "pt", format: "letter" });
			doc.setFontSize(9);
			doc.text('PROCESO: ', 40, 30);
			doc.text('VERSION: ' + this.registros[0].prc_data.prc_version, 40, 40);
			doc.text('CODIGO: ' + this.registros[0].prc_data.prc_codigo, 40, 50);
			doc.text('DESCRIPCION: ' + this.registros[0].prc_data.prc_descripcion, 40, 60);
			doc.autoTable({
				html: '#divTable',
				//startY: doc.lastAutoTable.finalY + 15,
				startY: 70,
				bodyStyles: { fontSize: 7 },
				columnStyles: { 0: { halign: 'center', fillColor: [0, 255, 0] } },
				margin: 40,
				rowPageBreak: 'auto',
				bodyStyles: { valign: 'top' },
				showHead: 'everyPage',
				showFoot: 'everyPage'
			});
			doc.save(pdfName);
		},


		doImprimir2() {
			let pdfName = 'procedimiento.pdf';
			var doc = new jsPDF({ orientation: "landscape", unit: "pt", format: "letter" });
			doc.setFontSize(9);
			var tabla = [];
			this.registros.forEach(function (row) {
				var fila = [];
				fila.push(row.act_data.act_orden);
				fila.push(row.act_data.act_descripcion);
				fila.push(row.act_data.act_duracion_horas);
				//fila.push(row.act_data.act_duracion_horas_minimo);
				//fila.push(row.act_data.act_duracion_horas_maximo);
				fila.push(row.act_data.act_descripcion);
				fila.push(row.act_data.act_siguiente);
				tabla.push(fila);
			});

			doc.autoTable({
				//head: head,
				body: this.tabla,
				//startY: doc.lastAutoTable.finalY + 15,
				//rowPageBreak: 'auto',
				//bodyStyles: { valign: 'top' },
			});
			/*
							var lin = 50;
							this.registros.forEach(function (row) {
								doc.text("Hello World", 10, lin);
								if (lin > maxLen) {
										doc.addPage();
									lin = 50;
								} else {
									lin += 10;
								}
							});
			*/
			doc.save(pdfName);
		},
	},


	beforeUpdate: function () {
		if (this.dataTable) {
			this.dataTable.destroy()
		}
	},

	updated: function () {
		this.dataTable = $("#divTable").DataTable({
			lengthMenu: [[25, 50, -1], [25, 50, "Todos"]],
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
		})
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

.combo {
	font-size: 10px;
	height: 25px !important;
}

.mensaje {
	font-size: 10px;
	color: #FF0000;
}
</style>

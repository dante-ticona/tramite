<template>
    <div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h5>Reporte de Legal</h5>
                    <div class="d-flex">
                        <button class="btn btn-primary mr-2" data-toggle="modal" data-target="#filtroModal">
                            <i class="fa fa-file-excel"></i> Generar Reportes Trámites
                        </button>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#ReportesModal">
                            <i class="fa fa-file-excel"></i> Generar Reportes por Personal
                        </button>
                    </div>
                </div>
                <!-- Modal Filtros -->
                <div class="modal fade" id="filtroModal" tabindex="-1" role="dialog" aria-labelledby="filtroModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document" style="max-width: 40%;">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="filtroModalLabel">Generación de Reportes Legal</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <!-- Fecha Inicial -->
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha-ini" class="font-weight-bold">Fecha Inicial</label>
                                        <input id="fecha-ini" type="date" class="form-control" v-model="filtro.fecha_ini">
                                    </div>
                                    <!-- Fecha Final -->
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha-fin" class="font-weight-bold">Fecha Final</label>
                                        <input id="fecha-fin" type="date" class="form-control" v-model="filtro.fecha_fin">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <!-- Loading Overlay -->
                                    <div id="overlay" ref="overlay" class="overlay" v-if="loading">
                                        <div class="loader"></div>
                                        <span class="loader-text">TramiteSIP</span>
                                        <span class="loading-text">Generando...</span>
                                    </div>
                                    <!-- Botones -->
                                    <div class="col-md-4 mb-2">
                                        <button class="btn btn-primary btn-block" @click="listarRegistrosReporte()">
                                            <i class="fa fa-search white" aria-hidden="true"></i> Buscar
                                        </button>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <button class="btn btn-success btn-block" @click="listarRegistrosReporte('GENERAR-EXCEL')">
                                            <i class="fa fa-file-excel" aria-hidden="true"></i> Generar Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Reportes -->
                <div class="modal fade" id="ReportesModal" tabindex="-1" role="dialog" aria-labelledby="ReportesModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document" style="max-width: 40%;">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="ReportesModalLabel">Generación de Reportes por Abogado</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Selector de Usuario -->
                                <div class="form-group">
                                    <label for="usuario-select" class="font-weight-bold">Seleccione un Usuario</label>
                                    <select id="usuario-select" v-model="filtro_personal.usuario" class="form-control" @change="handleUsuarioChange">
                                        <option value="">Seleccione un usuario</option>
                                        <option value="TODOS">TODOS LOS USUARIOS</option>
                                        <option v-for="usuario in usuariosLegal" :value="usuario.email" :key="usuario.email">
                                            {{ usuario.email }} - {{ usuario.name }} - {{ usuario.nodo_descripcion }}
                                        </option>
                                    </select>
                                    <span v-if="errorUsuario" class="text-danger mt-1 d-block">Debe seleccionar un usuario o TODOS.</span>
                                </div>

                                <!-- Registros Listados -->
                                <div v-if="filtro_personal.usuario" class="mt-3">
                                    <h5 class="font-weight-bold">Registros Listados Por:</h5>
                                    <div class="badge badge-secondary p-2">
                                        <ul class="list-unstyled mb-0">
                                            <li v-for="usuario in (typeof filtro_personal.usuario === 'string' ? filtro_personal.usuario.split(',') : [])" :key="usuario" class="mb-1">
                                                <i class="fa fa-user mr-2"></i>{{ usuario }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Filtros de Fecha -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <label for="fecha-ini" class="font-weight-bold">Fecha Inicial</label>
                                        <input id="fecha-ini" type="date" class="form-control" v-model="filtro_personal.fecha_ini_personal">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="fecha-fin" class="font-weight-bold">Fecha Final</label>
                                        <input id="fecha-fin" type="date" class="form-control" v-model="filtro_personal.fecha_fin_personal">
                                    </div>
                                </div>
                                <!-- Botones de Acción -->
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <button class="btn btn-primary btn-block" @click="buscarRegistrosXusuario()">
                                            <i class="fa fa-search white" aria-hidden="true"></i> Buscar
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-success btn-block" @click="buscarRegistrosXusuario('GENERAR-EXCEL-USUARIOS')">
                                            <i class="fa fa-file-excel" aria-hidden="true"></i> Generar Excel
                                        </button>
                                    </div>

                                </div>
                                <!-- Loading Overlay -->
                                <div id="overlay" ref="overlay" class="overlay" v-if="loading">
                                    <div class="loader"></div>
                                    <span class="loader-text">TramiteSIP</span>
                                    <span class="loading-text">Actualizando...</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Botones de control -->
                    <div class="d-flex justify-content-start align-items-center mb-3">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <button class="btn btn-outline-secondary nav-link d-flex align-items-center" @click="toggleMenu" style="border-radius: 5px; padding: 5px 10px;" title="Expandir / Contraer">
                                    <i class="fa fa-arrows-alt mr-2"></i> Expandir
                                </button>
                            </li>
                        </ul>
                        <div class="ml-3">
                            <button class="btn btn-outline-primary mr-2" @click="zoomInTable" style="border-radius: 5px; padding: 5px 10px;" title="Acercar Vista de Tabla">
                                <i class="fa fa-search-plus"></i> Acercar
                            </button>
                            <button class="btn btn-outline-primary" @click="zoomOutTable" style="border-radius: 5px; padding: 5px 10px;" title="Alejar Vista de Tabla">
                                <i class="fa fa-search-minus"></i> Alejar
                            </button>
                        </div>
                    </div>

                    <!-- Tabla para 'tramites' -->
                    <h3 class="text-center">{{ titulo_listado_tabla }}</h3>
                    <template v-if="this.tablaActual === 'abogados'">
                        <h4 v-show="this.filtro_personal.usuario"><br>
                            REGISTROS LISTADOS POR:
                            <div class="badge badge-secondary p-2">
                                <span>
                                    <i class="fa fa-user mr-2"></i>{{ filtro_personal.usuario.split(',').join(', ') }}
                                </span>
                            </div>
                            <div class="badge badge-primary p-2">
                                Nro Registros: {{ this.nroAtendidos }}
                            </div>
                        </h4>
                    </template>
                    <div v-if="tablaActual === 'tramites'" class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                        <div class="mb-2">
                            <span>Mostrando {{ paginatedRegistrosTramites.length }} de {{ registros.length }} registros</span>
                        </div>
                        <nav class="mb-2 w-100 d-flex justify-content-center align-items-center">
                            <button class="btn btn-secondary mx-2" @click="changePageTramites(currentPageTramites - 1)" :disabled="currentPageTramites === 1">
                                Anterior
                            </button>
                            <select class="form-control w-auto mx-2" v-model="currentPageTramites" @change="changePageTramites(Number(currentPageTramites))">
                                <option v-for="page in totalPagesTramites" :key="page" :value="page">
                                    Página {{ page }}
                                </option>
                            </select>
                            <button class="btn btn-secondary mx-2" @click="changePageTramites(currentPageTramites + 1)" :disabled="currentPageTramites === totalPagesTramites">
                                Siguiente
                            </button>
                        </nav>
                    </div>
                    <div class="table-responsive" :style="{ fontSize: tableZoom + 'px' }">
                        <table v-if="tablaActual === 'tramites'" class="table table-bordered table-hover table-striped background-container">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">NODO</th>
                                    <th scope="col">PROCESO<br>ACTIVIDAD</th>
                                    <th scope="col">DATOS DEL PODER</th>
                                    <th scope="col">FECHAS DERIVACIÓN</th>
                                    <th scope="col">TIPO DE TESTIMONIO</th>
                                    <th scope="col">DATOS SOLICITANTE</th>
                                    <th scope="col">DATOS BENEFICIARIO</th>
                                    <th scope="col">DATOS APODERADO</th>
                                    <th scope="col">ESTADO DERIVACIÓN</th>
                                    <th scope="col">NRO. CASO</th>
                                    <th scope="col">DATOS DEL ASEGURADO</th>
                                    <th scope="col">USUARIO</th>
                                    <th scope="col">REGISTRADO<br>MODIFICADO</th>
                                    <th scope="col">
                                        DEPARTAMENTO <br>
                                        REGIONAL <br>
                                        AGENCIA
                                    </th>
                                    <th>NOTARIA O TESTIMONIO </th>
                                    <th scope="col">ESTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(r, index) in paginatedRegistrosTramites" :key="r.cas_id">
                                    <td width="3%" scope="row">{{ (currentPageTramites - 1) * itemsPerPageTramites + index + 1 }}</td>
                                    <td>
                                        <span class="badge badge-success">{{ r.nodo_codigo }}</span> -
                                        {{ r.nodo_descripcion }}
                                        <span class="badge badge-success">{{ r.cas_estado }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ r.prc_descripcion }}</strong><br>
                                        <span class="badge badge-dark">{{ r.act_orden }}</span> - {{ r.act_descripcion }} <br>
                                        <span class="badge badge-primary" v-if="r.nombre_prestacion">
                                            {{ r.nombre_prestacion }}
                                        </span> <br>

                                        <div class="expandable">
                                            <div style="display: flex; align-items: center;" @click="toggle(r.cas_id)">
                                                <span class="badge" style="background: linear-gradient(45deg, #EDCD4E, #f2dd9b); font-size: 0.8em; margin-right: 5px;">
                                                    <strong class="break-text"> {{ r.tipo_eap_legal }} </strong>
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
                                                                {{ r.as_sub_solicitud_value }}
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="badge" style="background: linear-gradient(45deg, #EDCD4E, #f2dd9b); font-size: 0.8em;">
                                                                {{ r.as_tipo_eap_value }}
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </transition>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span><strong>Nro. Poder</strong></span>
                                                <span class="badge badge-secondary"> {{ r.nro_poder_sol || 'N/A' }} </span>
                                            </div>
                                            <div>
                                                <span><strong>Nro. Notaria</strong></span>
                                                <span class="badge badge-warning"> {{ r.nro_notaria_sol || 'N/A' }} </span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span><strong>Nom. Notario</strong></span> <br>
                                                <span class="badge badge-primary break-text"> {{ r.nombre_notario_sol || 'N/A' }} </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column align-items-start">
                                            <div class="mt-2">
                                                <span><strong>Fecha Derivación ASIG-LE</strong></span>
                                                <span class="badge badge-secondary"> {{ r.fecha_derivacion_legal ? r.fecha_derivacion_legal.substring(0, 18) : 'N/A' }} </span>
                                            </div>

                                            <hr style="border: 0.2px solid #ccc; width: 100%;">

                                            <div>
                                                <span><strong>Fecha Derivación Abg</strong></span>
                                                <span class="badge badge-secondary"> {{ r.fecha_derivacion_abg? r.fecha_derivacion_abg.substring(0,18) : 'N/A' }} </span>
                                            </div>

                                            <hr style="border: 0.2px solid #ccc; width: 100%;">

                                            <div>
                                                <span><strong>Fecha Derivación GNL</strong></span>
                                                <span class="badge badge-secondary"> {{ r.fecha_derivacion_gnl? r.fecha_derivacion_gnl.substring(0,18): 'N/A' }} </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary" v-if="r.extranjero_poder === '1'"> EXTRANJERO </span>
                                        <span class="badge badge-secondary" v-if="r.extranjero_poder === '2'"> NACIONAL </span>
                                        <span class="badge badge-warning" v-if="r.extranjero_poder === '3'"> PROTOCOLIZADO </span>
                                    </td>
                                    <td>
                                        {{ r.sol_ci }} <br>
                                        {{ r.sol_primer_apellido }} <br>
                                        {{ r.sol_segundo_apellido }} <br>
                                        {{ r.sol_primer_nombre }}
                                    </td>
                                    <td>
                                        <button v-if="r.grilla_daco" class="btn btn-info btn-sm" @click="openGrillaDacoModal(r.grilla_daco)" style="color:#ffffff;">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button v-if="r.grilla_dahe" class="btn btn-success btn-sm" @click="openGrillaDaheModal(r.grilla_dahe)" style="color:#ffffff;">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                    <td> {{ r.estado_derivacion }}</td>
                                    <td>
                                        {{ r.cas_cod_id }} <br>
                                        <span class="badge badge-secondary" style="text-align: left;" v-if="r.cas_nro_caso">
                                            CUA : {{ r.cua }}
                                        </span>
                                    </td>
                                    <td>
                                        <span v-html="r.cas_nombre_caso"></span>
                                    </td>
                                    <td>{{ r.nom_usuario }}</td>
                                    <td>
                                        <i class="fas fa-calendar"></i> {{ r.cas_registrado.substr(0, 16) }} <br>
                                        <i class="fas fa-clock"></i> {{ r.cas_modificado ? r.cas_modificado.substr(0, 16) : '-' }}
                                    </td>
                                    <td>
                                        {{ r.cas_departamento }} <br>
                                        {{ r.cas_regional }} <br>
                                        {{ r.cas_agencia }} <br>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary" v-if="r.testimonio_judicial !== null"> {{ r.testimonio_judicial }} </span>
                                        <span class="badge badge-secondary" v-else>NO</span>
                                    </td>
                                    <td><span class="badge badge-warning">{{ r.est_codigo }}</span></td>
                                </tr>
                                <tr v-if="!paginatedRegistrosTramites.length">
                                    <td colspan="10" class="text-center">No hay registros disponibles</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="tablaActual === 'tramites'" class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                        <div class="mb-2">
                            <span>Mostrando {{ paginatedRegistrosTramites.length }} de {{ registros.length }} registros</span>
                        </div>
                        <nav class="mb-2 w-100 d-flex justify-content-center align-items-center">
                            <button class="btn btn-secondary mx-2" @click="changePageTramites(currentPageTramites - 1)" :disabled="currentPageTramites === 1">
                                Anterior
                            </button>
                            <select class="form-control w-auto mx-2" v-model="currentPageTramites" @change="changePageTramites(Number(currentPageTramites))">
                                <option v-for="page in totalPagesTramites" :key="page" :value="page">
                                    Página {{ page }}
                                </option>
                            </select>
                            <button class="btn btn-secondary mx-2" @click="changePageTramites(currentPageTramites + 1)" :disabled="currentPageTramites === totalPagesTramites">
                                Siguiente
                            </button>
                        </nav>
                    </div>
                    <!-- Tabla para 'abogado' o lista de abogados -->

                    <div class="table-responsive" :style="{ fontSize: tableZoom + 'px' }">
                        <table v-if="tablaActual === 'abogados'" class="table table-bordered table-hover table-striped table-responsive background-container">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">NODO</th>
                                    <th scope="col">PROCESO<br>ACTIVIDAD</th>
                                    <th scope="col">DATOS DEL PODER</th>
                                    <th scope="col">FECHAS DERIVACIÓN</th>
                                    <th scope="col">TIPO DE TESTIMONIO</th>
                                    <th scope="col">DATOS SOLICITANTE</th>
                                    <th scope="col">DATOS BENEFICIARIO</th>
                                    <th scope="col">DATOS APODERADO</th>
                                    <th scope="col">ESTADO DERIVACIÓN</th>
                                    <th scope="col">NRO. CASO</th>
                                    <th scope="col">CAMPOS CLAVE</th>
                                    <th scope="col">USUARIO</th>
                                    <th scope="col">REGISTRADO<br>MODIFICADO</th>
                                    <th scope="col">DEPARTAMENTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(abg, index) in paginatedRegistrosAbogados" :key="index">
                                    <td width="3%" scope="row">{{ (currentPageAbogados - 1) * itemsPerPageAbogados + index + 1 }}</td>
                                    <td>
                                        <span class="badge badge-success">{{ abg.nodo_codigo }}</span> -
                                            {{ abg.nodo_descripcion }}
                                        <span class="badge badge-success">{{ abg.cas_estado }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ abg.prc_descripcion }}</strong><br>
                                        <span class="badge badge-dark">{{ abg.act_orden }}</span> - {{ abg.act_descripcion }} <br>
                                        <span class="badge badge-primary" v-if="abg.nombre_prestacion">
                                            {{ abg.nombre_prestacion }}
                                        </span> <br>
                                        <span class="badge badge-secondary" v-if="abg.tipo_eap_legal">
                                            {{ abg.tipo_eap_legal }}
                                        </span> <br>
                                        <span class="badge" style="background: linear-gradient(45deg, #EDCD4E, #f2dd9b); font-size: 0.8em; margin-right: 5px; margin-bottom: 5px;">
                                            {{ abg.as_sub_solicitud_value }}
                                        </span> <br>
                                        <span class="badge" style="background: linear-gradient(45deg, #03A9F4, #81D4FA); font-size: 0.8em; margin-right: 5px; margin-top: 5px;">
                                            {{ abg.as_tipo_eap_value }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span><strong>Nro. Poder</strong></span>
                                                <span class="badge badge-secondary"> {{ abg.nro_poder_sol || 'N/A' }} </span>
                                            </div>
                                            <div>
                                                <span><strong>Nro. Notaria</strong></span>
                                                <span class="badge badge-warning"> {{ abg.nro_notaria_sol || 'N/A' }} </span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span><strong>Nom. Notario</strong></span> <br>
                                                <span class="badge badge-primary break-text"> {{ abg.nombre_notario_sol || 'N/A' }} </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column align-items-start">
                                            <div class="mt-2">
                                                <span><strong>Fecha Derivación ASIG-LE</strong></span>
                                                <span class="badge badge-secondary"> {{ abg.fecha_derivacion_legal? abg.fecha_derivacion_legal.substring(0,18): 'N/A' }} </span>
                                            </div>

                                            <hr style="border: 0.2px solid #ccc; width: 100%;">

                                            <div>
                                                <span><strong>Fecha Derivación Abg</strong></span>
                                                <span class="badge badge-secondary"> {{ abg.fecha_derivacion_abg? abg.fecha_derivacion_abg.substring(0,18): 'N/A' }} </span>
                                            </div>

                                            <hr style="border: 0.2px solid #ccc; width: 100%;">

                                            <div>
                                                <span><strong>Fecha Derivación GNL</strong></span>
                                                <span class="badge badge-secondary"> {{ abg.fecha_derivacion_gnl? abg.fecha_derivacion_gnl.substring(0,18) : 'N/A' }} </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary" v-if="abg.extranjero_poder === '1'"> EXTRANJERO </span>
                                        <span class="badge badge-secondary" v-if="abg.extranjero_poder === '2'"> NACIONAL </span>
                                        <span class="badge badge-warning" v-if="abg.extranjero_poder === '3'"> PROTOCOLIZADO </span>
                                    </td>
                                    <td>
                                        {{ abg.sol_ci }} <br>
                                        {{ abg.sol_primer_apellido }} <br>
                                        {{ abg.sol_segundo_apellido }} <br>
                                        {{ abg.sol_primer_nombre }}
                                    </td>
                                    <td>
                                        <button v-if="abg.grilla_daco" class="btn btn-info btn-sm" @click="openGrillaDacoAbgModal(abg.grilla_daco)" style="color:#ffffff;">
                                            <i class="fa fa-eye"></i> Ver Detalles
                                        </button>
                                    </td>
                                    <td>
                                        <button v-if="abg.grilla_dahe" class="btn btn-success btn-sm" @click="openGrillaDaheAbgModal(abg.grilla_dahe)" style="color:#ffffff;">
                                            <i class="fa fa-eye"></i> Ver Detalles
                                        </button>
                                    </td>
                                    <td> {{ abg.estado_derivacion }}</td>
                                    <td>
                                        {{ abg.cas_cod_id }} <br>
                                        <span class="badge badge-secondary" style="text-align: left;" v-if="abg.cas_nro_caso">
                                            CUA : {{ abg.cua }}
                                        </span>
                                    </td>
                                    <td>
                                        <span v-if="abg.cas_nombre_caso.includes('|')" v-html="abg.cas_nombre_caso.split('|').join(' ')"></span>
                                        <span v-else v-html="abg.cas_nombre_caso.split('<br/>').join(' ')"></span>
                                    </td>
                                    <td>{{ abg.nom_usuario }}</td>
                                    <td>
                                        <span class="badge badge-primary">
                                            <i class="fas fa-calendar"></i> {{ abg.cas_registrado.substr(0, 16) }} <br>
                                        </span>
                                        <hr></hr>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> {{ abg.cas_modificado ? abg.cas_modificado.substr(0, 16) : '-' }}
                                        </span>
                                    </td>
                                    <td>{{ abg.cas_departamento }}</td>
                                </tr>
                                <tr v-if="!paginatedRegistrosAbogados.length">
                                    <td colspan="4" class="text-center">No hay registros disponibles</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="tablaActual === 'abogados'" class="d-flex justify-content-center align-items-center mt-3 flex-wrap">
                        <div class="mb-2">
                            <span>Mostrando {{ paginatedRegistrosAbogados.length }} de {{ tramitesXusuario.length }} registros</span>
                        </div>
                        <nav class="mb-2 w-100 d-flex justify-content-center align-items-center">
                            <button class="btn btn-secondary mx-2" @click="changePageAbogados(currentPageAbogados - 1)" :disabled="currentPageAbogados === 1">
                                Anterior
                            </button>
                            <select class="form-control w-auto mx-2" v-model="currentPageAbogados" @change="changePageAbogados(Number(currentPageAbogados))">
                                <option v-for="page in totalPagesAbogados" :key="page" :value="page">
                                    Página {{ page }}
                                </option>
                            </select>
                            <button class="btn btn-secondary mx-2" @click="changePageAbogados(currentPageAbogados + 1)" :disabled="currentPageAbogados === totalPagesAbogados">
                                Siguiente
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal grilla daco -->
        <div class="modal fade" id="grillaDacoModal" tabindex="-1" role="dialog" aria-labelledby="grillaDacoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="grillaDacoModalLabel">Detalles Datos Beneficiario (s)</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>CI</th>
                                    <th>Nombre</th>
                                    <th>Parentesco</th>
                                    <th>Fecha de Nacimiento</th>
                                    <th>Género</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in parsedGrillaDaco" :key="index">
                                    <td>{{ item.ci }}</td>
                                    <td>{{ item.nombres }} {{ item.primer_apellido }} {{ item.segundo_apellido }}</td>
                                    <td>
                                        <span v-if="item.parentesco === '1-HIJ'">Hija o Hijo</span>
                                        <span v-else-if="item.parentesco === '1-CONY'">Cónyugue</span>
                                        <span v-else-if="item.parentesco === '1-CONV'">Conviviente</span>
                                        <span v-else-if="item.parentesco === '2-HER'">Hermano o Hermana</span>
                                        <span v-else-if="item.parentesco === '2-PAD'">Padre</span>
                                        <span v-else-if="item.parentesco === '2-MAD'">Madre</span>
                                        <span v-else-if="item.parentesco === '3-OTR'">Otros</span>
                                        <span v-else>{{ item.parentesco }}</span>
                                    </td>
                                    <td>{{ item.fecha_nacimiento }}</td>
                                    <td>{{ item.genero === 'F' ? 'Femenino' : item.genero === 'M' ? 'Masculino' : 'N/A' }}</td>
                                    <td>{{ item.nro_celular || 'N/A' }}</td>
                                    <td>{{ item.correo || 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal grilla dahe -->
        <div class="modal fade" id="grillaDaheModal" tabindex="-1" role="dialog" aria-labelledby="grillaDaheModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="grillaDacoModalLabel">Detalles Datos Apoderado (s)</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Id Persona Sip</th>
                                    <th>CI</th>
                                    <th>Nombre</th>
                                    <th>Funcionario <br> Gestora</th>
                                    <th>Fecha de Nacimiento</th>
                                    <th>Género</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in parsedGrillaDahe" :key="index">
                                    <td>{{ item.id_persona_sip }}</td>
                                    <td>{{ item.ci }}</td>
                                    <td>{{ item.nombres }} {{ item.primer_apellido }} {{ item.segundo_apellido }}</td>
                                    <td> {{ item.funcionario_gestora }}</td>
                                    <td>{{ item.fecha_nacimiento }}</td>
                                    <td>{{ item.genero === 'F' ? 'Femenino' : item.genero === 'M' ? 'Masculino' : 'N/A' }}</td>
                                    <td>{{ item.nro_celular || 'N/A' }}</td>
                                    <td>{{ item.correo || 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal grilla daco abg-->
        <div class="modal fade" id="grillaDacoAbgModal" tabindex="-1" role="dialog" aria-labelledby="grillaDacoAbgModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="grillaDacoAbgModalLabel">Detalles Datos Beneficiario (s)</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>CI</th>
                                    <th>Nombre</th>
                                    <th>Parentesco</th>
                                    <th>Fecha de Nacimiento</th>
                                    <th>Género</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in parsedGrillaDacoAbg" :key="index">
                                    <td>{{ item.ci }}</td>
                                    <td>{{ item.nombres }} {{ item.primer_apellido }} {{ item.segundo_apellido }}</td>
                                    <td>
                                        <span v-if="item.parentesco === '1-HIJ'">Hija o Hijo</span>
                                        <span v-else-if="item.parentesco === '1-CONY'">Cónyugue</span>
                                        <span v-else-if="item.parentesco === '1-CONV'">Conviviente</span>
                                        <span v-else-if="item.parentesco === '2-HER'">Hermano o Hermana</span>
                                        <span v-else-if="item.parentesco === '2-PAD'">Padre</span>
                                        <span v-else-if="item.parentesco === '2-MAD'">Madre</span>
                                        <span v-else-if="item.parentesco === '3-OTR'">Otros</span>
                                        <span v-else>{{ item.parentesco }}</span>
                                    </td>
                                    <td>{{ item.fecha_nacimiento }}</td>
                                    <td>{{ item.genero === 'F' ? 'Femenino' : item.genero === 'M' ? 'Masculino' : 'N/A' }}</td>
                                    <td>{{ item.nro_celular || 'N/A' }}</td>
                                    <td>{{ item.correo || 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal grilla dahe abg-->
        <div class="modal fade" id="grillaDaheAbgModal" tabindex="-1" role="dialog" aria-labelledby="grillaDaheAbgModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="grillaDacoAbgModalLabel">Detalles Datos Apoderado (s)</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Id Persona Sip</th>
                                    <th>CI</th>
                                    <th>Nombre</th>
                                    <th>Funcionario <br> Gestora</th>
                                    <th>Fecha de Nacimiento</th>
                                    <th>Género</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in parsedGrillaDaheAbg" :key="index">
                                    <td>{{ item.id_persona_sip }}</td>
                                    <td>{{ item.ci }}</td>
                                    <td>{{ item.nombres }} {{ item.primer_apellido }} {{ item.segundo_apellido }}</td>
                                    <td> {{ item.funcionario_gestora }}</td>
                                    <td>{{ item.fecha_nacimiento }}</td>
                                    <td>{{ item.genero === 'F' ? 'Femenino' : item.genero === 'M' ? 'Masculino' : 'N/A' }}</td>
                                    <td>{{ item.nro_celular || 'N/A' }}</td>
                                    <td>{{ item.correo || 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isDownloading" class="download-overlay">
            <div class="svg-container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="excel-icon">
                    <rect x="8" y="8" width="48" height="48" rx="8" ry="8" fill="#1d6f42" />
                    <text x="50%" y="50%" text-anchor="middle" fill="#fff" font-size="20" font-family="Arial" dy=".3em">XLS</text>
                </svg>
            </div>
            <div class="download-text">Generando reporte, por favor espere...</div>
        </div>
    </div>
</template>

<style scoped>

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
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 10;
    }

    .loader-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .loader {
        width: 120px;
        height: 120px;
        border: 10px solid transparent;
        border-top: 10px solid #1e88e5;
        border-right: 10px solid #1565c0;
        border-radius: 50%;
        animation: spin 1s linear infinite, gradient 3s ease-in-out infinite;
        box-shadow: 0 0 25px rgba(30, 136, 229, 0.8), 0 0 25px rgba(21, 101, 192, 0.8);
        position: relative;
        background-color: rgba(255, 255, 255, 0.498);
    }

    .loader-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 18px;
        color: #1e88e5;
        font-weight: bold;
        text-shadow: none;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes gradient {
        0%, 100% {
            border-top-color: #1e88e5;
            border-right-color: #1565c0;
        }
        50% {
            border-top-color: #64b5f6;
            border-right-color: #0d47a1;
        }
    }

    .background-container {
        position: relative;
        background: url('/img/marca_agua_gestora_bandeja.png') no-repeat center center;
        background-size: 50%;
    }
    .table {
        position: relative;
        z-index: 1;
    }

    .mine {
        background-color: #dcf8c6;
        align-self: flex-end;
    }

    .theirs {
        background-color: #f1f0f0;
        align-self: flex-start;
    }

    .break-text {
        display: inline-block;
        max-width: 70ch;
        word-wrap: break-word;
        white-space: pre-wrap;
        text-align: left;
    }

    .download-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 1050;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-family: Arial, sans-serif;
        font-size: 18px;
        text-align: center;
    }

    .svg-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 15px;
    }

    .excel-icon {
        width: 64px;
        height: 64px;
        animation: bounce 1.5s infinite;
    }

    .loading-dots {
        display: flex;
        justify-content: center;
        font-size: 24px;
        margin-top: 10px;
    }

    .loading-dots span {
        animation: blink 1.5s infinite;
    }

    .loading-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .loading-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    .download-text {
        font-size: 20px;
        font-weight: bold;
        margin-top: 10px;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes blink {
        0%, 100% {
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
    }
</style>
<script>
    export default {
        name: 'Reporte',
        data() {
            return {
                singular: 'Caso',
                registro: { cas_data: {} },
                registros: [],
                tramitesXusuario: [],
                procesos: [],
                areas: [],
                siguiente: { act_data: {} },
                filtro: { prc_codigo: '', cas_nro_caso: '', cas_tipo:'', fecha_ini: this.fechaIni, fecha_fin: '', id_departamento:'', id_agencia: '', id_regional:'' , id_area:''},
                filtro_personal: { usuario: '', fecha_ini_personal: '', fecha_fin_personal: '' },
                dataTable: null,
                historico: [],
                documento: [],
                departamento:[],
                regional:[],
                agencia:[],
                loading: false,
                currentPageTramites: 1,
                itemsPerPageTramites: 10,
                currentPageAbogados: 1,
                itemsPerPageAbogados: 10,
                usuariosLegal: [],
                tablaActual: 'tramites',
                titulo_listado_tabla :'',
                fechaHabil: '',
                cantidadTramitesXusuario: 0,
                errorUsuario: false,
                nroAtendidos: 0,
                parsedGrillaDaco: [],
                parsedGrillaDahe: [],
                parsedGrillaDacoAbg: [],
                parsedGrillaDaheAbg: [],
                isDownloading: false,
                expandedItems: {},
                tableZoom: 14
            }
        },

        mounted() {
            this.listarUsuariosLegal();
            this.listarProcesos();
            this.listarAreas();
            const date = new Date();
            date.setDate(1);
            const year = date.getFullYear();
            const month = date.getMonth() + 1;
            const day = date.getDate();
            const fechaIni = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;

            const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            const year1 = lastDay.getFullYear();
            const month1 = lastDay.getMonth() + 1;
            const day1 = lastDay.getDate();
            const fechaFin = `${year1}-${month1.toString().padStart(2, '0')}-${day1.toString().padStart(2, '0')}`;
            this.filtro = { prc_codigo: '', cas_nro_caso: '', cas_tipo: '', fecha_ini: fechaIni, fecha_fin: fechaFin, id_departamento: '', id_agencia: '', id_regional:'', id_area:'' };
            this.filtro_personal = { usuario: '', fecha_ini_personal: fechaIni, fecha_fin_personal: fechaFin };
            //this.listarDepartamento();
            this.listarDepartamento().then(departamentoData => {
                if(departamentoData && departamentoData.length > 0){
                    this.listarRegional(departamentoData[0].id_sip_departamento);
                }
            });
        },

        created() {

        },

        methods: {

            toggle(casId) {
                this.$set(this.expandedItems, casId, !this.expandedItems[casId]);
                this.isExpanded = this.expandedItems[casId];
            },

            openModal(id_documento, url_documento) {
                var url = "/api/verDocumentoPdfNfs/" + id_documento;
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

            listarRegistrosReporte(action = null) {
                this.loading = true;
                this.isDownloading = action === 'GENERAR-EXCEL';
                let gRegistro = { ...this.filtro };

                let url;
                let responseType = 'json';

                if (action === 'GENERAR-EXCEL') {
                    url = 'api/v1/generarExcel';
                    responseType = 'blob';
                } else if (action === 'GENERAR-CSV') {
                    url = 'api/v1/generarCsv';
                    responseType = 'blob';
                } else {
                    url = 'api/v1/reporteLegal';
                }

                axios.post(url, gRegistro, { responseType })
                    .then(response => {
                        if (action === 'GENERAR-EXCEL' || action === 'GENERAR-CSV') {
                            const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                            const url = window.URL.createObjectURL(blob);
                            const link = document.createElement('a');
                            link.href = url;
                            link.setAttribute('download', action === 'GENERAR-EXCEL' ? 'ReporteLegal.xlsx' : 'ReporteLegal.csv');
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);

                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "Reporte Generado ...",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            this.registros = response.data.data;
                            this.tablaActual = 'tramites';
                            this.titulo_listado_tabla = 'Listado de Trámites Legal';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.isDownloading = false;
                    });
            },

            handleUsuarioChange() {
                if (!this.filtro_personal.usuario) {
                    this.errorUsuario = true;
                } else {
                    this.errorUsuario = false;
                    if (this.filtro_personal.usuario === 'TODOS') {
                        this.filtro_personal.usuario = this.usuariosLegal
                            .map(usuario => usuario.email)
                            .join(',');
                    }
                }
            },

            habilitarBotonesFechas() {
                console.log("habilitarBotonesFechas");

                let url = "api/validacionFecha/";
                axios.get(url)
                    .then((response) => {
                        if (response.data.codigoRespuesta == "200") {
                            this.fechaHabil = response.data.data;
                        }
                        else {
                            console.log("error");
                        }
                    })
            },

            buscarRegistrosXusuario(action = null) {
                if (!this.filtro_personal.usuario) {
                    this.errorUsuario = true;
                    return;
                }
                this.errorUsuario = false;
                this.loading = true;
                this.isDownloading = action === 'GENERAR-EXCEL-USUARIOS';
                let usuario = this.filtro_personal.usuario;

                if (usuario === 'TODOS') {
                    usuario = this.usuariosLegal
                        .map(usuario => usuario.email)
                        .join(',');
                }

                const fecha_ini_personal = this.filtro_personal.fecha_ini_personal;
                const fecha_fin_personal = this.filtro_personal.fecha_fin_personal;

                let gRegistro = { usuario, fecha_ini_personal, fecha_fin_personal };
                if (action) {
                    gRegistro.action = action;
                }

                let url;
                let responseType = 'json';

                if (action === 'GENERAR-EXCEL-USUARIOS') {
                    url = 'api/v1/generarExcelXUsuarios';
                    responseType = 'blob';
                } else if (action === 'GENERAR-CSV-USUARIOS') {
                    url = 'api/v1/generarCsvXUsuarios';
                    responseType = 'blob';
                } else {
                    url = 'api/v1/generarReporteTramiteXUsuario';
                }

                axios.post(url, gRegistro, { responseType })
                    .then(response => {
                        if (action === 'GENERAR-EXCEL-USUARIOS' || action === 'GENERAR-CSV-USUARIOS') {
                            const blob = new Blob([response.data], { type: action === 'GENERAR-EXCEL-USUARIOS' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'text/csv' });
                            const url = window.URL.createObjectURL(blob);
                            const link = document.createElement('a');
                            link.href = url;
                            link.setAttribute('download', action === 'GENERAR-EXCEL-USUARIOS' ? 'ReporteLegalUsuarios.xlsx' : 'ReporteLegalUsuarios.csv');
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);

                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "Reporte Generado ...",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            this.tramitesXusuario = response.data.data;
                            this.tablaActual = 'abogados';
                            this.titulo_listado_tabla = 'Listado Trámites Atendidos por Abogado';
                            this.nroAtendidos = response.data.nroAtendidos;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.isDownloading = false;
                    });
            },
            listarUsuariosLegal() {
                let url = "api/v1/getuserLegal";
                axios.get(url)
                    .then(response => {
                        if (response.data && response.data.codigoRespuesta === 200) {
                            this.usuariosLegal = response.data.data || [];
                            console.log("USUARIOS LEGAL >> ", this.usuariosLegal);
                        } else {
                            console.error("Respuesta inesperada o error:", response.data?.mensaje || "Error desconocido");
                        }
                    })
                    .catch(error => {
                        console.error("Error al obtener datos de usuario:", error);
                    });
            },

            generarReporte(){
                this.loading = true;
                let gRegistro = this.filtro;
                let url = "api/generarReporte";
                axios.post(url, gRegistro)
                .then(response =>{
                    const currentURL = window.location.href;
                    const baseURL = new URL(currentURL).origin + '/' + new URL(currentURL).pathname.split('/')[0];
                    const relativePath = 'reportes_generados/'+ response.data.nombreArchivo;
                    const completeUrl = `${baseURL}${relativePath}`;
                    const nombreArchivo = response.data.nombreArchivo;
                    var link = document.createElement('a');
                    link.href = completeUrl;
                    link.download = nombreArchivo;
                    link.click();
                })
                .finally(() => {
                    this.loading = false;
                });
            },

            listarProcesos() {
                let url = "api/procesosTodos";
                axios.get(url).then(response => {
                    this.procesos = response.data.data;
                    this.procesos.forEach(function (row) {
                        row.prc_data = JSON.parse(row.prc_data);
                    });
                });
            },

            listarAreas() {
                let url = "api/listarAreas";
                axios.get(url).then(response => {
                    this.areas = response.data.data;
                });
            },
            //historico
            doHistorico(id, id_padre) {
                console.log("doHistorico",id);
                console.log("id_padre",id_padre);
                var id_caso;
                if(id_padre == 0){
                    id_caso = id;
                } else {
                    id_caso = id_padre;
                }
                let that = this;
                let url = "api/casosHistorico/" + id_caso;
                axios.get(url)
                    .then((response) => {
                        this.historico = response.data.data;
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            },
            doDocumentoPdf(htc_id) {
                console.log("htc_id",htc_id);
                const datos = { htc_id: htc_id };
                axios.post('api/obtenerDocumento', datos)
                    .then(response => {
                        console.log("doDocumentoPdf",response.data.data);
                        this.documento = response.data.data;
                    })
                    .catch(error => {
                        console.error('Error al generar al listado', error);
                    });
            },

            verImagen: function (ruta) {
                window.open(ruta, '_blank');
            },

            async listarDepartamento() {
                let url = "api/buscarDepartamento";
                axios.get(url).then(response => {
                    this.departamento = response.data.data;
                    return response.data.data;
                });
            },

            async listarRegional(id_departamento){
                this.regional = [];
                this.agencia = [];

                if(!id_departamento){
                    this.filtro.id_agencia= '';
                    this.filtro.id_regional='';
                    return;
                }

                let url = 'api/buscarRegional';
                var params = {"id_departamento": id_departamento};
                axios.post(url, params).then(response => {
                    this.regional = response.data.data;
                    return response.data.data;
                });
            },

            async listarAgencia(id_regional){
                this.agencia = [];
                if(!id_regional){
                    return;
                }
                let url = 'api/buscarAgencia';
                var params = {"id_regional": id_regional};
                axios.post(url, params).then(response =>{
                    this.agencia = response.data.data;
                    return response.data.data;
                })
            },

            changePageTramites(page) {
                if (page >= 1 && page <= this.totalPagesTramites) {
                    this.currentPageTramites = page;
                }
            },

            changePageAbogados(page) {
                if (page >= 1 && page <= this.totalPagesAbogados) {
                    this.currentPageAbogados = page;
                }
            },

            limpiarFiltros() {
                this.filtro.fecha_ini = '';
                this.filtro.fecha_fin = '';
                this.filtro.prc_codigo = '';
                this.filtro.cas_nro_caso = '';
                this.filtro.cas_tipo = '';
                this.filtro.id_departamento = '';
                this.filtro.id_agencia = '';
                this.filtro.id_regional = '';
                this.filtro.id_area = '';

                // Limpiar los filtros del modal de personal
                this.filtro_personal.usuario = '';
                this.filtro_personal.fecha_ini_personal = '';
                this.filtro_personal.fecha_fin_personal = '';

                // Reiniciar errores
                this.errorUsuario = false;

                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Filtros Limpiados",
                    showConfirmButton: false,
                    timer: 1500
                });

            },

            openGrillaDacoModal(grillaDaco) {
                this.parsedGrillaDaco = grillaDaco ? JSON.parse(grillaDaco) : [];
                $('#grillaDacoModal').modal('show');
            },

            openGrillaDaheModal(grillaDahe) {
                this.parsedGrillaDahe = grillaDahe ? JSON.parse(grillaDahe) : [];
                $('#grillaDaheModal').modal('show');
            },

            openGrillaDacoAbgModal(grillaDacoAbg) {
                this.parsedGrillaDacoAbg = grillaDacoAbg ? JSON.parse(grillaDacoAbg) : [];
                $('#grillaDacoAbgModal').modal('show');
            },

            openGrillaDaheAbgModal(grillaDaheAbg) {
                this.parsedGrillaDaheAbg = grillaDaheAbg ? JSON.parse(grillaDaheAbg) : [];
                $('#grillaDaheAbgModal').modal('show');
            },

            toggleMenu() {
                const body = document.body;
                if (body) {
                    body.classList.toggle('sidebar-collapse');
                } else {
                    console.error('No se encontró el elemento <body>.');
                }
            },

            zoomInTable() {
                this.tableZoom += 2;
            },
            zoomOutTable() {
                if (this.tableZoom > 10) {
                    this.tableZoom -= 2;
                }
            },
        },

        computed: {
            totalPagesTramites() {
                return Math.ceil(this.registros.length / this.itemsPerPageTramites);
            },
            paginatedRegistrosTramites() {
                const start = (this.currentPageTramites - 1) * this.itemsPerPageTramites;
                const end = start + this.itemsPerPageTramites;
                return this.registros.slice(start, end);
            },
            totalPagesAbogados() {
                return Math.ceil(this.tramitesXusuario.length / this.itemsPerPageAbogados);
            },
            paginatedRegistrosAbogados() {
                const start = (this.currentPageAbogados - 1) * this.itemsPerPageAbogados;
                const end = start + this.itemsPerPageAbogados;
                return this.tramitesXusuario.slice(start, end);
            }
        },
    }
</script>





/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import Vue from 'vue';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

// --- PivotTable ---
import VuePivottable from 'vue-pivottable'
import 'vue-pivottable/dist/vue-pivottable.css'
Vue.use(VuePivottable)
// --- PivotTable --- fin

// --- Leaflet ---
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

// --- TheMask ---
import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);

// --- ToggleButton ---
import ToggleButton from 'vue-js-toggle-button'
Vue.use(ToggleButton)

// Vuex
import Vuex from 'vuex';
import { store } from './store';
import LegalHistoryService from './services/legal-history.service';
Vue.use(Vuex);

let routes = [
    { path: '/dashboard', props: true, component: require('./components/Dashboard.vue').default },
    { path: '/users', component: require('./components/parametros/Users.vue').default },
    { path: '/usersNodos', component: require('./components/parametros/UsersNodos.vue').default },
    { path: '/cambioAgenciaUsers', component: require('./components/parametros/cambioAgenciaUsers.vue').default },
    { path: '/nodosProcesos', component: require('./components/parametros/NodosProcesos.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },

    { path: '/procesos', component: require('./components/diseno/Procesos.vue').default },
    { path: '/modeladoProceso/:prc_id', props: true, component: require('./components/diseno/modeladoProceso.vue').default },
    { path: '/actividades', component: require('./components/diseno/Actividades.vue').default },
    { path: '/formularios', component: require('./components/diseno/Formularios.vue').default },
    { path: '/modeladoFormularios', component: require('./components/diseno/modeladoFormulario.vue').default },
    { path: '/f3', component: require('./components/reportes/F3.vue').default },
    { path: '/disenoProceso', component: require('./components/diseno/F4.vue').default },
    { path: '/impresiones', component: require('./components/diseno/Impresiones.vue').default },

    { path: '/nodos', component: require('./components/parametros/Nodos.vue').default },
    { path: '/catalogos', component: require('./components/parametros/Catalogos.vue').default },
    { path: '/tactividades', component: require('./components/parametros/TActividades.vue').default },
    { path: '/tformularios', component: require('./components/parametros/TFormularios.vue').default },
    { path: '/estadosAvance', component: require('./components/parametros/estadosAvance.vue').default },
    { path: '/actuaciones', component: require('./components/parametros/Actuaciones.vue').default },

    { path: '/tipows', component: require('./components/ws/TipoWs.vue').default },
    { path: '/ws', component: require('./components/ws/Ws.vue').default },

    { path: '/crearCaso', component: require('./components/CrearCaso.vue').default },
    { path: '/misCasos', component: require('./components/MisCasos.vue').default },
    { path: '/atenderCaso/:url_encode_cas_id', props: true, component: require('./components/AtenderCaso.vue').default },
    { path: '/atenderCasoRender/:url_encode_cas_id', props: true, component: require('./components/AtenderCasoRender.vue').default },
    { path: '/buscarCasos', component: require('./components/BuscarCasos.vue').default },
    { path: '/ConsultasAsegurados', component: require('./components/ConsultasAsegurados.vue').default },

    { path: '/cierreTramites', component: require('./components/CierreTramites.vue').default },
    { path: '/CambiarContrasena', component: require('./components/CambiarContrasena.vue').default },

    { path: '/miCorrespondencia', component: require('./components/ceroPapel/MiCorrespondencia.vue').default },
    { path: '/atenderCorrespondencia/:crr_id', props: true, component: require('./components/ceroPapel/AtenderCorrespondencia.vue').default },
    { path: '/atenderActuaciones/:crr_id', props: true, component: require('./components/ceroPapel/AtenderActuaciones.vue').default },
    { path: '/atenderContenido/:crr_id', props: true, component: require('./components/ceroPapel/AtenderContenido.vue').default },

    { path: '/archivos', component: require('./components/archivos/parametros/Archivos.vue').default },
    { path: '/tiposArchivo', component: require('./components/archivos/parametros/TiposArchivo.vue').default },
    { path: '/tiposDoc', component: require('./components/archivos/parametros/TiposDoc.vue').default },
    { path: '/subtiposDoc', component: require('./components/archivos/parametros/SubtiposDoc.vue').default },

    { path: '/nodosTrabajos', component: require('./components/reportes/NodosTrabajos.vue').default },
    { path: '/reporteProceso', component: require('./components/reportes/ReporteProcesos.vue').default },
    { path: '/reporteProceso', component: require('./components/reportes/ReporteProcesos.vue').default },
    { path: '/reporteJubilacion', component: require('./components/reportes/ReporteProcesosJubilacion.vue').default },
    { path: '/reporte-legal', component: require('./components/reportes/RepoLegal.vue').default },

    { path: '/registroPasaporte', component: require('./components/registroPasaporte.vue').default },
    { path: '/buscarDocumentos', component: require('./components/buscarDocumentos.vue').default },

    { path: '/notificacionFechas', component: require('./components/GetNotificacionFechas.vue').default },

    { path: '/notificacionComplementarios', component: require('./components/NotificacionComplementarios.vue').default },

    { path: '/notificaciones', component: require('./components/Notificaciones.vue').default },

    { path: '/administrarPendientes', component: require('./components/AdministrarPendientes.vue').default },

    { path: '/buscar_1582', component: require('./components/Buscar_1582.vue').default },
    { path: '/generarPDF1582', component: require('./components/renderPDF1582.vue').default },
    { path: '/servicio-controller', component: require('./components/ACGComponent.vue').default },
    { path: '/indicadores', component: require('./components/DashboardV2.vue').default },
    { path: '/generacion-graficas', component: require('./components/Graficas.vue').default },
    { path: '/notificonsulta', component: require('./components/NotifiConsultas.vue').default },
    { path: '/notificaciones_consultas', component: require('./components/NotifiListado.vue').default },
    {
        path: '/departamento_detalle',
        name: 'departamentoDetalle',
        component: require('./components/graficas/modulos/DetalleDepartamento.vue').default,
        props: true
    },
];

const router = new VueRouter({
    routes
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('rmx-atender-destinos', require('./components/ceroPapel/cmp/rmxAtenderDestinos.vue').default);
Vue.component('rmx-atender-copias', require('./components/ceroPapel/cmp/rmxAtenderCopias.vue').default);
Vue.component('notifi-consultas', require('./components/NotifiConsultas.vue').default);

const app = new Vue({
    el: '#app',
    router,
    store: store,
    provide:{
        legalHistoryService: new LegalHistoryService()
    },
    methods: {
        closeModalAndRedirect(route) {
            $('.modal').modal('hide');

            const url = new URL(window.location);
            url.searchParams.delete('prc_codigo');
            url.searchParams.delete('cas_nro_caso');
            url.searchParams.delete('cas_gestion');
            window.history.pushState({}, '', url);

            setTimeout(() => {
                if (this.$route.path !== route) {
                    this.$router.push(route).catch(err => {
                        if (err.name !== 'NavigationDuplicated') {
                            console.error(err);
                        }
                    });
                }
            }, 500);
        }
    }
});

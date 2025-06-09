<template>
    <div>
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" collapsed="true" data-widget="pushmenu" href="#"><i class="fa fa-bars" style="font-size: 1.5em;"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="dropdown" href="#" @click="buscarNotificaciones(1)">
                        <i class="fas fa-sync-alt" style="font-size: 1.5em;"></i>
                        <span class="badge badge-danger navbar-badge"></span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-bell" style="font-size: 1.5em;" @click="buscarNotificaciones"></i>
                        <span class="badge badge-danger navbar-badge">{{ cantidad }}</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 400px;">
                        <template v-if="parsedNotifications.length">
                            <a v-for="notificacion in parsedNotifications" :key="notificacion.id" :href="generarUrl(notificacion.url, notificacion.cas_cod_id)" class="dropdown-item" @click="marcarLeido(notificacion.id)">

                            <!-- <a v-for="notificacion in parsedNotifications" :key="notificacion.id" :href="notificacion.url" class="dropdown-item" @click="marcarLeido(notificacion.id)"> -->
                                <div class="media">
                                    <img src="/img/usuario-chat.png" alt="TRAMITE SIP" class="img-size-50 mr-3 img-circle">
                                    <div class="media-body">
                                        <h3 class="dropdown-item-title">
                                            <span class="float-right text-sm text-primary"><i class="fas fa-bell"></i></span>
                                        </h3>
                                        <p class="text-sm">{{ notificacion.message }}</p>
                                        <span class="badge badge-warning">{{ notificacion.cas_cod_id }}</span>
                                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> <small><strong>{{ notificacion.created_at }}</strong></small></p>
                                    </div>
                                </div>
                            </a>
                        </template>
                        <template v-else>
                            <div class="dropdown-item text-center">
                                <i class="fas fa-thumbs-up"></i> No hay pendientes
                            </div>
                        </template>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button" @click="toggleFullScreen">
                        <i class="fas fa-expand-arrows-alt" style="font-size: 1.5em;"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>

import axios from 'axios';
import Swal from 'sweetalert2';

import { EventoMensaje } from './eventoMensajes';

export default {
    data() {
        return {
            notifications: [],
            parsedNotifications: [],
            cantidad : 0,
            usrId: window.Laravel.usr_id,
            usrUser: window.Laravel.usr_user,

            filtros: {
                prc_codigo: '',
                cas_nro_caso: ''
            }
        };
    },
    mounted() {
        this.listaNotificaciones();

        EventoMensaje.$on('mensajeEnviado', this.listaNotificaciones);
    },
    created() {
        this.buscarNotificaciones();
    },
    methods: {
        generarUrl(baseUrl, cas_cod_id) {
            const url = new URL(baseUrl);
            const parts = cas_cod_id.split('/');
            const prc_codigo = parts[0];
            const cas_nro_caso = parts[1];
            const cas_gestion = parts[2];
            url.searchParams.append('prc_codigo', prc_codigo);
            url.searchParams.append('cas_nro_caso', cas_nro_caso);
            url.searchParams.append('cas_gestion', cas_gestion);
            return url.toString();
        },
        buscarNotificaciones(sw) {
            if (sw === 1) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Se actualizo la lista de notificaciones",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
            axios.get(`/api/v1/notificaciones?userId=${this.usrId}`)
                .then(response => {
                    this.notifications = response.data.data;
                    this.cantidad = response.data.cantidad;
                    this.parsearNotifications();
                })
                .catch(error => {
                    console.error('Error en notificaciones:', error);
                });
        },
        parsearNotifications() {
            this.parsedNotifications = this.notifications.map(notification => {
                const data = JSON.parse(notification.data);
                const message = this.extraerMensaje(data.message);
                const casCodId = data.cas_cod_id;
                const createdAt = notification.created_at;
                return {
                    id: notification.id,
                    url: data.url,
                    message: message,
                    cas_cod_id: casCodId,
                    created_at: createdAt
                };
            });
        },
        extraerMensaje(message) {
            const regex = /Tienes un mensaje de \[\{"name":"(.*?)"\}\]/;
            const match = message.match(regex);
            return match ? `Tienes un mensaje de ${match[1]}` : message;
        },
        toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        },
        listaNotificaciones() {
            this.buscarNotificaciones();
        },
        marcarLeido(id) {
            axios.post(`/api/v1/marcarLeido`, { id: id.toString() })
                .then(response => {
                    this.buscarNotificaciones();
                    const toastMixin = Swal.mixin({
                        toast: true,
                        icon: 'success',
                        title: 'Tram',
                        position: 'top-right',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        showClass: {
                            popup: 'swal2-show',
                            backdrop: 'swal2-backdrop-show',
                            icon: 'swal2-icon-show'
                        },
                        hideClass: {
                            popup: 'swal2-hide',
                            backdrop: 'swal2-backdrop-hide',
                            icon: 'swal2-icon-hide'
                        }
                    });
                    toastMixin.fire({
                        title: 'Mensaje marcado como leído'
                    });
                })
                .catch(error => {
                    console.error('Error al marcar como leído:', error);
                });
        }
    }
    };
</script>

<style scoped>
</style>

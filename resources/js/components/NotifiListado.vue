<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>{{ plural }}</h5>
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">Bandeja de Consultas</h3>

                                    <div class="card-tools">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" placeholder="Buscar ..." v-model="searchQuery" @input="searchNotifications">
                                            <div class="input-group-append">
                                                <div class="btn btn-primary" @click="searchNotifications">
                                                    <i class="fas fa-search"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="card-body p-0">
                                    <div class="mailbox-controls">
                                        
                                        <button type="button" class="btn btn-default btn-sm" @click="listaNotificaciones(1)" title="Actualizar">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <div class="float-right">
                                            {{ pagination.start }}-{{ pagination.end }}/{{ pagination.total }}
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm" @click="prevPage" :disabled="pagination.page === 1">
                                                    <i class="fas fa-chevron-left"></i>
                                                </button>
                                                <button type="button" class="btn btn-default btn-sm" @click="nextPage" :disabled="pagination.page === pagination.totalPages">
                                                    <i class="fas fa-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive mailbox-messages background-container">
                                        <table class="table table-hover table-striped">
                                            <tbody>
                                                <tr v-for="notificacion in filteredNotifications" :key="notificacion.id">
                                                    <td>
                                                        <div class="icheck-primary">
                                                            <input type="checkbox" :value="notificacion.id" :id="'check' + notificacion.id">
                                                            <label :for="'check' + notificacion.id"></label>
                                                        </div>
                                                    </td>
                                                    <td class="mailbox-user">
                                                        <a href="javascript:void(0);">
                                                            <i class="fas fa-envelope-open text-success" v-if="notificacion.read_at"></i>
                                                            <i class="fas fa-user text-primary" v-else></i>
                                                        </a>
                                                    </td>
                                                    <td class="mailbox-name"><a href="javascript:void(0);"><strong>{{ notificacion.cas_cod_id }}</strong> </a></td>
                                                    <td class="mailbox-subject"><b>{{ notificacion.tipo_conversacion }}</b></td>
                                                    <td class="mailbox-attachment" >
                                                        <small class="text-muted">
                                                        <i class="fas fa-check-circle text-success" v-if="notificacion.read_at"></i> {{ notificacion.read_at }}</small>
                                                    </td>
                                                    <td class="mailbox-date">
                                                        <small class="text-muted"><i class="far fa-clock mr-1 text-primary"></i> <strong>{{ notificacion.created_at }}</strong> </small>
                                                    </td>
                                                    <td class="mailbox-date">
                                                        <div style="display: flex;">
                                                            <div style="position: relative;">
                                                                <button
                                                                    data-toggle="modal"
                                                                    @click="openChatModal(notificacion.cas_cod_id)"
                                                                    data-target="#modalChat"
                                                                    type="button"
                                                                    class="btn btn-success btn-circle pulseBtn"
                                                                    title="Abrir chat de Consultas"
                                                                    style="background-color: #0b63bb;">
                                                                    <i class="fas fa-comments white" aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                            <!-- <span style="margin-right: 10px;"></span>
                                                            <button
                                                                data-toggle="modal"
                                                                @click="openChatModal2(r.cas_cod_id)"
                                                                data-target="#modalChat"
                                                                type="button"
                                                                class="btn btn-secondary btn-circle"
                                                                title="Registro de Logs">
                                                                <i class="fas fa-cogs" aria-hidden="true"></i>
                                                            </button> -->
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer p-0">
                                    <div class="mailbox-controls">
                                        <button type="button" class="btn btn-default btn-sm" @click="listaNotificaciones(1)" title="Actualizar">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
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
                            <p><strong>Número de Tramite: {{ this.currentCasCodId }} </strong>
                                <span style="position: absolute; top: -8px; right: -8px; background-color: red; color: white; border-radius: 50%; padding: 3px 7px; font-size: 10px;">
                                    {{ this.cantidadMsg }}
                                </span>
                            </p>
                            <hr>
                            <div v-if="showLoader" class="loader-container">
                                <div class="loader-wrapper">
                                    <div class="loader"></div>
                                    <span class="loader-text">TramiteSip</span>
                                    <span class="loading-text">Cargando...</span>
                                </div>
                            </div>

                            <div class="direct-chat-messages" v-if="!showLoader" ref="messagesContainer">
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
                        <div class="card-footer">
                            <!-- <form @submit.prevent="sendMessage">
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
                            </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script scoped>
    import axios from 'axios';
    import Swal from 'sweetalert2';

    export default {
        data() {
            return {
                plural: 'Consultas TramiteSIP',
                usrUser: window.Laravel.usr_user,
                usrId: window.Laravel.usr_id,
                notificaciones: [],
                filteredNotifications: [],
                searchQuery: '',
                showLoader: false,
                messages: [],
                newMessage: '',
                showModal: false,
                currentCasCodId: '',
                cantidadMsg: 0,
                pagination: {
                    page: 1,
                    perPage: 15,
                    total: 0,
                    start: 1,
                    end: 5,
                    totalPages: 1
                }
            };
        },
        mounted() {
            this.listaNotificaciones();
        },

        created() {
            this.fetchMessages(this.usrUser, this.currentCasCodId);
        },

        methods: {
            searchNotifications() {
                this.filteredNotifications = this.notificaciones.filter(notificacion => {
                    return notificacion.cas_cod_id.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                           notificacion.tipo_conversacion.toLowerCase().includes(this.searchQuery.toLowerCase());
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
                            const newMessageObj = {
                                id: Date.now(),
                                mensaje: this.newMessage,
                                isMine: true,
                                regional: this.nombre_regional,
                                userName: this.usrUser + '@gestora.bo',
                                createdAt: new Date().toISOString()
                            };
                            this.messages.push(newMessageObj);
                            this.newMessage = '';
                            this.$nextTick(() => {
                                this.scrollToEnd();
                            });
                            var toastMixin = Swal.mixin({
                                toast: true,
                                icon: 'success',
                                title: 'TramiteSIP',
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
                                title: 'Mensaje Enviado ...'
                            });
                        })
                        .catch(error => {
                            console.error('Error al enviar el mensaje:', error);
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
                    }
                })
                .catch(error => {
                    console.error('Error al enviar el mensaje:', error);
                });
            },
            scrollToEnd() {
                this.$nextTick(() => {
                    const messagesContainer = this.$refs.messagesContainer;
                    if (messagesContainer) {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                });
            },
            openChatModal(cas_cod_id) {
                this.currentCasCodId = cas_cod_id;
                this.showModal = true;
                this.showLoader = true;
                this.fetchMessages(this.usrUser, cas_cod_id, true);
            },
            fetchMessages(usuario, nroTramite) {
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
                            this.messages = response.data.data;
                            this.messages = response.data.data;
                            this.cantidadMsg = response.data.cantidadMsg;
                        }
                        this.showLoader = false;
                    })
                    .catch(error => {
                        console.error('Error al consumir mensajes:', error);
                        this.showLoader = false;
                    });
            },
            listaNotificaciones(sw = 0) {
                if (sw === 1) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Bandeja actualizada",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

                let url = 'api/v1/listarnotifipanel';
                axios.get(url).then(response => {
                    this.notificaciones = response.data.data;
                    this.filteredNotifications = this.notificaciones;
                    this.pagination.total = this.notificaciones.length;
                    this.pagination.totalPages = Math.ceil(this.pagination.total / this.pagination.perPage);
                    this.updatePagination();
                    this.parseNotifications();
                }).catch(error => {
                    console.log(error);
                });
            },
            updatePagination() {
                this.pagination.start = (this.pagination.page - 1) * this.pagination.perPage + 1;
                this.pagination.end = Math.min(this.pagination.page * this.pagination.perPage, this.pagination.total);
                this.filteredNotifications = this.notificaciones.slice(this.pagination.start - 1, this.pagination.end);
            },
            nextPage() {
                if (this.pagination.page < this.pagination.totalPages) {
                    this.pagination.page++;
                    this.updatePagination();
                }
            },
            prevPage() {
                if (this.pagination.page > 1) {
                    this.pagination.page--;
                    this.updatePagination();
                }
            },
            parseNotifications() {
                this.notificaciones = this.notificaciones.map(notification => {
                    const data = JSON.parse(notification.data);
                    const message = this.extractMessage(data.message);
                    const mensaje = this.extractMessage(data.mensaje);
                    const casCodId = data.cas_cod_id;
                    const createdAt = notification.created_at;
                    const readAt = notification.read_at;
                    return {
                        id: notification.id,
                        url: data.url,
                        message: message,
                        cas_cod_id: casCodId,
                        created_at: createdAt,
                        read_at: readAt,
                        mensaje: mensaje
                    };
                });
            },
            extractMessage(message) {
                const regex = /Tienes un mensaje de \[\{"name":"(.*?)"\}\]/;
                const match = message.match(regex);
                return match ? `Tienes un mensaje de ${match[1]}` : message;
            },
            truncate(text, length) {
                return text.length > length ? text.substring(0, length) + '...' : text;
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
            openChatModal(casCodId) {
                this.currentCasCodId = casCodId;
                this.showModal = true;
                this.showLoader = true;
                this.fetchMessages(this.usrUser, casCodId, true);
            },
            closeChatModal() {
                this.showModal = false;
                this.showLoader = false;
            }
    }
    };
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
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
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

<template>
    <div>
        <button @click="showModal = true" class="btn btn-primary">
            <i class="fas fa-comments"></i> Abrir chat
        </button>
        <div v-if="showModal" class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chat</h5>
                        <button type="button" class="close" @click="showModal = false" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="direct-chat-messages">
                                <div v-for="message in messages" :key="message.id" :class="['chat-bubble', message.isMine ? 'mine' : 'theirs']">
                                    <img src="/img/usuario-chat.png" alt="User" class="chat-avatar">
                                    <p>{{ message.mensaje }}</p>
                                    <small class="chat-timestamp">
                                        <i class="fas fa-calendar-alt"></i> {{ new Date(message.created_at).toLocaleString() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <form @submit.prevent="sendMessage">
                                <div class="input-group">
                                    <input v-model="newMessage" placeholder="Escribir mensaje ..." class="form-control">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-warning">
                                            Enviar <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            messages: [],
            newMessage: '',
            showModal: false
        };
    },
    created() {
        this.fetchMessages();
    },
    methods: {
        async fetchMessages() {
            try {
                const response = await axios.get('/api/mensajes');
                console.log("LOS MENSAJES >>> ", response.data);
                this.messages = response.data;
            } catch (error) {
                console.error('Error al consumir mensajes:', error);
            }
        },
        async sendMessage() {
            try {
                const response = await axios.post('/api/mensajes', { mensaje: this.newMessage });
                this.messages.push({
                    ...response.data,
                    isMine: true
                });
                this.newMessage = '';
            } catch (error) {
                console.error('error al enviar el mensaje:', error);
            }
        }
    }
};
</script>

<style scoped>
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

    .modal {
        background: rgba(0, 0, 0, 0.5);
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-dialog {
        max-width: 600px;
        width: 100%;
    }

    .modal-content {
        border-radius: 10px;
        overflow: hidden;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>

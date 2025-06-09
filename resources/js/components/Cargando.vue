<template>
    <div v-if="cargando" class="cargando">
        <div class="cargando-overlay"></div>
        <div class="cargando-contenido">
            <span>CARGANDO...</span>
            <i class="fa fa-spinner"></i>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        cargando: Boolean
    }
};
</script>

<style scoped>
/* Estilos para el componente de carga */
.cargando {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
}

.cargando-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.cargando-contenido {
    z-index: 10000;
    background-color: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Estilos para el ícono de carga */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
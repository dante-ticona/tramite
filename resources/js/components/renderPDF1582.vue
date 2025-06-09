<template>
    <div>
        <h1>RENDER API PDF 1582</h1>
        <input v-model="nroTramite" placeholder="Ingrese nroTramite" />
        <button class="btn btn-primary" @click="fetchPdf">Generar PDF</button>
        <iframe v-if="pdfUrl" :src="pdfUrl" width="100%" height="1000px" style="width: 100vw;"></iframe>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            nroTramite: "",
            pdfUrl: null
        };
    },
    methods: {
        fetchPdf() {
            const params = {
                nroTramite: this.nroTramite
            };
            
            axios.post('api/generarPDF1582', params)
            .then(response => {
                const data = response.data;
                console.log('PDF data:', data);
                if (data.codigoRespuesta.code === 200) {
                    this.renderPdf(data.data);
                } else {
                    console.error('Error fetching PDF:', data.codigoRespuesta.mensaje);
                }
            })
            .catch(error => console.error('Error:', error));
        },
        renderPdf(base64Data) {
            const byteCharacters = atob(base64Data);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);

            const blob = new Blob([byteArray], { type: 'application/pdf' });

            const blobUrl = URL.createObjectURL(blob);

            this.pdfUrl = blobUrl;
        }
    }
};
</script>
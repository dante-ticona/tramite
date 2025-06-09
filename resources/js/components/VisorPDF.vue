<template>
    <div>
        <!-- Modal -->
        <div class="modalVisorPdf" :style="{ display: modalVisorPdfDisplay }">
            <div class="modal-contenidoVisorPdf">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Documento a Rubricar
                    </h5>
                    <span class="closeVisorPdf" @click="cerrarModal"
                        >&times;</span
                    >
                </div>
                <fieldset>
                    <div class="container">
                        <div class="row">
                            <input
                                id="parameters"
                                type="hidden"
                                value='[	{"idParametro":1,"descrizione":"FIRMA","valore":"NOMBRE: TATIANA","nota":null}   ]'
                            />
                            <!-- Below the pdf base 64 rapresentation -->
                            <input id="pdfBase64" type="hidden" value="as" />
                            <div
                                class="col-md-12"
                                id="pdfManager"
                                v-show="showPdfManager"
                            >
                                <div class="row" id="selectorContainer">
                                    <div
                                        class="col-fixed-240"
                                        id="parametriContainer"
                                    ></div>
                                    <div class="col-fixed-605">
                                        <!-- Otros elementos del componente -->
                                        <div id="app">
                                            <div
                                                id="pageContainer"
                                                class="pdfViewer singlePageView dropzone nopadding"
                                                :style="{
                                                    backgroundColor:
                                                        'transparent',
                                                }"
                                            >
                                                <canvas
                                                    id="the-canvas"
                                                    style="
                                                        border: 1px solid black;
                                                    "
                                                ></canvas>
                                                <canvas
                                                    id="new-canvas"
                                                    class="overlay-canvas"
                                                    style="
                                                        border: 1px solid black;
                                                    "
                                                ></canvas>
                                                <div>
                                                    <span
                                                        >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span
                                                    >
                                                    <span
                                                        >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span
                                                    >
                                                    <span
                                                        >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span
                                                    >
                                                    <span
                                                        >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span
                                                    >
                                                    <button class="btn btn-primary"
                                                        id="prev"
                                                        @click="
                                                            irPaginaAnterior
                                                        "
                                                    >
                                                        Anterior
                                                    </button>
                                                    <button class="btn btn-primary"
                                                        id="next"
                                                        @click="
                                                            irPaginaSiguiente
                                                        "
                                                    >
                                                        Siguiente
                                                    </button>
                                                    <span
                                                        >Pagina:
                                                        <span id="page_num">{{
                                                            this.pageNum
                                                        }}</span>
                                                        de
                                                        <span
                                                            id="page_count"
                                                        ></span
                                                    ></span>
                                                    <button
                                                        class="btn btn-success"
                                                        @click="
                                                            agregarFirmaDigital
                                                        "
                                                    >
                                                        Guardar Rubrica Firma
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    inject: ["openModal"],
    data() {
        return {
            modalActivo: false, // Controla la visibilidad del modal
            modalVisorPdfDisplay: "none",
            pdfManagerDisplay: "none",
            showPdfManager: true,
            maxPDFx: 595,
            maxPDFy: 842,
            offsetY: 7,

            pageRendering: false,
            pageNumPending: null,
            scale: 1.5,
            canvas: null,
            ctx: null,
            tipoDocumento: "",
            //***** */
            pdfDoc: null,
            pageNum: 1,
            canvas: null,
            pageContainer: null,
            pdfBase64: null,

            firmaX: -0.5,
            firmaY: -110,
            deltaFirmaX: 0,
            deltaFirmaY: 0,
            usrId: window.Laravel.usr_id,
        };
    },
    mounted() {
        // Obtener el canvas y su contexto
        this.canvas = document.getElementById("the-canvas");
        this.ctx = this.canvas.getContext("2d");

        // Obtener el nuevo canvas y su contexto
        this.newCanvas = document.getElementById("new-canvas");
        this.newCtx = this.newCanvas.getContext("2d");
        // Event listeners para el movimiento del mouse
        document.addEventListener("mousedown", this.iniciarArrastre);
        document.addEventListener("mousemove", this.moverCanvas);
        document.addEventListener("mouseup", this.detenerArrastre);

        // Llamar a la función para cargar y mostrar la imagen
        this.mostrarImagen();
    },
    methods: {

        resetFirmaDocDesp(e) {
            const cas_id = this.registro.cas_id;
            const cas_act_id = this.registro.cas_act_id;

            const params = { "cas_id": cas_id, "act_id": cas_act_id, "tipo": "derivar" };

            const url = "api/resetearFirmaDocDesp";
            return axios.post(url, params).then(response => {
                 return true;
             });
        },

        incrustar(val) {
            // this.modalActivo = true;

            switch (val) {
                case "normal": {
                    // miAplicacion.cerrarWinSubirArchivo();
                    this.modalVisorPdfDisplay = "block";
                    break;
                }
                case "fondos": {
                    console.log("fondos");
                    //miAplicacion.cerrarWinSubirArchivoFondos();
                    this.modalVisorPdfDisplay = "block";
                    break;
                }
            }
        },
        cerrarModal() {
            this.modalVisorPdfDisplay = "none";
        },

        handleButtonClick() {
            this.openModal();
        },

        initVisor(val64) {
            console.log("initVisor", val64);
            var pdfData = atob(val64);
            this.pdfBase64 = val64;
            pdfjsLib
                .getDocument({ data: pdfData })
                .promise.then((pdfDoc) => {
                    // PDF cargado exitosamente
                    console.log("PDF cargado:", pdfDoc);
                    const canvas = document.getElementById("the-canvas");
                    const pageContainer =
                        document.getElementById("pageContainer");
                    pdfjsLib
                        .getDocument({ data: pdfData })
                        .promise.then((pdfDoc) => {
                            this.pdfDoc = pdfDoc;
                            // Renderizar la primera página
                            // this.renderizarPagina(pdfDoc, 1, canvas, pageContainer);
                            this.renderizarPagina(1);
                        })
                        .catch((error) => {
                            console.error("Error al cargar el PDF:", error);
                        });
                    // Actualizar el contador de páginas
                    const pageCountElement =
                        document.getElementById("page_count");
                    if (pageCountElement) {
                        pageCountElement.textContent = pdfDoc.numPages;
                    }
                    //this.renderPage('normal');
                })
                .catch((error) => {
                    // Manejar errores de carga del PDF
                    console.error("Error al cargar el PDF:", error);
                });
        },
        // renderizarPagina(pdfDoc, numPagina, canvas, pageContainer) {
        renderizarPagina(numeroPagina) {
            this.pdfDoc.getPage(numeroPagina).then((page) => {
                const viewport = page.getViewport({ scale: 1 });
                this.canvas.height = viewport.height;
                this.canvas.width = viewport.width;

                const context = this.canvas.getContext("2d");
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport,
                };

                // Renderizar la página en el canvas
                page.render(renderContext);

                // Mostrar el contenedor de la página
                if (this.pageContainer !== null) {
                    this.pageContainer.style.display = "block";
                }

                // Actualizar el número de página actual
                this.pageNum = numeroPagina;
            });
        },
        irPaginaAnterior() {
            if (this.pageNum > 1) {
                this.renderizarPagina(this.pageNum - 1);
            }
        },
        irPaginaSiguiente() {
            if (this.pageNum < this.pdfDoc.numPages) {
                this.renderizarPagina(this.pageNum + 1);
            }
        },

        iniciarArrastre(event) {
            // Verificar si el clic fue en el nuevo canvas
            if (event.target === this.newCanvas) {
                this.dragging = true;
                this.dragStartX = event.clientX - this.newCanvas.offsetLeft;
                this.dragStartY = event.clientY - this.newCanvas.offsetTop;

                this.deltaFirmaX = event.clientX - this.dragStartX;
                this.deltaFirmaY = event.clientY - this.dragStartY;
            }
        },
        moverCanvas(event) {
            if (this.dragging) {
                const offsetX = event.clientX - this.dragStartX;
                const offsetY = event.clientY - this.dragStartY;
                this.newCanvas.style.left = offsetX + "px";
                this.newCanvas.style.top = offsetY + "px";
            }
        },
        detenerArrastre() {
            if (this.dragging) {
                const offsetX = event.clientX - this.dragStartX;
                const offsetY = event.clientY - this.dragStartY;

                this.firmaX = this.firmaX + (this.deltaFirmaX - offsetX);
                this.firmaY = this.firmaY + (this.deltaFirmaY - offsetY);

                this.dragging = false;
            }
        },
        mostrarImagen() {
            // Crear una nueva instancia de la imagen
            const imagen = new Image();
            imagen.src = "img/rubrica.jpg";
            imagen.onload = () => {
                const nuevoAncho = imagen.width / 7;
                const nuevoAlto = imagen.height / 7;
                this.newCanvas.width = nuevoAncho + 15;
                this.newCanvas.height = nuevoAlto;
                this.newCtx.drawImage(imagen, 0, 0, nuevoAncho + 15, nuevoAlto);
                this.newCanvas.classList.add('rubrica');
                this.newCanvas.id = 'mi-id';
            };
        },
        async agregarFirmaDigital() {
            if (!this.pdfBase64) {
                console.error("Documento PDF no cargado");
                return;
            }
            const cleanBase64 = this.pdfBase64.replace(/\s+/g, '');
            console.log("🚀 ~ Cleaned Base64 PDF:", cleanBase64);
            this.pdfBase64 = cleanBase64;
            var parametros = {
                slot: this.$store.state.token,
                pin: this.$store.state.pin,
                alias: this.$store.state.alias,
                pdf: this.pdfBase64,
                rubrica: {
                    x: -1 * this.firmaX,
                    y: this.firmaY,
                    ancho: 200,
                    alto: 100,
                    pagina: this.pageNum,
                    tamanioFuente: 6,
                    imagenEscala: 0.5,
                    imageBase64:
                        "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",
                    margenTextos: 10,
                    dimesionLogo: 80,
                },
            };

            var that = this;
            $.ajax({
                url: "https://localhost:9000/api/token/firmar_pdf",
                type: "POST",
                data: JSON.stringify(parametros),
                contentType: "application/json",
                dataType: "json",
                success: function (json) {
                    var pdfBase64 = json.datos.pdf_firmado;

                    var binaryString = window.atob(pdfBase64);
                    var binaryLen = binaryString.length;
                    var bytes = new Uint8Array(binaryLen);
                    for (var i = 0; i < binaryLen; i++) {
                        var ascii = binaryString.charCodeAt(i);
                        bytes[i] = ascii;
                    }

                    var blob = new Blob([bytes], { type: "application/pdf" });
                    var url = URL.createObjectURL(blob);
                    window.open(url);

                    that.cerrarModal();
                    that.handleButtonClick();
                    that.registarArchivoUnoPorUno(pdfBase64);
                },
                error: function (xhr, status) {
                    Ext.MessageBox.alert(
                        "<b>ATENCIÓN</b>",
                        "Hubo un problema con su Token."
                    );
                },
            });
        },

        registarArchivoUnoPorUno(pdfBase64) {console.log('registarArchivoUnoPorUno');
            const registro = this.$store.state.registro;
            console.log("LOS REGISTROS QUE LLEGAN >>>> ",registro);
            console.log("aquiii",this.usrId);

            this.resetFirmaDoc(registro);

            const items = registro.cas_data_valores;
            const ci = registro.cas_data.AS_CI;
            let id_persona = "";
            for (var i = 0; i < items.length; i++) {
                if (items[i].frm_campo == "AS_IDPERSONA") {
                    id_persona = items[i].frm_value;
                }
            }

            const datos = {
                tam: 2000,
                valor_id: this.$store.state.docDocId ? this.$store.state.docDocId : 100 + this.$store.state.indexDocFirma,
                valor_descripcion: this.$store.state.nombreDocumento,
                pdf: pdfBase64,
                caso: registro.cas_cod_id,
                id_caso: registro.cas_id,
                documentoOriginalObligatorio: "",
                presentacionObligatoria: "",
                ci: this.$store.state.docCategoria ? this.$store.state.docCategoria : "categoria", //ci,
                parentesco: this.$store.state.docReferencia ? this.$store.state.docReferencia: "referencia",
                switch: "",
                pfrm_value: "",
                id_persona_sip: id_persona,
                id_observacion: "1",
                detalle_documento: this.$store.state.actividad ? this.$store.state.actividad : "",
                usr_id: this.usrId,
            };
            /*const datos = {
                tam: 2000,
                valor_id: 100 + this.$store.state.indexDocFirma,
                valor_descripcion: this.$store.state.nombreDocumento,
                pdf: pdfBase64,
                caso: registro.cas_cod_id,
                id_caso: registro.cas_id,
                documentoOriginalObligatorio: "",
                presentacionObligatoria: "",
                ci: "categoria", //ci,
                parentesco: "referencia",
                switch: "",
                pfrm_value: "",
                id_persona_sip: id_persona,
                id_observacion: "1",
            };*/
            console.log("documentos datoss =====>", datos);
            axios
                .post("api/guardarDocumentosRequisitos", datos)
                .then((response) => {
                    console.log("respuesta", response.data);
                    this.pdfBase64 = "";
                })
                .catch((error) => {
                    console.error("Error al generar o abrir el PDF", error);
                });
        },

        async resetFirmaDoc(e) {

            console.log("resetFirmaDoc", e);

            const { cas_id, cas_act_id } = e;

            const params = { "cas_id": cas_id, "act_id": cas_act_id, "tipo": "derivar" };

            console.log("el params que se envia", params);

            const url = "api/resetearFirmaDoc";
            return axios.post(url, params).then(response => {
                 return true;
             });
        },
    },
};
</script>

<style scoped>
/* firma */
/* The Modal (background) */
.modal {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    padding-top: 100px;
    /* Location of the box */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.4);
    /* Black w/ opacity */
}

.rubrica {
    border: 2px solid red;
    animation-name: pulse;
    animation-duration: 1s;
    animation-iteration-count: infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}


.titulo-rubrica {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-bottom: 10px;
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 30%;
    font: 12px sans-serif;
}

.modal-content-verificador {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 30%;
    font: 12px sans-serif;
    max-height: calc(100vh - 210px);
    overflow-y: auto;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

/* Visor pdf */
.modalVisorPdf {
    background-color: rgba(0, 0, 0, 0.8);
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 2;
    /* Sit on top */
    /*padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.4);
    /* Black w/ opacity */
}

/* The Close Button */
.closeVisorPdf {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.closeVisorPdf:hover,
.closeVisorPdf:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.centerVisorButton {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 50px;
    border: 3px solid blue;
}

.modal-contenidoVisorPdf {
    background-color: #ffffff;
    max-width: 650px;
    height: auto;
    padding: 20px 20px;
    margin: 20% auto;
    position: relative;
}

.modalOpcione {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    padding-top: 100px;
    /* Location of the box */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.4);
    /* Black w/ opacity */
}

/* Modal Content */
.modal-contentOpcione {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 42%;
    font: 12px sans-serif;
}

.closeOpcione {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.closeOpcione:hover,
.closeOpcione:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.grey {
    background-color: rgba(128, 128, 128, 0.25);
}

.red {
    background-color: rgba(255, 0, 0, 0.25);
}

.blue {
    background-color: rgba(0, 0, 255, 0.25);
}

.buttonFirmar {
    background-color: #008cba;
    border: none;
    color: white;
    padding: 10px 35px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}

.buttonverDocumento {
    background-color: #af8b08;
    border: none;
    color: white;
    padding: 10px 35px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}

.buttonFirmarUpload {
    background-color: #d96c00;
    border: none;
    color: white;
    padding: 10px 35px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}

/* estilo select*/
.box {
}

.box select {
    background-color: #0563af;
    color: white;

    width: 250px;
    border: none;
    font-size: 15px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    -webkit-appearance: button;
    appearance: button;
    outline: none;
}

.box::before {
    font-family: FontAwesome;
    position: absolute;
    top: 0;
    right: 0;
    width: 20%;
    height: 100%;
    text-align: center;
    font-size: 28px;
    line-height: 45px;
    color: rgba(255, 255, 255, 0.5);
    background-color: rgba(255, 255, 255, 0.1);
    pointer-events: none;
}

.box:hover::before {
    color: rgba(255, 255, 255, 0.6);
    background-color: rgba(255, 255, 255, 0.2);
}

.textoFrmtoken {
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.btnFrmtoken {
    background-color: #4caf50;
    border: none;
    color: white;
    padding: 5px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
}

/* subir archivo*/
.inputfile-box {
    position: relative;
}

.inputfile {
    display: none;
}

.container {
    display: inline-block;
    width: 100%;
}

.file-box {
    display: inline-block;
    width: 80%;
    border: 1px solid #ccc;
    padding: 5px 0px 5px 5px;
    box-sizing: border-box;
    border-radius: 4px;
    height: calc(2rem - 2px);
}

.file-button {
    background: #0563af;
    color: white;
    padding: 5px;
    position: absolute;
    border: 1px solid;
    top: 0px;
    right: 0px;
}

/* loader */
/* (A) FULL SCREEN WRAPPER */
#spinner {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 9999;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.7);
    transition: opacity 0.2s;
}

/* (B) CENTER LOADING SPINNER */
#spinner img {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%);
}

/* (C) SHOW & HIDE */
#spinner {
    visibility: hidden;
    opacity: 0;
}

#spinner.show {
    visibility: visible;
    opacity: 1;
}

/* The Close Button */
.closeVisorPdf {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.closeVisorPdf:hover,
.closeVisorPdf:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.centerVisorButton {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 50px;
    border: 3px solid blue;
}

/*.modal-contenidoVisorPdf {
	background-color: #ffffff;
	width: 1000px;
	height: auto;
	padding: 20px 20px;
	margin: 20% auto;
	position: relative;
}*/

.modalOpcione {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    padding-top: 100px;
    /* Location of the box */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.4);
    /* Black w/ opacity */
}

/* Modal Content */
.modal-contentOpcione {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 42%;
    font: 12px sans-serif;
}

.closeOpcione {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.closeOpcione:hover,
.closeOpcione:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.grey {
    background-color: rgba(128, 128, 128, 0.25);
}

.red {
    background-color: rgba(255, 0, 0, 0.25);
}

.blue {
    background-color: rgba(0, 0, 255, 0.25);
}

.buttonFirmar {
    background-color: #008cba;
    border: none;
    color: white;
    padding: 10px 35px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}

.buttonverDocumento {
    background-color: #af8b08;
    border: none;
    color: white;
    padding: 10px 35px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}

.buttonFirmarUpload {
    background-color: #d96c00;
    border: none;
    color: white;
    padding: 10px 35px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}

/* estilo select*/
.box {
}

.box select {
    background-color: #0563af;
    color: white;

    width: 250px;
    border: none;
    font-size: 15px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    -webkit-appearance: button;
    appearance: button;
    outline: none;
}

.box::before {
    font-family: FontAwesome;
    position: absolute;
    top: 0;
    right: 0;
    width: 20%;
    height: 100%;
    text-align: center;
    font-size: 28px;
    line-height: 45px;
    color: rgba(255, 255, 255, 0.5);
    background-color: rgba(255, 255, 255, 0.1);
    pointer-events: none;
}

.box:hover::before {
    color: rgba(255, 255, 255, 0.6);
    background-color: rgba(255, 255, 255, 0.2);
}

.textoFrmtoken {
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.btnFrmtoken {
    background-color: #4caf50;
    border: none;
    color: white;
    padding: 5px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
}

/* subir archivo*/
.inputfile-box {
    position: relative;
}

.inputfile {
    display: none;
}

.container {
    display: inline-block;
    width: 100%;
}

.file-box {
    display: inline-block;
    width: 80%;
    border: 1px solid #ccc;
    padding: 5px 0px 5px 5px;
    box-sizing: border-box;
    border-radius: 4px;
    height: calc(2rem - 2px);
}

.file-button {
    background: #0563af;
    color: white;
    padding: 5px;
    position: absolute;
    border: 1px solid;
    top: 0px;
    right: 0px;
}

.overlay-canvas {
    position: absolute; /* o relative, según tus necesidades */
    bottom: 0;
    left: 0;
    z-index: 2; /* Ajusta esto para que el nuevo canvas esté sobre el primero */
}
</style>

<template>
    <div class="container-fluid">
        <section class="content">
            <div class="card">
            <div class="card-header">
                <h3 class="card-title">Registro de Tramites por Departamento {{ departamento }}</h3>
                <div class="card-tools">
                    <div class="btn-group" role="group">
                        <router-link to="/indicadores">
                            <button type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </button>
                        </router-link>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-16 col-md-12 col-lg-10 order-2 order-md-1">
                        <div class="row">
                            <div class="col-12">
                                <div class="navegacion">
                                    <div class="d-flex justify-content-start">
                                        <button type="button" class="btn btn-primary mb-3 mr-2" data-toggle="modal" data-target="#filterUno">
                                            <i class="fas fa-filter"></i>
                                        </button>

                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="submitForm(3)" title="Limpiar Filtros">
                                            <i class="fas fa-broom"></i>
                                        </button>
                                    </div>

                                    <div class="d-flex justify-content-start">
                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn" style="width: 40px; height: 40px;" data-toggle="modal" data-target="#alertaTramitesModalComponent" title="Informativo">
                                            <i class="fas fa-lightbulb"></i>
                                        </button>

                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle ml-2" style="width: 40px; height: 40px;" @click="submitForm()" title="Actualizar">
                                            <i class="fas fa-retweet"></i>
                                        </button>
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped" style="border: 1px solid #ddd; border-radius: 10px; padding: 10px;">

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 col-md-12 col-lg-2 order-1 order-md-2">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <img :src="departamentoInfo.img" :alt="departamentoInfo.nombre" style="width: 80%; height: auto; display: block; margin: 0 auto 5px;" />
                                <center> <span class="badge bg-primary text-center">{{ oruro }}</span></center>
                                <span class="info-box-text text-center text-muted">{{ departamentoInfo.nombre }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>
    </div>
</template>

<script>
    import axios from 'axios'
    import pieChart from '../Pie.vue'

    export default {
        name: 'DetalleDepartamento',
        components: {
            pieChart
        },

        props: {
            departamento: {
                type: String,
                required: true
            }
        },
    data() {
        return {
            regionales: [],
            listadoTramites: [],
            selectAll: false,
            FechaHora: '',
            usrUser: window.Laravel.usr_user,
            procesos: [],
            selectedProcesos: [],
            chartOptions: {},

            filtro: {
                prc_codigo: '',
                cas_nro_caso: '',
                cas_tipo: '',
                fecha_ini: this.fechaIni,
                fecha_fin: '',
                filtros: {
                fecha_inicio: '',
                fecha_fin: '',
                gestion: ''
                },
                id_departamento: '',
                id_agencia: '',
                id_regional: '',
                id_area: ''
            },

            pieChartData: [],

            lapaz : 0,
            oruro : 0,
            potosi : 0,

            departamentos_det : [
                { id: 1, nombre: 'LA PAZ', img: 'data:image/webp;base64,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' },
                { id: 2, nombre: 'BENI', img: 'data:image/webp;base64,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' },
                { id: 3, nombre: 'PANDO', img: 'data:image/webp;base64,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' },
                { id: 4, nombre: 'ORURO', img: 'data:image/webp;base64,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'},
                { id: 5, nombre: 'COCHABAMBA', img: 'data:image/webp;base64,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'},
                { id: 6, nombre: 'SANTA CRUZ', img: 'data:image/webp;base64,UklGRgIHAABXRUJQVlA4WAoAAAAQAAAAxwAAxwAAQUxQSDAEAAABV6C2jSQ1eeHdc8bMHxEBxqb6d3MSQVcAaLVtdQSW8zaVYVIZJpVbTyq3npxX1hQoenLK1VQGTY9BW6JYcH7zxfeee+7Roymi/xMA/6ffBLBFtudbYam4/CkV7RkWXxERi2uCLaHidrGWUDX3hDrfKmHqiBTXqJk6AkVVq4NPfHHCKRJmnjS/IOlYmGdI7ImyhNSBJPNIfkSQ+YZuLEfcIH0uRlyhwVSKqEKDRSBE+CsadYX4iGYdGZ5hB8y3plwJkgZNewIkNRrv2RfVaH7OurBChkPbwl+RY2bbR+Tp2fUMmQZWLSHXOZu+bztgGfkOrbnwGRkXjh3nPyNv344Su2CVW8+OyT+GBW7B35cJt74dPzArLCmZYQd8ffUqsGPE6ShYGzaMhvbAlBF69qxxOmzPhFPmWJNwwsAamHJK7ZlwQt+amNXAGnjGqXCtiTlh3xqoOR21Z8ppaE/SdAHMM0otgvVOGPEZ2xS2XQA1m8yqdYnCRw9v3bp0ct9eZ0Zccyk4LeDsb68e3r586cTe91yQ0w8KiNgia4fRSI25yyiyyGME3dDY43OadkJpT4/TqjVF0AXYDf1OuHf5xF5HvA1fP7p969KJvY6p0rLZrx/dvnXpxN5NAMdJ1oWYnf+Rk1TCII4polacIcUKijugmMqzg6ISJ3e74DAQhq04LkWM4kInOBRhJ0TdUHUBjDoBKmlcmqQRxqOBRWF8onAqS0AEUS1J0aeCkST4HRl8kGQ3XSLJUTqoOmGlE+JOgFaO1DFQy4GpQ/Xgd5T0LFGCshY+zUgYzDySUhq87hCEjTh4KHK1FlDevLmnVQqEiIFOLVNfI8EuWBFqoBbVQmWO0geU2lVqxPKUKrF6SqVYvtJErL5SLNZACWqpxmqlVOgqjcQKlKCW6qhaKdVYbUEq9JRisXYoQS3VdrVSqNxRS1qZUtD8WaaBzkimOZ1Ypp5OKJOrA61EGejGKPFQKxFpl1YkUqAFrUSeXiVQ4ei9EygF/USgAQFU8sxRvJenRxFV4vgUMML84RfxoDoCUEuyk6YMABpJBjQTD0KUtEcDALEkYyBPJNnVCb0uyB262Ibi68Z/6KQgxJNTm0E1nGrslCE75YBm3Kj5BiI++RUX9OeVcrDv8WUXSD+oDE2ELB5f3gLUYaXQNwHGikeXN4PJpJnlG2mN5HdOuWB6cUYGRhu6N7dPOsDx40YDO84B13C6Qd9MTRawgaT5i2eHywcWEXEMZiuqDDh/RBzYcY9VWOGcHbtYQdKYmlIFvGAxNbRO5TELK9+GDLhPB2bWiIbsqsIzskq0i12Jh428JeqxW8PcMTGhKVx2q/jGM7FAMwT2P2QucCsCfm93ALvc4Tdy+J0DaUckPZH+1MlAouKizhF5EsQzYavhyxO/vAMwVRuD0PGlm7dmX94q1cwQ/s8JVlA4IKwCAADwGgCdASrIAMgAPpFInUolpSMhpxg5wLASCWNu4Wuw4iFatd5DD/u6RitsB5gPOA/wHqA/yu+AegB0o2RV+AP4B+DX1+9/gp9EieA8dWq+y9qI8p4LPkFd1BzxKh5A4rl64y6tjd8OXpmCpHJ2IB57cRb6NqGI43z24E8LMvEq28Fp2YCp3MAI2o5VZawoUrj60hSGaMgahdL/6IM+qQzRl+tsflEhglth0WXfDLEOsrUn0ady+HFAIc7Eo7PaJDLk8yYhnO2Rgt1sFSU5NQu8JGm/1fA7/G8ZLY3CUBU0AAD++QfeMGk2/oNXqu6Z8o+xc6e63VRYyfN66DKRtH31QAIsZpxNNM87LVmAvNbSaKNP4AyAT6yL6RcgQAVoRzutSig4K4VH727y1fACYXy+22zsb3QT1Kv1ehPOK/jbscqgMrIh3wH1lgZSsbpTrO5i0pPlm/zkqDJbw4yB+V8LHEQr73TgefvmcM6TDQAsYVv73DtB/rwI3p7k81JdmMPvyckrk9/1xoapEDBxhzMpuUO0Ns2YZoZiVt0zS/6U/wEw05K1kYdQqELxVexTmV6eU+flt4HentHlqSQJ9wB6kWHkAGYEYLHfX0IAQfQZopn/veJGB4vLd5V19yN8TBV6OIvlasz0Av7vaJJuWPkxgU8TUAXSPjLOd7aTR1gby/06M2mOM8s1LeLOd4GTgx7AtRZ4g+DrcVgr1sLvXDqSQ2HP5ptLkjr65iFjNf4w5y6Szl29gyakTw1YvaMmCm3iTONW1anzPJNRHDiN0qpFjXuVVoxiV/X7QUupJ077oTmGMQ4/lIAj+KITcpZnymf4V2xo0T5f/8a6vlH0tNwg+y73sGdU5GM9GLYh0noij6cr4pzrYK//w+uOi31Af/+LtQLnLYZUyqWAAAA='},
                { id: 7, nombre: 'POTOSI', img: 'data:image/webp;base64,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'},
                { id: 9, nombre: 'CHUQUISACA', img: 'data:image/webp;base64,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'},
                { id: 10, nombre: 'TARIJA', img: 'data:image/webp;base64,UklGRoQFAABXRUJQVlA4WAoAAAAQAAAAxwAAxwAAQUxQSNACAAABN6CobSM2VSh9jpsREaA+O6BrW3vUyPoR4JiqviamJ2JPi7hG4hoVm+l7/3ew+AXiU9jrRvR/AuR///878HBJ6CQLDGgTMm9t4lUA+Lu5h8u9asDe6wEAzNY64D/NBJv4dDd7AvgxVwDY+ww3uL/TFkURXA8Ar8b1AMD6TI6l7bf76+vrxXl+Pp/Pp9+/ft3f3amGGyB1vAFApquw4e9sbnTZ5Ka7aXQPW8JubnKhvhluYFRvm8r9kIoUk6NRdZtK5zBvjbzB/efX3dywKTNTKGBfoG2Na9yUzFYaX5s4sGUbAFojItWmmrnDCsD39/f3psq5h1U2n8+9McuiYOauxKzMd8TaKNSKntguCpliIGYUIzGJQauZeNVRKDXgnUchVRTEEkXFy0oMWs2BVx2FXRQyzQMvE4UkBla0b7SaKJSqK608Cpmqp2VUAy1RT6xaVQHWteqB1k7V0cpUA61UU4B2ojnQsqK90mpUA61SNdHKNRVop5oDL6N54CXailarKmjVKhlY7XRvrDJdxcroZCSVeLxzsuJZcWp8ZKJUe3WUdl4PlDKvglLqJSOjxK8LxV7uk58DgNeXl/PlvPTksOL/EIg1IiLFZI2sOjjqBapASnG+7WXdzpEvIFMYmWv1qyNbYmTy4EiX6MNIgjJLyK9f5+fVSgn04JDFj9NKaVDtciI/n1cxQdVriBSfy1kJtbop1xF5WqwNRiYA2VpynBbah3MEYFYTOY2LmHDkEVaC/FjASsjvTRjS+zVBSRpI5VeGFezklVLqfaxQPvjUnGT0yEn1HobUVWeF9JuuiULJ6qDLWVW6jJXoDK1RldDqNVZoXzU1rwdNyqtQtEJ8msuZ9TM2YfY2UwrzaialJqPDCvc3R0mucqTkZLpJYtAI+xFAGYWc3gAgi0JKr49CB8DQqwAk9GQChH8PG4E3tBGQsYnBWx2DqoyBZFH4JyhWUDggjgIAABAZAJ0BKsgAyAA+kUifSiWkoyGneqiQsBIJZW7hbcDwXRrE/qeTYOzzz9tX5gOgBvBHoAdJP/csm58AfwD8AKb9ERTKk8caYemVJ440w9MqTxxph6NTJ1qHWuNbOWqkO54OQ6+rJo2PXbXJ19KPCr+PsO8cNUbR+2ONMO3rWYeZRq0JSeNRJZs/xBZOaLpUL1SzmTSQ4DJ1RwMkMEyUIFACp3PlOHEhUbtJ3fDBAp+XaaAgsXttmDXO95P0qHTKjGUJRvrlfDRdKhlzSdUMuXoAAP76Dx5SJy/gQ7dpgAAAAZDp2LWq/8SWtNyH8BWuBMcOzVRu9oz/QXsx89VL5Blb0bLp6wn0XHBNsOah9bWzbsgn5ynBXU/nds6UYLjpcPf7gSbKEGIfWjMDiJ3LZ3phW33QD2/StFpP3+VTJ/UrVIZMm/e3hZGBoDa0sWy5LzNlwuldEepIGHocxSOTERQXQDvMlFyQafhA20fRX6Nvtn42yq+f+oW9AASs0WFfMdY37/4N/7dj//+gsB+t++zXkKgc03lV65k1NECfPhnrWUSQHVbS+N77CsV7NV/3D9+4T50CFp8MsBNPX5ghbjUIpljj+4f//54hY576B+9fECgjtrPWZEzB9vf1KAHPHITE+tDZNEjKWF8AAV3k3c1dkBnBu35cAJZbnEv4C/dT2eS+XPGRmjk2MB0wBvl/B637EoLG1vit2QnfrDYB/SGquZNhJGfdmORPGSYVlht95h8myGq1c83SBT+fR5xpLVnz9oq6ZmJX8zf9n1ns8S8HROCt+ZerWJURAACtvhem4ZEr1+YZPNfwRZLJqG8+YiEPpt1yk6z+TeIAEKOqlhBsR9MfNrgB+y6AAAAAAA=='}
            ],

            selectedIds: [4, 12, 3, 1, 13, 9, 5, 15, 16],
            selectedCodes: ['RMIN', 'MAHER', 'PM', 'INV', 'PAGCC', 'JUB', 'GFU', 'JUB1582', 'LEGAL']
        };
    },
    mounted() {
        this.updateDateTime();
        setInterval(this.updateDateTime, 1000);

        setTimeout(() => {
            this.loading = false;
        }, 2000);

        this.enviarDatos();
    },

    computed: {
        departamentoInfo() {
            return this.departamentos_det.find(dep => dep.nombre === this.departamento);
        }
    },

    methods: {

        enviarDatos() {
            const payload = {
                selectedIds: this.selectedIds,
                selectedCodes: this.selectedCodes,
                departamento: this.departamento
            };

            axios.post('api/v1/kpi/cantidadTramiteXDepto', payload)
                .then(response => {
                    console.log('Respuesta del servidor Registros por dpto:', response.data);
                })
                .catch(error => {
                    console.error('Error al enviar los datos:', error);
                });
        },

        updateDateTime() {
            const now = new Date();
            this.FechaHora = now.toLocaleString();
        },
        updateChartData() {
            this.pieChartData = this.regionales.map(item => ({
                value: item.total,
                name: item.cas_regional
            }));
        }
    }
    };
</script>

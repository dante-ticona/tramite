<template>
    <div>
        <div class="row"
            
            style="background: rgb(248, 247, 240); display: flex 1; justify-content: stretch; margin: 5px;">
            <div class="col-md-2">Componentes
                <div style="margin: 1px; flex: 1; background:lightcyan;">
                    <Container behaviour="copy" group-name="1" :get-child-payload="getChildPayload1">
                        <Draggable v-for="item in items1" :key="item.id">
                            <div class="draggable-item">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-9"
                                        style="border: 1px solid #54a3e2; margin: 5px; font-size:x-small;">
                                        <i class="fa fa-bars blue"></i>
                                        {{item.data}}
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>
                            </div>
                        </Draggable>
                    </Container>
                </div>
            </div>
            <div class="col-md-7">Formulario
                <div style="border: 1px dotted; margin: 5px; flex: 1; background:rgb(230, 230, 230);">
                    <Container group-name="1" :get-child-payload="getChildPayload2" @drop="onDrop('items2', $event)"
                        :remove-on-drop-out="true">
                        <Draggable v-for="(item, idx) in items2" :key="item.id">
                            <div class="draggable-item" style="margin: 2px; border: 1px dotted; ">
                                <div class="row" style="margin: 5px;">
                                    <div class="col-md-1" v-if="idx >= 0">
                                        <i class="fa fa-bars blue" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-md-9" v-if="item.data == 'TITLE'">
                                        <h1>{{ item.data2 }}</h1>
                                    </div>
                                    <div class="col-md-9" v-if="item.data == 'SUBTITLE'">
                                        <h3>{{ item.data2 }}</h3>
                                    </div>
                                    <div class="col-md-9" v-if="item.data == 'TEXT'">{{ item.data2 }} <input type="text"
                                            class="form-control"></div>
                                    <div class="col-md-9" v-if="item.data == 'NUMBER'">{{ item.data2 }} <input
                                            type="number" class="form-control"></div>
                                    <div class="col-md-9" v-if="item.data == 'DATE'">{{ item.data2 }} <input type="date"
                                            class="form-control"></div>
                                    <div class="col-md-9" v-if="item.data == 'TEXTAREA'">{{ item.data2
                                        }} <textarea class="form-control" rows="3"></textarea>
                                    </div>
                                    <div class="col-md-9" v-if="item.data == 'CHECKBOX'">{{ item.data2
                                        }} <input type="checkbox" class="form-c-ontrol">
                                    </div>

                                    <div class="col-md-9" v-if="item.data == 'DROPDOWNLIST'">
                                        {{ item.data2 }} <select class="form-control">
                                            <option>Opción 1 local</option>
                                            <option>Opción 2 local</option>
                                        </select>
                                    </div>
                                    <div class="col-md-9" v-if="item.data == 'SELECT'">
                                        {{ item.data2 }} <select class="form-control">
                                            <option>Opción 1 servicio web</option>
                                            <option>Opción 2 servicio web</option>
                                            <option>Opción 3 servicio web</option>
                                            <option>Opción 4 servicio web</option>
                                        </select>
                                    </div>
                                    <div class="col-md-9" v-if="item.data == 'SCRIPT'">{{ item.data2
                                        }} <textarea class="form-control" disabled></textarea>
                                    </div>

                                    <!-- Propiedades -->
                                    <div class="col-md-1" v-if="idx >= 0" @click="doPropiedades(idx)">
                                        <button>
                                            <i class="fas fa-table blue" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </Draggable>
                    </Container>
                </div>
            </div>
            <div class="col-md-2">Propiedades
                <div class="col-md-10" style="background:lightcyan; width:100%;">
                    <div style="font-size:x-small;">
                        <div>
                            <div>Tipo</div>
                            <div><input type="text" v-model="tipo" disabled></div>
                        </div>
                        <div>
                            <div>Campo</div>
                            <div><input type="text" v-model="campo" @change="actualizarCampo()"></div>
                        </div>
                        <div>
                            <div>Etiqueta</div>
                            <div><input type="text" v-model="etiqueta" @change="actualizarCampo()"></div>
                        </div>
                        <div>
                            <div>Deshabilitado</div>
                            <div>
                                <select v-model="deshabilitado" @change="actualizarCampo()">
                                    <option value="false">No</option>
                                    <option value="true">Si</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div>Obligatorio</div>
                            <div>
                                <select v-model="obligatorio" @change="actualizarCampo()">
                                    <option value="false">No</option>
                                    <option value="true">Si</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div>OnChange</div>
                            <div><input type="text" v-model="onChan" @change="actualizarCampo()"></div>
                        </div>
                        <div>
                            <div>OnClick</div>
                            <div><input type="text" v-model="onClic" @change="actualizarCampo()"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <input type="button" class="btn btn-primary btn-xl" value="Generar JSON" @click="doGenerarJSON()">
            <textarea v-model="jsonItems2" class="form-control" rows="5"></textarea>
        </div>
    </div>
</template>

<script>
    import { Container, Draggable } from 'vue-smooth-dnd'
    import { applyDrag } from './helpers'

    export default {
        name: 'Copy',

        components: { Container, Draggable },

        data() {
            return {
                tipo: '',
                campo: '',
                etiqueta: '',
                deshabilitado: '',
                obligatorio: '',
                onClic: '',
                onChan: '',

                items1: [{
                    id: '1',
                    data: `TITLE`,
                    data2: 'Ejemplo de Título',
                    data3: 'TITLE_',
                    data4: 'false',
                    data5: 'false'
                }, {
                    id: '2',
                    data: `SUBTITLE`,
                    data2: 'Ejemplo de Súbtitulo',
                    data3: 'SUBTITLE_',
                    data4: 'false',
                    data5: 'false'
                }, {
                    id: '3',
                    data: `TEXT`,
                    data2: 'Texto',
                    data3: 'TEXT_',
                    data4: 'false',
                    data5: 'false'
                }, {
                    id: '4',
                    data: `NUMBER`,
                    data2: 'Campo número',
                    data3: 'NUMBER_',
                    data4: 'false',
                    data5: 'false'
                }, {
                    id: '5',
                    data: `DATE`,
                    data2: 'Fecha',
                    data3: 'DATE_',
                    data4: 'false',
                    data5: 'false'
                }, {
                    id: '6',
                    data: `TEXTAREA`,
                    data2: 'Texto Largo',
                    data3: 'TEXAREA_',
                    data4: 'false',
                    data5: 'false'
                }, {
                    id: '7',
                    data: `CHECKBOX`,
                    data2: 'Opción',
                    data3: 'CHECKBOX_',
                    data4: 'false',
                    data5: 'false'
                }, {
                    id: '8',
                    data: `DROPDOWNLIST`,
                    data2: 'Lista (local)',
                    data3: 'DROPDOWNLIST_',
                    data4: 'false',
                    data5: 'false'
                }, {
                    id: '9',
                    data: `SELECT`,
                    data2: 'Lista (servicio web)',
                    data3: 'SELECT_',
                    data4: 'false',
                    data5: 'false'
                }],
                items2: [],
                jsonItems2: [],
                idx: -1
            }
        },

        methods: {
            onDrop(collection, dropResult) {
                this[collection] = applyDrag(this[collection], dropResult);
            },

            getChildPayload1(index) {
                return this.items1[index]
            },

            getChildPayload2(index) {
                return this.items2[index]
            },

            doPropiedades(idx) {
                this.tipo = this.items2[idx].data;
                this.etiqueta = this.items2[idx].data2;
                this.campo = this.items2[idx].data3;
                this.deshabilitado = this.items2[idx].data4;
                this.obligatorio = this.items2[idx].data5;

                this.idx = idx;
            },

            doGenerarJSON() {
                let i = 0;
                this.jsonItems2 = '[' + this.items2.map(s => {
                    i++;
                    let tipo = Object.values(s)[1];
                    let obj = {};
                    switch (tipo) {
                        case 'TITLE':
                            obj = {
                                "frm_tipo": Object.values(s)[1],
                                "frm_value": Object.values(s)[2]
                            };
                            break;
                        case 'SUBTITLE':
                            obj = {
                                "frm_tipo": Object.values(s)[1],
                                "frm_value": Object.values(s)[2],
                            };
                            break;
                        case 'TEXT':
                            obj = {
                                "frm_tipo": Object.values(s)[1],
                                "frm_value": Object.values(s)[2],
                                "frm_campo": Object.values(s)[3],
                                "frm_deshabilitado": Object.values(s)[4],
                                "frm_obligatorio": Object.values(s)[5],
                            };
                            break;
                        case 'NUMBER':
                            obj = {
                                "frm_tipo": Object.values(s)[1],
                                "frm_value": Object.values(s)[2],
                                "frm_campo": Object.values(s)[3],
                                "frm_deshabilitado": Object.values(s)[4],
                                "frm_obligatorio": Object.values(s)[5],
                            };
                            break;
                        case 'DATE':
                            obj = {
                                "frm_tipo": Object.values(s)[1],
                                "frm_value": Object.values(s)[2],
                                "frm_campo": Object.values(s)[3],
                                "frm_deshabilitado": Object.values(s)[4],
                                "frm_obligatorio": Object.values(s)[5],
                            };
                            break;
                        case 'CHECKBOX':
                            obj = {
                                "frm_tipo": Object.values(s)[1],
                                "frm_value": Object.values(s)[2],
                                "frm_campo": Object.values(s)[3],
                                "frm_deshabilitado": Object.values(s)[4],
                                "frm_obligatorio": Object.values(s)[5],
                            };
                            break;
                        case 'TEXTAREA':
                            obj = {
                                "frm_tipo": Object.values(s)[1],
                                "frm_value": Object.values(s)[2],
                                "frm_campo": Object.values(s)[3],
                                "frm_deshabilitado": Object.values(s)[4],
                                "frm_obligatorio": Object.values(s)[5],
                            };
                            break;
                        default:
                            obj = {
                                "frm_tipo": Object.values(s)[1],
                                "frm_value": Object.values(s)[2],
                                "frm_campo": Object.values(s)[3],
                            };
                    }
                    return JSON.stringify(obj);
                }) + ']';
                //let that = this;
                //this.jsonItems2 = Object.keys(that.items2).reduce((acc, key) => {
                //    return that.items2[key]
                //});
            },

            actualizarCampo(x) {
                this.items2[this.idx].data = this.tipo;
                this.items2[this.idx].data2 = this.etiqueta;
                this.items2[this.idx].data3 = this.campo;
                this.items2[this.idx].data4 = this.deshabilitado;
                this.items2[this.idx].data5 = this.obligatorio;
            }
        }
    }
</script>
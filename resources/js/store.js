import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    token: '',
    pin: '',
    alias: '',
    registro: '',
    nombreDocumento: '',
    indexDocFirma: '',
    indexDoc: '',
    docReferencia: '',
    docCategoria: '',
    docDocId: 0,
    actividad: '',
  },
  mutations: {
    updateToken(state, token) {
      state.token = token;
    },
    updatePin(state, pin) {
      state.pin = pin;
    },
    updateAlias(state, alias) {
      state.alias = alias;
    },
    updateRegistro(state, registro) {
      state.registro = registro;
    },
    updateNombreDocumento(state, nombreDocumento) {
      state.nombreDocumento = nombreDocumento;
    },
    updateIndexDocFirma(state, indexDocFirma) {
      state.indexDocFirma = indexDocFirma;
    },
    updateIndexDoc(state, indexDoc) {
      state.indexDoc = indexDoc;
    },
    updateDocReferencia(state, docReferencia) {
      state.docReferencia = docReferencia;
    },
    updateDocCategoria(state, docCategoria) {
      state.docCategoria = docCategoria;
    },
    updateDocDocId(state, docDocId) {
      state.docDocId = docDocId;
    },
    updateActividad(state, actividad) {
      state.actividad = actividad;
    },
  },
  actions: {
    updateToken({ commit }, token) {
      commit('updateToken', token);
    },
    updatePin({ commit }, pin) {
      commit('updatePin', pin);
    },
    updateAlias({ commit }, alias) {
      commit('updateAlias', alias);
    },
    updateRegistro({ commit }, registro) {
      commit('updateRegistro', registro);
    },
    updateNombreDocumento({ commit }, nombreDocumento) {
      commit('updateNombreDocumento', nombreDocumento);
    },
    updateIndexDocFirma({ commit }, indexDocFirma) {
      commit('updateIndexDocFirma', indexDocFirma);
    },
    updateIndexDoc({ commit }, indexDoc) {
      commit('updateIndexDoc', indexDoc);
    },
    updateDocReferencia({ commit }, docReferencia) {
      commit('updateDocReferencia', docReferencia);
    },
    updateDocCategoria({ commit }, docCategoria) {
      commit('updateDocCategoria', docCategoria);
    },
    updateDocDocId({ commit }, docDocId) {
      commit('updateDocDocId', docDocId);
    },
    updateActividad({ commit }, actividad) {
      commit('updateActividad', actividad);
    },
  },
});

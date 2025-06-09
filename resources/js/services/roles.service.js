import { Api }  from './api.service';

export default {
    index() {
        try {
            return Api().get(`/roles`);
        } catch (error) {
            console.error('Error al obtener las roles:', error);
            throw error;
        }
    },
    store(datos) {
        try {
            return Api().post('/roles', datos);
        } catch (error) {
            console.error('Error al crear la roles:', error);
            throw error;
        }
    },
    show(id) {
        try {
            return Api().get(`/roles/${id}`);
        } catch (error) {
            console.error('Error al obtener la roles:', error);
            throw error;
        }
    }
};


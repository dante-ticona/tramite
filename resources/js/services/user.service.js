import { Api } from './api.service';

export default {
    index(usrId) {
        try {
            return Api().get('/users/'+usrId);
        } catch (error) {
            console.error('Error al obtener usuario:', error);
            throw error;
        }
    }
};



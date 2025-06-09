import { Api } from './api.service';

export default {
    index(usrId){
        try {
            return Api().get('/usrnodosXId/'+usrId);
        } catch (error) {
            console.error('Error al obtener los nodos:', error);
        }
    }
}


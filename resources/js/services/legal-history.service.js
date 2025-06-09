import axios from 'axios';

class LegalHistoryService {
    async getLegalHistory(casId){
        const data = {
            cas_id: casId
        };
        const response = await axios.post('/api/getCasosLegalTramite', data);
        return response.data.data;
    }
}
export default LegalHistoryService;
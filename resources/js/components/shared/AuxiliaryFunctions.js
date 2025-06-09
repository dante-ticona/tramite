import axios from "axios";
//complement-method: encryptId
//centralized
export function decryptId(encryptId){
    const decoded = atob(decodeURIComponent(encryptId));
    // console.log("============> decrypt-id-caso:"+ decoded);
    return decoded;
}
//complement-method: decryptId
//centralized
export function encryptId(id) {
    let idString = String(id);
    let encryptedId = encodeURIComponent( btoa(idString));
    // console.log("+++++++++++++++++++++++> encript-id-caso"+encryptedId);
    return encryptedId;
}

export function clearLockerLayout(){
    try{
        const fadeLayoutMany = document.querySelectorAll('div.modal-backdrop.fade.show');//modal-backdrop fade
        fadeLayoutMany.forEach(el => {
            el.classList.remove('show','modal-backdrop','fade');
        });
    }catch(e){}
}

export function  createEnumDynamically(objKeyValues) {
    const enumObj = {};
    for (const val of objKeyValues) {
      enumObj[val.key] = val.value;
    }
    return Object.freeze(enumObj); // makes the object immutable
  }


// export async function getDataConfigurationAboutActiveCase(cas_act_id, actOrden){
export async function getDataConfigurationAboutActiveCase(cas_id){
    const responseTipoCasoActivo =  await axios.post('/api/determinamosElTipoCaso', {
        casId: cas_id
    });
    if(responseTipoCasoActivo.data && 
        responseTipoCasoActivo.data.data && 
        (responseTipoCasoActivo.data.data.length === 0 || responseTipoCasoActivo.data.data.length > 1)){
        throw new Error("No existe configuracion coherente asociada al caso.");
    }
    return responseTipoCasoActivo.data.data[0];
}
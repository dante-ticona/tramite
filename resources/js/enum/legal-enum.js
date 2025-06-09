import axios from "axios";

// src/enums/Status.js
export const LegalActividadesFirmaEnum = Object.freeze({
    LEGAL_ACT_ORDEN_32: 32,
    LEGAL_ACT_ORDEN_40: 40
  });
  //this object LegalActivitiesConfigService will be overwrite (from backend) if the flow LEGAL is active.
export const LegalActivitiesConfigService = {
  // _data is like [
  //   { act_orden: 32, act_id: -100 },
  //   { act_orden: 40, act_id: -100 },
  // ]
  _data: null,
  get data() {
    return this._data;
  },
  set data(newArray) {
    this._data = newArray;
  },
  async loadData() {
    const response = await axios.post('/api/fetchConfigDataFromAnyCodProcess', 
      { "codigoProceso": "LEGAL" });
    // assume your API returns an array in response.data
    if(response.data && 
      response.data.data ){
        this.data = response.data.data;
        // throw new Error("No existe actividades asociadas a este proceso.");
    }
  },
};
//1) this object LegalConfigCasoActivo show information relevant about the current process case.
//2) this object will be overwrite (from backend) if the flow LEGAL is active.
export const LegalConfigCasoActivo = {
  _data: {
    prc_id: -100,
    prc_codigo: "LEGAL",
    act_id: -100,
    act_orden: -100,
  },
  get data() {
    return this._data;
  },
  set data(newObject) {
    this._data = newObject;
  },
};


  
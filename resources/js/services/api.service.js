import axios from "axios"

let local = "dev";

let urlBase = ""

if(local == 'dev'){
    urlBase = "http://localhost:8000"
}if(local == 'prod'){
    urlBase = "https://gestora.bo"
}if(local == 'test'){
    urlBase = "http://127.0.0.1:3000"
}

export const urlAsset = urlBase;

let urlBackend = `${urlBase}/api`;

export function Api(){
    // let token = null;

    // Suponiendo que ya tienes el token almacenado en el local storage
    // const token = localStorage.getItem('auth_token');
    let token = localStorage.getItem('auth_token');

    try {
        token = atob(localStorage.getItem("access_token"));

    } catch (error) {
        localStorage.removeItem('access_token');
        token = null
    }

    let api =  axios.create({
        baseURL: urlBackend,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer '+token
        },
    });


    // api.interceptors.response.use(
    //     (response) => {
    //         return response;
    //     },
    //     (error) => {
    //         if(error.response.status === 401) {
    //             localStorage.removeItem("access_token")
    //             window.location.href = "/login"
    //         }

    //         return Promise.reject(error)
    //     }
    // )

    return api;
};


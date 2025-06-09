function estadoDerivacion() {
        var estado = document.getElementById("ESTADO_DERIVACION").value; 
  
        if (estado === "DESISTIDO"){
            volverRequeridoNotaDesistido();

            quitarRequeridoNotaAceptado();
            quitarRequeridoNotaRechazo();
        } else if (estado === "ACEPTADO"){
            volverRequeridoNotaAceptado();

            quitarRequeridoNotaDesistido();
            quitarRequeridoNotaRechazo();
        } else if(estado ==="CON NOTA DE RECHAZO"){
            volverRequeridoNotaRechazo();
            
            quitarRequeridoNotaAceptado();
            quitarRequeridoNotaDesistido();
        } else if(estado === "OBSERVADO"){
            quitarRequeridoNotaAceptado();
            quitarRequeridoNotaDesistido();
            quitarRequeridoNotaRechazo();
        }
    }

    function volverRequeridoNotaDesistido(){
        document.getElementById("NOTA_DESISTIDO").setAttribute("required", "required");
        const nota_desistido = document.getElementById('NOTA_DESISTIDO_idd');
        const label = nota_desistido.querySelector('label');
        label.style.display = "block";
        document.getElementById("NOTA_DESISTIDO_ID").setAttribute("required", "required");

        if (label && !label.textContent.includes(' (*)')) {
            label.textContent += ' (*)';
        }
    }

    function quitarRequeridoNotaDesistido(){
        const nota_desistido = document.getElementById('NOTA_DESISTIDO_idd');
        document.getElementById("NOTA_DESISTIDO").removeAttribute("required");
        const label = nota_desistido.querySelector('label');
        if (label) {
            label.textContent = label.textContent.replace(' (*)', '');
        }  
        document.getElementById("NOTA_DESISTIDO_ID").removeAttribute("required");
    }

    function volverRequeridoNotaAceptado(){
        document.getElementById("CONTRATO_FIRMADO").setAttribute("required", "required");
        const nota_aceptado = document.getElementById('CONTRATO_FIRMADO_idd');
        const label = nota_aceptado.querySelector('label');
        label.style.display = "block";
        document.getElementById("CONTRATO_FIRMADO_ID").setAttribute("required", "required");

        if (label && !label.textContent.includes(' (*)')) {
            label.textContent += ' (*)';
        }
    }

    function quitarRequeridoNotaAceptado(){
        const nota_aceptado = document.getElementById('CONTRATO_FIRMADO_idd');
        document.getElementById("CONTRATO_FIRMADO").removeAttribute("required");
        const label = nota_aceptado.querySelector('label');
        if (label) {
            label.textContent = label.textContent.replace(' (*)', '');
        }  
        document.getElementById("CONTRATO_FIRMADO_ID").removeAttribute("required");
    }

    function volverRequeridoNotaRechazo(){
        document.getElementById("NOTA_RECHAZO_FIRMADO").setAttribute("required", "required");
        const nota_rechazo = document.getElementById('NOTA_RECHAZO_FIRMADO_idd');
        const label = nota_rechazo.querySelector('label');
        label.style.display = "block";
        document.getElementById("NOTA_RECHAZO_FIRMADO_ID").setAttribute("required", "required");

        if (label && !label.textContent.includes(' (*)')) {
            label.textContent += ' (*)';
        }
    }

    function quitarRequeridoNotaRechazo(){
        const nota_rechazo = document.getElementById('NOTA_RECHAZO_FIRMADO_idd');
        document.getElementById("NOTA_RECHAZO_FIRMADO").removeAttribute("required");
        const label = nota_rechazo.querySelector('label');
        if (label) {
            label.textContent = label.textContent.replace(' (*)', '');
        }  
        document.getElementById("NOTA_RECHAZO_FIRMADO_ID").removeAttribute("required");
    }
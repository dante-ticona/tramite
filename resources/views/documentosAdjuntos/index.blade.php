<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0055)https://www.sigplusweb.com/sigwebtablet_autokeydemo.htm -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
  <link href="../assetsBackend/dist/css/bootstrap.min.css" rel="stylesheet">

	<style type="text/css">
	

		.btn_respaldos_plus {
			height: 50px;
			line-height: 45px;
			width: 50px;
			font-size: 2em;
			font-weight: bold;
			border-radius: 50%;
			background-color: #4CAF50;
			color: white;
			text-align: center;
			cursor: pointer;

		}

		.btn_respaldos_minus {
			height: 50px;
			line-height: 45px;
			width: 50px;
			font-size: 2em;
			font-weight: bold;
			border-radius: 50%;
			background-color: #ff3333;
			color: white;
			text-align: center;
			cursor: pointer;
		}

		/* firma */
		/* The Modal (background) */
		.modal {
			display: none;
			/* Hidden by default */
			position: fixed;
			/* Stay in place */
			z-index: 1;
			/* Sit on top */
			padding-top: 100px;
			/* Location of the box */
			left: 0;
			top: 0;
			width: 100%;
			/* Full width */
			height: 100%;
			/* Full height */
			overflow: auto;
			/* Enable scroll if needed */
			background-color: rgb(0, 0, 0);
			/* Fallback color */
			background-color: rgba(0, 0, 0, 0.4);
			/* Black w/ opacity */

		}


		/* Modal Content */
		.modal-content {
			background-color: #fefefe;
			margin: auto;
			padding: 20px;
			border: 1px solid #888;
			width: 30%;
			font: 12px sans-serif;
		}

		.modal-content-verificador {
			background-color: #fefefe;
			margin: auto;
			padding: 20px;
			border: 1px solid #888;
			width: 30%;
			font: 12px sans-serif;
			max-height: calc(100vh - 210px);
			overflow-y: auto;
		}

		/* The Close Button */
		.close {
			color: #aaaaaa;
			float: right;
			font-size: 28px;
			font-weight: bold;
		}

		.close:hover,
		.close:focus {
			color: #000;
			text-decoration: none;
			cursor: pointer;
		}

		/* Visor pdf */
		.modalVisorPdf {
			background-color: rgba(0, 0, 0, .8);
			display: none;
			/* Hidden by default */
			position: fixed;
			/* Stay in place */
			z-index: 2;
			/* Sit on top */
			/*padding-top: 100px; /* Location of the box */
			left: 0;
			top: 0;
			width: 100%;
			/* Full width */
			height: 100%;
			/* Full height */
			overflow: auto;
			/* Enable scroll if needed */
			background-color: rgb(0, 0, 0);
			/* Fallback color */
			background-color: rgba(0, 0, 0, 0.4);
			/* Black w/ opacity */
		}

		/* The Close Button */
		.closeVisorPdf {
			color: #aaaaaa;
			float: right;
			font-size: 28px;
			font-weight: bold;
		}

		.closeVisorPdf:hover,
		.closeVisorPdf:focus {
			color: #000;
			text-decoration: none;
			cursor: pointer;
		}

		.centerVisorButton {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 50px;
			border: 3px solid blue;
		}

		.modal-contenidoVisorPdf {
			background-color: #ffffff;
			width: 1000px;
			height: auto;
			padding: 20px 20px;
			margin: 20% auto;
			position: relative;
		}

		.modalOpcione {
			display: none;
			/* Hidden by default */
			position: fixed;
			/* Stay in place */
			z-index: 1;
			/* Sit on top */
			padding-top: 100px;
			/* Location of the box */
			left: 0;
			top: 0;
			width: 100%;
			/* Full width */
			height: 100%;
			/* Full height */
			overflow: auto;
			/* Enable scroll if needed */
			background-color: rgb(0, 0, 0);
			/* Fallback color */
			background-color: rgba(0, 0, 0, 0.4);
			/* Black w/ opacity */

		}


		/* Modal Content */
		.modal-contentOpcione {
			background-color: #fefefe;
			margin: auto;
			padding: 20px;
			border: 1px solid #888;
			width: 42%;
			font: 12px sans-serif;
		}

		.closeOpcione {
			color: #aaaaaa;
			float: right;
			font-size: 28px;
			font-weight: bold;
		}

		.closeOpcione:hover,
		.closeOpcione:focus {
			color: #000;
			text-decoration: none;
			cursor: pointer;
		}

		.grey {
			background-color: rgba(128, 128, 128, .25);
		}

		.red {
			background-color: rgba(255, 0, 0, .25);
		}

		.blue {
			background-color: rgba(0, 0, 255, .25);
		}


		.buttonFirmar {
			background-color: #008CBA;
			border: none;
			color: white;
			padding: 10px 35px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
		}


		.buttonverDocumento {
			background-color: #af8b08;
			border: none;
			color: white;
			padding: 10px 35px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
		}


		.buttonFirmarUpload {
			background-color: #d96c00;
			border: none;
			color: white;
			padding: 10px 35px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
		}

		/* estilo select*/
		.box {}

		.box select {
			background-color: #0563af;
			color: white;

			width: 250px;
			border: none;
			font-size: 15px;
			box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
			-webkit-appearance: button;
			appearance: button;
			outline: none;
		}

		.box::before {

			font-family: FontAwesome;
			position: absolute;
			top: 0;
			right: 0;
			width: 20%;
			height: 100%;
			text-align: center;
			font-size: 28px;
			line-height: 45px;
			color: rgba(255, 255, 255, 0.5);
			background-color: rgba(255, 255, 255, 0.1);
			pointer-events: none;
		}

		.box:hover::before {
			color: rgba(255, 255, 255, 0.6);
			background-color: rgba(255, 255, 255, 0.2);
		}

		.textoFrmtoken {
			margin: 8px 0;
			display: inline-block;
			border: 1px solid #ccc;
			border-radius: 4px;
			box-sizing: border-box;
		}

		.btnFrmtoken {
			background-color: #4CAF50;
			border: none;
			color: white;
			padding: 5px 20px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			cursor: pointer;
		}

		/* subir archivo*/
		.inputfile-box {
			position: relative;
		}

		.inputfile {
			display: none;
		}

		.container {
			display: inline-block;
			width: 100%;
		}

		.file-box {
			display: inline-block;
			width: 80%;
			border: 1px solid #ccc;
			padding: 5px 0px 5px 5px;
			box-sizing: border-box;
			border-radius: 4px;
			height: calc(2rem - 2px);
		}

		.file-button {
			background: #0563af;
			color: white;
			padding: 5px;
			position: absolute;
			border: 1px solid;
			top: 0px;
			right: 0px;
		}

		/* loader */
		/* (A) FULL SCREEN WRAPPER */
		#spinner {
			position: fixed;
			top: 0;
			left: 0;
			z-index: 9999;
			width: 100vw;
			height: 100vh;
			background: rgba(0, 0, 0, 0.7);
			transition: opacity 0.2s;
		}

		/* (B) CENTER LOADING SPINNER */
		#spinner img {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%);
		}

		/* (C) SHOW & HIDE */
		#spinner {
			visibility: hidden;
			opacity: 0;
		}

		#spinner.show {
			visibility: visible;
			opacity: 1;
		}

		.cuadroAA {
			font: 12px sans-serif;
			background: #fff;
			overflow: auto;
			/*height: 390px;
			width : 300px;TST*/
			height: 200px;
			width: 265px;
			border-left: 1px solid silver;
			border-top: 1px solid silver;
			border-bottom: 1px solid silver;
			border-right: 1px solid silver;
		}

		.cuadroBA {
			font: 12px sans-serif;
			background: #fff;
			overflow: auto;
			height: 200px;
			width: 265px;
			border-left: 1px solid silver;
			border-top: 1px solid silver;
			border-bottom: 1px solid silver;
			border-right: 1px solid silver;
		}

               .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
            font-size: 3.5rem;
            }
        }
        #global {
            height: 300px;
            width: 120%;
            border: 1px solid #ddd;
            background: #f1f1f1;
            overflow-y: scroll;
        }
        #mensajes {
            height: auto;
        }
        
	</style>


  <script type="text/javascript" src="http://desa-flujovirtual.gestora.bo:8000/js/SigWebTablet.js.descarga"></script>

     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function () {

		adicionar();
	
	});
</script>
  <script type="text/javascript">
		function adicionar() {
			var i = 0;
			$('#adicionar').click(function () {
				i++;
				c = "borrarCampoJq('RespaldoFile_" + i + "')";
				$('#tabla_dinamica').append('<tr id="row_btn_' + i + '">' +
					'<td><input name="RespaldoFile[]" id="RespaldoFile_' + i + '" type="file"  onchange="validarExtensiones(this);" /><span onclick=' + c + ' ><font face="Tahoma" size="2" color="red" style="background-color: #f5e9d3;cursor: pointer;"> Cancelar</font></span> </td>' +
					'<td><textarea name="RespaldoDesc[]" id="RespaldoDesc_' + i + '" rows="1" cols="50"></textarea></td>' +
					'<td><button type="button" name="remove" id="btn_' + i + '" onclick="removeCampo(this);" class="btn_respaldos_minus" >-</button></td></tr>');
			});
		}

        
		function removeCampo(val) {
			var button_id = val.id;
			var myobj = document.getElementById("row_" + button_id);
			myobj.remove();
		}
		function guardar(val) {
			console.log('guardar');



		}



function seleccionarArchivo(numero) {
	const tabla = document.getElementById("tabla_dinamica");
	const filas = tabla.querySelectorAll("tr");
	console.log('filas',filas);
	const tam = filas.length ;
	for (var i = 0; i < tam; i++) { 
		var id_respaldo = 'RespaldoFile_'+i;
		var id_referencia = 'RespaldoDesc_'+i;
		const referencia = document.getElementById(id_referencia).value;
		const fileInput = document.getElementById(id_respaldo);
		cargarArchivo( fileInput.files[0], i,referencia);
	}

}


function cargarArchivo(archivo, numero,referencia) {
        // Crear un objeto FormData para enviar el archivo
    if (archivo) {
        // Crear un objeto FormData para enviar el archivo
        var formData = new FormData();

		
        formData.append('archivo', archivo );
   formData.append('numero', numero);
   formData.append('id', {{ $id }});
   formData.append('referencia',referencia);


        // Realizar una solicitud AJAX para guardar el archivo
        $.ajax({
            url: '{{ route("guardar_archivo") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Archivo guardado exitosamente:', response);

                
            },
            error: function(xhr, status, error) {
                console.error('Error al guardar el archivo:', error);
            }
        });
    }
}
</script>

  <link href="../assetsBackend/heroes.css" rel="stylesheet">
</head>
<body>

<main>
            <div class="b-example-divider"></div>
            <div class="bg-dark text-secondary px-4 py-5 text-center">
                <div class="py-5">
                <h1 class="display-5 fw-bold text-white" align ="center">DOCUMENTO ADJUNTOS</h1>                
                </div>
            </div>
            <div class="b-example-divider mb-0" align='right'></div>    <button   class="btn btn-primary btn-lg px-4 me-md-2 fw-bold" onclick="seleccionarArchivo()">guardar documentos</button>
   
	
			<button   class="btn btn-success btn-lg px-4 me-md-2 fw-bold" onclick="window.location.href='/home#/atenderCaso/1084'">Volver</button>



  <table id="tabla_documento" class="table table-striped" width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#000000" style="border-collapse:collapse;border-color:#d7cccc;background-color: #fff;">
    <tr bgcolor="#585858">
      <td width="50%"  class="display-5 fw-bold text-white">
        <b>DOCUMENTO</b>
        
          </td>
          <td width="50%" class="display-5 fw-bold text-white">
     <b>REFERENCIA</b>
           
              </td>
            </tr>
            <tr>
              <div id='msgUploadTipo' style='visibility: hidden;'><img
                src='../img/error.png' />&nbsp;<b>
                  <font color='red'>
                    <blink>El tipo de archivo permitido es:
                      <script>
                        document.write(_validFileExtensions.join(", "));
                      </script>
                    </blink>
                  </font>
                </b></div>
                <div id='msgUploadPeso' style='visibility: hidden;'><img
                  src='../img/error.png' />&nbsp;<b>
                    <font color='red'>
                      <blink>El peso máximo para subir un archivo es: 120MB !!!</blink>
                    </font>
                  </b></div>
                  <div id='msgUploadRequerido' style='visibility: hidden;'><img
                    src='../img/error.png' />&nbsp;<b>
                      <font color='red'>
                        <blink>Se requiere que suba el archivo !!!</blink>
                      </font>
                    </b></div>
                  </tr>
                </table>
                <table id="tabla_dinamica" width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#000000" style="border-collapse:collapse;border-color:#d7cccc;background-color: #fff;">
                <tr align='left'>
                  <td width="50%"><input name="RespaldoFile[]" id="RespaldoFile_0" type="file" onchange="validarExtensiones(this);" /><span onclick="borrarCampoJq('RespaldoFile_0')">
                    <font face='Tahoma' size='2' color='red' style='background-color: #f5e9d3;cursor: pointer;'> Cancelar</font>
                  </span></td>
                  <td width="40%"><textarea name="RespaldoDesc[]" id="RespaldoDesc_0" rows="1"
                    cols="50"></textarea> </td>
                    <td width="10%"><button type="button" name="adicionar" id="adicionar"
                      class="btn_respaldos_plus">+</button></td>
                    </tr>
                  </table>

    </main>
 </body></html>
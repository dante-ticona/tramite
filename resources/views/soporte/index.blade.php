<!doctype html>
<html lang="en">
    <head>
        <!-- Bootstrap core CSS -->
        <link href="../assetsBackend/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <style>
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
        <!-- Custom styles for this template -->
        <link href="../assetsBackend/heroes.css" rel="stylesheet">
    </head>
    <body>
        <main>
            <div class="container my-5">
                <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg">
                    <div class="col-lg-7 p-3 p-lg-5 pt-lg-3">
                        <h1 class="display-4 fw-bold lh-1">Consulta de Datos</h1>
                        <p></p>
                        <form action="contact_post" method="POST" class="row g-3">
                            @csrf                            
                            <label>Sentencia</label>
                            <textarea name="sentencia" id="sentencia"></textarea>
                            <br>                                                        
                            <div class="col-auto">
                                <button type="submit"  class="btn btn-primary btn-lg px-4 me-md-2 fw-bold">Ejecutar</button>
                                <button type="button" class="btn btn-outline-secondary btn-lg px-4">Limpiar Sentencia</button>
                            </div>
                        </form>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4 mb-lg-3">
                        </div>
                    </div>
                    <div class="col-lg-4 offset-lg-1 p-0 overflow-hidden shadow-lg">
                         <img id="image-container" src="" alt="Imagen"  alt="" width="420" height="320"  >
                    </div>
                </div>
            </div>
            <div class="b-example-divider"></div>
            <div class="container col-xxl-8 px-4 py-5">
                <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-lg-12">
                    <h1 class="display-5 fw-bold lh-1 mb-3">Respuesta Sentencia</h1>                    
                    <div id="global">
                        <div id="mensajes">            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-start" id="nuevo">                        
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <code>
                        <pre>
                            {{$data}}
                        </pre>
                    </code>
                </div>
                </div>
            </div>
            <div class="b-example-divider"></div>
            <div class="bg-dark text-secondary px-4 py-5 text-center">
                <div class="py-5">
                <h1 class="display-5 fw-bold text-white">Sistema de Tramites Backend</h1>                
                </div>
            </div>
            <div class="b-example-divider mb-0"></div>
        </main>
        <script type="text/javascript">
            const user = {!! json_encode($respuestaDB) !!};
            const cabecera = {!! json_encode($cabecera) !!};
            var respuestasql = JSON.parse(user);
            if (respuestasql.length > 0) {
                var   html = '<table class="table table-striped">'+
                                '<thead>'+
                                '<tr>'+
                                '<th scope="col">#</th>';
                        cabecera.forEach(atributo => {
                            html +=  '<th scope="col">'+atributo+'</th>';
                        });
                        html += '</tr>'+
                                    '</thead>';
                        html += ' <tbody>';
                        var pos = 1; 
                        respuestasql.forEach(dataIni => {
                            html += '<tr><th scope="row">'+pos+'</th>';
                            dataIni.forEach(valor => {
                                html += '<td>'+valor+'</td>';
                            });
                            pos += 1;
                            html += '</tr>';
                        });

                        html += ' </tbody></table>';

                document.getElementById("nuevo").innerHTML = html;
            }else{
                document.getElementById("nuevo").innerHTML = "<p>Se recupero Información vacía, ó no se detecto una sentencia correcta.</p>";

            }
        </script>
<script>
        $(document).ready(function() {
    function changeImage() {
        $.get('/get-images', function(response) {
            var images = response.images;
            var randomImage = images[Math.floor(Math.random() * images.length)];
            $('#image-container').attr('src', randomImage);
        });
    }
    setInterval(changeImage, 9000);
    changeImage();
});
</script>
        <script src="../assetsBackend/dist/js/bootstrap.bundle.min.js"></script> 
    </body>
</html>

<template>
    <!-- Modal informativo Departamentos -->
    <div class="modal fade" id="alertaDepartamentosModalComponent" tabindex="-1" role="dialog" aria-labelledby="alertaDepartamentosModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertaDepartamentosModalTitle"> <i class="fas fa-lightbulb"></i> Informativo Departamentos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Aquí puede visualizar la información cantidad de tramites registrado por departamentos.<br><hr>
                    Los datos son totales filtrados por <strong>MES</strong> <em><strong> Por defecto, puede actualizar estos datos con los filtros </strong></em><i class="fas fa-filter"></i>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'InfoDeps'
    };
</script>

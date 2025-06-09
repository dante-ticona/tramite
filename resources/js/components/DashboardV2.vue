<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <h3 class="profile-username text-center"> <strong> GENERACIÓN DE INDICADORES DE ALERTA TEMPRANA Y CONTROL DE AVANCE </strong> </h3>
                        <div class="direct-chat-msg">
                            <div class="d-flex align-items-center">
                                <img src="img/animacion-chat-bot-gestora3.gif" alt="TramiteSIP" class="mr-1">
                                <div class="direct-chat-text ml-1">
                                    Hola Bienvenido (a) <strong> {{ this.usrUser }} </strong>
                                </div>
                            </div>

                            <div class="text-center">
                                <span class="badge badge-primary" style="font-size: 1.1rem;">
                                    Mes actual: <strong>{{ mesActual.toUpperCase() }}</strong>
                                </span>
                            </div>

                            <div class="text-center">
                                <span class="badge badge-success">
                                    <i class="fas fa-calendar-alt" style="font-size: 1.1em;"></i>
                                    <span style="font-size: 1.1em;">
                                        {{ FechaHora.split(',')[0] }}
                                    </span>
                                </span>
                                <span class="badge badge-success">
                                    <i class="fas fa-clock" style="font-size: 1.1em;"></i>
                                    <span style="font-size: 1.1em;">
                                        {{ FechaHora.split(',')[1] }}
                                    </span>
                                </span>
                            </div>
                            <br>

                            <marquee behavior="scroll" direction="left">
                                <span class="badge badge-info" style="font-size: 1.2em; color:white;">
                                    La Gestora Pública trabaja con equidad y responsabilidad, por una jubilación digna y segura.
                                </span>
                            </marquee>

                            <br> <br>

                            <div class="callout callout-warning">
                                <h4 class="card-title" style="font-size: 0.75rem">
                                    <i class="fas fa-bullhorn"></i>
                                    Uso de Botones
                                </h4>

                                <div class="card-body d-flex justify-content-center align-items-center">
                                    <button type="button" @click="empezarTutorial" class="btn btn-primary mb-3 mr-2">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </div>

                                <div class="d-flex justify-content-center align-items-center">
                                    <button id="filtro" type="button" class="btn btn-primary mb-3 mr-2" disabled>
                                        <i class="fas fa-filter"></i>
                                    </button>

                                    <button id="limpiarFiltros" type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" disabled>
                                        <i class="fas fa-broom"></i>
                                    </button>

                                    <button id="informativoBotton" type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn" style="width: 40px; height: 40px;" disabled>
                                        <i class="fas fa-lightbulb"></i>
                                    </button>

                                    <button id="actualizar" type="button" class="btn btn-success btn-sm mb-3 rounded-circle ml-2" style="width: 40px; height: 40px;" disabled>
                                        <i class="fas fa-retweet"></i>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <strong>TOTAL DE REGISTROS DE TRAMITE POR DEPARTAMENTOS</strong> <br>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex justify-content-start">
                                <!-- Boton Modal Departamento -->
                                <button type="button" class="btn btn-primary mb-3 mr-2" data-toggle="modal" data-target="#filterDeptos">
                                    <i class="fas fa-filter"></i>
                                </button>

                                <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="submitFormDep(3)" title="Limpiar Filtros Departamentos">
                                    <i class="fas fa-broom"></i>
                                </button>
                            </div>

                            <div class="d-flex justify-content-start">
                                <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn" style="width: 40px; height: 40px;" data-toggle="modal" data-target="#alertaDepartamentosModalComponent" title="Informativo">
                                    <i class="fas fa-lightbulb"></i>
                                </button>

                                <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle ml-2" style="width: 40px; height: 40px;" @click="submitFormDep(3)" title="Actualizar">
                                    <i class="fas fa-retweet"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Departamento -->
                        <div class="modal fade" id="filterDeptos" tabindex="-1" role="dialog" aria-labelledby="filterDeptosLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 42%">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="filterDeptosLabel">
                                        <i class="fas fa-filter"></i> Filtros Departamentos
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="d-flex justify-content-center">

                                                    <button type="submit" class="btn btn-success mr-2" data-toggle="modal" data-target="#dateModalDep">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i> Fecha
                                                    </button>
                                                    <!-- Fecha Hoy -->
                                                    <div class="modal fade" id="dateModalDep" tabindex="-1" role="dialog" aria-labelledby="dateModalDepLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="dateModalDepLabel">Seleccionar Fecha</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <loader-graficos v-if="isLoading"></loader-graficos>
                                                                    <form @submit.prevent="submitFormDep(1)">
                                                                        <div class="form-group">
                                                                            <label for="filtroFechaHoyDep">Fecha</label>
                                                                            <input type="date" v-model="filtroFechaHoyDep" class="form-control" id="filtroFechaHoyDep" :max="new Date().toISOString().split('T')[0]">
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                                                            Aplicar Fecha
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-info" data-toggle="modal" data-target="#dateRangosDep">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i> Rangos Fechas
                                                    </button>

                                                    <div class="modal fade" id="dateRangosDep" tabindex="-1" role="dialog" aria-labelledby="dateRangosDep" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="dateRangosDep">Seleccionar Fecha</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <loader-graficos v-if="isLoading"></loader-graficos>
                                                                    <form @submit.prevent="submitFormDep(2)">
                                                                        <div class="form-group">
                                                                            <label for="selectedDate">Fecha Inicial</label>
                                                                            <input type="date" v-model="filtroFechaInicialDep" class="form-control" id="filtroFechaInicialDep" :max="new Date().toISOString().split('T')[0]">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="selectedDate">Fecha Final</label>
                                                                            <input type="date" v-model="filtroFechaFinalDep" class="form-control" id="filtroFechaFinalDep" :max="new Date().toISOString().split('T')[0]">
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">Aplicar Fechas</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <form @submit.prevent="submitFormDepartamentos">
                                                    <div>
                                                        <input type="checkbox" id="selectAll" v-model="selectAll" @change="toggleSelectAll" class="custom-checkbox">
                                                        <label for="selectAll">
                                                            <small style="font-size: 0.8em;"><strong>  --- SELECCIONAR TODO ---</strong> </small>
                                                        </label>
                                                    </div>
                                                    <div v-for="proceso in predefinedProcesos" :key="proceso.codigo">
                                                        <input type="checkbox" :id="proceso.codigo" :value="{ prc_id: proceso.prc_id, codigo: proceso.codigo }" v-model="selectedProcesos" class="custom-checkbox">
                                                        <label :for="proceso.codigo" class="d-inline-block mr-2">
                                                            <small class="truncate-text" style="font-size: 0.8em;" :title="proceso.descripcion">{{ proceso.descripcion }}</small>
                                                            <small class="truncate-text" style="font-size: 0.8em;" :title="proceso.codigo"><strong>({{ proceso.codigo }})</strong></small>
                                                        </label>
                                                    </div>
                                                    <hr>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-chart-bar" aria-hidden="true"></i> GENERAR
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal informativo Departamentos -->
                        <info-deps> </info-deps>

                        <div v-if="this.filtroFechaHoyDep">
                            <p> <strong> Filtro de Fecha Hoy </strong> </p>
                            <span class="badge badge-success" style="font-size: 1.2em;">
                                <i class="fas fa-calendar"></i> {{ this.filtroFechaHoyDep }}
                            </span>
                        </div>

                        <div v-if="this.filtroFechaInicialDep && this.filtroFechaFinalDep">
                            <p> <strong> Filtro de Rango de Fechas</strong> </p>

                            <span class="badge badge-primary" style="font-size: 1.2em;">
                                <i class="fas fa-calendar"></i>
                                Fecha Inicial <span class="badge badge-success">{{ this.filtroFechaInicialDep }}</span>
                            </span> <br>

                            <span class="badge badge-primary" style="font-size: 1.2em;">
                                <i class="fas fa-calendar"></i>
                                Fecha Final <span class="badge badge-warning">{{ this.filtroFechaFinalDep }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0" id="segundoFiltro">
                        <div class="departments-grid">
                            <router-link
                                :to="{ name: 'departamentoDetalle', params: { departamento: 'LA PAZ' } }"
                                @click="getDepartamentos('LA PAZ')">
                                <div class="department-card">
                                    <div class="product-img">
                                        <img src="data:image/webp;base64,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" alt="Pando" class="img-size-50">
                                    </div>
                                    <button class="product-title btn btn-primary btn-sm" style="color:#ffffff; font-size: 0.7rem; padding: 0.2rem 0.4rem;">LA PAZ</button> <br>
                                    <span class="badge badge-warning">{{ lapaz }}</span>
                                </div>
                            </router-link>

                            <router-link
                                :to="{ name: 'departamentoDetalle', params: { departamento: 'BENI' } }"
                                @click="getDepartamentos('BENI')">
                                <div class="department-card">
                                    <div class="product-img">
                                        <img src="data:image/webp;base64,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" alt="Beni" class="img-size-50">
                                    </div>
                                    <button class="product-title btn btn-primary btn-sm" style="color:#ffffff; font-size: 0.7rem; padding: 0.2rem 0.4rem;">BENI</button> <br>
                                    <span class="badge badge-warning">{{ beni }}</span>
                                </div>
                            </router-link>

                            <router-link
                                :to="{ name: 'departamentoDetalle', params: { departamento: 'PANDO' } }"
                                @click="getDepartamentos('PANDO')">
                                <div class="department-card">
                                    <div class="product-img">
                                        <img src="data:image/webp;base64,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" alt="Pando" class="img-size-50">
                                    </div>
                                    <button class="product-title btn btn-primary btn-sm" style="color:#ffffff; font-size: 0.7rem; padding: 0.2rem 0.4rem;">PANDO</button> <br>
                                    <span class="badge badge-warning">{{ pando }}</span>
                                </div>
                            </router-link>

                            <router-link
                                :to="{ name: 'departamentoDetalle', params: { departamento: 'ORURO' } }"
                                @click="getDepartamentos('ORURO')">
                                <div class="department-card">
                                    <div class="product-img">
                                        <img src="data:image/webp;base64,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" alt="oruro" class="img-size-50">
                                    </div>
                                    <button class="product-title btn btn-primary btn-sm" style="color:#ffffff; font-size: 0.7rem; padding: 0.2rem 0.4rem;">ORURO</button> <br>
                                    <span class="badge badge-warning">{{ oruro }}</span>
                                </div>
                            </router-link>

                            <router-link
                                :to="{ name: 'departamentoDetalle', params: { departamento: 'COCHABAMBA' } }"
                                @click="getDepartamentos('COCHABAMBA')">
                                <div class="department-card">
                                    <div class="product-img">
                                        <img src="data:image/webp;base64,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"
                                        alt="Cochabamba" class="img-size-50">
                                    </div>
                                    <button class="product-title btn btn-primary btn-sm" style="color:#ffffff; font-size: 0.7rem; padding: 0.2rem 0.4rem;">COCHABAMBA</button> <br>
                                    <span class="badge badge-warning">{{ cochabamba }}</span>
                                </div>
                            </router-link>

                            <router-link
                                :to="{ name: 'departamentoDetalle', params: { departamento: 'SANTA CRUZ' } }"
                                @click="getDepartamentos('SANTA CRUZ')">
                                <div class="department-card">
                                    <div class="product-img">
                                        <img src="data:image/webp;base64,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" alt="Santa Cruz" class="img-size-50">
                                    </div>
                                    <button class="product-title btn btn-primary btn-sm" style="color:#ffffff; font-size: 0.7rem; padding: 0.2rem 0.4rem;">SANTA CRUZ</button> <br>
                                    <span class="badge badge-warning">{{ santaCruz }}</span>
                                </div>
                            </router-link>

                            <router-link
                                :to="{ name: 'departamentoDetalle', params: { departamento: 'POTOSI' } }"
                                @click="getDepartamentos('POTOSI')">
                                <div class="department-card">
                                    <div class="product-img">
                                        <img src="data:image/webp;base64,UklGRq4GAABXRUJQVlA4WAoAAAAQAAAAxwAAxwAAQUxQSE0DAAABL6CmbQM2VSy9C2BGRIDyDrrWtteJq88HeiXBvfcM7kXAvYFRz1j67v9iApL9/7/2qYro/wTg//rjR2PfmRzMcySDeStJ9sZ5/hkasx7Xy+WSXjB8GnVmYW/SytLQWJSKeLSIGzZVmKrA1p60xWDPY4tgD/BXKmJvEJ5loTEolvFgz52lgQzmeBY3nuytWYtm4PyYrYlFAQDGxjgeYXAqY1uFzqC4QW/QWoWlCm6Dg0H4LhssQqyCiyW9SXCPghaXziDAxazmRpuAlBMYYPSaQ05WLXkHq3wVxrzZKuQFs2IVnlmDWT6rMwtZrV1rFZAyOsN8Rm8Y4rtg2fKOR8N8RjDsO4OtWSNzO4vG35fLg9mDQWeWB3s8t7yakzbhyZgbNz6Z4rj5/PH762RF3O5lb8OdO08m3Ll3sODO/Q24swKR9rlIkY0uFymzVTUmCh0E+HDaakyUGgR8k6HdxCfK7fZLJPm5gafkYTfPl6EtOVP0tFt8RZ7yzpTdy2FoM26UHbC7zyBPb+4U3u+HNYdz+8edwmcIdCmHPAEPSm8l4JnHkCi9g8ixQP4vCNV0vbQw7xfkOj0DBI96WkmLmhmSn2omSS6qGeScH9TbiXGJiiF3URSqMAvy/xigKEiKeliHtgqdHE/FvZyo6VCFQc6qaZLjNc1yEKvgYg2AqGYShahlkDVq6WRBSyPLKQmQvSiZhEUlvTAq7WQ5LY0sryTgn8JchUmYY6oA4FQM4rBqOMhbNPTyXBUQFXQKFgWtAlcFrPIaDYs8qJTXqFirsIhrVUBcp2OtwlNaX4VBx7e0oOMpjV0VQlMDzlXgsQqhCmyqAPmLgqDAK5gUOAW9AkR5jYZFXIBGv1somlS43QZvAXbr4S1Ie3WAzws64l4tgJjF1qIGwJJ3sAh/3rNmg8ILpBy29syvfFav4VsGYs6kYdxpeONzggas+/RvEDPYaPD7tO/8q6+v6ydUpj1mZP788QOanztcmhztfrPQwtBxq2sD844w1m0Dc9MWcxWmKhyq0NvDKqQtOnu+q7BscIK9Y1kHi4uOMDkWnGDzWtBWATUIVZitinlHq1JeU4MBVjO7tcplBdRgMgtrRujsGt9cf8Ly29fX1+Wjwf+UAQBWUDggOgMAADAbAJ0BKsgAyAA+kUihSqWko6GnmAiAsBIJY27hacsAPwA/ABPIyIyG1Vdeubd8ivd/YPUJthfMZ54fpn/zu+ZegB0sn+VydXwB/APwA+v3v8FoJSacH/FLoO5cwL2J1MGjTg/Y3y6CFM6cwA9uXMZ6epghEOOOG/+c4h/pItNVD67lm8UOOk7+0st251EUNtbzwklrLu/Nfd35asUuclEI4OhWQsPKi/YzvbbUj67xDrFJczU1zxxKnCYePiN9sZy0YwueHKtIlrzhmuJmHTeKHbhNq6Zfzdy5jQIvcUNDDmgA/vfxe/y7x1CkHkWXUmJev0OBhjg8uci++L1QTYzZ8opECI2s/wXjEQuo7G9o/x/9u//+JwhJThrk++C3gAR297xbINcvbVOKj/L330BvLuOFnKTBiKvrywxSCjjj1isF4CzSAmwcuos801wCBO6+IkR3arZ1+C9fr4v3/G2MLa96Qa7cMyr+nhErHkoL/2ZbAcExBQqEeYG5svSANBHX4ABowvGP2YFSdmef+KUuB0jSaHp2iWqSBJtK0rHDp3GC/1FyDnd6t8ZJLYLkXfkRpWnT4Hmrmt+kS7BQN9xOuP50VVC7/HpZTeBwh5OU/QPFqcoMIE8WvWMNQ3aAFOg6bA/HW3PkT+9qY0feAwm6In9GXw2bxrL4ZGr+uY5kOeK+bd/wbMyXQQvYi9IPA7n8v+Z2c25EgmfmxSsR+q568kKGrd5nTOiW6rES3KicpBhuOywnXBVF3fOUR+sUnEN67wGwqAGf3h8o0z+fGpE316Mf9psDFIRiFKer1/KMp5BWWaromCkeKDnA0FIohp6nkbdV0SnTA2ETvYZSdqRJMPslpD21rAeT/I94RqFJus+RUGfqKgvO6mwpW7GMNVX5SO571TbzydT8o05OxKovPEjI7tVhXbCoWngM5syGJt4tw1dA+k70ho+LL+5RwtmLThN4esU00MlKU7BDEpUY2Q8F/TQ14aAZ4yiHOJY5+I4A+ZUNiT66CgzRDhFHAR5UoX/pjU244jyHX3JIJT88fYvCdlAWsFbH93M9YADMdavu2zwt74tApGuDsbOyY/9HAK+X8vwAAAA=" alt="potosi" class="img-size-50">
                                    </div>
                                    <button class="product-title btn btn-primary btn-sm" style="color:#ffffff; font-size: 0.7rem; padding: 0.2rem 0.4rem;">POTOSI</button> <br>
                                    <span class="badge badge-warning">{{ potosi }}</span>
                                </div>
                            </router-link>

                            <router-link
                                :to="{ name: 'departamentoDetalle', params: { departamento: 'CHUQUISACA' } }"
                                @click="getDepartamentos('CHUQUISACA')">
                                <div class="department-card">
                                    <div class="product-img">
                                        <img src="data:image/webp;base64,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" alt="Chuquisaca" class="img-size-50">
                                    </div>
                                    <button class="product-title btn btn-primary btn-sm" style="color:#ffffff; font-size: 0.7rem; padding: 0.2rem 0.4rem;">CHUQUISACA</button> <br>
                                    <span class="badge badge-warning">{{ chuquisaca }}</span>
                                </div>
                            </router-link>

                            <router-link
                                :to="{ name: 'departamentoDetalle', params: { departamento: 'TARIJA' } }"
                                @click="getDepartamentos('TARIJA')">
                                <div class="department-card">
                                    <div class="product-img">
                                        <img src="data:image/webp;base64,UklGRoQFAABXRUJQVlA4WAoAAAAQAAAAxwAAxwAAQUxQSNACAAABN6CobSM2VSh9jpsREaA+O6BrW3vUyPoR4JiqviamJ2JPi7hG4hoVm+l7/3ew+AXiU9jrRvR/AuR///878HBJ6CQLDGgTMm9t4lUA+Lu5h8u9asDe6wEAzNY64D/NBJv4dDd7AvgxVwDY+ww3uL/TFkURXA8Ar8b1AMD6TI6l7bf76+vrxXl+Pp/Pp9+/ft3f3amGGyB1vAFApquw4e9sbnTZ5Ka7aXQPW8JubnKhvhluYFRvm8r9kIoUk6NRdZtK5zBvjbzB/efX3dywKTNTKGBfoG2Na9yUzFYaX5s4sGUbAFojItWmmrnDCsD39/f3psq5h1U2n8+9McuiYOauxKzMd8TaKNSKntguCpliIGYUIzGJQauZeNVRKDXgnUchVRTEEkXFy0oMWs2BVx2FXRQyzQMvE4UkBla0b7SaKJSqK608Cpmqp2VUAy1RT6xaVQHWteqB1k7V0cpUA61UU4B2ojnQsqK90mpUA61SNdHKNRVop5oDL6N54CXailarKmjVKhlY7XRvrDJdxcroZCSVeLxzsuJZcWp8ZKJUe3WUdl4PlDKvglLqJSOjxK8LxV7uk58DgNeXl/PlvPTksOL/EIg1IiLFZI2sOjjqBapASnG+7WXdzpEvIFMYmWv1qyNbYmTy4EiX6MNIgjJLyK9f5+fVSgn04JDFj9NKaVDtciI/n1cxQdVriBSfy1kJtbop1xF5WqwNRiYA2VpynBbah3MEYFYTOY2LmHDkEVaC/FjASsjvTRjS+zVBSRpI5VeGFezklVLqfaxQPvjUnGT0yEn1HobUVWeF9JuuiULJ6qDLWVW6jJXoDK1RldDqNVZoXzU1rwdNyqtQtEJ8msuZ9TM2YfY2UwrzaialJqPDCvc3R0mucqTkZLpJYtAI+xFAGYWc3gAgi0JKr49CB8DQqwAk9GQChH8PG4E3tBGQsYnBWx2DqoyBZFH4JyhWUDggjgIAABAZAJ0BKsgAyAA+kUifSiWkoyGneqiQsBIJZW7hbcDwXRrE/qeTYOzzz9tX5gOgBvBHoAdJP/csm58AfwD8AKb9ERTKk8caYemVJ440w9MqTxxph6NTJ1qHWuNbOWqkO54OQ6+rJo2PXbXJ19KPCr+PsO8cNUbR+2ONMO3rWYeZRq0JSeNRJZs/xBZOaLpUL1SzmTSQ4DJ1RwMkMEyUIFACp3PlOHEhUbtJ3fDBAp+XaaAgsXttmDXO95P0qHTKjGUJRvrlfDRdKhlzSdUMuXoAAP76Dx5SJy/gQ7dpgAAAAZDp2LWq/8SWtNyH8BWuBMcOzVRu9oz/QXsx89VL5Blb0bLp6wn0XHBNsOah9bWzbsgn5ynBXU/nds6UYLjpcPf7gSbKEGIfWjMDiJ3LZ3phW33QD2/StFpP3+VTJ/UrVIZMm/e3hZGBoDa0sWy5LzNlwuldEepIGHocxSOTERQXQDvMlFyQafhA20fRX6Nvtn42yq+f+oW9AASs0WFfMdY37/4N/7dj//+gsB+t++zXkKgc03lV65k1NECfPhnrWUSQHVbS+N77CsV7NV/3D9+4T50CFp8MsBNPX5ghbjUIpljj+4f//54hY576B+9fECgjtrPWZEzB9vf1KAHPHITE+tDZNEjKWF8AAV3k3c1dkBnBu35cAJZbnEv4C/dT2eS+XPGRmjk2MB0wBvl/B637EoLG1vit2QnfrDYB/SGquZNhJGfdmORPGSYVlht95h8myGq1c83SBT+fR5xpLVnz9oq6ZmJX8zf9n1ns8S8HROCt+ZerWJURAACtvhem4ZEr1+YZPNfwRZLJqG8+YiEPpt1yk6z+TeIAEKOqlhBsR9MfNrgB+y6AAAAAAA==" alt="Tarija" class="img-size-50">
                                    </div>
                                    <button class="product-title btn btn-primary btn-sm" style="color:#ffffff; font-size: 0.7rem; padding: 0.2rem 0.4rem;">TARIJA</button> <br>
                                    <span class="badge badge-warning">{{ tarija }}</span>
                                </div>
                            </router-link>

                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="alertaDepartamentosModal" tabindex="-1" role="dialog" aria-labelledby="alertaDepartamentosModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="alertaDepartamentosModalTitle"> <i class="fas fa-lightbulb"></i> Informativo</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Aquí puede actualizar la información de los departamentos.<br>
                                Clasificados por el total registrados por <strong>MEdasdsad</strong>.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-heard card-primary card-outline">
                        <div class="card-body">
                            <div class="card-body table-responsive p-0">
                                <h4 class="card-title"> <strong>ULTIMOS TRÁMITES REGISTRADOS</strong> </h4> <br>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="ultimosRegistros(1)" title="Actualizar">
                                        <i class="fas fa-retweet"></i>
                                    </button>

                                    <button type="button" class="btn btn-primary btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="$router.push('/misCasos')" title="Mis Casos">
                                        <i class="fas fa-folder-open"></i>
                                    </button>
                                </div>
                                <table class="table table-striped table-valign-middle">
                                <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Trámite</th>
                                    <th>Fecha Registro</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(tramite, index) in this.formattedUltimosTramites" :key="tramite.id">
                                        <td>
                                            <span class="badge badge-dark">{{ index + 1 }}</span>
                                        </td>
                                        <td>
                                            <small>
                                                <span class="badge" style="background: linear-gradient(45deg, #EDCD4E, #f2dd9b); font-size: 0.8em;">
                                                    <strong class="break-text"> {{ tramite.cas_cod_id }} </strong>
                                                </span>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar" style="color:#274690"></i> {{ tramite.formattedDate }} <br>
                                                <i class="fas fa-clock" style="color:#274690"></i> {{ tramite.formattedTime }}
                                            </small>
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header card-primary card-outline">
                        <h4 class="card-title"> <strong>DATOS COMPLEMENTARIOS</strong> </h4> <br>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn" style="width: 40px; height: 40px;" data-toggle="modal" data-target="#alertaDepartamentosModal" title="Actualizar">
                                <i class="fas fa-lightbulb"></i>
                            </button>
                            <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="datosComplementarios(1)" title="Actualizar">
                                <i class="fas fa-retweet"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ TotalUsers }}</h3>
                                        <p>Usuarios</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ TotalRegional }}</h3>
                                        <p>Regionales</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-bookmark"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ TotalAgencia }}</h3>
                                        <p>Agencias</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-bars"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="tab-content">
                            <div>
                                <div class="col-md-4 text-right">
                                    <div class="d-flex justify-content-end">
                                        <router-link to="/generacion-graficas">
                                            <button type="button" class="btn btn-outline-info btn-flat pulseBtn mr-2">
                                                <i class="fa fa-chart-bar"></i> <strong>VER MAS GRAFICAS</strong>
                                            </button>
                                        </router-link>
                                        <button type="button" class="btn btn-success btn-sm rounded-circle" style="width: 40px; height: 40px;" @click="datosGenerales(1)" title="Actualizar">
                                            <i class="fas fa-retweet"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-lg-3 col-6">
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3>{{ this.totalTramitesHoy }}</h3>
                                                <p>Total de Tramites Registrados <strong> Hoy </strong> </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                            <a class="small-box-footer">
                                                <strong> REGISTROS HOY </strong>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>{{ this.totalTramitesOccidente }}</h3>
                                                <p>Total <strong>Mes</strong> Trámites <strong>OCCIDENTE</strong></p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                            <a class="small-box-footer">
                                                <strong> OCCIDENTE </strong>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h3>{{ this.totalTramitesOriente }}</h3>
                                                <p>Total <strong>Mes</strong> Trámites <strong>ORIENTE</strong></p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                            <a class="small-box-footer">
                                                <strong> ORIENTE </strong>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3>{{ this.totalTramitesValles }}</h3>
                                                <p>Total <strong>Mes </strong>Trámites <strong>VALLES</strong></p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                            <a class="small-box-footer">
                                                <strong> VALLES </strong>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-primary card-outline">
                    <div class="card-body">

                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">
                                <div class="post">
                                    <h4 class="card-title"> <strong> LISTADO DE TRAMITES </strong></h4> <br>

                                    <!-- Boton Modal -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex justify-content-start">
                                            <button type="button" class="btn btn-primary mb-3 mr-2" data-toggle="modal" data-target="#filterUno">
                                                <i class="fas fa-filter"></i>
                                            </button>

                                            <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="submitForm(3)" title="Limpiar Filtros">
                                                <i class="fas fa-broom"></i>
                                            </button>
                                        </div>

                                        <div class="d-flex justify-content-start">
                                            <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn" style="width: 40px; height: 40px;" data-toggle="modal" data-target="#alertaTramitesModalComponent" title="Informativo">
                                                <i class="fas fa-lightbulb"></i>
                                            </button>

                                            <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle ml-2" style="width: 40px; height: 40px;" @click="submitForm()" title="Actualizar">
                                                <i class="fas fa-retweet"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade" id="filterUno" tabindex="-1" role="dialog" aria-labelledby="filterUnoLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="max-width: 42%">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="filterUnoLabel">
                                                        <i class="fas fa-filter"></i> Filtros de Búsqueda Tramites
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="d-flex justify-content-center">
                                                                <button type="submit" class="btn btn-success mr-2" data-toggle="modal" data-target="#dateModal">
                                                                    <i class="fa fa-calendar" aria-hidden="true"></i> Fecha
                                                                </button>
                                                                <!-- Fecha Hoy -->
                                                                <div class="modal fade" id="dateModal" tabindex="-1" role="dialog" aria-labelledby="dateModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="dateModalLabel">Seleccionar Fecha</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <loader-graficos v-if="isLoading"></loader-graficos>
                                                                                <form @submit.prevent="submitForm(1)">
                                                                                    <div class="form-group">
                                                                                        <label for="filtroFechaHoy">Fecha</label>
                                                                                        <input type="date" v-model="filtroFechaHoy" class="form-control" id="filtroFechaHoy" :max="new Date().toISOString().split('T')[0]">
                                                                                    </div>
                                                                                    <button type="submit" class="btn btn-primary">
                                                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                                                        Aplicar Fecha
                                                                                    </button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <button type="submit" class="btn btn-info" data-toggle="modal" data-target="#dateRangos">
                                                                    <i class="fa fa-calendar" aria-hidden="true"></i> Rangos Fechas
                                                                </button>

                                                                <div class="modal fade" id="dateRangos" tabindex="-1" role="dialog" aria-labelledby="dateRangos" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="dateRangos">Seleccionar Fecha</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <loader-graficos v-if="isLoading"></loader-graficos>

                                                                                <form @submit.prevent="submitForm(2)">
                                                                                    <div class="form-group">
                                                                                        <label for="selectedDate">Fecha Inicial</label>
                                                                                        <input type="date" v-model="filtroFechaInicial" class="form-control" id="filtroFechaInicial" :max="new Date().toISOString().split('T')[0]">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label for="selectedDate">Fecha Final</label>
                                                                                        <input type="date" v-model="filtroFechaFinal" class="form-control" id="filtroFechaFinal" :max="new Date().toISOString().split('T')[0]">
                                                                                    </div>
                                                                                    <button type="submit" class="btn btn-primary">Aplicar Fechas</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <form @submit.prevent="submitForm">
                                                                <div>
                                                                    <input type="checkbox" id="selectAll" v-model="selectAll" @change="toggleSelectAll" class="custom-checkbox">
                                                                    <label for="selectAll">
                                                                        <small style="font-size: 0.8em;"><strong>  --- SELECCIONAR TODO ---</strong> </small>
                                                                    </label>
                                                                </div>
                                                                <div v-for="proceso in predefinedProcesos" :key="proceso.codigo">
                                                                    <input type="checkbox" :id="proceso.codigo" :value="{ prc_id: proceso.prc_id, codigo: proceso.codigo }" v-model="selectedProcesos" class="custom-checkbox">
                                                                    <label :for="proceso.codigo" class="d-inline-block mr-2">
                                                                        <small class="truncate-text" style="font-size: 0.8em;" :title="proceso.descripcion">{{ proceso.descripcion }}</small>
                                                                        <small class="truncate-text" style="font-size: 0.8em;" :title="proceso.codigo"><strong>({{ proceso.codigo }})</strong></small>
                                                                    </label>
                                                                </div>
                                                                <hr>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="fa fa-chart-bar" aria-hidden="true"></i> GENERAR
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <info-tramites></info-tramites>

                                    <br> <br>

                                    <div v-if="this.filtroFechaHoy">
                                        <p> <strong> Filtro de Fecha Hoy </strong> </p>
                                        <span class="badge badge-success" style="font-size: 1.2em;">
                                            <i class="fas fa-calendar"></i> {{ this.filtroFechaHoy }}
                                        </span>
                                    </div>

                                    <div v-if="this.filtroFechaInicial && this.filtroFechaFinal">
                                        <p> <strong> Filtro de Rango de Fechas</strong> </p>

                                        <span class="badge badge-primary" style="font-size: 1.2em;">
                                            <i class="fas fa-calendar"></i>
                                            Fecha Inicial <span class="badge badge-success">{{ this.filtroFechaInicial }}</span>
                                        </span>

                                        <span class="badge badge-primary" style="font-size: 1.2em;">
                                            <i class="fas fa-calendar"></i>
                                            Fecha Final <span class="badge badge-warning">{{ this.filtroFechaFinal }}</span>
                                        </span>
                                    </div>

                                    <br>
                                        <bar-chart :chartData="barChartData"/>
                                    <hr>
                                </div>
                            </div>
                            <div class="content">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header border-0">
                                                    <h4 class="card-title"> <strong>  TOTAL DE REGISTROS DE TRAMITE POR DEPARTAMENTOS GRAFICO </strong> </h4> <br>
                                                    <div v-if="this.filtroFechaHoyDep">
                                                        <p> <strong> Filtro de Fecha Hoy </strong> </p>
                                                        <span class="badge badge-success" style="font-size: 1.2em;">
                                                            <i class="fas fa-calendar"></i> {{ this.filtroFechaHoyDep }}
                                                        </span>
                                                    </div>

                                                    <div v-if="this.filtroFechaInicialDep && this.filtroFechaFinalDep">
                                                        <p> <strong> Filtro de Rango de Fechas</strong> </p>

                                                        <span class="badge badge-primary" style="font-size: 1.2em;">
                                                            <i class="fas fa-calendar"></i>
                                                            Fecha Inicial <span class="badge badge-success">{{ this.filtroFechaInicialDep }}</span>
                                                        </span> <br>

                                                        <span class="badge badge-primary" style="font-size: 1.2em;">
                                                            <i class="fas fa-calendar"></i>
                                                            Fecha Final <span class="badge badge-warning">{{ this.filtroFechaFinalDep }}</span>
                                                        </span>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <!-- Modal -->
                                                        <button type="button" class="btn btn-primary mb-3 mr-2" data-toggle="modal" data-target="#filterDos">
                                                            <i class="fas fa-filter"></i>
                                                        </button>

                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn mr-2" style="width: 40px; height: 40px;" data-toggle="modal" data-target="#alertaDepartamentosModalTitle1" title="Informativo Depto.">
                                                            <i class="fas fa-lightbulb"></i>
                                                        </button>

                                                        <info-deps1> </info-deps1>

                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="submitFormDep(3)" title="Actualizar">
                                                            <i class="fas fa-retweet"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="filterDos" tabindex="-1" role="dialog" aria-labelledby="filterDosLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="filterDosLabel">
                                                                        <i class="fas fa-filter"></i>Filtros de Búsqueda Departamentos Graficos</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <div class="d-flex justify-content-center">

                                                                                <button type="submit" class="btn btn-success mr-2" data-toggle="modal" data-target="#dateModalDepGraficos">
                                                                                    <i class="fa fa-calendar" aria-hidden="true"></i> Fecha
                                                                                </button>
                                                                                <!-- Fecha Hoy -->
                                                                                <div class="modal fade" id="dateModalDepGraficos" tabindex="-1" role="dialog" aria-labelledby="dateModalDepGraficosLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="dateModalDepGraficosLabel">Seleccionar Fecha</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <loader-graficos v-if="isLoading"></loader-graficos>
                                                                                                <form @submit.prevent="submitFormDep(1)">
                                                                                                    <div class="form-group">
                                                                                                        <label for="filtroFechaHoyDep">Fecha</label>
                                                                                                        <input type="date" v-model="filtroFechaHoyDep" class="form-control" id="filtroFechaHoyDep" :max="new Date().toISOString().split('T')[0]">
                                                                                                    </div>
                                                                                                    <button type="submit" class="btn btn-primary">
                                                                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                                                                        Aplicar Fecha
                                                                                                    </button>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <button type="submit" class="btn btn-info" data-toggle="modal" data-target="#dateRangosDepGraficos">
                                                                                    <i class="fa fa-calendar" aria-hidden="true"></i> Rangos Fechas
                                                                                </button>

                                                                                <div class="modal fade" id="dateRangosDepGraficos" tabindex="-1" role="dialog" aria-labelledby="dateRangosDepGraficos" aria-hidden="true">
                                                                                    <div class="modal-dialog" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="dateRangosDepGraficos">Seleccionar Fecha</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <loader-graficos v-if="isLoading"></loader-graficos>
                                                                                                <form @submit.prevent="submitFormDep(2)">
                                                                                                    <div class="form-group">
                                                                                                        <label for="selectedDate">Fecha Inicial</label>
                                                                                                        <input type="date" v-model="filtroFechaInicialDep" class="form-control" id="filtroFechaInicialDep" :max="new Date().toISOString().split('T')[0]">
                                                                                                    </div>
                                                                                                    <div class="form-group">
                                                                                                        <label for="selectedDate">Fecha Final</label>
                                                                                                        <input type="date" v-model="filtroFechaFinalDep" class="form-control" id="filtroFechaFinalDep" :max="new Date().toISOString().split('T')[0]">
                                                                                                    </div>
                                                                                                    <button type="submit" class="btn btn-primary">Aplicar Fechas</button>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <pieDep :data="pieDepData" :options="chartOptions"></pieDep>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header border-0">
                                                    <div>
                                                        <h3 class="card-title"> <strong> CASOS PENDIENTES DE VALIDACIÓN POR PRESTACIÓN DE VALOR DE COUTA </strong> </h3>

                                                        <div class="d-flex justify-content-end">
                                                            <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn mr-2" style="width: 40px; height: 40px;" data-toggle="modal" data-target="#alertaDepartamentosModal" title="Informativo">
                                                                <i class="fas fa-lightbulb"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="getEtapaPreviaCalculo(1)" title="Actualizar">
                                                                <i class="fas fa-retweet"></i>
                                                            </button>
                                                        </div>

                                                        <!-- Modal -->

                                                        <div class="modal fade" id="filtroValorCouta" tabindex="-1" role="dialog" aria-labelledby="filtroValorCoutaLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document" style="max-width: 33%">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="filterUnoLabel"> <i class="fas fa-filter"></i> Filtros de Búsqueda Prestación de valor de couta</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-8">
                                                                                <div class="d-flex justify-content-center">

                                                                                    <!-- Fecha Hoy -->
                                                                                    <div class="modal fade" id="dateModalValorCouta" tabindex="-1" role="dialog" aria-labelledby="dateModalValorCoutaLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog" role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title" id="dateModalValorCoutaLabel">Seleccionar Fecha</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <loader-graficos v-if="isLoading"></loader-graficos>
                                                                                                    <form @submit.prevent="submitFormValorCouta(1)">
                                                                                                        <div class="form-group">
                                                                                                            <label for="filtroFechaHoyValorCouta">Fecha</label>
                                                                                                            <input type="date" v-model="filtroFechaHoyValorCouta" class="form-control" id="filtroFechaHoyValorCouta" :max="new Date().toISOString().split('T')[0]">
                                                                                                        </div>
                                                                                                        <button type="submit" class="btn btn-primary">
                                                                                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                                                                                            Aplicar Fecha
                                                                                                        </button>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <button type="submit" class="btn btn-info" data-toggle="modal" data-target="#dateRangosValorCouta">
                                                                                        <i class="fa fa-calendar" aria-hidden="true"></i> Rangos Fechas
                                                                                    </button>

                                                                                    <div class="modal fade" id="dateRangosValorCouta" tabindex="-1" role="dialog" aria-labelledby="dateRangosValorCouta" aria-hidden="true">
                                                                                        <div class="modal-dialog" role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title" id="dateRangosValorCouta">Seleccionar Fecha</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <loader-graficos v-if="isLoading"></loader-graficos>

                                                                                                    <form @submit.prevent="submitFormValorCouta(2)">
                                                                                                        <div class="form-group">
                                                                                                            <label for="filtroFechaInicialValorCouta">Fecha Inicial</label>
                                                                                                            <input type="date" v-model="filtroFechaInicialValorCouta" class="form-control" id="filtroFechaInicialValorCouta" :max="new Date().toISOString().split('T')[0]">
                                                                                                        </div>
                                                                                                        <div class="form-group">
                                                                                                            <label for="filtroFechaFinalValorCouta">Fecha Final</label>
                                                                                                            <input type="date" v-model="filtroFechaFinalValorCouta" class="form-control" id="filtroFechaFinalValorCouta" :max="new Date().toISOString().split('T')[0]">
                                                                                                        </div>
                                                                                                        <button type="submit" class="btn btn-primary">Aplicar Fechas</button>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <hr>
                                                                                <form @submit.prevent="submitFormValorCouta">
                                                                                    <div>
                                                                                        <input type="checkbox" id="selectAllValorCouta" v-model="selectAllValorCouta" @change="toggleSelectAllValorCouta">
                                                                                        <label for="selectAllValorCouta">
                                                                                            <small style="font-size: 0.8em;"><strong> Seleccionar todos</strong> </small>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div v-for="proceso in predefinedProcesosValorCouta" :key="proceso.codigo">
                                                                                        <input type="checkbox" :id="proceso.codigo" :value="{ prc_id: proceso.prc_id, codigo: proceso.codigo }" v-model="selectedProcesosValorCouta">
                                                                                        <label :for="proceso.codigo" class="d-inline-block mr-2">
                                                                                            <small style="font-size: 0.8em;">{{ proceso.descripcion }}</small>
                                                                                            <small style="font-size: 0.8em;"><strong>({{ proceso.codigo }})</strong></small>
                                                                                        </label>
                                                                                    </div>
                                                                                    <hr>
                                                                                    <button type="submit" class="btn btn-primary">
                                                                                        <i class="fa fa-chart-bar" aria-hidden="true"></i> Generar
                                                                                    </button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <couta-chart
                                                            :totalTramitesOccidentePendientes=this.totalTramitesOccidentePendientes
                                                            :totalTramitesOrientePendientes=this.totalTramitesOrientePendientes
                                                            :totalTramitesVallesPendientes=this.totalTramitesVallesPendientes
                                                            :diferenciaOccidenteAyer=this.diferenciaOccidenteAyer
                                                            :diferenciaOrienteAyer=this.diferenciaOrienteAyer
                                                            :diferenciaVallesAyer=this.diferenciaVallesAyer
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header border-0">
                                                    <div class="d-flex justify-content-between">
                                                        <h3 class="card-title"><strong> ESTADO DE AVANCE DE TRAMITES </strong> </h3>
                                                    </div>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="filterDos" tabindex="-1" role="dialog" aria-labelledby="filterDosLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="filterDosLabel">
                                                                        <i class="fas fa-filter"></i> Filtros Estado de Avance de Tramites</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form @submit.prevent="applyFilters">
                                                                        <div class="form-group">
                                                                            <label for="startDate">Fecha Inicio</label>
                                                                            <input type="date" v-model="filtro.filtros.fecha_inicio" class="form-control" id="startDate">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="endDate">Fecha Fin</label>
                                                                            <input type="date" v-model="filtro.filtros.fecha_fin" class="form-control" id="endDate">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="gestion">Gestión</label>
                                                                            <select v-model="filtro.filtros.cas_gestion" class="form-control">
                                                                                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                                                            </select>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                                                                    </form>
                                                                    <form @submit.prevent="submitForm">
                                                                        <hr>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fa fa-chart-bar" aria-hidden="true"></i>
                                                                            Generar
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <stack-chart
                                                            :keysOccidente="keysOccidente"
                                                            :valuesOccidente="valuesOccidente"
                                                            :keysOriente="keysOriente"
                                                            :valuesOriente="valuesOriente"
                                                            :keysValles="keysValles"
                                                            :valuesValles="valuesValles"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2';

    import pieChart from './graficas/Pie.vue'
    import barChart from './graficas/Bar.vue'
    import barrasChart from './graficas/Bar2.vue'
    import stackChart from './graficas/Stack.vue'
    import baseChart from './graficas/Basepie.vue'
    import pieDep from './graficas/Piedep.vue'
    import coutaChart from './graficas/ValorCouta.vue'
    import chartPendiente from './graficas/ChartPendiente.vue'
    import LoaderGraficos from './graficas/LoaderGraficos.vue'
    import Agencias from './graficas/Agencias.vue'
    import Meses from './graficas/Meses.vue'
    import torta from './graficas/Torta.vue'
    import Regionales from './graficas/Regionales.vue'

    import InfoDeps from './graficas/informativos/InfoDeps.vue'
    import InfoDeps1 from './graficas/informativos/InfoDeps1.vue'
    import InfoTramites from './graficas/informativos/InfoTramites.vue'

    import Shepherd from "shepherd.js";
    import "shepherd.js/dist/css/shepherd.css";

    export default {
        name: 'TramiteSIP',
        components: {
            pieChart,
            barChart,
            stackChart,
            baseChart,
            pieDep,
            coutaChart,
            barrasChart,
            chartPendiente,
            LoaderGraficos,
            Agencias,
            Meses,
            torta,
            Regionales,

            //modales
            InfoDeps,
            InfoDeps1,
            InfoTramites
        },
    data() {
        return {

            pasos: [
                { id: "filtro", text: "<strong>Filtro de Busqueda</strong>, este componente permite el filtro de los datos por dia y rango de Fechas,'En la mayoria de los filtros de encuentran por mes o por dia eso en su defecto' ", attachTo: { element: "#filtro", on: "bottom" }, classes: "custom-step-class" },
                { id: "limpiarFiltros", text: "<strong> Limpiar Filtros </strong>, este componente permite la limpieza de los filtros de Busqueda por Fechas y rango de Fechas", attachTo: { element: "#limpiarFiltros", on: "top" } },
                { id: "informativoBotton", text: "<strong> Alerta informativa </strong>, este componente permite visualizar una ventana emergente informativa sobre el grafico", attachTo: { element: "#informativoBotton", on: "right" } },
                { id: "actualizar", text: "<strong> Actualizar </strong>, este componente permite actualizar los registros de la grafica, traendo asi los ultimos registros del sistema TramiteSip", attachTo: { element: "#actualizar", on: "left" } },
            ],

        isLoading: false,
        usrUser: window.Laravel.usr_user,

        totalTramitesHoy: 0,
        totalTramitesOccidente: 0,
        totalTramitesOriente: 0,
        totalTramitesValles: 0,

        showChatBubble: false,
            lapaz : 0,
            beni : 0,
            pando : 0,
            oruro : 0,
            cochabamba : 0,
            santaCruz : 0,
            potosi : 0,
            chuquisaca : 0,
            tarija : 0,

        pieDepData: [],

        TotalUsers : 0,
        TotalRegional : 0,
        TotalAgencia : 0,

        totalTramitesOccidentePendientes:0,
        totalTramitesOrientePendientes:0,
        totalTramitesVallesPendientes:0,

        diferenciaOccidenteAyer:0,
        diferenciaOrienteAyer:0,
        diferenciaVallesAyer:0,

        estadoAvance : [],
        selectedDate: '',

        dataEstadoAvanceOccidente: [],
        dataEstadoAvanceOriente: [],
        dataEstadoAvanceValles: [],

        keysOccidente: [],
        valuesOccidente: [],

        keysOriente: [],
        valuesOriente: [],

        keysValles: [],
        valuesValles: [],

        selectAll: false,
        selectAllValorCouta: false,
        years: [],
        FechaHora: '',
        procesos: [],
        selectedProcesos: [],
        selectedProcesosValorCouta: [],
        chartOptions: {},

        filtroFechaHoy: '',
        filtroFechaInicial: '',
        filtroFechaFinal: '',

        //filtros para depts.
        filtroFechaHoyDep : '',
        filtroFechaInicialDep : '',
        filtroFechaFinalDep : '',

        filtroFechaHoyValorCouta: '',
        filtroFechaInicialValorCouta: '',
        filtroFechaFinalValorCouta: '',

        listUltimosTramites : [],

        filtro: { prc_codigo: '', cas_nro_caso: '', cas_tipo: '', fecha_ini: this.fechaIni, fecha_fin: '',
        filtros: { fecha_inicio: '', fecha_fin: '', gestion: '' },
        id_departamento: '', id_agencia: '', id_regional: '', id_area: '' },

        barChartData: {
            labels: [],
            datasets: [
            {
                label: 'Graficas 1',
                backgroundColor: [],
                data: []
            }
            ]
        },

        barChartData2: {
            labels: [],
            datasets: [
            {
                label: 'Graficas 2',
                backgroundColor: [],
                data: []
            }
            ]
        },

        barChartAgencias: {
            labels: [],
            datasets: [
            {
                label: 'Agencias',
                backgroundColor: [],
                data: []
            }
            ]
        },

        barChartPorMes: {
            labels: [],
            datasets: [
            {
                label: 'Registros Por Mes',
                backgroundColor: [],
                data: []
            }
            ]
        },

        barChartRegionales: {
            labels: [],
            datasets: [
            {
                label: 'Registros Por Regionales',
                backgroundColor: [],
                data: []
            }
            ]
        },

        predefinedProcesos: [],

        predefinedProcesosValorCouta: [
            { prc_id: 4, codigo: 'RMIN', descripcion: 'RETIRO MINIMO - FINAL'},
            { prc_id: 12, codigo: 'MAHER', descripcion: 'MASA HEREDITARIA'},
            { prc_id: 3, codigo: 'PM', descripcion: 'PENSIÓN POR MUERTE'},
            { prc_id: 1, codigo: 'INV', descripcion: 'PENSIÓN POR INVALIDEZ'},
            { prc_id: 13, codigo: 'PAGCC', descripcion: 'PAGOS DE COMPENSACION DE COTIZACIONES'},
            { prc_id: 9, codigo: 'JUB', descripcion: 'PENSIÓN POR JUBILACIÓN'},
            { prc_id: 5, codigo: 'GFU', descripcion: 'GASTOS FUNERARIOS'},
            { prc_id: 15, codigo: 'JUB1582', descripcion: 'JUBILACIÓN LEY 1582'},
        ]

        };
    },
        watch: {
            procesos(newVal) {
                if (newVal.length > 0) {
                    this.submitForm();
                }
            }
        },
        mounted() {
            this.updateDateTime();
            setInterval(this.updateDateTime, 1000);
            this.listarProcesosG();
            this.datosGenerales();
            this.submitForm();
            this.metricasDepartamentos();
            this.populateYears();
            this.getEtapaPreviaCalculo();
            this.getEstadoAvance();
            this.ultimosRegistros();
            this.datosComplementarios();
            this.registrosXAgencias();
            this.registrosXMes();
            this.registrosXRegionales();

            this.selectedProcesosValorCouta = this.predefinedProcesosValorCouta.map(proceso => ({
                prc_id: proceso.prc_id,
                codigo: proceso.codigo
            }));
        },
    computed: {
        formattedUltimosTramites() {
            return this.listUltimosTramites.map(tramite => ({
                ...tramite,
                formattedDate: this.formatearFecha(tramite.cas_registrado.substr(0, 10)),
                formattedTime: tramite.cas_registrado.substr(10, 5)
            }));
        },

        mesActual() {
            const now = new Date();
            return now.toLocaleString(this.locale, { month: 'long' });
        }
    },
    methods: {

        getDepartamentos(departamento) {
            console.log(`Departamento seleccionado: ${departamento}`);
        },

        empezarTutorial() {
            const tour = new Shepherd.Tour({
                useModalOverlay: true,
                defaultStepOptions: {
                    classes: "shadow-md bg-purple-dark",
                    scrollTo: true,
                },
            });

            this.pasos.forEach((step, index) => {
                tour.addStep({
                    id: step.id,
                    text: step.text,
                    attachTo: step.attachTo,
                    buttons: this.getButtons(index, tour),
                });
            });

            tour.start();
        },

        getButtons(index, tour) {
            const buttons = [];
            if (index > 0) {
                buttons.push({
                    text: "<< ANTERIOR",
                    action: tour.back,
                });
            }
            if (index < this.pasos.length - 1) {
                buttons.push({
                    text: "SIGUIENTE >>",
                    action: tour.next,
                });
            } else {
                buttons.push({
                    text: "FINALIZAR",
                    action: tour.complete,
                });
            }
            return buttons;
        },

        populateYears() {
            const currentYear = new Date().getFullYear();
            const startYear = 2023;
            for (let year = startYear; year <= currentYear; year++) {
                this.years.push(year);
            }
        },

        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedProcesos = this.predefinedProcesos.map(proceso => ({
                    prc_id: proceso.prc_id,
                    codigo: proceso.codigo
                }));
            } else {
                this.selectedProcesos = [];
            }
        },

        toggleSelectAllValorCouta() {
            if (this.selectAllValorCouta) {
                this.selectedProcesosValorCouta = this.predefinedProcesosValorCouta.map(proceso => ({
                    prc_id: proceso.prc_id,
                    codigo: proceso.codigo
                }));
            } else {
                this.selectedProcesosValorCouta = [];
            }
        },

        listarProcesosG() {
            let url = "api/v1/kpi/listadoProcesosG";
            axios.get(url).then(response => {
                if (response.data.codigoRespuesta.code === 200) {
                    this.procesos = response.data.data;
                    this.predefinedProcesos = response.data.data;

                    this.procesos.forEach(function (row) {
                        if (row.prc_data) {
                            try {
                                row.prc_data = JSON.parse(row.prc_data);
                            } catch (e) {
                                console.error('JSON invalido >', row.prc_data);
                            }
                        }
                    });
                    this.selectedProcesos = this.procesos.map(proceso => ({
                        prc_id: proceso.prc_id,
                        codigo: proceso.codigo
                    }));
                } else {
                    console.error("Error en la respuesta api", response.data.codigoRespuesta.mensaje);
                }
            }).catch(error => {
                console.error("Error al consumir api", error);
            });
        },

        submitForm(sw) {
            if(sw == 1){
                this.filtroFechaInicial = '';
                this.filtroFechaFinal = '';
            } else if(sw == 2){
                this.filtroFechaHoy = '';
            } else if(sw == 3){
                this.filtroFechaHoy = '';
                this.filtroFechaInicial = '';
                this.filtroFechaFinal = '';

                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Filtros de fechas Limpiados",
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            const selectedIds = this.selectedProcesos.map(proceso => proceso.prc_id);
            const selectedCodes = this.selectedProcesos.map(proceso => proceso.codigo);

            const payload = {
                selectedIds: selectedIds,
                selectedCodes: selectedCodes,
                fechaHoy : this.filtroFechaHoy,
                fechaInicial : this.filtroFechaInicial,
                fechaFinal : this.filtroFechaFinal
            };

            axios.post('api/v1/kpi/cantidadTramite', payload)
                .then(response => {
                    if (response.data.codigoRespuesta && response.data.codigoRespuesta.code === 200) {
                        const respoCantidadTramite = response.data.data;

                        const labels = respoCantidadTramite.map(item => item.codigo);
                        const values = respoCantidadTramite.map(item => item.total);

                        this.updateChart({ labels, values });
                    } else {
                        console.error("error en la respuesta del API", response.data.codigoRespuesta.mensaje);
                    }
                })
                .catch(error => {
                    console.error("error", error);
                });
        },

        submitFormDepartamentos(sw){
            if(sw == 1){
                this.filtroFechaInicialDep = '';
                this.filtroFechaFinalDep = '';
            } else if(sw == 2){
                this.filtroFechaHoyDep = '';
            } else if(sw == 3){
                this.filtroFechaHoyDep = '';
                this.filtroFechaInicialDep = '';
                this.filtroFechaFinalDep = '';

                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Filtros de fechas Limpiados",
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            const selectedIds = this.selectedProcesos.map(proceso => proceso.prc_id);
            const selectedCodes = this.selectedProcesos.map(proceso => proceso.codigo);

            const payload = {
                selectedIds: selectedIds,
                selectedCodes: selectedCodes,
                fechaHoy : this.filtroFechaHoy,
                fechaInicial : this.filtroFechaInicial,
                fechaFinal : this.filtroFechaFinal
            };

            axios.post('api/v1/kpi/cantidadTramite', payload)
                .then(response => {
                    if (response.data.codigoRespuesta && response.data.codigoRespuesta.code === 200) {
                        const respoCantidadTramite = response.data.data;

                        const labels = respoCantidadTramite.map(item => item.codigo);
                        const values = respoCantidadTramite.map(item => item.total);

                        this.updateChart({ labels, values });
                    } else {
                        console.error("error en la respuesta del API", response.data.codigoRespuesta.mensaje);
                    }
                })
                .catch(error => {
                    console.error("error", error);
                });
        },

        updateChart({ labels, values }) {
            this.isLoading = true;
                setTimeout(() => {
                    this.isLoading = false;
                }, 2000);

            const colors = values.map(() => `#${Math.floor(Math.random()*16777215).toString(16)}`);

            this.barChartData = {
                labels: labels,
                datasets: [
                    {
                        label: 'TramiteSIP',
                        backgroundColor: colors,
                        data: values
                    }
                ]
            };
        },

        submitFormDep(sw){
            this.isLoading = true;
            setTimeout(() => {
                this.isLoading = false;
            }, 2000);

            if(sw == 1){
                this.filtroFechaInicialDep = '';
                this.filtroFechaFinalDep = '';
            } else if(sw == 2){
                this.filtroFechaHoyDep = '';
            } else if(sw == 3){
                // this.filtroFechaHoyDep = '';
                // this.filtroFechaInicialDep = '';
                // this.filtroFechaFinalDep = '';

                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Registros Actualizados ...",
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            const payloadDep = {
                fechaHoyDep : this.filtroFechaHoyDep,
                fechaInicialDep : this.filtroFechaInicialDep,
                fechaFinalDep : this.filtroFechaFinalDep
            };

            axios.get('api/v1/kpi/cantidadRegistrosPorMes', { params: { gestion: selectedYear } })
                .then(response => {
                    console.log("Response -----> Actualizando ... Departamentos >>> : ", response.data);
                    this.lapaz = response.data.data["LA PAZ"];
                    this.oruro = response.data.data["ORURO"];
                    this.potosi = response.data.data["POTOSI"];
                    this.cochabamba = response.data.data["COCHABAMBA"];
                    this.chuquisaca = response.data.data["CHUQUISACA"];
                    this.tarija = response.data.data["TARIJA"];
                    this.santaCruz = response.data.data["SANTA CRUZ"];
                    this.beni = response.data.data["BENI"];
                    this.pando = response.data.data["PANDO"];
                    this.updatePieDepData();
                })
                .catch(error => {
                    console.error("Error!!!", error);
                });
        },

        updateDateTime() {
            const now = new Date();
            this.FechaHora = now.toLocaleString();
        },

        datosGenerales(sw){
            if (sw === 1) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Datos Generales Actualizados",
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            axios.get('api/v1/kpi/datosGenerales')
            .then(response => {
                if (response.data.codigoRespuesta.code === 200) {
                    this.totalTramitesHoy = response.data.data;
                    this.totalTramitesOccidente = response.data.data_occidente;
                    this.totalTramitesOriente = response.data.data_oriente;
                    this.totalTramitesValles = response.data.data_valles;
                } else {
                    console.error("Error en api datosGenerales", response.data.codigoRespuesta.mensaje);
                }
            })
            .catch(error => {
                console.error("error en consumir api", error);
            });
        },

        metricasDepartamentos() {
            axios.get('api/v1/kpi/metricasDepartamentos')
                .then(response => {
                    if (response.data.codigoRespuesta.code === 200) {
                        this.lapaz = response.data.data["LA PAZ"];
                        this.oruro = response.data.data["ORURO"];
                        this.potosi = response.data.data["POTOSI"];
                        this.cochabamba = response.data.data["COCHABAMBA"];
                        this.chuquisaca = response.data.data["CHUQUISACA"];
                        this.tarija = response.data.data["TARIJA"];
                        this.santaCruz = response.data.data["SANTA CRUZ"];
                        this.beni = response.data.data["BENI"];
                        this.pando = response.data.data["PANDO"];
                        this.updatePieDepData();
                    } else {
                        console.error("Error en la respuesta api", response.data.codigoRespuesta.mensaje);
                    }
                })
                .catch(error => {
                    console.error("error al consumir api", error);
                });
        },
        updatePieDepData() {
            this.pieDepData = [
                { value: this.lapaz, name: 'LA PAZ' },
                { value: this.oruro, name: 'ORURO' },
                { value: this.potosi, name: 'POTOSI' },
                { value: this.cochabamba, name: 'COCHABAMBA' },
                { value: this.chuquisaca, name: 'CHUQUISACA' },
                { value: this.tarija, name: 'TARIJA' },
                { value: this.santaCruz, name: 'SANTA CRUZ' },
                { value: this.beni, name: 'BENI' },
                { value: this.pando, name: 'PANDO' }
            ];
        },

        getEtapaPreviaCalculo(sw) {
            if (sw === 1) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Datos Actualizado ...",
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            let url = 'api/v1/kpi/etapaPreviaCalculo';
            let config = {};

            if (sw === 2) {
                config = { params: { comparativa: 'comparativa' } };
                console.log("El params ", config);
            }

            axios.get(url, config)
            .then(response => {
                if (response.data.codigoRespuesta.code === 200) {
                    this.totalTramitesOccidentePendientes = response.data.dataOccidente[0]?.total_occidente_count || 0;
                    this.totalTramitesOrientePendientes = response.data.dataOriente[0]?.total_oriente_count || 0;
                    this.totalTramitesVallesPendientes = response.data.dataValles[0]?.total_valles_count || 0;

                    // diferencias
                    this.diferenciaOccidenteAyer = response.data.diferenciaOccidenteAyer || 0;
                    this.diferenciaOrienteAyer = response.data.diferenciaOrienteAyer || 0;
                    this.diferenciaVallesAyer = response.data.diferenciaVallesAyer || 0;
                } else {
                    console.error("Error en la respuesta api", response.data.codigoRespuesta.mensaje);
                }
            })
            .catch(error => {
                console.error("error en consumir api", error);
            });
        },

        getEstadoAvance() {
            axios.get('api/v1/kpi/estadoAvanceTramite')
                .then(response => {
                    if (response.data.codigoRespuesta.code === 200) {
                        const data = response.data;
                        this.dataEstadoAvanceOccidente = data.dataEstadoAvanceOccidente;
                        this.dataEstadoAvanceOriente = data.dataEstadoAvanceOriente;
                        this.dataEstadoAvanceValles = data.dataEstadoAvanceValles;
                        this.keysOccidente = Object.keys(data.dataEstadoAvanceOccidente[0]);
                        this.valuesOccidente = Object.values(data.dataEstadoAvanceOccidente[0]);
                        this.keysOriente = Object.keys(data.dataEstadoAvanceOriente[0]);
                        this.valuesOriente = Object.values(data.dataEstadoAvanceOriente[0]);
                        this.keysValles = Object.keys(data.dataEstadoAvanceValles[0]);
                        this.valuesValles = Object.values(data.dataEstadoAvanceValles[0]);
                    } else {
                        console.error("Error en la respuesta api", response.data.codigoRespuesta.mensaje);
                    }
                })
                .catch(error => {
                    console.error("error en consumir api", error);
                });
        },
        estadoBandejaPendientes(){
            let url = "api/v1/kpi/casosPendientesBandeja";
            axios.get(url).then(response => {
            if (response.data.codigoRespuesta.code === 200) {
                console.log("api/v1/kpi/casosPendientesBandeja", response.data.data);
                this.procesos = response.data.data;

                const labels = Object.keys(this.procesos);
                const values = Object.values(this.procesos);

                const colors = labels.map(() => {
                const predefinedColors = ['#28a745'];
                return predefinedColors[Math.floor(Math.random() * predefinedColors.length)];
                });

                this.predefinedProcesos = labels.map((label, index) => ({
                    codigo: label,
                    descripcion: label,
                    cantidad: values[index]
                }));

                this.barChartData2 = {
                labels: labels,
                datasets: [
                    {
                    label: 'TramiteSIP',
                    backgroundColor: colors,
                    data: values
                    }
                ]
                };
            } else {
                console.error("Error respuesta api", response.data.codigoRespuesta.mensaje);
            }
            }).catch(error => {
            console.error("Error al consumir api", error);
            });
        },

        ultimosRegistros(sw=0){
            if (sw === 1) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Datos Actualizados ...",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
            let url = "api/v1/kpi/ultimosRegistros";
            axios.get(url).then(response => {
                if (response.data.codigoRespuesta.code === 200) {
                    this.listUltimosTramites = response.data.data;
                } else {
                    console.error("Error en la respuesta api", response.data.codigoRespuesta.mensaje);
                }
            }).catch(error => {
                console.error("Error al consumir api", error);
            });
        },

        formatearFecha(fecha){
            const losmeses = {
                JAN: 'ENERO',
                FEB: 'FEBRERO',
                MAR: 'MARZO',
                APR: 'ABRIL',
                MAY: 'MAYO',
                JUN: 'JUNIO',
                JUL: 'JULIO',
                AUG: 'AGOSTO',
                SEP: 'SEPTIEMBRE',
                OCT: 'OCTUBRE',
                NOV: 'NOVIEMBRE',
                DEC: 'DICIEMBRE'
            };
            const [dia, mes, anio] = fecha.split('-');
            return `${dia}-${losmeses[mes.toUpperCase()]}-${anio}`;
        },

        datosComplementarios(sw) {
            if (sw === 1) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Datos Actualizados ...",
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            let url = "api/v1/kpi/datosComplementarios";
            axios.get(url).then(response => {
            if (response.data.codigoRespuesta.code === 200) {
                this.TotalUsers = response.data.totalUsers[0].total,
                this.TotalRegional = response.data.totalRegional[0].total,
                this.TotalAgencia = response.data.totalAgencia[0].total
            } else {
                console.error("Error en la respuesta del API", response.data);
            }
            }).catch(error => {
            console.error("Error al consumir el API", error);
            });
        },

        registrosXAgencias(){
            let url = "api/v1/kpi/registrosPorAgencia";
            axios.get(url).then(response => {
            if (response.data.codigoRespuesta.code === 200) {
            const labels = Object.keys(response.data.data);
            const values = Object.values(response.data.data);

            const colors = labels.map(() => {
                const predefinedColors = [
                '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#007bff', '#6610f2', '#e83e8c'
                ];
                return predefinedColors[Math.floor(Math.random() * predefinedColors.length)];
            });

            this.barChartAgencias = {
                labels: labels,
                datasets: [
                    {
                        label: 'REGISTROS POR AGENCIA',
                        backgroundColor: colors,
                        data: values
                    }
                ]
            };

            } else {
                console.error("Error en la respuesta del API", response.data);
            }
            }).catch(error => {
                console.error("Error API", error);
            });
        },

        registrosXMes(){
            axios.get('api/v1/kpi/cantidadRegistrosPorMes')
                .then(response => {
                    if (response.data.codigoRespuesta.code === 200) {
                        const labels = Object.keys(response.data.data);
                        const values = Object.values(response.data.data);

                        const colors = labels.map(() => {
                            const predefinedColors = [
                                '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#007bff', '#6610f2', '#e83e8c'
                            ];
                            return predefinedColors[Math.floor(Math.random() * predefinedColors.length)];
                        });

                        this.barChartPorMes = {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Registros por Mes',
                                    backgroundColor: colors,
                                    data: values
                                }
                            ]
                        };

                    } else {
                        console.error("Error en la respuesta del API", response.data.codigoRespuesta.mensaje);
                    }
                })
                .catch(error => {
                    console.error("Error al consumir el API", error);
                });
        },

        registrosXRegionales(){
            let url = "api/v1/kpi/cantidadRegistrosPorRegional";
            axios.get(url).then(response => {
            if (response.data.codigoRespuesta.code === 200) {
            const labels = Object.keys(response.data.data);
            const values = Object.values(response.data.data);

            const colors = labels.map(() => {
                const predefinedColors = [
                '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#007bff', '#6610f2', '#e83e8c'
                ];
                return predefinedColors[Math.floor(Math.random() * predefinedColors.length)];
            });

            this.barChartRegionales = {
                labels: labels,
                datasets: [
                    {
                        label: 'REGISTROS POR REGIONALES',
                        backgroundColor: colors,
                        data: values
                    }
                ]
            };
            } else {
                console.error("Error en la respuesta del API", response.data);
            }
            }).catch(error => {
                console.error("Error API", error);
            });
        },

    }
    };
</script>

<style scoped>
    .month-display {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }
    .step-box {
        margin: 20px;
        padding: 15px;
        border: 1px solid #ccc;
        display: inline-block;
    }

    .product-img img:hover {
        transform: scale(1.4);
        transition: transform 0.3s ease;
    }

    .pulseBtn {
        background: #0b63bb;
        color: #fff;
        border: 1px solid #0b63bb;
        font-size: 1rem;
        box-shadow: 0 0 0 0 rgba(88, 120, 243, 0.4);
        -moz-animation: pulse 2s infinite;
        -webkit-animation: pulse 2s infinite;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(88, 120, 243, 1);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(88, 120, 243, 0);
        }
        100% {
            box-shadow: 0 0 0 50px rgba(88, 120, 243, 0);
        }
    }

    .departments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        padding: 1rem;
    }

    .department-card {
        background-color: #f8f9fa;
        border-radius: 8px;
        text-align: center;
        padding: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .department-card .product-img img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        margin-bottom: 0.5rem;
        border-radius: 50%;
    }
    .department-card .product-title {
        font-size: 1rem;
        font-weight: bold;
        color: #343a40;
    }
    .department-card .badge {
        display: inline-block;
        margin-top: 0.5rem;
        font-size: 0.85rem;
    }
    .truncate-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
        display: inline-block;
    }
    .custom-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #007bff;
    }
</style>

<template>
    <section class="content center">
      <div class="error-page">
        <h2 class="headline" style="color:#FFC107;"> 404</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle" style="color:#FFC107;"></i> Oops! Página no encontrada.</h3>

          <p>
            No pudimos encontrar la página que estabas buscando.
            Mientras tanto
            <router-link to="/dashboard"> puede regresar al panel </router-link>
          </p>

        </div>
      </div>
    </section>
</template>

<style>
      .center {
        position: absolute;
        top: 25%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%); /* IE 9 */
        -webkit-transform: translate(-50%, -50%); /* Chrome, Safari, Opera */
      }
</style>

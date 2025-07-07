<template>
  <div class="p-6 max-w-4xl mx-auto border border-gray-300 bg-white text-sm">
    <h2 class="text-center font-bold text-base uppercase mb-1">
      CONVENIO MULTILATERAL IBEROAMERICANO DE SEGURIDAD SOCIAL
    </h2>
    <p class="text-center italic mb-2">
      CONVENÇÃO MULTILATERAL IBERO-AMERICANA DE SEGURANÇA SOCIAL
    </p>
    <h3 class="text-center font-semibold mb-4">FORMULARIO DE ENLACE / FORMULÁRIO DE LIGAÇÃO</h3>

    <!-- SECCIÓN 1 -->
    <section class="mb-6">
      <h4 class="bg-gray-200 font-bold p-1 text-sm">
        1. ORGANISMO DE ENLACE O INSTITUCIÓN COMPETENTE QUE TRAMITA EL PROCEDIMIENTO
      </h4>
      <p class="italic text-xs mb-2">
        ORGANISMO DE LIGAÇÃO OU INSTITUIÇÃO COMPETENTE QUE EFECTUA A TRAMITAÇÃO DO PEDIDO
      </p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <label>
          Nombre del Organismo:
          <input v-model="form.organismo.nombre" class="input" />
        </label>
        <label>
          Dirección:
          <input v-model="form.organismo.direccion" class="input" />
        </label>
        <label>
          Localidad:
          <input v-model="form.organismo.localidad" class="input" />
        </label>
        <label>
          País:
          <input v-model="form.organismo.pais" class="input" />
        </label>
        <label>
          Nº de expediente o referencia:
          <input v-model="form.organismo.referencia" class="input text-blue-700" />
        </label>
      </div>
    </section>

    <!-- SECCIÓN 2 -->
    <section>
      <h4 class="bg-gray-200 font-bold p-1 text-sm">
        2. ORGANISMOS DE ENLACE O INSTITUCIONES COMPETENTES DESTINATARIAS
      </h4>
      <p class="italic text-xs mb-2">
        ORGANISMOS DE LIGAÇÃO OU INSTITUIÇÕES COMPETENTES DESTINATÁRIAS
      </p>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <label>
          País:
          <input v-model="form.destino.pais" class="input" />
        </label>
        <label>
          Nombre del Organismo:
          <input v-model="form.destino.organismo" class="input" />
        </label>
        <label>
          Dirección:
          <input v-model="form.destino.direccion" class="input" />
        </label>
      </div>
    </section>
    <!-- SECCIÓN 3 ─────────────────────────────────────────────────────────────-->
    <section class="mb-6">
      <h4 class="bg-gray-200 font-bold p-1 text-sm">
        3. SOLICITUD PRESENTADA POR EL INTERESADO
      </h4>
      <p class="italic text-xs mb-3">
        SOLICITAÇÃO APRESENTADO PELO INTERESSADO
      </p>

      <!-- Checkboxes -->
      <div class="flex flex-wrap gap-x-8 gap-y-2 mb-4">
        <label><input type="checkbox" v-model="form.solicitud.tipos.jubilacion" /> Jubilación / <em>Velhice</em></label>
        <label><input type="checkbox" v-model="form.solicitud.tipos.incapacidad" /> Incapacidad / <em>Invalidez</em></label>
        <label><input type="checkbox" v-model="form.solicitud.tipos.supervivencia" /> Supervivencia / <em>Pensão por morte</em></label>
        <label><input type="checkbox" v-model="form.solicitud.tipos.certificacion" /> Certificación de períodos de seguro</label>
      </div>

      <label class="block">
        Fecha de la solicitud / <em>Data do requerimento</em>:
        <input type="date" v-model="form.solicitud.fecha" class="input" />
      </label>
    </section>



 <!-- SECCIÓN 4 ─────────────────────────────────────────────────────────────-->
    <section>
      <h4 class="bg-gray-200 font-bold p-1 text-sm mb-2">
        4. DATOS DEL TRABAJADOR ASEGURADO / <em>DADOS DO TRABALHADOR SEGURADO</em>
      </h4>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <label>
          Apellido(s) / <em>Sobrenome(s)</em>
          <input v-model="form.trabajador.apellido" class="input" />
        </label>

        <label>
          Apellidos de soltera / <em>Sobrenome de solteira</em>
          <input v-model="form.trabajador.apellidoSoltera" class="input" />
        </label>

        <label class="md:col-span-2">
          Nombre(s) / <em>Nome(s)</em>
          <input v-model="form.trabajador.nombres" class="input" />
        </label>

        <label>
          Fecha de nacimiento / <em>Data de nascimento</em>
          <input type="date" v-model="form.trabajador.fechaNacimiento" class="input" />
        </label>

        <label>
          Nº Documento de identificación (institución competente)
          <input v-model="form.trabajador.docIdentidad" class="input" />
        </label>

        <label>
          Nº Seguridad Social (institución competente)
          <input v-model="form.trabajador.numSeguridadSocial" class="input" />
        </label>

        <label>
          Nº Documento de identificación (institución destinataria)
          <input v-model="form.trabajador.docIdentidadDest" class="input" />
        </label>
      </div>
    </section>


      <!-- SECCIÓN 5 ─────────────────────────────────────────────────────────────-->
      <section class="mb-6">
        <h4 class="bg-gray-200 font-bold p-1 text-sm">
          5. DATOS DEL SOLICITANTE (COMPLETAR EXCLUSIVAMENTE EN CASO DE PENSIONES DE SUPERVIVENCIA)
        </h4>
        <p class="italic text-xs mb-2">
          DADOS DO SOLICITANTE (COMPLETAR EXCLUSIVAMENTE EM CASO DE PENSÕES POR MORTE)
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <label>Apellido(s): <input v-model="form.solicitante.apellido" class="input" /></label>
          <label>Apellidos de soltera: <input v-model="form.solicitante.apellidoSoltera" class="input" /></label>
          <label class="md:col-span-2">Nombre(s): <input v-model="form.solicitante.nombres" class="input" /></label>
          <label>Fecha de nacimiento: <input type="date" v-model="form.solicitante.fechaNacimiento" class="input" /></label>
          <label>Nº Doc. identidad (inst. competente): <input v-model="form.solicitante.docIdentidad" class="input" /></label>
          <label>Nº Doc. identidad (inst. destinataria): <input v-model="form.solicitante.docIdentidadDest" class="input" /></label>
        </div>
      </section>
    

      <!-- SECCIÓN 6 ─────────────────────────────────────────────────────────────-->
  <section>
    <h4 class="bg-gray-200 font-bold p-1 text-sm">
      6. DATOS DEL TRABAJADOR ASEGURADO / <em>DADOS DO TRABALHADOR SEGURADO</em>
    </h4>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Apellidos y nombres -->
      <label>Apellido(s): <input v-model="form.trabajadorExt.apellido" class="input" /></label>
      <label>Apellidos de soltera: <input v-model="form.trabajadorExt.apellidoSoltera" class="input" /></label>
      <label class="md:col-span-2">Nombre(s): <input v-model="form.trabajadorExt.nombres" class="input" /></label>

      <!-- Padres -->
      <label class="md:col-span-2">Nombre(s) del padre: <input v-model="form.trabajadorExt.nombrePadre" class="input" /></label>
      <label class="md:col-span-2">Nombre(s) de la madre: <input v-model="form.trabajadorExt.nombreMadre" class="input" /></label>

      <!-- Fecha nacimiento, sexo, lugar -->
      <label>Fecha de nacimiento: <input type="date" v-model="form.trabajadorExt.fechaNacimiento" class="input" /></label>

      <div class="flex items-center gap-4">
        Sexo:
        <label><input type="radio" value="M" v-model="form.trabajadorExt.sexo" /> Masculino</label>
        <label><input type="radio" value="F" v-model="form.trabajadorExt.sexo" /> Femenino</label>
      </div>

      <label class="md:col-span-2">Lugar de nacimiento: <input v-model="form.trabajadorExt.lugarNacimiento" class="input" /></label>
    </div>
  </section>

    <!-- 6.7 – 6.14 Campos adicionales del trabajador asegurado -->
  <section class="mt-8">
    <!-- ENCABEZADO YA MOSTRADO ANTES -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- 6.7 País de nacimiento -->
      <label>País de nacimiento:
        <input v-model="form.trabajadorExt.paisNacimiento" class="input" />
      </label>

      <!-- 6.8 Nacionalidad -->
      <label>Nacionalidad:
        <input v-model="form.trabajadorExt.nacionalidad" class="input" />
      </label>

      <!-- 6.9 Profesión -->
      <label>Profesión o actividad:
        <input v-model="form.trabajadorExt.profesion" class="input" />
      </label>

      <!-- 6.10 Situación laboral -->
      <div>
        <label class="block mb-1">Fecha en que dejó o dejará de trabajar y/o cotizar:</label>
        <input type="date" v-model="form.trabajadorExt.fechaCese" :disabled="form.trabajadorExt.sigueCotizando" class="input" />
        <label class="inline-flex items-center gap-1 mt-2">
          <input type="checkbox" v-model="form.trabajadorExt.sigueCotizando" />
          El asegurado sigue trabajando y/o cotizando
        </label>
      </div>
    </div>

    <!-- 6.11 Estado civil -->
    <div class="mt-6">
      <p class="font-semibold mb-1">Estado civil:</p>
      <div class="flex flex-wrap gap-6">
        <label><input type="radio" value="Soltero"     v-model="form.trabajadorExt.estadoCivil" /> Soltero</label>
        <label><input type="radio" value="Casado"      v-model="form.trabajadorExt.estadoCivil" /> Casado</label>
        <label><input type="radio" value="Viudo"       v-model="form.trabajadorExt.estadoCivil" /> Viudo</label>
        <label><input type="radio" value="Divorciado"  v-model="form.trabajadorExt.estadoCivil" /> Divorciado</label>
        <label><input type="radio" value="Separado"    v-model="form.trabajadorExt.estadoCivil" /> Separado</label>
        <label><input type="radio" value="UnionHecho"  v-model="form.trabajadorExt.estadoCivil" /> Unión de hecho</label>
        <label><input type="radio" value="Concubinato" v-model="form.trabajadorExt.estadoCivil" /> Concubinato</label>
      </div>
    </div>

    <!-- 6.12 – 6.14 Datos de fallecimiento -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
      <label>Fecha de fallecimiento:
        <input type="date" v-model="form.trabajadorExt.fechaFallec" class="input" />
      </label>
      <label>País de fallecimiento:
        <input v-model="form.trabajadorExt.paisFallec" class="input" />
      </label>
    </div>

    <!-- 6.14 Causa del fallecimiento -->
    <div class="mt-4">
      <p class="font-semibold mb-1">Causa del fallecimiento:</p>
      <div class="flex flex-col sm:flex-row sm:flex-wrap gap-x-6 gap-y-2">
        <label><input type="radio" value="AccidentTrabajo"      v-model="form.trabajadorExt.causaFallec" /> Accidente de trabajo</label>
        <label><input type="radio" value="EnfermedadProfesional" v-model="form.trabajadorExt.causaFallec" /> Enfermedad profesional</label>
        <label><input type="radio" value="AccidentNoLaboral"    v-model="form.trabajadorExt.causaFallec" /> Accidente no laboral</label>
        <label><input type="radio" value="EnfermedadComun"      v-model="form.trabajadorExt.causaFallec" /> Enfermedad común</label>
      </div>
    </div>
  </section>

   <!-- SECCIÓN 6.15 DIRECCIÓN ───────────────────────────────────────────── -->
  <section class="mt-8">
    <h4 class="bg-gray-200 font-bold p-1 text-sm">
      6.15 Dirección / <em>Endereço</em>
    </h4>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <label>Calle / <em>Rua</em> <input v-model="form.trabajadorExt.direccion.calle" class="input" /></label>
      <label>Nº Piso/Depto <input v-model="form.trabajadorExt.direccion.numero" class="input" /></label>
      <label>Localidad <input v-model="form.trabajadorExt.direccion.localidad" class="input" /></label>
      <label>C.P. / <em>CEP</em> <input v-model="form.trabajadorExt.direccion.cp" class="input" /></label>
      <label>País <input v-model="form.trabajadorExt.direccion.pais" class="input" /></label>
      <label>Nº teléfono <input v-model="form.trabajadorExt.direccion.telefono" class="input" /></label>
      <label class="md:col-span-2">Correo electrónico <input v-model="form.trabajadorExt.direccion.email" class="input" /></label>
    </div>
  </section>

  <!-- SECCIÓN 6.16 PRESTACIONES ────────────────────────────────────────── -->
  <section class="mt-8">
    <h4 class="bg-gray-200 font-bold p-1 text-sm">
      6.16 ¿Cobra otras prestaciones de otros regímenes de la seguridad social?
    </h4>

    <div class="flex items-center gap-8 my-2">
      <label><input type="radio" :value="false" v-model="form.trabajadorExt.cobraPrestaciones" /> No</label>
      <label><input type="radio" :value="true"  v-model="form.trabajadorExt.cobraPrestaciones" /> Sí</label>
    </div>

    <!-- Tabla de prestaciones -->
    <table class="w-full text-xs border">
      <thead class="bg-gray-100">
        <tr>
          <th class="border p-1 text-left">Tipo de prestación</th>
          <th class="border p-1 text-center">¿La cobra?</th>
          <th class="border p-1 text-left">Organismo pagador</th>
          <th class="border p-1 text-left">Cuantía mensual</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in form.trabajadorExt.prestaciones" :key="row.tipo">
          <td class="border p-1">{{ row.tipo }}</td>
          <td class="border p-1 text-center">
            <input type="checkbox" v-model="row.cobra" />
          </td>
          <td class="border p-1">
            <input v-model="row.organismo" class="input border-none p-0" :disabled="!row.cobra" />
          </td>
          <td class="border p-1">
            <input v-model="row.cuantia" class="input border-none p-0" :disabled="!row.cobra" />
          </td>
        </tr>
      </tbody>
    </table>
  </section>

  <!-- SECCIÓN 7 ──────────────────────────────────────────────────────────── -->
  <section class="mt-8">
    <h4 class="bg-gray-200 font-bold p-1 text-sm">
      7. ACUMPLIMENTAR ÚNICAMENTE EN EL SUPUESTO DE SOLICITUD DE PENSIÓN DE INCAPACIDAD
    </h4>
    <p class="italic text-xs mb-2">
      PARA SER CUMPRIDO SOMENTE NO CASO DA APLICAÇÃO DE PENSÃO DE DISCAPACIDADE
    </p>

    <p class="font-semibold mb-1">7.1 Origen de la incapacidad:</p>
    <div class="flex flex-col sm:flex-row sm:flex-wrap gap-x-10 gap-y-2">
      <label><input type="checkbox" v-model="form.incapacidad.origen.accidenteTrabajo" /> Accidente de trabajo</label>
      <label><input type="checkbox" v-model="form.incapacidad.origen.enfermedadProfesional" /> Enfermedad profesional</label>
      <label><input type="checkbox" v-model="form.incapacidad.origen.accidenteNoLaboral" /> Accidente no laboral</label>
      <label><input type="checkbox" v-model="form.incapacidad.origen.enfermedadComun" /> Enfermedad común</label>
    </div>
  </section>

  <!-- SECCIÓN 8 ──────────────────────────────────────────────────────────── -->
  <section class="mt-8">
    <h4 class="bg-gray-200 font-bold p-1 text-sm">
      8. PERÍODOS DE SEGURO, DE COTIZACIÓN O DE EMPLEO ALEGADOS POR EL SOLICITANTE
    </h4>

    <table class="w-full text-xs border mt-2">
      <thead class="bg-gray-100">
        <tr>
          <th class="border p-1">Desde</th>
          <th class="border p-1">Hasta</th>
          <th class="border p-1">Nombre de Empresa y domicilio</th>
          <th class="border p-1">País</th>
          <th class="border p-1">Régimen de contribución / actividad</th>
          <th class="border p-1 w-6"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, idx) in form.incapacidad.periodos" :key="idx">
          <td class="border p-1"><input type="date" v-model="row.desde" class="input border-none p-0" /></td>
          <td class="border p-1"><input type="date" v-model="row.hasta" class="input border-none p-0" /></td>
          <td class="border p-1"><input v-model="row.empresa" class="input border-none p-0" /></td>
          <td class="border p-1"><input v-model="row.pais" class="input border-none p-0" /></td>
          <td class="border p-1"><input v-model="row.regimen" class="input border-none p-0" /></td>
          <td class="border p-1 text-center">
            <button @click="removeFila(idx)" class="text-red-600 font-bold" title="Eliminar">–</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mt-2">
      <button @click="addFila" class="bg-green-600 text-white px-3 py-1 rounded text-xs">+ Añadir fila</button>
    </div>
  </section>


 <!-- SECCIÓN 9 – Datos del solicitante (solo pensión supervivencia) -->
  <section class="mt-10">
    <h4 class="bg-gray-200 font-bold p-1 text-sm">
      9. DATOS DEL SOLICITANTE (COMPLETAR EXCLUSIVAMENTE EN CASO DE PENSIONES POR SUPERVIVENCIA)
    </h4>
    <p class="italic text-xs mb-3">
      DADOS DO SOLICITANTE (COMPLETAR EXCLUSIVAMENTE EM CASO DE PENSÕES POR MORTE)
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <label>9.1 Apellido(s):
        <input v-model="form.solicitanteSup.apellido" class="input" />
      </label>
      <label>Apellidos de soltera:
        <input v-model="form.solicitanteSup.apellidoSoltera" class="input" />
      </label>

      <label class="md:col-span-2">Nombre(s):
        <input v-model="form.solicitanteSup.nombres" class="input" />
      </label>

      <label>9.2 Nombre(s) del padre:
        <input v-model="form.solicitanteSup.nombrePadre" class="input" />
      </label>
      <label>9.3 Nombre(s) de la madre:
        <input v-model="form.solicitanteSup.nombreMadre" class="input" />
      </label>

      <label>9.4 Fecha de nacimiento:
        <input type="date" v-model="form.solicitanteSup.fechaNacimiento" class="input" />
      </label>

      <!-- Sexo -->
      <div>
        9.5 Sexo:<br />
        <label class="inline-flex items-center gap-1">
          <input type="radio" value="M" v-model="form.solicitanteSup.sexo" /> Masculino
        </label>
        <label class="inline-flex items-center gap-1 ml-4">
          <input type="radio" value="F" v-model="form.solicitanteSup.sexo" /> Femenino
        </label>
      </div>

      <label>9.6 Lugar de nacimiento:
        <input v-model="form.solicitanteSup.lugarNacimiento" class="input" />
      </label>
      <label>9.7 País de nacimiento:
        <input v-model="form.solicitanteSup.paisNacimiento" class="input" />
      </label>

      <label>9.8 Nacionalidad:
        <input v-model="form.solicitanteSup.nacionalidad" class="input" />
      </label>
    </div>

    <!-- Estado civil -->
    <div class="mt-6">
      <p class="font-semibold mb-1">9.9 Estado civil:</p>
      <div class="flex flex-wrap gap-x-8 gap-y-2">
        <label><input type="radio" value="Soltero"     v-model="form.solicitanteSup.estadoCivil" /> Soltero</label>
        <label><input type="radio" value="Casado"      v-model="form.solicitanteSup.estadoCivil" /> Casado</label>
        <label><input type="radio" value="Viudo"       v-model="form.solicitanteSup.estadoCivil" /> Viudo</label>
        <label><input type="radio" value="Divorciado"  v-model="form.solicitanteSup.estadoCivil" /> Divorciado</label>
        <label><input type="radio" value="Separado"    v-model="form.solicitanteSup.estadoCivil" /> Separado</label>
        <label><input type="radio" value="Concubinato" v-model="form.solicitanteSup.estadoCivil" /> Concubinato</label>
        <label><input type="radio" value="UnionHecho"  v-model="form.solicitanteSup.estadoCivil" /> Unión de hecho</label>
        <label><input type="radio" value="Otros"       v-model="form.solicitanteSup.estadoCivil" /> Otros</label>
      </div>
    </div>
  </section>

 <!-- SECCIÓN 9.10-9.16 (complemento) ──────────────────────────────────── -->
  <section class="mt-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <label>9.10 Parentesco con el asegurado:
        <input v-model="form.solicitanteSup.parentesco" class="input" />
      </label>

      <label>9.11 Fecha de matrimonio:
        <input type="date" v-model="form.solicitanteSup.fechaMatrimonio" class="input" />
      </label>

      <label>9.12 Fecha de divorcio o separación:
        <input type="date" v-model="form.solicitanteSup.fechaDivorcio" class="input" />
      </label>

      <label>9.13 Fecha de nuevas nupcias:
        <input type="date" v-model="form.solicitanteSup.fechaNuevasNupcias" class="input" />
      </label>

      <label>9.14 Fecha de inscripción como pareja de hecho:
        <input type="date" v-model="form.solicitanteSup.fechaInscripcion" class="input" />
      </label>

      <label>9.15 Ingresos anuales últimos 12 meses:
        <input type="number" v-model="form.solicitanteSup.ingresosUlt12m" class="input" min="0" />
      </label>
    </div>

    <!-- Dirección del solicitante -->
    <h5 class="font-semibold mt-6 mb-2">9.16 Dirección</h5>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <label>Calle / Rua <input v-model="form.solicitanteSup.direccion.calle" class="input" /></label>
      <label>Nº / Piso / Depto <input v-model="form.solicitanteSup.direccion.numero" class="input" /></label>
      <label>Localidad <input v-model="form.solicitanteSup.direccion.localidad" class="input" /></label>
      <label>C.P. / CEP <input v-model="form.solicitanteSup.direccion.cp" class="input" /></label>
      <label>Provincia / Estado / Región <input v-model="form.solicitanteSup.direccion.region" class="input" /></label>
      <label>País <input v-model="form.solicitanteSup.direccion.pais" class="input" /></label>
      <label>Teléfono <input v-model="form.solicitanteSup.direccion.telefono" class="input" /></label>
      <label class="md:col-span-2">Correo electrónico <input v-model="form.solicitanteSup.direccion.email" class="input" /></label>
    </div>

    <!-- Otras informaciones -->
    <label class="block mt-6">9.17 Otras informaciones:
      <textarea v-model="form.solicitanteSup.otrasInfo" rows="3" class="input"></textarea>
    </label>
  </section>


 <section class="mt-6">
    <h3 class="font-bold text-lg mb-2">10. Datos relativos a los familiares del asegurado</h3>

    <div v-for="(fam, idx) in form.family" :key="idx" class="border p-4 mt-4 relative rounded">
      <h4 class="font-semibold mb-2">{{ idx + 1 }}º Familiar</h4>
      <button
        v-if="form.family.length > 1"
        class="absolute top-2 right-2 text-red-600"
        @click="eliminarFamiliar(idx)"
        title="Eliminar"
      >🗑</button>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <label>Apellido(s):
          <input v-model="fam.apellido" class="input" />
        </label>
        <label>Nombre(s):
          <input v-model="fam.nombres" class="input" />
        </label>

        <div>
          Sexo:
          <label class="ml-2"><input type="radio" value="M" v-model="fam.sexo" /> Hombre</label>
          <label class="ml-4"><input type="radio" value="F" v-model="fam.sexo" /> Mujer</label>
        </div>

        <label>Parentesco:
          <input v-model="fam.parentesco" class="input" />
        </label>

        <label>Fecha de nacimiento:
          <input type="date" v-model="fam.fechaNac" class="input" />
        </label>

        <div class="flex flex-col gap-2 md:col-span-2">
          <label><input type="checkbox" v-model="fam.incapacitado" /> ¿Está incapacitado para el trabajo?</label>
          <label><input type="checkbox" v-model="fam.trabaja" /> ¿Trabaja?</label>
          <label><input type="checkbox" v-model="fam.convive" /> ¿Convive con el solicitante?</label>
          <label><input type="checkbox" v-model="fam.solicitaPension" /> ¿Solicita pensión?</label>
        </div>

        <label>Ingresos anuales:
          <input type="number" min="0" v-model="fam.ingresos" class="input" />
        </label>

        <label class="md:col-span-2">Dirección:
          <input v-model="fam.direccion" class="input" />
        </label>
      </div>
    </div>

    <div class="mt-4 text-center">
      <button @click="agregarFamiliar" class="bg-blue-600 text-white px-4 py-2 rounded">+ Agregar familiar</button>
    </div>
  </section>


 <!-- SECCIÓN 11 -->

 <section class="mt-6 border p-4 rounded">
    <h3 class="font-bold text-lg mb-4">11. Datos del Apoderado o Representante Legal</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <label>11.1 Apellido(s):
        <input v-model="form.representante.apellido" class="input" />
      </label>
      <label>Nombre(s):
        <input v-model="form.representante.nombre" class="input" />
      </label>

      <div class="md:col-span-2">
        <label>
          <input type="radio" value="representante" v-model="form.representante.tipo" />
          Representante
        </label>
        <label class="ml-4">
          <input type="radio" value="apoderado" v-model="form.representante.tipo" />
          Apoderado
        </label>
      </div>

      <label class="md:col-span-2">Número de identidad:
        <input v-model="form.representante.numeroIdentidad" class="input" />
      </label>
    </div>

    <h4 class="font-semibold mt-6 mb-2">11.2 Dirección</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <label>Calle:
        <input v-model="form.representante.direccion.calle" class="input" />
      </label>
      <label>N° Piso / Depto:
        <input v-model="form.representante.direccion.piso" class="input" />
      </label>
      <label>Localidad:
        <input v-model="form.representante.direccion.localidad" class="input" />
      </label>
      <label>Código Postal (C.P / CEP):
        <input v-model="form.representante.direccion.cp" class="input" />
      </label>
      <label>Provincia / Estado:
        <input v-model="form.representante.direccion.provincia" class="input" />
      </label>
      <label>Departamento:
        <input v-model="form.representante.direccion.departamento" class="input" />
      </label>
      <label>Región:
        <input v-model="form.representante.direccion.region" class="input" />
      </label>
      <label>País:
        <input v-model="form.representante.direccion.pais" class="input" />
      </label>
      <label>Teléfono:
        <input v-model="form.representante.direccion.telefono" class="input" />
      </label>
      <label>Correo electrónico:
        <input v-model="form.representante.direccion.email" class="input" />
      </label>
    </div>
  </section>
   <!-- SECCIÓN 12 -->
    <section class="p-6 border rounded-lg bg-white space-y-6">
    <h2 class="text-xl font-semibold">12. FORMA DE PAGO DE LA PENSIÓN</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <label>
        País:
        <input v-model="form.formPago.pais" class="input" />
      </label>

      <label>
        Titular de la cuenta:
        <input v-model="form.formPago.titularCuenta" class="input" />
      </label>

      <div class="md:col-span-2">
        <span class="font-semibold">Forma de pago deseada:</span>
        <label class="ml-4">
          <input type="radio" value="cheque" v-model="form.formPago.formaPago" />
          Cheque nominal
        </label>
        <label class="ml-4">
          <input type="radio" value="transferencia" v-model="form.formPago.formaPago" />
          Transferencia bancaria
        </label>
      </div>

      <label>BIC / SWIFT:
        <input v-model="form.formPago.bic" class="input" />
      </label>

      <label>IBAN:
        <input v-model="form.formPago.iban" class="input" />
      </label>
    </div>

    <div class="space-y-2">
      <h3 class="font-semibold">CCC / CUENTA CORRIENTE DE AHORRO</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <label>N° de entidad:
          <input v-model="form.formPago.entidad" class="input" />
        </label>
        <label>N° de agencia:
          <input v-model="form.formPago.agencia" class="input" />
        </label>
        <label>Control:
          <input v-model="form.formPago.control" class="input" />
        </label>
        <label>N° de cuenta:
          <input v-model="form.formPago.nroCuenta" class="input" />
        </label>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <label>Nombre del banco:
        <input v-model="form.formPago.banco" class="input" />
      </label>
      <label>Dirección del banco:
        <input v-model="form.formPago.direccion" class="input" />
      </label>
    </div>
  </section>
   <!-- SECCIÓN 13 -->

  <section class="p-6 border rounded-lg bg-white space-y-6">
    <h2 class="text-lg font-semibold">
      13. APLAZAMIENTO DEL DERECHO A PENSIÓN DE VEJEZ O JUBILACIÓN
      <small class="text-sm font-normal">(Artículo 16.5 del Acuerdo de Aplicación)</small>
    </h2>

    <div class="space-y-4">
      <div class="flex items-center space-x-4">
        <label class="font-medium">
          13.1 ¿Aplaza la solicitud en otro(s) Estado(s) Parte?
        </label>
        <label>
          <input
            type="radio"
            value="si"
            v-model="form.form13.aplazaSolicitud"
            class="mr-1"
          />
          Sí / Sim
        </label>
        <label>
          <input
            type="radio"
            value="no"
            v-model="form.form13.aplazaSolicitud"
            class="mr-1"
          />
          No / Não
        </label>
      </div>

      <div v-if="form.form13.aplazaSolicitud === 'si'">
        <label class="block font-medium">
          13.2 En caso afirmativo, indique en cuál o cuáles
        </label>
        <input
          v-model="form.form13.estadosParte"
          class="w-full mt-1 p-2 border border-gray-300 rounded-md"
          placeholder="Especifique los Estados Parte"
        />
      </div>
    </div>
  </section>
<!-- SECCIÓN 14 -->

  <section class="p-4 border rounded bg-white space-y-4">
    <h2 class="text-lg font-semibold">
      14. CERTIFICACIÓN DE PERÍODOS DE SEGURO, DE COTIZACIÓN O DE EMPLEO
    </h2>

    <table class="w-full text-sm border border-collapse table-fixed">
      <thead class="bg-gray-100">
        <tr>
          <th class="border px-2 py-1">Desde<br /><em>Inicio</em></th>
          <th class="border px-2 py-1">Hasta<br /><em>Até</em></th>
          <th class="border px-2 py-1">Régimen de contribución<br /><em>Regime</em></th>
          <th class="border px-2 py-1">Período voluntario / obligatorio<br /><em>Periodo assimilado</em></th>
          <th class="border px-2 py-1">Observaciones<br /><em>Observações</em></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in form.periodos" :key="index">
          <td class="border px-1">
            <input type="text" v-model="item.desde" class="w-full p-1 border rounded" placeholder="dd/mm/aaaa" />
          </td>
          <td class="border px-1">
            <input type="text" v-model="item.hasta" class="w-full p-1 border rounded" placeholder="dd/mm/aaaa" />
          </td>
          <td class="border px-1">
            <input type="text" v-model="item.regimen" class="w-full p-1 border rounded" />
          </td>
          <td class="border px-1">
            <input type="text" v-model="item.periodo" class="w-full p-1 border rounded" />
          </td>
          <td class="border px-1">
            <input type="text" v-model="item.observaciones" class="w-full p-1 border rounded" />
          </td>
        </tr>
      </tbody>
    </table>

    <button
      @click="agregarFila"
      class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
    >
      Añadir fila
    </button>

    <div class="pt-4 text-sm font-medium">
      TOTAL COTIZADOS:
      Años/Ano ............ Meses/Mês ............ Días/Día ............
    </div>
  </section>


  <section class="p-4 border rounded bg-white space-y-4">
    <h2 class="text-lg font-semibold">14.2 - Totalización y Beneficio</h2>

    <div class="flex items-center space-x-3">
      <input type="checkbox" v-model="form.datos.sinPeriodos" id="sinPeriodos" />
      <label for="sinPeriodos">
        No tiene periodos de seguro computables para totalización
        <em class="text-gray-500 ml-1">/ Não tem períodos de seguros computáveis para totalização</em>
      </label>
    </div>

    <div>
      <label class="block mb-2">
        14.3 La persona asegurada que acredite un período de seguro inferior a un año
        <em class="block text-gray-500">Pessoa segurada que acredita um período de seguro inferior a um ano</em>
      </label>

      <div class="flex items-center space-x-4 ml-6">
        <label class="inline-flex items-center">
          <input
            type="radio"
            v-model="form.datos.podraBeneficiarse"
            :value="true"
            class="mr-1"
          />
          Podrá beneficiarse
          <em class="text-gray-500 ml-1">/ você pode beneficiar</em>
        </label>

        <label class="inline-flex items-center">
          <input
            type="radio"
            v-model="form.datos.podraBeneficiarse"
            :value="false"
            class="mr-1"
          />
          No podrá beneficiarse
          <em class="text-gray-500 ml-1">/ você não pode beneficiar</em>
        </label>
      </div>

      <p class="mt-3 ml-6 text-sm text-gray-600 italic">
        De una pensión en virtud de la legislación nacional (artículo 14 del Convenio)
        <br />
        De uma pensão à luz da legislação nacional (artigo 14 do Convênio)
      </p>
    </div>
  </section>




    <!-- Botón -->
    <div class="mt-6 text-center">
      <button @click="submitForm" class="bg-blue-600 text-white px-6 py-2 rounded">
        Guardar formulario
      </button>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'

const form = reactive({
  organismo: {
    nombre: 'GESTORA PUBLICA DE LA SEGURIDAD SOCIAL DE LARGO PLAZO',
    direccion: 'C/ REYES ORTIZ N 73 ESQ FEDERICO SUAZO, EDIFICIO TORRES GUNDLACH, TORRE ESTE',
    localidad: 'LA PAZ',
    pais: 'BOLIVIA',
    referencia: 'CMISS-03-N° 0012',
  },
  destino: {
    pais: 'BOLIVIA',
    organismo: 'AUTORIDAD DE FISCALIZACION Y CONTROL DE PENSIONES Y SEGUROS',
    direccion: 'C/ REYES ORTIZ N 73 ESQ FEDERICO SUAZO, EDIFICIO TORRES GUNDLACH, TORRE ESTE',
  },  // 3. Solicitud
  solicitud: {
    tipos: {
      jubilacion: false,
      incapacidad: false,
      supervivencia: true,   // marcada en tu imagen
      certificacion: false
    },
    fecha: '2024-11-26'
  },
 // 4. Datos del trabajador asegurado
  trabajador: {
    apellido: 'RUIZ',
    apellidoSoltera: 'FELIX',
    nombres: 'ALEJANDRO',
    fechaNacimiento: '1955-08-06',
    docIdentidad: '13010822',
    numSeguridadSocial: 'INSS ESPAÑA',
    docIdentidadDest: ''
  },
    /* 5. Soliciante (solo pensiones de supervivencia) */
  solicitante: {
    apellido:        'ZARATE',
    apellidoSoltera: 'ROJAS',
    nombres:         'JUDY AYLIN',
    fechaNacimiento: '1967-10-23',
    docIdentidad:    '1142525',
    docIdentidadDest: ''
  },
    /* 6. Trabajador asegurado (se amplía respecto a la sección 4) */
  trabajadorExt: {
        apellido:        'RUIZ',
        apellidoSoltera: 'FELIX',
        nombres:         'ALEJANDRO',
        nombrePadre:     'FELIX TORRES ALEJANDRO',
        nombreMadre:     'RUIZ LUNA AMALIA',
        fechaNacimiento: '1955-08-06',
        sexo:            'M',          // 'M' | 'F'
        lugarNacimiento: 'BARCELONA VILANOVA I LA GELTRÚ',
        paisNacimiento:  'ESPAÑA',
        nacionalidad:    'ESPAÑOLA – BOLIVIANA POR MATRIMONIO',
        profesion:       'ELECTRICISTA',
        sigueCotizando:  true,     // true = “El asegurado sigue trabajando y/o cotizando”
        fechaCese:       '',       // sólo si no sigue cotizando
        estadoCivil:     'Casado', // Soltero | Casado | Viudo | Divorciado | Separado | UnionHecho | Concubinato
        fechaFallec:     '2024-09-27',
        paisFallec:      'BOLIVIA',
        causaFallec:     'EnfermedadComun',
            /* 6.15 Dirección */
    direccion: {
      calle:        'RUA EUROPA',
      numero:       'S/N',
      localidad:    'CHUQUISACA-MONTEAGUDO',
      cp:           '',
      pais:         'BOLIVIA',
      telefono:     '72990256',
      email:        'juayzaro67@gmail.com'
    }, // AccidentTrabajo | EnfermedadProfesional | AccidentNoLaboral | EnfermedadComun
    /* 6.16 Otras prestaciones */
    cobraPrestaciones: true,      // Sí / No
    prestaciones: [
      { tipo: 'Vejez',              cobra: true,  organismo: 'TESORERIA GENERAL DE LA SEGURIDAD SOCIAL', cuantia: '' },
      { tipo: 'Incapacidad',        cobra: false, organismo: '', cuantia: '' },
      { tipo: 'Viudedad',           cobra: false, organismo: '', cuantia: '' },
      { tipo: 'Orfandad',           cobra: false, organismo: '', cuantia: '' },
      { tipo: 'Desempleo',          cobra: false, organismo: '', cuantia: '' },
      { tipo: 'Otra',               cobra: false, organismo: '', cuantia: '' }
    ]
  },
    incapacidad: {
    origen: {
      accidenteTrabajo:        false,
      enfermedadProfesional:   false,
      accidenteNoLaboral:      false,
      enfermedadComun:         false
    },
    /* Sección 8: tabla dinámica */
    periodos: [
      { desde: '', hasta: '', empresa: '', pais: '', regimen: '' }
    ]
  },


  solicitanteSup: {
    apellido:        'ZARATE',
    apellidoSoltera: 'ROJAS',
    nombres:         'JUDY AYLIN',
    nombrePadre:     'ZARATE  MARCELINO',
    nombreMadre:     'ROJAS IGNACIA',
    fechaNacimiento: '1967-10-23',
    sexo:            'F',                // 'M' | 'F'
    lugarNacimiento: 'MONTEAGUDO',
    paisNacimiento:  'BOLIVIA',
    nacionalidad:    'BOLIVIANA',
    estadoCivil:     'Casado',         // Soltero | Casado | Viudo | Divorciado | Separado | Concubinato | UnionHecho | Otros
       parentesco:      'CÓNYUGE',
    fechaMatrimonio: '2008-07-14',
    fechaDivorcio:   '',
    fechaNuevasNupcias: '',
    fechaInscripcion: '2008-07-14',
    ingresosUlt12m:  24000,

    direccion: {
      calle:     'RUA EUROPA',
      numero:    'S/N',
      localidad: 'MONTEAGUDO',
      cp:        '',
      region:    'CHUQUISACA',
      pais:      'BOLIVIA',
      telefono:  '72990256',
      email:     'juayzaro67@gmail.com'
    },

    otrasInfo: ''
  },

   family: [
    {                      // 1º familiar precargado
      apellido: 'ZARATE ROJAS',
      nombres:  'JUDY AYLIN',
      sexo:     'F',              // 'M' | 'F'
      parentesco: 'CÓNYUGE',
      fechaNac: '1967-10-23',
      incapacitado: false,
      trabaja: true,
      ingresos: 24000,
      convive: true,
      solicitaPension: true,
      direccion: 'Z/ EL CHURO ZAPALLAR, CALLE EUROPA S/N'
    }
  ], 
representante: {
  apellido: '',
  nombre: '',
  tipo: '', // 'representante' o 'apoderado'
  numeroIdentidad: '',
  direccion: {
    calle: '',
    piso: '',
    localidad: '',
    cp: '',
    provincia: '',
    departamento: '',
    region: '',
    pais: '',
    telefono: '',
    email: ''
  } 
}, 
formPago :{
  pais: 'BOLIVIA',
  titularCuenta: '',
  formaPago: 'transferencia',
  bic: '',
  iban: '',
  entidad: '',
  agencia: '',
  control: '',
  nroCuenta: '',
  banco: '',
  direccion: ''
},

 form13 :{
  aplazaSolicitud: 'no',
  estadosParte: ''
},
periodos:
  [{
    desde: '',
    hasta: '',
    regimen: '',
    periodo: '',
    observaciones: ''
  }],

datos :{
  sinPeriodos: false,
  periodoInferior: false,
  podraBeneficiarse: null // true, false o null
},



})

function agregarFila() {
  form.periodos.push({
    desde: '',
    hasta: '',
    regimen: '',
    periodo: '',
    observaciones: ''
  })
}

function submitForm() {
  console.log('Datos del formulario:', form)
  alert('Formulario guardado (ver consola para datos)')
}

function addFila() {
  form.incapacidad.periodos.push({ desde: '', hasta: '', empresa: '', pais: '', regimen: '' })
}

function removeFila(idx) {
  if (form.incapacidad.periodos.length > 1)
    form.incapacidad.periodos.splice(idx, 1)
}

function addFamiliar() {
  form.family.push({
    apellido: '', nombres: '', sexo: '', parentesco: '',
    fechaNac: '', incapacitado: false, trabaja: false,
    ingresos: '', convive: false, solicitaPension: false, direccion: ''
  })
}
function removeFamiliar(i) {
  if (form.family.length > 1) form.family.splice(i, 1)
}

function agregarFamiliar() {
  form.family.push({
    apellido: '',
    nombres: '',
    sexo: '',
    parentesco: '',
    fechaNac: '',
    incapacitado: false,
    trabaja: false,
    ingresos: null,
    convive: false,
    solicitaPension: false,
    direccion: ''
  })
}

function eliminarFamiliar(index) {
  if (confirm('¿Eliminar este familiar?')) {
    form.family.splice(index, 1)
  }
}
</script>

<style scoped>
.input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 0.375rem;
}
</style>

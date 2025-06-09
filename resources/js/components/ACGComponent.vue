<template>
    <div>
    <input type="file" ref="fileInput" @change="onFileChange" />
    <button @click="uploadFile" class="btn btn-primary">
        <i class="fas fa-eye"></i> Verificar
    </button>

      <div v-if="fileExists">
        <h4><span class="badge badge-success">El archivo existe. Este es su contenido:</span></h4>
        <pre>{{ existingContent }}</pre>
        <button @click="confirmOverwrite" class="btn btn-warning">Sobrescribir</button>
        <button @click="copyToClipboard" id="copyButton" class="swal2-confirm swal2-styled" style="margin-left: 10px; padding: 5px;">
            <i class="fas fa-copy"></i> Copiar
        </button>
      </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      file: null,
      fileExists: false,
      existingContent: "",
      tipo: "",
      path: "",
    };
  },
  methods: {
    onFileChange(event) {
      this.file = event.target.files[0];
      this.fileExists = false;
    },
    async uploadFile() {
      if (!this.file) {
        alert("Por favor, seleccione un archivo.");
        return;
      }

      let formData = new FormData();
      formData.append("file", this.file);

      try {
        let response = await fetch("/api/upload-controller", {
          method: "POST",
          body: formData,
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
          },
        });

        let result = await response.json();
        this.tipo = result.tipo;
        this.path = result.path;

        if (response.ok) {
          if (result.content) {
            // Si el archivo ya existe en el servidor
            this.fileExists = true;
            this.existingContent = result.content;
            console.log("pasando igual nomas ");
          } else {
            // No existe
            alert("No existe el archivo en el servidor.");
          }
        } else {
          alert(result.message || "Error al verificar el archivo.");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Error al verificar el archivo.");
      }
    },
    confirmOverwrite() {
      if (confirm("¿Está seguro de que desea sobrescribir el archivo?")) {
        this.overwriteFile(this.tipo,this.path);
      }
    },
    async confirmUpload() {
      if (!this.file) {
        alert("Por favor, seleccione un archivo.");
        return;
      }

      let formData = new FormData();
      formData.append("file", this.file);

      try {
        let response = await fetch("/api/upload-controller", {
          method: "POST",
          body: formData,
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
          },
        });

        let result = await response.json();

        console.log("El resultado >> ", result);

        if (response.ok) {
          alert(result.message || "Archivo subido exitosamente.");
          this.resetFileInput();
        } else {
          alert(result.message || "Error al subir el archivo.");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Error al subir el archivo.");
      }
    },
    async overwriteFile(tipo, path) {
      const code = prompt("Por favor, ingrese el código de autorización:");
      if (code !== "3quipoAdm") {
        alert("Código incorrecto. No se puede proceder.");
        return;
      }

      if (!this.file) {
        alert("Por favor, seleccione un archivo.");
        return;
      }

      let formData = new FormData();
      formData.append("file", this.file);
      formData.append("tipo", tipo);
      formData.append("path", path);

      try {
        let response = await fetch("/api/overwrite-controller", {
          method: "POST",
          body: formData,
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
          },
        });

        let result = await response.json();

        if (response.ok) {
          alert(result.message || "Archivo sobrescrito exitosamente.");
          this.fileExists = false;
          this.resetFileInput();
        } else {
          alert(result.message || "Error al sobrescribir el archivo.");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Error al sobrescribir el archivo.");
      }
    },
    resetFileInput() {
      this.file = null;
      this.$refs.fileInput.value = null;
    },
    copyToClipboard() {
      navigator.clipboard.writeText(this.existingContent).then(() => {
        alert("Contenido copiado al portapapeles.");
      }).catch(err => {
        console.error("Error al copiar al portapapeles:", err);
        alert("No se pudo copiar el contenido.");
      });
    },
  },
};
</script>

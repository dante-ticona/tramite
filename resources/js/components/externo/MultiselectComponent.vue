<template>
  <div>
    {{ componentLabel }} 
    <label v-show="required "
          style="color:rgb(228, 31, 31);"> (*)</label>
    <multiselect 
      :id="domId"
      v-model="localValue" 
      tag-placeholder="Seleccionar una opcion" 
      placeholder="Buscar o adicionar opcion" 
      label="frm_etiqueta" 
      track-by="frm_value" 
      :options="options" 
      :multiple="true" 
      :taggable="true" 
      :closeOnSelect="false"
      :disabled="disabled"
      :componentLabel="componenetLabel"
    />
    <input type="hidden" :id="domId + '_HIDDEN'" :value="safeStringify()" />
    <div v-if="required && validation === null">
      <input  type="text" :required="true" v-model="validation" 
        style="background-color: #BC1707; border: 1px solid #BC1707; width: 0.5px; height: 0.5px; cursor:not-allowed; pointer-events:none;" />
      <label style="color: #BC1707;">Este campo es obligatorio.</label>
    </div>
  </div>
</template>
<script>
import Multiselect from 'vue-multiselect';

export default {
  props: ["value", "options","required","disabled","domId","componentLabel"],
  components: {
    Multiselect
  },
  data() {
    return {
      // Create a local copy of the value prop to manage changes locally
      localValue: this.value,
    };
  },
  created() {
    __GestoraRenderEventBus.$on(this.domId, (newOptions) => {
      this.options = newOptions;
    });
    __GestoraRenderEventBus.$on(this.domId + '_VALUE',(newValue)=>{
      this.localValue = newValue;
    });
    __GestoraRenderEventBus.$on(this.domId + '_REQUIRED',(newValue)=>{
      this.required = newValue;
    });
  },
  computed: {
      validation() {
        return this.localValue === null || this.localvalue === '' || (this.localValue && this.localValue.length === 0) ? null: 'some-value'; 
      }
    },
  watch: {
    // Watch for changes to the localValue and emit an 'input' event to update the parent
    localValue(newValue) {
      this.$emit("input", newValue);
    },
    // Watch for changes in the parent's value prop to sync it with localValue
    value(newValue) {
      this.localValue = newValue;
    }
  },
  methods: {
    addTag(newTag) {
      const tag = {
        frm_etiqueta: newTag,
        frm_value: newTag.substring(0, 2) + Math.floor(Math.random() * 10000000)
      };
      
      this.options.push(tag);
      this.localValue.push(tag); // Update the local value

      // Emit the updated value to the parent to ensure the parent is in sync
      this.$emit("input", this.localValue);
    },
  safeStringify() {
  const obj = this.localValue;
  const seen = new WeakSet();
  return JSON.stringify(obj, (key, value) => {
    if (typeof value === "object" && value !== null) {
      if (seen.has(value)) {
        return; // Omit circular references
      }
      seen.add(value);
    }
    return value;
  });
}

  }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
  <div class="editDialog">
    <div class="dialogHeader p-2 flex items-center justify-between bg-gray-100">
      <h2>Bearbeiten</h2>

      <button class="close" @click="closeEditDialog">
        <img src="../../assets/close.svg" width="20" alt="schließen" />
      </button>
    </div>
    <div class="dialogBody">
      <component :is="component.type" :component="component"></component>
    </div>
    <div class="dialogActions">
      <button class="btn btn-secondary" @click="closeEditDialog">
        Abbrechen
      </button>
      <button class="btn bg-blue-500 px-4 py-2 text-white rounded ml-4" @click="closeEditDialog">
        Speichern
      </button>
    </div>
  </div>
</template>

<script>
import text from "./EditDrawerComponents/textComponent.vue";
import headline from "./EditDrawerComponents/headlineComponent.vue";
import button from "./EditDrawerComponents/buttonComponent.vue";
import image from "./EditDrawerComponents/imageComponent.vue";
export default {
  name: "editDialog",
  props: {
    component: {
      type: Object,
      required: true,
    },
  },
  components: {
    text,
    headline,
    button,
    image,
  },
  data() {
    return {
      component: this.component,
    };
  },
  methods: {
    closeEditDialog() {
      this.$emit("closeEditDialog");
    },
  },
};
</script>

<style>
.editDialog {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 500px;
  height: 30vh;
  background-color: white;
  border: 2px solid #e2e8f0;
  border-radius: 0.5rem;
  z-index: 100;
}

.dialogActions {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: flex-end;
  padding: 20px;
}

.dialogBody {
  padding: 20px;
  height: calc(100% - 100px);
  overflow-y: scroll;
}
</style>
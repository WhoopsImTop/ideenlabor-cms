<template>
  <div>
    <div class="useablecompontentRow flex items-center">
      <div
        v-for="(component, index) in useableComponents"
        :key="component.type"
        :id="index"
        class="useable-component mx-1 px-4 py-2 border-2 border-gray-200 rounded bg-gray-50"
        draggable="true"
      >
        <h3>{{ component.label }}</h3>
      </div>
    </div>
    <component
      v-for="(component, index) in pageContent.components"
      :key="index"
      :is="component.type"
      :component="component"
      :index="index"
      @removeRow="removeRow"
    ></component>
    <button class="py-1 px-2 bg-gray-200 rounded" @click="addRow">
      Reihe hinzufügen
    </button>
  </div>
</template>

<script>
import useableComponents from "../template/components.json";
import row from "./builderPlugins/rowPlugin.vue";
export default {
  data() {
    return {
      useableComponents: useableComponents,
      pageContent: {
        components: [
          {
            type: "row",
            label: "Reihe",
            components: []
          },
        ],
      },
    };
  },
  components: {
    row,
  },
  methods: {
    removeRow(index) {
      this.pageContent.components.splice(index, 1);
      //if the last row is removed, add a new one
      if (this.pageContent.components.length === 0) {
        this.addRow();
      }
    },
    addRow() {
      this.pageContent.components.push({
        type: "row",
        label: "Reihe",
        layout: "col",
      });
    },
  },

  mounted() {
    //handle drag and drop
    const useableComponents = document.querySelectorAll(".useable-component");

    //drag and drop for useable components
    useableComponents.forEach((useableComponent) => {
      useableComponent.addEventListener("dragstart", (e) => {
        useableComponent.classList.add("dragging");
      });
      useableComponent.addEventListener("dragend", (e) => {
        useableComponent.classList.remove("dragging");
      });
    });
  },
};
</script>

<style>
</style>
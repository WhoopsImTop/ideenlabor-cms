<template>
  <div>
    <component-edit-drawer
      v-if="ComponentDrawer"
      :component="currentComponentToEdit"
      @closeEditDialog="ComponentDrawer = false"
    ></component-edit-drawer>
    <componentsDrawer
      v-if="showComponentsDrawer"
      @closeComponentDrawer="unsetComponentsDrawer"
      @addComponentToRow="addComponentFromDrawer"
    ></componentsDrawer>
    <div class="component-rows">
      <component
        v-for="(component, index) in pageContent.components"
        :key="index"
        :is="component.type"
        :component="component"
        :index="index"
        class="component"
        draggable="true"
        @removeRow="removeRow"
        @triggerComponentsDrawer="setComponentsDrawer"
        @triggerComponentEdit="showComponentDrawer"
        @triggerComponentDelete="removeComponent"
        @moveRowUp="moveRowUp"
        @moveRowDown="moveRowDown"
      ></component>
    </div>
    <button class="py-1 px-2 bg-gray-200 rounded" @click="addRow">
      Reihe hinzufügen
    </button>
  </div>
</template>

<script>
import useableComponents from "../template/components.json";
import row from "./builderPlugins/rowPlugin.vue";
import componentsDrawer from "./builderPlugins/componentsDrawer.vue";
import componentEditDrawer from "./builderPlugins/componentEditDrawer.vue";
export default {
  props: {
    pageContent: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      useableComponents: useableComponents,
      showComponentsDrawer: false,
      rowIndex: 0,
      componentIndex: 0,
      ComponentDrawer: false,
      currentComponentToEdit: null,
      pageContent: this.pageContent,
    };
  },
  components: {
    row,
    componentsDrawer,
    componentEditDrawer,
  },
  methods: {
    moveRowUp(component) {
      if (component > 0) {
        this.pageContent.components.splice(
          component - 1,
          0,
          this.pageContent.components.splice(component, 1)[0]
        );
      }
    },
    moveRowDown(component) {
      if (component < this.pageContent.components.length - 1) {
        this.pageContent.components.splice(
          component + 1,
          0,
          this.pageContent.components.splice(component, 1)[0]
        );
      }
    },
    removeComponent(component) {
      this.pageContent.components[this.rowIndex].component[
        this.componentIndex
      ].components.splice(
        this.pageContent.components[this.rowIndex].component[
          this.componentIndex
        ].components.indexOf(component),
        1
      );
    },
    setComponentsDrawer(componentIndex, index) {
      this.componentIndex = componentIndex;
      this.rowIndex = index;
      this.showComponentsDrawer = true;
    },
    unsetComponentsDrawer() {
      this.rowIndex = 0;
      this.componentIndex = 0;
      this.currentComponentToEdit = null;
      this.showComponentsDrawer = false;
    },
    addComponentFromDrawer(component) {
      this.pageContent.components[this.rowIndex].component[
        this.componentIndex
      ].components.push(component);
      this.unsetComponentsDrawer();
      this.showComponentDrawer(component);
    },
    showComponentDrawer(component) {
      this.ComponentDrawer = true;
      console.log(component);
      this.currentComponentToEdit = component;
    },
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
        component: [
          {
            type: "col",
            label: "Spalte",
            components: [],
          },
        ],
      });
    },
  },

  mounted() {
    
  },
};
</script>

<style>
</style>
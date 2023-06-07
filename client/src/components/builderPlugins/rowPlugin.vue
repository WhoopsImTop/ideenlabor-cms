<template>
  <div class="builder-element">
    <div class="builder-element-heading">
      <h3>{{ component.label }}</h3>
      <div class="layout-options">
        <select v-model="rowLayout">
          <option value="col">1 Spalte</option>
          <option value="col col">2 Spalten</option>
          <option value="col col col">3 Spalten</option>
          <option value="col col col col">4 Spalten</option>
        </select>
      </div>
      <div class="heading-actions">
        <button @click="removeRow()">
          <img width="20" src="../../assets/delete.svg" alt="Preview" />
        </button>
      </div>
    </div>
    <div class="builder-element-drapable-zone" :style="generatedLayout">
      <div
        class="dropable-column"
        v-for="col in layoutCoulmns"
        :key="col"
      ></div>
    </div>
  </div>
</template>

<script>
import useableComponents from "../../template/components.json";
export default {
  name: "row",
  props: {
    component: {
      type: Object,
      required: true,
    },
    index: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      component: this.component,
      components: [],
      rowLayout: this.component.layout || "col",
      useableComponents: useableComponents,
    };
  },

  computed: {
    generatedLayout() {
      //return grid-template-areas based on rowLayout as string
      return {
        gridTemplateAreas: `"${this.rowLayout}"`,
      };
    },
    layoutCoulmns() {
      //return number of columns based on rowLayout as string
      return this.rowLayout.split(" ").length;
    },
  },

  watch: {
    rowLayout() {
      //update the layout of the row
      this.handleDropableZones();
    },
  },

  methods: {
    getData() {
      return this.component;
    },
    removeRow() {
      this.$emit("removeRow", this.index);
    },
    handleDropableZones() {
      console.log("handleDropableZones");
      setTimeout(() => {
        const columns = document.querySelectorAll(".dropable-column");

        //drag and drop for columns
        columns.forEach((column) => {
          column.addEventListener("dragover", (e) => {
            e.preventDefault();
            //get id from dragged component
            const draggedComponentId =
              document.querySelector(".dragging").id;
            //find element by index in useableComponents
            const draggedComponent = this.useableComponents[draggedComponentId];
            console.log(draggedComponent);
            //create a new element
            const newElement = document.createElement("div");
            newElement.innerText = draggedComponent.label;
            //add the new element to the column
            column.appendChild(newElement);

            this.components.push({
              type: draggedComponent.type,
              label: draggedComponent.label,
              component: draggedComponent,
            });
          });
          column.addEventListener("dragleave", (e) => {
            e.preventDefault();
            //remove the dragged component from the column
            column.removeChild(document.querySelector(".dragging"));
          });
        });
      }, 200);
    },
  },
};
</script>

<style>
</style>
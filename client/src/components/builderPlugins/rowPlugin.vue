<template>
  <div class="builder-element">
    <div class="builder-element-heading">
      <h3>{{ component.label }}</h3>
      <select v-model="cols" @change="updateCols()">
        <option>Spaltenanzahl ändern</option>
        <option v-for="n in 6" :key="n" :value="n">{{ n }} Spalten</option>
      </select>
      <div class="heading-actions">
        <button @click="removeRow()">
          <img width="20" src="../../assets/delete.svg" alt="Preview" />
        </button>
      </div>
    </div>
    <div class="builder-element-drapable-zone" :style="generatedLayout">
      <div
        class="dropable-column"
        v-for="(col, i) in component.component"
        :key="col"
      >
        <div
          class="component flex align-center justify-between"
          v-for="(component, index) in col.components"
          v-show="component.label"
          :key="index"
        >
          {{ component.label }}
          <div class="editBtn" @click="triggerComponentEdit(component)">
            <img src="../../assets/edit.svg" width="20" alt="edit" />
          </div>
        </div>
        <div class="addBtn" @click="triggerComponentsDrawer(i, index)">
          <img src="../../assets/add.svg" width="25" alt="add" />
        </div>
      </div>
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
      useableComponents: useableComponents,
      index: this.index,
      componentIndex: null,
      cols: 0,
    };
  },

  computed: {
    generatedLayout() {
      //return grid-template-areas based on rowLayout as string
      //return number of columns based on rowLayout as string
      let cols = "";
      for (let i = 0; i < this.component.component.length; i++) {
        cols += "col ";
      }
      return {
        gridTemplateAreas: `"${cols}"`,
      };
    },
  },

  methods: {
    updateCols() {
      //update rowLayout based on number of columns
      let newLayout = [];
      for (let i = 0; i < this.cols; i++) {
        newLayout.push({
          type: "col",
          label: "Spalte",
          components: [].concat(this.component.component[i]?.components || []),
        });
      }
      this.component.component = newLayout;
    },
    getData() {
      return this.component;
    },
    removeRow() {
      this.$emit("removeRow", this.index);
    },
    triggerComponentsDrawer(componentIndex, index) {
      this.$emit("triggerComponentsDrawer", componentIndex, index);
    },
    triggerComponentEdit(component) {
      this.$emit("triggerComponentEdit", component);
    },
  },
};
</script>

<style>
.addBtn {
  background-color: #000;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.2);
  margin: 10px auto 10px;
}
</style>
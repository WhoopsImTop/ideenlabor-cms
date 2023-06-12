<template>
  <div
    class="componentDialog border-2 border-gray-100 rounded-md"
    draggable="true"
  >
    <div
      class="componentDialog__header p-2 flex items-center justify-between bg-gray-100"
    >
      <h2>Komponenten</h2>
      <button class="close" @click="closeComponentDrawer()">
        <img src="../../assets/close.svg" width="20" alt="schließen" />
      </button>
    </div>
    <div class="componentDialog__body p-2">
      <div class="useable-components">
        <div
          class="useable-component"
          v-for="(component, index) in useableComponents"
          :key="index"
          draggable="true"
          :data-component="component"
          @click="addComponentToRow(component)"
        >
          <div class="useable-component__icon">
            <i :class="component.icon"></i>
          </div>
          <div class="useable-component__label">{{ component.label }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useableComponents from "../../template/components.json";
export default {
  data() {
    return {
      useableComponents: useableComponents,
    };
  },
  methods: {
    closeComponentDrawer() {
      this.$emit("closeComponentDrawer");
    },
    addComponentToRow(component) {
      this.$emit("addComponentToRow", component);
    },
  },
  mounted() {
    document
      .querySelector(".componentDialog")
      .addEventListener("dragstart", (e) => {
        this.$el.classList.add("dragging");
        this.$el.addEventListener("drag", (e) => {
          e.preventDefault();
          //set top and left position of the dialog
          //to the position of the mouse cursor
          //center the dialog to the cursor
          this.$el.style.top = e.clientY - this.$el.offsetHeight / 2 + "px";
          this.$el.style.left = e.clientX - this.$el.offsetWidth * 1.5 + "px";
        });

        this.$el.addEventListener("dragend", (e) => {
          e.preventDefault();
          //set top and left position of the dialog
          //to the position of the mouse cursor
          this.$el.style.top = e.clientY - this.$el.offsetHeight / 2 + "px";
          this.$el.style.left = e.clientX - this.$el.offsetWidth * 1.5 + "px";
          this.$el.classList.remove("dragging");
        });
      });
  },
};
</script>

<style>
.componentDialog {
  position: fixed;
  top: 300px;
  width: 300px;
  background-color: #fff;
  z-index: 100;
  transform: translateX(100%);
  transition: transform 0.3s ease-in-out;
}

.useable-components {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  grid-gap: 5px;
}

.useable-component {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  cursor: pointer;
}

.dragging {
  opacity: 0.5;
  cursor: grabbing;
}

.useable-component:hover {
  background-color: #f8f8f8;
}
</style>
<template>
  <div>
    <textarea class="w-full border-solid border-2 border-gray-200 rounded px-3 py-2 h-32" v-model="component.content"></textarea>
  </div>
</template>

<script>
export default {
  props: {
    component: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      component: this.component,
    };
  },
  methods: {
    getData() {
      return this.component;
    },
  },
};
</script>

<style>
</style>
<template>
  <div>
    <label
      >Button Text<input
        type="text"
        v-model="component.content.text"
        class="w-full border-solid border-2 border-gray-200 rounded px-3 py-2"
    /></label>
    <label
      >Button Link<input
        type="text"
        v-model="component.content.link"
        placeholder="https://"
        class="w-full border-solid border-2 border-gray-200 rounded px-3 py-2"
    /></label>
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
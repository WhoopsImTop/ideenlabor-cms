<template>
  <div class="grid grid-cols-6 gap-4 h-screen">
    <sidebar></sidebar>
    <div class="bg-gray-100 col-span-5 rounded-2xl p-8">
      <slot></slot>
    </div>
  </div>
</template>

<script>
import sidebar from "./../components/layout/sidebar.vue";
export default {
  components: {
    sidebar,
  },
};
</script>

<style></style>

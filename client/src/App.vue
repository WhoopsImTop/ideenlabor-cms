<script setup>
import sidebar from "./components/layout/sidebar.vue";
</script>

<template>
  <div class="grid grid-cols-6 gap-4 h-screen">
    <sidebar></sidebar>
    <router-view class="bg-gray-100 col-span-5 rounded-2xl p-8"></router-view>
  </div>
</template>

<style scoped>
</style>

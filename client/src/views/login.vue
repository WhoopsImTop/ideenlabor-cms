<template>
  <loginLayout>
    <loginComponent></loginComponent>
  </loginLayout>
</template>

<script>
import loginLayout from "../layouts/loginLayout.vue";
import loginComponent from "../components/loginComponent.vue";
export default {
  name: "login",
  components: {
    loginComponent,
    loginLayout,
  },
};
</script>

<style></style>

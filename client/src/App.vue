<template>
  <div>
    <router-view></router-view>
  </div>
</template>
<script>
import sidebar from "./components/layout/sidebar.vue";
export default {
  name: "App",
  components: {
    sidebar,
  },
  data() {
    return {
      token: localStorage.getItem("token"),
    };
  },
  //check if token changes in local storage
  watch: {
    token: function (val) {
      this.token = val;
    },
  },
};
</script>

<style scoped>
</style>

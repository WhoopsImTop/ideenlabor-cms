<template>
  <div>
    <div v-if="token" class="grid grid-cols-6 gap-4 h-screen">
      <sidebar></sidebar>
      <router-view class="bg-gray-100 col-span-5 rounded-2xl p-8"></router-view>
    </div>
    <div v-else>
      <router-view></router-view>
    </div>
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
  created() {
    if (localStorage.getItem("token")) {
      this.$router.push("cms");
    } else {
      this.$router.push("cms/login");
    }
  },
};
</script>

<style scoped>
</style>

<template>
  <div>
    <header class="bg-white shadow-sm py-2">
      <div class="container flex items-center justify-between mx-auto">
        <img
          src="../assets/khri8_logo.png"
          alt="KHRI8 Logo"
          style="height: 70px"
        />
        <button
          class="bg-pink-50 px-3 py-2 rounded text-pink-950 text-xs tracking-wider uppercase hover:bg-pink-100"
          @click="logout"
        >
          Abmelden
        </button>
      </div>
    </header>
    <div class="bg-pink-50/40">
      <div class="container mx-auto">
        <slot></slot>
      </div>
    </div>
    <footer class="py-2 bg-pink-100 static bottom-0 left-0 right-0">
      <div
        class="container mx-auto flex items-center justify-between text-pink-950"
      >
        <span class="text-xs"> khri8! more playgrounds.</span>
        <span class="text-xs"> Version 1.0.0</span>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  methods: {
    logout() {
      this.$store.state.customerCenter = null;
      sessionStorage.removeItem("customerCenter");
      this.$router.push({ name: "customerCenterLogin" });
    },
  },
};
</script>

<style></style>

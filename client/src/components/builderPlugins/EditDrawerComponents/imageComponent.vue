<template>
  <div>
    <input type="file" @change="uploadImage" />
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
    uploadImage(event) {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.component.content = reader.result;
      };
    },
  },
};
</script>

<style>
</style>
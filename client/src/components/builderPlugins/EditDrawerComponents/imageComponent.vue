<template>
  <div>
    <input type="file" id="image" @change="uploadImage" />
    <img class="imagePreview" :src="'https://ideenlabor-agentur.de' + imageUrl" />
  </div>
</template>

<script>
import axios from "axios";
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
      imageUrl: this.component ? this.component.src : "",
    };
  },
  methods: {
    getData() {
      return this.component;
    },
    uploadImage(event) {
      let formData = new FormData();
      formData.append("image", document.getElementById("image").files[0]);
      axios
        .post("/api/images", formData)
        .then((response) => {
          this.imageUrl = response.data.data.src;
          this.component.src = response.data.data.src;
        })
        .catch((error) => {
          window.alert("Es ist ein Fehler aufgetreten, bitte überprüfen Sie das Bild und versuchen Sie es erneut.")
        });
    },
  },
};
</script>

<style>
</style>
<template>
  <div>
    <h1>Medien</h1>
    <div class="row">
      <div class="col" v-for="image in images" :key="image.id">
        <img :src="'http://127.0.0.1:8000' + image.src" width="100" />
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      images: [],
    };
  },
  mounted() {
    this.getImages();
  },
  methods: {
    getImages() {
      axios
        .get("http://127.0.0.1:8000/api/images")
        .then((response) => {
          this.images = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style>
.row {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  grid-gap: 10px;
  margin-top: 50px;
}

.col {
  border: 1px solid #ccc;
  padding: 10px;
  width: 100px;
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
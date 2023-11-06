<template>
  <div>
    <h1>Medien</h1>
    <div class="editImagePopUp rounded" v-if="imagePopUp">
      <div class="grid grid-cols-2 gap-16 p-6">
        <img :src="'/api/public' + activeImage.src" />
        <div>
          <h3 class="font-bold mb-5">Bild Aktualisieren</h3>
          <div>
            <input
              class="border-solid border-2 p-4 rounded"
              id="image"
              type="file"
              @change="uploadImage(activeImage.id)"
            />
          </div>
          <div class="row">
            <button
              class="text-red-500 bg-red-50 p-2"
              @click="deleteImage(activeImage.id)"
            >
              Bild löschen
            </button>
            <button
              class="text-blue-500 bg-blue-50 p-2"
              @click="imagePopUp = false"
            >
              Schließen
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div
        class="col"
        v-for="image in images"
        :key="image.id"
        @click="(activeImage = image), (imagePopUp = true)"
      >
        <img :src="'/api/public' + image.src" width="100" />
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
      activeImage: {},
      imagePopUp: false,
    };
  },
  created() {
    this.getImages();
  },
  methods: {
    getImages() {
      axios
        .get("/api/images")
        .then((response) => {
          this.images = response.data.data;
        })
        .catch((err) => {
          if (err.response.data.message == "Unauthenticated.") {
            this.$router.push("/cms/login");
          } else {
            window.alert(
              "Es ist ein Fehler aufgetreten",
              err.response.data.message
            );
          }
        });
    },
    uploadImage(id) {
      let formData = new FormData();
      formData.append("image", document.getElementById("image").files[0]);
      axios
        .patch("/api/images/" + id, formData)
        .then((response) => {
          this.getImages();
          this.imagePopUp = false;
        })
        .catch((error) => {
          window.alert("Es ist ein Fehler aufgetreten");
        });
    },
    deleteImage(id) {
      axios
        .delete("/api/images/" + id)
        .then((response) => {
          this.getImages();
          this.imagePopUp = false;
        })
        .catch((error) => {
          window.alert("Es ist ein Fehler aufgetreten");
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

.editImagePopUp {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  width: 800px;
  height: 250px;
  background: #f8f8f8;
  z-index: 100;
}
</style>
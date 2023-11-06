<template>
  <div>
    <h2 class="rounded-full inline py-1 px-3 text-sm font-bold bg-gray-50">{{ folderToUploadInName }}</h2>
    <div
      class="dropzone-container"
      @dragover="dragover"
      @dragleave="dragleave"
      @drop="drop"
    >
      <input
        type="file"
        multiple
        name="file"
        :id="id"
        class="hidden-input"
        @change="onChange"
        ref="file"
        :accept="acceptedFileTypes"
      />
      <label :for="id" class="file-label">
        <div
          v-if="isDragging"
          style="display: flex; flex-direction: column; align-items: center"
        >
          <img
            src="../assets/drop.svg"
            width="100"
            style="margin-bottom: 10px"
          />
          <p>Dateien fallen lassen zum hochladen</p>
        </div>
        <div
          v-else
          style="display: flex; flex-direction: column; align-items: center"
        >
          <img
            src="../assets/upload.svg"
            width="100"
            style="margin-bottom: 10px"
          />
          <p>Dateien reinziehen oder <u class="highlighted">Hochladen</u></p>
        </div>
      </label>
    </div>
    <div class="fileList">
      <div class="fileUploadStatus" v-if="files.length > 0">
        <p
          v-for="file in files"
          :key="file.name"
          class="py-1 flex items-center justify-between mb-1 px-2 bg-gray-50 rounded"
        >
          <span class="fileName text-sm">{{ file.name }}</span>
          <span class="file-loader" v-if="!file.uploaded"
            ><img
              class="animate-spin"
              src="../assets/loading.svg"
              width="25"
              alt="loading"
          /></span>
          <span v-else
            ><img src="../assets/check.svg" width="25" alt="loading"
          /></span>
        </p>
      </div>
    </div>
    <div class="errorList" v-if="errors.length > 0">
      <p v-for="error in errors" :key="error.message" class="error">
        {{ error.message }}
      </p>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: [
    "acceptedFileTypes",
    "id",
    "customer_center_id",
    "folderToUploadIn",
    "folderToUploadInName",
  ],
  data() {
    return {
      isDragging: false,
      files: [],
      errors: [],
    };
  },
  methods: {
    getData() {
      return this.files;
    },
    onChange() {
      this.files.forEach((file) => {
        if (file.uploaded) return;
        file.uploaded = false;
      });
      this.files.push(...this.$refs.file.files);

      //upload files to server
      this.files.forEach(async (file) => {
        if (file.uploaded) return;
        this.uploadFile(file);
      });
    },
    uploadFile(file) {
      const formData = new FormData();
      //add file and token to formdata
      formData.append("file", file);
      formData.append("customer_center_id", this.customer_center_id);
      formData.append("folder_id", this.folderToUploadIn);
      axios
        .post("/api/uploads", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: "Bearer " + sessionStorage.getItem("token"),
          },
        })
        .then((res) => {
          file.uploaded = true;
          file.id = res.data.id;
          this.$emit("file-uploaded", res.data.data);
          setTimeout(() => {
            this.files.splice(this.files.indexOf(file), 1);
          }, 1000);
          //update ui
          this.$forceUpdate();
        })
        .catch((err) => {
          console.log(err);
          this.errors.push({
            message: file.name + " konnte nicht hochgeladen werden",
          });
          //remove file from list
          this.files.splice(this.files.indexOf(file), 1);
        });
    },
    dragover(e) {
      e.preventDefault();
      //add dragover class to dropzone
      e.target.classList.add("dragover");
      this.isDragging = true;
    },
    dragleave(e) {
      this.isDragging = false;
      e.target.classList.remove("dragover");
    },
    drop(e) {
      e.preventDefault();
      this.$refs.file.files = e.dataTransfer.files;
      this.onChange();
      this.isDragging = false;
      e.target.classList.remove("dragover");
    },
  },
};
</script>

<style scoped>
.highlighted {
  color: var(--primary);
  cursor: pointer;
}
.dropzone-container {
  background-color: var(--primary-light);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 200px;
  border-radius: 5px;
  margin-bottom: 20px;
}

.dropzone-container.dragover {
  border: 2px solid var(--primary);
}

.hidden-input {
  display: none;
}

.file-label {
  cursor: pointer;
}

.file-label:hover {
  text-decoration: underline;
}

.uploaded-file-list {
  display: flex;
  flex-direction: column;
  margin-top: 10px;
}

.uploaded-file {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: var(--primary-light);
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 10px;
  position: relative;
}

.file-loader {
  animation: spin 1s linear infinite;
}

.fileName {
  /* display max 30 Chars */
  max-width: 200px;
  overflow: hidden;
  text-overflow: ellipsis;
}

@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}
</style>

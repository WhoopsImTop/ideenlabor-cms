<template>
  <div v-if="currentCustomerCenter !== null">
    <div
      v-if="moveEntry"
      class="absolute z-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 shadow-sm bg-white flex flex-col p-4 rounded-lg"
    >
      <div class="flex items-center justify-between">
        <h2 class="font-bold text-lg">Verschieben</h2>
        <div>
          <img
            src="../../assets/close.svg"
            width="20"
            alt="schließen"
            @click="moveEntry = false"
          />
        </div>
      </div>
      <!-- list all folders to move in -->
      <div class="mt-4">
        <label class="block">
          <span class="text-gray-700">Ordner</span>
          <select
            class="mt-1 block w-full rounded-md border-gray-300 border border-gray-500 outline-none focus:border-pink-600 focus:ring focus:ring-pink-200 focus:ring-opacity-50 py-2 px-3"
            v-model="folderToMoveIn"
          >
            <option
              v-for="folder in currentCustomerCenter.folders[0].folder_children"
              :key="folder.id"
              :value="folder.id"
            >
              {{ folder.folder_name }}
            </option>
          </select>
        </label>
      </div>
      <div class="flex items-center justify-end">
        <button
          class="bg-pink-600 text-white rounded-full py-2 px-4 mt-4 mr-2"
          @click="moveEntryToFolder()"
        >
          Verschieben
        </button>
        <button
          class="bg-gray-100 text-gray-900 rounded-full py-2 px-4 mt-4"
          @click="moveEntry = false"
        >
          Schließen
        </button>
      </div>
    </div>

    <div
      v-if="createFolderModal"
      class="absolute z-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 rounded shadow-sm bg-white flex flex-col p-4 rounded-lg"
    >
      <div class="flex items-center justify-between">
        <h2 class="font-bold text-lg">Ordner Erstellen</h2>
        <div>
          <img
            src="../../assets/close.svg"
            width="20"
            alt="schließen"
            @click="closeFolderModal()"
          />
        </div>
      </div>
      <div class="mt-4">
        <label class="block">
          <span class="text-gray-700">Ordnername</span>
          <input
            type="text"
            class="mt-1 block w-full rounded-md border-gray-300 border border-gray-500 outline-none focus:border-pink-600 focus:ring focus:ring-pink-200 focus:ring-opacity-50 py-2 px-3"
            placeholder="Ordnername"
            v-model="newFolderName"
          />
        </label>
        <label class="block mt-4">
          <span class="text-gray-700">Ordner Beschreibung</span>
        </label>
        <ckeditor
          :editor="editor"
          v-model="newFolderDescription"
          :config="editorConfig"
        ></ckeditor>
      </div>
      <div class="flex items-center justify-end">
        <button
          class="bg-pink-600 text-white rounded-full py-2 px-4 mt-4 mr-2"
          @click="CreateFolder()"
        >
          {{ editingFolder === null ? "Erstellen" : "Aktualisieren" }}
        </button>
        <button
          class="bg-gray-100 text-gray-900 rounded-full py-2 px-4 mt-4"
          @click="closeFolderModal()"
        >
          Schließen
        </button>
      </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
      <div class="rounded bg-white p-2 shadow-sm mb-4">
        <p>
          <strong>Name:</strong>
          {{
            currentCustomerCenter.customer.customer_name != ""
              ? currentCustomerCenter.customer.customer_name
              : currentCustomerCenter.customer.customer_company_name
          }}
        </p>
        <p>
          <strong>Email:</strong>
          {{ currentCustomerCenter.customer.customer_email }}
        </p>
        <p>
          <strong>Kundennummer:</strong>
          {{ currentCustomerCenter.customer.customer_number }}
        </p>
        <p>
          <strong>PIN:</strong>
          {{ currentCustomerCenter.customer.customer_pin || "" }}
        </p>
      </div>
      <div class="rounded bg-white p-2 shadow-sm mb-4">
        <dragAndDropUploaderVue
          :acceptedFileTypes="['*']"
          :id="currentCustomerCenter.id"
          :customer_center_id="currentCustomerCenter.id"
          @file-uploaded="PushFilesToCurrentFolder($event)"
          :folderToUploadIn="currentFolder.id"
          :folderToUploadInName="currentFolder.folder_name"
        />
      </div>
    </div>
    <div class="rounded bg-white p-2 shadow-sm">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <h3 class="font-bold">Dateien</h3>
          <div class="breadcrumb flex items-center">
            <span
              class="rounded-full py-1 px-3 text-sm font-bold bg-gray-50 border ml-2 hover:cursor-pointer"
              :class="
                breadCrumb.folder_hash === currentFolder.folder_hash
                  ? 'border border-pink-600 bg-pink-100'
                  : ''
              "
              v-for="breadCrumb in FolderBreadCrumb"
              :key="breadCrumb.id"
              @click="fetchFolder(breadCrumb.folder_hash)"
              >{{ breadCrumb.folder_name }}</span
            >
          </div>
        </div>
        <a
          class="flex items-center bg-gray-50 rounded py-2 px-4 hover:cursor-pointer"
          @click="addFolder()"
          ><img
            src="../../assets/folder.svg"
            width="20"
            class="mr-2"
            alt="folder"
          />
          Ordner hinzufügen</a
        >
      </div>
      <table class="w-full mt-2">
        <thead class="bg-gray-50 rounded shadow-sm">
          <th class="text-left text-sm font-normal px-2 py-2">Dateiname</th>
          <th class="text-left text-sm font-normal px-2 py-2">Größe</th>
          <th class="text-left text-sm font-normal px-2 py-2">Erstellt am</th>
          <th class="text-right text-sm font-normal px-2 py-2">Aktionen</th>
        </thead>
        <tbody>
          <tr
            v-for="folder in currentFolder.folder_children"
            :key="folder.id"
            class="hover:bg-gray-100 hover:cursor-pointer"
          >
            <td
              class="text-sm font-normal py-2 px-2"
              @click="fetchFolder(folder.folder_hash)"
            >
              <p class="flex items-center">
                <img
                  src="./../../assets/folder.svg"
                  width="20"
                  alt="folder"
                  class="mr-2"
                />
                {{ folder.folder_name }}
              </p>
            </td>
            <td class="text-sm font-normal py-2 px-2"></td>
            <td class="text-sm font-normal py-2 px-2">
              {{ new Date(folder.created_at).toLocaleString("de-DE") }}
            </td>
            <td>
              <div class="flex items-center justify-end py-2 px-2">
                <a @click="editFolder(folder.id)" class="mx-1">
                  <img src="./../../assets/edit.svg" width="18" alt="folder" />
                </a>
                <a @click="moveFolder(folder.id)" class="mx-1">
                  <img
                    src="./../../assets/move_folder.svg"
                    width="20"
                    alt="folder"
                  />
                </a>
                <a @click="deleteFolder(folder.folder_hash)" class="mx-1"
                  ><img src="./../../assets/delete.svg" width="20"
                /></a>
              </div>
            </td>
          </tr>
          <tr
            v-for="file in currentFolder.uploads"
            :key="file.id"
            class="hover:bg-gray-100"
          >
            <td class="text-sm font-normal py-2 px-2">
              <p class="flex items-center">
                <img
                  src="./../../assets/file.svg"
                  width="20"
                  alt="folder"
                  class="mr-2"
                />
                {{ file.file_name }}
              </p>
            </td>
            <td class="text-sm font-normal py-2 px-2">
              {{ calculateFileSize(file.file_size) }}
            </td>
            <td class="text-sm font-normal py-2 px-2">
              {{ new Date(file.created_at).toLocaleString("de-DE") }}
            </td>
            <td>
              <div class="flex items-center justify-end py-2 px-2">
                <a @click="moveFile(file.id)" class="mx-1">
                  <img
                    src="./../../assets/move_folder.svg"
                    width="20"
                    alt="file"
                  />
                </a>
                <a @click="deleteFile(file.id)" class="mx-1"
                  ><img src="./../../assets/delete.svg" width="20"
                /></a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import dragAndDropUploaderVue from "../../components/dragAndDropUploader.vue";

import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
  components: {
    dragAndDropUploaderVue,
  },
  data() {
    return {
      currentCustomerCenter: null,
      currentFolder: null,
      FolderBreadCrumb: [],
      createFolderModal: false,
      newFolderName: "",
      newFolderDescription: "",
      editingFolder: null,
      moveEntry: false,
      folderToMoveIn: null,
      folderToMove: null,
      folderToMoveInName: null,
      folderToMoveId: null,
      fileToMove: null,
      editor: ClassicEditor,
      editorData: "<p>Content of the editor.</p>",
      editorConfig: {
        toolbar: [
          "heading",
          "|",
          "bold",
          "italic",
          "link",
          "bulletedList",
          "numberedList",
          "blockQuote",
          "undo",
          "redo",
        ],
      },
    };
  },
  methods: {
    moveEntryToFolder() {
      //find folder_name of folderToMoveIn
      this.folderToMoveInName =
        this.currentCustomerCenter.folders[0].folder_children.find(
          (folder) => folder.id === this.folderToMoveIn
        ).folder_name;

      this.folderToMoveId =
        this.currentCustomerCenter.folders[0].folder_children.find(
          (folder) => folder.id === this.folderToMoveIn
        ).id;
      if (this.folderToMove !== null) {
        axios
          .patch("/api/folders/" + this.folderToMove, {
            folder_parent_id: this.folderToMoveId,
            folder_name: this.folderToMoveInName,
          })
          .then((response) => {
            this.currentFolder.folder_children =
              this.currentFolder.folder_children.filter(
                (folder) => folder.id !== this.folderToMove
              );
            this.currentCustomerCenter.folders[0].folder_children.push(
              response.data.data
            );
            this.moveEntry = false;
            this.folderToMoveId = null;
            this.folderToMoveInName = null;
            this.folderToMove = null;
          })
          .catch((error) => {
            console.log(error);
          });
      } else if (this.fileToMove !== null) {
        axios
          .patch("/api/uploads/" + this.fileToMove, {
            folder_id: this.folderToMoveId,
          })
          .then((response) => {
            this.currentFolder.uploads = this.currentFolder.uploads.filter(
              (file) => file.id !== this.fileToMove
            );
            this.currentFolder.uploads.push(response.data.data);
            this.moveEntry = false;
            this.folderToMoveId = null;
            this.folderToMoveInName = null;
            this.fileToMove = null;
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },

    moveFolder(folderId) {
      this.moveEntry = true;
      this.folderToMove = folderId;
    },

    moveFile(fileId) {
      this.moveEntry = true;
      this.fileToMove = fileId;
    },

    addFolder() {
      this.createFolderModal = true;
    },
    CreateFolder() {
      if (this.editingFolder) {
        axios
          .put("/api/folders/" + this.editingFolder.id, {
            folder_hash: this.editingFolder.folder_hash,
            customer_id: this.currentCustomerCenter.customer.id,
            folder_name: this.newFolderName,
            description: this.newFolderDescription,
          })
          .then((response) => {
            this.editingFolder.folder_name = this.newFolderName;
            this.editingFolder.description = this.newFolderDescription;
            this.createFolderModal = false;
            this.newFolderDescription = "";
            this.newFolderName = "";
            this.editingFolder = null;
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        axios
          .post("/api/folders", {
            folder_name: this.newFolderName,
            description: this.newFolderDescription,
            folder_parent_id: this.currentFolder.id,
            customer_id: this.currentCustomerCenter.customer.id,
          })
          .then((response) => {
            this.currentFolder.folder_children.push(response.data.data);
            this.createFolderModal = false;
            this.newFolderDescription = "";
            this.newFolderName = "";
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    editFolder(folderId) {
      this.editingFolder = this.currentFolder.folder_children.find(
        (folder) => folder.id === folderId
      );
      this.newFolderName = this.editingFolder.folder_name;
      this.newFolderDescription = this.editingFolder.description;
      this.createFolderModal = true;
    },
    closeFolderModal() {
      this.createFolderModal = false;
      this.newFolderDescription = "";
      this.newFolderName = "";
      this.editingFolder = null;
    },
    PushFilesToCurrentFolder(file) {
      this.currentFolder.uploads.push(file);
    },
    calculateFileSize(fileSize) {
      return (fileSize / 1024 / 1024).toFixed(2) + " MB";
    },
    fetchFolder(folderHash) {
      axios
        .get("/api/folders/" + folderHash)
        .then((response) => {
          this.setFolderBreadcrumb(this.currentFolder);
          this.currentFolder = response.data.data;
          this.setFolderBreadcrumb(this.currentFolder);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    setFolderBreadcrumb(folder) {
      //check if folder is already in breadcrumb
      const isInBreadcrumb = this.FolderBreadCrumb.find(
        (breadCrumb) => breadCrumb.id === folder.id
      );

      if (!isInBreadcrumb) {
        this.FolderBreadCrumb.push(folder);
      } else {
        //remove all folders after the current folder
        const indexOfCurrentFolder = this.FolderBreadCrumb.findIndex(
          (breadCrumb) => breadCrumb.id === folder.id
        );
        this.FolderBreadCrumb.splice(indexOfCurrentFolder + 1);
      }
    },
    deleteFile(fileId) {
      axios
        .delete("/api/uploads/" + fileId)
        .then((response) => {
          this.currentFolder.uploads = this.currentFolder.uploads.filter(
            (file) => file.id !== fileId
          );
        })
        .catch((error) => {
          console.log(error);
        });
    },
    deleteFolder(folderId) {
      axios
        .delete("/api/folders/" + folderId)
        .then((response) => {
          this.currentFolder.folder_children =
            this.currentFolder.folder_children.filter(
              (folder) => folder.id !== folderId
            );
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    axios
      .get("/api/customerCenters/" + this.$route.params.id)
      .then((response) => {
        this.currentCustomerCenter = response.data.data;
        this.currentFolder = response.data.data.folders[0];
      });
  },
};
</script>

<style>
.ck-editor__editable {
  min-height: 150px !important;
}
</style>

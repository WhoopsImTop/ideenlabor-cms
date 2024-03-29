//CKEditor
import CKEditor from "@ckeditor/ckeditor5-vue";


import { createApp } from "vue";
import "./assets/css/style.css";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import axios from "axios";

import "./assets/css/ckeditor-remove.css"

//if local development set the baseURL to localhost
//if production set the baseURL to the server

/* if (process.env.NODE_ENV === "production") { */
axios.defaults.baseURL = "https://ideenlabor-agentur.de";
/* } else if (process.env.NODE_ENV === "development") {
  axios.defaults.baseURL = "http://127.0.0.1:8000";
} */

if (localStorage.getItem("token") != null) {
  //post laravel sanctum token to api
  axios.defaults.headers.common["Authorization"] =
    "Bearer " + localStorage.getItem("token");
}

createApp(App).use(router).use(store).use(CKEditor).mount("#app");

<template>
  <div>
    <h1>Übersicht</h1>

    <!-- create two col layout with inputs -->
    <div>
      <div class="form-control">
        <label>Firmen Name</label>
        <input
          type="text"
          v-model="company.company_name"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen Adresse</label>
        <input
          type="text"
          v-model="company.company_address"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen PLZ</label>
        <input
          type="text"
          v-model="company.company_zip"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen Stadt</label>
        <input
          type="text"
          v-model="company.company_city"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen Land</label>
        <input
          type="text"
          v-model="company.company_country"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen Telefon</label>
        <input
          type="text"
          v-model="company.company_phone"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen E-Mail</label>
        <input
          type="text"
          v-model="company.company_email"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen Webseite</label>
        <input
          type="text"
          v-model="company.company_website"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen Bild</label>
        <input
          type="file"
          id="image"
          @change="uploadImage()"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen Bankinhaber</label>
        <input
          type="text"
          v-model="company.company_bank_holder"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen Bankname</label>
        <input
          type="text"
          v-model="company.company_bank_name"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen IBAN</label>
        <input
          type="text"
          v-model="company.company_bank_iban"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen BIC</label>
        <input
          type="text"
          v-model="company.company_bank_bic"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen Steuernummer</label>
        <input
          type="text"
          v-model="company.company_tax_id"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
      <div class="form-control">
        <label>Firmen Umsatzsteuer ID</label>
        <input
          type="text"
          v-model="company.company_vat_id"
          class="border-2 border-solid border-gray-200 p-2"
        />
      </div>
    </div>
    <button
      class="btn px-4 py-2 rounded bg-blue-600 text-white"
      @click="createCompany()"
    >
      Speichern
    </button>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      company: {
        company_name: "",
        company_address: "",
        company_zip: "",
        company_city: "",
        company_country: "",
        company_phone: "",
        company_email: "",
        company_website: "",
        company_image: "",
        company_bank_holder: "",
        company_bank_name: "",
        company_bank_iban: "",
        company_bank_bic: "",
        company_tax_id: "",
        company_vat_id: "",
      },
    };
  },
  methods: {
    uploadImage() {
      let formData = new FormData();
      formData.append("image", document.getElementById("image").files[0]);
      axios
        .post("/api/images", formData)
        .then((response) => {
          this.company.company_image = response.data.data.src;
        })
        .catch((error) => {
          window.alert("Es ist ein Fehler aufgetreten");
        });
    },
    getCompanyData() {
      axios
        .get("/api/company")
        .then((response) => {
          if (response.data.length > 0) {
            this.company = response.data[0];
          }
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
    createCompany() {
      if (this.company.id) {
        axios
          .patch("/api/company/" + this.company.id, this.company)
          .then((response) => {
            console.log(response);
            this.company = response.data;
          })
          .catch((error) => {
            window.alert("Es ist ein Fehler aufgetreten");
          });
      } else {
        axios
          .post("/api/company/", this.company)
          .then((response) => {
            console.log(response);
            this.company = response.data;
          })
          .catch((error) => {
            window.alert("Es ist ein Fehler aufgetreten");
          });
      }
    },
  },
  created() {
    this.getCompanyData();
  },
};
</script>

<style>
</style>
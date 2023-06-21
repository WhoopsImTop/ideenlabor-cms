<template>
  <div class="customerPopUp">
    <div class="popup-head">
      <h2>Neuen Kunden anlegen</h2>
      <button class="close-btn" @click="closePopup">
        <img src="../../assets/close.svg" alt="schließen" width="20" />
      </button>
    </div>
    <div class="popup-content">
      <form>
        <div class="form-group">
          <label for="customer_name">Firmenname</label>
          <input
            type="text"
            id="customer_name"
            v-model="customer.customer_company_name"
            class="form-control"
            placeholder="Name"
          />
        </div>
        <div class="form-group">
          <label for="customer_name">Name</label>
          <input
            type="text"
            id="customer_name"
            v-model="customer.customer_name"
            class="form-control"
            placeholder="Name"
          />
        </div>
        <div class="form-group">
          <label for="customer_address">Straße</label>
          <input
            type="text"
            id="customer_address"
            v-model="customer.customer_address"
            class="form-control"
            placeholder="Straße"
          />
        </div>
        <div class="form-group">
          <label for="customer_zip">PLZ</label>
          <input
            type="text"
            id="customer_zip"
            v-model="customer.customer_zip"
            class="form-control"
            placeholder="PLZ"
          />
        </div>
        <div class="form-group">
          <label for="customer_city">Ort</label>
          <input
            type="text"
            id="customer_city"
            v-model="customer.customer_city"
            class="form-control"
            placeholder="Ort"
          />
        </div>
        <div class="form-group">
          <label for="customer_phone">Telefon</label>
          <input
            type="text"
            id="customer_phone"
            v-model="customer.customer_phone"
            class="form-control"
            placeholder="Telefon"
          />
        </div>
        <div class="form-group">
          <label for="customer_email">E-Mail</label>
          <input
            type="text"
            id="customer_email"
            v-model="customer.customer_email"
            class="form-control"
            placeholder="E-Mail"
          />
        </div>
      </form>
    </div>
    <div class="popup-actions">
      <button
        @click="saveCustomer()"
        class="btn px-4 py-2 rounded bg-blue-600 text-white"
      >
        Speichern
      </button>
      <button
        @click="deleteCustomer()"
        class="btn px-4 py-2 rounded text-red-600"
      >
        Kunde Löschen
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: {
    existingCustomer: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      customer: {
        customer_company_name: "",
        customer_name: "",
        customer_address: "",
        customer_zip: "",
        customer_city: "",
        customer_phone: "",
        customer_email: "",
      },
    };
  },
  methods: {
    saveCustomer() {
      if (this.customer.customer_number) {
        axios
          .patch(
            "http://127.0.0.1:8000/api/customers/" + this.customer.id,
            this.customer
          )
          .then((response) => {
            this.$emit("closePopup", response.data);
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        axios
          .post("http://127.0.0.1:8000/api/customers", this.customer)
          .then((response) => {
            this.$emit("closePopup", response.data);
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    deleteCustomer() {
      axios
        .delete("http://127.0.0.1:8000/api/customers/" + this.customer.id)
        .then((response) => {
          this.$emit("closePopup", response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    closePopup() {
      this.$emit("closePopup");
    },
  },
  beforeMount() {
    if (this.existingCustomer) {
      this.customer = this.existingCustomer;
    }
  },
};
</script>

<style scoped>
.customerPopUp {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 400px;
  height: 800px;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  z-index: 100;
  background-color: #ffffff;
  padding: 20px;
  border-radius: 5px;
}

.popup-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

input {
  width: 100%;
  padding: 10px;
  border: 1px solid #dddddd;
  border-radius: 5px;
  margin-bottom: 20px;
}

.popup-actions {
  position: absolute;
  bottom: 20px;
  display: flex;
  justify-content: flex-end;
}
</style>
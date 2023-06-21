<template>
  <div class="customerPopUp">
    <div class="popup-head">
      <h2>Leistung Anlegen</h2>
      <button class="close-btn" @click="closePopup">
        <img src="../../assets/close.svg" alt="schließen" width="20" />
      </button>
    </div>
    <div class="popup-content">
      <form>
        <div class="form-group">
          <label for="customer_name">Name</label>
          <input
            type="text"
            id="customer_name"
            v-model="service.service_name"
            class="form-control"
          />
        </div>
        <div class="form-group">
          <label for="customer_address">Preis</label>
          <input
            type="text"
            id="customer_address"
            v-model="service.service_price"
            class="form-control"
          />
        </div>
        <div class="form-group">
          <label for="customer_zip">Beschreibung</label>
          <textarea
            type="text"
            id="customer_zip"
            v-model="service.service_description"
            class="form-control"
          ></textarea>
        </div>
        <div class="form-group">
          <label for="customer_city">Einheit</label>
          <select
            type="text"
            id="customer_city"
            v-model="service.service_unit"
            class="form-control"
          >
            <option value="Stück">Stück</option>
            <option value="Stunden">Stunde(n)</option>
            <option value="tag">Tag</option>
          </select>
        </div>
        <div class="form-group">
          <label for="customer_phone">Steuern</label>
          <input
            type="text"
            id="customer_phone"
            v-model="service.service_tax"
            class="form-control"
          />
        </div>
      </form>
    </div>
    <div class="popup-actions">
      <button
        @click="saveService()"
        class="btn px-4 py-2 rounded bg-blue-600 text-white"
      >
        Speichern
      </button>
      <button
        @click="deleteService()"
        class="btn px-4 py-2 rounded text-red-600"
      >
        Leistung Löschen
      </button>
    </div>
  </div>
</template>
  
  <script>
import axios from "axios";
export default {
  props: {
    existingService: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      service: {
        service_name: "",
        service_price: "",
        service_description: "",
        service_unit: "Stunden",
        service_tax: "19.00",
        service_total: "",
      },
    };
  },
  methods: {
    saveService() {
      if (this.service.id) {
        this.service.service_total = this.service.service_price;
        axios
          .patch(
            "http://127.0.0.1:8000/api/services/" + this.service.id,
            this.service
          )
          .then((response) => {
            this.$emit("closePopup", response.data);
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        this.service.service_total = this.service.service_price;
        axios
          .post("http://127.0.0.1:8000/api/services", this.service)
          .then((response) => {
            this.$emit("closePopup", response.data);
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    deleteService() {
      axios
        .delete("http://127.0.0.1:8000/api/services/" + this.service.id)
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
    if (this.existingService) {
      this.service = this.existingService;
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
  height: 700px;
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

input, select, textarea {
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
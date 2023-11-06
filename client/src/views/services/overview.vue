<template>
  <div>
    <div class="flex justify-between">
      <h1>Alle Leistungen</h1>
      <button
        class="btn px-4 py-2 rounded bg-blue-600 text-white"
        @click="showPopup = true"
      >
        Neue Leistung
      </button>
    </div>
    <servicePopup
      v-if="showPopup"
      @closePopup="closePopup"
      :existingService="activeService"
      @close="showPopup = false"
    ></servicePopup>
    <table class="mt-10 divide-y divide-gray-300 w-full">
      <thead class="bg-gray-200">
        <tr>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            Leistung
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            Preis
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            Einheit
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            Beschreibung
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            Aktionen
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="service in services" :key="service.id">
          <td class="p-4">{{ service.service_name }}</td>
          <td>
            {{
              new Intl.NumberFormat("de-DE", {
                style: "currency",
                currency: "EUR",
              }).format(service.service_price)
            }}
          </td>
          <td>{{ service.service_unit }}</td>
          <td>{{ service.service_description }}</td>
          <td>
            <button
              class="btn px-2 py-1 rounded bg-blue-600 text-white"
              @click="editService(service)"
            >
              bearbeiten
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import servicePopup from "../../components/services/servicePopup.vue";
import axios from "axios";
export default {
  data() {
    return {
      services: [],
      showPopup: false,
      activeService: null,
    };
  },
  components: {
    servicePopup,
  },
  created() {
    this.getServices();
  },
  methods: {
    getServices() {
      axios
        .get("/api/services")
        .then((response) => {
          this.services = response.data;
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

    editService(service) {
      this.activeService = service;
      this.showPopup = true;
    },
    closePopup() {
      this.showPopup = false;
      this.getServices();
      this.activeService = null;
    },
  },
};
</script>

<style>
</style>
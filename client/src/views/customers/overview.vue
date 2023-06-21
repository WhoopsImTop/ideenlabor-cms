<template>
  <div>
    <div class="flex justify-between">
      <h1>Kunden</h1>
      <a
        class="btn px-4 py-2 rounded bg-blue-600 text-white"
        @click="showPopup = true"
        >Neuer Kunde</a
      >
    </div>
    <customerPopupVue
      v-if="showPopup"
      @closePopup="closePopup"
      :existingCustomer="activeCustomer"
      @close="showPopup = false"
    />
    <table class="mt-10 divide-y divide-gray-300 w-full">
      <thead class="bg-gray-200">
        <tr>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            Kundennummer
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            Name
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            Adresse
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            PLZ
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            Ort
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            Telefon
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-bold text-black"
            scope="col"
          >
            E-Mail
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
        <tr v-for="customer in customers" :key="customer.customer_number">
          <td class="p-4">{{ customer.customer_number }}</td>
          <td>{{ customer.customer_name }}</td>
          <td>{{ customer.customer_address }}</td>
          <td>{{ customer.customer_zip }}</td>
          <td>{{ customer.customer_city }}</td>
          <td>{{ customer.customer_phone }}</td>
          <td>{{ customer.customer_email }}</td>
          <td>
            <button
              class="btn px-2 py-1 rounded bg-blue-600 text-white"
              @click="editCustomer(customer)"
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
import axios from "axios";
import customerPopupVue from "../../components/customers/customerPopup.vue";
export default {
  data() {
    return {
      customers: [],
      showPopup: false,
      activeCustomer: null,
    };
  },
  components: {
    customerPopupVue,
  },
  methods: {
    editCustomer(customer) {
      this.activeCustomer = customer;
      this.showPopup = true;
    },
    closePopup() {
      this.showPopup = false;
      this.getCustomers();
      this.activeCustomer = null;
    },
    getCustomers() {
      axios
        .get("http://127.0.0.1:8000/api/customers")
        .then((response) => {
          this.customers = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  beforeMount() {
    this.getCustomers();
  },
};
</script>

<style>
</style>
<template>
  <defaultLayout>
    <div class="flex justify-between">
      <h1>Kunden</h1>

      <input
        class="border-2 rounded border-solid p-2 mr-3"
        type="search"
        v-model="searchCustomer"
        placeholder="Suchen"
      />

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
            Firma
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
        <tr
          v-for="customer in filteredCustomers"
          :key="customer.customer_number"
        >
          <td class="p-4">{{ customer.customer_number }}</td>
          <td>{{ customer.customer_company_name }}</td>
          <td>{{ customer.customer_name }}</td>
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
  </defaultLayout>
</template>

<script>
import axios from "axios";
import defaultLayout from "../../layouts/defaultLayout.vue";
import customerPopupVue from "../../components/customers/customerPopup.vue";
export default {
  data() {
    return {
      customers: [],
      searchCustomer: "",
      showPopup: false,
      activeCustomer: null,
    };
  },
  components: {
    customerPopupVue,
    defaultLayout,
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
        .get("/api/customers")
        .then((response) => {
          this.customers = response.data.data;
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
  },
  computed: {
    filteredCustomers() {
      return this.customers.filter((customer) => {
        return customer.customer_name
          ? customer.customer_name
              .toLowerCase()
              .includes(this.searchCustomer.toLowerCase())
          : "" || customer.customer_company_name
          ? customer.customer_company_name
              .toLowerCase()
              .includes(this.searchCustomer.toLowerCase())
          : "";
      });
    },
  },
  created() {
    this.getCustomers();
  },
};
</script>

<style></style>

<template>
  <defaultLayout>
    <div class="flex justify-between">
      <h1>Kundencenter</h1>
      <input
        class="border-2 rounded border-solid p-2 mr-3"
        type="search"
        v-model="searchCustomerCenter"
        placeholder="Suchen"
      />
      <div>
        <router-link
          class="btn px-4 py-2 rounded bg-blue-600 text-white"
          to="/cms/customer-center/create"
          >Neues Kundencenter</router-link
        >
      </div>
    </div>
    <div class="mt-10 grid grid-cols-6">
      <div :class="tableActions ? ' col-span-4' : 'col-span-6'">
        <table
          class="divide-y divide-gray-300 w-full"
          id="invoice_tabe_overview"
        >
          <thead class="bg-gray-200">
            <tr>
              <th
                v-for="header in tableHeaders"
                :key="header.key"
                class="px-6 py-3 text-left text-xs font-bold text-black"
              >
                {{ header.name }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="customerCenter in filteredInvoices"
              :key="customerCenter.invoiceNumber"
              class="bg-gray-100 hover:bg-gray-200"
              @click="editInvoice(customerCenter)"
            >
              <td
                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black"
              >
                {{ customerCenter.id }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                {{
                  customerCenter.customer.customer_name
                    ? customerCenter.customer.customer_name
                    : customerCenter.customer.customer_company_name
                }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                {{
                  new Date(customerCenter.created_at).toLocaleString("de-DE")
                }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </defaultLayout>
</template>

<script>
import defaultLayout from "../../layouts/defaultLayout.vue";
import axios from "axios";
export default {
  name: "invoices",
  components: {
    defaultLayout,
  },
  data() {
    return {
      tableActions: false,
      searchCustomerCenter: "",
      tableHeaders: [
        {
          name: "KundencenterId",
          key: "id",
        },
        {
          name: "Kunde",
          key: "customer",
        },
        {
          name: "Erstellt am",
          key: "created_at",
        },
      ],
      tableCustomerCenterData: [],
    };
  },
  methods: {
    editInvoice(customerCenter) {
      this.$router.push({
        name: "editCustomerCenter",
        params: { id: customerCenter.id },
      });
    },
    deleteInvoice(customerCenter) {
      if (confirm("Wollen Sie die Rechnung wirklich löschen?")) {
        axios
          .delete("/api/customerCenters/" + customerCenter.id)
          .then((response) => {
            this.tableCustomerCenterData = this.tableCustomerCenterData.filter(
              (item) => item.id !== customerCenter.id
            );
            this.tableActions = false;
          })
          .catch((error) => {
            window.alert("Es ist ein Fehler aufgetreten");
          });
      }
    },
  },
  computed: {
    filteredInvoices() {
      return this.tableCustomerCenterData.reverse();
    },
  },
  created() {
    axios
      .get("/api/customerCenters")
      .then((response) => {
        this.tableCustomerCenterData = response.data.data;
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
};
</script>

<style>
.close {
  cursor: pointer;
}

table {
  width: 100%;
  max-height: 100vh !important;
  overflow-y: scroll;
}

.sidebar {
  position: fixed;
  right: 0;
  top: 0;
  height: 100vh;
  width: 400px;
  z-index: 100;
}
</style>

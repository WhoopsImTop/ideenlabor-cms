<template>
  <div>
    <div class="flex justify-between">
      <h1>Angebote</h1>
      <input
        class="border-2 rounded border-solid p-2 mr-3"
        type="search"
        v-model="searchInvoice"
        placeholder="Suchen"
      />
      <router-link
        class="btn px-4 py-2 rounded bg-blue-600 text-white"
        to="/cms/create-offers"
        >Neues Angebot</router-link
      >
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
              v-for="invoice in filteredInvoices"
              :key="invoice.invoiceNumber"
              class="bg-gray-100 hover:bg-gray-200"
              @click="setInvoiceActive(invoice)"
            >
              <td
                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black"
              >
                {{ invoice.invoice_number }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                {{
                  invoice.customer.customer_name
                    ? invoice.customer.customer_name
                    : invoice.customer.customer_company_name
                }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                {{
                  new Date(invoice.invoice_date).toLocaleDateString("DE-de", {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                  })
                }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                <p>
                  {{
                    new Date(
                      parseDate(invoice.invoice_due_date)
                    ).toLocaleDateString("DE-de", {
                      year: "numeric",
                      month: "long",
                      day: "numeric",
                    })
                  }}
                </p>
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                {{
                  new Intl.NumberFormat("de-DE", {
                    style: "currency",
                    currency: "EUR",
                  }).format(invoice.invoice_total)
                }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                {{ invoice.invoice_status }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div
        v-if="tableActions"
        class="sidebar col-span-2 bg-gray-100 border-solid border-2 border-gray-200"
      >
        <div class="flex flex-row align-center justify-between p-4 border-b-2">
          <h3>Rechnung {{ activeInvoice.invoiceNumber }}</h3>
          <div class="close" @click="tableActions = false">
            <img width="20" src="../../assets/close.svg" alt="close" />
          </div>
        </div>
        <div class="flex flex-row align-center justify-between p-4 border-b-2">
          <div class="edit" @click="editOffer(activeInvoice)">
            <img width="20" src="../../assets/edit.svg" alt="edit" />
          </div>
          <div class="delete" @click="deleteOffer(activeInvoice)">
            <img width="20" src="../../assets/delete.svg" alt="delete" />
          </div>
        </div>
        <div
          class="invoicePreview bg-gray-300 rounded m-4 flex"
          style="height: 200px"
        >
          <iframe
            width="100%"
            height="200px"
            frameborder="0"
            :src="
              'https://ideenlabor-agentur.de/api/public/' +
              activeInvoice.invoice_path
            "
          ></iframe>
        </div>
        <div class="flex flex-col p-4">
          <span>{{ activeInvoice.customer.customer_name }}</span>
          <span>{{ activeInvoice.customer.customer_street }}</span>
          <span
            >{{ activeInvoice.customer.customer_zip }}
            {{ activeInvoice.customer.customer_city }}</span
          >
          <span class="text-right mt-5"
            >Erstellt am
            {{
              new Date(activeInvoice.invoice_date).toLocaleDateString("DE-de", {
                year: "numeric",
                month: "long",
                day: "numeric",
              })
            }}</span
          >
          <span v-if="activeInvoice.invoice_send_date" class="text-right"
            >Versendet am
            {{
              new Date(activeInvoice.invoice_send_date).toLocaleDateString(
                "DE-de",
                {
                  year: "numeric",
                  month: "long",
                  day: "numeric",
                }
              )
            }}</span
          >
          <span v-else class="text-right">Noch nicht versendet</span>
          <hr
            style="width: 100%; margin: 20px auto; border: 1px solid #dddddd"
          />
          <span class="text-right"
            >zu erhalten
            {{ parseFloat(activeInvoice.invoice_total).toFixed(2) }} €</span
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "invoices",
  data() {
    return {
      tableActions: false,
      searchInvoice: "",
      activeInvoice: {
        invoiceNumber: "",
        customer: "",
        invoiceDate: "",
        dueDate: "",
        amount: "",
        status: "",
      },
      tableHeaders: [
        {
          name: "Angebotsnummer",
          key: "invoiceNumber",
        },
        {
          name: "Kunde",
          key: "customer",
        },
        {
          name: "Angebotsdatum",
          key: "invoiceDate",
        },
        {
          name: "Gültig bis",
          key: "dueDate",
        },
        {
          name: "Betrag",
          key: "amount",
        },
        {
          name: "Status",
          key: "status",
        },
      ],
      tableInvoiceData: [],
    };
  },
  methods: {
    setInvoiceActive(invoice) {
      this.tableActions = true;
      this.activeInvoice = invoice;
    },
    editOffer(invoice) {
      this.$router.push({
        name: "edit-offer",
        params: { offer_number: invoice.invoice_number },
      });
    },
    parseDate(date) {
      //format d.m.yyyy to yyyy-mm-dd
      let dateArray = date.split(".");
      return dateArray[2] + "-" + dateArray[1] + "-" + dateArray[0];
    },
    deleteOffer(invoice) {
      //window alert to ask if sure to delete
      if (
        confirm(
          `Sind Sie sicher, dass Sie dieses Angebot ${invoice.invoice_number} löschen möchten?`
        )
      ) {
        axios
          .delete("/api/offers/" + invoice.invoice_number)
          .then((response) => {
            this.tableInvoiceData = this.tableInvoiceData.filter(
              (item) => item.invoice_number !== invoice.invoice_number
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
      return this.tableInvoiceData
        .filter((invoice) => {
          return (
            invoice.invoice_number
              .toLowerCase()
              .includes(this.searchInvoice.toLowerCase()) ||
            invoice.customer.customer_name
              .toLowerCase()
              .includes(this.searchInvoice.toLowerCase()) ||
            invoice.invoice_status
              .toLowerCase()
              .includes(this.searchInvoice.toLowerCase())
          );
        })
        .reverse();
    },
  },
  created() {
    axios
      .get("/api/offers")
      .then((response) => {
        this.tableInvoiceData = response.data.data;
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
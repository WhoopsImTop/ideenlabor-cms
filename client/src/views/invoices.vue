<template>
  <div>
    <div class="flex justify-between">
      <h1>Rechnungen</h1>
      <router-link
        class="btn px-4 py-2 rounded bg-blue-600 text-white"
        to="/create-invoice"
        >Neue Rechnung</router-link
      >
    </div>
    <div class="mt-10 grid grid-cols-6">
      <div :class="tableActions ? ' col-span-4' : 'col-span-6'">
        <table class="divide-y divide-gray-300 w-full">
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
              v-for="invoice in tableInvoiceData"
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
                {{ invoice.customer.customer_name }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                {{ invoice.invoice_date }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                {{ invoice.invoice_due_date }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                {{ invoice.invoice_total }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-black"
              >
                {{ invoice.status }}
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
            <img width="20" src="../assets/close.svg" alt="close" />
          </div>
        </div>
        <div class="flex flex-row align-center justify-between p-4 border-b-2">
          <div class="edit" @click="editInvoice(activeInvoice)">
            <img width="20" src="../assets/edit.svg" alt="edit" />
          </div>
          <div class="mail" @click="mailInvoice(activeInvoice)">
            <img width="20" src="../assets/mail.svg" alt="mail" />
          </div>
          <div class="print" @click="printInvoice(activeInvoice)">
            <img width="20" src="../assets/print.svg" alt="print" />
          </div>
          <div class="delete" @click="deleteInvoice(activeInvoice)">
            <img width="20" src="../assets/delete.svg" alt="delete" />
          </div>
        </div>
        <div class="invoicePreview bg-gray-300 rounded m-4 flex h-32">
          <iframe width="100%" height="100%" frameborder="0" :src="'http://127.0.0.1:8000/' + activeInvoice.invoice_path"></iframe>
        </div>
        <div class="flex flex-col p-4">
          <span class="text-right"
            >Erstellt am
            {{
              new Date(activeInvoice.invoiceDate).toLocaleDateString("DE-de", {
                year: "numeric",
                month: "long",
                day: "numeric",
              })
            }}</span
          >
          <span class="text-right"
            >Versendet am
            {{
              new Date(activeInvoice.mailDate).toLocaleDateString("DE-de", {
                year: "numeric",
                month: "long",
                day: "numeric",
              })
            }}</span
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
          name: "Rechnungsnummer",
          key: "invoiceNumber",
        },
        {
          name: "Kunde",
          key: "customer",
        },
        {
          name: "Rechnungsdatum",
          key: "invoiceDate",
        },
        {
          name: "Fälligkeitsdatum",
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
  },
  beforeMount() {
    axios
      .get("http://127.0.0.1:8000/api/invoices")
      .then((response) => {
        this.tableInvoiceData = response.data.data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style>
.close {
  cursor: pointer;
}
</style>
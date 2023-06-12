<template>
  <div>
    <h1>Rechnung erstellen</h1>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Kundenangaben</h3>
      <div class="grid grid-cols-2 gap-16 py-4">
        <div class="col-span-1">
          <div class="flex flex-col">
            <customer-search-component @customerSelected="setCutomerData" />
          </div>
          <div class="flex flex-col my-2">
            <input
              class="border-2 rounded border-solid p-2"
              v-model="customer.customer_address"
              placeholder="Adresszusatz"
            />
          </div>
          <div class="flex flex-col my-2">
            <input
              class="border-2 rounded border-solid p-2"
              v-model="customer.customer_street"
              placeholder="Straße"
            />
          </div>
          <div class="grid grid-cols-6 gap-4 my-2">
            <div class="flex flex-col col-span-2">
              <input
                class="border-2 rounded border-solid p-2"
                v-model="customer.customer_zip"
                placeholder="Plz"
              />
            </div>
            <div class="flex flex-col col-span-4">
              <input
                class="border-2 rounded border-solid p-2"
                v-model="customer.customer_city"
                placeholder="Ort"
              />
            </div>
          </div>
        </div>
        <div class="col-span-1">
          <div class="flex items-center">
            <label class="mr-4 font-bold">Rechnungsnummer</label>
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.invoice_number"
              placeholder="automatisch"
            />
          </div>
          <div class="flex items-center my-2">
            <label class="mr-4 font-bold">Kundennummer</label>
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.customer_number"
              placeholder="automatisch"
            />
          </div>
          <div class="flex items-center my-2">
            <label class="mr-4 font-bold">Rechnungsdatum</label>
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.invoice_date"
              type="date"
              placeholder="automatisch"
            />
          </div>
          <div class="flex items-center my-2">
            <label class="mr-4 font-bold">Leistungszeitraum</label>
            <input
              class="border-2 rounded border-solid p-2 w-full mr-3"
              v-model="leistungszeitraumStart"
              type="date"
              placeholder="automatisch"
            />
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="leistungszeitraumEnde"
              type="date"
              placeholder="automatisch"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Kopfbereich</h3>
      <div class="py-4">
        <div class="flex flex-col">
          <div class="flex flex-col">
            <label>Belegtitel</label>
            <input
              class="border-2 rounded border-solid p-2"
              v-model="invoice.invoice_title"
              placeholder="Rechnungstitel"
            />
          </div>
          <div class="flex flex-col mt-4">
            <label>Einleitungstext</label>
            <input
              class="border-2 rounded border-solid p-2"
              v-model="invoice.invoice_start_text"
              placeholder="Rechnungstitel"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Belegpositionen (Netto)</h3>
      <table class="divide-y divide-gray-300 w-full mt-4" id="invoiceTable">
        <thead class="bg-gray-200">
          <tr>
            <th
              v-for="header in invoiceTableHeader"
              :key="header"
              :style="'width:' + header.width"
              class="text-left py-2"
            >
              {{ header.title }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(position, index) in invoice.invoice_positions"
            :key="position"
            :id="index"
            class="hover:bg-gray-100 border-b-2 border-gray-100"
            draggable="true"
            @change="updatePosition(index)"
          >
            <td class="py-4">
              <img src="./../assets/drag.svg" width="20" alt="drag" />
            </td>
            <td>
              {{ index + 1 }}
            </td>
            <td>
              <input
                class="rounded px-2 py-2"
                v-model="position.description"
                placeholder="Produkt / Dienstleistung"
              />
            </td>
            <td>
              <input
                class="rounded px-2 py-2 text-right"
                v-model="position.quantity"
                placeholder="Menge"
              />
            </td>
            <td>
              <input class="rounded px-2 py-2" v-model="position.unit" />
            </td>
            <td>
              <input
                class="rounded px-2 py-2 text-right"
                v-model="position.price"
              />
            </td>
            <td>
              <input
                class="rounded px-2 py-2 text-right"
                v-model="position.tax"
              />
            </td>
            <td>
              <input
                class="rounded px-2 py-2 text-right"
                v-model="position.discount"
              />
            </td>
            <td>
              <p class="font-bold">
                {{
                  new Intl.NumberFormat("de-DE", {
                    style: "currency",
                    currency: "EUR",
                  }).format(position.amount)
                }}
              </p>
            </td>
            <td @click="removeInvoicePosition(index)">
              <img src="./../assets/close.svg" width="20" alt="delete" />
            </td>
          </tr>
        </tbody>
      </table>
      <div class="grid grid-cols-12 gap-4">
        <div class="col-span-2">
          <button
            @click="AddInvoiceEntry()"
            class="col-span-2 border-solid border-2 border-gray-500 px-3 py-1 rounded mt-4"
          >
            Position hinzufügen
          </button>
        </div>
        <div class="col-start-8 col-end-13 border-solid border-2 rounded my-4">
          <div class="flex flex-col px-2 pt-2">
            <div class="flex justify-between pb-2">
              <p>Zwischensumme:</p>
              <p>
                {{
                  new Intl.NumberFormat("de-DE", {
                    style: "currency",
                    currency: "EUR",
                  }).format(total)
                }}
              </p>
            </div>
          </div>
          <div class="flex flex-col px-2 pt-2">
            <div class="flex justify-between pb-2">
              <p>zuzüglich USt 19%</p>
              <p>
                {{
                  new Intl.NumberFormat("de-DE", {
                    style: "currency",
                    currency: "EUR",
                  }).format(taxTotal)
                }}
              </p>
            </div>
          </div>
          <div
            class="flex justify-between border-gray-200 bg-gray-200 py-2 px-2"
          >
            <p class="text-lg font-bold">Gesamtbetrag</p>
            <p class="text-lg font-bold">
              {{
                new Intl.NumberFormat("de-DE", {
                  style: "currency",
                  currency: "EUR",
                }).format(totalWithTax)
              }}
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Fußbereich</h3>
      <div class="py-4">
        <div class="flex flex-col">
          <label>Zahlungsbedingung</label>
          <input
            class="border-2 rounded border-solid p-2"
            v-model="invoice.paymentCondition"
            placeholder="Zahlungsbedingung"
          />
        </div>
        <div class="flex flex-col mt-4">
          <label>Nachbemerkung</label>
          <input
            class="border-2 rounded border-solid p-2"
            v-model="invoice.afterword"
            placeholder="Nachbemerkung"
          />
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Rechnungsstellung</h3>
      <div class="py-4">
        <button
          class="btn px-4 py-2 rounded bg-gray-200 mr-3"
          @click="saveAsDraft"
        >
          Entwurf speichern
        </button>
        <button
          class="btn px-4 py-2 rounded bg-blue-600 text-white"
          @click="generateInvoice"
        >
          Rechnung erstellen
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import CustomerSearchComponent from "../components/customerSearchComponent.vue";
export default {
  name: "createInvoice",
  components: {
    CustomerSearchComponent,
  },
  props: {},
  data() {
    return {
      leistungszeitraumStart: "",
      leistungszeitraumEnde: "",
      customer: {
        customer_name: "",
        customer_address: "",
        customer_street: "",
        customer_zip: "",
        customer_city: "",
        customer_number: "",
        customer_email: "",
      },
      invoice: {
        customer_id: "",
        invoice_number: "",
        customer_number: "",
        invoice_date: "",
        delivery_date: "",
        invoice_title: "Rechnung",
        invoice_start_text:
          "Unsere Lieferungen/Lieferung stellen wir Ihnen wie folgt in Rechnung:",
        invoice_positions: [
          {
            position: "",
            quantity: 1,
            unit: "Stunde",
            price: 0.0,
            tax: 19,
            discount: 0.0,
            amount: 0.0,
          },
        ],
        invoice_payment_condition: "",
        invoice_afterword: "Vielen Dank für die gute Zusammenarbeit.",
      },
      invoiceTableHeader: {
        drag: {
          title: "",
          width: "10%",
        },
        position: {
          title: "Pos.",
          width: "5%",
        },
        description: {
          title: "Produkt / Service",
          width: "30%",
        },
        quantity: {
          title: "Menge",
          width: "5%",
        },
        unit: {
          title: "Einheit",
          width: "5%",
        },
        price: {
          title: "Preis",
          width: "10%",
        },
        tax: {
          title: "MwSt.",
          width: "5%",
        },
        discount: {
          title: "Rabatt (%)",
          width: "5%",
        },
        amount: {
          title: "Gesamt",
          width: "10%",
        },
        delete: {
          title: "",
          width: "10%",
        },
      },
    };
  },
  computed: {
    taxTotal() {
      return (
        this.invoice.invoice_positions.reduce(
          (acc, cur) => parseFloat(acc) + parseFloat(cur.amount),
          0
        ) * 0.19
      );
    },
    total() {
      return this.invoice.invoice_positions.reduce(
        (acc, cur) => parseFloat(acc) + parseFloat(cur.amount),
        0
      );
    },
    totalWithTax() {
      return (
        this.invoice.invoice_positions.reduce(
          (acc, cur) => parseFloat(acc) + parseFloat(cur.amount),
          0
        ) +
        this.invoice.invoice_positions.reduce(
          (acc, cur) => parseFloat(acc) + parseFloat(cur.amount),
          0
        ) *
          0.19
      );
    },
  },
  methods: {
    saveAsDraft() {
      this.invoice.status = "entwurf";
      this.invoice.total = this.total;
      console.log(this.invoice.customer_number);
      axios
        .post("http://127.0.0.1:8000/api/invoices", {
          invoice: this.invoice,
        })
        .then((res) => {
          console.log(res);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    generateInvoice() {
      this.invoice.status = "offen";
      this.invoice.total = this.total;
      axios
        .post("http://127.0.0.1:8000/api/invoices", {
          invoice: this.invoice,
        })
        .then((res) => {
          console.log(res);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    setCutomerData(customer) {
      this.customer = customer;
      this.invoice.customer_number = customer.customer_number;
      this.invoice.customer_id = customer.id;
    },
    updatePosition(index) {
      const position = this.invoice.invoice_positions[index];
      position.amount =
        position.quantity * position.price * (1 - position.discount / 100);
    },
    AddInvoiceEntry() {
      this.invoice.invoice_positions.push({
        position: "",
        quantity: 1,
        unit: "Stunde",
        price: 0.0,
        tax: 19,
        discount: 0.0,
        amount: 0.0,
      });
    },
    removeInvoicePosition(index) {
      this.invoice.invoice_positions.splice(index, 1);
    },
    calculatePaymentDate(invoice_date, days) {
      const dateObj = new Date(invoice_date);
      dateObj.setDate(dateObj.getDate() + days);
      return dateObj.toLocaleDateString("de-DE");
    },
  },
  mounted() {
    this.invoice.invoice_date = new Date().toISOString().slice(0, 10);
    this.invoice.delivery_date = new Date().toISOString().slice(0, 10);
    this.leistungszeitraumStart = new Date().toISOString().slice(0, 10);
    this.leistungszeitraumEnde = new Date().toISOString().slice(0, 10);

    this.invoice.paymentCondition = `Zahlbar innerhalb von 14 Tagen nach Rechnungsdatum (Zahlungsziel: ${this.calculatePaymentDate(
      this.invoice.invoice_date,
      14
    )})`;
  },
};
</script>

<style>
</style>
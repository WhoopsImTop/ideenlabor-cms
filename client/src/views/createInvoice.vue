<template>
  <div>
    <h1>Rechnung erstellen</h1>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Kundenangaben</h3>
      <div class="grid grid-cols-2 gap-16 py-4">
        <div class="col-span-1">
          <div class="flex flex-col">
            <select class="border-2 rounded border-solid p-2">
              <option>Kunde Suchen oder erfassen</option>
              <option>2</option>
            </select>
          </div>
          <div class="flex flex-col my-2">
            <input
              class="border-2 rounded border-solid p-2"
              v-model="invoice.customer.address"
              placeholder="Adresszusatz"
            />
          </div>
          <div class="flex flex-col my-2">
            <input
              class="border-2 rounded border-solid p-2"
              v-model="invoice.customer.street"
              placeholder="Straße"
            />
          </div>
          <div class="grid grid-cols-6 gap-4 my-2">
            <div class="flex flex-col col-span-2">
              <input
                class="border-2 rounded border-solid p-2"
                v-model="invoice.customer.zip"
                placeholder="Plz"
              />
            </div>
            <div class="flex flex-col col-span-4">
              <input
                class="border-2 rounded border-solid p-2"
                v-model="invoice.customer.city"
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
              v-model="invoice.invoiceNumber"
              placeholder="automatisch"
            />
          </div>
          <div class="flex items-center my-2">
            <label class="mr-4 font-bold">Kundennummer</label>
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.customerNumber"
              placeholder="automatisch"
            />
          </div>
          <div class="flex items-center my-2">
            <label class="mr-4 font-bold">Datum</label>
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.invoice_date"
              type="date"
              placeholder="automatisch"
            />
          </div>
          <div class="flex items-center my-2">
            <label class="mr-4 font-bold">Lieferdatum</label>
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.deliveryDate"
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
              v-model="invoice.invoiceTitle"
              placeholder="Rechnungstitel"
            />
          </div>
          <div class="flex flex-col mt-4">
            <label>Einleitungstext</label>
            <input
              class="border-2 rounded border-solid p-2"
              v-model="invoice.invoiceStartText"
              placeholder="Rechnungstitel"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Belegpositionen (Brutto)</h3>
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
            v-for="(position, index) in invoice.invoicePositions"
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
              <input
                class="rounded px-2 py-2 text-right"
                v-model="position.amount"
              />
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
        <div
          class="col-start-8 col-end-13 border-solid border-2 border-gray-200 rounded my-4"
        >
          <div class="flex justify-between bg-gray-200 px-2 pt-2">
            <p class="text-lg font-bold">Gesamtbetrag</p>
            <p class="text-lg font-bold">{{ total }} €</p>
          </div>
          <div class="flex justify-between bg-gray-200 pb-2 pl-2">
            <p>enthält USt 19% ({{ taxTotal }} €)</p>
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
  </div>
</template>

<script>
export default {
  name: "createInvoice",
  props: {},
  data() {
    return {
      invoice: {
        customer: {
          name: "",
          address: "",
          street: "",
          zip: "",
          city: "",
        },
        invoiceNumber: "",
        customerNumber: "",
        invoice_date: "",
        deliveryDate: "",
        invoiceTitle: "Rechnung",
        invoiceStartText:
          "Unsere Lieferungen/Lieferung stellen wir Ihnen wie folgt in Rechnung:",
        invoicePositions: [
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
        paymentCondition: "",
        afterword: "Vielen Dank für die gute Zusammenarbeit.",
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
      return this.invoice.invoicePositions
        .reduce(
          (acc, cur) =>
            parseInt(acc) +
            ((parseInt(cur.amount) / (100 + parseInt(cur.tax))) *
              parseInt(cur.tax)),
          0
        )
        .toFixed(2);
    },
    total() {
      return this.invoice.invoicePositions
        .reduce((acc, cur) => parseInt(acc) + parseInt(cur.amount), 0)
        .toFixed(2);
    },
  },
  methods: {
    updatePosition(index) {
      const position = this.invoice.invoicePositions[index];
      position.amount = (
        position.quantity *
        position.price *
        (1 - position.discount / 100)
      ).toFixed(2);
    },
    AddInvoiceEntry() {
      this.invoice.invoicePositions.push({
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
      this.invoice.invoicePositions.splice(index, 1);
    },
    calculatePaymentDate(invoice_date, days) {
      const dateObj = new Date(invoice_date);
      dateObj.setDate(dateObj.getDate() + days);
      return dateObj.toLocaleDateString("de-DE");
    },
  },
  mounted() {
    this.invoice.invoice_date = new Date().toISOString().slice(0, 10);
    this.invoice.deliveryDate = new Date().toISOString().slice(0, 10);

    this.invoice.paymentCondition = `Zahlbar innerhalb von 14 Tagen nach Rechnungsdatum (Zahlungsziel: ${this.calculatePaymentDate(
      this.invoice.invoice_date,
      14
    )})`;
  },
};
</script>

<style>
</style>
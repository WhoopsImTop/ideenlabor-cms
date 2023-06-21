<template>
  <div>
    <h1>Angebot erstellen</h1>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Kundenangaben</h3>
      <div class="grid grid-cols-2 gap-16 py-4">
        <div class="col-span-1">
          <div class="flex flex-col">
            <customer-search-component
              ref="customerSelect"
              @customerSelected="setCutomerData"
            />
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
            <label class="mr-4 font-bold">Angebotsnummer</label>
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.invoice_number"
              ref="invoiceNumberInput"
              placeholder="automatisch"
            />
          </div>
          <div class="flex items-center my-2">
            <label class="mr-4 font-bold">Kundennummer</label>
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.customer_number"
              ref="customerNumberInput"
              placeholder="automatisch"
            />
          </div>
          <div class="flex items-center my-2">
            <label class="mr-4 font-bold">Angebotsdatum</label>
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.invoice_date"
              ref="invoiceDateInput"
              type="date"
              placeholder="automatisch"
            />
          </div>
          <div class="flex items-center my-2" v-if="!lieferdatum">
            <label class="mr-4 font-bold">Leistungszeitraum</label>
            <input
              class="border-2 rounded border-solid p-2 w-full mr-3"
              v-model="invoice.invoice_delivery_start_date"
              ref="leistungszeitraumStartInput"
              type="date"
              placeholder="automatisch"
            />
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.invoice_delivery_end_date"
              ref="leistungszeitraumEndeInput"
              type="date"
              placeholder="automatisch"
            />
          </div>
          <div class="flex items-center my-2" v-if="lieferdatum">
            <label class="mr-4 font-bold">Lieferdatum</label>
            <input
              class="border-2 rounded border-solid p-2 w-full"
              v-model="invoice.invoice_delivery_date"
              ref="invoiceDueDateInput"
              type="date"
              placeholder="automatisch"
            />
          </div>
          <span
            class="text-blue-400 font-bold cursor-pointer"
            @click="lieferdatum = !lieferdatum"
            >{{ lieferdatum ? "Lieferzeitraum" : "Lieferdatum" }}</span
          >
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
              ref="invoiceTitleInput"
              placeholder="Rechnungstitel"
            />
          </div>
          <div class="flex flex-col mt-4">
            <label>Einleitungstext</label>
            <input
              class="border-2 rounded border-solid p-2"
              v-model="invoice.invoice_start_text"
              ref="invoiceStartTextInput"
              placeholder="Rechnungstitel"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Belegpositionen (Netto)</h3>

      <div class="invoiceTable">
        <div class="divTableRow head">
          <div
            class="divTableHead"
            v-for="header in invoiceTableHeader"
            :key="header"
            :style="'width:' + header.width + ';text-align:' + header.text"
          >
            {{ header.title }}
          </div>
        </div>
        <div
          class="divTableCol"
          v-for="(position, index) in invoice.invoice_positions"
          @change="updatePosition(index)"
          :key="index"
        >
          <div class="divTableRow">
            <div class="divTableCell" style="width: 30px">
              <img src="./../../assets/drag.svg" width="20" alt="drag" />
            </div>
            <div class="divTableCell" style="width: 50px">
              {{ index + 1 }}
            </div>
            <div class="divTableCell" style="width: 35%">
              <serviceSearchComponent ref="service" :index="index" v-model="position.name" @selectedService="setPositionData"></serviceSearchComponent>
            </div>
            <div class="divTableCell" style="width: 10%">
              <input
                v-model="position.quantity"
                placeholder="Menge"
                @focus="$event.target.select()"
              />
            </div>
            <div class="divTableCell" style="width: 15%">
              <select v-model="position.unit">
                <option
                  v-for="unit in unitOptions"
                  :key="unit.value"
                  :value="unit.value"
                >
                  {{ unit.text }}
                </option>
              </select>
            </div>
            <div class="divTableCell" style="width: 10%">
              <input
                type="text"
                v-model="position.price"
                @focus="$event.target.select()"
              />
            </div>
            <div class="divTableCell" style="width: 8%">
              <input
                type="text"
                v-model="position.tax"
                @focus="$event.target.select()"
              />
            </div>
            <div class="divTableCell" style="width: 150px">
              <input
                type="text"
                v-model="position.discount"
                @focus="$event.target.select()"
              />
            </div>
            <div class="divTableCell" style="width: 100px">
              {{
                new Intl.NumberFormat("de-DE", {
                  style: "currency",
                  currency: "EUR",
                }).format(position.amount)
              }}
            </div>
            <div
              class="divTableCell"
              @click="removeInvoicePosition(index)"
              style="width: 30px"
            >
              <img src="./../../assets/close.svg" width="20" alt="delete" />
            </div>
          </div>

          <div class="divTableRow">
            <div class="divTableCell" style="width: 100%">
              <textarea v-model="position.description"></textarea>
            </div>
          </div>
        </div>
      </div>

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
      <div class="grid grid-cols-6 gap-4 my-2">
        <div class="flex flex-col col-span-4">
          <label>Zahlungsbedingung</label>
          <input
            class="border-2 rounded border-solid p-2"
            v-model="invoice.invoice_payment_condition"
            ref="paymentCondition"
            placeholder="Zahlungsbedingung"
          />
        </div>
        <div class="flex flex-col col-span-2">
          <label>Zahlungsziel</label>
          <select
            @change="updatePaymentConditions"
            class="border-2 rounded border-solid p-2"
            v-model="zahlungsziel"
          >
            <option value="0">sofort</option>
            <option value="7">7 Tage</option>
            <option value="14">14 Tage</option>
          </select>
        </div>
      </div>
      <div class="py-4">
        <div class="flex flex-col mt-4">
          <label>Nachbemerkung</label>
          <input
            class="border-2 rounded border-solid p-2 w-full"
            ref="afterword"
            v-model="invoice.invoice_afterword"
            placeholder="Nachbemerkung"
          />
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Angebotsstellung</h3>
      <div class="py-4">
        <button
          class="btn px-4 py-2 rounded bg-gray-200 mr-3"
          @click="saveAsDraft"
        >
          {{ createDraftInvoiceButtonText }}
        </button>
        <button
          class="btn px-4 py-2 rounded bg-blue-600 text-white"
          @click="generateInvoice"
        >
          {{ createInvoiceButtonText }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import CustomerSearchComponent from "../../components/customerSearchComponent.vue";
import serviceSearchComponent from "../../components/serviceSearchComponent.vue";
export default {
  name: "createInvoice",
  components: {
    CustomerSearchComponent,
    serviceSearchComponent,
  },
  props: {},
  data() {
    return {
      leistungszeitraumStart: "",
      leistungszeitraumEnde: "",
      createInvoiceButtonText: "Angebot erstellen",
      createDraftInvoiceButtonText: "Entwurf speichern",
      zahlungsziel: 7,
      lieferdatum: true,
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
        invoice_delivery_date: "",
        invoice_delivery_start_date: "",
        invoice_delivery_end_date: "",
        invoice_due_date: "",
        invoice_title: "Angebot",
        invoice_start_text:
          "Für Folgenden Auftrag bieten wir folgendes Angebot an:",
        invoice_positions: [
          {
            name: "",
            position: 1,
            quantity: 1,
            unit: "Stunde",
            price: 0.0,
            tax: 19.0,
            discount: 0.0,
            amount: 0.0,
            description: "",
          },
        ],
        invoice_payment_condition: "",
        invoice_afterword: "Vielen Dank für die gute Zusammenarbeit.",
      },
      invoiceTableHeader: {
        drag: {
          title: "*",
          width: "30px",
        },
        position: {
          title: "Pos.",
          width: "50px",
        },
        description: {
          title: "Produkt / Service",
          width: "35%",
        },
        quantity: {
          title: "Menge",
          width: "10%",
        },
        unit: {
          title: "Einheit",
          width: "15%",
        },
        price: {
          title: "Preis",
          width: "10%",
        },
        tax: {
          title: "MwSt.",
          width: "8%",
        },
        discount: {
          title: "Rabatt (%)",
          width: "150px",
        },
        amount: {
          title: "Gesamt",
          width: "100px",
          text: "right",
        },
        delete: {
          title: "*",
          width: "30px",
        },
      },
      unitOptions: [
        { value: "Stunden", text: "Stunden" },
        { value: "Stück", text: "Stück" },
        { value: "Tag", text: "Tag" },
        { value: "Monat", text: "Monat" },
        { value: "Produkt", text: "Produkt" },
      ],
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
  watch: {
    "invoice.invoice_date": function (newVal, oldVal) {
      if (newVal != oldVal) {
        this.updatePaymentConditions();
      }
    },
  },
  methods: {
    setPositionData(data, index) {
      this.invoice.invoice_positions[index].name = data.service_name;
      this.invoice.invoice_positions[index].description = data.service_description;
      this.invoice.invoice_positions[index].price = data.service_price;
      this.invoice.invoice_positions[index].tax = data.service_tax;
      this.invoice.invoice_positions[index].unit = data.service_unit;
      this.invoice.invoice_positions[index].amount = data.service_price;
    },
    updatePaymentConditions() {
      const dueDate = new Date(this.invoice.invoice_date);

      // Calculate the due date based on the selected payment term
      dueDate.setDate(dueDate.getDate() + parseInt(this.zahlungsziel));

      this.invoice.invoice_payment_condition = `Zahlbar innerhalb von ${
        this.zahlungsziel
      } Tagen nach Rechnungsdatum (Zahlungsziel: ${this.calculatePaymentDate()})`;
    },
    generateCustomer() {
      this.customer.customer_name = this.$refs.customerSelect.getData();
      let thus = this;
      return new Promise(function (resolve, reject) {
        axios
          .post("http://127.0.0.1:8000/api/customers", thus.customer)
          .then((res) => {
            resolve(res.data.data);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },
    async saveAsDraft() {
      this.createDraftInvoiceButtonText = "Speichern...";
      if (this.validateInvoice()) {
        if (!this.customer.customer_number) {
          let customer = await this.generateCustomer().then((customer) => {
            console.log(customer);
            return customer;
          });
          this.customer = customer;
          this.invoice.customer_id = customer.id;
          this.invoice.customer_number = customer.customer_number;
        }
        this.invoice.invoice_status = "entwurf";
        this.invoice.invoice_total = this.totalWithTax;
        this.invoice.invoice_subtotal = this.total;
        this.invoice.invoice_tax = this.taxTotal;

        if (this.lieferdatum) {
          this.invoice.invoice_delivery_start_date = "";
          this.invoice.invoice_delivery_end_date = "";
        } else {
          this.invoice.invoice_delivery_date = "";
        }

        this.invoice.invoice_positions.forEach((element) => {
          element.description.replace(/\r?\n/g, "<br />");
        });
        await axios
          .post("http://127.0.0.1:8000/api/offers", this.invoice)
          .then((res) => {
            this.invoice.invoice_number = res.data.invoice_number;
          })
          .catch((err) => {
            window.alert("Fehler beim Speichern des Entwurfs!");
          });
        this.createDraftInvoiceButtonText = "Entwurf gespeichert!";
        setTimeout(() => {
          this.createDraftInvoiceButtonText = "Entwurf speichern";
        }, 2000);
      } else {
        this.createDraftInvoiceButtonText = "Bitte füllen Sie alle Felder aus!";
        setTimeout(() => {
          this.createDraftInvoiceButtonText = "Entwurf speichern";
        }, 2000);
      }
    },
    async generateInvoice() {
      this.createInvoiceButtonText = "Angebot wird erstellt...";
      if (this.validateInvoice()) {
        if (!this.customer.customer_number) {
          let customer = await this.generateCustomer().then((customer) => {
            console.log(customer);
            return customer;
          });
          this.customer = customer;
          this.invoice.customer_id = customer.id;
          this.invoice.customer_number = customer.customer_number;
        }
        this.invoice.invoice_status = "entwurf";
        this.invoice.invoice_total = this.totalWithTax;
        this.invoice.invoice_subtotal = this.total;
        this.invoice.invoice_tax = this.taxTotal;

        if (this.lieferdatum) {
          this.invoice.invoice_delivery_start_date = "";
          this.invoice.invoice_delivery_end_date = "";
        } else {
          this.invoice.invoice_delivery_date = "";
        }

        this.invoice.invoice_positions.forEach((element) => {
          //replace linebreaks and wrap in <p> tags around each line
          element.description = element.description
            .replace(/\r?\n/g, "<br />")
            .split("<br />")
            .map((line) => `<p>${line}</p>`)
            .join("");
        });
        await axios
          .post("http://127.0.0.1:8000/api/offers", this.invoice)
          .then((res) => {
            this.invoice.invoice_number = res.data.invoice_number;
            this.$router.push("/send-offer/" + res.data.data.invoice_number);
          })
          .catch((err) => {
            this.createInvoiceButtonText = "Fehler beim Erstellen der Angebot!";
            setTimeout(() => {
              this.createInvoiceButtonText = "Angebot erstellen";
            }, 2000);
          });
      } else {
        this.createInvoiceButtonText = "Bitte überprüfe die Eingaben!";
        setTimeout(() => {
          this.createInvoiceButtonText = "Angebot erstellen";
        }, 2000);
      }
    },
    validateInvoice() {
      //mark invalid fields with red border by ref
      let valid = true;
      if (!this.invoice.invoice_date) {
        this.$refs.invoiceDateInput.classList.add("is-invalid");
        valid = false;
      } else {
        this.$refs.invoiceDateInput.classList.remove("is-invalid");
      }

      if (!this.invoice.invoice_start_text) {
        this.$refs.invoiceStartTextInput.classList.add("is-invalid");
        valid = false;
      } else {
        this.$refs.invoiceStartTextInput.classList.remove("is-invalid");
      }

      if (!this.invoice.invoice_title) {
        this.$refs.invoiceTitleInput.classList.add("is-invalid");
        valid = false;
      } else {
        this.$refs.invoiceTitleInput.classList.remove("is-invalid");
      }

      if (valid) {
        return true;
      } else {
        return false;
      }
    },
    setCutomerData(customer) {
      this.customer = customer;
      console.log(customer);
      this.invoice.customer_number = customer.customer_number;
      this.invoice.customer_id = customer.id;
    },
    updatePosition(index) {
      const position = this.invoice.invoice_positions[index];
      //display price with 2 decimals
      position.price = parseFloat(position.price).toFixed(2);
      //display mwst with 2 decimals
      position.tax = parseFloat(position.tax).toFixed(2);
      position.amount =
        position.quantity * position.price * (1 - position.discount / 100);
    },
    AddInvoiceEntry() {
      this.invoice.invoice_positions.push({
        name: "",
        position: this.invoice.invoice_positions.length + 1,
        quantity: 1,
        unit: "Stunde",
        price: 0.0,
        tax: 19.0,
        discount: 0.0,
        amount: 0.0,
        description: "",
      });
    },
    removeInvoicePosition(index) {
      this.invoice.invoice_positions.splice(index, 1);
    },
    calculatePaymentDate() {
      const dueDate = new Date(this.invoice.invoice_date);

      // Calculate the due date based on the selected payment term
      dueDate.setDate(dueDate.getDate() + parseInt(this.zahlungsziel));
      return dueDate.toLocaleDateString("de-DE");
    },
  },
  beforeMount() {
    this.invoice.invoice_date = new Date().toISOString().slice(0, 10);
    this.invoice.invoice_delivery_date = new Date().toISOString().slice(0, 10);
    this.leistungszeitraumStart = new Date().toISOString().slice(0, 10);
    this.leistungszeitraumEnde = new Date().toISOString().slice(0, 10);

    this.invoice.invoice_payment_condition = `Zahlbar innerhalb von ${
      this.zahlungsziel
    } Tagen nach Rechnungsdatum (Zahlungsziel: ${this.calculatePaymentDate()})`;

    this.invoice.invoice_due_date = this.calculatePaymentDate();
  },
};
</script>

<style>
.divTable {
  width: 100%;
}

.divTableRow {
  display: flex;
}

.divTableHead {
  background-color: #eee;
  display: table-header-group;
  padding: 10px 5px;
  font-weight: bold;
  height: 100%;
}

.divTableRow {
  display: flex;
  align-items: center;
}

.divTableCol {
  border-bottom: 1px solid #ddd;
}

.divTableCell {
  padding: 10px 5px;
}

.divTableCell input,
.divTableCell select,
.divTableCell textarea {
  width: 100%;
  border: none;
  background-color: transparent;
  border: 1px solid #888888;
  border-radius: 5px;
  padding: 10px 10px;
}

.is-invalid {
  border: 1px solid red !important;
}

.is-valid {
  border: 1px solid green !important;
}
</style>
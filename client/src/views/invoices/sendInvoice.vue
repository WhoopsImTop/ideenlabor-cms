<template>
  <defaultLayout>
    <div class="sendInvoiceGrid">
      <iframe
        :src="pdfSource"
        ref="invoice"
        width="100%"
        height="100%"
      ></iframe>
      <div class="sidebar p-4">
        <h2 class="font-bold text-lg">Rechnung versenden</h2>
        <hr class="my-4" />

        <div class="mt-4">
          <label>
            Email-Adresse:
            <input
              class="border-2 border-solid border-gray-200 rounded px-2 py-1"
              type="text"
              placeholder="Email Adresse"
              v-model="invoiceEmail"
            />
          </label>
        </div>

        <div class="mt-4">
          <textarea
            v-model="invoiceEmailMessage"
            cols="30"
            rows="10"
          ></textarea>
        </div>

        <div class="mt-4 flex align-center">
          <button
            :disabled="loadingBtn"
            @click="sendInvoiceViaEmail"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          >
            {{ loadingBtnText }}
          </button>
        </div>

        <p>{{ successResponse }}</p>
      </div>
    </div>
  </defaultLayout>
</template>

<script>
import defaultLayout from "../../layouts/defaultLayout.vue";
import axios from "axios";
import VuePdfEmbed from "vue-pdf-embed";
export default {
  name: "createInvoice",
  props: {},
  components: {
    VuePdfEmbed,
    defaultLayout,
  },
  data() {
    return {
      invoice: null,
      pdfSource: null,
      invoiceEmail: null,
      successResponse: "",
      invoiceEmailMessage: `Sehr geehrte Damen und Herren,
Im Anhang finden Sie die Rechnung {{ invoice.invoice_number }} vom {{ invoice.invoice_date }}.
Bei Fragen stehen wir Ihnen gerne zur Verfügung.

Mit freundlichen Grüßen
Christian Bucher`,
      loadingBtn: false,
      loadingBtnText: "Rechnung per Email versenden",
    };
  },
  methods: {
    sendInvoiceViaEmail() {
      this.loadingBtn = true;
      this.loadingBtnText = "Lade...";
      //replace \n with <br> for html email
      let invoiceMessage = this.invoiceEmailMessage.replace(/\n/g, "<br>");
      axios
        .post("/api/invoices/send/" + this.$route.params.invoice_number, {
          email: this.invoiceEmail,
          subject: "Rechnung " + this.invoice.invoice_number,
          message: invoiceMessage,
        })
        .then((response) => {
          this.successResponse = response.data.message;
          this.loadingBtn = false;
          this.loadingBtnText = "Rechnung erfolgreich versendet";
          setTimeout(() => {
            this.loadingBtnText = "Rechnung per Email versenden";
          }, 3000);
        })
        .catch((error) => {
          this.loadingBtn = false;
          this.loadingBtnText = "Rechnung per Email versenden";
          console.log(error);
          window.alert("Es ist ein Fehler aufgetreten");
        });
    },
  },
  created() {
    axios
      .get("/api/invoices/" + this.$route.params.invoice_number)
      .then((response) => {
        this.invoice = response.data.data;
        this.invoiceEmail = this.invoice.customer.customer_email;
        //display pdf in canvas
        this.pdfSource =
          "https://ideenlabor-agentur.de/api/public/" +
          this.invoice.invoice_path;
        this.invoiceEmailMessage = this.invoiceEmailMessage
          .replace("{{ invoice.invoice_number }}", this.invoice.invoice_number)
          .replace(
            "{{ invoice.invoice_date }}",
            new Date(this.invoice.invoice_date).toLocaleDateString("de-DE")
          );
      })
      .catch((error) => {
        window.alert("Es ist ein Fehler aufgetreten");
      });
  },
};
</script>

<style>
.sendInvoiceGrid {
  display: grid;
  grid-template-columns: 3fr 1fr;
  grid-template-rows: 1fr;
  grid-template-areas: "pdf sidebar";
  height: 100vh;
}

.pdf {
  grid-area: pdf;
}

.sidebar {
  grid-area: sidebar;
  background-color: #f5f5f5;
  padding: 20px;
}

textarea {
  width: 100%;
  border: 2px solid #e2e8f0;
  border-radius: 0.25rem;
  padding: 0.5rem;
}
</style>

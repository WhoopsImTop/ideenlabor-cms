<template>
  <div
    class="absolute translate-x-1/2 right-1/2 top-1/2 -translate-y-1/2 bg-white shadow-sm border border-pink-200 rounded-lg w-[40vw] min-w-[600px]"
  >
    <div
      v-if="lostPasswordPopUp"
      id="lostPassword"
      class="absolute translate-x-1/2 right-1/2 top-1/2 -translate-y-1/2 bg-white border border-pink-200 shadow-sm p-3 rounded-lg w-[35vw] min-w-[500px]"
    >
      <div
        class="inline-flex items-center justify-between w-full border-b border-pink-200 pb-3"
      >
        <div>
          <h3 class="font-bold text-pink-950">
            Sie haben ihr Passwort vergessen ?
          </h3>
          <p class="text-xs text-pink-950">
            Aus Sicherheitsgründen, müssen wir das Passwort für Sie
            zurücksetzen.
          </p>
        </div>
        <div
          class="hover:bg-pink-50 hover:cursor-pointer w-7 h-7 rounded-full flex items-center justify-center"
          @click="lostPasswordPopUp = false"
        >
          <img src="../../assets/close.svg" width="20" height="20" />
        </div>
      </div>
      <p class="mb-2 mt-4 text-sm text-pink-950">
        Schreiben Sie eine Email an uns und wir werden ihnen schnellstmöglich
        das Passwort zurücksetzen.
      </p>
      <a
        class="bg-pink-50 rounded-lg px-3 py-2 text-pink-950 uppercase font-bold text-xs inline-block mt-4"
        href="mailto:englen@khri8.com"
        >khri8! Kontaktieren</a
      >
    </div>

    <div
      class="inline-flex items-center justify-between w-full p-3 border-b border-pink-200"
    >
      <h3 class="font-bold text-pink-950">Einstellungen</h3>
      <div
        class="hover:bg-pink-50 hover:cursor-pointer w-7 h-7 rounded-full flex items-center justify-center"
        @click="$emit('close-popup')"
      >
        <img src="../../assets/close.svg" width="20" height="20" />
      </div>
    </div>

    <div class="p-3">
      <div id="Unternehmensinformationen">
        <h3 class="font-bold text-pink-950 text-xs uppercase mt-2 mb-1">
          Unternehmensinformationen
        </h3>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-xs text-pink-950 ml-1">Firmenname</label>
            <input
              type="text"
              class="w-full border border-pink-200 rounded-lg py-2 px-3 focus:outline-pink-600/50"
              placeholder="Firmenname"
              v-model="customerCenter.customer_company_name"
            />
          </div>
          <div>
            <label class="text-xs text-pink-950 ml-1">Ansprechpartner</label>
            <input
              type="text"
              class="w-full border border-pink-200 rounded-lg py-2 px-3 focus:outline-pink-600/50"
              placeholder="Vor- und Nachname"
              v-model="customerCenter.customer_name"
            />
          </div>
          <div>
            <label class="text-xs text-pink-950 ml-1"> Rechnungs-E-Mail </label>
            <input
              type="text"
              class="w-full border border-pink-200 rounded-lg py-2 px-3 focus:outline-pink-600/50"
              placeholder="Rechnungs-E-Mail"
              v-model="customerCenter.customer_email"
            />
          </div>
          <div>
            <label class="text-xs text-pink-950 ml-1">Straße</label>
            <input
              type="text"
              class="w-full border border-pink-200 rounded-lg py-2 px-3 focus:outline-pink-600/50"
              placeholder="Straße"
              v-model="customerCenter.customer_street"
            />
          </div>
          <div>
            <label class="text-xs text-pink-950 ml-1">PLZ</label>
            <input
              type="text"
              class="w-full border border-pink-200 rounded-lg py-2 px-3 focus:outline-pink-600/50"
              placeholder="Postleitzahl"
              v-model="customerCenter.customer_zip"
            />
          </div>
          <div>
            <label class="text-xs text-pink-950 ml-1">Stadt</label>
            <input
              type="text"
              class="w-full border border-pink-200 rounded-lg py-2 px-3 focus:outline-pink-600/50"
              placeholder="Stadt"
              v-model="customerCenter.customer_city"
            />
          </div>
        </div>
        <div class="border-b border-pink-200 my-4"></div>
        <div v-if="!customerCenter.customer_password">
          <h3 class="font-bold text-pink-950 text-xs uppercase mt-2 mb-1">
            Passwortschutz für Rechnungen
          </h3>
          <p class="text-pink-950 text-xs mb-3">
            Hier können Sie ggf. ein Passwort für die Rechnungen festlegen.
          </p>
          <div class="relative">
            <input
              type="password"
              class="w-full border border-pink-200 rounded-lg py-2 px-3 focus:outline-pink-600/50"
              v-model="password"
            />
          </div>
        </div>
        <div v-else>
          <div class="flex items-center">
            <img src="../../assets/kundencenter/protected.svg" width="60" />
            <div class="flex flex-col ml-2">
              <h3 class="text-pink-950 font-bold text-lg">
                Passwort wurde gesetzt.
              </h3>
              <p class="text-pink-950 text-xs">
                Sie haben den Passwortschutz für die Rechnungen aktiviert
              </p>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4 mt-2">
            <div class="flex flex-col">
              <label class="text-xs text-pink-950 ml-1 mb-1"
                >Altes Passwort</label
              >
              <input
                type="password"
                class="w-full border border-pink-200 rounded-lg py-2 px-3 focus:outline-pink-600/50"
                v-model="old_password"
              />
            </div>
            <div class="flex flex-col">
              <label class="text-xs text-pink-950 ml-1 mb-1"
                >Neues Passwort</label
              >
              <input
                type="password"
                class="w-full border border-pink-200 rounded-lg py-2 px-3 focus:outline-pink-600/50"
                v-model="new_password"
              />
            </div>
          </div>
          <span
            class="block text-xs text-pink-950 text-right italic mr-2 mt-1 underline hover:cursor-pointer hover:text-pink-800"
            @click="lostPasswordPopUp = true"
            >passwort vergessen?</span
          >
        </div>

        <div class="border-b border-pink-200 my-3"></div>

        <div class="flex items-center justify-end relative">
          <div
            v-if="passwordChangeMessage"
            class="bg-pink-50 text-pink-950 text-xs uppercase rounded-lg py-2 px-3 font-bold mr-2 absolute left-0"
          >
            {{ passwordChangeMessage }}
          </div>
          <button
            class="bg-pink-50 hover:bg-pink-100 hover:cursor-pointer text-pink-950 text-xs uppercase rounded-lg py-2 px-3 font-bold mr-2"
            @click="saveCustomerCenter"
          >
            Speichern
          </button>
          <button
            class="bg-zinc-50 hover:bg-zinc-100 hover:cursor-pointer text-pink-950 text-xs uppercase rounded-lg py-2 px-3 font-bold"
            @click="$emit('close-popup')"
          >
            Abbrechen
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      customerCenter: this.$store.state.customerCenter,
      password: "",
      old_password: "",
      new_password: "",
      passwordChangeMessage: "",
      lostPasswordPopUp: false,
    };
  },
  methods: {
    saveCustomerCenter() {
      let body = {};

      body.customer_company_name =
        this.customerCenter.customer_company_name || "";
      body.customer_name = this.customerCenter.customer_name || "";
      body.customer_email = this.customerCenter.customer_email || "";
      body.customer_street = this.customerCenter.customer_street || "";
      body.customer_zip = this.customerCenter.customer_zip || "";
      body.customer_city = this.customerCenter.customer_city || "";

      if (this.password) {
        body.customer_password = this.password;
      }

      if (this.new_password) {
        body.new_password = this.new_password;
      }

      if (this.old_password) {
        body.old_password = this.old_password;
      }

      axios
        .post(
          "/api/customerCenter/updateCustomerInformation/" +
            this.customerCenter.id,
          body
        )
        .then((res) => {
          if (res.data.success) {
            this.passwordChangeMessage = "Daten wurden gespeichert";
            setTimeout(() => {
              this.passwordChangeMessage = "";
            }, 3000);
          }
        })
        .catch((err) => {
          this.passwordChangeMessage = err.response.data.message;
        });
    },
  },
};
</script>

<style></style>

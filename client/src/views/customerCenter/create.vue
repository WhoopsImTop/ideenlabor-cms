<template>
  <defaultLayout>
    <h1>Kundencenter erstellen</h1>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Kunde Auswählen</h3>
      <div class="grid grid-cols-2 gap-16 py-4">
        <div class="col-span-1">
          <div class="flex flex-col">
            <customer-search-component
              ref="customerSelect"
              @customerSelected="setCutomerData"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Aktionen</h3>
      <div class="py-4">
        <button
          class="btn px-4 py-2 rounded bg-blue-600 text-white"
          @click="generateCustomerCenter"
        >
          {{ createCustomerCenterButtonText }}
        </button>
      </div>
    </div>
  </defaultLayout>
</template>

<script>
import defaultLayout from "../../layouts/defaultLayout.vue";
import axios from "axios";
import CustomerSearchComponent from "../../components/customerSearchComponent.vue";
export default {
  data() {
    return {
      customer: {},
      createCustomerCenterButtonText: "Erstellen",
    };
  },
  components: {
    CustomerSearchComponent,
    defaultLayout,
  },
  methods: {
    generateCustomerCenter() {
      this.createCustomerCenterButtonText = "Erstelle...";
      axios
        .post("/api/customerCenters", {
          customer_id: this.customer.id,
        })
        .then((res) => {
          this.createCustomerCenterButtonText = "Erstellen";
          this.$router.push('/cms/customer-center/' + res.data.id);
        })
        .catch((err) => {
          this.createCustomerCenterButtonText = "Erstellen";
          console.log(err);
        });
    },
    setCutomerData(customer) {
      this.customer = customer;
      console.log(customer);
    },
  },
};
</script>

<style></style>

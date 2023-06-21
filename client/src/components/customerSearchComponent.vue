<template>
  <div class="customer_search">
    <input
      class="border-2 rounded border-solid p-2 w-full"
      placeholder="Name des Kunden"
      v-model="customer_name"
    />
    <div class="customer_search_resultes" v-if="selectedCustomer === null">
        <div class="result" v-for="result in filteredCustomers" :key="result.customer_number" @click="selectCustomer(result)">
            <p>{{ result.customer_name }}</p>
        </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      customer_name: "",
      customers: [],
      selectedCustomer: null,
    };
  },
  computed: {
    filteredCustomers() {
      return this.customers.filter((customer) => {
        return customer.customer_name
          .toLowerCase()
          .includes(this.customer_name.toLowerCase());
      });
    },
  },
  methods: {
    getData() {
        return this.customer_name;
    },
    selectCustomer(customer) {
        this.customer_name = customer.customer_name;
        this.selectedCustomer = customer;
        this.$emit("customerSelected", customer);
    },
  },
  beforeMount() {
    axios
      .get("http://127.0.0.1:8000/api/customers")
      .then((response) => {
        console.log(response.data.data);
        this.customers = response.data.data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style>
.customer_search {
    position: relative;
    width: 100%;
}

.customer_search_resultes {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background-color: white;
    border: 1px solid black;
    max-height: 200px;
    overflow-y: scroll;
    border-radius: 5px;
}

.customer_search_resultes .result {
    padding: 10px;
    border-bottom: 1px solid black;
}
</style>
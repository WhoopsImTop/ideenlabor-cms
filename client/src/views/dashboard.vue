<template>
  <div>
    <h1>Dashboard</h1>
    <div class="flex row">
      <div class="col p-5" style="border-right: 1px solid #dddddd">
        <h3>Brutto</h3>
        <p>{{ totalBrutto }} €</p>
      </div>
      <div class="col p-5">
        <h3>Netto</h3>
        <p>{{ (totalBrutto / 119) * 100 }} €</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "dashboard",
  data() {
    return {
      invoices: [],
      totalBrutto: 0,
    };
  },
  methods: {
    calculateTotal() {
      let total = 0;
      this.invoices.forEach((invoice) => {
        total += parseFloat(invoice.invoice_total);
      });
      this.totalBrutto = total.toFixed(2);
    },
  },
  mounted() {
    axios
      .get("/api/invoices")
      .then((res) => {
        this.invoices = res.data.data;
        this.calculateTotal();
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
</style>
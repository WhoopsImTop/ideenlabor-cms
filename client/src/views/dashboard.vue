<template>
  <defaultLayout>
    <h1>Dashboard</h1>
    <div class="flex row">
      <div class="col p-5" style="border-right: 1px solid #dddddd">
        <h3>Brutto</h3>
        <p>
          {{
            new Intl.NumberFormat("de-DE", {
              style: "currency",
              currency: "EUR",
            }).format(totalBrutto)
          }}
        </p>
      </div>
      <div class="col p-5">
        <h3>Netto</h3>
        <p>
          {{
            new Intl.NumberFormat("de-DE", {
              style: "currency",
              currency: "EUR",
            }).format((totalBrutto / 119) * 100)
          }}
        </p>
      </div>
    </div>
    <!-- draw a graph with the income by month -->
    <div class="col p-5" v-if="invoices.length > 0">
      <h3>Umsatz nach Monat</h3>
      <Line :data="chartData" :options="chartOptions"></Line>
    </div>
  </defaultLayout>
</template>

<script>
import defaultLayout from "../layouts/defaultLayout.vue";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
} from "chart.js";
import { Line } from "vue-chartjs";
import axios from "axios";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

export default {
  name: "dashboard",
  components: {
    Line,
    defaultLayout,
  },
  data() {
    return {
      invoices: [],
      chartData: {
        labels: [],
        datasets: [
          {
            label: "Umsatz",
            backgroundColor: "#f87979",
            data: [],
          },
        ],
      },
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
        // create an array with the months and the income for chart.js
        let months = [];
        let income = [];
        this.invoices.forEach((invoice) => {
          let date = new Date(invoice.invoice_date);
          let month = date.toLocaleString("default", { month: "long" });
          if (months.includes(month)) {
            let index = months.indexOf(month);
            income[index] += parseFloat(invoice.invoice_total);
          } else {
            months.push(month);
            income.push(parseFloat(invoice.invoice_total));
          }
        });

        this.chartData = {
          labels: months,
          datasets: [
            {
              label: "Umsatz",
              backgroundColor: "#f87979",
              borderColor: "#f87979",
              data: income,
            },
            {
              label: "Netto",
              backgroundColor: "#82ba2620",
              borderColor: "#82ba26",
              fill: true,
              data: income.map((value) => {
                return (value / 119) * 100;
              }),
            },
          ],
        };

        //interpolate the chart to make lines smooth
        this.chartOptions = {
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
              },
            ],
          },
          elements: {
            line: {
              tension: 0.4,
            },
          },
        };
      })
      .catch((err) => {
        if (err.response.data.message == "Unauthenticated.") {
          localStorage.removeItem("token");
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

<style></style>

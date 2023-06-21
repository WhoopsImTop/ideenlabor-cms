<template>
  <div style="position: relative">
    <input
      type="text"
      v-model="search"
      placeholder="Leistung Suchen"
      @focus="showSearchResults = true"
      @blur="hideSearchBar"
    />
    <div class="results" v-if="showSearchResults">
      <div
        v-for="service in filteredServices"
        :key="service.id"
        @click="setData(service)"
        class="result"
      >
        <h3>{{ service.service_name }}</h3>
        <p>{{ service.service_description }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: {
    index: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      search: "",
      services: [],
      selectedService: null,
      showSearchResults: false,
    };
  },
  computed: {
    filteredServices() {
      return this.services.filter((service) => {
        return service.service_name.match(this.search);
      });
    },
  },
  methods: {
    hideSearchBar() {
      setTimeout(() => {
        this.showSearchResults = false;
      }, 200);
    },
    getServices() {
      axios
        .get("http://127.0.0.1:8000/api/services")
        .then((response) => {
          this.services = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    setData(service) {
      this.selectedService = service;
      this.search = service.service_name;
      this.$emit("selectedService", this.selectedService, this.index);
      this.showSearchResults = false;
    },
    getData() {
      return this.selectedService;
    },
  },
  created() {
    this.getServices();
  },
};
</script>

<style>
.results {
  width: 300px;
  position: absolute;
  background-color: white;
  border: 1px solid #ccc;
  border-radius: 5px;
  overflow: hidden;
  z-index: 100;
  top: 60px;
}

.result {
  padding: 10px;
  cursor: pointer;
}

.result:hover {
  background-color: #ccc;
}

.result h3 {
  font-weight: bold;
}

.result p {
  font-size: 12px;
  color: #666;
}
</style>
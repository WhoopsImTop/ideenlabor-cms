<template>
  <div class="content-container full-height" @keyup.enter="login">
    <div class="login-container">
      <img
        class="login-logo-container"
        src="../assets/ideenlabor-logo-lok-prinzip-blau.svg"
        alt="logo"
      />
      <div class="login-container-form">
        <input
          class="form-input"
          type="email"
          v-model="email"
          placeholder="Email"
        />
        <input
          class="form-input"
          type="password"
          v-model="password"
          placeholder="Password"
        />
        <p class="error-message" v-if="errorMessage != ''">
          {{ errorMessage }}
        </p>
        <button class="login-button" @click="login">Anmelden</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "login",
  data() {
    return {
      email: "",
      password: "",
      errorMessage: "",
    };
  },
  methods: {
    login() {
      this.errorMessage = "";
      axios
        .post("/api/login", {
          email: this.email,
          password: this.password,
        })
        .then((res) => {
          localStorage.setItem("token", res.data.token);
          window.location.href = "/cms/";
        })
        .catch((err) => {
          this.errorMessage = "Email oder Passwort falsch!";
        });
    },
  },
};
</script>

<style scoped>
:root {
  --max-width: 1440px;
  --primary-color: #1a73e8;
  --text-white: #fff;
  --background-light: #f8f8f8;
  --paragraph-font-size: 16px;
  --paragraph-line-height: 22px;
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: "Poppins", sans-serif;
  font-size: var(--paragraph-font-size);
  line-height: var(--paragraph-line-height);
}

.content-container {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: 0 20px;
}

.full-height {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-container {
  max-width: 350px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  justify-content: center;
  margin-bottom: 100px;
}

.login-logo-container {
  width: 200px;
  margin: 0 auto;
  margin-bottom: 20px;
}

.login-container-form {
  margin-top: 20px;
  padding: 20px;
  border-radius: 5px;
  background-color: var(--background-light);
}

input,
textarea,
select {
  width: 100%;
  padding: 10px 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: var(--background-light);
}

.form-input {
  margin-bottom: 10px;
}

button {
  background-color: #1a73e8 !important;
  color: #ffffff !important;
  padding: 10px 15px !important;
  border: none !important;
  border-radius: 5px !important;
  cursor: pointer !important;
}

.login-button {
  width: 100%;
  margin-top: 20px;
}

.error-message {
  background-color: rgb(183, 48, 48);
  padding: 10px 15px;
  color: #fff;
  border-radius: 5px;
  font-size: 14px;
}
</style>

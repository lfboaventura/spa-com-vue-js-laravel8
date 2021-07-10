<template>
  <login-template>
    <span slot="leftMenu">
      <img
        src="http://www.emarket.ppg.br/wp-content/uploads/2013/08/descomplicando-as-midias-sociais-1030x928.jpeg"
        alt=""
        class="responsive-img"
      />
    </span>
    <span slot="main">
      <h4>Login</h4>
      <input type="text" placeholder="E-mail" v-model="email" />
      <input type="password" placeholder="E-Senha" v-model="password" />
      <button v-on:click="login" class="btn waves-effect waves-light">
        Entrar
      </button>
      <router-link to="/register" class="btn orange waves-effect waves-light"
        >Casdastre-se</router-link
      ></span
    >
  </login-template>
</template>

<script>
import LoginTemplate from "@/templates/LoginTemplate";

export default {
  name: "Login",
  components: {
    LoginTemplate,
  },
  data() {
    return { email: "", password: "" };
  },
  methods: {
    login() {
      this.$http
        .post(this.$urlAPI + `login`, {
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          if (response.data.status) {
            if (response.data.user.token) {
              this.$store.commit("setUser", response.data.user);
              sessionStorage.setItem(
                "user",
                JSON.stringify(response.data.user)
              );
              this.$router.push("/");
            } else {
              alert("Usuário inválido!");
            }
          } else {
            let errors = "";
            for (let e of Object.values(response.data.errors)) {
              errors += e + " ";
            }
            alert(response.data.message + "</br>" + errors);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>

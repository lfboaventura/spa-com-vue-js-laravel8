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
      <h4>Cadastro</h4>
      <input type="text" placeholder="Nome" v-model="name" />
      <input type="text" placeholder="E-mail" v-model="email" />
      <input type="password" placeholder="E-Senha" v-model="password" />
      <input
        type="password"
        name=""
        id=""
        placeholder="Confirme sua senha"
        v-model="password_confirmation"
      />
      <button v-on:click="register" class="btn waves-effect waves-light">
        Enviar
      </button>
      <router-link to="/login" class="btn orange waves-effect waves-light"
        >Já possuo conta</router-link
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
    return {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    };
  },
  methods: {
    register() {
      this.$http
        .post(this.$urlAPI + `register`, {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
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

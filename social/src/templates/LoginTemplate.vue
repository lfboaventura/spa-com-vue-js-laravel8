<template>
  <span>
    <header>
      <nav-bar-layout logo="Social" url="/" color="green darken-3">
        <li><router-link to="/">Home</router-link></li>
        <li><router-link to="/login">Entrar</router-link></li>
        <li><router-link to="/register">Cadastra-se</router-link></li>
      </nav-bar-layout>
    </header>
    <main>
      <div class="container">
        <div class="row">
          <grid-layout size="8">
            <card-menu-layout>
              <slot name="leftMenu" />
            </card-menu-layout>
          </grid-layout>
          <grid-layout size="4">
            <slot name="main" />
          </grid-layout>
        </div>
      </div>
    </main>

    <footer-layout
      color="green darken-3"
      logo="Social"
      description="Teste de descrição"
      year="2021"
    >
      <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
      <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
      <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
      <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
    </footer-layout>
  </span>
</template>

<script>
import NavBarLayout from "@/components/layouts/NavBarLayout";
import FooterLayout from "@/components/layouts/FooterLayout";
import GridLayout from "@/components/layouts/GridLayout";
import CardMenuLayout from "@/components/layouts/CardMenuLayout";

export default {
  name: "LoginTemplate",
  components: {
    NavBarLayout,
    FooterLayout,
    GridLayout,
    CardMenuLayout,
  },
  data() {
    return {
      user: false,
    };
  },
  created() {
    if (sessionStorage.getItem("user")) {
      this.user = JSON.parse(sessionStorage.getItem("user"));
    } else {
      this.user = false;
    }
  },
};
</script>

<style>
</style>

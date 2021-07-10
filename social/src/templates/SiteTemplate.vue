<template>
  <span>
    <header>
      <nav-bar-layout logo="Social" url="/" color="green darken-3">
        <li><router-link to="/">Home</router-link></li>
        <li>
          <router-link to="/profile">Meu Perfil</router-link>
        </li>
        <li><a v-on:click="exit()">Sair</a></li>
      </nav-bar-layout>
    </header>
    <main>
      <div class="container">
        <div class="row">
          <grid-layout size="4">
            <card-menu-layout>
              <slot name="leftMenu" />
            </card-menu-layout>
            <card-menu-layout>
              <slot name="leftMenuFriends" />
              <slot name="leftMenuFollowers" />
            </card-menu-layout>
          </grid-layout>
          <grid-layout size="8">
            <slot name="main" />
          </grid-layout>
        </div>
      </div>
    </main>

    <footer-layout
      color="green darken-3"
      logo="Social"
      description=""
      year="2021"
    >
      <!-- <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
      <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
      <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
      <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li> -->
    </footer-layout>
  </span>
</template>

<script>
import NavBarLayout from "@/components/layouts/NavBarLayout";
import FooterLayout from "@/components/layouts/FooterLayout";
import GridLayout from "@/components/layouts/GridLayout";
import CardMenuLayout from "@/components/layouts/CardMenuLayout";

export default {
  name: "SiteTemplate",
  components: {
    NavBarLayout,
    FooterLayout,
    GridLayout,
    CardMenuLayout,
  },
  created() {
    if (this.$store.getters.getUser) {
      this.user = this.$store.getters.getUser;
    } else {
      this.$router.push("/login");
    }
  },
  data() {
    return {
      user: false,
    };
  },
  methods: {
    exit() {
      this.$store.commit('setUser',null);
      sessionStorage.clear();
      this.$router.push("/login");
    },
  },
};
</script>

<style>
</style>

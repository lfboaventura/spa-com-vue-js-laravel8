<template>
  <site-template>
    <span slot="leftMenu">
      <img :src="user.image" :alt="name" class="responsive-img" />
    </span>
    <span slot="main">
      <h4>Perfil</h4>
      <input type="text" placeholder="Nome" v-model="name" />
      <input type="text" placeholder="E-mail" v-model="email" disabled />

      <div class="file-field input-field">
        <div class="btn">
          <span>Imagem</span>
          <input type="file" v-on:change="saveImage" />
        </div>
        <div class="file-path-wrapper">
          <input class="file-path validate" type="text" />
        </div>
      </div>

      <input type="password" placeholder="E-Senha" v-model="password" />
      <input
        type="password"
        name=""
        id=""
        placeholder="Confirme sua senha"
        v-model="password_confirmation"
      />

      <button v-on:click="saveProfile()" class="btn waves-effect waves-light">
        Atualizar
      </button>
      <router-link to="/login" class="btn orange waves-effect waves-light"
        >Já possuo conta</router-link
      ></span
    >
  </site-template>
</template>

<script>
import SiteTemplate from "@/templates/SiteTemplate";

export default {
  name: "Profile",
  components: {
    SiteTemplate,
  },
  data() {
    return {
      user: "",
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      image: "",
    };
  },
  created() {
    if (sessionStorage.getItem("user")) {
      this.user = JSON.parse(sessionStorage.getItem("user"));
      this.name = this.user.name;
      this.email = this.user.email;
    } else {
      this.$router.push("/login");
    }
  },
  methods: {
    saveImage(e) {
      let f = e.target.files || e.dataTransfer.files;
      if (!f.length) {
        return;
      }

      let reader = new FileReader();
      reader.onloadend = (e) => {
        this.image = e.target.result;
      };
      reader.readAsDataURL(f[0]);
    },
    saveProfile() {
      this.$http
        .put(
          this.$urlAPI + `profile`,
          {
            name: this.name,
            image: this.image,
            password: this.password,
            password_confirmation: this.password_confirmation,
          },
          { headers: { authorization: "Bearer " + this.user.token } }
        )
        .then((response) => {
          if (response.data.status) {
            if (response.data.user.token) {
              this.user.image = response.data.user.image;
              this.name = response.data.user.name;
              sessionStorage.setItem(
                "user",
                JSON.stringify(response.data.user)
              );
              alert(response.data.message);
            } else {
              alert(response.data.message);
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

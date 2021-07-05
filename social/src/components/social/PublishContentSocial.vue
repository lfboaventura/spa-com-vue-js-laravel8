<template>
  <div class="row">
    <grid-layout class="input-field" size="12">
      <input type="text" v-model="content.title" />
      <textarea
        v-if="content.title"
        id="contentId"
        v-model="content.text"
        placeholder="Conteúdo"
        class="materialize-textarea"
      ></textarea>
      <input
        v-if="content.title && content.text"
        placeholder="link"
        type="text"
        v-model="content.link"
      />
      <input
        v-if="content.title && content.text"
        placeholder="url da image"
        type="text"
        v-model="content.image"
      />
      <label for="contentId">O que está acontecendo</label>
    </grid-layout>
    <p v-if="content.title && content.text" class="right-align">
      <button @click="addContent()" class="btn waves-effect waves-light">
        Publicar
      </button>
    </p>
  </div>
</template>

<script>
import GridLayout from "@/components/layouts/GridLayout";
import ButtonComponents from "@/components/forms/ButtonComponents";

export default {
  name: "PublishContentSocial",
  props: ["user"],
  data() {
    return {
      content: { title: "", text: "", link: "", image: "" },
    };
  },
  methods: {
    addContent() {
      this.$http
        .post(
          this.$urlAPI + `content/add`,
          {
            title: this.content.title,
            text: this.content.text,
            link: this.content.link,
            image: this.content.image,
          },
          {
            headers: { authorization: "Bearer " + this.user.token },
          }
        )
        .then((response) => {
          debugger;
          if (response.data.status) {
            alert(response.data.message);
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
  components: {
    GridLayout,
    ButtonComponents,
  },
};
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>

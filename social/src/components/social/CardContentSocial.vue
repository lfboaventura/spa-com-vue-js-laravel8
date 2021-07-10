<template>
  <div class="row">
    <div class="card">
      <div class="card-content">
        <div class="row valign-wrapper">
          <grid-layout size="1">
            <router-link :to="'/page/' + userContent.id + '/' + $slug(userContent.name) ">
              <img
                :src="userContent.image"
                :alt="userContent.name"
                class="circle responsive-img"
              />
            </router-link>
          </grid-layout>
          <grid-layout size="11">
            
              <span class="black-text">
                <strong>
                  <router-link :to="'/page/' + userContent.id + '/' + $slug(userContent.name) ">{{ userContent.name }}</router-link>
                </strong> -
                <small>{{ dateTimePost }}</small>
              </span>
            
          </grid-layout>
        </div>
      </div>

      <slot></slot>

      <div class="card-action">
        <p>
          <a style="cursor: pointer" @click="setLiked(idContent)">
            <i
              :style="liked ? 'color:red' : ''"
              class="material-icons"
              >{{ liked ? "favorite" : "favorite_border" }}</i
            >{{ quantityLikes }}</a
          >
          <a
            style="cursor: pointer; color: black"
            @click="openComment(idContent)"
          >
            <i class="material-icons">insert_comment</i
              >{{ comments.length }}</a
            >
          </p>
          <p v-if="displayComments" class="right-align">
            <input type="text" placeholder="Comentar" v-model="comment">
            <button v-if="comment" @click="setComments(idContent)" class="btn waves-effect waves-light">Enviar</button>
        </p>
        <p v-if="displayComments">
          <ul class="collection">
            <li class="collection-item avatar" v-for="item in comments" :key="item.id">
              <img :src="item.user.image" :alt="item.user.name" class="circle">
              <span class="title">{{item.user.name}}<small> - {{item.created_at}}</small></span>
              <p>{{item.text}}</p>
            </li>
          </ul>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import GridLayout from "@/components/layouts/GridLayout";

export default {
  name: "CardContentSocial",
  props: [
    "dateTimePost",
    "idContent",
    "liked",
    "quantityLikes",
    "comments",
    "userContent",
  ],
  data() {
    return {
      displayComments: false,
      comment: "",
      next_page_url: "",
    };
  },
  components: {
    GridLayout,
  },
  methods: {
    setComments(idContent) {
      this.$http
        .post(
          this.$urlAPI + `content/comment/` + idContent,
          {
            text: this.comment,
            userPage: this.$route.name === "Page" ? this.$route.params.id : null,
          },
          {
            headers: {
              authorization: "Bearer " + this.$store.getters.getToken,
            },
          }
        )
        .then((response) => {
        debugger;
          if (response.data.status) {
            this.$store.commit("setTimeline", response.data.list.data);
            this.comment = "";
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
    openComment() {
      this.displayComments = !this.displayComments;
    },
    setLiked(idContent) {
      this.$http
        .put(
          this.$urlAPI + `content/liked/` + idContent,
          {
            userPage: this.$route.name === "Page" ? this.$route.params.id : null,
          },
          {
            headers: {
              authorization: "Bearer " + this.$store.getters.getToken,
            },
          }
        )
        .then((response) => {
          debugger;
          if (response.data.status) {
            this.liked = response.data.liked;
            this.quantityLikes = response.data.quantityLikes;
            this.comments = response.data.comments;
            this.$store.commit("setTimeline", response.data.list.data);
          } else {
            let errors = "";
            for (let e of Object.values(response.data.errors)) {
              errors += e + " ";
            }
            alert(response.data.message + "</br>" + errors);
          }
        })
        .catch((error) => {
          debugger;
          alert(error.message);
        });
    },
  },
};
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>

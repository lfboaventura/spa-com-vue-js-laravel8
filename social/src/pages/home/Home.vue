<template>
  <site-template>
    <span slot="leftMenu">
      <div class="row valign-wrapper">
        <grid-layout size="4">
          <router-link :to="'/page/' + user.id + '/' + $slug(user.name)">
            <img
              :src="user.image"
              :alt="user.name"
              class="circle responsive-img"
            />
          </router-link>
        </grid-layout>
        <grid-layout size="8">
          <router-link :to="'/page/' + user.id + '/' + $slug(user.name)">
            <span class="black-text">
              <h5>{{ user.name }}</h5>
            </span>
          </router-link>
        </grid-layout>
      </div>
    </span>

    <span slot="leftMenuFriends">
      <h6>
        <b>{{ friends ? "Seguindo" : "Não Seguindo Ninguém" }} </b>
      </h6>
      <li class="collection-item avatar" v-for="item in friends" :key="item.id">
        <router-link :to="'/page/' + item.id + '/' + $slug(item.name)">
          <span class="title">{{ item.name }}</span>
        </router-link>
      </li>
    </span>

    <span slot="leftMenuFollowers">
      <h6>
        <b>{{ followers ? "Seguidores" : "Sem Seguidores" }} </b>
      </h6>
      <li class="collection-item avatar" v-for="item in followers" :key="item.id">
        <router-link :to="'/page/' + item.id + '/' + $slug(item.name)">
          <span class="title">{{ item.name }}</span>
        </router-link>
      </li>
    </span>

    <span slot="main">
      <publish-content-social />
      <card-content-social
        v-for="item in listContents"
        :key="item.id"
        :dateTimePost="item.created_at"
        :idContent="item.id"
        :quantityLikes="item.quantityLikes"
        :liked="item.liked"
        :comments="item.comments"
        :userContent="item.user"
      >
        <card-detail-social
          :image="item.image"
          :text="item.text"
          :title="item.title"
          :link="item.link"
        >
        </card-detail-social>
      </card-content-social>
      <p v-if="next_page_url && 1 === 2" class="center-align">
        <button @click="moreContent" class="btn waves-effect waves-light">
          Carregar mais publicações...
        </button>
      </p>
      <div v-scroll="handleScrollHome"></div>
    </span>
  </site-template>
</template>

<script>
import SiteTemplate from "@/templates/SiteTemplate";
import CardContentSocial from "@/components/social/CardContentSocial";
import CardDetailSocial from "@/components/social/CardDetailSocial";
import GridLayout from "@/components/layouts/GridLayout";
import PublishContentSocial from "@/components/social/PublishContentSocial";

export default {
  name: "Home",
  components: {
    SiteTemplate,
    CardContentSocial,
    CardDetailSocial,
    GridLayout,
    PublishContentSocial,
  },
  computed: {
    listContents() {
      return this.$store.getters.getTimeline;
    },
  },
  data() {
    return { user: "", next_page_url: "", stopScroll: false, friends: false, followers: false };
  },
  created() {
    if (sessionStorage.getItem("user")) {
      this.user = JSON.parse(sessionStorage.getItem("user"));
      this.name = this.user.name;
      this.email = this.user.email;

      this.$http
        .get(this.$urlAPI + `content/list`, {
          headers: {
            authorization: "Bearer " + this.$store.getters.getToken,
          },
        })
        .then((response) => {
          debugger;
          if (response.data.status) {
            this.$store.commit("setTimeline", response.data.contents.data);
            this.next_page_url = response.data.contents.next_page_url;
          }
        })
        .catch((error) => {
          alert(error.message);
        });

      this.$http
        .get(this.$urlAPI + `user/friends/` + this.user.id, {
          headers: {
            authorization: "Bearer " + this.$store.getters.getToken,
          },
        })
        .then((response) => {
          debugger
          if (response.data.status) {
            this.friends = response.data.friendsProfile;
            this.followers = this.followers = response.data.followers.length === 0 ? false : response.data.followers;
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    } else {
      this.$router.push("/login");
    }
  },
  methods: {
    handleScrollHome() {
      if (this.stopScroll) {
        return;
      }
      if (this.$route.name !== "Home") {
        return;
      }
      if (window.scrollY >= document.body.clientHeight - 970) {
        this.stopScroll = true;
        this.moreContent();
      }
    },
    moreContent() {
      if (!this.next_page_url) {
        return;
      }
      this.$http
        .get(this.next_page_url, {
          headers: {
            authorization: "Bearer " + this.$store.getters.getToken,
          },
        })
        .then((response) => {
          if (response.data.status) {
            this.$store.commit(
              "setUpdateTimeline",
              response.data.contents.data
            );
            this.next_page_url = response.data.contents.next_page_url;
            this.stopScroll = false;
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

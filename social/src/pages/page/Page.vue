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
          <a
            v-if="user.id !== userLogged.id"
            style="cursor: pointer"
            @click="setFollow(user.id)"
            class=""
            >{{ follow }}</a
          >
        </grid-layout>
      </div>
    </span>

    <span slot="leftMenuFriends">
      <h6>
        <b>{{ friends ? "Seguindo" : "Não Seguindo Ninguém" }} </b>
      </h6>
      <li v-for="item in friends" :key="item.id">
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
      <publish-content-social v-if="this.$route.params.id == this.userLogged.id"/>
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
      <div v-scroll="handleScrollPage"></div>
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
  name: "Page",
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
    return {
      user: "",
      next_page_url: "",
      stopScroll: false,
      userLogged: "",
      follow: "Seguir",
      friends: false,
      followers: false,
    };
  },
  created() {
    if (sessionStorage.getItem("user")) {
      this.updatePage();
    } else {
      this.$router.push("/login");
    }
  },
  watch:{
    '$route':"updatePage",
  },
  methods: {
    updatePage() {
      this.user = JSON.parse(sessionStorage.getItem("user"));
      this.userLogged = this.user;

      this.$http
        .get(this.$urlAPI + `content/page/` + this.$route.params.id, {
          headers: {
            authorization: "Bearer " + this.$store.getters.getToken,
          },
        })
        .then((response) => {
          if (response.data.status) {
            this.$store.commit("setTimeline", response.data.contents.data);
            this.next_page_url = response.data.contents.next_page_url;
            this.user = response.data.userPage;
          }
        })
        .catch((error) => {
          alert(error.message);
        });

      this.follow = "Seguir";

      this.$http
        .get(this.$urlAPI + `user/friends/` + this.$route.params.id, {
          headers: {
            authorization: "Bearer " + this.$store.getters.getToken,
          },
        })
        .then((response) => {
          if (response.data.status) {
            this.followers = response.data.followers.length === 0 ? false : response.data.followers;
            this.friends =
              this.$route.params.id != this.userLogged.id
                ? response.data.friendsPage.length === 0
                  ? false
                  : response.data.friendsPage
                : response.data.friendsProfile.length === 0
                ? false
                : response.data.friendsProfile;

            // const following = response.data.friendsProfile.find(
            //   (e) => (e = this.user)
            // );
            this.follow = response.data.friendsProfile ? "Seguindo" : "Seguir";
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    setFollow(id) {
      this.follow = "Seguir";
      this.$http
        .post(
          this.$urlAPI + `user/follow`,
          {
            id: id,
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
            this.follow = response.data.following ? "Seguindo" : "Seguir";
            this.followers = response.data.followers.length === 0 ? false : response.data.followers;
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
    handleScrollPage() {
      if (this.stopScroll) {
        return;
      }
      if (this.$route.name !== "Page") {
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

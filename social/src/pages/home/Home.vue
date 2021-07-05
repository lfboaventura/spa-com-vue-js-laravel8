<template>
  <site-template>
    <span slot="leftMenu">
      <div class="row valign-wrapper">
        <grid-layout size="4">
          <img
            :src="user.image"
            :alt="user.name"
            class="circle responsive-img"
          />
        </grid-layout>
        <grid-layout size="8">
          <span class="black-text">
            <h5>{{ user.name }}</h5>
          </span>
        </grid-layout>
      </div>
    </span>

    <span slot="main">
      <publish-content-social :user="user" />
      <card-content-social
        profilePicture="https://materializecss.com/images/yuna.jpg"
        nameProfile="Maria Silva"
        dateTimePost="29/06/2021 - 14:43"
      >
        <card-detail-social
          image="https://materializecss.com/images/sample-1.jpg"
          text="I am a very simple card. I am good at containing small bits of information. I am convenient because I require little markup to use effectively."
          title="Card Title"
        >
        </card-detail-social>
      </card-content-social>
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
  data() {
    return { user: "" };
  },
  created() {
    if (sessionStorage.getItem("user")) {
      this.user = JSON.parse(sessionStorage.getItem("user"));
      this.name = this.user.name;
      this.email = this.user.email;
      this.image = this.user.image;
    } else {
      this.$router.push("/login");
    }
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>

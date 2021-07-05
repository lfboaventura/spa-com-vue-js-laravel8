<template>
  <div class="row">
    <div class="card">
      <div class="card-content">
        <div class="row valign-wrapper">
          <grid-layout size="1">
            <img
              :src="profilePicture"
              :alt="nameProfile"
              class="circle responsive-img"
            />
          </grid-layout>
          <grid-layout size="11">
            <span class="black-text">
              <strong>{{ nameProfile }}</strong> -
              <small>{{ dateTimePost }}</small>
            </span>
          </grid-layout>
        </div>
      </div>

      <slot></slot>

      <div class="card-action">
        <p>
          <i class="material-icons">favorite_border</i>
          <i class="material-icons">insert_comment</i>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import GridLayout from "@/components/layouts/GridLayout";

export default {
  name: "CardContentSocial",
  props: ["profilePicture", "nameProfile", "dateTimePost"],
  data() {
    return {};
  },
  components: {
    GridLayout,
  },
};
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>

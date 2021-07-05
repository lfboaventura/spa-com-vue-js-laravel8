<template>
  <span>
    <div v-if="image" class="card-image">
      <img :src="image" />
      <span class="card-title">{{ title || "" }}</span>
    </div>

    <div v-if="text" class="card-content">
      <p>{{ text }}</p>
    </div>
  </span>
</template>

<script>
export default {
  name: "CardDetailSocial",
  props: ["image", "title", "text"],
  data() {
    return {};
  },
  components: {},
};
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>

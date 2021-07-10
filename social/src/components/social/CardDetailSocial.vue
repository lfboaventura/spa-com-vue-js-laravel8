<template>
  <span>
    <div v-if="image" class="card-image">
      <a v-if="link" :href="link" target="_blank">
        <img :src="image" />
        <span class="card-title">{{ title || "" }}</span>
      </a>
      <span v-if="!link">
        <img :src="image" />
        <span class="card-title">{{ title || "" }}</span>
      </span>
    </div>

    <div v-if="text" class="card-content">
      <span v-if="!image">
        <span class="card-title">{{ title || "" }}</span>
      </span>
      <p>{{ text }}</p>
      <p v-if="link"><a :href="link" target="_blank">Confira mais aqui...</a></p>
    </div>
  </span>
</template>

<script>
export default {
  name: "CardDetailSocial",
  props: ["image", "title", "text", "link"],
  data() {
    return {};
  },
  components: {},
};
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>

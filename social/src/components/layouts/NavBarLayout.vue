<template>

  <nav :class="color || 'green darken-3'">
    <div class="container">
      <div class="nav-wrapper">
        <router-link :to="url || '/'" class="brand-logo">{{ logo || "Site" }}</router-link>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <slot/>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "NavBarLayout",
  props: ["logo", "color", "url"],
  data() {
    return {};
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>

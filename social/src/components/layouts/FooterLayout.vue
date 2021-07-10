<template>
  <footer :class="'page-footer ' + color || 'green darken-3'">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">{{ logo || "Site" }}</h5>
          <p class="grey-text text-lighten-4">
            {{ description || "Sua descrição" }}
          </p>
        </div>
        <div class="col l4 offset-l2 s12">
          <h5 class="white-text">Links</h5>
          <ul>
            <slot></slot>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
        © {{ year || "2021" }} Copyright {{ logo || "Site" }}
        <a class="grey-text text-lighten-4 right" href="#!"></a>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "FooterLayouyt",
  props: ["logo", "color", "description", "year"],
  data() {
    return {};
  },
};
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>

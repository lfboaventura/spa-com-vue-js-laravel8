<template>
  <grid-layout
    :class="'btn waves-effect waves-light ' + color || ''"
    :size="size"
  >
    {{ title || "" }}
  </grid-layout>
</template>

<script>
import GridLayout from "@/components/layouts/GridLayout";

export default {
  name: "ButtonComponents",
  props: ["title", "color", "size"],
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

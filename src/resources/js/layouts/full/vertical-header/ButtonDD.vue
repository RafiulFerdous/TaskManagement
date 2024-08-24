<script setup>
import { useRoute, useRouter } from "vue-router";
import Environment from "@/environments/environment";

// Write data definition statements here
const route = useRoute();
const router = useRouter();

const redirectToHome = () => {
  router.push("/");
};
</script>

<template>
  <a :href="Environment.liveUrl" target="_blank">
  <v-btn  variant="text"  class="custom-hover-primary ml-0 ml-md-5 " >
    Visit live site
  </v-btn>
  </a>
</template>


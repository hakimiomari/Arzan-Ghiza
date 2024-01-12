<template>
  <router-view />
</template>

<script setup>
import { onMounted } from "vue";
import "./plugins/Chart";

onMounted(() => {
  document.title = "Arzan Ghiza";
});
</script>

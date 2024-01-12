<template>
  <div
    class="row q-ma-lg justify-evenly items-center"
    style="height: 100vh"
  ></div>
</template>
<script setup>
import { onMounted } from "vue";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";

const router = useRouter();

onMounted(() => {
  Swal.fire({
    icon: "error",
    title: "Oops...",
    text: "Your are cancel payments",
    footer: '<a href="#">Why do I have this issue?</a>',
    timer: 2000,
  });
  setTimeout(() => {
    router.push("/");
  }, 3000);
});
</script>

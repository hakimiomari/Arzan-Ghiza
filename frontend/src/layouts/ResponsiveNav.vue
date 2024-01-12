<template>
  <div class="responsive">
    <div class="list">
      <router-link
        style="color: #fff; text-decoration: none"
        to="/"
        @click="toggleMenu"
        ><li>Posts</li></router-link
      >
      <router-link
        style="color: #fff; text-decoration: none"
        to="/"
        @click="toggleMenu"
        ><li>Services</li></router-link
      >
      <router-link
        style="color: #fff; text-decoration: none"
        to="/about"
        @click="toggleMenu"
        ><li>About</li></router-link
      >
    </div>
    <div style="margin-bottom: 7rem; width: 100%; padding: 0 2rem">
      <q-btn
        @click="toggleSignIn"
        style="
          width: 100%;
          background-color: #0d47a1;
          color: #fff;
          border-radius: 5px;
        "
        >Sign in</q-btn
      >
    </div>
  </div>
</template>
<script setup>
import { ref, defineProps } from "vue";

const props = defineProps({
  toggleMenu: Function,
  signInToggle: Function,
});

const toggleSignIn = () => {
  props.toggleMenu();
  setTimeout(() => {
    props.signInToggle();
  }, 500);
};
</script>
<style>
.responsive {
  height: 100%;
  position: fixed;
  top: 4.7rem;
  right: 0;
  left: 0;
  bottom: 0;
  background: #1976d2;
  z-index: 999;
  padding: 0;
  overflow: hidden;
  display: flex;
  justify-content: space-between;
  flex-direction: column;
}
.list {
  padding: 1rem;
  box-sizing: border-box;
  margin-top: 1rem;
  display: flex;
  flex-direction: column;
  color: #fff;
}

.subBussiness,
.subUser {
  padding-left: 1rem !important;
  display: flex;
  flex-direction: column;
}

.subBussiness > span,
.subUser > span {
  padding: 0.5rem 1rem;
  cursor: context-menu;
  background: transparent;
  border-radius: 5px;
}

li {
  font-size: 1rem;
  list-style: none;
  padding: 0.5rem 1rem;
  cursor: context-menu;
  background: transparent;
  border-radius: 5px;
}

li:hover,
.subUser > span:hover,
.subBussiness > span:hover {
  background: #639cd4;
}
</style>

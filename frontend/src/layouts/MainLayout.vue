<template>
  <div>
    <q-layout view="lHh Lpr lFf">
      <q-header elevated class="topHeader" style="z-index: 1">
        <div class="flex justify-between items-center" style="padding: 0 5px">
          <div class="signUp">
            <router-link
              to="/register"
              style="color: #fff; text-decoration: none"
              ><q-btn
                @click="toggleLeftDrawer = false"
                flat
                label="Sign up"
                style="border: 0.5px solid #faeded"
            /></router-link>
          </div>
          <div class="row items-center">
            <router-link to="/" style="color: #fff; text-decoration: none">
              <q-toolbar-title
                ><q-img
                  src="../assets/logo3.png"
                  style="width: 100px; height: 100%"
                  class="logo"
                ></q-img>
              </q-toolbar-title>
            </router-link>
            <div class="row login-tabs" style="margin-left: 2rem">
              <q-tabs
                inline-label
                active-color=""
                v-model="activeTab"
                narrow-indicator
              >
                <q-route-tab name="post" label="Posts" to="/" />
                <q-route-tab name="service" label="Services" to="/services" />
                <q-route-tab to="/about" name="about" label="About" />
              </q-tabs>
            </div>
          </div>
          <div class="registration-btns flex items-center">
            <q-btn flat label="Sign in" @click="signInToggle" />
            <Login v-model="signIn" />
            <router-link
              style="text-decoration: none; color: #fff"
              to="/register"
              ><q-btn flat label="Sign Up"
            /></router-link>
          </div>
          <div class="icon">
            <q-btn
              v-if="!toggleLeftDrawer"
              flat
              icon="menu"
              @click="toggleMenu"
            />

            <q-btn
              flat
              v-if="toggleLeftDrawer"
              @click="toggleMenu"
              class="material-icons"
              icon="close"
            ></q-btn>
          </div>
        </div>
      </q-header>

      <Transition name="fade">
        <ResponsiveNav
          v-if="toggleLeftDrawer"
          :toggleMenu="toggleMenu"
          :signInToggle="signInToggle"
        />
      </Transition>

      <q-page-container>
        <router-view> </router-view>
      </q-page-container>
    </q-layout>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import Login from "../pages/login/Login.vue";
import ResponsiveNav from "./ResponsiveNav.vue";
import { useRouter } from "vue-router";

const router = useRouter();

const toggleLeftDrawer = ref(false);
const toggleMenu = async () => {
  toggleLeftDrawer.value = !toggleLeftDrawer.value;
};

const signIn = ref(false);
const signInToggle = () => {
  signIn.value = !signIn.value;
};

const activeTab = ref("post");
const localStorageKey = "myAppActiveTab";

onMounted(() => {
  restoreActiveTab();
});

watch(activeTab, (newTab) => {
  saveActiveTab(newTab);
});

function saveActiveTab(tabName) {
  localStorage.setItem(localStorageKey, tabName);
}

function restoreActiveTab() {
  const storedTab = localStorage.getItem(localStorageKey);
  if (storedTab && storedTab !== activeTab.value) {
    activeTab.value = storedTab;
  }
}
</script>

<style>
.icon,
.signUp {
  display: none;
}
@media screen and (max-width: 800px) {
  .topHeader {
    padding: 0.5rem 0;
  }
  .login-tabs,
  .registration-btns {
    display: none;
  }
  .responsive {
    display: flex;
  }
  .logo {
    height: 60px !important;
  }
  .icon,
  .signUp {
    display: block;
  }
}

.fade-enter-from {
  top: -100vh;
}
.fade-enter-to,
.fade-leave-from {
  top: 4.4rem;
}
.fade-enter-active,
.fade-leave-active {
  transition: all 1s ease;
}

.fade-leave-to {
  top: -100vh;
}
</style>

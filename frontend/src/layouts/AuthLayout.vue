<template lang="">
  <q-layout view="lHh Lpr lFf">
    <q-header elevated style="background-color: #1976d2; z-index: 1 !important">
      <div class="flex justify-between">
        <div class="flex items-center">
          <router-link
            style="text-decoration: none; color: #fff"
            to="/authenticated"
          >
            <q-toolbar-title
              ><q-img
                src="../assets/logo3.png"
                style="width: 100px; height: 100%"
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
              <q-route-tab to="/authenticated" name="post" label="Posts" />
              <q-route-tab
                name="service"
                label="Services"
                to="/auth-services"
              />
              <q-route-tab to="/auth-about" name="about" label="About" />
            </q-tabs>
          </div>
        </div>
        <div
          class="flex items-center"
          style="cursor: pointer; margin-right: 1rem"
          @click="toggleProfile"
        >
          <div
            style="
              width: 40px;
              height: 40px;
              border-radius: 50%;
              margin-right: 1rem;
            "
          >
            <img
              style="width: 100%; height: 100%; border-radius: 100%"
              :src="
                data.image ? data.image : 'src/assets/images/profile22.avif'
              "
            />
          </div>

          <span style="font-size: 1.2rem">{{ data?.name }}</span>
        </div>
        <Profile v-model="showProfile" />
      </div>
    </q-header>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>
<script setup>
import { onMounted, ref, watch } from "vue";
import { useRouter } from "vue-router";
import Profile from "../pages/Porfile/Profile.vue";
import axios from "axios";

const router = useRouter();
const showProfile = ref(false);

const toggleProfile = () => {
  showProfile.value = !showProfile.value;
};

// tabs //////////////////////////
const activeTab = ref("post");
const localStorageKey = "myAppActiveTab";

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
// tabs //////////////////////////

const user = ref("");
const data = ref("");
onMounted(async () => {
  restoreActiveTab();
  user.value = JSON.parse(localStorage.getItem("user"));
  await axios
    .get(`http://localhost:8000/api/user/getData/${user.value.id}`, {
      headers: { Authorization: `Bearer ${user.value.token}` },
    })
    .then((res) => {
      data.value = res.data.user;
    });
});
</script>

<style>
.fade-enter-from {
  right: -255px;
}
.fade-enter-to,
.fade-leave-from {
  right: 0;
}
.fade-enter-active,
.fade-leave-active {
  transition: all 0.5s ease;
}

.fade-leave-to {
  right: -255px;
}
</style>

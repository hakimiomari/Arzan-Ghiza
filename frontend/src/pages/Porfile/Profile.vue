<template lang="">
  <q-dialog transition-show="" position="top">
    <q-card
      style="
        width: 350px !important;
        border-radius: 5px;
        background-color: rgb(255, 255, 255);
        position: absolute !important;
        top: 4rem;
        right: 0.2rem;
        overflow: hidden !important;
        background: #1976d2;
        padding: 1rem 0;
      "
    >
      <q-card-section style="display: flex; justify-content: center">
        <div class="myImg">
          <img
            :src="data.image ? data.image : 'src/assets/images/profile22.avif'"
          />
        </div>
      </q-card-section>
      <q-card-section>
        <div class="flex column text-center">
          <span style="font-size: 1.2rem; margin-bottom: 0.4rem">{{
            data.name
          }}</span>
          <span style="font-size: 1rem; color: #d4d0d0">{{ data.email }}</span>
        </div>
      </q-card-section>
      <hr style="width: 100%; margin-top: 1rem" />
      <q-card-section style="padding: 26px 0">
        <div class="flex justify-start column" style="width: 100%; gap: 0.7rem">
          <span class="profile-list"
            ><router-link
              style="
                text-decoration: none;
                color: #fff;
                display: flex;
                align-items: center;
              "
              to="/profile"
              @click="toggleProfile"
              ><q-icon
                name="person"
                style="font-size: 1.9rem; margin-right: 0.4rem"
              ></q-icon
              >Profile</router-link
            >
          </span>
          <span
            class="profile-list"
            @click="changeToBussinessAccount"
            v-if="data.has_bussiness_account == 1"
            ><q-icon
              name="swap_horiz"
              style="font-size: 1.9rem; margin-right: 0.4rem"
            ></q-icon
            >Swith To Bussiness Account
          </span>
          <span class="profile-list" v-else>
            <router-link
              style="
                text-decoration: none;
                color: #fff;
                display: flex;
                align-items: center;
              "
              to="/create-bussiness-account"
            >
              <q-icon
                name="store"
                style="font-size: 1.9rem; margin-right: 0.4rem"
              ></q-icon>
              Create Business Account
            </router-link>
          </span>
          <span class="profile-list" @click="Logout"
            ><q-icon
              name="input"
              style="font-size: 1.8rem; margin-right: 0.4rem"
            ></q-icon>
            Logout</span
          >
        </div>
      </q-card-section>
    </q-card>
    <div
      v-if="loading"
      style="
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
      "
    >
      <div class="q-gutter-md row">
        <q-spinner color="primary" size="5em" :thickness="7" />
      </div>
    </div>
  </q-dialog>
</template>
<script setup>
import { onMounted, ref, defineProps } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { useUserStore } from "src/stores/user_store";

const userStore = useUserStore();

const loading = ref(false);
const router = useRouter();
const props = defineProps({
  toggleProfile: Function,
});

const data = ref("");
const user = ref("");

onMounted(async () => {
  user.value = JSON.parse(localStorage.getItem("user"));
  await axios
    .get(`http://127.0.0.1:8000/api/user/getData/${user.value.id}`, {
      headers: { Authorization: `Bearer ${user.value.token}` },
    })
    .then((res) => {
      data.value = res.data.user;
    });
});

const changeToBussinessAccount = () => {
  loading.value = true;
  axios
    .post(
      `http://127.0.0.1:8000/api/user/changeToBussinessAccount`,
      { email: data.value.email },
      {
        headers: { Authorization: `Bearer ${user.value.token}` },
      }
    )
    .then((res) => {
      loading.value = false;
      localStorage.clear();
      localStorage.setItem("user", JSON.stringify(res.data.data));
      router.push("/dashboard");
    })
    .catch((err) => {
      console.log(err);
    });
};

const Logout = async () => {
  const id = user.value.id;
  try {
    if (user.value.roles == 1 || user.value.roles == 3) {
      await axios
        .get(`http://127.0.0.1:8000/api/logout/${id}`, {
          headers: { Authorization: `Bearer ${user.value.token}` },
        })
        .then((res) => {
          localStorage.clear();
          user.value = null;
          localStorage.clear();
          router.push("/");
        });
    } else {
      await axios
        .get(`http://127.0.0.1:8000/api/bussiness/logout/${id}`, {
          headers: { Authorization: `Bearer ${user.value.token}` },
        })
        .then((res) => {
          localStorage.clear();
          user.value = null;
          router.push("/");
        });
    }
  } catch (err) {
    console.log(err);
  }
};
</script>
<style scoped>
.profile-list {
  cursor: pointer;
  font-size: 1.2rem;
  padding: 0.3rem 1rem;
  border-radius: 5px;
  color: #fff;
  transition: all 0.3s ease;
}
.profile-list:hover {
  background: rgb(72, 165, 202);
}
.myImg {
  width: 80px;
  height: 80px;
  border-radius: 100%;
  display: flex;
  justify-content: center;
}
.myImg > img {
  width: 100%;
  height: 100%;
  border-radius: 100%;
}
</style>

<template>
  <div
    style="
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100vw;
      height: 100vh;
      overflow: hidden;
    "
  >
    <q-card
      style="
        width: 500px;
        border-radius: 5px;
        background-color: rgb(255, 255, 255);
        padding: 0 2rem 2rem 2rem;
      "
    >
      <q-card-section>
        <h5
          style="
            margin-top: 2rem;
            color: #34c5fd;
            font-weight: 600;
            font-size: 2rem;
            letter-spacing: 2px;
            margin-bottom: 1rem;
          "
        >
          Register Your Bussiness
        </h5>
        <div style="margin-bottom: 1rem">
          <span style="font-size: 1rem; font-weight: 300; color: #67748e"
            >Enter your Bussiness name and Delivery Services</span
          >
        </div>
        <form @submit.prevent="loginAsBussiness" style="padding: 1rem 0">
          <div class="row column">
            <q-input
              type="text"
              style="font-size: 0.825rem; margin-bottom: 0.325rem"
              label="Bussiness Name"
              v-model="data.bussiness_name"
            />
            <span class="text-red" v-if="errors" style="font-size: 0.725rem">{{
              errors.bussiness_name ? errors.bussiness_name[0] : ""
            }}</span>
          </div>
          <!-- ////////////////////////// -->
          <div class="row column">
            <q-input
              type="password"
              style="font-size: 0.825rem; margin-bottom: 0.325rem"
              label="Password"
              v-model="data.password"
            />
            <span class="text-red" v-if="errors" style="font-size: 0.725rem">{{
              errors.password ? errors.password[0] : ""
            }}</span>
          </div>
          <!-- /////////// -->
          <div class="row column">
            <q-input
              type="password"
              style="font-size: 0.825rem; margin-bottom: 0.325rem"
              label="Confirm Password"
              v-model="data.confirm_password"
            />
            <span class="text-red" v-if="errors" style="font-size: 0.725rem">{{
              errors.confirm_password ? errors.confirm_password[0] : ""
            }}</span>
          </div>
          <!-- ////////////////////////// -->
          <div class="text-left q-pt-lg" @click="() => (toggle = !toggle)">
            <q-toggle v-model="toggle" id="toggle" color="primary" keep-color />
            <span
              style="
                font-size: 1rem;
                cursor: pointer;
                font-weight: 500;
                text-align: left;
              "
            >
              Delivery Services
            </span>
          </div>
          <q-btn
            type="submit"
            :loading="loading"
            :percentage="percentage"
            style="
              margin-top: 2rem;
              background: #34c5fd;
              color: #fff;
              width: 100%;
            "
          >
            Register
            <template v-slot:loading>
              <div class="on-left">
                <q-spinner-ios color="white" size=".8em" />
              </div>
              Registering...
            </template>
          </q-btn>
        </form>
      </q-card-section>
    </q-card>
  </div>
</template>
<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "src/stores/user_store";

const userStore = useUserStore();
const router = useRouter();

const errors = ref([]);
const loading = ref(false);
const percentage = ref(0);
const intervals = ref(null);
const toggle = ref(false);
const user = JSON.parse(localStorage.getItem("user"));
const data = ref({
  user_id: user.id,
  bussiness_name: "",
  confirm_password: "",
  password: "",
  delivery: "",
});

const loginAsBussiness = async () => {
  if (toggle.value) {
    data.value.delivery = 1;
  } else {
    data.value.delivery = 0;
  }
  loading.value = true;
  percentage.value = 0;

  percentage.value += Math.floor(Math.random() * 8 + 10);
  if (percentage.value >= 100) {
    loading.value = false;
  }
  await axios
    .post("http://127.0.0.1:8000/api/create-new-account", data.value, {
      headers: { Authorization: `Bearer ${user.token}` },
    })
    .then((res) => {
      loading.value = false;
      percentage.value = 0;
      intervals.value = null;
      localStorage.setItem("user", JSON.stringify(res.data.data));
      errors.value = "";
      router.push("/dashboard");
    })
    .catch((err) => {
      loading.value = false;
      percentage.value = 0;
      intervals.value = null;
      errors.value = err.response.data.errors;
    });
};
</script>

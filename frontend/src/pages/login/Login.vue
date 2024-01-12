<template>
  <q-dialog transition-show="" style="z-index: 0 !important">
    <q-card
      style="
        height: 500px;
        width: 400px;
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
          Sign In
        </h5>
        <div style="margin-bottom: 0.725rem">
          <span style="font-size: 1rem; font-weight: 300; color: #67748e"
            >Enter your email and password to sign in</span
          >
        </div>
        <form @submit.prevent="login" style="padding: 0.725rem 0">
          <div class="row column">
            <q-input
              type="email"
              style="font-size: 0.825rem; margin-bottom: 0.325rem"
              label="Email"
              v-model="data.email"
            />
            <span class="text-red" v-if="errors" style="font-size: 0.725rem">{{
              errors.email ? errors.email[0] : ""
            }}</span>
          </div>
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
          <div class="text-left q-pt-lg" @click="() => (value = !value)">
            <q-toggle v-model="value" id="toggle" color="primary" keep-color />
            <span
              style="
                font-size: 1rem;
                cursor: pointer;
                font-weight: 500;
                text-align: left;
              "
            >
              Login as Bussiness Account
            </span>
          </div>
          <q-btn
            type="submit"
            :loading="loading"
            :percentage="percentage"
            style="
              margin-top: 1.5rem;
              background: #34c5fd;
              color: #fff;
              width: 100%;
            "
          >
            Login
            <template v-slot:loading>
              <div class="on-left">
                <q-spinner-ios color="white" size=".8em" />
              </div>
              Logining...
            </template>
          </q-btn>
          <div style="margin-top: 1rem">
            <p
              style="
                padding: 0;
                margin: 0;
                text-decoration: underline;
                cursor: pointer;
                color: #143988;
              "
              @click="goToForgetPassword"
            >
              Forget password
            </p>
          </div>
        </form>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";
import axios from "axios";

const router = useRouter();

const errors = ref([]);
const loading = ref(false);
const percentage = ref(0);
const intervals = ref(null);
const value = ref(false);

const data = ref({
  email: "",
  password: "",
});

const goToForgetPassword = () => {
  router.push("/forgetPassword");
};

const login = async () => {
  loading.value = true;
  percentage.value = 0;

  percentage.value += Math.floor(Math.random() * 8 + 10);
  if (percentage.value >= 100) {
    loading.value = false;
  }
  try {
    if (!value.value) {
      await axios
        .post(
          "http://localhost:8000/api/login",
          {
            email: data.value.email,
            password: data.value.password,
          },
          {
            withCredentials: true,
          }
        )
        .then((res) => {
          loading.value = false;
          percentage.value = 0;
          intervals.value = null;
          localStorage.setItem("user", JSON.stringify(res.data.data));
          errors.value = "";
          if (res.data.data.roles == 1) {
            router.push("/admin");
          } else {
            router.push("authenticated");
          }
        });
    } else {
      await axios
        .post(
          "http://localhost:8000/api/bussiness/login",
          {
            email: data.value.email,
            password: data.value.password,
          },
          {
            withCredentials: true,
          }
        )
        .then((res) => {
          loading.value = false;
          percentage.value = 0;
          intervals.value = null;
          errors.value = "";
          localStorage.setItem("user", JSON.stringify(res.data.data));
          router.push("/dashboard");
        });
    }
  } catch (err) {
    loading.value = false;
    percentage.value = 0;
    intervals.value = null;
    errors.value = err.response.data.errors;
    if (err.response.status === 400) {
      new Swal({
        position: "top-end",
        icon: "error",
        title: err.response.data.errors,
        showConfirmButton: false,
        timer: 2000,
      });
    }
    if (err.response.status == 401) {
      new Swal({
        position: "top-end",
        icon: "error",
        title: "No Query Exist",
        showConfirmButton: false,
        timer: 2000,
      });
    }
  }
};
</script>

<style scoped>
.swal-modal {
  position: absolute !important;
  top: 0;
  right: 0;
}
.swal-button {
  display: none;
}
</style>

<template>
  <div
    style="
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    "
  >
    <div>
      <form
        @submit.prevent="forgetPassword"
        style="padding: 0.725rem 0; width: 400px"
      >
        <div class="row column">
          <q-input
            type="email"
            style="font-size: 0.825rem; margin-bottom: 0.325rem"
            label="Enter Your Email"
            v-model="data.email"
          />

          <span v-if="code">waiting for a moment...</span>
          <span class="text-red" v-if="errors" style="font-size: 0.725rem">{{
            errors.email ? errors.email[0] : ""
          }}</span>
        </div>
        <div
          class="text-left q-pt-lg"
          @click="() => (data.bussiness = !data.bussiness)"
        >
          <q-toggle
            v-model="data.bussiness"
            id="toggle"
            color="primary"
            keep-color
          />
          <span
            style="
              font-size: 1rem;
              cursor: pointer;
              font-weight: 500;
              text-align: left;
            "
          >
            Bussiness Account
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
          Send verification
          <template v-slot:loading>
            <div class="on-left">
              <q-spinner-ios color="white" size=".8em" />
            </div>
            SENDING...
          </template>
        </q-btn>
      </form>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const errors = ref([]);
const loading = ref(false);
const percentage = ref(0);
const code = ref(false);

const data = ref({
  email: "",
  bussiness: false,
});
const user = JSON.parse(localStorage.getItem("user"));
const forgetPassword = async () => {
  loading.value = true;
  code.value = true;
  await axios
    .post("http://localhost:8000/api/forget/password", data.value)
    .then((res) => {
      router.push("/verify-otp");
    })
    .catch((err) => {
      errors.value = err.response.data.errors;
      loading.value = false;
      code.value = false;
    });
};
</script>

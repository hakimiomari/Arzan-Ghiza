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
      <form @submit.prevent="sentOtp" style="padding: 0.725rem 0; width: 400px">
        <div class="row column">
          <q-input
            type="text"
            style="font-size: 0.825rem; margin-bottom: 0.325rem"
            label="Enter OTP That You Recieved"
            v-model="data.otp"
          />
          <span class="text-red" v-if="errors" style="font-size: 0.725rem">{{
            errors.otp ? errors.otp[0] : ""
          }}</span>
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
          Verify
          <template v-slot:loading>
            <div class="on-left">
              <q-spinner-ios color="white" size=".8em" />
            </div>
            VERIFYING...
          </template>
        </q-btn>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();

const errors = ref("");
const loading = ref(false);
const percentage = ref(0);

const data = ref({
  otp: "",
});

const sentOtp = async () => {
  loading.value = true;
  await axios
    .post("http://localhost:8000/api/verify", data.value)
    .then((res) => {
      errors.value = "";
      loading.value = false;
      router.push("/change_password");
    })
    .catch((err) => {
      loading.value = false;
      errors.value = err.response.data.errors;
    });
};
</script>

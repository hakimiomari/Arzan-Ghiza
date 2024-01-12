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
            label="New Password"
            v-model="data.password"
          />
          <span class="text-red" v-if="errors" style="font-size: 0.725rem">{{
            errors.password ? errors.password[0] : ""
          }}</span>
        </div>
        <div class="row column">
          <q-input
            type="text"
            style="font-size: 0.825rem; margin-bottom: 0.325rem"
            label="Confirm New Password"
            v-model="data.confirm_new_password"
          />
          <span class="text-red" v-if="errors" style="font-size: 0.725rem">{{
            errors.confirm_new_password ? errors.confirm_new_password[0] : ""
          }}</span>
          <span class="text-red" v-if="errors" style="font-size: 0.725rem">{{
            errors.email ? errors.email : ""
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
          Login
          <template v-slot:loading>
            <div class="on-left">
              <q-spinner-ios color="white" size=".8em" />
            </div>
            LOGGING...
          </template>
        </q-btn>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useUserStore } from "src/stores/user_store";
import axios from "axios";

const userStore = useUserStore();

const router = useRouter();

const errors = ref("");
const loading = ref(false);
const percentage = ref(0);

const data = ref({
  password: "",
  confirm_new_password: "",
});

const sentOtp = async () => {
  loading.value = true;
  await axios
    .post("http://localhost:8000/api/changePassword", data.value)
    .then((res) => {
      loading.value = false;
      percentage.value = 0;
      errors.value = "";
      localStorage.setItem("user", JSON.stringify(res.data.data));
      if (res.data.data.roles == 1) {
        router.push("/admin");
      }
      if (res.data.data.roles == 2) {
        router.push("/dashboard");
      }
      if (res.data.data.roles == 3) {
        router.push("/authenticated");
      }
    })
    .catch((err) => {
      console.log(err);
      loading.value = false;
      errors.value = err.response.data.errors;
    });
};
</script>

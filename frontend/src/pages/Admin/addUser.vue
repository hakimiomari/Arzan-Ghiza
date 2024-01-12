<template>
  <div class="registration_form">
    <div class="user_form">
      <q-card-section>
        <div style="margin-bottom: 3rem">
          <span
            style="
              color: #34c5fd;
              font-weight: 600;
              font-size: 2rem;
              letter-spacing: 1px;
            "
          >
            Registration Form
          </span>
        </div>
        <form
          @submit.prevent="register"
          class="row column"
          style=""
          enctype="multipart/form-dat"
        >
          <div class="flex" style="width: 100%; gap: 1rem">
            <div class="row column input-group" style="width: 48%">
              <q-input
                type="text"
                style="font-size: 0.825rem; margin-bottom: 0.325rem"
                label="Name"
                v-model="data.name"
              />
              <span class="text-red" style="font-size: 0.825rem">
                {{ errors.name ? errors.name[0] : "" }}
              </span>
            </div>
            <div class="row column input-group" style="width: 48%">
              <q-input
                type="email"
                style="font-size: 0.825rem; margin-bottom: 0.325rem"
                label="Email"
                v-model="data.email"
              />
              <span class="text-red" style="font-size: 0.825rem">
                {{ errors.email ? errors.email[0] : "" }}
              </span>
            </div>
          </div>
          <div class="row column input-group" style="width: 48%">
            <q-input
              type="number"
              style="font-size: 0.825rem; margin-bottom: 0.325rem"
              label="Phone Number"
              v-model="data.phone"
            />
            <span class="text-red" style="font-size: 0.825rem">
              {{ errors.phone ? errors.phone[0] : "" }}
            </span>
          </div>

          <div class="flex" style="width: 100%; gap: 1rem">
            <div class="row column input-group" style="width: 48%">
              <q-input
                type="password"
                style="font-size: 0.825rem; margin-bottom: 0.325rem"
                label="Password"
                v-model="data.password"
              />
              <span class="text-red" style="font-size: 0.825rem">
                {{ errors.password ? errors.password[0] : "" }}
              </span>
            </div>
            <div class="row column input-group" style="width: 48%">
              <q-input
                type="password"
                style="font-size: 0.825rem; margin-bottom: 0.325rem"
                label="Confirm Password"
                v-model="data.confirmPassword"
              />
              <span class="text-red" style="font-size: 0.825rem">
                {{ errors.confirmPassword ? errors.confirmPassword[0] : "" }}
              </span>
            </div>
          </div>
          <!-- /////////////////////////////////////////////// -->
          <div v-if="!value" class="row" style="width: 100%; gap: 1rem">
            <div class="row" style="width: 100%; gap: 1rem">
              <div class="row column input-group" style="width: 48%">
                <q-input
                  type="text"
                  style="font-size: 0.825rem; margin-bottom: 0.325rem"
                  label="City"
                  v-model="data.city"
                />
                <span class="text-red" style="font-size: 0.825rem">
                  {{ errors.city ? errors.city[0] : "" }}
                </span>
              </div>
              <div class="row column input-group" style="width: 48%">
                <q-input
                  type="text"
                  style="font-size: 0.825rem; margin-bottom: 0.325rem"
                  label="District"
                  v-model="data.district"
                />
                <span class="text-red" style="font-size: 0.825rem">
                  {{ errors.district ? errors.district[0] : "" }}
                </span>
              </div>
            </div>
            <div class="row" style="width: 100%">
              <div class="row column input-group" style="width: 48%">
                <q-input
                  type="text"
                  style="font-size: 0.825rem; margin-bottom: 0.325rem"
                  label="Valige"
                  v-model="data.valige"
                />
                <span class="text-red" style="font-size: 0.825rem">
                  {{ errors.valige ? errors.valige[0] : "" }}
                </span>
              </div>
            </div>
          </div>
          <!-- /////////////////////////////////////////////// -->

          <q-btn
            :disabled="value"
            v-if="!value"
            type="submit"
            :loading="loading"
            :percentage="percentage"
            style="
              width: 48%;
              margin: auto;
              margin-top: 2rem;
              background: #34c5fd;
              padding: 0.5rem 0;
            "
            class="text-white"
          >
            Register
            <template v-slot:loading>
              <div class="on-left">
                <q-spinner-ios color="white" size=".8em" />
              </div>
              submitting...
            </template>
          </q-btn>
        </form>
      </q-card-section>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";
import axios from "axios";

const router = useRouter();

const loading = ref(false);
const percentage = ref(0);
const intervals = ref(null);

const data = ref({
  name: "",
  email: "",
  phone: "",
  city: "",
  district: "",
  valige: "",
  password: "",
  confirmPassword: "",
});

// toggle button

const errors = ref([]);
const user = JSON.parse(localStorage.getItem("user"));
const register = async () => {
  loading.value = true;
  percentage.value = 0;

  percentage.value += Math.floor(Math.random() * 8 + 10);
  if (percentage.value >= 100) {
    loading.value = false;
  }
  try {
    await axios
      .post(
        "http://localhost:8000/api/register",
        {
          name: data.value.name,
          email: data.value.email,
          phone: data.value.phone,
          password: data.value.password,
          confirmPassword: data.value.confirmPassword,
          city: data.value.city,
          district: data.value.district,
          valige: data.value.valige,
        },
        {
          headers: { Authorization: `Bearer ${user.token}` },
        }
      )
      .then((res) => {
        loading.value = false;
        percentage.value = 0;
        intervals.value = null;
        errors.value = "";
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Account Successfully Created",
          showConfirmButton: false,
          timer: 3000,
        });
        router.push("/users");
      });
  } catch (err) {
    loading.value = false;
    percentage.value = 0;
    intervals.value = null;
    errors.value = err.response.data.errors;
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

.registration_form {
  display: flex;
  align-items: center;
  justify-content: center;
}
.user_form {
  width: 100% !important;
  border-radius: 12px;
  background-color: rgb(255, 255, 255);
  padding: 4rem 8rem;
}

@media screen and (max-width: 1000px) {
  .registration_image {
    display: none;
  }
  .signUp {
    display: flex;
    justify-content: center;
  }
  .registration_form {
    height: auto;
    display: flex;
    justify-content: center;
  }
  .user_form {
    padding: 2rem 4rem;
  }
}
@media screen and (max-width: 650px) {
  .input-group {
    width: 100% !important;
  }
}
@media screen and (max-width: 500px) {
  .user_form {
    padding: 2rem 1rem;
  }
  .template {
    margin-top: 0;
  }
}
</style>

<template>
  <div>
    <div
      v-if="spinner"
      style="
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      "
    >
      <q-spinner-ios color="primary" size="6em" />
    </div>
    <q-page v-else class="row justify-center items-center q-my-lg updateForm">
      <div
        v-if="data"
        class="row column col-12"
        style="justify-content: center; padding: 0 2rem"
      >
        <div>
          <form style="margin-top: 2rem" @submit.prevent="updateUser">
            <div class="col-12 flex justify-center">
              <div class="image-container">
                <q-img class="image" :src="imgUrl ? imgUrl : data.photo" />
                <label class="profile_image" for="image"
                  ><img src="../../assets/images/camera.svg" alt=""
                /></label>
                <input
                  accept="image/*"
                  type="file"
                  @change="fileChange"
                  id="image"
                  hidden
                  class="q-px-lg"
                />
              </div>
            </div>
            <div class="col-12 flex row">
              <!-- name -->
              <div class="col-md-6 col-12">
                <q-input
                  square
                  v-model="data.name"
                  type="text"
                  label="Name"
                  class="q-px-lg"
                  style="margin-bottom: 1rem; margin-top: 1rem"
                >
                  <template v-slot:prepend>
                    <q-icon name="person" />
                  </template>
                </q-input>
                <p
                  v-if="errors.name"
                  class="text-xs mt-1"
                  style="color: red; margin-left: 2rem"
                >
                  {{ errors.name[0] }}
                </p>
              </div>
              <!-- bussiness name -->
              <div class="col-12 col-md-6">
                <q-input
                  square
                  v-model="data.bussiness_name"
                  type="text"
                  label="Bussiness Name"
                  class="q-px-lg"
                  style="margin-bottom: 1rem; margin-top: 1rem"
                >
                  <template v-slot:prepend>
                    <q-icon name="person" />
                  </template>
                </q-input>
                <p
                  v-if="errors.bussiness_name"
                  class="text-xs mt-1"
                  style="color: red; margin-left: 2rem"
                >
                  {{ errors.bussiness_name[0] }}
                </p>
              </div>
            </div>

            <!-- ///////////// -->
            <div class="col-12 flex row">
              <!-- email -->
              <div class="col-12 col-md-6">
                <q-input
                  square
                  v-model="data.email"
                  type="email"
                  label="Email"
                  class="q-px-lg"
                  style="margin-bottom: 1rem"
                >
                  <template v-slot:prepend>
                    <q-icon name="email" />
                  </template>
                </q-input>
                <p
                  v-if="errors.email"
                  class="text-xs mt-1"
                  style="color: red; margin-left: 2rem"
                >
                  {{ errors.email[0] }}
                </p>
              </div>
              <!-- phone -->
              <div class="col-12 col-md-6">
                <q-input
                  square
                  v-model="data.phone"
                  type="text"
                  label="Phone"
                  class="q-px-lg"
                  style="margin-bottom: 1rem"
                >
                  <template v-slot:prepend>
                    <q-icon name="phone" />
                  </template>
                </q-input>
                <p
                  v-if="errors.phone"
                  class="text-xs mt-1"
                  style="color: red; margin-left: 2rem"
                >
                  {{ errors.phone[0] }}
                </p>
              </div>
            </div>
            <!-- ////////////// -->
            <div class="col-12 flex row">
              <!-- delivery -->
              <div class="text-left q-pt-lg col-12 col-md-6">
                <q-toggle
                  v-model="data.delivery"
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
                  Delivery Services
                </span>
              </div>
              <!-- city -->
              <div class="col-12 col-md-6">
                <q-input
                  square
                  v-model="data.address.city"
                  type="text"
                  label="City"
                  class="q-px-lg"
                  style="margin-bottom: 1rem"
                >
                  <template v-slot:prepend>
                    <q-icon name="pin_drop" />
                  </template>
                </q-input>
                <p
                  v-if="errors.city"
                  class="text-xs mt-1"
                  style="color: red; margin-left: 2rem"
                >
                  {{ errors.city[0] }}
                </p>
              </div>
            </div>
            <div class="flex row col-12">
              <!-- district -->
              <div class="col-12 col-md-6">
                <q-input
                  square
                  v-model="data.address.district"
                  type="text"
                  label="District"
                  class="q-px-lg"
                  style="margin-bottom: 1rem"
                >
                  <template v-slot:prepend>
                    <q-icon name="home" />
                  </template>
                </q-input>
                <p
                  v-if="errors.district"
                  class="text-xs mt-1"
                  style="color: red; margin-left: 2rem"
                >
                  {{ errors.district[0] }}
                </p>
              </div>
              <!-- valige -->
              <div class="col-12 col-md-6">
                <q-input
                  square
                  v-model="data.address.valige"
                  value="data.address.valige"
                  type="text"
                  label="Valige"
                  class="q-px-lg"
                  style="margin-bottom: 1rem"
                >
                  <template v-slot:prepend>
                    <q-icon name="person_pin_circle" />
                  </template>
                </q-input>
                <p
                  v-if="errors.valige"
                  class="text-xs mt-1"
                  style="color: red; margin-left: 2rem"
                >
                  {{ errors.valige[0] }}
                </p>
              </div>
            </div>

            <!-- button -->
            <q-btn
              type="submit"
              :loading="loading"
              :percentage="percentage"
              style="
                width: 200px;
                padding: 0.725rem;
                background-color: #1976d2;
                border-radius: 5px;
                cursor: pointer;
                border: none;
                margin-top: 1.5rem;
              "
              class="text-white block q-mx-auto q-my-md"
            >
              Update Profile
              <template v-slot:loading>
                <div class="on-left">
                  <q-spinner-ios color="white" size=".8em" />
                </div>
                Updating...
              </template>
            </q-btn>
          </form>
        </div>
      </div>
    </q-page>
  </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import { useImageUpload } from "../../components/composible/useImageUpload.js";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";
import axios from "axios";

const router = useRouter();

const { photo, imgUrl, fileChange } = useImageUpload();

const user = ref("");
const data = ref("");
const errors = ref("");

// loading
const loading = ref(false);
const percentage = ref(0);
const intervals = ref(null);

const spinner = ref(true);
onMounted(async () => {
  user.value = JSON.parse(localStorage.getItem("user"));
  await axios
    .get(`http://localhost:8000/api/bussiness/user/getData/${user.value.id}`, {
      headers: { Authorization: `Bearer ${user.value.token}` },
    })
    .then((res) => {
      data.value = res.data.user[0];
      spinner.value = false;
    });
});

const updateUser = async (event) => {
  // loading
  loading.value = true;
  percentage.value = 0;

  event.preventDefault();
  let BUserData = new FormData();
  BUserData.append("image", photo.value);
  BUserData.append("id", data.value.id);
  BUserData.append("name", data.value.name);
  BUserData.append("bussiness_name", data.value.bussiness_name);
  BUserData.append("delivery", data.value.delivery);
  BUserData.append("email", data.value.email);
  BUserData.append("phone", data.value.phone);
  BUserData.append("city", data.value.address.city);
  BUserData.append("district", data.value.address.district);
  BUserData.append("valige", data.value.address.valige);
  try {
    await axios
      .post(`http://localhost:8000/api/bussiness/user/updateUser`, BUserData, {
        headers: { Authorization: `Bearer ${user.value.token}` },
      })
      .then((res) => {
        if (res.status === 200) {
          loading.value = false;
          percentage.value = 0;
          intervals.value = null;
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Profile Successfully Updated",
            showConfirmButton: false,
            timer: 2000,
          });
          router.push("/bussiness-profile");
        }
      });
  } catch (error) {
    console.log(error);
    loading.value = false;
    percentage.value = 0;
    intervals.value = null;
    errors.value = error.response.data.errors;
    if (errors.value.image[0]) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: errors.value.image[0],
        footer: '<a href="#">Why do I have this issue?</a>',
      });
    }
  }
};
</script>
<style scoped>
.swal2-popup {
  z-index: 99999;
}
.profile_image {
  position: absolute;
  right: 5%;
  top: 80%;
}
.profile_image > img {
  width: 30px;
}
.image-container {
  position: relative;
  width: 200px;
  height: 200px;
  border-radius: 100%;
  border: 10px solid #1976d2;
}
.image {
  width: 100%;
  height: 100%;
  border-radius: 100%;
  object-fit: contain;
}
.formImage {
  border-top-left-radius: 6px;
  border-bottom-left-radius: 6px;
}
.form {
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2), 0 2px 2px rgba(0, 0, 0, 0.14),
    0 3px 1px -2px rgba(0, 0, 0, 0.12);
}
@media screen and (max-width: 850px) {
  .formImage {
    display: none;
  }
  .updateForm {
    padding: 0;
  }
}
@media screen and (max-width: 1400px) and (min-width: 850px) {
  .updateForm {
    padding: 0 4rem;
  }
}
@media screen and (min-width: 1400px) {
  .updateForm {
    padding: 0 8rem;
  }
}
</style>

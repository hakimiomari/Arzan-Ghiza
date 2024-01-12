<template>
  <q-page class="row justify-center items-center q-my-lg" style="">
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
    <div
      v-else
      class="row column col-sm-8 col-md-5 col-12"
      style="justify-content: center"
    >
      <div>
        <form style="margin-top: 2rem" @submit.prevent="updateUser">
          <div class="col-12 flex justify-center">
            <div class="image-container">
              <q-img class="image" :src="imgUrl ? imgUrl : data.image" />
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
          <!-- name -->
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
            v-if="errors?.name"
            class="text-xs mt-1"
            style="color: red; margin-left: 2rem"
          >
            {{ errors?.name[0] }}
          </p>

          <!-- email -->
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
          <!-- phone -->
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
          <!-- city -->
          <q-input
            square
            v-model="data.city"
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
          <!-- district -->
          <q-input
            square
            v-model="data.district"
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
          <!-- valige -->
          <q-input
            square
            v-model="data.valige"
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
              updating...
            </template>
          </q-btn>
        </form>
      </div>
    </div>
  </q-page>
</template>
<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { useImageUpload } from "../../components/composible/useImageUpload.js";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";

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
    .get(`http://127.0.0.1:8000/api/user/getData/${user.value.id}`, {
      headers: { Authorization: `Bearer ${user.value.token}` },
    })
    .then((res) => {
      data.value = res.data.user;
      spinner.value = false;
    });
});

const updateUser = async () => {
  loading;
  loading.value = true;
  percentage.value = 0;

  event.preventDefault();
  let imgData = new FormData();
  imgData.append("image", photo.value);
  imgData.append("id", data.value.id);
  imgData.append("name", data.value.name);
  imgData.append("email", data.value.email);
  imgData.append("phone", data.value.phone);
  imgData.append("city", data.value.city);
  imgData.append("district", data.value.district);
  imgData.append("valige", data.value.valige);
  try {
    await axios
      .post(`http://127.0.0.1:8000/api/user/updateUser`, imgData, {
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
            title: "Profile Successfully Changed",
            showConfirmButton: false,
            timer: 2000,
          });
          if (user.value.id !== 1) {
            router.push("/profile");
          } else {
            router.push("/admin-profile");
          }
        }
      });
  } catch (error) {
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
@media (max-width: 850px) {
  .formImage {
    display: none;
  }
}
</style>

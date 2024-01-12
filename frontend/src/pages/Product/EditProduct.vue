<template>
  <div
    class="row template"
    style="align-items: center; justify-content: center; margin: 2rem"
  >
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
    <div v-else class="registration_form">
      <q-card style="width: 100%; box-shadow: none">
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
              Edit Product
            </span>
          </div>
          <form @submit.prevent="store" class="row column">
            <!-- product image -->
            <div class="col-12 q-mb-lg flex justify-center">
              <div class="image-container">
                <q-img class="image" :src="imgUrl ? imgUrl : form_data.image" />
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
            <!-- product image -->
            <div class="flex" style="width: 100%; gap: 1rem">
              <div class="row column input-group" style="width: 48%">
                <q-input
                  type="text"
                  style="font-size: 0.825rem; margin-bottom: 0.325rem"
                  label="Title"
                  v-model="form_data.tittle"
                />
                <span
                  class="text-red"
                  v-if="errors.tittle"
                  style="font-size: 0.825rem"
                >
                  {{ errors?.tittle ? errors.tittle[0] : "" }}
                </span>
              </div>
              <div class="row column input-group" style="width: 48%">
                <q-input
                  type="text"
                  style="font-size: 0.825rem; margin-bottom: 0.325rem"
                  label="Actual Price"
                  v-model="form_data.price"
                />
                <span
                  class="text-red"
                  v-if="errors.price"
                  style="font-size: 0.825rem"
                >
                  {{ errors?.price ? errors.price[0] : "" }}
                </span>
              </div>
              <div class="row column input-group" style="width: 48%">
                <q-input
                  type="text"
                  style="font-size: 0.825rem; margin-bottom: 0.325rem"
                  label="Discount Price"
                  v-model="form_data.discount_price"
                />
                <span
                  class="text-red"
                  v-if="errors.discount_price"
                  style="font-size: 0.825rem"
                >
                  {{ errors?.discount_price ? errors.discount_price[0] : "" }}
                </span>
              </div>
              <div class="row column input-group" style="width: 48%">
                <q-input
                  type="number"
                  style="font-size: 0.825rem; margin-bottom: 0.325rem"
                  label="Quantity"
                  v-model="form_data.quantity"
                />
                <span
                  v-if="errors.quantity"
                  class="text-red"
                  style="font-size: 0.825rem"
                >
                  {{ errors?.quantity ? errors.quantity[0] : "" }}
                </span>
              </div>

              <div class="row column input-group" style="width: 48%">
                <label for="date" style="color: gray; margin-bottom: 3px"
                  >Expires Date</label
                >
                <input
                  placeholder="Expires Date"
                  class="flatpickr"
                  style="
                    font-size: 0.825rem;
                    margin-bottom: 0.325rem;
                    padding: 0.5rem;
                    border: 1px solid rgb(184, 176, 176);
                    border-radius: 3px;
                  "
                  v-model="form_data.expires_date"
                />
                <span
                  class="text-red"
                  v-if="errors.expires_date"
                  style="font-size: 0.825rem"
                >
                  {{ errors?.expires_date ? errors.expires_date[0] : "" }}
                </span>
              </div>
              <div class="row column input-group" style="width: 48%">
                <q-input
                  label="Description"
                  v-model="form_data.description"
                  filled
                  autogrow
                />
                <span class="text-red" style="font-size: 0.825rem">
                  {{ errors?.description ? errors.description[0] : "" }}
                </span>
              </div>
              <div class="row" style="width: 100%; margin-top: 2rem">
                <q-btn
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
                  Save Changes
                  <template v-slot:loading>
                    <div class="on-left">
                      <q-spinner-ios color="white" size=".8em" />
                    </div>
                    submitting...
                  </template>
                </q-btn>
              </div>
            </div>
          </form>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import Swal from "sweetalert2";
import { useRouter } from "vue-router";
import { useImageUpload } from "src/components/composible/useImageUpload";
import { useRoute } from "vue-router";
import flatpickr from "flatpickr";
import "flatpickr/dist/themes/material_blue.css";
import axios from "axios";

const route = useRoute();
const { photo, fileChange, imgUrl } = useImageUpload();

const router = useRouter();

const loading = ref(false);
const percentage = ref(0);

const form_data = ref("");

const errors = ref("");
const user = JSON.parse(localStorage.getItem("user"));

const spinner = ref(true);
onMounted(async () => {
  flatpickr(".flatpickr", {
    enableTime: true,
    dateFormat: "y-m-d H:i",
    defaultHour: 10,
    time_24hr: true,
  });
  try {
    await axios
      .get(
        `http://localhost:8000/api/bussiness/products/product/${route.params.id}`,
        {
          headers: { Authorization: `Bearer ${user.token}` },
        }
      )
      .then((res) => {
        form_data.value = res.data.product;
        spinner.value = false;
      });
  } catch (err) {
    new Swal({
      position: "top-end",
      icon: "error",
      title: "No Query Exist for this Id",
      showConfirmButton: false,
      timer: 2000,
    });
  }
});

const store = async () => {
  loading.value = true;
  percentage.value = 0;

  // image
  let data = new FormData();
  data.append("id", form_data.value.id);
  data.append("image", photo.value);
  data.append("tittle", form_data.value.tittle);
  data.append("description", form_data.value.description);
  data.append("price", form_data.value.price);
  data.append("discount_price", form_data.value.discount_price);
  data.append("quantity", form_data.value.quantity);
  data.append("expires_date", form_data.value.expires_date);

  percentage.value += Math.floor(Math.random() * 8 + 10);
  if (percentage.value >= 100) {
    loading.value = false;
  }

  try {
    await axios
      .post("http://localhost:8000/api/bussiness/product/update", data, {
        headers: { Authorization: `Bearer ${user.token}` },
      })
      .then((res) => {
        loading.value = false;
        percentage.value = 0;
        errors.value = "";
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Product Successfully Updated",
          showConfirmButton: false,
          timer: 1500,
        });
        if (user.roles == 1) {
          router.push("/admin");
        } else {
          router.push("/dashboard");
        }
      });
  } catch (err) {
    loading.value = false;
    percentage.value = 0;
    errors.value = err.response.data.errors;
  }
};
</script>
<style scoped>
.profile_image {
  position: absolute;
  right: 2%;
  top: 89%;
}
.profile_image > img {
  width: 30px;
  cursor: pointer;
}
.image-container {
  position: relative;
  width: 100%;
  height: 350px;
  border-radius: 5px;
}
.image {
  width: 100%;
  height: 100%;
  border-radius: 5px;
  object-fit: contain;
}
.formImage {
  border-top-left-radius: 6px;
  border-bottom-left-radius: 6px;
}
.swal-modal {
  position: absolute !important;
  top: 0;
  right: 0;
}
.swal-button {
  display: none;
}
.swal-modal {
  position: absolute !important;
  top: 0;
  right: 0;
}
.swal-button {
  display: none;
}

.registration_form {
  width: 70%;
  display: flex;
  align-items: center;
  justify-content: center;
}

@media screen and (max-width: 1000px) {
  .registration_image {
    display: none;
  }
  .image-container {
    width: 100%;
    height: 300px;
  }
  .profile_image {
    position: absolute;
    right: 2%;
    top: 85%;
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
}
@media screen and (max-width: 650px) {
  .input-group {
    width: 100% !important;
  }
  .image-container {
    width: 100%;
    height: 200px !important;
  }
  .profile_image {
    position: absolute;
    right: 2%;
    top: 85%;
  }
}
@media screen and (max-width: 500px) {
  .template {
    margin-top: 0;
  }
}
</style>

<template>
  <div
    class="row template"
    style="align-items: center; justify-content: center; margin: 2rem"
  >
    <div class="registration_form">
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
              Add Product
            </span>
          </div>
          <form @submit.prevent="store" class="row column">
            <div class="flex" style="width: 100%; gap: 1rem">
              <div class="row column input-group" style="width: 48%">
                <q-input
                  type="text"
                  style="font-size: 0.825rem; margin-bottom: 0.325rem"
                  label="Title"
                  v-model="form_data.title"
                />
                <span
                  class="text-red"
                  v-if="errors.title"
                  style="font-size: 0.825rem"
                >
                  {{ errors?.title ? errors.title[0] : "" }}
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

              <div
                class="date_picker"
                style="
                  display: flex;
                  align-items: center;
                  width: 100%;
                  gap: 1rem;
                "
              >
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
              </div>
              <div class="row column input-group q-pt-lg" style="">
                <q-file
                  filled
                  bottom-slots
                  v-model="model"
                  label="Label"
                  counter
                  @input="fileChange"
                >
                  <template v-slot:prepend>
                    <q-icon name="cloud_upload" @click.stop.prevent />
                  </template>
                  <template v-slot:append>
                    <q-icon
                      name="close"
                      @click.stop.prevent="model = null"
                      class="cursor-pointer"
                    />
                  </template>

                  <template v-slot:hint> Field hint </template>
                </q-file>
                <span
                  v-if="errors.image"
                  class="text-red"
                  style="font-size: 0.825rem"
                >
                  {{ errors?.image ? errors.image[0] : "" }}
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
                  Add Product
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
import flatpickr from "flatpickr";
import "flatpickr/dist/themes/material_blue.css";
import axios from "axios";

const { photo, fileChange, imgUrls } = useImageUpload();

const router = useRouter();

const loading = ref(false);
const percentage = ref(0);
const model = ref("");

const form_data = ref({
  title: "",
  description: "",
  price: "",
  discount_price: "",
  quantity: "",
  expires_date: "",
});

const errors = ref("");
const user = ref("");

onMounted(() => {
  user.value = JSON.parse(localStorage.getItem("user"));
  flatpickr(".flatpickr", {
    enableTime: true,
    dateFormat: "y-m-d H:i",
    defaultHour: 10,
    time_24hr: true,
  });
});

const store = async () => {
  loading.value = true;
  percentage.value = 0;

  // image
  let data = new FormData();
  data.append("bussiness_id", user.value.id);
  data.append("image", photo.value);
  data.append("title", form_data.value.title);
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
      .post("http://localhost:8000/api/bussiness/store", data, {
        headers: { Authorization: `Bearer ${user.value.token}` },
      })
      .then((res) => {
        loading.value = false;
        percentage.value = 0;
        errors.value = "";
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Product Successfully added",
          showConfirmButton: false,
          timer: 1500,
        });
        router.push("/dashboard");
      });
  } catch (err) {
    loading.value = false;
    percentage.value = 0;
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
}
@media screen and (max-width: 650px) {
  .input-group {
    width: 100% !important;
  }
  .date_picker {
    flex-direction: column;
  }
}
@media screen and (max-width: 500px) {
  .template {
    margin-top: 0;
  }
}
</style>

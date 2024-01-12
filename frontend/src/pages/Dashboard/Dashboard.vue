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
    <div v-else>
      <div
        style="
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
        "
        v-if="data.length <= 0"
      >
        No Recored found
      </div>

      <div class="flex justify-center q-gutter-xl items-center wrap q-pa-lg">
        <q-card
          class=""
          style="width: 350px; height: auto"
          flat
          bordered
          v-for="item in data"
          :key="item.id"
        >
          <q-img class="img" :src="item.imgUrl" />

          <q-card-section>
            <q-btn
              fab
              color="primary"
              icon="place"
              class="absolute"
              style="top: 0; right: 12px; transform: translateY(-50%)"
            />

            <div class="row no-wrap items-center">
              <div class="col text-h6 ellipsis">
                <span
                  v-if="new Date(item.expires_date) < new Date()"
                  style="text-decoration: line-through"
                  >{{ item.tittle }}</span
                >
                <span v-else>{{ item.tittle }} </span>
                <span
                  style="color: red; font-size: 0.825rem; padding: 0 0.5rem"
                  v-if="new Date(item.expires_date) < new Date()"
                  >Product Expired</span
                >
              </div>
              <div
                class="col-auto text-grey text-caption q-pt-md row no-wrap items-center"
              >
                <q-icon name="place" />
                {{ item.address?.city }}
              </div>
            </div>
          </q-card-section>
          <q-card-section class="q-pt-none">
            <div class="text-subtitle1 flex justify-between">
              <p>{{ item?.user?.bussiness_name }}</p>
              <div class="flex column">
                <span
                  style="
                    text-decoration: line-through;
                    text-decoration-color: red;
                    color: gray;
                  "
                  >${{ item?.price }}</span
                >
                <span v-if="item.discount_price"
                  >${{ item?.discount_price }}</span
                >
              </div>
            </div>
            <div class="text-caption text-grey">
              {{ item.description }}
            </div>
          </q-card-section>

          <q-separator />

          <q-card-actions class="flex justify-between items-start">
            <div>
              <q-btn flat round icon="event" />
              <router-link
                :to="{
                  name: 'bussiness_product_details',
                  params: { id: item.id },
                }"
              >
                <q-btn flat color="primary"> See more... </q-btn>
              </router-link>
            </div>
            <div class="">
              <q-btn-dropdown color="blue" dropdown-icon="more_vert">
                <q-list>
                  <q-item clickable v-close-popup @click="onItemClick">
                    <router-link
                      style="text-decoration: none"
                      :to="{ name: 'edit-post', params: { id: item.id } }"
                    >
                      <q-item-section style="padding: 0 !important">
                        <q-item-label style="padding: 0 !important"
                          >Edit</q-item-label
                        >
                      </q-item-section>
                    </router-link>
                  </q-item>

                  <q-item clickable v-close-popup @click="onItemClick">
                    <q-item-section
                      @click="deletePost(item.id)"
                      style="padding: 0 !important"
                    >
                      <q-item-label style="padding: 0 !important"
                        >Delete</q-item-label
                      >
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-btn-dropdown>
            </div>
          </q-card-actions>
        </q-card>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import Swal from "sweetalert2";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();
const data = ref("");

const user = ref("");
const deletePost = async (id) => {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        axios
          .get(`http://localhost:8000/api/bussiness/product/delete/${id}`, {
            headers: { Authorization: `Bearer ${user.value.token}` },
          })
          .then((res) => {
            bProduct();
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Post Succesfully Deleted",
              showConfirmButton: false,
              timer: 2000,
            });
          });
      } catch (err) {
        console.log(err);
      }
    }
  });
};

const spinner = ref(true);
onMounted(async () => {
  user.value = JSON.parse(localStorage.getItem("user"));
  bProduct();
});
const bProduct = async () => {
  try {
    await axios
      .get(`http://localhost:8000/api/bussiness/products/${user.value.id}`, {
        headers: { Authorization: `Bearer ${user.value.token}` },
      })
      .then((res) => {
        data.value = res.data.products;
        spinner.value = false;
      });
  } catch (err) {
    console.log(err);
  }
};
</script>
<style scoped>
.q-card {
  width: 350px;
  height: 440px;
}
.img {
  height: 220px !important;
}
</style>

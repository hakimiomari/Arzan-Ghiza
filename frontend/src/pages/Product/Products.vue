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
    <div v-else class="wrapper">
      <div style="margin-right: 7rem" class="flex justify-end q-mt-lg w-100">
        <form @submit.prevent="search">
          <div class="flex items-center w-100 ">
            <q-input
              style="width: 400px; padding: 0 !important"
              v-model="searchData"
              filled
              placeholder="Search here..."
            >
              <button
                style="
                  background: transparent;
                  width: 30px !important;
                  border: none;
                  outline: none;
                "
                type="submit"
              >
                <q-icon
                  class="cursor-pointer"
                  style="font-size: 2rem; background-color: transparent"
                  name="search"
                />
              </button>
            </q-input>
          </div>
        </form>
      </div>
      <!-- ////////////////////////////////////////////////// -->
      <div
        v-if="searchError"
        style="height: 50vh"
        class="flex justify-center items-center"
      >
        <p>{{ searchError }}</p>
      </div>
      <div
        v-if="isSearchData"
        class="flex justify-center q-gutter-xl items-center wrap q-pa-lg"
      >
        <q-card
          v-for="item in newData"
          :key="item.id"
          style="width: 350px"
          class=""
          flat
          bordered
        >
          <q-img style="height: 220px" class="img" :src="item?.image" />
          <q-card-section>
            <q-btn
              fab
              color="primary"
              icon="place"
              class="absolute"
              style="top: 0; right: 12px; transform: translateY(-50%)"
            />

            <div class="row no-wrap items-center">
              <div class="col text-h6 ellipsis">{{ item.tittle }}</div>
              <div
                class="col-auto text-grey text-caption q-pt-md row no-wrap items-center"
              >
                <q-icon name="place" />
                {{ item?.city }}
              </div>
            </div>
          </q-card-section>

          <q-card-section class="q-pt-none">
            <div class="text-subtitle1 flex justify-between">
              <p>$・Italian, Cafe</p>
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
          <q-card-actions v-if="user">
            <q-btn flat round icon="event" />
            <router-link
              :to="{ name: 'Product-Details', params: { id: item.id } }"
            >
              <q-btn flat color="primary"> See more... </q-btn>
            </router-link>
          </q-card-actions>
          <q-card-actions v-else>
            <q-btn flat round icon="event" />
            <router-link :to="{ name: 'Details', params: { id: item.id } }">
              <q-btn flat color="primary"> See more... </q-btn>
            </router-link>
          </q-card-actions>
        </q-card>
      </div>
      <!-- ////////////////////////////////////////////////// -->
      <div
        v-else
        class="flex justify-center q-gutter-xl items-center wrap q-pa-lg"
      >
        <q-card
          v-for="item in data"
          :key="item.id"
          style="width: 350px"
          class=""
          flat
          bordered
        >
          <q-img style="height: 220px" class="img" :src="item?.imgUrl" />
          <q-card-section>
            <q-btn
              fab
              color="primary"
              icon="place"
              class="absolute"
              style="top: 0; right: 12px; transform: translateY(-50%)"
            />

            <div class="row no-wrap items-center">
              <div class="col text-h6 ellipsis">{{ item.tittle }}</div>
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
          <q-card-actions v-if="user">
            <q-btn flat round icon="event" />
            <router-link
              :to="{ name: 'Product-Details', params: { id: item.id } }"
            >
              <q-btn flat color="primary"> See more... </q-btn>
            </router-link>
          </q-card-actions>
          <q-card-actions v-else>
            <q-btn flat round icon="event" />
            <router-link :to="{ name: 'Details', params: { id: item.id } }">
              <q-btn flat color="primary"> See more... </q-btn>
            </router-link>
          </q-card-actions>
        </q-card>
      </div>
      <!-- pagination -->
      <Pagination
        @getCurrentPageData="(page) => onPageChange(page)"
        @getPrevPageData="getProducts(prev_page_url)"
        @getNextPageData="getProducts(next_page_url)"
        :current_page="currentPage"
        :last_page="last_page"
      />
      <Footer v-if="footerLoaded" />
    </div>
  </div>
</template>
<script setup>
import { onMounted, onUpdated, ref, watch } from "vue";
import axios from "axios";
import Footer from "src/layouts/footer.vue";
import Pagination from "../Pagination/Pagination.vue";

// pagination ////////////////////////////
const data = ref([]);
const currentPage = ref(null);
const next_page_url = ref(null);
const prev_page_url = ref(null);
const last_page = ref(null);
const baseUrl = "http://127.0.0.1:8000/api/bussiness/products";

const onPageChange = async (page) => {
  currentPage.value = page;
  await getProducts(`${baseUrl}?page=${currentPage.value}`);
};

const spinner = ref(true);
const getProducts = async (url) => {
  await axios
    .get(url)
    .then((res) => {
      data.value = res.data.products.data;
      currentPage.value = res.data.products.current_page;
      last_page.value = res.data.products.last_page;
      next_page_url.value = res.data.products.next_page_url;
      prev_page_url.value = res.data.products.prev_page_url;
      spinner.value = false;
    })
    .catch((err) => console.log(err));
};

const footerLoaded = ref(false);
const user = ref("");
onMounted(() => {
  user.value = JSON.parse(localStorage.getItem("user"));
  getProducts(baseUrl);
  footerLoaded.value = true;
});

//
const newData = ref("");
const searchData = ref("");
const isSearchData = ref(false);
const searchError = ref("");
const search = async () => {
  await axios
    .post("http://127.0.0.1:8000/api/search", { search: searchData.value })
    .then((res) => {
      isSearchData.value = true;
      newData.value = res.data.data;
      currentPage.value = res.data.data.current_page;
      last_page.value = res.data.data.last_page;
      next_page_url.value = res.data.data.next_page_url;
      prev_page_url.value = res.data.data.prev_page_url;
    })
    .catch((err) => {
      isSearchData.value = true;
      searchError.value = err.response.data.message;
    });
};
onUpdated(() => {
  if (searchData.value.length < 1) {
    isSearchData.value = false;
    searchError.value = "";
  }
});
</script>
<style scoped>
.wrapper {
  display: flex;
  min-height: 100vh;
  flex-direction: column;
  justify-content: space-between;
}
.fa:hover {
  cursor: pointer;
}
.q-card {
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2), 0 2px 2px rgba(0, 0, 0, 0.14),
    0 3px 1px -2px rgba(0, 0, 0, 0.12) !important;
}
</style>

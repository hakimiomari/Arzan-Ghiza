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
    <div v-else class="parent-class">
      <div class="inner-class">
        <div class="image-container">
          <img :src="data.image" />
        </div>
        <div class="card-details">
          <div class="q-pa-sm">
            <div class="flex items-center">
              <div class="col text-h3 ellipsis">
                {{ data.tittle }}
              </div>
            </div>
            <div class="flex justify-between">
              <q-rating v-model="stars" :max="5" size="32px" />
              <div class="q-pa-sm">
                <p
                  class="paragraph"
                  style="text-decoration: line-through"
                  v-if="data.price"
                >
                  ${{ data?.price }}
                </p>
                <h6 style="font-weight: 700">${{ data.discount_price }}</h6>
              </div>
            </div>
            <q-timeline color="secondary">
              <q-timeline-entry icon="badge">
                <template v-slot:title> {{ data.tittle }}</template>
                <template v-slot:subtitle> Description</template>

                <div>
                  {{ data.description }}
                </div>
              </q-timeline-entry>

              <q-timeline-entry icon="location_on">
                <template v-slot:title> Address </template>
                <div>
                  <q-table
                    :rows="rows"
                    :rows-per-page-options="[]"
                    color="amber"
                  >
                  </q-table>
                </div>
              </q-timeline-entry>
              <q-timeline-entry icon="info">
                <template v-slot:title>Info </template>
                <q-list bordered separator>
                  <q-item>
                    <q-item-section avatar>
                      <q-avatar
                        color="primary"
                        text-color="white"
                        icon="apartment"
                      />
                    </q-item-section>
                    <q-item-section>{{
                      data?.user?.bussiness_name
                    }}</q-item-section>
                  </q-item>
                  <q-item>
                    <q-item-section avatar>
                      <q-avatar
                        color="primary"
                        text-color="white"
                        icon="history"
                      />
                    </q-item-section>
                    <q-item-section>{{ data.expires_date }}</q-item-section>
                  </q-item>
                  <q-item v-if="data?.user?.delivery">
                    <q-item-section avatar>
                      <q-avatar
                        color="primary"
                        text-color="white"
                        icon="local_shipping"
                      />
                    </q-item-section>
                    <q-item-section>Delivery</q-item-section>
                  </q-item>
                </q-list>
              </q-timeline-entry>
              <q-timeline-entry icon="manage_history">
                <template v-slot:title>Product Info </template>
                <q-list bordered separator>
                  <q-item>
                    <q-item-section avatar>
                      <q-avatar
                        color="primary"
                        text-color="white"
                        icon="shopping_cart"
                      />
                    </q-item-section>
                    <q-item-section>Ordered : {{ orders }}</q-item-section>
                  </q-item>
                  <q-item>
                    <q-item-section avatar>
                      <q-avatar
                        color="primary"
                        text-color="white"
                        icon="shopping_bag"
                      />
                    </q-item-section>
                    <q-item-section
                      >Remaining : {{ data.quantity }}</q-item-section
                    >
                  </q-item>
                </q-list>
              </q-timeline-entry>
            </q-timeline>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import Swal from "sweetalert2";
import axios from "axios";

const new_quantity = ref(1);

const increment = () => new_quantity.value++;
const decrement = () => {
  if (new_quantity.value > 1) {
    return new_quantity.value--;
  }
};

const route = useRoute();
const router = useRouter();
const data = ref("");
const orders = ref(0);
const rows = ref([]);

const spinner = ref(true);
const user = ref("");
user.value = JSON.parse(localStorage.getItem("user"));
onMounted(async () => {
  await axios
    .get(
      `http://localhost:8000/api/products/product/details/${route.params.id}`,
      {
        headers: { Authorization: `Bearer ${user.value.token}` },
      }
    )
    .then((res) => {
      data.value = res.data.product[0];
      res.data.orders.map((item) => (orders.value += item.order_quantity));
      rows.value = [
        {
          city: data.value.address.city,
          district: data.value.address.district,
          valige: data.value.address.valige,
        },
      ];
      spinner.value = false;
    });
});

const submit = async () => {
  if (user.value) {
    router.push({
      path: `/checkout/${route.params.id}`,
      query: { quantity: new_quantity.value },
    });
  } else {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please Login and Try Again!",
      footer: '<a href="#">Why do I have this issue?</a>',
    });
  }
};
</script>
<style scoped>
.q-timeline__title {
  margin-bottom: 8px !important;
  font-size: 12px !important;
}
.q-timeline__subtitle {
  font-size: 12px !important;
}
.q-table__bottom {
  display: none !important;
}
.q-table thead tr {
  height: 30px !important;
}
td {
  font-weight: 500;
  letter-spacing: 1px;
}
thead {
  background-color: rgb(22, 142, 216);
}
.q-table__bottom {
  display: none;
}
.parent-class {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 2rem 0 3rem;
  width: 100vw;
}
.inner-class {
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2), 0 2px 2px rgba(0, 0, 0, 0.14),
    0 3px 1px -2px rgba(0, 0, 0, 0.12) !important;
  width: 50%;
}
.q-timeline__entry {
  line-height: 20px;
}
li {
  font-size: 0.8rem;
}
.q-timeline__content {
  padding-bottom: 0 !important;
}
.q-timeline__title {
  margin: 10px 0 !important;
}
.image-container > img {
  width: 100%;
  height: 250px;
  object-fit: cover;
  background-position: center;
}
.card-details {
  width: 100%;
  padding: 0 2rem;
}
</style>

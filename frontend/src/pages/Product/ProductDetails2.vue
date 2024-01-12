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
              <div class="col text-h5 ellipsis">
                {{ data.tittle }}
              </div>
            </div>
            <div class="flex justify-between">
              <q-rating v-model="stars" :max="5" size="24px" />
              <div>
                <p
                  class="paragraph"
                  style="text-decoration: line-through"
                  v-if="data.price"
                >
                  ${{ data?.price }}
                </p>
                <h6 style="font-weight: 600">${{ data.discount_price }}</h6>
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
                  <!-- ///////////// -->
                  <q-item v-else>
                    <q-item-section avatar>
                      <q-avatar
                        color="primary"
                        text-color="white"
                        icon="local_shipping"
                      />
                    </q-item-section>
                    <q-item-section>No Delivery</q-item-section>
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
          <div
            style="
              display: flex;
              justify-content: space-between;
              align-items: center;
              margin-bottom: 1rem;
              padding: 0 1rem;
            "
          >
            <div
              style="
                display: flex;
                align-items: center;
                gap: 1rem;
                border: 1px solid #b6b2b2;
                padding: 5px 15px;
                border-radius: 3px;
              "
            >
              <div>
                <span style="font-size: 1rem">Quantity: </span>
                <span style="font-size: 1rem">{{ new_quantity }}</span>
              </div>
              <div style="display: flex; flex-direction: column">
                <q-icon
                  name="expand_less"
                  style="font-size: 1.4rem; cursor: pointer"
                  @click="increment"
                ></q-icon>
                <q-icon
                  name="expand_more"
                  style="font-size: 1.4rem; cursor: pointer"
                  @click="decrement"
                ></q-icon>
              </div>
            </div>
            <q-btn
              color="blue"
              @click="submit"
              class="order-btn"
              style="letter-spacing: 2px"
              >Buy now!</q-btn
            >
          </div>
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import Swal from "sweetalert2";
import Footer from "src/layouts/footer.vue";
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
onMounted(() => {
  getProductDate();
});
const user = ref("");
user.value = JSON.parse(localStorage.getItem("user"));

const getProductDate = async () => {
  await axios
    .get(
      `http://localhost:8000/api/products/product/details/${route.params.id}`
    )
    .then((res) => {
      console.log(res);
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
    })
    .catch((err) => {
      Swal.fire({
        title: "This Product is unavailable",
        confirmButtonText: "Ok",
      }).then((result) => {
        if (result.isConfirmed) {
          spinner.value = false;
        }
      });
      router.push("/authenticated");
    });
};
const getFreshProduct = async () => {
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
    })
    .catch((err) => {
      router.push("/authenticated");
    });
};

const submit = async () => {
  if (user.value) {
    if (new_quantity.value <= data.value.quantity) {
      await axios
        .post(
          "http://localhost:8000/api/products/check_product",
          {
            quantity: new_quantity.value,
            id: data.value.id,
          },
          {
            headers: { Authorization: `Bearer ${user.value.token}` },
          }
        )
        .then((res) => {
          router.push({
            path: `/checkout/${route.params.id}`,
            query: { quantity: new_quantity.value },
          });
        })
        .catch((err) => {
          console.log(err);
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: err.response.data.quantity
              ? err.response.data.quantity
              : err.response.data.expires_date,
            footer: '<a href="#">Why do I have this issue?</a>',
          });
          if (err.response.data.quantity) {
            getFreshProduct();
          }
          if (err.response.data.expires_date) {
            router.push("/authenticated");
          }
        });
    } else {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "The Order Quantity Should Less than or Equal to the Product Quantity",
        footer: '<a href="#">Why do I have this issue?</a>',
      });
    }
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

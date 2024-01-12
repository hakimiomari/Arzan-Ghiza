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
        No Record Exist
      </div>

      <div
        v-else
        class="q-pa-md"
        style="display: flex; flex-direction: column; gap: 1rem"
      >
        <q-table
          flat
          table-class="text-dark-8"
          table-header-class="text-orange bg-blue-5"
          bordered
          title="Your Orders"
          :rows="data"
          :columns="columns"
          row-key="name"
          :rows-per-page-options="[20]"
          color="primary"
        >
          <template #body-cell-action="props">
            <q-td class="flex justify-center">
              <router-link
                :to="{
                  name: 'update-user-profile',
                  params: { id: props.row.id },
                }"
              >
                <q-icon
                  size="sm"
                  name="edit"
                  color="primary"
                  style="margin-right: 0.5rem"
                  class="cursor-pointer"
                ></q-icon>
              </router-link>

              <q-icon
                color="warning"
                size="sm"
                name="delete"
                class="cursor-pointer"
                @click="deleteUser(props.row)"
              ></q-icon>
            </q-td>
          </template>
        </q-table>
        <Pagination
          @getCurrentPageData="(page) => onPageChange(page)"
          @getPrevPageData="getAllUsers(prev_page_url)"
          @getNextPageData="getAllUsers(next_page_url)"
          :current_page="currentPage"
          :last_page="last_page"
        />

        <div class="q-mt-lg q-mb-lg flex justify-center q-mx-lg">
          <div
            class="q-mx-lg"
            style="display: block; width: 500px; height: 500px"
          >
            <canvas
              id="barChart"
              style="height: 350px !important; width: 500px !important"
            ></canvas>
          </div>
          <div
            class="q-mx-lg"
            style="display: block; width: 400px; height: 400px"
          >
            <canvas
              id="myChart"
              style="height: 350px !important; width: 500px !important"
            ></canvas>
          </div>
        </div>
        <div class="flex justify-center gap-4 q-mt-lg">
          <div
            style="
              background-color: blueviolet;
              padding: 2rem;
              border-radius: 10px;
              color: #fff;
              margin-right: 1rem;
              margin-bottom: 1rem;
            "
          >
            <h4 class="q-mx-lg">Total Price: {{ totalPrice }}</h4>
          </div>
          <div
            style="
              background-color: blueviolet;
              padding: 2rem;
              border-radius: 10px;
              color: #fff;
              margin-bottom: 1rem;
            "
          >
            <h4 class="q-mx-lg">Total Products: {{ allProducts.length }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import Pagination from "../Pagination/Pagination.vue";
import Chart from "chart.js/auto";
import axios from "axios";

// ///////////////////////////////
const user = ref("");
user.value = JSON.parse(localStorage.getItem("user"));
const data = ref([]);
const currentPage = ref(null);
const next_page_url = ref(null);
const prev_page_url = ref(null);
const last_page = ref(null);
const baseUrl = `http://localhost:8000/api/bussiness/sales/${user.value.id}`;

const onPageChange = async (page) => {
  currentPage.value = page;
  await getAllUsers(`${baseUrl}?page=${currentPage.value}`);
};

// columns;
const columns = ref([
  {
    name: "id",
    required: true,
    label: "Order ID",
    align: "left",
    field: "id",
    sortable: true,
  },
  {
    name: "bussiness_name",
    required: true,
    label: "Product Name",
    align: "left",
    field: "product_name",
    sortable: true,
  },
  {
    name: "customer",
    required: true,
    label: "Customer",
    align: "left",
    field: "customer",
    sortable: true,
  },
  {
    name: "price",
    label: "Price",
    align: "left",
    field: "price",
    sortable: true,
  },
  {
    name: "order_quantity",
    label: "Order Quantity",
    align: "left",
    field: "order_quantity",
    sortable: true,
  },
  {
    name: "total",
    label: "Total Amount",
    align: "left",
    field: "total",
    sortable: true,
  },
  {
    name: "created_at",
    label: "Order Date",
    align: "left",
    field: "created_at",
    sortable: true,
  },
]);

const spinner = ref(true);
const BUserOrders = async (url) => {
  await axios
    .get(url, {
      headers: { Authorization: `Bearer ${user.value.token}` },
    })
    .then((res) => {
      console.log(res);
      data.value = res.data.order.data;
      currentPage.value = res.data.order.current_page;
      last_page.value = res.data.order.last_page;
      next_page_url.value = res.data.order.next_page_url;
      prev_page_url.value = res.data.order.prev_page_url;
      spinner.value = false;
    });
};

// ///////////////////////
const allChartData = ref([]);
const allProducts = ref([]);
const orderCountsByMonth = ref({});
const productCountsByMonth = ref({});
const getChartData = async () => {
  await axios
    .get(`http://localhost:8000/api/bussiness/chart/${user.value.id}`, {
      headers: { Authorization: `Bearer ${user.value.token}` },
    })
    .then((res) => {
      allChartData.value = res.data.orders;
      allProducts.value = res.data.products;
      orderCountsByMonth.value = countProductsByMonth(allChartData.value);
      productCountsByMonth.value = countProductsByMonth(allProducts.value);
      barChart();
      circleChart();
    });
};

const totalPrice = ref(0);
const countProductsByMonth = (products) => {
  const counts = {};

  // Iterate over each product
  products.forEach((product) => {
    if (product.order_quantity) {
      totalPrice.value += product.price;
    }
    const date = new Date(product.created_at);
    const month = date.toLocaleString("default", { month: "long" }); // Get month name

    // Increment the count for the corresponding month
    if (counts[month]) {
      counts[month] += 1;
    } else {
      counts[month] = 1;
    }
  });

  return counts;
};

onMounted(async () => {
  BUserOrders(baseUrl);
  getChartData();
});
const circleChart = () => {
  const ctx = document.getElementById("myChart");
  // doughnut
  const myChart = new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
      datasets: [
        {
          label: "# of Orders",
          data: [
            orderCountsByMonth?.value.January
              ? orderCountsByMonth?.value.January
              : 0,
            orderCountsByMonth?.value.February
              ? orderCountsByMonth?.value.February
              : 0,
            orderCountsByMonth?.value.March
              ? orderCountsByMonth?.value.March
              : 0,
            orderCountsByMonth?.value.April
              ? orderCountsByMonth?.value.April
              : 0,
            orderCountsByMonth?.value.May ? orderCountsByMonth?.value.May : 0,
            orderCountsByMonth?.value.June ? orderCountsByMonth?.value.June : 0,
            orderCountsByMonth?.value.July ? orderCountsByMonth?.value.July : 0,
            orderCountsByMonth?.value.August
              ? orderCountsByMonth?.value.August
              : 0,
            orderCountsByMonth?.value.September
              ? orderCountsByMonth?.value.September
              : 0,
            orderCountsByMonth?.value.October
              ? orderCountsByMonth?.value.October
              : 0,
            orderCountsByMonth?.value.November
              ? orderCountsByMonth?.value.November
              : 0,
            orderCountsByMonth?.value.December
              ? orderCountsByMonth?.value.December
              : 0,
          ],
          borderWidth: 1,
        },
        {
          label: "# of Product",
          data: [
            productCountsByMonth?.value.January
              ? productCountsByMonth?.value.January
              : 0,
            productCountsByMonth?.value.February
              ? productCountsByMonth?.value.February
              : 0,
            productCountsByMonth?.value.March
              ? productCountsByMonth?.value.March
              : 0,
            productCountsByMonth?.value.April
              ? productCountsByMonth?.value.April
              : 0,
            productCountsByMonth?.value.May
              ? productCountsByMonth?.value.May
              : 0,
            productCountsByMonth?.value.June
              ? productCountsByMonth?.value.June
              : 0,
            productCountsByMonth?.value.July
              ? productCountsByMonth?.value.July
              : 0,
            productCountsByMonth?.value.August
              ? productCountsByMonth?.value.August
              : 0,
            productCountsByMonth?.value.September
              ? productCountsByMonth?.value.September
              : 0,
            productCountsByMonth?.value.October
              ? productCountsByMonth?.value.October
              : 0,
            productCountsByMonth?.value.November
              ? productCountsByMonth?.value.November
              : 0,
            productCountsByMonth?.value.December
              ? productCountsByMonth?.value.December
              : 0,
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
  myChart;
};
const barChart = () => {
  const ctx = document.getElementById("barChart");
  // doughnut
  const myChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
      datasets: [
        {
          label: "# of Orders",
          data: [
            orderCountsByMonth?.value.January
              ? orderCountsByMonth?.value.January
              : 0,
            orderCountsByMonth?.value.February
              ? orderCountsByMonth?.value.February
              : 0,
            orderCountsByMonth?.value.March
              ? orderCountsByMonth?.value.March
              : 0,
            orderCountsByMonth?.value.April
              ? orderCountsByMonth?.value.April
              : 0,
            orderCountsByMonth?.value.May ? orderCountsByMonth?.value.May : 0,
            orderCountsByMonth?.value.June ? orderCountsByMonth?.value.June : 0,
            orderCountsByMonth?.value.July ? orderCountsByMonth?.value.July : 0,
            orderCountsByMonth?.value.August
              ? orderCountsByMonth?.value.August
              : 0,
            orderCountsByMonth?.value.September
              ? orderCountsByMonth?.value.September
              : 0,
            orderCountsByMonth?.value.October
              ? orderCountsByMonth?.value.October
              : 0,
            orderCountsByMonth?.value.November
              ? orderCountsByMonth?.value.November
              : 0,
            orderCountsByMonth?.value.December
              ? orderCountsByMonth?.value.December
              : 0,
          ],
          borderWidth: 1,
        },
        {
          label: "# of Product",
          data: [
            productCountsByMonth?.value.January
              ? productCountsByMonth?.value.January
              : 0,
            productCountsByMonth?.value.February
              ? productCountsByMonth?.value.February
              : 0,
            productCountsByMonth?.value.March
              ? productCountsByMonth?.value.March
              : 0,
            productCountsByMonth?.value.April
              ? productCountsByMonth?.value.April
              : 0,
            productCountsByMonth?.value.May
              ? productCountsByMonth?.value.May
              : 0,
            productCountsByMonth?.value.June
              ? productCountsByMonth?.value.June
              : 0,
            productCountsByMonth?.value.July
              ? productCountsByMonth?.value.July
              : 0,
            productCountsByMonth?.value.August
              ? productCountsByMonth?.value.August
              : 0,
            productCountsByMonth?.value.September
              ? productCountsByMonth?.value.September
              : 0,
            productCountsByMonth?.value.October
              ? productCountsByMonth?.value.October
              : 0,
            productCountsByMonth?.value.November
              ? productCountsByMonth?.value.November
              : 0,
            productCountsByMonth?.value.December
              ? productCountsByMonth?.value.December
              : 0,
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
  myChart;
};
</script>
<style>
.q-table__title {
  font-size: 2rem !important;
  font-weight: bold !important;
}
th {
  font-size: 1rem !important;
  color: #fff !important;
  font-weight: bold !important;
}
td {
  font-size: 1rem !important;
}
</style>

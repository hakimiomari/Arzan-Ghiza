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
    <div
      v-else
      class="q-pa-md"
      style="display: flex; flex-direction: column; gap: 1rem"
    >
      <q-table
        flat
        bordered
        table-class="text-dark-8"
        table-header-class="text-orange bg-blue-5"
        title="Users"
        :rows="data"
        :columns="columns"
        row-key="name"
        :rows-per-page-options="[20]"
        color="amber"
        :row-class="(props) => (props.index % 2 === 0 ? 'zebra-row' : '')"
      >
        <template v-slot:top-right>
          <router-link to="add_new_user">
            <q-btn color="primary" icon="add">Add User</q-btn>
          </router-link>
        </template>
        <template #body-cell-action="props">
          <q-td class="flex justify-center zebra-style">
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
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import Pagination from "../Pagination/Pagination.vue";
import Swal from "sweetalert2";
import axios from "axios";

// ///////////////////////////////
const data = ref([]);
const currentPage = ref(null);
const next_page_url = ref(null);
const prev_page_url = ref(null);
const last_page = ref(null);
const baseUrl = "http://localhost:8000/api/admin/users";

const onPageChange = async (page) => {
  currentPage.value = page;
  await getAllUsers(`${baseUrl}?page=${currentPage.value}`);
};

// columns;
const columns = ref([
  {
    name: "name",
    required: true,
    label: "Name",
    align: "left",
    field: "name",
    sortable: true,
  },
  {
    name: "email",
    required: true,
    label: "Email",
    align: "left",
    field: "email",
    sortable: true,
  },
  {
    name: "phone",
    label: "Phone",
    align: "left",
    field: "phone",
    sortable: true,
  },
  {
    name: "city",
    label: "City",
    align: "left",
    field: "city",
    sortable: true,
  },
  {
    name: "district",
    label: "District",
    align: "left",
    field: "district",
    sortable: true,
  },
  {
    name: "valige",
    label: "Valige",
    align: "left",
    field: "valige",
    sortable: true,
  },
  {
    name: "created At",
    label: "Created At",
    align: "left",
    field: "created_at",
    sortable: true,
  },
  {
    name: "updated_at",
    label: "Updated At",
    align: "left",
    field: "updated_at",
    sortable: true,
  },
  { name: "action", label: "Action", align: "center", sortable: true },
]);

// delete
const admin = JSON.parse(localStorage.getItem("user"));
const deleteUser = (user) => {
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
          .get(`http://localhost:8000/api/user/delete/${user.id}`, {
            headers: { Authorization: `Bearer ${admin.token}` },
          })
          .then((res) => {
            getAllUsers(baseUrl);
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "account succesfully deleted",
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
const getAllUsers = async (url) => {
  await axios
    .get(url, { headers: { Authorization: `Bearer ${admin.token}` } })
    .then((res) => {
      data.value = res.data.users.data;
      currentPage.value = res.data.users.current_page;
      last_page.value = res.data.users.last_page;
      next_page_url.value = res.data.users.next_page_url;
      prev_page_url.value = res.data.users.prev_page_url;
      spinner.value = false;
    });
};

onMounted(async () => {
  getAllUsers(baseUrl);
});
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
.zebra-style {
  background-color: #f5eaea !important; /* Set the background color for even rows */
}

.zebra-style:nth-child(odd) {
  background-color: #ffffff !important; /* Set the background color for odd rows */
}
</style>

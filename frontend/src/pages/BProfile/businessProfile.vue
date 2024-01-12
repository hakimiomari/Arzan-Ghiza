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
      <div class="row justify-center q-gutter-lg q-mt-md">
        <q-img
          :src="data[0]?.photo"
          class="col-11 col-md-4"
          style="border: 1px solid rgb(206, 199, 199); border-radius: 5px"
        ></q-img>
        <div class="col-10 col-md-6">
          <div class="row q-gutter-x-lg items-center q-mt-md">
            <h6 class="">{{ data[0]?.name }}</h6>
            <span class="text-grey"
              ><q-icon name="place" size="20px" /> {{ data[0]?.address?.city }},
              Afghanistan</span
            >
          </div>
          <div>
            <q-rating v-model="stars" :max="5" size="30px" />
            <span style="font-size: 1rem" class="text-grey q-ml-md"
              >(2 Reviews)</span
            >
          </div>
          <div class="text-blue q-mt-sm"><p>Business Type</p></div>
          <div class="row q-gutter-x-xl">
            <p class="">
              <q-icon name="email" size="24px" class="q-mr-sm" />Email :
            </p>
            <span class="text-blue">{{ data[0]?.email }}</span>
          </div>
          <div class="row q-gutter-x-xl">
            <p class="">
              <q-icon name="phone" size="24px" class="q-mr-sm" />Phone :
            </p>
            <span class="text-blue">{{ data[0]?.phone }}</span>
          </div>
          <div class="row q-gutter-x-lg" style="text-wrap: no-wrap">
            <p class="">
              <q-icon name="place" size="24px" class="q-mr-sm" />Address
            </p>
            <span class="text-blue"
              >{{ data[0]?.address?.valige }},{{ data[0]?.address?.district }},
              {{ data[0]?.address?.city }}</span
            >
          </div>
          <div class="row q-gutter-x-lg">
            <p class="">
              <q-icon name="category" size="24px" class="q-mr-sm" />Category
            </p>
            <span class="text-blue">Fast Food</span>
          </div>
          <div class="row q-gutter-lg q-mt-md">
            <q-btn
              flat
              color="white"
              class="q-my-md bg-blue"
              style="
                border: 1px solid rgb(0, 162, 255);
                text-transform: capitalize;
              "
              to="/update-bussiness-profile"
              >Update Profile</q-btn
            >
            <q-btn
              to="/reset-bussiness-profile-password"
              flat
              color="white"
              class="q-my-md bg-blue"
              style="
                border: 1px solid rgb(0, 162, 255);
                text-transform: capitalize;
              "
              >Reset Password</q-btn
            >
          </div>

          <hr class="text-grey" />
        </div>
      </div>

      <div class="row justify-center q-gutter-lg q-mt-md q-mb-xl">
        <div class="col-10 col-md-4">
          <h6>Business Details</h6>
          <p class="text-justify text-grey text-bold">
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quaerat
            obcaecati quis molestias ad vel explicabo optio quae expedita
            repellat accusamus. Unde possimus eius quis vero exercitationem
            neque quam quos
          </p>
          <q-btn
            @click="deleteAccount"
            flat
            color="white"
            class="q-my-md bg-red"
            style="
              border: 1px solid rgb(0, 162, 255);
              text-transform: capitalize;
            "
            >Delete Account</q-btn
          >
        </div>
        <div
          class="col-12 col-md-6 row justify-center items-start q-gutter-lg q-mr-md"
        >
          <div
            class="text-center text-blue q-pa-md"
            style="border: 1px solid rgb(0, 149, 255)"
          >
            <h6>
              <q-icon name="folder" />
              Product
            </h6>
            <h6>{{ data[0]?.products.length }}</h6>
          </div>
          <div
            class="text-center text-blue q-pa-md"
            style="border: 1px solid rgb(0, 149, 255)"
          >
            <h6><q-icon name="folder" /> Sales</h6>
            <h6>{{ data[0]?.sales.length }}</h6>
          </div>
        </div>
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

const user = ref("");
const data = ref("");
const spinner = ref(true);
onMounted(async () => {
  user.value = JSON.parse(localStorage.getItem("user"));
  await axios
    .get(`http://localhost:8000/api/bussiness/user/${user.value.id}`, {
      headers: { Authorization: `Bearer ${user.value.token}` },
    })
    .then((res) => {
      data.value = res.data.user;
      spinner.value = false;
    });
});

// delete account
const deleteAccount = async () => {
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
          .get(
            `http://localhost:8000/api/bussiness/user/delete/${data.value[0].id}`,
            {
              headers: { Authorization: `Bearer ${user.value.token}` },
            }
          )
          .then((res) => {
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Account Succesfully Deleted",
              showConfirmButton: false,
              timer: 2000,
            });
            localStorage.removeItem("user");
            router.push("/");
          });
      } catch (err) {
        console.log(err);
      }
    }
  });
};
</script>

<style scoped>
body {
  overflow-x: hidden;
}
p,
span {
  font-size: 1rem;
  text-overflow: ellipsis;
  word-wrap: break-word;
}
</style>

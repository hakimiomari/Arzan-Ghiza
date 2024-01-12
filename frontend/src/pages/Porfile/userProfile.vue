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
    <div v-else class="container">
      <div class="main-bodyy">
        <div
          class="col-12 text-center"
          style="margin-bottom: 2rem; margin-top: 2rem"
        >
          <h4 style="font-weight: 700; color: #424141">User Profile</h4>
        </div>
        <div class="row q-gutter-lg justify-center">
          <div class="col-md-3 mb-3">
            <div class="cardd">
              <div class="card-bodyy">
                <div
                  class="d-flex flex-column justify-center items-center text-center"
                >
                  <q-img
                    style="border: 10px solid #666464"
                    v-if="data.image"
                    :src="data.image"
                    class="imgg"
                  />
                  <q-img
                    style="border: 10px solid #666464; width: 50%; height: 50%"
                    id="profilePicture"
                    v-else
                    src="src/assets/images/profile22.avif"
                    alt=""
                    class="imgg block"
                  />
                  <div class="mt-3">
                    <h4>{{ data?.name }}</h4>
                    <p class="text-secondary mb-1">{{ data?.email }}</p>
                    <p class="text-muted font-size-sm">
                      {{ data?.valige }}, {{ data?.district }}, {{ data?.city }}
                    </p>
                    <q-btn v-if="user.id !== 1" color="red" @click="deleteUser"
                      >Delete Account
                    </q-btn>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="cardd mb-3">
              <div class="card-bodyy">
                <div class="row fields">
                  <div>
                    <h6 class="mb-0">Name:</h6>
                  </div>
                  <div class="col-sm-9 text-secondary values">
                    {{ data?.name }}
                  </div>
                </div>
                <hr />
                <div class="row fields">
                  <div>
                    <h6 class="mb-0">Email:</h6>
                  </div>
                  <div class="col-sm-9 text-secondary values">
                    {{ data?.email }}
                  </div>
                </div>
                <hr />
                <div class="row fields">
                  <div>
                    <h6 class="mb-0">Phone:</h6>
                  </div>
                  <div class="col-sm-9 text-secondary values">
                    {{ data?.phone }}
                  </div>
                </div>

                <hr />
                <div class="row fields">
                  <div>
                    <h6 class="mb-0">Address:</h6>
                  </div>
                  <div class="col-sm-9 text-secondary values">
                    {{ data?.valige }}, {{ data?.district }}, {{ data?.city }}
                  </div>
                </div>
                <hr />
                <div class="row fields q-mb-lg">
                  <div class="col-sm-12">
                    <router-link
                      v-if="user.id == 1"
                      style="
                        width: 150px;
                        text-align: center;
                        padding: 1rem;
                        font-size: 1rem;
                      "
                      to="/admin-updateProfile"
                      color="blue"
                      id="updateButton"
                      class="router-linkk"
                    >
                      <q-btn style="padding: 0.625rem 1.2rem" color="blue"
                        >Update Profile</q-btn
                      >
                    </router-link>
                    <router-link
                      v-else
                      style="
                        width: 150px;
                        text-align: center;
                        padding: 0.725rem;
                        font-size: 1rem;
                      "
                      to="/updateProfile"
                      id="updateButton"
                    >
                      <q-btn
                        style="padding: 0.625rem 1.2rem margin-top: 2rem;"
                        color="blue"
                        >Update Profile</q-btn
                      >
                    </router-link>
                    <router-link
                      v-if="user.id == 1"
                      to="admin-changeUserPassword"
                      id="updateButton"
                      style="
                        width: 150px;
                        text-align: center;
                        padding: 0.725rem;
                        font-size: 1rem;
                      "
                    >
                      <q-btn style="padding: 0.625rem 1.2rem" color="blue"
                        >Reset Password</q-btn
                      >
                    </router-link>
                    <router-link
                      v-else
                      to="changeUserPassword"
                      color="blue"
                      id="updateButton"
                      class="router-linkk"
                      style="
                        width: 150px;
                        text-align: center;
                        padding: 0.725rem;
                        font-size: 1rem;
                      "
                    >
                      <q-btn style="padding: 0.625rem 1.2rem" color="blue"
                        >Reset Password</q-btn
                      >
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import { useRouter } from "vue-router";

const router = useRouter();
const user = ref("");
const data = ref("");

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

const deleteUser = () => {
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
          .get(`http://127.0.0.1:8000/api/user/delete/${data.value.id}`, {
            headers: { Authorization: `Bearer ${user.value.token}` },
          })
          .then((res) => {
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "account succesfully deleted",
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
.values {
  font-size: 1.2rem;
}

.main-bodyy {
  padding: 15px;
  width: 100%;
  height: 100%;
}
.cardd {
  margin-bottom: 1rem;
  padding: 1rem;
  box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.1),
    0 10px 20px 0 rgba(0, 0, 0, 0.06);
}
.fields {
  display: flex;
  align-items: center;
  flex-direction: row;
  justify-content: start;
  gap: 1rem;
  padding: 0.725rem 0;
}

.cardd {
  position: relative;
  height: 100%;
  display: flex;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 0 solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
}

.card-bodyy {
  flex: 1 1 auto;
  min-height: 1px;
  padding: 1rem;
  display: flex;
  /* justify-content: center; */
  /* align-items: center; */
  flex-direction: column;
}

.gutters-smm {
  margin-right: -8px;
  margin-left: -8px;
}

.gutters-smm > .coll,
.gutters-smm > [class*="col-"] {
  padding-right: 8px;
  padding-left: 8px;
}
.mbb-3,
.myy-3 {
  margin-bottom: 1rem !important;
}

.bg-grayy300 {
  background-color: #e2e8f0;
}
.hh-100 {
  height: 100% !important;
}
.shadow-nonee {
  box-shadow: none !important;
}
.imgg {
  width: 180px;
  height: 180px;
  margin: 2rem;
  border-radius: 50%;
}
</style>

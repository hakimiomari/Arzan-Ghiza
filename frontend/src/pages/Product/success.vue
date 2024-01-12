<template>
  <div class="row q-ma-lg justify-evenly items-center" style="height: 100vh">
    <q-card class="col-12 col-lg-4 col-md-5">
      <q-card-section>
        <div class="row justify-center q-py-sm">
          <q-icon
            name="check_circle_round"
            size="30px"
            class="text-green q-ma-auto block"
          />
        </div>
        <h6 class="text-center" style="color: rgb(14, 247, 14)">
          Payment Successfull
        </h6>
        <p class="text-center text-grey">Transaction Number: 498702537495</p>
      </q-card-section>
      <q-separator></q-separator>
      <q-card-section>
        <div class="row justify-around text-grey">
          <p>Amount Paid:</p>
          <p>$20.00</p>
        </div>
        <div class="row justify-around text-grey">
          <p>Subscription:</p>
          <p>One Time Subscription</p>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>
<script setup>
import axios from "axios";
import { onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const user = JSON.parse(localStorage.getItem("user"));
onMounted(async () => {
    axios
      .get("http://localhost:8000/api/saving-order-data", {
        headers: { Authorization: `Bearer ${user.token}` },
      })
      .then((res) => {
          router.push("/");
      })
      .catch((err) => {
        console.log(err);
      });
});
</script>

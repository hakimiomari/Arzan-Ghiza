<template>
  <div
    style="
      width: 100vw;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    "
  >
    <div class="q-gutter-md row">
      <q-spinner color="primary" size="5em" :thickness="7" />
    </div>
    <stripe-checkout
      ref="checkoutRef"
      mode="payment"
      :sessionId="sessionId"
      :pk="publishableKey"
      :line-items="lineItems"
      :success-url="successURL"
      :cancel-url="cancelURL"
      @loading="(v) => (loading = v)"
    />
  </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { StripeCheckout } from "@vue-stripe/vue-stripe";
import axios from "axios";

const route = useRoute();

const publishableKey = ref(
  "pk_test_51O4z8xLVQxq2gpGf35hMCay5Cllxeu4abPID4q3JsXweQ8NvIQGePtBuiQAiWU4LtmLUm56fi7eqRZ8qVO7v9cuJ00ixmWlezl"
);
const checkoutRef = ref(null);
const sessionId = ref(null);

onMounted(() => {
  getSession();
});

const user = JSON.parse(localStorage.getItem("user"));
const getSession = async () => {
  axios
    .post(
      `http://localhost:8000/api/getSession/`,
      {
        id: route.params.id,
        quantity: route.query.quantity,
      },
      {
        headers: { Authorization: `Bearer ${user.token}` },
      }
    )
    .then((res) => {
      sessionId.value = res.data.checkout.id;
      checkoutRef.value.redirectToCheckout();
    })
    .catch((err) => console.log(err));
};
</script>

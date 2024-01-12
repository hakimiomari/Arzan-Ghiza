<template>
  <div style="margin-top: 2rem">
    <h3 class="text-center">Our Services</h3>
    <div class="row q-my-lg q-gutter-lg justify-center">
      <q-img
        src="../../assets/images/cock.jpg"
        class="image col-8 col-sm-6 col-md-4 col-lg-3 order-2"
        style=""
        alt=""
      />

      <div class="col-10 col-md-5 col-lg-4">
        <p class="text-h6 text-md-h4 text-bold order-first">
          Arzan Ghiza Services Overview
        </p>
        <p class="text-justify text-grey-7">
          At Arzan Ghiza, we offer innovative services to tackle food waste and
          promote sustainability. Our user-friendly platform empowers
          individuals, businesses, and charities to actively reduce food waste
          and make a positive impact. With streamlined food donation, surplus
          food reaches vulnerable populations instead of landfills. Our waste
          reduction strategies optimize operations, minimize waste, and improve
          the bottom line. Partnering with us showcases commitment to
          sustainability, enhancing brand image and attracting eco-conscious
          customers. Our educational programs equip individuals with knowledge
          and tools for sustainable choices. Together, we create a future of
          minimized food waste, nourished communities, and a thriving planet.
          Join Arzan Ghiza today and be part of the sustainable food revolution.
        </p>
        <div
          style="border-bottom: 1px solid black; height: 1px; width: 100%"
        ></div>
      </div>
      <div
        class="col-10 col-md-8 row q-gutter-x-md q-mt-lg bg-blue text-white q-py-lg q-px-md"
        style="margin-top: 100px"
      >
        <q-icon name="redeem" color="" size="60px" class="col-2"></q-icon>
        <div class="col-10 col-md-8">
          <p class="text-h6 q-mb-sm">Discounted Purchase</p>
          <p class="text-justify">
            Our discounted purchase service offers individuals and businesses a
            convenient platform to upload surplus food details, such as quantity
            and expiration dates. Users can track their contributions and
            receive recognition for their efforts in reducing food waste.
          </p>
        </div>
      </div>
      <div
        class="col-10 col-md-8 row q-gutter-x-md q-mt-lg bg-blue text-white q-py-lg q-px-md"
      >
        <q-icon
          name="shopping_cart"
          color=""
          size="60px"
          class="col-2"
        ></q-icon>
        <div class="col-10 col-md-8">
          <p class="text-h6 q-mb-sm">Inventory management system</p>
          <p class="text-justify">
            Our inventory management service helps businesses effectively track
            and manage their food inventory. With features like stock
            monitoring, expiration date reminders, and waste notifications,
            businesses can optimize inventory and make informed decisions to
            reduce overproduction and waste.
          </p>
        </div>
      </div>

      <div
        class="col-10 col-md-8 row q-gutter-x-md q-mt-lg bg-blue text-white q-py-lg q-px-md"
      >
        <q-icon name="redeem" color="" size="60px" class="col-2"></q-icon>
        <div class="col-10 col-md-9">
          <p class="text-h6 q-mb-sm">Collaborative Community</p>
          <p class="text-justify">
            Arzan Ghiza creates a collaborative community for individuals,
            businesses, and charities dedicated to reducing food waste. Our
            platform facilitates networking, knowledge sharing, and
            collaboration, allowing users to connect, participate in
            discussions, and access resources for a sustainable and food-secure
            future.
          </p>
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>
<script setup>
import Footer from "../../layouts/footer.vue";
</script>

<style scoped>
p {
  line-height: 2;
}
.image {
  width: 300px;
  height: 300px;
}
</style>

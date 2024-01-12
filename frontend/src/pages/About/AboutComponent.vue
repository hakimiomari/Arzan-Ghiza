<template>
  <div>
  <div
    class="row justify-center items-center q-gutter-y-lg"
    style="margin-bottom: 3rem"
  >
    <div class="about-section-head col-12 q-mb-lg">
      <h2
        class="text-center text-white text-bold q-mb-lg"
        style="margin-top: 5rem"
      >
        About Us
      </h2>
      <div class="row justify-center items-center q-gutterx-lg q-mx-lg">
        <h5 class="text-white text-center col-12 text-bold q-mb-md">
          A Sustainable Solution for a Greener Future
        </h5>
        <p
          class="text-white col-12 col-sm-8 col-md-6 text-justify"
          style="font-size: large; line-height: 1.8"
        >
          Welcome to Arzan Ghiza, the leading innovator in food waste management
          and sustainability. We are passionate about revolutionizing the way we
          address food waste, and our mission is to create a greener future by
          providing a comprehensive and efficient solution. With our
          cutting-edge technology, user-friendly platform, and unwavering
          commitment to sustainability, Arzan Ghiza is at the forefront of
          transforming how individuals, businesses, and communities tackle the
          pressing issue of food waste.
        </p>
      </div>
    </div>
    <div class="row justify-center q-gutter-lg" style="">
      <div class="col-11 col-md-5 col-lg-4">
        <img
          src="../../assets/images/spencer-davis-dxTBgnHZ8ZE-unsplash.jpg"
          style="
            object-fit: cover;
            width: 100%;
            height: 100%;
            border-radius: 24px;
          "
          alt=""
        />
      </div>

      <div
        class="col-11 col-md-5 col-lg-4 bg-blue q-pa-lg text-white text-justify"
        style="border-radius: 24px"
      >
        <h5
          class="q-my-md text-bold col-9 col-md-6"
          style="word-break: break-all; word-wrap: break-word"
        >
          Unlocking Potential: Empowering Businesses and Communities for
          Sustainable Food Waste Management
        </h5>
        <p style="font-size: large; line-height: 1.5" class="q-my-md">
          At Arzan Ghiza, we understand that food waste is not just a problem;
          it's an opportunity for positive change. Our state-of-the-art food
          waste management system offers a holistic approach that encompasses
          prevention, donation, and sustainable practices. Through our
          streamlined platform, businesses can effectively manage and reduce
          food waste at its source, optimizing operations and minimizing
          environmental impact. Meanwhile, our user-friendly interface empowers
          individuals and charities to easily connect and donate surplus food,
          ensuring that edible resources are redirected to those in need.
        </p>
      </div>
    </div>
    <div class="row justify-center q-gutter-lg flex-row-" style="">
      <div
        class="col-11 col-md-5 col-lg-4 bg-blue q-pa-lg text-white text-justify"
        style="border-radius: 24px"
      >
        <h5
          class="q-my-md text-bold"
          style="word-break: keep-all; word-wrap: break-word"
        >
          Join the Movement: Arzan Ghiza's Vision for a Zero Food Waste Society
        </h5>
        <p style="font-size: large; line-height: 1.5" class="q-my-md">
          At Arzan Ghiza, we understand that food waste is not just a problem;
          it's an opportunity for positive change. Our state-of-the-art food
          waste management system offers a holistic approach that encompasses
          prevention, donation, and sustainable practices. Through our
          streamlined platform, businesses can effectively manage and reduce
          food waste at its source, optimizing operations and minimizing
          environmental impact. Meanwhile, our user-friendly interface empowers
          individuals and charities to easily connect and donate surplus food,
          ensuring that edible resources are redirected to those in need.
        </p>
      </div>
      <div class="col-11 col-md-5 col-lg-4">
        <img
          src="../../assets/images/lily-banse--YHSwy6uqvk-unsplash.jpg"
          style="
            object-fit: cover;
            width: 100%;
            height: 100%;
            border-radius: 24px;
          "
          alt=""
        />
      </div>
    </div>
    <div
      class="col-12 col-md-8 bg-blue q-pa-lg text-white text-justify"
      style="border-radius: 24px"
    >
      <h5
        class="q-my-md text-bold"
        style="word-break: keep-all; word-wrap: break-word"
      >
        Arzan Ghiza: Driving Sustainability and Social Responsibility Together
      </h5>
      <p style="font-size: large; line-height: 1.5" class="q-my-md">
        Join us in the journey towards a sustainable future. By partnering with
        Arzan Ghiza, businesses can showcase their commitment to corporate
        social responsibility, enhance their brand image, and attract
        eco-conscious customers. Together, we can minimize food waste, alleviate
        hunger, and create a more sustainable and resilient world. Explore our
        services, be part of the food waste revolution, and join Arzan Ghiza in
        shaping a greener future for generations to come.
      </p>
    </div>
  </div>

    <Footer></Footer>
  </div>
</template>
<script setup>
import Footer from "src/layouts/footer.vue";
</script>
<style scoped>
.about-section-head {
  top: 0;
  left: 0;
  right: 0;
  background: url("../../assets/images/spencer-davis-dxTBgnHZ8ZE-unsplash.jpg");
  background-size: 100% 100%;
  object-fit: cover;
  background-blend-mode: overlay;
  background-color: rgba(0, 0, 0, 0.5);
  min-height: 100vh;
  position: relative;
}
about-section-head::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.3);
}
@media (min-width: 1000px) {
  .about-section-head {
    top: 0;
    left: 0;
    right: 0;
    background: url("../../assets/images/spencer-davis-dxTBgnHZ8ZE-unsplash.jpg");
    background-size: 100% 100%;
    object-fit: cover;
    background-blend-mode: overlay;
    background-color: rgba(0, 0, 0, 0.5);
    min-height: 100vh;
  }
}
</style>

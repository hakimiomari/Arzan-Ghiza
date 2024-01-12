<template>
  <div
    class="q-mt-xl q-pt-xl footer bg-blue-6"
    style="position: relative; bottom: 0 !important"
  >
    <p
      class="text-bold text-h4 text-center q-mx-auto text-grey-3"
      style="max-width: 600px"
    >
      Buy cheap, eat cheap
    </p>
    <p style="max-width: 800px" class="text-center q-mx-auto text-grey-3">
      Lorem ipsum dolor sit, amet consectetur adipisicing elit. Accusamus rem
      deleniti vero, labore debitis sint quaerat. Voluptas sunt natus enim!
    </p>

    <div class="row justify-center q-gutter-x-lg">
      <a
        href="mailto:rizwanmurad877@gmail.com"
        class="col-5 col-md-3 link q-pa-sm q-my-md"
        >Contact Us</a
      >
    </div>
    <div class="flex justify-center q-gutter-xl-lg">
      <div class="social">
        <q-icon name="facebook" color="white" size="30px" class="q-pa-md">
        </q-icon>
        <p>Facebook</p>
      </div>

      <div class="social">
        <q-icon name="X" color="white" size="30px" class="q-pa-md"> </q-icon>
        <p>Twitter</p>
      </div>
      <div class="social">
        <q-icon name="instagram" color="white" size="30px" class="q-pa-md">
        </q-icon>
        <p>Instagram</p>
      </div>
    </div>
    <div
      class="bg-blue-10 text-white text-center q-pt-md row justify-center q-gutter-x-xl"
      style="font-size: large"
    >
      <p>Location: Shahre-naw, Kabul, Afghanistan</p>
      <p>Established: 15, 10, 2023</p>
    </div>
  </div>
</template>
<script setup></script>

<style>
.link {
  text-decoration: none;
  text-transform: capitalize;
  text-align: center;
  font-size: large;
  color: white;
  border: 1px solid white;
}

.link:hover {
  color: rgb(13, 102, 236);
  background-color: white;
}
.social {
  color: white;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-right: 1rem;
  cursor: pointer;
}
.social > p {
  text-align: center;
}
</style>

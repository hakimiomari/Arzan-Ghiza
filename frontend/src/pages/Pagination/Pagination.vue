<script setup>
import { onUpdated } from "vue";

const props = defineProps({
  current_page: Number,
  last_page: Number,
});
</script>
<template>
  <div class="flex justify-center">
    <q-btn-group push>
      <q-btn
        push
        @click="$emit('getPrevPageData')"
        :class="{ active: current_page !== 1 }"
        :disabled="current_page === 1"
        >Prev</q-btn
      >
      <div v-for="page in last_page" :key="page">
        <q-btn
          push
          class="page-link"
          :class="{ active: current_page === page }"
          @click="$emit('getCurrentPageData', page)"
        >
          {{ page }}
        </q-btn>
      </div>
      <q-btn
        push
        @click="$emit('getNextPageData')"
        :class="{ active: current_page !== last_page }"
        :disabled="current_page === last_page"
        >Next</q-btn
      >
    </q-btn-group>
  </div>
</template>
<style scoped>
.page-link.active {
  background-color: rgb(30, 138, 238) !important;
  color: white;
}
</style>

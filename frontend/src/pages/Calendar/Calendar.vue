<template>
  <div class="q-pa-md calendar">
    <div class="details">
      <div class="header">
        <h4>Calendar</h4>
        <p>
          The Calendar gives you an overview of the days customers can come by
          your store and pick up a Surprise Bag. The days shown in the Calendar
          match your weekly schedule, but if there are temporary changes to your
          schedule, you can add these as Special Days here.
        </p>
      </div>
      <q-date v-model="date" today-btn />
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";

const date = ref("2019/02/01");

// Get the current date
const currentDate = new Date();

const year = currentDate.getFullYear();
const month = String(currentDate.getMonth() + 1).padStart(2, "0");
const day = String(currentDate.getDate()).padStart(2, "0");
const formattedDate = `${year}/${month}/${day}`;

// Update the ref with the current date
date.value = formattedDate;
</script>

<style scoped>
.details {
  width: 70%;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 2rem 0;
}
.header {
  padding: 1rem 0;
}
.header > p {
  padding-top: 1rem;
  font-size: 1.2rem;
}

.q-date__calendar-item > div {
  color: #000 !important;
}
.calendar {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-top: 2rem;
}
.q-date {
  width: 100%;
}
@media screen and (max-width: 700px) {
  .details {
    width: 100%;
    padding: 1rem;
  }
  .calendar {
    margin-top: 0;
  }
}
</style>

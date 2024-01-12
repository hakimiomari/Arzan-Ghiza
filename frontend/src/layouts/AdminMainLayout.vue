<template>
  <div>
    <q-layout view="hHh lpR fFf">
      <q-header
        elevated
        class="bg-primary text-white"
        height-hint="98"
        style="z-index: 200 !important"
      >
        <q-toolbar>
          <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

          <q-toolbar-title
            ><q-img
              src="../assets/logo3.png"
              style="width: 100px; height: 100%"
            ></q-img>
          </q-toolbar-title>
          <div
            class="flex items-center"
            style="cursor: pointer; margin-right: 1rem"
          >
            <q-icon
              name="person"
              size="2rem"
              style="margin-right: 0.5rem; cursor: pointer"
            />
            <span style="font-size: 1.2rem">{{ user?.name }}</span>
          </div>
        </q-toolbar>
      </q-header>

      <q-drawer show-if-above v-model="leftDrawerOpen" side="left" bordered>
        <div class="closeBtn">
          <q-btn
            flat
            @click="toggleLeftDrawer"
            class="material-icons close-btn"
            icon="close"
          ></q-btn>
        </div>
        <q-list>
          <router-link to="/admin" style="text-decoration: none">
            <q-item-label header>
              <q-btn
                flat
                class="material-icons list-icons"
                icon="dashboard"
              ></q-btn>
              <span>Dashboard</span>
            </q-item-label>
          </router-link>
          <router-link to="/add-post" style="text-decoration: none">
          </router-link>
          <router-link to="/admin-calendar" style="text-decoration: none">
            <q-item-label header
              ><q-btn
                flat
                class="material-icons list-icons"
                icon="calendar_today"
              ></q-btn>
              <span>Calendar</span>
            </q-item-label>
          </router-link>
          <!-- users -->
          <router-link to="/users" style="text-decoration: none">
            <q-item-label header
              ><q-btn
                flat
                class="material-icons list-icons"
                icon="groups"
              ></q-btn>
              <span>Users</span>
            </q-item-label>
          </router-link>
          <!-- bussiness -->
          <router-link to="/bussiness-users" style="text-decoration: none">
            <q-item-label header
              ><q-btn
                flat
                class="material-icons list-icons"
                icon="store"
              ></q-btn>
              <span>Bussiness</span>
            </q-item-label>
          </router-link>
          <!-- sales -->
          <router-link to="/admin/sales" style="text-decoration: none">
            <q-item-label header
              ><q-btn
                flat
                class="material-icons list-icons"
                icon="savings"
              ></q-btn>
              <span>Sales</span>
            </q-item-label>
          </router-link>
          <router-link to="/admin-profile" style="text-decoration: none">
            <q-item-label header
              ><q-btn
                flat
                class="material-icons list-icons"
                icon="person"
              ></q-btn>
              <span>Profile</span>
            </q-item-label>
          </router-link>
          <q-item-label @click="Logout" header
            ><q-btn flat class="material-icons list-icons" icon="input"></q-btn>
            <span>Sign Out</span>
          </q-item-label>
        </q-list>
      </q-drawer>
      <q-page-container>
        <router-view />
      </q-page-container>
    </q-layout>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();

const showProfile = ref(false);

const toggleProfile = () => {
  showProfile.value = !showProfile.value;
};

const leftDrawerOpen = ref(false);
const width = ref(null);

const toggleDrawer = (e) => {
  width.value = document.body.clientWidth;
  if (width.value <= 1006) {
    console.log(width.value);
    leftDrawerOpen.value = !leftDrawerOpen.value;
  }
};

// leftDrawer
const toggleLeftDrawer = () => {
  leftDrawerOpen.value = !leftDrawerOpen.value;
};

const user = ref("");

onMounted(() => {
  user.value = JSON.parse(localStorage.getItem("user"));
});

const Logout = async () => {
  try {
    await axios
      .get(`http://localhost:8000/api/logout/${user.value.id}`, {
        headers: { Authorization: `Bearer ${user.value.token}` },
      })
      .then((res) => {
        localStorage.clear();
        user.value = null;
        router.push("/");
      });
  } catch (err) {
    console.log(err);
  }
};
</script>

<style>
.q-drawer {
  position: fixed;
}
.closeBtn {
  display: none;
  justify-content: flex-end;
  padding: 0;
}
.close-btn:hover {
  color: red;
}

.q-item__label {
  display: flex;
  flex-direction: row;
  align-items: center;
  font-size: 1rem;
  color: #222;
  cursor: pointer;
  margin: 1rem;
  border-radius: 5px;
  padding: 0.5rem;
  transition: all 0.1s ease;
}
.q-item__label:hover {
  background: #f0eded;
}
.lists {
  margin-top: 2rem;
  padding-left: 1rem;
}
@media screen and (max-width: 1006px) {
  .closeBtn {
    display: flex;
  }
}
</style>

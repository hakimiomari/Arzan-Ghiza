import { defineStore } from "pinia";
import { api } from "src/boot/axios";

export const useUserStore = defineStore("user", {
  state: () => ({
    id: null,
    token: null,
    role_id: null,
  }),

  actions: {
    async setUserDetails(res) {
      this.$state.id = res.data.data.id;
      this.$state.token = res.data.data.token;
      this.$state.role_id = res.data.data.roles[0].id;
    },
    async fetchUser() {
      let res = await api.get(
        "http://127.0.0.1:8000/api/users/" + this.$state.id
      );
      this.$state.id = res.data.data.id;
      this.$state.token = res.data.data.token;
      this.$state.role_id = res.data.data.roles[0].id;
    },
    clearUser() {
      this.$state.id = null;
      this.$state.token = null;
      this.$state.role_id = null;
    },
  },
  persist: true,
});

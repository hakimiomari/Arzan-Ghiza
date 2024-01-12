import { store } from "quasar/wrappers";
import { createPinia } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

export default store((/* { ssrContext } */) => {
  const pinia = createPinia();
  pinia.use(piniaPluginPersistedstate);

  return pinia;
});

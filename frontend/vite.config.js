import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

import { envConfig } from "./config.js";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  define: {
    "import.meta.env.VUE_API_URL": JSON.stringify(envConfig.VUE_API_URL),
  },
});

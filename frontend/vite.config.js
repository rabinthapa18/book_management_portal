import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

let envConfig;
if (process.env.NODE_ENV === "dev") {
  envConfig = require("./config.dev");
} else if (process.env.NODE_ENV === "prod") {
  envConfig = require("./config.prod");
}

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  define: {
    "import.meta.env.VUE_API_URL": JSON.stringify(envConfig.VUE_API_URL),
  },
});

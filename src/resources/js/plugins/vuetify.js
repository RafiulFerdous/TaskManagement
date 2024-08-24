/**
 * Framework documentation: https://vuetifyjs.com`
 */

// Styles
import "@mdi/font/css/materialdesignicons.css";
import { md3 } from 'vuetify/blueprints'
import "vuetify/styles";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

// Composables
import { createVuetify } from "vuetify";
import { PurpleTheme } from "@/theme/LightTheme";

// https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides

export default createVuetify({
  // blueprint: md3,
  components,
  directives,
  theme: {
    defaultTheme: "PurpleTheme",
    themes: {
      PurpleTheme,
    },
  },
  defaults: {
    VBtn: {},
    VCard: {
      rounded: "md",
    },
    VTextField: {
      rounded: "lg",
    },
    VTooltip: {
      // set v-tooltip default location to top
      location: "top",
    }
  },
});

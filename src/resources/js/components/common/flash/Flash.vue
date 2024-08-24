<template>
  <transition name="slideup">
    <div class="text-center">
      <v-snackbar v-model="snackbar" multi-line>
        {{ text }}
        <template v-slot:actions>
          <v-btn color="red" variant="text" @click="snackbar = false">
            Close
          </v-btn>
        </template>
      </v-snackbar>
    </div>
  </transition>
</template>

<!-- <script>
  export default {
    data: () => ({
      snackbar: false,
      text: `I am a multi-line snackbar.\nI can have more than one line. This is another line that is quite long.`,
    }),
  }
</script> -->

<script>
export default {
  props: {
    message: String,

    level: {
      default: "success",
    },

    important: {
      default: false,
    },

    icon: {
      default: true,
    },
  },

  data() {
    return {
      body: this.message,
      levels: this.level,
      importants: this.important,
      snackbar: false,
    };
  },

  computed: {
    alertIcon() {
      switch (this.levels) {
        case "success":
          return "fa-check";
        case "warning":
          return "fa-exclamation-triangle";
        case "danger":
          return "fa-exclamation-circle";
        case "info":
          return "fa-info-circle";
        default:
          return "fa-check";
      }
    },
    isIconVisible() {
      return JSON.parse(this.icon);
    },
  },

  created() {
    if (this.message) {
      this.flash();
    }

    window.events.$on("flash", (data) => this.flash(data));
  },

  methods: {
    flash(data) {
      if (data) {
        this.body = data.message;
        this.levels = data.level;
        this.importants = data.important;
      }
      this.show = true;
      if (!this.importants) this.hide();
    },

    hide() {
      clearInterval(this.timerId);
      this.timerId = setTimeout(() => {
        this.show = false;
      }, 3000);
    },

    btnClose() {
      this.show = false;
    },
  },
};
</script>

<style scoped>
.alert-flash {
  position: fixed;
  right: 5px;
  top: 5px;
  z-index: 99999;
}
</style>

export default {
  data() {
    return {
      width: window.innerWidth
    };
  },
  computed: {
    isMobile: function() {
      return this.width <= 576;
    }
  },
  created() {
    window.addEventListener("resize", () => (this.width = window.innerWidth));
  },
  beforeDestroy() {
    window.removeEventListener(
      "resize",
      () => (this.width = window.innerWidth)
    );
  }
};

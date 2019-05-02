<template>
  <div class="row">
    <div class="col-md-6 col-sm-12 mrg-btm-15">
      <b-dropdown v-model="filter" class="full-width-dropdown">
        <button slot="trigger" class="button">
          <span>Filter ({{ filterTitle }})</span>
          <b-icon icon="caret-down"/>
        </button>

        <b-dropdown-item value="open">Open</b-dropdown-item>
        <b-dropdown-item value="closed">Closed</b-dropdown-item>
      </b-dropdown>
    </div>
    <div class="col-md-6 col-sm-12 mrg-btm-15">
      <div class="pull-right full-width-dropdown">
        <b-field>
          <b-input v-model="term"
                   placeholder="Search..."
                   type="search"
                   icon="search"/>
        </b-field>
      </div>
    </div>
  </div>
</template>

<script>
import { get, debounce, upperFirst, size } from "lodash";
export default {
  data() {
    return {
      filter: "open",
      term: "",
      columns: [{ key: "id", title: "ID" }]
    };
  },
  computed: {
    filterTitle: function() {
      return upperFirst(this.filter);
    }
  },
  watch: {
    filter: function() {
      this.onChange();
    },
    term: debounce(function() {
      this.onChange();
    }, 250)
  },
  methods: {
    onChange() {
      this.$emit("change", {
        term: this.term,
        filter: this.filter
      });
    }
  }
};
</script>

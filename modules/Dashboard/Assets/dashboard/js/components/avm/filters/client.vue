<template>
  <div class="row">
    <div class="col-md-6 col-sm-12 mrg-btm-15">
      <b-dropdown v-model="filter" class="full-width-dropdown">
        <button slot="trigger" class="button">
          <span>Filter ({{ filterTitle }})</span>
          <b-icon icon="caret-down"/>
        </button>

        <b-dropdown-item value="active">Active</b-dropdown-item>
        <b-dropdown-item value="all">All</b-dropdown-item>
        <b-dropdown-item value="completed">Completed</b-dropdown-item>
      </b-dropdown>
      <b-dropdown class="full-width-dropdown">
        <button slot="trigger" class="button">
          <span>Columns ({{ selectedColumns.length }})</span>
          <b-icon icon="caret-down"/>
        </button>
        <div v-for="column in columns" 
             :key="column.key" 
             class="field">
          <b-checkbox v-model="selectedColumns"
                      :native-value="column.key">
            {{ column.title }}
          </b-checkbox>
        </div>
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
  props: {
    visibleColumns: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      filter: "all",
      term: "",
      selectedColumns: this.visibleColumns,
      columns: [
        { key: "id", title: "ID" },
        { key: "address", title: "Address" },
        { key: "ordereddate", title: "Date Placed" },
        { key: "loanrefnum", title: "Loan Number" },
        { key: "product", title: "Product" },
        { key: "status", title: "Status" },
        { key: "borrower", title: "Borrwer" }
      ]
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
    }, 250),
    selectedColumns: function() {
      if (size(this.selectedColumns) <= 0) {
        return false;
      }
      this.onColumnsChange();
    }
  },
  methods: {
    onChange() {
      this.$emit("change", {
        term: this.term,
        filter: this.filter
      });
    },
    onColumnsChange() {
      this.$emit("columns", this.selectedColumns);
    }
  }
};
</script>

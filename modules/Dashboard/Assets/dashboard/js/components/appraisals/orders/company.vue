<template>
  <div class="row">
    <div class="col-md-12">
      <filters :visible-columns="visibleColumns" 
               @change="onFilersChange" 
               @columns="onColumnsChange" />
      <div class="row">
        <div class="col-md-12">
          <orders :data="tableData" 
                  :is-loading="isLoading" 
                  :current-page="currentPage" 
                  :per-page="perPage" 
                  :total="total" 
                  :to="to" 
                  :from="from" 
                  :visible-columns="visibleColumns"
                  @page-change="onPageChange"
                  @sort-change="onSortChange" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import http from "@/services/axios";
import Orders from "@@/components/appraisals/orders";
import Filters from "@/components/appraisals/filters/client";
export default {
  components: {
    Orders,
    Filters
  },
  data() {
    return {
      tableData: [],
      isLoading: true,
      currentPage: 1,
      perPage: this.$app.options.perPage || 50,
      total: 0,
      from: 0,
      to: 0,
      term: this.term,
      filter: "all",
      sort: ["ordereddate", "desc"],
      visibleColumns: [
        "id",
        "address",
        "ordereddate",
        "loanrefnum",
        "product",
        "status",
        "borrower"
      ]
    };
  },
  created() {
    this.fetchOrders();
  },
  methods: {
    fetchOrders() {
      this.isLoading = true;

      http
        .get("/appraisals/company", {
          params: {
            term: this.term,
            filter: this.filter,
            page: this.currentPage,
            perPage: this.perPage,
            sortField: this.sort[0],
            sortOrder: this.sort[1]
          }
        })
        .then(response => {
          this.tableData = response.data.data;
          this.total = response.data.total;
          this.from = response.data.from;
          this.to = response.data.to;
        })
        .catch(error => console.log(error))
        .then(() => {
          window.scrollTo(0, 0);
          this.isLoading = false;
        });
    },
    onSortChange(field, order) {
      this.sort = [field, order];
      this.triggerReload();
    },
    onPageChange(page) {
      this.currentPage = page;
      this.triggerReload();
    },
    onFilersChange({ term, filter }) {
      this.term = term;
      this.filter = filter;
      this.triggerReload();
    },
    onColumnsChange(columns) {
      this.visibleColumns = columns;
    },
    triggerReload() {
      this.fetchOrders();
    }
  }
};
</script>

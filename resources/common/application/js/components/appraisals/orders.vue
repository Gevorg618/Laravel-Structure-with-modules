<template>
  <b-table
    :data="data"
    :default-sort="sort"
    :striped="true"
    :narrowed="true"
    :hoverable="true"
    :loading="isLoading"
    :mobile-cards="true"
    :total="total"
    :per-page="perPage"
    :current-page="currentPage"
    paginated
    backend-sorting
    backend-pagination
    @sort="onSort"
    @page-change="onPageChange">

    <template slot-scope="props">

      <b-table-column 
        :visible="isVisible('id')"
        field="id" 
        label="ID" 
        width="40" 
        sortable>
        <span class="text-info">
          <a href="#">{{ props.row.id }}</a>
        </span>
      </b-table-column>

      <b-table-column
        :visible="isVisible('ordereddate')"
        field="ordereddate" 
        label="Date" 
        sortable>
        {{ placedDate(props.row) }}
      </b-table-column>

      <b-table-column
        :visible="isVisible('loanrefnum')"
        field="loanrefnum" 
        label="Loan #" 
        sortable>
        {{ props.row.loanrefnum }}
      </b-table-column>

      <b-table-column
        :visible="isVisible('address')"
        field="propaddress1" 
        label="Address"
        sortable>
        <span class="text-info">
          <a href="#">{{ props.row.address }}</a>
        </span>
      </b-table-column>

      <b-table-column
        :visible="isVisible('product')"
        field="appr_type" 
        label="Product" 
        sortable>
        {{ product(props.row) }}
      </b-table-column>

      <b-table-column
        :visible="isVisible('status')"
        field="status" 
        label="Status" 
        sortable>
        {{ status(props.row) }}
      </b-table-column>

      <b-table-column
        :visible="isVisible('paymentstatus')"
        field="payment" 
        label="Payment Status">
        {{ props.row.paymentstatus }}
      </b-table-column>

      <b-table-column
        :visible="isVisible('schd_date')"
        field="schd_date" 
        label="Inspection Date" 
        sortable>
        {{ scheduledDate(props.row) }}
      </b-table-column>

      <b-table-column 
        :visible="isVisible('borrower')"
        field="borrower" 
        label="Borrower" 
        sortable>
        {{ props.row.borrower }}
      </b-table-column>
    </template>

    <template slot="empty">
      <section class="section">
        <div class="content has-text-grey has-text-centered">
          <p>No Orders Found.</p>
        </div>
      </section>
    </template>
    <template slot="bottom-left">
      <span v-if="total" class="has-text-grey">
        Displaying {{ from }} - {{ to }} out of {{ formatTotal }} results.
      </span>
    </template>
  </b-table>
</template>

<script>
import { get, includes } from "lodash";
import moment from "moment";
import accounting from "accounting";
export default {
  props: {
    data: {
      type: [Array, Object],
      required: true
    },
    total: {
      type: [Number, String],
      default: 0
    },
    currentPage: {
      type: [Number, String],
      default: 0
    },
    from: {
      type: [Number, String],
      default: 0
    },
    to: {
      type: [Number, String],
      default: 0
    },
    perPage: {
      type: [Number, String],
      default: 50
    },
    sort: {
      type: Array,
      default: () => ["ordereddate", "desc"]
    },
    isLoading: {
      type: Boolean,
      default: false
    },
    visibleColumns: {
      type: Array,
      required: true
    }
  },
  data() {
    return {};
  },
  computed: {
    formatTotal: function() {
      return accounting.formatNumber(this.total);
    },
    formatFrom: function() {
      return accounting.formatNumber(this.from);
    },
    formatTo: function() {
      return accounting.formatNumber(this.to);
    }
  },
  methods: {
    status(row) {
      return get(row, "order_status.title", "--");
    },
    product(row) {
      return get(row, "shortapprtypename", "--");
    },
    placedDate(row) {
      let date = get(row, "ordereddate");
      if (date) {
        return moment(date).format("M/D/YYYY H:m A");
      }
      return "--";
    },
    scheduledDate(row) {
      let date = get(row, "schd_date");
      if (date) {
        return moment(date).format("M/D/YYYY H:m A");
      }
      return "--";
    },
    onSort(field, order) {
      this.$emit("sort-change", field, order);
    },
    onPageChange(page) {
      this.$emit("page-change", page);
    },
    isVisible(key) {
      return includes(this.visibleColumns, key);
    }
  }
};
</script>
